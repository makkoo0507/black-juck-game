<?php

namespace blackJack;

class Rule
{
    //BlackJackGameクラス,GameManagerクラスで作成したインスタンスたち取り込んでおく
    public function __construct(private PlayingCards $cards,private Dealer $dealer)
    {
    }

    // インシュランスが選択された時
    public function selectedInsurance(Player $who)
    {
        echo $who->getName() . 'はInsuranceを選択しました';
        RunWithEnter();
    }

    // イーブンが選択された時
    public function selectedEven(Player $who)
    {
        echo $who->getName() . 'はEvenを選択しました';
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
