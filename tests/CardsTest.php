<?php

namespace blackJack\test ;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../src/cards.php');
use blackJack\Cards; //use blackJack\Sample as Sample の省略

class CardsTest extends TestCase
{
    public function testCards()
    {
        $cards = new Cards();
        $this->assertSame(TRUE,in_array('C4',$cards->getCards()));
        $this->assertSame(TRUE,in_array('CQ',$cards->getCards()));
        $this->assertSame(TRUE,in_array('H7',$cards->getCards()));
        $this->assertSame(TRUE,in_array('HJ',$cards->getCards()));
        $this->assertSame(TRUE,in_array('S10',$cards->getCards()));
        $this->assertSame(TRUE,in_array('SA',$cards->getCards()));
        $this->assertSame(TRUE,in_array('D2',$cards->getCards()));
        $this->assertSame(TRUE,in_array('DK',$cards->getCards()));
        $this->assertSame(TRUE,in_array('D3',$cards->getCards()));
    }
}
