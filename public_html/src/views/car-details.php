<?php
declare(strict_types=1);

$imagesRoot = __DIR__ . '/../../images';
$carSlug = strtolower(trim((string)($_GET['car'] ?? '')));

$brandPrices = [
    'ford' => 55,
    'mercedes' => 55,
    'opel' => 55,
    'peugeot' => 55,
    'volkswagen' => 55,
];

$brandDescriptions = [
    'ford' => 'Reliable and practical for everyday travel and weekend trips.',
    'mercedes' => 'Mercedes Sprinter van with 7.5 m3 cargo volume, ideal for larger loads.',
    'opel' => 'Opel Corsa compact automatic, practical for city use with A/C and easy handling.',
    'peugeot' => 'Compact and efficient choice for urban rides and short journeys.',
    'volkswagen' => 'Balanced all-rounder with smart features and a smooth drive.',
];

$slugAliases = [
    'mercedes-sprinter' => 'mercedes-benz-sprinter',
    'mercedes-sprinter-2019' => 'mercedes-benz-sprinter',
    'ford-transit-2020' => 'ford-transit',
    'opel-corsa-2020' => 'opel-corsa',
    'volkswagen-caddy-2020' => 'volkswagen-caddy',
];

if ($carSlug !== '' && isset($slugAliases[$carSlug])) {
    $carSlug = $slugAliases[$carSlug];
}

function format_car_title(string $slug): string
{
    return ucwords(str_replace(['-', '_'], ' ', $slug));
}

