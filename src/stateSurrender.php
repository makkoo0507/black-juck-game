<?php

namespace blackJack;

require_once(__DIR__.'/config.php');
use blackJack\Config;

class StateSurrender
{
    public function needsSelectAction()
    {
        return FALSE;
    }

    public function action(AbstractPlayer $player)
    {
        $player->changeActionState(Config::STATE['surrender']);
        echo $player->getName() . 'はSurrenderを選択しました';
        RunWithEnter();
        return;
    }
}
