<?php

namespace App\Repository;

use InvalidArgumentException;

class GamesRepository implements GamesRepositoryI
{
    public function __construct(readonly string $gamesDataPath)
    {
        if (!file_exists($gamesDataPath)) {
            throw new InvalidArgumentException("$gamesDataPath does not exist");
        }
    }

    public function findAll(): array
    {
        $json = json_decode(file_get_contents($this->gamesDataPath));

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException("Content of $this->gamesDataPath is not valid Json");
        }

        return $json;
    }

    public function save(array $games): void
    {
        file_put_contents($this->gamesDataPath, json_encode($games, JSON_PRETTY_PRINT));
    }
}
