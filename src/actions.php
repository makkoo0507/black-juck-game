<?php

namespace blackJack;

class Action
{
    //blackJackGameクラスで作成したインスタンスたち取り込んでおく
    public function __construct(private PlayingCards $cards, private HandleCards $handler,private Dealer $dealer, private CalculateHand $calculator)
    {
    }

    // standが選択された時の
    public function selectedStand(AbstractPlayer $who)
    {
        $who->changeActionState(STATE['stand']);
        echo $who->getName() . 'は' . ACTIONS['stand'] . 'を選択しました';
        RunWithEnter();
    }
    // Hitが選択された時
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
            $who->changeActionState(STATE['burst']);
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
            $who->changeActionState(STATE['burst']);
            return;
        }
        // burstでなくても一枚引いたら終了
        echo $who->getName() . 'の得点は' . $this->calculator->calculate($who->getMyHand()) . PHP_EOL;
        return;
    }

    // サレンダーが選択された時
    public function selectedSurrender(AbstractPlayer $who)
    {
        $who->changeActionState(STATE['surrender']);
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
        return [$player1,$player2];
    }
    // インシュランスが選択された時
    public function selectedInsurance(AbstractPlayer $who)
    {
        echo $who->getName() . 'は' . ACTIONS['insurance'] . 'を選択しました';
        RunWithEnter();
    }
    // 新たな変数でイーブンかインシュランスを判断する

    // イーブンが選択された時
    public function selectedEven(AbstractPlayer $who)
    {
        echo $who->getName() . 'は' . ACTIONS['even'] . 'を選択しました';
        RunWithEnter();
    }
}
