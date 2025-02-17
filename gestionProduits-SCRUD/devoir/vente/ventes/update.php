<?php 
ob_start();
require_once '../../classeVente/modelVente.php';

$connection = connecter();
$erreur = [
    "Description" => null,
    "Quantite" => null,
    "Prix_Total" => null,
    "Date" => null
]; 

$message = '';

$id = isset($_GET["Id_Vente"]) ? $_GET["Id_Vente"] : null;

if (!$id) {
    $message = 'ID de vente non spécifié.';
    exit();
}

$Vente = Vente::getDetail($id, $connection);

if (!$Vente) {
    $message = 'Vente non trouvée.';
    exit();
}

$Description = $Vente->getDescription();
$Quantite = $Vente->getQuantite();
$Prix_Total = $Vente->getPrix_Total();
$Date = $Vente->getDate();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $Description = isset($_POST['Description']) ? trim($_POST['Description']) : null;
    $Quantite = isset($_POST['Quantite']) ? trim($_POST['Quantite']) : null;
    $Prix_Total = isset($_POST['Prix_Total']) ? trim($_POST['Prix_Total']) : null;
    $Date = isset($_POST['Date']) ? trim($_POST['Date']) : null;

    if (empty($Description)) {
        $erreur["Description"] = "Vous devez remplir la description.";
    } elseif (is_numeric($Description)) {
        $erreur["Description"] = "La description ne doit pas être un nombre.";
    }
    
    if (empty($Quantite)) {
        $erreur["Quantite"] = "Vous devez remplir la quantité.";
    } elseif (!is_numeric($Quantite)) {
        $erreur["Quantite"] = "La quantité doit être un nombre.";
    }
    
    if (empty($Prix_Total)) {
        $erreur["Prix_Total"] = "Vous devez remplir le prix total.";
    } elseif (!is_numeric($Prix_Total)) {
        $erreur["Prix_Total"] = "Le prix total doit être un nombre.";
    }
    
    if (empty($Date)) {
        $erreur["Date"] = "Vous devez choisir une date.";
    }

    if (!array_filter($erreur)) { 
        $Vente->modifier($Description, $Quantite, $Prix_Total, $Date, $id, $connection);
        $message = 'Vente modifiée avec succès.';
        header("Location: index.php");
        exit(); 
    } 
}
ob_end_flush();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/admin.css">
    <title>Modifier Vente</title>
</head>
<body>
    <header>
        <div class="logo">
            <h1 class="admin-txt"><span>TechAchat & </span>Réparation</h1>
        </div>
        <ul class="nav">
            <li>14 GSM Caen </li>
        </ul>
    </header>
    <div class="admin-wrapper">
        <div class="menu">
            <ul>
                <li><a href="../../index.php">Historique des ventes </a></li>
            </ul>
        </div>
        <div class="contenue">
            <h2 class="title">Modifier Vente</h2>
            <form action="" method="POST" class="form">
                <label>Description :</label><br>
                <input type="text" name="Description" value="<?php echo $Description; ?>" placeholder="Ex : Iphone 15 Pro Max"><br>
                <span class="w3-text-red"><?php echo $erreur["Description"] ?? ''; ?></span><br/>
                
                <label>Quantite :</label><br>
                <input type="text" name="Quantite" value="<?php echo $Quantite; ?>" placeholder="Ex: 1"><br>
                <span class="w3-text-red"><?php echo $erreur["Quantite"] ?? ''; ?></span><br/>

                <label>Prix Total :</label><br>
                <input type="text" name="Prix_Total" value="<?php echo $Prix_Total; ?>" placeholder="Ex: 1200.00"><br>
                <span class="w3-text-red"><?php echo $erreur["Prix_Total"] ?? ''; ?></span><br/>

                <label>Date :</label><br>
                <input type="date" name="Date" value="<?php echo $Date; ?>"><br>
                <span class="w3-text-red"><?php echo $erreur["Date"] ?? ''; ?></span><br/>
                
                <button type="submit" class="ajt" name="submit"><span>Modifier</span></button>
                <button type="submit" class="annuler"><a href="index.php">Annuler</a></button>
            </form>
            <?php if($message): ?>
                <p><?php echo $message; ?></p>
            <?php endif; ?>
        </div>
    </div>
    <!-- jquery-->
    
    <!-- js-->
    <script src="../js/script.js"></script>
</body>
</html>
