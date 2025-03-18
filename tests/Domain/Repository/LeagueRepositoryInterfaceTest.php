<?php

namespace App\Tests\Domain\Repository;

use App\Domain\Model\League;
use App\Domain\Repository\LeagueRepositoryInterface;
use Override;
use PHPUnit\Framework\TestCase;

class LeagueRepositoryInterfaceTest extends TestCase
{
    private LeagueRepositoryInterface $leagueRepository;

    /**
     * @return void
     */
    public function testSave(): void
    {
        $league = new League('Premier League');

        $this->leagueRepository
            ->expects($this->once())
            ->method('save')
            ->with($league);

        $this->leagueRepository->save($league);
    }

    /**
     * @return void
     */
    public function testFindById(): void
    {
        $leagueId = 1;
        $league = new League('Premier League');
        $league->setId($leagueId);

        $this->leagueRepository
            ->expects($this->once())
            ->method('findById')
            ->with($leagueId)
            ->willReturn($league);

        $foundLeague = $this->leagueRepository->findById($leagueId);

        $this->assertInstanceOf(League::class, $foundLeague);
        $this->assertEquals($leagueId, $foundLeague->getId());
    }

    /**
     * @return void
     */
    public function testFindByName(): void
    {
        $leagueName = 'Premier League';
        $league = new League($leagueName);
        $league->setId(1);

        $this->leagueRepository
            ->expects($this->once())
            ->method('findByName')
            ->with($leagueName)
            ->willReturn($league);

        $foundLeague = $this->leagueRepository->findByName($leagueName);

        $this->assertInstanceOf(League::class, $foundLeague);
        $this->assertEquals($leagueName, $foundLeague->getName());
    }

    /**
     * @return void
     */
    public function testFindAll(): void
    {
        $league1 = new League('Premier League');
        $league1->setId(1);

        $league2 = new League('La Liga');
        $league2->setId(2);

        $this->leagueRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$league1, $league2]);

        $leagues = $this->leagueRepository->findAll();

        $this->assertCount(2, $leagues);
        $this->assertInstanceOf(League::class, $leagues[0]);
        $this->assertInstanceOf(League::class, $leagues[1]);
    }

    /**
     * @return void
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    #[Override] protected function setUp(): void
    {
        $this->leagueRepository = $this->createMock(LeagueRepositoryInterface::class);
    }
}