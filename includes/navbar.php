<header class="bg-gray-200 bg-opacity-75 text-white py-2 fixed w-full top-0">
    <div class="container mx-auto px-4">
        <?php
        // Define the $isLoggedOut variable based on the user's login status
        $isLoggedOut = true; // Set to true if the user is logged out, false if logged in
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
                <li class="mr-4">
                    <a href="make_appointment.php" class="font-medium text-gray-800 hover:text-red-500">Make Appointment</a>
                </li>
                <li class="mr-4">
                    <a href="view_appointments.php" class="font-medium text-gray-800 hover:text-red-500">View Appointments</a>
                </li>
                <li class="mr-4">
                    <a href="contact.php" class="font-medium text-gray-800 hover:text-red-500">Contact</a>
                </li>
                <li class="mr-4">
                    <a href="about.php" class="font-medium text-gray-800 hover:text-red-500">About</a>
                </li>
                <?php if ($isLoggedOut) { ?>
                    <li>
                        <a href="login.php" class="font-medium bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-500">Login</a>
                    </li>
                <?php } else { ?>
                    <li>
                        <a href="logout.php" class="font-medium bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-500">Logout</a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
    </div>
</header>
