<div class="container mainContainer">
  <div class="row">
    <div class="col-md-8">
      
      <?php displayTweets($_GET['userId']); ?>
      
    </div>
    <div class="col-md-4">

      <?php displaySearch(); ?>

      <hr>

      <?php displayTweetBox(); ?> <!-- post tweet -->
      
    </div>
  </div>
</div>