<?php

namespace App\Repository;

interface GamesRepositoryI
{
    public function findAll(): array;

    public function save(array $games): void;
}
