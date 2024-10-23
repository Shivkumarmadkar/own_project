<?php
include 'db_connect.php'; // Ensure your database connection is included
session_start();  // Start the session
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mobile = $_POST['mobile'] ?? '';
    $enteredOtp = $_POST['otp'] ?? '';

    // Validate inputs
    if (empty($mobile) || strlen($mobile) != 10) {
        echo json_encode(['error' => true, 'message' => 'Invalid or missing mobile number.']);
        exit;
    }

    if (empty($enteredOtp) || strlen($enteredOtp) != 6) {
        echo json_encode(['error' => true, 'message' => 'Invalid or missing OTP.']);
        exit;
    }

    
     // Verify OTP from session
     if (isset($_SESSION['otp'])) {
        if ($_SESSION['otp'] == $enteredOtp) {
            echo json_encode(['error' => false, 'message' => 'OTP verified successfully.']);

            unset($_SESSION['otp']);
        } else {
            echo json_encode(['error' => true, 'message' => 'Invalid OTP. Please try again.']);
        }
    } else {
        echo json_encode(['error' => true, 'message' => 'OTP expired or not found. Please resend OTP.']);
    }

 
} else {
    echo json_encode(['error' => true, 'message' => 'Invalid request method. Use POST.']);
}
?>





<?php

   // // Fetch the latest OTP for the given mobile number
    // $query = "SELECT otp FROM voter_complain_otp_all 
    //           WHERE mobile_no = ? ORDER BY created_at DESC LIMIT 1";
    // $stmt = $conn->prepare($query);
    // $stmt->bind_param('s', $mobile);
    // $stmt->execute();
    // $stmt->bind_result($storedOtp);
    // $stmt->fetch();
    // $stmt->close();

    // // Check if OTP matches
    // if ($storedOtp && $storedOtp === $enteredOtp) {
    //     echo json_encode(['error' => false, 'message' => 'OTP verified successfully.']);
    // } else {
    //     echo json_encode(['error' => true, 'message' => 'Invalid OTP. Please try again.']);
    // }

?>