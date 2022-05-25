<?php

namespace blackJack;

// 各クラスを記載しているファイルの読み込み
require_once(__DIR__ . '/config.php');

class BlackJackGame
{
    // インスタンス用の変数を定義
    // トランプデッキの作成
    private Cards $cards;
    //　player(自分)の作成
    private Player $player;
    // 自分とCPUを格納する配列
    private array $players;
    // player(ディーラー)の作成
    private Dealer $dealer;
    // カードの扱いを行うインスタンス（シャッフル、配布など）
    private HandleCards $handler;
    // 計算機(手札の足し算,バーストの判定など)
    private CalculateHand $calculator;
    // ゲームの進行、マネジメントに関わるインスタンス
    private GameManager $manager;

    //各プレイヤーの結果を格納する配列
    // private array $result=[];

    // constructでインスタンスの作成
    public function __construct($name,$CPUNumber)
    {
        // CPUは3つまで、それ以上の選択があった場合ゲームのストップとエラー分の表示
        if($CPUNumber>4){
            echo "CPUは最大3台までです".PHP_EOL;
            exit;
        }
        // それぞれ、実際にインスタンスを作成
        $this->cards = new Cards();
        $this->handler = new HandleCards();
        $this->player = new Player($name);
        $this->dealer = new Dealer();
        $this->calculator = new CalculateHand();
        $this->createPlayersArray($CPUNumber);
        $this->manager = new GameManager($this->cards,$this->handler,$this->player,$this->players,$this->dealer,$this->calculator);
    }

    // playersに自分とCPUのインスタンスを格納
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
        $this->manager->start();
        //プレイヤーはアクションを選択して実行していく
        foreach ($this->players as $player) {
            while($player->getActionState()===STATE['hit']){
                $selectedAction = $player->selectAction();
                $this->manager->takeSelectedAction($player,$selectedAction);
            }
        }
        //全員Standの状態にになり、ディーラーがドローしていく処理
        $this->manager->DealerHit();
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


$game = new BlackJackGame('masato',1);
$game->playGame();
