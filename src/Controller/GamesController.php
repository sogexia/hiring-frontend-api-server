<?php

namespace App\Controller;

use App\Repository\GamesRepositoryI;
use App\Repository\PlayersRepositoryI;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GamesController extends AbstractController
{
    public function __construct(
        private GamesRepositoryI $gamesRepository,
        private PlayersRepositoryI $playersRepository
    ) {
    }

    #[Route('/api/v1/games', name: 'app_get_games', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $mock = $this->gamesRepository->findAll();
        return $this->json($mock);
    }

    #[Route('/api/v1/games', name: 'app_add_games', methods: ['POST'])]
    public function add(RequestStack $request): JsonResponse
    {
        try {
            $payload = $request->getCurrentRequest()->getPayload()->all();
            if (count($payload) <= 1) {
                throw new InvalidArgumentException('Missing players in game.');
            }
            foreach ($payload as $player) {
                foreach (['playerId', 'score'] as $mandatoryProperty) {
                    if (!array_key_exists($mandatoryProperty, $player)) {
                        throw new InvalidArgumentException(sprintf('playerId and score are mandatory for each player ; Missing %s.', $mandatoryProperty));
                    }
                }
            }
            $playersId = array_map(function ($player) {
                return $player["playerId"];
            }, $payload);
            if (array_unique($playersId) !== $playersId) {
                throw new InvalidArgumentException('Same players cannot be more than once in payload.');
            }

            $players = $this->playersRepository->findAll();
            foreach ($playersId as $id) {
                $playerFoundInMock = count(array_filter(array_map(function($player) {
                    return $player->id;
                }, $players), function ($playerIdMock) use ($id) {
                    return $playerIdMock === $id;
                }));
                if ($playerFoundInMock !== 1) {
                    throw new InvalidArgumentException(sprintf('Unknown player with id: %s.', $id));
                }
            }

            $games = $this->gamesRepository->findAll();
            $games[] = [
                "id" => \count($games)+1,
                "scores" => $payload
            ];
            $this->gamesRepository->save($games);
            return $this->json(array_pop($games), Response::HTTP_CREATED);
        } catch (InvalidArgumentException $exception) {
            return $this->json(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $exception) {
            return $this->json(['message' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

