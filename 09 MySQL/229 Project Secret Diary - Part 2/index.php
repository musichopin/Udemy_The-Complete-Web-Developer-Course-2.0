<?php

    session_start();

    $error = "";  

// to prevent directly going to loggedinpage.php when we log out
    if (array_key_exists("logout", $_GET)) { // alt: if (isset($_GET['logout']))
        
        unset($_SESSION['id']); // alt: session_unset()
        setcookie("id", "", time() - 60*60);
        $_COOKIE["id"] = "";  //needless?
        
//to block directly going 2 index.php from loggedinpage.php w/o logging out first         
    } else if (array_key_exists("id", $_SESSION) OR array_key_exists("id", $_COOKIE)) {
        
        header("Location: loggedinpage.php");
        
    }

    if (array_key_exists("submit", $_POST)) {
        
        require("connection.php");
        
        if (!$_POST['email']) {
            
            $error .= "An email address is required<br>";
            
        } 
        
        if (!$_POST['password']) {
            
            $error .= "A password is required<br>";
            
        } 
        
        if ($error != "") {
            
            $error = "<p>There were error(s) in your form:</p>".$error;
            
        } else { // no error
            
            if ($_POST['signUp'] == '1') { //signup
            
                $query = "SELECT id FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1"; /*limit is needless*/

                $result = mysqli_query($link, $query); 
/*$result is object containing info about returned table*/

                if (mysqli_num_rows($result) > 0) {

                    $error = "That email address is taken. Wanna <a class='toggleForms'>Log in</a> instead";

                } else { // unique email address

                    $query = "INSERT INTO `users` (`email`, `password`, `diary`) VALUES ('".mysqli_real_escape_string($link, $_POST['email'])."', '".mysqli_real_escape_string($link, $_POST['password'])."', '')";                    

                    if (!mysqli_query($link, $query)) {

                        $error = "<p>Could not sign you up - please try again later.</p>";

                    } else {

                        $id = mysqli_insert_id($link);//last inserted id
                        
                        $query = "UPDATE `users` SET password = '".md5(md5($id).$_POST['password'])."' WHERE id = ".$id." LIMIT 1";
                        
                        mysqli_query($link, $query);

                        $_SESSION['id'] = $id;

                        if (isset($_POST['stayLoggedIn']) && $_POST['stayLoggedIn'] == '1') {

                            setcookie("id", $id, time() + 60*60*24*365);

                        } 
                            
                        header("Location: loggedinpage.php");

                    }

                }
                
            } else { //login
                    
                $query = "SELECT * FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";
/*$result is object containing info about returned table*/            
                $result = mysqli_query($link, $query);
/*$row is array representing a row of returned table*/           
                $row = mysqli_fetch_array($result);
            
                if (isset($row)) {
                    
                    $hashedPassword = md5(md5($row['id']).$_POST['password']);
                    
                    if ($hashedPassword == $row['password']) {
                        
                        $_SESSION['id'] = $row['id'];
// if(isset($_POST['stayLoggedIn'])) is enough check
                        if (isset($_POST['stayLoggedIn']) && $_POST['stayLoggedIn'] == '1') {

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

<?php include("header.php"); ?>
      
<div class="container" id="homePageContainer">
      
    <h1>Secret Diary</h1>
          
    <p><strong>Store your thoughts permanently and securely.</strong></p>
      
    <div id="error"><?php if ($error!="") {

        echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';

    } ?></div>

    <form method="post" id = "signUpForm"> <!-- signup form -->
        
        <p>Interested? Sign up now.</p>
        
        <fieldset class="form-group">

            <input class="form-control" type="email" name="email" placeholder="Your Email">
            
        </fieldset>
        
        <fieldset class="form-group">
        
            <input class="form-control" type="password" name="password" placeholder="Password">
            
        </fieldset>
        
        <div class="checkbox">
        
            <label>
        
                <input type="checkbox" name="stayLoggedIn" value=1> Stay logged in
                
            </label>
            
        </div>
        
        <fieldset class="form-group">
        
            <input type="hidden" name="signUp" value="1">
            
            <input class="btn btn-success" type="submit" name="submit" value="Sign Up!">
            
        </fieldset>
        
        <p><a class="toggleForms">Log in</a></p>

    </form>

    <form method="post" id = "logInForm"> <!-- login form -->
        
        <p>Log in using your username and password.</p>
        
        <fieldset class="form-group">

            <input class="form-control" type="email" name="email" placeholder="Your Email">
            
        </fieldset>
        
        <fieldset class="form-group">
        
            <input class="form-control"type="password" name="password" placeholder="Password">
            
        </fieldset>
        
        <div class="checkbox">
        
            <label>
        
                <input type="checkbox" name="stayLoggedIn" value=1> Stay logged in
                
            </label>
            
        </div>
            
            <input type="hidden" name="signUp" value="0">
        
        <fieldset class="form-group">
            
            <input class="btn btn-success" type="submit" name="submit" value="Log In!">
            
        </fieldset>
        
        <p><a class="toggleForms">Sign up</a></p>

    </form>
          
</div>

<?php include("footer.php"); ?>


