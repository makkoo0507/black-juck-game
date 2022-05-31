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
        private string $actionState = Config::STATE['hit'],
        private string $result = 'No results'
        ){}
    // 名前の取得
    public function getName(){
        return $this->name;
    }
    // 手札を取得
    public function getHand(){
        return $this->hand;
    }

    // デッキから一枚手札に加える
    public function drawCardFromDeck(PlayingCards $cards)
    {
        $drawnCard = $cards->drawnCard();
        $this->hand->addCardToHand($drawnCard);
    }

    //stateの変更
    public function changeActionState(string $action){
        $this->actionState = $action;
    }

    //stateの取得
    public function getActionState(){
        return $this->actionState;
    }

    // 手札を指定された枚数オープン
    // 手札を表示する
    public function showHand(int $showNumber): string
    {
        $showCards = $this->hand->showHand($showNumber);
        return $this->getName() . '手札' . '[' . implode(',', $showCards) . ']';
    }
    // 結果を保持
    public function changeResult(string $result){
        $this->result = $result;
    }
    // 結果を取得
    public function getResult(){
        return $this->result;
    }
    
    //次のアクションを選択する
    abstract public function selectAction();
    //insuranceを選択する
    abstract public function selectInsurance();
    //evenを選択する
    abstract public function selectEven();
}
