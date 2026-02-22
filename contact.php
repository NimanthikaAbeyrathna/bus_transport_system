<?php
session_start();
include("config/db_connect.php");


if(isset($_POST['submit'])){  // check if form submitted
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $sql = "INSERT INTO contact_message (name, email, message) 
            VALUES ('$name', '$email', '$message')";

    if(mysqli_query($conn, $sql)){
        
        header("Location: contact.php?success=1");
        exit();
    } else {
        $error = "Error sending message: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link rel="shortcut icon" href="images/favicon.png" type="image/png">
    <link rel="stylesheet" href="css/contact.css">
</head>
<body>
<?php include("includes/header.php"); ?>

<div class="contact-container">
    <h1>Contact BusLynk</h1>
    <p>If you have any questions, suggestions, or feedback, feel free to contact us.</p>

    <form action="" method="POST" class="contact-form">
        <input type="text" name="name" placeholder="Your Name" required>
        <input type="email" name="email" placeholder="Your Email" required>
        <textarea name="message" placeholder="Your Message" rows="5" required></textarea>
        <button type="submit" name="submit">Send Message</button>
    </form>

    <div class="contact-info">
        <p><strong>Email:</strong> support@buslynk.com</p>
        <p><strong>Phone:</strong> +94 71 123 4577</p>
        <p><strong>Location:</strong> Rathnapura, Sri Lanka</p>
    </div>
</div>

<?php include("includes/footer.php"); ?>
</body>
</html>