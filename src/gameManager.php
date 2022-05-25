<?php

namespace blackJack;

require_once(__DIR__ . '/config.php');

class GameManager
{
    //blackJackGameクラスで作成したインスタンスたち取り込んでおく
    public function __construct(private Cards $cards, private HandleCards $handler, private Player $player, private array $players, private Dealer $dealer, private CalculateHand $calculator)
    {
    }
    // ゲーム開始時のカード配布とカード表示
    public function start()
    {
        echo 'ブラックジャックを開始！' . PHP_EOL;

        // カードを混ぜる
        $this->handler->shuffleCards($this->cards);

        //プレイヤーとディーラーにカードを２枚ずつ配布
        for ($i = 0; $i < 2; $i++) {
            foreach ($this->players as $player) {
                $this->handler->dealCard($player, $this->cards);
            }
            $this->handler->dealCard($this->dealer, $this->cards);
        }
        //プレイヤーとディーラーのカードを表示 (ディーラーは一枚だけ表示)
        foreach ($this->players as $player) {
            echo $this->handler->showHand($player, count($player->getMyHand())) . PHP_EOL;
            // プレイヤーがブラックジャックだった時の処理
            if ($this->calculator->calculate($player->getMyHand()) === RESULT['blackJack']) {
                $player->changeActionState(RESULT['blackJack']);
                $player->changeResult(RESULT['blackJack']);
                echo RESULT['blackJack'];
                RunWithEnter();
            }
        }
        // ディーラーのカード表示
        echo $this->handler->showHand($this->dealer, 1);
        RunWithEnter();
        //プレイヤーにブラックジャックが出ているかつディーラーの一枚目のカードがAだったとき、インシュランスの選択 とブラックジャックかの確認
        if ($this->calculator->convertCardToNumber($this->dealer->getMyHand()[0]) === CARD_NUMBERS['A']) {
            // プレイヤーはインシュランスかイーブンの選択しが生まれる
            foreach ($this->players as $player) {
                if ($player->getResult() === RESULT['blackJack']) {
                    $selectedAction = $player->selectEven();
                    $this->takeSelectedAction($player, $selectedAction);
                }
                if ($player->getResult() !== RESULT['blackJack']) {
                    $selectedAction = $player->selectInsurance();
                    $this->takeSelectedAction($player, $selectedAction);
                }
            }
            // ディーラーが二枚目のカード確認
            //ディーラの二枚目でブラックジャックだった場合
            if ($this->calculator->calculate($this->dealer->getMyHand()) === RESULT['blackJack']) {
                $this->dealer->changeResult(RESULT['blackJack']);
                echo 'ディーラーの' . RESULT['blackJack'] . $this->handler->showHand($this->dealer, count($this->dealer->getMyHand())) . PHP_EOL;
                $this->gameSet();
                exit;
            }
            //ディーラの二枚目でブラックジャックじゃなかった場合
            if ($this->calculator->calculate($this->dealer->getMyHand()) !== RESULT['blackJack']) {
                echo 'ディーラーは No BlackJack';
                RunWithEnter();
                return;
            }
        }

        //ディーラーの一枚目のカードが10pointだったとき、ブラックジャックかどうか
        if ($this->calculator->convertCardToNumber($this->dealer->getMyHand()[0]) === CARD_NUMBERS['10']) {
            if ($this->calculator->calculate($this->dealer->getMyHand()) === RESULT['blackJack']) {
                echo 'ディーラーの' . RESULT['blackJack'] . $this->handler->showHand($this->dealer, count($this->dealer->getMyHand())) . PHP_EOL;
                $this->gameSet();
                exit;
            }
            if ($this->calculator->calculate($this->dealer->getMyHand()) !== RESULT['blackJack']) {
                echo 'ディーラーは No BlackJack';
                RunWithEnter();
                return;
            }
        }
    }

