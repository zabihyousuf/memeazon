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
function delete($imageID)
{
  global $db;
  $query = "DELETE FROM Tags WHERE imageID=:imageID";
  $statement = $db->prepare($query);
  $statement->bindValue(':imageID', $imageID);
  $statement->execute();
  $statement->closeCursor();
  $query = "DELETE FROM vote WHERE imageID=:imageID";
  $statement = $db->prepare($query);
  $statement->bindValue(':imageID', $imageID);
  $statement->execute();
  $statement->closeCursor();
  $query = "DELETE FROM imageIdentifier WHERE imageID=:imageID";
  $statement = $db->prepare($query);
  $statement->bindValue(':imageID', $imageID);
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
  $_SESSION['userID'] = $userIDRandInt;
  $_SESSION['welcome'] = "Welcome " . $_POST['username'] . "!";
  header("Location: home.php");
}
function getUserID($username) {

  global $db;
  $query = "SELECT userID FROM Users WHERE username=:username";
  $statement = $db->prepare($query);
  $statement->bindValue(':username', $username);
  $statement->execute();
  $result = $statement->fetch();
  $statement->closeCursor();
  return $result;
}
function getImageID($image) {

  global $db;
  $query = "SELECT imageID FROM imageIdentifier WHERE image=:image";
  $statement = $db->prepare($query);
  $statement->bindValue(':image', $image);
  $statement->execute();
  $result = $statement->fetch();
  $statement->closeCursor();
  return $result;
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
    if (isset($_SESSION['notLoggedInError'])) {
      unset($_SESSION['notLoggedInError']);
    }
    $_SESSION['username'] = $username;
    $_SESSION['userID'] = getUserID($username)[0];
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
function getAllMessages($userID) {

  global $db;
  $query = "SELECT * FROM message NATURAL JOIN imageIdentifier WHERE userID=:userID";
  $statement = $db->prepare($query);
  $statement->bindValue(':userID', $userID);
  $statement->execute();
  $results = $statement->fetchAll();
  $statement->closeCursor();
  return $results;
}
function sendMessage($username, $image, $textContent) {

  global $db;
  $_SESSION['testing'] = 'testing';
  $userID = getUserID($username)[0];
  $imageID = getImageID($image)[0];
  if (!isset($userID) || !isset($imageID)) {
    $_SESSION['msgError'] = "Please make sure you've entered a valid username and image url";
    header("Location: messages.php");
  }

  $msgIDRandInt = rand(4, 20000);
  $check = "SELECT * FROM message WHERE messageID=:messageID";
  $statement = $db->prepare($check);
  $statement->bindValue(':messageID', $msgIDRandInt);
  $statement->execute();
  $result = $statement->fetch();
  $statement->closeCursor();
  if ($result) {
    $_SESSION['takenMsgIDError'] = "Sorry there was an error on our end. Try again please.";
    header("Location: messages.php");
  }

  $query = "INSERT INTO message (userID, imageID, messageID, textContent) VALUES (:userID, :imageID, :messageID, :textContent)";
  $statement = $db->prepare($query);
  $statement->bindValue(':userID', $userID);
  $statement->bindValue(':imageID', $imageID);
  $statement->bindValue(':messageID', $msgIDRandInt);
  $statement->bindValue(':textContent', $textContent);
  $statement->execute();
  $statement->closeCursor();
  if (isset($_SESSION['msgError'])) {
    unset($_SESSION['msgError']);
  }
  if (isset($_SESSION['takenMsgIDError'])) {
    unset($_SESSION['takenMsgIDError']);
  }
}
function getNotFollowing($userID)
{

  global $db;
  $query = "SELECT u1.username, u1.userID FROM Users as u1
            LEFT JOIN following as f1
            ON u1.userID = f1.user2 AND f1.user1=:userID
            WHERE f1.user2 IS NULL AND u1.userID!=:userID";
  $statement = $db->prepare($query);
  $statement->bindValue(':userID', $userID);
  $statement->execute();
  $result = $statement->fetchAll();
  $statement->closeCursor();
  return $result;
}
function followUser($user1, $user2)
{

  global $db;
  $query = "INSERT INTO following (user1, user2) VALUES (:user1, :user2)";
  $statement = $db->prepare($query);
  $statement->bindValue(':user1', $user1);
  $statement->bindValue(':user2', $user2);
  $statement->execute();
  $statement->closeCursor();
}
?>
