<?php
namespace App\CustomLibrary;

use Illuminate\Support\Facades\DB;

class CreateMongodbCollection{
    public static function create(string $collectionName){

        if(is_null($collectionName) || trim($collectionName) == ""){
            echo "Collection name is empty";
            exit();
        }

        $database = DB::connection('mongodb')->getMongoDB();

        // Check if collection exists
        $collections = $database->listCollections();
        $collectionExists = false;

        foreach ($collections as $collection) {
            if ($collection->getName() === $collectionName) {
                $collectionExists = true;
                break;
            }
        }

        // Create the collection if it doesn't exist
        if (!$collectionExists) {
            $database->createCollection($collectionName);
            echo "Collection '$collectionName' created successfully!";
        } else {
            echo "Collection '$collectionName' already exists.";
        }
    }
}