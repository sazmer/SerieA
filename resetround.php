<?php
include('Player.php');
session_start();


//echo a single entry from the array

if (isset($_SESSION['lastRound'])) {
    $_SESSION['playerArray'] = array();
    foreach ($_SESSION['lastRound'] as $player) {
        $_SESSION['playerArray'][] = clone $player;
    }
}
$_SESSION['savedMatches'] = array();
$_SESSION['canReport'] = false;


$returnRound = json_encode($_SESSION['lastRound']);
echo $returnRound;

?> 