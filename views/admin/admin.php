<!DOCTYPE html>
<html>
<head>
    <title>Add Community Data</title>
    <link rel="stylesheet" href="../../styles/admin.css">
</head>
<body>
    <div class="form-container">
        <h2>Log New Risk Zone</h2>
        <form action="../../controller/submit.php" method="POST">
            <label>Barangay Name:</label>
            <input type="text" name="barangay_name" required placeholder="e.g., Singcang-Airport">
            
            <label>Flood Risk Level:</label>
            <select name="flood_risk">
                <option value="Low">Low</option>
                <option value="Moderate">Moderate</option>
                <option value="High">High</option>
            </select>
            
            <label>Health Impact Score (0.00 - 5.00):</label>
            <input type="number" step="0.01" name="health_impact_score" required>
            
            <label>Polygon Coordinates (JSON Array):</label>
            <textarea name="polygon_coordinates" rows="4" required placeholder="[[[122.9, 10.6], ...]]]"></textarea>
            
            <button type="submit">Save to Database</button>
        </form>
    </div>
</body>
</html>