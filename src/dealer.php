<?php

namespace blackJack;

require_once(__DIR__ . '/abstractPlayer.php');

use blackJack\AbstractPlayer;

require_once(__DIR__ . '/calculateHand.php');

use blackJack\CalculateHand;


class Dealer extends abstractPlayer
{
    // 手札
    private array $hands;
    // 計算機
    private CalculateHand $calc;
    public function __construct(private string $name = "dealer")
    {
        parent::__construct($name);
        $this->calc = new CalculateHand;
    }
    private $actionState = STATE['hit'];
    public function changeActionState(string $action){
        $this->actionState = $action;
    }

    public function getActionState(){
        return $this->actionState;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getMyHand()
    {
        return $this->hands;
    }
    public function addCardToHand(string $card): array
    {
        $this->hands[] = $card;
        return $this->hands;
    }
    // ヒットするかどうかのルール
    public function selectAction(bool $ReEnter = FALSE)
    {

        $calcPlayerHandNumber = $this->calc->calculate($this->getMyHand());
        if($calcPlayerHandNumber<17){
            return ACTIONS['hit'];
        }
        return ACTIONS['stand'];
    }

    public function selectInsurance(){
        return ACTIONS['noInsurance'];
    }
    public function selectEven()
    {
        return ACTIONS['noEven'];
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
}
