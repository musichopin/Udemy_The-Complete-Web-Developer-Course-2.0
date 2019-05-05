<?php if (isset($_SESSION['id'])) { ?>
	<div id="tweetSuccess" class="alert alert-success">Your tweet was posted successfully.</div>
    <div id="tweetFail" class="alert alert-danger"></div>
<!-- not a real form as we use below button for ajax req -->    
    <div class="form">
      <div class="form-group">
          <textarea class="form-control" id="tweetContent"></textarea>
      </div>
      <button id="postTweetButton" class="btn btn-primary">Post Tweet</button>
  </div>
<?php } ?>            
