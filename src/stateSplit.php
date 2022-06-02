<?php

namespace blackJack;

require_once(__DIR__.'/config.php');

use blackJack\Config;

class StateSplit
{
    public function needsSelectAction()
    {
        return FALSE;
    }
    public function action(AbstractPlayer $player,PlayingCards $cards)
    {
        $player->changeActionState(Config::STATE['split']);
        echo $player->getName() . 'は' . Config::STATE['split'] . 'を選択しました';
        RunWithEnter();
        $whoClass = $player::class;
        $player1 = new $whoClass($player->getName() . '1');
        $player2 = new $whoClass($player->getName() . '2');

        foreach ([$player1, $player2] as $spritPlayer) {
            $spritPlayer->getHand()->addCardToHand($player->getHand()->getCardInHand(array_search($spritPlayer, [$player1, $player2])));
            $spritPlayer->drawCardFromDeck($cards);
            echo $spritPlayer->showHand($spritPlayer->getHand()->getCardsNumber());
            RunWithEnter();
        }
        return [$player1,$player2];
    }
}
