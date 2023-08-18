<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register as Doctor</title>
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

    <div class="container mx-auto py-8 mb-8  mt-20">
        <div class="mx-auto bg-white bg-opacity-90 p-4  border border-gray-300 rounded shadow-md" style="width: 70%;">
            <h2 class="text-2xl font-bold mb-4">Register as Doctor</h2>
            <form action="process.php" method="POST" name="doctor_register">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700">Name</label>
                    <input type="text" name="name" id="name" class="w-full py-1 pl-2 border-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email</label>
                    <input type="email" name="email" id="email" class="w-full py-1 pl-2 border-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700">Password</label>
                    <input type="password" name="password" id="password" class="w-full py-1 pl-2 border-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <!-- confirm password -->
                <div class="mb-4">
                    <label for="confirm_password" class="block text-gray-700">Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="w-full py-1 pl-2 border-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <!-- contact number -->
                <div class="mb-4">
                    <label for="phone" class="block text-gray-700">Contact Number</label>
                    <input type="text" name="phone" id="phone" class="w-full py-1 pl-2 border-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="+880" required>
                </div>
                <div class="flex flex-col md:flex-row md:space-x-4 mb-4">
                    <div class="mb-4 md:w-1/2">
                        <label for="specialization" class="block text-gray-700">Specialization</label>
                        <input type="text" name="specialization" id="specialization" class="w-full py-1 pl-2 border-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    <!-- fee -->
                    <div class="mb-4 md:w-1/2">
                        <label for="fee" class="block text-gray-700">Consultant Fee</label>
                        <input type="text" name="fee" id="fee" class="w-full py-1 pl-2 border-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="1000 BDT" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Availability Days</label>
                    <div class="flex flex-wrap">
                        <div class="mr-4">
                            <input type="checkbox" name="availability[]" value="Monday" id="monday">
                            <label for="monday" class="ml-2">Monday</label>
                        </div>
                        <div class="mr-4">
                            <input type="checkbox" name="availability[]" value="Tuesday" id="tuesday">
                            <label for="tuesday" class="ml-2">Tuesday</label>
                        </div>
                        <div class="mr-4">
                            <input type="checkbox" name="availability[]" value="Wednesday" id="wednesday">
                            <label for="wednesday" class="ml-2">Wednesday</label>
                        </div>
                        <div class="mr-4">
                            <input type="checkbox" name="availability[]" value="Thursday" id="thursday">
                            <label for="thursday" class="ml-2">Thursday</label>
                        </div>
                        <div class="mr-4">
                            <input type="checkbox" name="availability[]" value="Friday" id="friday">
                            <label for="friday" class="ml-2">Friday</label>
                        </div>
                        <div class="mr-4">
                            <input type="checkbox" name="availability[]" value="Saturday" id="saturday">
                            <label for="saturday" class="ml-2">Saturday</label>
                        </div>
                        <div class="mr-4">
                            <input type="checkbox" name="availability[]" value="Sunday" id="sunday">
                            <label for="sunday" class="ml-2">Sunday</label>
                        </div>
                    </div>
                </div>
                <!-- doctor registration number -->
                <div class="mb-4">
                    <label for="reg_num" class="block text-gray-700">Registration Number</label>
                    <input type="text" name="reg_num" id="reg_num" class="w-full py-1 pl-2 border-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="0123456789.." required>
                </div>
                <div class="flex flex-col md:flex-row md:space-x-4 mb-4">
                    <div class="mb-4 md:w-1/2">
                        <label for="start" class="block text-gray-700">Start Checkup Time</label>
                        <input type="time" name="start" id="start" class="w-full py-1 pl-2 border-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    <div class="mb-4 md:w-1/2">
                        <label for="end" class="block text-gray-700">End Checkup Time</label>
                        <input type="time" name="end" id="end" class="w-full py-1 pl-2 border-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                </div>

                <div class="flex justify-center">
                    <button type="submit" name="doctor_register" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Register</button>
                </div>
            </form>
        </div>
    </div>
</body>



</html>