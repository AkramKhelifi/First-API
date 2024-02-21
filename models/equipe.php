<?php

class Equipe
{
    // Connexion à la base de données et nom de la table
    private $conn;
    private $table_name = "equipe";

    // Propriétés de l'objet Equipe correspondant aux colonnes de la table
    public $NE;
    public $NOM;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Lire toutes les équipes
    public function read()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Créer une nouvelle équipe
    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " SET NE=:NE, NOM=:NOM";

        $stmt = $this->conn->prepare($query);

        // Nettoyer les données
        $this->NE = htmlspecialchars(strip_tags($this->NE));
        $this->NOM = htmlspecialchars(strip_tags($this->NOM));

        // Lier les valeurs
        $stmt->bindParam(":NE", $this->NE);
        $stmt->bindParam(":NOM", $this->NOM);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Mettre à jour une équipe
    public function update()
    {
        $query = "UPDATE " . $this->table_name . " SET NOM=:NOM WHERE NE=:NE";

        $stmt = $this->conn->prepare($query);

        // Nettoyer les données
        $this->NE = htmlspecialchars(strip_tags($this->NE));
        $this->NOM = htmlspecialchars(strip_tags($this->NOM));

        // Lier les valeurs
        $stmt->bindParam(":NE", $this->NE);
        $stmt->bindParam(":NOM", $this->NOM);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Supprimer une équipe
    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE NE=:NE";

        $stmt = $this->conn->prepare($query);

        // Nettoyer les données
        $this->NE = htmlspecialchars(strip_tags($this->NE));

        // Lier la valeur
        $stmt->bindParam(":NE", $this->NE);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Méthode pour récupérer les équipes et le nombre de projets qui leur appartiennent
    public function readTeamsAndProjectCount() {
        $query = "SELECT equipe.NOM, COUNT(projet.NE) as nombre_projets 
                  FROM " . $this->table_name . " 
                  LEFT JOIN projet ON equipe.NE = projet.NE 
                  GROUP BY equipe.NOM";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

}

