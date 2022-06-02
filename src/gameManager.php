<?php

namespace blackJack;

require_once(__DIR__ . '/config.php');

class GameManager
{
    private Rule $rule;
    //blackJackGameクラスで作成したインスタンスたち取り込んでおく
    public function __construct(private PlayingCards $cards, private Player $player, private array $players, private Dealer $dealer)
    {
        $this->rule = new Rule($cards, $dealer);
    }
    // ゲーム開始時のカード配布とカード表示
    public function start()
    {
        echo 'ブラックジャックを開始！' . PHP_EOL;
        // カードを混ぜる
        $this->cards->shuffleCards();

        //プレイヤーとディーラーにカードを２枚ずつ配布
        for ($i = 0; $i < 2; $i++) {
            foreach ($this->players as $player) {
                $player->drawCardFromDeck($this->cards);
            }
            $this->dealer->drawCardFromDeck($this->cards);
        }
    }

    // プレイヤーは二枚、ディーラは一枚だけカードをオープン
    public function openCards()
    {
        //プレイヤーとディーラーのカードを表示 (ディーラーは一枚だけ表示)
        foreach ($this->players as $player) {
            echo $player->showHand($player->getHand()->getCardsNumber()) . PHP_EOL;
            // プレイヤーがブラックジャックだった時の動作
            $this->takePlayerBlackJack($player);
        }
        // ディーラーのカード表示
        echo $this->dealer->showHand(1);
        RunWithEnter();
    }

    //ディーラーの一枚目のカードがAだったとき、インシュランスやイーブンを選択可能
    public function dealerOpenCardIsA()
    {
        // 一枚目がAでなければ、特に何もしない
        if (!$this->dealer->existFistCard('A')) {
            return;
        }
        // プレイヤーはインシュランスかイーブンの選択しが生まれる
        foreach ($this->players as $player) {
            if ($player->getActionState() === Config::STATE['blackJack']) {
                $selectedAction = $player->selectEven();
                $this->takeSelectedInsurances($player, $selectedAction);
            }
            if ($player->getActionState() !== Config::STATE['blackJack']) {
                $selectedAction = $player->selectInsurance();
                $this->takeSelectedInsurances($player, $selectedAction);
            }
        }
    }

    //ディーラーの手札がブラックジャックか確認
    public function isDealerCardTotal21()
    {
        // プレイヤーのオープンしているカードがAor10ポイントでなければ、特に何もしない
        if (!$this->dealer->existFistCard('A') && !$this->dealer->existFistCard('10')) {
            return;
        }
        if ($this->dealer->getHand()->EvaluateHand() === Config::RESULT['blackJack']) {
            $this->dealer->changeActionState(Config::STATE['blackJack']);
            echo 'ディーラーの' . Config::RESULT['blackJack'] . $this->dealer->showHand($this->dealer->getHand()->getCardsNumber()) . PHP_EOL;
            RunWithEnter();
            $this->gameSet();
            exit;
        }
        //ディーラの二枚目でブラックジャックじゃなかった場合
        if ($this->dealer->getHand()->EvaluateHand() !== Config::RESULT['blackJack']) {
            echo 'ディーラーは No BlackJack';
            RunWithEnter();
            return;
        }
    }

    // プレイヤーがアクションを選択してカードをドローしていく
    public function PlayerDraw()
    {
        foreach ($this->players as $player) {
            while ($this->stateToContinue($player)) {
                $selectedAction = $player->selectAction();
                $this->takeSelectedAction($player, $selectedAction);
            }
        }
    }

    // プレイヤーが次のアクションを選択できるか、アクションが終了かSTATEの判定
    public function stateToContinue(Player $player):bool
    {
        $className = Config::STATE_CLASSES[$player->getActionState()];
        $stateClass = new $className();
        return $stateClass->needsSelectAction();
    }

    // プレイヤーがブラックジャックだった時の処理
    public function takePlayerBlackJack(Player $player){
        if ($player->getHand()->EvaluateHand() === Config::RESULT['blackJack']) {
            $player->changeActionState(Config::STATE['blackJack']);
            echo Config::RESULT['blackJack'];
            RunWithEnter();
        }
    }

    // プレイヤーのアクション選択に応じての処理
    public function takeSelectedAction(AbstractPlayer $who, $selectedAction)
    {
        // $splitPlayers = FALSE;
        if(array_key_exists($selectedAction, Config::STATE_CLASSES)){
            $className = Config::STATE_CLASSES[$selectedAction];
            $stateClass = new $className();
            $splitPlayers = $stateClass->action($who,$this->cards);
        }
        if ($splitPlayers) {
            array_splice($this->players, array_search($who, $this->players), 1, $splitPlayers);
            $this->PlayerDraw();
        }
    }
    // インシュランス選択に応じての処理
    public function takeSelectedInsurances(AbstractPlayer $who, $selectedAction)
    {
        if ($selectedAction === Config::INSURANCES_CHOICES['insurance']) {
            $this->rule->selectedInsurance($who);
        }
        if ($selectedAction === Config::INSURANCES_CHOICES['noInsurance']) {
            echo $who->getName() . 'はinsuranceしません';
            RunWithEnter();
        }
        if ($selectedAction === Config::INSURANCES_CHOICES['even']) {
            $this->rule->selectedEven($who);
        }
        if ($selectedAction === Config::INSURANCES_CHOICES['noEven']) {
            echo $who->getName() . 'はevenしません';
            RunWithEnter();
        }
    }


    //デーラーのターン　ディーラのカードオープン
    public function DealerDraw()
    {
        echo 'dealerの手札オープン';
        RunWithEnter();
        echo $this->dealer->showHand($this->dealer->getHand()->getCardsNumber()) . '得点' . $this->dealer->getHand()->evaluateHand();
        RunWithEnter();

        $isAllPlayersBlackJack=TRUE;
        foreach($this->players as $player){
            if($player->getActionState()!==Config::STATE['blackJack']){
                $isAllPlayersBlackJack=FALSE;
            }
        }
        if($isAllPlayersBlackJack){
            $this->takeSelectedAction($this->dealer, Config::STATE['stand']);
            return;
        }
        //ディーラーの得点が17以上になるまでカードを引く
        while ($this->dealer->getActionState() === Config::STATE['hit']) {
            $selectedAction = $this->dealer->selectAction();
            $this->takeSelectedAction($this->dealer, $selectedAction);
        }
    }


    // 試合結果出力
    public function gameSet()
    {
        foreach ($this->players as $player) {
            $this->rule->JudgementResult($this->dealer, $player);
        }

        foreach ($this->players as $player) {
            echo $player->getName() . ':' . $player->getResult() . PHP_EOL;
        }
    }
}
