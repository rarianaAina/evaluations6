<?php

namespace App\Imports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ClientsImport implements ToModel
{
    public function model(array $row)
    {
        return new Client([
            'external_id'   => $row[0] ?? null,
            'address'       => $row[1] ?? null,
            'zipcode'       => $row[2] ?? null,
            'city'          => $row[3] ?? null,
            'company_name'  => $row[4] ?? null,
            'vat'           => $row[5] ?? null,
            'company_type'  => $row[6] ?? null,
            'client_number' => $row[7] ?? null,
            'user_id'       => $row[8] ?? null,
            'industry_id'   => $row[9] ?? null,
        ]);
    }
}

