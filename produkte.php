<?php

// Starten einer neuen Session oder Fortsetzen der aktuellen Session
session_start();
// Variablen
$URL =  $_SERVER['PHP_SELF'];
$_SESSION['search'] = "";
$numRating;
$filter_method;

// Importierung der Klassen "DB" und "ProductController"
include('./classes/DB.php');
include('./classes/ProductController.php');
include('./classes/UserController.php');
// Erstellen einer neuen DB-Verbindungen anhand des Konstruktors der "DB"-Klasse
// Wenn die Verbindung fehlgeschlagen ist, gibt das IF-Statement eine Fehlermeldung aus
try {

  $database = new DB("localhost", "crud", "root", "");
} catch (PDOException $e) {

  die("ERROR: Verbindung konnte nicht aufgebaut werden. Grund: " . $e->getMessage());
}



// Erstellung einer neuen Instanz von der Klasse "ProductController"
$productController = new ProductController($database);
$data = $productController->getAll();
$productController->updateProductAvgRating();

// wenn bei einem gelisteten Produkt auf "Kaufen" geklickt wird
if (isset($_POST["add"])) {

  $productController->addToCart($_POST['product_id_cart']);
}


// Login-Prompt

if ($_SERVER["REQUEST_METHOD"] == "POST" || isset($_POST['submit'])) {
  if (isset($_POST['login-prompt-submit-button'])) {

    if (!isset($_SESSION['loggedin']) || ($_SESSION['loggedin'] === false)) {

      $email = $_POST['login_email_input'];
      $password = $_POST['login_password_input'];

      $userController = new UserController($database);

      $userController->assignUserInput($email, $password);

      $data = $userController->getuserByEmail();

      if ($data !== false) {

        if ($userController->checkIfCorrect($data)) {

          $_SESSION['loginfailure'] = false;
          $_SESSION['loggedin'] = true;

          $user_id = $data[0]['id'];
          $_SESSION['user_id'] = $user_id;

          header("location: $URL");
          exit;
        }
      }
    } else {

      $_SESSION['loginfailure'] = true;
      echo "failure";
    }
  }

  // Produktseite

  // Suchfunktion
  if (isset($_POST['search'])) {
    $_SESSION['search'] = $_POST['search'];
    header("location: search.php");
    exit;
  }


  if (isset($_POST['product_page_name']) || isset($_POST['product-pic'])) {
    $_SESSION["product_page_id"] = $_POST['product_page_name'];
    echo ($_SESSION["product_page_id"]);
    header("Location: ./produktseite.php");
    exit;
  }

  if (isset($_POST['filter'])) {
    $filter_method = $_POST['filter'];
    // Absteigender Preisfilter
    if ($_POST['filter'] === "price_descending") {

      usort($data, function ($a, $b) {
        if ($a['preis'] == $b['preis']) {
          return 0;
        }
        return ($a['preis'] > $b['preis']) ? -1 : 1;
      });
    }

    // Aufsteigender Preisfilter
    if ($_POST['filter'] === "price_ascending") {
      usort($data, function ($a, $b) {
        if ($a['preis'] == $b['preis']) {
          return 0;
        }
        return ($a['preis'] > $b['preis']) ? 1 : -1;
      });
    }

    // Absteigender Ratingfilter
    if ($_POST['filter'] === "rating_ascending") {
      foreach ($data as $row) {
        $num = $productController->getRating($row['id']);
        $numRating = $num["numRating"];
      }
      usort($data, function ($a, $b) {

        if ($a['rating'] == $b['rating']) {
          return 0;
        }
        return ($a['rating'] > $b['rating']) ? 1 : -1;
      });
    }

    // Aufsteigender Ratingfilter
    if ($_POST['filter'] === "rating_descending") {
      foreach ($data as $row) {
        $num = $productController->getRating($row['id']);
        $numRating = $num["numRating"];
      }
      usort($data, function ($a, $b) {

        if ($a['rating'] == $b['rating']) {
          return 0;
        }
        return ($a['rating'] > $b['rating']) ? -1 : 1;
      });
    }
  }

  // Nach Genre Filter


}


?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Form Aufgabe</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <script src="https://kit.fontawesome.com/59e04dcbeb.js" crossorigin="anonymous"></script>
  <link href="indexStyle.css" rel="stylesheet">
</head>

