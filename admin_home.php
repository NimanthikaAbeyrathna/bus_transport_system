<?php
include("config/db_connect.php");

// Get all locations
$location_list = [];
$result = mysqli_query($conn, "SELECT * FROM location");

while ($row = mysqli_fetch_assoc($result)) {
    $location_list[] = $row;
}

// search buses

$search_result = [];

if (isset($_GET['search'])) {

    $from = mysqli_real_escape_string($conn, $_GET['from']);
    $to = mysqli_real_escape_string($conn, $_GET['to']);
    $schedule = mysqli_real_escape_string($conn, $_GET['schedule']);

    if ($from != $to) {

        $sql = "SELECT b.*, 
                       l1.location_name AS from_name,
                       l2.location_name AS to_name,
                       r.route_id,
                       c.contact
                FROM bus b
                JOIN location l1 ON b.start_location_id = l1.location_id
                JOIN location l2 ON b.destination_location_id = l2.location_id
                JOIN route r ON b.route_id = r.route_id
                LEFT JOIN contact c ON b.id = c.bus_id
                WHERE b.start_location_id = '$from'
                AND b.destination_location_id = '$to'
                AND b.schedule_type = '$schedule'";

        $query = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_assoc($query)) {
            $search_result[] = $row;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="shortcut icon" href="images/favicon.png" type="image/png">
    <link rel="stylesheet" href="css/home.css">
</head>

<body>
    <?php
    require("includes/admin_home_header.php");
    ?>



    <!-- Hero Section -->
    <section class="hero">
        <div class="search-box">
            <h2>Find Your Bus</h2>

            <form method="GET">
                <div class="form-group">

                    <div>
                        <label>From:</label>
                        <select name="from" required>
                            <option value="">Select Location</option>
                            <?php
                            foreach ($location_list as $loc) {
                                echo "<option value='" . $loc['location_id'] . "'>" . $loc['location_name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div>
                        <label>To:</label>
                        <select name="to" required>
                            <option value="">Select Location</option>
                            <?php
                            foreach ($location_list as $loc) {
                                echo "<option value='" . $loc['location_id'] . "'>" . $loc['location_name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div>
                        <label>Schedule</label>
                        <select name="schedule" required>
                            <option value="week-day">week-day</option>
                            <option value="saturday">saturday</option>
                            <option value="sunday">sunday</option>
                        </select>
                    </div>

                </div>

                <button type="submit" name="search">Search Buses</button>
            </form>
        </div>
    </section>

    <!-- Bus Results -->
    <section class="results">
        <h2>Available Bus Services</h2>

        <?php if (isset($_GET['search'])): ?>

            <?php if ($from == $to): ?>
                <p>Please select different locations.</p>

            <?php elseif (count($search_result) > 0): ?>

                <?php foreach ($search_result as $bus): ?>

                    <div class="bus-card">
                        <div><strong>From:</strong> <?php echo $bus['from_name']; ?></div>
                        <div><strong>Departure:</strong> <?php echo $bus['departure_time']; ?></div>
                        <div><strong>To:</strong> <?php echo $bus['to_name']; ?></div>
                        <div><strong>Arrival:</strong> <?php echo $bus['arrival_time']; ?></div>
                        <div><strong>Schedule:</strong> <?php echo $bus['schedule_type']; ?></div>
                        <div><strong>Category:</strong> <?php echo $bus['category']; ?></div>
                        <div><strong>Vehicle No:</strong> <?php echo $bus['vehicle_no']; ?></div>
                        <div><strong>Route ID:</strong> <?php echo $bus['route_id']; ?></div>
                        <div><strong>Ownership:</strong> <?php echo $bus['ownership']; ?></div>
                        <div><strong>Contact:</strong> <?php echo $bus['contact']; ?></div>
                    </div>

                <?php endforeach; ?>

            <?php else: ?>
                <p>No buses found.</p>
            <?php endif; ?>

        <?php endif; ?>
    </section>

    <?php
    include("includes/footer.php");
    ?>

</body>

</html>