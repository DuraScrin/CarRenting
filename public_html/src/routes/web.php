<?php
declare(strict_types=1);

class Router
{
	private array $getRoutes = [];

	public function get(string $path, callable $handler): void
	{
		$this->getRoutes[$path] = $handler;
	}

	public function run(): void
	{
		$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
		$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

		if ($method === 'GET' && isset($this->getRoutes[$path])) {
			($this->getRoutes[$path])();
			return;
		}

		http_response_code(404);
		echo '404 Not Found';
	}
}

$router = new Router();

$router->get('/', function (): void {
	require __DIR__ . '/../views/home.php';
});

$router->get('/car', function (): void {
	require __DIR__ . '/../views/car-details.php';
});

return $router;