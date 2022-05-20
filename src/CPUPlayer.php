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
    // 自分の手札
    private array $hands;
    // 手札を取得
    public function getMyHand()
    {
        return $this->hands;
    }
    // 手札にカードを一枚加える
    public function addCardToHand(string $card): array
    {
        $this->hands[] = $card;
        return $this->hands;
    }

    // ヒットするかステイするかを決定するルールをクラス毎で設定する
    // CPUプレイヤーは何かしらの規則に則りプレー

    public function selectActionRule()
    {
        $calcPlayerHandNumber = $this->calc->calculate($this->getMyHand());
        if($calcPlayerHandNumber<17){
            return 'hit';
        }
        return 'stay';
    }
}
