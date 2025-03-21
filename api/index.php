<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Infrastructure\Autoloader;
use App\Application\Controllers\LeagueController;
use App\Application\Services\LeagueService;
use App\Infrastructure\Repository\{FootballMatchRepository, LeagueRepository, TeamRepository};

Autoloader::register();

// Repositories
$leagueRepository = new LeagueRepository();
$teamRepository = new TeamRepository();
$footballMatchRepository = new FootballMatchRepository();

// Services
$leagueService = new LeagueService($leagueRepository, $teamRepository, $footballMatchRepository);

// Controllers
$leagueController = new LeagueController($leagueService);

// Handle CORS (Cross-Origin Resource Sharing)
//header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
//header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight request for CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Route the request
try {
    $leagueController->handleRequest($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
} catch (JsonException $e) {
    return $e->getMessage();
}
