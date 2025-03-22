<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use App\Services\GeneratingData\DatabaseSeederService;

class DatabaseSeederController extends Controller
{
    public function seed(): RedirectResponse
    {
        $seederService = new DatabaseSeederService();
        $output = $seederService->seedDummyData();

        // Stocker le message de succès et les détails de l'exécution dans la session
        Session::flash('success', 'Seeding completed');
        Session::flash('seed_output', $output);

        // Redirection vers une vue (ex: 'import.index' ou une autre page)
        return redirect()->route('dashboard');
    }
}
