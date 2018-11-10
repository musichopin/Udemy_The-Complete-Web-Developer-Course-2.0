<?php

    session_start();

    // echo $_SESSION['id'];

    if ($_SESSION['email']) {
        
        echo "You are logged in!";
        
    } else {
        
        header("Location: index.php");
        
    }

    // unset($_SESSION['email']);
    // session_destroy();
?>
