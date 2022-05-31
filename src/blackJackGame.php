<?php

namespace blackJack;

// 各クラスを記載しているファイルの読み込み
require_once(__DIR__ . '/config.php');

class BlackJackGame
{
    // インスタンス用の変数を定義
    // トランプデッキの作成
    private PlayingCards $cards;
    //　player(自分)の作成
    private Player $player;
    // 自分とCPUを格納する配列
    private array $players;
    // player(ディーラー)の作成
    private Dealer $dealer;
    // ゲームの進行、マネジメントに関わるインスタンス
    private GameManager $manager;

    // constructでインスタンスの作成
    public function __construct($name, $CPUNumber)
    {
        // CPUは3つまで、それ以上の選択があった場合ゲームのストップとエラー分の表示
        if ($CPUNumber > 4) {
            echo "CPUは最大3台までです" . PHP_EOL;
            exit;
        }
        // それぞれ、実際にインスタンスを作成
        $this->cards = new PlayingCards(Config::SUITS, Config::CARD_NUMBER);
        $this->player = new Player($name);
        $this->dealer = new Dealer();
        $this->createPlayersArray($CPUNumber);
        $this->manager = new GameManager($this->cards, $this->player, $this->players, $this->dealer);
    }

    // playersに自分とCPUのインスタンスを格納
    public function createPlayersArray(int $CPUNumber)
    {
        $this->players[] = $this->player;
        for ($i = 0; $i < $CPUNumber; $i++) {
            $CPU = new CPU('CPU' . $i);
            $this->players[] = $CPU;
        }
    }

    // ゲームの開始
    public function playGame()
    {
        // 最初のカード配布とカードopen プレイヤーがブラックジャックでないか確認
        $this->manager->start();
        // カードopen プレイヤーがブラックジャックでないか確認
        $this->manager->openCards();
        //ディーラーがエースを持ってるか場合　インシュランス、イーブン、選択
        $this->manager->dealerOpenCardIsA();
        // ディーラーがブラックジャックでないか確認
        $this->manager->isDealerCardTotal21();
        //各プレーヤーstand、hit、surrender、double、spritの選択
        $this->manager->PlayerDraw();
        //全員Standの状態にになり、ディーラーがドローしていく処理
        $this->manager->DealerDraw();
        //結果発表
        $this->manager->gameSet();
    }
}

// Enterを押すまで表示を止めるための関数
function RunWithEnter()
{
    if (trim(fgets(STDIN)) === '') {
    }
}

// BlackJackGameのインスタンスを作成時にプレイヤーネームとCPUの数を指定
$game = new BlackJackGame('masato', 1);
$game->playGame();
