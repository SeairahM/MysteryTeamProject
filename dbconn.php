<?php
/*DBConn.php
This File acts as a connector for the database, it provides simple access to connect and test query the database.
*/

//DATABASE SETTINGS
$DBServer = "localhost";
$DBUser = "admin";
$DBPass = "MysteryTeam2019";
$DB = "PHP";

//DATABASE CONNECTION
$DBConn = mysqli_connect($DBServer, $DBUser, $DBPass, $DB);

//CHECK IF NOT CONNECTED
if (mysqli_connect_errno()) {   
    die("Failed to connect to database: " . mysqli_connect_error());    
}

//UNCOMMENT LINE BELOW TO PERFORM DATABASE TEST
if ($_SERVER["REQUEST_URI"] == "/dbconn.php"){
    PrintTables($DBConn);
}

//RUN A QUERY ON THE DATABASE TO PRINT A LIST OF AVALIABLE TABLES
function PrintTables($DBConn){
    $query = "SHOW TABLES";
    
    if ($result = mysqli_query($DBConn, $query)) {
        $numTables = mysqli_num_rows($result);
        echo ("<H1>Database " . $DB . " contains " . $numTables . " table/s.</H1>");
        /* fetch associative array */
        while ($row = mysqli_fetch_assoc($result)) {
            echo("<p>- " . $row["Tables_in_PHP"] . "</p>");
        }
    
        /* free result set */
        mysqli_free_result($result);
    }
    
    /* close connection */
    mysqli_close($DBConn);
}

?>