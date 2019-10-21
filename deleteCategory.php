<?php
require_once("checkLogin.php"); 
require_once("dbconn.php");
require_once("functions.php");

//DB Functions
function getCategoryID($DBConn, $name){
    $query = "SELECT categoryID FROM Categories WHERE categoryName = '$name'";    
      
    if ($result = mysqli_query($DBConn, $query)) {
        $categoryExists = mysqli_num_rows($result);   
        if ($categoryExists == 1){
            while ($row = mysqli_fetch_assoc($result)) {
                $categoryID = $row['categoryID'];
                return $categoryID;
            }      
        }
        else{
            return -1;
        }
    }
}

function setItemsNewCategory($DBConn, $currID, $newID){
    $query = "UPDATE Items SET itemCategory = '$newID' WHERE itemCategory = '$currID'";
    return ($result = mysqli_query($DBConn, $query));
}

function deleteCategory($DBConn, $categoryID){
    $query = "DELETE FROM Categories WHERE categoryID = '$categoryID'";
    return ($result = mysqli_query($DBConn, $query));
}

//check to see if the page is being loaded as a result of submitting the form
if(isset($_POST['categoryName']))
{
    $categoryName = sanitizeInput($_POST['categoryName']);  

    if ($categoryName == "Uncategorised"){
        $displayError = TRUE;
        $errorMsg = "Can't Delete That Category!";
    }
    else{
        $categoryID = getCategoryID($DBConn, $categoryName);
        if ($categoryID == -1){
            $displayError = TRUE;
            $errorMsg = "Category Dosen't Exist";
        }
        else{
            $newcategoryID = getCategoryID($DBConn, "Uncategorised");
            if ($newcategoryID == -1){
                $displayError = TRUE;
                $errorMsg = "Database Error";
            }
            else{
                if (setItemsNewCategory($DBConn, $categoryID, $newcategoryID)){
                    if (deleteCategory($DBConn, $categoryID))
                    {
                        $displaySuccess = TRUE;
                    }
                    else{
                        $displayError = TRUE;
                        $errorMsg = "Database Error";
                    }
                }
                else{
                    $displayError = TRUE;
                    $errorMsg = "Database Error";
                }

            }
        }
    }
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>People's Health Pharmacy - Delete Category</title>
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
                        <p>An Error Occured While Deleting Category";
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
                    <p>Successfully Deleted Category</p>
                </div>";
            }
        ?>
	    <form action = "deleteCategory.php" method = "POST">
            <h1>Delete Category</h1>
            <label>Category:   </label>
            <select name="categoryName" required>
            <?php
                 $query = "SELECT categoryName from Categories";
    
                 if ($result = mysqli_query($DBConn, $query)) {                    
                     while ($row = mysqli_fetch_assoc($result)) {
                        $current = $row['categoryName'];
                        if ($current != "Uncategorised")
                            echo("<option value=\"$current\">$current</option>");
                    }              
                    
                    mysqli_free_result($result);
                }
            ?>
            </select><br/><br/>           
            <input type="submit" value="Delete Category"/><br/>
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