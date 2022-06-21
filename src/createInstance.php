<?php
namespace blackJack;

// 各クラスを記載しているファイルの読み込み
require_once(__DIR__ . '/../lib/constants.php');

use blackJack\Constants;

require_once(__DIR__ . '/../lib/card.php');

use blackJack\Card;

require_once(__DIR__ . '/../lib/playingCards.php');
use blackJack\PlayingCards;


require_once(__DIR__ . '/../lib/hand.php');

use blackJack\Hand;

require_once(__DIR__ . '/../lib/abstractPlayer.php');

use blackJack\AbstractPlayer;

require_once(__DIR__ . '/../lib/player.php');

use blackJack\Player;

require_once(__DIR__ . '/../lib/CPU.php');

use blackJack\CPU;

require_once(__DIR__ . '/../lib/dealer.php');

use blackJack\Dealer;

require_once(__DIR__ . '/../lib/gameManager.php');

use blackJack\GameManager;



$title="Black Jack";

//ゲームに必要なインスタンスを作成

class CreateInstance
{
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
        $this->cards = new PlayingCards(Constants::SUITS, Constants::CARD_NUMBER);
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

    public function dealerOpenCardIsA(){

    }
    public function getCards(){
        return $this->cards;
    }

    public function getPlayers(){
        return $this->players;
    }
    public function getPlayer(){
        return $this->player;
    }
    public function getDealer(){
        return $this->dealer;
    }
    public function getManager(){
        return $this->manager;
    }
}
