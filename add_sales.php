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
      $linesnum = $_POST["linesnum"] + 1;
    }
  ?>
  <p id="linesnum"><?php echo $linesnum; ?></p><p> items sold.</p>
<?php
  //db connection - from dbconn.php
  $DBServer = "localhost";
  $DBUser = "admin";
  $DBPass = "MysteryTeam2019";
  $DB = "PHP";

  $conn = mysqli_connect($DBServer, $DBUser, $DBPass, $DB);

  //get lists of item names
  $sql = "SELECT itemID, itemName, itemPrice FROM Items";
  $result = $conn->query($sql);
  //input sales form
  echo "<form action=\"add_sales_process.php?submit=n\" method=\"POST\" id =\"form_process\"";
  echo "<input type=\"text\" name=\"Payment Method\" />";
  //iterate over lines of sales
  $i = 0;
  while ($i < $linesnum) {
    //select item from list
    $j = 0;
    echo "<select name=\"itemID\" id=\"itemline_". $i. "\">";
    while ($j < $result->num_rows) {
    //1 itemID per option
      while ($row = $result->fetch_assoc()) {
          echo "<option value=\"". $row["itemID"]. ">". $row["itemID"]. "-". $row["itemName"]. "</option>";
        }
      $j += 1;
    }
    echo "</select>";
    echo "<input type=\"text\" id=\"amtline_". $i. "\" name=\"itemAmount\" />";
    $i += 1;
    //redo query for additional sale lines
    mysqli_free_result($result);
    $result = $conn->query($sql);
  }
  echo "<input type=\"submit\" value=\"Calculate price\" />";
  echo "</form>";
?>
<!-- add sales line -->
<form action = "add_sales.php?newline=y" method="POST" id="button_new_line">
  <input hidden type="text" name="linesnum" value=<?php $linesnum ?> />
  <input type="submit" value="Add sales line" />
</form>
</body>
</html>
