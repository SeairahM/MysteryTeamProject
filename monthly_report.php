<!DOCTYPE=html>
<html lang='en'>
<head>
  <link rel="stylesheet" href="PHP_SR_StyleSheet.css">
  <script src="reportScript.js"></script>
</head>
  <?php
  require_once("dbconn.php");
  $conn = $DBConn;
  //get current date
  date_default_timezone_set('Australia/Melbourne');
  $currentmonth = date('m');
  $currentyear = date('Y');
  ?>
  <?php
  //save category details into 2 arrays for dropdown in filter report form
  $categoryIDs = array();
  $categoryNames = array();
  $sql = "SELECT categoryID, categoryName FROM Categories";
  $result = $conn->query($sql);
  $i = 0;
  while ($row = $result->fetch_assoc()) {
    $categoryIDs[$i] = $row["categoryID"];
    $categoryNames[$i] = $row["categoryName"];
    $i += 1;
  }
  mysqli_free_result($result);
  ?>
<body>
<?php include("navigation.php"); ?>
  <div class='container'>
  <h1>Monthly Report</h1>
  <h2>Current month: <?php echo $currentmonth; ?>/<?php echo $currentyear; ?></h2>
  <!--form to filter report-->
  <form id='filterform' method='post' action='monthly_report.php'>
    <label for='repcat'>Category</label>
    <select id='repcat' name='itemCategory'>
      <option value='0' id='0'>No filter</option>
      <?php
      //show category dropdown
      $j = 0;
      while ($j < $i) {
        echo "<option value=\"". $categoryIDs[$j]. "\" id=\"". $categoryIDs[$j]. "\"";
        echo ">". $categoryNames[$j]. "</option>";
        $j += 1;
      }
      ?>
    </select>
    <label for='repmonth'>Report for: </label>
    <select id='repmonth' name='rep_month' required='required'>
      <?php
      //echo 12 months, precheck current month
      $i = 0;
      while ($i < 12) {
        echo "<option value=\"". ($i + 1). "\" id=\"". ($i + 1). "\"";
        if (!isset($_POST["rep_month"]) && $i + 1 == $currentmonth) {
          echo " selected ";
        }
        echo ">". ($i + 1). "</option>";
        $i += 1;
      }
      ?>
      <!--text input for year, prefill current year-->
      <label for='repyear'> in </label>
      <input type='text' name='rep_year' id='repyear' value='<?php echo $currentyear; ?>' pattern="\d{4}" placeholder="YYYY" required='required' />
      <!--submit-->
      <input type='submit' value='Generate report' />
    </select>
  </form>
    <?php
    //invalid input msg
    if (isset($_GET["valid"])) {
      if ($_GET["valid"] == 'n') {
        echo "<p>Invalid input. Please try again.</p>";
      }
    }
    //check filter form
    $displayCat = "No filter";
    if (isset($_POST["rep_month"])) {
      $filtermonth = $_POST["rep_month"];
      $filteryear = $_POST["rep_year"];

      if ($_POST["itemCategory"] != "0") {
        $filtercat = " AND itemCategory = \"". $_POST["itemCategory"]. "\" ";
        $displayCat = $categoryNames[$_POST["itemCategory"] - 1];
      }
      else {
        $filtercat = "";
      }
    }
    else {
      $filtermonth = $currentmonth;
      $filteryear = $currentyear;
      $filtercat = "";
    }
    //display filter criteria
    echo "<p>Displaying month: ". $filtermonth. "</p>";
    echo "<p>Displaying year: ". $filteryear. "</p>";
    echo "<p>Category: ". $displayCat. "</p>";
   ?>

 <form method='post' action='download.php'>
   <input hidden type='text' name='repmonth' value='<?php echo $filtermonth; ?>' />
   <input hidden type='text' name='repyear' value='<?php echo $filteryear; ?>' />
   <input hidden type='text' name='repcat' value='<?php if ($filtercat == "") {echo "Nofilter";} else {echo $displayCat;} ?>' />

  <table border='1' style='border-collapse:collapse;'>
    <tr>
     <th>Category</th>
     <th>Item ID</th>
     <th>Item Name</th>
     <th>Number of Sales</th>
     <th>Remaining Stock</th>
    </tr>
    <?php
     $query2 = "SELECT itemCategory, itemID, itemName, stockAmt, COUNT(*) AS counts FROM SaleLines NATURAL JOIN SaleRecords
     NATURAL JOIN Items WHERE MONTH(dateTime) = ". $filtermonth. " AND YEAR(dateTime) = ". $filteryear. $filtercat. " GROUP BY itemID";
     $results = mysqli_query($conn,$query2);

     $record_arr = array();
     while($row = mysqli_fetch_array($results)){
      $cat = $categoryNames[$row['itemCategory'] - 1];
      $id = $row['itemID'];
      $name = $row['itemName'];
      $sAmount = $row['counts'];
      $ItemAmount = $row['stockAmt'];
      $record_arr[] = array($cat,$id,$name,$sAmount, $ItemAmount);
   ?>
      <tr>
       <td><?php echo $cat; ?></td>
       <td><?php echo $id; ?></td>
       <td><?php echo $name; ?></td>
       <td><?php echo $sAmount; ?></td>
       <td><?php echo $ItemAmount?></td>
      </tr>
   <?php
    }
    mysqli_free_result($results);
   ?>
   </table>
    <input type='submit' value='Export' name='Export'>
   <?php
    $serialize_record_arr = serialize($record_arr);
   ?>
  <textarea name='export_data' style='display: none;'><?php echo $serialize_record_arr; ?></textarea>
 </form>
