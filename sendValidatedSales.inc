<?php
  echo "<p>Total: ". $total. "</p>";
  echo "<form method=\"post\" action=\"saveNewSalesToDB.php\">";
  require_once("echoSaleLineInputs.inc");
  echoSaleLineInputs($leftstocks);
  //inputs for sale record
  echo "<label for=\"paymethod\">Pay Method</label>";
  echo "<select id=\"paymethod\" name=\"paymethod\" required=\"required\">";
    echo "<option value=\"CASH\" selected>Cash</option>";
    echo "<option value=\"DEBIT\">Debit Card</option>";
    echo "<option value=\"CREDIT\">Credit Card</option>";
  echo "</select>";
  echo "<input type=\"hidden\" name=\"totalcost\" value=\"". $total. "\" />";

  //manual datetime
  // echo "<label for=\"datetime\">Date Sold</label>";
  // echo "<input type=\"text\" id=\"datetime\" name=\"datetime\" pattern=\"^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])\" placeholder=\"YYYY-MM-DD\" />";

  //automatic datetime
  date_default_timezone_set('Australia/Melbourne');
  $datetime = date('Y-m-d h:i:s', time());
  echo "<input type=\"hidden\" name=\"datetime\" value=\"". $datetime. "\" />";
?>
