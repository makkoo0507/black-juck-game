<?php
namespace blackJack;

class PlayingCards
{

    private $cards;
    public function __construct(array $suit,array $cardNumber)
    {
        $this->createCards($suit,$cardNumber);
    }

    public function createCards(array $suit,array $cardNumber)
    {
        $this->cards = array_map(function ($symbol) use($cardNumber){
            $cards = [];
            foreach ($cardNumber as $number) {
                $cards[] = new Card($symbol,$number);
            }
            return $cards;
        }, $suit);

        $mergedCard = [];
        foreach ($this->cards as $cards) {
            $mergedCard = array_merge($mergedCard, $cards);
        }
        $this->cards = $mergedCard;
    }

    public function getCards()
    {
        return $this->cards;
    }

    public function drawnCard()
    {
        $DrawnCard = $this->cards[0];
        $this->cards = array_slice($this->cards,1);
        return $DrawnCard;
    }

    public function shuffleCards(){
        shuffle($this->cards);
    }

}
