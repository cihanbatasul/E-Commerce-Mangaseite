<?php
session_start();
include('../../classes/DB.php');
include('../../classes/ProductController.php');

if (isset($_SESSION["loggedin"]) && ($_SESSION["loggedin"] === false)) {
  header("location: ../startseite/index.php");
  exit;
}

try {

  $database = new DB("localhost", "crud", "root", "passwordForWebsite");
} catch (PDOException $e) {

  die("ERROR: Verbindung konnte nicht aufgebaut werden. Grund: " . $e->getMessage());
}


// Gesamtpreis wird hier ermittelt
$preis;

function price_det()
{

  $_SESSION['warenkorb_total_price'] = 0;

  if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {

    foreach ($_SESSION['cart'] as $item) {

      // Preis mal Quantität
      $preis = $item['price'] * $item['quantity'];
      $_SESSION['warenkorb_total_price'] += $preis;
    }
  } else {

    $_SESSION['warenkorb_total_price'] = 0;
  }
}

price_det();

// Manipulation der Quantität der Warenkorb-Items 
if (isset($_POST['increase']) || isset($_POST['decrease']) || isset($_POST['quantity']) || isset($_POST['delete'])) {

  $product_id = $_POST['product_id'];
  $acol = array_column($_SESSION['cart'], 'id');

  // Wenn Produkt-Id in der Array, Quantität + 1
  if (in_array($product_id, $acol)) {

    if (isset($_POST['increase'])) {


      $_SESSION['cart'][$product_id]['quantity'] = $_SESSION['cart'][$product_id]['quantity'] + 1;
      price_det();
    }
    if (isset($_POST['quantity'])) {

      $_SESSION['cart'][$product_id]['quantity'] = $_POST['quantity'];
      price_det();
    }
    if (isset($_POST['decrease']) && $_SESSION['cart'][$product_id]['quantity'] > 1) {

      $_SESSION['cart'][$product_id]['quantity'] = $_SESSION['cart'][$product_id]['quantity'] - 1;
      price_det();
    }
    if (isset($_POST['delete'])) {

      unset($_SESSION['cart'][$product_id]);
      price_det();
    }

    // Item aus dem Warenkorb entfernen, sobald die Quantiät 0 erreicht

    if ($_SESSION['cart'][$product_id]['quantity'] == 0) {

      unset($_SESSION['cart'][$product_id]);

      price_det();
    }
  }


  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Suchfunktion
    if (isset($_POST['search'])) {
      $_SESSION['search'] = $_POST['search'];
      header("location: ../suchergebnisse/search.php");
      exit;
    }
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
  <script defer src="../../FormValidation.js"></script>
  <link href="../../indexStyle.css" rel="stylesheet">

</head>

<body>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
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
  </style>

  <nav class="navbar navbar-expand-lg shadow outermost-navdiv" ">
    <div class=" container-fluid">
    <!-- "Logo" -->
    <a class="navbar-brand fs-5" href="../startseite/index.php"><img src="../../pics/webtoons.png"></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">

        <!-- Index -->
        <li class="nav-item">
          <a class="nav-link " aria-current="page" href="../startseite/index.php">Home</a>
        </li>

        <!-- Login -->
        <li class="nav-item">
          <a class="nav-link login-button" href="../login/login.php">Login</a>
        </li>

        <!-- Produkte -->
        <li class="nav-item">
          <a class="nav-link " aria-current="page" href="../produkte/produktAuflistung/produkte.php">Produkte</a>
        </li>

        <!-- Warenkorb Button -->
        <li class="nav-item">
          <a class="nav-link  warenkorb-button" aria-current="page" href="warenkorb.php"><i class="fa-solid fa-bag-shopping"></i> <span class="badge bg-primary rounded-pill">
              <?php if (isset($_SESSION['cart']))
                echo count($_SESSION['cart']) ?>
            </span>Warenkorb</a>
        </li>

        <!-- Dropdown mit Links -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Optionen
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item login-button" href="../regestrierung/regestrieren.php">Regestrieren</a></li>
            <li><a class="dropdown-item login-button" href="../login/login.php">Login</a></li>
            <li>
              <hr class="dropdown-divider ">
            </li>
            <li><a class="dropdown-item" href="../produkte/produktAuflistung/produkte.php">Produkte</a></li>
          </ul>
        </li>
      </ul>


      <!-- Suchfunktion -->
      <div class="nav-rightside" style="display: flex;">
        <form class="d-flex" role="search" method="post">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search" value="">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
        <!-- ProfilButton -->
        <a class="nav-link" id="profile-button" aria-current="page" href="../profil/profil.php"><i class="fa-solid fa-id-card"></i></a>
        <!-- Logout -->
        <a class="nav-link" id="logout-button" aria-current="page" href="unset_session_variables.php" onclick=""><i class="fa-solid fa-person-through-window fa-lg"></i></a>
      </div>
    </div>
    </div>
  </nav>

  <section class="h-100 h-custom" style="background-color: #989696;">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12">
          <div class="card card-registration card-registration-2" style="border-radius: 15px;">
            <div class="card-body p-0">
              <div class="row g-0">
                <div class="col-lg-8">
                  <div class="p-5">
                    <div class="d-flex justify-content-between align-items-center mb-5">
                      <h1 class="fw-bold mb-0 text-black">Warenkorb</h1>

                    </div>

                    <?php

                    //Produkte werden aufgelistet. Es wird durch die Session Variable gelooped
                    if (isset($_SESSION['cart'])) {
                      foreach ($_SESSION['cart'] as $item) {
                    ?>
                        <form method="post">
                          <hr class="my-4">
                          <div class="row mb-4 d-flex justify-content-between align-items-center">
                            <div class="col-md-2 col-lg-2 col-xl-2">
                              <img src="../../pics/<?php echo $item['imgUrl'] ?> " class="img-fluid rounded-3" alt="">
                            </div>

                            <div class="col-md-3 col-lg-3 col-xl-3">
                              <h6 class="text-muted"> </h6>
                              <h6 class="text-black mb-0"> <?php echo $item['name'] ?> </h6>
                            </div>

                            <!-- <form method="post">-->
                            <input type="hidden" name="product_id" value="<?php echo $item['id'] ?>">
                            <div class="col-md-3 col-lg-3 col-xl-2 d-flex">
                              <button name="decrease" class="btn btn-link px-2" onclick="this.parentNode.querySelector('input[type=number]').stepDown(); updateTotalPrice()">
                                <i class="fas fa-minus"></i>
                              </button>

                              <input id="form1" min="0" name="quantity" value="<?php echo $item['quantity'] ?>" type="number" class="form-control form-control-sm" />

                              <button name="increase" class="btn btn-link px-2" onclick="this.parentNode.querySelector('input[type=number]').stepUp(); updateTotalPrice()">
                                <i class="fas fa-plus"></i>
                              </button>
                            </div>
                            <!-- </form> -->
                            <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                              <h6 class="mb-0"><?php echo $item['price'] ?> € </h6>
                            </div>

                            <div class="col-md-1 col-lg-1 col-xl-1 text-end">
                              <a name="delete" value="<?php echo $item['id'] ?>" href="" class="text-muted"><i class="fas fa-times"></i></a>
                            </div>
                          </div>
                        </form>
                    <?php

                      }
                    }
                    ?>

                    <hr class="my-4">

                    <div class="pt-5">
                      <h6 class="mb-0"><a href="../produkte/produktAuflistung/produkte.php" class="text-body"><i class="fas fa-long-arrow-alt-left me-2"></i>Zurück zum Shop</a></h6>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 bg-grey">
                  <div class="p-5">
                    <h3 class="fw-bold mb-5 mt-2 pt-1">Summary</h3>
                    <hr class="my-4">

                    <div class="d-flex justify-content-between mb-4">
                      <h5 class="text-uppercase" id="warenkorb-item-quantity"></h5>
                      <h5 id="total-price2"><?php echo $_SESSION['warenkorb_total_price'] ?> € </h5>
                    </div>

                    <h5 class="text-uppercase mb-3">Shipping</h5>

                    <div class="mb-4 pb-2">
                      <select class="select">
                        <option value="1">Standard-Delivery- €5.00</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                        <option value="4">Four</option>
                      </select>
                    </div>

                    <h5 class="text-uppercase mb-3">Give code</h5>

                    <div class="mb-5">
                      <div class="form-outline">
                        <input type="text" id="form3Examplea2" class="form-control form-control-lg" />
                        <label class="form-label" for="form3Examplea2">Enter your code</label>
                      </div>
                    </div>

                    <hr class="my-4">
                    <!-- Gesamtpreis -->
                    <div class="d-flex justify-content-between mb-5 ">
                      <h5 class="text-uppercase">Gesamtpreis</h5>
                      <h5 id="total-price"></h5>
                    </div>

                    <button type="button" class="btn btn-dark btn-block btn-lg" data-mdb-ripple-color="dark"><a href="../regestrierung/regestrieren.php">Regestrieren</a></button>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  </div>
  </div>

</body>

<script>
  function updateTotalPrice() {

    // Den aktuellen Gesamtpreis aus der SESSION-Variable abrufen
    var totalPrice = <?php echo $_SESSION['warenkorb_total_price']; ?>;

    // Den Inhalt des "total-price"-Elements aktualisieren
    document.getElementById("total-price").innerHTML = `${totalPrice} €`;

  }

  function updateTotalItems() {

    // Die aktuelle Quantität aus der SESSION-Variable abrufen

    var totalItems = <?php echo count($_SESSION['cart']); ?>;

    if (totalItems > 1) {
      document.getElementById("warenkorb-item-quantity").innerHTML = `${totalItems} Produkte`;
    } else {

      totalItems === 0 ? document.getElementById("warenkorb-item-quantity").innerHTML = `Warenkorb leer` :
        document.getElementById("warenkorb-item-quantity").innerHTML = `${totalItems} Produkt`;
    }

    // Den Inhalt des "warenkorb-item-quantity"-Elements aktualisieren

  }

  window.onload = function() {

    updateTotalItems()
    updateTotalPrice();

  }
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

</html>