    public function takeSelectedAction(AbstractPlayer $who, $selectedAction)
    {
        if ($selectedAction === ACTIONS['hit']) {
            $this->selectedHit($who);
        }
        if ($selectedAction === ACTIONS['stand']) {
            $this->selectedStand($who);
        }
        if ($selectedAction === ACTIONS['surrender']) {
            $this->selectedSurrender($who);
        }
        if ($selectedAction === ACTIONS['double']) {
            $this->selectedDouble($who);
        }
        if ($selectedAction === ACTIONS['split']) {
            $this->selectedSplit($who);
        }
        if ($selectedAction === ACTIONS['insurance']) {
            $this->selectedInsurance($who);
        }
        if ($selectedAction === ACTIONS['noInsurance']) {
            echo $who->getName() . 'はinsuranceしません';
            RunWithEnter();
            return;
        }
        if ($selectedAction === ACTIONS['even']) {
            $this->selectedEven($who);
        }
        if ($selectedAction === ACTIONS['noEven']) {
            echo $who->getName() . 'はevenしません';
            RunWithEnter();
            return;
        }
    }

    // stayが選択された時の
    public function selectedStand(AbstractPlayer $who)
    {
        $who->changeActionState(STATE['stand']);
        $who->changeResult($this->calculator->calculate($who->getMyHand()));
        echo $who->getName() . 'は' . ACTIONS['stand'] . 'を選択しました';
        RunWithEnter();
    }

    // Hitが選択された時
    // バーストした時の挙動を分離する
    public function selectedHit(AbstractPlayer $who)
    {
        $who->changeActionState(STATE['hit']);
        // playerにカードを一枚配る
        echo $who->getName() . 'は' . ACTIONS['hit'] . 'を選択しました';
        RunWithEnter();
        $this->handler->dealCard($who, $this->cards);
        echo $this->handler->showHand($who, count($who->getMyHand()));
        RunWithEnter();
        //一枚引いた結果burstしたら終了
        if ($this->calculator->calculate($who->getMyHand()) === RESULT['burst']) {
            $who->changeActionState(STATE['stand']);
            $who->changeResult(RESULT['burst']);
            echo  RESULT['burst'] . 'しました' . PHP_EOL;
        }
    }

    // ダブルが選択された時
    public function selectedDouble(AbstractPlayer $who)
    {
        $who->changeActionState(STATE['double']);
        // playerにカードを一枚配る
        echo $who->getName() . 'は' . ACTIONS['double'] . 'を選択しました';
        RunWithEnter();
        $this->handler->dealCard($who, $this->cards);
        echo $this->handler->showHand($who, count($who->getMyHand()));
        RunWithEnter();
        //一枚引いた結果burstしたら通知
        if ($this->calculator->calculate($who->getMyHand()) === RESULT['burst']) {
            echo  RESULT['burst'] . 'しました' . PHP_EOL;
            $who->changeResult(RESULT['burst']);
            return;
        }
        // burstでなくても一枚引いたら終了
        echo $who->getName() . 'の得点は' . $this->calculator->calculate($who->getMyHand()) . PHP_EOL;
        return;
    }

