<?php
include('config/db_connect.php');

if(isset($_GET['id'])){

  $id = mysqli_real_escape_string($conn,$_GET['id']);

  $sql = "SELECT * FROM users WHERE id=$id";

  $result = mysqli_query($conn,$sql);

  $user = mysqli_fetch_assoc($result);

  mysqli_free_result($result);
  mysqli_close($conn);

}

 ?>

 <!DOCTYPE html>
 <html>
   <?php include ('templates/header.php'); ?>
   <div>
     <?php if($user): ?>

       <h4><?php echo htmlspecialchars($user['name']); ?></h4>
       <p>Created by: <?php echo htmlspecialchars($user['email']); ?></p>
       <p><?php echo htmlspecialchars($user['gender']); ?></p>
       <p><?php echo date($user['created_at']); ?></p>
       <h5>Address:</h5>
       <p><?php echo htmlspecialchars($user['address']); ?></p>

     <?php else: ?>

       <h5>No such user exists</h5>

     <?php endif; ?>
   </div>
   <?php include ('templates/footer.php') ?>
 </html>
