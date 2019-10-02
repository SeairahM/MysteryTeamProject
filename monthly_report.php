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
<?php

 ?>
</div>
</body>
</html>
