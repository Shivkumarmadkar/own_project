<?php
include 'db_connect.php'; 

header('Content-Type: application/json');

// Retrieve POST data
$voterId = isset($_POST['voterId']) ? $_POST['voterId'] : '';
$wardNo = isset($_POST['wardNo']) ? $_POST['wardNo'] : '';

$response = ['success' => false, 'message' => '']; // Default response

try {
    // Validate ward number
    $wardNo = intval($wardNo); 
    if ($wardNo < 1 || $wardNo > 20) {
        throw new Exception('Invalid ward number. Please select a valid ward.');
    }

    // Construct table name
    $tableName = "voter_data_details_all_$wardNo";
    
    // Prepare and execute the first query to check the database
    $sql = "SELECT name, contact, pb_no, fathers_husbands_name, age, gender FROM $tableName WHERE voter_id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception('Error preparing SQL query.');
    }

    $stmt->bind_param('s', $voterId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Fetch pb_name using pb_no
        $pbNo = $row['pb_no'];
        $sqlPb = "SELECT pb_name, pb_address FROM pb_ward_combination_all WHERE pb_no = ?";
        $stmtPb = $conn->prepare($sqlPb);
        $stmtPb->bind_param('s', $pbNo);
        $stmtPb->execute();
        $resultPb = $stmtPb->get_result();

        if ($resultPb->num_rows > 0) {
            $pbRow = $resultPb->fetch_assoc();
            $pbName = $pbRow['pb_name'];
            $pb_address = $pbRow['pb_address'];

            $pbName = str_replace(["\n", "\r"], '', $pbName); 
            $pbName = trim($pbName); 

            $pb_address = str_replace(["\n", "\r"], '', $pb_address); // Removes all newline characters
            $pb_address = trim($pb_address); 

        } else {
            $pbName = 'No PB name found.';
            $pb_address = 'No PB address found.';

        }

        // Build the response data from the database
        $response['success'] = true;
        $response['data'] = [
            'name' => $row['name'],
            'contact' => $row['contact'],
            'pb_no' => $row['pb_no'],
            'pb_name' => $pbName,
            'pb_address' => $pb_address,
            'fathers_husbands_name' => $row['fathers_husbands_name'],
            'age' => $row['age'],
            'gender' => $row['gender']
        ];

        $stmtPb->close();
    } else {
        // No data found in the database, so call the external API
        $apiUrl = 'https://api.gridlines.io/voter-api/boson/fetch';
        $apiData = [
            'voter_id' => $voterId,
            'consent' => 'Y'
        ];

        $apiHeaders = [
            'Accept: application/json',
            'Content-Type: application/json',
            'X-API-Key: C8EbVBaNqR4g3vhBAiPXdt8cLPkNLJoL',
            'X-Auth-Type: API-Key'
        ];

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($apiData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $apiHeaders);

        $apiResponse = curl_exec($ch);
        $apiStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($apiStatus == 200) {
            $apiResponseData = json_decode($apiResponse, true);
            if (isset($apiResponseData['data']['voter_data'])) {
                $voterData = $apiResponseData['data']['voter_data'];
                
                // Map the API response fields to your form fields
                $response['success'] = true;
                $response['data'] = [
                    'name' => $voterData['name'] ?? 'N/A',
                    'contact' => '', // The API doesn't return contact information
                    'pb_no' => $voterData['part_number'] ?? 'N/A', // part_number maps to pb_no
                    'pb_name' => $voterData['part_name'] ?? 'N/A', // part_name maps to pb_name
                    'pb_address' => $voterData['polling_station'] ?? 'N/A', // part_name maps to pb_name
                    'fathers_husbands_name' => $voterData['husband_name'] ?? 'N/A',
                    'age' => $voterData['age'] ?? 'N/A',
                    'gender' => $voterData['gender'] ?? 'N/A'
                ];
            } else {
                $response['message'] = 'No data found in the external API.';
            }
        } else {
            $response['message'] = 'Failed to fetch data from the external API.';
        }
    }

    $stmt->close();
} catch (mysqli_sql_exception $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

$conn->close();

// Send response as JSON
echo json_encode($response);
