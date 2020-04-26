<?php
require('phpstatements.php');
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
        <form action="searchResults.php" method="GET" style="margin-left: 30px;">
          <input id="search" type="text" placeholder="Type here" name="action">
          <input id="submit" type="submit" value="Search">
        </form>
      </ul>
    </div>
  </nav>
  <div class="container">
    <h1 style="padding-bottom: 5px">Post a meme</h1>
    <div class="memeform">
      <div class="container">
      <hr/>
      <div class="row">
      <form class="col-sm" action="" enctype="multipart/form-data" method="POST">
      <div class="uploader" onclick="$('#filePhoto').click()">
      <div class="innerUploader">
          <img class="hidden" src="" />
          <input accept="image/*" type="file" name="img"  id="filePhoto" />
      </div>
      </div>
       <h5>Select a category!</h5>
       <select name="tag">
         <option value="">Select...</option>
         <option value="funny">Funny</option>
         <option value="Sad">Sad</option>
         <option value="Happy">Happy</option>
         <option value="Sports">Sports</option>
         <option value="Random">Random</option>
       </select>
       <input type="submit" name="submit" value="Upload" />
      </form>
      <div class="col-sm">
      <?php
      if(isset($_POST['submit'])){
       $img=$_FILES['img'];
       if($img['name']==''){
        echo "<h2>Select an Image Please.</h2>";
       }
       else {
        $filename = $img['tmp_name'];
        $client_id='67fd839d20ce847';		// Replace this with your client_id, if you want images to be uploaded under your imgur account
        $handle = fopen($filename, 'r');
        $data = fread($handle, filesize($filename));
        $pvars = array('image' => base64_encode($data));
        $timeout = 30;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $client_id));
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $pvars);

        $out = curl_exec($curl);
        curl_close ($curl);
        $pms = json_decode($out,true);
        $url=$pms['data']['link'];
        if(!isset($_POST['tag']))
        {
          $_SESSION['takenNameError'] = "You must select a tag!";
          header("Location: postmeme.php");
        }
        else{
            uploadMeme($url, $_POST['tag'], $_SESSION['username']);
        }
        // if($url!=''){
        //  echo "<h4 bg-success>Uploaded Without Any Problem</h4>";
        //  echo "<input type='text' id='image-link' value='".substr($url,8)."'/><button onclick='copyToClipboard()'>Copy link</button><br/>";        }
        // else{
        //  echo "<h4 class='bg-danger'>There’s a Problem</h4>";
        //  echo "<div>".$pms['data']['error']."</div>";
        // }

       }
      }
      ?>
      </div>
      </div>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      <script>
      var imageLoader = document.getElementById('filePhoto');
      imageLoader.addEventListener('change', handleImage, false);

      function handleImage(e) {
          var reader = new FileReader();
          reader.onload = function (event) {
              $('.innerUploader img').attr('src',event.target.result).removeClass("hidden" );
          }
          reader.readAsDataURL(e.target.files[0]);
      }

      function copyToClipboard() {
        var copyText = document.getElementById("image-link");
        copyText.select();
        document.execCommand("copy");
        alert("Copied the link: " + copyText.value);
      }
      </script>
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
      document.getElementById("memeposter").style.backgroundColor = "#6BC9FF";
    }

    function out() {
      document.getElementById("memeposter").style.backgroundColor = "";
    }

  </script>

</body>
</html>
