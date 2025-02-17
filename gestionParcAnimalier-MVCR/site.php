<?php
/*
 * On indique que les chemins des fichiers qu'on inclut
 * seront relatifs au répertoire src.
 */
set_include_path("./src");

/* Inclusion des classes utilisées dans ce fichier */
require_once("PathInfoRouter.php");
require_once("model/AnimalStorageMySQL.php");
require_once("../../private/mysql_config.php"); 

session_start(); 

try {
    /*initisation de la connex a la bbd avec un instance de pdo*/
    $pdo = new PDO(
        'mysql:host=' . MYSQL_HOST . ';
        dbname=' . MYSQL_DB . ';
        charset=utf8', 
        MYSQL_USER, 
        MYSQL_PASSWORD
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//gestion d'erreur 
    $storage = new AnimalStorageMySQL($pdo);

} catch (PDOException $e) {
    die("Erreur : Impossible de se connecter à la base de données. " . $e->getMessage());
}
/*
 * Cette page est simplement le point d'arrivée de l'internaute
 * sur notre site. On se contente de créer un routeur
 * et de lancer son main.
 */
$router = new PathInfoRouter();
$router->main($storage);
?>
     