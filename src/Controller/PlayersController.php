<?php

namespace App\Controller;

use App\Repository\PlayersRepositoryI;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PlayersController extends AbstractController
{
    public function __construct(
        private PlayersRepositoryI $playersRepository
    ) {
    }

    #[Route('/api/v1/players', name: 'app_get_players', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $players = $this->playersRepository->findAll();
        return $this->json($players);
    }

    #[Route('/api/v1/players', name: 'app_add_players', methods: ['POST'])]
    public function add(RequestStack $request): JsonResponse
    {
        try {
            $response = Response::HTTP_OK;
            $name = $request->getCurrentRequest()->getPayload()->get('name');
            if (is_null($name) || trim($name) === '') {
                throw new InvalidArgumentException('Name cannot be empty.');
            }
            $players = $this->playersRepository->findAll();
            $playersName = array_map(function ($player) {
                return $player->name;
            }, $players);
            if (!in_array($name, $playersName)) {
                $players[] = [
                    "id" => \count($players)+1,
                    "name" => $name,
                ];

                $this->playersRepository->save($players);
                $response = Response::HTTP_CREATED;
            }
            return $this->json(array_pop($players), $response);
        } catch (InvalidArgumentException $exception) {
            return $this->json(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $exception) {
            return $this->json(['message' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

