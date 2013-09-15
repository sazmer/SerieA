<?php

include('Player.php');
session_start();

foreach ($_REQUEST['ids'] as $id) {
    foreach ($_SESSION['playerArray'] as $player) {

        if ($player->id == $id) {
            $player->rest++;
        }
    }
}
?>
 