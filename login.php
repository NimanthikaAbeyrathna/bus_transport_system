<?php
session_start();
include("config/db_connect.php");

if(isset($_POST['login'])){

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM user WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) == 1){
        $row = mysqli_fetch_assoc($result);

        // Verify password
        if(password_verify($password, $row['password'])){
            // Set session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];

            // Redirect to home page
            header("Location: home.php");
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "Email not registered!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="shortcut icon" href="images/favicon.png" type="image/png">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/login.css">
</head>
<body>
    <div class="login-container">
        <form action="login.php" method="POST" class="login-form">
            <h2>Login</h2>

            <?php if(isset($error)) { ?>
            <p style="color:red; text-align:center;"><?php echo $error; ?></p>
        <?php } ?>

            <div class="input-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>

            <button type="submit" name="login" value="submit">Login</button>

            <p class="register-link">
                Don't have an account? <a href="signup.php">Register</a>
            </p>
        </form>
    </div>
</body>
</html>