<?php
// include 'db_connect.php';  

// header('Content-Type: application/json');  

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $mobile = $_POST['mobile'] ?? '';
//     $voterId = $_POST['voterId'] ?? '';

//     if (empty($mobile) || strlen($mobile) != 10) {
//         echo json_encode(['error' => true, 'message' => 'Invalid mobile number.']);
//         exit;
//     }

//     if (empty($voterId)) {
//         echo json_encode(['error' => true, 'message' => 'Voter ID is required.']);
//         exit;
//     }

//     $otp = rand(100000, 999999);

//     $insertQuery = "INSERT INTO voter_complain_otp_all (voter_id, mobile_no, otp, created_at) VALUES (?, ?, ?, NOW())";
//     $stmt = $conn->prepare($insertQuery);
//     $stmt->bind_param('sss', $voterId, $mobile, $otp);

//     if (!$stmt->execute()) {
//         echo json_encode(['error' => true, 'message' => 'Failed to insert OTP.']);
//         exit;
//     }

//     $stmt->close();
//     $conn->close();

//     $message = urlencode("Welcome to BMAPAN. Your OTP to verify contact number is $otp. Developed by MISCOS Technologies Private Limited");
//     $url = "http://api.msg91.com/api/sendhttp.php?authkey=362180A9fmXMgXDi3O65c9e9bdP1&mobiles=91$mobile&message=$message&sender=BMAPAN&route=4&DLT_TE_ID=1307171060435463268";

//     $ch = curl_init();
//     curl_setopt_array($ch, [
//         CURLOPT_URL => $url,
//         CURLOPT_RETURNTRANSFER => true,
//         CURLOPT_POST => true,
//         CURLOPT_SSL_VERIFYHOST => 0,
//         CURLOPT_SSL_VERIFYPEER => 0,
//     ]);

//     $output = curl_exec($ch); 

//     if (curl_errno($ch)) {
//         echo json_encode(['error' => true, 'message' => 'OTP sending failed: ' . curl_error($ch)]);
//     } else {
//         echo json_encode(['error' => false, 'message' => 'OTP sent successfully!', 'otp' => $otp]);
//     }

//     curl_close($ch);
// } else {
//     echo json_encode(['error' => true, 'message' => 'Invalid request method.']);
// }
?>
<?php

session_start();  // Start the session  
include 'db_connect.php';  // Include your DB connection file

header('Content-Type: application/json');  // Set content type to JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mobile = $_POST['mobile'] ?? '';
    $voterId = $_POST['voterId'] ?? '';

    // Validate inputs
    if (empty($mobile) || strlen($mobile) != 10) {
        echo json_encode(['error' => true, 'message' => 'Invalid mobile number.']);
        exit;
    }

    if (empty($voterId)) {
        echo json_encode(['error' => true, 'message' => 'Voter ID is required.']);
        exit;
    }

    // Generate a 6-digit OTP
    $otp = rand(100000, 999999);

    // Store OTP and mobile number in session
    $_SESSION['otp'] = $otp;

    // Prepare the SMS message
    $message = urlencode("Welcome to BMAPAN. Your OTP to verify contact number is $otp. Developed by MISCOS Technologies Private Limited");
    $url = "http://api.msg91.com/api/sendhttp.php?authkey=362180A9fmXMgXDi3O65c9e9bdP1&mobiles=91$mobile&message=$message&sender=BMAPAN&route=4&DLT_TE_ID=1307171060435463268";

    // Initialize cURL
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
    ]);

    $output = curl_exec($ch);  // Execute the cURL request

    if (curl_errno($ch)) {
        echo json_encode(['error' => true, 'message' => 'OTP sending failed: ' . curl_error($ch)]);
    } else {
        echo json_encode(['error' => false, 'message' => 'OTP sent successfully!', 'otp' => $otp]);
    }

    curl_close($ch);
} else {
    echo json_encode(['error' => true, 'message' => 'Invalid request method.']);
}
?>
