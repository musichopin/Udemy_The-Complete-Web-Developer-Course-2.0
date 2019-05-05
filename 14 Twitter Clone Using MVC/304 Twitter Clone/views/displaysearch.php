<!-- *name-value pairs below redirect to index page with query string.
even though it is a real form we didnt need 2 write method="get" and action="index.php" attributes 4 form el which are executed by default. as we use get request in the form when we redirect to index.php query string changes accordingly and we may access query string parameters with vars like $_GET["page"] and $_GET["q"]* -->
<form class="form-inline">
  <div class="form-group">
    <input type="hidden" name="page" value="search">
    <input type="text" name="q" class="form-control" id="search" placeholder="Search">
  </div>
  <button type="submit" class="btn btn-primary">Search Tweets</button>
</form>