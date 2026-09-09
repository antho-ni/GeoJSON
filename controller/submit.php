<?php
// Database credentials
$host = 'localhost';
$db   = 'geojson';
$user = 'root';
$pass = '';

// Create a connection
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was actually submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Grab the data from the form
    $barangay = $_POST['barangay_name'];
    $risk = $_POST['flood_risk'];
    $score = $_POST['health_impact_score'];
    $coords = $_POST['polygon_coordinates'];

    // Prepare the SQL statement for secure insertion
    $stmt = $conn->prepare("INSERT INTO community_health_reports (barangay_name, flood_risk, health_impact_score, polygon_coordinates) VALUES (?, ?, ?, ?)");
    
    // Bind the variables to the statement (s = string, d = double/decimal)
    $stmt->bind_param("ssds", $barangay, $risk, $score, $coords);

    // Execute and check for success
    if ($stmt->execute()) {
        echo "<h3>Success! New zone added.</h3>";
        echo "<a href='../'>Go back to the map</a>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>