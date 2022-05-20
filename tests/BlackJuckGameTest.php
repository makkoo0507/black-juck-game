<?php

namespace blackJack\test ;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../src/blackJackGame.php' );
use blackJack\BlackJackGame; //use blackJack\Sample as Sample の省略
require_once(__DIR__ . '/../src/player.php');
use blackJack\Player; //use blackJack\Sample as Sample の省略

// class BlackJackGameTest extends TestCase
// {
//     public function testStart()
//     {
//         // $player = new Player('masato');
//         $game = new BlackJackGame('masato');
//         $this->assertSame(2,count($game->start()));
//     }
// }
