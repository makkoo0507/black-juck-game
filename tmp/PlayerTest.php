<?php

namespace blackJack\test ;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../src/player.php');
use blackJack\Player; //use blackJack\Sample as Sample の省略

class PlayerTest extends TestCase
{
    public function testPlayer()
    {
        $player = new Player('masato');
        $this->assertSame('masato',$player->getName());
        $this->assertSame(['D4'],$player->addCardToHand('D4'));
        $this->assertSame(['D4','H5'],$player->addCardToHand('H5'));
    }
}
