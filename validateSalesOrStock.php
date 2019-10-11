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
  //db connection from dbconn
  require_once("dbconn.php");
  $conn = $DBConn;

  //check for direct url
  if (!isset($_POST["addwhat"])) {
    header("Location: index.php");
  }
  else {
    if ($_POST["addwhat"] == "stock") { $addingstock = true; }
    else { $addingstock = false; $total = 0; }
  }
  //functions
  function validStock($stockflag, $inStock, $saleAmt) {
    if ($stockflag) { return ((int)$inStock + (int)$saleAmt) >= 0; }
    else {
      if ((int)$saleAmt < 0) { return false; }
      return ((int)$inStock - (int)$saleAmt) >= 0;
    }
  }
  //main display
  $valid = true;
  $leftstocks = array();
  $i = 0;
  //convert string to numeric?
  $linesnum = (int)$_POST["linesnum"];
  // $key = "itemID_". $i;
  // echo "<p>Item ID found at name ". $key. ":". $_POST[$key]. "</p>";
  while ($i < $linesnum) {
    $itemID = $_POST["itemID_" . $i];
    $amt = (int)$_POST["itemAmount_" . $i];
    //get lists of item names
    //separate queries for sale/stock
    if (!$addingstock) {
      $sql = "SELECT itemID, itemName, itemPrice, stockAmt FROM Items WHERE itemID = \"" . $itemID . "\"";
    }
    else {
      $sql = "SELECT itemID, itemName, stockAmt FROM Items WHERE itemID = \"" . $itemID . "\"";
    }
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
      //only calculate total if sales
      if (!$addingstock) {
        if ($amt >= 0) {
          $linePrice = $row["itemPrice"]*$amt;
          echo "<p>". $row["itemID"]. " - ". $row["itemName"]. " x ". $amt. " = ". $linePrice. "</p>";
        }
        else {
          echo "<p>". $row["itemID"]. " - ". $row["itemName"]. " x ". $amt. "</p>";
        }
      }
      else {
        echo "<p>Add: ". $row["itemID"]. " - ". $row["itemName"]. " x ". $amt. "</p>";
      }
      if (validStock($addingstock,$row["stockAmt"],$amt)) {
        if ($addingstock) { $leftinstock = (int)$row["stockAmt"] + $amt; }
        else { $leftinstock = (int)$row["stockAmt"] - $amt; }
        echo "<p>Valid item amount, ". $leftinstock . " left in stock.</p>";
        $leftstocks[$i] = $leftinstock;
      }
      else {
        echo "<p>Invalid item amount. Please check with support.</p>";
        $valid = false;
      }
    }
    if (!$addingstock && $valid) { $total += $linePrice; }
    mysqli_free_result($result);
    $i += 1;
  }

  //hidden input for items
  if ($valid) {
    if (!$addingstock) { require_once("sendValidatedSales.inc"); }
    else { require_once("sendValidatedStock.inc"); }
    //submit
    echo "<input type=\"submit\" value=\"Submit Record\" />";
  }
  else {
    echo "<p>Cannot proceed - invalid item amount(s) above.</p>";
  }
  echo "</form>";
  //prefill using localStorage via JS
  echo "<p><a href=\"addSalesOrStock.php?linesnum=". $linesnum. "\">Back to Add Sales/Stock</a></p>";
?>
</div>
<footer>
  <?php include("footer.php"); ?>
</footer>
</body>
</html>
