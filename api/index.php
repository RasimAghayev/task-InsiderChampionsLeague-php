<?php

require __DIR__.'/../vendor/autoload.php';

use App\Application\Services\LeagueService;
use App\Infrastructure\Autoloader;
use App\Infrastructure\Repository\{FootballMatchRepository, LeagueRepository, TeamRepository};
Autoloader::register();

// Repositories
$leagueRepository = new LeagueRepository();
$teamRepository = new TeamRepository();
$footballMatchRepository = new FootballMatchRepository();

// Services
$leagueService = new LeagueService($leagueRepository, $teamRepository, $footballMatchRepository);

// Handle CORS (Cross-Origin Resource Sharing)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight request for CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Parse request body
$input = file_get_contents('php://input');
$data = parseRequestBody($input);

// Route the request
try {
    handleRequest($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'], $data, $leagueService);
} catch (Exception $e) {
    sendErrorResponse($e->getMessage(), 500);
}

/**
 * Parse the request body and validate JSON format.
 *
 * @param string $input
 * @return array
 */
function parseRequestBody(string $input): array
{
    try {
        return json_decode($input, true, 512, JSON_THROW_ON_ERROR) ?? [];
    } catch (JsonException $e) {
        sendErrorResponse('Invalid JSON format', 400);
        exit();
    }
}

/**
 * Handle the incoming HTTP request.
 *
 * @param string $method
 * @param string $uri
 * @param array $data
 * @param LeagueService $leagueService
 */
function handleRequest(string $method, string $uri, array $data, LeagueService $leagueService): void
{
    switch ($method) {
        case 'POST':
            handlePostRequest($uri, $data, $leagueService);
            break;

        case 'GET':
            handleGetRequest($uri, $leagueService);
            break;

        case 'PUT':
            handlePutRequest($uri, $data, $leagueService);
            break;

        case 'DELETE':
            handleDeleteRequest($uri, $data, $leagueService);
            break;

        default:
            sendErrorResponse('Endpoint not found', 404);
            break;
    }
}

/**
 * Handle POST requests.
 *
 * @param string $uri
 * @param array $data
 * @param LeagueService $leagueService
 */
function handlePostRequest(string $uri, array $data, LeagueService $leagueService): void
{
    switch ($uri) {
        case '/leagues':
            validateRequiredField($data, 'name', 'League name is required');
            $leagueService->createLeague($data['name']);
            sendSuccessResponse('League created successfully');
            break;

        case '/teams':
            validateRequiredField($data, 'name', 'Team name is required');
            $strength = $data['strength'] ?? 0;
            $leagueService->addTeam($data['name'], $strength);
            sendSuccessResponse('Team added successfully');
            break;

        case '/matches':
            validateRequiredField($data, 'home_team_id', 'Home team ID is required');
            validateRequiredField($data, 'away_team_id', 'Away team ID is required');
            $leagueService->createMatch($data['home_team_id'], $data['away_team_id']);
            sendSuccessResponse('Match created successfully');
            break;

        case '/leagues/{id}/generate-teams':
            validateRequiredField($data, 'number_of_teams', 'Number of teams is required');
            $leagueService->generateTeamsForLeague($data['number_of_teams']);
            sendSuccessResponse('Teams generated successfully');
            break;

        case '/leagues/{id}/generate-matches':
            $leagueService->generateMatchesForLeague();
            sendSuccessResponse('Matches generated successfully');
            break;

        default:
            sendErrorResponse('Endpoint not found', 404);
            break;
    }
}

/**
 * Handle GET requests.
 *
 * @param string $uri
 * @param LeagueService $leagueService
 */
function handleGetRequest(string $uri, LeagueService $leagueService): void
{
    switch ($uri) {
        case '/leagues/{id}':
            $league = $leagueService->getLeagueById($data['id']);
            sendSuccessResponse($league);
            break;

        case '/teams/{id}':
            $team = $leagueService->getTeamById($data['id']);
            sendSuccessResponse($team);
            break;

        case '/matches/{id}':
            $match = $leagueService->getMatchById($data['id']);
            sendSuccessResponse($match);
            break;

        default:
            sendErrorResponse('Endpoint not found', 404);
            break;
    }
}

/**
 * Handle PUT requests.
 *
 * @param string $uri
 * @param array $data
 * @param LeagueService $leagueService
 * @throws \JsonException
 */
function handlePutRequest(string $uri, array $data, LeagueService $leagueService): void
{
    switch ($uri) {
        case '/leagues/{id}':
            validateRequiredField($data, 'name', 'League name is required');
            $leagueService->updateLeague($data['id'], $data['name']);
            sendSuccessResponse('League updated successfully');
            break;

        case '/teams/{id}':
            validateRequiredField($data, 'name', 'Team name is required');
            validateRequiredField($data, 'strength', 'Team strength is required');
            $leagueService->updateTeam($data['id'], $data['name'], $data['strength']);
            sendSuccessResponse('Team updated successfully');
            break;

        case '/matches/{id}':
            validateRequiredField($data, 'home_goals', 'Home goals is required');
            validateRequiredField($data, 'away_goals', 'Away goals is required');
            $leagueService->updateMatch($data['id'], $data['home_goals'], $data['away_goals']);
            sendSuccessResponse('Match updated successfully');
            break;

        default:
            sendErrorResponse('Endpoint not found', 404);
            break;
    }
}

/**
 * Handle DELETE requests.
 *
 * @param string $uri
 * @param array $data
 * @param LeagueService $leagueService
 * @throws \JsonException
 */
function handleDeleteRequest(string $uri, array $data, LeagueService $leagueService): void
{
    switch ($uri) {
        case '/leagues/{id}':
            $leagueService->deleteLeague($data['id']);
            sendSuccessResponse('League deleted successfully');
            break;

        case '/teams/{id}':
            $leagueService->deleteTeam($data['id']);
            sendSuccessResponse('Team deleted successfully');
            break;

        case '/matches/{id}':
            $leagueService->deleteMatch($data['id']);
            sendSuccessResponse('Match deleted successfully');
            break;

        default:
            sendErrorResponse('Endpoint not found', 404);
            break;
    }
}

/**
 * Validate that a required field exists in the request data.
 *
 * @param array $data
 * @param string $field
 * @param string $errorMessage
 * @throws \JsonException
 */
function validateRequiredField(array $data, string $field, string $errorMessage): void
{
    if (empty($data[$field])) {
        sendErrorResponse($errorMessage, 400);
        exit();
    }
}

/**
 * Send a success response with JSON format.
 *
 * @param string|array $message
 * @param int $statusCode
 * @throws \JsonException
 */
function sendSuccessResponse(string|array $message, int $statusCode = 200): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode(is_string($message) ? ['message' => $message] : $message, JSON_THROW_ON_ERROR);
}

/**
 * Send an error response with JSON format.
 *
 * @param string $message
 * @param int $statusCode
 * @throws \JsonException
 */
function sendErrorResponse(string $message, int $statusCode): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode(['error' => $message], JSON_THROW_ON_ERROR);
}