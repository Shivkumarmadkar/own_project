<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection
include 'db_connect.php';

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]));
}

$pb_no = isset($_POST['pb_no']) ? trim($_POST['pb_no']) : '';
$mo_no = isset($_POST['mo_no']) ? trim($_POST['mo_no']) : '';

// Voter data
$voter_data = [
    'voterId' => $_POST['voterId'] ?? '',
    'votername' => $_POST['votername'] ?? '',
    'fathers_husbands_name' => $_POST['fathers_husbands_name'] ?? '',
    'pb_name' => $_POST['pb_name'] ?? '',
    'pb_address' => $_POST['pb_address'] ?? '',
    'age' => $_POST['age'] ?? '',
    'gender' => $_POST['gender'] ?? ''
];

$new_data_col_value = json_encode($voter_data);

// Fetch the latest entry for the given pb_no
$sql_check = "SELECT line_no, curr_col, 
                     mob_col1, mob_col2, mob_col3, mob_col4, mob_col5, 
                     mob_col6, mob_col7, mob_col8, mob_col9, mob_col10, 
                     mob_col11, mob_col12, mob_col13, mob_col14, mob_col15, 
                     mob_col16, mob_col17, mob_col18, mob_col19, mob_col20, 
                     mob_col21, mob_col22, mob_col23, mob_col24, mob_col25, 
                     mob_col26, mob_col27, mob_col28, mob_col29, mob_col30, 
                     mob_col31, mob_col32, mob_col33, mob_col34, mob_col35, 
                     mob_col36, mob_col37, mob_col38, mob_col39, mob_col40
              FROM voter_complain_feedback__details_all 
              WHERE pb_id = ? 
              ORDER BY line_no DESC 
              LIMIT 1";

$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $pb_no);
$stmt_check->execute();
$result = $stmt_check->get_result();
$row = $result->fetch_assoc();

$found = false;
$column_to_update = '';
$mob_col_data = '';

// Get all mobile columns dynamically
$sql_columns = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_NAME = 'voter_complain_feedback__details_all' 
                AND COLUMN_NAME LIKE 'mob_col%'";

$result_columns = $conn->query($sql_columns);
$mob_cols = [];
while ($column = $result_columns->fetch_assoc()) {
    $mob_cols[] = $column['COLUMN_NAME'];
}

// Check if the mobile number exists in any mob_col
foreach ($mob_cols as $mob_col_name) {
    $mob_data = json_decode($row[$mob_col_name] ?? '', true);
    if ($mob_data && isset($mob_data['mobile']) && $mob_data['mobile'] == $mo_no) {
        $found = true;
        $column_to_update = $mob_col_name;
        $mob_col_data = $mob_data;
        break;
    }
}

$stmt_update = null;
$stmt_insert = null;
if ($found) {
    // Update the existing mobile data
    $mob_col_data['count'] += 1;  // Increment the count
    $updated_mob_col_value = json_encode($mob_col_data);  // Update mobile column data

    // Prepare to update data_col1
    $data_col_name = str_replace('mob_col', 'data_col', $column_to_update);
    
    // Fetch existing data_col1 and decode it
    $existing_data_col_value = $row[$data_col_name] ?? '';

    // Append new voter data to existing data
    if (!empty($existing_data_col_value)) {
        // Check if existing data is valid JSON
        $existing_data_col_value = rtrim($existing_data_col_value, ', '); // Remove trailing comma
        $existing_data_col_value .= ','; // Add a comma before appending new data
    } else {
        $existing_data_col_value = ''; // If it was empty, start fresh
    }

    // Append the new voter data as a JSON string
    $existing_data_col_value .= json_encode($voter_data);

    // Prepare the SQL update query
    $sql_update = "UPDATE voter_complain_feedback__details_all 
                   SET $column_to_update = ?, $data_col_name = ? 
                   WHERE pb_id = ? AND line_no = ?";

    // Execute the update query
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sssi", $updated_mob_col_value, $existing_data_col_value, $pb_no, $row['line_no']);
    $stmt_update->execute();

}else {
    // Condition 2 and 3: Insert into the next available column or a new row
    $new_curr_col = ($row) ? $row['curr_col'] + 1 : 1;
    $line_no = ($row && $new_curr_col <= 40) ? $row['line_no'] : ($row['line_no'] + 1);
    $new_curr_col = ($new_curr_col > 40) ? 1 : $new_curr_col;

    $mob_col_name = 'mob_col' . $new_curr_col;
    $data_col_name = 'data_col' . $new_curr_col;

    $new_mob_col_value = json_encode(['mobile' => $mo_no, 'count' => 1]);

    if ($row) {
        // Update existing row with new mobile and voter data
        $sql_insert = "UPDATE voter_complain_feedback__details_all 
                       SET curr_col = ?, $mob_col_name = ?, $data_col_name = ? 
                       WHERE pb_id = ? AND line_no = ?";

        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("isssi", $new_curr_col, $new_mob_col_value, $new_data_col_value, $pb_no, $line_no);
    } else {
        // Insert a new row if pb_id doesn't exist
        $sql_insert = "INSERT INTO voter_complain_feedback__details_all 
                       (pb_id, line_no, curr_col, $mob_col_name, $data_col_name) 
                       VALUES (?, ?, ?, ?, ?)";

        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("sisss", $pb_no, $line_no, $new_curr_col, $new_mob_col_value, $new_data_col_value);
    }

    $stmt_insert->execute();
}

// Check if the operation was successful
$update_success = $stmt_update ? $stmt_update->affected_rows > 0 : false;
$insert_success = $stmt_insert ? $stmt_insert->affected_rows > 0 : false;

if ($update_success || $insert_success) {
    echo json_encode(['success' => true, 'message' => 'Form submitted successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to submit the form.']);
}

// Close the connection
$conn->close();
?>
