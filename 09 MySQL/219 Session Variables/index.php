<?php

    session_start();

    if (array_key_exists('email', $_POST) OR array_key_exists('password', $_POST)) {

        $error = "";

        $link = mysqli_connect("localhost", "root", "", "cl29-secretdi");

            if (mysqli_connect_error()) {
        
                die ("There was an error connecting to the database");
        
            } 
        
        if ($_POST['email'] == '') {
            
            $error .= "<p>Email address is required.</p>";
            
        } 

        if ($_POST['password'] == '') {
            
            $error .= "<p>Password is required.</p>";            
                    
        } 

        if ($error=="") {

            $query = "SELECT `id` FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";
            
            $result = mysqli_query($link, $query);
            
            if (mysqli_num_rows($result) > 0) {

                // $row = mysqli_fetch_array($result);
                // echo $row['id'] . "<br/>";
                // print_r($row);
                
                echo "<p>That email address has already been taken.</p>";
                
            } else {
                
                $query = "INSERT INTO `users` (`email`, `password`) VALUES ('".mysqli_real_escape_string($link, $_POST['email'])."', '".mysqli_real_escape_string($link, $_POST['password'])."')";
                
                if (mysqli_query($link, $query)) {
                    //$_SESSION['id'] = mysqli_insert_id($link);
                    $_SESSION['email'] = $_POST['email'];
                    
                    header("Location: session.php");
                    
                } else {
                    
                    echo "<p>There was a problem signing you up - please try again later.</p>";
                    
                }
                
            }

        } else {

            echo $error;

        }

    }

?>

<form method = "post">

    <input name="email" type="text" placeholder="Email address">
    
    <input name="password" type="password" placeholder="Password">
    
    <input type="submit" value = "Sign up">

</form>