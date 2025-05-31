<?php
// Connect DB
$mysqli = new mysqli('localhost', 'root', '', 'ottostore');
if ($mysqli->connect_error) {
    die('DB Connection Failed: ' . $mysqli->connect_error);
}

// Query Data
$sql = "
SELECT
  r.store_name,
  r.software,
  r.system_no,
  r.viewed,
  r.not_viewed,
  r.ultra,
  r.mobile,
  r.issues,
  r.location,
  r.start_time,
  r.end_time,
  r.date
FROM store_reports AS r
INNER JOIN stores AS s ON r.store_name = s.store_name
WHERE s.region = 'Kerala'
ORDER BY r.date, r.store_name
";

$result = $mysqli->query($sql);

// Set Headers to force download as Excel
$filename = "Kerala_" . date('d-m-Y') . ".xls";
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");

// Output Column Headers
$columns = ['Store Name', 'Software', 'System No', 'Viewed', 'Not Viewed', 'UltraViewer', 'Mobile', 'Issues', 'Location', 'Start Time', 'End Time', 'Date'];
echo implode("\t", $columns) . "\n";

// Output Data
while ($row = $result->fetch_assoc()) {
    echo implode("\t", [
        $row['store_name'],
        $row['software'],
        $row['system_no'],
        $row['viewed'],
        $row['not_viewed'],
        $row['ultra'],
        $row['mobile'],
        $row['issues'],
        $row['location'],
        $row['start_time'],
        $row['end_time'],
        $row['date']
    ]) . "\n";
}
exit;
?>
