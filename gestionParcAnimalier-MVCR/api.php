<?php
set_include_path("./src");

require_once('ApiRouter.php');
require_once('model/AnimalStorageSession.php');
require_once("model/AnimalStorage.php");
// Démarrer la session
session_start();

// Initialiser le stockage
$storage = new AnimalStorageSession();

// Initialiser et exécuter le routeur API
$router = new ApiRouter();
$router->main($storage);
