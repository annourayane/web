<?php 
set_include_path("./src");
require_once("view/View.php"); 
require_once("control/Controller.php");
require_once("model/Animal.php"); 
require_once("view/ViewJson.php"); 
require_once("model/AnimalStorage.php");
class ApiRouter {

    private $controller;

    public function main(AnimalStorage $storage) {
        if (!isset($_GET['collection'])) {
            $this->handleHtmlRequest($storage);
        } else {
            $this->handleApiRequest($storage);
        }
    }

    private function handleHtmlRequest(AnimalStorage $storage) {
        $feedback = null;
        if (isset($_SESSION['feedback'])) {
            $feedback = $_SESSION['feedback'];
            unset($_SESSION['feedback']); 
        }
    
        // Remplacer l'objet ApiRouter par PathInfoRouter
        $pathInfoRouter = new PathInfoRouter(); // Créez une instance de PathInfoRouter
        $view = new View($pathInfoRouter, $feedback); // Passez PathInfoRouter à View
        $this->controller = new Controller($this, $view, $storage);
    
        $action = isset($_GET['action']) ? $_GET['action'] : '';
    
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
    
    private function handleApiRequest(AnimalStorage $storage) {
        $viewJson = new ViewJson($this);
        $this->controller = new Controller($this, $viewJson, $storage);

        $collection = isset($_GET['collection']) ? $_GET['collection'] : '';
        $id = isset($_GET['id']) ? $_GET['id'] : '';

        if ($collection === 'animaux') {
            if ($id !== '') {
                $this->controller->showInformation($id);
            } else {
                $this->controller->showList();
            }
        } else {
            http_response_code(400); // Bad Request
            $viewJson->setData(['error' => 'Invalid collection']);
            $viewJson->render();
        }
    }
}
