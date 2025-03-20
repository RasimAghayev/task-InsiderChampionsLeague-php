<?php

namespace App\Domain\Model;

use JsonSerializable;

class League implements JsonSerializable
{
    private ?int $id = null;
    private string $name;
    private array $teams = [];
    private array $matches = [];
    private array $results = [];

    /**
     * @param string $name
     */

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return void
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param \App\Domain\Model\Team $team
     * @return void
     */
    public function addTeam(Team $team): void
    {
        $this->teams[] = $team;
    }

    /**
     * @return Team[]
     */
    public function getTeams(): array
    {
        return $this->teams;
    }

    /**
     * @return void
     */
    public function generateMatches(): void
    {
        $this->matches = [];

        $teamCount = count($this->teams);
        foreach ($this->teams as $i => $iValue) {
            for ($j = $i + 1; $j < $teamCount; $j++) {
                $this->matches[] = new FootballMatch($this->teams[$i], $this->teams[$j]);
            }
        }
    }

    /**
     * @return FootballMatch[]
     */
    public function getMatches(): array
    {
        return $this->matches;
    }

    /**
     * @throws \Random\RandomException
     */
    public function simulateWeeks(int $weeks): void
    {
        $this->results = [];
        for ($week = 1; $week <= $weeks; $week++) {
            foreach ($this->matches as $match) {
                $homeStrength = $match->getHomeTeam()->getStrength();
                $awayStrength = $match->getAwayTeam()->getStrength();

                $homeGoalsMin = max(0, $homeStrength - 10);
                $homeGoalsMax = min($homeStrength + 10, 5);
                $awayGoalsMin = max(0, $awayStrength - 10);
                $awayGoalsMax = min($awayStrength + 10, 5);

                if ($homeGoalsMin > $homeGoalsMax) {
                    $homeGoalsMin = $homeGoalsMax;
                }
                if ($awayGoalsMin > $awayGoalsMax) {
                    $awayGoalsMin = $awayGoalsMax;
                }

                // Generate random goals within valid ranges
                $homeGoals = random_int($homeGoalsMin, $homeGoalsMax);
                $awayGoals = random_int($awayGoalsMin, $awayGoalsMax);
                $match->play($homeGoals, $awayGoals);
                $this->results[] = [
                    'week' => $week,
                    'match' => $match,
                    'home_goals' => $homeGoals,
                    'away_goals' => $awayGoals,
                ];
            }
        }
    }

    public function getResults(): array
    {
        return $this->results;
    }

    public function getLeagueTable(): array
    {
        usort($this->teams, static function ($a, $b) {
            if ($a->getPoints() === $b->getPoints()) {
                return $b->getGoalDifference() <=> $a->getGoalDifference();
            }
            return $b->getPoints() <=> $a->getPoints();
        });

        return array_map(static function ($team) {
            return [
                'name' => $team->getName(),
                'points' => $team->getPoints(),
                'played' => $team->getPlayed(),
                'won' => $team->getWon(),
                'drawn' => $team->getDrawn(),
                'lost' => $team->getLost(),
                'goals_for' => $team->getGoalsFor(),
                'goals_against' => $team->getGoalsAgainst(),
                'goal_difference' => $team->getGoalDifference(),
            ];
        }, $this->teams);
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'teams' => array_map(static function (Team $team) {
                return $team->jsonSerialize(); // Assuming Team also implements JsonSerializable
            }, $this->teams),
            'matches' => array_map(static function (FootballMatch $match) {
                return $match->jsonSerialize(); // Assuming FootballMatch also implements JsonSerializable
            }, $this->matches),
            'results' => $this->results,
            'league_table' => $this->getLeagueTable(),
        ];
    }
}