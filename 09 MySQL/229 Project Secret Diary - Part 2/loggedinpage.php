<?php

    session_start();

    if (array_key_exists("id", $_COOKIE) && $_COOKIE ['id']) { //needless?
        
        $_SESSION['id'] = $_COOKIE['id'];
        
    }

    if (array_key_exists("id", $_SESSION)) {
              
      require("connection.php");
      
      $query = "SELECT diary FROM `users` WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
      $row = mysqli_fetch_array(mysqli_query($link, $query));

      $diaryContent = $row['diary']; // needed to load diary for logged in user
      
    } else {
// to prevent directly going to loggedinpage.php when we are not logged in
      header("Location: index.php");
        
    }

  	include("header.php");

?>

    <nav class="navbar navbar-light bg-faded navbar-fixed-top">
      <a class="navbar-brand" href="#">Secret Diary</a>
      <div class="pull-xs-right">
        <a href ='index.php?logout=1'>
          <button class="btn btn-success-outline" type="submit">Logout</button>
        </a>
      </div>
    </nav>

    <!-- container-fluid class doesnt not has max-width unlike container -->
    <div class="container-fluid" id="containerLoggedInPage">
      <textarea id="diary" class="form-control"><?php echo $diaryContent; ?></textarea>
    </div>

<?php
    include("footer.php");
?>