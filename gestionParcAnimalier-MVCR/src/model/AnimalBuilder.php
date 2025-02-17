<?php

class AnimalBuilder {

    const NAME_REF = 'NAME';
    const SPECIES_REF = 'SPECIES';
    const AGE_REF = 'AGE';

    private $data;  //donnees de  l'animal
    private $error; 

    public function __construct(array $data) {
        $this->data = $data;
        $this->error = null; 
    }


    public function getData(): array {
        return $this->data;
    }

    public function getError(): ?string {
        return $this->error;
    }


    public function setError(string $error): void {
        $this->error = $error;
    }

// verifie validite des champs 
    public function isValid(): bool {
        
        if (empty($this->data[self::NAME_REF])) {
            $this->error = "Le champ 'Nom' est obligatoire.";
            return false;
        }
        if (empty($this->data[self::SPECIES_REF])) {
            $this->error = "Le champ 'Espèce' est obligatoire.";
            return false;
        }
        if (empty($this->data[self::AGE_REF]) || !is_numeric($this->data[self::AGE_REF]) || $this->data[self::AGE_REF] <= 0) {
            $this->error = "L'âge doit être un nombre positif.";
            return false;
        }
        return true;
    }

    public function createAnimal(): ?Animal {
        if (!$this->isValid()) {
            return null; 
        }

        return new Animal(
            $this->data[self::NAME_REF],
            $this->data[self::SPECIES_REF],
            (int)$this->data[self::AGE_REF]
        );
    }
}

?>
