<?php
include_once("db-connection.php");

if($conn == false){
    die("ERROR: Verbindung konnte nicht aufgebaut werden. Grund: " . mysqli_connect_error());
}


$stmt = "SELECT * FROM produkte;";
$db_members_query = mysqli_query($conn, $stmt);

if($db_members_query == false){
    echo "Problem mit der Query.";
}


?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form Aufgabe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link  href="style.css" rel="stylesheet"> 
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


<div class="container">
<div class="row">
<?php
while($record = mysqli_fetch_assoc($db_members_query)){
    ?>


<div class="col">

    <div class="card shadow-sm p-3 mb-5 bg-body rounded" style="width: 18rem;">
  <img src="<?php echo $record['picUrl']; ?>" class="card-img-top blah" alt="...">

  <div class="card-body">
    <h5 class="card-title"><?php echo $record['name']; ?></h5>
    <p class="card-text"><?php echo $record['beschreibung']; ?></p>
  </div>

  <ul class="list-group list-group-flush">
    <li class="list-group-item"><?php echo $record['preis']; ?> $</li>
  </ul>

  <div class="card-body">
  <button type="button" class="btn btn-primary"><a href="#" class="link-light">Kaufen</a></button>
  <button type="button" class="btn btn-primary"><a href="#" class="link-light">Bewerten</a></button>
  </div>


</div>


<?php

}
?>
</div>
</div>

</body>
</html>
