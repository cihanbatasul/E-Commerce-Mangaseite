<?php
session_start();

// Variablen
$URL =  $_SERVER['PHP_SELF'];

// Importierung der Klassen "DB" und "ProductController"
include('./classes/DB.php');
include('./classes/ProductController.php');
include('./classes/UserController.php');

try {

    $database = new DB("localhost", "crud", "root", "");
} catch (PDOException $e) {

    die("ERROR: Verbindung konnte nicht aufgebaut werden. Grund: " . $e->getMessage());
}

$productController = new ProductController($database);

$searchResult = $productController->getProductBySearch($_SESSION['search']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['id'])) {
        $_SESSION['product_page_id'] = $_POST['id'];
        header("location: ./produktseite.php");
        exit;
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
    <style>
        @import url('https://fonts.googleapis.com/css2?family=EB+Garamond&family=Rajdhani:wght@300;400&display=swap');

        * {
            font-family: 'Rajdhani', sans-serif;

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

        .img {
            width: 50%;
            height: 4vw;
            object-fit: cover;
        }

        .searchResultTable {
            margin-top: 5rem;
        }

        /** Footer **/
        footer {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #0496ff;
            margin-top: auto;
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


    </div>

    </div>
    <div class="container searchResultTable">
        <div class="row">
            <h3>Ergebnisse:</h3>

            <?php
            if (count($searchResult) > 0) { ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col"></th>
                            <th scope="col">Link</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($searchResult as $row) {

                        ?>
                            <form method="post">
                                <tr>
                                    <th scope="row"><?php echo $row['id'] ?>
                                    </th>
                                    <td><?php echo $row['name'] ?></td>
                                    <td><img class="img" src="./pics/<?php echo $row['picUrl'] ?>" /></td>
                                    <input type="hidden" name="id" value="<?php echo $row['id'] ?>" />

                                    <td> <button type="submit" href="">Zur Produktseite</button></td>

                                </tr>
                            </form>
                        <?php } ?>
                    </tbody>
                </table>

            <?php
            } else { ?>

                <h3>Es gibt keine Produkte, die den Suchkriterien entspricht.</h3>

            <?php
            }
            ?>
        </div>
    </div>


    </div>
    </div>
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