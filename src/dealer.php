<?php

namespace blackJack;

require_once(__DIR__ . '/abstractPlayer.php');

use blackJack\AbstractPlayer;


class Dealer extends abstractPlayer
{
    public function __construct(
        private string $name = 'dealer',
        private Hand $hand = new Hand,
        private string $actionState = Config::STATE['hit'],
        private string $result = 'No results'
        )
    {
        parent::__construct($name,$hand,$actionState,$result);
    }


    // 一枚目に引数で選んだNumberがあるかかどうか
    public function existFistCard(string $cardNumber){
        return $this->hand->existFistCard($cardNumber);
    }

    // プレイヤーが次のアクションを選ぶときのルール
    public function selectAction(bool $ReEnter = FALSE)
    {
        $calcPlayerHandNumber = $this->hand->evaluateHand();
        if($calcPlayerHandNumber<17){
            return Config::STATE['hit'];
        }
        return Config::STATE['stand'];
    }

    public function selectInsurance(){
        return Config::INSURANCES_CHOICES['noInsurance'];
    }
    public function selectEven()
    {
        return Config::INSURANCES_CHOICES['noEven'];
    }

}
