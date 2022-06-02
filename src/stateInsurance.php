<?php

namespace blackJack;

require_once(__DIR__.'/config.php');

use blackJack\Config;

class StateInsurance
{
    public function needsSelectAction()
    {
        return TRUE;
    }
    public function action(AbstractPlayer $player)
    {
        $player->changeActionState(Config::STATE['stand']);
        echo $player->getName() . 'はStandを選択しました';
        RunWithEnter();
    }
}
