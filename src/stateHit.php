<?php

namespace blackJack;

require_once(__DIR__.'/config.php');
use blackJack\Config;

class StateHit
{
    public function needsSelectAction()
    {
        return TRUE;
    }

    public function action(AbstractPlayer $player,PlayingCards $cards)
    {
        $player->changeActionState(Config::STATE['hit']);
        // playerにカードを一枚配る
        echo $player->getName() . 'はHitを選択しました';
        RunWithEnter();
        $player->drawCardFromDeck($cards);
        echo $player->showHand($player->getHand()->getCardsNumber());
        RunWithEnter();
        //一枚引いた結果burstしたら終了
        if ($player->getHand()->evaluateHand() === Config::RESULT['burst']) {
            $player->changeActionState(Config::STATE['burst']);
            echo  Config::RESULT['burst'] . 'しました' . PHP_EOL;
        }
    }
}
