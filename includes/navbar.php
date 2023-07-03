<div class="container mx-auto px-4">
    <?php
    // Check if the user is logged in as a doctor
    if (isset($_SESSION["doctor_id"])) {
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
    }

    // Check if the user is logged in as a patient
    if (isset($_SESSION["patient_id"])) {
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
    }
    ?>
    <nav class="flex justify-between">
        <ul>
            <li>
                <a href="index.php" class="font-bold text-2xl"><span style="color: #DF2E38">Medi</span>-<span style="color: #FFD6A5">lab</span></a>
            </li>
        </ul>
        <ul class="flex my-auto">
            <li class="mr-4">
                <a href="index.php" class="font-medium text-gray-800 hover:text-red-500">Home</a>
            </li>
            <!-- <li class="mr-4">
                <a href="make_appointment.php" class="font-medium text-gray-800 hover:text-red-500">Make Appointment</a>
            </li>
            <li class="mr-4">
                <a href="view_appointments.php" class="font-medium text-gray-800 hover:text-red-500">View Appointments</a>
            </li> -->
            <li class="mr-4">
                <a href="contact.php" class="font-medium text-gray-800 hover:text-red-500">Contact</a>
            </li>
            <li class="mr-4">
                <a href="about.php" class="font-medium text-gray-800 hover:text-red-500">About</a>
            </li>
            <?php if (isset($doctor)) { ?>
                <li>
                    <a href="doctor_dashboard.php" class="font-medium text-gray-800 hover:text-red-500 mr-2"><?php echo $doctor['name']; ?></a>
                </li>
                <li>
                    <a href="logout.php" class="font-medium bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-500">Logout</a>
                </li>
            <?php } elseif (isset($patient)) { ?>
                <li>
                <a href="patient_dashboard.php" class="font-medium text-gray-800 hover:text-red-500 mr-2"><?php echo $patient['name']; ?></a>
                </li>
                <li>
                    <a href="logout.php" class="font-medium bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-500">Logout</a>
                </li>
            <?php } else { ?>
                <li>
                    <a href="index.php" class="font-medium bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-500">Login</a>
                </li>
            <?php } ?>
        </ul>
    </nav>
</div>
