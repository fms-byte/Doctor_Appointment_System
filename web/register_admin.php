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
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Perform the registration (replace with your registration logic)
    // Replace DB_HOST, DB_USER, DB_PASSWORD, and DB_NAME with your actual database credentials
    $conn = mysqli_connect("localhost", "root", "", "doctor_appointment_system");
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit;
    }

    // Check if the admin already exists
    $query = "SELECT * FROM admins WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    $existingAdmin = mysqli_fetch_assoc($result);

    if ($existingAdmin) {
        // Admin already exists
        $error = "Admin with this email already exists";
    } else {
        // Insert the admin into the database
        $insertQuery = "INSERT INTO admins (name, email, password) VALUES ('$name', '$email', '$password')";
        $insertResult = mysqli_query($conn, $insertQuery);

        if ($insertResult) {
            // Admin registration successful
            $_SESSION["admin_id"] = mysqli_insert_id($conn);
            header("Location: admin.php");
            exit();
        } else {
            // Failed to register admin
            $error = "Failed to register admin";
        }
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-gray-800 text-white p-4">
        <h1 class="text-2xl font-bold">Admin Panel</h1>
    </header>

    <!-- Content -->
    <main class="w-1/2 container mx-auto">
        <h2 class="text-2xl font-bold mb-4">Register Admin</h2>

        <?php if (isset($error)) : ?>
            <div class="bg-red-200 text-red-800 p-4 mb-4">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" class="max-w-md bg-white p-6 rounded shadow-md">
            <div class="mb-4">
                <label for="name" class="block font-bold mb-2">Name</label>
                <input type="text" id="name" name="name" class="w-full px-3 py-2 border border-gray-300 rounded">
            </div>

            <div class="mb-4">
                <label for="email" class="block font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded">
            </div>

            <div class="mb-4">
                <label for="password" class="block font-bold mb-2">Password</label>
                <input type="password" id="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded">
            </div>

            <button type="submit" class="bg-gray-800 hover:bg-gray-700 text-white py-2 px-4 rounded">Register</button>
        </form>
    </main>
</body>

</html>
