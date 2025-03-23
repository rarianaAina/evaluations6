<?php

namespace App\Services;

use App\Models\Offer;

class OfferService
{
    public function getAllOffers()
    {
        return Offer::with(['invoiceLines', 'invoice'])->get();
    }

    public function getOfferById($id)
    {
        return Offer::with(['invoiceLines', 'invoice'])->findOrFail($id);
    }
}