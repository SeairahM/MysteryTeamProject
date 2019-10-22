<?php
  function echoSaleLineInputs($leftstocks) {
    //convert string to numeric?
    $linesnum = (int)$_POST["linesnum"];
    echo "<input type=\"hidden\" name=\"linesnum\" value=\"". $linesnum. "\" />";
    $i = 0;
    while ($i < $linesnum) {
      $currentItemID = "itemID_" . $i;
      $currentAmtID = "itemAmount_" . $i;
      $leftID = "leftamt_" . $i;
      echo "<input type=\"hidden\" name=\"". $currentItemID. "\" value=\"". $_POST[$currentItemID]. "\" />";
      echo "<input type=\"hidden\" name=\"". $currentAmtID. "\" value=\"". $_POST[$currentAmtID]. "\" />";
      echo "<input type=\"hidden\" name=\"". $leftID. "\" value=\"". $leftstocks[$i]. "\" />";
      $i += 1;
    }
  }
?>
