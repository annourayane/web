<?php

require_once("view/View.php");
require_once("control/Controller.php");
require_once("model/AnimalStorage.php");

class PathInfoRouter {

    private $baseDeUrl = '/groupe-5/site.php'; //url de base du site 
    private $controller;//agit selon les requetes 

    public function getAnimalURL($id) {
        // Generer url avec PATH_INFO
        return $this->baseDeUrl . '/' . urlencode($id);
    }
    public function getHomeURL() {
        return $this->baseDeUrl; 
    }

    public function getListURL() {
        return $this->baseDeUrl . '/liste'; 
    }

    public function getAnimalCreationURL() {
        return $this->baseDeUrl . '/nouveau';
    }
    public function getAnimalSaveURL() {
        return $this->baseDeUrl . '/sauverNouveau';
    }

    /**
     * apres la creation d'un animal(methode post) l'user est redirigé vers 
     * une nouvelle url
     */
    public function POSTredirect($url, $feedback) {
        $_SESSION['feedback']=$feedback; //sotcker feedback dans la session
        
        header("Location: $url",true,303);  // Redirection vers l'URL
        exit; /**header envoie une requete http au navigateur */
    }
    public function main(AnimalStorage $storage) {

        $feedback = null;
        if (isset($_SESSION['feedback'])) {
            $feedback = $_SESSION['feedback'];
            unset($_SESSION['feedback']); 
        }

   
        $view = new View($this,$feedback);
        //$view = new View($this, '', '', $this->feedback);

        // verifier si un feedback est passé dans l'url
        if (isset($_GET['feedback'])) {
            $view->setFeedback($_GET['feedback']); // Transmettre le feedback a la vue
        }
       
        $this->controller = new Controller($this, $view, $storage);

        $action = isset($_GET['action']) ? $_GET['action'] : '';

        // Gestion des actions selon le parametre dans l'url

        if ($action === 'nouveau') {
           $this->controller->createNewAnimal();

        } elseif ($action === 'sauverNouveau' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->controller->saveNewAnimal($_POST);

        } else {
         
            $pathInfo = isset($_SERVER['PATH_INFO']) ? trim($_SERVER['PATH_INFO'], '/') : '';
    
            if ($pathInfo === '') {
                $this->controller->showHomePage();

            } elseif ($pathInfo === 'liste') {
                $this->controller->showList();

            } elseif ($pathInfo === 'nouveau') {
                $this->controller->createNewAnimal();
                 
            } elseif ($pathInfo === 'sauverNouveau') {
                $this->controller->saveNewAnimal($_POST);

            } else {
                $this->controller->showInformation($pathInfo);
            }
        }


        $view->render();
    }
}
?>
