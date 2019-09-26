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
	<h2>Add Item Page</h2>	
	<p>Please enter the details of the item you would like to add below:</p>
	<form action = "addItem.php" method = "post">
	<fieldset>
		<legend>Item Details</legend>
		<label>Name: </label>
		<input type = "text" name = "name" /><br>		<!-- Name of the item -->
		
		<label>Description: </label>					<!-- Note of the item -->
		<textarea name="note" rows="6" cols="30" maxlength="260" ></textarea><br> 
		
		<label>Category: </label>
		<?php
		$host = "localhost";				// Our host url
		$user = "admin"; 					// Our user name
		$pswd = "MysteryTeam2019"; 			// Our password 
		$dbnm = "PHP"; 						// Our database name
	
		$conn = @mysqli_connect($host, $user, $pswd, $dbnm)
					or die('Unable to connect to the server');
					
		$getCategoryQuery = "SELECT categoryID, categoryName FROM Categories";
	
		$getCategoryResults = @mysqli_query($conn, $getCategoryQuery)		//Getting the category table from the database
								or die('Couldnt get the category'); 
		
		echo "<select name=\"category\" >";
		echo "<option value=""> --- </option>";
		
		while($category = mysqli_fetch_row($getCategoryResults))
		{
			echo "<option value=\"" . $category[0] . " \">" . $category[1] . "</option>";  //Creating the categories dynamically
		}
		
		echo "</select><br>"
		
		mysqli_close($conn);  				//Closing connection
		?>
		
		<label>Stock: </label>
		<input type = "text" name = "stock" /><br>  <!-- Stock of the itme --> 
		
		<label>Price: </label>
		<input type = "text" name = "price" /><br>  <!-- Price of the item -->
	</fieldset>
	</form>
</body>
</html>