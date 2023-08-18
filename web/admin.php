<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION["admin_id"])) {

    header("Location: admin_login.php");
    exit();
}
// Get the admin's ID from the session
$adminId = $_SESSION["admin_id"];

// Connect to the database (replace DB_HOST, DB_USER, DB_PASSWORD, and DB_NAME with your actual database credentials)
$conn = mysqli_connect("localhost", "root", "", "doctor_appointment_system");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit;
}

// Prepare and execute the SQL query to retrieve the admin's information
$query = "SELECT * FROM admins WHERE admin_id = $adminId";
$result = mysqli_query($conn, $query);

// Fetch the admin's information as an associative array
$admin = mysqli_fetch_assoc($result);

// Update doctor status if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["doctor_id"]) && isset($_POST["status"])) {
        $doctorId = $_POST["doctor_id"];
        $status = $_POST["status"];

        // Prepare and execute the SQL query to update the doctor's status
        $updateQuery = "UPDATE doctors SET status = '$status' WHERE doctor_id = $doctorId";
        $updateResult = mysqli_query($conn, $updateQuery);

        if ($updateResult) {
            // Status updated successfully
            echo "Status updated successfully.";
        } else {
            // Failed to update status
            echo "Failed to update status.";
        }
    } elseif (isset($_POST["delete_doctor_id"])) {
        $doctorId = $_POST["delete_doctor_id"];

        // Prepare and execute the SQL query to delete the doctor from the database
        $deleteQuery = "DELETE FROM doctors WHERE doctor_id = $doctorId";
        $deleteResult = mysqli_query($conn, $deleteQuery);

        if ($deleteResult) {
            // Doctor deleted successfully
            echo "Doctor deleted successfully.";
        } else {
            // Failed to delete doctor
            echo "Failed to delete doctor.";
        }
    } elseif (isset($_POST["delete_patient_id"])) {
        $patientId = $_POST["delete_patient_id"];

        // Prepare and execute the SQL query to delete the patient from the database
        $deleteQuery = "DELETE FROM patients WHERE patient_id = $patientId";
        $deleteResult = mysqli_query($conn, $deleteQuery);

        if ($deleteResult) {
            // Patient deleted successfully
            echo "Patient deleted successfully.";
        } else {
            // Failed to delete patient
            echo "Failed to delete patient.";
        }
    }
}

// Prepare and execute the SQL query to retrieve the list of patients
$queryPatients = "SELECT * FROM patients";
$resultPatients = mysqli_query($conn, $queryPatients);

// Fetch the list of patients as an associative array
$patients = mysqli_fetch_all($resultPatients, MYSQLI_ASSOC);

// Prepare and execute the SQL query to retrieve the list of doctors
$queryDoctors = "SELECT * FROM doctors";
$resultDoctors = mysqli_query($conn, $queryDoctors);

// Fetch the list of doctors as an associative array
$doctors = mysqli_fetch_all($resultDoctors, MYSQLI_ASSOC);

// Prepare and execute the SQL query to retrieve the doctor's appointments with patient's name
$queryAppointment = "SELECT a.appointment_id, p.name AS pname, p.phone,d.name AS dname, a.appointment_date, a.appointment_time, a.status 
          FROM appointments a
          JOIN patients p ON a.patient_id = p.patient_id
          JOIN doctors d ON a.doctor_id = d.doctor_id";
$resultAppointment = mysqli_query($conn, $queryAppointment);

// Fetch the appointments as an associative array
$appointments = mysqli_fetch_all($resultAppointment, MYSQLI_ASSOC);

// Prepare and execute the SQL query to retrieve the list of admins
$queryAdmin = "SELECT * FROM admins";
$resultAdmin = mysqli_query($conn, $queryAdmin);

// Fetch the list of doctors as an associative array
$admins = mysqli_fetch_all($resultAdmin, MYSQLI_ASSOC);

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .editable {
            cursor: pointer;
        }

        .editable input[type="text"] {
            border: none;
            background-color: transparent;
            padding: 0;
            width: 100%;
            text-align: center;
        }
    </style>
</head>

