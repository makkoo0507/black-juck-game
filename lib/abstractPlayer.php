<?php

namespace blackJack;

abstract class AbstractPlayer
{
    // 自分の手札
    // private array $hand;
    // インスタンスの生成時に名前を受け取る
    public function __construct(
        private string $name,
        private Hand $hand=new Hand,
        private string $actionState = Constants::STATE['hit'],
        private $insurancesState = Constants::INSURANCES_STATE['unselected'],
        private string $score = 'No scores',
        ){}
    // 名前の取得
    public function getName(){
        return $this->name;
    }
    // 手札を取得
    public function getHand(){
        return $this->hand;
    }

    //stateの変更
    public function setActionState(string $action){
        $this->actionState = $action;
    }

    //stateの取得
    public function getActionState(){
        return $this->actionState;
    }

    // アクションの選択を続けるかの判断

    public function judgeContinueAction(){
        if($this->getActionState()=== Constants::STATE['hit'] ){
            return TRUE;
        }
        if($this->getActionState()===Constants::STATE['split']){
            return TRUE;
        }
        return FALSE;
    }

    public function judgeSelectableInsurance(Dealer $dealer){
        if ($this->getHand()->getCardsNumber() !== 2) {
            return FALSE;
        }
        if(! $dealer->existFistCard('A')){
            return FALSE;
        }
        if($this->getInsurancesState() !== Constants::INSURANCES_STATE['unselected']){
            return FALSE;
        }
        return TRUE;
    }


    //insurance stateの変更
    public function setInsurancesState(string $insurancesState){
        $this->insurancesState = $insurancesState;
    }

    //insurance stateの取得
    public function getInsurancesState(){
        return $this->insurancesState;
    }

    // 手札を指定された枚数オープン
    // 手札を表示する
    public function setHandImgs(int $showNumber)
    {
        $this->handImgs = $this->hand->setHandImgs($showNumber);
    }

    public function getHandImgs(){
        return $this->hand->getHandImgs();
    }

    // 結果を保持
    public function setScore(string $score){
        $this->score = $score;
    }
    // 結果を取得
    public function getScore(){
        return $this->score;
    }

    public function onBlackJack(){
        $this->setActionState(Constants::STATE['blackJack']);
    }

    // 各アクションの処理　splitだけ処理が別
    public function takeSelectedAction($selectedAction){
        if(array_key_exists($selectedAction, Constants::STATE_CLASSES)){
            $className = Constants::STATE_CLASSES[$selectedAction];
            $stateClass = new $className();
            $stateClass->action($this,$this->cards);
        }
    }

    //次のアクションを選択する
    abstract public function selectAction();

    abstract public function selectInsurances();
}
