<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo $title?></title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/stylesheet.css">
    <!-- <link href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" rel="stylesheet"> -->
</head>

<body>
    <header>
    <h1 class="title"><a href="top.php"><img src="./img/headerLogo.png"></a></h1>
    </header>
    <div class="wrapper-content">
        <?php include $content?>
    </div>
</body>
</html>
