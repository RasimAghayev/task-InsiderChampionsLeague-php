<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\FootballMatch;
use App\Domain\Repository\FootballMatchRepositoryInterface;
use App\Infrastructure\Database\DatabaseConnection;
use Override;
use PDO;

readonly class FootballMatchRepository implements FootballMatchRepositoryInterface
{

    private PDO $connection;
    private TeamRepository $teamRepository;
    private LeagueRepository $leagueRepository;

    /**
     *
     */
    public function __construct()
    {
        $this->connection = DatabaseConnection::getInstance()->getConnection();

        $this->teamRepository = new TeamRepository();
        $this->leagueRepository = new LeagueRepository();
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
                "INSERT INTO matches (home_team_id, away_team_id, home_goals, away_goals, played,league_id) 
                 VALUES (:home_team_id, :away_team_id, :home_goals, :away_goals, :played, :league_id) 
                 RETURNING id"
            );

            $stmt->execute([
                'home_team_id' => $match->getHomeTeam()->getId(),
                'away_team_id' => $match->getAwayTeam()->getId(),
                'home_goals' => $match->getHomeGoals(),
                'away_goals' => $match->getAwayGoals(),
                'played' => $match->isPlayed(),
                'league_id' => $match->getLeagueId(),
            ]);

            $match->setId($this->connection->lastInsertId());
        } else {
            // Update existing match
            $stmt = $this->connection->prepare(
                "UPDATE matches 
                 SET home_team_id = :home_team_id, away_team_id = :away_team_id, 
                     home_goals = :home_goals, away_goals = :away_goals, played = :played , league_id = :league_id 
                 WHERE id = :id"
            );

            $stmt->execute(params: [
                'id' => $match->getId(),
                'home_team_id' => $match->getHomeTeam()->getId(),
                'away_team_id' => $match->getAwayTeam()->getId(),
                'home_goals' => $match->getHomeGoals(),
                'away_goals' => $match->getAwayGoals(),
                'played' => $match->isPlayed(),
                'league_id' => $match->getLeagueId(),
            ]);
        }
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
        foreach ($data as $row) {
            $match = $this->getMatch($row);
            $matches[] = $match;
        }
        return $matches;
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
        return $this->getMatch($data);
    }


    /**
     * Find all matches from the database.
     *
     * @return \App\Domain\Model\FootballMatch[]
     */
    #[Override]
    public function findAll(): array
    {
        $stmt = $this->connection->query("SELECT * FROM matches");
        $data = $stmt->fetchAll();
        $matches = [];
        foreach ($data as $row) {
            $match = $this->getMatch($row);
            $matches[] = $match;
        }
        return $matches;
    }


    #[Override]
    public function delete(FootballMatch $match): void
    {
        $stmt = $this->connection->prepare("DELETE FROM matches WHERE id = :id");
        $stmt->execute(['id' => $match->getId()]);
    }

    private function getMatch(array $row): FootballMatch
    {
        $homeTeam = $this->teamRepository->findById((int)$row['home_team_id']);
        $awayTeam = $this->teamRepository->findById((int)$row['away_team_id']);
        $match = new FootballMatch($homeTeam, $awayTeam);
        $match->setId((int)$row['id']);
        $match->setLeagueId((int)$row['league_id']);
        $match->play((int)$row['home_goals'], (int)$row['away_goals']);
        return $match;
    }

}