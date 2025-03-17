<?php

class Team
{
    public $id;
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

    /**
     * @return void
     */
    public function save(): void
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO teams (name, strength) VALUES (:name, :strength) RETURNING id");
        $stmt->execute([
            'name' => $this->name,
            'strength' => $this->strength
        ]);
        $this->id = $stmt->fetchColumn();
    }

    /**
     * @return void
     */
    public function update(): void
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE teams SET points = :points, played = :played, won = :won, drawn = :drawn, lost = :lost, goals_for = :goals_for, goals_against = :goals_against WHERE id = :id");
        $stmt->execute([
            'points' => $this->points,
            'played' => $this->played,
            'won' => $this->won,
            'drawn' => $this->drawn,
            'lost' => $this->lost,
            'goals_for' => $this->goalsFor,
            'goals_against' => $this->goalsAgainst,
            'id' => $this->id
        ]);
    }
}