<?php

class Chercheur
{
    // Connexion à la base de données et nom de la table
    private $conn;
    private $table_name = "chercheur";

    // Propriétés de l'objet correspondant aux colonnes de la table
    public $NC;
    public $NOM;
    public $PRENOM;
    public $NE;

    // Constructeur avec $db comme connexion à la base de données
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Lire les chercheurs
    public function read()
    {
        // Sélectionnez tous les enregistrements
        $query = "SELECT * FROM " . $this->table_name;

        // Préparez la déclaration de requête
        $stmt = $this->conn->prepare($query);

        // Exécutez la requête
        $stmt->execute();

        return $stmt;
    }

    // Créer un chercheur
    public function create()
    {
        // Requête pour insérer un enregistrement
        $query = "INSERT INTO " . $this->table_name . " SET NC=:NC, NOM=:NOM, PRENOM=:PRENOM, NE=:NE";

        // Préparez la déclaration de requête
        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->NC = htmlspecialchars(strip_tags($this->NC));
        $this->NOM = htmlspecialchars(strip_tags($this->NOM));
        $this->PRENOM = htmlspecialchars(strip_tags($this->PRENOM));
        $this->NE = htmlspecialchars(strip_tags($this->NE));

        // Bind values
        $stmt->bindParam(":NC", $this->NC);
        $stmt->bindParam(":NOM", $this->NOM);
        $stmt->bindParam(":PRENOM", $this->PRENOM);
        $stmt->bindParam(":NE", $this->NE);

        // Exécutez la requête
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Mettre à jour le chercheur
    public function update()
    {
        // Requête pour mettre à jour un enregistrement
        $query = "UPDATE " . $this->table_name . " SET NOM = :NOM, PRENOM = :PRENOM, NE = :NE WHERE NC = :NC";

        // Préparez la déclaration de requête
        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->NOM = htmlspecialchars(strip_tags($this->NOM));
        $this->PRENOM = htmlspecialchars(strip_tags($this->PRENOM));
        $this->NE = htmlspecialchars(strip_tags($this->NE));
        $this->NC = htmlspecialchars(strip_tags($this->NC));

        // Bind new values
        $stmt->bindParam(":NOM", $this->NOM);
        $stmt->bindParam(":PRENOM", $this->PRENOM);
        $stmt->bindParam(":NE", $this->NE);
        $stmt->bindParam(":NC", $this->NC);

        // Exécutez la requête
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Supprimer un chercheur
    public function delete()
    {
        // Requête pour supprimer un enregistrement
        $query = "DELETE FROM " . $this->table_name . " WHERE NC = ?";

        // Préparez la déclaration de requête
        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->NC = htmlspecialchars(strip_tags($this->NC));

        // Bind NC
        $stmt->bindParam(1, $this->NC);

        // Exécutez la requête
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}


