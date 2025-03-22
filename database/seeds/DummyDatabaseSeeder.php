<?php

use Illuminate\Database\Seeder;

class DummyDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Récupérer le paramètre `--count` depuis la commande
        $count = (int) (config('seeder.default_count', 10)); // Valeur par défaut: 10

        $this->call(UsersDummyTableSeeder::class, false, ['count' => $count]);
        $this->call(ClientsDummyTableSeeder::class, false, ['count' => $count]);
        $this->call(TasksDummyTableSeeder::class, false, ['count' => $count]);
        $this->call(LeadsDummyTableSeeder::class, false, ['count' => $count]);
    }
}
