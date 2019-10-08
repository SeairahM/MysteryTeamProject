<?php
  echo "<form method=\"post\" action=\"saveNewStockToDB.php\">";
  require_once("echoSaleLineInputs.inc");
  echoSaleLineInputs($leftstocks);
?>
