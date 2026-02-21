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
    require("includes/header.php");
   ?>
     <!-- Hero Section -->
    <section class="hero">
        <div class="search-box">
            <h2>Find Your Bus</h2>

            <div class="form-group">
                <div>
                    <label>From:</label>
                    <select>
                        <option>Colombo Fort</option>
                        <option>Kandy</option>
                        <option>Galle</option>
                    </select>
                </div>

                <div>
                    <label>To:</label>
                    <select>
                        <option>Kandy</option>
                        <option>Colombo Fort</option>
                        <option>Jaffna</option>
                    </select>
                </div>
            </div>

            <button>Search Buses</button>
        </div>
    </section>

    <!-- Bus Results -->
    <section class="results">
        <h2>Available Bus Services: Colombo Fort to Kandy</h2>

        <div class="bus-card">
            <div><strong>Departure:</strong> 06:30 AM</div>
            <div><strong>Arrival (Est.):</strong> 09:30 AM</div>
            <div><strong>Duration:</strong> 3h 00m</div>
            <div><strong>Bus Type:</strong> SLTB (Intercity)</div>
            <div><strong>Frequency:</strong> Every 30 mins</div>
        </div>

        <div class="bus-card">
            <div><strong>Departure:</strong> 07:00 AM</div>
            <div><strong>Arrival (Est.):</strong> 10:15 AM</div>
            <div><strong>Duration:</strong> 3h 15m</div>
            <div><strong>Bus Type:</strong> Private (Semi-Luxury)</div>
            <div><strong>Frequency:</strong> Hourly</div>
        </div>

        <div class="bus-card">
            <div><strong>Departure:</strong> 07:45 AM</div>
            <div><strong>Arrival (Est.):</strong> 10:30 AM</div>
            <div><strong>Duration:</strong> 2h 45m</div>
            <div><strong>Bus Type:</strong> Private (Highway A/C)</div>
            <div><strong>Frequency:</strong> Every 45 mins</div>
        </div>
    </section>

    <?php
    include("includes/footer.php");
    ?>
    
</body>
</html>