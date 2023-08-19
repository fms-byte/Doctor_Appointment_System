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
if (!$doctorResult || mysqli_num_rows($doctorResult) === 0) {
    // Doctor record not found
    echo '<script>alert("Your account is no longer active. Please contact admin for more information. Redirecting to home page...");</script>';
    // Wait for 2 seconds (in milliseconds)
    echo '<meta http-equiv="refresh" content="2;url=index.php">';
    // Destroy the session
    session_destroy();
    exit();
} else {
    $doctor = mysqli_fetch_assoc($doctorResult);

    // Check if the doctor's account is pending
    if ($doctor['status'] == 'pending') {
        $message = "Your account is pending approval. Patient services will resume after verification. Please wait for verification or contact admin after 24 hours. Thank you for your patience.";
    }
}



// Prepare and execute the SQL query to retrieve the doctor's appointments with patient's name
$query = "SELECT a.appointment_id, p.name, p.phone, a.appointment_date, a.appointment_time, a.status 
          FROM appointments a
          JOIN patients p ON a.patient_id = p.patient_id
          WHERE a.doctor_id = $doctorId";
$result = mysqli_query($conn, $query);

// Fetch the appointments as an associative array
$appointments = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the appointment ID and new status from the POST data
    $appointmentId = $_POST["appointment_id"];
    $newStatus = $_POST["new_status"];

    // Connect to the database (replace DB_HOST, DB_USER, DB_PASSWORD, and DB_NAME with your actual database credentials)
    $conn = mysqli_connect("localhost", "root", "", "doctor_appointment_system");

    // Check connection
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit;
    }

    // Prepare and execute the SQL query to update the appointment status
    $updateQuery = "UPDATE appointments SET status = '$newStatus' WHERE appointment_id = $appointmentId";
    $updateResult = mysqli_query($conn, $updateQuery);

    if ($updateResult) {
        // Status updated successfully
        // header("Location: doctor_dashboard.php");
        echo "Success";
    } else {
        // Failed to update status
        echo "Error";
    }
}

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
                    <img src="<?php echo isset($doctor['profile_picture']) ? $doctor['profile_picture'] : '../img/doctor.png'; ?>" alt="Doctor's Image" class="w-48 h-48 rounded-full mr-4">

                </div>
                <div class="w-3/5">
                    <h2 class="text-2xl font-bold mb-2"><?php echo $doctor['name']; ?></h2>
                    <p>Speciality:<strong>&nbsp <?php echo $doctor['specialization']; ?></strong></p>
                    <p>Email:<strong> &nbsp<?php echo $doctor['email']; ?></strong></p>
                    <p>Contact:<strong> &nbsp<?php echo $doctor['phone']; ?></strong></p>
                    <p>Availability:<strong>&nbsp <?php echo $doctor['availability']; ?></strong></p>
                    <p>Working Hours:<strong> &nbsp<?php echo $doctor['start']; ?> - <?php echo $doctor['end']; ?></strong></p>
                    <p>Registration Number:<strong> &nbsp<?php echo $doctor['reg_num']; ?></strong></p>
                </div>
                <div class="w-1/5 lg:ml-4 text-right">
                    <a href="edit_profile.php" class="bg-gray-700 text-white px-4 py-2 rounded-md hover:bg-gray-600">Update Profile</a>
                </div>
            </div>
        </div>

        <!-- Show message if doctor's account is pending -->
        <?php if (isset($message)) : ?>
            <div class="bg-yellow-300 text-yellow-900 rounded p-4 mb-4">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Appointments Table -->
        <table class="min-w-full bg-white border border-gray-300">
            <thead class="bg-gray-200">
                <tr>
                    <th class="w-1/6 px-4 py-2 border border-gray-400">Appointment ID</th>
                    <th class="w-1/6 px-4 py-2 border border-gray-400">Patient Name</th>
                    <th class="w-1/6 px-4 py-2 border border-gray-400">Contact</th>
                    <th class="w-1/6 px-4 py-2 border border-gray-400">Date</th>
                    <th class="w-1/6 px-4 py-2 border border-gray-400">Time</th>
                    <th class="w-1/6 px-4 py-2 border border-gray-400">Status</th>
                    <th class="w-1/6 px-4 py-2 border border-gray-400">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $appointment) : ?>
                    <tr>
                        <td class="px-4 py-2 border-b text-center"><?php echo $appointment['appointment_id']; ?></td>
                        <td class="px-4 py-2 border-b text-center"><?php echo $appointment['name']; ?></td>
                        <td class="px-4 py-2 border-b text-center"><?php echo $appointment['phone']; ?></td>
                        <td class="px-4 py-2 border-b text-center"><?php echo $appointment['appointment_date']; ?></td>
                        <td class="px-4 py-2 border-b text-center"><?php echo $appointment['appointment_time']; ?></td>
                        <td class="px-4 py-2 border-b text-center"><?php echo $appointment['status']; ?></td>
                        <td class="px-4 py-2 border-b text-center">
                            <?php if ($appointment['status'] == 'confirmed') : ?>
                                <button onclick="changeStatus(<?php echo $appointment['appointment_id']; ?>, 'completed')" class="bg-green-300 text-black px-3  rounded-md hover:bg-green-200">Completed</button>
                                <button onclick="changeStatus(<?php echo $appointment['appointment_id']; ?>, 'absent')" class="mt-1 bg-red-500 text-white px-3 rounded-md hover:bg-red-600">Absent</button>
                            <?php elseif ($appointment['status'] == 'completed') : ?>
                                <span class="text-green-500">Completed</span>
                            <?php elseif ($appointment['status'] == 'absent') : ?>
                                <span class="text-red-500">Absent</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script>
        function changeStatus(appointmentId, newStatus) {
            // Send an AJAX request to update the status using process.php
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'doctor_dashboard.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Status updated successfully
                        const statusCell = document.querySelector(`#status-${appointmentId}`);
                        statusCell.textContent = newStatus;
                        alert('Status updated successfully.');

                        // Reload the page to reflect the updated status
                        
                    } else {
                        // Failed to update status
                        alert('Failed to update status.');
                    }
                }
            };
            location.reload();

            const data = `appointment_id=${appointmentId}&new_status=${newStatus}`;
            xhr.send(data);
        }
    </script>
</body>

</html>