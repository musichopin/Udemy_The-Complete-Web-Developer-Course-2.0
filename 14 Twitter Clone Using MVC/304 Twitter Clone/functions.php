<?php

    session_start();

    $link = mysqli_connect("localhost", "root", "", "cl29-twitter");

    if (mysqli_connect_errno()) { // alt: mysqli_connect_error()
        
        print_r(mysqli_connect_error());
        exit();
        
    }

// when we click logout button we destroy session
// *even though we delete the session[id] we get the same id when log in thanks to using the same id as primary key in users table. we need session[id] to be unique as we can then us it in where clause and we need session[id] to understand if a user is logged in*
    if (isset($_GET['function']) && $_GET['function'] == "logout") {

        session_unset();
        // alt: unset($_SESSION['id']);
        // alt2: session_destroy();

    }

    function welcomeUser() { //called by home.php
        global $link;

        $query = "SELECT * FROM users WHERE id = " . $_SESSION['id'] ." LIMIT 1";
        $result = mysqli_query($link, $query);
        $user = mysqli_fetch_assoc($result);

        echo "<h5>Welcome " . $user['email'] ."</h5>";
    }

    function time_since($since) {
// prints tweet times as 5 secs, 1 min, 4 days etc
        $chunks = array(
            array(60 * 60 * 24 * 365 , 'year'),
            array(60 * 60 * 24 * 30 , 'month'),
            array(60 * 60 * 24 * 7, 'week'),
            array(60 * 60 * 24 , 'day'),
            array(60 * 60 , 'hour'),
            array(60 , 'min'),
            array(1 , 'sec')
        );

        for ($i = 0, $j = count($chunks); $i < $j; $i++) {
            $seconds = $chunks[$i][0];
            $name = $chunks[$i][1];
            if (($count = floor($since / $seconds)) != 0) {
                break;
            }
        }

        $print = ($count == 1) ? '1 '.$name : "$count {$name}s";
        return $print;
    }

    function displayTweets($type) {
        
        global $link; //to access global $link var from within function

        if ($type == 'public') { // 4 all tweets (called in both logged in and logged out state)
// $whereClause for tweets table            
            $whereClause = "";
                
        } else if ($type == 'timeline') { // 4 followed tweets
// selects every field for users the logged in user is following
            $query = "SELECT * FROM isFollowing WHERE follower = ". mysqli_real_escape_string($link, $_SESSION['id']);
/*$result is object containing info about returned table*/
            $result = mysqli_query($link, $query);
            
            $whereClause = "";
// if logged user is following someone            
            while ($follow = mysqli_fetch_assoc($result)) { //looping rows
/*$follow is array representing each row of returned table*/
                if ($whereClause == "") $whereClause = "WHERE";
                else $whereClause.= " OR";

                $whereClause.= " userId = ".$follow['isFollowing'];
               
            }
// if loggedin user is not following anyone dont show anyone
            if (!$whereClause) $whereClause = "WHERE userId = -1";
            
        } else if ($type == 'yourtweets') {

            $whereClause = "WHERE userId = ". mysqli_real_escape_string($link, $_SESSION['id']);
            
        } else if ($type == 'search') {//search box(form w/ get req)
// $_GET['q'] comes from text entered into search box            
            echo "<p>Showing search results for '".mysqli_real_escape_string($link, $_GET['q'])."':</p>";
            
            $whereClause = "WHERE tweet LIKE '%". mysqli_real_escape_string($link, $_GET['q'])."%'";
            
        } else if (is_numeric($type)) {//when we click on a user link
            
            $query = "SELECT * FROM users WHERE id = ".mysqli_real_escape_string($link, $type)." LIMIT 1";
            $result = mysqli_query($link, $query);
            $user = mysqli_fetch_assoc($result);
            
            if (isset($user)) { /*in case user enters url an invalid no*/
                echo "<h2>".mysqli_real_escape_string($link, $user['email'])."'s Tweets</h2>";
            }
            
            $whereClause = "WHERE userId = ". mysqli_real_escape_string($link, $type);
            
        }
        
        
        $query = "SELECT * FROM tweets ".$whereClause." ORDER BY `datetime` DESC LIMIT 10";
        
        $result = mysqli_query($link, $query);
        
        if (mysqli_num_rows($result) == 0) {
            
            echo "There are no tweets to display.";
            
        } else {
            
            while ($tweet = mysqli_fetch_assoc($result)) {
// $tweet['userId'] is foreign key                
                $userQuery = "SELECT * FROM users WHERE id = ".mysqli_real_escape_string($link, $tweet['userId'])." LIMIT 1";

                $userQueryResult = mysqli_query($link, $userQuery);

                $user = mysqli_fetch_assoc($userQueryResult);
// 1 row oldugundan while loop kullanılmaz                

// date practise:
                // date_default_timezone_set("UTC"); 
                // echo time();
                // echo "<br>";
                // echo date("Y/m/d h:m:s");
                // echo "<br>";
                // echo $tweet['datetime'];
                // echo "<br>";
                // echo strtotime($tweet['datetime']); exit;
                // echo "<br>";

                echo "<div class='tweet'>
                        <p><a href='?page=publicprofiles&userId=".$user['id']."'>".$user['email']."</a> 
                        <span class='time'>".time_since((time()+60*60*3)  - strtotime($tweet['datetime']))." ago</span></p>";
                
                echo "<p>".$tweet['tweet']."</p>";

//if user is logged in and if tweets are not users' own tweets, show follow/unfollow links
                if(isset($_SESSION['id']) && ($_SESSION['id'] != $tweet['userId'] )) {
//alt: if(isset($_SESSION['id']) && ($_SESSION['id'] != $user['id'])
//data- attribute is part of js
//*below a tag isnt real as it lacks href attr & we use it 4 ajax req*
                    echo "<p><a class='toggleFollow' data-userId='".$tweet['userId']."'>";
// $tweet['userId'] is userId of tweet on follow/unfollow button clicked
// $_SESSION['id'] is id of logged in user
// $follower: takip eden; $isFollowing: takip ettiği                 
                    $isFollowingQuery = "SELECT * FROM isFollowing WHERE follower = ". mysqli_real_escape_string($link, $_SESSION['id'])." AND isFollowing = ". mysqli_real_escape_string($link, $tweet['userId'])." LIMIT 1";

                    $isFollowingQueryResult = mysqli_query($link, $isFollowingQuery);
//we are doing this query sta 2 write follow or unfollow links when we load the page. on footer.php we do a similar if sta in case follow/unfollow button is clicked                  
                    if (mysqli_num_rows($isFollowingQueryResult) > 0) {
                        
                        echo "Unfollow";
                        
                    } else { // 1st stage
                        
                        echo "Follow";
                        
                    }
                        
                    echo "</a></p>";
                    
                }

                echo "</div>";
                
            }
            
        }
        
    }

