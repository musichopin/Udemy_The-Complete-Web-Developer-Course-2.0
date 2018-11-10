<!-- signup form only worked when we had 3 columns table without diary column -->
<?php

    session_start();

    $error = "";    

// to prevent directly going to loggedinpage.php when we log out
    if(isset($_GET['logout'])) { // alt: if(isset($_GET['logout']))
    
        unset($_SESSION['id']);
        setcookie("id", "", time() - 60*60);
        $_COOKIE["id"] = "";  //needless?

//if we directly wanna go 2 index.php from loggedinpage.php w/o logging out first        
    } else if (array_key_exists("id", $_SESSION) OR array_key_exists("id", $_COOKIE)) {
        
        header("Location: loggedinpage.php");
        
    }

    if (array_key_exists("submit", $_POST)) {
        
        $link = mysqli_connect("localhost", "root", "", "cl29-secretdi");
        
        if (mysqli_connect_error()) {
            
            die ("Database Connection Error");
            
        }
        
        
        
        if (!$_POST['email']) {
            
            $error .= "An email address is required<br>";
            
        } 
        
        if (!$_POST['password']) {
            
            $error .= "A password is required<br>";
            
        } 
        
        if ($error != "") {
            
            $error = "<p>There were error(s) in your form:</p>".$error;
            
        } else {
            
            if ($_POST['signUp'] == '1') {
            
                $query = "SELECT id FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";

                $result = mysqli_query($link, $query);

                if (mysqli_num_rows($result) > 0) {

                    $error = "That email address is taken.";

                } else {

                    $query = "INSERT INTO `users` (`email`, `password`, `diary`) VALUES ('".mysqli_real_escape_string($link, $_POST['email'])."', '".mysqli_real_escape_string($link, $_POST['password'])."', '')";

                    if (!mysqli_query($link, $query)) {

                        $error = "<p>Could not sign you up - please try again later.</p>";

                    } else {

                        $_SESSION['id'] = mysqli_insert_id($link);

                        if (isset($_POST['stayLoggedIn'])) {

                            setcookie("id", mysqli_insert_id($link), time() + 60*60*24*365);

                        } 

                        $query = "UPDATE `users` SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."' WHERE id = ".mysqli_insert_id($link)." LIMIT 1";

                        mysqli_query($link, $query);

                        header("Location: loggedinpage.php");

                    }

                } 
                
            } else { //login
                    
                    $query = "SELECT * FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";
                
                    $result = mysqli_query($link, $query); // table
                
                    $row = mysqli_fetch_array($result); // only row from table
                
                    if (isset($row)) {
                        
                        $hashedPassword = md5(md5($row['id']).$_POST['password']);
                        
                        if ($hashedPassword == $row['password']) {
                            
                            $_SESSION['id'] = $row['id'];
                            
                            if ($_POST['stayLoggedIn'] == '1') {

                                setcookie("id", $row['id'], time() + 60*60*24*365);

                            } 

                            header("Location: loggedinpage.php");
                                
                        } else { // wrong pw
                            
                            $error = "That email/password combination could not be found.";
                            
                        }
                        
                    } else { // wrong email
                        
                        $error = "That email/password combination could not be found.";
                        
                    }
                    
                }
            
        }
        
        
    }


?>

<div id="error"><?php echo $error; ?></div>

<form method="post">

    <input type="email" name="email" placeholder="Your Email">
    
    <input type="password" name="password" placeholder="Password">
    
    <input type="checkbox" name="stayLoggedIn" value=1>
    
    <input type="hidden" name="signUp" value="1">
        
    <input type="submit" name="submit" value="Sign Up!">

</form>

<form method="post">

    <input type="email" name="email" placeholder="Your Email">
    
    <input type="password" name="password" placeholder="Password">
    
    <input type="checkbox" name="stayLoggedIn" value=1>
    
    <input type="hidden" name="signUp" value="0">
        
    <input type="submit" name="submit" value="Log In!">

</form>