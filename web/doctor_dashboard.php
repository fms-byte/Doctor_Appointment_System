<?php
session_start();

// Check if the doctor is logged in
if (!isset($_SESSION["doctor_id"])) {
    header("Location: doctor_login.php");
    exit();
}

// Get the doctor's ID from the session
$doctorId = $_SESSION["doctor_id"];

// Connect to the database (replace DB_HOST, DB_USER, DB_PASSWORD, and DB_NAME with your actual database credentials)
$conn = mysqli_connect("localhost", "root", "", "doctor_appointment_system");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit;
}

// Prepare and execute the SQL query to retrieve the doctor's information
$doctorQuery = "SELECT * FROM doctors WHERE doctor_id = $doctorId";
$doctorResult = mysqli_query($conn, $doctorQuery);
$doctor = mysqli_fetch_assoc($doctorResult);

// Check if the doctor's account is pending
if ($doctor['status'] == 'pending') {
    $message = "Your account is pending approval.";
}

// Prepare and execute the SQL query to retrieve the doctor's appointments with patient's name
$query = "SELECT a.appointment_id, p.name, a.appointment_date, a.appointment_time, a.status 
          FROM appointments a
          JOIN patients p ON a.patient_id = p.patient_id
          WHERE a.doctor_id = $doctorId";
$result = mysqli_query($conn, $query);

// Fetch the appointments as an associative array
$appointments = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-gray-200 text-white py-2 fixed w-full top-0 ">
        <?php include '../includes/navbar.php'; ?>
    </header>

    <!-- Main Content -->
    <div class="container mx-auto my-16">
        <h1 class="text-2xl font-bold mb-4 mt-4">Doctor Dashboard</h1>

        <!-- Doctor's Information -->
        <div class="w-full mx-auto mb-8">
            <div class="bg-white shadow-md rounded p-4 flex flex-col items-center justify-center lg:flex-row lg:items-center">
                <div class="w-1/5 text-center">
                    <img src="path_to_doctor_image" alt="Doctor's Image" class="w-16 h-16 rounded-full mr-4">
                </div>
                <div class="w-3/5">
                    <h2 class="text-2xl font-bold mb-2"><?php echo $doctor['name']; ?></h2>
                    <p><strong>Speciality:</strong> <?php echo $doctor['specialization']; ?></p>
                    <p><strong>Email:</strong> <?php echo $doctor['email']; ?></p>
                    <p><strong>Availability:</strong> <?php echo $doctor['availability']; ?></p>
                    <p><strong>Working Hours:</strong> <?php echo $doctor['start']; ?> - <?php echo $doctor['end']; ?></p>
                </div>
                <div class="w-1/5 lg:ml-4 text-right">
                    <a href="edit_doctor.php" class="bg-gray-700 text-white px-4 py-2 rounded-md hover:bg-gray-600">Edit Profile</a>
                </div>
            </div>
        </div>

        <!-- Show message if doctor's account is pending -->
        <?php if (isset($message)) : ?>
            <div class="bg-yellow-200 text-yellow-800 rounded p-4 mb-4">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Appointments Table -->
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr>
                    <th class="px-4 py-2 border-b">Appointment ID</th>
                    <th class="px-4 py-2 border-b">Patient Name</th>
                    <th class="px-4 py-2 border-b">Date</th>
                    <th class="px-4 py-2 border-b">Time</th>
                    <th class="px-4 py-2 border-b">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $appointment) : ?>
                    <tr>
                        <td class="px-4 py-2 border-b text-center"><?php echo $appointment['appointment_id']; ?></td>
                        <td class="px-4 py-2 border-b text-center"><?php echo $appointment['name']; ?></td>
                        <td class="px-4 py-2 border-b text-center"><?php echo $appointment['appointment_date']; ?></td>
                        <td class="px-4 py-2 border-b text-center"><?php echo $appointment['appointment_time']; ?></td>
                        <td class="px-4 py-2 border-b text-center"><?php echo $appointment['status']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>
