<?php

require_once("view/View.php");
require_once("model/Animal.php"); 
require_once("model/AnimalStorage.php");
require_once("model/AnimalStorageStub.php");
require_once('Router.php');
require_once("PathInfoRouter.php"); 
require_once("model/AnimalBuilder.php"); 

class Controller {
    private $view;
    private $router; 
    private $storage;

    public function __construct( $router,$view ,  $storage) {
     
        $this->view = $view;
        $this->router = $router; 
        $this->storage = $storage;
    }

    
    public function getView() {
        return $this->view;
    }

 public function showInformation($id) {
        $animal = $this->storage->read($id); //renvoie l'animal
        if ($animal) {
            $this->view->prepareAnimalPage($animal); 
        } else {
            $this->view->prepareUnknownAnimalPage();
        }
    }

    public function showHomePage() {
        $this->view->prepareHomePage();
    }

    //  liste des animaux
    public function showList() {
        
        $animals = $this->storage->readAll(); //renvoie tous  les animaux
        $this->view->prepareListPage($animals);
    }

   
    public function createNewAnimal() {
        $builder = new AnimalBuilder([]);
        
        $this->view->prepareAnimalCreationPage($builder); 
    }


    public function saveNewAnimal(array $data) {
        $builder = new AnimalBuilder($data);
        
        if (!$builder->isValid()) {
            $this->view->prepareAnimalCreationPage($builder);
            return;
        }
        
        // Gestion de l'image téléchargee
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../uploads/';//stocker les imgs selectionnee

            $fileName = uniqid() . '_' . basename($_FILES['image']['name']);//genrer nom pour l'img
            $uploadFile = $uploadDir . $fileName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0775, true);
                error_log("Le dossier 'uploads' a été créé : " . $uploadDir);
            }


            //deplacer l'img vers uploads
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                // Chemin relatif pour permettre a l'img d'etre affiche dans le web
                $imagePath = '/groupe-5/uploads/' . $fileName; //
            } else {
                 $this->view->prepareAnimalCreationPage($builder, "Erreur lors du téléchargement de l'image.");
                 return;
            }
        


        }
        error_log("Chemin de l'image après upload : " . $imagePath);
        
        
        $animal = $builder->createAnimal();
        $animal->setImagePath($imagePath); // Ajouter le chemin de l'image a l'animal
        $id = $this->storage->create($animal); //creat enregistre 'animall dans la bd
        
        $feedback = "L'animal a été créé avec succès.";
        $this->view->displayAnimalCreationSuccess($id, $feedback);
    }
}

?>
