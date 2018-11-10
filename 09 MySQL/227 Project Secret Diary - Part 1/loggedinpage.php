<?php

    session_start();

    if (array_key_exists("id", $_COOKIE)) { // needless?
        
        $_SESSION['id'] = $_COOKIE['id'];
        
    }

    if (array_key_exists("id", $_SESSION)) {
        
        echo "<p>Logged In! <a href='index.php?logout=1'>Log out</a></p>";
        
    } else {
// to prevent directly going to loggedinpage.php when we are not logged in
        header("Location: index.php");
        
    }


?>