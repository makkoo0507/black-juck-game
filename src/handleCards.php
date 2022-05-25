<?php
namespace blackJack;

class HandleCards
{
    public function shuffleCards(Cards $cards):void
    {
        $cards->shuffledCards();
    }

    public function dealCard(AbstractPlayer $who,Cards $cards):void
    {
        $who->addCardToHand($cards->getCards()[0]);
        $cards->drownCard();
    }

    // 手札を表示する関数
    public function showHand(AbstractPlayer $who, int $showNumber): string
    {
        $showCards = []; //表示するカードだけ格納する
        for ($i = 0; $i < count($who->getMyHand()); $i++) {
            if ($i < $showNumber) {
                $showCards[] = $who->getMyHand()[$i];
            } else {
                $showCards[] = '⬜︎'; //表示しないカードの置き換え
            }
        }
        return $who->getName() . '手札' . '[' . implode(',', $showCards) . ']';
    }
}
