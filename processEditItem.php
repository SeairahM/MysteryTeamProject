<?php
  require_once("checkLogin.php");
  if (!isset($_POST["itemID"])) {
    header("Location: displayItems.php");
  }

  //db connection - from dbconn.php
  require_once("dbconn.php");
  $conn = $DBConn;

  $itemID = $_POST["itemID"];
  $itemName = $_POST["itemName"];
  $stockAmt = $_POST["stockAmt"];
  $itemPrice = $_POST["itemPrice"];
  $itemCategory = $_POST["itemCategory"];
  $itemNote = $_POST["itemNote"];

  $sql = "UPDATE Items SET
  itemName = \"". $itemName. "\",stockAmt = \"". $stockAmt. "\",
  itemPrice = \"". $itemPrice. "\",itemCategory = \"". $itemCategory. "\",
  itemNote = \"". $itemNote. "\" WHERE itemID = \"". $itemID. "\"";
  // echo "<p>Query is: ". $sql. "</p>";
  $result = $conn->query($sql);
  if ($result) {
    header("Location: displayItems.php?edited=y");
  }
  else {
    header("Location: displayItems.php?edited=n");
  }
?>
