<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OfferService;
use Illuminate\Http\JsonResponse;

class OfferController extends Controller
{
    private $offerService;

    public function __construct(OfferService $offerService)
    {
        $this->offerService = $offerService;
    }

    public function index(): JsonResponse
    {
        $offers = $this->offerService->getAllOffers();
        return response()->json($offers);
    }

    public function show($id): JsonResponse
    {
        $offer = $this->offerService->getOfferById($id);
        return response()->json($offer);
    }
}