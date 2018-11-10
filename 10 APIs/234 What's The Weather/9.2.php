<?php
    
  $weather = "";
  $error = "";
    
  if (isset($_GET['city'])) {
//below url firstly taken from an ex of "current weather data api" (@ openweathermap.org)
//later we put the api key (which we created ourselves) to the end of the url and put the 
//city user typed as well
    $link = "http://api.openweathermap.org/data/2.5/weather?q=".urlencode($_GET['city'])."&appid=c67b3e9fd1d8898146ab568d3a67aecd";
// urlencode() converts special chars like spaces into char codes
    
    $file_headers = get_headers($link);
    
// print_r($file_headers); exit;
    // checks if url exists
    if($file_headers[0] == "HTTP/1.1 404 Not Found" ) {
      
        $error = "That city could not be found.";

    } else {
// file_get_contents reads file into a string (json string here)
      $urlContents = file_get_contents($link);
// json_decode converts json string into an assoc array using true param      
      $weatherArray = json_decode($urlContents, true);

      // echo $urlContents . "<br><br>";
      // print_r($weatherArray) . "<br><br>";
      // var_dump($weatherArray);
      // die;
      
// we used api doc and page source of php file (to see assoc array in better format when we typed print_r($weatherArray) to look for params and type them down
      if ($weatherArray['cod'] == 200) {
      
          $weather = "The weather in ".$_GET['city']." is currently '".$weatherArray['weather'][0]['description']."'. ";

          $tempInCelcius = intval($weatherArray['main']['temp'] - 273);

          $weather .= " The temperature is ".$tempInCelcius."&deg;C and the wind speed is ".$weatherArray['wind']['speed']." m/s.";
          
      } else {
          
          $error = "Could not find city - please try again.";
          
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
          -webkit-background-size: cover;
          -moz-background-size: cover;
          -o-background-size: cover;
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
          
          
          
          <form>
  <fieldset class="form-group">
    <label for="city">Enter the name of a city.</label>
    <input type="text" class="form-control" name="city" id="city" placeholder="Eg. London, Tokyo" value = "<?php echo $_GET['city']; ?>">
  </fieldset>
  
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
      
          <div id="weather"><?php 
              
              if (isset($_GET['city']) && $weather) {
                  
                  echo '<div class="alert alert-success" role="alert">'
                      .$weather.
                    '</div>';

              } else if (isset($_GET['city']) && $error) {
                  
                  echo '<div class="alert alert-danger" role="alert">'
                      .$error.
                    '</div>';
                  
              }
              
              ?></div>

    <!-- jQuery first, then Bootstrap JS. -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js" integrity="sha384-vZ2WRJMwsjRMW/8U7i6PWi6AlO1L79snBrmgiDpgIWJ82z8eA5lenwvxbMV1PAh7" crossorigin="anonymous"></script>
  </body>
</html>