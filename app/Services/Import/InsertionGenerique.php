<?php

namespace App\Services\Import;

use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class InsertionGenerique {

    public function insertFromTempTable($tableName, $columnDefs) {
        DB::beginTransaction();

        try {
            
            $gestionContrainte = new GestionContrainte();
            $constraintsMap = $gestionContrainte->removeAllConstraints($tableName);

            $columns = DB::select("SHOW COLUMNS FROM temp");

            $idName = "";
            $columnsToInsert = [];

            foreach ($columns as $column) {
                $columnName = $column->Field;
                foreach ($columnDefs as $def) {
                    if ($columnName === $def['nom']) {
                        if ($def['int'] == 0) {
                            $idName = $def['nom'];  
                        }
                        $columnsToInsert[$columnName] = $def['int'];
                        break;
                    }
                }
            }

            
            if (empty($columnsToInsert)) {
                Log::warning("Aucune colonne à insérer trouvée dans la table 'temp'.");
                return;
            }

             
            $recordIds = [];
            if (!empty($idName)) {
                if (Schema::hasColumn('temp', $idName)) {
                    $ids = DB::table("temp")->pluck($idName)->toArray();
                    $recordIds = array_unique(array_filter($ids));  
                } else {
                    Log::error("La colonne d'identifiant '$idName' n'existe pas dans la table 'temp'.");
                    throw new Exception("La colonne d'identifiant '$idName' n'existe pas dans la table 'temp'.");
                }
            } else {
                Log::error("Aucune colonne d'identifiant trouvée dans les définitions de colonnes.");
                throw new Exception("Aucune colonne d'identifiant trouvée dans les définitions de colonnes.");
            }
            
             
            $existingRecords = DB::table($tableName)->whereIn("id", $recordIds)->pluck("id")->toArray();
            $newRecordIds = array_diff($recordIds, $existingRecords);

            // dd($existingRecords);
            foreach ($newRecordIds as $recordId) {
                $data = [];
                foreach ($columnsToInsert as $columnName => $isId) {
                    if ($isId == 0) {
                        $data["id"] = $recordId;  
                    } else {
                        $data[$columnName] = DB::table("temp")
                            ->where($idName, $recordId)
                            ->value($columnName);
                    }
                }
                Log::error("". json_encode($data));
                DB::table($tableName)->insert($data);
            }

             
            $gestionContrainte->restoreConstraints($tableName, $constraintsMap);

             
            DB::commit();
            Log::info("Les nouveaux enregistrements ont été insérés avec succès dans la table '$tableName'.");
        } catch (Exception $e) {
             
            DB::rollBack();
            Log::error("Erreur lors de l'insertion des enregistrements : " . $e->getMessage());
            throw $e;
        }
    }
}