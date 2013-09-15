<?php
include('Player.php');
session_start();


$timeOut = 12 * 3600; //Tid innan session expirar i sekunder.
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $timeOut)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
$user = "kristiina"; //Username to protected page. 
$pass = "4dm1n!"; //Password to protected page. 
$user1 = "ostersund";
$pass1 = "0st3rsund!";
$user2 = "emil";
$pass2 = "Huu5k0!";
if(($_POST['username'] == $user &&  $_POST['password'] == $pass)||($_POST['username'] == $user1 &&  $_POST['password'] == $pass1) ||($_POST['username'] == $user2 &&  $_POST['password'] == $pass2)) 
{ 
$_SESSION['logged_in'] = "true"; 
$_SESSION['username'] = $_POST['username']; //Saves your username. 
echo('<meta name="refresh" content="0; url=page.php" />'); //Refreshes the page. 
} 


if(!isset($_SESSION['logged_in'])) 
  { 
header('Location: login.php');
    exit();
  } 

?>
<html>
    <head>
        <title>Valkommen till Fyrisbeach</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="shortcut icon" href="favicon.ico" >

<!--        <link href="css/style.css" rel="stylesheet" type="text/css">-->
    <link rel="stylesheet" href="css/style.css"
       >
    
    
<!--        <link rel="stylesheet" href="css/mobile.css"
       media="only screen and (max-device-width: 480px)">-->

        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>


        <script src="script/main.js"></script>
        <script type="text/javascript">
            function newPopup(url) {
                popupWindow = window.open(
                        url, 'popUpWindow', 'height=700,width=800,left=10,top=10,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes');
            }
        </script>
    </head>

    <body>
        <form>




            <div id="matchingContainer">
                <div id="fieldsLeft">
                    <div id="allDatabase">
                        Hela databasen:<br>
                        <SELECT id="all" name="all" MULTIPLE SIZE=20><!--ALLA MEDLEMAR -->
                            <?php
                            include('DB.php');
                            $query = sprintf("SELECT * FROM players");
                            $result = $mysqli->query($query);
                            $arrayToPrint = array();
                            while ($row = $result->fetch_row()) {
                                $playerA = array();
                                $playerA [] = $row[0];
                                $playerA [] = $row[1];
                                $playerA [] = $row[2];
                                $arrayToPrint [] = $playerA;
                            }
                            usort($arrayToPrint, "sortByFname");

                            function sortByFname($p1, $p2) {
                                if ($p1[1] > $p2[1]) {
                                    return 1;
                                } else if ($p1[1] < $p2[1]) {
                                    return -1;
                                } else {
                                    return 0;
                                }
                            }

                            foreach ($arrayToPrint as $playerB) {
                                echo '<option value="' . $playerB[0] . '">' . $playerB[1] . " " . $playerB[2] . '</option>';
                            }
                            /*
                              while ($row = $result->fetch_row()) {

                              echo '<option value="' . $row[0] . '">' . $row[1] . " " . $row[2] . '</option>';
                              }
                             * */
                            ?>
                        </SELECT>
                    </div>
                    <div class="arrowButtons">
                        <input type="button" name="toDay" value="&rarr;">

                        <br>
                        <input type="button" id="toAll" name="toAll" value="&larr;">
                    </div>
                    <div id="thisRound">
                        Denna runda: <a style="margin-left:30px;" href="#" id="sortByNum">Nr</a> || <a href="#" id="sortByFname">Förnamn</a><br>
                        <SELECT id="day" name="day" MULTIPLE SIZE=20><!--DAGENS SPELARE -->
                            <?php
                            if (isset($_SESSION['savedPlayers']) && count($_SESSION['savedPlayers']) > 0) {
                                foreach ($_SESSION['savedPlayers'] as $player) {
                                    echo '<option value="' . $player->id . '">' . $player->boardNumber . " " . $player->firstname . " " . $player->lastname . '</option>';
                                }
                            }
                            ?>
                        </SELECT>
                    </div>

                    <div class="arrowButtons arrowRight">
                        <input type="button" name="fromRest" value="&#8593;">
                        <input type="button" name="toRest" value="&#8595;">
                    </div>

                    <div id="willRest">
                        Vilande spelare:<br>
                        <SELECT id="rest" name="rest" MULTIPLE SIZE=5><!--VILANDE SPELARE -->
                            <?php
                            if (isset($_SESSION['restPlayers']) && count($_SESSION['restPlayers']) > 0) {
                                foreach ($_SESSION['restPlayers'] as $player) {
                                    echo '<option value="' . $player->id . '">' . $player->boardNumber . " " . $player->firstname . " " . $player->lastname . '</option>';
                                }
                            }
                            ?>
                        </SELECT>
                    </div>
                    <br>

                </div> 
                <div id="buttonsRight">
                    Banor:<input id="courts" size="1" name="courts" type="text">
                     <SELECT id="prioRest" name="prioRest">
                         <option value="true">Vila</option>
                         <option value="false">Mixed</option>
                     </SELECT>
                    <br><br><br><br>
                    <input type="button" name="matchning" value="Matcha!">
                    <input type="button" name="reset" value="Reset">
                    <br>
                    <br>

                    <a href="JavaScript:newPopup('admininterface.php');">Admin</a>
                    <br>
                    <a href="JavaScript:newPopup('ranking.php');">Ranking</a>
                    <br>
                    <a href="JavaScript:newPopup('todaysWins.php');">Dagens vinster</a>
                    <br>
                    <a id="expResult" href="">Exportera dagens</a>
                    <br>
                    
                    <a href="JavaScript:newPopup('view_exported.php');">Se exporterade</a>
                </div>
            </div>      


        </form>

        <div id="restingContainer">

            <form><fieldset><legend>Vilande spelare:</legend>
                    <div id="resting"></div></fieldset></form>
        </div>

        <div id="resultButtonDiv" style="clear:both">

        </div>
        <div id="matchContainer">
            <div id="matchResults">Resultat<br>

            </div>

        </div>
    </div>
</body>
</html>