</div>
<!-- Monthly sales
New form for csv -> accesses downloadMonthReport.php
 -->
<div>
<form method='post' action='downloadMonthReport.php'>
<table border='1' style='border-collapse:collapse;'>
<h2>Last Month Sales</h2>
<tr>
     <th>Category</th>
     <th>Number of Sales</th>
     <th>Number of Items Sold</th>
     <th>Remaining Stock</th>
     <th>DateTime</th>
    </tr>
<?php

//get last 30 days sales ect
$query3 = "SELECT itemCategory, itemID, itemName, dateTime, stockAmt, saleAmt, COUNT(*) AS counts FROM SaleLines NATURAL JOIN SaleRecords
NATURAL JOIN Items WHERE datetime BETWEEN NOW() - INTERVAL 30 DAY AND NOW() GROUP BY itemID";
$results3 = mysqli_query($conn,$query3);
  $record_arr1 = array();
     while($row = mysqli_fetch_array($results3)){
      $cat = $categoryNames[$row['itemCategory'] - 1];
      $sAmount = $row['counts'];
      $ItemAmount = $row['stockAmt'];
      $saleAMT = $row["saleAmt"];
      $time = $row['dateTime'];
      $record_arr1[] = array($cat,$sAmount,$saleAMT, $ItemAmount);
   ?>
      <tr>
       <td><?php echo $cat; ?></td>
       <td><?php echo $sAmount; ?></td>
       <td><?php echo $saleAMT;?></td>
       <td><?php echo $ItemAmount?></td>
       <td><?php echo $time; ?></td>
      </tr>
      <?php
      }
    mysqli_free_result($results3);
  ?>
  </table>
  <input type='submit' value='Export' name='Export'>
   <?php
    $serialize_record_arr1 = serialize($record_arr1);
   ?>
  <textarea name='export_data' style='display: none;'><?php echo $serialize_record_arr1; ?></textarea>
 </form>
</div>


<!--Item Predictions -->
<div>

<form method='post' action='downloadPredictionItems.php'>
<table border='1' style='border-collapse:collapse;'>
<h2>Next Month Prediction</h2>
<h4> Grouped by Items</h4>
<p>In the "Stock To Be Bought" columns, the negative items mean you have enough in stock.</p>
<p>The positive number is how much you need to buy for the next month.</p>
<tr>

     <th>Item Name</th>
     <th>Predicted Items to be Sold</th>
     <th>Stock To Be Bought</th>
    </tr>
<?php
// gets average sales
$query3 = "SELECT itemName, AVG(saleAmt) as predictedItemSales, saleAmt-stockAmt as toBuy FROM Items NATURAL JOIN saleLines NATURAL JOIN SaleRecords GROUP BY itemName, MONTH(dateTime)";

$results3 = mysqli_query($conn,$query3);
  $record_arr2 = array();

     while($row = mysqli_fetch_array($results3)){
      $name = $row['itemName'];
      $predicted = $row['predictedItemSales'];
      $itemNeeded = $row['toBuy'];
      $record_arr2[] = array($name,$predicted, $itemNeeded);

   ?>
      <tr>
       <td><?php echo $name; ?></td>
       <td><?php echo $predicted; ?></td>
       <td><?php echo $itemNeeded;?></td>
      </tr>
      <?php
      }
    mysqli_free_result($results3);
  ?>
  </table>
  <input type='submit' value='Export' name='Export'>
   <?php
    $serialize_record_arr2 = serialize($record_arr2);
   ?>
  <textarea name='export_data' style='display: none;'><?php echo $serialize_record_arr2; ?></textarea>
 </form>
</div>

<!--Category Predictions -->

<div>
<form method='post' action='downloadCategoryPrediction.php'>
<table border='1' style='border-collapse:collapse;'>
<h4>Grouped by Category</h4>
<tr>
     <th>Category</th>
     <th>Predicted Items to be Sold</th>
    </tr>
<?php
//get last 30 days sales ect
$query3 = "SELECT itemCategory, AVG(saleAmt) as predictedItemSales FROM Items NATURAL JOIN saleLines NATURAL JOIN SaleRecords GROUP BY itemCategory, MONTH(dateTime)";

$results3 = mysqli_query($conn,$query3);
  $record_arr3 = array();
     while($row = mysqli_fetch_array($results3)){
      $cat = $categoryNames[$row['itemCategory'] - 1];
      $predicted = $row['predictedItemSales'];
      $record_arr3[] = array($cat,$predicted);
   ?>
      <tr>
       <td><?php echo $cat; ?></td>
       <td><?php echo $predicted; ?></td>
      </tr>
      <?php
      }
    mysqli_free_result($results3);
  ?>
  </table>
  <input type='submit' value='Export' name='Export'>
   <?php
    $serialize_record_arr3 = serialize($record_arr3);
   ?>
  <textarea name='export_data' style='display: none;'><?php echo $serialize_record_arr3; ?></textarea>
 </form>
</div>

</body>
</html>
