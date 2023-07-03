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
            <h1 class="text-2xl font-bold mb-4">Admin Panel</h1>
            <ul class="space-y-2">
                <li>
                    <a href="#" class="block py-2 px-4 rounded-md hover:bg-gray-700">Dashboard</a>
                </li>
                <li>
                    <a href="#" class="block py-2 px-4 rounded-md hover:bg-gray-700">Settings</a>
                </li>
                <li>
                    <a href="#" class="block py-2 px-4 rounded-md hover:bg-gray-700">Logout</a>
                </li>
            </ul>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="ml-64 p-6">
        <h1 class="text-2xl font-bold mb-4">Dashboard</h1>

        <!-- Admin's Information -->
        <div class="bg-white shadow-md rounded p-4 flex flex-col items-center justify-center">
            <h2 class="text-2xl font-bold mb-2"><?php echo $admin['name']; ?></h2>
            <p><strong>Email:</strong> <?php echo $admin['email']; ?></p>
        </div>

        <!-- Patients Table -->
        <div class="bg-white shadow-md rounded p-4 mt-4">
            <h2 class="text-xl font-bold mb-4">Patients List</h2>
            <table class="min-w-full bg-white border border-gray-300 text-center">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">ID</th>
                        <th class="py-2 px-4 border-b">Name</th>
                        <th class="py-2 px-4 border-b">Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($patients as $patient) : ?>
                        <tr>
                            <td class="py-2 px-4 border-b"><?php echo $patient['patient_id']; ?></td>
                            <td class="py-2 px-4 border-b"><?php echo $patient['name']; ?></td>
                            <td class="py-2 px-4 border-b"><?php echo $patient['email']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Doctors Table -->
        <div class="bg-white shadow-md rounded p-4 mt-4">
            <h2 class="text-xl font-bold mb-4">Doctors List</h2>
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border-b">Doctor ID</th>
                        <th class="px-4 py-2 border-b">Name</th>
                        <th class="px-4 py-2 border-b">Specialization</th>
                        <th class="px-4 py-2 border-b">Email</th>
                        <th class="px-4 py-2 border-b">Availability</th>
                        <th class="px-4 py-2 border-b">Status<p class="text-sm">(pending/approved)</p></th>
                        <th class="px-4 py-2 border-b">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($doctors as $doctor) : ?>
                        <tr>
                            <td class="px-4 py-2 border-b text-center doctor-id"><?php echo $doctor['doctor_id']; ?></td>
                            <td class="px-4 py-2 border-b text-center"><?php echo $doctor['name']; ?></td>
                            <td class="px-4 py-2 border-b text-center"><?php echo $doctor['specialization']; ?></td>
                            <td class="px-4 py-2 border-b text-center"><?php echo $doctor['email']; ?></td>
                            <td class="px-4 py-2 border-b text-center"><?php echo $doctor['availability']; ?></td>
                            <td class="px-4 py-2 border-b text-center status editable"><?php echo $doctor['status']; ?></td>
                            <td class="px-4 py-2 border-b text-center">
                                <button onclick="toggleStatusEdit(this)" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-1 px-4 rounded">
                                    Edit
                                </button>
                            </td>
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
                            // Redirect to admin.php after status update
                            window.location.href = 'admin.php';
                        })
                        .catch((error) => {
                            console.error(error);
                        });
                });

                statusElement.textContent = '';
                statusElement.appendChild(inputElement);
                statusElement.classList.remove('editable');
                element.textContent = 'Save';
                inputElement.focus();
            } else {
                element.textContent = 'Edit';
                statusElement.classList.add('editable');
                statusElement.textContent = currentStatus;
            }
        }

        function updateStatus(doctorId, newStatus) {
            return new Promise((resolve, reject) => {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'update_status.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        resolve();
                    } else {
                        reject(new Error('Failed to update status'));
                    }
                };
                xhr.onerror = function() {
                    reject(new Error('Failed to make the request'));
                };
                xhr.send('doctorId=' + doctorId + '&newStatus=' + newStatus);
                window.location.href = 'admin.php';
            });
        }
    </script>

</body>

</html>