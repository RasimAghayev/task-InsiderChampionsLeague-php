<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\FootballMatch;
use App\Domain\Repository\FootballMatchRepositoryInterface;
use App\Infrastructure\Database\DatabaseConnection;
use Override;

class FootballMatchRepository implements FootballMatchRepositoryInterface
{

    private \PDO $connection;

    /**
     *
     */
    public function __construct()
    {
        $this->connection = DatabaseConnection::getInstance()->getConnection();
    }

    /**
     * @param \App\Domain\Model\FootballMatch $match
     * @return void
     */
    #[Override] public function save(FootballMatch $match): void
    {
        if ($match->getId() === null) {
            // Insert new match
            $stmt = $this->connection->prepare(
                "INSERT INTO matches (home_team_id, away_team_id, home_goals, away_goals, played) 
                 VALUES (:home_team_id, :away_team_id, :home_goals, :away_goals, :played) 
                 RETURNING id"
            );

            $stmt->execute([
                'home_team_id' => $match->getHomeTeam()->getId(),
                'away_team_id' => $match->getAwayTeam()->getId(),
                'home_goals' => $match->getHomeGoals(),
                'away_goals' => $match->getAwayGoals(),
                'played' => $match->isPlayed(),
            ]);

            $match->setId($this->connection->lastInsertId());
        } else {
            // Update existing match
            $stmt = $this->connection->prepare(
                "UPDATE matches 
                 SET home_team_id = :home_team_id, away_team_id = :away_team_id, 
                     home_goals = :home_goals, away_goals = :away_goals, played = :played 
                 WHERE id = :id"
            );

            $stmt->execute([
                'id' => $match->getId(),
                'home_team_id' => $match->getHomeTeam()->getId(),
                'away_team_id' => $match->getAwayTeam()->getId(),
                'home_goals' => $match->getHomeGoals(),
                'away_goals' => $match->getAwayGoals(),
                'played' => $match->isPlayed(),
            ]);
        }
    }

    /**
     * @param int $id
     * @return \App\Domain\Model\FootballMatch|null
     */
    #[Override] public function findById(int $id): ?FootballMatch
    {
        $stmt = $this->connection->prepare("SELECT * FROM matches WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();

        if ($data === false) {
            return null;
        }

        $teamRepository = new TeamRepository();
        $homeTeam = $teamRepository->findById($data['home_team_id']);
        $awayTeam = $teamRepository->findById($data['away_team_id']);

        $match = new FootballMatch($homeTeam, $awayTeam);
        $match->setId($data['id']);
        $match->play($data['home_goals'], $data['away_goals']);

        return $match;
    }

    /**
     * @param int $leagueId
     * @return array
     */
    #[Override] public function findByLeagueId(int $leagueId): array
    {
        $stmt = $this->connection->prepare("SELECT * FROM matches WHERE league_id = :league_id");
        $stmt->execute(['league_id' => $leagueId]);
        $data = $stmt->fetchAll();

        $matches = [];
        $teamRepository = new TeamRepository();

        foreach ($data as $matchData) {
            $homeTeam = $teamRepository->findById($matchData['home_team_id']);
            $awayTeam = $teamRepository->findById($matchData['away_team_id']);

            $match = new FootballMatch($homeTeam, $awayTeam);
            $match->setId($matchData['id']);
            $match->play($matchData['home_goals'], $matchData['away_goals']);
            $matches[] = $match;
        }

        return $matches;
    }
}