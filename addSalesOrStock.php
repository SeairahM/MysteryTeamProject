<?php require_once("checkLogin.php"); ?>
<!DOCTYPE=html>
<html lang='en'>
<head>
  <?php include("head.php"); ?>
   <link rel="stylesheet" href="PHP_SR_StyleSheet.css">
   <script src="add_sales_script.js"></script>
</head>
<body>
  <?php include("navigation.php"); ?>
  <div>
  <h1>Add Sales/Stock</h1>
  <?php
    //increment line num
    if (!isset($_GET["newline"])) {
      $linesnum = 1;
    }
    else {
      $linesnum = (int)$_POST["linesnum"] + 1;
    }
    if (isset($_GET["linesnum"])) {
      $linesnum = (int)$_GET["linesnum"];
    }
  ?>
  <span hidden id="linesnum"><?php echo $linesnum; ?></span>
<?php
  //db connection - from dbconn.php
  require_once("dbconn.php");
  $conn = $DBConn;

  //get lists of item names
  $sql = "SELECT itemID, itemName, stockAmt FROM Items";
  $result = $conn->query($sql);
  //input sales form
  echo "<form action=\"validateSalesOrStock.php\" method=\"POST\" id =\"form_process\">";
  //hidden input of sale lines no
  echo "<input hidden type=\"text\" name=\"linesnum\" value=\"". $linesnum. "\" />";
  //iterate over lines of sales
  $i = 0;
  while ($i < $linesnum) {
    //select item from list
    $j = 0;
    echo "<label for=\"itemline_". $i. "\">Item ID</label>";
    echo "<select name=\"itemID_". $i. "\" id=\"itemline_". $i. "\">";
    while ($j < $result->num_rows) {
    //1 itemID per option
      while ($row = $result->fetch_assoc()) {
        echo "<option value=\"". $row["itemID"]. "\">". $row["itemID"]. "-". $row["itemName"]. "-". $row["stockAmt"]. " in stock</option>";
      }
      $j += 1;
    }
    echo "</select>";
    echo "<label for=\"amtline_". $i. "\">Item Amount</label>";
    echo "<input type=\"text\" id=\"amtline_". $i. "\" name=\"itemAmount_". $i. "\" pattern=\"-?\d+\"/>";
    $i += 1;
    echo "<br />";
    //redo query for additional sale lines
    mysqli_free_result($result);
    $result = $conn->query($sql);
  }

  echo "<label for=\"addwhatstock\">Add Stock</label>";
  echo "<input type=\"radio\" id=\"addwhatstock\" name=\"addwhat\" value=\"stock\" checked=\"checked\"/>";
  echo "<label for=\"addwhatsales\">Add Sales</label>";
  echo "<input type=\"radio\" id=\"addwhatsales\" name=\"addwhat\" value=\"sales\" />";
  echo "<input type=\"submit\" value=\"Check Item Amount\" />";
  echo "</form>";
?>
<!-- add sales line -->
<form action="addSalesOrStock.php?newline=y" method="POST" id="button_new_line">
  <input hidden type="text" name="linesnum" value=<?php echo $linesnum; ?> />
  <input type="submit" value="Add new line" />
</form>
<!-- reset button -->
<a href="addSalesOrStock.php" id="button_reset">Reset form</a>
</div>
<footer>
  <?php include("footer.php"); ?>
</footer>
</body>
</html>
