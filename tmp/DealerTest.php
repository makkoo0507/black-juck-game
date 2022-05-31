<?php

namespace blackJack\test ;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../src/dealer.php');
use blackJack\Dealer; //use blackJack\Sample as Sample の省略

class DealerTest extends TestCase
{
    public function testPlayer()
    {
        $dealer = new Dealer();
        $this->assertSame('dealer',$dealer->getName());
        $this->assertSame(['D4'],$dealer->addCardToHand('D4'));
        $this->assertSame(['D4','H5'],$dealer->addCardToHand('H5'));
    }
}
