<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Cars</title>
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
    <?php include 'layouts/header.php'; ?>

    <div class="container">
        <h1>Available Cars</h1>
        <div class="car-list">
            <!-- PHP code to fetch and display available cars will go here -->
            <?php
            // Example of fetching cars from the database
            // Assuming $cars is an array of car objects fetched from the database
            foreach ($cars as $car) {
                echo '<div class="car-item">';
                echo '<h2>' . htmlspecialchars($car->name) . '</h2>';
                echo '<p>Model: ' . htmlspecialchars($car->model) . '</p>';
                echo '<p>Price per day: $' . htmlspecialchars($car->price_per_day) . '</p>';
                echo '<a href="booking.php?car_id=' . htmlspecialchars($car->id) . '">Book Now</a>';
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <?php include 'layouts/footer.php'; ?>
</body>
</html>