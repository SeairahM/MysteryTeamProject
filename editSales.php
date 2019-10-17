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
	include("navigation.php");?>
<div>
<?php
	require_once("dbconn.php");
	include("functions.php");
	$conn = $DBConn;
	$valid = true;
	$sale = $_POST["saleEdited"];
	$newCost = sanitizeInput($_POST["cost"]);
	$newMethod = sanitizeInput($_POST["method"]);			//Storing the inputs from the edit Form so we can use them later
	$cost_pattern = "/^[0-9]+\.[0-9]+$|^[0-9]+$/";
	$newItemsNumber = $_POST["newItems"];
	

	if(isset($_POST["cost"]) && isset($_POST["method"]))
	{
		if(!empty($_POST["cost"]) && !empty($_POST["method"]))  	// Check if the pay method and the total cost are checked
		{
			if(preg_match($cost_pattern, $newCost))
			{
	
				$saleInfoQuery = "SELECT totalCost, payMethod FROM SaleRecords WHERE saleID = '" . $sale . "'";  //Grabbing the Total Cost and the Pay mehtod from the database
	
				$saleInfoResults = @mysqli_query($conn, $saleInfoQuery)
						   or die('Coult not grab sale info');
	
				while($saleInfo = mysqli_fetch_row($saleInfoResults))
				{
					if($newCost != $saleInfo[0]  )  					//If the user has changed the total Cost then update and display an update message
					{	
						$updateSaleCostQuery = "UPDATE SaleRecords
									SET totalCost = '" . $newCost . "' 
									WHERE saleID=" . $sale . "";
	
	
						$updateSaleDetailsResult = @mysqli_query($conn, $updateSaleCostQuery)		
									   or die('Couldnt update the sale\'s cost');
										
						echo "<p>The total cost of the sale has been changed!</p>";
					}					
						

					if($newMethod != $saleInfo[1]  )				//If the user has changed the pay Method then update and display an update message
					{
						$updateSaleDetailsQuery = "UPDATE SaleRecords
						SET payMethod = '" . $newMethod . "' 
						WHERE saleID=" . $sale . "";
	
	
						$updateSaleDetailsResult = @mysqli_query($conn, $updateSaleDetailsQuery)		
									   or die('Couldnt update the sale\'s details');
										
						echo "<p>The pay method of the sale has been changed!</p>";
					}	
				}	
			}
			else
			{
				echo"<p>The total cost must be a positive number</p>";
			}
		}	
		else
		{
			echo "<p>Some of the details were either blank or zero. Please fix them before editting the sale.</p>";  // Else if wrong input was entered display an error message to the screen
		}
	
	}
	else
	{
		echo "<p>Some of the sale's details were not set. Please fill them in to continue editting.</p>";
	}
	
	
	$checkAmountQuery = "SELECT  itemID, saleAmt FROM SaleLines	
			     WHERE saleID = ". $sale . "";
								
	$checkAmountResults = @mysqli_query($conn, $checkAmountQuery)			//Grabbing all the items and their amount of the sale the user is editting
			      or die('Couldnt get the sales item information');

	$index = 0;
	$storedStockAmounts = array();
	$stock_pattern = "/^[0-9]+$/";
	
	while($checkAmount = mysqli_fetch_row($checkAmountResults))			//Checking if the pre existing item's new stock was a valid input from the editSale form 
	{
		$updateAmount = $_POST["" . $checkAmount[0] . ""];
		$storedStockAmounts[$index] = $updateAmount; 
		
		if(!preg_match($stock_pattern, $updateAmount) || $updateAmount == 0)  
		{
			$valid = false;
			break;
		}
		
		$index++;
	}
	
	
	$amount_pattern = "/^[0-9]+$/";
	$checkNewItems = array();
	$itemIdArray = array();
	$valid1 = true;
		
	for($i = 0; $i < $newItemsNumber; $i++)
	{
		$newItem_sAmount = $_POST["itemAmt" . $i . ""];				//Checking if the details of the newly added items from the edit sale form where valid inputs.
		$checkNewItems[$i] = $newItem_sAmount;
			
		$newItem_sId = $_POST["item" . $i . ""];
		$itemIdArray[$i] = $newItem_sId;	
			
		if(!preg_match($amount_pattern, $newItem_sAmount) || $newItem_sAmount == 0)  
		{
			$valid1 = false;
			break;
		}
	}
	
	
	$index = 0;
	$salesItemsDetailsQuery = "SELECT  itemID, saleAmt FROM SaleLines	
				   WHERE saleID = ". $sale . "";
								
	$salesItemsDetailsResults = @mysqli_query($conn, $salesItemsDetailsQuery)	      //Grabbing all the items and their amount of the sale the user is editting
				    or die('Couldnt get the sales item information');
	
	
	
	if($valid != false && $valid1 != false)						      //If both of the pre-existing items and the newly added ones are both valid inputs then..
	{
		while($salesItemsDetails = mysqli_fetch_row($salesItemsDetailsResults))		//Going through all of the items
		{
			if($storedStockAmounts[$index] != $salesItemsDetails[1])		//If the user has changed the item amount then do...
			{	
				$getOldItemStockQuery = "SELECT stockAmt FROM Items 
							WHERE itemID = '" . $salesItemsDetails[0] . "'";
			
				$getOldItemStockResult = @mysqli_query($conn, $getOldItemStockQuery)		
							or die('Couldnt get the old item stock');
			
				while($row = mysqli_fetch_row($getOldItemStockResult))
				{
					$oldItemStock = $row[0];				// Get the old item Stock
				}
			
				if($storedStockAmounts[$index] < $salesItemsDetails[1])		// if the new sale amount from the user is less that the old one, then that means that user is returning items so the general 
				{											//stock of the returned item will be increase depending on the difference between the old sale amount and the new
				
					$stockDiffernce = $salesItemsDetails[1] - $storedStockAmounts[$index];
					$newStockAmount = $oldItemStock + $stockDiffernce;
				
					$updateStockAmountQuery = "UPDATE Items 
								   SET stockAmt = '" . $newStockAmount . "'
								   WHERE itemID = '" . $salesItemsDetails[0] . "'";
					
					$updateStockAmoutResult = @mysqli_query($conn, $updateStockAmountQuery)		//Update the stock amount of the item
								  or die('Couldnt update the item\'s stock');
					
																								
					
					$updateSaleAmountQuery = "UPDATE SaleLines 
								  SET saleAmt = '" . $storedStockAmounts[$index] . "'
								WHERE itemID = '" . $salesItemsDetails[0] . "' AND saleID = '" . $sale . "'"; 		//Update the sale item amount and display the messages
												
					$updateSaleAmoutResult = @mysqli_query($conn, $updateSaleAmountQuery)		
								 or die('Couldnt update the sales\'s amount');
												
					echo "<p>Item with ID :" . $salesItemsDetails[0] . " was updated successfully!</p>";	
				}
				else 												//However, if the new sale amount that the user has typed in is higher that the old amount in the database, then deduct the 
				{																								// the difference
					$stockDiffernce = $storedStockAmounts[$index] - $salesItemsDetails[1];
					$generalStock = $oldItemStock + $salesItemsDetails[1];
				
					if($storedStockAmounts[$index] <= $generalStock)					// If the new sale number the user typed in is not higher than the overall stock then the sale cannot be made
					{																// because the stock is not enough to support the users demand. The update will not be registered and they website																		
						$newStockAmount = $oldItemStock - $stockDiffernce;				// Will display an error message. If not it will update the item stock and the sale accordingly 
				
				
						$updateStockAmountQuery = "UPDATE Items 
									  SET stockAmt = '" . $newStockAmount . "'
									   WHERE itemID = '" . $salesItemsDetails[0] . "'";
									
						$updateStockAmoutResult = @mysqli_query($conn, $updateStockAmountQuery)		// Updating overall item stock
									  or die('Couldnt update the item\'s stock');
									
						$updateSaleAmountQuery = "UPDATE SaleLines 
									  SET saleAmt = '" . $storedStockAmounts[$index] . "'
									  WHERE itemID = '" . $salesItemsDetails[0] . "' AND saleID = '" . $sale . "'";
												
						$updateSaleAmoutResult = @mysqli_query($conn, $updateSaleAmountQuery)		//Updating the item amount of the editted sale
									  or die('Couldnt update the sales\'s amount');
				
						echo "<p>Item with ID :" . $salesItemsDetails[0] . " was updated successfully!</p>";
					}
					else
					{
						echo "<p>Invalid sale Amount for item with ID :" . $salesItemsDetails[0] . ". Sale amount was not updated.</p>";
					}
				}
			}
			else
			{
				echo "<p>No changes were made for item with ID:" . $salesItemsDetails[0] . "</p>";  //Displaying message in case the item sale amount was not changed
			}				
			
			$index++;
		}
		
		
		for($i = 0; $i < $newItemsNumber; $i++)								//Grabbing all the newly added items and their amount of the sale the user is entering
		{
			$itemAmount = $_POST["itemAmt" . $i . ""];
			$itemInformationQuery = "SELECT itemName, stockAmt FROM Items	
						WHERE itemID = ". $itemIdArray[$i] . "";
								
			$itemInformationResult = @mysqli_query($conn, $itemInformationQuery)					
						or die('Couldnt get the item\'s information');
				
			while($itemInformation = mysqli_fetch_row($itemInformationResult))
			{	
				if($itemInformation[1] > $itemAmount)  						//Checks if the amount of the new item is less than the available stock
				{
					$preExistingItemQuery = "SELECT saleAmt FROM SaleLines	
								WHERE itemID = ". $itemIdArray[$i] . " AND saleID = " . $sale . "";
					
					$preExistingItemResult = @mysqli_query($conn, $preExistingItemQuery)		
								or die('Couldnt search for the new item in the sale');		//checks to see if the new item the user is adding exists in the original sale
												
					$existence = mysqli_fetch_row($preExistingItemResult);
					$difference = $itemInformation[1] - $itemAmount;
					
		
					if($existence[0] == NULL)								//If it doesnt, then the code with insert the details of that item into the SaleLines table of the datebase 
					{	
						$InsertStockAmountQuery = "INSERT INTO SaleLines (saleID, itemID, saleAmt) 
									   VALUES ('" . $sale . "', '" . $itemIdArray[$i] . "', '" . $itemAmount . "')";
					
						$InsertStockAmoutResult = @mysqli_query($conn, $InsertStockAmountQuery)		
									  or die('Couldnt insert new item into the sale');

						$updateStockAmount2Query = "UPDATE Items 
									    SET stockAmt = '" . $difference . "'
									    WHERE itemID = '" . $itemIdArray[$i] . "'";
												
						$updateStockAmount2Result = @mysqli_query($conn, $updateStockAmount2Query)	 // Updating overall item stock
									    or die('Couldnt update the item\'s stock');
													
						echo"<p>Item with ID:" . $itemIdArray[$i] . " - " . $itemInformation[0] . "  was successfully inserted into the sale!!!</p>";
					}
					else											//If the item DOES exists however, update that line of the table with the amount the customer is buying
					{
						$addition = $existence[0] + $itemAmount;
						
						$updateSaleAmountQuery = "UPDATE SaleLines 
									  SET saleAmt = '" . $addition . "'
									  WHERE itemID = '" . $itemIdArray[$i] . "'";
						
						$updateSaleAmountResult = @mysqli_query($conn, $updateSaleAmountQuery)		// Updating item sale amount
									  or die('Couldnt update the item\'s stock');
						
						$updateStockAmount2Query = "UPDATE Items 
									    SET stockAmt = '" . $difference . "'
									    WHERE itemID = '" . $itemIdArray[$i] . "'";
						
						$updateStockAmount2Result = @mysqli_query($conn, $updateStockAmount2Query)		// Updating overall item stock
									    or die('Couldnt update the item\'s stock');
													
						echo"<p>Item with ID:" . $itemIdArray[$i] . " - " . $itemInformation[0] . "  was successfully updated into the sale!!!</p>";
					}
				}
				else
				{
					echo "<p>The amount of newly added item with ID:" . $itemIdArray[$i] . " - " . $itemInformation[0] . " exceeded the available stock. Please insert a valid stock amount. Item was not added.</p>"; 		
				}	//If the user request an amount that is larger than the stock of the item then it will display a message saying that they can add 
			}				//add the item due to insufficient stock
		}	
	}
	else
	{
		echo "<p>The stock amount of an item was not a positive number. Please type in a positive number to edit the sale.</p>";  //Displaying message when the user enters wrong input
	}
?>
<a href="saleDisplay.php">Return to Display sales</a>
</div>
<footer>
<?php
	include("footer.php");
?>
</footer>
</body>
</html>
