<?php

include('Player.php');
session_start();


if ($_REQUEST['type'] == "day") {
    if (!isset($_SESSION['savedPlayers'])) {
        $_SESSION['savedPlayers'] = array();
    }
    $savedPlayers = $_SESSION['savedPlayers'];
} else {
    if (!isset($_SESSION['restPlayers'])) {
        $_SESSION['restPlayers'] = array();
    }
    $savedPlayers = $_SESSION['restPlayers'];
}

/* ---------------- Vanliga spelare ----------------- */

foreach ($_REQUEST['ids'] as $requestId) {
    $existed = false;
    foreach ($savedPlayers as $player) {
        if ($player->id == $requestId) {
            $existed = true;
        }
    }
    if (!$existed) {
        if(!isset($_SESSION['playerArray'])){
            $_SESSION['playerArray'] = array();
        }
        foreach ($_SESSION['playerArray'] as $player) {
            if ($player->id == $requestId) {
                $savedPlayers[] = $player;
            }
        }
    }
}

if ($_REQUEST['type'] == "day") {
    $_SESSION['savedPlayers'] = $savedPlayers;
} else {
    $_SESSION['restPlayers'] = $savedPlayers;
}
$matchUt = json_encode($savedPlayers);
echo $matchUt;
?>
