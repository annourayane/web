<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/admin.css">
    <title>Gestion Des Ventes </title>
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
            <div class="boutton">
                <a href="create.php" class="ajouter" >+Ajouter Vente</a>
                
            </div>
            <div class="boutton">
                <a href="select.php" class="rechercher" >Rechercher Vente</a>
                
            </div>

            <div class="table">
                <h2 class="title"> Historique Des Ventes :</h2>
                
                <table class="row">
                <thead>
                <th>Id_Vente</th>
                <th>Description</th>
            
                <th colspan="3">Action</th>
                </thead>
                <tbody>
                <?php 
                require_once '../../classeVente/modelVente.php';  
                $connection = connecter();
                $ventes = Vente::getAllVentes($connection);
                foreach ($ventes as $vente): ?>
                <tr>
                <td><?php echo $vente->getId_Vente(); ?></td>
                <td><?php echo $vente->getDescription(); ?></td>
        
                <td><a class='modifier' href="Detail.php?Id_Vente=<?php echo $vente->getId_Vente(); ?>">Détail</a></td>
                <td><a class='modifier' href="update.php?Id_Vente=<?php echo $vente->getId_Vente(); ?>">Modifier</a></td>
                <td><a class='supprimer' href="delete.php?Id_Vente=<?php echo $vente->getId_Vente(); ?>">Supprimer</a></td>


            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

            </div>

        </div>
    </div>
    <!-- jquery-->
    
    <!-- js-->
    <script src="../js/script.js"></script>
   
</body>
</html>
