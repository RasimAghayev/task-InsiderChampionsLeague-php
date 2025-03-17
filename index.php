<?php

require_once 'vendor/autoload.php';

use App\Application\Services\LeagueService;
use App\Infrastructure\Repository\LeagueRepository;
use App\Infrastructure\Repository\TeamRepository;
use App\Infrastructure\Repository\FootballMatchRepository;

$leagueRepository = new LeagueRepository();
$teamRepository = new TeamRepository();
$footballMatchRepository = new FootballMatchRepository();

$leagueService = new LeagueService($leagueRepository, $teamRepository, $footballMatchRepository);

$leagueService->createLeague('Premier League');

$leagueService->addTeam('Chelsea', 5);
$leagueService->addTeam('Arsenal', 4);
$leagueService->addTeam('Manchester City', 4);
$leagueService->addTeam('Liverpool', 3);

$leagueService->playRound();
$leagueService->displayLeagueTable();