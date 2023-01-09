<?php

// Die Methoden innerhalb der Klasse setzen eine Datenbankverbindung voraus

class userController
{

    private PDO $conn;

    // Parameter ist eine Datenbankinstanz
    private $email = "";
    private $password = "";
    private $username =  "";

    public function __construct(DB $database)
    {
        // Verbindungsmethode der DB Klasse wird aufgerufen, der Returnwert (die Verbindung) wird der $conn Variable zugewiesen

        $this->conn = $database->getConnection();
    }

    //Methode, um alle Produkte aus der DB zu holen - Returnwert ist ein Array

    public function getAll(): array
    {

        $sql = "SELECT * FROM form_member";

        $stmt = $this->conn->query($sql);

        $data = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $data[]  = $row;
        }

        return $data;
    }

    public function assignUserInput($emailInput, $passwordInput)
    {

        $this->email = $emailInput;
        $this->password = $passwordInput;
    }

    public function getuserByEmail()
    {

        // muss pw decoden iwie

        $sql = $this->conn->prepare('SELECT * FROM form_member WHERE email=?');
        $sql->bindParam(1, $this->email);
        $sql->execute();

        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {

            $data[]  = $row;
        }

        if (!empty($data)) {

            return $data;
        } else {

            $_SESSION['loginfailure'] = true;
            return false;
        }
    }

    public function getuserByName()
    {

        $sql = $this->conn->prepare('SELECT * FROM form_member WHERE username=?');
        $sql->bindParam(1, $this->username);
        $sql->execute();

        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {

            $data[]  = $row;
        }

        if (!empty($data)) {

            return $data;
        } else {

            return false;
        }
    }

    public function getuserById($id)
    {

        // muss pw decoden iwie

        $sql = $this->conn->prepare('SELECT * FROM form_member WHERE id=?');
        $sql->bindParam(1, $id);
        $sql->execute();

        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {

            $data[]  = $row;
        }

        if (!empty($data)) {

            return $data;
        } else {

            $_SESSION['loginfailure'] = true;
            return false;
        }
    }
    public function checkIfCorrect(array $data)
    {
        foreach ($data as $row) {

            if ($this->email === $row['email'] && password_verify($this->password, $row['password'])) {
                return true;
            }
        }
        return false;
    }


    public function create($username, $email, $password, $name, $vorname)
    {

        $this->username = $username;

        if (!empty($db_entries = $this->getuserByEmail($email))) {
            echo "email vergeben"; //$this -> errorMessage("Email vergeben");
        } else if (!empty($db_entries = $this->getuserByName())) {
            echo "Username bereits vergeben";
        } else {

            $sql = "INSERT INTO `form_member` (`username`, `email`, `password`, `name`, `vorname`) VALUES (?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindValue(1, $username, PDO::PARAM_STR);
            $stmt->bindValue(2, $email, PDO::PARAM_STR);
            $stmt->bindValue(3, $password, PDO::PARAM_STR);
            $stmt->bindValue(4, $name, PDO::PARAM_STR);
            $stmt->bindValue(5, $vorname, PDO::PARAM_STR);

            $stmt->execute();

            return $this->conn->lastInsertId();
        }
    }
}
