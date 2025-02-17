<?php

/**
 * PDO (PHP Data Objects) qui permet d'acceder a des bdd
 */
require_once 'AnimalStorage.php';

class AnimalStorageMySQL implements AnimalStorage {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function readAll() { /**recupere toutes les donnees des nimaux de la bd dans un tab  */
        try {
            $query = "SELECT * FROM animals;";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(); 

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $animals = [];

            //creer des instances d'animal a partir des donnes de la bd 
            foreach ($results as $row) {
                $animal = new Animal($row['name'],
                 $row['species'],
                  $row['age'],
                  $row['image_path']);
                $animals[$row['id']] = $animal; //l'index de l'animal est son id 
            }
    
            return $animals; 

        } catch (PDOException $e) {
            throw new Exception("Erreur SQL : " . $e->getMessage());
        }
    }


    public function delete($id) {
        throw new Exception("Méthode delete non encore implémentée");
    }

    public function update($id, Animal $a) {
      
        throw new Exception("Méthode update non encore implémentée");
    }


    public function read($id) {
        try {
            // Préparation de la requête avec un placeholder nommé
            $query = "SELECT name, species, age, image_path FROM animals WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
    
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
    
            // recup des donnees
            $animalData = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($animalData) { //si on trouve l'animal on crree une instance avec ces donnees 
                $animal = new Animal($animalData['name'],
                 $animalData['species'], 
                 $animalData['age'],
                 $animalData['image_path']);
                return $animal;
            } else {
                throw new Exception("Animal non trouvé pour l'ID: $id");
            }
        } catch (PDOException $e) {
            throw new Exception("Erreur SQL : " . $e->getMessage());
        }
    }

    public function create(Animal $a) {
        try {
            $query = "INSERT INTO animals (name, species, age, image_path) VALUES (:name, :species, :age ,:image_path)";
            $stmt = $this->pdo->prepare($query);


            $stmt->execute([
                ':name' => $a->getName(),
                ':species' => $a->getSpecies(),
                ':age' => $a->getAge(),
                ':image_path' => $a->getImagePath(), // Ajouter le chemin de l'image
                ]);
            /*$name = $a->getName();       
            $species = $a->getSpecies();
            $age = $a->getAge();
    
        
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);//remplir placeholder avec une var 
            $stmt->bindParam(':species', $species, PDO::PARAM_STR);
            $stmt->bindParam(':age', $age, PDO::PARAM_INT);
            $stmt->execute();  */
    
            
            $id = $this->pdo->lastInsertId();
    
            return $id; 
        } catch (PDOException $e) {
            
            throw new Exception("Erreur lors de la création de l'animal : " . $e->getMessage());
        }
    }

}
