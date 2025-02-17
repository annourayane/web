<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/admin.css">
    <title>Rechercher une vente</title>
</head>

<body>
    <header class="header">
        <div class="logo">
            <h1 class="admin-txt">TechAchat & Réparation</h1>
        </div>
        <nav class="nav">
            <ul>
                <li>14 GSM Caen</li>
            </ul>
        </nav>
    </header>

    <div class="admin-wrapper">
        <aside class="menu">
            <ul>
                <li><a href="../../index.php">Historique des ventes</a></li>
            </ul>
        </aside>

        <main class="content">
            <h1>Effectuer une recherche par Description</h1>

            <form action="select.php" method="POST" class="search-form">
                <label for="description">Description : (Veuillez saisir la description exacte de la vente et assurez-vous de ne pas ajouter d'espace à la fin de la description)</label>

                <input type="text" name="description" id="description" placeholder="Ex: Téléphone Samsung Galaxy S21">
                <div class="button-group">
                    <button type="submit" class="btn ajt" name="submit">Chercher</button>
                    <a href="index.php" class="btn annuler">Revenir</a>
                </div>
            </form>

            <div class="tableSelect">
                <h2 class="titleSelect">Résultat de la recherche</h2>

                <table class="rowSelect">
                    <thead>
                        <tr>
                            <th>Id_Vente</th>
                            <th>Description</th>
                           
                            <th colspan="3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    require_once '../../classeVente/modelVente.php';

                    $ventes = [];
                        
                    if(isset($_POST['description'])) {
                            $description = $_POST['description'];
                            $connection = connecter();
                        
                            if(!empty($description)) {
                                $ventes = Vente::searchVente($connection, $description);
                            }
                    } 
                    if(!empty($ventes)) {
                     foreach ($ventes as $vente): 
                    ?>
                    <tr>
                    <td><?php echo $vente->getId_Vente(); ?></td>
                    <td><?php echo $vente->getDescription(); ?></td>
                    <td><a class='modifier' href="Detail.php?Id_Vente=<?php echo $vente->getId_Vente(); ?>">Détail</a></td>
                    <td><a class='modifier' href="update.php?Id_Vente=<?php echo $vente->getId_Vente(); ?>">Modifier</a></td>
                    <td><a class='supprimer' href="delete.php?Id_Vente=<?php echo $vente->getId_Vente(); ?>">Supprimer</a></td>
                    </tr>
                    <?php endforeach; } else { ?>
                    <tr>
                    <td colspan="7">Vide .</td>
                    </tr>
                     <?php } 
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script src="../js/script.js"></script>
</body>

</html>
