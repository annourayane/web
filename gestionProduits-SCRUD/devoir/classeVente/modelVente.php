<?php

function connecter(): ?PDO
{
    $dns = 'mysql:host=mysql.info.unicaen.fr;dbname=annou231_bd';
    $utilisateur = 'annou231';
    $motDePasse = 'ashaih4chuuthohY';


    $options = [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];


    try {
        $connection = new PDO($dns, $utilisateur, $motDePasse, $options);
        return $connection;
    } catch (PDOException $e) {
        echo "Connexion à MySQL impossible : ", $e->getMessage();
        return null;
    }
}

class Vente {
    private $Id_Vente;
    private $Description;
    private $Quantite;
    private $Prix_Total;
    private $Date;
    private $connexion;

    // Constructeur
    public function __construct($Description, $Quantite, $Prix_Total, $Date) {
        $this->Description = $Description;
        $this->Quantite = $Quantite;
        $this->Prix_Total = $Prix_Total;
        $this->Date = $Date;
        $this->connexion = connecter();
    }

    // Getters
    public function getId_Vente() {
        return $this->Id_Vente;
    }

    public function setId_Vente($Id_Vente) {
        $this->Id_Vente = $Id_Vente;
    }
    public function getDescription(): ?string {
        return $this->Description;
    }

    public function getQuantite(): ?int {
        return $this->Quantite;
    }

    public function getPrix_Total(): ?float {
        return $this->Prix_Total;
    }

    public function getDate(): ?string {
        return $this->Date;
    }


    public function __toString() {
        return "Id_Vente: {$this->Id_Vente}, Description: {$this->Description}, Quantite: {$this->Quantite}, Prix_Total: {$this->Prix_Total}, Date: {$this->Date}";
    }



public static function getAllVentes(PDO $connexion): array {
    $query = "SELECT * FROM vente";
    $stmt = $connexion->prepare($query);
    $stmt->execute();

    $ventes = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $vente = new Vente($row['Description'], $row['Quantite'], $row['Prix_Total'], $row['Date']);
        $vente->setId_Vente($row['Id_Vente']);
        $ventes[] = $vente;
    }

    return $ventes;
}

public function ajouterVente(): bool {
    $query = "INSERT INTO vente (Description, Quantite, Prix_Total, Date) VALUES (:Description, :Quantite, :Prix_Total, :Date)";
    
    try {
        $stmt = $this->connexion->prepare($query);
        $params = [
            ':Description' => $this->Description,
            ':Quantite' => $this->Quantite,
            ':Prix_Total' => $this->Prix_Total,
            ':Date' => $this->Date
        ];
        
        return $stmt->execute($params);
        
    } catch (PDOException $e) {
        echo "Erreur lors de l'ajout de la vente : " . $e->getMessage();
        return false;
    }
}

public function supprimer(): bool {
    $query = "DELETE FROM vente WHERE Id_Vente = :Id_Vente";

    try {
        $stmt = $this->connexion->prepare($query);
        $params = [
            ':Id_Vente' => $this->Id_Vente
        ];

        return $stmt->execute($params);
        
    } catch (PDOException $e) {
        echo "Erreur lors de la suppression de la vente : " . $e->getMessage();
        return false;
    }
}

public static function searchVente(PDO $connexion, string $description): array {
    $description = trim($description);
    $query = "SELECT * FROM vente WHERE Description LIKE :Description";
    $stmt = $connexion->prepare($query);
    $stmt->execute([':Description' => '%'.$description.'%']);

    $ventes = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $vente = new Vente($row['Description'], $row['Quantite'], $row['Prix_Total'], $row['Date']);
        $vente->setId_Vente($row['Id_Vente']);
        $ventes[] = $vente;
    }

    return $ventes;
}

public function modifier($description, $quantite, $prixTotal, $date, $idVente, PDO $connexion) {
    try {
        $sql = "UPDATE vente SET Description = :Description, Quantite = :Quantite, Prix_Total = :PrixTotal, Date = :Date WHERE Id_Vente = :id_Vente";
        $stmt = $connexion->prepare($sql);

        $result = $stmt->execute(array(
            ':Description' => $description,
            ':Quantite' => $quantite,
            ':PrixTotal' => $prixTotal,
            ':Date' => $date,
            ':id_Vente' => $idVente
        ));

        if (!$result) {
            throw new Exception("Erreur lors de la modification de la vente.");
        }
        return true;
    } catch (Exception $e) {
        throw new Exception("Erreur lors de la modification de la vente: " . $e->getMessage());
    }
}

public static function getDetail($idVente, PDO $connexion) {
    try {
        $sql = "SELECT * FROM vente WHERE Id_Vente = :id_Vente";
        $stmt = $connexion->prepare($sql);
        $stmt->execute([':id_Vente' => $idVente]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $vente = new Vente($row['Description'], $row['Quantite'], $row['Prix_Total'], $row['Date']);
            $vente->setId_Vente($row['Id_Vente']);
            return $vente;
        }
        return null;
    } catch (Exception $e) {
        throw new Exception("Erreur lors de la récupération des détails de la vente: " . $e->getMessage());
    }
}


    // Destructeur
    public function __destruct() {
        $this->connexion = null;
    }
}



?>
