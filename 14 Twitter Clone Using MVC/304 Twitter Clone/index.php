<!-- index.php is where all files of mvc come together -->
<?php

    require("functions.php");

    include("views/header.php");

    if (isset($_GET['page']) && $_GET['page'] == 'timeline') {//link
        
        include("views/timeline.php");
        
    } else if (isset($_GET['page']) && $_GET['page'] == 'yourtweets') {//link
        
        include("views/yourtweets.php");
        
    } else if (isset($_GET['page']) && $_GET['page'] == 'search') {//form
     
        include("views/search.php");
        
    } else if (isset($_GET['page']) && $_GET['page'] == 'publicprofiles') {//link
        
        include("views/publicprofiles.php");
        
    } else {//default

        include("views/home.php");
        
    }
        
    include("views/footer.php");

?>