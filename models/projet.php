<?php
class Projet {
    private $conn;
    private $table_name = "projet";

    public $NP;
    public $NOM;
    public $BUDGET;
    public $NE;

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
        $query = "INSERT INTO " . $this->table_name . " SET NP=:NP, NOM=:NOM, BUDGET=:BUDGET, NE=:NE";

        $stmt = $this->conn->prepare($query);

        $this->NP=htmlspecialchars(strip_tags($this->NP));
        $this->NOM=htmlspecialchars(strip_tags($this->NOM));
        $this->BUDGET=htmlspecialchars(strip_tags($this->BUDGET));
        $this->NE=htmlspecialchars(strip_tags($this->NE));

        $stmt->bindParam(":NP", $this->NP);
        $stmt->bindParam(":NOM", $this->NOM);
        $stmt->bindParam(":BUDGET", $this->BUDGET);
        $stmt->bindParam(":NE", $this->NE);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET NOM=:NOM, BUDGET=:BUDGET, NE=:NE 
                  WHERE NP=:NP";

        $stmt = $this->conn->prepare($query);

        $this->NP=htmlspecialchars(strip_tags($this->NP));
        $this->NOM=htmlspecialchars(strip_tags($this->NOM));
        $this->BUDGET=htmlspecialchars(strip_tags($this->BUDGET));
        $this->NE=htmlspecialchars(strip_tags($this->NE));

        $stmt->bindParam(":NP", $this->NP);
        $stmt->bindParam(":NOM", $this->NOM);
        $stmt->bindParam(":BUDGET", $this->BUDGET);
        $stmt->bindParam(":NE", $this->NE);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE NP=:NP";

        $stmt = $this->conn->prepare($query);

        $this->NP=htmlspecialchars(strip_tags($this->NP));

        $stmt->bindParam(":NP", $this->NP);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getUniqueBudgets() {
        $query = "SELECT DISTINCT BUDGET FROM " . $this->table_name . " WHERE BUDGET IS NOT NULL ORDER BY BUDGET DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getBySpecificBudgetRange() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE BUDGET BETWEEN 400000 AND 900000";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }


}

