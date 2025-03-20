<?php

namespace App\Domain\Model;

use InvalidArgumentException;
use JsonSerializable;

class Team implements JsonSerializable
{
    private ?int $id = null;
    private string $name;
    private int $strength;
    private int $points = 0;
    private int $played = 0;
    private int $won = 0;
    private int $drawn = 0;
    private int $lost = 0;
    private int $goalsFor = 0;
    private int $goalsAgainst = 0;
    private int $leagueId=0;
    /**
     * @param string $name
     * @param int $strength
     */
    public function __construct(string $name, int $strength)
    {
        $this->name = $name;
        $this->strength = $strength;
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
     * @return int
     */
    public function getStrength(): int
    {
        return $this->strength;
    }

    /**
     * @param int $strength
     * @return void
     */

    public function setStrength(int $strength): void
    {
        $this->strength = $strength;
    }


    /**
     * @return int
     */
    public function getPoints(): int
    {
        return $this->points;
    }

    /**
     * @param int $points
     * @return void
     */
    public function setPoints(int $points): void
    {
        $this->points = $points;
    }

    /**
     * @return int
     */
    public function getPlayed(): int
    {
        return $this->played;
    }

    /**
     * @param int $played
     * @return void
     */
    public function setPlayed(int $played): void
    {
        $this->played = $played;
    }

    /**
     * @return int
     */
    public function getWon(): int
    {
        return $this->won;
    }

    /**
     * @param int $won
     * @return void
     */
    public function setWon(int $won): void
    {
        $this->won = $won;
    }

    /**
     * @return int
     */
    public function getDrawn(): int
    {
        return $this->drawn;
    }

    /**
     * @param int $drawn
     * @return void
     */
    public function setDrawn(int $drawn): void
    {
        $this->drawn = $drawn;
    }

    /**
     * @return int
     */
    public function getLost(): int
    {
        return $this->lost;
    }

    /**
     * @param int $lost
     * @return void
     */
    public function setLost(int $lost): void
    {
        $this->lost = $lost;
    }

    /**
     * @return int
     */
    public function getGoalsFor(): int
    {
        return $this->goalsFor;
    }

    /**
     * @param int $goalsFor
     * @return void
     */
    public function setGoalsFor(int $goalsFor): void
    {
        $this->goalsFor = $goalsFor;
    }

    /**
     * @return int
     */
    public function getGoalsAgainst(): int
    {
        return $this->goalsAgainst;
    }

    /**
     * @param int $goalsAgainst
     * @return void
     */
    public function setGoalsAgainst(int $goalsAgainst): void
    {
        $this->goalsAgainst = $goalsAgainst;
    }

    /**
     * @return int
     */
    public function getLeagueId(): int
    {
        return $this->leagueId;
    }

    /**
     * @param int $leagueId
     * @return void
     */
    public function setLeagueId(int $leagueId): void
    {
        $this->leagueId = $leagueId;
    }
    /**
     * @return int
     */
    public function getGoalDifference(): int
    {
        return $this->goalsFor - $this->goalsAgainst;
    }

    /**
     * Record a win for the team.
     *
     * @param int $goalsFor
     * @param int $goalsAgainst
     * @return void
     */
    public function recordWin(int $goalsFor, int $goalsAgainst): void
    {
        $this->updateStatistics($goalsFor, $goalsAgainst, 'win');
    }

    /**
     * Record a loss for the team.
     *
     * @param int $goalsFor
     * @param int $goalsAgainst
     * @return void
     */
    public function recordLoss(int $goalsFor, int $goalsAgainst): void
    {
        $this->updateStatistics($goalsFor, $goalsAgainst, 'loss');
    }

    /**
     * Record a draw for the team.
     *
     * @param int $goalsFor
     * @param int $goalsAgainst
     * @return void
     */
    public function recordDraw(int $goalsFor, int $goalsAgainst): void
    {
        $this->updateStatistics($goalsFor, $goalsAgainst, 'draw');
    }

    /**
     * Update team statistics based on match result.
     *
     * @param int $goalsFor
     * @param int $goalsAgainst
     * @param string $result ('win', 'loss', 'draw')
     * @return void
     */
    private function updateStatistics(int $goalsFor, int $goalsAgainst, string $result): void
    {
        $this->validateGoals($goalsFor, $goalsAgainst);
        $this->played++;
        $this->goalsFor += $goalsFor;
        $this->goalsAgainst += $goalsAgainst;

        switch ($result) {
            case 'win':
                $this->points += 3;
                $this->won++;
                break;

            case 'loss':
                $this->lost++;
                break;

            case 'draw':
                $this->points++;
                $this->drawn++;
                break;

            default:
                throw new InvalidArgumentException("Invalid match result: {$result}");
        }
    }

    /**
     * Validate goals before updating statistics.
     *
     * @param int $goalsFor
     * @param int $goalsAgainst
     * @return void
     */
    private function validateGoals(int $goalsFor, int $goalsAgainst): void
    {
        if ($goalsFor < 0 || $goalsAgainst < 0) {
            throw new InvalidArgumentException("Goals cannot be negative.");
        }
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
            'strength' => $this->strength,
            'points' => $this->points,
            'played' => $this->played,
            'won' => $this->won,
            'drawn' => $this->drawn,
            'lost' => $this->lost,
            'goals_for' => $this->goalsFor,
            'goals_against' => $this->goalsAgainst,
            'goal_difference' => $this->getGoalDifference(),
            'league_id' => $this->leagueId,
        ];
    }
}