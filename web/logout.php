<?php
// Start the session
session_start();

// Check if the user is logged in as a doctor
if (isset($_SESSION["doctor_id"])) {
    // Unset the doctor session variable
    unset($_SESSION["doctor_id"]);
    // Redirect to the doctor login page
    header("Location: index.php");
    exit();
}

// Check if the user is logged in as a patient
if (isset($_SESSION["patient_id"])) {
    // Unset the patient session variable
    unset($_SESSION["patient_id"]);
    // Redirect to the patient login page
    header("Location: index.php");
    exit();
}

// If none of the above conditions are met, redirect to the homepage
header("Location: index.php");
exit();
?>
