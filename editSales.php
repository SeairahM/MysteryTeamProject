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
	require_once("dbconn.php");
	$conn = $DBConn;
	

	$sale = $_POST["saleEdited"];
	$newCost = $_POST["cost"];
	$newMethod = $_POST["method"];							//Storing the inputs fromt the edit Form so we can use them later
	
	$saleInfoQuery = "SELECT totalCost, payMethod FROM salerecords WHERE saleID = '" . $sale . "'";  //Grabbing the Total Cost and the Pay mehtod from the database
	
	$saleInfoResults = @mysqli_query($conn, $saleInfoQuery)
						or die('Coult not grab sale info');
	
	while($saleInfo = mysqli_fetch_row($saleInfoResults))
	{
		if($newCost != $saleInfo[0]  )  					//If the user has changed the total Cost then update and display an update message
		{
			$updateSaleCostQuery = "UPDATE salerecords
										SET totalCost = '" . $newCost . "' 
										WHERE saleID=" . $sale . "";
	
	
			$updateSaleDetailsResult = @mysqli_query($conn, $updateSaleCostQuery)		
										or die('Couldnt update the sale\'s cost');
										
			echo "<p>The total cost of the sale has been changed!</p>";
		}					
						

		if($newMethod != $saleInfo[1]  )				//If the user has changed the pay Method then update and display an update message
		{
			$updateSaleDetailsQuery = "UPDATE salerecords
										SET payMethod = '" . $newMethod . "' 
										WHERE saleID=" . $sale . "";
	
	
			$updateSaleDetailsResult = @mysqli_query($conn, $updateSaleDetailsQuery)		
										or die('Couldnt update the sale\'s details');
										
			echo "<p>The pay method of the sale has been changed!</p>";
		}	
	}
	
	
			$saleItemsQuery = "SELECT  itemID, saleAmt FROM salelines	
								WHERE saleID = ". $sale . "";
								
			$saleItemsResults = @mysqli_query($conn, $saleItemsQuery)					//Grabbing all the items and their amount of the sale the user is editting
								or die('Couldnt get the sales item information');
		
	
	
	
	
	while($saleDetails = mysqli_fetch_row($saleItemsResults))		//Going through all of the items
	{
		$updateAmount = $_POST["" . $saleDetails[0] . ""];			//Grabbing each text field input from the edit form 
			
		
		if($updateAmount != $saleDetails[1])		//If the user has changed the item amount then do...
		{	
	
			$getOldItemStockQuery = "SELECT stockAmt FROM items WHERE itemID = '" . $saleDetails[0] . "'";
			
			$getOldItemStockResult = @mysqli_query($conn, $getOldItemStockQuery)		
									 or die('Couldnt get the old item stock');
			
			while($row = mysqli_fetch_row($getOldItemStockResult))
			{
				$oldItemStock = $row[0];						// Get the old item Stock
			}
			
			if($updateAmount < $saleDetails[1])		// if the new sale amount from the user is less that the old one, then that means that user is returning items so the general 
			{											//stock of the returned item will be increase depending on the difference between the old sale amount and the new
				
				$stockDiffernce = $saleDetails[1] - $updateAmount;
				
				$newStockAmount = $oldItemStock + $stockDiffernce;
				
				$updateStockAmountQuery = "UPDATE items 
											SET stockAmt = '" . $newStockAmount . "'
											WHERE itemID = '" . $saleDetails[0] . "'";
					
				$updateStockAmoutResult = @mysqli_query($conn, $updateStockAmountQuery)		
										   or die('Couldnt update the item\'s stock');
				if($updateAmount == 0) 						// If the new sale amount the user enters in the edit Form is 0, they returned all of the items and basically the sale of that 				
				{											// of that item never happened thats why we have to delete that sale line
				
					$deleteItemQuery = "DELETE FROM salelines
									WHERE itemID = '" . $saleDetails[0] . "' AND saleID = '" . $sale . "'";			
							
					echo "<p>Item with ID :" . $saleDetails[0] . " was deleted successfully! </p>";
					
					$deleteItemResult = @mysqli_query($conn, $deleteItemQuery)		
									or die('Couldnt delete the item from the sale');
									
									
									
					
					$countOfItemsQuery = "SELECT COUNT(itemID) FROM salelines WHERE saleID ='" . $sale . "'";
									
					$countOfItemResult = @mysqli_query($conn, $countOfItemsQuery)		
										or die('Couldnt delete the item from the sale');
										
					while($row = mysqli_fetch_row($countOfItemResult))
					{
						if($row[0] == 0)		//This means that if the saleline table doesnt have any items for the sale the user is editting then that sale has no items
						{														//so the entire sale needs to be deleted form the salesRecords table
							$deleteSaleQuery = "DELETE FROM salerecords
												WHERE saleID = '" . $sale . "'";			
							
							echo "<p>Sale with ID :" . $sale . " was deleted successfully! </p>";
					
							$deleteSaleResult = @mysqli_query($conn, $deleteSaleQuery)		
												or die('Couldnt delete the item from the sale');
						}
					}	
				}
				else			// else if the new item amount is not 0 but still less than the old amount, just update the item amount and display messages
				{
					$updateSaleAmountQuery = "UPDATE salelines 
												SET saleAmt = '" . $updateAmount . "'
												WHERE itemID = '" . $saleDetails[0] . "' AND saleID = '" . $sale . "'";
												
					$updateSaleAmoutResult = @mysqli_query($conn, $updateSaleAmountQuery)		
												or die('Couldnt update the sales\'s amount');
												
					echo "<p>Item with ID :" . $saleDetails[0] . " was updated successfully!</p>";
				}
			}
			else 			//However, if the new sale amount that the user has typed in is higher that the old amount in the database, then deduct the 
			{																	// the difference
				$stockDiffernce = $updateAmount - $saleDetails[1];
				$generalStock = $oldItemStock + $saleDetails[1];
				
				if($updateAmount <= $generalStock)			// If the new sale number the user typed in is not higher than the overall stock then the sale cannot be made
				{													// because the stock is not enough to support the users demand. The update will not be registered and they website
																			// Will display an error message. If not it will update the item stock and the sale accordingly 
					$newStockAmount = $oldItemStock - $stockDiffernce;
				
				
					$updateStockAmountQuery = "UPDATE items 
												SET stockAmt = '" . $newStockAmount . "'
												WHERE itemID = '" . $saleDetails[0] . "'";
								  
					$updateStockAmoutResult = @mysqli_query($conn, $updateStockAmountQuery)		// Updating overall item stock
												or die('Couldnt update the item\'s stock');
									
					$updateSaleAmountQuery = "UPDATE salelines 
												SET saleAmt = '" . $updateAmount . "'
												WHERE itemID = '" . $saleDetails[0] . "' AND saleID = '" . $sale . "'";
												
					$updateSaleAmoutResult = @mysqli_query($conn, $updateSaleAmountQuery)		//Updating the item amount of the editted sale
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
?>
<a href="saleDisplay.php">Return to Display sales</a>
<footer>
<?php
	include("footer.php");
?>
</footer>
</body>
</html>
