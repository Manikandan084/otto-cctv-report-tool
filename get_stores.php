<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ottostore";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$region = $_GET['region'] ?? '';

if ($region === 'Other States') {
    // ✅ FIXED: Select both state and store_name
    $sql = "SELECT state, store_name FROM stores WHERE region = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $region);
    $stmt->execute();
    $result = $stmt->get_result();

    $stores = [];
    while ($row = $result->fetch_assoc()) {
        $state = $row['state'];
        $stores[$state][] = $row['store_name'];
    }

    // Sort states and store names alphabetically
    ksort($stores);
    foreach ($stores as &$slist) {
        sort($slist);
    }

    echo json_encode($stores);
} else {
    // For other region values
    $sql = "SELECT store_name FROM stores WHERE region = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $region);
    $stmt->execute();
    $result = $stmt->get_result();

    $stores = [];
    while ($row = $result->fetch_assoc()) {
        $stores[] = $row['store_name'];
    }

    sort($stores);
    echo json_encode($stores);
}

$conn->close();
?>
