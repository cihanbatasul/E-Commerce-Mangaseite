<?php
session_start();
// (Session)variablen
$rating = false;
$rating_pushed = false;
$rating_error = false;

$star_rating_number = 0;

$URL =  $_SERVER['PHP_SELF'];
$loginStatus;

if (!isset($_SESSION['loggedin'])) {
  $loginStatus = false;
};

// DB und Produktcontroller werden eingebunden
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

// Wenn auf der Produktseite bei einem Produkt auf "Details" geklickt wird
$productController = new ProductController($database);

$data = $productController->getProductById($_SESSION["product_page_id"]);

$_SESSION['product_img'] = $data[0]['picUrl'];

if (isset($_SESSION['loggedin'])) {

  $didUserRate = $productController->checkUserRating($_SESSION['user_id'], $_SESSION['product_page_id']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" || isset($_POST['submit'])) {

  if (isset($_POST['rate'])) {

    $rating = true;
  }

  if (isset($_POST['cancel_rating'])) {
    $rating = false;
  }

  if (isset($_POST['submit_rating'])) {

    $userid = $_SESSION['user_id'];
    $product_rating = $_POST['bewertung'];
    $comment = $_POST['rating_comment'];

    $pushRating = $productController->pushRating($userid, $_SESSION["product_page_id"], $product_rating, $comment);

    if ($pushRating === true) {

      $rating_pushed = true;
      $rating = false;
      $didUserRate = true;
      //header("Refresh:0");
    } else {
      $rating_error = true;
    }
  }

  // login 

  if (isset($_POST['login-prompt-submit-button'])) {

    if (!isset($_SESSION['loggedin']) || ($_SESSION['loggedin'] === false)) {

      $email = $_POST['login_email_input'];
      $password = $_POST['login_password_input'];

      $userController = new UserController($database);

      $userController->assignUserInput($email, $password);

      $data = $userController->getuserByEmail();

      if (
        $data !== false
      ) {

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


  // Produkt in den Warenkorb 
  if (isset($_POST['add'])) {

    $productController->addToCart($_SESSION["product_page_id"]);
  }
  // Suchfunktion
  if (isset($_POST['search'])) {
    $_SESSION['search'] = $_POST['search'];
    header("location: search.php");
    exit;
  }
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

  <style>
    @import url('https://fonts.googleapis.com/css2?family=EB+Garamond&family=Rajdhani:wght@300;400&display=swap');

    * {
      font-family: 'Rajdhani', sans-serif;

    }


    .rating {
      margin-top: 5rem;
    }

    html {
      height: 100%;
    }



    body {
      background-color: #ffffff;
      overflow-x: hidden;
      font-size: large;
      min-height: 100%;
      display: flex;
      flex-direction: column;
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


    .pic-col {
      position: relative;
      width: 400px;
      max-width: 400px;
      height: 600px;
      display: flex;
      align-items: center;
      justify-content: center;
      background-image: url('./pics/<?php echo $_SESSION['product_img'] ?>');
      background-size: cover;
      overflow: hidden;
      border-radius: 20px;
      box-shadow: rgba(17, 17, 26, 0.1) 0px 4px 16px, rgba(17, 17, 26, 0.1) 0px 8px 24px, rgba(17, 17, 26, 0.1) 0px 16px 56px;

    }

    .pic-col::before {
      content: ' ';
      position: absolute;
      width: 650px;
      height: 140%;
      background: linear-gradient(#457b9d, #e63946);
      animation: animate 4s linear infinite;
    }

    .pic-col::after {
      content: ' ';
      position: absolute;
      inset: 3px;
      background-image: url('./pics/<?php echo $_SESSION['product_img'] ?>');
      background-size: cover;
      border-radius: 16px;
    }

    @keyframes animate {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(160deg);
      }
    }

    .product_img {

      width: 100%;
      height: auto;
      border-style: solid;
      border-radius: 20px;
      box-shadow: rgba(50, 50, 93, 0.25) 0px 30px 60px -12px, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px;

    }

    /* bewertung */
    .user_rating {
      display: flex;


    }

    .user_rating>a {
      padding: 1rem;
      margin-left: 3rem;
    }

    .form-check-input {
      background-image: url('./pics/star-regular.svg');
      background-color: white;
      border-style: none;

    }

    .form-check-input:checked[type=radio] {
      background-image: url('./pics/star-solid.svg');
      background-color: transparent;
      border-style: hidden;
      border-style: none;
    }



    /** Footer **/
    footer {
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: #0496ff;
      margin-top: 5rem;
    }

    .social-media-icons>ul {
      display: flex;
      align-items: center;
      flex-wrap: wrap;

    }

    .social-media-icons>ul>li {
      color: white;
      list-style: none;
      margin: 1rem;
      padding: 2rem;
    }

    .social-media-icons>ul>li:hover {

      cursor: pointer;
      transition: all 1s;
      transform: translatey(-20%);

    }

    .fa-brands {
      font-size: 2em;
    }


    /* Login Prompt */
    .loginPromptContainer {
      display: flex;
      align-items: center;
      justify-content: center;
    }
  </style>

  <nav class="navbar navbar-expand-lg shadow outermost-div">
    <div class="container-fluid">
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
            <a class="nav-link" id="logout-button" aria-current="page" href="unset_session_variables.php" onclick=""><i class="fa-solid fa-person-through-window fa-lg"></i></a>
          <?php
          } else {
          ?>
            <a class="nav-link login-button" id="testLogin" href="login.php" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="This top tooltip is themed via CSS variables."><i class="fa-solid fa-right-from-bracket"></i></a>

          <?php
          } ?>

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





  <?php if ($rating_pushed === true) {

  ?>
    <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
      <strong>Produktbewertung erfolgreich!
      </strong>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php
  } else if ($rating_error === true) {
  ?>
    <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
      <strong>Etwas ist in der Produktbewertung schiefgelaufen. Versuche es erneut.
      </strong>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php
  }


  ?>




  <div class="container">
    <?php


    foreach ($data as $record) {

      $product_id = $record['id'];
      $product_name = $record['name'];
      $product_price = $record['preis'];
      $product_img = $record['picUrl'];
      $_SESSION['product_img'] = $product_img;
      $product_stückzahl = $record['stückzahl'];
      $product_beschreibung = $record['beschreibung'];

      $num = $productController->getRating($product_id);
      $numRating = $num["numRating"];

    ?>
      <div class="row mt-5 productRow">

        <!-- product pic -->
        <div class=" col md-6 mt-3 pic-col sm">

        </div>

        <div class="col md-6 mt-3">

          <card>

            <h1>
              <?php echo $product_name ?>
            </h1>

            <p><?php echo $product_price ?> €</p>

            <p>
              <span>
                <?php echo $product_beschreibung ?>
              </span>
            </p>
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
        <form method="post">
          <div class="col">
            <?php
            if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] === false) {
            ?>
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#login-prompt">In den Warenkorb</button>

            <?php
            } else {
            ?>
              <button type="submit" class="btn btn-primary buttons" name="add"><a href="#" class="link-light">In den Warenkorb</a></button>
            <?php
            }
            ?>

            <?php
            if (isset($_SESSION['loggedin'])  && $didUserRate === true) {
            ?>
              <button class="btn btn-primary disabled" type="button" aria-disabled="true" name="rate">Bereits bewertet</button>
            <?php
            } else if (!isset($_SESSION['loggedin'])) {
            ?>
              <button class="btn btn-primary disabled" type="button" aria-disabled="true" name="rate">Einloggen, um zu bewerten</button>
            <?php
            } else {
            ?>
              <button class="btn btn-primary" type="submit" name="rate">Bewerten</button>
            <?php
            }
            ?>
          </div>
        </form>
        </card>

        <?php
        if ($rating === true) {
        ?>
          <div class="container rating">
            <form method="post">
              <h3>Bewertung</h3>

              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="bewertung" id="inlineRadio1" value="1">
                <label class="form-check-label" for="inlineRadio1"></label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="bewertung" id="inlineRadio2" value="2">
                <label class="form-check-label" for="inlineRadio2"></label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="bewertung" id="inlineRadio3" value="3">
                <label class="form-check-label" for="inlineRadio3"></label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="bewertung" id="inlineRadio3" value="4">
                <label class="form-check-label" for="inlineRadio3"></label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="bewertung" id="inlineRadio3" value="5">
                <label class="form-check-label" for="inlineRadio3"></label>
              </div>


              <div class="mb-3">
                <label for="comment" class="form-label">Kommentar</label>
                <textarea class="form-control" id="comment" rows="3" name="rating_comment"></textarea>
              </div>
              <button type="submit" name="submit_rating" class="btn btn-primary">Speichern</button>
              <button type="submit" name="cancel_rating" class="btn btn-primary">Abbrechen</button>
          </div>
          </form>
        <?php
        }
        ?>
      </div>

  </div>

  </div>

  </div>
<?php
    }
?>
<footer class="text-center text-white">
  <div class="row">
    <div class="social-media-icons">
      <ul>
        <li class="footer_list_item"><i class="fa-brands fa-instagram"></i></li>
        <li class="footer_list_item"><i class="fa-brands fa-youtube"></i></li>
        <li class="footer_list_item"><i class="fa-brands fa-linkedin"></i></li>
        <li class="footer_list_item"><i class="fa-brands fa-twitter"></i></li>
      </ul>
    </div>
  </div>
</footer>


<script>
  // Login Button wird entfernt, wenn User eingeloggt ist

  const loginButton = document.querySelectorAll('.login-button');



  if (<?php echo $_SESSION['loggedin'] === true ?>) {

    for (let i = 0; i < loginButton.length; i++) {
      loginButton[i].hidden = true;
    }


  }
  if (<?php echo $loginStatus ?> === false) {

    for (let i = 0; i < loginButton.length; i++) {
      loginButton[i].hidden = false;
    }

  }
</script>

</body>

</html>