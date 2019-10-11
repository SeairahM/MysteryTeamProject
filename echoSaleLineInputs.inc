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
?>
