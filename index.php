<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Data Map</title>
    <!-- Load Leaflet CSS first -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Load your custom CSS second -->
    <link rel="stylesheet" href="styles/styles.css" />
</head>
<body>
    <div class="map-container">
        <h2>Community Health and Flood Risk Map</h2>
        <div id="myMap"></div>

        <!-- Simple legend -->
        <div style="position: fixed; bottom: 20px; left: 20px; background: white; padding: 10px; border-radius: 5px; box-shadow: 0 0 15px rgba(0,0,0,0.2); z-index: 1000;">
            <h4 style="margin-top: 0;">Flood Risk Legend</h4>
            <div style="display: flex; align-items: center; margin-bottom: 5px;">
                <div style="background: #ef4444; width: 20px; height: 20px; display: inline-block; margin-right: 10px;"></div>
                <span>High Risk</span>
            </div>
            <div style="display: flex; align-items: center;">
                <div style="background: #f59e0b; width: 20px; height: 20px; display: inline-block; margin-right: 10px;"></div>
                <span>Low/Moderate Risk</span>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- Load your custom JS second -->
    <script src="Js/GeoJSON.js"></script>

</body>
</html>