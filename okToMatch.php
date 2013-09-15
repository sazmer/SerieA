<?php


session_start();

   if($_SESSION['canReport'] == false){
       echo 'false';
   }else{
       echo 'true';
   }



?>
