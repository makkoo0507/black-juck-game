<?php

namespace blackJack;

require_once(__DIR__ . '/config.php');

use blackJack\Config;

class CPU extends Player
{
    // インスタンスの生成時に名前を受け取る
    // インスタンスの生成時に名前を受け取る,handインスタンスの作成
    public function __construct(
        private string $name = 'player',
        private Hand $hand = new Hand,
        private string $actionState = Config::STATE['hit'],
        private string $result = 'No results'
        )
    {
        parent::__construct($name,$hand,$actionState,$result);
    }


    // CPUが次のアクションを選ぶときのルール
    public function selectAction(bool $ReEnter = FALSE)
    {
        if ($this->hand->getCardsNumber() === 2 && $this->hand->isSameNumberCards()){
            return Config::ACTIONS['split'];
        }
        if($this->getActionState()===Config::RESULT['blackJack']){
            return Config::RESULT['blackJack'];
        }
        $calcPlayerHandNumber = $this->hand->evaluateHand();
        if($calcPlayerHandNumber<17){
            return Config::ACTIONS['hit'];
        }
        return Config::ACTIONS['stand'];
    }

    public function selectInsurance()
    {
        return Config::ACTIONS['insurance'];
    }
    public function selectEven()
    {
        return Config::ACTIONS['noEven'];
    }
}
