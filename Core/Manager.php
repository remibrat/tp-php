<?php

namespace App\Core;


class Manager
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

    }

    public function find(int $id): ?Model
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

    public function findAll(): array
    {
        $sql = "SELECT * FROM $this->table";

        $result = $this->sql($sql);

        $rows = $result->fetchAll();
        
        $results = array();
        
        foreach($rows as $row) {

            $object = new $this->class();
            array_push($results, $object->hydrate($row));
        } 

        return $results;
      
    }

    public function findBy(array $params, array $order = null): array
    {
        $results = array();

        $sql = "SELECT * FROM $this->table where ";

        // Select * FROM users WHERE firstname LIKE :firstname ORDER BY id desc

        foreach($params as $key => $value) {
            if(is_string($value))
                $comparator = 'LIKE';
            else 
                $comparator = '=';

            $sql .= " $key $comparator :$key and"; 
            // Select * FROM users WHERE firstname LIKE :firstname and
            // [":firstname" => 'Fadyl%']
            // ["firstname" => 'Fadyl%']
            $params[":$key"] = $value;
            // ["firstname" => 'Fadyl%', ":firstname" => 'Fadyl%']
            unset($params[$key]);
           // [":firstname" => 'Fadyl%']
        }

        $sql = rtrim($sql, 'and');
        // Select * FROM users WHERE firstname LIKE :firstname

        if($order) {
            $sql .= "ORDER BY ". key($order). " ". $order[key($order)]; 
        }
        // Select * FROM users WHERE firstname LIKE :firstname ORDER BY id desc

        $result = $this->sql($sql, $params);
        $rows = $result->fetchAll();
        
        foreach($rows as $row) {
            $object = new $this->class();
            array_push($results, $object->hydrate($row));
        } 

        return $results;

    }

    public function count(array $params): int
    {
       

        $sql = "SELECT COUNT(*) FROM $this->table where ";

        foreach($params as $key => $value) {
            if(is_string($value))
                $comparator = 'LIKE';
            else 
                $comparator = '=';
            $sql .= " $key $comparator :$key and"; 

            $params[":$key"] = $value;
            unset($params[$key]);
        }

        $sql = rtrim($sql, 'and');

        $result = $this->sql($sql, $params);
        return $result->fetchColumn();


    }

    public function delete(int $id): bool
    {
       

        $sql = "DELETE FROM $this->table where id = :id";

        $result = $this->sql($sql, [':id' => $id]);

        return true;


    }

    /// Faire un find all  qui renvoie un tableau avec toutes les valeurs

    /// Faire un count qui renvoit un entier avec le nombre d'élèment

    /// Faire un findBy qui prend en paramètre un tableau de paramètres (clé = champs db, valeur = valeur en db)
    /// et qui renvoit un tableau d'objet correspondant à where champsDB1 = ValueDB1 && champsDB2 = ValueDB2
    /// Ensuite rajouter un deuxième paramètre qui gère l'ordre (order by champsDB valueOrder) valueORDER = ASC or DESC
    // function findBy(array $params, array $)
    // Faire un delete function delete(int $id)

    protected function sql($sql, $parameters = null)
    {
        if ($parameters) {
            $queryPrepared = $this->pdo->prepare($sql);
            $queryPrepared->execute($parameters);

            return $queryPrepared;
        } else {
            $queryPrepared = $this->pdo->prepare($sql);
            $queryPrepared->execute();

            return $queryPrepared;
        }
    }

    
    
}
