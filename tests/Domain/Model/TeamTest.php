<?php

namespace App\Tests\Domain\Model;

use App\Domain\Model\Team;
use PHPUnit\Framework\TestCase;

class TeamTest extends TestCase
{
    public function testTeamCreation()
    {
        $team = new Team('Chelsea', 5);
        $this->assertEquals('Chelsea', $team->getName());
        $this->assertEquals(5, $team->getStrength());
    }

    public function testRecordWin()
    {
        $team = new Team('Chelsea', 5);
        $team->recordWin(2, 1);

        $this->assertEquals(3, $team->getPoints());
        $this->assertEquals(1, $team->getWon());
        $this->assertEquals(1, $team->getPlayed());
        $this->assertEquals(2, $team->getGoalsFor());
        $this->assertEquals(1, $team->getGoalsAgainst());
    }

    public function testRecordLoss()
    {
        $team = new Team('Chelsea', 5);
        $team->recordLoss(1, 2);

        $this->assertEquals(0, $team->getPoints());
        $this->assertEquals(1, $team->getLost());
        $this->assertEquals(1, $team->getPlayed());
        $this->assertEquals(1, $team->getGoalsFor());
        $this->assertEquals(2, $team->getGoalsAgainst());
    }

    public function testRecordDraw()
    {
        $team = new Team('Chelsea', 5);
        $team->recordDraw(1, 1);

        $this->assertEquals(1, $team->getPoints());
        $this->assertEquals(1, $team->getDrawn());
        $this->assertEquals(1, $team->getPlayed());
        $this->assertEquals(1, $team->getGoalsFor());
        $this->assertEquals(1, $team->getGoalsAgainst());
    }
}