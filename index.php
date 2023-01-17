<?php
session_start();
// Variablen 
$_SESSION["product_page_id"] = 0;
if (isset($_SESSION["loggedin"]) && ($_SESSION["loggedin"] === true)) {
  //header("location: warenkorb.php");
  //exit;
}

// Importierung der Klassen "DB" und "ProductController"
include('./classes/DB.php');
include('./classes/ProductController.php');

// Erstellen einer neuen DB-Verbindungen anhand des Konstruktors der "DB"-Klasse
// Wenn die Verbindung fehlgeschlagen ist, gibt das IF-Statement eine Fehlermeldung aus
try {

  $database = new DB("localhost", "crud", "root", "");
} catch (PDOException $e) {

  die("ERROR: Verbindung konnte nicht aufgebaut werden. Grund: " . $e->getMessage());
}

$productController = new ProductController($database);
$data = [];
$data =  $productController->getAll();

$categories = $productController->getCategoryItems();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // suche
  if (isset($_POST['search'])) {
    $_SESSION['search'] = $_POST['search'];
    header("location: search.php");
    exit;
  }
  // top 10 Weiterleitung zur Produktseite
  if (isset($_POST['topRatedButton'])) {
    $_SESSION["product_page_id"] = $_POST['toprated_id'];
    header("location: ./produktseite.php");
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
  <link href="indexStyle.css" rel="stylesheet" type="text/css">
  <script defer src="https://kit.fontawesome.com/59e04dcbeb.js" crossorigin="anonymous"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  <script defer src="./SlideIn.js"></script>
</head>

<body>

  <style>
    @import url('https://fonts.googleapis.com/css2?family=EB+Garamond&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=EB+Garamond&family=Rajdhani:wght@300;400&display=swap');

    * {
      font-family: 'Rajdhani', sans-serif;

    }


    body {
      background-color: #ffffff;
      overflow-x: hidden;
      display: flex;
      min-height: 100vh;
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

    .product-categories {
      background-color: #ffffff;
      margin-bottom: 10rem;
      color: #284b63;
    }

    .slider-div {
      background-color: #ffffff;
    }

    .customContainer {
      background-color: #ffffff;

    }

    .productBody {
      display: flex;
      align-items: center;

      padding: 3rem;
    }

    .productBody>h5 {
      padding: 2rem;
      margin-left: 4rem;
    }



    .categoriesTest {
      display: flex;
      flex-direction: row;
      margin-top: 7rem;
      flex-wrap: wrap;
    }

    .categoryTitle {
      display: flex;
      flex-direction: row;

    }

    .categoryItemDiv {
      display: flex;
      flex-direction: row;
      margin-left: 5rem;
      flex-wrap: wrap;

    }

    .categoryItems {
      margin-left: 5rem;
      margin-bottom: 3rem;
      width: 200px;
      height: 200px;
      background-size: cover;
      border-radius: 2px;
      color: white;
      overflow: hidden;
      text-overflow: ellipsis;
      border: 0;
    }


    .categoryItems>p {
      font-size: small;
      visibility: hidden;
    }

    .categoryItems:hover {
      cursor: pointer;
      background: #1d3557;

      box-shadow: rgba(50, 50, 93, 0.25) 0px 30px 60px -12px, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px;

    }

    .categoryItems:hover p {

      visibility: visible;

    }

    .hidden {
      opacity: 0;
      transform: translateX(-100%);
      transition: all 3s;
      filter: blur(2);
    }

    .hidden:nth-child(2) {
      transition-delay: 200ms;
    }

    .hidden:nth-child(3) {
      transition-delay: 400ms;
    }

    .hidden:nth-child(4) {
      transition-delay: 600ms;
    }

    .hidden:nth-child(5) {
      transition-delay: 800ms;
    }


    .hidden2 {
      opacity: 0;
      transform: translateX(100%);
      transition: all 3s;
      filter: blur(2);
    }

    .hidden2:nth-child(2) {
      transition-delay: 100ms;
    }

    .hidden2:nth-child(3) {
      transition-delay: 200ms;
    }

    .hidden2:nth-child(4) {
      transition-delay: 300ms;
    }

    .hidden2:nth-child(5) {
      transition-delay: 400ms;
    }


    .show {
      opacity: 1;
      filter: blur(0);
      transform: translateX(0);
    }

    /** toprated */

    .toprated {
      display: flex;
      flex-direction: column;

      margin-bottom: 20rem;
    }

    .title {
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .toprated-img {
      width: auto;
      height: 100px;

    }

    /** Footer **/
    footer {

      display: flex;
      position: relative;
      width: 100%;
      min-height: 100%;

      align-items: center;
      justify-content: center;
      flex-direction: column;
      background-color: #3586ff;
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

    footer .wave {

      position: absolute;
      top: -100px;
      left: 0;
      width: 100%;
      height: 100px;
      background: url(./pics/wave.png);
      background-size: 1000px 100px;
    }

    footer .wave#wave1 {
      z-index: 1000;
      opacity: 1;
      bottom: 0;
      animation: animateWave 4s linear infinite;
    }

    footer .wave#wave2 {
      z-index: 999;
      opacity: 0.5;
      bottom: 10px;
      animation: animateWave 4s linear infinite;
    }

    footer .wave#wave3 {
      z-index: 1000;
      opacity: 0.2;
      bottom: 15px;
      animation: animateWave 3s linear infinite;
    }

    footer .wave#wave4 {
      z-index: 999;
      opacity: 0.7;
      bottom: 20px;
      animation: animateWave_02 3s linear infinite;
    }

    @keyframes animateWave {
      0% {
        background-position-x: 1000px;
      }

      100% {
        background-position-x: 0px;
      }
    }

    @keyframes animateWave_02 {
      0% {
        background-position-x: 0px;
      }

      100% {
        background-position-x: 1000px;
      }
    }

    .intro-text {
      margin-top: 4rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .intro-text-cols {
      display: flex;

    }

    @media (max-width: 390px) {
      .intro-text {}
    }
  </style>



  <div class="customContainer">
    <nav class="navbar navbar-expand-lg shadow outermost-navdiv">
      <div class=" container-fluid">
        <a class="navbar-brand fs-5" href="index.php"><img src="./pics/webtoons.png"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="index.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="produkte.php">Produkte</a>
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


    <div class="row slider-div">
      <div id="carousel-featured" class="carousel slide" data-bs-ride="false">
        <div class="carousel-indicators">
          <button type="button" data-bs-target="#carousel-featured" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
          <button type="button" data-bs-target="#carousel-featured" data-bs-slide-to="1" aria-label="Slide 2"></button>
          <button type="button" data-bs-target="#carousel-featured" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
          <div class="carousel-item active c-item">
            <img src="./pics/eren-titan.jpg" class="d-block w-100 c-img" alt="...">
            <div class="carousel-caption top-0 mt-4  carousel-featured-caption">
              <p class="mt-5 fs-3 text-uppercase">Bald erhältlich!</p>
              <h1 class="display-1 fw-bolder text-capitalize">Attack On Titan</h1>
              <button class="btn btn-primary mt-2 px-4 py-2 fs-5 mt-5 slider-button-1">Zum Manga</button>
            </div>
          </div>
          <div class="carousel-item c-item">
            <img src="./pics/haikyu-slider.png" class="d-block w-100 c-img" alt="...">
            <div class="carousel-caption top-0 mt-4  carousel-featured-caption">
              <p class="mt-5 fs-3 text-uppercase">Volleyball.</p>
              <h1 class="display-1 fw-bolder text-capitalize">Haikyu</h1>
              <button class="btn btn-primary mt-2 px-4 py-2 fs-5 mt-5 slider-button-2">Zum Manga</button>
            </div>
          </div>
          <div class="carousel-item c-item">
            <img src="./pics/slide-cowboy.png" class="d-block w-100 c-img" alt="...">
            <div class="carousel-caption top-0 mt-4 carousel-featured-caption">
              <p class="mt-5 fs-3 text-uppercase">Some representative placeholder content for the first slide.</p>
              <h1 class="display-1 fw-bolder text-capitalize">Cowboy Bebop</h1>
              <button class="btn btn-primary mt-2 px-4 py-2 fs-5 mt-5 slider-button-3">Zum Manga</button>
            </div>
          </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carousel-featured" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carousel-featured" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>

    </div>

    <div cass="row mt-4">
      <div class="container intro-text">
        <h2>Diese Seite</h2>
        <div class="intro-text-cols">
          <div class="col">
            <div class="p-5">
              Lorem ipsum dolor sit amet consectetur adipisicing elit. In mollitia vero, sapiente ducimus non aliquid illum magnam nobis! Quos repellendus itaque nobis sed? Nihil quod pariatur sed blanditiis quisquam? Reiciendis!
              Deserunt cumque praesentium laudantium asperiores porro veniam fugit! Possimus enim vitae tempora, est aperiam ex cupiditate nobis dolores! Earum fugiat repellat hic dolore eius qui magni quaerat dolorem, temporibus quae?
              Similique facilis tempora laudantium sequi, fuga eligendi. Est eius magni quibusdam! Voluptate explicabo ipsa, provident assumenda, a laboriosam rerum est temporibus rem praesentium dolorum eos. Adipisci tenetur iusto a voluptates.
              Blanditiis architecto beatae excepturi sed, maxime consequatur eius, quae magni molestias nam iusto. Illo est animi dolor illum. Pariatur vitae numquam, sed accusamus dolore velit ipsa error voluptatum dicta ad.
              Eligendi eius alias iste quos molestias similique veniam aperiam blanditiis natus doloremque, nemo harum possimus quam eaque optio ad commodi cumque! Nostrum, quae accusamus ea reiciendis eius ex ipsum expedita?</div>
          </div>
          <div class="col">
            <div class="p-5">
              Lorem ipsum dolor sit amet consectetur adipisicing elit. Eaque culpa architecto totam dolorum optio error similique veritatis vel sequi magni dignissimos alias qui exercitationem cum, harum autem. Obcaecati, eligendi numquam!
              Ratione, quae et itaque fugit quod fugiat tempore pariatur. Tenetur harum, sint eligendi quas, officiis aliquam dolor eveniet alias magnam placeat minus at laborum facilis molestiae soluta repellat, itaque libero.
              Tempora dolorum cupiditate fugit doloribus! Aut ipsam harum voluptatibus quo alias accusamus blanditiis sapiente velit hic, non quidem maiores repudiandae aperiam, omnis illo obcaecati quae? Quasi possimus repellat consectetur nostrum.
              Aperiam ea at non numquam quasi, odio, doloribus reiciendis illo rem ipsum ullam cumque inventore nihil unde tempore, vel sapiente natus. Accusantium fugiat numquam velit? Ducimus veniam praesentium possimus voluptates?
              Porro quos quibusdam facere quo dolore enim molestias dicta inventore. Animi, eos excepturi consequuntur laborum labore quaerat, veritatis necessitatibus aliquid totam, vel sequi doloribus veniam reprehenderit placeat voluptatibus sint nesciunt!</div>
          </div>
        </div>
      </div>
    </div>



    <div class="container product-categories">
      <div class="row top-0 mt-4 product-categories2">
        <div class="header-wrap">
          <h2 class="product-categories-heading hidden">ALLE KATEGORIEN
          </h2>
        </div>

        <div class="categoriesTest hidden">
          <div class="categoryTitle hidden">
            <h5 class="categoryDescription">Sport</h5>
          </div>



          <div class="categoryItemDiv ">

            <?php
            $countSport = 0;


            $usedIdsSport = [];

            while ($countSport < 3) {

              foreach ($data as $row) {

                if ($row['genre'] === 5  && in_array($row['id'], $usedIdsSport) === false && $countSport < 3) {

            ?>
                  <div class="categoryItems  " style="background-image: url('./pics/<?php echo $row['picUrl'] ?>')">
                    <h5>
                      <?php echo $row['name'];
                      ?>
                    </h5>

                    <p><?php echo $row['beschreibung'] ?></p>
                  </div>


            <?php
                  array_push(
                    $usedIdsSport,
                    $row['id']
                  );
                  $countSport++;
                }
              }
            }
            ?>

          </div>
        </div>


        <div class="categoriesTest hidden2">
          <div class="categoryTitle hidden2">
            <h5 class="categoryDescription">Action</h5>
          </div>

          <div class="categoryItemDiv">

            <?php
            $countAction = 0;
            $usedIdsAction = [];

            while ($countAction < 3) {

              foreach ($data as $row) {

                if ($row['genre'] === 1  && in_array($row['id'], $usedIdsAction) === false && $countAction < 3) {

            ?>
                  <div class="categoryItems" style="background-image: url('./pics/<?php echo $row['picUrl'] ?>')">
                    <h5>
                      <?php echo $row['name'];
                      ?>
                    </h5>

                    <p><?php echo $row['beschreibung'] ?></p>
                  </div>


            <?php
                  array_push(
                    $usedIdsAction,
                    $row['id']
                  );
                  $countAction++;
                }
              }
            }
            ?>
          </div>



        </div>



      </div>
    </div>
  </div>

  <!--- top rated -->
  <div class="row toprated">
    <div class="col title">
      <h2 class="title hidden">TOP RATED</h2>
    </div>
    <div class="col">
      <div class="container">

        <table class="table">
          <thead>
            <tr class="hidden2">
              <th scope="col">Platz</th>
              <th scope="col">Name</th>
              <th scope="col"></th>
              <th scope="col">Link</th>
            </tr>
          </thead>

          <tbody>
            <?php

            $topRated = $data;
            $topRatedCount = 0;
            usort($topRated, function ($a, $b) {

              if ($a['rating'] == $b['rating']) {
                return 0;
              }
              return ($a['rating'] > $b['rating']) ? -1 : 1;
            });



            while ($topRatedCount <= 6) {
              foreach ($topRated  as $row) {
                $topRatedCount++;
                if ($topRatedCount <= 6) {
                  $className;
                  if ($topRatedCount % 2 === 0) {
                    $className = "hidden";
                  } else {
                    $className = "hidden2";
                  }
            ?>
                  <form method="post">
                    <tr class="<?php echo $className ?>">
                      <th scope="row"><?php echo $topRatedCount ?></th>
                      <td><?php echo $row['name'] ?></td>
                      <td><img class="toprated-img" src="./pics/<?php echo $row['picUrl'] ?>" /></td>
                      <input type="hidden" name="toprated_id" value="<?php echo $row['id'] ?>" />
                      <td><button type="submit" name="topRatedButton">Zur Produktseite</button></td>
                    </tr>
                  </form>
            <?php
                }
              }
            }
            ?>
          </tbody>

        </table>

      </div>
    </div>
  </div>

  <!-- Footer -->
  <div class="row">

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
  </div>





  </div>

  </div>


  </div>

  </div>







  </div>

  </div>



  </div>
  <script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

    // Login Button wird entfernt, wenn User eingeloggt ist
    const isLoggedIn = <?php echo $_SESSION['loggedin'] ?>;
    const loginButton = document.querySelectorAll('.login-button');
    const warenkorbButton = document.querySelectorAll('.warenkorb-button')
    const logoutbutton = document.getElementById('logout-button');

    if (isLoggedIn) {

      for (let i = 0; i < loginButton.length; i++) {
        loginButton[i].hidden = true;

      }

    } else {

      for (let i = 0; i < loginButton.length; i++) {
        loginButton[i].hidden = false;

      }


    }

    function load_search_autocomplete(query) {
      if (query.length > 2) {
        var form_data = new FormData();
      } else {
        document.getElementById('search_result').innerHTML = '';
      }
    }
  </script>
</body>

</html>