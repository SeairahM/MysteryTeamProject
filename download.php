<?php
if (isset($_POST["repmonth"]))
{
  $filename = 'monthlySalesRecord_'. $_POST['repmonth']. "_". $_POST['repyear']. "_". $_POST['repcat']. ".csv";
  $export_data = unserialize($_POST['export_data']);
  $first = true;

  // file creation
  $file = fopen('php://output', 'w');
  $list = array('Item Category', 'Item ID', 'Item Name', 'Number of Sales', 'Remaining Stock');
  fputcsv($file, $list);
  foreach ($export_data as $line){
   fputcsv($file,$line);
  }

  fclose($file);

  // download
  header("Content-Description: File Transfer");
  header("Content-Disposition: attachment; filename=".$filename);
  header("Content-Type: application/csv; ");

  exit();
}
else {
  header("Location: index.php");
}
?>