<body class="bg-gray-100">
    <!-- Sidebar -->
    <aside class="bg-gray-800 text-white h-screen w-64 fixed">
        <div class="p-6">
            <a href="admin.php" class="text-2xl font-bold mb-4">Admin Panel</a>
            <ul class="space-y-2 mt-4 ">
                <li>
                    <a href="#dashboard" class="block py-2 px-4 rounded-md hover:bg-gray-700">Dashboard</a>
                </li>
                <li>
                    <a href="#doctors-list" class="block py-2 px-4 rounded-md hover:bg-gray-700">Doctors</a>
                </li>
                <li>
                    <a href="#patients-list" class="block py-2 px-4 rounded-md hover:bg-gray-700">Patients</a>
                </li>
                <li>
                    <a href="#appointments-list" class="block py-2 px-4 rounded-md hover:bg-gray-700">Appointments</a>
                </li>
                <li>
                    <?php if ($admin['role'] === 'superadmin') : ?>
                        <div class="rounded mt-4">
                            <a href="register_admin.php" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">Add Admin</a>
                        </div>
                    <?php endif; ?>
                </li>
                <li>
                    <a href="admin_logout.php" class="block py-2 px-4 rounded-md hover:bg-gray-700">Logout</a>
                </li>
            </ul>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="ml-64 p-6">
        <!-- <h1 class="text-2xl font-bold mb-4" id="dashboard">Dashboard</h1> -->

        <!-- Admin's Information -->
        <div class="bg-white shadow-md rounded p-4 mt-4" id="dashboard">
            <h2 class="text-2xl font-bold mb-4">Dashboard</h2>
            <p><strong>Name:</strong> <?php echo $admin['name']; ?></p>
            <p><strong>Email:</strong> <?php echo $admin['email']; ?></p>
            <p><strong>Role:</strong> <?php echo $admin['role']; ?></p>
            <p class="py-2 px-4 <?php echo ($admin['role'] === 'superadmin') ? 'bg-green-300' : 'bg-red-300'; ?> text-black text-center font-bold rounded">
                <?php echo ($admin['role'] === 'superadmin') ? 'You are eligible to add admin.' : 'You are not eligible to add admin.'; ?>
            </p>
        </div>

        <!-- admin's list -->
        <div class="bg-white shadow-md rounded p-4 mt-4" id="admins-list">
            <h2 class="text-xl font-bold mb-4">Admins List</h2>
            <table class="min-w-full bg-white border border-gray-300 text-center">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">Name</th>
                        <th class="py-2 px-4 border-b">Email</th>
                        <th class="py-2 px-4 border-b">Role</th>
                        <th class="py-2 px-4 border-b">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($admins as $admin) : ?>
                        <tr>
                            <td class="py-2 px-4 border-b"><?php echo $admin['name']; ?></td>
                            <td class="py-2 px-4 border-b"><?php echo $admin['email']; ?></td>
                            <td class="py-2 px-4 border-b"><?php echo $admin['role']; ?></td>
                            <td class="py-2 px-4 border-b">
                                <?php if ($admin['role'] === 'admin') : ?>
                                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this admin?');">
                                        <input type="hidden" name="delete_admin_id" value="<?php echo $admin['admin_id']; ?>">
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-4 rounded">Delete</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>


        <!-- Doctors Table -->
        <div class="bg-white shadow-md rounded p-4 mt-4" id="doctors-list">
            <h2 class="text-xl font-bold mb-4">Doctors List</h2>
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border-b">Name</th>
                        <th class="px-4 py-2 border-b">Specialization</th>
                        <th class="px-4 py-2 border-b">Email</th>
                        <th class="px-4 py-2 border-b">Contact</th>
                        <th class="px-4 py-2 border-b">Availability</th>
                        <th class="px-4 py-2 border-b">Working Hours</th>
                        <th class="px-4 py-2 border-b">Status<p class="text-sm">(pending/approved)</p>
                        </th>
                        <th class="px-4 py-2 border-b">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($doctors as $doctor) : ?>
                        <tr>
                            <td class="px-4 py-2 border-b text-center"><?php echo $doctor['name']; ?></td>
                            <td class="px-4 py-2 border-b text-center"><?php echo $doctor['specialization']; ?></td>
                            <td class="px-4 py-2 border-b text-center"><?php echo $doctor['email']; ?></td>
                            <td class="px-4 py-2 border-b text-center"><?php echo $doctor['phone']; ?></td>
                            <td class="px-4 py-2 border-b text-center"><?php echo $doctor['availability']; ?></td>
                            <td class="px-4 py-2 border-b text-center"><?php echo $doctor['start'] . '-' . $doctor['end']; ?></td>
                            <td class="px-4 py-2 border-b text-center status editable"><?php echo $doctor['status']; ?></td>
                            <td class="px-4 py-2 border-b text-center">
                                <button onclick="toggleStatusEdit(this)" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-1 px-4 rounded">
                                    Edit
                                </button>
                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this doctor?');">
                                    <input type="hidden" name="delete_doctor_id" value="<?php echo $doctor['doctor_id']; ?>">
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-4 mt-1 rounded">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Patients Table -->
        <div class="bg-white shadow-md rounded p-4 mt-4" id="patients-list">
            <h2 class="text-xl font-bold mb-4">Patients List</h2>
            <table class="min-w-full bg-white border border-gray-300 text-center">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">Name</th>
                        <th class="py-2 px-4 border-b">Email</th>
                        <th class="py-2 px-4 border-b">Contact</th>
                        <th class="py-2 px-4 border-b">Age</th>
                        <th class="py-2 px-4 border-b">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($patients as $patient) : ?>
                        <tr>
                            <td class="py-2 px-4 border-b"><?php echo $patient['name']; ?></td>
                            <td class="py-2 px-4 border-b"><?php echo $patient['email']; ?></td>
                            <td class="py-2 px-4 border-b"><?php echo $patient['phone']; ?></td>
                            <td class="py-2 px-4 border-b"><?php echo $patient['age']; ?></td>
                            <td class="py-2 px-4 border-b">
                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this patient?');">
                                    <input type="hidden" name="delete_patient_id" value="<?php echo $patient['patient_id']; ?>">
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-4 rounded">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- appointment's table -->
        <div class="bg-white shadow-md rounded p-4 mt-4" id="appointments-list">
            <h2 class="text-xl font-bold mb-4">Appointments List</h2>
            <table class="min-w-full bg-white border border-gray-300 text-center">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">Appointment ID</th>
                        <th class="py-2 px-4 border-b">Patient Name</th>
                        <th class="py-2 px-4 border-b">Contact</th>
                        <th class="py-2 px-4 border-b">Doctor Name</th>
                        <th class="py-2 px-4 border-b">Date</th>
                        <th class="py-2 px-4 border-b">Time</th>
                        <th class="py-2 px-4 border-b">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $appointment) : ?>
                        <tr>
                            <td class="py-2 px-4 border-b"><?php echo $appointment['appointment_id']; ?></td>
                            <td class="py-2 px-4 border-b"><?php echo $appointment['pname']; ?></td>
                            <td class="py-2 px-4 border-b"><?php echo $appointment['phone']; ?></td>
                            <td class="py-2 px-4 border-b"><?php echo $appointment['dname']; ?></td>
                            <td class="py-2 px-4 border-b"><?php echo $appointment['appointment_date']; ?></td>
                            <td class="py-2 px-4 border-b"><?php echo $appointment['appointment_time']; ?></td>
                            <td class="py-2 px-4 border-b"><?php echo $appointment['status']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </main>

    <script>
        function toggleStatusEdit(element) {
            const row = element.closest('tr');
            const statusElement = row.querySelector('.status');
            const currentStatus = statusElement.textContent.trim();
            const doctorId = row.querySelector('.doctor-id').textContent;

            if (statusElement.classList.contains('editable')) {
                const inputElement = document.createElement('input');
                inputElement.type = 'text';
                inputElement.value = currentStatus;
                inputElement.classList.add('text-center');
                inputElement.addEventListener('blur', () => {
                    const newStatus = inputElement.value;
                    updateStatus(doctorId, newStatus)
                        .then(() => {
                            statusElement.textContent = newStatus;
                            statusElement.classList.add('editable');
                            inputElement.parentNode.removeChild(inputElement);
                            element.textContent = 'Edit';
                        })
                        .catch(() => {
                            alert('Status Updating...');
                        });
                });

                statusElement.textContent = '';
                statusElement.classList.remove('editable');
                statusElement.appendChild(inputElement);
                inputElement.focus();
                element.textContent = 'Save';
            }
        }

        function updateStatus(doctorId, newStatus) {
            return new Promise((resolve, reject) => {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '<?php echo $_SERVER['PHP_SELF']; ?>', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            resolve();
                        } else {
                            reject();
                        }
                    }
                };

                const data = `doctor_id=${doctorId}&status=${newStatus}`;
                xhr.send(data);
                //redirect to admin.php
                window.location.href = "admin.php";
            });
        }
    </script>
</body>

</html>