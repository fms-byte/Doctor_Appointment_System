<?php
session_start();

// Check if the patient is logged in
if (!isset($_SESSION["patient_id"])) {
    header("Location: patient_login.php");
    exit();
}

// Check if the doctor_id is provided in the POST request
if (isset($_POST["doctor_id"])) {
    $doctorId = $_POST["doctor_id"];

    // Example code to insert the appointment into the database
    $patientId = $_SESSION["patient_id"];
    $appointmentDate = $_POST["appointment_date"]; // Get the selected appointment date

    // Connect to the database (replace DB_HOST, DB_USER, DB_PASSWORD, and DB_NAME with your actual database credentials)
    $conn = mysqli_connect("localhost", "root", "", "doctor_appointment_system");

    // Check connection
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit;
    }

    // Prepare the SQL query to retrieve the doctor's working hours
    $doctorQuery = "SELECT start, end FROM doctors WHERE doctor_id = $doctorId";

    // Execute the query
    $doctorResult = mysqli_query($conn, $doctorQuery);
    $doctor = mysqli_fetch_assoc($doctorResult);

    // Prepare the SQL query to retrieve the doctor's available days
    $doctorDaysQuery = "SELECT availability FROM doctors WHERE doctor_id = $doctorId";

    // Execute the query
    $doctorDaysResult = mysqli_query($conn, $doctorDaysQuery);
    $doctorDays = mysqli_fetch_assoc($doctorDaysResult);

    // Check if the doctor's available days are fetched successfully
    if ($doctorDays && isset($doctorDays['availability'])) {
        $availableDays = explode(",", $doctorDays['availability']);
        $selectedDay = date('l', strtotime($appointmentDate));

        // Check if the selected day is in the doctor's available days
        if (in_array($selectedDay, array_map('trim', $availableDays))) {
            // Check if the doctor's working hours are available
            if ($doctor && isset($doctor['start']) && isset($doctor['end'])) {
                $startTime = $doctor['start'];
                $endTime = $doctor['end'];

                // Prepare the SQL query to retrieve the latest appointment for the doctor on the selected date
                $latestAppointmentQuery = "SELECT appointment_time FROM appointments WHERE doctor_id = $doctorId AND appointment_date = '$appointmentDate' ORDER BY appointment_time DESC LIMIT 1";

                // Execute the query
                $latestAppointmentResult = mysqli_query($conn, $latestAppointmentQuery);
                $latestAppointment = mysqli_fetch_assoc($latestAppointmentResult);

                if ($latestAppointment && isset($latestAppointment['appointment_time'])) {
                    $lastAppointmentTime = $latestAppointment['appointment_time'];
                    $nextAppointmentTime = date('H:i:s', strtotime($lastAppointmentTime . ' + 30 minutes'));
                } else {
                    // If there are no previous appointments on the selected date, set the appointment time to the start time
                    $nextAppointmentTime = $startTime;
                }

                // Check if the next appointment time is within the working hours
                if ($nextAppointmentTime <= $endTime) {
                    // Prepare the SQL query to insert the appointment
                    $insertQuery = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time) VALUES ($patientId, $doctorId, '$appointmentDate', '$nextAppointmentTime')";

                    // Execute the query
                    $result = mysqli_query($conn, $insertQuery);

                    // Check if the appointment was successfully inserted
                    if ($result) {
                        // Redirect to the patient_dashboard.php with a success message
                        header("Location: patient_dashboard.php?success=1");
                        exit();
                    } else {
                        echo '<script>alert("Failed to insert the appointment."); window.location.href = "patient_dashboard.php";</script>';
                        exit();
                    }
                } else {
                    echo '<script>alert("No available slots for the selected date."); window.location.href = "patient_dashboard.php";</script>';
                    exit();
                }
            }
        } else {
            echo '<script>alert("The doctor is not available on the selected date."); window.location.href = "patient_dashboard.php";</script>';
            exit();
        }
    }
    echo '<script>alert("No available slots for the selected date."); window.location.href = "patient_dashboard.php";</script>';
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
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

    <div class="container w-1/2 mx-auto py-8 mb-8 mt-20">
        <div class="mx-auto bg-white bg-opacity-90 p-4 border border-gray-300 rounded shadow-md">
            <h2 class="text-2xl font-bold mb-4">Make Appointment</h2>
            <?php
            // Retrieve the doctor's details from the database using doctor_id
            $doctorId = $_GET['doctor_id'];
            // Connect to the database and execute a query to fetch doctor's details (replace with your own code)
            $conn = mysqli_connect("localhost", "root", "", "doctor_appointment_system");
            $query = "SELECT * FROM doctors WHERE doctor_id = $doctorId";
            $result = mysqli_query($conn, $query);
            $doctor = mysqli_fetch_assoc($result);

            // Check if the doctor's details are fetched successfully
            if ($doctor) {
                $doctorName = $doctor['name'];
                $specialization = $doctor['specialization'];
            ?>
                <form action="make_appointment.php" method="POST">
                    <input type="hidden" name="doctor_id" value="<?php echo $doctorId; ?>">
                    <div class="flex flex-col mb-4">
                        <label for="doctor_name" class="text-lg font-medium mb-2">Doctor's Name</label>
                        <input type="text" id="doctor_name" name="doctor_name" value="<?php echo $doctorName; ?>" readonly class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex flex-col mb-4">
                        <label for="specialization" class="text-lg font-medium mb-2">Specialization</label>
                        <input type="text" id="specialization" name="specialization" value="<?php echo $specialization; ?>" readonly class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex flex-col mb-4">
                        <label for="availability" class="text-lg font-medium mb-2">Available Days</label>
                        <input type="text" id="availability" name="availability" value="<?php echo $doctor['availability']; ?>" readonly class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex flex-col mb-4">
                        <label for="start" class="text-lg font-medium mb-2">Available Time</label>
                        <input type="text" id="start" name="start" value="<?php echo $doctor['start'] . '-' . $doctor['end']; ?>" readonly class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex flex-col mb-4">
                        <label for="appointment_date" class="text-lg font-medium mb-2">Appointment Date</label>
                        <input type="date" id="appointment_date" name="appointment_date" required class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex flex-col mb-4">
                        <label for="appointment_time" class="text-lg font-medium mb-2">Appointment Time </label>
                        <input type="time" id="appointment_time" name="appointment_time" required class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <!-- Add other input fields for appointment details here -->
                    <div class="flex items-center justify-center mt-4">
                        <button type="submit" name="make_appointment" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Confirm Appointment</button>
                    </div>
                </form>
            <?php
            } else {
                echo "Doctor not found.";
            }
            ?>
        </div>
    </div>
</body>

</html>