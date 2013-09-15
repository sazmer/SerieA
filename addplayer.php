<?php

include('DB.php');

$fname = $_REQUEST['fname'];
$lname = $_REQUEST['lname'];
$sex = $_REQUEST['sex'];
$member = $_REQUEST['member'];

$checkNameQuery = ("SELECT * FROM players WHERE first_name='$fname' AND last_name='$lname'");
$checkResult = $mysqli->query($checkNameQuery);

if ($checkResult->num_rows > 0) {
    echo "fail";
} else {
    $query = ("INSERT INTO players (first_name, last_name, sex, wins, member) "
            . "VALUES ('$fname','$lname','$sex', 0, '$member')");
    $result = $mysqli->query($query);
    echo $mysqli->insert_id;
}
//execute the SQL query and return records
//$result = mysql_query("SELECT id, first_name, last_name, sex FROM players");
//fetch tha data from the database
?>
