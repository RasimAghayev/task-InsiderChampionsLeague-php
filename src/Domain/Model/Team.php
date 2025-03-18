<?php
namespace App\Domain\Model;

class Team
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
     * @return int
     */
    public function getStrength(): int
    {
        return $this->strength;
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
    public function getGoalDifference(): int
    {
        return $this->goalsFor - $this->goalsAgainst;
    }

    /**
     * @param int $goalsFor
     * @param int $goalsAgainst
     * @return void
     */
    public function recordWin(int $goalsFor, int $goalsAgainst): void
    {
        $this->points += 3;
        $this->won++;
        $this->played++;
        $this->goalsFor += $goalsFor;
        $this->goalsAgainst += $goalsAgainst;
    }

    /**
     * @param int $goalsFor
     * @param int $goalsAgainst
     * @return void
     */
    public function recordLoss(int $goalsFor, int $goalsAgainst): void
    {
        $this->lost++;
        $this->played++;
        $this->goalsFor += $goalsFor;
        $this->goalsAgainst += $goalsAgainst;
    }

    /**
     * @param int $goalsFor
     * @param int $goalsAgainst
     * @return void
     */
    public function recordDraw(int $goalsFor, int $goalsAgainst): void
    {
        ++$this->points;
        $this->drawn++;
        $this->played++;
        $this->goalsFor += $goalsFor;
        $this->goalsAgainst += $goalsAgainst;
    }
}