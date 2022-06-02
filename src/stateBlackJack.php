<?php

namespace blackJack;

require_once(__DIR__.'/config.php');
use blackJack\Config;

class StateBlackJack
{
    public function needsSelectAction()
    {
        return FALSE;
    }
}
