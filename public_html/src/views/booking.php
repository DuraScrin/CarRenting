<?php
declare(strict_types=1);

$availableCars = [
    'Ford Transit',
    'Mercedes Benz Sprinter',
    'Opel Corsa',
    'Peugeot 108',
    'Volkswagen Caddy',
    'Volkswagen ID3',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental Booking</title>
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
    <?php include __DIR__ . '/layouts/header.php'; ?>

    <main class="home-main booking-page">
        <section class="container booking-shell card">
            <div class="booking-modal" role="alertdialog" aria-modal="true" aria-labelledby="booking-modal-title">
                <div class="booking-modal-panel">
                    <h1 id="booking-modal-title" class="booking-modal-title">At the moment we don't have any available cars.</h1>
                    <button type="button" class="booking-modal-back-btn" onclick="window.history.back()" data-track-event="button_click" data-track-type="booking" data-track-id="modal_go_back">Go back</button>
                </div>
            </div>

            <div class="booking-form-wrap">
                <h2 class="section-title">Booking Request</h2>
                <form class="booking-request-form" action="/booking" method="post">
                    <fieldset class="booking-form-lock" disabled>
                        <div class="field full">
                            <label for="car_id">Select Car</label>
                            <select name="car_id" id="car_id">
                                <option value="">Choose a car</option>
                                <?php foreach ($availableCars as $carName): ?>
                                    <option value="<?php echo htmlspecialchars(strtolower(str_replace(' ', '-', $carName)), ENT_QUOTES, 'UTF-8'); ?>">
                                        <?php echo htmlspecialchars($carName, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="field">
                            <label for="pickup_location">Pickup Location</label>
                            <input type="text" name="pickup_location" id="pickup_location" value="Antwerpen">
                        </div>

                        <div class="field">
                            <label for="pickup_date">Pickup Date</label>
                            <input type="date" name="pickup_date" id="pickup_date">
                        </div>

                        <div class="field">
                            <label for="pickup_time">Pickup Time</label>
                            <select name="pickup_time" id="pickup_time">
                                <option>10:00</option>
                                <option>12:00</option>
                                <option>14:00</option>
                                <option>16:00</option>
                            </select>
                        </div>

                        <div class="field">
                            <label for="return_date">Return Date</label>
                            <input type="date" name="return_date" id="return_date">
                        </div>

                        <div class="field">
                            <label for="return_time">Return Time</label>
                            <select name="return_time" id="return_time">
                                <option>10:00</option>
                                <option>12:00</option>
                                <option>14:00</option>
                                <option>16:00</option>
                            </select>
                        </div>

                        <div class="field full">
                            <label for="full_name">Full Name</label>
                            <input type="text" name="full_name" id="full_name" placeholder="Your name">
                        </div>

                        <div class="field full">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" placeholder="you@example.com">
                        </div>

                        <div class="field full">
                            <label for="phone">Phone</label>
                            <input type="tel" name="phone" id="phone" placeholder="+32...">
                        </div>

                        <div class="field full">
                            <label for="notes">Notes</label>
                            <textarea name="notes" id="notes" rows="4" placeholder="Tell us anything useful about your trip"></textarea>
                        </div>

                        <button type="submit" data-track-event="booking_submit_attempt" data-track-type="booking" data-track-id="book_now">Book Now</button>
                    </fieldset>
                </form>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/layouts/footer.php'; ?>
</body>
</html>