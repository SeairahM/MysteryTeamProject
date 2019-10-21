<?php
echo "<header id='navigation'>
		<img src=\"images/PHP_SR_logo.png\" alt='Logo'/>
		<a href='signout.php'>SIGN-OUT</a>
		<nav>
		    <div class='dropdown'>
			    <button class='dropbtn'>SALES 
			      <i class='fa fa-caret-down'></i>
			    </button>
			    <div class='dropdown-content'>
			    <!-- ADD SALES PAGE LINKS HERE!!! -->
			      <a href='addSalesOrStock.php'>ADD SALES/STOCK</a>
			      <a href='saleDisplay.php'>DISPLAY SALES</a>
			      <a href='monthly_report.php'>REPORTS</a>
			    </div>
			</div>
			<div class='dropdown'>
			    <button class='dropbtn'>ITEMS 
			      <i class='fa fa-caret-down'></i>
			    </button>
			    <div class='dropdown-content'>
			    <!-- ADD ITEM PAGE LINKS HERE!!! -->
						<a href='addItemForm.php'>ADD ITEMS</a>
						<a href='displayItems.php'>DISPLAY ITEMS</a>
						<a href='addCategory.php'>ADD CATEGORY</a>
						<a href='deleteCategory.php'>DELETE CATEGORY</a>
			    </div>
			</div> 		
			<a href='addUser.php'>USERS</a>			
		</nav>
	</header>";
?>
