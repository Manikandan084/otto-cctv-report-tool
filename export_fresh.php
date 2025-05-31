<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"fresh_entry.xls\"");
header("Pragma: no-cache");
header("Expires: 0");

// Example: adjust these field names to match your form input names
$columns = ["Store", "Software", "System No.", "Viewed", "Not Viewed", "UltraViewer", "Mobile", "Issues"];
echo implode("\t", $columns) . "\n";

$data = [
    $_POST['store'] ?? '',
    $_POST['software'] ?? '',
    $_POST['system_no'] ?? '',
    $_POST['viewed'] ?? '',
    $_POST['not_viewed'] ?? '',
    $_POST['ultra'] ?? '',
    $_POST['mobile'] ?? '',
    $_POST['issues'] ?? ''
];

echo implode("\t", $data) . "\n";
?>
