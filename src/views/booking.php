<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental Booking</title>
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
    <?php include 'layouts/header.php'; ?>

    <div class="container">
        <h1>Book a Car</h1>
        <form action="/booking/submit" method="POST">
            <label for="car_id">Select Car:</label>
            <select name="car_id" id="car_id" required>
                <!-- Options will be populated dynamically from the database -->
            </select>

            <label for="pickup_date">Pickup Date:</label>
            <input type="date" name="pickup_date" id="pickup_date" required>

            <label for="return_date">Return Date:</label>
            <input type="date" name="return_date" id="return_date" required>

            <input type="submit" value="Book Now">
        </form>
    </div>

    <?php include 'layouts/footer.php'; ?>
</body>
</html>