<div class="container mainContainer">
  <div class="row">
    <div class="col-md-8">
      
      <?php if (isset($_GET['userid']) && $_GET['userid'] != "") { ?> <!--if we click a displayed user-->
      
        <?php displayTweets($_GET['userid']); ?>
      
      <?php } else { ?> <!--when we click public profiles link on header.php-->
        
        <h2>Active Users</h2>
        
        <?php publicProfiles(); ?>
      
      <?php } ?>

    </div>
    <div class="col-md-4">

      <?php displaySearch(); ?>

      <hr>

      <?php displayTweetBox(); ?> <!-- post tweet -->
      
    </div>
  </div>
</div>