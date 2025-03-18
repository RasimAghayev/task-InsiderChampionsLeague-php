<?php

namespace App\Application\Services;

use App\Domain\Model\{League,Team,FootballMatch};
use App\Domain\Repository\{FootballMatchRepositoryInterface,LeagueRepositoryInterface,TeamRepositoryInterface};
use RuntimeException;

class LeagueService
{
    private LeagueRepositoryInterface $leagueRepository;
    private TeamRepositoryInterface $teamRepository;
    private FootballMatchRepositoryInterface $footballMatchRepository;
    private ?League $currentLeague = null;

    public function __construct(
        LeagueRepositoryInterface $leagueRepository,
        TeamRepositoryInterface $teamRepository,
        FootballMatchRepositoryInterface $footballMatchRepository
    ) {
        $this->leagueRepository = $leagueRepository;
        $this->teamRepository = $teamRepository;
        $this->footballMatchRepository = $footballMatchRepository;
        $this->currentLeague = $this->leagueRepository->findLatestLeague();
    }

    public function createLeague(string $name): void
    {
        $league = new League($name);
        $this->leagueRepository->save($league);
        $this->currentLeague = $league;
    }

    public function getLeagueById(int $id): ?League
    {
        return $this->leagueRepository->findById($id);
    }

    public function updateLeague(int $id, string $name): void
    {
        $league = $this->leagueRepository->findById($id);
        if ($league === null) {
            throw new RuntimeException("League not found");
        }
        $league->setName($name);
        $this->leagueRepository->save($league);
    }

    public function deleteLeague(int $id): void
    {
        $league = $this->leagueRepository->findById($id);
        if ($league === null) {
            throw new RuntimeException("League not found");
        }
        $this->leagueRepository->delete($league);
    }

    public function addTeam(string $name, int $strength): void
    {
        if ($this->currentLeague === null) {
            throw new RuntimeException("No league created yet.");
        }

        $team = new Team($name, $strength);
        $this->teamRepository->save($team);
        $this->currentLeague->addTeam($team);
    }

    public function getTeamById(int $id): ?Team
    {
        return $this->teamRepository->findById($id);
    }

    public function updateTeam(int $id, string $name, int $strength): void
    {
        $team = $this->teamRepository->findById($id);
        if ($team === null) {
            throw new RuntimeException("Team not found");
        }
        $team->setName($name);
        $team->setStrength($strength);
        $this->teamRepository->save($team);
    }

    public function deleteTeam(int $id): void
    {
        $team = $this->teamRepository->findById($id);
        if ($team === null) {
            throw new RuntimeException("Team not found");
        }
        $this->teamRepository->delete($team);
    }

    public function createMatch(int $homeTeamId, int $awayTeamId): void
    {
        $homeTeam = $this->teamRepository->findById($homeTeamId);
        $awayTeam = $this->teamRepository->findById($awayTeamId);

        if ($homeTeam === null || $awayTeam === null) {
            throw new RuntimeException("One or both teams not found");
        }

        $match = new FootballMatch($homeTeam, $awayTeam);
        $this->footballMatchRepository->save($match);
    }

    public function getMatchById(int $id): ?FootballMatch
    {
        return $this->footballMatchRepository->findById($id);
    }

    public function updateMatch(int $id, int $homeGoals, int $awayGoals): void
    {
        $match = $this->footballMatchRepository->findById($id);
        if ($match === null) {
            throw new RuntimeException("Match not found");
        }
        $match->play($homeGoals, $awayGoals);
        $this->footballMatchRepository->save($match);
    }

    public function deleteMatch(int $id): void
    {
        $match = $this->footballMatchRepository->findById($id);
        if ($match === null) {
            throw new RuntimeException("Match not found");
        }
        $this->footballMatchRepository->delete($match);
    }

    public function generateTeamsForLeague(int $numberOfTeams): void
    {
        if ($this->currentLeague === null) {
            throw new RuntimeException("No league created yet.");
        }

        for ($i = 1; $i <= $numberOfTeams; $i++) {
            $team = new Team("Team $i", random_int(50, 100));
            $this->teamRepository->save($team);
            $this->currentLeague->addTeam($team);
        }
    }

    public function generateMatchesForLeague(): void
    {
        if ($this->currentLeague === null) {
            throw new RuntimeException("No league created yet.");
        }

        $this->currentLeague->generateMatches();
        $matches = $this->currentLeague->getMatches();

        foreach ($matches as $match) {
            $this->footballMatchRepository->save($match);
        }
    }
}