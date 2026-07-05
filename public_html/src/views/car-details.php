<?php
declare(strict_types=1);

$imagesRoot = __DIR__ . '/../../images';
$carSlug = strtolower(trim((string)($_GET['car'] ?? '')));

function format_car_title(string $slug): string
{
    return ucwords(str_replace(['-', '_'], ' ', $slug));
}

$selectedCar = null;
$brands = glob($imagesRoot . '/*', GLOB_ONLYDIR) ?: [];
sort($brands);

foreach ($brands as $brandDir) {
    $brand = basename($brandDir);
    $brandImages = glob($brandDir . '/*.{jpg,jpeg,png,webp,gif}', GLOB_BRACE) ?: [];

    foreach ($brandImages as $filePath) {
        $fileName = basename($filePath);
        $fileSlug = strtolower(pathinfo($fileName, PATHINFO_FILENAME));

        if ($carSlug !== '' && $fileSlug === $carSlug && stripos($fileName, 'blueprint') === false) {
            $selectedCar = [
                'brand' => $brand,
                'file' => $fileName,
                'slug' => $fileSlug,
                'title' => format_car_title($fileSlug),
                'webPath' => '/images/' . $brand . '/' . $fileName,
                'blueprints' => array_values(array_filter($brandImages, static function (string $candidate) use ($fileSlug): bool {
                    return stripos(basename($candidate), 'blueprint') !== false
                        && stripos(basename($candidate), $fileSlug) !== false;
                })),
            ];
            break 2;
        }
    }
}

if ($selectedCar === null && !empty($brands)) {
    foreach ($brands as $brandDir) {
        $brandImages = glob($brandDir . '/*.{jpg,jpeg,png,webp,gif}', GLOB_BRACE) ?: [];
        foreach ($brandImages as $filePath) {
            $fileName = basename($filePath);
            if (stripos($fileName, 'blueprint') !== false) {
                continue;
            }

            $fileSlug = strtolower(pathinfo($fileName, PATHINFO_FILENAME));
            $selectedCar = [
                'brand' => basename($brandDir),
                'file' => $fileName,
                'slug' => $fileSlug,
                'title' => format_car_title($fileSlug),
                'webPath' => '/images/' . basename($brandDir) . '/' . $fileName,
                'blueprints' => array_values(array_filter($brandImages, static function (string $candidate) use ($fileSlug): bool {
                    return stripos(basename($candidate), 'blueprint') !== false
                        && stripos(basename($candidate), $fileSlug) !== false;
                })),
            ];
            break 2;
        }
    }
}

$pageTitle = $selectedCar ? $selectedCar['title'] . ' Details' : 'Car Details';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
    <?php include __DIR__ . '/layouts/header.php'; ?>

    <main class="home-main">
        <section class="container detail-hero card">
            <?php if ($selectedCar !== null): ?>
                <p class="hero-badge">Car Details</p>
                <h1 class="hero-title"><?php echo htmlspecialchars($selectedCar['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
                <p class="detail-meta">Brand: <?php echo htmlspecialchars(ucfirst($selectedCar['brand']), ENT_QUOTES, 'UTF-8'); ?></p>
                <div class="detail-image-wrap">
                    <img class="detail-main-image" src="<?php echo htmlspecialchars($selectedCar['webPath'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($selectedCar['title'], ENT_QUOTES, 'UTF-8'); ?>">
                    <span class="car-status-badge is-unavailable">Unavailable</span>
                </div>

                <p>
                    <a href="/booking?car=<?php echo rawurlencode($selectedCar['slug']); ?>"
                       class="booking-modal-back-btn"
                       data-track-event="car_book_click"
                       data-track-type="car"
                       data-track-id="<?php echo htmlspecialchars($selectedCar['slug'], ENT_QUOTES, 'UTF-8'); ?>">Book this car</a>
                </p>

                <?php if (!empty($selectedCar['blueprints'])): ?>
                    <section class="detail-blueprints">
                        <h2 class="section-title">Blueprints</h2>
                        <div class="blueprint-grid">
                            <?php foreach ($selectedCar['blueprints'] as $blueprintPath): ?>
                                <article class="card blueprint-card">
                                    <img src="<?php echo htmlspecialchars('/images/' . $selectedCar['brand'] . '/' . basename($blueprintPath), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($selectedCar['title'] . ' blueprint', ENT_QUOTES, 'UTF-8'); ?>">
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>
            <?php else: ?>
                <p class="hero-badge">Car Details</p>
                <h1 class="hero-title">Car not found</h1>
                <p class="detail-meta">The requested car could not be found. Go back to the homepage and choose another vehicle.</p>
            <?php endif; ?>
        </section>
    </main>

    <?php include __DIR__ . '/layouts/footer.php'; ?>
</body>
</html>