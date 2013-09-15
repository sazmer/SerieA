<?php

session_start();

if(isset($_SESSION['savedMatches'])){
    echo json_encode($_SESSION['savedMatches']);
}

 
?>
