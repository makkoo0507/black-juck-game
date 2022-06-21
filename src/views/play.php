<main>
    <div class="wrapper-play-all">
        <form action="play.php" method="post">
            <input class="restart" type="submit" name="restart" value="Restart">
        </form>
        <div class='dealer-space'>
            <p>
                <span>
                    <?= $_SESSION['dealer']->getName() ?>
                </span>
                <? if (!in_array('back.png', $_SESSION['dealer']->getHandImgs())) : ?>
                    <span class="score">
                        <?= $_SESSION['dealer']->getScore() ?>
                    </span>
                <? endif ?>
                <span class="actionState">
                    <?= $_SESSION['dealer']->getActionState() ?>
                </span>
            </p>
            <ul class='hand'>
                <? foreach ($_SESSION['dealer']->getHandImgs() as $number => $cardImg) : ?>
                    <li class='card' style='<?= 'right:' . 25 * $number . 'px;' ?>'> <img src="./img/<?= $cardImg ?>"></li>
                <? endforeach ?>
            </ul>
        </div>
        <div class='players-space'>
            <? foreach ($_SESSION['players'] as $player) : ?>
                <div class='player-space'>
                    <p>
                        <span>
                            <?= $player->getName() ?>
                        </span>
                        <span class="score">
                            <?= $player->getScore() ?>
                        </span>
                        <span class="actionState">
                            <?= $player->getActionState() ?>
                        </span>
                        <span class="insuranceState">
                            <?= $player->getInsurancesState() ?>
                        </span>
                    </p>
                    <div class='hand-wrapper'>
                        <ul class='hand'>
                            <? foreach ($player->getHandImgs() as $number => $cardImg) : ?>
                                <li class='card' style='<?= 'right:' . 25 * $number . 'px;' ?>'><img src="./img/<?= $cardImg ?>"></li>
                            <? endforeach ?>
                        </ul>
                    </div>
                </div>
            <? endforeach ?>
        </div>
        <div class="choices-space">
            <div class="choices-wrapper">
                <!-- インシュランスに関する選択 -->
                <? if ($_SESSION['manager']->getPlayerToSelectInsurance($_SESSION['players'], $_SESSION['dealer']) !== NULL) : ?>
                    <? $player = $_SESSION['manager']->getPlayerToSelectInsurance($_SESSION['players'], $_SESSION['dealer']) ?>
                    <ul class="insurance action-list">
                        <? foreach ($player->getSelectionOfInsurances($_SESSION['dealer']) as $selection) : ?>
                            <li class="insurance-item">
                                <form method="post" action="play.php">
                                    <input type="hidden" name="isSelectedInsurances" value="TRUE">
                                    <input type="hidden" name="playerName" value="<?= $player->getName() ?>">
                                    <input type="submit" name="selectedOfPlayer" value="<?= $selection ?>">
                                </form>
                            </li>
                        <? endforeach ?>
                    </ul>
                <? endif ?>
                <!-- アクションに関する選択 -->
                <? if (!isset($_POST['result']) && !$_SESSION['manager']->needsShowResult($_SESSION['dealer']) && !$_SESSION['manager']->getPlayerToSelectInsurance($_SESSION['players'], $_SESSION['dealer']) ) : ?>
                    <? $player = $_SESSION['manager']->getPlayerToAction($_SESSION['players'], $_SESSION['dealer']) ?>
                    <ul class="action-list">
                        <? foreach ($player->getSelectionOfAction($_SESSION['dealer']) as $actionName => $view) : ?>
                            <li class="action-item">
                                <form method="post" action="play.php">
                                    <input type="hidden" name="advance" value="TRUE">
                                    <input type="hidden" name="<?= $actionName ?>" value="TRUE">
                                    <input type="hidden" name="playerName" value="<?= $player->getName() ?>">
                                    <input type="hidden" name="selectedAction" value="<?= $actionName ?>">
                                    <input type="submit" name="submit" value="<?= $view ?>">
                                </form>
                            </li>
                        <? endforeach ?>
                    </ul>
                <? endif ?>
                <!-- 結果発表 -->
                <? if ($_SESSION['manager']->needsShowResult($_SESSION['dealer'])) : ?>
                    <ul class="action-list">
                        <li class="action-item">
                            <form method="post" action="play.php">
                                <input type="hidden" name="getResult" value="TRUE">
                                <input type="submit" name="submit" value="結果">
                            </form>
                        </li>

                    </ul>
                <? endif ?>
            </div>
        </div>
    </div>
</main>
