<?php

    setcookie("customerId", "1234", time() + 60 * 60 * 24);

    // updates the cookie:
    // $_COOKIE["customerId"] = "test";
    
    // deletes the cookie:
    // setcookie("customerId", "", time() - 60 * 60);

    echo $_COOKIE["customerId"];

?>