<?php
session_start();
include(__DIR__ . '/../../classes/DB.php');
include(__DIR__ . '/../../classes/ProductController.php');
include(__DIR__ . '/../../classes/UserController.php');

if (isset($_SESSION["loggedin"]) && ($_SESSION["loggedin"] === true)) {
  header("location: ../startseite/index.php");
  exit;
}


$_SESSION['loginfailure'] = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" || isset($_POST['submit'])) {

  $email = $_POST["login_email_input"];
  $password = $_POST["login_password_input"];

  if (empty($email) || empty($password)) {

    echo "Alles ausfüllen.";
  }

  try {

    $database = new DB("localhost", "crud", "root", "passwordForWebsite");
  } catch (PDOException $e) {

    die("ERROR: Verbindung konnte nicht aufgebaut werden. Grund: " . $e->getMessage());
  }

  $userController = new UserController($database);

  $userController->assignUserInput($email, $password);

  $data = $userController->getuserByEmail();

  if ($data !== false) {

    if ($userController->checkIfCorrect($data)) {

      $_SESSION['loginfailure'] = false;
      $_SESSION['loggedin'] = true;

      $user_id = $data[0]['id'];
      $_SESSION['user_id'] = $user_id;

      if ($_SERVER['PHP_SELF'] !== '/index.php') {

        header("location: ../startseite/index.php");
        exit;
      }
    } else {

      $_SESSION['loginfailure'] = true;
    }
  }
  // Suchfunktion
  if (isset($_POST['search'])) {
    $_SESSION['search'] = $_POST['search'];
    header("location: ../suchergebnisse/search.php");
    exit;
  }
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <script src="https://kit.fontawesome.com/59e04dcbeb.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="../../indexStyle.css" type="text/css">
  <link rel="stylesheet" href="login.css" type="text/css">
  <title>Login</title>
</head>

<body>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  <style>

  </style>
  <nav class="navbar navbar-expand-lg shadow outermost-navdiv">
    <div class=" container-fluid">
      <a class="navbar-brand fs-5" href="index.php"><img src="../../pics/webtoons.png"></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="../startseite/index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link " aria-current="page" href="../produkte/produktAuflistung/produkte.php">Produkte</a>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Optionen
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="../regestrierung/regestrieren.php">Regestrieren</a></li>
              <li><a class="dropdown-item" href="../login/login.php">Login</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="../produkte/produktAuflistung/produkte.php">Produkte</a></li>
            </ul>
          </li>

        </ul>
        <<!-- Suchfunktion -->
          <div class="nav-rightside" style="display: flex;">
            <form class="d-flex" role="search" method="post">
              <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search" value="">
              <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
            <!-- Warenkorb -->
            <?php
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
            ?>
              <a class="nav-link warenkorb-button" aria-current="page" href="../warenkorb/warenkorb.php"><i class="fa-solid fa-bag-shopping warenkorb-button"></i> <span class="badge bg-primary rounded-pill warenkorb-button">
                  <?php if (isset($_SESSION['cart']))
                    echo count($_SESSION['cart']);
                  else echo "0" ?></span></a>

            <?php
            } else {
            ?>
              <a class="nav-link login-button" id="testLogin" href="../login/login.php" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="This top tooltip is themed via CSS variables."><i class="fa-solid fa-right-from-bracket"></i></a>

            <?php
            } ?>
          </div>


      </div>
    </div>
  </nav>

  <!-- login fehler -->

  <?php if ($_SESSION['loginfailure'] === true) {

  ?>
    <div class="alert alert-warning alert-dismissible fade show text-center" role="alert">
      <strong>E-Mail-Adresse, Benutzername oder Passwort ungültig. Bitte versuche es noch einmal.
      </strong>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php
  }
  ?>

  <div class="container mt-3">

    <div class="row">

      <div class="col-md-6 my-auto loginPic">

        <img class="img-fluid w-100" src="../../pics/mangaboy.png" alt="" />

      </div>

      <div class="col-md-6 loginForm">

        <form method="post">
          <!-- Email input -->
          <div class="form-floating mb-4">
            <input type="email" id="login_email_input" class="form-control" placeholder="Email" name="login_email_input" />
            <label class="form-label" for="login_email_input">Email Adresse</label>
          </div>

          <!-- Password input -->
          <div class="form-floating mb-4">
            <input type="password" id="login_password_input" class="form-control" placeholder="Passwort" name="login_password_input" />
            <label class="form-label" for="login_password_input">Passwort</label>
          </div>

          <!-- 2 column grid layout für inline styling -->
          <div class="row mb-4">

            <div class="col d-flex justify-content-center">
              <!-- Checkbox -->
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="form2Example31" checked />
                <label class="form-check-label" for="form2Example31"> Remember me </label>
              </div>
            </div>

            <div class="col">
              <!-- mach ich iwann -->
              <a href="#!">Passwort vergessen?</a>
            </div>

          </div>

          <!-- Submit button -->
          <div class="row signupRow">
            <button type="submit" class="btn btn-primary btn-block mb-4 signupButton">Sign in</button>
          </div>
          <!-- Regestrieren -->
          <div class="text-center">

            <p>Noch keinen Account? <a href="../regestrierung/regestrieren.php">Regestrieren</a></p>
            <p>oder anmelden mit:</p>
            <button type="button" class="btn btn-link btn-floating mx-1">
              <i class="fab fa-facebook-f"></i>
            </button>

            <button type="button" class="btn btn-link btn-floating mx-1">
              <i class="fab fa-google"></i>
            </button>

            <button type="button" class="btn btn-link btn-floating mx-1">
              <i class="fab fa-twitter"></i>
            </button>

            <button type="button" class="btn btn-link btn-floating mx-1">
              <i class="fab fa-github"></i>
            </button>

          </div>
        </form>
      </div>
    </div>
  </div>


  <footer>
    <div class="waves">
      <div class="wave" id="wave1"></div>
      <div class="wave" id="wave2"></div>
      <div class="wave" id="wave3"></div>
      <div class="wave" id="wave4"></div>
    </div>
    <div class="social-media-icons">
      <ul>
        <li class="footer_list_item"><i class="fa-brands fa-instagram"></i></li>
        <li class="footer_list_item"><i class="fa-brands fa-youtube"></i></li>
        <li class="footer_list_item"><i class="fa-brands fa-linkedin"></i></li>
        <li class="footer_list_item"><i class="fa-brands fa-twitter"></i></li>
      </ul>
    </div>
  </footer>



</body>

</html>