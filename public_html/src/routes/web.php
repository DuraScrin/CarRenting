<?php
declare(strict_types=1);

class Router
{
	private array $getRoutes = [];
	private array $postRoutes = [];

	public function get(string $path, callable $handler): void
	{
		$this->getRoutes[$path] = $handler;
	}

	public function post(string $path, callable $handler): void
	{
		$this->postRoutes[$path] = $handler;
	}

	public function run(): void
	{
		$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
		$path = parse_url($requestUri, PHP_URL_PATH) ?: '/';
		$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

		if ($method === 'GET' && isset($this->getRoutes[$path])) {
			AnalyticsController::trackPageView($requestUri);
			($this->getRoutes[$path])();
			return;
		}

		if ($method === 'POST' && isset($this->postRoutes[$path])) {
			($this->postRoutes[$path])();
			return;
		}

		http_response_code(404);
		echo '404 Not Found';
	}
}

require_once __DIR__ . '/../controllers/AnalyticsController.php';

$router = new Router();

$router->get('/', function (): void {
	require __DIR__ . '/../views/home.php';
});

$router->get('/car', function (): void {
	require __DIR__ . '/../views/car-details.php';
});

$router->get('/cars', function (): void {
	require __DIR__ . '/../views/cars.php';
});

$router->get('/booking', function (): void {
	require __DIR__ . '/../views/booking.php';
});

$router->post('/api/events', function (): void {
	AnalyticsController::ingestEvent();
});

return $router;