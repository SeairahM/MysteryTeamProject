<!DOCTYPE=html>
<html lang='en'>
<head>
</head>
<body>
<?php
  function echoSaleLineInputs($leftstocks) {
    //convert string to numeric?
    $linesnum = (int)$_POST["linesnum"];
    echo "<input hidden type=\"text\" name=\"linesnum\" value=\"". $linesnum. "\" />";
    $i = 0;
    while ($i < $linesnum) {
      $currentItemID = "itemID_" . $i;
      $currentAmtID = "itemAmount_" . $i;
      $leftID = "leftamt_" . $i;
      echo "<input hidden type=\"text\" name=\"". $currentItemID. "\" value=\"". $_POST[$currentItemID]. "\" />";
      echo "<input hidden type=\"text\" name=\"". $currentAmtID. "\" value=\"". $_POST[$currentAmtID]. "\" />";
      echo "<input hidden type=\"text\" name=\"". $leftID. "\" value=\"". $leftstocks[$i]. "\" />";
      $i += 1;
    }
  }

  function enoughStock($inStock, $saleAmt) {
    return (int)$inStock >= (int)$saleAmt;
  }

  if (isset($_GET["submit"])) {
    //db connection from dbconn
    require_once("dbconn.php");
    $conn = $DBConn;

    if ($_GET["submit"] == "n" && isset($_POST["linesnum"])) {
      $leftstocks = array();
      $i = 0;
      $total = 0;
      //convert string to numeric?
      $linesnum = (int)$_POST["linesnum"];
      // $key = "itemID_". $i;
      // echo "<p>Item ID found at name ". $key. ":". $_POST[$key]. "</p>";
      while ($i < $linesnum) {
        $valid = true;
        $itemID = $_POST["itemID_" . $i];
        $amt = (int)$_POST["itemAmount_" . $i];
        //get lists of item names
        echo "<p>Item ID querying:". $itemID. "</p>";
        $sql = "SELECT itemID, itemName, itemPrice, stockAmt FROM Items WHERE itemID = \"" . $itemID . "\"";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
          //make sure $amt is numeric?
          $linePrice = $row["itemPrice"]*$amt;
          echo "<p>". $row["itemID"]. " - ". $row["itemName"]. " x ". $amt. " = ". $linePrice. "</p>";
          if (enoughStock($row["stockAmt"],$amt)) {
            $leftinstock = (int)$row["stockAmt"] - $amt;
            echo "<p>Valid sale amount, ". $leftinstock . " left in stock.</p>";
            $leftstocks[$i] = $leftinstock;
          }
          else {
            echo "<p>Invalid sale amount, not enough in stock. Please check with support.</p>";
            $valid = false;
          }
        }
        $total += $linePrice;
        mysqli_free_result($result);
        $i += 1;
      }

      //hidden input for items
      if ($valid) {
        echo "<p>Total: ". $total. "</p>";
        echo "<form method=\"post\" action=\"add_sales_process.php?submit=y\">";
        echoSaleLineInputs($leftstocks);
        //inputs for sale record
        echo "<label for=\"paymethod\">Pay Method</label>";
        echo "<input type=\"text\" id=\"paymethod\" name=\"paymethod\" />";
        echo "<input hidden type=\"text\" name=\"totalcost\" value=\"". $total. "\" />";

        //manual datetime
        // echo "<label for=\"datetime\">Date Sold</label>";
        // echo "<input type=\"text\" id=\"datetime\" name=\"datetime\" pattern=\"^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])\" placeholder=\"YYYY-MM-DD\" />";

        //automatic datetime
        date_default_timezone_set('Australia/Melbourne');
        $datetime = date('Y-m-d h:i:s', time());
        echo "<input hidden type=\"text\" name=\"datetime\" value=\"". $datetime. "\" />";

        //submit
        echo "<input type=\"submit\" value=\"Submit Record\" />";
      }
      else {
        echo "<p>Cannot proceed - invalid sale amount(s) above.</p>";
      }
      echo "</form>";
      //prefill using localStorage via JS
      echo "<p><a href=\"add_sales.php?linesnum=". $linesnum. "\">Back to Add Sales Record</a></p>";
    }
    elseif ($_GET["submit"] == "y") {
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
    }
  }
?>
</body>
</html>
