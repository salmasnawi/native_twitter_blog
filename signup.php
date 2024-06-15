<?php
require_once('header.php');
session_start();
include_once 'db_connect.php';

$error_msg = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate form data
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_msg[] = "Error: All fields are required.";
    } else {
        // Check if password and confirm password match
        if ($password != $confirm_password) {
            $error_msg[] = "Error: Password and confirm password do not match.";
        } else {
            // Filter user input
            $username = htmlspecialchars(trim($username));
            $email = trim($email);

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error_msg[] = "Error: Invalid email format.";
            } else {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Check if username or email already exist in the database
                $sql = "SELECT * FROM users WHERE username=? OR email=?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "ss", $username, $email);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $count = mysqli_num_rows($result);

                if ($count > 0) {
                    $error_msg[] = "Error: Username or email already in use.";
                } else {
                    // Insert user into database
                    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);
                    if (mysqli_stmt_execute($stmt)) {
                        require_once('classes.php');
                        $result = Subscriber::register($username, $email, $hashed_password);
                        header("Location: login.php?registered=true");
                        exit;
                    } else {
                        $error_msg[] = "Error: " . mysqli_error($conn);
                    }
                }
            }
        }
    }
}

mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="styles/signup.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
    background: url('images/pink.jpg') no-repeat center center fixed;
    background-size: cover;
    font-family: Arial, sans-serif;
}
</style>
</head>
<body>

<div class="container" style="      margin-top: 50px;
">
    <div class="box form-box">
        <form action="signup.php" method="post">
            <?php if (!empty($error_msg)) { ?>
                <p><?php foreach ($error_msg as $msg) { echo $msg . "<br>"; } ?></p>
            <?php } ?>
            <div class="field input">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" autocomplete="off" required>
            </div>

            <div class="field input">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" autocomplete="off" >
            </div>

            <div class="field input">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" autocomplete="off" required>
            </div>
            <div class="field input">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" required>
                <br>
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
      line-height: 26px;" value="Register" required>
            </div>
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</div>

</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</html>

<?php
$_SESSION["error_msg"] = null;
?>