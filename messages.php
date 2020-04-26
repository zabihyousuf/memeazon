<?php
include_once('connection.php');
require('phpstatements.php');
if (!isset($_SESSION)) {
  session_start();
}
if (!isset($_SESSION['username'])) {
  $_SESSION['notLoggedInError'] = "You are not logged in";
  header("Location: login.php");
}
$rows = getAllMessages($_SESSION['userID']);
if (!empty($_POST['action']) && $_POST['action'] == "SendMsg") {
  sendMessage($_POST['receiver'], $_POST['meme'], $_POST['message']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta author="Andrew Neidringhaus, Steve Phan, Daniel Yenegeta & Zabih Yousuf">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>View your messages!</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
  integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" />
  <link rel="stylesheet" href="messages.css" />
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
          <a class="nav-link" href="postmeme.php">Post a meme</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="memeday.php">Meme of the Day</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="messages.php">Messages</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="follow.php">Follow Users</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Sign Out</a>
        </li>
      </ul>
    </div>
  </nav>
  <div class="container">
    <table style="width:100%">
        <tr>
          <th>Meme</th>
          <th>Message</th>
        </tr>
        <?php foreach ( $rows as $elements) : ?>
          <tr>
            <td>
              <a href="<?php echo $elements["image"]; ?> "><?php echo $elements["image"]; ?></a>
            </td>
            <td>
              <?php echo($elements['textContent']) ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  <div class="container">
    <form method="POST" action="" class="memeform">
      <input type="text" name="receiver" placeholder="Enter username" autofocus />
      <input type="text" name="meme" placeholder="Enter meme url" autofocus />
      <input type="text" name="message" placeholder="Enter message" autofocus />
      <input type="submit" name="action" value="SendMsg" class="btn btn-primary" />
    </form>
    <?php
      if (isset($_SESSION['msgError'])) {
        echo ("<p>" . $_SESSION['msgError'] . "</p>");
      }
      if (isset($_SESSION['takenMsgIDError'])) {
        echo ("<p>" . $_SESSION['takenMsgIDError'] . "</p>");
      }
    ?>
  </div>
</body>
</html>
