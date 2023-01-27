<?php
session_start();
// (Session)variablen


$URL =  $_SERVER['PHP_SELF'];

$loginStatus;

if (!isset($_SESSION['loggedin'])) {
    $loginStatus = false;
};

if (isset($_SESSION["loggedin"]) && ($_SESSION["loggedin"] === false)) {
    header("location: index.php");
    exit;
}

// DB und Produktcontroller werden eingebunden
include(__DIR__ . '/../../classes/DB.php');
include(__DIR__ . '/../../classes/ProductController.php');

include('../../classes/UserController.php');

// Erstellen einer neuen DB-Verbindungen anhand des Konstruktors der "DB"-Klasse
// Wenn die Verbindung fehlgeschlagen ist, gibt das IF-Statement eine Fehlermeldung aus
try {

    $database = new DB("localhost", "crud", "root", "passwordForWebsite");
} catch (PDOException $e) {

    die("ERROR: Verbindung konnte nicht aufgebaut werden. Grund: " . $e->getMessage());
}

$userController = new UserController($database);
$user = $userController->getUserById($_SESSION['user_id']);
var_dump($user);


// login 

if (isset($_POST['login-prompt-submit-button'])) {

    if (!isset($_SESSION['loggedin']) || ($_SESSION['loggedin'] === false)) {

        $email = $_POST['login_email_input'];
        $password = $_POST['login_password_input'];



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




// Login-Prompt und Produkt ins Warenkorb

if ($_SERVER["REQUEST_METHOD"] == "POST" || isset($_POST['submit'])) {
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
    <link href="../../indexStyle.css" rel="stylesheet">
    <link href="profil.css" rel="stylesheet">
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=EB+Garamond&display=swap');
    </style>

    <nav class="navbar navbar-expand-lg shadow outermost-div">
        <div class="container-fluid">
            <!-- "Logo" -->
            <a class="navbar-brand fs-5" href="index.php"><img src="../../pics/webtoons.png"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <!-- Index -->
                    <li class="nav-item">
                        <a class="nav-link " aria-current="page" href="../startseite/index.php">Home</a>
                    </li>
                    <!-- Produkte -->
                    <li class="nav-item">
                        <a class="nav-link " aria-current="page" href="../produkte/produktAuflistung/produkte.php">Produkte</a>
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
                                <hr class="dropdown-divider login-button">
                            </li>
                            <li><a class="dropdown-item" href="../produkte/produktAuflistung/produkte.php">Produkte</a></li>
                        </ul>
                    </li>

                </ul>
                <!-- Suchfunktion -->
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
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

                    <!-- Logout -->
                    <a class="nav-link" id="logout-button" aria-current="page" href="../../unset_session_variables.php" onclick=""><i class="fa-solid fa-person-through-window fa-lg"></i></a>
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



    <div class="container">
        <div class="row reihe">
            <div class="col-4  und">
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Voluptatibus enim laborum repellendus. Ducimus harum quisquam ipsam ab, atque illum beatae voluptatem dolorum perferendis, in doloribus, maxime praesentium id. Ea, fuga.
                    Enim vero veniam, error necessitatibus repudiandae iste dolores ipsa neque asperiores. Voluptatum velit tenetur quos obcaecati nisi veritatis magnam! Dolor, illum molestiae aliquid inventore ipsa veniam ipsum nobis labore adipisci!
                    Sunt beatae obcaecati eligendi ex vitae? Autem voluptate id voluptatem vero neque unde dolorem, eligendi numquam optio excepturi iusto ipsa cum cumque nemo, architecto totam iure odit deserunt nihil quidem?
                    Consectetur, hic provident! Error excepturi nihil, distinctio quasi veritatis ullam facere quos quia perferendis hic, exercitationem architecto consectetur cum delectus iusto sint deleniti ducimus nulla consequuntur commodi. Voluptatibus, et quis!
                    Voluptas aut ex necessitatibus ullam veniam, eius commodi cupiditate, expedita quidem quis dignissimos excepturi deserunt nostrum provident. Dicta eos, suscipit explicabo dolorem repudiandae nobis beatae. Accusantium facere unde atque quia.</p>


            </div>
            <div class="col-8  und">
                <p>
                    <?php echo $user[0]['username']  ?>
                </p>

                <button class="btn btn-primary">Nachricht senden</button>
                <button class="btn btn-primary">Kontaktanfrage senden</button>
                <button class="btn btn-primary">Iwas senden</button>
            </div>
        </div>
        <div class="row reihe2">
            <div class="col-4 und2">
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Voluptatibus enim laborum repellendus. Ducimus harum quisquam ipsam ab, atque illum beatae voluptatem dolorum perferendis, in doloribus, maxime praesentium id. Ea, fuga.
                    Enim vero veniam, error necessitatibus repudiandae iste dolores ipsa neque asperiores. Voluptatum velit tenetur quos obcaecati nisi veritatis magnam! Dolor, illum molestiae aliquid inventore ipsa veniam ipsum nobis labore adipisci!
                    Sunt beatae obcaecati eligendi ex vitae? Autem voluptate id voluptatem vero neque unde dolorem, eligendi numquam optio excepturi iusto ipsa cum cumque nemo, architecto totam iure odit deserunt nihil quidem?
                    Consectetur, hic provident! Error excepturi nihil, distinctio quasi veritatis ullam facere quos quia perferendis hic, exercitationem architecto consectetur cum delectus iusto sint deleniti ducimus nulla consequuntur commodi. Voluptatibus, et quis!
                    Voluptas aut ex necessitatibus ullam veniam, eius commodi cupiditate, expedita quidem quis dignissimos excepturi deserunt nostrum provident. Dicta eos, suscipit explicabo dolorem repudiandae nobis beatae. Accusantium facere unde atque quia.</p>
            </div>
            <div class="col-8 und2">
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Voluptatibus enim laborum repellendus. Ducimus harum quisquam ipsam ab, atque illum beatae voluptatem dolorum perferendis, in doloribus, maxime praesentium id. Ea, fuga.
                    Enim vero veniam, error necessitatibus repudiandae iste dolores ipsa neque asperiores. Voluptatum velit tenetur quos obcaecati nisi veritatis magnam! Dolor, illum molestiae aliquid inventore ipsa veniam ipsum nobis labore adipisci!
                    Sunt beatae obcaecati eligendi ex vitae? Autem voluptate id voluptatem vero neque unde dolorem, eligendi numquam optio excepturi iusto ipsa cum cumque nemo, architecto totam iure odit deserunt nihil quidem?
                    Consectetur, hic provident! Error excepturi nihil, distinctio quasi veritatis ullam facere quos quia perferendis hic, exercitationem architecto consectetur cum delectus iusto sint deleniti ducimus nulla consequuntur commodi. Voluptatibus, et quis!
                    Voluptas aut ex necessitatibus ullam veniam, eius commodi cupiditate, expedita quidem quis dignissimos excepturi deserunt nostrum provident. Dicta eos, suscipit explicabo dolorem repudiandae nobis beatae. Accusantium facere unde atque quia.</p>
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

        const loginButton = document.querySelectorAll(' .login-button');
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