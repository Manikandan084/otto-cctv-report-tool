<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// DB config
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ottostore";

// Connect DB
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database connection failed", "error" => $conn->connect_error]);
    exit();
}

// Get query parameters
$date = $_GET['date'] ?? date('Y-m-d');
$region = $_GET['region'] ?? '';

if (!$region) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Missing region parameter"]);
    exit();
}

// Fetch data
$sql = "SELECT * FROM store_reports WHERE date = ? AND region = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $date, $region);

if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Query failed", "error" => $stmt->error]);
    exit();
}

$result = $stmt->get_result();
$stores = [];

while ($row = $result->fetch_assoc()) {
    $stores[] = $row;
}

echo json_encode(["status" => "success", "stores" => $stores]);

$stmt->close();
$conn->close();
?>
