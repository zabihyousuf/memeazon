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
function get_friends_memes()
{
   global $db;
   $query = "select * from imageIdentifier left join Users on author=userId left join following on Users.userId=following.userId order by Users.username DESC;";
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

function showAwards()
{
   global $db;
   $query = "select * from award left join imageIdentifier on memeId=imageId order by award.type DESC;";
   $statement = $db->prepare($query);
   $statement->execute();

   // fetchAll() returns an array for all of the rows in the result set
   $results = $statement->fetchAll();

   // closes the cursor and frees the connection to the server so other SQL statements may be issued
   $statement->closecursor();

   return $results;
}
function upvote($imageID, $counter)
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

  $userIDRandInt = rand(4, 20000);
  $check2 = "SELECT * FROM Users WHERE userID=:userID";
  $statement2 = $db->prepare($check2);
  $statement2->bindValue(':userID', $userIDRandInt);
  $statement2->execute();
  $result2 = $statement2->fetch();
  $statement2->closeCursor();
  if ($result2) {
    $_SESSION['takenNameError'] = "Sorry there was an error on our end. Try again please.";
    header("Location: signup.php");
  }
  $passwordHash = password_hash($password, PASSWORD_BCRYPT);
  $query = "INSERT INTO Users (userID, firstname, lastname, username, password, mySavedMemes) VALUES (:userID,:firstname, :lastname, :username, :password, 'none')";
  $statement = $db->prepare($query);
  $statement->bindValue(':userID', $userIDRandInt);
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
function searchResults($search)
{
   global $db;
   $query = "select * from imageIdentifier left join Users on author=userID where imageID in (select imageID from Tags where tagName LIKE :search)";
   $statement = $db->prepare($query);
   $statement->bindValue(':search', $search);
   $statement->execute();

   // fetchAll() returns an array for all of the rows in the result set
   $results = $statement->fetchAll();
   // closes the cursor and frees the connection to the server so other SQL statements may be issued
   $statement->closecursor();

   return $results;
}
function uploadMeme($url,$tag,$username)
{
  global $db;
  //get userID
  $query = 'select userID from Users where username=:username';
  $statement = $db->prepare($query);
  $statement->bindValue(':username', $username);
  $statement->execute();
  $userID = $statement->fetchAll();
  $statement->closecursor();
  foreach ( $userID as $elements):
    $userID= $elements['userID'];
  endforeach;


  //upload image
  $query = "insert into imageIdentifier (author, image) values (:userID, :url)";
  $statement = $db->prepare($query);
  $statement->bindValue(':userID', $userID);
  $statement->bindValue(':url', $url);
  $statement->execute();
  $statement->closeCursor();

  //get imageID
  $query = "select imageID from imageIdentifier where imageID=:url";
  $statement = $db->prepare($query);
  $statement->bindValue(':url', "6");
  $statement->execute();
  $imageID = $statement->fetchAll();
  $statement->closeCursor();
  foreach ( $imageID as $elements):
    $imageID= $elements['imageID'];
  endforeach;



  //insert into vote
  $query = "insert into vote (imageID, counter, trendMeter) values (:imageID, 0,0)";
  $statement = $db->prepare($query);
  $statement->bindValue(':imageID', $imageID);
  $statement->execute();
  $statement->closeCursor();

  //insert into Tags
  $tagIDRandInt = rand(4, 20000);
  $check2 = "SELECT * FROM Tags WHERE tagID=:tagID";
  $statement2 = $db->prepare($check2);
  $statement2->bindValue(':tagID', $tagIDRandInt);
  $statement2->execute();
  $result2 = $statement2->fetch();
  $statement2->closeCursor();
  if ($result2) {
    $_SESSION['takenNameError'] = "Sorry there was an error on our end. Try again please.";
    header("Location: postmeme.php");
  }
  $query = "insert into Tags (tagID, imageID, tagName) values (:tagID,:imageID, :tagName)";
  $statement = $db->prepare($query);
  $statement->bindValue(':tagID', $tagIDRandInt);
  $statement->bindValue(':imageID', $imageID);
  $statement->bindValue(':tagName', $tag);
  $statement->execute();
  $imageID = $statement->fetchAll();
  $statement->closeCursor();

  // header("Location: home.php");
}
?>
