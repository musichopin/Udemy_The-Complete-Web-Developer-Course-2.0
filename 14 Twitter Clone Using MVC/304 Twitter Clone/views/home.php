<div class="container mainContainer">
  <div class="row">
    <div class="col-md-8">
          
      <h2>Recent tweets</h2> <!-- where we display tweets -->
          
      <?php displayTweets('public'); ?>
        
    </div>
    <div class="col-md-4"> <!-- where we search and post tweets -->
          
      <?php displaySearch(); ?>
        
      <hr>
        
      <?php displayTweetBox(); ?> <!-- post tweet -->
          
    </div>
  </div>
</div>