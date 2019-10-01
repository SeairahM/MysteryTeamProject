<?php require_once("checkLogin.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php
		include("head.php");
	?>
</head>
<body>
<?php
	include("navigation.php");
	require_once("dbconn.php");
	$conn = $DBConn;

	$numberOfSales = 1;

	$dateTimeQuery = "SELECT saleID, dateTime FROM SaleRecords ORDER BY dateTime DESC";  // Selectiing all the sales from the most recent ones to the oldest

	$dateTimeResults = @mysqli_query($conn, $dateTimeQuery)
						or die('Couldnt get the dates');



	while($sale = mysqli_fetch_row($dateTimeResults))  				//Fetching sales
	{
		if($numberOfSales <= 10)									//Keep fetching sales till the variable reaches 10
		{
			$itemCount = 1;
			$displayItemsInSaleQuery = "SELECT SaleLines.itemID, Items.itemName, SaleLines.saleAmt FROM SaleLines
			INNER JOIN Items
			ON SaleLines.itemID=Items.itemID
			WHERE saleID = ". $sale[0] . "";  						// SQL Query that returns the details of the items in the current sales record iteration


			$displayItemsInSaleResult = @mysqli_query($conn, $displayItemsInSaleQuery)
										or die('Couldnt display the items of the Sale');

			echo "<p>Sale ID Number: " . $sale[0] . "
			<form action=\"editSalesForm.php\" method=\"post\">
			<input type = \"hidden\" name = \"edit\" value = \"" . $sale[0] . "\"  />
			<input type =\"submit\" value = \"Edit\" /></form></p>";					// Creating the Edit Sale button which will send the number of the sale we clicked to edit to the editSale.php file


			echo "<table width='100%' border='1'>";
			echo "<tr><th>Number</th><th>Item ID</th><th>Item Name</th><th>Quantity</th></tr>";

			while($items = mysqli_fetch_row($displayItemsInSaleResult))
			{
				echo "<tr><td>" . $itemCount++ . "</td><td>" . $items[0] . "</td><td>" . $items[1] . "</td><td>" . $items[2] . "</td></tr>";	   // Display a table of all the currect sales record's items
			}

			echo "</table>";

			$numberOfSales++;
		}
		else
		{
			break;          // Else when it reaches 10, stop fetching
		}
	}
?>
<footer>
<?php
	include("footer.php");
?>
</footer>
</body>
</html>
