<?php
declare(strict_types=1);

final class AnalyticsController
{
    private const ALLOWED_EVENTS = [
        'page_view',
        'button_click',
        'car_view',
        'car_book_click',
        'booking_submit_attempt',
    ];

    private static ?PDO $pdo = null;

    public static function trackPageView(string $pagePath): void
    {
        self::storeEvent([
            'event_name' => 'page_view',
            'page_path' => self::normalizePath($pagePath),
            'target_type' => null,
            'target_identifier' => null,
            'car_id' => null,
            'metadata' => null,
        ]);

        $routePath = parse_url($pagePath, PHP_URL_PATH) ?: '';
        $carSlug = self::extractCarSlugFromPath($pagePath);
        if ($routePath === '/car' && $carSlug !== null) {
            self::storeEvent([
                'event_name' => 'car_view',
                'page_path' => self::normalizePath($pagePath),
                'target_type' => 'car',
                'target_identifier' => $carSlug,
                'car_id' => self::lookupCarIdBySlug($carSlug),
                'metadata' => null,
            ]);
        }
    }

    public static function ingestEvent(): void
    {
        $payload = json_decode((string) file_get_contents('php://input'), true);
        if (!is_array($payload)) {
            http_response_code(400);
            echo 'Invalid JSON payload';
            return;
        }

        $eventName = (string) ($payload['event_name'] ?? '');
        if (!in_array($eventName, self::ALLOWED_EVENTS, true)) {
            http_response_code(422);
            echo 'Unsupported event_name';
            return;
        }

        $pagePath = self::normalizePath((string) ($payload['page_path'] ?? ($_SERVER['REQUEST_URI'] ?? '/')));
        $targetType = self::nullableString($payload['target_type'] ?? null, 50);
        $targetIdentifier = self::nullableString($payload['target_identifier'] ?? null, 100);

        $carSlug = null;
        if ($targetType === 'car' && $targetIdentifier !== null) {
            $carSlug = $targetIdentifier;
        } elseif ($eventName === 'car_view' || $eventName === 'car_book_click') {
            $carSlug = self::extractCarSlugFromPath($pagePath);
        }

        $carId = null;
        if (isset($payload['car_id']) && is_numeric($payload['car_id'])) {
            $carId = (int) $payload['car_id'];
        } elseif ($carSlug !== null) {
            $carId = self::lookupCarIdBySlug($carSlug);
        }

        self::storeEvent([
            'event_name' => $eventName,
            'page_path' => $pagePath,
            'target_type' => $targetType,
            'target_identifier' => $targetIdentifier,
            'car_id' => $carId,
            'metadata' => self::sanitizeMetadata($payload['metadata'] ?? null),
        ]);

        http_response_code(204);
    }

    private static function storeEvent(array $event): void
    {
        $pdo = self::db();
        if ($pdo === null) {
            return;
        }

        try {
            $metadataJson = null;
            if (isset($event['metadata']) && $event['metadata'] !== null) {
                $metadataJson = json_encode($event['metadata'], JSON_UNESCAPED_SLASHES);
            }

            $statement = $pdo->prepare(
                'INSERT INTO analytics_events (event_name, page_path, target_type, target_identifier, car_id, session_hash, client_fingerprint_hash, metadata_json)
                 VALUES (:event_name, :page_path, :target_type, :target_identifier, :car_id, :session_hash, :client_fingerprint_hash, :metadata_json)'
            );

            $statement->execute([
                ':event_name' => $event['event_name'],
                ':page_path' => $event['page_path'],
                ':target_type' => $event['target_type'],
                ':target_identifier' => $event['target_identifier'],
                ':car_id' => $event['car_id'],
                ':session_hash' => self::sessionHash(),
                ':client_fingerprint_hash' => self::clientFingerprintHash(),
                ':metadata_json' => $metadataJson,
            ]);
        } catch (Throwable $exception) {
            error_log('Analytics event insert failed: ' . $exception->getMessage());
        }
    }

    private static function db(): ?PDO
    {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        require __DIR__ . '/../config/database.php';
        if (isset($pdo) && $pdo instanceof PDO) {
            self::$pdo = $pdo;
            return self::$pdo;
        }

        return null;
    }

    private static function sessionHash(): string
    {
        $sessionId = session_id();
        return hash('sha256', 'session:' . $sessionId);
    }

    private static function clientFingerprintHash(): string
    {
        $ip = (string) ($_SERVER['REMOTE_ADDR'] ?? '');
        $userAgent = (string) ($_SERVER['HTTP_USER_AGENT'] ?? '');
        return hash('sha256', 'fingerprint:' . $ip . '|' . $userAgent);
    }

    private static function normalizePath(string $value): string
    {
        $value = trim($value);
        if ($value === '') {
            return '/';
        }

        $path = parse_url($value, PHP_URL_PATH);
        $query = parse_url($value, PHP_URL_QUERY);
        if ($path === null || $path === false || $path === '') {
            $path = '/';
        }

        return $query ? $path . '?' . $query : $path;
    }

    private static function sanitizeMetadata(mixed $metadata): ?array
    {
        if (!is_array($metadata)) {
            return null;
        }

        $sensitiveKeyPattern = '/email|phone|name|password|token|address|street|city|zip|postal/i';
        $clean = [];

        foreach ($metadata as $key => $value) {
            if (!is_string($key)) {
                continue;
            }

            if (preg_match($sensitiveKeyPattern, $key) === 1) {
                continue;
            }

            if (is_scalar($value) || $value === null) {
                $clean[$key] = self::truncate((string) $value, 120);
            }
        }

        return $clean === [] ? null : $clean;
    }

    private static function lookupCarIdBySlug(string $slug): ?int
    {
        $pdo = self::db();
        if ($pdo === null) {
            return null;
        }

        try {
            $statement = $pdo->prepare(
                'SELECT id
                 FROM cars
                 WHERE LOWER(REPLACE(CONCAT(make, "-", model), " ", "-")) = :slug
                 LIMIT 1'
            );
            $statement->execute([':slug' => strtolower($slug)]);
            $result = $statement->fetchColumn();

            return $result !== false ? (int) $result : null;
        } catch (Throwable $exception) {
            error_log('Car lookup for analytics failed: ' . $exception->getMessage());
            return null;
        }
    }

    private static function extractCarSlugFromPath(string $path): ?string
    {
        $query = parse_url($path, PHP_URL_QUERY);
        if ($query === null || $query === false) {
            return null;
        }

        parse_str($query, $params);
        $car = $params['car'] ?? null;
        if (!is_string($car) || trim($car) === '') {
            return null;
        }

        return strtolower(trim($car));
    }

    private static function nullableString(mixed $value, int $maxLength): ?string
    {
        if (!is_string($value)) {
            return null;
        }

        $value = trim($value);
        if ($value === '') {
            return null;
        }

        return self::truncate($value, $maxLength);
    }

    private static function truncate(string $value, int $maxLength): string
    {
        if (strlen($value) <= $maxLength) {
            return $value;
        }

        return substr($value, 0, $maxLength);
    }
}
