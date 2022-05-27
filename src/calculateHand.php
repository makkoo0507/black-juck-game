<?php

namespace blackJack;

require_once(__DIR__ . '/config.php');

class CalculateHand
{
    public function isSameNumberCards(array $hand)
    {
        if (count($hand) > 2) {
            return FALSE;
        }
        $handNumbers = [];
        foreach ($hand as $card) {
            $handNumbers[] = $card->getCardNumbers();
        }
        if ($handNumbers[0] === $handNumbers[1]) {
            return TRUE;
        }
        return FALSE;
    }

    public function calculate(array $hand): mixed
    {
        $sum = $this->addCards($hand);
        if (count($hand) === 2 && $sum === LIMIT_NUMBER) {
            return RESULT['blackJack'];
        }
        if ($sum > LIMIT_NUMBER) {
            return RESULT['burst'];
        }
        return $sum;
    }

    public function addCards(array $hand): int
    {
        $sum = 0;
        foreach ($hand as $card) {
            if ($card->getCardNumbers() !== CARD_NUMBERS['A']) {
                $sum += $card->getCardNumbers()[0];
            }
        }
        if ($this->numberOfA($hand)) {
            $sum1 = $sum + 10 + $this->numberOfA($hand);
            $sum2 = $sum + $this->numberOfA($hand);
            if ($sum1 > 21) {
                return $sum2;
            }
            return $sum1;
        }
        return $sum;
    }

    public function numberOfA(array $hand)
    {
        $count = 0;
        foreach ($hand as $card) {
            if ($card->getCardNumbers() === CARD_NUMBERS['A']) {
                $count++;
            }
        }
        return $count;
    }

    public function JudgementResult(Dealer $dealer, AbstractPlayer $player)
    {
        $this->JudgementInDealerBlackJack($dealer, $player);
        $this->JudgementInBurstState($dealer, $player);
        $this->JudgementInSurrenderState($dealer, $player);
        $this->JudgementInDoubleState($dealer, $player);
        $this->JudgementInBlackJack($dealer, $player);
        $this->JudgementInStandState($dealer, $player);
    }
    public function JudgementInDealerBlackJack(Dealer $dealer, AbstractPlayer $player)
    {
        if ($dealer->getActionState() !== STATE['blackJack']) {
            return;
        }
        if($player->getActionState() === STATE['blackJack']){
            $player->changeResult(RESULT['push']);
        }else{
            $player->changeResult(RESULT['lose']);
        }
        return;
    }
    public function JudgementInBurstState(Dealer $dealer, AbstractPlayer $player)
    {
        if ($player->getActionState() !== STATE['burst']) {
            return;
        }
        $player->changeResult(RESULT['burst']);
        return;
    }
    public function JudgementInSurrenderState(Dealer $dealer, AbstractPlayer $player)
    {
        if ($player->getActionState() !== STATE['surrender']) {
            return;
        }
        $player->changeResult(RESULT['surrender']);
        return;
    }
    public function JudgementInDoubleState(Dealer $dealer, AbstractPlayer $player)
    {
        $PlayerHandNumber = $this->calculate($player->getMyHand());
        $DealerHandNumber = $this->calculate($dealer->getMyHand());
        if ($player->getActionState() !== STATE['double']) {
            return;
        }
        if ($dealer->getResult() === RESULT['burst']) {
            $player->changeResult(RESULT['double']);
            return;
        }
        if ($PlayerHandNumber > $DealerHandNumber) {
            $player->changeResult(RESULT['double']);
            return;
        }
        if ($PlayerHandNumber < $DealerHandNumber) {
            $player->changeResult(RESULT['lose']);
            return;
        }
    }
    public function JudgementInBlackJack(Dealer $dealer, AbstractPlayer $player)
    {
        if ($player->getActionState() !== STATE['blackJack']) {
            return;
        }
        if ($dealer->getActionState() === STATE['blackJack']) {
            $player->changeResult(RESULT['push']);
            return;
        }
        if ($dealer->getActionState() !== STATE['blackJack']) {
            $player->changeResult(RESULT['blackJack']);
            return;
        }
    }
    public function JudgementInStandState(Dealer $dealer, AbstractPlayer $player)
    {
        if ($player->getActionState() !== STATE['stand']) {
            return;
        }
        $PlayerHandNumber = $this->calculate($player->getMyHand());
        $DealerHandNumber = $this->calculate($dealer->getMyHand());
        if ($dealer->getActionState() === STATE['burst']) {
            $player->changeResult(RESULT['win']);
            return;
        }
        if ($PlayerHandNumber > $DealerHandNumber) {
            $player->changeResult(RESULT['win']);
            return;
        }
        if ($PlayerHandNumber < $DealerHandNumber) {
            $player->changeResult(RESULT['lose']);
            return;
        }
        if ($PlayerHandNumber === $DealerHandNumber) {
            $player->changeResult(RESULT['push']);
            return;
        }
    }
    // ディーラーがブラックジャックの時の勝敗
}
