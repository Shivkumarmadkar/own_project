<?php
include 'db_connect.php'; // Include the connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $language = $_POST['language']; // Get the selected language

    // Determine which columns to select based on the language
    if ($language === 'marathi') {
        $sql = "SELECT cat_id, cat_name_marathi AS category_name FROM category_header_all";
    } else {
        $sql = "SELECT cat_id, cat_name_english AS category_name FROM category_header_all";
    }
    
    $result = $conn->query($sql);

    // Create an array to store the fetched categories
    $categories = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row; // Store the category name
        }
    }

    echo json_encode($categories); // Return the categories array as JSON
}
?>
