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
    // 名前の取得
    abstract public function getName();
    // 手札を取得
    abstract public function getMyHand();
    // 手札にカードを一枚加える
    abstract public function addCardToHand(string $card);
    //次のアクションを選択する
    abstract public function selectActionRule();
}
