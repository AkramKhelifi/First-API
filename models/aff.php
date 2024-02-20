<?php
class Aff {
    // Connexion à la base de données et nom de la table
    private $conn;
    private $table_name = "aff";

    // Propriétés de l'objet Aff correspondant aux colonnes de la table
    public $NP;
    public $NC;
    public $ANNEE;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lire toutes les affiliations
    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Créer une nouvelle affiliation
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET NP=:NP, NC=:NC, ANNEE=:ANNEE";

        $stmt = $this->conn->prepare($query);

        // Nettoyer les données
        $this->NP=htmlspecialchars(strip_tags($this->NP));
        $this->NC=htmlspecialchars(strip_tags($this->NC));
        $this->ANNEE=htmlspecialchars(strip_tags($this->ANNEE));

        // Lier les valeurs
        $stmt->bindParam(":NP", $this->NP);
        $stmt->bindParam(":NC", $this->NC);
        $stmt->bindParam(":ANNEE", $this->ANNEE);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Mettre à jour une affiliation
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET ANNEE=:ANNEE 
                  WHERE NP=:NP AND NC=:NC";

        $stmt = $this->conn->prepare($query);

        // Nettoyer les données
        $this->NP=htmlspecialchars(strip_tags($this->NP));
        $this->NC=htmlspecialchars(strip_tags($this->NC));
        $this->ANNEE=htmlspecialchars(strip_tags($this->ANNEE));

        // Lier les valeurs
        $stmt->bindParam(":NP", $this->NP);
        $stmt->bindParam(":NC", $this->NC);
        $stmt->bindParam(":ANNEE", $this->ANNEE);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Supprimer une affiliation
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE NP=:NP AND NC=:NC";

        $stmt = $this->conn->prepare($query);

        // Nettoyer les données
        $this->NP=htmlspecialchars(strip_tags($this->NP));
        $this->NC=htmlspecialchars(strip_tags($this->NC));

        // Lier les valeurs
        $stmt->bindParam(":NP", $this->NP);
        $stmt->bindParam(":NC", $this->NC);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}

