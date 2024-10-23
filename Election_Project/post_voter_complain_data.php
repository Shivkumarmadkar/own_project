<?php


//new new working code end

//final working code start

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection
include 'db_connect.php';



// Ensure the database connection is successful
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]));
}

// Retrieve POST data and sanitize inputs
$voterId = isset($_POST['voterId']) ? trim($_POST['voterId']) : '';
$pb_no = isset($_POST['pb_no']) ? trim($_POST['pb_no']) : '';
$pb_name = isset($_POST['pb_name']) ? trim($_POST['pb_name']) : '';
$pb_address = isset($_POST['pb_address']) ? trim($_POST['pb_address']) : '';
$votername = isset($_POST['votername']) ? trim($_POST['votername']) : '';
$fathers_husbands_name = isset($_POST['fathers_husbands_name']) ? trim($_POST['fathers_husbands_name']) : '';
$age = isset($_POST['age']) ? trim($_POST['age']) : '';
$gender = isset($_POST['gender']) ? trim($_POST['gender']) : '';
$cat_id = isset($_POST['cat_id']) ? trim($_POST['cat_id']) : '';
$sub_cat_id = isset($_POST['sub_cat_id']) ? trim($_POST['sub_cat_id']) : '';
$problem_description = isset($_POST['problem_description']) ? trim($_POST['problem_description']) : '';
$mo_no = isset($_POST['mo_no']) ? intval($_POST['mo_no']) : 1;

// Validate required inputs
// if (empty($pb_no) || empty($cat_id) || empty($sub_cat_id) || empty($problem_description)) {
//     echo json_encode(['success' => false, 'message' => 'All fields are required.']);
//     exit;
// }

// Prepare voter data as an object for `data_col`
$data_col_value = json_encode([
    'voterId' => $voterId,
    'votername' => $votername,
    'fathers_husbands_name' => $fathers_husbands_name,
    'pb_name' => $pb_name,
    'pb_address' => $pb_address,
    'age' => $age,
    'gender' => $gender,
]);

// Check if `pb_id` exists in the table
$sql_check = "SELECT curr_col, line_no FROM voter_complain_feedback__details_all WHERE pb_id = ? ORDER BY line_no DESC LIMIT 1";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $pb_no);
$stmt_check->execute();
$result = $stmt_check->get_result();

if ($result->num_rows > 0) {
    // If `pb_id` exists, fetch the current column value and line number
    $row = $result->fetch_assoc();
    $curr_col = (int) $row['curr_col'];
    $line_no = (int) $row['line_no'];

    if ($curr_col >= 40) {
        // If all data columns are filled, increment the line number and insert a new row
        $line_no++;  // Increment line number
        $curr_col = 1;  // Reset curr_col for new entry

        $mob_col_value = json_encode([
            'mobile' => $mo_no,
            'count' => $curr_col,
            'data' => [
                'cat_id' => $cat_id,
                'sub_cat_id' => $sub_cat_id,
                'description' => $problem_description,
            ]
        ], JSON_PRETTY_PRINT);

        // Insert new row with incremented line_no and reset curr_col
        $sql_insert = "INSERT INTO voter_complain_feedback__details_all 
                       (pb_id, line_no, curr_col, data_col1, mob_col1) 
                       VALUES (?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("siiss", $pb_no, $line_no, $curr_col, $data_col_value, $mob_col_value);

        if ($stmt_insert->execute()) {
            // Check if any rows were affected
            if ($stmt_insert->affected_rows > 0) {
                echo json_encode(['success' => true, 'message' => 'New entry added with incremented line number.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'No rows were added.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add new entry: ' . $stmt_insert->error]);
        }
        $stmt_insert->close();
        exit;
    }

    // If columns are not full, update the next available column
    $new_curr_col = $curr_col + 1;
    $data_col_name = "data_col" . $new_curr_col;
    $mob_col_name = "mob_col" . $new_curr_col;

    $mob_col_value = json_encode([
        'mobile' => $mo_no,
        'count' => 1,
        'data' => [
            'cat_id' => $cat_id,
            'sub_cat_id' => $sub_cat_id,
            'description' => $problem_description,
        ]
    ], JSON_PRETTY_PRINT);

    // Prepare the update query
    $sql_update = "UPDATE voter_complain_feedback__details_all 
                   SET curr_col = ?, $data_col_name = ?, $mob_col_name = ? 
                   WHERE pb_id = ? AND line_no = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("isssi", $new_curr_col, $data_col_value, $mob_col_value, $pb_no, $line_no);

    if ($stmt_update->execute()) {
        // Check if any rows were affected
        if ($stmt_update->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Complaint updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No rows were updated.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update complaint: ' . $stmt_update->error]);
    }
    $stmt_update->close();
} else {
    // If `pb_id` does not exist, insert new data with line_no = 1
    $line_no = 1;
    $curr_col = 1;

    $mob_col_value = json_encode([
        'mobile' => $mo_no,
        'count' => $curr_col,
        'data' => [
            'cat_id' => $cat_id,
            'sub_cat_id' => $sub_cat_id,
            'description' => $problem_description,
        ]
    ], JSON_PRETTY_PRINT);

    // Insert new row with line_no = 1
    $sql_insert = "INSERT INTO voter_complain_feedback__details_all 
                   (pb_id, line_no, curr_col, data_col1, mob_col1) 
                   VALUES (?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("siiss", $pb_no, $line_no, $curr_col, $data_col_value, $mob_col_value);

    if ($stmt_insert->execute()) {
        // Check if any rows were affected
        if ($stmt_insert->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'New complaint registered with line number 1.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No rows were added.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to register complaint: ' . $stmt_insert->error]);
    }
    $stmt_insert->close();
}

// Close connections
$stmt_check->close();
$conn->close();

?>
