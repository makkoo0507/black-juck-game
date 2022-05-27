<?php

namespace blackJack;

abstract class AbstractPlayer
{
    // インスタンスの生成時に名前を受け取る
    public function __construct(private string $name)
    {
    }
    // 自分の手札
    private array $hands;

    abstract public function changeActionState(string $action);

    abstract public function getActionState();
    // 名前の取得
    abstract public function getName();
    // 手札を取得
    abstract public function getMyHand();
    // 手札にカードを一枚加える
    abstract public function addCardToHand(Card $card);
    // 結果を保持
    abstract public function changeResult(string $result);
    // 結果を取得
    abstract public function getResult();
    //次のアクションを選択する
    abstract public function selectAction();
    //insuranceを選択する
    abstract public function selectInsurance();
    //evenを選択する
    abstract public function selectEven();
}
