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
	$host = "localhost";				// Our host url
	$user = "admin"; 					// Our user name
	$pswd = "MysteryTeam2019"; 			// Our password 
	$dbnm = "PHP"; 						// Our database name
	$salesNumber = 1;
	
	$conn = @mysqli_connect($host, $user, $pswd, $dbnm)
				or die('Unable to connect to the server');
	
	
	date_default_timezone_set('Australia/Melbourne');		
	$currentDateTime = date("d/m/Y H:i:s ");

	$dateTimeQuery = "SELECT saleID, dateTime FROM SaleRecords ORDER BY dateTime DESC";
	
	$dateTimeResults = @mysqli_query($conn, $dateTimeQuery)
								or die('Couldnt get the dates');  

	
	while($sale = mysqli_fetch_row($dateTimeResults))
	{
		if($numberOfSales <= 10)
		{
			$itemCount = 1;
			$displayItemsInSaleQuery = "SELECT SaleLines.itemID, Items.itemName, SaleLines.saleAmt FROM SaleLines 
			INNER JOIN Items
			ON SaleLines.itemID=Items itemID
			WHERE saleID = ". $sale[0] . "";
			
			
			$displayItemsInSaleResult = @mysqli_query($conn, $displayItemsInSaleQuery)
										or die('Couldnt display the items of the Sale');
				
			echo "<p>Sale ID Number: " . sale[0] . "</p>";
			echo "<form action=\"editSale.php\" method=\"post\"> 
			<input type = \"hidden\" name = \"edit\" value = \"" . $sale[0] . "\"  /> 
			<input type =\"submit\" value = \"Edit\" /></form>" 
			
			echo "<table width='100%' border='1'>";	
			echo "<tr><th>Number</th><th>Item ID</th><th>Item Name</th><th>Quantity</th></tr>";	
				
			while($items = mysqli_fetch_row($displayItemsInSaleResult))
			{
				echo "<tr><td>" . $itemCount++ . "</td><td>" . $items[0] . "</td><td>" . $items[1] . "</td><td>" . $items[2] . "</td></tr>"	
			}
			
			echo "</table>";
				
			$numberOfSales++;
		}
		else
		{
			break;
		}
	}	
?>
</body>
</html>