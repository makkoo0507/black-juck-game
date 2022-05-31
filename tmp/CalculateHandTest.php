<?php

namespace blackJack\test ;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../src/calculateHand.php');
use blackJack\CalculateHand; //use blackJack\Sample as Sample の省略

class CalculateHandTest extends TestCase
{
    public function testCalculateHand()
    {
        $calculator = new CalculateHand();
        $this->assertSame(4,$calculator->convertCardToNumber('C4'));
        $this->assertSame(9,$calculator->calculate(['C4','H5']));
        $this->assertSame('burst',$calculator->calculate(['C4','H5','CK','S6']));
    }
}
