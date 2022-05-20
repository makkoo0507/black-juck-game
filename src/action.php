<?php

namespace blackJack;

abstract class Action
{
    public function __construct(private HandleCards $handler,private CalculateHand $calculator,private Cards $cards,private Dealer $dealer)
    {
    }
}
