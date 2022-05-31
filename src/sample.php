<?php

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

    private function addNumber(int $x,int $y){
        return $x+$y;
    }

    public function addAddNumber(int $z,int $u)
    {
        return $this->addNumber(1,1)+$z+$u;
    }
}

class Sam
{
    private Sample $sample;
    public function __construct()
    {
        $this->sample = new Sample();
        echo $this->sample->addAddNumber(1,1);
    }
}

$sam = new Sam();
// static なら$this->sample->addNumber も　Sample::addNumberとしても使える(publicがついても同じ) 。
//　static関数の中でインスタンス関数は使えない(使う方法はあるっぽい)。
//
//privateとstaticはprivateが強い


/**
 * 第二段階リファクタリング!!
 * カードクラスを作成して、pointの取得はカードクラスで行う
 *
 */



/**
 * 第一段階とりあえず作る！！
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
 * ディーラーがブラックジャックだったらその時点で終了
 * プレイヤーがブラックジャックの時は他のプレイヤーを待つか？→待つ
 * ディーラーの得点が10の時はブラックジャックか確認するか？　確認する
 *
 * ディーラーがAを持つ時、サレンダーとインシュランスの順序
 * インシュランスから聞かれて、選択したらディーラーオープンした
 * サレンダーはヒットorスタンドと同じタイミング
 */

/**
 * 第二段階 一つの関数で複数の処理を担っているものがあるので、以下の段階に区切っていく。
 * ポーカーの流れ
 * カードを配る、表示
 * プレイヤーがブラックジャックでないか確認
 * ディーラーがエースを持ってるか確認
 * インシュランス、イーブン、サレンダーの選択
 * ディーラーがブラックジャックでないか確認
 * 各プレーヤーstand、hit、double、spritの選択
 * ディーラーのカードオープン
 * 勝敗の確認
 * 結果発表
 */

/**
 * 第三段階　もう少しオブジェクト指向らしく書いていく。
 * abstractPlayerを継承でPlayerとDealer,Playerを継承でCPUという手順にする。
 *
 */

// とりあえず思いついたままに書こう！
//　一回必要だと思ったクラスは作る。まとめられるところは後でまとめる。
