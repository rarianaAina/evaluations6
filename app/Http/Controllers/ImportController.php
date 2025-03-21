<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ClientsImport;

class ImportController extends Controller
{
    /**
     * Affiche le formulaire d'importation de clients.
     */
    public function showImportForm()
    {
        return view('import.clients');
    }

    /**
     * Traite le fichier CSV pour importer des clients.
     */
    public function importClients(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048'
        ]);

        Excel::import(new ClientsImport, $request->file('csv_file'));

        return redirect()->back()->with('success', 'Importation réussie.');
    }
}
