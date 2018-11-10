<?php

    $link = mysqli_connect("localhost", "root", "", "cl29-secretdi");
        
    if (mysqli_connect_error()) {
        
        die ("Database Connection Error");
        
    }

?>