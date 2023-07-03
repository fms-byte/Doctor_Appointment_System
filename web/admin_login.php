<?php
session_start();

// Check if the admin is already logged in
if (isset($_SESSION["admin_id"])) {
    // Redirect to the admin panel
    header("Location: admin.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the form inputs
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Authenticate the admin (replace with your authentication logic)
    // Replace DB_HOST, DB_USER, DB_PASSWORD, and DB_NAME with your actual database credentials
    $conn = mysqli_connect("localhost", "root", "", "doctor_appointment_system");
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit;
    }

    $query = "SELECT * FROM admins WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $query);
    $admin = mysqli_fetch_assoc($result);

    if ($admin) {
        // Admin is authenticated
        $_SESSION["admin_id"] = $admin["admin_id"];
        header("Location: admin.php");
        exit();
    } else {
        // Invalid credentials
        $error = "Invalid email or password";
    }

    mysqli_close($conn);
}
?>

<!-- Your HTML code here -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <header class="bg-gray-800 text-white py-4">
        <div class="container mx-auto flex items-center justify-between px-4">
            <h1 class="text-2xl font-bold">Admin Panel</h1>
            <a href="register_admin.php" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Register</a>
        </div>
    </header>

    <div class="flex items-center justify-center h-screen">
        <div class="bg-white p-8 rounded shadow-md">
            <h2 class="text-2xl font-bold mb-4">Admin Login</h2>
            <form action="admin.php" method="POST">
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-bold mb-2">Email:</label>
                    <input type="email" name="email" id="email" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-bold mb-2">Password:</label>
                    <input type="password" name="password" id="password" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500" required>
                </div>
                <div class="flex justify-center">
                    <button type="submit" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Log In
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
