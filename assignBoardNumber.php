<?php

include('DB.php');
include('Player.php');

session_start();

if (!isset($_SESSION['boardNumberCounter'])) {
    $_SESSION['boardNumberCounter'] = array();
}
if (!isset($_SESSION['playerArray'])) {
    $_SESSION['playerArray'] = array();
}

$playerArrayStats = $_SESSION['playerArray'];

header('Content-type: application/json');
//$playerIds = explode(',', $_REQUEST['ids']);
//$playerIdsImploded = implode(',', $playerIds);

$query = "SELECT * FROM players WHERE id IN (" . $_REQUEST['ids'] . ")";

$result = $mysqli->query($query);

$newlyInserted = array();

while ($row = $result->fetch_row()) {

    $playerExists = false;
    foreach ($playerArrayStats as $player) {
        if ($player->id == $row[0]) {
            $playerExists = true;
            if ($player->boardNumber == null) {
                $insertedPlayer = insertBoardNumber($player);
                $newlyInserted[] = $insertedPlayer->boardNumber;

            } else {
                $newlyInserted[] = $player->boardNumber;

           
            }
        }
    }
    if (!$playerExists) {
        $newPlayer = new Player($row[0], $row[1], $row[2], $row[3], $row[4],$row[6]);
        $insertedPlayer = insertBoardNumber($newPlayer);
        $newlyInserted[] = $insertedPlayer->boardNumber;
        $playerArrayStats[] = $insertedPlayer;

    }
}
$result->close();
$mysqli->close();
$_SESSION['playerArray'] = $playerArrayStats;

$newlyInserted = json_encode($newlyInserted);
echo $newlyInserted;

function insertBoardNumber($player) {
    $boardNumbers = $_SESSION['boardNumberCounter'];
    
    $foundSpace = false;
    foreach ($boardNumbers as $number => $player_) {

        if ($player_ == "falskt" && $foundSpace == false) {
            $player->boardNumber = $number + 1;
            $boardNumbers[$number] = $player;
             $foundSpace = true;
          
        }
    }
    if (!$foundSpace) {
        $player->boardNumber = count($boardNumbers) + 1;
        $boardNumbers[] = $player;
   
    }
    $_SESSION['boardNumberCounter'] = $boardNumbers;
    return $player;
}

?>
