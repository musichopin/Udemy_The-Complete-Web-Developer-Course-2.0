<?php
    
    $weather = "";
    $error = "";

// vers1: initial version
    if (array_key_exists('city', $_GET)) { //alt: if (isset($_GET['city']))
        
        $city = str_replace(' ', '', $_GET['city']); // 4 cities with space
        
        $file_headers = @get_headers("http://www.weather-forecast.com/locations/$city/forecasts/latest");
        
// print_r($file_headers);
        // checks if url exists
        if($file_headers[9] == "HTTP/1.1 404 Not Found" ) {
          
            $error = "That city could not be found.";

        } else {
// file_get_contents reads entire file into a string        
          $forecastPage = file_get_contents("https://www.weather-forecast.com/locations/".$city."/forecasts/latest");

// <td bgcolor="#99CC99" height="25"><span class="phrase"> string is in every page w/ city
          $pageArray = explode('<td bgcolor="#99CC99" height="25"><span class="phrase">', $forecastPage);
              
          if (sizeof($pageArray) > 1) {//4 precaution in case html of website changes
          
            $secondPageArray = explode('</span></td>', $pageArray[1]);
        
            if (sizeof($secondPageArray) > 1) { // 4 precaution (not necessary)

              $weather = $secondPageArray[0];
                
            } else {
                
              $error = "That city could not be found.";
                
            }
              
          } else {
          
            $error = "That city could not be found.";
          
          }
        
        }
        
    }

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>Weather Scraper</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css" integrity="sha384-y3tfxAZXuh4HwSYylfB+J125MxIs6mR5FOHamPBG064zB+AFeWH94NdvaCBm8qnd" crossorigin="anonymous">
      
    <style type="text/css">
    
      html {
        background: url(background.jpeg) no-repeat center center fixed; 
        background-size: cover;
      }
      
      body {
          background: none;
      }
      
      .container {
          
          text-align: center;
          margin-top: 100px;
          width: 450px;
          
      }
      
      input {
          
          margin: 20px 0;
          
      }
      
      #weather {
          
          margin-top:15px;
          
      }
       
    </style>
  </head>
  <body>
    
    <div class="container">
        
      <h1>What's The Weather?</h1>
            
      <form> <!-- default method is get and submits to same page  -->
        <fieldset class="form-group">
          <label for="city">Enter the name of a city.</label>
          <input type="text" class="form-control" name="city" id="city" placeholder="Eg. London, Tokyo" value = "<?php 
    																										   
            if (array_key_exists('city', $_GET)) { //alt:if (isset($_GET['city']))
        	   
              echo $_GET['city'];/*4 city name 2 reappaer on box after submitting*/
        	   
        	  }
      	   
          ?>">
        </fieldset>
    
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
        
      <div id="weather"><?php 
          
          if ($weather) { //alt: if (isset($_GET['city']) && $weather)
              
            echo '<div class="alert alert-success" role="alert"> 
              '.$weather.'
              </div>';
              
          } else if ($error) { //alt: if (isset($_GET['city']) && $error) {
              
            echo '<div class="alert alert-danger" role="alert">
              '.$error.'
              </div>';
              
          }
          
      ?></div>
    </div>

    <!-- jQuery first, then Bootstrap JS. -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js" integrity="sha384-vZ2WRJMwsjRMW/8U7i6PWi6AlO1L79snBrmgiDpgIWJ82z8eA5lenwvxbMV1PAh7" crossorigin="anonymous"></script>
  </body>
</html>
<!-- vers1: initial version:

  // if (array_key_exists('city', $_GET)) {

  //   $forecastPage = file_get_contents("https://www.weather-forecast.com/locations/".$_GET['city']."/forecasts/latest");

  //   $pageArray= explode('<td bgcolor="#99CC99" height="25"><span class="phrase">', $forecastPage);

  //   $secondPageArray = explode('</span></td>', $pageArray[1]);

  //   echo $secondPageArray[0];

  // } -->