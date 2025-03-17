<?php

namespace App\Domain\Model;

class League
{
    private ?int $id = null;
    private string $name;
    private array $teams = [];
    private array $matches = [];

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
     * @return Team[]
     */
    public function getLeagueTable(): array
    {
        $sortedTeams = $this->teams;

        usort($sortedTeams, static function (Team $a, Team $b) {
            // Sort by points (descending)
            if ($a->getPoints() !== $b->getPoints()) {
                return $b->getPoints() - $a->getPoints();
            }

            // If points are equal, sort by goal difference (descending)
            if ($a->getGoalDifference() !== $b->getGoalDifference()) {
                return $b->getGoalDifference() - $a->getGoalDifference();
            }

            // If goal difference is equal, sort by goals scored (descending)
            if ($a->getGoalsFor() !== $b->getGoalsFor()) {
                return $b->getGoalsFor() - $a->getGoalsFor();
            }

            // If all criteria are equal, sort alphabetically by name
            return strcmp($a->getName(), $b->getName());
        });

        return $sortedTeams;
    }
}