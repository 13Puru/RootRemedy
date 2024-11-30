<?php
// Database connection
include ("db_config.php");
// Query to fetch location data
$sql = "SELECT * FROM markers"; // Replace 'your_table_name' with your actual table name
$result = $conn->query($sql);

$locations = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $locations[] = $row;
    }
}

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($locations);

$conn->close();
?>
