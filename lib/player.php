<?php

namespace blackJack;

class Player extends abstractPlayer
{

    // プレイヤーが次のアクションを選ぶときのルール
    public function selectAction(bool $ReEnter = FALSE)
    {
        return $_POST['selectedAction'];
    }



    // 次のアクションを選択するためのメッセージ
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
        if ($this->getHand()->getCardsNumber() === 2 && $this->getHand()->isSameNumberCards()) {
            return [
                'hit' => 'Hit',
                'stand' => 'Stand',
                'surrender' => 'surrender',
                'double' => 'double',
                'split' => 'split'
            ];
        }
        if ($this->getHand()->getCardsNumber() === 2) {
            return [
                'hit' => 'Hit',
                'stand' => 'Stand',
                'surrender' => 'Surrender',
                'double' => 'Double'
            ];
        }
        if ($this->getHand()->getCardsNumber() > 2) {
            return [
                'hit' => 'Hit',
                'stand' => 'Stand',
            ];
        }
    }

    public function getSelectionOfInsurances(Dealer $dealer)
    {
        if (!$this->judgeSelectableInsurance($dealer)) {
            return [];
        }
        if ($this->getScore() === Constants::RESULT['blackJack']) {
            return [
                Constants::INSURANCES_STATE['even'],
                Constants::INSURANCES_STATE['noEven']
            ];
        }
        if ($this->getScore() !== Constants::RESULT['blackJack']) {
            return [
                Constants::INSURANCES_STATE['insurance'],
                Constants::INSURANCES_STATE['noInsurance']
            ];
        }
    }


    public function selectInsurances()
    {
        return $_POST["selectedOfPlayer"];
    }

    public function getResult(Dealer $dealer)
    {
        if ($this->getActionState() === Constants::STATE['surrender']) {
            return Constants::RESULT['surrender'];
        }
        if (
            $this->getActionState() === Constants::STATE['blackJack']
            && $dealer->getActionState() === Constants::STATE['blackJack']
            && $this->getInsurancesState() === Constants::INSURANCES_STATE['even']
        ) {
            return Constants::RESULT['even'];
        }
        if (
            $this->getActionState() === Constants::STATE['blackJack']
            && $dealer->getActionState() === Constants::STATE['blackJack']
            && $this->getInsurancesState() !== Constants::INSURANCES_STATE['even']
        ) {
            return Constants::RESULT['push'];
        }
        if ($this->getActionState() === Constants::STATE['blackJack']) {
            return Constants::RESULT['blackJack'];
        }
        if (
            $dealer->getActionState() === Constants::STATE['blackJack']
            && $this->getInsurancesState() === Constants::INSURANCES_STATE['insurance']
        ) {
            return Constants::RESULT['insurance'];
        }
        if ($this->getActionState() === Constants::STATE['burst']) {
            return Constants::RESULT['lose'];
        }
        if ($dealer->getActionState() === Constants::STATE['burst']) {
            return Constants::RESULT['win'];
        }
        if ($this->getScore() < $dealer->getScore()) {
            return Constants::RESULT['lose'];
        }
        if ($dealer->getScore() < $this->getScore() && $this->getActionState() === Constants::STATE['double']) {
            return Constants::RESULT['double'];
        }
        if ($dealer->getScore() < $this->getScore()) {
            return Constants::RESULT['win'];
        }
        if ($dealer->getScore() === $this->getScore()) {
            return Constants::RESULT['push'];
        }
    }
}
