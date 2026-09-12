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
    <main class="map-container">
        <h2>Community Health and Flood Risk Map</h2>
        <div id="myMap"></div>

        <aside class="map-legend" aria-label="Flood risk legend">
            <h4>Flood Risk Legend</h4>
            <div class="legend-item">
                <span class="legend-swatch legend-swatch-high"></span>
                <span>High Risk</span>
            </div>
            <div class="legend-item">
                <span class="legend-swatch legend-swatch-other"></span>
                <span>Low/Moderate Risk</span>
            </div>
        </aside>
    </main>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- Load your custom JS second -->
    <script src="Js/GeoJSON.js"></script>

</body>
</html>