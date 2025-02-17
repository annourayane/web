<?php

set_include_path("./src");
require_once("view/View.php"); 
require_once("control/Controller.php");
require_once("model/Animal.php"); 

class Router {

    private $baseDeUrl = '/groupe-5/site.php';  
    private $controller;

    public function getAnimalURL($id) {

        return $this->baseDeUrl.'?id='.$id;
    }

    public function main() {
      
        $view = new View($this);

        $storage = new AnimalStorageStub();

        $this->controller = new Controller($this, $view, $storage);



         if (isset($_GET['action'])) {
            if ($_GET['action'] == 'liste') {
               
                $this->controller->showList();
            } else {
                
                $this->controller->showHomePage();
            }
        } elseif (isset($_GET['id'])) {
            
            $id = $_GET['id'];
            $this->controller->showInformation($id);
        } else {
          
            $this->controller->showHomePage();
        }

       
        $view->render();
    }

    public function getController() {
        return $this->controller;
    }
}

        // Préparer la page de test
        //$view->prepareTestPage();
        //$view -> prepareAnimalPage("Médor", "Chien");       
        // Afficher la page de test
        //$controller -> showInformation("medor"); 
        //recuperer l'animal a partir de l'url 
        // Vérifier si un paramètre 'id' existe dans l'URL
         // Vérifier si un paramètre 'action' existe dans l'URL
