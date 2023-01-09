<?php
session_start();

include('./classes/DB.php');
include('./classes/UserController.php');

if (isset($_SESSION["loggedin"]) && ($_SESSION["loggedin"] === true)) {
  header("location: index.php");
  exit;
}

// Variablen

$userCreateSuccess = false;

// Erstellen einer neuen DB-Verbindungen anhand des Konstruktors der "DB"-Klasse
// Wenn die Verbindung fehlgeschlagen ist, gibt das IF-Statement eine Fehlermeldung aus
try {

  $database = new DB("localhost", "crud", "root", "");
} catch (PDOException $e) {

  die("ERROR: Verbindung konnte nicht aufgebaut werden. Grund: " . $e->getMessage());
}

$userController = new userController($database);

if ($_SERVER["REQUEST_METHOD"] == "POST" || isset($_POST['submit'])) {

  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $password2 = $_POST["password2"];
  $name = $_POST["nachname"];
  $vorname = $_POST["vorname"];

  if ($password != $password2) {
    echo "Passwörter stimmen nicht überein.";
  }

  if (!isset($email)) {
    echo "Email oder Username müssen eingetragen sein.";
  }

  $password_hash = password_hash($password, PASSWORD_DEFAULT);

  if ($createUser = $userController->create($username, $email, $password_hash, $name, $vorname) === true) {
    $userCreateSuccess = true;
  }
}

?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Aufgabe</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <script src="https://kit.fontawesome.com/59e04dcbeb.js" crossorigin="anonymous"></script>
  <link href="indexStyle.css" rel="stylesheet">
</head>

<body>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=EB+Garamond&family=Rajdhani:wght@300;400&display=swap');

    * {
      font-family: 'Rajdhani', sans-serif;

    }
  </style>
  <nav class="navbar navbar-expand-lg" style="background-color: #23252b;">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">Form Seite</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link " aria-current="page" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link  login-button" href="login.php">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link " aria-current="page" href="produkte.php">Produkte</a>
          </li>



          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Optionen
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item login-button" href="regestrieren.php">Regestrieren</a></li>
              <li><a class="dropdown-item login-button" href="login.php">Login</a></li>
              <li>
                <hr class="dropdown-divider login-button">
              </li>
              <li><a class="dropdown-item" href="produkte.php">Produkte</a></li>
            </ul>
          </li>

        </ul>
        <form class="d-flex" role="search">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form>

      </div>
    </div>
  </nav>

  <!-- Regestrierungdwhlwe -->
  <?php if ($userCreateSuccess === true) {

  ?>
    <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
      <strong>Ihr Account wurde erstellt!
      </strong>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php
  }
  ?>

  <div class="container mt-3">

    <form method="post">

      <div class="row">
        <div class="col">
          <div class="form-floating mb-4">
            <input type="text" id="usernameInput" name="username" class="form-control" placeholder="Benutzername" />
            <label class="form-label" htmlFor="usernameInput">Nutzername</label>
          </div>
        </div>

        <div class="row mb-4">
          <div class="col">
            <div class="form-floating">
              <input type="text" id="first_nameInput" name="vorname" class="form-control" placeholder="Vorname" />
              <label class="form-label" for="first_nameInput">Vorname</label>
            </div>
          </div>
          <div class="col">
            <div class="form-floating">
              <input type="text" id="last_nameInput" name="nachname" class="form-control" placeholder="Nachname" />
              <label class="form-label" htmlFor="last_nameInput">Nachname</label>
            </div>
          </div>
        </div>


        <div class="form-floating mb-4">
          <input type="email" id="emailInput" name="email" class="form-control" placeholder="Email" />
          <label class="form-label" htmlFor="emailInput">Email Adresse</label>
        </div>

        <div class="row">
          <div class="col">
            <div class="form-floating mb-4">
              <input type="password" id="passwordInput" name="password" class="form-control" placeholder="Passwort" />
              <label class="form-label" htmlFor="passwordInput">Password</label>
            </div>
          </div>
          <div class="col">
            <div class="form-floating mb-4">
              <input type="password" id="passwordInput2" name="password2" class="form-control" placeholder="Passwort" />
              <label class="form-label" htmlFor="passwordInput2">Password</label>
            </div>
          </div>
        </div>

        <div class="form-check d-flex justify-content-center mb-4">
          <input class="form-check-input me-2" type="checkbox" value="" id="termsInput" />
          <label class="form-check-label" htmlFor="termsInput">
            Ich bin mit den ToS einverstanden.
          </label>
        </div>


        <div class="row reg_submit"><button type="submit" class="btn btn-primary btn-block mb-4 register-button">Regestrieren</button></div>



    </form>
  </div>


  <script>
    // Login Button wird entfernt, wenn User eingeloggt ist

    const loginButton = document.querySelectorAll('.login-button');

    if (<?php echo $_SESSION['loggedin'] === true ?>) {

      for (let i = 0; i < loginButton.length; i++) {
        loginButton[i].hidden = true;
      }

    } else {
      for (let i = 0; i < loginButton.length; i++) {
        loginButton[i].hidden = false;
      }
    }


    const warenkorbButton = document.getElementById('warenkorb-button')
    const logoutbutton = document.getElementById('logout-button');
    if (<?php echo $_SESSION['loggedin'] === true ?>) {

      warenkorbButton.hidden = false;
      logoutbutton.hidden = false;

    } else {

      warenkorbButton.hidden = true;
      logoutbutton.hidden = true;
    }
  </script>

</body>

</html>