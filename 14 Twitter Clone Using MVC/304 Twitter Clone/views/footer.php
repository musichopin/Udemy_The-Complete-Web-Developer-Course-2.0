<footer class="footer">
    <div class="container">
        <p>&copy; My Website 2016</p>
    </div>
</footer>

<!-- Modal (to login-signup) taken from bootstrap modal comp -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="loginModalTitle">Login</h4>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger" id="loginAlert"></div>
        <form> <!-- form part taken from bootstrap form comp (not a real form) -->
            <input type="hidden" id="loginActive" name="loginActive" value="1">
<!-- hidden input keeps track of whether we are in login mode or signup mode -->            
            <fieldset class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" placeholder="Email address">
            </fieldset>
            <fieldset class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" placeholder="Password">
            </fieldset>
        </form>
      </div>
      <div class="modal-footer">
        <a id="toggleLogin">Sign up</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="loginSignupButton" class="btn btn-primary">Login</button>
      </div>
    </div>
  </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js" integrity="sha384-vZ2WRJMwsjRMW/8U7i6PWi6AlO1L79snBrmgiDpgIWJ82z8eA5lenwvxbMV1PAh7" crossorigin="anonymous"></script>
<script>
    /*toggles login and signup menus*/
    $("#toggleLogin").click(function() {
        
        if ($("#loginActive").val() == "1") {
            
            $("#loginActive").val("0");
            $("#loginModalTitle").html("Sign Up");
            $("#loginSignupButton").html("Sign Up");
            $(this).html("Login");
            
        } else {
            
            $("#loginActive").val("1");
            $("#loginModalTitle").html("Login");
            $("#loginSignupButton").html("Login");
            $(this).html("Sign up");
            
        }
    })

// 3 events below refer to 3 ajax requests
    $("#loginSignupButton").click(function() { /*login/signup event*/
        
        $.ajax({
            method: "POST",
            url: "actions.php?action=loginSignup",
            data: "email=" + $("#email").val() + "&password=" + $("#password").val() + "&loginActive=" + $("#loginActive").val(),
            success: function(result) {
                if (result == "1") {
                    //redirects if response is positive (session is set)
                    window.location.assign("index.php");
                    
                } else {
                    
                    $("#loginAlert").html(result).show(); //chaining
                    
                }
            }
        })
    })

    $(".toggleFollow").click(function() { //follow/unfollow event
        // alert($(this).attr("data-userId")); // jquery
        // alert(this.getAttribute("data-userId")); // js
        var id = $(this).attr("data-userId");
        
        $.ajax({
            method: "POST",
            url: "actions.php?action=toggleFollow",
            data: "userId=" + id,
            success: function(result) {
                
                if (result == "1") {
// as $(this) means sth different inside ajax req, we didnt use it below 
// (but we cud have assigned $(this) to a var outside of ajax call above)
                    $("a[data-userId='" + id + "']").html("Follow");
                    
                } else if (result == "2") {
                    
                    $("a[data-userId='" + id + "']").html("Unfollow");
                    
                }
            }
            
        })
        
    })
    
    $("#postTweetButton").click(function() {
        
        $.ajax({
            method: "POST",
            url: "actions.php?action=postTweet",
            data: "tweetContent=" + $("#tweetContent").val(),
            success: function(result) {
                
                if (result == "1") {
                    
                    $("#tweetSuccess").show();
                    $("#tweetFail").hide();
                    setTimeout(function(){
                        window.location.assign("index.php")
                    }, 1000);
                    
                } else if (result != "") {
                    
                    $("#tweetFail").html(result).show();
                    $("#tweetSuccess").hide();
                    
                }
            }
            
        })
        
    })
    
</script>

</body>
</html>