<?php require_once("checkLogin.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php
		include("head.php");
	?>
</head>
<body>
	<?php include("navigation.php"); ?>
	<h2>Add Item Page</h2>
	<?php
		require_once("dbconn.php");
		include("functions.php");

		$itemName = sanitizeInput($_POST["name"]);
		$itemNote = sanitizeInput($_POST["note"]);
		$itemCategory = sanitizeInput($_POST["category"]);   // Getting the detalis of the item we want to add from the user through the form
		$itemStock = sanitizeInput($_POST["stock"]);
		$itemPrice = sanitizeInput($_POST["price"]);
		$conn = $DBConn;
		$valid = true;
		$stock_pattern = "/^[0-9]+$/";
		$price_pattern = "/^[0-9]+\.[0-9]+$|^[0-9]+$/";


		if(isset($_POST["name"]) && isset($_POST["note"]) && isset($_POST["category"]) && isset($_POST["stock"]) && isset($_POST["price"])) // Checks if any of them are NULL
		{
			if(!empty($_POST["name"]) && !empty($_POST["note"]) && !empty($_POST["category"])) //Check if any of them are null or 0 or empty strings
			{	
				if(!preg_match($price_pattern, $itemPrice) || $itemPrice == 0)   //checks if the ID matches the format
				{
					$valid = false;
					echo "<p>The price needs to be a positive number</p>";
				}
			
				if(!preg_match($stock_pattern, $itemStock) || $itemStock == 0)   //checks if the ID matches the format
				{
					$valid = false;
					echo "<p>The stock quantity needs to be a positive number</p>";
				}
			
				if($valid == true)
				{	
					$addItemQuery = "INSERT INTO Items (itemName, itemNote, itemCategory, stockAmt, itemPrice) 
							VALUES('$itemName', '$itemNote', '$itemCategory', '$itemStock', '$itemPrice')";
		
					$displaySalesResults = @mysqli_query($conn, $addItemQuery)	//Inserting the item into the database
								or die('Couldnt add the item'); 
				
					echo "<p>Item was added successfully!!!</p>";
				}
				else
				{
					echo "<p>Some of the sections were incorrect. Please go back and fix them to add the item</p>";
				}	
			}
			else
			{
				echo"<p>Some of the item\'s details were left blank. Please fill in all the section to add the item.</p>";
			}
		}
		else
		{
			echo "The details of the item do no exist. Please go to a different part of the page.";
		}
	?>
	<a href="saleDisplay.php">Go to Display sales</a><br>
	<a href="addItemForm.php">Return to Add Items</a>
	<footer>
	<?php
		include("footer.php");
	?>
</footer>
</body>
</html>
