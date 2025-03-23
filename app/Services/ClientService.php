<?php

namespace App\Services;

use App\Models\Client;

class ClientService
{
    public function getAllClients()
    {
        return Client::with(['projects', 'invoices'])->get();
    }

    public function getClientById($id)
    {
        return Client::with(['projects', 'invoices'])->findOrFail($id);
    }
}