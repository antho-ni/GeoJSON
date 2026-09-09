// Initialize map
var map = L.map('myMap').setView([10.65, 122.95], 12);

// Load the street map visual tiles
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
}).addTo(map);

// Our GeoJSON geometry and property data
var geojsonData = {
    "type": "FeatureCollection",
    "features": [
        {
            "type": "Feature",
            "properties": {
                "barangay": "Mandalagan",
                "floodRisk": "High",
                "healthImpactScore": 3.80
            },
            "geometry": {
                "type": "Polygon",
                "coordinates": [[[122.95, 10.69], [122.96, 10.69], [122.96, 10.68], [122.95, 10.68]]]
            }
        },
        {
            "type": "Feature",
            "properties": {
                "barangay": "Tangub",
                "floodRisk": "Moderate",
                "healthImpactScore": 3.33
            },
            "geometry": {
                "type": "Polygon",
                "coordinates": [[[122.93, 10.64], [122.94, 10.64], [122.94, 10.63], [122.93, 10.63]]]
            }
        }
    ]
};

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

// Inject the data, color it, and attach popups
L.geoJSON(geojsonData, {
    style: function(feature) {
        return {
            color: feature.properties.floodRisk === 'High' ? '#ef4444' : '#f59e0b',
            weight: 2,
            fillOpacity: 0.5
        };
    },
    onEachFeature: bindDataPopup
}).addTo(map);