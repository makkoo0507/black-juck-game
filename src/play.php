<?php

namespace blackJack;

require_once(__DIR__ . '/createInstance.php');

use blackJack\CreateInstance;

session_start();

// if (!isset($_SESSION['count'])) {
//     // キー'count'が登録されていなければ、1を設定
//     $_SESSION['count'] = 1;
// } else {
//     //  キー'count'が登録されていれば、その値をインクリメント
//     $_SESSION['count']++;
// }
// echo $_SESSION['count'] . "回目の訪問です。";


//ここから



function createInstances($playerName, $numberOfCPU)
{
    $objects = new CreateInstance($playerName, $numberOfCPU);
    $_SESSION['me'] = $objects->getPlayer();
    $_SESSION['cards'] = $objects->getCards();
    $_SESSION['players'] = $objects->getPlayers();
    $_SESSION['dealer'] = $objects->getDealer();
    $_SESSION['manager'] = $objects->getManager();
}

// スタートとリスタート
if (isset($_POST["start"])) {
    $_SESSION["playerName"] = $_POST["player-name"];
    $_SESSION["numberOfCPU"] = $_POST["number-of-CPU"];
    createInstances($_SESSION["playerName"], $_SESSION["numberOfCPU"]);
    $_SESSION['manager']->start($_SESSION['players'], $_SESSION['dealer'], $_SESSION['cards']);
}

if (isset($_POST['restart'])) {
    createInstances($_SESSION["playerName"], $_SESSION["numberOfCPU"]);
    $_SESSION['manager']->start($_SESSION['players'], $_SESSION['dealer'], $_SESSION['cards']);
}

// プレイヤーがブラックジャックのときの処理
foreach ($_SESSION['players'] as $player) {
    if ($player->getScore() === Constants::RESULT['blackJack']) {
        $player->onBlackJack();
    }
}

// ディーラーのopenカードがAのとき
//insuranceの選択
if ($_SESSION['manager']->isDealerFirstCardAOr10($_SESSION['dealer']) && isset($_POST["start"])  ) {
    $_SESSION['dealer']->setActionState(Constants::STATE['possibleBJ']);
}

if ($_SESSION['manager']->isDealerFirstCardAOr10($_SESSION['dealer']) && isset($_POST['restart'])  ) {
    $_SESSION['dealer']->setActionState(Constants::STATE['possibleBJ']);
}

if (isset($_POST['open'])) {
    if($_SESSION['manager']->isDealerCardTotal21($_SESSION['dealer'])){
        $_SESSION['dealer']->setActionState(Constants::STATE['blackJack']);
    }
    if(!$_SESSION['manager']->isDealerCardTotal21($_SESSION['dealer'])){
        $_SESSION['dealer']->setActionState(Constants::STATE['noBlackJack']);
    }
}
if(isset($_POST['check'])){
    if($_SESSION['manager']->isDealerCardTotal21($_SESSION['dealer'])){
        $_SESSION['dealer']->setActionState(Constants::STATE['blackJack']);
        $_SESSION['dealer']->setHandImgs(2);

    }
    if(!$_SESSION['manager']->isDealerCardTotal21($_SESSION['dealer'])){
        $_SESSION['dealer']->setActionState(Constants::STATE['noBlackJack']);
    }
}

// インシュランスが選択されたとき
if (isset($_POST["isSelectedInsurances"])) {
    foreach ($_SESSION['players'] as $player) {
        if ($player->getName() !== $_POST["playerName"]) {
            continue;
        }
        $player->setInsurancesState($player->selectInsurances());
    }
}
if(isset($_POST['open'])){
    $_SESSION['dealer']->setHandImgs(2);
    $_SESSION['dealer']->setActionState(Constants::STATE['hit']);
}
// アクションが選択されたとき　引数にプレイヤーを受け取る
if (isset($_POST['advance']) && !isset($_POST['check'])&& !isset($_POST['open']) ) {
    if ($_SESSION['dealer']->getName() === $_POST["playerName"]) {
        $_SESSION['manager']->takeSelectedAction($_SESSION['dealer'], $_SESSION['cards']);
    }
    foreach ($_SESSION['players'] as $player) {
        if ($player->getName() !== $_POST["playerName"]) {
            continue;
        }
        $_SESSION['manager']->takeSelectedAction($player, $_SESSION['cards']);
    }
}
if(isset($_POST['getResult'])){
    foreach($_SESSION['players'] as $player ){
        $result=$player->getResult($_SESSION['dealer']);
        $player->setActionState($result);
    }
    $_POST['result']='compleat';
}

$content = "views/play.php";
require "views/layout.php";
