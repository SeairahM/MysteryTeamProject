<?php
require_once("dbconn.php");

echo("<h1>Checking and Creating Database Tables</h1>");

//CHECK AND CREATE USERS TABLE
if (tableExists($DBConn, "Users")){
    echo("<p>Users Table Exists</p>");
}
else{
    $query = "CREATE TABLE Users(userID INT AUTO_INCREMENT, username VARCHAR(50) NOT NULL, password VARCHAR(50) NOT NULL, firstName VARCHAR(50), lastName VARCHAR(50), PRIMARY KEY (UserID))";
    createTable($DBConn, $query);
    echo("<p>Created Users Table</p>");
}


//CHECK AND CREATE CATEGORIES TABLE
if (tableExists($DBConn, "Categories")){
    echo("<p>Categories Table Exists</p>");
}
else{
    $query = "CREATE TABLE Categories(categoryID INT AUTO_INCREMENT, categoryName VARCHAR(255) NOT NULL, categoryNote VARCHAR(255), PRIMARY KEY (categoryID))";
    createTable($DBConn, $query);
    echo("<p>Created Categories Table</p>");
}

//CHECK AND CREATE ITEMS TABLE
if (tableExists($DBConn, "Items")){
    echo("<p>Items Table Exists</p>");
}
else{
    $query = "CREATE TABLE Items(itemID INT AUTO_INCREMENT, itemName VARCHAR(255) NOT NULL, itemNote VARCHAR(255), itemCategory INT, stockAmt int, itemPrice decimal, PRIMARY KEY (itemID), FOREIGN KEY (itemCategory) REFERENCES Categories(categoryID))";
    createTable($DBConn, $query);
    echo("<p>Created Items Table</p>");
}


//CHECK AND CREATE SaleRecords TABLE
if (tableExists($DBConn, "SaleRecords")){
    echo("<p>SaleRecords Table Exists</p>");
}
else{
    $query = "CREATE TABLE SaleRecords(saleID INT AUTO_INCREMENT, totalCost decimal NOT NULL, payMethod VARCHAR(50) NOT NULL, dateTime DATETIME, PRIMARY KEY (saleID))";
    createTable($DBConn, $query);
    echo("<p>Created SaleRecords Table</p>");
}


//CHECK AND CREATE SaleLines TABLE
if (tableExists($DBConn, "SaleLines")){
    echo("<p>SaleLines Table Exists</p>");
}
else{
    $query = "CREATE TABLE SaleLines(saleLineID INT AUTO_INCREMENT, saleID INT NOT NULL, itemID INT NOT NULL, saleAmt INT NOT NULL, PRIMARY KEY (saleLineID), FOREIGN KEY (saleID) REFERENCES SaleRecords(saleID), FOREIGN KEY (itemID) REFERENCES Items(itemID))";
    createTable($DBConn, $query);
    echo("<p>Created SaleLines Table</p>");
}



/*
-------------------- SUPPORT FUNCTIONS -------------------
*/
function createTable($DBConn, $query){
    if ($result = mysqli_query($DBConn, $query)) {
        return TRUE;
    }
    else{
        return FALSE;
    }
}

function tableExists($DBConn, $table){
    $query = "SHOW TABLES LIKE '" . $table . "'";
    
    if ($result = mysqli_query($DBConn, $query)) {
        $numTables = mysqli_num_rows($result);  
        mysqli_free_result($result);
        if ($numTables == 1){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }
}


?>