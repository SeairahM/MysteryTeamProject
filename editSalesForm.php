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

	$sale = $_POST["edit"];
	
	
	$saleRecordQuery = "SELECT * FROM salerecords WHERE saleID = '" . $sale . "'";
	
	$saleRecordResult = @mysqli_query($conn, $saleRecordQuery)		//Getting the sale information of the sale the user clicked to edit
							or die('Couldnt get the sale details1');
							
							
	$saleItemsQuery = "SELECT salelines.itemID, items.itemName, salelines.saleAmt FROM salelines 
								INNER JOIN items
								ON salelines.itemID=items.itemID    
								WHERE saleID = '". $sale . "'";
								
	$saleItemsResults = @mysqli_query($conn, $saleItemsQuery)		//Getting the items and their details of the sale the user clicked to edit
						or die('Couldnt get the sale details2');
	

	
	while($saleDetails = mysqli_fetch_row($saleRecordResult))
	{
		$totalCost = $saleDetails[1];
		$payMethod = $saleDetails[2];				//Storing the sale general details in varibles so that we can use them freely later
		$dateAndTime = $saleDetails[3];
	}
	
	echo "<form action = \"editSales.php\" method = \"post\" >
					<p>Editting Sale No: " . $sale . "</p>
					<fieldset>
						<legend>New Sale Details</legend>
						<label>New Total Cost: </label>
						<input type=\"text\" name=\"cost\" value=\"" . $totalCost . "\" /><br>
						
						<label>New Payment Method: </label>
						<input type=\"text\" name=\"method\" value=\"" . $payMethod . "\" /><br><br>
						
						<input type=\"hidden\" name=\"saleEdited\" value=\"" . $sale . "\" />";   //Creating the editting form of the sale the user wants to edit
																									
						
		
	while($saleItems = mysqli_fetch_row($saleItemsResults))
	{
		echo "<label>ItemID:" . $saleItems[0] . " " . $saleItems[1] . "</label><br>
			  <label>New Sale Quantity</label>
			  <input type=\"text\" name=\"" . $saleItems[0] . "\" value=\"" . $saleItems[2] . "\" /><br><br>";	//Creating the text fields for the amount of the items in the sale the user wants to edit/change
	}
					

	echo " 		<input type=\"submit\" value=\"Submit Changes\" />							
				<input type=\"reset\" value=\"Reset\" />	
				</fieldset>
			</form>";											//Submit and Reset Buttons
	
	mysqli_close($conn);  //closing connection
?>
</body>
</html>
