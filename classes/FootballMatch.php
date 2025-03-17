<?php
class FootballMatch {
    public $homeTeam;
    public $awayTeam;
    public $homeGoals;
    public $awayGoals;

    /**
     * @param $homeTeam
     * @param $awayTeam
     * @throws \Random\RandomException
     */
    public function __construct($homeTeam, $awayTeam) {
        $this->homeTeam = $homeTeam;
        $this->awayTeam = $awayTeam;
        $this->play();
    }

    /**
     * @return void
     * @throws \Random\RandomException
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

    /**
     * @return void
     */
    public function save(): void
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO matches (home_team_id, away_team_id, home_goals, away_goals) VALUES (:home_team_id, :away_team_id, :home_goals, :away_goals)");
        $stmt->execute([
            'home_team_id' => $this->homeTeam->id,
            'away_team_id' => $this->awayTeam->id,
            'home_goals' => $this->homeGoals,
            'away_goals' => $this->awayGoals
        ]);
    }
}