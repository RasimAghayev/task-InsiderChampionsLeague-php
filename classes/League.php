<?php
class League {
    public $teams = [];
    public $matches = [];

    /**
     * @param \Team $team
     * @return void
     */
    public function addTeam( Team $team): void
    {
        $team->save();
        $this->teams[] = $team;
    }

    /**
     * @return void
     * @throws \Random\RandomException
     */
    public function playRound(): void
    {
        foreach ($this->teams as $i => $iValue) {
            for ($j = $i + 1, $jMax = count($this->teams); $j < $jMax; $j++) {
                $match = new FootballMatch($this->teams[$i], $this->teams[$j]);
                $this->matches[] = $match;
                $match->save();
            }
        }

        foreach ($this->teams as $team) {
            $team->update();
        }
    }

    /**
     * @return void
     */
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