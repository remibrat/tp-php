<?php

namespace App\Core;


class DB
{
    private $table;
    private $pdo;
    protected $class;

    public function __construct(string $class, string $table)
    {
        $this->class = $class;
        //SINGLETON
        try {
            $this->pdo = new \PDO(DB_DRIVER.":host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PWD);
        } catch (\Throwable $e) {
            die("Erreur SQL : ".$e->getMessage());
        }
        $this->table =  DB_PREFIXE.$table;
    }


    public function save($objectToSave)
    {

        $objectArray =  $objectToSave->__toArray();

        $columnsData = array_values($objectArray);
        $columns = array_keys($objectArray);
        // On met 2 points devant chaque clé du tableau
        $params = array_combine(
            array_map(function($k){ return ':'.$k; }, array_keys($objectArray)),
            $objectArray
        );;
        
        if (!is_numeric($objectToSave->getId())) {
            
            //INSERT
            $sql = "INSERT INTO ".$this->table." (".implode(",", $columns).") VALUES (:".implode(",:", $columns).");";
            //foreach()
        } else {

            //UPDATE
            foreach ($columns as $column) {
                $sqlUpdate[] = $column."=:".$column;
            }

            $sql = "UPDATE ".$this->table." SET ".implode(",", $sqlUpdate)." WHERE id=:id;";
        }
       
        $this->sql($sql, $params);

       // $queryPrepared = $this->pdo->prepare($sql);
       // $queryPrepared->execute($columnsData);
    }

    public function find(int $id)
    {
        $sql = "SELECT * FROM $this->table where id = :id";

        $result = $this->sql($sql, [':id' => $id]);

        $row = $result->fetch();

        if ($row) {
            $object = new $this->class();
            return $object->hydrate($row);
        } else {
            return null;
        }
      
    }

    /// Faire un find all  qui renvoie un tableau avec toutes les valeurs

    /// Faire un count qui renvoit un entier avec le nombre d'élèment

    /// Faire un findBy qui prend en paramètre un tableau de paramètres (clé = champs db, valeur = valeur en db)
    /// et qui renvoit un tableau d'objet correspondant à where champsDB1 = ValueDB1 && champsDB2 = ValueDB2
    /// Ensuite rajouter un deuxième paramètre qui gère l'ordre (order by champsDB valueOrder) valueORDER = ASC or DESC

    protected function sql($sql, $parameters = null)
    {
        if ($parameters) {
            $queryPrepared = $this->pdo->prepare($sql);
            $queryPrepared->execute($parameters);

            return $queryPrepared;
        } else {
            $queryPrepared = $this->pdo->prepare($sql);

            return $queryPrepared;
        }
    }

    
    
}
