<?php
include("config/db_connect.php");

// Fetch Locations for the dropdowns
$locations = $conn->query("SELECT * FROM location");

// Fetch Routes for the dropdown
$routes = $conn->query("SELECT r.route_id, l1.location_name as start, l2.location_name as end 
                        FROM route r 
                        JOIN location l1 ON r.start_location = l1.location_id 
                        JOIN location l2 ON r.end_location = l2.location_id");

// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $v_no = $_POST['vehicle_no'];
    $cat = $_POST['category'];
    $route = $_POST['route_id'];
    $owner = $_POST['ownership'];
    $sched = $_POST['schedule_type'];
    $start_id = $_POST['start_location_id'];
    $dep_time = $_POST['departure_time'];
    $dest_id = $_POST['destination_location_id'];
    $arr_time = $_POST['arrival_time'];

    $sql = "INSERT INTO bus 
            (vehicle_no, category, route_id, ownership, schedule_type, start_location_id, departure_time, destination_location_id, arrival_time) 
            VALUES 
            ('$v_no', '$cat', '$route', '$owner', '$sched', '$start_id', '$dep_time', '$dest_id', '$arr_time')";

    if ($conn->query($sql) === TRUE) {

        $bus_id = $conn->insert_id;

        if (!empty($_POST['contact_number'])) {

            foreach ($_POST['contact_number'] as $contact) {

                if (!empty($contact)) {

                    $contact = mysqli_real_escape_string($conn, $contact);

                    $contact_sql = "INSERT INTO contact 
                                    (bus_id, contact)
                                    VALUES 
                                    ('$bus_id', '$contact')";

                    $conn->query($contact_sql);
                }
            }
        }
        echo "Save successfully!";
        header("Location: edit.php?success=1");
        exit();

    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<?php

$sql = "SELECT 
            b.id,
            b.vehicle_no,
            b.category,
            b.ownership,
            b.schedule_type,
            b.route_id,
            b.departure_time,
            b.arrival_time,
            l1.location_name AS start_name,
            l2.location_name AS end_name,
            GROUP_CONCAT(c.contact SEPARATOR ', ') AS contacts,
            r.start_location AS route_start_id,
            r.end_location AS route_end_id
        FROM bus b
        JOIN location l1 ON b.start_location_id = l1.location_id
        JOIN location l2 ON b.destination_location_id = l2.location_id
        LEFT JOIN contact c ON b.id = c.bus_id
        LEFT JOIN route r ON b.route_id = r.route_id
        GROUP BY b.id
        ORDER BY b.id ASC";

$result = $conn->query($sql);
?>

<?php
// DELETE BUS
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']); // sanitize input

    // First delete related contacts
    $conn->query("DELETE FROM contact WHERE bus_id = $delete_id");

    // Then delete the bus
    if ($conn->query("DELETE FROM bus WHERE id = $delete_id")) {
        header("Location: edit.php?deleted=1"); // redirect to avoid resubmission
        exit();
    } else {
        echo "Error deleting bus: " . $conn->error;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Database</title>
    <link rel="shortcut icon" href="images/favicon.png" type="image/png">
    <link rel="stylesheet" href="css/edit.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">


</head>

<body>
    <?php
    require("includes/header.php");
    ?>
    <div class="container">
        <h1>Update Bus Records</h1>
        <div class="form-container">

            <h2>Add a New Bus</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Vehicle Number</label>
                    <input type="text" name="vehicle_no" placeholder="e.g. NB-5544" required>
                </div>

                <div class="form-group">
                    <label>Category</label>
                    <select name="category">
                        <option value="Inter-city">Inter-city</option>
                        <option value="Semi-Luxury">Semi-Luxury</option>
                        <option value="SLTB">SLTB</option>
                        <option value="Private">Private</option>
                        <option value="High-way">High-way</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Assigned Route</label>
                    <select name="route_id">
                        <?php while ($row = $routes->fetch_assoc()): ?>
                            <option value="<?php echo $row['route_id']; ?>">
                                Route
                                <?php echo $row['route_id']; ?>:
                                <?php echo $row['start']; ?> to
                                <?php echo $row['end']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Ownership</label>
                    <input type="text" name="ownership" placeholder="e.g. Nimal Express" required>
                </div>

                <div class="form-group">
                    <label>Schedule Type</label>
                    <select name="schedule_type">
                        <option value="week-day">Week Day</option>
                        <option value="saturday">Saturday</option>
                        <option value="sunday">Sunday</option>
                    </select>
                </div>
                <div>
                    <div class="form-group">
                        <label>Contact Number 1</label>
                        <input type="text" name="contact_number[]">
                    </div>

                    <div class="form-group">
                        <label>Contact Number 2</label>
                        <input type="text" name="contact_number[]">
                    </div>
                </div>

                <div style="display: flex; gap: 10px;">
                    <div class="form-group" style="flex: 1;">
                        <label>From</label>
                        <select name="start_location_id">
                            <?php
                            $locations->data_seek(0); // Reset pointer
                            while ($loc = $locations->fetch_assoc()): ?>
                                <option value="<?php echo $loc['location_id']; ?>">
                                    <?php echo ucfirst($loc['location_name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Departure Time</label>
                        <input type="time" name="departure_time" required>
                    </div>
                </div>

                <div style="display: flex; gap: 10px;">
                    <div class="form-group" style="flex: 1;">
                        <label>To</label>
                        <select name="destination_location_id">
                            <?php
                            $locations->data_seek(0);
                            while ($loc = $locations->fetch_assoc()): ?>
                                <option value="<?php echo $loc['location_id']; ?>">
                                    <?php echo ucfirst($loc['location_name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Arrival Time</label>
                        <input type="time" name="arrival_time" required>
                    </div>
                </div>

                <button type="submit">Save Bus</button>
            </form>
        </div>

    </div>

    <!-- table -->
    <h2 class="table-title">Bus Records</h2>

    <div class="table-container">
        <table class="bus-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Vehicle No</th>
                    <th>Category</th>
                    <th>Route</th>
                    <th>Ownership</th>
                    <th>Schedule</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Departure</th>
                    <th>Arrival</th>
                    <th>Contacts</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?= $row['id']; ?>
                        </td>
                        <td>
                            <?= $row['vehicle_no']; ?>
                        </td>
                        <td>
                            <?= $row['category']; ?>
                        </td>
                        <td>
                            <?= $row['route_id']; ?>
                        </td>
                        <td>
                            <?= $row['ownership']; ?>
                        </td>
                        <td>
                            <?= $row['schedule_type']; ?>
                        </td>
                        <td>
                            <?= ucfirst($row['start_name']); ?>
                        </td>
                        <td>
                            <?= ucfirst($row['end_name']); ?>
                        </td>
                        <td>
                            <?= $row['departure_time']; ?>
                        </td>
                        <td>
                            <?= $row['arrival_time']; ?>
                        </td>
                        <td>
                            <?= $row['contacts']; ?>
                        </td>
                        <td>
                            <a class="btn-edit" href="update.php?id=<?= $row['id']; ?>">
                                Edit
                            </a>

                            <a class="btn-delete" href="edit.php?delete_id=<?= $row['id']; ?>"
                                onclick="return confirm('Are you sure you want to delete this bus?');">
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>


    <?php
    include("includes/footer.php");
    ?>

</body>

</html>