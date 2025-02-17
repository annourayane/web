<?php
// Définition des types pour les variables
$nom = null;
$age = null;
$erreur = ["nom" => null, "age" => null];


// Fonction de connexion à la base de données
function connecter(): ?PDO
{
    require_once('../../../../../private/config.php');


    // Options de connexion
    $options = [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];

    // Connexion à la base de données
    try {
        $dsn = DB_HOST . DB_NAME;
        $connection = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $connection;
    } catch (PDOException $e) {
        echo "Connexion à MySQL impossible : ", $e->getMessage();
        //exit(); // Arrêter l'exécution du script en cas d'échec de connexion
        return null;
    }
}



?>

