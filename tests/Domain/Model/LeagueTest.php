<?php

namespace App\Tests\Domain\Model;

use App\Domain\Model\League;
use App\Domain\Model\Team;
use PHPUnit\Framework\TestCase;

class LeagueTest extends TestCase
{
    public function testLeagueCreation(): void
    {
        $league = new League('Premier League');
        $this->assertEquals('Premier League', $league->getName());
        $this->assertEmpty($league->getTeams());
        $this->assertEmpty($league->getMatches());
    }

    public function testAddTeam(): void
    {
        $league = new League('Premier League');
        $team = new Team('Chelsea', 5);

        $league->addTeam($team);

        $this->assertCount(1, $league->getTeams());
        $this->assertEquals($team, $league->getTeams()[0]);
    }

    public function testGenerateMatches(): void
    {
        $league = new League('Premier League');
        $team1 = new Team('Chelsea', 5);
        $team2 = new Team('Arsenal', 4);

        $league->addTeam($team1);
        $league->addTeam($team2);

        $league->generateMatches();

        $matches = $league->getMatches();
        $this->assertCount(1, $matches);

        $match = $matches[0];
        $this->assertEquals($team1, $match->getHomeTeam());
        $this->assertEquals($team2, $match->getAwayTeam());
    }

    public function testGetLeagueTable(): void
    {
        $league = new League('Premier League');
        $team1 = new Team('Chelsea', 5);
        $team2 = new Team('Arsenal', 4);

        $league->addTeam($team1);
        $league->addTeam($team2);

        $league->generateMatches();

        // Play a match
        $matches = $league->getMatches();
        $matches[0]->play(2, 1);

        $leagueTable = $league->getLeagueTable();
        $this->assertCount(2, $leagueTable);

        // Chelsea should be first
        $this->assertEquals('Chelsea', $leagueTable[0]->getName());
        $this->assertEquals(3, $leagueTable[0]->getPoints());

        // Arsenal should be second
        $this->assertEquals('Arsenal', $leagueTable[1]->getName());
        $this->assertEquals(0, $leagueTable[1]->getPoints());
    }
}