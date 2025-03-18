<?php

namespace App\Domain\Model;

class FootballMatch
{
    private ?int $id = null;
    private Team $homeTeam;
    private Team $awayTeam;
    private int $homeGoals=0;
    private int $awayGoals=0;
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
     * @return bool
     */
    public function isPlayed(): bool
    {
        return $this->played;
    }

    /**
     * @param int $homeGoals
     * @param int $awayGoals
     * @return void
     */
    public function play(int $homeGoals, int $awayGoals): void
    {
        if ($this->played) {
            throw new \RuntimeException("Match has already been played");
        }

        $this->homeGoals = $homeGoals;
        $this->awayGoals = $awayGoals;
        $this->played = true;

        // Update team statistics
        if ($homeGoals > $awayGoals) {
            $this->homeTeam->recordWin($homeGoals, $awayGoals);
            $this->awayTeam->recordLoss($awayGoals, $homeGoals);
        } elseif ($homeGoals < $awayGoals) {
            $this->homeTeam->recordLoss($homeGoals, $awayGoals);
            $this->awayTeam->recordWin($awayGoals, $homeGoals);
        } else {
            $this->homeTeam->recordDraw($homeGoals, $awayGoals);
            $this->awayTeam->recordDraw($awayGoals, $homeGoals);
        }
    }
}