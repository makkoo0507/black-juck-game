<?php
namespace blackJack;

class Card
{
    private $card;
    public function __construct(private string $suit,private string $cardNumber)
    {
        $this->card = $suit.$cardNumber;
    }

    public function getCard(){
        return $this->card;
    }

    public function getCardNumbers(){
        return Config::CARD_NUMBERS[$this->cardNumber];
    }
}

