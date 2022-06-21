<?php

namespace blackJack;

class Dealer extends abstractPlayer
{
    public function __construct(private string $dealerName = 'dealer')
    {
        parent::__construct($dealerName);
    }


    // 一枚目に引数で選んだNumberがあるかかどうか
    public function existFistCard(string $cardNumber){
        return $this->getHand()->existFistCard($cardNumber);
    }

    // プレイヤーが次のアクションを選ぶときのルール
    public function selectAction()
    {
        $calcPlayerHandNumber = $this->getHand()->evaluateHand();
        if($calcPlayerHandNumber<17){
            return 'hit';
        }
        return 'stand';
    }

    public function getSelectionOfAction()
    {
        if($this->existFistCard('A') && $this->getActionState()===Constants::STATE["possibleBJ"]){
            return [
                'check' => 'check',
            ];
        }
        if($this->existFistCard('10') && $this->getActionState()===Constants::STATE["possibleBJ"]){
            return [
                'check' => 'check',
            ];
        }
        if(in_array('back.png',$this->getHandImgs())){
            return [
                'open' => 'Card Open',
            ];
        }
        return [
            'next' => 'Advance'
        ];
    }

    public function selectInsurances(){
        return;
    }
}
