<?php

include('config/db_connect.php');

$gender = $name = $email = $address = '';
$errors = array('email'=>'','name'=>'','address'=>'');
$update = false;
$id=0;

if(isset($_GET['edit'])){
  $id = $_GET['edit'];
  $update = true;
  $sql = "SELECT * FROM users WHERE id=$id";
  $resultE = mysqli_query($conn, $sql);
  $row=$resultE->fetch_array();
  if(count($row)){
    $name=$row['name'];
    $email=$row['email'];
    $address=$row['address'];
    $gender=$row['gender'];
  }
}

if(isset($_POST['save'])){

  if(empty($_POST['email'])){
    $errors['email'] = 'An email is required <br />';
  } else{
    $email= $_POST['email'];
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
      $errors['email'] = 'Email must be a valid email address';
    }
  }
  if(empty($_POST['name'])){
    $errors['name'] = 'A name is required <br />';
  } else{
    $name = $_POST['name'];
    if(!preg_match('/^[a-zA-Z\s]+$/',$name)){
      $errors['name'] = 'Name must be letters and spaces only';
    }
  }
  if(empty($_POST['address'])){
    $errors['address'] = 'Atleast one address is required <br />';
  } else{
    $address = $_POST['address'];
    if(!preg_match('/^([a-zA-Z\s]+)(,\s*[a-zA-Z\s]*)*$/',$address)){
      $errors['address'] = 'Address must be a comma separated list';
    }
  }

  $gender=$_POST['group1'];
  if(array_filter($errors)){
    //echo 'errors in the form';address
  } else{
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $name = mysqli_real_escape_string($conn,$_POST['name']);
    $address = mysqli_real_escape_string($conn,$_POST['address']);
    $gender = mysqli_real_escape_string($conn,$_POST['group1']);

    $sql = "INSERT INTO users(email,name,address,gender) VALUES ('$email','$name','$address','$gender')";
    if(mysqli_query($conn,$sql)){
      header('Location: index.php');
    }else{
      echo 'query error: '.mysqli_error($conn);
    }
  }

}//end of post

if(isset($_POST['update'])){
  $name=$_POST['name'];
  $id=$_POST['id'];
  $name=$_POST['name'];
  $address=$_POST['address'];
  $email=$_POST['email'];
  $gender=$_POST['group1'];

  $sql="UPDATE users SET name='$name', email='$email', address='$address', gender='$gender' WHERE id=$id";
  if(mysqli_query($conn,$sql)){
    header('Location: index.php');
    echo 'asndjczx';
  }else{
    echo 'query error: '.mysqli_error($conn);
  }
}

if(isset($_POST['contactFrmSubmit']) && !empty($_POST['name']) && !empty($_POST['email']) && (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false)){

    // Submitted form data
    $name   = $_POST['name'];
    $email  = $_POST['email'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];

    $sql = "INSERT INTO users(email,name,address,gender) VALUES ('$email','$name','$address','$gender')";
    if(mysqli_query($conn,$sql)){
      header('Location: index.php');
    }else{
      echo 'query error: '.mysqli_error($conn);
    }
  }

?>

<html>
<?php include ('templates/header.php'); ?>
  <div class="container">
    <form action="add.php" method="post" class="reg-form">
    <h3>Add a User</h3>
      <div class="form-group">
      <input type="hidden" name="id" value="<?php echo $id; ?>">
        <label for="">Your Email:</label>
        <input type="text" name="email" value="<?php echo htmlspecialchars($email) ?>" placeholder="Enter Email">
        <div><?php echo $errors['email']; ?></div>
      </div>

      <div class="form-group">
        <label for="">Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($name) ?>" placeholder="Enter Name">
        <div><?php echo $errors['name']; ?></div>
      </div>

      <div class="form-group">
        <label for="">Address:</label>
        <input type="text" name="address" value="<?php echo htmlspecialchars($address) ?>" placeholder="Enter Address">
        <div><?php echo $errors['address']; ?></div>
      </div>
      <div class="form-group">
        <label for="">Gender</label>
      <p>
        <label>
          <input name="group1" type="radio" checked id="male" value="male" />
          <span>Male</span>
        </label>
      </p>
      <p>
        <label>
          <input name="group1" type="radio" id="female" value = "female" />
          <span>Female</span>
        </label>
      </p>
      </div>
      <div>
        <?php
        if ($update == true):
         ?>
        <input type="submit" name="update" value="update" class="w-100 btn btn-lg btn-info">
      <?php else: ?>
        <input type="submit" name="save" value="save" class="w-100 btn btn-lg btn-primary">
      <?php endif; ?>
      </div>
    </form>
  </div>
  <?php include ('templates/footer.php') ?>
  <script type="text/javascript">
  $(document).ready(function(){
  $('select').formSelect();
});
  </script>

</html>
