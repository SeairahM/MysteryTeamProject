<!DOCTYPE=html>
<html lang='en'>
<head>
</head>
<body>
  <h1>Add New Sales Record</h1>
  <?php
    //increment line num
    if (!isset($_GET["newline"])) {
      $linesnum = 1;
    }
    else {
      $linesnum = (int)$_POST["linesnum"] + 1;
    }
  ?>
  <p id="linesnum"><?php echo $linesnum; ?></p><p> items sold.</p>
<?php
  //db connection - from dbconn.php
  require_once("dbconn.php");
  $conn = $DBConn;

  //get lists of item names
  $sql = "SELECT itemID, itemName FROM Items";
  $result = $conn->query($sql);
  echo "<p>". $result->num_rows. "rows</p>";
  //input sales form
  echo "<form action=\"add_sales_process.php?submit=n\" method=\"POST\" id =\"form_process\">";
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
        echo "<option value=\"". $row["itemID"]. "\">". $row["itemID"]. "-". $row["itemName"]. "</option>";
      }
      $j += 1;
    }
    echo "</select>";
    echo "<label for=\"amtline_". $i. "\">Item Amount</label>";
    echo "<input type=\"text\" id=\"amtline_". $i. "\" name=\"itemAmount_". $i. "\" />";
    $i += 1;
    echo "<br />";
    //redo query for additional sale lines
    mysqli_free_result($result);
    $result = $conn->query($sql);
  }
  echo "<input type=\"submit\" value=\"Calculate price\" />";
  echo "</form>";
?>
<!-- add sales line -->
<form action = "add_sales.php?newline=y" method="POST" id="button_new_line">
  <input hidden type="text" name="linesnum" value=<?php echo $linesnum; ?> />
  <input type="submit" value="Add sales line" />
</form>
</body>
</html>
