<?php
declare(strict_types=1);

$imagesRoot = __DIR__ . '/../../images';
$imageExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
$cars = [];

$brandPrices = [
    'ford' => 79,
    'mercedes' => 129,
    'opel' => 59,
    'peugeot' => 49,
    'volkswagen' => 89,
];

$brandDescriptions = [
    'ford' => 'Reliable and practical for everyday travel and weekend trips.',
    'mercedes' => 'Comfort-focused premium rental with extra space and refinement.',
    'opel' => 'Affordable city-friendly option that is easy to park and drive.',
    'peugeot' => 'Compact and efficient choice for urban rides and short journeys.',
    'volkswagen' => 'Balanced all-rounder with smart features and a smooth drive.',
];

foreach (glob($imagesRoot . '/*', GLOB_ONLYDIR) ?: [] as $brandDir) {
    $brand = basename($brandDir);
    foreach ($imageExtensions as $ext) {
        foreach (glob($brandDir . '/*.' . $ext) ?: [] as $filePath) {
            $fileName = basename($filePath);
            if (stripos($fileName, 'blueprint') !== false) {
                continue;
            }

            $slug = strtolower(pathinfo($fileName, PATHINFO_FILENAME));
            $title = ucwords(str_replace(['-', '_'], ' ', $slug));

            $cars[] = [
                'brand' => $brand,
                'slug' => $slug,
                'title' => $title,
                'description' => $brandDescriptions[$brand] ?? 'Comfortable and practical car rental option.',
                'price' => $brandPrices[$brand] ?? 69,
                'webPath' => '/images/' . $brand . '/' . $fileName,
                'detailsUrl' => '/car?car=' . rawurlencode($slug),
            ];

            break 2;
        }
    }
}

usort($cars, static function (array $left, array $right): int {
    return strcmp($left['title'], $right['title']);
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Cars</title>
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
    <?php include __DIR__ . '/layouts/header.php'; ?>

    <main class="home-main">
        <section class="container cars-hero card">
            <p class="hero-badge">Available Fleet</p>
            <h1 class="hero-title">Choose Your Car</h1>
            <p class="detail-meta">Browse all available cars in list view. Each item shows a thumbnail, a short description, and the price per day.</p>
        </section>

        <section class="container cars-list-section">
            <?php if (!empty($cars)): ?>
                <div class="cars-list">
                    <?php foreach ($cars as $car): ?>
                                <a class="cars-list-item card"
                                    href="<?php echo htmlspecialchars($car['detailsUrl'], ENT_QUOTES, 'UTF-8'); ?>"
                                    data-track-event="button_click"
                                    data-track-type="car"
                                    data-track-id="<?php echo htmlspecialchars($car['slug'], ENT_QUOTES, 'UTF-8'); ?>">
                            <img class="cars-list-thumb" src="<?php echo htmlspecialchars($car['webPath'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($car['title'], ENT_QUOTES, 'UTF-8'); ?>">
                            <div class="cars-list-body">
                                <h2><?php echo htmlspecialchars($car['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
                                <p><?php echo htmlspecialchars($car['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                            </div>
                            <div class="cars-list-price">
                                <span>Price per day</span>
                                <strong>€<?php echo htmlspecialchars((string)$car['price'], ENT_QUOTES, 'UTF-8'); ?></strong>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="card empty-gallery">
                    <p>No cars found yet. Add homepage images in `public_html/images/{brand}/`.</p>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <?php include __DIR__ . '/layouts/footer.php'; ?>
</body>
</html><!DOCTYPE html>