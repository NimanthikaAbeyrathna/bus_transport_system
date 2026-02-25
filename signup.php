<?php
include("config/db_connect.php");

if (isset($_POST["submit"])) {

    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit();
    }

    $check_email = "SELECT * FROM user WHERE email='$email'";
    $result = mysqli_query($conn, $check_email);

    if (mysqli_num_rows($result) > 0) {
        echo "Email already exists!";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO user(name,email,password,role)
            VALUES('$name','$email','$hashed_password','user')";

    if (mysqli_query($conn, $sql)) {
        // Log in new user immediately
        session_start();
        $_SESSION['user_id'] = mysqli_insert_id($conn);
        $_SESSION['user_name'] = $name;

        header("Location: index.php"); // Redirect to home
        exit();
    } else {
        echo "Query error: " . mysqli_error($conn);
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>signup</title>
    <link rel="shortcut icon" href="images/favicon.png" type="image/png">
    <link rel="stylesheet" href="css/signup.css">
</head>

<body>

    <div class="container">
        <form action="signup.php" method="POST" class="form-box">
            <h2>Create Account</h2>

            <div class="input-group">
                <label>Name</label>
                <input type="text" name="name" placeholder="Enter your name" required>
            </div>

            <div class="input-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Create password" required>
            </div>

            <div class="input-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" placeholder="Confirm password" required>
            </div>

            <button type="submit" name="submit" value="submit">Sign Up</button>

            <p class="bottom-text">
                Already have an account? <a href="login.php">Login</a>
            </p>
        </form>
    </div>

</body>

</html>