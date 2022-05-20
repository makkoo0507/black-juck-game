<?php
namespace blackJack;

class CalculateHand
{
    const CARD_NUMBERS=[
        'A'=>[1,11],
        '2'=>[2,2],
        '3'=>[3,3],
        '4'=>[4,4],
        '5'=>[5,5],
        '6'=>[6,6],
        '7'=>[7,7],
        '8'=>[8,8],
        '9'=>[9,9],
        '10'=>[10,10],
        'J'=>[10,10],
        'Q'=>[10,10],
        'K'=>[10,10]
    ];

    const LIMIT_NUMBER = 21;

    public function calculate(array $hand):mixed
    {
        $handNumbers=[];
        foreach($hand as $card){
            $handNumbers[]= $this->convertCardToNumber($card);
        }
        $sum = $this->addCards($handNumbers);
        if($sum>self::LIMIT_NUMBER){
            return 'burst';
        }
        return $sum;
    }

    public function convertCardToNumber(string $card):array
    {
        $cardNumber = self::CARD_NUMBERS[substr($card,1)];
        return $cardNumber;
    }

    public function addCards(array $hand):int
    {
        $sum0 = 0;
        $sum1 = 0;
        foreach($hand as $card){
                $sum0 += $card[0];
                $sum1 += $card[1];
        }
        if($sum1>21){
            $sum = $sum0;
        }else{
            $sum = $sum1;
        }
        return $sum;
    }

    // public function judgeWinner(array $playerHand,array $dealerHand){}
}
