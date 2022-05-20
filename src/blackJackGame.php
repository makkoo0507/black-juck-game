<?php

namespace blackJack;

// 各クラスを記載しているファイルの読み込み
require_once(__DIR__ . '/cards.php');

use blackJack\Cards;

require_once(__DIR__ . '/player.php');

use blackJack\Player;

require_once(__DIR__ . '/CPUPlayer.php');

use blackJack\CPUPlayer;

require_once(__DIR__ . '/dealer.php');

use blackJack\Dealer;

require_once(__DIR__ . '/calculateHand.php');

use blackJack\CalculateHand;

require_once(__DIR__ . '/handleCards.php');

use blackJack\HandleCards;

require_once(__DIR__ . '/progress.php');

use blackJack\Progress;

require_once(__DIR__ . '/rule.php');

use blackJack\rule;


class BlackJackGame
{
    // インスタンス用の変数を定義
    private Cards $cards;
    private Player $player;
    private array $players;
    private Dealer $dealer;
    private HandleCards $handler;
    private CalculateHand $calculator;
    private Progress $progress;
    private Rule $rule;

    // constructでインスタンスの作成
    public function __construct($name,$CPUNumber)
    {
        if($CPUNumber>4){
            echo "CPUは最大3台までです".PHP_EOL;
            exit;
        }
        $this->cards = new Cards();
        $this->handler = new HandleCards();
        $this->player = new Player($name);
        $this->dealer = new Dealer();
        $this->calculator = new CalculateHand();
        $this->createPlayersArray($CPUNumber);
        $this->rule = new Rule($this->handler,$this->calculator,$this->cards,$this->dealer);
    }

    public function createPlayersArray(int $CPUNumber)
    {
        $this->players[] = $this->player;
        for ($i = 0; $i < $CPUNumber; $i++) {
            $CPUPlayer = new CPUPlayer('CPU' . $i);
            $this->players[] = $CPUPlayer;
        }
    }

    // ゲームの開始
    public function playGame()
    {
        // 最初のカード配布とカードopen
        $this->start();

        // プレイヤー毎にアクションを選択　選択する関数はrule、選択のルールは各プレイヤークラス
          //選択したアクションを実行していく

        //プレイヤーはHitをしていく
        foreach ($this->players as $player) {
            $this->rule->takeSelectedAction($player);
        }
        //全員Stayの選択になったときの処理
        $this->DealerHit();
        //結果発表
        $this->gameSet();
    }

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

        //プレイヤーとディーラーのカードを表示 (ディーラーは一枚だけ表示)
        foreach ($this->players as $player) {
            echo $this->handler->showHand($player, count($player->getMyHand())) . PHP_EOL;
        }
        echo $this->handler->showHand($this->dealer, 1) . PHP_EOL;
    }

    // Stayの時の挙動
    //デーラーのターン　ディーラのカードオープン
    public function DealerHit()
    {
        echo 'dealerの手札オープン';
        RunWithEnter();
        echo $this->handler->showHand($this->dealer, count($this->dealer->getMyHand())) . '得点' . $this->calculator->calculate($this->dealer->getMyHand());
        RunWithEnter();
        //ディーラーの得点が17以上になるまでカードを引く
        // $this->drawCardUntilOver17();
        $this->rule->takeSelectedAction($this->dealer,$this);
    }

    // 試合結果出力
    public function gameSet()
    {
        $results=[];
        foreach($this->players as $player){
            $results[$player->getName()] = $this->rule->JudgementResult($player);
        }

        foreach($results as $name => $result){
            echo $name.':'.$result .PHP_EOL;
        }
    }

}

// Enterを押すまで表示を止めるための関数
function RunWithEnter()
{
    if (trim(fgets(STDIN)) === '') {
    }
}


$game = new BlackJackGame('masato',1);
$game->playGame();
