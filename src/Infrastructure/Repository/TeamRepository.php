<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\Team;
use App\Domain\Repository\TeamRepositoryInterface;
use App\Infrastructure\Database\DatabaseConnection;
use Override;
use PDO;

class TeamRepository implements TeamRepositoryInterface
{
    private PDO $connection;

    /**
     *
     */
    public function __construct()
    {
        $this->connection = DatabaseConnection::getInstance()->getConnection();
    }

    /**
     * @param \App\Domain\Model\Team $team
     * @return void
     */
    #[Override] public function save(Team $team): void
    {
        if ($team->getId() === null) {
            // Insert new team
            $stmt = $this->connection->prepare(
                "INSERT INTO teams (name, strength, points, played, won, drawn, lost, goals_for, goals_against) 
                 VALUES (:name, :strength, :points, :played, :won, :drawn, :lost, :goals_for, :goals_against) 
                 RETURNING id"
            );

            $stmt->execute([
                'name' => $team->getName(),
                'strength' => $team->getStrength(),
                'points' => $team->getPoints(),
                'played' => $team->getPlayed(),
                'won' => $team->getWon(),
                'drawn' => $team->getDrawn(),
                'lost' => $team->getLost(),
                'goals_for' => $team->getGoalsFor(),
                'goals_against' => $team->getGoalsAgainst(),
            ]);

            $team->setId($this->connection->lastInsertId());
        } else {
            // Update existing team
            $stmt = $this->connection->prepare(
                "UPDATE teams 
                 SET name = :name, strength = :strength, points = :points, played = :played, 
                     won = :won, drawn = :drawn, lost = :lost, goals_for = :goals_for, goals_against = :goals_against 
                 WHERE id = :id"
            );

            $stmt->execute([
                'id' => $team->getId(),
                'name' => $team->getName(),
                'strength' => $team->getStrength(),
                'points' => $team->getPoints(),
                'played' => $team->getPlayed(),
                'won' => $team->getWon(),
                'drawn' => $team->getDrawn(),
                'lost' => $team->getLost(),
                'goals_for' => $team->getGoalsFor(),
                'goals_against' => $team->getGoalsAgainst(),
            ]);
        }
    }

    /**
     * @param int $id
     * @return \App\Domain\Model\Team|null
     */
    public function findById(int $id): ?Team
    {
        $stmt = $this->connection->prepare("SELECT * FROM teams WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();

        if ($data === false) {
            return null;
        }

        return $this->getTeam($data);
    }

    /**
     * @param mixed $data
     * @return \App\Domain\Model\Team
     */
    public function getTeam(mixed $data): Team
    {
        $team = new Team($data['name'], $data['strength']);
        $team->setId($data['id']);
        $team->setPoints($data['points']);
        $team->setPlayed($data['played']);
        $team->setWon($data['won']);
        $team->setDrawn($data['drawn']);
        $team->setLost($data['lost']);
        $team->setGoalsFor($data['goals_for']);
        $team->setGoalsAgainst($data['goals_against']);
        return $team;
    }

    public function findAll(): array
    {
        $stmt = $this->connection->query("SELECT * FROM teams");
        $data = $stmt->fetchAll();

        $teams = [];
        foreach ($data as $teamData) {
            $team = $this->getTeam($teamData);
            $teams[] = $team;
        }

        return $teams;
    }
    #[Override]
    public function delete(Team $team): void
    {
        $stmt = $this->connection->prepare("DELETE FROM teams WHERE id = :id");
        $stmt->execute(['id' => $team->getId()]);
    }
}