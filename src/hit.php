<?php

namespace blackJack;

require_once(__DIR__.'/action.php');
use blackJack\Action;

class Hit extends Action
{
    private $judgedCardsNumber;
    public function __construct(private HandleCards $handler,private CalculateHand $calculator,private Cards $cards,private Dealer $dealer)
    {
        parent::__construct($handler,$calculator,$cards,$dealer);
    }

    public function getJudgedCardsNumber()
    {
        if(count($this->player->getMyHand())>0){
            return TRUE;
        }
        return FALSE;
    }

    public function getPlayerSelect(){
        if($this->player->selectActionRule()==='hit'){
            return TRUE;
        }
        return FALSE;
    }

    public function selectedHit()
    {
        // playerにカードを一枚配る
        echo $this->player->getName().'はヒットを選択しました';
        RunWithEnter();
        $this->handler->dealCard($this->player, $this->cards);
        echo $this->handler->showHand($this->player, count($this->player->getMyHand()));
        RunWithEnter();
    }

}
