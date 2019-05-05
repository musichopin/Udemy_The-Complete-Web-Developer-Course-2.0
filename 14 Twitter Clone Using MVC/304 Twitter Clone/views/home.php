<!-- this file displays tweets on index.php w/o query string in both logged in and logged out state -->
<div class="container mainContainer">
  <div class="row">
    <div class="col-md-8">
      
      <?php if(isset($_SESSION['id'])) welcomeUser(); ?>
          
      <h2>Recent tweets</h2> <!-- where we display tweets -->
          
      <?php displayTweets('public'); ?>
        
    </div>
    <div class="col-md-4">
        
      <?php displaySearch(); ?> <!-- search tweets -->
<!-- better choice would be to include a file IMO-->   
      <?php //include("views/displaysearch.php"); ?>
        
      <hr>
        
      <?php displayTweetBox(); ?> <!-- post a tweet -->
<!-- better choice would be to include a file IMO-->      
      <?php //include("views/displaytweetbox.php"); ?>
          
    </div>
  </div>
</div>