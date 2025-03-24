<?php

namespace App\Http\Controllers;

use App\Services\Import\ImportCsvV2;
use App\Services\Import\InsertionGenerique;
use Illuminate\Http\Request;
// use App\Services\Import\ImportCsv;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Session;

class ImportController extends Controller
{
    private $importCsv;

    public function __construct(ImportCsvV2 $importCsv)
    {
        $this->importCsv = $importCsv;
    }

     
    public function index()
    {
        return view("import.index");
    }

    public function uploadCsv(Request $request)
    {
          
         if ($request->hasFile('csv_file') && $request->file('csv_file')->isValid()) {
            $file = $request->file('csv_file');
            $filePath = $file->storeAs('csv', 'data.csv', 'local');  
     
            $result = $this->importCsv->createTemporaryTableFromCsv(storage_path("app/csv/data.csv"));
            $tableName="users";
            $columnDefs = [
                ['nom' => 'name', 'int' => 1],
                ['nom' => 'user_id', 'int' => 0], // Colonne d'identifiant (client_id)
                 // Colonne normale (client_name)
            ];
            $insertionGenerique=new InsertionGenerique();
            $insertionGenerique->insertFromTempTable($tableName, $columnDefs);
            Session::flash('success', 'Importation réussie');
            Session::flash('import_message', $result);
        } else {
            Session::flash('error', 'Erreur lors de l\'importation du fichier CSV.');
        }
    
        return redirect()->route('import.index');
    }
}
