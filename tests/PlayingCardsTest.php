<?php

namespace blackJack\test ;

use blackJack\Card;
use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../src/playingCards.php');
use blackJack\PlayingCards; //use blackJack\Sample as Sample の省略


class PlayingCardsTest extends TestCase
{
    public function testCards()
    {
        $cards = new PlayingCards(SUITS,CARD_NUMBER);
        $this->assertSame(TRUE,in_array(new Card('H',4),$cards->getCards()));
        $this->assertSame(TRUE,in_array(new Card('C','Q'),$cards->getCards()));
        $this->assertSame(TRUE,in_array(new Card('H','7'),$cards->getCards()));
        $this->assertSame(TRUE,in_array(new Card('H','J'),$cards->getCards()));
        $this->assertSame(TRUE,in_array(new Card('S',10),$cards->getCards()));
    }
}
