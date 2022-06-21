<?php

namespace blackJack;

class GameManager
{

    // ゲーム開始時のカード配布とカード表示
    public function start($players,Dealer $dealer,PlayingCards $cards)
    {
        // カードを混ぜる
        $cards->shuffleCards();

        //プレイヤーとディーラーにカードを２枚ずつ配布
        for ($i = 0; $i < 2; $i++) {
            foreach ($players as $player) {
                $this->drawCard($player,$cards);
                $player->setHandImgs($player->getHand()->getCardsNumber());
                $playerHandScore = $player->getHand()->evaluateHand();
                $player->setScore($playerHandScore);
            }
            $this->drawCard($dealer,$cards);
            $dealer->setHandImgs((1));
            $dealerHandScore = $_SESSION['dealer']->getHand()->evaluateHand();
            $dealer->setScore($dealerHandScore);
        }
    }

    public function drawCard(AbstractPlayer $player,PlayingCards $cards){
        $player->getHand()->addCardToHand($cards->getCards()[0]);
        $cards->drawnCard();
    }


    public function needsSelectInsurances(Dealer $dealer, Player $player)
    {
        if (!$dealer->existFistCard('A')) {
            return FALSE;
        }
        if ($player()->getInsurancesState() !== Constants::INSURANCES_STATE['unselected']) {
            return FALSE;
        }
        if (isset($_POST['selectedAction'])) {
            return FALSE;
        }
        return TRUE;
    }

    public function isDealerFirstCardAOr10(Dealer $dealer)
    {
        if (!$dealer->existFistCard('A') && !$dealer->existFistCard('10')) {
            return FALSE;
        }
        if ($dealer->existFistCard('A') || $dealer->existFistCard('10')) {
            return TRUE;
        }
    }

    public function getPlayerToAction(array $players,Dealer $dealer){
        if($this->getPlayerToSelectInsurance($players, $dealer)){
            return NULL;
        }
        if($dealer->getActionState() === Constants::STATE['possibleBJ']){
            return $dealer;
        }
        foreach($players as $player){
            if($player->judgeContinueAction()){
                return $player;
            }
        }
        return $dealer;

    }
    public function getPlayerToSelectInsurance(array $players,Dealer $dealer){
        foreach($players as $player){
            if($player->judgeSelectableInsurance($dealer)){
                return $player;
            }
        }
        return NULL;
    }

    public function takeSelectedAction(AbstractPlayer $player,PlayingCards $cards)
    {
        $selectedAction = $player->selectAction();
        $actionFuncs = [
            "hit"=>"takeHit",
            "stand"=>"takeStand",
            "surrender"=>"takeSurrender",
            "double"=>"takeDouble",
            "split"=>"takeSplit"
        ];
        $funcName= $actionFuncs[$selectedAction];
        $this->$funcName($player,$cards);
    }

    public function takeHit(AbstractPlayer $player, PlayingCards $cards)
    {
        $player->setActionState(Constants::STATE['hit']);

        $this->drawCard($player,$cards);
        $player->setHandImgs($player->getHand()->getCardsNumber());
        $playerHandScore = $player->getHand()->evaluateHand();
        $player->setScore($playerHandScore);

        if ($player->getScore() === Constants::RESULT['burst']) {
            $player->setActionState(Constants::STATE['burst']);
        }
    }
    public function takeStand(AbstractPlayer $player)
    {
        $player->setActionState(Constants::STATE['stand']);
    }
    public function takeSurrender(AbstractPlayer $player)
    {
        $player->setActionState(Constants::STATE['surrender']);
    }
    public function takeDouble(AbstractPlayer $player, PlayingCards $cards)
    {
        $player->setActionState(Constants::STATE['double']);
        $this->drawCard($player,$cards);
        $player->setHandImgs($player->getHand()->getCardsNumber());
        $playerHandScore = $player->getHand()->evaluateHand();
        $player->setScore($playerHandScore);

        if ($player->getScore() === Constants::RESULT['burst']) {
            $player->setActionState(Constants::STATE['burst']);
        }
    }

    public function takeSplit(AbstractPlayer $player)
    {
        $player->setActionState(Constants::STATE['split']);
        $playerClass = $player::class;
        $player1 = new $playerClass($player->getName() . '1');
        $player2 = new $playerClass($player->getName() . '2');

        foreach ([$player1, $player2] as $spritPlayer) {
            $spritPlayer->getHand()->addCardToHand($player->getHand()->getCardInHand(array_search($spritPlayer, [$player1, $player2])));

            $this->drawCard($spritPlayer,$_SESSION['cards']);
            $spritPlayer->setHandImgs($spritPlayer->getHand()->getCardsNumber());
            $playerHandScore = $spritPlayer->getHand()->evaluateHand();
            $spritPlayer->setScore($playerHandScore);
        }
        array_splice($_SESSION['players'], array_search($player, $_SESSION['players']), 1, [$player1, $player2]);
    }



    //ディーラーの手札がブラックジャックか確認
    public function isDealerCardTotal21(Dealer $dealer)
    {
        if ($dealer->getHand()->evaluateHand() === Constants::RESULT['blackJack']) {
            return TRUE;
        }
        return FALSE;
    }


    // プレイヤーが次のアクションを選択できるか、アクションが終了かSTATEの判定
    public function stateToContinue(Player $player): bool
    {
        $className = Constants::STATE_CLASSES[$player->getActionState()];
        $stateClass = new $className();
        return $stateClass->needsSelectAction();
    }

    // 試合結果出力

    public function needsShowResult(Dealer $dealer){
        if(isset($_POST['result'])){
            return FALSE;
        }
        if($dealer->getActionState()===Constants::STATE['blackJack']){
            return TRUE;
        }
        if($dealer->getActionState()===Constants::STATE['burst']){
            return TRUE;
        }
        if($dealer->getActionState()===Constants::STATE['stand']){
            return TRUE;
        }
        return FALSE;
    }


    public function gameSet(array $players,Dealer $dealer)
    {
        return;
    }
}
