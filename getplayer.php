<?php
include 'DB.php';

$id = $_REQUEST['id'] ;

$query = sprintf("SELECT * FROM players WHERE id='%s'",
mysql_real_escape_string($id));


$result = mysql_query($query);
$array = mysql_fetch_row($result);   
 
echo json_encode($array);

?>