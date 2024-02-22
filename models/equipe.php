<?php

class Equipe
{
    private $conn;
    private $table_name = "equipe";

    public $NE;
    public $NOM;

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
        $query = "INSERT INTO " . $this->table_name . " SET NE=:NE, NOM=:NOM";

        $stmt = $this->conn->prepare($query);

        $this->NE = htmlspecialchars(strip_tags($this->NE));
        $this->NOM = htmlspecialchars(strip_tags($this->NOM));

        $stmt->bindParam(":NE", $this->NE);
        $stmt->bindParam(":NOM", $this->NOM);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update()
    {
        $query = "UPDATE " . $this->table_name . " SET NOM=:NOM WHERE NE=:NE";

        $stmt = $this->conn->prepare($query);

        $this->NE = htmlspecialchars(strip_tags($this->NE));
        $this->NOM = htmlspecialchars(strip_tags($this->NOM));

        $stmt->bindParam(":NE", $this->NE);
        $stmt->bindParam(":NOM", $this->NOM);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE NE=:NE";

        $stmt = $this->conn->prepare($query);

        $this->NE = htmlspecialchars(strip_tags($this->NE));

        $stmt->bindParam(":NE", $this->NE);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

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

