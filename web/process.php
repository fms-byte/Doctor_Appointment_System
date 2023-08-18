<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['doctor_register'])) {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $specialization = $_POST['specialization'];
    $fee = $_POST['fee'];
    $phone = $_POST['phone'];
    $reg_num = $_POST['reg_num'];
    $start = $_POST['start'];
    $end = $_POST['end'];
    $availability = implode(', ', $_POST['availability']); // Convert array to comma-separated string

    // Check if the passwords match
    if ($password != $confirm_password) {
        echo '<script>alert("Passwords do not match"); window.location.href = "register_doctor.php";</script>';
        exit();
    }

    // Connect to the database (replace DB_HOST, DB_USER, DB_PASSWORD, and DB_NAME with your actual database credentials)
    $conn = mysqli_connect("localhost", "root", "", "doctor_appointment_system");

    // Check connection
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit;
    }

    // Prepare and execute the SQL query for doctor registration
    $query = "INSERT INTO doctors (name, email, phone, password, specialization,fee, reg_num, start, end, availability) VALUES ('$name', '$email', '$phone', '$password', '$specialization','$fee', '$reg_num', '$start', '$end', '$availability')";

    if (mysqli_query($conn, $query)) {
        echo '<script>alert("Doctor registration successful!"); window.location.href = "doctor_login.php";</script>';
        exit();
    } else {
        echo '<script>alert("Error: ' . mysqli_error($conn) . '"); window.location.href = "register_doctor.php";</script>';
        exit();
    }

    // Close the database connection

} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['doctor_login'])) {
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Connect to the database (replace DB_HOST, DB_USER, DB_PASSWORD, and DB_NAME with your actual database credentials)
    $conn = mysqli_connect("localhost", "root", "", "doctor_appointment_system");

    // Check connection
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit;
    }

    // Prepare and execute the SQL query for doctor login
    $query = "SELECT * FROM doctors WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        // Doctor found, login successful
        $doctor = mysqli_fetch_assoc($result);
        $_SESSION["doctor_id"] = $doctor["doctor_id"];
        $_SESSION["doctor_name"] = $doctor["name"];

        //alert message saying welcome back doctor name
        $welcomeMessage = "Welcome back " . $doctor["name"];

        // Redirect to the doctor's dashboard or any other page you want
        header("Location: doctor_dashboard.php");
        exit();
    } else {
        // Doctor not found, login failed
        echo '<script>alert("Invalid email or password"); window.location.href = "doctor_login.php";</script>';
        exit();
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['patient_register'])) {
    // Get the form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $age = $_POST['age'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if the passwords match
    if ($password != $confirm_password) {
        echo '<script>alert("Passwords do not match"); window.location.href = "register_patient.php";</script>';
        exit();
    }

    $conn = mysqli_connect("localhost", "root", "", "doctor_appointment_system");

    // Check connection
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit;
    }

    // Prepare and execute the SQL query for doctor registration
    $query = "INSERT INTO patients (name, email, phone, age, password) VALUES ('$name', '$email', '$phone', '$age', '$password')";

    if (mysqli_query($conn, $query)) {
        echo '<script>alert("Patient registration successful!"); window.location.href = "patient_login.php";</script>';
        exit();
    } else {
        echo '<script>alert("Error: ' . mysqli_error($conn) . '"); window.location.href = "register_patient.php";</script>';
        exit();
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['patient_login'])) {
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Connect to the database (replace DB_HOST, DB_USER, DB_PASSWORD, and DB_NAME with your actual database credentials)
    $conn = mysqli_connect("localhost", "root", "", "doctor_appointment_system");

    // Check connection
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit;
    }

    // Prepare and execute the SQL query for doctor login
    $query = "SELECT * FROM patients WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        // Doctor found, login successful
        $patient = mysqli_fetch_assoc($result);
        $_SESSION["patient_id"] = $patient["patient_id"];
        $_SESSION["patient_name"] = $patient["name"];

        // Redirect to the doctor's dashboard or any other page you want
        header("Location: patient_dashboard.php");
        exit();
    } else {
        // Doctor not found, login failed
        echo '<script>alert("Invalid email or password"); window.location.href = "patient_login.php";</script>';
        exit();
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['doctor_update'])) {
    
    $conn = mysqli_connect("localhost", "root", "", "doctor_appointment_system");

    // Check connection
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit;
    }

    // Prepare and execute the SQL query to update the doctor's information
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $phone = $_POST['phone'];
    $reg_num = $_POST['reg_num'];
    $specialization = $_POST['specialization'];
    $fee = $_POST['fee'];
    $availability = implode(", ", $_POST['availability']);
    $start = $_POST['start'];
    $end = $_POST['end'];
    $doctorId = $_SESSION["doctor_id"];

    // Check if the passwords match
    if ($password != $confirm_password) {
        echo '<script>alert("Passwords do not match"); window.location.href = "edit_profile.php";</script>';
        exit();
    }

    // Handle file upload
    if ($_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
        $fileName = $_FILES['profile_picture']['name'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $newFileName = $doctorId . '_' . time() . '.' . $fileExtension;
        $uploadDirectory = '../img/';

        // Move the uploaded file to the desired location
        if (move_uploaded_file($fileTmpPath, $uploadDirectory . $newFileName)) {
            // File upload was successful
            $profilePicture = $uploadDirectory . $newFileName;
        } else {
            // File upload failed
            $profilePicture = '../img/doctor.png';
            echo "Error uploading profile picture.";
        }
    } else {
        // No file was uploaded or an error occurred
        $profilePicture = '../img/doctor.png';
    }

    $updateQuery = "UPDATE doctors SET name = '$name', password = '$password',phone = '$phone', reg_num='$reg_num', specialization = '$specialization', fee='$fee', availability = '$availability', start = '$start', end = '$end', profile_picture = '$profilePicture' WHERE doctor_id = $doctorId";

    $updateResult = mysqli_query($conn, $updateQuery);

    if ($updateResult) {
        echo "Profile updated successfully.";
        header("Location: doctor_dashboard.php");
    } else {
        echo "Error updating profile: " . mysqli_error($conn);
    }

    

    mysqli_close($conn);
}
