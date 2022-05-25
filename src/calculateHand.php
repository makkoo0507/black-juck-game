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
            $handNumbers[] = $this->convertCardToNumber($card);
        }
        if ($handNumbers[0] === $handNumbers[1]) {
            return TRUE;
        }
        return FALSE;
    }

    public function calculate(array $hand): mixed
    {
        $handNumbers = [];
        foreach ($hand as $card) {
            $handNumbers[] = $this->convertCardToNumber($card);
        }
        $sum = $this->addCards($handNumbers);
        if(count($hand) === 2 && $sum === LIMIT_NUMBER){
            return RESULT['blackJack'];
        }
        if ($sum > LIMIT_NUMBER) {
            return RESULT['burst'];
        }
        return $sum;
    }

    public function convertCardToNumber(string $card): array
    {
        $cardNumber = CARD_NUMBERS[substr($card, 1)];
        return $cardNumber;
    }

    public function addCards(array $hand): int
    {
        $sum0 = 0;
        $sum1 = 0;
        foreach ($hand as $card) {
            $sum0 += $card[0];
            $sum1 += $card[1];
        }
        if ($sum1 > 21) {
            $sum = $sum0;
        } else {
            $sum = $sum1;
        }
        return $sum;
    }

    // public function judgeWinner(array $playerHand,array $dealerHand){}
}
