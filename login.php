<?php
require_once('header.php');
session_start();
include_once 'db_connect.php';


// Check for error message in URL query string
if (isset($_GET['error_msg'])) {
    $display_error = htmlspecialchars($_GET['error_msg']);
    unset($_GET['error_msg']); // Clear error message from URL query string
} elseif (isset($_SESSION['error_msg'])) { // If not found in URL, check session
    $display_error = $_SESSION['error_msg'];
    unset($_SESSION['error_msg']); // Clear error message from session
} else {
    $display_error = ""; // If no error message, set it to an empty string
}

// Process login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Sanitize email address
    $email = mysqli_real_escape_string($conn, $email);

    // Query to retrieve user from database
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        $error_msg = "Error: ". mysqli_error($conn);
        $_SESSION['error_msg'] = $error_msg; // Store error message in session
        header("Location: login.php?error_msg=". urlencode($error_msg)); // Add error message to URL
        exit;
    }

    echo "Query executed successfully!<br>";

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        echo "User found!<br>";

        // Verify password
      // Verify password
if (password_verify($password, $row['password'])) {
    echo "Password verified!<br>";

    require_once('classes.php');
    $user = User::login($email, $row['password']); // Pass the hashed password from the database
    if (!empty($user)){
        echo "User object created successfully!<br>";
        $_SESSION['user'] = serialize($user);

        if($user->role == "admin"){
            header("Location: home.php");
            exit;
        } else if($user->role == "subscriber"){
            header("Location: home.php"); // Redirect to homepage.php for users
            exit;
        }
    } else {
        $error_msg = "Invalid user role or other login issue";
        $_SESSION['error_msg'] = $error_msg; // Store error message in session
        header("Location: login.php");
        exit;
    }
} else {
    $error_msg = "Invalid password";
    $_SESSION['error_msg'] = $error_msg; 
    // Store error message in session
    header("Location: login.php");
    exit;
}
    } else {
        $error_msg = "User not found";
        $_SESSION['error_msg'] = $error_msg; // Store error message in session
        header("Location: login.php");
        exit;
    }

    // Close connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles/login.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
    background: url('styles/pink.jpg') no-repeat center center fixed;
    background-size: cover;
    font-family: Arial, sans-serif;
}
</style>

</head>
<body>


<div class="container">
    <div class="box form-box">
    <?php
        if (!empty($display_error)) { // Check if there's an error message to display
            echo "<p style='color: red;'>{$display_error}</p>";
        }
        ?>
        <form action="login.php" method="post">
            <div class="field input">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" autocomplete="off" >
            </div>

            <div class="field input">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" autocomplete="off">
            </div>

            <div class="field">
                <input type="submit" class="btn" name="submit"style="    width: 100%;
      padding: 12px;
      margin-bottom: 20px;
      border: 0;
      border-radius: 20px;
      background-color: rgb(250, 124, 145);
      color: black;
      font-family: Rubik;
      transition-property: all;
      transition-duration: 0.5s;
      font-size: 19px;
      font-style: normal;
      font-weight: 400;
      line-height: 26px;" value="Login">
            </div>
            <div class="links">
                Don't have an account? <a href="signup.php">Sign Up Now</a>
            </div>
        </form>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>