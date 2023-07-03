<?php
// update_status.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the doctor ID and new status from the POST data
    $doctorId = $_POST['doctorId'];
    $newStatus = $_POST['newStatus'];

    // Perform the database update
    // Replace DB_HOST, DB_USER, DB_PASSWORD, and DB_NAME with your actual database credentials
    $conn = mysqli_connect("localhost", "root", "", "doctor_appointment_system");
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit;
    }

    $updateQuery = "UPDATE doctors SET status = '$newStatus' WHERE doctor_id = $doctorId";
    $result = mysqli_query($conn, $updateQuery);
    if ($result) {
        // The update was successful
        header("Location: admin.php");
        echo "Status updated successfully";
    } else {
        // The update failed
        header("Location: admin.php");
        echo "Failed to update status";
    }

    mysqli_close($conn);
} else {
    // Invalid request method
    header("Location: admin.php");
    echo "Invalid request";
}
?>
