<?php

namespace App\Application\Controllers;

use App\Application\Services\LeagueService;
use Exception;
use JsonException;
use JsonSerializable;

class LeagueController
{
    private LeagueService $leagueService;

    public function __construct(LeagueService $leagueService)
    {
        $this->leagueService = $leagueService;
    }

    /**
     * @throws \JsonException
     */
    public function handleRequest(string $method, string $uri): void
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
                http_response_code(200);
                exit();
            }

            $data = [];
            if (in_array($method, ['POST', 'PUT'])) {
                $input = file_get_contents('php://input');
                $data = $this->parseRequestBody($input);
            }

            switch ($method) {
                case 'POST':
                    $this->handlePostRequest($uri, $data);
                    break;
                case 'GET':
                    $this->handleGetRequest($uri);
                    break;
                case 'PUT':
                    $this->handlePutRequest($uri, $data);
                    break;
                case 'DELETE':
                    $this->handleDeleteRequest($uri);
                    break;
                default:
                    $this->sendErrorResponse('Endpoint not found', 404);
                    break;
            }
        } catch (Exception|JsonException $e) {
            $this->sendErrorResponse($e->getMessage(), 500);
        }
    }

    /**
     * @throws \JsonException
     */
    private function parseRequestBody(string $input): array
    {
        try {
            return json_decode($input, true, 512, JSON_THROW_ON_ERROR) ?? [];
        } catch (JsonException $e) {
            $this->sendErrorResponse('Invalid JSON format', 400);
            exit();
        }
    }

    /**
     * @throws \JsonException
     */
    private function sendErrorResponse(string $message, int $statusCode): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode(['error' => $message], JSON_THROW_ON_ERROR);
    }

    /**
     * @throws \Random\RandomException
     * @throws \JsonException
     */
    private function handlePostRequest(string $uri, array $data): void
    {
        switch ($uri) {
            case '/leagues':
                $this->validateRequiredField($data, 'name', 'League name is required');
                $this->leagueService->createLeague($data['name']);
                $this->sendSuccessResponse('League created successfully');
                break;
            case '/teams':
                $this->validateRequiredField($data, 'name', 'Team name is required');
                $strength = $data['strength'] ?? 0;
                $this->leagueService->addTeam($data['name'], $strength);
                $this->sendSuccessResponse('Team added successfully');
                break;
            case '/matches':
                $this->validateRequiredField($data, 'home_team_id', 'Home team ID is required');
                $this->validateRequiredField($data, 'away_team_id', 'Away team ID is required');
                $this->leagueService->createMatch($data['home_team_id'], $data['away_team_id']);
                $this->sendSuccessResponse('Match created successfully');
                break;
            case '/leagues/generate-teams':
                    $this->validateRequiredField($data, 'number_of_teams', 'Number of teams is required');
                    $this->leagueService->generateTeamsForLeague($data['number_of_teams']);
                    $this->sendSuccessResponse('Teams generated successfully');
                break;
            case '/leagues/generate-matches':
                $this->validateRequiredField($data, 'number_of_weeks', 'Number of weeks is required');
                    $this->leagueService->generateMatchesForLeague($data['number_of_weeks']);
                    $this->sendSuccessResponse('Matches generated successfully');
                break;
            default:
                $this->sendErrorResponse('Endpoint not found POST', 404);
                break;
        }
    }

    /**
     * @throws \JsonException
     */
    private function validateRequiredField(array $data, string $field, string $errorMessage): void
    {
        if (empty($data[$field])) {
            $this->sendErrorResponse($errorMessage, 400);
            exit();
        }
    }

    /**
     * @throws \JsonException
     */
    private function sendSuccessResponse(mixed $message): void
    {
        http_response_code(200);
        header('Content-Type: application/json');
        if ($message instanceof JsonSerializable) {
            $message = $message->jsonSerialize();
        }
        echo json_encode(is_string($message) ? ['message' => $message] : $message, JSON_THROW_ON_ERROR);
    }

    /**
     * @throws \JsonException
     */
    /**
     * @throws \JsonException
     */
    private function handleGetRequest(string $uri): void
    {
        // Extract ID from league URL
        if (preg_match('#^/leagues/(\d+)$#', $uri, $matches)) {
            $id = (int)$matches[1];
            $league = $this->leagueService->getLeagueById($id);
            if ($league === null) {
                $this->sendErrorResponse('League not found', 404);
                return;
            }
            $this->sendSuccessResponse($league);
            return;
        }

        // Extract ID from team URL
        if (preg_match('#^/teams/(\d+)$#', $uri, $matches)) {
            $id = (int)$matches[1];
            $team = $this->leagueService->getTeamById($id);
            if ($team === null) {
                $this->sendErrorResponse('Team not found', 404);
                return;
            }
            $this->sendSuccessResponse($team);
            return;
        }

        // Extract ID from match URL
        if (preg_match('#^/matches/(\d+)$#', $uri, $matches)) {
            $id = (int)$matches[1];
            $match = $this->leagueService->getMatchById($id);
            if ($match === null) {
                $this->sendErrorResponse('Match not found', 404);
                return;
            }
            $this->sendSuccessResponse($match);
            return;
        }

        // Handle non-dynamic URLs
        switch ($uri) {
            case '/leagues':
                $this->sendSuccessResponse($this->leagueService->getAllLeagues());
                break;
            case '/teams':
                $this->sendSuccessResponse($this->leagueService->getAllTeams());
                break;
            case '/matches':
                $this->sendSuccessResponse($this->leagueService->getAllMatches());
                break;
            default:
                $this->sendErrorResponse('Endpoint not found', 404);
                break;
        }
    }

    /**
     * @throws \JsonException
     */
    /**
     * @throws \JsonException
     */
    private function handlePutRequest(string $uri, array $data): void
    {
        // Extract ID from league URL
        if (preg_match('#^/leagues/(\d+)$#', $uri, $matches)) {
            $id = (int)$matches[1];
            $this->validateRequiredField($data, 'name', 'League name is required');
            $this->leagueService->updateLeague($id, $data['name']);
            $this->sendSuccessResponse('League updated successfully');
            return;
        }

        // Extract ID from team URL
        if (preg_match('#^/teams/(\d+)$#', $uri, $matches)) {
            $id = (int)$matches[1];
            $this->validateRequiredField($data, 'name', 'Team name is required');
            $this->validateRequiredField($data, 'strength', 'Team strength is required');
            $this->leagueService->updateTeam($id, $data['name'], $data['strength']);
            $this->sendSuccessResponse('Team updated successfully');
            return;
        }

        // Extract ID from match URL
        if (preg_match('#^/matches/(\d+)$#', $uri, $matches)) {
            $id = (int)$matches[1];
            $this->validateRequiredField($data, 'home_goals', 'Home goals is required');
            $this->validateRequiredField($data, 'away_goals', 'Away goals is required');
            $this->leagueService->updateMatch($id, $data['home_goals'], $data['away_goals']);
            $this->sendSuccessResponse('Match updated successfully');
            return;
        }

        $this->sendErrorResponse('Endpoint not found', 404);
    }

    /**
     * @throws \JsonException
     */
    /**
     * @throws \JsonException
     */
    private function handleDeleteRequest(string $uri): void
    {
        // Extract ID from league URL
        if (preg_match('#^/leagues/(\d+)$#', $uri, $matches)) {
            $id = (int)$matches[1];
            $this->leagueService->deleteLeague($id);
            $this->sendSuccessResponse('League deleted successfully');
            return;
        }

        // Extract ID from team URL
        if (preg_match('#^/teams/(\d+)$#', $uri, $matches)) {
            $id = (int)$matches[1];
            $this->leagueService->deleteTeam($id);
            $this->sendSuccessResponse('Team deleted successfully');
            return;
        }

        // Extract ID from match URL
        if (preg_match('#^/matches/(\d+)$#', $uri, $matches)) {
            $id = (int)$matches[1];
            $this->leagueService->deleteMatch($id);
            $this->sendSuccessResponse('Match deleted successfully');
            return;
        }

        $this->sendErrorResponse('Endpoint not found', 404);
    }
}