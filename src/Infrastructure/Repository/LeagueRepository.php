<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\League;
use App\Domain\Repository\LeagueRepositoryInterface;
use App\Infrastructure\Database\DatabaseConnection;
use Override;
use PDO;

class LeagueRepository implements LeagueRepositoryInterface
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
     * @param \App\Domain\Model\League $league
     * @return void
     */
    #[Override] public function save(League $league): void
    {
        if ($league->getId() === null) {
            // Insert new league
            $stmt = $this->connection->prepare(
                "INSERT INTO leagues (name) VALUES (:name) RETURNING id"
            );

            $stmt->execute(['name' => $league->getName()]);
            $league->setId($this->connection->lastInsertId());
        } else {
            // Update existing league
            $stmt = $this->connection->prepare(
                "UPDATE leagues SET name = :name WHERE id = :id"
            );

            $stmt->execute([
                'id' => $league->getId(),
                'name' => $league->getName(),
            ]);
        }
    }

    /**
     * @param int $id
     * @return \App\Domain\Model\League|null
     */
    #[Override] public function findById(int $id): ?League
    {
        $stmt = $this->connection->prepare("SELECT * FROM leagues WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();

        if ($data === false) {
            return null;
        }

        return $this->extracted($data);
    }
    #[Override]
    public function delete(League $league): void
    {
        $stmt = $this->connection->prepare("DELETE FROM leagues WHERE id = :id");
        $stmt->execute(['id' => $league->getId()]);
    }

    #[Override]
    public function findLatestLeague(): ?League
    {
        $stmt = $this->connection->query("SELECT * FROM leagues ORDER BY id DESC LIMIT 1");
        $data = $stmt->fetch();

        if ($data === false) {
            return null;
        }

        $league = new League($data['name']);
        $league->setId($data['id']);
        return $league;
    }

    /**
     * @param mixed $data
     * @return \App\Domain\Model\League
     */
    public function extracted(mixed $data): League
    {
        $league = new League($data['name']);
        $league->setId($data['id']);

        // Load teams and matches for the league
        $teamRepository = new TeamRepository();
        $matchRepository = new FootballMatchRepository();

        $teams = $teamRepository->findAll();
        $matches = $matchRepository->findByLeagueId($league->getId());

        foreach ($teams as $team) {
            $league->addTeam($team);
        }

        foreach ($matches as $match) {
            $league->generateMatches();
        }

        return $league;
    }

    /**
     * @return array
     */
    #[Override] public function findAll(): array
    {
        $stmt = $this->connection->query("SELECT * FROM leagues");
        $data = $stmt->fetchAll();

        $leagues = [];
        $teamRepository = new TeamRepository();
        $matchRepository = new FootballMatchRepository();

        foreach ($data as $leagueData) {
            $league = new League($leagueData['name']);
            $league->setId($leagueData['id']);

            $teams = $teamRepository->findAll();
            $matches = $matchRepository->findByLeagueId($league->getId());

            foreach ($teams as $team) {
                $league->addTeam($team);
            }

            foreach ($matches as $match) {
                $league->generateMatches();
            }

            $leagues[] = $league;
        }

        return $leagues;
    }

    /**
     * @param string $name
     * @return \App\Domain\Model\League|null
     */
    #[Override] public function findByName(string $name): ?League
    {
        $stmt = $this->connection->prepare("SELECT * FROM leagues WHERE name = :name");
        $stmt->execute(['name' => $name]);
        $data = $stmt->fetch();

        if ($data === false) {
            return null;
        }

        return $this->extracted($data);
    }
}