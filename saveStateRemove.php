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

foreach ($_REQUEST['ids'] as $requestId) {
    foreach ($savedPlayers as $i => $player) {
        if ($player->id == $requestId) {
            unset($savedPlayers[$i]);
            if ($_REQUEST['removeBoard'] == "true") {
                foreach ($_SESSION['playerArray'] as $id => $player_) {
                    if ($player_->id === $requestId) {

                        $_SESSION['boardNumberCounter'][$player_->boardNumber - 1] = "falskt";
                       // print_r($_SESSION['boardNumberCounter']);
                        $player_->boardNumber = null;
                        unset($_SESSION['playerArray'][$id]);
                    }
                }
            }
        }
    }
}


if ($_REQUEST['type'] == "day") {
    $_SESSION['savedPlayers'] = $savedPlayers;
} else {
    $_SESSION['restPlayers'] = $savedPlayers;
}
?>
