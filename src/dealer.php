<?php

namespace blackJack;

require_once(__DIR__ . '/abstractPlayer.php');

use blackJack\AbstractPlayer;

require_once(__DIR__ . '/calculateHand.php');

use blackJack\CalculateHand;


class Dealer extends abstractPlayer
{
    // 手札
    private array $hands;
    // 計算機
    private CalculateHand $calc;
    public function __construct(private string $name = "dealer")
    {
        parent::__construct($name);
        $this->calc = new CalculateHand;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getMyHand()
    {
        return $this->hands;
    }
    public function addCardToHand(string $card): array
    {
        $this->hands[] = $card;
        return $this->hands;
    }
    // ヒットするかどうかのルール
    public function selectActionRule(bool $ReEnter = FALSE)
    {
        $calcPlayerHandNumber = $this->calc->calculate($this->getMyHand());
        if($calcPlayerHandNumber<17){
            return 'hit';
        }
        return 'stay';
    }
}
