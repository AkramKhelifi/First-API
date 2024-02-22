<?php
class Aff {
    private $conn;
    private $table_name = "aff";

    public $NP;
    public $NC;
    public $ANNEE;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET NP=:NP, NC=:NC, ANNEE=:ANNEE";

        $stmt = $this->conn->prepare($query);

        $this->NP=htmlspecialchars(strip_tags($this->NP));
        $this->NC=htmlspecialchars(strip_tags($this->NC));
        $this->ANNEE=htmlspecialchars(strip_tags($this->ANNEE));

        $stmt->bindParam(":NP", $this->NP);
        $stmt->bindParam(":NC", $this->NC);
        $stmt->bindParam(":ANNEE", $this->ANNEE);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET ANNEE=:ANNEE 
                  WHERE NP=:NP AND NC=:NC";

        $stmt = $this->conn->prepare($query);

        $this->NP=htmlspecialchars(strip_tags($this->NP));
        $this->NC=htmlspecialchars(strip_tags($this->NC));
        $this->ANNEE=htmlspecialchars(strip_tags($this->ANNEE));

        $stmt->bindParam(":NP", $this->NP);
        $stmt->bindParam(":NC", $this->NC);
        $stmt->bindParam(":ANNEE", $this->ANNEE);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE NP=:NP AND NC=:NC";

        $stmt = $this->conn->prepare($query);

        $this->NP=htmlspecialchars(strip_tags($this->NP));
        $this->NC=htmlspecialchars(strip_tags($this->NC));

        $stmt->bindParam(":NP", $this->NP);
        $stmt->bindParam(":NC", $this->NC);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}

