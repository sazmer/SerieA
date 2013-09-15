<?php

include 'Player.php';
session_start();

include('DB.php');
$playerId = $_REQUEST['id'];
$query = "SELECT * FROM players WHERE id IN (" . $playerId . ")";

$fname = $_REQUEST['fname'];
$lname = $_REQUEST['lname'];
$sex = $_REQUEST['sex'];
$wins = $_REQUEST['wins'];
$rests = $_REQUEST['rests'];
$id = $_REQUEST['id'];
$member = $_REQUEST['member'];
$played_games = $_REQUEST['played_games'];
$query = ("UPDATE players SET first_name=\"$fname\", last_name=\"$lname\", sex=\"$sex\", wins=\"$wins\", member=\"$member\", played_games=\"$played_games\" "
        . " WHERE id=\"$id\"");
$result = $mysqli->query($query);


$playerId = $mysqli->insert_id;

if (isset($_SESSION['playerArray'])) {
    $playerArrayStats = $_SESSION['playerArray'];
    $player = getPlayerFromID($playerArrayStats, $id);
    if (isset($player)) {
        //make changes to player
        $player->firstname = $fname;
        $player->lastname = $lname;
        $player->gender = $sex;
        $player->wins = $wins;
        $player->rest = $rests;
    }
} else {
    $playerArrayStats[] = new Player($id, $fname, $lname, $sex, $wins, $rests);
    $_SESSION['playerArray'] = $playerArrayStats;
}



$_SESSION['playerArray'] = $playerArrayStats;

echo "Success!";

function getPlayerFromID($playerArray, $playerID) {
    foreach ($playerArray as $player) {
        if ($player->getID() == $playerID) {
            return $player;
        }
    }
    //echo $playerID . 'SPELARID!';
}

?>
