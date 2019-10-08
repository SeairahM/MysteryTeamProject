<?php require_once("checkLogin.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php
		include "head.php";
	?>
</head>
<body>
<?php 
	include "navigation.php";
	require_once("dbconn.php");
	$conn = $DBConn;
	
	$itemName = $_POST["name"];
	$itemNote = $_POST["note"];
	$itemCategory = $_POST["category"];   // Getting the detalis of the item we want to add from the user through the form
	$itemStock = $_POST["stock"];
	$itemPrice = $_POST["price"];
	
	$addItemQuery = "INSERT INTO Items (itemName, itemNote, itemCategory, stockAmt, itemPrice) VALUES('$itemName', '$itemNote', '$itemCategory', '$itemStock', '$itemPrice')";	
	$displaySalesResults = @mysqli_query($conn, $addItemQuery)		//Inserting the item into the database
								or die('Couldnt add the item'); 
				
	echo "<p>Item was added successfully!!!</p>";
?>
<footer>
<?php
	include("footer.php");
?>
</footer>
</body>
</html>
