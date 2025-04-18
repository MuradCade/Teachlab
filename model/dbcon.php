<?php
   include_once('env.php');
      $servername = HOSTNAME;
      $username = USER_NAME;
      $pwd = PASSWORD;
      $dbname = DATABASE_NAME;
     $connection = new mysqli($servername,$username,$pwd,$dbname);

     if(!$connection){
        echo 'connection failed';
     }


  


