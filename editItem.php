<?php
  require_once("checkLogin.php");
  if (!isset($_POST["itemID"])) {
    header("Location: displayItems.php");
  }
?>
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
  <h1>Edit Item ID <?php echo $_POST["itemID"]; ?></h1>
  <p>Note: Please input unit price as decimal (e.g 6.0)</p>
  <?php
  //db connection - from dbconn.php
  require_once("dbconn.php");
  $conn = $DBConn;

  //save category details into 2 arrays for dropdown in edit item form
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
  //get item list
  $sql = "SELECT * FROM Items WHERE itemID=\"". $_POST["itemID"]. "\"";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  ?>
  <form action="processEditItem.php" method="POST">
    <input type='text' hidden name='itemID' value='<?php echo $_POST["itemID"]; ?>' />
    <!--text fields for editable columns, with prefilled value-->
    <label for='itemname'>Item Name</label>
    <input type='text' id='itemname' name='itemName' value='<?php echo $row["itemName"]; ?>' /><br />
    <label for='itemnote'>Item Note</label>
    <input type='text' id='itemnote' name='itemNote' value='<?php echo $row["itemNote"]; ?>' /><br />
    <label for='itemstock'>Stock</label>
    <input type='text' id='itemstock' name='stockAmt' value='<?php echo $row["stockAmt"]; ?>' pattern='\d+' /><br />
    <label for='itemprice'>Unit Price</label>
    <input type='text' id='itemprice' name='itemPrice' value='<?php echo $row["itemPrice"]; ?>' pattern='\d+\.?\d+'/><br />
    <label for='itemcat'>Category</label>
    <select id='itemcat' name='itemCategory'>
    <?php
    $j = 0;
    //show category dropdown
    while ($j < $i) {
      echo "<option value=\"". $categoryIDs[$j]. "\"";
      //preset current category
      if ($categoryIDs[$j] == $row["itemCategory"]) {
        echo " selected ";
      }
      echo ">". $categoryNames[$j]. "</option>";
      $j += 1;
    }
    mysqli_free_result($result);
    ?>
  </select>
    <!--submit-->
    <input type="submit" value="Update item" />
  </form>
  </div>
  <footer>
    <?php include("footer.php"); ?>
  </footer>
</body>
</html>
