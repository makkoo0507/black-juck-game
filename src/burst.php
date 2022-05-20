<?php

namespace blackJack;

require_once(__DIR__ . '/action.php');

use blackJack\Action;

class Burst extends Action
{
    public function __construct(private HandleCards $handler, private CalculateHand $calculator, private Cards $cards, private Dealer $dealer)
    {
        parent::__construct($handler, $calculator, $cards, $dealer);
    }

    public function getJudgedCardsNumber()
    {
        if (count($this->player->getMyHand()) >= 3) {
            return TRUE;
        }
        return FALSE;
    }

    public function getPlayerBurstState()
    {
        if ($this->calculator->calculate($this->player->getMyHand()) === 'burst') {
            return TRUE;
        }
    }

    public function burstState()
    {
        echo 'burstしました' . PHP_EOL;
        return;
    }
}
