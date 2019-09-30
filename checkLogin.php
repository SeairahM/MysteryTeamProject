<?php
    session_start();
    if(!isset($_SESSION["user_id"]))
    {
        header("Location: index.php");
    }
    if ($_SERVER["REQUEST_URI"] == "/checkLogin.php"){
       echo "<p>User is Logged In</p>";
    }
?>