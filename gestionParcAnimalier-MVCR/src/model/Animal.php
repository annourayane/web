<?php

class Animal {
    
    private $name;
    private $species;
    private $age;
    private $imagePath; 


    public function __construct($name, $species, $age,$imagePath = null) {
        $this->name = $name;
        $this->species = $species;
        $this->age = $age;
        $this->imagePath = $imagePath; 
    }

    public function setImagePath($imagePath) {
        $this->imagePath = $imagePath;
    }

    public function getImagePath() {
          return $this->imagePath;
    }
    public function getName() {
        return $this->name;
    }

    public function getSpecies() {
        return $this->species;
    }

    public function getAge() {
        return $this->age;
    }
    public function setNom($nom) {
        $this->nom = $nom;
    }
    public function setAge($age) {
        $this->age = $age;
    }
    public function setEspece($espece) {
        $this->espece = $espece;
    }
}
