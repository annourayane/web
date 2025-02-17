<?php
// src/model/StaticAnimalStorage.php

//require_once("AnimalStorage.php");
//require_once("Animal.php");

class AnimalStorageStub implements AnimalStorage {
    
    private $animals;  // Tableau des animaux

    public function __construct() {
        $this->animals = array(
            'medor' => new Animal('Medor', 'chien', 5),
            'felix' => new Animal('Felix', 'chat', 2),
            'denver' => new Animal('Denver', 'dinosaure', 180),
            'flipflop' => new Animal('flip flop', 'dino', 18),
        );
    }


    public function read($id) {
        // Verifier si l'animal existe dans le tab
        return isset($this->animals[$id]) ? $this->animals[$id] : null;
    }

    public function readAll() {
        return $this->animals;
    }
      
    public function create (Animal $a) {
        throw new BadMethodCallException("Impossible d'ajouter un animal dans AnimalStorageStub.");  
    }

    
    public function delete($id){
        throw new BadMethodCallException("Impossible de supprimer un animal dans AnimalStorageStub.");
    }

 
    public function update($id, Animal $a){
        throw new BadMethodCallException("Impossible de mettre a jour un animal dans AnimalStorageStub.");
    }



}
?>
