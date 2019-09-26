<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="description" content="Assignment 1" />
	<meta name="keywords" content="assingment" />
	<meta name="author" content="Apostolos Lafazanis" />
	<!-- <link rel="stylesheet" 	 href="style.css" />			-- Linking CSS stylesheet-->
	<title>Job Post Result</title>
</head>
<body>
<?php 
	$host = "localhost";				// Our host url
	$user = "admin"; 					// Our user name
	$pswd = "MysteryTeam2019"; 			// Our password 
	$dbnm = "PHP"; 						// Our database name
	
	$conn = @mysqli_connect($host, $user, $pswd, $dbnm)
				or die('Unable to connect to the server');
				
				
	$itemName = $_POST["name"];
	$itemNode = $_POST["note"];
	$itemCategory = $_POST["category"];   // Getting the detalis of the item we want to add from the user through the form
	$itemStock = $_POST["stock"];
	$itemPrice = $_POST["price"];
	
	$addItemQuery = "INSERT INTO Items (itemName, itemNode, itemCategory, stockAmt, itemPrice) 
						VALUES($itemName, $itemNode, $itemCategory, $itemStock, $itemPrice)";
	
	$displaySalesResults = @mysqli_query($conn, $addItemQuerys)		//Inserting the item into the database
								or die('Couldnt add the item'); 
				
	echo "<p>Item was added successfully!!!</p>";
	
	mysqli_close($conn);  //closing connection
?>
</body>
</html>