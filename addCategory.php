<?php
require_once("checkLogin.php"); 
require_once("dbconn.php");
require_once("functions.php");

//check to see if the page is being loaded as a result of submitting the form
if(isset($_POST['name']))
{
    $name = sanitizeInput($_POST['name']);
    $description = sanitizeInput($_POST['description']);

     //check if category already exists
     $query = "SELECT categoryName FROM Categories WHERE categoryName = '$name'";        
     if ($result = mysqli_query($DBConn, $query)) {
         $userExists = mysqli_num_rows($result);            
         if ($userExists > 0){            
            $errorMsg = "Category Exists";      
            $displayError = TRUE;
         }
         else{
            //username doesn't exist so create the category
            $query = "INSERT INTO Categories(categoryName, categoryNote) VALUES ('$name','$description')";
            if ($result = mysqli_query($DBConn, $query)) {
                $displaySuccess = TRUE;
            }
            //Error Occured while adding category to database
            else{
                $displayError = TRUE;
                $errorMsg = "Database Error";
            }
         }
    }
    //Error Occured checking if the category exists
    else{
        $displayError = TRUE;
        $errorMsg = "Database Error";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>People's Health Pharmacy - Add Category</title>
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
                        <p>An Error Occured While Adding Category";
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
                    <p>Successfully Added Category</p>
                </div>";
            }
        ?>
	    <form action = "addCategory.php" method = "POST">
            <h1>Add Category</h1>
            <label>Name:   </label>
            <input type="text" name="name" pattern="[A-Za-z]{1,255}" required/><br/>            
            <label>Description:    </label>
            <input type="text" name="description" pattern="[A-Za-z\d ]{1,255}"/><br/><br/>
            <input type="submit" value="Add Category"/>&nbsp;
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