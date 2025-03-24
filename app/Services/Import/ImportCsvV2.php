<?php

namespace App\Services\Import;

use Illuminate\Support\Facades\DB;
use Exception;

class ImportCsvV2
{
    
    public function createTemporaryTableFromCsv($filePath)
    {
         
        DB::beginTransaction();

        try {
            
            $data = array_map('str_getcsv', file($filePath));
            $data = array_filter($data, function ($row) {
                return array_filter($row);  
            });

            if (empty($data)) {
                throw new Exception("Le fichier CSV est vide ou mal formaté.");
            }
 
            $headers = array_map('trim', array_shift($data));
 
            $tempTableName = 'temp';
            self::createTempTable($tempTableName, $headers);

            
            self::insertIntoTempTable($tempTableName, $data, $headers);
 
            DB::commit();

            return $tempTableName; 
        } catch (Exception $e) {
            
            DB::rollBack();
            throw $e;  
        }
    }

    
    private function createTempTable($tableName, $headers)
    {
        $columnDefs = array_map(function ($column) {
            return "$column VARCHAR(255)";  
        }, $headers);

        $columnDefsString = implode(", ", $columnDefs);

        // dd("CREATE TEMPORARY TABLE $tableName ($columnDefsString)");
        DB::statement("DROP TABLE IF EXISTS $tableName");
        DB::statement("CREATE TABLE $tableName ($columnDefsString)");

    }

    private function insertIntoTempTable($tableName, $data, $headers)
    {
        $columns = implode(", ", $headers);  

        foreach ($data as $row) {
             
            $escapedValues = array_map(function ($value) {
                return "'" . addslashes($value) . "'";
            }, $row);

             
            $valuesString = implode(", ", $escapedValues);

             
            DB::statement("INSERT INTO $tableName ($columns) VALUES ($valuesString)");
        }
    }


    public function insertUsersFromTempTable()
    {
        DB::beginTransaction();

        try {
            
            $gestionContrainte = new GestionContrainte();
            $constraintsMap = $gestionContrainte->removeAllConstraints("users");

            $columns = DB::select("SHOW COLUMNS FROM temp");
            $userColumns = array_filter($columns, function ($column) {
                return strpos($column->Field, 'user_') === 0;
            });

            if (empty($userColumns)) {
                return;
            }

            $userIds = [];
            foreach ($userColumns as $column) {
                $columnName = $column->Field;
                $ids = DB::table("temp")->pluck($columnName)->toArray();
                $userIds = array_merge($userIds, $ids);
            }
            $userIds = array_unique(array_filter($userIds)); 

            $existingUsers = DB::table('users')->whereIn('id', $userIds)->pluck('id')->toArray();
            $newUserIds = array_diff($userIds, $existingUsers);

            foreach ($newUserIds as $userId) {
                DB::table('users')->insert(['id' => $userId]);
            }

            $gestionContrainte->restoreConstraints("users",$constraintsMap);

            DB::commit();
            echo "Les nouveaux utilisateurs ont été insérés avec succès.";
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }


}

