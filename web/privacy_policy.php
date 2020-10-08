<?php
$launguage = $_GET["launguage"];

?>

<!DOCTYPE html>
    <html>
    <head>
      <meta charset='utf-8'>
      <meta name='viewport' content='width=device-width'>
      <title>Privacy Policy</title>
      <style> body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; padding:1em; } </style>
    </head>
    <body>
      <?php

      if($launguage == 'ko') {
        include_once 'privacy_policy_ko.html';
      }
      else {
        include_once 'privacy_policy_en.html';
      }
      ?>
    
    </body>
    </html>
      