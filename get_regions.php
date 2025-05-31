<?php
include 'db.php';

$result = $conn->query("SELECT DISTINCT region FROM stores ORDER BY region");
$regions = [];

while ($row = $result->fetch_assoc()) {
    $regions[] = $row["region"];
}

echo json_encode($regions);
?>
