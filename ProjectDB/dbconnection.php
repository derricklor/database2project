<?php
// make a include file that contains this connection.
  $db = mysqli_connect('localhost', 'root', '', 'user');
  //check connection
  if(!$db){
   echo 'Could not connect to Database: ' . mysqli_connect_error();
  }

  ?>