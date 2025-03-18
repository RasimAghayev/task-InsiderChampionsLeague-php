<?php

namespace App\Tests\Application\Services;

use App\Application\Services\LeagueService;
use App\Domain\Model\League;
use App\Domain\Model\Team;
use App\Domain\Repository\FootballMatchRepositoryInterface;
use App\Domain\Repository\LeagueRepositoryInterface;
use App\Domain\Repository\TeamRepositoryInterface;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class LeagueServiceTest extends TestCase
{
    private LeagueService $leagueService;
    private LeagueRepositoryInterface $leagueRepository;
    private TeamRepositoryInterface $teamRepository;
    private FootballMatchRepositoryInterface $footballMatchRepository;

    /**
     * @return void
     */
    public function testCreateLeague(): void
    {
        $leagueName = 'Premier League';

        $this->leagueRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function ($league) use ($leagueName) {
                return $league instanceof League && $league->getName() === $leagueName;
            }));

        $this->leagueService->createLeague($leagueName);

        $this->assertNotNull($this->leagueService);
    }

    /**
     * @return void
     */
    public function testAddTeam(): void
    {
        $leagueName = 'Premier League';
        $teamName = 'Chelsea';
        $teamStrength = 5;

        $this->leagueService->createLeague($leagueName);

        $this->teamRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function ($team) use ($teamName, $teamStrength) {
                return $team instanceof Team &&
                    $team->getName() === $teamName &&
                    $team->getStrength() === $teamStrength;
            }));

        $this->leagueService->addTeam($teamName, $teamStrength);
    }

    /**
     * @return void
     */
    public function testAddTeamWithoutLeague(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('No league created yet.');

        $this->leagueService->addTeam('Chelsea', 5);
    }

    /**
     * @return void
     * @throws \Random\RandomException
     */
    public function testPlayRound(): void
    {
        $leagueName = 'Premier League';

        $this->leagueService->createLeague($leagueName);

        $this->leagueService->addTeam('Chelsea', 5);
        $this->leagueService->addTeam('Arsenal', 4);

        $this->footballMatchRepository
            ->expects($this->atLeastOnce())
            ->method('save');

        $this->leagueService->playRound();
    }

    /**
     * @return void
     * @throws \Random\RandomException
     */
    public function testPlayRoundWithoutLeague(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('No league created yet.');

        $this->leagueService->playRound();
    }

    /**
     * @return void
     * @throws \Random\RandomException
     */
    public function testDisplayLeagueTable(): void
    {
        $leagueName = 'Premier League';

        $this->leagueService->createLeague($leagueName);

        $this->leagueService->addTeam('Chelsea', 5);
        $this->leagueService->addTeam('Arsenal', 4);

        $this->leagueService->playRound();

        $this->expectOutputRegex('/League Table/');
        $this->leagueService->displayLeagueTable();
    }

    /**
     * @return void
     */
    public function testDisplayLeagueTableWithoutLeague(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('No league created yet.');

        $this->leagueService->displayLeagueTable();
    }

    /**
     * @return void
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    protected function setUp(): void
    {
        $this->leagueRepository = $this->createMock(LeagueRepositoryInterface::class);
        $this->teamRepository = $this->createMock(TeamRepositoryInterface::class);
        $this->footballMatchRepository = $this->createMock(FootballMatchRepositoryInterface::class);

        $this->leagueService = new LeagueService(
            $this->leagueRepository,
            $this->teamRepository,
            $this->footballMatchRepository
        );
    }
}