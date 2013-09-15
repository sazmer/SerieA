<?php
session_start();
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

        <link href="css/style.css" rel="stylesheet" type="text/css">


        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>


        <script src="script/adminScript.js"></script>
    </head>

    <body>

        <form>


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
                ?>
            </SELECT>
            <input type="button" name="getPlayer" value="Hämta spelare">
            <fieldset>
                <legend>Ändra information om spelare</legend>
                <div id="textPos">
                    Förnamn: <input name="fname" type="text"><br>
                    Efternamn: <input name="lname" type="text"><br>
                    Kön: <select id="selectSex" name="sex">
                        <option value="M">Man</option>
                        <option value="F">Kvinna</option>
                    </select>

                    Vinster: <input name="wins" type="text"><br>
                    <select style="float:right; margin-right: 350px;" id="selectMember" name="member">
                        <option value="N">Icke-medlem</option>
                        <option value="Y">Medlem</option>
                    </select>
                    Antal vilade: <input name="rests" type="text"><br>

                    Antal spelade: <input name="played_games" type="text"><br>
                    <input type="button" name="change" value="Genomför ändringar">
                    <input type="button" name="purge" value="Ta bort spelare">
                    </fieldset>
                </div>
                <div style="clear:both;"> 
                    <fieldset id="addplayerFieldset">
                        <legend>Lägg till spelare</legend>
                        <div id="textPos">
                            Förnamn: <input name="infname" type="text"><br>
                            Efternamn: <input name="inlname" type="text"><br>
                            <br>
                            <select style="float:right; margin-right: 50px;" type="select" name="inmember">
                                <option value="N">Icke-medlem</option>
                                <option value="Y">Medlem</option>
                            </select>
                            <INPUT type="radio" name="insex" value="M" checked="checked"> Man<BR>
                            <INPUT type="radio" name="insex" value="F"> Kvinna<BR>

                            <input type="submit" value="Lägg till spelare"><p id="resultatP" class="hidden"></p>
                    </fieldset><br>
                </div>
        </form>
        <br><br>
       <!--<a href="#" id="unsetSession">Unset session</a>-->

    </body>
</html>