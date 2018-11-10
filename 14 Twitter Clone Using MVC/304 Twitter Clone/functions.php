<?php

    session_start();

    $link = mysqli_connect("localhost", "root", "", "cl29-twitter");

    if (mysqli_connect_errno()) {
        
        print_r(mysqli_connect_error());
        exit();
        
    }

// when we click logout button we destroy session
    if (isset($_GET['function']) && $_GET['function'] == "logout") {

        session_unset();

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

        if ($type == 'public') {
            
            $whereClause = "";
                
        } else if ($type == 'timeline') {
            
            $query = "SELECT * FROM isFollowing WHERE follower = ". mysqli_real_escape_string($link, $_SESSION['id']);

            $result = mysqli_query($link, $query);
            
            $whereClause = "";
// if logged in user is following someone            
            while ($follow = mysqli_fetch_assoc($result)) { //looping rows
                
                if ($whereClause == "") $whereClause = "WHERE";
                else $whereClause.= " OR";

                $whereClause.= " userid = ".$follow['isFollowing'];
                
            }
// if loggedin user is not following anyone dont show anyone
            if (!$whereClause) $whereClause = "WHERE userid = -1";
            
        } else if ($type == 'yourtweets') {

            $whereClause = "WHERE userid = ". mysqli_real_escape_string($link, $_SESSION['id']);
            
        } else if ($type == 'search') {
// $_GET['q'] comes from text entered into search box            
            echo "<p>Showing search results for '".mysqli_real_escape_string($link, $_GET['q'])."':</p>";
            
            $whereClause = "WHERE tweet LIKE '%". mysqli_real_escape_string($link, $_GET['q'])."%'";
            
        } else if (is_numeric($type)) { /*called by publicprofiles.php*/
            
            $userQuery = "SELECT * FROM users WHERE id = ".mysqli_real_escape_string($link, $type)." LIMIT 1";
            $userQueryResult = mysqli_query($link, $userQuery);
            $user = mysqli_fetch_assoc($userQueryResult);
            
            if (isset($user)) { /*in case user enters url an invalid no*/
                echo "<h2>".mysqli_real_escape_string($link, $user['email'])."'s Tweets</h2>";
            }
            
            $whereClause = "WHERE userid = ". mysqli_real_escape_string($link, $type);
            
        }
        
        
        $query = "SELECT * FROM tweets ".$whereClause." ORDER BY `datetime` DESC LIMIT 10";
        
        $result = mysqli_query($link, $query);
        
        if (mysqli_num_rows($result) == 0) {
            
            echo "There are no tweets to display.";
            
        } else {
            
            while ($tweet = mysqli_fetch_assoc($result)) {
// $tweet['userid'] is foreign key                
                $userQuery = "SELECT * FROM users WHERE id = ".mysqli_real_escape_string($link, $tweet['userid'])." LIMIT 1";

                $userQueryResult = mysqli_query($link, $userQuery);

                $user = mysqli_fetch_assoc($userQueryResult);

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
                        <p><a href='?page=publicprofiles&userid=".$user['id']."'>".$user['email']."</a> 
                        <span class='time'>".time_since((time()+60*60*3)  - strtotime($tweet['datetime']))." ago</span>:</p>";
                
                echo "<p>".$tweet['tweet']."</p>";

//if user is logged in or if tweets are not users' own tweets, show follow button
                if(isset($_SESSION['id']) && ($_SESSION['id'] != $tweet['userid'])) {
// data- attribute is part of js
                    echo "<p><a class='toggleFollow' data-userId='".$tweet['userid']."'>";
// $tweet['userid'] is userid of tweet on follow/unfollow button clicked
// $_SESSION['id'] is id of logged in user
// $follower: takip eden; $isFollowing: takip ettiği                    
                    $isFollowingQuery = "SELECT * FROM isFollowing WHERE follower = ". mysqli_real_escape_string($link, $_SESSION['id'])." AND isFollowing = ". mysqli_real_escape_string($link, $tweet['userid'])." LIMIT 1";
                    $isFollowingQueryResult = mysqli_query($link, $isFollowingQuery);
//we are doing this query sta 2 write follow or unfollow right when we load the page. on footer.php we do a similar if sta in case follow/unfollow button is clicked                  
                    if (mysqli_num_rows($isFollowingQueryResult) > 0) {
                        
                        echo "Unfollow";
                        
                    } else {
                        
                        echo "Follow";
                        
                    }
                        
                    echo "</a></p>";
                    
                }

                echo "</div>";
                
            }
            
        }
        
    }

    function publicProfiles() {
        
        global $link;
        
        $query = "SELECT * FROM users LIMIT 10";
        
        $result = mysqli_query($link, $query);

        if (mysqli_num_rows($result) == 0) {
            
            echo "There are no users to display.";
            
        } else {

            while ($user = mysqli_fetch_assoc($result)) {
                
                echo "<p><a href='index.php?page=publicprofiles&userid=".$user['id']."'>".$user['email']."</a></p>";
                
            }
            
        }        
        
    }

    function displaySearch() {
// name-value pairs below redirect to search page with query string
// we didnt need 2 write method="get" and action="index.php" attributes 4 form el
        echo '<form class="form-inline">
          <div class="form-group">
            <input type="hidden" name="page" value="search">
            <input type="text" name="q" class="form-control" id="search" placeholder="Search">
          </div>
          <button type="submit" class="btn btn-primary">Search Tweets</button>
        </form>';
        
    }

    function displayTweetBox() {  /*post tweet*/
        
        if (isset($_SESSION['id'])) { /*hide for logged out users*/
            
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