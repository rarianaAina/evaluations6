<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Import\ImportCsv;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Session;

class ImportController extends Controller
{
    private $importCsv;

    public function __construct(ImportCsv $importCsv)
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
     
            $result = $this->importCsv->importFromCsv(storage_path("app/csv/data.csv"));
    
            Session::flash('success', 'Importation réussie');
            Session::flash('import_message', $result);
        } else {
            Session::flash('error', 'Erreur lors de l\'importation du fichier CSV.');
        }
    
        return redirect()->route('import.index');
    }
}
