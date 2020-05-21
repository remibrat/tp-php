<?php 
namespace App\Managers;

use App\Core\Manager;
use App\Models\User;

class UserManager extends Manager {

    // FONCTION PARENT
    // public function __construct(string $class, string $table)
    // {
    //     $this->class = $class;
    //     //SINGLETON
    //     try {
    //         $this->pdo = new \PDO(DB_DRIVER.":host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PWD);
    //     } catch (\Throwable $e) {
    //         die("Erreur SQL : ".$e->getMessage());
    //     }
    //     $this->table =  DB_PREFIXE.$table;
    // }


    public function __construct()
    {
        parent::__construct(User::class, 'users');
    }

    public function getUserAdmin()
    {
        // selectionner mes admins
        // boucle sur mon résultat, crée User à mettre dans la liste à returner
        //return ma liste
    }
}