<html>
<head>
<?php
include("head.php")
?>
<link rel="stylesheet" type="text/css" href="sign_in.css">
</head>
<?php
    require_once("dbconn.php");
    session_start();

    //LOGIN ATTEMPT - CHECK LOGIN AND REDIRECT OR SHOW INCORRECT USER/PASS
    if (isset($_POST["User"]) && isset($_POST["Pass"])){  
        $User = $_POST["User"];
        $Pass = $_POST["Pass"];             
        //check user and pass in db and get the users ID to apply to session
        $query = "SELECT userID FROM Users WHERE username = '$User' AND password = '$Pass'";        
        if ($result = mysqli_query($DBConn, $query)) {
            $userExists = mysqli_num_rows($result);            
            if ($userExists > 0){
               $row = mysqli_fetch_assoc($result);
               $_SESSION["user_id"] = $row["userID"];      
               header("Location: add_sales.php"); //CHANGE REDIRECT TO REFLECT MAIN HOME PAGE FOR SIGNED IN USER ONCE COMPLETE           
            }
            else{
                //Username and/or password wrong so display error in login form
                session_destroy();                
                displayLoginPage(TRUE);
            }      
            mysqli_free_result($result);
        }
        else{
            session_destroy();
            displayLoginPage();
        }
    }
    else{

        //check to see if there is an active session
        if (isset($_SESSION["user_id"])){         
                //there is an active session so redirect to "home" page as they are already signed in       
                header("Location: add_sales.php"); //CHANGE REDIRECT TO REFLECT MAIN HOME PAGE FOR SIGNED IN USER ONCE COMPLETE       
        }
        //No Active session is there so clear and initialise
        else{
            session_destroy();
            displayLoginPage();
        }
    }



 function  displayLoginPage($displayError = FALSE){
    echo "<form action=\"index.php\" method=\"POST\">
        <div class=\"container\">";
        if ($displayError == TRUE){
            echo "<label id=\"error\">Incorrect Username and or Password Entered!</label><br/>";
        }
        echo "<label for=\"user\">Username</label>
            <input type=\"text\" placeholder=\"Enter Username\" name=\"User\" required>
        
            <label for=\"pass\">Password</label>
            <input type=\"password\" placeholder=\"Enter Password\" name=\"Pass\" required>
        
            <button type=\"submit\">Login</button>
        </div>
    </form>";
 }
?>
<footer id="signincss">
<?php
include("footer.php");
?>
</footer>
</html>
