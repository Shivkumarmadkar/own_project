<?php
include 'db_connect.php'; // Ensure your database connection is included

// Fetch candidate data
$sql = "SELECT name, picture FROM candidate_header_all WHERE id='15' "; // Modify this query as needed
$result = $conn->query($sql);

// Prepare response array
$response = [];

if ($result->num_rows > 0) {
    $candidate = $result->fetch_assoc(); // Fetch the first row
    $response['name'] = $candidate['name'];
    $response['picture'] = $candidate['picture'];
} else {
    $response['error'] = "No candidate found.";
}

$conn->close(); // Close the database connection

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
