<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ClientService;
use Illuminate\Http\JsonResponse;

class ClientController extends Controller
{
    private $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    public function index(): JsonResponse
    {
        $clients = $this->clientService->getAllClients();
        return response()->json($clients);
    }

    public function show($id): JsonResponse
    {
        $client = $this->clientService->getClientById($id);
        return response()->json($client);
    }
}