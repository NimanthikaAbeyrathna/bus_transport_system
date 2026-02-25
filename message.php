<?php
include("config/db_connect.php");


// Fetch messages
$result = $conn->query("SELECT * FROM contact_message ORDER BY created_at ASC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Messages</title>
    <link rel="shortcut icon" href="images/favicon.png" type="image/png">
    <link rel="stylesheet" href="css/message.css">
</head>

<body>
    <?php
        require("includes/admin_home_header.php");
    ?>
    <div class="container">
        <h2>Messages</h2>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Date</th>

                </tr>

                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= htmlspecialchars($row['name']); ?></td>
                        <td><?= htmlspecialchars($row['email']); ?></td>
                        <td class="message-box"><?= htmlspecialchars($row['message']); ?></td>
                        <td><?= $row['created_at']; ?></td>

                    </tr>
                <?php endwhile; ?>

            </table>
        <?php else: ?>
            <p>No messages found.</p>
        <?php endif; ?>

    </div>


    <?php
    include("includes/footer.php");
    ?>

</body>

</html>