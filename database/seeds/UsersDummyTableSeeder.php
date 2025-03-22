<?php

use App\Models\Absence;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use App\Models\Department;
use App\Models\RoleUser;
use App\Models\User;

class UsersDummyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $createDep = new Department;
        $createDep->id = '2';
        $createDep->name = 'Nerds';
        $createDep->external_id = Uuid::uuid4();
        $createDep->external_id = Uuid::uuid4();
        $createDep->save();
        $createDep = new Department;
        $createDep->id = '3';
        $createDep->name = 'Genius';
        $createDep->external_id = Uuid::uuid4();
        $createDep->external_id = Uuid::uuid4();
        $createDep->save();

        // factory(User::class, 5)->create()->each(function ($u) {
        //     if(rand(1, 4) == 3) {
        //         factory(Absence::class)->create([
        //             'user_id' => $u->id
        //         ]);
        //     }
        // });

        factory(User::class, 5)->create()->each(function ($u) {
            //dump("Utilisateur créé : " . $u->id); // Ajoute cette ligne pour voir combien d'utilisateurs sont créés
            
            if(rand(1, 4) == 3) {
                factory(Absence::class)->create([
                    'user_id' => $u->id
                ]);
            }
        });
              
        $u = User::query()->latest()->first();
        factory(Absence::class)->create([
            'user_id' => $u->id,
            'start_at' => now()->subDays(2),
            'end_at' => now()->addDays(1),
        ]);
      
        $userCount = User::count();
        dump("Nombre total d'utilisateurs dans la base : $userCount");
    }
}
