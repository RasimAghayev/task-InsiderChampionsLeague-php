<?php

namespace App\Tests\Domain\Repository;

use App\Domain\Model\FootballMatch;
use App\Domain\Model\Team;
use App\Domain\Repository\FootballMatchRepositoryInterface;
use Override;
use PHPUnit\Framework\TestCase;

class FootballMatchRepositoryInterfaceTest extends TestCase
{
    private FootballMatchRepositoryInterface $footballMatchRepository;

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    #[Override] protected function setUp(): void
    {
        $this->footballMatchRepository = $this->createMock(FootballMatchRepositoryInterface::class);
    }

    /**
     * @return void
     */
    public function testSave(): void
    {
        $homeTeam = new Team('Chelsea', 5);
        $awayTeam = new Team('Arsenal', 4);
        $match = new FootballMatch($homeTeam, $awayTeam);

        $this->footballMatchRepository
            ->expects($this->once())
            ->method('save')
            ->with($match);

        $this->footballMatchRepository->save($match);
    }

    /**
     * @return void
     */
    public function testFindById(): void
    {
        $matchId = 1;
        $homeTeam = new Team('Chelsea', 5);
        $awayTeam = new Team('Arsenal', 4);
        $match = new FootballMatch($homeTeam, $awayTeam);
        $match->setId($matchId);

        $this->footballMatchRepository
            ->expects($this->once())
            ->method('findById')
            ->with($matchId)
            ->willReturn($match);

        $foundMatch = $this->footballMatchRepository->findById($matchId);

        $this->assertInstanceOf(FootballMatch::class, $foundMatch);
        $this->assertEquals($matchId, $foundMatch->getId());
    }

    /**
     * @return void
     */
    public function testFindByLeagueId(): void
    {
        $leagueId = 1;
        $homeTeam = new Team('Chelsea', 5);
        $awayTeam = new Team('Arsenal', 4);
        $match = new FootballMatch($homeTeam, $awayTeam);
        $match->setId(1);

        $this->footballMatchRepository
            ->expects($this->once())
            ->method('findByLeagueId')
            ->with($leagueId)
            ->willReturn([$match]);

        $matches = $this->footballMatchRepository->findByLeagueId($leagueId);

        $this->assertCount(1, $matches);
        $this->assertInstanceOf(FootballMatch::class, $matches[0]);
    }
}