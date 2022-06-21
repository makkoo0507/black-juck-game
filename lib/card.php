<?php
namespace blackJack;

class Card
{
    private $card;
    private $img;
    public function __construct(private string $suit,private string $cardNumber)
    {
        $this->card = $suit.$cardNumber;
        $this->img = array_keys(Constants::SUITS,$suit)[0].$cardNumber.'_result.png';
    }

    public function getCard(){
        return $this->card;
    }

    public function getImg(){
        return $this->img;
    }

    public function getCardNumbers(){
        return Constants::CARD_NUMBERS[$this->cardNumber];
    }
}
