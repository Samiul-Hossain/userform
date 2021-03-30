<?php

include('config/db_connect.php');

if(isset($_POST['email']))
{
  $email = $_POST['email'];
  $sql = "SELECT * FROM users WHERE email='$email'";
  $result = mysqli_query($conn,$sql);
  if(mysqli_num_rows($result)>0)
  {
    echo 0;//'<span class="text-danger">Email exists</span>'
  }
  else{
    echo 1;
  }
}
?>
