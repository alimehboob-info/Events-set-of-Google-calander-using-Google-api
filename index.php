<?php
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

session_start();
if (isset($_SESSION['admin_email'])) {
    header("Location: dashboard.php");
    exit();
}
// Check if the user is already logged in
if (isset($_POST['email']) && isset($_POST['password'])) {
    // Get the entered email and password from the login form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate email
    $email_query = "SELECT * FROM admin_login WHERE email = ?";
    $stmt_email = $conn->prepare($email_query);
    $stmt_email->bind_param("s", $email);
    $stmt_email->execute();
    $result_email = $stmt_email->get_result();

    // Validate password
    $password_query = "SELECT * FROM admin_login WHERE email = ? AND password = ?";
    $stmt_password = $conn->prepare($password_query);
    $stmt_password->bind_param("ss", $email, $password);
    $stmt_password->execute();
    $result_password = $stmt_password->get_result();

    // Check if the email exists
    if ($result_email->num_rows !== 1) {
        $error = "Invalid email";
        $_SESSION['email_error'] = true;
    }

    // Check if the password is incorrect
    elseif ($result_password->num_rows !== 1) {
        $error = "Invalid password";
        $_SESSION['password_error'] = true;
    } else {
        // Login successful
        $_SESSION['admin_email'] = $email;
        header("Location: dashboard.php");
        exit();
    }

    // Close the prepared statements
    $stmt_email->close();
    $stmt_password->close();
}

// Check for specific errors in session
if (isset($_SESSION['email_error']) && $_SESSION['email_error']) {
    $error = "Invalid email";
    $_SESSION['email_error'] = false;
}

if (isset($_SESSION['password_error']) && $_SESSION['password_error']) {
    $error = "Invalid password";
    $_SESSION['password_error'] = false;
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Login Form Using Bootstrap 5</title>
    <!-- Bootstrap 5 CDN Link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS Link -->
    <link rel="stylesheet" href="css/adminloginform.css">
    <link rel="icon" href="images/favicon.png" type="image/png" sizes="48x48">
</head>

<body>
    <section class="wrapper">
        <div id="alertMessage" class="alert alert-danger" style="display: none;">
            Wrong email or password.
        </div>

        <div class="container_fluid setColor">
            <div class="col-sm-8 offset-sm-2 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4 text-center">

                <form action="index.php" method="post" class="rounded shadow glassmorphism py-5 px-4">
                    <div class="logo">
                        <img decoding="async" src="images/logo.png" class="img-fluid" alt="Logo">
                    </div>
                    <h3 class=" fw-bolder fs-4 mb-2" style="color: #81B921;">Admin Login </h3>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control <?php if (isset($error))
                            echo 'is-invalid'; ?>" name="email" id="email" placeholder="name@example.com" required>
                        <label for="email">Email address</label>
                        <?php if (isset($error)) { ?>
                            <div class="invalid-feedback">
                                Invalid email or password.
                            </div>
                        <?php } ?>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control <?php if (isset($error))
                            echo 'is-invalid'; ?>" name="password" id="password" placeholder="Password" required>
                        <label for="Password">Password</label>
                        <?php if (isset($error)) { ?>
                            <div class="invalid-feedback">
                                Invalid email or password.
                            </div>
                        <?php } ?>
                    </div>
                    <div class="mt-2 text-end">
                        <a href="#" class=" fw-bold text-decoration-none" style="color: #81B921;">Forget Password?</a>
                    </div>
                    <button type="submit" class="btn text-light submit_btn w-100 my-4"
                        style="background-color: #81B921;">Login</button>

                    <a href="https://bitsclan.com/" class="btn btn-light login_with w-100 mb-3 shadow"
                        style="color: #81B921;">
                        <img decoding="async" alt="Logo" src="images/logo.png" class="img-fluid me-3">Bitsclan
                        Official Site
                    </a>
                </form>
            </div>
        </div>
    </section>

</body>

</html>