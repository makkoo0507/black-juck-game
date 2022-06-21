<?php
namespace blackJack;

class Constants
{
    const LIMIT_NUMBER = 21;

    const SUITS =
    [
        's' => '♠️',
        'h' => '❤️',
        'd' => '♦️',
        'c' => '☘️'
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


    const INSURANCES_STATE =
    [
        'unselected'=>'',
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
        self::INSURANCES_STATE['insurance'] => 'i',
        self::INSURANCES_STATE['even'] => 'e'
    ];

    const STATE =
    [
        'possibleBJ'=>'Possible&nbsp;&nbsp;BJ',
        'noBlackJack'=>'No&nbsp;&nbsp;BJ',
        'blackJack' => 'Stand&nbsp;&nbsp;BJ',
        'burst' => 'Stand&nbsp;&nbsp;Burst',
        'hit' => 'Continue',
        'stand' => 'Stand',
        'surrender' => 'Surrender',
        'double' => 'Double&nbsp;&nbsp;Down',
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
        'blackJack' => 'Black&nbsp;&nbsp;Jack!',
        'insurance' => 'LOSE(Insurance)',
        'burst' => 'BURST',
        'dealer burst' => 'WIN',
        'win' => 'WIN',
        'double' => 'WIN(Double Down)',
        'surrender' => 'LOSE(Surrender)',
        'lose' => 'LOSE',
        'push' => 'PUSH',
        'even' => 'PUSH(Even)'
    ];
}
