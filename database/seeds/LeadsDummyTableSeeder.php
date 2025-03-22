<?php

use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeadsDummyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        dump("Avant création des leads, nombre d'utilisateurs : " . User::count());

        // Récupérer les utilisateurs existants
        $users = User::pluck('id')->toArray();
        
        // Si aucun utilisateur n'existe, on arrête ici
        if (empty($users)) {
            dump("⚠️ Aucun utilisateur disponible. Aucun commentaire ne sera créé.");
            return;
        }

        factory(Lead::class, 20)->create()->each(function ($l) use ($users) {
            // Sélectionner un utilisateur au hasard parmi ceux existants
            $userId = $users[array_rand($users)];
            dump("Utilisateur sélectionné pour les commentaires : " . $userId);

            if (rand(0, 5) == 1) {
                factory(App\Models\Comment::class, 3)->create([
                    'source_type' => Lead::class,
                    'source_id' => $l->id,
                    'user_id' => $userId,
                ]);
            }

            factory(App\Models\Comment::class, 2)->create([
                'source_type' => Lead::class,
                'source_id' => $l->id,
                'user_id' => $userId,
            ]);
        });

        dump("Après création des leads, nombre d'utilisateurs : " . User::count());
    }
}
