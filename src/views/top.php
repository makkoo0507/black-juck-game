<main>
    <form action="play.php" method="post">
        <input type="hidden" name='start' value="TRUE">
        <div class="wrapper-name">
            <label for="name">プレイヤーの名前</label>
            <input type="text" name='player-name' id='name' value="Player">
        </div>
        <div class =wrapper-CPU-number>
            <label for="number-of-CPU">CPUの数</label>
            <select name="number-of-CPU" id="number-of-CPU">
                <option value="0">0</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
            </select>
        </div>
        <input class="play-btn" type="submit" value="PLAY">
    </form>
</main>
