<?php

namespace blackJack;

require_once(__DIR__ . '/abstractPlayer.php');

use blackJack\AbstractPlayer;

require_once(__DIR__ . '/calculateHand.php');

use blackJack\CalculateHand;

class Player extends abstractPlayer
{
    // 計算機のインスタンス
    private CalculateHand $calc;
    // インスタンスの生成時に名前を受け取る
    public function __construct(private string $name = 'player')
    {
        parent::__construct($name);
        $this->calc = new CalculateHand;
    }
    // 名前の出力
    public function getName()
    {
        return $this->name;
    }

    // ステイト
    private $actionState = STATE['hit'];

    public function changeActionState(string $action){
        $this->actionState = $action;
    }

    public function getActionState(){
        return $this->actionState;
    }


    // 自分の手札
    private array $hands;
    // 手札を取得
    public function getMyHand()
    {
        return $this->hands;
    }
    public function changeMyHand(array $newHand){
        $this->hands = $newHand;
    }
    //勝敗結果
    private string $result = 'No results';
    // 結果を保持
    public function changeResult(string $result){
        $this->result = $result;
    }
    // 結果を取得
    public function getResult(){
        return $this->result;
    }
    // 手札にカードを一枚加える
    public function addCardToHand(string $card): array
    {
        $this->hands[] = $card;
        return $this->hands;
    }

    public function selectActionMessage()
    {
        if (count($this->getMyHand()) === 2 && $this->calc->isSameNumberCards($this->getMyHand())){
            return
            ACTIONS_INPUT_KEY[ACTIONS['hit']].'('.ACTIONS['hit'].')'.' or '.
            ACTIONS_INPUT_KEY[ACTIONS['stand']].'('.ACTIONS['stand'].')'.' or '.
            ACTIONS_INPUT_KEY[ACTIONS['surrender']].'('.ACTIONS['surrender'].')'.' or '.
            ACTIONS_INPUT_KEY[ACTIONS['split']].'('.ACTIONS['split'].')'.' or '.
            ACTIONS_INPUT_KEY[ACTIONS['double']].'('.ACTIONS['double'].')'.':';

        }
        if (count($this->getMyHand()) === 2) {
            return
            ACTIONS_INPUT_KEY[ACTIONS['hit']].'('.ACTIONS['hit'].')'.' or '.
            ACTIONS_INPUT_KEY[ACTIONS['stand']].'('.ACTIONS['stand'].')'.' or '.
            ACTIONS_INPUT_KEY[ACTIONS['surrender']].'('.ACTIONS['surrender'].')'.' or '.
            ACTIONS_INPUT_KEY[ACTIONS['double']].'('.ACTIONS['double'].')'.':';
        }
        if (count($this->getMyHand()) > 2) {
            return 'h(hit) or s(stand) :';
        }
    }

    public function selectAction(bool $ReEnter = FALSE)
    {
        if($this->getActionState()===RESULT['blackJack']){
            return RESULT['blackJack'];
        }
        $calcPlayerHandNumber = $this->calc->calculate($this->getMyHand());
        if (!$ReEnter) { // 記載ミスによる再記入の時は表示しない
            echo $this->getName() . 'の現在の得点は' . $calcPlayerHandNumber . PHP_EOL;
        }

        echo $this->selectActionMessage();
        $selectedAction = trim(fgets(STDIN));
        if (in_array($selectedAction, ACTIONS_INPUT_KEY) === FALSE) {
            $ReEnter = TRUE;
            return $this->selectAction($ReEnter);
        }
        return array_keys(ACTIONS_INPUT_KEY, $selectedAction)[0];
    }
    public function selectInsurance()
    {
        echo 'Insurance? y or n :';
        $isInsurance = trim(fgets(STDIN));
        if($isInsurance === 'y'){
            return ACTIONS['insurance'];
        }
        if($isInsurance === 'n' ){
            return ACTIONS['noInsurance'];
        }
        return $this->selectInsurance();
    }
    public function selectEven()
    {
        echo 'Even? y or n :';
        $isEven = trim(fgets(STDIN));
        if($isEven === 'y'){
            return ACTIONS['even'];
        }
        if($isEven === 'n' ){
            return ACTIONS['noEven'];
        }
        return $this->selectEven();
    }
}
