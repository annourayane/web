<?php
include("frag_deb.php");
require('Lib.php');

// Détection de l'action à effectuer
$action = isset($_GET['action']) ? trim($_GET['action']) : null;
$corps = ""; 
switch ($action) {
    case "afficher":
        $corps = "<h1>Liste des personnes</h1>";
        $connection = connecter();
        
        // verifier si on est connecter a la base de donnees 
        if ($connection) {
        		echo "Connexion Etablie"; 
        } else {
        		echo " Connexion n'esi pas Etablie !!! ";
        }      
        
        $requete = "SELECT * FROM Personne";

        try {
            
            // Exécution de la requête
            $query = $connection->query($requete);
            $query->setFetchMode(PDO::FETCH_OBJ);
            $corps .= "<div class='grid-container'>";
            $corps .= "<div class='item'>idP</div>";
            $corps .= "<div class='item'>Nom</div>";
            $corps .= "<div class='item'>Age</div>";
            $corps .= "<div class='item'>Action</div>";
            $corps .="</div>";


            while ($enregistrement = $query->fetch()) {
                $corps .= "<div class='grid-container'><span><u><b>".$enregistrement->idP."</b></u></span> <span>".$enregistrement->nom." </span><span>". $enregistrement->age."</span>";
                // Ajout des icônes de modification et de suppression
                $corps .= '<span>';
                $corps .= '<a href="index.php?action=modifier&idP='.$enregistrement->idP.'"><span class="glyphicon glyphicon-pencil"></span></a>';
                $corps .= '<a href="index.php?action=supprimer&idP='.$enregistrement->idP.'"><span class="glyphicon glyphicon-trash"></span></a>';
                $corps .= '</span>';
                
                
                $corps .= "</div>";
            }
        } catch (PDOException $e) {
            echo "Erreur lors de l'exécution de la requête : ", $e->getMessage();
        } finally {
            // Fermeture de la connexion et libération de la ressource
            $query = null;
            $connection = null;
        }
        $zonePrincipale = $corps;
        break;
    
    case "delete":
        $connection =connecter();
        // recuperer l'idP de la personne a supprimer 
        $idP = key_exists('idP',$_POST)? $_POST['idP']: null;

        $corps="<h1>Suppression de la personne ".$idP."</h1>" ;
        
        //$corps.="<h1>A Compléter ... !</h1> ";
    // echapper l'id pour eviter les injections sql 
         $idP = $connection->quote($idP);
        // Ecrire la requête de suppression
        $requete = "DELETE FROM Personne WHERE idP = $idP";
    
    
        //Exécuter la requête
        $connection->query($requete);
        
    
        // Fermeture de la connexion et libération de la ressource
        $query = null;
        $connection = null;
        $zonePrincipale=$corps ;
        
        break;
   
    case "modifier": // c'est le meme code de l'insertion avec qlq modification 
        $cible='modifier';
        $idP=$_GET["idP"];

        if (!isset($_POST["nom"]) && !isset($_POST["age"])) {
            try {
                $connection = connecter();
                $requete = "SELECT * FROM Personne WHERE idP = $idP";
                $query = $connection->query($requete);
                $personne = $query->fetch(PDO::FETCH_OBJ);
                $nom = $personne->nom;
                $age = $personne->age;
            
                
            } catch (PDOException $e) {
                echo "Erreur lors de l'insertion des donnees : ", $e->getMessage();
            }
            // Affichage du formulaire
            include("formulairePersonne.html");   
            $zonePrincipale = $corps;
        } else {
            // Traitement des donnÃ©es du formulaire
            $nom = isset($_POST['nom']) ? trim($_POST['nom']) : null;
            $age = isset($_POST['age']) ? trim($_POST['age']) : null;

            // VÃ©rification des champs
            if ($nom == "") {
                $erreur["nom"] = "Il manque un nom";
            }
            if ($age == "") {
                $erreur["age"] = "Il manque un Ã¢ge";
            } elseif (!is_numeric($age)) {
                $erreur["age"] = "L'Ã¢ge doit Ãªtre numÃ©rique";
            }

            // Comptage des erreurs
            $compteur_erreur = count(array_filter($erreur));

            // Traitement en fonction du nombre d'erreurs
            if ($compteur_erreur == 0) {
            
            $corps = " <form action='index.php?action=update&idP={$idP}&age={$age}&nom={$nom}' method='POST'>
                <p>Etes vous sur de vouloir mettre Ã  jour cette personne ?</p>
                <p>
                    <input type='submit' value='Enregistrer' class='btn btn-danger'>
                    <a href='index.php' class='btn btn-secondary'>Annuler</a>
                </p>
                </form> ";
                $zonePrincipale = $corps;
            }
        }

        break;
    

    
    case "saisir": // Saisie via le formulaire
    $connection =connecter();
        $cible='saisir'; //insertion des données
        if (!isset($_POST["nom"]) && !isset($_POST["age"])) {
        		$idP='';
            // Affichage du formulaire
            include("formulairePersonne.html");
            $zonePrincipale = $corps;
        } else {
            // Traitement des données du formulaire
            $nom = isset($_POST['nom']) ? trim($_POST['nom']) : null;
            $age = isset($_POST['age']) ? trim($_POST['age']) : null;

            // Vérification des champs
            if ($nom == "") {
                $erreur["nom"] = "Il manque un nom";
            }
            if ($age == "") {
                $erreur["age"] = "Il manque un âge";
            } elseif (!is_numeric($age)) {
                $erreur["age"] = "L'âge doit être numérique";
            }

            // Comptage des erreurs
            $compteur_erreur = count(array_filter($erreur));

            // Traitement en fonction du nombre d'erreurs
            if ($compteur_erreur == 0) {
                
                $nom_encode  = $connection->quote($nom);
                $age_encode = $connection->quote($age);
   				 
   				  
                    
                // Exécution de la requête d'insertion non préparée
                // A compléter ....
                // ...
                
                // Ecrire la requête d'insertion ...
                $requete = "INSERT INTO Personne (nom,age)
                            VALUES ($nom_encode, $age_encode)";
                
                // Exécuter la requête avec query
                $connection->query($requete);
                    
                // A compléter ....
                // ...
                // Récupérer l'identifiant de la dernière donnée insérée $idP = ....
                $idP = $connection->lastInsertId();  

                //$corps="<h1>A Compléter ... !</h1> ";
                $corps .= "Insertion de : ". $nom_encode . " agé de " . $age_encode . " ans avec comme clé principale <u>" .$idP . "</u><br/>";
                
                $zonePrincipale = $corps;

            } else {
                // Affichage du formulaire avec les erreurs
                include("formulairePersonne.html");
                $zonePrincipale = $corps;
            }
        }
        break;
        
 
        case'update':
            $idP = $_GET["idP"];
            $nom = $_GET["nom"];
            $age = $_GET["age"];
            
             $connection =connecter();
             if($connection){ 
                // Encoder les valeurs des chaÃ®nes de caractÃ¨res
                $nom_encode = htmlspecialchars($nom, ENT_QUOTES);
        
                // Ã‰chapper les valeurs de chaÃ®nes de caractÃ¨res
                $nomEchappe = $connection->quote($nom_encode);
    
    
                $requete = "UPDATE Personne
                SET nom = $nomEchappe, age = $age
                WHERE idP = $idP";
    
                $connection->query($requete);
            }else {
                echo "ya une erreur de connexion a la base données !!!!!!!!!! : ";
            }
            
            $corps = "<h1>Mise a jour de la personne $idP</h1>";
            //$corps .= "". $nom_encode . " " . $age. " " .$idP . "<br/>";
            $corps .= "<div style='font-size: 40px;'>". $idP . " " . $nom_encode. " " .$age . "</div><br/>";

            $zonePrincipale = $corps;
            break;
       
      



           
    case "supprimer": //un id particulier
        $idP=$_GET["idP"];
        $corps="<h1>Suppression de la personne ".$idP."</h1>" ;
        
        $corps.="<h1>Etes vous sure de vouloir supprimer cette personne</h1> ";
         $corps .= "<form method='POST' action='index.php?action=delete'>";
          $corps .= "<input type='hidden' name='idP' value='{$idP}'>";
         $corps .= "<input type='submit' value='Enregistrer' class='btn btn-danger'>";
         $corps .= "<a href='index.php' class='btn btn-secondary'>Annuler</a>";
    		$corps .= "</form>";
        $zonePrincipale=$corps ;
        $connection = null;
        break;
    default:
        $zonePrincipale = "";
        break;

}

// Inclusion du squelette HTML
include("frag_fin.php");
?>
