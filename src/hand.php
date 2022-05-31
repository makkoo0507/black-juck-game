<?php
namespace blackJack;

require_once(__DIR__.'/config.php');
use blackJack\Config;

class Hand
{

    private array $hand;

    // 手札にカードを一枚加える
    public function addCardToHand(Card $card)
    {
        $this->hand[] = $card;
    }

    //手札の枚数を表示
    public function getCardsNumber(){
        return count($this->hand);
    }

    //カードを表示する形に変換
    public function convertToDisplayFormat():array
    {
        $DisplayFormatHand=[];
        foreach($this->hand as $card){
            $DisplayFormatHand[] = $card->getCard();
        }
        return $DisplayFormatHand;
    }

    // 手札を表示する
    public function showHand(int $showNumber)
    {
        $DisplayFormatHand = $this->convertToDisplayFormat();
        $showCards = []; //表示するカードだけ格納する
        for ($i = 0; $i < count($DisplayFormatHand); $i++) {
            if ($i < $showNumber) {
                $showCards[] = $DisplayFormatHand[$i];
            } else {
                $showCards[] = '⬜︎'; //表示しないカードの置き換え
            }
        }
        return $showCards;
        // return $this->getName() . '手札' . '[' . implode(',', $showCards) . ']';
    }

    public function getCardInHand(int $number):Card
    {
        return $this->hand[$number];
    }

    public function existFistCard(string $cardNumber){
        if($this->hand[0]->getCardNumbers()===Config::CARD_NUMBERS[$cardNumber]){
            return TRUE;
        }
        return FALSE;
    }

    public function evaluateHand(): mixed
    {
        $sum = $this->addCards($this->hand);
        if (count($this->hand) === 2 && $sum === Config::LIMIT_NUMBER) {
            return Config::RESULT['blackJack'];
        }
        if ($sum > Config::LIMIT_NUMBER) {
            return Config::RESULT['burst'];
        }
        return $sum;
    }

    public function addCards(): int
    {
        $sum = 0;
        foreach ($this->hand as $card) {
            if ($card->getCardNumbers() !== Config::CARD_NUMBERS['A']) {
                $sum += $card->getCardNumbers()[0];
            }
        }
        if (self::numberOfA($this->hand)) {
            $sum1 = $sum + 10 + $this->numberOfA($this->hand);
            $sum2 = $sum + $this->numberOfA($this->hand);
            if ($sum1 > 21) {
                return $sum2;
            }
            return $sum1;
        }
        return $sum;
    }

    public function isSameNumberCards()
    {
        if (count($this->hand) > 2) {
            return FALSE;
        }
        $handNumbers = [];
        foreach ($this->hand as $card) {
            $handNumbers[] = $card->getCardNumbers();
        }
        if ($handNumbers[0] === $handNumbers[1]) {
            return TRUE;
        }
        return FALSE;
    }


    public function numberOfA()
    {
        $count = 0;
        foreach ($this->hand as $card) {
            if ($card->getCardNumbers() === Config::CARD_NUMBERS['A']) {
                $count++;
            }
        }
        return $count;
    }
}
