<?php
require_once("model/Animal.php");
// Importer le routeur
//require_once('Router.php');
require_once("PathInfoRouter.php"); 
require_once("model/AnimalBuilder.php"); 

class View {
    private $title; 
    private $content; 
    private $router; 
    private $menu; 
    private $feedback;
    public function __construct(PathInfoRouter $router,$feedback=null){
      
        $this->router = $router; 
        $this->feedback = $feedback;
        $this->menu = array(
            $this->router->getHomeURL() => 'Accueil',
            $this->router->getListURL() => 'Liste des animaux',
            $this->router->getAnimalCreationURL() => 'Ajouter un animal'
        ); 
    }  
    public function prepareTestPage() {
        
        $this->title = 'Page de test';
        $this->content = 'Ceci est un contenu de test pour vérifier le rendu de la page.';
    }
    
    public function prepareAnimalPage(Animal $animal) {
        $this->title = "Page sur " . $animal->getName();
        $this->content = $animal->getName() . " est un " . $animal->getSpecies() . " âgé de " . $animal->getAge() . " ans.";
        if ($animal->getImagePath()) {
           //affichage de l'img
           // $this->content .= "<br><img src='{$animal->getImagePath()}' alt='{$animal->getName()}' style='max-width: 300px;'>";
            $this->content .= "<br><img src='{$animal->getImagePath()}' alt='{$animal->getName()}' style='max-width: 300px;'>";
            }
    }
    public function prepareUnknownAnimalPage(){
        $this->title = "Erreur"; 
        $this->content = "La page demandé n'existe pas"; 
    }
  
    public function prepareHomePage() {
        $this->title = 'Bienvenue sur le site des animaux';
        $this->content = 'Bienvenue sur notre site !';
    }

    public function prepareListPage($animals) {
        $this->title = 'Liste des animaux';
        $this->content = 'Voici une liste d\'animaux disponibles :<br>';
    
        //liens vers les pages de  chaque animal 
        $this->content .= '<ul>';
        foreach ($animals as $id  => $animal) {
           // $animalURL = $this->router->getAnimalURL($animal->id); 
            $animalURL = $this->router->getAnimalURL($id);  
            $this->content .= '<li><a href="' . $animalURL . '">' . $animal->getName() . '</a></li>';
        }
        $this->content .= '</ul>';
    }


    private function renderMenu() {
        $menuHtml = '<nav><ul>';
        foreach ($this->menu as $url => $text) {
            $menuHtml .= '<li><a href="' . $url . '">' . $text . '</a></li>';
        }
        $menuHtml .= '</ul></nav>';
        return $menuHtml;
    }
    

    

    public function render(){
       // verifie si le feedback est dans l'url
    if (isset($_GET['feedback'])) {
        $this->feedback = htmlspecialchars($_GET['feedback']);
    }

    // Affichage  de la page
        echo "
        <!DOCTYPE html>
        <html lang='fr'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>{$this->title}</title>
        </head>
        <body>
            " . $this->renderMenu() . " <!-- Appel à renderMenu pour afficher le menu -->
            <h1>{$this->title}</h1>
        ";
     
        if (!empty($this->feedback)) {
            echo "<p style='color: green;'>{$this->feedback}</p>";
        }
        echo "
            <div>{$this->content}</div>
        </body>
        </html>
        ";
    }
 
    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

   
    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
    }


    public function prepareAnimalCreationPage(AnimalBuilder $builder ) {
        $data = $builder->getData();//recup des donnees precedement soumises
        $error = $builder->getError();
      //  $error = $builder ? $builder->getError() : null;
    
        $saveURL = $this->router->getAnimalSaveURL(); // URL pour sauvegarder l'animal
         //recpere les donnees deja saisie pas le user 
        $name = htmlspecialchars($builder->getData()[AnimalBuilder::NAME_REF] ?? '');//recupere le nom si'il existe 
        $species = htmlspecialchars($builder->getData()[AnimalBuilder::SPECIES_REF] ?? '');
        $age = htmlspecialchars($builder->getData()[AnimalBuilder::AGE_REF] ?? '');
    
        $this->content = '';
        if ($builder->getError() != null) {
            $this->content .= "<p style='color: red;'>" . htmlspecialchars($builder->getError()) . "</p>";
        }
        $this->title = "Créer un nouvel animal";
    
        $this->content .= '<form action="' . $saveURL . '" method="POST" enctype="multipart/form-data">';
        $this->content .= '<label for="' . AnimalBuilder::NAME_REF . '">Nom :</label>';
        $this->content .= '<input type="text" id="' . AnimalBuilder::NAME_REF . '" name="' . AnimalBuilder::NAME_REF . '" value="' . $name . '" >';
        $this->content .= '<br><br>';
        
        $this->content .= '<label for="' . AnimalBuilder::SPECIES_REF . '">Espèce :</label>';
        $this->content .= '<input type="text" id="' . AnimalBuilder::SPECIES_REF . '" name="' . AnimalBuilder::SPECIES_REF . '" value="' . $species . '" >';
        $this->content .= '<br><br>';
        
    
        $this->content .= '<label for="' . AnimalBuilder::AGE_REF . '">Âge :</label>';
        $this->content .= '<input type="number" id="' . AnimalBuilder::AGE_REF . '" name="' . AnimalBuilder::AGE_REF . '" value="' . $age . '" min="0" >';
        $this->content .= '<br><br>';
        
        $this->content .= '<label for="image">Image :</label>'; //champde telechargement de l'img
        $this->content .= '<input type="file" id="image" name="image">';
        $this->content .= '<br><br>';
        $this->content .= '<button type="submit">Créer</button>';
        
        $this->content .= '</form>';
   
    }   

    public function displayAnimalCreationSuccess($id) {
        // url de l'animal a partir du routeur
        $url = $this->router->getAnimalURL($id); //url pour acceder a la page de l'animal 
        
        $feedback = "L'animal a été créé avec succès !";
       
    
        //  redirection avec feedback
        $this->router->POSTredirect( $url ,$feedback);
    }
}
?>