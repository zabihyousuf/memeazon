<?php
include_once('connection.php');
require('phpstatements.php');
if (!empty($_POST['action']))
{
   if ($_POST['action'] == "UpVote"){
     getFriendInfo_by_name($_POST['imageID'], $_POST['counter']);
   }
}
$rows = getMemes();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta author="Andrew Neidringhaus, Steve Phan, Daniel Yenegeta & Zabih Yousuf">
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
          <a class="nav-link" href="yourmemes.php">View your memes</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">View your timeline</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="postmeme.html">Post a meme</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="memeday.php">Meme of the Day</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="memeday.php">Signed Out</a>
        </li>
      </ul>
    </div>
  </nav>
  <div class="container">
    <table style="width:100%">
      <tr>
        <th>Meme</th>
        <th>Author</th>
        <th>Upvote</th>
      </tr>
      <?php foreach ( $rows as $elements) : ?>
        <tr>
          <td>
            <img class="card-img-top"  src=" <?php echo $elements['image']; ?> " alt="Card image cap" style="max-width:100px;">
          </td>
          <td><?php echo $elements['username']; ?></td>
          <td>
            <form action ='home.php' method ='post'>
              <input type="submit" value="UpVote" name="action" class="btn btn-primary" />
              <input type="hidden" name="imageID" value="<?php echo $elements['imageID'] ?>" />
              <input type="hidden" name="counter" value="<?php echo $elements['counter']; ?>" />
            </form>
          </td>
          <td><?php echo $elements['counter']; ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
    <!-- my php code which uses x-path to get results from xml query. -->
    </div>
</div>
</body>
</html>
