<?php
class League {
    public array $teams = [];
    public array $matches = [];

    public function addTeam($team): void
    {
        $this->teams[] = $team;
    }

    public function playRound(): void
    {
        foreach ($this->teams as $i => $iValue) {
            for ($j = $i + 1, $jMax = count($this->teams); $j < $jMax; $j++) {
                $match = new FootballMatch($this->teams[$i], $this->teams[$j]);
                $this->matches[] = $match;
            }
        }
    }

    public function getLeagueTable(): void
    {
        usort($this->teams, function($a, $b) {
            if ($a->points == $b->points) {
                return $b->getGoalDifference() - $a->getGoalDifference();
            }
            return $b->points - $a->points;
        });

        echo "League Table\n";
        echo "| Team | PTS | P | W | D | L | GD |\n";
        foreach ($this->teams as $team) {
            echo "| {$team->name} | {$team->points} | {$team->played} | {$team->won} | {$team->drawn} | {$team->lost} | {$team->getGoalDifference()} |\n";
        }
    }
}