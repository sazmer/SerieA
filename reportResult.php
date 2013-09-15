<?php

include 'Player.php';
include 'DB.php';
session_start();

if (isset($_SESSION['canReport']) && $_SESSION['canReport']) {
    foreach ($_REQUEST['winnerIds'] as $winnerId) {
        if (isset($_SESSION['playerArray'])) {
            foreach ($_SESSION['playerArray'] as $player) {
                if ($player->getId() === $winnerId) {
                    $player->wins++;
                    $player->todays_wins++;
                }
            }


            $query = sprintf("UPDATE players SET wins = wins + 1 WHERE id=$winnerId");

            $mysqli->query($query);
        }
    }


    $_SESSION['canReport'] = false;

    $playerIds = array_merge($_REQUEST['loserIDs'], $_REQUEST['winnerIds']);
    foreach ($playerIds as $playerId) {
        if (isset($_SESSION['playerArray'])) {
            foreach ($_SESSION['playerArray'] as $player) {
                if ($player->getId() === $playerId) {
                    $player->rested_last = 0;
                    $player->played_games++;
                    
                }
            }
        }
    }
    $playerIdsImploded = implode(',', $playerIds);

    $query = sprintf("UPDATE players SET played_games = played_games + 1 WHERE id IN (" . $playerIdsImploded . ")");

    $mysqli->query($query);


    echo 'YES';
} else {
    echo 'NO';
}
?>
