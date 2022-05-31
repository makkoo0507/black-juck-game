<?php

namespace blackJack;

require_once(__DIR__ . '/config.php');

use blackJack\Config;

use blackJack\CalculateHand;

class Player extends abstractPlayer
{
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

    // 次のアクションを選択するためのメッセージ
    public function selectActionMessage()
    {
        if ($this->hand->getCardsNumber() === 2 && $this->hand->isSameNumberCards()){
            return
            Config::ACTIONS_INPUT_KEY[Config::ACTIONS['hit']].'('.Config::ACTIONS['hit'].')'.' or '.
            Config::ACTIONS_INPUT_KEY[Config::ACTIONS['stand']].'('.Config::ACTIONS['stand'].')'.' or '.
            Config::ACTIONS_INPUT_KEY[Config::ACTIONS['surrender']].'('.Config::ACTIONS['surrender'].')'.' or '.
            Config::ACTIONS_INPUT_KEY[Config::ACTIONS['split']].'('.Config::ACTIONS['split'].')'.' or '.
            Config::ACTIONS_INPUT_KEY[Config::ACTIONS['double']].'('.Config::ACTIONS['double'].')'.':';

        }
        if ($this->hand->getCardsNumber() === 2) {
            return
            Config::ACTIONS_INPUT_KEY[Config::ACTIONS['hit']].'('.Config::ACTIONS['hit'].')'.' or '.
            Config::ACTIONS_INPUT_KEY[Config::ACTIONS['stand']].'('.Config::ACTIONS['stand'].')'.' or '.
            Config::ACTIONS_INPUT_KEY[Config::ACTIONS['surrender']].'('.Config::ACTIONS['surrender'].')'.' or '.
            Config::ACTIONS_INPUT_KEY[Config::ACTIONS['double']].'('.Config::ACTIONS['double'].')'.':';
        }
        if ($this->hand->getCardsNumber() > 2) {
            return 'h(hit) or s(stand) :';
        }
    }

    // プレイヤーが次のアクションを選ぶときのルール
    public function selectAction(bool $ReEnter = FALSE)
    {
        if($this->getActionState()===Config::RESULT['blackJack']){
            return Config::RESULT['blackJack'];
        }
        $calcPlayerHandNumber = $this->hand->EvaluateHand();
        if (!$ReEnter) { // 記載ミスによる再記入の時は表示しない
            echo $this->getName() . 'の現在の得点は' . $calcPlayerHandNumber . PHP_EOL;
        }

        echo $this->selectActionMessage();
        $selectedAction = trim(fgets(STDIN));
        if (in_array($selectedAction, Config::ACTIONS_INPUT_KEY) === FALSE) {
            $ReEnter = TRUE;
            return $this->selectAction($ReEnter);
        }
        return array_keys(Config::ACTIONS_INPUT_KEY, $selectedAction)[0];
    }

    // インシュランスを選択するためのメッセージ
    public function selectInsurance()
    {
        echo 'Insurance? y or n :';
        $isInsurance = trim(fgets(STDIN));
        if($isInsurance === 'y'){
            return Config::ACTIONS['insurance'];
        }
        if($isInsurance === 'n' ){
            return Config::ACTIONS['noInsurance'];
        }
        return $this->selectInsurance();
    }

    //イーブンを選択するためのメッセージ
    public function selectEven()
    {
        echo 'Even? y or n :';
        $isEven = trim(fgets(STDIN));
        if($isEven === 'y'){
            return Config::ACTIONS['even'];
        }
        if($isEven === 'n' ){
            return Config::ACTIONS['noEven'];
        }
        return $this->selectEven();
    }
}
