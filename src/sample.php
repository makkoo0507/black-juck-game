<?php
namespace blackJack;

class Sample
{
    private $number = 0;
    public function sample()
    {
        return 'sample';
    }
    public function getNumber(){
        return $this->number;
    }
}

$sample = new Sample;




/**
 * ブラックジャックゲームを作る
 * ゲームを実行するためのファイル blackJackGame.php start
 * カードデッキがある、カードをシャッフル、カードを配る
 * ディーラーがいる、プレイヤーがいる ディーラーもプレイヤーに含める
 * 最初のカードが配られた。
 * ブラックジャックなら終了（ステップ１ではこの過程はない）
 * 点数を計算して次のカードを引くか選択
 * 左からhit or stand を聞いていく。
 * hitを繰り返して　その中でburstしたら終わり。
 * standで止めたら、次のプレイヤー
 *　最後にディーラ
 * burstしたらそのプレイヤーだけ終了！　今は全体が終了してしまうので改良が必要
 * ブラックジャックのルール（ヒット、ステイ、ダブル...）→ディーラの行為handleCards(配る、プレイヤーの手札を表示する...)
 * 各ルールが適応される条件
 * ヒット　カードの合計が21以下　プレイヤーがヒットを選択
 * ステイ　プレイヤーがステイを選択
 * ダブル　カードの枚数が２枚　プレイヤーがダブルを選択
 */



// とりあえず思いついたままに書こう！
