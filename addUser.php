<?php
require_once("checkLogin.php"); 
require_once("dbconn.php");
require_once("functions.php");

//check to see if the page is being loaded as a result of submitting the form
if(isset($_POST['username']) && isset($_POST['password']))
{
    $username = sanitizeInput($_POST['username']);
    $password = sanitizeInput($_POST['password']);
    $fName = sanitizeInput($_POST['fname']);
    $lName = sanitizeInput($_POST['lname']);

     //check if username already exists
     $query = "SELECT userID FROM Users WHERE username = '$username'";        
     if ($result = mysqli_query($DBConn, $query)) {
         $userExists = mysqli_num_rows($result);            
         if ($userExists > 0){            
            $errorMsg = "Username Exists";      
            $displayError = TRUE;
         }
         else{
            //username doesn't exist so create the user
            $query = "INSERT INTO Users(username, password, firstName, lastName) VALUES ('$username','$password','$fName','$lName')";
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
	<title>People's Health Pharmacy - Add User</title>
    <?php
        include "head.php";
    ?>
</head>
<body>
	<header>
		<img src="images/PHP_SR_logo.png" alt="Logo"/>
		<a href="signout.php">SIGN-OUT</a>
		<aside>
        <?php
            include "navigation.php";
        ?>
		</aside>
	</header>

	<div>
        <?php         
           if ($displayError)
           {
                echo "<div id=\"error\">
                        <p>An Error Occured While Adding User";
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
                    <p>Successfully Added User</p>
                </div>";
            }
        ?>
	    <form action = "addUser.php" method = "POST">
            <h1>Add User</h1>
            <label>Username:   </label>
            <input type="text" name="username" required/><br/>
            <label>Password:   </label>
            <input type="password" name="password" required/><br/>
            <label>First Name: </label>
            <input type="text" name="fname" /><br/>
            <label>Surname:    </label>
            <input type="text" name="lname" /><br/><br/>
            <input type="submit" value="Add User"/>&nbsp;
            <input type="reset" value="Clear"/><br/>
        </form>
	</div>

	<footer>
        <?php
            include "footer.php";
        ?>
    	<section>
	</footer>
</body>
</html>