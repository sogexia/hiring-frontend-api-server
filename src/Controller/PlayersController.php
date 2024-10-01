<?php

namespace App\Controller;

use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PlayersController extends AbstractController
{
    #[Route('/api/v1/players', name: 'app_get_players', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $mock = $this->getPlayers();
        return $this->json($mock);
    }

    /**
     * API payload : '{"name":"player 3"}'
     */
    #[Route('/api/v1/players', name: 'app_add_players', methods: ['POST'])]
    public function add(RequestStack $request): JsonResponse
    {
        try {
            $response = Response::HTTP_OK;
            $name = $request->getCurrentRequest()->getPayload()->get('name');
            if (is_null($name) || trim($name) === '') {
                throw new InvalidArgumentException('Name cannot be empty.');
            }
            $mock = $this->getPlayers();
            $playersName = array_map(function ($player) {
                return $player->name;
            }, $mock);
            if (!in_array($name, $playersName)) {
                $mock[] = [
                    "id" => \count($mock)+1,
                    "name" => $name,
                ];

                file_put_contents($this->getPlayersPath(), json_encode($mock, JSON_PRETTY_PRINT));
                $response = Response::HTTP_CREATED;
            }
            return $this->json(array_pop($mock), $response);
        } catch (InvalidArgumentException $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function getPlayersPath(): string
    {
        return __DIR__ . '/../Mock/players.json';
    }

    private function getPlayers(): array
    {
        return json_decode(file_get_contents($this->getPlayersPath()));
    }
}

