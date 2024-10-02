<?php

namespace App\Repository;

interface PlayersRepositoryI
{
    public function findAll(): array;

    public function save(array $players): void;
}