//displays public profiles 4 the "Public Profiles" link in header
    function publicProfiles() {
        
        global $link;
        
        $query = "SELECT * FROM users LIMIT 10";
        
        $result = mysqli_query($link, $query);

        if (mysqli_num_rows($result) == 0) {
            
            echo "There are no users to display.";
            
        } else {

            while ($user = mysqli_fetch_assoc($result)) {
                
                echo "<p><a href='index.php?page=publicprofiles&userId=".$user['id']."'>".$user['email']."</a></p>";
                
            }
            
        }      
        
    }

    function displaySearch() { //display search box (get req)
// *name-value pairs below redirect to index page with query string.
// even though it is a real form we didnt need 2 write method="get" and action="index.php" attributes 4 form el which are executed by default. as we use get request in the form when we redirect to index.php query string changes accordingly and we may access query string parameters with vars like $_GET["page"] and $_GET["q"]*
        echo '<form class="form-inline">
          <div class="form-group">
            <input type="hidden" name="page" value="search">
            <input type="text" name="q" class="form-control" id="search" placeholder="Search">
          </div>
          <button type="submit" class="btn btn-primary">Search Tweets</button>
        </form>';
        
    }

    function displayTweetBox() { //display tweet box (ajax req)
        
        if (isset($_SESSION['id'])) { /*hide for logged out users*/
//*not a real form as we use below button for ajax req and there is no form tag below*
            echo '<div id="tweetSuccess" class="alert alert-success">Your tweet was posted successfully.</div>
                <div id="tweetFail" class="alert alert-danger"></div>
                <div class="form">
                    <div class="form-group">
                        <textarea class="form-control" id="tweetContent"></textarea>
                    </div>
                    <button id="postTweetButton" class="btn btn-primary">Post Tweet</button>
                </div>';
            
        }
        
    }

?>