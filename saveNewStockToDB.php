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
  if (!isset($_POST["linesnum"])) {
    header("Location: index.php");
  }
  //db connection from dbconn
  require_once("dbconn.php");
  $conn = $DBConn;

  //update item stock
  $i = 0;
  $linesnum = (int)$_POST['linesnum'];
  while ($i < $linesnum) {
    $currentItemID = "itemID_" . $i;
    $leftID = "leftamt_" . $i;
    $i += 1;
    //modify item amount for each sale line
    $sql = "UPDATE Items SET stockAmt = \"". $_POST[$leftID]. "\" WHERE itemID = \"". $_POST[$currentItemID]. "\"";
    // echo "<p>Query is:". $sql. "</p>";
    $result = $conn->query($sql);
    if ($result) {
      echo "<p>Item stock updated.</p>";
    }
    else {
      echo "<p>Failed to update stock of item number ". $_POST[$currentItemID] . ", please contact tech support.</p>";
    }
  }
  echo "<p>Stocking of ". $i. " items processed.</p>";
  echo "<a href=\"addSalesOrStock.php\">Back to Add Stock/Sales</a>";
?>
</div>
<footer>
  <?php include("footer.php"); ?>
</footer>
</body>
</html>
