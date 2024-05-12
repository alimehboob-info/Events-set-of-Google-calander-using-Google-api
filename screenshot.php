<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    // Redirect to the login page
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/dashboard.css">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <title>Screenshot</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">

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
                <span class="tooltip">Messages</span>
            </li>


            <li>
                <a href="admin_logout.php">
                    <i class='bx bx-lock'></i>
                    <span class="links_name">Logout</span>
                </a>
                <span class="tooltip">Logout</span>
            </li>
        </ul>
    </div>
    <div class="container mt-md-5 ">
        <div class="row justify-content-end ">
            <div class="col-md-10">
                <div class="card shadow-lg ">
                    <div class="card-header bg-success text-light">
                        working Details
                    </div>
                    <div class="card-body">
                        <img src="images/login.png" class="img-thumbnail rounded img-fluid" alt="...">
                        <img src="images/1.png" class="img-thumbnail rounded img-fluid" alt="...">

                        <img src="images/2.png" class="img-thumbnail rounded img-fluid" alt="...">

                    </div>


                    <img src="images/3.png" class="img-thumbnail rounded img-fluid" alt="...">
                    <img src="images/4.png" class="img-thumbnail rounded img-fluid" alt="...">

                </div>
            </div>

        </div>


    </div>

    </div>
    <script src="script/dashboard.js"></script>
</body>

</html>