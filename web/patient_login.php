<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login as Patient</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {            
            /* Adjust this value to match the height of your navbar */
            background-image: url('../img/cover.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            height: 100vh;
            display: flex;
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

    <div class="container mx-auto py-8 mb-8 mt-20">
        <div class="mx-auto bg-white bg-opacity-90 p-4 border border-gray-300 rounded shadow-md" style="width: 30%;">
            <h2 class="text-2xl font-bold mb-4">Login as Patient</h2>
            <form action="process.php" method="POST">
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email</label>
                    <input type="email" name="email" id="email" class="w-full py-1 pl-2 border-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700">Password</label>
                    <input type="password" name="password" id="password" class="w-full py-1 pl-2 border-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex justify-center">
                    <button type="submit" name="patient_login" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Login</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
