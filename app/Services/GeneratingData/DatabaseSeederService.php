<?php

namespace App\Services\GeneratingData;

use Illuminate\Support\Facades\Artisan;

class DatabaseSeederService
{
    public function seedDummyData()
    {
        Artisan::call('db:seed', [
            '--class' => 'DummyDatabaseSeeder'
        ]);

        return Artisan::output();
    }
}
