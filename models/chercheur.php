<?php

class Chercheur
{
    private $conn;
    private $table_name = "chercheur";

    public $NC;
    public $NOM;
    public $PRENOM;
    public $NE;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read()
    {
        $query = "SELECT * FROM " . $this->table_name;

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " SET NC=:NC, NOM=:NOM, PRENOM=:PRENOM, NE=:NE";

        $stmt = $this->conn->prepare($query);

        $this->NC = htmlspecialchars(strip_tags($this->NC));
        $this->NOM = htmlspecialchars(strip_tags($this->NOM));
        $this->PRENOM = htmlspecialchars(strip_tags($this->PRENOM));
        $this->NE = htmlspecialchars(strip_tags($this->NE));

        $stmt->bindParam(":NC", $this->NC);
        $stmt->bindParam(":NOM", $this->NOM);
        $stmt->bindParam(":PRENOM", $this->PRENOM);
        $stmt->bindParam(":NE", $this->NE);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function update()
    {
        $query = "UPDATE " . $this->table_name . " SET NOM = :NOM, PRENOM = :PRENOM, NE = :NE WHERE NC = :NC";

        $stmt = $this->conn->prepare($query);

        $this->NOM = htmlspecialchars(strip_tags($this->NOM));
        $this->PRENOM = htmlspecialchars(strip_tags($this->PRENOM));
        $this->NE = htmlspecialchars(strip_tags($this->NE));
        $this->NC = htmlspecialchars(strip_tags($this->NC));

        $stmt->bindParam(":NOM", $this->NOM);
        $stmt->bindParam(":PRENOM", $this->PRENOM);
        $stmt->bindParam(":NE", $this->NE);
        $stmt->bindParam(":NC", $this->NC);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE NC = ?";

        $stmt = $this->conn->prepare($query);

        $this->NC = htmlspecialchars(strip_tags($this->NC));

        $stmt->bindParam(1, $this->NC);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function readWithTeam() {
        $query = "SELECT chercheur.NC, chercheur.NOM, chercheur.PRENOM, equipe.NOM as NOM_EQUIPE 
              FROM " . $this->table_name . " 
              JOIN equipe ON chercheur.NE = equipe.NE";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
public function readQualifiedResearchers() {
    $query = "SELECT nom, prenom FROM req5";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
}

}


