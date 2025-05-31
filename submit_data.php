<?php
// Enable full error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set response type
header('Content-Type: application/json');

// DB credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ottostore";

// Connect to MySQL
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Database connection failed",
        "error" => $conn->connect_error
    ]);
    exit();
}

// Get JSON input
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// Validate input
if (!$data || !isset($data['stores']) || !is_array($data['stores'])) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Invalid or missing 'stores' data"
    ]);
    exit();
}

// Global fields from root of JSON
$date_field  = $data['date'] ?? date('Y-m-d');
$location    = $data['location'] ?? '';
$region      = $data['region'] ?? '';
$start_time  = $data['start_time'] ?? '00:00:00';
$end_time    = $data['end_time'] ?? '00:00:00';

// Prepare SQL insert statement
$stmt = $conn->prepare("
    INSERT INTO store_reports 
    (store_name,`Phone number`,software, system_no, viewed, not_viewed, ultra, mobile, issues, start_time, end_time, location, date, region)
    VALUES (?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

if (!$stmt) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Failed to prepare SQL statement",
        "error" => $conn->error
    ]);
    exit();
}

$stmt->bind_param(
    'ssssssssssssss', // 14 's' for 14 string params
    $store_name,
    $phonenumber,
    $software,
    $system_no,
    $viewed,
    $not_viewed,
    $ultra,
    $mobile,
    $issues,
    $start_time_param,
    $end_time_param,
    $location_param,
    $date_param,
    $region_param
);

// Assign global values to param variables
$start_time_param = $start_time;
$end_time_param   = $end_time;
$location_param   = $location;
$date_param       = $date_field;
$region_param     = $region;

foreach ($data['stores'] as $store) {
    $store_name  = $store['store'] ?? '';
    $phonenumber = $store['phone'] ?? '';
    $software    = $store['software'] ?? '';
    $system_no   = $store['system_no'] ?? '';
    $viewed      = !empty($store['viewed']) ? 'viewed' : '';
    $not_viewed  = !empty($store['not_viewed']) ? 'not viewed' : '';
    $ultra       = !empty($store['ultra']) ? 'ultraviewer' : '';
    $mobile      = !empty($store['mobile']) ? 'mobile' : '';
    $issues      = $store['issues'] ?? '';

    if (!$stmt->execute()) {
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "message" => "Insert failed for store: $store_name",
            "error" => $stmt->error
        ]);
        $stmt->close();
        $conn->close();
        exit();
    }
}

 

// Close resources
$stmt->close();
$conn->close();

// Final success response
echo json_encode(["status" => "success", "message" => "Data inserted successfully"]);
?>
