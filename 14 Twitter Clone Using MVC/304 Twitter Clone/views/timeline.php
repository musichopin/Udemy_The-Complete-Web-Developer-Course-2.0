<div class="container mainContainer">
  <div class="row">
    <div class="col-md-8">
      <h2>Tweets For You</h2>
<!-- view tweets of people that logged in user is following -->
      <?php displayTweets('timeline'); ?>

    </div>
    <div class="col-md-4">

      <?php displaySearch(); ?>

      <hr>

      <?php displayTweetBox(); ?> <!-- post tweet -->

    </div>
  </div>
</div>