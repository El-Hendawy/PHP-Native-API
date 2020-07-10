<?php
namespace Core\Http\Models;

class DB {

   private function __construct(){
       //Here you will create DB Connection Object
       echo "New DB Object Created";
   }

   public static function getInstance(){
       //Here we will check if there is already instance created if not create a new one.
        static $Instance = null;

        if($Instance == null){

            $Instance = new static();
        }

        else{

            echo "DB Object Already Created";

        }
        
    return $Instance;
    }
}