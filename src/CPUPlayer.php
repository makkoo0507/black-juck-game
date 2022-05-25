<?php

namespace blackJack;

require_once(__DIR__ . '/abstractPlayer.php');

use blackJack\AbstractPlayer;

require_once(__DIR__ . '/calculateHand.php');

use blackJack\CalculateHand;

class CPUPlayer extends abstractPlayer
{
    // 計算機のインスタンス
    private CalculateHand $calc;

    // インスタンスの生成時に名前を受け取る
    public function __construct(private string $name = 'CPU')
    {
        parent::__construct($name);
        $this->calc = new CalculateHand;
    }
    // 名前の出力
    public function getName()
    {
        return $this->name;
    }

    //現在のアクションについて
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
    // 手札にカードを一枚加える
    public function addCardToHand(string $card): array
    {
        $this->hands[] = $card;
        return $this->hands;
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



    // ヒットするかステイするかを決定するルールをクラス毎で設定する
    // CPUプレイヤーは何かしらの規則に則りプレー

    public function selectAction()
    {
        if (count($this->getMyHand()) === 2 && $this->calc->isSameNumberCards($this->getMyHand())){
            return ACTIONS['stand'];
        }
        if($this->getActionState()===RESULT['blackJack']){
            return RESULT['blackJack'];
        }
        $calcPlayerHandNumber = $this->calc->calculate($this->getMyHand());
        if($calcPlayerHandNumber<17){
            return ACTIONS['hit'];
        }
        return ACTIONS['stand'];
    }

    public function selectInsurance()
    {
        return ACTIONS['insurance'];
    }
    public function selectEven()
    {
        return ACTIONS['noEven'];
    }
}
