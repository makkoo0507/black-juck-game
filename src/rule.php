<?php

namespace blackJack;

class Rule
{
    private const ACTIONS =
    [
        'hit'=>'Hit',
        'stay'=>'Stay',
        'surrender'=>'Surrender',
        'double'=>'Double Down',
        'split'=>'Split',
        'insurance'=>'Insurance',
        'even'=>'Even Money'
    ];
    public function __construct(private HandleCards $handler,private CalculateHand $calculator,private Cards $cards,private Dealer $dealer)
    {
    }

    // 次のアクションを選択させる
    //この関数の意味？？　
    // public function selectAction(AbstractPlayer $player)
    // {
    //     $selectedAction = $player->selectActionRule();
    //     return self::ACTIONS[$selectedAction];
    // }

    // HITかSTAYを選択した時の挙動
    public function takeSelectedAction(AbstractPlayer $who)
    {
        //各プレイヤーのルールに従ってアクションの取得
        $selectedAction = $who->selectActionRule();
        // ヒットした場合
        if ($selectedAction === self::ACTIONS['hit']) {
            $this->selectedHit($who);
        }
        if($selectedAction === self::ACTIONS['stay']){
            $this->selectedStay($who);
        }
        if($selectedAction === self::ACTIONS['surrender']){
            // $this->selectedSurrender($who);
        }
        if($selectedAction === self::ACTIONS['double']){
            // $this->selectedDouble($who);
        }
        if($selectedAction === self::ACTIONS['split']){
            // $this->selectedSplit($who);
        }
        if($selectedAction === self::ACTIONS['insurance']){
            // $this->selectedInsurance($who);
        }
        if($selectedAction === self::ACTIONS['even']){
            // $this->selectedEven($who);
        }
    }

    // stayが選択された時の
    public function selectedStay(AbstractPlayer $who){
        echo $who->getName().'はステイを選択しました';
        RunWithEnter();
    }

    // Hitが選択された時
    // バーストした時の挙動を分離する
    public function selectedHit(AbstractPlayer $who)
    {
        // playerにカードを一枚配る
        echo $who->getName().'はヒットを選択しました';
        RunWithEnter();
        $this->handler->dealCard($who, $this->cards);
        echo $this->handler->showHand($who, count($who->getMyHand()));
        RunWithEnter();
        //一枚引いた結果burstしたら終了
        if ($this->calculator->calculate($who->getMyHand()) === 'burst') {
            echo 'burstしました' . PHP_EOL;
            return;
        }
        //burstしなければ続行
        $this->takeSelectedAction($who);
    }



    public function JudgementResult(AbstractPlayer $player){
        $calcPlayerHandNumber = $this->calculator->calculate($player->getMyHand());
        $calcDealerHandNumber = $this->calculator->calculate($this->dealer->getMyHand());
        if ($calcPlayerHandNumber === 'burst') {
            return 'burst';
        }
        if ($calcDealerHandNumber === 'burst') {
            return 'win';
        }
        if ($calcPlayerHandNumber > $calcDealerHandNumber) {
            return 'win';
        }
        if ($calcPlayerHandNumber < $calcDealerHandNumber) {
            return'lose';
        }
        return 'push';
    }


}
