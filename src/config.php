<?php
require_once(__DIR__ . '/cards.php');

use blackJack\Cards;

require_once(__DIR__ . '/player.php');

use blackJack\Player;

require_once(__DIR__ . '/CPUPlayer.php');

use blackJack\CPUPlayer;

require_once(__DIR__ . '/dealer.php');

use blackJack\Dealer;

require_once(__DIR__ . '/calculateHand.php');

use blackJack\CalculateHand;

require_once(__DIR__ . '/handleCards.php');

use blackJack\HandleCards;

require_once(__DIR__ . '/gameManager.php');

use blackJack\GameManager;

const LIMIT_NUMBER = 21;

const SUITS =
[
    'spade' => 'S',
    'heart' => 'H',
    'Diamond' => 'D',
    'clob' => 'C'
];
const CARD_NUMBER =
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

const CARD_NUMBERS =
[
    'A' => [1, 11],
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


const ACTIONS =
[
    'hit' => 'Hit',
    'stand' => 'Stand',
    'surrender' => 'Surrender',
    'double' => 'Double Down',
    'split' => 'Split',
    'insurance' => 'Insurance',
    'noInsurance' => 'No Insurance',
    'even' => 'Even Money',
    'noEven' => 'No Even'
];

const ACTIONS_INPUT_KEY =
[
    ACTIONS['hit'] => 'h',
    ACTIONS['stand'] => 's',
    ACTIONS['surrender'] => 'su',
    ACTIONS['double'] => 'd',
    ACTIONS['split'] => 'sp',
    ACTIONS['insurance'] => 'i',
    ACTIONS['even'] => 'e'
];

const STATE =
[
    'blackJack' => 'end',
    'hit' => 'continue',
    'stand' => 'stand',
    'surrender' => 'surrender',
    'double' => 'Double Down',
    'split' => 'Split',
    'insurance' => 'Insurance',
    'noInsurance' => 'No Insurance',
    'even' => 'Even Money',
    'noEven' => 'No Even'
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
