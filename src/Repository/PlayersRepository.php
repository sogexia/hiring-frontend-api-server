<?php

namespace App\Repository;

use InvalidArgumentException;

class PlayersRepository implements PlayersRepositoryI
{
    public function __construct(readonly string $playersDataPath)
    {
        if (!file_exists($playersDataPath)) {
            throw new InvalidArgumentException("$playersDataPath does not exist");
        }
    }

    public function findAll(): array
    {
        $json = json_decode(file_get_contents($this->playersDataPath));

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException("Content of $this->playersDataPath is not valid Json");
        }

        return $json;
    }

    public function save(array $players): void
    {
        file_put_contents($this->playersDataPath, json_encode($players, JSON_PRETTY_PRINT));
    }
}
