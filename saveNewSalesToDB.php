<?php require_once("checkLogin.php"); ?>
<!DOCTYPE=html>
<html lang='en'>
<head>
  <?php include("head.php"); ?>
  <link rel="stylesheet" href="PHP_SR_StyleSheet.css">
</head>
<body>
<?php include("navigation.php"); ?>
<div>
<?php
  if (!isset($_POST["totalcost"])) {
    header("Location: index.php");
  }
  //db connection from dbconn
  require_once("dbconn.php");
  $conn = $DBConn;

  //insert sale record
  $sql = "INSERT INTO SaleRecords (totalCost, payMethod, dateTime) VALUES ". "(".
  "'". $_POST["totalcost"]. "', '". $_POST["paymethod"]. "', '". $_POST["datetime"]. "')";
  $result = $conn->query($sql);
  if ($result) {
    echo "<p>Sale record saved.</p>";
    //insert sale lines
    $linesnum = (int)$_POST['linesnum'];
    $i = 0;
    //get saleID of latest record
    $sql = "SELECT saleID FROM SaleRecords ORDER BY saleID DESC LIMIT 1";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
      $saleID = $row["saleID"];
    }
    if ($result) {
      echo "<p>Sale ID is ". $saleID. ".</p>";
      mysqli_free_result($result);
    }
    //insert sale line(s)
    while ($i < $linesnum) {
      $currentItemID = "itemID_" . $i;
      $currentAmtID = "itemAmount_" . $i;
      $leftID = "leftamt_" . $i;
      $sql = "INSERT INTO SaleLines (saleID, itemID, saleAmt)
      VALUES". "(". "'" . $saleID. "', '". $_POST[$currentItemID]. "', '". $_POST[$currentAmtID]. "')";
      $result = $conn->query($sql);
      if ($result) {
        echo "<p>Sale line saved.</p>";
      }
      else {
        echo "<p>Failed to save sale line of item number". $_POST[$currentItemID]. ", please contact tech support.</p>";
      }
      $i += 1;
      //modify item amount for each sale line
      $sql = "UPDATE Items SET stockAmt = \"". $_POST[$leftID]. "\" WHERE itemID = \"". $_POST[$currentItemID]. "\"";
      // echo "<p>Query is:". $sql. "</p>";
      $result = $conn->query($sql);
      if ($result) {
        echo "<p>Sale stock updated.</p>";
      }
      else {
        echo "<p>Failed to update stock of item number ". $_POST[$currentItemID] . ", please contact tech support.</p>";
      }
    }
    echo "<p>". $i. " Sale Lines processed.</p>";
  }
  else {
    echo "<p>Failed to save sale record.</p>";
  }
  echo "<a href=\"addSalesOrStock.php\">Back to Add Stock/Sales</a>";
?>
</div>
<footer>
  <?php include("footer.php"); ?>
</footer>
</body>
</html>
