<?php
include("config/db_connect.php");

// Check ID
if (!isset($_GET['id'])) {
    header("Location: edit.php");
    exit();
}

$id = intval($_GET['id']);

// Fetch bus details
$sql = "SELECT * FROM bus WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    header("Location: edit.php");
    exit();
}

$bus = $result->fetch_assoc();

// Fetch routes
$routes = $conn->query("SELECT r.route_id, l1.location_name as start, l2.location_name as end 
                        FROM route r 
                        JOIN location l1 ON r.start_location = l1.location_id 
                        JOIN location l2 ON r.end_location = l2.location_id");

// Fetch locations
$locations = $conn->query("SELECT * FROM location");

// Fetch contacts
$contact_result = $conn->query("SELECT contact FROM contact WHERE bus_id = $id");
$contacts = [];
while ($row = $contact_result->fetch_assoc()) {
    $contacts[] = $row['contact'];
}

// ================= UPDATE =================
if (isset($_POST['update_bus'])) {

    $vehicle_no = $_POST['vehicle_no'];
    $category = $_POST['category'];
    $route_id = $_POST['route_id'];
    $ownership = $_POST['ownership'];
    $schedule_type = $_POST['schedule_type'];
    $start_location_id = $_POST['start_location_id'];
    $departure_time = $_POST['departure_time'];
    $destination_location_id = $_POST['destination_location_id'];
    $arrival_time = $_POST['arrival_time'];

    $update_sql = "UPDATE bus SET 
        vehicle_no='$vehicle_no',
        category='$category',
        route_id='$route_id',
        ownership='$ownership',
        schedule_type='$schedule_type',
        start_location_id='$start_location_id',
        departure_time='$departure_time',
        destination_location_id='$destination_location_id',
        arrival_time='$arrival_time'
        WHERE id=$id";

    if ($conn->query($update_sql)) {

        // Delete old contacts
        $conn->query("DELETE FROM contact WHERE bus_id = $id");

        // Insert new contacts
        if (!empty($_POST['contact_number'])) {
            foreach ($_POST['contact_number'] as $contact) {
                if (!empty($contact)) {
                    $conn->query("INSERT INTO contact (bus_id, contact) 
                                  VALUES ($id, '$contact')");
                }
            }
        }

        header("Location: edit.php?updated=1");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Bus</title>
    <link rel="shortcut icon" href="images/favicon.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <?php include("includes/header.php"); ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-primary text-white rounded-top-4">
                        <h4 class="mb-0">Edit Bus Details</h4>
                    </div>

                    <div class="card-body p-4">

                        <form method="POST">
                            <!-- Vehicle Info -->
                            <div class="row g-3"> 

                                <div class="col-md-6">
                                    <label class="form-label">Vehicle Number</label>
                                    <input readonly name="id" value="<?= $bus['id']; ?>" class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Vehicle Number</label>
                                    <input type="text" name="vehicle_no" class="form-control"
                                        value="<?= $bus['vehicle_no']; ?>" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Category</label>
                                    <select name="category" class="form-select">
                                        <option value="Inter-city" <?= ($bus['category'] == "Inter-city") ? "selected" : ""; ?>>Inter-city</option>
                                        <option value="Semi-Luxury" <?= ($bus['category'] == "Semi-Luxury") ? "selected" : ""; ?>>Semi-Luxury</option>
                                        <option value="SLTB" <?= ($bus['category'] == "SLTB") ? "selected" : ""; ?>>SLTB
                                        </option>
                                        <option value="Private" <?= ($bus['category'] == "Private") ? "selected" : ""; ?>>
                                            Private</option>
                                        <option value="High-way" <?= ($bus['category'] == "High-way") ? "selected" : ""; ?>>
                                            High-way</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Route</label>
                                    <select name="route_id" class="form-select">
                                        <?php while ($r = $routes->fetch_assoc()): ?>
                                            <option value="<?= $r['route_id']; ?>"
                                                <?= ($bus['route_id'] == $r['route_id']) ? "selected" : ""; ?>>
                                                Route <?= $r['route_id']; ?>:
                                                <?= $r['start']; ?> → <?= $r['end']; ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Ownership</label>
                                    <input type="text" name="ownership" class="form-control"
                                        value="<?= $bus['ownership']; ?>">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Schedule</label>
                                    <select name="schedule_type" class="form-select">
                                        <option value="week-day" <?= ($bus['schedule_type'] == "week-day") ? "selected" : ""; ?>>Week Day</option>
                                        <option value="saturday" <?= ($bus['schedule_type'] == "saturday") ? "selected" : ""; ?>>Saturday</option>
                                        <option value="sunday" <?= ($bus['schedule_type'] == "sunday") ? "selected" : ""; ?>>
                                            Sunday</option>
                                    </select>
                                </div>

                            </div>

                            <hr class="my-4">

                            <!-- Contacts -->
                            <h6 class="mb-3 text-secondary">Contact Information</h6>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Contact 1</label>
                                    <input type="text" name="contact_number[0]" class="form-control"
                                        value="<?= $contacts[0] ?? ''; ?>">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Contact 2</label>
                                    <input type="text" name="contact_number[1]" class="form-control"
                                        value="<?= $contacts[1] ?? ''; ?>">
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Schedule Info -->
                            <h6 class="mb-3 text-secondary">Journey Details</h6>

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label">From</label>
                                    <select name="start_location_id" class="form-select" required>
                                        <?php
                                        $locations->data_seek(0);
                                        while ($loc = $locations->fetch_assoc()):
                                            ?>
                                            <option value="<?= $loc['location_id']; ?>"
                                                <?= ($bus['start_location_id'] == $loc['location_id']) ? "selected" : ""; ?>>
                                                <?= ucfirst($loc['location_name']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Departure Time</label>
                                    <input type="time" name="departure_time" class="form-control"
                                        value="<?= $bus['departure_time']; ?>" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">To</label>
                                    <select name="destination_location_id" class="form-select" required>
                                        <?php
                                        $locations->data_seek(0);
                                        while ($loc = $locations->fetch_assoc()):
                                            ?>
                                            <option value="<?= $loc['location_id']; ?>"
                                                <?= ($bus['destination_location_id'] == $loc['location_id']) ? "selected" : ""; ?>>
                                                <?= ucfirst($loc['location_name']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Arrival Time</label>
                                    <input type="time" name="arrival_time" class="form-control"
                                        value="<?= $bus['arrival_time']; ?>" required>
                                </div>

                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <a href="edit.php" class="btn btn-outline-secondary me-2">
                                    Cancel
                                </a>
                                <button type="submit" name="update_bus" class="btn btn-primary px-4">
                                    Update Bus
                                </button>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php include("includes/footer.php"); ?>

</body>

</html>