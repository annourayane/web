<?php
require_once '../../classeVente/modelVente.php';

if (isset($_POST['Id_Vente'])) {
    $connection = connecter();
    $vente = new Vente('', 0, 0.0, '');
    $vente->setId_Vente($_POST['Id_Vente']);

    if ($vente->supprimer()) { 
        header("Location: index.php"); 
        exit();
    } else {
        echo "Erreur lors de la suppression de la vente.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/admin.css">
    <title>Confirmation de suppression</title>
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
            <h1>Confirmation de suppression</h1>
            <p>Êtes-vous sûr de vouloir supprimer cette vente ?</p>
            
            <form action="delete.php" method="POST">
                <input type="hidden" name="Id_Vente" value="<?php echo $_GET['Id_Vente']; ?>">
                
                <button type="submit" class="Enregistrer" name="submit">Enregistrer</button>
                <button class="ConfirmationAnnuler"><a href="index.php">Annuler</a></button>
            </form>
        </div>
    </div>
</body>
</html>
