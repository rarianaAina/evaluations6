<?php

use Illuminate\Database\Seeder;

class ClientsDummyTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Client::class, 1)->create()->each(function ($c) {
            factory(\App\Models\Contact::class)->create([
                'client_id' => $c->id
            ]);
        });
    }
}