<body>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=EB+Garamond&family=Rajdhani:wght@300;400&display=swap');

    * {
      font-family: 'Rajdhani', sans-serif;

    }


    body {
      background-color: #ffffff;
      overflow-x: hidden;
      font-size: large;
    }

    .navbar-brand>img {
      width: 80px;
      height: auto;
    }

    .nav {
      padding-top: 0px;
      padding-bottom: 0px;
    }

    .nav-link {
      color: #284b63;
      font-size: 1.15rem;
    }

    .nav-link:hover {

      color: #00DC64;
    }

    .outermost-navdiv {
      background-color: #FFFFFF;
    }

    .product-card-img {
      width: 100%;
      height: 15vw;
      object-fit: cover;
    }

    .card {

      max-height: 800px;
      width: 18rem;
    }

    .card:hover {
      box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px;
    }

    .card-title {

      white-space: nowrap;
    }

    .card-text {
      background-color: transparent;
      display: block;
      max-height: 240px;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .container {
      background-color: transparent;
    }

    .card-body {
      background-color: transparent;
    }

    .productimg {
      width: 100%;
      height: 15vw;
      object-fit: cover;
    }

    .buttons {
      background: rgb(2, 0, 36);
      background: linear-gradient(90deg, rgba(2, 0, 36, 1) 0%, rgba(56, 168, 24, 1) 0%, rgba(0, 212, 255, 1) 100%);
      border-style: none;
    }

    .buttons:hover,
    .buttons:focus {
      background-color: #284b63;
      box-shadow: inset 0 0 0 0.1em;
    }

    /* Login Prompt */
    .loginPromptContainer {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* Filter */

    .dropdown-filter-toggle {
      background: rgb(2, 0, 36);
      background: linear-gradient(90deg, rgba(2, 0, 36, 1) 0%, rgba(56, 168, 24, 1) 0%, rgba(0, 212, 255, 1) 100%);
      font-size: large;
      border-style: none;
    }

    .dropdown-filter-toggle:hover {
      box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px;
    }

    .filter_span {
      font-size: smaller;
    }

    .second-navdiv {
      background-color: #FFFFFF;
      height: 50px;
    }

    .filter-method {
      display: flex;

    }

    .filterRow {
      align-items: center;
      justify-content: center;
      margin-bottom: 2rem;

    }

    .filter-method>.btn-close {
      margin-left: 1rem;
    }
  </style>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

  <nav class="navbar navbar-expand-lg shadow outermost-navdiv"">
    <div class=" container-fluid">
    <!-- "Logo" -->
    <a class="navbar-brand fs-5" href="index.php"><img src="./pics/webtoons.png"></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <!-- Index -->
        <li class="nav-item">
          <a class="nav-link " aria-current="page" href="index.php">Home</a>
        </li>
        <!-- Produkte -->
        <li class="nav-item">
          <a class="nav-link " aria-current="page" href="produkte.php">Produkte</a>
        </li>

        <!-- Dropdown mit Links -->
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
      <!-- Suchfunktion -->
      <div class="nav-rightside" style="display: flex;">
        <form class="d-flex" role="search" method="post">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search" value="">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
        <!-- Warenkorb -->
        <?php
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
        ?>
          <a class="nav-link warenkorb-button" aria-current="page" href="warenkorb.php"><i class="fa-solid fa-bag-shopping warenkorb-button"></i> <span class="badge bg-primary rounded-pill warenkorb-button">
              <?php if (isset($_SESSION['cart']))
                echo count($_SESSION['cart']);
              else echo "0" ?></span></a>

          <!-- Logout -->
          <a class="nav-link" id="logout-button" aria-current="page" href="unset_session_variables.php" onclick=""><i class="fa-solid fa-person-through-window fa-lg"></i>

            </i></a>
        <?php
        } else {
        ?>
          <a class="nav-link login-button" id="testLogin" href="login.php" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="This top tooltip is themed via CSS variables."><i class="fa-solid fa-right-from-bracket"></i></a>

        <?php
        } ?>

      </div>
    </div>
    </div>
  </nav>


  <!-- Login-Prompt -->
  <div class="modal fade" id="login-prompt" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Einloggen, um fortzufahren</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="container loginPromptContainer">
            <div class="col-md-6 loginForm">

              <form method="post" name="login-prompt-form">
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
                  <button type="submit" name="login-prompt-submit-button" class="btn btn-primary btn-block mb-4 signupButton">Sign in</button>
                </div>
                <!-- Regestrieren -->
                <div class="text-center">

                  <p>Noch keinen Account? <a href="regestrieren.php">Regestrieren</a></p>
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
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>


  <! -- Produkte -->
    <div class="container mt-5">
      <!-- Dropdown mit Links -->
      <div class="row filterRow">



        <div class="col lg-3 md-3">
          <form method="post">
            <div class="dropdown">
              <button class="btn btn-secondary dropdown-toggle dropdown-filter-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Filtern <span class="filter_span"> nach</span>
              </button>
              <ul class="dropdown-menu">
                <li>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="filter" id="filter_genre" value="genre">
                    <label class="form-check-label" for="filter_genre">
                      Genre
                    </label>
                  </div>
                </li>
                <li>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="filter" id="filter_price" value="price_descending">
                    <label class="form-check-label" for="filter_price">
                      Preis (absteigend)
                    </label>
                  </div>
                </li>
                <li>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="filter" id="filter_price" value="price_ascending">
                    <label class="form-check-label" for="filter_price">
                      Preis (aufsteigend)
                    </label>
                  </div>
                </li>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="filter" id="filter_rating" value="rating_descending">
                  <label class="form-check-label" for="filter_rating">
                    Bewertung (absteigend)
                  </label>
                </div>
                <li>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="filter" id="filter_rating" value="rating_ascending">
                    <label class="form-check-label" for="filter_rating">
                      Bewertung (aufsteigend)
                    </label>
                  </div>
                </li>
                <li><button class="btn btn-primary" type="submit">Filtern</button></li>
              </ul>
            </div>
          </form>
        </div>


        <div class="col lg-3 md-3">
          <form method="post">
            <div class="dropdown">
              <button class="btn btn-secondary dropdown-toggle dropdown-filter-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Genre
              </button>
              <ul class="dropdown-menu">
                <li>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="filter" id="filter_action" value="action">
                    <label class="form-check-label" for="filter_action">
                      Action
                    </label>
                  </div>
                </li>
                <li>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="filter" id="filter_sport" value="sport">
                    <label class="form-check-label" for="filter_sport">
                      Sport
                    </label>
                  </div>
                </li>
                <li>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="filter" id="filter_fantasy" value="fantasy">
                    <label class="form-check-label" for="filter_fantasy">
                      Fantasy
                    </label>
                  </div>
                </li>
                <li><button class="btn btn-primary" type="submit">Filtern</button></li>
              </ul>
            </div>
          </form>
        </div>

        <div class="col filter-method">

          <?php if (isset($filter_method)) {
          ?>
            <p>Filtermethode: <span><?php echo $filter_method ?></span></p>
            <button type="button" class="btn-close" aria-label="Close" onclick="location.href='<?php echo $URL; ?>'"></button>
          <?php
          }
          ?>

        </div>

      </div>



      <div class=" row">

        <?php

        foreach ($data as $record) {

          $product_id = $record['id'];
          $product_name = $record['name'];
          $product_price = $record['preis'];
          $product_img = $record['picUrl'];
          $product_stückzahl = $record['stückzahl'];
          $product_beschreibung = $record['beschreibung'];
          $numRating = $record['rating']

        ?>


          <div class="col align-items-stretch ">
            <form method="post">
              <div class="card  p-3 mb-5 bg-body rounded">
                <!--<button type="submit" name="product-pic"><img src="pics/// echo $record['picUrl'] " class="card-img-top product-card-img" alt="..."></button><-->
                <input type="image" src="pics/<?php echo $record['picUrl'] ?>" alt="submit" name="product-pic" class="productimg" />
                <div class="card-body">
                  <h5 class="card-title"><?php echo $record['name']; ?></h5>
                  <input type="hidden" name="name" value="<?php echo $product_name ?>">
                  <input type="hidden" name="product_id_cart" value="<?php echo $product_id ?>">
                  <span class="card-text "><?php echo $record['beschreibung']; ?></span>
                </div>

                <ul class="list-group list-group-flush">
                  <li class="list-group-item" name="preis" value="<?php echo $product_price ?>"><?php echo $record['preis']; ?> $</li>
                  <input type="hidden" name="preis" value="<?php echo $product_price ?>">
                </ul>
                <table class="table">
                  <tr>
                    <th scope="row">Rating:</th>
                    <td>
                      <div class="star-rating">
                        <ul class="list-inline">
                          <?php

                          $start = 1;

                          if ($numRating != NULL) {

                            while ($start <= 5) {

                              if ($numRating < $start) {
                          ?>

                                <li class="list-inline-item"><i class="fa fa-star-o"></i></li>

                              <?php

                              } else {
                              ?>

                                <li class="list-inline-item"><i class="fa fa-star"></i></li>

                            <?php

                              }
                              $start++;
                            }
                          } else {
                            ?>

                            <span>Keine Bewertungen</span>

                          <?php
                          }
                          ?>

                    </td>
                    </ul>
              </div>
              </tr>
              </table>


              <div class="card-body">
                <?php
                if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] === false) {
                ?>
                  <button type="button" class="btn btn-primary buttons" data-bs-toggle="modal" data-bs-target="#login-prompt">Kaufen</button>

                <?php
                } else {
                ?>
                  <button type="submit" class="btn btn-primary buttons" name="add">Kaufen</button>
                <?php
                }
                ?>

                <input type="hidden" name="picUrl" value="<?php echo $product_img ?>">
                <input type="hidden" name="beschreibung" value="<?php echo $product_beschreibung ?>">
                <input type="hidden" name="stückzahl" value="<?php echo $product_stückzahl ?>">
                <button type="submit" id="details-button" class="btn btn-primary buttons" name="product_page_name" value="<?php echo $record['id'] ?>">Details</button>
            </form>
          </div>
      </div>

    </div>

  <?php
        }

  ?>

  </div>
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