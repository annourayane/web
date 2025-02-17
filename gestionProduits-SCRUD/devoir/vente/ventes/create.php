<?php
ob_start(); // Démarre la capture de la sortie

require_once '../../classeVente/modelVente.php';  
$connection = connecter();

$erreur = [
    "Description" => null,
    "Quantite" => null,
    "Prix_Total" => null,
    "Date" => null
]; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $description = $_POST["Description"];
    $prixTotal = floatval($_POST["Prix_Total"]); 
    $quantite = intval($_POST["Quantite"]); 
    $date = $_POST["Date"];

    
    if (empty($description)) {
        $erreur["Description"] = "Vous devez remplir la description.";
    }elseif (is_numeric($description)) {
        $erreur["Quantite"] = "La quantité doit être string.";
    }

    if (empty($quantite)) {
        $erreur["Quantite"] = "Vous devez remplir la quantité.";
    } elseif (!is_numeric($quantite)) {
        $erreur["Quantite"] = "La quantité doit être un nombre.";
    }

    if (empty($prixTotal)) {
        $erreur["Prix_Total"] = "Vous devez remplir le prix total.";
    } elseif (!is_numeric($prixTotal)) {
        $erreur["Prix_Total"] = "Le prix total doit être un nombre.";
    }

    if (empty($date)) {
        $erreur["Date"] = "Vous devez choisir une date.";
    }


    if (!array_filter($erreur)) { 
        $vente = new Vente($description, $quantite, $prixTotal, $date);


        if ($vente->ajouterVente()) {
            header("Location: index.php?success=1");
            exit();
        } else {
            header("Location: index.php?error=1");
            exit();
        }
    }
}
ob_end_flush(); // Envoie la sortie et désactive le tampon
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="../../css/admin.css">
    <title>Ajouter Ventes</title>
</head>
<body>
    <header>
        <div class="logo">
            <h1 class="admin-txt"><span>TechAchat & </span>Réparation</h1>
        </div>
        <ul class="nav">
            <li>14 GSM Caen</li>
        </ul>
    </header>
    <div class="admin-wrapper">
        <div class="menu">
            <ul>
                <li><a href="../../index.php">Historique des ventes</a></li>
            </ul>
        </div>
        <div class="contenue">
            <h2 class="title">Ajouter une Vente</h2>
            <form action="create.php" method="POST" class="form">
                <label>Description :</label><br>
                <input type="text" name="Description" placeholder="Ex : Iphone 15 Pro Max"><br>
                <span class="w3-text-red"><?php echo $erreur["Description"] ?? ''; ?></span><br/>

                <label>Quantite :</label><br>
                <input type="text" name="Quantite" placeholder="Ex: 1"><br>
                <span class="w3-text-red"><?php echo $erreur["Quantite"] ?? ''; ?></span><br/>

                <label>Prix Total :</label><br>
                <input type="text" name="Prix_Total" placeholder="Ex: 1200.00"><br>
                <span class="w3-text-red"><?php echo $erreur["Prix_Total"] ?? ''; ?></span><br/>
                
                <label>Date :</label><br>
                <input type="date" name="Date" placeholder="Ex: 12/12/2023"><br>
                <span class="w3-text-red"><?php echo $erreur["Date"] ?? ''; ?></span><br/>

                <button type="submit" class="ajt" name="submit"><span>+Ajouter</span></button>
                <button type="button" class="annuler" onclick="window.location.href='index.php'">Annuler</button>

            </form>
        </div>
    </div>
  
    <script src="../js/script.js"></script>
</body>
</html>
