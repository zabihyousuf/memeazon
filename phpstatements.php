<?php
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
?>
