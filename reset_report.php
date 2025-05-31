<?php
header('Content-Type: application/json');

// Get region from query parameter
$region = $_GET['region'] ?? '';

// Check if the region is provided
if (empty($region)) {
    echo json_encode(['error' => 'Region is required']);
    exit;
}

// Fetch the store list from get_stores.php using CURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost/get_stores.php?region=' . urlencode($region));
// Adjust the URL as needed
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$storesData = curl_exec($ch);

// Check for CURL errors
if (curl_errno($ch)) {
    echo json_encode(['error' => 'CURL error: ' . curl_error($ch)]);
    curl_close($ch);
    exit;
}

// Close the CURL session
curl_close($ch);

// Decode JSON response from get_stores.php
$stores = json_decode($storesData, true);

// Validate the data is an array
if (!is_array($stores)) {
    echo json_encode(['error' => 'Invalid data format from get_stores.php']);
    exit;
}

// Build default report structure
$defaultData = [];

foreach ($stores as $store) {
    // Ensure each key exists to prevent undefined index errors
    $defaultData[] = [
        'store' => $store['store'] ?? '',
        'software' => '',
        'system_no' => '',
        'viewed' => false,
        'not_viewed' => false,
        'ultraviewer' => false,
        'mobile' => false,
        'issues' => ''
    ];
}

// Return JSON response
echo json_encode($defaultData);
?>
