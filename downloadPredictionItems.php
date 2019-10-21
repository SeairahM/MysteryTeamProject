<?php
$filename = 'monthlySalesRecord.csv';
$export_data = unserialize($_POST['export_data']);
$first = true;

// file creation
$file = fopen('php://output', 'w');
$list = array('Item Name', 'Predicted Sales', 'Stock Needed');
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
?>
