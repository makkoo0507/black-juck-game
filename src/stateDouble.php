<?php

namespace blackJack;

require_once(__DIR__.'/config.php');
use blackJack\Config;

class StateDouble
{
    public function needsSelectAction()
    {
        return FALSE;
    }
    public function action(AbstractPlayer $player,PlayingCards $cards)
    {
        $player->changeActionState(Config::STATE['double']);
        // playerにカードを一枚配る
        echo $player->getName() . 'はDoubleを選択しました';
        RunWithEnter();
        $player->drawCardFromDeck($cards);
        echo $player->showHand($player->getHand()->getCardsNumber());
        RunWithEnter();
        //一枚引いた結果burstしたら通知
        if ($player->getHand()->evaluateHand() === Config::RESULT['burst']) {
            echo  Config::RESULT['burst'] . 'しました' . PHP_EOL;
            $player->changeActionState(Config::STATE['burst']);
            return;
        }
        // burstでなくても一枚引いたら終了
        echo $player->getName() . 'の得点は' . $player->getHand()->evaluateHand() . PHP_EOL;
        return;
    }
}
