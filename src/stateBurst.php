<?php

namespace blackJack;

require_once(__DIR__.'/config.php');
use blackJack\Config;

class StateBurst
{
    public function needsSelectAction()
    {
        return FALSE;
    }
}
