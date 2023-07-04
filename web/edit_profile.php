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
// if ($doctor['status'] == 'pending') {
//     $message = "Your account is pending approval.";
// }


// Close the database connection
mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            /* Adjust this value to match the height of your navbar */
            background-image: url('../img/cover.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            height: 100vh;
            display: block;
            flex-direction: column;
        }
    </style>
</head>

<body>
    <!-- header start -->
    <header class="bg-gray-200 bg-opacity-75 text-white py-2 fixed w-full top-0 ">
        <?php include '../includes/navbar.php'; ?>
    </header>
    <!-- header end -->

    <div class="container mx-auto py-8 mt-8">
        <div class="mx-auto bg-white bg-opacity-90 p-4 border border-gray-300 rounded shadow-md" style="width: 70%;">
            <h2 class="text-2xl font-bold mb-4">Edit Your Profile</h2>
            <form action="process.php" method="POST" name="doctor_update">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700">Name</label>
                    <input type="text" name="name" id="name" value="<?php echo $doctor['name']; ?>" class="w-full py-1 pl-2 border-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label for="profile_picture" class="block text-gray-700">Profile Picture</label>
                    <input type="file" name="profile_picture" id="profile_picture" accept="image/*" class="py-1">
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email</label>
                    <input type="email" name="email" id="email" value="<?php echo $doctor['email']; ?>" readonly class="w-full py-1 pl-2 border-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-100">
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700"> New Password</label>
                    <input type="password" name="password" id="password" value="<?php echo $doctor['password']; ?>" class="w-full py-1 pl-2 border-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label for="specialization" class="block text-gray-700">Specialization</label>
                    <input type="text" name="specialization" id="specialization" value="<?php echo $doctor['specialization']; ?>" class="w-full py-1 pl-2 border-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Availability Days</label>
                    <div class="flex flex-wrap">
                        <?php
                        $availableDays = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
                        $currentAvailability = explode(", ", $doctor['availability']); // Replace $availabilityFromDatabase with the actual variable/column from your database

                        foreach ($availableDays as $day) {
                            $isChecked = in_array($day, $currentAvailability) ? 'checked' : '';
                        ?>
                            <div class="mr-4">
                                <input type="checkbox" name="availability[]" value="<?php echo $day; ?>" id="<?php echo strtolower($day); ?>" <?php echo $isChecked; ?>>
                                <label for="<?php echo strtolower($day); ?>" class="ml-2"><?php echo $day; ?></label>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="start" class="block text-gray-700">Start</label>
                    <input type="time" name="start" id="start" value="<?php echo $doctor['start']; ?>" class="w-full py-1 pl-2 border-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label for="end" class="block text-gray-700">End</label>
                    <input type="time" name="end" id="end" value="<?php echo $doctor['end']; ?>" class="w-full py-1 pl-2 border-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex justify-center">
                    <button type="submit" name="doctor_update" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700">Update</button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>