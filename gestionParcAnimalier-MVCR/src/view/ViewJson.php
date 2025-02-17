<?php
require_once("model/Animal.php");
require_once("model/AnimalBuilder.php"); 
require_once("ApiRouter.php"); 

class ViewJson {
    private $router;
    private $data = [];

    public function __construct($router) {
        $this->router = $router;
    }

    // Méthode pour définir les données à envoyer en réponse JSON
    public function setData($data) {
        $this->data = $data;
    }

    // Rendre la réponse JSON avec gestion des erreurs
    public function render() {
        // Si des erreurs sont présentes, on peut définir un code HTTP spécifique
        if (isset($this->data['error'])) {
            http_response_code(404);  // Code 404 pour "Non trouvé" ou personnaliser
        }
        header('Content-Type: application/json');
        echo json_encode($this->data, JSON_PRETTY_PRINT);
    }

    // Préparer la page avec une erreur si l'animal n'est pas trouvé
    public function prepareUnknownAnimalPage() {
        $this->setData(['error' => 'Animal non trouvé']);
        $this->render();
    }

    // Préparer la liste des animaux pour la réponse JSON
    public function prepareListPage($animals) {
        $animalsData = [];
        foreach ($animals as $id => $animal) {
            $animalsData[] = [
                
                'id' => $id,  // Maintenant, getId() fonctionnera car l'ID est associé à l'animal
                'nom' => $animal->getName()
            ];
        }
        $this->setData($animalsData);
        $this->render();
    }
    

    // Préparer la page avec les détails de l'animal
    public function prepareAnimalPage($animal) {
        if ($animal) {
            $this->setData([
                'nom' => $animal->getName(),
                'espece' => $animal->getSpecies(),
                'age' => $animal->getAge()
            ]);
        } else {
            $this->setData(['error' => 'Animal non trouvé']);
        }
        $this->render();
    }
}
?>
