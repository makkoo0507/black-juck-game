<?php
namespace blackJack;

require_once(__DIR__ . '/card.php');

use blackJack\Card;

require_once(__DIR__ . '/playingCards.php');

use blackJack\PlayingCards;

require_once(__DIR__ . '/hand.php');

use blackJack\Hand;

require_once(__DIR__ . '/abstractPlayer.php');

use blackJack\AbstractPlayer;

require_once(__DIR__ . '/player.php');

use blackJack\Player;

require_once(__DIR__ . '/CPU.php');

use blackJack\CPU;

require_once(__DIR__ . '/dealer.php');

use blackJack\Dealer;

require_once(__DIR__ . '/gameManager.php');

use blackJack\GameManager;


require_once(__DIR__ . '/rule.php');

use blackJack\Rule;

require_once(__DIR__ . '/stateHit.php');

use blackJack\StateHit;

require_once(__DIR__ . '/stateBlackJack.php');

use blackJack\StateBlackJack;

require_once(__DIR__ . '/stateBurst.php');

use blackJack\StateBurst;

require_once(__DIR__ . '/stateStand.php');

use blackJack\StateStand;

require_once(__DIR__ . '/stateSurrender.php');

use blackJack\StateSurrender;

require_once(__DIR__ . '/stateDouble.php');

use blackJack\StateDouble;

require_once(__DIR__ . '/stateSplit.php');

use blackJack\StateSplit;

class Config
{
    const LIMIT_NUMBER = 21;

    const SUITS =
    [
        'spade' => '♠️',
        'heart' => '❤️',
        'Diamond' => '♦️',
        'clob' => '☘️'
    ];
    const CARD_NUMBER =
    [
        1 => 'A',
        // 2 => 'A',
        // 3 => 'A',
        // 4 => 'A',
        // 5 => 'A',
        // 6 => 'A',
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

    const CARD_NUMBERS =
    [
        'A' => [11, 1],
        '2' => [2, 2],
        '3' => [3, 3],
        '4' => [4, 4],
        '5' => [5, 5],
        '6' => [6, 6],
        '7' => [7, 7],
        '8' => [8, 8],
        '9' => [9, 9],
        '10' => [10, 10],
        'J' => [10, 10],
        'Q' => [10, 10],
        'K' => [10, 10]
    ];


    const INSURANCES_CHOICES =
    [
        'insurance' => 'Insurance',
        'noInsurance' => 'No Insurance',
        'even' => 'Even Money',
        'noEven' => 'No Even'
    ];

    const INPUT_KEY =
    [
        self::STATE['hit'] => 'h',
        self::STATE['stand'] => 's',
        self::STATE['surrender'] => 'su',
        self::STATE['double'] => 'd',
        self::STATE['split'] => 'sp',
        self::INSURANCES_CHOICES['insurance'] => 'i',
        self::INSURANCES_CHOICES['even'] => 'e'
    ];

    const STATE =
    [
        'blackJack' => 'stand at black Jack',
        'burst' => 'stand at burst',
        'hit' => 'continue at hit',
        'stand' => 'stand',
        'surrender' => 'surrender',
        'double' => 'stand at Double Down',
        'split' => 'Split',
    ];

    const STATE_CLASSES =
    [
        self::STATE['blackJack'] => StateBlackJack::class,
        self::STATE['burst'] => StateBurst::class,
        self::STATE['hit'] => StateHit::class,
        self::STATE['stand'] => StateStand::class,
        self::STATE['surrender'] => StateSurrender::class,
        self::STATE['double'] => StateDouble::class,
        self::STATE['split'] => StateSplit::class
    ];

    const RESULT =
    [
        'blackJack' => 'Black Jack!',
        'insurance' => 'Insurance Succeeds',
        'burst' => 'BURST',
        'dealer burst' => 'WIN',
        'win' => 'WIN',
        'double' => 'WIN(Double Down)',
        'surrender' => 'LOSE(Surrender)',
        'lose' => 'LOSE',
        'push' => 'PUSH'
    ];
}
