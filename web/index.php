<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Appointment System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('../img/index.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
        }
    </style>
</head>

<body>
    <!-- header start -->
    <header class="bg-gray-200 bg-opacity-75 text-white py-2 fixed w-full top-0 ">
        <?php include '../includes/navbar.php'; ?>
    </header>
    <!-- header end -->

    <main class="container mx-auto px-4 py-8">
        <!-- <div class="text-center pt-28 flex justify-between items-center">
        <div class="w-1/2 bg-blue-100 bg-opacity-75 border-lg rounded px-4 py-16 mt-8 mr-4 h-full">
            <h2 class="text-3xl font-bold mb-4">Welcome to Medi-Lab.</h2>
            <p class="text-gray-600">Medi-Lab is a state-of-the-art healthcare platform that connects patients with doctors for convenient and efficient medical appointments.</p>
            </br>
            <p class="text-gray-600">Please <span class="text-red-500">log in</span> or register to proceed.</p>
        </div>

        <div class="mt-8 w-1/2 flex">
            <div class="items-center bg-blue-100 bg-opacity-75 border-lg rounded p-4 mr-2 h-full">
                <h3 class="text-xl font-semibold mb-2 p-2">Doctors</h3>
                <p class="text-gray-800 mb-4">Are you a doctor? Log in or Register as a doctor to manage appointments.</p>
                <a href="register_doctor.php" class="inline-block mx-auto w-48 bg-gray-800 text-white px-6 py-2 rounded-lg text-medium hover:bg-gray-600">Register as Doctor</a>
                <p class="text-gray-500 mx-auto mt-2">Already registered? Log in..</p>
                <a href="doctor_login.php" class="mt-4 inline-block mx-auto w-48 bg-gray-800 text-white px-6 py-2 rounded-lg text-medium hover:bg-gray-600">Doctor Login</a>
            </div>

            <div class="items-center bg-blue-100 bg-opacity-75 border-lg rounded p-4 ml-2 h-full">
                <h3 class="text-xl font-semibold mb-2 p-2">Patients</h3>
                <p class="text-gray-800 mb-4">Are you a patient? Log in or register as a Patient to book appointments.</p>
                <a href="register_patient.php" class="inline-block mx-auto w-48 bg-gray-800 text-white px-6 py-2 rounded-lg text-medium hover:bg-gray-600">Register as Patient</a>
                <p class="text-gray-500 mx-auto mt-2">Already registered? Log in..</p>
                <a href="patient_login.php" class="mt-4 inline-block mx-auto w-48 bg-gray-800 text-white px-6 py-2 rounded-lg text-medium hover:bg-gray-600">Patient Login</a>
            </div>
        </div>
    </div> -->

    <?php
        // Check if a patient is logged in
        if (isset($_SESSION['patient_id'])) {
            // Establish a database connection
            $conn = new mysqli('localhost', 'root', '', 'doctor_appointment_system');

            // Check if the connection was successful
            if ($conn->connect_error) {
                die('Connection failed: ' . $conn->connect_error);
            }

            // Retrieve the patient's name from the database
            $patientId = $_SESSION['patient_id'];
            $query = "SELECT name FROM patients WHERE patient_id = $patientId";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $patientName = $row['name'];
            } else {
                $patientName = "Unknown";
            }

            // Close the database connection
            $conn->close();

            echo '<div class="text-center pt-28">';
            echo '<h2 class="text-3xl font-bold mb-4">Welcome, ' . $patientName . '!</h2>';
            echo '<p class="text-gray-600">You are logged in as a patient.</p>';
            echo '<a href="patient_dashboard.php" class="inline-block mt-4 w-48 bg-gray-800 text-white px-6 py-2 rounded-lg text-medium hover:bg-gray-600">Go to Dashboard</a>';
            echo '</div>';
        }
        // Check if a doctor is logged in
        elseif (isset($_SESSION['doctor_id'])) {
            // Establish a database connection
            $conn = new mysqli('localhost', 'root', '', 'doctor_appointment_system');

            // Check if the connection was successful
            if ($conn->connect_error) {
                die('Connection failed: ' . $conn->connect_error);
            }

            // Retrieve the doctor's name from the database
            $doctorId = $_SESSION['doctor_id'];
            $query = "SELECT name FROM doctors WHERE doctor_id = $doctorId";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $doctorName = $row['name'];
            } else {
                $doctorName = "Unknown";
            }

            // Close the database connection
            $conn->close();

            echo '<div class="text-center pt-28">';
            echo '<h2 class="text-3xl font-bold mb-4">Welcome, ' . $doctorName . '!</h2>';
            echo '<p class="text-gray-600">You are logged in as a doctor.</p>';
            echo '<a href="doctor_dashboard.php" class="inline-block mt-4 w-48 bg-gray-800 text-white px-6 py-2 rounded-lg text-medium hover:bg-gray-600">Go to Dashboard</a>';
            echo '</div>';
        } else {
            echo '<div class="text-center pt-28 flex justify-between items-center">';
            echo '<div class="w-1/2 bg-blue-100 bg-opacity-75 border-lg rounded px-4 py-16 mt-8 mr-4 h-full">';
            echo '<h2 class="text-3xl font-bold mb-4">Welcome to Medi-Lab.</h2>';
            echo '<p class="text-gray-600">Medi-Lab is a state-of-the-art healthcare platform that connects patients with doctors for convenient and efficient medical appointments.</p>';
            echo '</br>';
            echo '<p class="text-gray-600">Please <span class="text-red-500">log in</span> or register to proceed.</p>';
            echo '</div>';

            echo '<div class="mt-8 w-1/2 flex">';
            echo '<div class="items-center bg-blue-100 bg-opacity-75 border-lg rounded p-4 mr-2 h-full">';
            echo '<h3 class="text-xl font-semibold mb-2 p-2">Doctors</h3>';
            echo '<p class="text-gray-800 mb-4">Are you a doctor? Log in or Register as a doctor to manage appointments.</p>';
            echo '<a href="register_doctor.php" class="inline-block mx-auto w-48 bg-gray-800 text-white px-6 py-2 rounded-lg text-medium hover:bg-gray-600">Register as Doctor</a>';
            echo '<p class="text-gray-500 mx-auto mt-2">Already registered? Log in..</p>';
            echo '<a href="doctor_login.php" class="mt-4 inline-block mx-auto w-48 bg-gray-800 text-white px-6 py-2 rounded-lg text-medium hover:bg-gray-600">Doctor Login</a>';
            echo '</div>';

            echo '<div class="items-center bg-blue-100 bg-opacity-75 border-lg rounded p-4 ml-2 h-full">';
            echo '<h3 class="text-xl font-semibold mb-2 p-2">Patients</h3>';
            echo '<p class="text-gray-800 mb-4">Are you a patient? Log in or register as a Patient to make appointments.</p>';
            echo '<a href="register_patient.php" class="inline-block mx-auto w-48 bg-gray-800 text-white px-6 py-2 rounded-lg text-medium hover:bg-gray-600">Register as Patient</a>';
            echo '<p class="text-gray-500 mx-auto mt-2">Already registered? Log in..</p>';
            echo '<a href="patient_login.php" class="mt-4 inline-block mx-auto w-48 bg-gray-800 text-white px-6 py-2 rounded-lg text-medium hover:bg-gray-600">Patient Login</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </main>

    <!-- footer start -->
    <?php include '../includes/footer.php'; ?>
    <!-- footer end -->
</body>

</html>