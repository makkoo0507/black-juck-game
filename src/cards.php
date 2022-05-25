<?php

namespace blackJack;

class Cards
{

    private $cards;

    public function __construct()
    {
        $this->createCards();
    }

    public function createCards()
    {
        $this->cards = array_map(function ($suit) {
            $cards = [];
            foreach (CARD_NUMBER as $number) {
                $cards[] = $suit . $number;
            }
            return $cards;
        }, SUITS);

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

    public function drownCard()
    {
        $this->cards = array_slice($this->cards,1);
    }
    public function shuffledCards(){
        shuffle($this->cards);
    }
}
