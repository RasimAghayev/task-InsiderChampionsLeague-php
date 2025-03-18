<?php

namespace App\Application\Services;

use App\Domain\Model\League;
use App\Domain\Model\Team;
use App\Domain\Repository\FootballMatchRepositoryInterface;
use App\Domain\Repository\LeagueRepositoryInterface;
use App\Domain\Repository\TeamRepositoryInterface;

class LeagueService
{
    private LeagueRepositoryInterface $leagueRepository;
    private TeamRepositoryInterface $teamRepository;
    private FootballMatchRepositoryInterface $footballMatchRepository;
    private ?League $currentLeague = null;

    public function __construct(
        LeagueRepositoryInterface        $leagueRepository,
        TeamRepositoryInterface          $teamRepository,
        FootballMatchRepositoryInterface $footballMatchRepository
    )
    {
        $this->leagueRepository = $leagueRepository;
        $this->teamRepository = $teamRepository;
        $this->footballMatchRepository = $footballMatchRepository;
    }

    /**
     * @param string $name
     * @return void
     */
    public function createLeague(string $name): void
    {
        $league = new League($name);
        $this->leagueRepository->save($league);
        $this->currentLeague = $league;
    }

    /**
     * @param string $name
     * @param int $strength
     * @return void
     */
    public function addTeam(string $name, int $strength): void
    {
        if ($this->currentLeague === null) {
            throw new \RuntimeException("No league created yet.");
        }

        $team = new Team($name, $strength);
        $this->teamRepository->save($team);
        $this->currentLeague->addTeam($team);
    }

    /**
     * @return void
     * @throws \Random\RandomException
     */
    public function playRound(): void
    {
        if ($this->currentLeague === null) {
            throw new \RuntimeException("No league created yet.");
        }

        $matches = $this->currentLeague->getMatches();
        if (empty($matches)) {
            $this->currentLeague->generateMatches();
            $matches = $this->currentLeague->getMatches();
        }

        foreach ($matches as $match) {
            if (!$match->isPlayed()) {
                $homeGoals = random_int(0, 5);
                $awayGoals = random_int(0, 5);
                $match->play($homeGoals, $awayGoals);
                $this->footballMatchRepository->save($match);
            }
        }
    }

    /**
     * @return void
     */
    public function displayLeagueTable(): void
    {
        if ($this->currentLeague === null) {
            throw new \RuntimeException("No league created yet.");
        }

        $teams = $this->currentLeague->getLeagueTable();
        echo "League Table:\n";
        echo "-----------------------------------------\n";
        echo "| Team            | Pld | W | D | L | GF | GA | GD | Pts |\n";
        echo "-----------------------------------------\n";

        foreach ($teams as $team) {
            printf(
                "| %-15s | %3d | %1d | %1d | %1d | %2d | %2d | %2d | %3d |\n",
                $team->getName(),
                $team->getPlayed(),
                $team->getWon(),
                $team->getDrawn(),
                $team->getLost(),
                $team->getGoalsFor(),
                $team->getGoalsAgainst(),
                $team->getGoalDifference(),
                $team->getPoints()
            );
        }

        echo "-----------------------------------------\n";
    }
}