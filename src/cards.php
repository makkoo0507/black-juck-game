<?php

namespace blackJack;

class Cards
{
    private const SUITS =
    [
        'spade' => 'S',
        'heart' => 'H',
        'Diamond' => 'D',
        'clob' => 'C'
    ];
    private const CARD_NUMBER =
    [
        1 => 'A',
        2 => '2',
        3 => '3',
        4 => '4',
        5 => '5',
        6 => '6',
        7 => '7',
        8 => '8',
        9 => '9',
        10 => '10',
        11 => 'J',
        12 => 'Q',
        13 => 'K'
    ];
    private $cards;

    public function __construct()
    {
        $this->createCards();
        shuffle($this->cards);
    }

    public function createCards()
    {
        $this->cards = array_map(function ($suit) {
            $cards = [];
            foreach (self::CARD_NUMBER as $number) {
                $cards[] = $suit . $number;
            }
            return $cards;
        }, self::SUITS);

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
}
