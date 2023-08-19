<?php
session_start();

// Check if the patient is logged in
if (!isset($_SESSION["patient_id"])) {
    header("Location: patient_login.php");
    exit();
}

// Get the patient's ID from the session
$patientId = $_SESSION["patient_id"];

// Connect to the database (replace DB_HOST, DB_USER, DB_PASSWORD, and DB_NAME with your actual database credentials)
$conn = mysqli_connect("localhost", "root", "", "doctor_appointment_system");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit;
}

// Prepare and execute the SQL query to retrieve the patient's information
$patientQuery = "SELECT * FROM patients WHERE patient_id = $patientId";
$patientResult = mysqli_query($conn, $patientQuery);
$patient = mysqli_fetch_assoc($patientResult);

// Prepare and execute the SQL query to retrieve the patient's appointment history with doctor names
$appointmentQuery = "SELECT a.*, d.name AS doctor_name, d.phone AS doctor_phone, d.reg_num AS doctor_reg_num FROM appointments a INNER JOIN doctors d ON a.doctor_id = d.doctor_id WHERE a.patient_id = $patientId";
$appointmentResult = mysqli_query($conn, $appointmentQuery);
$appointments = mysqli_fetch_all($appointmentResult, MYSQLI_ASSOC);

// Prepare the base query to retrieve approved doctors
$query = "SELECT * FROM doctors WHERE status = 'approved'";

// Check if a specialization is provided in the search
if (isset($_GET['specialization']) && !empty($_GET['specialization'])) {
    $specialization = $_GET['specialization'];
    // Add a WHERE clause to filter by specialization
    $query .= " AND specialization LIKE '%$specialization%'";
}

// Execute the query
$result = mysqli_query($conn, $query);

// Fetch the doctors as an associative array
$doctors = mysqli_fetch_all($result, MYSQLI_ASSOC);
if (isset($_GET['error']) && $_GET['error'] == 1 && isset($_GET['message'])) {
    $errorMessage = $_GET['message'];
    echo '<script>alert("' . $errorMessage . '");</script>';
}

// Close the database connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            /* background-image: url('../img/cover.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            height: 100vh; */
            background: #f3f4f6;
        }
    </style>
</head>

<body>
    <!-- header start -->
    <header class="bg-gray-200 text-white py-2 fixed w-full top-0">
        <?php include '../includes/navbar.php'; ?>
    </header>
    <!-- header end -->

    <div class="container mx-auto py-8 mb-8 mt-20">
        <div class="mx-auto bg-white bg-opacity-90 p-4 border border-gray-300 rounded shadow-md">
            <h2 class="text-2xl font-bold mb-2">Patient Information</h2>
            <div class="w-full mx-auto bg-blue-200 flex justify-center p-4 mb-4 text-l">
                <div class="w-1/4 text-center flex items-center justify-center"><strong>Name:</strong>&nbsp;<?php echo $patient['name']; ?></div>
                <div class="w-1/4 flex items-center justify-center"><strong>Email:</strong>&nbsp;<?php echo $patient['email']; ?></div>
                <div class="w-1/4 flex items-center justify-center"><strong>Contact Number:</strong>&nbsp;<?php echo $patient['phone']; ?></div>
                <div class="w-1/4 flex items-center justify-center"><strong>Age:</strong>&nbsp;<?php echo $patient['age']; ?></div>
            </div>


            <p><strong>Appointment History:</strong></p>
            <?php if (empty($appointments)) { ?>
                <div class="flex justify-center items-center h-48">
                <p>No history found.</p>
                </div>
                
            <?php } else { ?>
                <table class="w-3/4 mx-auto bg-white border border-gray-300 mt-4">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-4 py-2 border-b">Doctor</th>
                            <th class="px-4 py-2 border-b">Contact</th>
                            <th class="px-4 py-2 border-b">Registration No.</th>
                            <th class="px-4 py-2 border-b">Date</th>
                            <th class="px-4 py-2 border-b">Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($appointments as $appointment) {
                            echo '<tr>';
                            echo '<td class="px-4 py-2 border-b text-center">' . $appointment['doctor_name'] . '</td>';
                            echo '<td class="px-4 py-2 border-b text-center">' . $appointment['doctor_phone'] . '</td>';
                            echo '<td class="px-4 py-2 border-b text-center">' . $appointment['doctor_reg_num'] . '</td>';
                            echo '<td class="px-4 py-2 border-b text-center">' . $appointment['appointment_date'] . '</td>';
                            echo '<td class="px-4 py-2 border-b text-center">' . $appointment['appointment_time'] . '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            <?php } ?>

        </div>

        <div class="mx-auto bg-white bg-opacity-90 p-4 border border-gray-300 rounded shadow-md mt-8">


            <h2 class="text-2xl font-bold mt-4 mb-4">Available Doctors</h2>
            <div class="mb-4 flex items-center">
                <label for="specialization" class="text-gray-700">Search by Specialization:</label>
                <input type="text" id="specialization" name="specialization" placeholder="click the search to view all" class="ml-2 px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <button type="button" id="searchBtn" class="ml-2 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Search</button>
            </div>
            <table class="min-w-full bg-white border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 border-b">Doctor Name</th>
                        <th class="px-4 py-2 border-b">Specialization</th>
                        <th class="px-4 py-2 border-b">Contact</th>
                        <th class="px-4 py-2 border-b">Consultant Fee</th>
                        <th class="px-4 py-2 border-b">Working Days</th>
                        <th class="px-4 py-2 border-b">Working Hours</th>
                        <th class="px-4 py-2 border-b">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($doctors as $doctor) {
                        echo '<tr>';
                        echo '<td class="px-4 py-2 border-b text-center">' . $doctor['name'] . '</td>';
                        echo '<td class="px-4 py-2 border-b text-center">' . $doctor['specialization'] . '</td>';
                        echo '<td class="px-4 py-2 border-b text-center">' . $doctor['phone'] . '</td>';
                        echo '<td class="px-4 py-2 border-b text-center">' . $doctor['fee'] . ' tk</td>';
                        echo '<td class="px-4 py-2 border-b text-center">' . $doctor['availability'] . '</td>';
                        echo '<td class="px-4 py-2 border-b text-center">' . $doctor['start'] . ' - ' . $doctor['end'] . '</td>';
                        echo '<td class="px-4 py-2 border-b text-center">
                            <form action="make_appointment.php?doctor_id=' . $doctor['doctor_id'] . '" method="POST">
                                <div class="flex items-center justify-center">
                                    <button type="submit" name="make_appointment" class="bg-blue-500 text-white p-1 rounded-md hover:bg-blue-600">Make Appointment</button>
                                </div>
                            </form>
                            </td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

<script>
    document.getElementById("searchBtn").addEventListener("click", function() {
        var specialization = document.getElementById("specialization").value;
        window.location.href = "patient_dashboard.php?specialization=" + specialization;
    });
</script>

</html>