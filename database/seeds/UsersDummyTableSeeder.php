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
    public function run($count = 5)
    {
        // Création des départements
        $departments = [
            ['id' => '2', 'name' => 'Nerds'],
            ['id' => '3', 'name' => 'Genius'],
        ];

        foreach ($departments as $dep) {
            $createDep = new Department;
            $createDep->id = $dep['id'];
            $createDep->name = $dep['name'];
            $createDep->external_id = Uuid::uuid4();
            $createDep->save();
        }

        // Création dynamique des utilisateurs
        factory(User::class, $count)->create()->each(function ($u) {
            if (rand(1, 4) == 3) {
                factory(Absence::class)->create([
                    'user_id' => $u->id
                ]);
            }
        });

        // Ajout d'une absence pour le dernier utilisateur créé
        $u = User::query()->latest()->first();
        factory(Absence::class)->create([
            'user_id' => $u->id,
            'start_at' => now()->subDays(2),
            'end_at' => now()->addDays(1),
        ]);
    }
}
