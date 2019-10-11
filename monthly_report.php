<!DOCTYPE=html>
<html lang='en'>
<head>
</head>
<?php
  require_once("dbconn.php");
  $conn = $DBConn;
  //get current date
  date_default_timezone_set('Australia/Melbourne');
  $currentmonth = date('m');
  //count sales for each item in month
  $sql = "SELECT itemID, itemName, COUNT(*) AS counts FROM SaleLines NATURAL JOIN SaleRecords
  NATURAL JOIN Items WHERE MONTH(dateTime) = ". $currentmonth. " GROUP BY itemID";
  $result = $conn->query($sql);
 ?>
<body>
<?php include("navigation.php"); ?>
  <div>
  <h1>Monthly Report</h1>
  <h2>Current month: <?php echo $currentmonth; ?></h2>
  <section>
    <table>
      <tr>
        <th>Item ID</th>
        <th>Item Name</th>
        <th>Sale Amount</th>
      </tr>
      <?php
      while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>". $row["itemID"]. "</td>";
        echo "<td>". $row["itemName"]. "</td>";
        echo "<td>". $row["counts"]. "</td>";
        echo "</tr>";
      }
      ?>
    </table>
  </section>
</div>

<div class="container">
 <form method='post' action='download.php'>
 
  <table border='1' style='border-collapse:collapse;'>
    <tr>
     <th>Item ID</th>
     <th>Item Name</th> 
     <th>Number of Sales</th>
     <th>Remaining Stock</th>
     <th>DateTime</th>
    </tr>
    <?php 
     $query2 = "SELECT itemID, itemName, dateTime, stockAmt, COUNT(*) AS counts FROM SaleLines NATURAL JOIN SaleRecords
     NATURAL JOIN Items WHERE MONTH(dateTime) = ". $currentmonth. " GROUP BY itemID";
     $results = mysqli_query($conn,$query2);

     $record_arr = array();
     while($row = mysqli_fetch_array($results)){
      $id = $row['itemID'];
      $name = $row['itemName'];
      $sAmount = $row['counts'];
      $ItemAmount = $row['stockAmt'];
      $time = $row['dateTime'];
      $record_arr[] = array($id,$name,$sAmount,$ItemAmount,$time);
   ?>
      <tr>
       <td><?php echo $id; ?></td>
       <td><?php echo $name; ?></td>
       <td><?php echo $sAmount; ?></td>
       <td><?php echo $ItemAmount?></td>
       <td><?php echo $time; ?></td>
      </tr>
   <?php
    }
   ?>
   </table>
    <input type='submit' value='Export' name='Export'>
   <?php 
    $serialize_record_arr = serialize($record_arr);
   ?>
  <textarea name='export_data' style='display: none;'><?php echo $serialize_record_arr; ?></textarea>
 </form>
</div>
</body>
</html>