$carProfiles = [
    'ford-transit' => [
        'summary' => 'Practical Transit van suited to daily transport, utility jobs, and light moving tasks.',
        'pricePerDay' => 55,
        'specs' => [
            'Vehicle class' => 'Ford Transit or similar',
            'Use case' => 'Cargo and utility transport',
            'Transmission' => 'Confirm at booking',
            'Climate' => 'Confirm at booking',
        ],
        'pricing' => [
            'Day rate' => 'EUR 55',
        ],
        'includedKm' => [
            'Mileage policy' => 'Confirm at booking',
        ],
        'insurance' => [
            'Insurance details' => 'Confirm at booking',
            'Deductible' => 'Confirm at booking',
        ],
    ],
    'mercedes-benz-sprinter' => [
        'summary' => 'Type A+ cargo van with 7.5 m3 load volume and a longer loading area for larger transport needs.',
        'pricePerDay' => 55,
        'specs' => [
            'Load volume' => '7.5 m3',
            'Interior (L x W x H)' => '270 x 172 x 165 cm',
            'Exterior (L x W x H)' => '526 x 242 x 265 cm',
            'Extra kilometers' => 'Confirm at booking',
        ],
        'pricing' => [
            'Day rate' => 'EUR 55',
        ],
        'includedKm' => [
            'Mileage policy' => 'Confirm at booking',
        ],
        'insurance' => [
            'Insurance details' => 'Confirm at booking',
            'Deductible' => 'Confirm at booking',
        ],
    ],
    'opel-corsa' => [
        'summary' => 'Compact Opel Corsa rental, ideal for city driving, short trips, and everyday comfort.',
        'pricePerDay' => 55,
        'specs' => [
            'Vehicle class' => 'Opel Corsa or similar',
            'Seats' => '4',
            'Doors' => '3',
            'Transmission' => 'Automatic',
            'Climate' => 'Air conditioning',
            'Bags / Suitcases' => '2 bags / 1 suitcase',
        ],
        'pricing' => [
            'Day rate' => 'EUR 55',
        ],
        'includedKm' => [
            'Mileage policy' => 'Confirm at booking',
        ],
        'insurance' => [
            'Insurance details' => 'Confirm at booking',
            'Deductible' => 'Confirm at booking',
        ],
    ],
    'peugeot-108' => [
        'summary' => 'Compact Peugeot 108, well suited for city travel, short commutes, and easy parking.',
        'pricePerDay' => 55,
        'specs' => [
            'Vehicle class' => 'Peugeot 108 or similar',
            'Use case' => 'Urban and short-distance driving',
            'Transmission' => 'Confirm at booking',
            'Climate' => 'Confirm at booking',
        ],
        'pricing' => [
            'Day rate' => 'EUR 55',
        ],
        'includedKm' => [
            'Mileage policy' => 'Confirm at booking',
        ],
        'insurance' => [
            'Insurance details' => 'Confirm at booking',
            'Deductible' => 'Confirm at booking',
        ],
    ],
    'volkswagen-caddy' => [
        'summary' => 'Versatile Volkswagen Caddy with practical space for passengers or light cargo use.',
        'pricePerDay' => 55,
        'specs' => [
            'Vehicle class' => 'Volkswagen Caddy or similar',
            'Use case' => 'Mixed city and practical utility use',
            'Transmission' => 'Confirm at booking',
            'Climate' => 'Confirm at booking',
        ],
        'pricing' => [
            'Day rate' => 'EUR 55',
        ],
        'includedKm' => [
            'Mileage policy' => 'Confirm at booking',
        ],
        'insurance' => [
            'Insurance details' => 'Confirm at booking',
            'Deductible' => 'Confirm at booking',
        ],
    ],
    'volkswagen-id3' => [
        'summary' => 'Modern Volkswagen ID.3 electric hatchback for quiet, efficient city and regional trips.',
        'pricePerDay' => 55,
        'specs' => [
            'Vehicle class' => 'Volkswagen ID.3 or similar EV',
            'Powertrain' => 'Electric',
            'Charging' => 'Confirm cable and charging policy at booking',
            'Transmission' => 'Automatic',
        ],
        'pricing' => [
            'Day rate' => 'EUR 55',
        ],
        'includedKm' => [
            'Mileage policy' => 'Confirm at booking',
        ],
        'insurance' => [
            'Insurance details' => 'Confirm at booking',
            'Deductible' => 'Confirm at booking',
        ],
    ],
];

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
                <?php
                    $brandDescription = $brandDescriptions[$selectedCar['brand']] ?? 'Comfortable and practical car rental option.';
                    $brandDayPrice = $brandPrices[$selectedCar['brand']] ?? 55;
                ?>
                <?php
                    $brandDefaultProfiles = [
                        'ford' => 'ford-transit',
                        'mercedes' => 'mercedes-benz-sprinter',
                        'opel' => 'opel-corsa',
                        'peugeot' => 'peugeot-108',
                        'volkswagen' => 'volkswagen-caddy',
                    ];
                    $profileSlug = $selectedCar['slug'];
                    if (isset($slugAliases[$profileSlug])) {
                        $profileSlug = $slugAliases[$profileSlug];
                    }
                    if (!isset($carProfiles[$profileSlug]) && isset($brandDefaultProfiles[$selectedCar['brand']])) {
                        $profileSlug = $brandDefaultProfiles[$selectedCar['brand']];
                    }
                    $carProfile = $carProfiles[$profileSlug] ?? null;
                ?>

                <div class="detail-top-grid" style="display:flex;gap:1.5rem;align-items:flex-start;">
                    <div class="detail-copy-column" style="flex:0 0 calc(50% - 0.75rem);max-width:calc(50% - 0.75rem);">
                        <p class="hero-badge">Car Details</p>
                        <h1 class="hero-title"><?php echo htmlspecialchars($selectedCar['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
                        <p class="detail-meta">Brand: <?php echo htmlspecialchars(ucfirst($selectedCar['brand']), ENT_QUOTES, 'UTF-8'); ?></p>
                        <p class="detail-meta"><?php echo htmlspecialchars($brandDescription, ENT_QUOTES, 'UTF-8'); ?></p>
                        <p class="detail-meta"><strong>Price per day: EUR <?php echo htmlspecialchars((string)$brandDayPrice, ENT_QUOTES, 'UTF-8'); ?></strong></p>
                        <?php if ($carProfile !== null): ?>
                            <p class="detail-meta"><?php echo htmlspecialchars($carProfile['summary'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <?php endif; ?>

                        <p>
                            <a href="/booking?car=<?php echo rawurlencode($selectedCar['slug']); ?>"
                               class="booking-modal-back-btn"
                               data-track-event="car_book_click"
                               data-track-type="car"
                               data-track-id="<?php echo htmlspecialchars($selectedCar['slug'], ENT_QUOTES, 'UTF-8'); ?>">Book this car</a>
                        </p>
                    </div>

                    <div class="detail-image-wrap" style="flex:0 0 calc(50% - 0.75rem);max-width:calc(50% - 0.75rem);margin-top:0;">
                        <img class="detail-main-image" src="<?php echo htmlspecialchars($selectedCar['webPath'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($selectedCar['title'], ENT_QUOTES, 'UTF-8'); ?>">
                        <span class="car-status-badge is-unavailable">Unavailable</span>
                    </div>
                </div>

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

                <?php if ($carProfile !== null): ?>
                    <section class="detail-blueprints">
                        <h2 class="section-title">Rental Pricing</h2>
                        <div class="card" style="padding: 1rem;">
                            <ul>
                                <?php foreach ($carProfile['pricing'] as $label => $value): ?>
                                    <li><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>: <?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </section>

                    <section class="detail-blueprints">
                        <h2 class="section-title">Included Kilometers</h2>
                        <div class="card" style="padding: 1rem;">
                            <ul>
                                <?php foreach ($carProfile['includedKm'] as $label => $value): ?>
                                    <li><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>: <?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </section>

                    <section class="detail-blueprints">
                        <h2 class="section-title">Insurance and Deductible</h2>
                        <div class="card" style="padding: 1rem;">
                            <ul>
                                <?php foreach ($carProfile['insurance'] as $label => $value): ?>
                                    <li><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>: <?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </section>

                    <section class="detail-blueprints">
                        <h2 class="section-title">Vehicle Specs</h2>
                        <div class="card" style="padding: 1rem;">
                            <ul>
                                <?php foreach ($carProfile['specs'] as $label => $value): ?>
                                    <li><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>: <?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?></li>
                                <?php endforeach; ?>
                            </ul>
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