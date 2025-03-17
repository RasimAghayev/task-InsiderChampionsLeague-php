<?php
class FootballMatch {
    public $homeTeam;
    public $awayTeam;
    public $homeGoals;
    public $awayGoals;

    /**
     * @param $homeTeam
     * @param $awayTeam
     */
    public function __construct($homeTeam, $awayTeam) {
        $this->homeTeam = $homeTeam;
        $this->awayTeam = $awayTeam;
        $this->play();
    }

    /**
     * @return void
     */
    private function play(): void
    {
        $this->homeGoals = $this->simulateGoals($this->homeTeam->strength);
        $this->awayGoals = $this->simulateGoals($this->awayTeam->strength);

        $this->homeTeam->goalsFor += $this->homeGoals;
        $this->homeTeam->goalsAgainst += $this->awayGoals;
        $this->awayTeam->goalsFor += $this->awayGoals;
        $this->awayTeam->goalsAgainst += $this->homeGoals;

        if ($this->homeGoals > $this->awayGoals) {
            $this->homeTeam->points += 3;
            ++$this->homeTeam->won;
            ++$this->awayTeam->lost;
        } elseif ($this->homeGoals < $this->awayGoals) {
            $this->awayTeam->points += 3;
            ++$this->awayTeam->won;
            ++$this->homeTeam->lost;
        } else {
            ++$this->homeTeam->points;
            ++$this->awayTeam->points;
            ++$this->homeTeam->drawn;
            ++$this->awayTeam->drawn;
        }

        ++$this->homeTeam->played;
        ++$this->awayTeam->played;
    }

    /**
     * @param $strength
     * @return int
     * @throws \Random\RandomException
     */
    private function simulateGoals($strength): int
    {
        return random_int(0, $strength);
    }
}