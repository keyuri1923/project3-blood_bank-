<?php
  $conn = mysqli_connect("localhost:3307","root","","blood_bank");
  // if(isset($conn)){
  //   echo "doneeeeee????";
  // }else{
  //   echo "problem";
  // }
  if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
  }
?>