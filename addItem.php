<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<meta name="description" content="Great Pharmacy Stocktake"/>
	<meta name="keywords" content="business"/>
	<meta name="author" content="Mystery Team"/>
	<link href="PHP_SR_StyleSheet.css" rel="stylesheet" />
</head>
<body>
<?php 
	require_once("dbconn.php");
				
	$itemName = $_POST["name"];
	$itemNote = $_POST["note"];
	$itemCategory = $_POST["category"];   // Getting the detalis of the item we want to add from the user through the form
	$itemStock = $_POST["stock"];
	$itemPrice = $_POST["price"];
	
	$addItemQuery = "INSERT INTO items (itemName, itemNote, itemCategory, stockAmt, itemPrice) 
						VALUES('$itemName', '$itemNote', '$itemCategory', '$itemStock', '$itemPrice')";
	
	$displaySalesResults = @mysqli_query($conn, $addItemQuery)		//Inserting the item into the database
								or die('Couldnt add the item'); 
				
	echo "<p>Item was added successfully!!!</p>";
	
	mysqli_close($conn);  //closing connection
?>
</body>
</html>
