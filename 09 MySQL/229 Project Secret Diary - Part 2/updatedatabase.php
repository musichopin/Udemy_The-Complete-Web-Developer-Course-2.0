<?php
// this file is where ajax request goes
    session_start();

    if (array_key_exists("content", $_POST)) {
        
        require("connection.php");
        
        $query = "UPDATE `users` SET `diary` = '".mysqli_real_escape_string($link, $_POST['content'])."' WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
        
        $result = mysqli_query($link, $query);

        // if($result) echo "success";
        // 	else echo "failed";
        
    }

?>
