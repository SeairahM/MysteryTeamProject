<?php
require_once("checkLogin.php"); 
require_once("dbconn.php");
require_once("functions.php");

//check to see if the page is being loaded as a result of submitting the form
if(isset($_POST['username']))
{
    $username = sanitizeInput($_POST['username']);  

     //check if username already exists
     $query = "SELECT userID FROM Users WHERE username = '$username'";        
     if ($result = mysqli_query($DBConn, $query)) {
         $userExists = mysqli_num_rows($result);            
         if ($userExists == 0){            
            $errorMsg = "Invalid User";      
            $displayError = TRUE;
         }
         else{
            //username doesn't exist so create the user
            $query = "DELETE FROM Users WHERE username='$username'";
            if ($result = mysqli_query($DBConn, $query)) {
                $displaySuccess = TRUE;
            }
            //Error Occured while adding user to database
            else{
                $displayError = TRUE;
                $errorMsg = "Database Error";
            }
         }
    }
    //Error Occured checking if the user exists
    else{
        $displayError = TRUE;
        $errorMsg = "Database Error";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>People's Health Pharmacy - Delete User</title>
    <?php
        include "head.php";
    ?>
</head>
<body>
        <?php
            include "navigation.php";
        ?>
	<div>
        <?php         
           if ($displayError)
           {
                echo "<div id=\"error\">
                        <p>An Error Occured While Deleting User";
                        if ($errorMsg != ""){
                            echo " - " . $errorMsg;
                        }
                        echo "</p></div>";
           }
       ?>
        <?php
            if ($displaySuccess)
            {   
                echo "<div id=\"success\">
                    <p>Successfully Deleted User</p>
                </div>";
            }
        ?>
	    <form action = "deleteUser.php" method = "POST">
            <h1>Delete User</h1>
            <label>Username:   </label>
            <select name="username" required>
            <?php
                 $query = "SELECT username from Users";
    
                 if ($result = mysqli_query($DBConn, $query)) {                    
                     while ($row = mysqli_fetch_assoc($result)) {
                        $current = $row['username'];
                        echo("<option value=\"$current\">$current</option>");
                    }              
                    
                    mysqli_free_result($result);
                }
            ?>
            </select><br/><br/>           
            <input type="submit" value="Delete User"/><br/>
        </form>
        <br/>
        <a href='addUser.php'>ADD USERS</a>
	</div>

	<footer>
        <?php
            include "footer.php";
        ?>
    	<section>
	</footer>
</body>
</html>