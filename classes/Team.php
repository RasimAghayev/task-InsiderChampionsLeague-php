<?php

class Team
{
    public $name;
    public $strength;
    public int $points = 0;
    public int $played = 0;
    public int $won = 0;
    public int $drawn = 0;
    public int $lost = 0;
    public int $goalsFor = 0;
    public int $goalsAgainst = 0;

    /**
     * @param $name
     * @param $strength
     */
    public function __construct($name, $strength)
    {
        $this->name = $name;
        $this->strength = $strength;
    }

    /**
     * @return int
     */
    public function getGoalDifference(): int
    {
        return $this->goalsFor - $this->goalsAgainst;
    }
}