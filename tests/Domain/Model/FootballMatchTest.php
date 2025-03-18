<?php

namespace App\Tests\Domain\Model;

use App\Domain\Model\FootballMatch;
use App\Domain\Model\Team;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class FootballMatchTest extends TestCase
{
    public function testMatchCreation(): void
    {
        $homeTeam = new Team('Chelsea', 5);
        $awayTeam = new Team('Arsenal', 4);
        $match = new FootballMatch($homeTeam, $awayTeam);

        $this->assertEquals($homeTeam, $match->getHomeTeam());
        $this->assertEquals($awayTeam, $match->getAwayTeam());
        $this->assertFalse($match->isPlayed());
    }

    public function testPlayMatch(): void
    {
        $homeTeam = new Team('Chelsea', 5);
        $awayTeam = new Team('Arsenal', 4);
        $match = new FootballMatch($homeTeam, $awayTeam);

        $match->play(2, 1);

        $this->assertTrue($match->isPlayed());
        $this->assertEquals(2, $match->getHomeGoals());
        $this->assertEquals(1, $match->getAwayGoals());

        // Check team statistics
        $this->assertEquals(3, $homeTeam->getPoints());
        $this->assertEquals(0, $awayTeam->getPoints());
    }

    public function testPlayMatchAlreadyPlayed(): void
    {
        $homeTeam = new Team('Chelsea', 5);
        $awayTeam = new Team('Arsenal', 4);
        $match = new FootballMatch($homeTeam, $awayTeam);

        $match->play(2, 1);

        // Try to play the match again
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Match has already been played');

        $match->play(3, 2);
    }
}