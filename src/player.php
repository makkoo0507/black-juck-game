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
    // playerは標準入力からゲット
    // CPUプレイヤーは何かしらの規則に則りプレー
    //次のアクションのルールを設定
    private const ACTIONS =
    [
        'h'=>'Hit',
        's'=>'Stay',
        'su'=>'Surrender',
        'd'=>'Double Down',
        'sp'=>'Split',
        'i'=>'Insurance',
        'e'=>'Even Money'
    ];

    public function selectActionRule(bool $ReEnter = FALSE){
        $calcPlayerHandNumber = $this->calc->calculate($this->getMyHand());
        if (!$ReEnter) { // 記載ミスによる再記入の時は表示しない
            echo $this->getName() . 'の現在の得点は' . $calcPlayerHandNumber . PHP_EOL;
        }
        if(count($this->getMyHand())===2){
            echo 'h(hit) or s(stand) or d(double) :';
            $selectedAction = trim(fgets(STDIN));
            if (array_key_exists($selectedAction,self::ACTIONS)===false) {
                $ReEnter = TRUE;
                return $this->selectActionRule($ReEnter);
            }
            return self::ACTIONS[$selectedAction];
        }
        echo 'h(hit) or s(stand):';
        $selectedAction = trim(fgets(STDIN));
        if (array_key_exists($selectedAction,self::ACTIONS)===FALSE) {
            $ReEnter = TRUE;
            return $this->selectActionRule($ReEnter);
        }
        return self::ACTIONS[$selectedAction];
    }

    



}
