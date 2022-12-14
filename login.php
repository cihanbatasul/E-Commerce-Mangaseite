<?php
session_start();

if(isset($_SESSION["loggedin"]) && ($_SESSION["loggedin"] == true)){
    header("location: welcome.php");
    exit;
}

include_once("db-connection.php");

if($conn == false){
    die("ERROR: Verbindung konnte nicht aufgebaut werden. Grund: " . mysqli_connect_error());
}

if($_SERVER["REQUEST_METHOD"] == "POST" || isset($_POST['submit'])){



    $username = $_POST["email"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    if(!$email || !$password){
        echo "Alles ausfüllen.";
    }

    $stmt = "SELECT id FROM form_member WHERE email = '$email' and 'password' = '$password';";
    $result = mysqli_query($conn, $stmt);
    if($result == false){
        echo "iein feher mit der query";
    }

    $rows = mysqli_num_rows($result);

    
    if($rows == 1){
        echo "testhallo";
        $_SESSION['login_user'] = $username;


        header("Location: welcome.php");
    }

    if($rows == 0){
        echo "FFFFFF";
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
    <a class="navbar-brand" href="#">Form Seite</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Login</a>
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
  
<div class="container form-container">
<form method="post">
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Email Adresse / Username</label>
    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email">
    
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Password</label>
    <input type="password" class="form-control" id="exampleInputPassword1" name="password">
  </div>
  <div class="mb-3 form-check">
    
  </div>
  <button type="submit" class="btn btn-primary" name="submit">Submit</button>
  <p>Haben sie noch keinen Account? <a href="regestrieren.php">Klicken sie hier.</a></p>
</form>
</div>




</body>
</html>