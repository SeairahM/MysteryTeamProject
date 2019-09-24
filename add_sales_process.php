<!DOCTYPE=html>
<html lang='en'>
<head>
</head>
<body>
<?php
  function echoSaleLineInputs() {
    //convert string to numeric?
    $linesnum = $_POST["linesnum"];
    echo "<input hidden type=\"text\" name=\"linesnum\" value=\"". $linesnum. "\" />";
    $i = 0;
    while ($i < $linesnum) {
      $currentItemID = "itemline_" + $i;
      $currentAmtID = "amtline_" + $i;
      echo "<input hidden type=\"text\" name=\"". $currentItemID. "\" value=\"". $_POST[$currentItemID]. " />";
      echo "<input hidden type=\"text\" name=\"". $currentAmtID. "\" value=\"". $_POST[$currentAmtID]. " />";
      $i += 1;
    }
  }
  if (isset($_GET["submit"])) {
    //db connection from dbconn
    $DBServer = "localhost";
    $DBUser = "admin";
    $DBPass = "MysteryTeam2019";
    $DB = "PHP";

    $conn = mysqli_connect($DBServer, $DBUser, $DBPass, $DB);

    if ($_GET["submit"] == "n" && isset($_POST["linesnum"])) {
      $i = 0;
      $total = 0;
      //convert string to numeric?
      $linesnum = $_POST["linesnum"];
      while ($i < $linesnum) {
        $itemID = $_POST["itemline_" + $i];
        $amt = $_POST["amtline_" + $i];
        //get lists of item names
        $sql = "SELECT itemID, itemName, itemPrice WHERE itemID = \"" + $itemID + "\" FROM Items";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
          //make sure $amt is numeric?
          $linePrice = $row["itemPrice"]*$amt;
          echo "<p>". $row["itemID"]. " - ". $row["itemName"]. " x ". $amt. " = ". $linePrice. "</p>";
          $total += $linePrice;
          break;
        }
        mysqli_free_result($result);
        $i += 1;
      }
      echo "<p>Total: ". $total. "</p>";
      echo "<form method=\"post\" action=\"add_sales_process.php?submit=y\">";
      //hidden input for items
      echoSaleLineInputs();
      //inputs for sale record
      echo "<label for=\"paymethod\">Pay Method</label>";
      echo "<input type=\"text\" id=\"paymethod\" name=\"paymethod\" />";
      //validation for datetime?
      echo "<label for=\"paymethod\">Date Sold</label>";
      echo "<input type=\"text\" id=\"datetime\" name=\"datetime\" />";
      echo "<p><a href=\"add_sales.php?edit=y\">Back to Add Sales Record</a></p>";
      echo "<input type=\"submit\" value=\"Submit Record\" />";
      echo "</form>";
    }
    elseif ($_GET["submit"] == "y") {

    }
  }
?>
</body>
</html>
