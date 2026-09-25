var mapElement = document.getElementById('myMap');
var map = L.map(mapElement, {
    preferCanvas: true,
    zoomAnimation: false,
    fadeAnimation: false,
    markerZoomAnimation: false
}).setView([10.65, 122.95], 12);

// Load the street map visual tiles
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap',
    updateWhenZooming: false,
    keepBuffer: 2
}).addTo(map);

// Layer groups for different datasets
var floodLayer1 = L.layerGroup();
var floodLayer2 = L.layerGroup();
var communityLayer = L.layerGroup().addTo(map);
var floodRenderer = L.canvas({ padding: 0.5 });
var floodLayerState = {
    flood1: { loaded: false, loading: false },
    flood2: { loaded: false, loading: false }
};

function showFloodData(data, layerGroup, styleOptions) {
    var layer = L.geoJSON(data, styleOptions);
    layer.addTo(layerGroup);
    layerGroup.addTo(map);
}

function loadFloodLayer(filePath, layerGroup, styleOptions, stateKey) {
    var state = floodLayerState[stateKey];
    if (state.loaded || state.loading) {
        return;
    }

    state.loading = true;
    var mapStatus = document.getElementById('map-status');
    if (mapStatus) {
        mapStatus.textContent = 'Loading flood layer...';
    }

    fetch(filePath)
        .then(response => {
            if (!response.ok) {
                throw new Error('HTTP ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            showFloodData(data, layerGroup, styleOptions);
            state.loaded = true;
            state.loading = false;
            if (mapStatus) {
                mapStatus.textContent = 'Flood layer loaded.';
            }
        })
        .catch(error => {
            state.loading = false;
            if (mapStatus) {
                mapStatus.textContent = 'Unable to load the flood layer.';
            }
            console.error('Error loading ' + filePath + ':', error);
        });
}

// Function to get color based on hazard level
function getHazardColor(hazLevel) {
    switch(true) {
        case hazLevel === 1.0:
            return '#fbbf24'; // Yellow - Low hazard
        case hazLevel === 2.0:
            return '#f97316'; // Orange - Medium hazard
        case hazLevel === 3.0:
            return '#ef4444'; // Red - High hazard
        default:
            return '#cccccc'; // Gray - Unknown
    }
}

function getHazardLevel(feature) {
    return feature.properties.HAZ ?? feature.properties.Var;
}

// Function to attach popups to flood data
function bindFloodPopup(feature, layer) {
    var hazLevel = getHazardLevel(feature);
    if (hazLevel !== undefined && hazLevel !== null) {
        var hazardText = hazLevel === 1.0 ? 'Low' : (hazLevel === 2.0 ? 'Medium' : 'High');
        var popupContent = `
            <div style="font-family: sans-serif;">
                <h3 style="margin: 0 0 5px 0; color: #1e293b;">Storm Surge Area</h3>
                <p style="margin: 0;"><b>Hazard Level:</b> ${hazardText} (${hazLevel})</p>
            </div>
        `;
        layer.bindPopup(popupContent);
    }
}

// Function to attach popups to community data
function bindCommunityPopup(feature, layer) {
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

var flood1Options = {
    style: function(feature) {
        return {
            renderer: floodRenderer,
            color: getHazardColor(getHazardLevel(feature)),
            weight: 1,
            fillOpacity: 0.4,
            smoothFactor: 2
        };
    },
    onEachFeature: bindFloodPopup
};

var flood2Options = {
    style: function(feature) {
        return {
            renderer: floodRenderer,
            color: getHazardColor(getHazardLevel(feature)),
            weight: 1,
            fillOpacity: 0.3,
            smoothFactor: 2
        };
    },
    onEachFeature: bindFloodPopup
};

// Fetch the data from your PHP backend
fetch(mapElement.dataset.apiUrl || 'controller/api.php')
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
            onEachFeature: bindCommunityPopup
        }).addTo(communityLayer);
        var mapStatus = document.getElementById('map-status');
        if (mapStatus) {
            mapStatus.textContent = data.features.length + ' community report(s) loaded.';
        }
    })
    .catch(error => {
        var mapStatus = document.getElementById('map-status');
        if (mapStatus) {
            mapStatus.textContent = 'Unable to load community reports.';
        }
        console.error('Error fetching map data:', error);
    });

// Add layer control
var baseLayers = {};
var overlayLayers = {
    'Community Data': communityLayer,
    'Storm Surge Forecast 1': floodLayer1,
    'Storm Surge Forecast 2': floodLayer2
};
L.control.layers(baseLayers, overlayLayers, { position: 'topright' }).addTo(map);

map.on('overlayadd', function(event) {
    if (event.layer === floodLayer1) {
        loadFloodLayer('Js/flood1.json', floodLayer1, flood1Options, 'flood1');
    }
    if (event.layer === floodLayer2) {
        loadFloodLayer('Js/flood2.json', floodLayer2, flood2Options, 'flood2');
    }
});