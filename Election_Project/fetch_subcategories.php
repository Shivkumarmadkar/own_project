<?php
include 'db_connect.php'; // Include the connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cat_id = $_POST['cat_id'];
    $language = $_POST['language']; // Get the selected language

    // Determine which column to select based on the language
    if ($language === 'marathi') {
        $sql = "SELECT sub_cat_id, sub_cat_name_marathi AS sub_category_name FROM sub_category_header_all WHERE cat_id = ?";
    } else {
        $sql = "SELECT sub_cat_id, sub_cat_name_english AS sub_category_name FROM sub_category_header_all WHERE cat_id = ?";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $cat_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Prepare response options
    if ($result->num_rows > 0) {
        if ($language === 'marathi') {
        $response = "<option style='font-weight: normal; font-size: 0.8em;' value=''>विशिष्ट समस्या निवडा</option>"; // Placeholder option
        }
        else{
        $response = "<option value=''>Select particular problem</option>"; // Placeholder option

        }
        while ($row = $result->fetch_assoc()) {
            $response .= "<option value='" . $row['sub_cat_id'] . "'>" . $row['sub_category_name'] . "</option>";
        }
        echo $response; // Send back the subcategory options
    } else {
        echo "<option value=''>No Subcategories Found</option>";
    }

    $stmt->close();
}
?>
