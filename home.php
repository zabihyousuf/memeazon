<?php
include_once('connection.php');
function getAllFriends()
{
   global $db;
   $query = "select * from imageIdentifier";
   $statement = $db->prepare($query);
   $statement->execute();

   // fetchAll() returns an array for all of the rows in the result set
   $results = $statement->fetchAll();

   // closes the cursor and frees the connection to the server so other SQL statements may be issued
   $statement->closecursor();

   return $results;
}
$rows = getAllFriends();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta author="Daniel Yenegeta & Mati Yiheyis">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Homepage</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
  integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" />
  <link rel="stylesheet" href="home.css" />
</head>
<body style="background-color:#CFE7FF">
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="home.php" style="font-family: 'Comic Sans MS';">Welcome to Memeazon</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="#">View your memes</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">View your timeline</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="postmeme.html">Post a meme</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Sign Out</a>
        </li>
      </ul>
    </div>
  </nav>
  <div class="container">
    <div class="row-fluid ">
    <!-- my php code which uses x-path to get results from xml query. -->
    <?php foreach ( $rows as $elements) : ?>
        <div class="col-sm-4 ">
            <div class="card-columns-fluid">
                <div class="card  bg-light" >
                    <img class="card-img-top"  src=" <?php echo $elements['image']; ?> " alt="Card image cap">
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
</div>
</body>
</html>
