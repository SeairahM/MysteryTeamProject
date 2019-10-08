<?php
  echo "<p>Total: ". $total. "</p>";
  echo "<form method=\"post\" action=\"saveNewSalesToDB.php\">";
  require_once("echoSaleLineInputs.inc");
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
?>
