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
  <h1>Items</h1>
  <?php
  if (isset($_GET["edited"])) {
    echo "<p>";
    if ($_GET["edited"] == 'y') { echo "Item successfully updated."; }
    else { echo "Something went wrong. Please try again."; }
    echo "</p>";
  }
  //function to echo edit button
  function echoEditButton($itemID) {
    echo "<form method=\"post\" action=\"editItem.php\">";
    echo "<input hidden type=\"text\" name=\"itemID\" value=\"". $itemID. "\" />";
    echo "<input type=\"submit\" value=\"Edit\" />";
    echo "</form>";
  }
  //db connection - from dbconn.php
  require_once("dbconn.php");
  $conn = $DBConn;

  //get lists of items plus categories
  //edit query by input from filter form
  if (isset($_GET['query'])) {
    $arrangeby = $_POST['arrangeby'];
    $searchitem = $_POST['searchitem'];
    $searchkey = $_POST['searchkey'];
    $orderkey = $_POST['order'];
  }
  else {
    $arrangeby = "itemID";
    $searchitem = "itemName";
    $searchkey = "";
    $orderkey = "DESC";
  }
  $sql_arrange = " ORDER BY ". $arrangeby. " ";
  if ($searchkey != "" || $searchkey != NULL) { $sql_search = " WHERE ". $searchitem. " = \"". $searchkey. "\" "; }
  else { $sql_search = " "; }
  $sql_order = $orderkey;
  $sql = "SELECT itemID, itemName, itemNote, stockAmt, categoryName, itemPrice
  FROM Items i JOIN Categories c ON i". ".". "itemCategory = c". ".". "categoryID".
  $sql_search. $sql_arrange. $sql_order;
  // echo "<p>Query is: ". $sql;
  $result = $conn->query($sql);
  ?>
  <!--filter items form-->
  <form action="displayItems.php?query=y" method="POST">
    <!--search text input-->
    <label for='searchkey'>Search for:</label>
    <input type='text' id='searchkey' name='searchkey'/><br />
    <!--pick column to search-->
    <input type='radio' id='searchitem1' name='searchitem' value='itemID' />
    <label for='searchitem1'>by Item ID</label>
    <input type='radio' id='searchitem2' name='searchitem' value='itemName' checked='checked'/>
    <label for='searchitem2'>by Item Name</label>
    <input type='radio' id='searchitem3' name='searchitem' value='categoryName' />
    <label for='searchitem3'>by Category</label><br />
    <!--pick column to order by-->
    <input type='radio' id='arrangeby1' name='arrangeby' value='itemID' checked='checked'/>
    <label for='arrangeby1'>Order by Item ID</label>
    <input type='radio' id='arrangeby2' name='arrangeby' value='itemName' />
    <label for='arrangeby2'>Order by Item Name</label>
    <input type='radio' id='arrangeby3' name='arrangeby' value='categoryName' />
    <label for='arrangeby3'>Order by Category</label><br />
    <!--pick column to order by-->
    <input type='radio' id='order1' name='order' value='ASC' checked='checked'/>
    <label for='order1'>In ascending order</label>
    <input type='radio' id='order2' name='order' value='DESC' />
    <label for='order2'>In descending order</label>
    <!--submit-->
    <input type="submit" value="Search" />
  </form>
  <!--check if any result found-->
  <?php
  if (mysqli_num_rows($result) == 0) {
    header("Location: displayItems.php?nomatch=y");
  }
  if (isset($_GET['nomatch'])) {
    echo "<p>No items found. Displaying all items.</p>";
  }
  ?>
  <!--item table-->
  <table>
    <tr>
      <th>Item ID</th>
      <th>Item Name</th>
      <th>Item Note</th>
      <th>Stock</th>
      <th>Category</th>
      <th>Unit Price</th>
      <th>Edit</th>
    </tr>
    <?php
    while ($row = $result->fetch_assoc()) {
      echo "<tr>";
      echo "<td>". $row["itemID"]. "</td>";
      echo "<td>". $row["itemName"]. "</td>";
      echo "<td>". $row["itemNote"]. "</td>";
      echo "<td>". $row["stockAmt"]. "</td>";
      echo "<td>". $row["categoryName"]. "</td>";
      echo "<td>". $row["itemPrice"]. "</td>";
      echo "<td>";
      echoEditButton($row["itemID"]);
      echo "</td>";
      echo "</tr>";
    }
    mysqli_free_result($result);
    ?>
  </table>
  </div>
  <footer>
    <?php include("footer.php"); ?>
  </footer>
</body>
</html>
