<?php
if (!isset($_SESSION)) {
  session_start();
}
if (!isset($_SESSION['username'])) {
  $_SESSION['notLoggedInError'] = "You are not logged in";
  header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta author="Andrew Neidringhaus, Steve Phan, Daniel Yenegeta & Zabih Yousuf">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Post a meme!</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
    integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" />
    <link rel="stylesheet" href="postmeme.css" />
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
            <a class="nav-link" href="yourmemes.php">View your memes</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="timeline.php">View your timeline</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="postmeme.html">Post a meme</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="memeday.php">Meme of the Day</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">Sign Out</a>
          </li>
        </ul>
      </div>
    </nav>
    <?php
      include_once('connection.php');
      global $db;
      $query = "INSERT INTO imageIdentifier VALUES ((SELECT userID FROM Users WHERE username=$username), $_POST[\"inputbox\"], 1+(SELECT MAX(imageID) FROM imageIdentifier), $_POST[\"meme\"])";
      $statement = $db->prepare($query);
      $statement->execute();
      if ($result) {
        echo "<h2 style=\"text-align: center; margin-top: 40px;\">Your meme has been posted!</h2>";
      } else {
        echo "<h2 style=\"text-align: center; margin-top: 40px;\">Oops! Something went wrong while posting your meme :(</h2>";
      }
     ?>

  </body>
</html>
