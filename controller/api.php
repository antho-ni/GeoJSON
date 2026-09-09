<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Database credentials (update these with your local server details)
$host = 'localhost';
$db   = 'geojson';
$user = 'root';
$pass = '';

// Create a connection
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query the data
$sql = "SELECT barangay_name, flood_risk, health_impact_score, polygon_coordinates FROM community_health_reports";
$result = $conn->query($sql);

// Set up the base GeoJSON structure
$geojson = array(
   'type'      => 'FeatureCollection',
   'features'  => array()
);

// Loop through the database rows and format them
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $feature = array(
            'type' => 'Feature',
            'properties' => array(
                'barangay' => $row['barangay_name'],
                'floodRisk' => $row['flood_risk'],
                'healthImpactScore' => $row['health_impact_score']
            ),
            'geometry' => array(
                'type' => 'Polygon',
                // Decode the JSON string stored in the database back into a PHP array
                'coordinates' => json_decode($row['polygon_coordinates'])
            )
        );
        // Add this feature to the main array
        array_push($geojson['features'], $feature);
    }
}

$conn->close();

// Output the final JSON to the browser
echo json_encode($geojson, JSON_NUMERIC_CHECK);
?>