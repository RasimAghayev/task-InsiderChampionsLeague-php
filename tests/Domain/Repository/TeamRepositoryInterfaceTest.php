<?php

namespace App\Tests\Domain\Repository;

use App\Domain\Model\Team;
use App\Domain\Repository\TeamRepositoryInterface;
use Override;
use PHPUnit\Framework\TestCase;

class TeamRepositoryInterfaceTest extends TestCase
{
    private TeamRepositoryInterface $teamRepository;

    /**
     * @return void
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    #[Override] protected function setUp(): void
    {
        $this->teamRepository = $this->createMock(TeamRepositoryInterface::class);
    }

    /**
     * @return void
     */
    public function testSave(): void
    {
        $team = new Team('Chelsea', 5);

        $this->teamRepository
            ->expects($this->once())
            ->method('save')
            ->with($team);

        $this->teamRepository->save($team);
    }

    /**
     * @return void
     */
    public function testFindById(): void
    {
        $teamId = 1;
        $team = new Team('Chelsea', 5);
        $team->setId($teamId);

        $this->teamRepository
            ->expects($this->once())
            ->method('findById')
            ->with($teamId)
            ->willReturn($team);

        $foundTeam = $this->teamRepository->findById($teamId);

        $this->assertInstanceOf(Team::class, $foundTeam);
        $this->assertEquals($teamId, $foundTeam->getId());
    }

    /**
     * @return void
     */
    public function testFindAll(): void
    {
        $team1 = new Team('Chelsea', 5);
        $team1->setId(1);

        $team2 = new Team('Arsenal', 4);
        $team2->setId(2);

        $this->teamRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$team1, $team2]);

        $teams = $this->teamRepository->findAll();

        $this->assertCount(2, $teams);
        $this->assertInstanceOf(Team::class, $teams[0]);
        $this->assertInstanceOf(Team::class, $teams[1]);
    }
}