<?php
include_once("db-connection.php");

if($conn == false){
  die("ERROR: Verbindung konnte nicht aufgebaut werden. Grund: " . mysqli_connect_error());
}

if($_SERVER["REQUEST_METHOD"] == "POST" || isset($_POST['submit'])){

  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $password2 = $_POST["password2"];
  $vorname = $_POST["vorname"];
  $nachname = $_POST["nachname"];

  if($password != $password2){
    echo "Passwörter stimmen nicht überein.";
  }

  if(!isset($email) || !isset($username)){
    echo "Email oder Username müssen eingetragen sein.";
  }

  $query = "SELECT id FROM form_member WHERE email = '$email'";
  $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            echo "<p>Email existiert bereits!</p>";
        } else {
          $passwordHashed = password_hash($password, PASSWORD_DEFAULT);
          $query = "INSERT INTO `form_member` (`id`, `username`, `email`, `password`, `name`, `vorname`) VALUES (NULL, '$username', '$email', '$passwordHashed', '$nachname', '$vorname');";

          if(!mysqli_query($conn, $query)){
            echo "Signup ist fehlgeschlagen.";
          }
            header("Location: login.php");
          

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
  </head>
  <body>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  
  <nav class="navbar navbar-expand-lg bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Form Seite</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="login.php">Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="produkte.php">Produkte</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Optionen
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="regestrieren.php">Regestrieren</a></li>
            <li><a class="dropdown-item" href="login.php">Login</a></li>
            <li><hr class="dropdown-divider"></li>
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
  
<div class="wrapper">
        <div class="form-left">
            <h2 class="text-uppercase">information</h2>
            <p>
                Hier können sie sich anmelden. 
            </p>
            
            <div class="form-field">
                <input method="post" action="" type="submit" class="account" value="Haben Sie bereits ein Konto?">
            </div>
        </div>

        <form class="form" method="post">
            <h2 class="text-uppercase">Registration</h2>
            <div class="row">
                <div class="col-sm-6 mb-3">
                    <label>username</label>
                    <input type="text" name="username" id="username" class="input-field">
                </div>
            <div class="row">
                <div class="col-sm-6 mb-3">
                    <label>Vorname</label>
                    <input type="text" name="vorname" id="first_name" class="input-field">
                </div>
                <div class="col-sm-6 mb-3">
                    <label>Nachname</label>
                    <input type="text" name="nachname" id="last_name" class="input-field">
                </div>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" class="input-field" name="email" required>
            </div>
            <div class="row">
                <div class="col-sm-6 mb-3">
                    <label>Password</label>
                    <input type="password" name="password" id="pwd" class="input-field">
                </div>
                <div class="col-sm-6 mb-3">
                    <label>Password wiederholen</label>
                    <input type="password" name="password2" id="cpwd" class="input-field">
                </div>
            </div>
            
            <div class="form-field">
                <input type="submit" value="Sign In" class="register" name="submit">
            </div>
        </form>
    </div>




</body>
</html>