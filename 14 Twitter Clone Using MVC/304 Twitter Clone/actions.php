<?php
// *this file is where 3 ajax requests goes from footer.php 
// (causing 4 permanent changes in db)*
    require("functions.php");
// *functions.php'yi require etmesi hem login/signup yaparken set ettiği session-id'nin index.php tarafından ulaşılması (ve unfollow/follow/post yaparken session-id'nin actions.php tarafından ulaşılması) bakımından onemli hem de database ile igili modifikasyon yapabilmek bakımından önemli*

    if (isset($_GET['action']) && $_GET['action'] == "loginSignup") { /*login-signup request*/
        
        $error = "";
        
        if (!$_POST['email']) {
            
            $error = "An email address is required.";
            
        } else if (!$_POST['password']) {
            
            $error = "A password is required";
            
        } else if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
  
            $error = "Please enter a valid email address.";
            
        }
        
        if ($error != "") {
            
            echo $error; /*sends $error to ajax request on footer.php*/
            exit();
            
        }
        
        
        if ($_POST['loginActive'] == "0") {//signup menu active-2nd stage
            
            $query = "SELECT * FROM users WHERE email = '". mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1"; //limit is 4 extra security

            $result = mysqli_query($link, $query);

            if (mysqli_num_rows($result) > 0) {

                $error = "That email address is already taken.";
                echo $error;
                exit;

            } else {
                
                $query = "INSERT INTO users (`email`, `password`) VALUES ('". mysqli_real_escape_string($link, $_POST['email'])."', '". mysqli_real_escape_string($link, $_POST['password'])."')";
                
                if (mysqli_query($link, $query)) {
                    
                    $_SESSION['id'] = mysqli_insert_id($link);
                    
                    $query = "UPDATE users SET password = '". md5(md5($_SESSION['id']).$_POST['password']) ."' WHERE id = ".$_SESSION['id']." LIMIT 1";

                    mysqli_query($link, $query);

                    echo 1;//4 response 2 ajax req on footer.php
                    
                } else {
                    
                    $error = "Couldn't create user - please try again later";
                    echo $error;
                    exit;                    
                    
                }
                
            }
            
        } else if ($_POST['loginActive'] == "1") { /*login menu is active-1st stage (ie default)*/
            
            $query = "SELECT * FROM users WHERE email = '". mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";
/*$result is object containing info about returned table*/             
            $result = mysqli_query($link, $query);
/*$user is array representing a row of returned table*/            
            $user = mysqli_fetch_assoc($result);

                if(isset($user)) {

                    $hashedPassword = md5(md5($user['id']).$_POST['password']);

                    if ($user['password'] == $hashedPassword) {
                        
                        $_SESSION['id'] = $user['id'];

                        echo 1;//4 response 2 ajax req on footer.php
                        
                    } else { // wrong pw
                        
                        $error = "Could not find that username/password combination. Please try again.";
                        echo $error;
                        exit;                        
                        
                    }
                    
                } else { // wrong email
                    
                    $error = "That email/password combination could not be found.";
                    echo $error;
                    exit;                    
                    
                }
                
        }
        
    }


    if (isset($_GET['action']) && $_GET['action'] == 'toggleFollow') { /*follow/unfollow request*/

// $_POST['userId'] is userId of tweet on clicked follow/unfollow button
// $_SESSION['id'] is id of logged in user
// $follower: takip eden; $isFollowing: takip ettiği
        $query = "SELECT * FROM isFollowing WHERE follower = ". mysqli_real_escape_string($link, $_SESSION['id'])." AND isFollowing = ". mysqli_real_escape_string($link, $_POST['userId'])." LIMIT 1";

        $result = mysqli_query($link, $query);

        if (mysqli_num_rows($result) > 0) { /*unfollow user (2nd stage)*/
            
            $follow = mysqli_fetch_assoc($result); //row

            $unfollowQuery = "DELETE FROM isFollowing WHERE id = ". mysqli_real_escape_string($link, $follow['id'])." LIMIT 1";
            
            mysqli_query($link, $unfollowQuery);
            
            echo "1"; // indicates unfollow 4 ajax req
              
        } else { /*follow user (1st stage)*/
            
            mysqli_query($link, "INSERT INTO isFollowing (follower, isFollowing) VALUES (". mysqli_real_escape_string($link, $_SESSION['id']).", ". mysqli_real_escape_string($link, $_POST['userId']).")");
            
            echo "2"; // indiacates follow 4 ajax req
            
        }
        
    }


    if (isset($_GET['action']) && $_GET['action'] == 'postTweet') { /*posting tweet req*/
        
        if (!$_POST['tweetContent']) {
                    
            echo "Your tweet is empty!";
                    
        } else if (strlen($_POST['tweetContent']) > 140) {
            
            echo "Your tweet is too long!";
            
        } else {
            
            mysqli_query($link, "INSERT INTO tweets (`tweet`, `userId`, `datetime`) VALUES ('". mysqli_real_escape_string($link, $_POST['tweetContent'])."', ". mysqli_real_escape_string($link, $_SESSION['id']).", NOW())");
            
            echo "1";
            
        }
        
    }

?>