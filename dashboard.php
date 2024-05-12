<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    // Redirect to the login page
    header("Location: index.php");
    exit();
}
// Establish a database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bitsclan";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>

<!-- ...HTML content... -->
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="icon" href="images/favicon.png" type="image/png" sizes="48x48">
    <link rel="stylesheet" href="css/d_style.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js">
    </script>
    <style>

    </style>
</head>

<body>
    <div class="sidebar">
        <div class="logo_content">
            <div class="logo">
                <div class="logo_name">
                    BitsClan
                </div>
            </div>
            <i class='bx bx-menu' id="btn"></i>
        </div>
        <ul class="nav_list">
            <li>
                <i class='bx bx-search'></i>
                <input type="text" placeholder="Search...">
                <span class="tooltip">Search</span>
            </li>
            <li>
                <a href="dashboard.php">
                    <i class='bx bx-grid-alt'></i>
                    <span class="links_name">Dashboard</span>
                </a>
                <span class="tooltip">Dashboard</span>
            </li>
            <li>
                <a href="#" onclick="toggleCalendar()">
                    <i class='bx bx-user'></i>
                    <span class="links_name">Calander</span>
                </a>

            </li>
            <li>
                <a href="screenshot.php">
                    <i class='bx bx-chat'></i>
                    <span class="links_name">ScreenShot</span>
                </a>

            </li>

            <li>
                <a href="admin_logout.php">
                    <i class='bx bx-lock'></i>
                    <span class="links_name">Logout</span>
                </a>
                <span class="tooltip">Logout</span>
            </li>
        </ul>
        <div class="content">
            <div class="user">
                <div class="user_details">
                    <div class="name_job">
                        <?php
                        // Check if the admin email session is set
                        if (isset($_SESSION['admin_email'])) {
                            echo $_SESSION['admin_email']; // Display the email address
                        }
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="home_content">
        <div class="images">
            <img src="images/logo.png" alt="logo">
        </div>
        <div class="container mt-4">
            <div class="row justify-content-center ">
                <div class="col-md-8">
                    <div class="card shadow-lg">
                        <div class="card-header bg-success text-light ">
                            Fetch Events Through Google Calander Api

                        </div>
                        <div class="card-body">
                            <button id="authorize_button" onclick="handleAuthClick()"
                                class="btn btn-outline-success">Authorize</button>
                            <button id="signout_button" onclick="handleSignoutClick()"
                                class="btn btn-outline-warning ">Sign Out</button>
                            <div class="mt-3">

                                <div class="card" style="width: 100%;">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <pre id="content" style="white-space: pre-wrap;"></pre>

                                        </li>
                                    </ul>
                                </div>

                                <!-- form -->
                                <div id="editForm" style="display: none;">
                                    <form onsubmit="submitEventEdit(event)">
                                        <input type="text" id="editEventName" placeholder="Event Name" required>
                                        <input type="text" id="editEventDescription" placeholder="Event Description">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </form>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mt-4">
            <div class="row justify-content-center ">
                <div class="col-md-10">
                    <div class="card shadow-lg ">
                        <div class="card-header bg-primary text-light">
                            <h5 class="card-title">Google Calendar</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">Click the button below to display the calendar</p>
                            <button onclick="toggleCalendar()" class="btn btn-primary btn-sm">Show/Hide
                                Calendar</button>
                            <div id="calendarContainer" style="display: none;">
                                <iframe src="https://calendar.google.com/calendar/embed?src=YOUR_CALENDAR_ID"
                                    style="border: 0" width="100%" height="600" frameborder="0" scrolling="no"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <script src="script/api.js"></script>
        <script async defer src="https://accounts.google.com/gsi/client" onload="gisLoaded()"></script>
        <script async defer src="https://apis.google.com/js/api.js" onload="gapiLoaded()"></script>
        <script src="script/dashboard.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        <script>
            function toggleCalendar() {
                const calendarContainer = document.getElementById('calendarContainer');
                if (calendarContainer.style.display === 'none') {
                    calendarContainer.style.display = 'block';
                } else {
                    calendarContainer.style.display = 'none';
                }
            }
        </script>
</body>

</html>