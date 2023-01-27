<?php

// Starten einer neuen Session oder Fortsetzen der aktuellen Session
session_start();
// Variablen
$URL =  $_SERVER['PHP_SELF'];
// Importierung der Klassen "DB" und "ProductController"
include('./classes/DB.php');
include('./classes/ProductController.php');
include('./classes/UserController.php');
// Erstellen einer neuen DB-Verbindungen anhand des Konstruktors der "DB"-Klasse
// Wenn die Verbindung fehlgeschlagen ist, gibt das IF-Statement eine Fehlermeldung aus
try {

    $database = new DB("localhost", "u623864896_crud", "root", "Newcat11new)");
} catch (PDOException $e) {

    die("ERROR: Verbindung konnte nicht aufgebaut werden. Grund: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <style>
        * {

            font-family: 'Courier New', Courier, monospace;

        }


        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;

        }
    </style>


</body>

</html>