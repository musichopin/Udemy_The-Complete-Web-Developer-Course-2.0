<!-- index.php is where all files of mvc come together 

*query stringe gore diger sayfaları burada include etmek yerine her bir query string icin index.php ye ugramadan (linkler ve searchbox icin) yeni sayfalar yaratabilirdik*

*projenin yapilis sırası (fikrimce):
users table yaratıldı ve rowları manuel olarak olusturuldu ve functions.php icinde session-id manuel olarak user-id olarak atandı (o id'li user olarak login oldugunu belirtmek icin)
publicProfiles() fonksiyonu ile bu fonksiyonu cagiran publicprofiles.php (tamamı degil) ve headerdaki public profiles linki olusturuldu ve userlar display edilebilir duruma gelindi
site ustunden login/signup icin header.php ve footer.php ustunde modal olusturuldu. footer.php ile actions.php arasında ajax request yaratıldı ve interface ustundan signup/login yapılabilir duruma gelindi.

logout icin header.php'ye anchor eklendi ve functions.php'ye sessionı unset eden if sta eklendi

tweets table yaratıldı ve rowları manuel olarak olusturuldu
displayTweets() fonksiyonu ile bu fonksiyonu cagıran home.php, yourtimeline.php, yourtweets.php, userlinks.php ve search.php sayfaları (tamamı degil) olusturuldu. bu sayfalara ulaşımı sağlayan headerdaki twitter, your timeline, your tweets linkleri ile main layouttaki user linkleri yaratıldı. search tweets butonunun oldugu form ise displaySearch() fonksiyonu ile olusturuldu ve bu fonksiyon home.php, yourtimeline.php, yourtweets.php, userlinks.php ve publicprofiles.php ustunden cagırıldı.
site ustunden tweet post etmek icin displayTweetBox() fonksiyonu olusturuldu ve bu fonksiyon home.php, yourtimeline.php, yourtweets.php, userlinks.php ve publicprofiles.php tarafından cagırıldı. footer.php ile actions.php arasında ajax request yaratıldı ve interface ustunden tweetin post edilmesi saglandı.

isfollowing table yaratıldı ve rowları manuel olarak olusturuldu
displayTweets() fonksiyonu icinde loggedin userın diger userları takip edip etmemesine gore follow/unfollow textleri olusturuldu (anchor olmadan)
site ustunden follow/unfollow etmek icin anchor tagleri follow/unfollow textlerini wrapledi ve a taglere uygun attribute degerleri verildi. footer.php ile actions.php arasında ajax request yaratıldı ve interface ustundan follow/unfollow yapılabilir duruma gelindi.*
-->
<?php

    require("functions.php");

    include("views/header.php");

    if (isset($_GET['page']) && $_GET['page'] == 'timeline') {//your timeline link in header
        
        include("views/timeline.php");
        
    } else if (isset($_GET['page']) && $_GET['page'] == 'yourtweets') {//your tweets link in header
        
        include("views/yourtweets.php");
        
    } else if (isset($_GET['page']) && $_GET['page'] == 'search') {//form with search tweets button on right hand side
     
        include("views/search.php");
        
    } else if (isset($_GET['page']) && isset($_GET['userId']) && $_GET['page'] == 'publicprofiles') {//user links w/ mail addresses on main screen
        
        include("views/userlinks.php");
// alt1: yukarı ve aşağı else if yerine        
    } else if (isset($_GET['page']) && $_GET['page'] == 'publicprofiles') {//public profiles link in header
        
        include("views/publicprofiles.php");
        
    } else {//w/o query string on index.php (other if stas are on index 2)
// when we log out, login/signup, click twitter link on top left corner of the page, click follow/unfollow links we happen 2 use else block
        include("views/home.php");
        
    }
        
    include("views/footer.php");

?>



<?php
/* alt1: we cud use publicprofilesanduserlinks file

    require("functions.php");

    include("views/header.php");

    if (isset($_GET['page']) && $_GET['page'] == 'timeline') {//link
        
        include("views/timeline.php");
        
    } else if (isset($_GET['page']) && $_GET['page'] == 'yourtweets') {//link
        
        include("views/yourtweets.php");
        
    } else if (isset($_GET['page']) && $_GET['page'] == 'search') {//form
     
        include("views/search.php");
        
    } else if (isset($_GET['page']) && $_GET['page'] == 'publicprofiles') { //link
        
        include("views/publicprofilesanduserlinks.php");
        
    } else {//on index.php w/o query string

        include("views/home.php");
        
    }
        
    include("views/footer.php");
*/
?>
