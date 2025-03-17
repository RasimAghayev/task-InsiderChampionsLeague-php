<?php

namespace App\Domain\Repository;

use App\Domain\Model\Team;

interface TeamRepositoryInterface
{
    /**
     * @param \App\Domain\Model\Team $team
     * @return void
     */
    public function save(Team $team): void;

    /**
     * @param int $id
     * @return \App\Domain\Model\Team|null
     */
    public function findById(int $id): ?Team;

    /**
     * @return array
     */
    public function findAll(): array;
}