<?php

namespace App\Controller;

use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PingController extends AbstractController
{
    #[Route('/api/v1/ping', name: 'app_get_ping', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json(['ping' => 'pong', 'ack' => microtime(true)]);
    }
}

