<?php

include 'Player.php';
session_start();

include('DB.php');
$playerId = $_REQUEST['id'];
$query = "SELECT * FROM players WHERE id IN (" . $playerId . ")";


$result = $mysqli->query($query);
$gottenPlayer = $result->fetch_row();

if (isset($_SESSION['playerArray'])) {
    $playerArrayStats = $_SESSION['playerArray'];
    $player = getPlayerFromID($playerArrayStats, $playerId);
}

if (isset($player)) {
    $gottenPlayer[] = $player->rest;
    
}else{
    $gottenPlayer[] = 0;
}

$result = json_encode($gottenPlayer);
echo $result;

function getPlayerFromID($playerArray, $playerID) {
    foreach ($playerArray as $player) {
        if ($player->getID() == $playerID) {
            return $player;
        }
    }
    //echo $playerID . 'SPELARID!';
}

?>
