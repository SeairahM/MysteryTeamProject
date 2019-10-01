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

	$sale = $_POST["edit"];
	
	
	$saleRecordQuery = "SELECT * FROM SaleRecord WHERE saleID = " . $sale . "";
	
	$saleRecordResult = @mysqli_query($conn, $saleRecordQuery)		//Inserting the item into the database
							or die('Couldnt get the sale details');
							
							
	$saleItemsQuery = "SELECT SaleLines.itemID, Items.itemName, SaleLines.saleAmt FROM SaleLines 
								INNER JOIN Items
								ON SaleLines.itemID=Items.itemID    
								WHERE saleID = ". $sale . "";
								
	$saleItemsResults = @mysqli_query($conn, $saleItemsQuery)		//Inserting the item into the database
						or die('Couldnt get the sale details');
	

	
	while($saleDetails = mysqli_fetch_row($saleRecordResult))
	{
		$totalCost = $saleDetails[0];
		$payMethod = $saleDetails[1];
		$dateAndTime = $saleDetails[2];
	}
	
	echo nl2br "<form action = \"editSales.php\" method = \"post\" >
					<p>Edit sale Number: " . $sale . "</p>\n
					<fieldset>
						<input type=\"text\" name=\"cost\" value=\"" . $totalCost . "\" />\n
						<input type=\"text\" name=\"method\" value=\"" . $payMethod . "\" />\n
						<input type=\"hidden\" name=\"saleEdited\" value=\"" . $sale . "\"";
		
	while($saleItems = mysqli_fetch_row($saleItemsResults))
	{
		echo nl2br "<label>" . $saleItems[0] . " " . $saleItems[1] . "</label>
		<input type=\"text\" name=\"" . $saleItems[0] . "\" value=\"" . $saleItems[2] . "\" />\n"		
	}
					

	echo "  	</fieldset>
			</form>";
	
	//echo nl2br "<p>Total cost of Items: "
	
	
	
	
	mysqli_close($conn);  //closing connection
?>
</body>
</html>