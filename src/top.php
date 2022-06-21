<?php

session_start();
session_destroy();

$title="Black Jack";
$content = "views/top.php" ;
require "views/layout.php";
