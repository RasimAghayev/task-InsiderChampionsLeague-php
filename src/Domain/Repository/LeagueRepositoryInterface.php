<?php
namespace App\Domain\Repository;

use App\Domain\Model\League;

interface LeagueRepositoryInterface
{
    /**
     * @param \App\Domain\Model\League $league
     * @return void
     */
    public function save(League $league): void;

    /**
     * @param int $id
     * @return \App\Domain\Model\League|null
     */
    public function findById(int $id): ?League;

    /**
     * @param string $name
     * @return \App\Domain\Model\League|null
     */
    public function findByName(string $name): ?League;

    /**
     * @return array
     */
    public function findAll(): array;
}