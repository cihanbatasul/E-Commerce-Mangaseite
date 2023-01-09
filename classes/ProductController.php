<?php

// Die Methoden innerhalb der Klasse setzen eine Datenbankverbindung voraus

class ProductController
{

    private PDO $conn;
    private string $key;
    // Parameter ist eine Datenbankinstanz

    public function __construct(DB $database)
    {
        // Verbindungsmethode der DB Klasse wird aufgerufen, der Returnwert (die Verbindung) wird der $conn Variable zugewiesen

        $this->conn = $database->getConnection();
    }

    //Methode, um alle Produkte aus der DB zu holen - Returnwert ist ein Array

    public function getAll(): array
    {

        $sql = "SELECT * FROM produkte";

        $stmt = $this->conn->query($sql);

        $data = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $data[]  = $row;
        }


        return $data;
    }

    public function getAllByPrice()
    {
    }
    public function getProductById($product_id)
    {
        $sql = "SELECT * FROM produkte WHERE id=" . $product_id;
        $stmt = $this->conn->query($sql);
        $data = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $data[]  = $row;
        }
        return $data;
    }

    // Kategorien
    public function getCategoryItems()
    {
        $sql = "SELECT * FROM kategorien";
        $stmt = $this->conn->query($sql);
        $data = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $data[]  = $row;
        }
        return $data;
    }

    public function getCategoryByDescription(string $description)
    {
        $sql = "SELECT * FROM kategorien WHERE bezeichnung=" . $description;
        $stmt = $this->conn->query($sql);
        $data = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $data[]  = $row;
        }
        return $data;
    }

    public function doesItemMatchCategory(int $productGenre)
    {
        $sql = "SELECT * FROM produkte WHERE genre=" . $productGenre;
        $stmt = $this->conn->query($sql);
        $data = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $data[]  = $row;
        }
        return $data;
    }
    // Warenkorb
    public function addToCart(int $id)
    {
        $data = $this->getProductById($id);
        $product_id = $data[0]['id'];
        $product_name = $data[0]['name'];
        $product_price = $data[0]['preis'];
        $product_picUrl = $data[0]['picUrl'];
        $product_quantity = 1;

        // If the cart already contains items
        // Wenn der Warenkorb bereits Artikel enthält
        if (!empty($_SESSION["cart"])) {

            // Split session array by ID attribute
            // Teilt das Session-Array nach der ID-Nummer auf
            $acol = array_column($_SESSION['cart'], 'id');

            // If product ID is already in the array, increase quantity by 1
            // Wenn die Produkt-ID bereits im Array vorhanden ist, erhöhe die Menge um 1
            if (in_array($product_id, $acol)) {
                $_SESSION['cart'][$product_id]['quantity'] += 1;
            }

            // If product ID is not in the array, add a new array item
            // Wenn die Produkt-ID im Array nicht vorhanden ist, füge ein neues Array-Element hinzu
            $product_array = array(
                'id' => $product_id,
                "name" => $product_name,
                "price" => $product_price,
                "imgUrl" => $product_picUrl,
                "quantity" => 1
            );

            $_SESSION['cart'][$product_id] = $product_array;
        } else {
            // Cart is completely empty, add item directly
            // Der Warenkorb ist vollständig leer, fügt den Artikel direkt hinzu
            $_SESSION["cart"] = array();

            $product_array = array(
                'id' => $product_id,
                "name" => $product_name,
                "price" => $product_price,
                "imgUrl" => $product_picUrl,
                "quantity" => $product_quantity
            );

            $_SESSION['cart'][$product_id] = $product_array;
        }
    }

    // Bewertungen
    // Gibt die gerundete, Durchschnittsbewertung eines Produkts wieder
    public function getRating($product_id)
    {

        $sql = "SELECT ROUND(AVG(rating), 1) as numRating FROM produkte_bewertungen WHERE product_id='" . $product_id . "'";
        $stmt = $this->conn->query($sql);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    public function GetAllRatings()
    {
        $sql = "SELECT ROUND(AVG(rating), 1) as numRating, product_id FROM produkte_bewertungen GROUP BY product_id";
        $stmt = $this->conn->query($sql);
        $data = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $data[]  = $row;
        }
        return $data;
    }

    public function updateProductAvgRating()
    {
        $sql = "CREATE TEMPORARY TABLE avg_ratings AS
               SELECT ROUND(AVG(rating), 1) as avg_rating, product_id
               FROM produkte_bewertungen
               GROUP BY product_id";
        $sql2 = "UPDATE produkte p
               JOIN avg_ratings r ON p.id = r.product_id
               SET p.rating = r.avg_rating";
        $stmt = $this->conn->query($sql);
        $stmt2 = $this->conn->query($sql2);
    }

    // überprüfen, ob Benutzer Produkt bereits bewertet hat
    public function checkUserRating($userid, $productid)
    {
        $sql = "SELECT * FROM produkte_bewertungen WHERE user_id='" . $userid . "' AND product_id=" . $productid;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return true;
        } else {

            return false;
        }
    }
    // Benutzerbewertung in der Datenbank speichern
    public function pushRating($userid, $product_id, $rating, $comment)
    {

        $sql = "INSERT INTO produkte_bewertungen (`user_id`, `product_id`, `rating`, `kommentar`) VALUES(?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(1, $userid, PDO::PARAM_INT);
        $stmt->bindValue(2, $product_id, PDO::PARAM_INT);
        $stmt->bindValue(3, $rating, PDO::PARAM_INT);
        $stmt->bindValue(4, $comment, PDO::PARAM_STR);
        $stmt->execute();


        if ($stmt->rowCount() > 0) {
            // Ausführung erfolgreich
            return true;
        } else {
            // Keine Zeilen betroffen, Ausführung erfolglos
            return false;
        }
    }

    // Filter Products


    public function filterDesc($array)
    {

        usort($array, function ($a, $b,) {
            if ($a[$this->key] == $b[$this->key]) {
                return 0;
            }
            return ($a[$this->key] > $b[$this->key]) ? -1 : 1;
        });
    }


    public function filterAsc($array)
    {

        usort($array, function ($a, $b) {
            if ($a[$this->key] == $b[$this->key]) {
                return 0;
            }
            return ($a[$this->key] > $b[$this->key]) ? 1 : -1;
        });
    }

    public function filterProducts(array $array, string $filtermethod)
    {
        switch ($filtermethod) {

            case "price_descending":
                $this->key = "preis";
                $this->filterDesc($array);
                break;

            case "price_ascending":
                $this->key = "preis";
                $array = $this->filterAsc($array);
                break;

            case "rating_ascending":
                $this->key = "rating";
                $array = $this->filterAsc($array);
                break;

            case "rating_descending":
                $this->key = "rating";
                $array = $this->filterDesc($array);
                break;
        }
    }

    public function getProductBySearch(string $input)
    {
        $input = "%" . $input . "%";
        $data = [];
        $sql = "SELECT * FROM `produkte` WHERE `name` LIKE :input";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":input", $input, PDO::PARAM_STR);
        if ($stmt->execute() === false) {
            var_dump($stmt->errorInfo());
        }

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $data[]  = $row;
        }

        return $data;
    }

    // umschreiben, sobald Adminpanel erstellt wurde
    public function create(array $data)
    {

        $sql = "INSERT INTO produkte('name', 'beschreibung', 'preis', 'stückzahl', 'picUrl') VALUES(:name, :beschreibung, :preis, :stückzahl, :picUrl)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":name", $data["name"], PDO::PARAM_STR);
        $stmt->bindValue(":beschreibung", $data["beschreibung"], PDO::PARAM_STR);
        $stmt->bindValue(":preis", $data["preis"] ?? 0, PDO::PARAM_INT);
        $stmt->bindValue(":stückzahl", $data["stückzahl"] ?? 0, PDO::PARAM_INT);
        $stmt->bindValue(":name", $data["name"], PDO::PARAM_STR);

        $stmt->execute();

        return $this->conn->lastInsertId();
    }
}
