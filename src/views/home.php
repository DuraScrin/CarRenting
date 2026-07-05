<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental Home</title>
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
    <?php include __DIR__ . '/layouts/header.php'; ?>
    <?php
    $imageDirectory = __DIR__ . '/../../public_html/images';
    $imagePatterns = ['*.jpg', '*.jpeg', '*.png', '*.webp', '*.gif'];
    $carImages = [];

    foreach ($imagePatterns as $pattern) {
        $files = glob($imageDirectory . '/' . $pattern) ?: [];
        $carImages = array_merge($carImages, $files);
    }

    sort($carImages);
    ?>

    <main class="home-main">
        <section class="hero container">
            <span class="hero-badge">Fast. Flexible. Reliable.</span>
            <h1 class="hero-title">Rent the Right Car for Every Journey</h1>
            <p class="hero-subtitle">Choose from city cars, family SUVs, and premium models at transparent prices.</p>
        </section>

        <section class="searchsection container">
            <div class="search-widget card">
                <h2 class="section-title">Search Availability</h2>
                <form class="booking-form" action="/cars" method="get">
                    <div class="field location">
                        <label>Pick-up location</label>
                        <input
                            type="text"
                            name="pickup_location"
                            placeholder="City, airport, station..."
                            autocomplete="off"
                        />
                    </div>

                    <div class="field date">
                        <label>Pick-up</label>
                        <input type="date" name="pickup_date">
                    </div>

                    <div class="field time">
                        <label>Time</label>
                        <select name="pickup_time">
                            <option>10:00</option>
                            <option>12:00</option>
                            <option>14:00</option>
                            <option>16:00</option>
                        </select>
                    </div>

                    <div class="field date">
                        <label>Return</label>
                        <input type="date" name="return_date">
                    </div>

                    <div class="field time">
                        <label>Time</label>
                        <select name="return_time">
                            <option>10:00</option>
                            <option>12:00</option>
                            <option>14:00</option>
                            <option>16:00</option>
                        </select>
                    </div>

                    <div class="checkbox">
                        <input type="checkbox" id="differentLocation" name="different_location" value="1">
                        <label for="differentLocation">
                            Return to a different location
                        </label>
                    </div>

                    <button type="submit">
                        Search
                    </button>
                </form>
            </div>
        </section>

        <section class="container gallery-section">
            <div class="section-heading">
                <h2 class="section-title">Our Cars</h2>
                <p class="section-subtitle">Browse the vehicles currently available in our fleet.</p>
            </div>

            <?php if (!empty($carImages)): ?>
                <div class="car-gallery">
                    <?php foreach ($carImages as $imagePath): ?>
                        <?php
                        $fileName = basename($imagePath);
                        $title = ucwords(str_replace(['-', '_'], ' ', pathinfo($fileName, PATHINFO_FILENAME)));
                        ?>
                        <article class="car-card card">
                            <div class="car-card-media">
                                <img src="/images/<?php echo htmlspecialchars($fileName, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>">
                                <span class="car-status-badge is-unavailable">Unavailable</span>
                            </div>
                            <div class="car-card-body">
                                <h3><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></h3>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="card empty-gallery">
                    <p>No car images found yet. Upload images to <code>public_html/images</code> to show them here.</p>
                </div>
            <?php endif; ?>
        </section>

        <section class="container actions-row">
            <a href="/cars" class="btn btn-primary">View Available Cars</a>
            <a href="/booking" class="btn btn-ghost">Make a Booking</a>
        </section>

        <section class="container highlights-grid">
            <article class="card feature-card">
                <h3>Wide Fleet</h3>
                <p>Economy, electric, SUV, and premium vehicles for every type of trip.</p>
            </article>
            <article class="card feature-card">
                <h3>No Hidden Fees</h3>
                <p>Clear pricing with transparent protection options and rental conditions.</p>
            </article>
            <article class="card feature-card">
                <h3>Flexible Booking</h3>
                <p>Change plans easily with fast online booking updates when available.</p>
            </article>
        </section>
    </main>

    <?php include __DIR__ . '/layouts/footer.php'; ?>
</body>
</html>