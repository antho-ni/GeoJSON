<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Community Data</title>
    <link rel="stylesheet" href="../../styles/admin.css">
    <!-- Leaflet core CSS is required for tile and control positioning. -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Leaflet.draw CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css" />
</head>
<body>
    <div class="form-container">
        <h2>Log New Risk Zone</h2>
        <div id="map" class="admin-map"></div>
        <form action="../../controller/submit.php" method="POST" id="dataForm">
            <label>Barangay Name:</label>
            <input type="text" name="barangay_name" required placeholder="e.g., Singcang-Airport">

            <label>Flood Risk Level:</label>
            <select name="flood_risk">
                <option value="Low">Low</option>
                <option value="Moderate">Moderate</option>
                <option value="High">High</option>
            </select>

            <label>Health Impact Score (0.00 - 5.00):</label>
            <input type="number" step="0.01" min="0" max="5" name="health_impact_score" required>

            <label>Polygon Coordinates (JSON Array):</label>
            <textarea name="polygon_coordinates" id="coordinates" rows="4" required placeholder="Draw a polygon on the map above to auto-fill coordinates"></textarea>

            <div style="margin-top: 15px;">
                <button type="button" id="clearDrawing">Clear Drawing</button>
                <button type="submit">Save to Database</button>
            </div>
        </form>
    </div>

    <!-- Leaflet and Leaflet.draw JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>
    <script>
        // Initialize map
        var map = L.map('map').setView([10.65, 122.95], 12);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Initialize draw control
        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        var drawControl = new L.Control.Draw({
            edit: {
                featureGroup: drawnItems
            },
            draw: {
                polygon: {
                    allowIntersection: false,
                    showArea: true
                },
                polyline: false,
                circle: false,
                marker: false,
                rectangle: false
            }
        });
        map.addControl(drawControl);

        // Handle drawing events
        map.on(L.Draw.Event.CREATED, function (event) {
            var layer = event.layer;

            // Remove any existing drawings
            drawnItems.clearLayers();

            // Add the new layer
            drawnItems.addLayer(layer);

            // Get coordinates and format as GeoJSON-like array
            var coords = layer.getLatLngs()[0]; // Get first (and only) ring of polygon
            var formattedCoords = [];

            // Convert to [lng, lat] format (GeoJSON standard)
            for (var i = 0; i < coords.length; i++) {
                formattedCoords.push([coords[i].lng, coords[i].lat]);
            }

            // Close the polygon by repeating the first point
            if (formattedCoords.length > 0 &&
                (formattedCoords[0][0] !== formattedCoords[formattedCoords.length-1][0] ||
                 formattedCoords[0][1] !== formattedCoords[formattedCoords.length-1][1])) {
                formattedCoords.push(formattedCoords[0]);
            }

            // Format as nested array for MySQL storage
            var finalCoords = [formattedCoords];

            // Update the textarea
            document.getElementById('coordinates').value = JSON.stringify(finalCoords);
        });

        // Handle edited drawings
        map.on(L.Draw.Event.EDITED, function (event) {
            var layers = event.layers;
            layers.eachLayer(function (layer) {
                // Get updated coordinates
                var coords = layer.getLatLngs()[0];
                var formattedCoords = [];

                for (var i = 0; i < coords.length; i++) {
                    formattedCoords.push([coords[i].lng, coords[i].lat]);
                }

                // Close the polygon
                if (formattedCoords.length > 0 &&
                    (formattedCoords[0][0] !== formattedCoords[formattedCoords.length-1][0] ||
                     formattedCoords[0][1] !== formattedCoords[formattedCoords.length-1][1])) {
                    formattedCoords.push(formattedCoords[0]);
                }

                var finalCoords = [formattedCoords];
                document.getElementById('coordinates').value = JSON.stringify(finalCoords);
            });
        });

        // Clear drawing button
        document.getElementById('clearDrawing').addEventListener('click', function() {
            drawnItems.clearLayers();
            document.getElementById('coordinates').value = '';
        });

        // Form validation
        document.getElementById('dataForm').addEventListener('submit', function(e) {
            var coords = document.getElementById('coordinates').value.trim();
            if (coords === '' || coords === '[]' || coords === '[[]]') {
                e.preventDefault();
                alert('Please draw a polygon on the map before submitting.');
            }
        });
    </script>
</body>
</html>