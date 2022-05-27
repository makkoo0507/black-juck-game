<?php

namespace blackJack;

require_once(__DIR__ . '/config.php');

class GameManager
{
    private Action $actions;
    //blackJackGameクラスで作成したインスタンスたち取り込んでおく
    public function __construct(private PlayingCards $cards, private HandleCards $handler, private Player $player, private array $players, private Dealer $dealer, private CalculateHand $calculator)
    {
        $this->actions = new Action($cards, $handler, $dealer, $calculator);
    }
    // ゲーム開始時のカード配布とカード表示
    public function start()
    {
        echo 'ブラックジャックを開始！' . PHP_EOL;
        // カードを混ぜる
        $this->handler->shuffleCards($this->cards);

        //プレイヤーとディーラーにカードを２枚ずつ配布
        for ($i = 0; $i < 2; $i++) {
            foreach ($this->players as $player) {
                $this->handler->dealCard($player, $this->cards);
            }
            $this->handler->dealCard($this->dealer, $this->cards);
        }
    }

    public function openCards()
    {
        //プレイヤーとディーラーのカードを表示 (ディーラーは一枚だけ表示)
        foreach ($this->players as $player) {
            echo $this->handler->showHand($player, count($player->getMyHand())) . PHP_EOL;
            // プレイヤーがブラックジャックだった時の動作
            $this->takePlayerBlackJack($player);
        }
        // ディーラーのカード表示
        echo $this->handler->showHand($this->dealer, 1);
        RunWithEnter();
    }
    public function dealerOpenCardIsA()
    {
        //ディーラーの一枚目のカードがAだったとき、
        //プレイヤーがブラックジャックならevenを選択できる
        //プレイヤーがブラックジャックでないならinsuranceを選択できる
        if ($this->dealer->getMyHand()[0]->getCardNumbers() !== CARD_NUMBERS['A']) {
            return;
        }
        // プレイヤーはインシュランスかイーブンの選択しが生まれる
        foreach ($this->players as $player) {
            if ($player->getActionState() === STATE['blackJack']) {
                $selectedAction = $player->selectEven();
                $this->takeSelectedAction($player, $selectedAction);
            }
            if ($player->getActionState() !== STATE['blackJack']) {
                $selectedAction = $player->selectInsurance();
                $this->takeSelectedAction($player, $selectedAction);
            }
        }
    }

    public function isDealerCardTotal21()
    {
        if ($this->dealer->getMyHand()[0]->getCardNumbers() !== CARD_NUMBERS['A'] && $this->dealer->getMyHand()[0]->getCardNumbers() !== CARD_NUMBERS['10']) {
            return;
        }

        if ($this->calculator->calculate($this->dealer->getMyHand()) === RESULT['blackJack']) {
            $this->dealer->changeActionState(STATE['blackJack']);
            echo 'ディーラーの' . RESULT['blackJack'] . $this->handler->showHand($this->dealer, count($this->dealer->getMyHand())) . PHP_EOL;
            RunWithEnter();
            $this->gameSet();
            exit;
        }
        
        //ディーラの二枚目でブラックジャックじゃなかった場合
        if ($this->calculator->calculate($this->dealer->getMyHand()) !== RESULT['blackJack']) {
            echo 'ディーラーは No BlackJack';
            RunWithEnter();
            return;
        }
    }


    public function PlayerDraw()
    {
        foreach ($this->players as $player) {
            while ($this->stateToContinue($player)) {
                $selectedAction = $player->selectAction();
                $this->takeSelectedAction($player, $selectedAction);
            }
        }
    }

    public function stateToContinue(AbstractPlayer $player):bool
    {
        if($player->getActionState() === STATE['blackJack']){
            return FALSE;
        }
        if($player->getActionState() === STATE['burst']){
            return FALSE;
        }
        if($player->getActionState() === STATE['stand']){
            return FALSE;
        }
        if($player->getActionState() === STATE['surrender']){
            return FALSE;
        }
        return TRUE;
    }


    public function takePlayerBlackJack(AbstractPlayer $player){
        if ($this->calculator->calculate($player->getMyHand()) === RESULT['blackJack']) {
            $player->changeActionState(STATE['blackJack']);
            echo RESULT['blackJack'];
            RunWithEnter();
        }
    }


    public function takeSelectedAction(AbstractPlayer $who, $selectedAction)
    {
        if ($selectedAction === ACTIONS['hit']) {
            $this->actions->selectedHit($who);
            return;
        }
        if ($selectedAction === ACTIONS['stand']) {
            $this->actions->selectedStand($who);
            return;
        }
        if ($selectedAction === ACTIONS['surrender']) {
            $this->actions->selectedSurrender($who);
            return;
        }
        if ($selectedAction === ACTIONS['double']) {
            $this->actions->selectedDouble($who);
            return;
        }
        if ($selectedAction === ACTIONS['split']) {
            $splitPlayers = $this->actions->selectedSplit($who);
            foreach ($splitPlayers as $player) {
                while ($player->getActionState() === STATE['hit']) {
                    $selectedAction = $player->selectAction();
                    $this->takeSelectedAction($player, $selectedAction);
                }
            }
            array_splice($this->players, array_search($who, $this->players), 1, $splitPlayers);
            return;
        }
        if ($selectedAction === ACTIONS['insurance']) {
            $this->actions->selectedInsurance($who);
            return;
        }
        if ($selectedAction === ACTIONS['noInsurance']) {
            echo $who->getName() . 'はinsuranceしません';
            RunWithEnter();
            return;
        }
        if ($selectedAction === ACTIONS['even']) {
            $this->actions->selectedEven($who);
            return;
        }
        if ($selectedAction === ACTIONS['noEven']) {
            echo $who->getName() . 'はevenしません';
            RunWithEnter();
            return;
        }
    }

    //デーラーのターン　ディーラのカードオープン
    public function DealerDraw()
    {
        echo 'dealerの手札オープン';
        RunWithEnter();
        echo $this->handler->showHand($this->dealer, count($this->dealer->getMyHand())) . '得点' . $this->calculator->calculate($this->dealer->getMyHand());
        RunWithEnter();

        $isAllPlayersBlackJack=TRUE;
        foreach($this->players as $player){
            if($player->getActionState()!==STATE['blackJack']){
                $isAllPlayersBlackJack=FALSE;
            }
        }
        if($isAllPlayersBlackJack){
            $this->takeSelectedAction($this->dealer, ACTIONS['stand']);
            return;
        }
        //ディーラーの得点が17以上になるまでカードを引く
        while ($this->dealer->getActionState() === STATE['hit']) {
            $selectedAction = $this->dealer->selectAction();
            $this->takeSelectedAction($this->dealer, $selectedAction);
        }
    }


    // 試合結果出力
    public function gameSet()
    {
        foreach ($this->players as $player) {
            $this->calculator->JudgementResult($this->dealer, $player);
        }

        foreach ($this->players as $player) {
            echo $player->getName() . ':' . $player->getResult() . PHP_EOL;
        }
    }
}