    // サレンダーが選択された時
    public function selectedSurrender(AbstractPlayer $who)
    {
        $who->changeActionState(ACTIONS['surrender']);
        $who->changeResult(RESULT['surrender']);
        echo $who->getName() . 'は' . ACTIONS['surrender'] . 'を選択しました';
        RunWithEnter();
        return;
    }
    // スプリットが選択された時
    public function selectedSplit(AbstractPlayer $who)
    {
        $who->changeActionState(STATE['split']);
        echo $who->getName() . 'は' . STATE['split'] . 'を選択しました';
        RunWithEnter();
        $whoClass = $who::class;
        $player1 = new $whoClass($who->getName() . '1');
        $player2 = new $whoClass($who->getName() . '2');

        foreach ([$player1, $player2] as $player) {
            $player->changeMyHand([$who->getMyHand()[array_search($player, [$player1, $player2])]]);
            $this->handler->dealCard($player, $this->cards);
            echo $this->handler->showHand($player, count($player->getMyHand())) . PHP_EOL;
            RunWithEnter();
        }
        foreach ([$player1, $player2] as $player) {
            while ($player->getActionState()===STATE['hit']) {
                $selectedAction = $player->selectAction();
                $this->takeSelectedAction($player, $selectedAction);
            }
        }
        array_splice($this->players, array_search($who, $this->players), 1, [$player1, $player2]);
        return;
    }
    // インシュランスが選択された時
    public function selectedInsurance(AbstractPlayer $who)
    {
        $who->changeActionState(ACTIONS['insurance']);
        echo $who->getName() . 'は' . ACTIONS['insurance'] . 'を選択しました';
        RunWithEnter();
    }

    // イーブンが選択された時
    public function selectedEven(AbstractPlayer $who)
    {
        $who->changeActionState(ACTIONS['even']);
        echo $who->getName() . 'は' . ACTIONS['even'] . 'を選択しました';
        RunWithEnter();
    }

    // Stayの時の挙動
    //デーラーのターン　ディーラのカードオープン
    public function DealerHit()
    {
        echo 'dealerの手札オープン';
        RunWithEnter();
        echo $this->handler->showHand($this->dealer, count($this->dealer->getMyHand())) . '得点' . $this->calculator->calculate($this->dealer->getMyHand());
        RunWithEnter();
        //ディーラーの得点が17以上になるまでカードを引く
        while ($this->dealer->getActionState() === STATE['hit']) {
            $selectedAction = $this->dealer->selectAction();
            $this->takeSelectedAction($this->dealer, $selectedAction);
        }
    }


    // 試合結果出力
    public function gameSet()
    {
        $results = [];
        foreach ($this->players as $player) {
            $results[$player->getName()] = $this->JudgementResult($player);
        }

        // foreach ($results as $name => $result) {
        //     echo $name . ':' . $result . PHP_EOL;
        // }

        foreach ($this->players as $player) {
            echo $player->getName(). ':' .$player->getResult(). PHP_EOL;
        }

    }

    public function JudgementResult(AbstractPlayer $player)
    {
        if ($player->getResult() === RESULT['blackJack'] && $this->dealer->getResult() === RESULT['blackJack']) {
            $player->changeResult(RESULT['push']);
            return;
        }
        if ($player->getResult() !== RESULT['blackJack'] && $this->dealer->getResult() === RESULT['blackJack']) {
            $player->changeResult(RESULT['lose']);
            return;
        }
        if ($player->getResult() === RESULT['blackJack'] && $this->dealer->getResult() !== RESULT['blackJack']) {
            $player->changeResult(RESULT['blackJack']);
            return;
        }
        if ($player->getResult() === RESULT['surrender']) {
            $player->changeResult(RESULT['surrender']);
            return;
        }
        if ($player->getResult() === RESULT['burst']) {
            $player->changeResult(RESULT['burst']);
            return;
        }
        if ($this->dealer->getResult(RESULT['burst']) === RESULT['burst']) {
            $player->changeResult(RESULT['dealer burst']);
            return;
        }

        $PlayerHandNumber = $this->calculator->calculate($player->getMyHand());
        $DealerHandNumber = $this->calculator->calculate($this->dealer->getMyHand());

        if($PlayerHandNumber > $DealerHandNumber && $player->getActionState() === STATE['double']){
            $player->changeResult(RESULT['double']);
            return;
        }
        if($PlayerHandNumber > $DealerHandNumber){
            $player->changeResult(RESULT['win']);
            return;
        }
        if($PlayerHandNumber < $DealerHandNumber){
            $player->changeResult(RESULT['lose']);
            return;
        }
        return RESULT['push'];
    }
}
