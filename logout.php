<?php
  if (!isset($_SESSION)) {
    session_start();
  }
  $_SESSION = array();
  session_destroy();
  header("Location: login.php");
  // authors: Andrew Neidringhaus, Steve Phan, Daniel Yenegeta & Zabih Yousuf
?>
