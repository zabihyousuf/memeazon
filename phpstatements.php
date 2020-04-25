<?php
  // authors: Andrew Neidringhaus, Steve Phan, Daniel Yenegeta & Zabih Yousuf
if (!isset($_SESSION)) {
  session_start();
}
include_once('connection.php');
function getMemes()
{
   global $db;
   $query = "select * from imageIdentifier left join Users on author=userId left join vote on imageIdentifier.imageId=vote.imageId order by vote.counter DESC;";
   $statement = $db->prepare($query);
   $statement->execute();

   // fetchAll() returns an array for all of the rows in the result set
   $results = $statement->fetchAll();

   // closes the cursor and frees the connection to the server so other SQL statements may be issued
   $statement->closecursor();

   return $results;
}
function getMemeOfTheDay()
{
   global $db;
   $query = "select * from memeOfTheDay left join imageIdentifier on memeOfTheDay.imageID = imageIdentifier.imageID left join Users on imageIdentifier.author=Users.userId Limit 1";
   $statement = $db->prepare($query);
   $statement->execute();

   // fetchAll() returns an array for all of the rows in the result set
   $results = $statement->fetchAll();

   // closes the cursor and frees the connection to the server so other SQL statements may be issued
   $statement->closecursor();

   return $results;
}
function getFriendInfo_by_name($imageID, $counter)
{
   global $db;
   $newcounter = $counter + 1;
   $query = "UPDATE vote SET counter=:counter WHERE imageID=:imageID";
   $statement = $db->prepare($query);
   $statement->bindValue(':imageID', $imageID);
   $statement->bindValue(':counter', $newcounter);
   $statement->execute();
   $statement->closeCursor();
}
function get_current_user_images($author)
{
   global $db;
   // $query = "select * from imageIdentifier left join Users on author=userId left join image on imageIdentifier.image order by vote.counter DESC";
   $query = "SELECT * from imageIdentifier LEFT JOIN vote ON imageIdentifier.imageID=vote.imageID WHERE author=:author ORDER BY vote.counter DESC";
   $statement = $db->prepare($query);
   $statement->bindValue(':author', $author);
   $statement->execute();

   // fetchAll() returns an array for all of the rows in the result set
   $results = $statement->fetchAll();

   // closes the cursor and frees the connection to the server so other SQL statements may be issued
   $statement->closecursor();

   return $results;
}
function usernameTaken($username)
{
  global $db;
  $query = "SELECT * FROM Users WHERE username=:username";
  $statement = $db->prepare($query);
  $statement->bindValue(':username', $username);
  $statement->execute();
  $result = $statement->fetch();
  $statement->closeCursor();
  if ($result) {
    return 1;
  } else {
    return 0;
  }
}
function addUser($firstname, $lastname, $username, $password)
{
  global $db;

  $check = "SELECT * FROM Users WHERE username=:username";
  $statement = $db->prepare($check);
  $statement->bindValue(':username', $username);
  $statement->execute();
  $result = $statement->fetch();
  $statement->closeCursor();
  if ($result) {
    $_SESSION['takenNameError'] = "There is already a user with that name";
    header("Location: signup.php");
  }
  $passwordHash = password_hash($password, PASSWORD_BCRYPT);
  $query = "INSERT INTO Users (firstname, lastname, username, password) VALUES (:firstname, :lastname, :username, :password)";
  $statement = $db->prepare($query);
  $statement->bindValue(':firstname', $firstname);
  $statement->bindValue(':lastname', $lastname);
  $statement->bindValue(':username', $username);
  $statement->bindValue(':password', $passwordHash);
  $statement->execute();
  $statement->closeCursor();
  $_SESSION['username'] = $_POST['username'];
  $_SESSION['welcome'] = "Welcome " . $_POST['username'] . "!";
  header("Location: home.php");
}
function validateUser($username, $password)
{
  global $db;
  $query = "SELECT * FROM Users WHERE username=:username";
  $statement = $db->prepare($query);
  $statement->bindValue(':username', $username);
  $statement->execute();
  $result = $statement->fetch();
  $statement->closeCursor();
  if (!$result) {
    $_SESSION['badNameError'] = "There is no user with that name, please enter a valid username";
    if (isset($_SESSION['notLoggedInError'])) {
      unset($_SESSION['notLoggedInError']);
    }
    header("Location: login.php");
  } else {
    if (isset($_SESSION['badNameError'])) {
      unset($_SESSION['badNameError']);
    }
  }

  $query = "SELECT password FROM Users WHERE username=:username";
  $statement = $db->prepare($query);
  $statement->bindValue(':username', $username);
  $statement->execute();
  $hashFromDB = $statement->fetch();
  $statement->closeCursor();
  $passFromDB = $hashFromDB[0];
  $pwd = htmlspecialchars($password);

  if (password_verify($pwd, $passFromDB)) {
    if (isset($_SESSION['badNameError'])) {
      unset($_SESSION['badNameError']);
    }
    if (isset($_SESSION['badPassError'])) {
      unset($_SESSION['badPassError']);
    }
    $_SESSION['username'] = $username;
    $_SESSION['welcome'] = "Welcome " . $username . "!";
    header("Location: home.php");
  } else {
    $_SESSION['badPassError'] = "Username and password do not match our records";
    if (isset($_SESSION['notLoggedInError'])) {
      unset($_SESSION['notLoggedInError']);
    }
    header("Location: login.php");
  }
}
function upvote($imageID) {

  global $db;
  $query = "UPDATE vote SET counter = counter + 1 WHERE imageID=:imageID";
  $statement = $db->prepare($query);
  $statement->bindValue(':imageID', $imageID);
  $statement->execute();
  $statement->closeCursor();
}
?>
