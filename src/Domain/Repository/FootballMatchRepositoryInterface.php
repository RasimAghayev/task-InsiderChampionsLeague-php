<?php

namespace App\Domain\Repository;

use App\Domain\Model\FootballMatch;

interface FootballMatchRepositoryInterface
{
    /**
     * @param \App\Domain\Model\FootballMatch $match
     * @return void
     */
    public function save(FootballMatch $match): void;
    /**
     * @param \App\Domain\Model\FootballMatch $match
     * @return void
     */
    public function delete(FootballMatch $match): void;

    /**
     * @param int $id
     * @return \App\Domain\Model\FootballMatch|null
     */
    public function findById(int $id): ?FootballMatch;

    /**
     * @param int $leagueId
     * @return array
     */
    public function findByLeagueId(int $leagueId): array;
}