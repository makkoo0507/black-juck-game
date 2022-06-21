<?php

namespace blackJack;

class CPU extends Player
{


    // CPUが次のアクションを選ぶときのルール
    public function selectAction(bool $ReEnter = FALSE)
    {
        if ($this->getHand()->getCardsNumber() === 2 && $this->getHand()->isSameNumberCards()) {
            return 'hit';
        }
        if ($this->getActionState() === Constants::RESULT['blackJack']) {
            return Constants::RESULT['blackJack'];
        }
        $calcPlayerHandNumber = $this->getHand()->evaluateHand();
        if ($calcPlayerHandNumber < 17) {
            return 'hit';
        }
        return 'stand';
    }


    public function getSelectionOfAction(Dealer $dealer)
    {
        if ($this->getHand()->getCardsNumber() === 2 && $dealer->existFistCard('A') && $this->getInsurancesState() === "unselected") {
            return [
                //post[ここ]=>画面表示
            ];
        }
        if ($this->getScore() === Constants::RESULT['blackJack']) {
            return [];
        }
        if ($this->getScore() === Constants::RESULT['burst']) {
            return [];
        }
        if ($this->getActionState() === Constants::STATE['stand']) {
            return [];
        }
        return [
            'next' => 'Advance'
        ];
    }

    public function getSelectionOfInsurances(Dealer $dealer)
    {
        if(!$this->judgeSelectableInsurance($dealer)){
            return [];
        }
        return [
            'next' => 'next'
        ];
    }

    // 選ばれた後の処理はplayerと一緒、選び方がそれぞれ異なる
    public function selectInsurances()
    {
        if ($this->getScore() === Constants::RESULT["blackJack"]) {
            return Constants::INSURANCES_STATE['noEven'];
        }
        if ($this->getScore() !== Constants::RESULT["blackJack"]) {
            return Constants::INSURANCES_STATE['noInsurance'];
        }
    }

    // インシュランスを選択するためのメッセージ
    public function insuranceOptions()
    {
        return [
            // Constants::INSURANCES_CHOICES['insurance']=>'',
            // Constants::INSURANCES_CHOICES['noInsurance']=>''
        ];
    }

    //イーブンを選択するためのメッセージ
    public function evenOptions()
    {
        return [
            // Constants::INSURANCES_CHOICES['even']=>'',
            // Constants::INSURANCES_CHOICES['noEven']=>''
        ];
    }
}
