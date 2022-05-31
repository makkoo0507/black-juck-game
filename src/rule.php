<?php

namespace blackJack;

class Rule
{
    //BlackJackGameクラス,GameManagerクラスで作成したインスタンスたち取り込んでおく
    public function __construct(private PlayingCards $cards,private Dealer $dealer)
    {
    }

    // standが選択された時の
    public function selectedStand(AbstractPlayer $who)
    {
        $who->changeActionState(Config::STATE['stand']);
        echo $who->getName() . 'は' . Config::ACTIONS['stand'] . 'を選択しました';
        RunWithEnter();
    }
    // Hitが選択された時
    public function selectedHit(AbstractPlayer $who)
    {
        $who->changeActionState(Config::STATE['hit']);
        // playerにカードを一枚配る
        echo $who->getName() . 'は' . Config::ACTIONS['hit'] . 'を選択しました';
        RunWithEnter();
        $who->drawCardFromDeck($this->cards);
        echo $who->showHand($who->getHand()->getCardsNumber());
        RunWithEnter();
        //一枚引いた結果burstしたら終了
        if ($who->getHand()->evaluateHand() === Config::RESULT['burst']) {
            $who->changeActionState(Config::STATE['burst']);
            echo  Config::RESULT['burst'] . 'しました' . PHP_EOL;
        }
    }

    // ダブルが選択された時
    public function selectedDouble(Player $who)
    {
        $who->changeActionState(Config::STATE['double']);
        // playerにカードを一枚配る
        echo $who->getName() . 'は' . Config::ACTIONS['double'] . 'を選択しました';
        RunWithEnter();
        $who->drawCardFromDeck($this->cards);
        echo $who->showHand($who->getHand()->getCardsNumber());
        RunWithEnter();
        //一枚引いた結果burstしたら通知
        if ($who->getHand()->evaluateHand() === Config::RESULT['burst']) {
            echo  Config::RESULT['burst'] . 'しました' . PHP_EOL;
            $who->changeActionState(Config::STATE['burst']);
            return;
        }
        // burstでなくても一枚引いたら終了
        echo $who->getName() . 'の得点は' . $who->getHand()->evaluateHand() . PHP_EOL;
        return;
    }

    // サレンダーが選択された時
    public function selectedSurrender(Player $who)
    {
        $who->changeActionState(Config::STATE['surrender']);
        echo $who->getName() . 'は' . Config::ACTIONS['surrender'] . 'を選択しました';
        RunWithEnter();
        return;
    }

    // スプリットが選択された時
    public function selectedSplit(Player $who)
    {
        $who->changeActionState(Config::STATE['split']);
        echo $who->getName() . 'は' . Config::STATE['split'] . 'を選択しました';
        RunWithEnter();
        $whoClass = $who::class;
        $player1 = new $whoClass($who->getName() . '1');
        $player2 = new $whoClass($who->getName() . '2');

        foreach ([$player1, $player2] as $player) {
            $player->getHand()->addCardToHand($who->getHand()->getCardInHand(array_search($player, [$player1, $player2])));
            $player->drawCardFromDeck($this->cards);
            echo $player->showHand($player->getHand()->getCardsNumber()) . PHP_EOL;
            RunWithEnter();
        }
        return [$player1,$player2];
    }
    // インシュランスが選択された時
    public function selectedInsurance(Player $who)
    {
        echo $who->getName() . 'は' . Config::ACTIONS['insurance'] . 'を選択しました';
        RunWithEnter();
    }
    // 新たな変数でイーブンかインシュランスを判断する

    // イーブンが選択された時
    public function selectedEven(Player $who)
    {
        echo $who->getName() . 'は' . Config::ACTIONS['even'] . 'を選択しました';
        RunWithEnter();
    }


    public function JudgementResult(Dealer $dealer, Player $player)
    {
        $this->JudgementInDealerBlackJack($dealer, $player);
        $this->JudgementInBurstState($dealer, $player);
        $this->JudgementInSurrenderState($dealer, $player);
        $this->JudgementInDoubleState($dealer, $player);
        $this->JudgementInBlackJack($dealer, $player);
        $this->JudgementInStandState($dealer, $player);
    }

    public function JudgementInDealerBlackJack(Dealer $dealer, Player $player)
    {
        if ($dealer->getActionState() !== Config::STATE['blackJack']) {
            return;
        }
        if($player->getActionState() === Config::STATE['blackJack']){
            $player->changeResult(Config::RESULT['push']);
        }else{
            $player->changeResult(Config::RESULT['lose']);
        }
        return;
    }
    public function JudgementInBurstState(Dealer $dealer, Player $player)
    {
        if ($player->getActionState() !== Config::STATE['burst']) {
            return;
        }
        $player->changeResult(Config::RESULT['burst']);
        return;
    }
    public function JudgementInSurrenderState(Dealer $dealer, Player $player)
    {
        if ($player->getActionState() !== Config::STATE['surrender']) {
            return;
        }
        $player->changeResult(Config::RESULT['surrender']);
        return;
    }
    public function JudgementInDoubleState(Dealer $dealer, Player $player)
    {
        $PlayerHandNumber = $player->getHand()->evaluateHand();
        $DealerHandNumber = $dealer->getHand()->evaluateHand();
        if ($player->getActionState() !== Config::STATE['double']) {
            return;
        }
        if ($dealer->getResult() === Config::RESULT['burst']) {
            $player->changeResult(Config::RESULT['double']);
            return;
        }
        if ($PlayerHandNumber > $DealerHandNumber) {
            $player->changeResult(Config::RESULT['double']);
            return;
        }
        if ($PlayerHandNumber < $DealerHandNumber) {
            $player->changeResult(Config::RESULT['lose']);
            return;
        }
    }
    public function JudgementInBlackJack(Dealer $dealer, Player $player)
    {
        if ($player->getActionState() !== Config::STATE['blackJack']) {
            return;
        }
        if ($dealer->getActionState() === Config::STATE['blackJack']) {
            $player->changeResult(Config::RESULT['push']);
            return;
        }
        if ($dealer->getActionState() !== Config::STATE['blackJack']) {
            $player->changeResult(Config::RESULT['blackJack']);
            return;
        }
    }
    public function JudgementInStandState(Dealer $dealer, Player $player)
    {
        if ($player->getActionState() !== Config::STATE['stand']) {
            return;
        }
        $PlayerHandNumber = $player->getHand()->evaluateHand();
        $DealerHandNumber = $dealer->getHand()->evaluateHand();
        if ($dealer->getActionState() === Config::STATE['burst']) {
            $player->changeResult(Config::RESULT['win']);
            return;
        }
        if ($PlayerHandNumber > $DealerHandNumber) {
            $player->changeResult(Config::RESULT['win']);
            return;
        }
        if ($PlayerHandNumber < $DealerHandNumber) {
            $player->changeResult(Config::RESULT['lose']);
            return;
        }
        if ($PlayerHandNumber === $DealerHandNumber) {
            $player->changeResult(Config::RESULT['push']);
            return;
        }
    }



}
