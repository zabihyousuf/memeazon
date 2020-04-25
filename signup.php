<?php
if (!isset($_SESSION)) {
  session_start();
}
include_once('connection.php');
require('phpstatements.php');
if (!empty($_POST['action'])) {
  if ($_POST['action'] == "SignUp") {
    $err = 0;
    if (empty($_POST['firstname'])) {
      $noFirstNameError = "Please enter your first name";
      $err = 1;
    }
    if (empty($_POST['lastname'])) {
      $noLastNameError = "Please enter your last name";
      $err = 1;
    }
    if (empty($_POST['username'])) {
      $noUserNameError = "Please enter a username";
      $err = 1;
    }
    if (empty($_POST['password'])) {
      $noPasswordError = "Please enter your password";
      $err = 1;
    }
    if (usernameTaken($_POST['username'])) {
      $_SESSION['takenNameError'] = "There is already a user with that name";
      $err = 1;
    } else {
      if (isset($_SESSION['takenNameError'])) {
        unset($_SESSION['takenNameError']);
      }
    }
    if (!$err) {
      addUser($_POST['firstname'], $_POST['lastname'], $_POST['username'], $_POST['password']);
    }
  }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta author="Andrew Neidringhaus, Steve Phan, Daniel Yenegeta & Zabih Yousuf">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sign up!</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
  integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" />
  <link rel="stylesheet" href="signup.css" />
</head>
<body style="background-color:#CFE7FF" id="contained">
  <div class="container">
    <h1 style="padding-bottom: 10px">Sign up</h1>
    <div class="login">
      <form method="POST" action="" name="loginform" onsubmit="return checkSignup()">
        <div class="form-group">
          <div class="inputbox">
            <label for="firstname">Enter your first name:</label>
            <input name="firstname" type="text" id="firstname" autofocus>
            <span class="error_message" id="fname_msg">
              <?php if (isset($noFirstNameError)) echo $noFirstNameError ?>
            </span>
          </div>
        </div>
        <div class="form-group">
          <div class="inputbox">
            <label for="lastname">Enter your last name:</label>
            <input name="lastname" type="text" id="lastname" autofocus>
            <span class="error_message" id="fname_msg">
              <?php if (isset($noLastNameError)) echo $noLastNameError ?>
            </span>
          </div>
        </div>
        <div class="form-group">
          <div class="inputbox">
            <label for="username">Enter a username:</label>
            <input name="username" type="text" id="username" autofocus>
            <span class="error_message" id="fname_msg">
              <?php if (isset($noUserNameError)) echo $noUserNameError ?>
            </span>
          </div>
        </div>
        <div class="form-group">
          <div class="inputbox">
            <label for="password">Enter a password:</label>
            <input name="password" type="password" id="password">
            <span class="error_message" id="fname_msg">
              <?php if (isset($noPasswordError)) echo $noPasswordError ?>
            </span>
          </div>
        </div>
        <div class="form-group">
          <div class="inputbox">
            <input type="submit" name="action" value="SignUp" id="submitbutton" onmouseover="hover()" onmouseout="out()">
          </div>
        </div>
      </form>
      <?php
        if (isset($_SESSION['takenNameError'])) {
          echo ("<p>" . $_SESSION['takenNameError'] . "</p>");
        }
      ?>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
  integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

  <script>

    // document.getElementById("submitbutton").addEventListener("click", function() {
    //   var username = document.getElementById("username").value;
    //   var password = document.getElementById("password").value;
    //   var email = document.getElementById("email").value;
    //   if (username === "") {
    //     alert("Please enter a username");
    //   } else if (password === "") {
    //     alert("Please enter a password")
    //   } else {
    //     alert("logged in as " + username + "!");
    //   }
    // })

    var anon = function(x) {
      document.getElementById(x).style.backgroundColor = "red";
    }

    function checkSignup() {
      var error = false;
      var firstname = document.getElementById("firstname").value;
      var lastname = document.getElementById("lastname").value;
      var username = document.getElementById("username").value;
      var password = document.getElementById("password").value;
      var email = document.getElementById("email").value;

      if (firstname === "") {
        error = true;
        document.getElementById("fname_msg").innerHTML = "Please enter your first name";
        anon("firstname");
      }
      if (lastname === "") {
        error = true;
        document.getElementById("lname_msg").innerHTML = "Please enter your last name";
        anon("lastname");
      }
      if (username === "") {
        error = true;
        document.getElementById("usr_msg").innerHTML = "Please enter a username";
        anon("username");
      }
      if (password === "") {
        error = true;
        document.getElementById("pass_msg").innerHTML = "Please enter a password";
        anon("password");
      }
      if (email === "") {
        error = true;
        document.getElementById("email_msg").innerHTML = "Please enter a computing id";
        anon("email");
      }

      var pattern = new RegExp("[a-z]{2,3}[1-9][a-z]{1,3}");
      var match_test = pattern.test(email);
      if (!match_test) {
        error = true;
        document.getElementById("email_msg").innerHTML = "Computing id must be between 4 and 7 characters and must follow standard UVA computing id format."
        anon("email");
      }

    }

    function hover() {
      document.getElementById("submitbutton").style.backgroundColor = "#6BC9FF";
    }

    function out() {
      document.getElementById("submitbutton").style.backgroundColor = "";
    }

  </script>

</body>
</html>
