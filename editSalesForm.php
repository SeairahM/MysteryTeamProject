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
?>
<div>
<?php 
	require_once("dbconn.php");
	$conn = $DBConn;

	if(isset($_POST["edit"]))
	{
		$sale = $_POST["edit"];	//if the user is clicking the edit button from the display Sales page, set and get the right variables
		$newItems = 0;
	}
	else
	{
		if(isset($_POST["addItem"]))	//else if the user is clicking the add new button  set the right variables
		{	
			$sale = $_POST["addItem"];
			$increment = $_POST["increment"];
			$newItems = $increment + 1;
		}
		else if(isset($_POST["removeItem"]))			//else if the user is clicking the remove new item button, set and get the right variables
		{
			$sale = $_POST["removeItem"];
			$decrement = $_POST["decrement"];
			if($decrement == 0)					//If the user tries to click the remove new item button 
			{								//when there are no new items to remove, display and error message. Else just procceed witht the removal
				echo "<p>You've got no new items to remove!</p>";  
				$newItems = 0;
			}
			else						
			{	
				$newItems = $decrement - 1;			
			}
		}
		else
		{
			$sale = $_POST["saleOfItem"];		//else that means that the user has clicked on the delete pre-existing item button
			$saleItemsArray = array();
			$index = 0;
			
			
			$checkIfEmptyQuery = "SELECT itemID FROM SaleLines WHERE saleID = '" . $sale . "'";
			
			$checkIfEmptyResult = @mysqli_query($conn, $checkIfEmptyQuery)		//Getting the sale information of the sale the user clicked to edit
					      or die('Couldnt check if it\'s empty');
								
			while($checkIfEmpty = mysqli_fetch_row($checkIfEmptyResult))
			{
				$saleItemsArray[$index] = $checkIfEmpty[0];			//checks the number of the elements in the array which contains the number of items in the sale
				$index++;
			}
			
			if(count($saleItemsArray) != 1)			//if there are more than 1 items in the array then procceed with the deletion
			{
				$newItems = 0;
				$itemToDelete = $_POST["deleteItem"];
			
				$deleteItemQuery = "DELETE FROM SaleLines
						    WHERE itemID = '" . $itemToDelete . "' AND saleID = '" . $sale . "'";			
							
				echo "<p>Item with ID :" . $itemToDelete . " was deleted successfully! </p>";
					
				$deleteItemResult = @mysqli_query($conn, $deleteItemQuery)		
						    or die('Couldnt delete the item from the sale1');
			}
			else						//else display an error message saying that you cant delete the last item in the sale
			{	
				$newItems = 0;
				echo "<p>You cant delete the last item of a sale!!!</p>";
			}
		}
	}
	
	
	$saleItemsQuery = "SELECT SaleLines.itemID, Items.itemName, SaleLines.saleAmt FROM SaleLines 
			   INNER JOIN Items
     			   ON SaleLines.itemID=Items.itemID    
			   WHERE saleID = '". $sale . "'";
								
	$saleItemsResults = @mysqli_query($conn, $saleItemsQuery)		//Getting the items and their details of the sale the user clicked to edit
			    or die('Couldnt get the sale details2');
	
	echo "<h2>Editting Sale No:$sale</h2>
	      <h3>Deleting Sales Items:</h3>";
	
	while($saleItems = mysqli_fetch_row($saleItemsResults))			//Display the items of the sale and a button to delete them for the sale 
	{
		echo " <form action = \"editSalesForm.php\" method = \"post\">
				<label>ItemID:" . $saleItems[0] . " " . $saleItems[1] . "</label><br>
				<label>Quantity: " . $saleItems[2] . "</label><br>
			    	<input type=\"hidden\" name=\"saleOfItem\" value=\"" . $sale . "\" />
				<input type =\"hidden\" name =\"deleteItem\" value = \"" . $saleItems[0] . "\"  />  		
				<input type =\"submit\" value = \"Delete Sale Item\" />
			  </form>
			  <br><br>";
	}

?>
	
<form action = "editSales.php" method = "post" >
<h3>General Sale Info, modify sales amount and add new Items!</h3>
<?php	
	$saleRecordQuery = "SELECT * FROM SaleRecords WHERE saleID = '" . $sale . "'";
	
	$saleRecordResult = @mysqli_query($conn, $saleRecordQuery)		//Getting the sale information of the sale the user clicked to edit
			    or die('Couldnt get the sale details1');
	
	while($saleDetails = mysqli_fetch_row($saleRecordResult))
	{
		$totalCost = $saleDetails[1];
		$payMethod = $saleDetails[2];				//Storing the sale general details in varibles so that we can use them freely later
		$dateAndTime = $saleDetails[3];			
	}
																							//Printing the basic sales details and a texboxes to change em
	echo "	<input type=\"hidden\" name=\"saleEdited\" value=\"" . $sale . "\" />   
			<input type=\"hidden\" name=\"newItems\" value=\"" . $newItems . "\" />
						
			<label>New Total Cost: </label>
			<input type=\"text\" name=\"cost\" value=\"" . $totalCost . "\" /><br>
						
			<label>New Payment Method: </label>
			<input type=\"text\" name=\"method\" value=\"" . $payMethod . "\" /><br><br>";	

	
	$saleItemsResults2 = @mysqli_query($conn, $saleItemsQuery)		//Getting the items and their details of the sale the user clicked to edit
			     or die('Couldnt get the sale details2');
	
	while($saleItems2 = mysqli_fetch_row($saleItemsResults2))		//Printing a textbox and for every item in the a sale with a textbox so the user can edit.
	{
		echo "<label>ItemID:" . $saleItems2[0] . " " . $saleItems2[1] . "</label><br>
			  <label>New Sale Quantity: </label>
			  <input type=\"text\" name=\"" . $saleItems2[0] . "\" value=\"" . $saleItems2[2] . "\" />	
			  <br><br>";
	}
	
	
	for($i = 0; $i < $newItems; $i++) 					//Prints a textbox and a dropdown box for every time we press the add new Item button
	{
		$availableItemsQuery = "SELECT itemID, itemName, stockAmt FROM Items";
	
		$availableItemsResult = @mysqli_query($conn, $availableItemsQuery)		
					or die('Couldnt get the sale details3');
		
		
		echo "<label>New Item ID</label>";
		echo "<select name=\"item" . $i . "\" >";
			echo "<option value=\"\"> --- </option>";
		
			while($oneItem = mysqli_fetch_row($availableItemsResult))
			{
				echo "<option value=\"" . $oneItem[0] . " \"> " . $oneItem[0] . " - " . $oneItem[1] . " - " . $oneItem[2]. " in stock </option>";  
			}
			echo "</select>";
			
			echo "<label>   Item amount</label>
				<input type=\"text\" name=\"itemAmt" . $i . "\" /><br><br>";			
	}		
?>
	<input type="submit" value="Submit Changes" />							
	<input type="reset" value="Reset" />
</form>		
</div>
<p>
<form action="editSalesForm.php" method = "post" >
	<input type="hidden" name="increment" value="<?php echo $newItems ?>" />		<!-- Add new Item button -->
	<input type="hidden" name="addItem" value="<?php echo $sale ?>" />
	<input type="submit" value="Add Item" />
</form>
<form action="editSalesForm.php" method = "post" >
	<input type="hidden" name="decrement" value="<?php echo $newItems ?>" />		<!-- Remove new Item button -->
	<input type="hidden" name="removeItem" value="<?php echo $sale ?>" />
	<input type="submit" value="Remove Item" />
</form>
</p>
<footer>
<?php
	include("footer.php");
?>
</footer>
</body>
</html>
