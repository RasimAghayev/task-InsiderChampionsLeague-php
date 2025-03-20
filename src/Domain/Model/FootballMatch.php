<?php
namespace App\Domain\Model;

use InvalidArgumentException;
use JsonSerializable;
use RuntimeException;

class FootballMatch implements JsonSerializable
{
    private ?int $id = null;
    private Team $homeTeam;
    private Team $awayTeam;
    private int $leagueId = 0;
    private int $homeGoals = 0;
    private int $awayGoals = 0;
    private bool $played = false;

    /**
     * @param \App\Domain\Model\Team $homeTeam
     * @param \App\Domain\Model\Team $awayTeam
     */
    public function __construct(Team $homeTeam, Team $awayTeam)
    {
        $this->homeTeam = $homeTeam;
        $this->awayTeam = $awayTeam;
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
     * @return \App\Domain\Model\Team
     */
    public function getHomeTeam(): Team
    {
        return $this->homeTeam;
    }

    /**
     * @return \App\Domain\Model\Team
     */
    public function getAwayTeam(): Team
    {
        return $this->awayTeam;
    }

    /**
     * @return int
     */
    public function getHomeGoals(): int
    {
        return $this->homeGoals;
    }

    /**
     * @return int
     */
    public function getAwayGoals(): int
    {
        return $this->awayGoals;
    }

    /**
     * @return int
     */
    public function isPlayed(): int
    {
        return $this->played;
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
     * Validate goals before playing the match.
     *
     * @param int $homeGoals
     * @param int $awayGoals
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateGoals(int $homeGoals, int $awayGoals): void
    {
        if ($homeGoals < 0 || $awayGoals < 0) {
            throw new InvalidArgumentException("Goals cannot be negative.");
        }
    }

    /**
     * Update team statistics based on match result.
     *
     * @param string $result ('win', 'loss', 'draw')
     * @param int $homeGoals
     * @param int $awayGoals
     * @return void
     */
    private function updateTeamStatistics(string $result, int $homeGoals, int $awayGoals): void
    {
        switch ($result) {
            case 'win':
                $this->homeTeam->recordWin($homeGoals, $awayGoals);
                $this->awayTeam->recordLoss($awayGoals, $homeGoals);
                break;

            case 'loss':
                $this->homeTeam->recordLoss($homeGoals, $awayGoals);
                $this->awayTeam->recordWin($awayGoals, $homeGoals);
                break;

            case 'draw':
                $this->homeTeam->recordDraw($homeGoals, $awayGoals);
                $this->awayTeam->recordDraw($awayGoals, $homeGoals);
                break;

            default:
                throw new InvalidArgumentException("Invalid match result: {$result}");
        }
    }

    /**
     * Play the match and update team statistics.
     *
     * @param int $homeGoals
     * @param int $awayGoals
     * @return void
     * @throws \RuntimeException
     */
    public function play(int $homeGoals, int $awayGoals): void
    {
        if ($this->played == 1) {
            $this->homeGoals = 0;
            $this->awayGoals = 0;
            $this->played = 0;
        }

        // Validate goals
        $this->validateGoals($homeGoals, $awayGoals);

        // Update match results
        $this->homeGoals = $homeGoals;
        $this->awayGoals = $awayGoals;
        $this->played = 1;

        $this->updateTeamStatistics($this->determineResult(), $homeGoals, $awayGoals);
    }

    private function determineResult(): string
    {
        if ($this->homeGoals > $this->awayGoals) {
            return 'win';
        }

        if ($this->homeGoals < $this->awayGoals) {
            return 'loss';
        }

        return 'draw';
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
            'home_team' => $this->homeTeam->jsonSerialize(),
            'away_team' => $this->awayTeam->jsonSerialize(),
            'home_goals' => $this->homeGoals,
            'away_goals' => $this->awayGoals,
            'played' => $this->played
        ];
    }
}