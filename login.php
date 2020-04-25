<?php
if (!isset($_SESSION)) {
  session_start();
}
if (isset($_SESSION['username'])) {
  header("Location: home.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta author="Andrew Neidringhaus, Steve Phan, Daniel Yenegeta & Zabih Yousuf">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Log in here</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
  integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" />
  <link rel="stylesheet" href="login.css" />
</head>
<body style="background-color:#CFE7FF" id="contained">
  <div class="container">
    <h1 style="padding-bottom: 10px">Log in</h1>
    <div class="login">
      <form method="POST" action="home.php" name="loginform">
        <div class="form-group">
          <div class="inputbox">
            <input name="username" type="text" id="username" placeholder="Enter username" autofocus>
            <span class="error_message" id="username_msg"></span>
          </div>
        </div>
        <div class="form-group">
          <div class="inputbox">
            <input name="password" type="password" id="password" placeholder="Enter password">
            <span class="error_message" id="pwd_msg"></span>
          </div>
        </div>
        <div class="form-group">
          <div class="inputbox">
            <input type="submit" name="action" value="LogIn" id="submitbutton" onmouseover="hover()" onmouseout="out()">
          </div>
        </div>
      </form>
      <?php
        if (isset($_SESSION['badNameError'])) {
          echo ("<p>" . $_SESSION['badNameError'] . "</p>");
        }
        if (isset($_SESSION['badPassError'])) {
          echo ("<p>" . $_SESSION['badPassError'] . "</p>");
        }
        if (isset($_SESSION['notLoggedInError'])) {
          echo ("<p>" . $_SESSION['notLoggedInError'] . "</p>");
        }
      ?>
      <a href="signup.php">Sign up here!</a>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
  integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

  <script>

    function hover() {
      document.getElementById("submitbutton").style.backgroundColor = "#6BC9FF";
    }

    function out() {
      document.getElementById("submitbutton").style.backgroundColor = "";
    }

  </script>

</body>
</html>
