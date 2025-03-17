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
     * @return int
     */
    public function getPlayed(): int
    {
        return $this->played;
    }

    /**
     * @return int
     */
    public function getWon(): int
    {
        return $this->won;
    }

    /**
     * @return int
     */
    public function getDrawn(): int
    {
        return $this->drawn;
    }

    /**
     * @return int
     */
    public function getLost(): int
    {
        return $this->lost;
    }

    /**
     * @return int
     */
    public function getGoalsFor(): int
    {
        return $this->goalsFor;
    }

    /**
     * @return int
     */
    public function getGoalsAgainst(): int
    {
        return $this->goalsAgainst;
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
