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
	<
<?php 
	$host = "localhost";				// Our host url
	$user = "root"; 					// Our user name
	$pswd = "MysteryTeam2019"; 			// Our password 
	$dbnm = "PHP"; 						// Our database name
	
	$conn = @mysqli_connect($host, $user, $pswd, $dbnm)
				or die('Unable to connect to the server');

	$sale = $_POST["saleEdited"];
	$newCost = $_POST["cost"];
	$newMethod = $_POST["method"];
	
	$updateSaleDetailsQuery = "UPDATE salerecords
							   SET totalCost = '" . $newCost . "', payMethod = '" . $newMethod . "' 
							   WHERE saleID=" . $sale . "";
	
	
	$updateSaleDetailsResult = @mysqli_query($conn, $updateSaleDetailsQuery)		//Inserting the item into the database
							or die('Couldnt update the sale\'s details');
							
						

						
	$saleItemsQuery = "SELECT  itemID, saleAmt FROM salelines	
					   WHERE saleID = ". $sale . "";
								
	$saleItemsResults = @mysqli_query($conn, $saleItemsQuery)		//Inserting the item into the database
						or die('Couldnt get the sales item information');
	
	
	
	while($saleDetails = mysqli_fetch_row($saleItemsResults))
	{
		$updateAmount = $_POST["" . $saleDetails[0] . ""];
			
		
		if($updateAmount != saleDetails[1])
		{	
	
			$getOldItemStockQuery = "SELECT stockAmt FROM items WHERE itemID = '" . $saleDetails[0] . "'";
			
			$getOldItemStockResult = @mysqli_query($conn, $getOldItemStockQuery)		
									 or die('Couldnt get the old item stock');
			
			while($row = mysqli_fetch_row($getOldItemStockResult))
			{
				$oldItemStock = $row[0];
			}
			
			if($updateAmount < saleDetails[1])
			{
				
				$stockDiffernce = $saleDetails[1] - $updateAmount;
				
				$newStockAmount = $oldItemStock + $stockDiffernce;
				
				$updateStockAmountQuery = "UPDATE items 
											SET stockAmt = '" . $newStockAmount . "'
											WHERE itemID = '" . $saleDetails[0] . "'";
					
				$updateStockAmoutResult = @mysqli_query($conn, $updateStockAmountQuery)		//Inserting the item into the database
										   or die('Couldnt update the item\'s stock');
				if($updateAmount == 0)
				{
				
					$deleteItemQuery = "DELETE FROM salelines
									WHERE itemID = '" . $saleDetails[0] . "' AND saleID = '" . $sale . "'";			
							
					echo "<p>Item with ID :" . $saleDetails[0] . " was deleted successfully! </p>";
					
					$deleteItemResult = @mysqli_query($conn, $deleteItemQuery)		//Inserting the item into the database
									or die('Couldnt delete the item from the sale');
									
					
				}
				else
				{
					$updateSaleAmountQuery = "UPDATE salelines 
												SET saleAmt = '" . $updateAmount . "'
												WHERE itemID = '" . $saleDetails[0] . "'";
												
					$updateSaleAmoutResult = @mysqli_query($conn, $updateSaleAmountQuery)		//Inserting the item into the database
												or die('Couldnt update the sales\'s amount');
												
					echo "<p>Item with ID :" . $saleDetails[0] . " was updated successfully!</p>";
				}
			}
			else 
			{
				$stockDiffernce = $updateAmount - saleDetails[1];
				$generalStock = $oldItemStock + $saleDetails[1];
				
				if($updateAmount <= $generalStock)
				{
				
					$newStockAmount = $oldItemStock - $stockDiffernce;
				
				
					$updateStockAmountQuery = "UPDATE items 
												SET stockAmt = '" . $newStockAmount . "'
												WHERE itemID = '" . $saleDetails[0] . "'";
								  
					$updateStockAmoutResult = @mysqli_query($conn, $updateStockAmountQuery)		//Inserting the item into the database
												or die('Couldnt update the item\'s stock');
									
					$updateSaleAmountQuery = "UPDATE salelines 
												SET saleAmt = '" . $updateAmount . "'
												WHERE itemID = '" . $saleDetails[0] . "'";
												
					$updateSaleAmoutResult = @mysqli_query($conn, $updateSaleAmountQuery)		//Inserting the item into the database
												or die('Couldnt update the sales\'s amount');
				
					echo "<p>Item with ID :" . $saleDetails[0] . " was updated successfully!</p>";
				}
				else
				{
					echo "<p>Invalid sale Amount for item with ID :" . $saleDetails[0] . ". Sale amount did not get updated.</p>";
				}
			}
		}
		
	}
	
	mysqli_close($conn);  //closing connection
?>
</body>
</html>