// Initialize map
var map = L.map('myMap').setView([10.65, 122.95], 12);

// Load the street map visual tiles
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
}).addTo(map);

// Our GeoJSON geometry and property data
// (Keep your map initialization and bindDataPopup function at the top)

// Function to attach popups to each shape
function bindDataPopup(feature, layer) {
    if (feature.properties && feature.properties.barangay) {
        var popupContent = `
            <div style="font-family: sans-serif;">
                <h3 style="margin: 0 0 5px 0; color: #1e293b;">Barangay ${feature.properties.barangay}</h3>
                <p style="margin: 0 0 3px 0;"><b>Flood Risk:</b> ${feature.properties.floodRisk}</p>
                <p style="margin: 0;"><b>Health Impact:</b> ${feature.properties.healthImpactScore}</p>
            </div>
        `;
        layer.bindPopup(popupContent);
    }
}

// Fetch the data from your PHP backend
fetch('../controller/api.php')
    .then(response => response.json())
    .then(data => {
        // Once the data arrives, inject it into the map
        L.geoJSON(data, {
            style: function(feature) {
                return {
                    color: feature.properties.floodRisk === 'High' ? '#ef4444' : '#f59e0b',
                    weight: 2,
                    fillOpacity: 0.5
                };
            },
            onEachFeature: bindDataPopup
        }).addTo(map);
    })
    .catch(error => console.error('Error fetching map data:', error));