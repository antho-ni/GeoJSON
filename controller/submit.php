<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

// Database credentials
$host = 'localhost';
$db   = 'geojson';
$user = 'root';
$pass = '';

// Create a connection
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    http_response_code(500);
    exit('Unable to connect to the database.');
}

$barangay = trim($_POST['barangay_name'] ?? '');
$risk = $_POST['flood_risk'] ?? '';
$score = filter_var($_POST['health_impact_score'] ?? null, FILTER_VALIDATE_FLOAT);
$coords = $_POST['polygon_coordinates'] ?? '';

if ($barangay === '' || strlen($barangay) > 100 || !in_array($risk, array('Low', 'Moderate', 'High'), true)) {
    http_response_code(422);
    exit('Please provide a valid barangay name and flood risk level.');
}

$coordsData = json_decode($coords, true);
$ring = $coordsData[0] ?? null;
$validCoordinates = is_array($coordsData) && count($coordsData) === 1 && is_array($ring) && count($ring) >= 4;

if ($validCoordinates) {
    foreach ($ring as $point) {
        if (!is_array($point) || count($point) !== 2 || !is_numeric($point[0]) || !is_numeric($point[1])) {
            $validCoordinates = false;
            break;
        }
    }
}

if ($score === false || $score < 0 || $score > 5 || !$validCoordinates) {
    http_response_code(422);
    exit('Please provide a health score from 0 to 5 and a valid polygon.');
}

$stmt = $conn->prepare("INSERT INTO community_health_reports (barangay_name, flood_risk, health_impact_score, polygon_coordinates) VALUES (?, ?, ?, ?)");

if (!$stmt) {
    http_response_code(500);
    $conn->close();
    exit('Unable to prepare the report.');
}

$coords = json_encode($coordsData);
$stmt->bind_param('ssds', $barangay, $risk, $score, $coords);

if ($stmt->execute()) {
    echo "<h3>Success! New zone added.</h3>";
    echo "<a href='../'>Go back to the map</a>";
} else {
    http_response_code(500);
    echo 'Unable to save the report.';
}

$stmt->close();
$conn->close();
?>