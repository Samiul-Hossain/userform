<?php

include('config/db_connect.php');

if(isset($_GET['delete'])){
  $id_to_delete = $_GET['delete'];
  $sql = "DELETE FROM users WHERE id=$id_to_delete";
  if(mysqli_query($conn,$sql)){
    header('Location: index.php');
  }else{
    echo 'query error'.mysqli_error($conn);
  }
}

if(isset($_GET['pageno'])){
  $pageno = $_GET['pageno'];
}else{
  $pageno = 1;
}

$no_of_records_per_page = 5;
$offset = ($pageno-1) * $no_of_records_per_page;

$total_pages_sql = "SELECT COUNT(*) FROM users";
$result = mysqli_query($conn,$total_pages_sql);
$total_rows = mysqli_fetch_array($result)[0];
$total_pages = ceil($total_rows/$no_of_records_per_page);

$sql = "SELECT * FROM users LIMIT $offset, $no_of_records_per_page";
$res_data = mysqli_query($conn,$sql);
$users = mysqli_fetch_all($res_data,MYSQLI_ASSOC);

// $sql = 'SELECT name,address,id,email,gender FROM users ORDER BY created_at LIMIT 5';
//
// $result = mysqli_query($conn, $sql);
//
// $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
//
// mysqli_free_result($result);
//
// mysqli_close($conn);

?>

<!DOCTYPE html>
<html>
  <?php include ('templates/header.php'); ?>
  <div class="table-responsive container-fluid ">
    <div class="row">
      <div class="col">
        <h2>Dashboard</h2>
      </div>
      <div class="col text-right">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalForm">Add User</button>
      </div>
    </div>
<!-- Modal -->
<div class="modal fade" id="modalForm" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Add User</h4>
        <button type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">x</span>
          <span class="sr-only">Close</span>
        </button>

      </div>

      <div class="modal-body">
        <p class="statusMsg"></p>
          <form role="form">
            <div class="form-group">
              <label for="name">Name:</label>
              <input type="text" name="" value="" class="form-control" id="name" placeholder="Enter your name">
            </div>
            <div class="form-group">
              <label for="email">Email:</label>
              <input type="text" name="" value="" class="form-control" id="email" placeholder="Enter your name">
              <span id="availability"></span>
            </div>
            <div class="form-group">
              <label for="address">Address:</label>
              <input type="text" name="" value="" class="form-control" id="address" placeholder="Enter your address">
            </div>
            <div class="form-group">
              <label for="">Gender:</label>
            <p>
              <label>
                <input name="group1" type="radio" checked id="male" value="male"/>
                <span>Male</span>
              </label>
            </p>
            <p>
              <label>
                <input name="group1" type="radio" id="female" value = "female"/>
                <span>Female</span>
              </label>
            </p>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary submitBtn" onclick="submitContactForm()" id="mail-submit">SUBMIT</button>
        </div>
        <p class="form-message"></p>
      </div>

    </div>
  </div>
</div>
    <form class="" action="index.php" method="post">

  <div class="container-fluid">
    <table class="table table-striped table-sm " id="mytable">
      <thead>
        <tr>
          <th></th>
          <th></th>
          <th>#</th>
          <th>Email</th>
          <th>Name</th>
          <th>Gender</th>
          <th>Address</th>

        </tr>
      </thead>
      <tbody>
        <div>
          <?php foreach ($users as $user) { ?>
              <tr>
                  <td>
                    <a href="add.php?edit=<?php echo $user['id']; ?>" class="fas fa-edit" value="<?php echo $user['id'] ?>" name="id_to_edit"></a>
                  </td>
                  <td>
                    <button type="button" class="btn btn-primary fas fa-trash-alt" data-toggle="modal" data-target="#exampleModal"></button>
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Warning!</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            Are you sure you want to delete?
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <a href="index.php?delete=<?php echo $user['id']; ?>" class=" btn btn-primary" value="<?php echo $user['id'] ?>" name="id_to_delete">Delete</a>
                            <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- <a href="index.php?delete=<?php echo $user['id']; ?>" class="fas fa-trash-alt" value="<?php echo $user['id'] ?>" name="id_to_delete"></a> -->
                  </td>
                  <td><?php echo $user['id']; ?></td>
                  <td><?php echo htmlspecialchars($user['email']); ?></td>
                  <td><?php echo htmlspecialchars($user['name']); ?></td>
                  <td><?php echo htmlspecialchars($user['gender']); ?></td>
                  <td><?php echo htmlspecialchars($user['address']); ?></td>
              </tr>
          <?php }   ?>
        </div>
      </tbody>
    </table>
    </div>
  </form>
  <script type="text/javascript">
    $(document).ready(function(){
      $('#email').blur(function(){

        var email = $(this).val();

        $.ajax({

          url:'http://localhost/userform/usecheck.php',
          method: "POST",
          data:{email:email},
          datatype: "text",
          success:function(html)
          {
            //$('#availability').html(html);
            if(html==0){
              $('#mail-submit').attr('disabled','disabled');
              $('#availability').html('<span class="text-danger">Email exists</span>');
          }
            else{
              $('#mail-submit'). removeAttr('disabled');
              $('#availability').html('<span class="text-success">Email available</span>');
          }

          }

        });
      });
    });
  </script>
  <script>
    function submitContactForm(){
      var reg = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
      var name = $('#name').val();
      var email = $('#email').val();
      var address = $('#address').val();
      var gender = $('input[name=group1]:checked').val();
      if(name.trim() == ''){
        alert('PLease enter your name.');
        $('#name').focus();
        return false;
      }else if(email.trim() == ''){
        alert('Please enter your email');
        $('#email').focus();
        return false;
      }else if(address.trim() == ''){
        alert('Please enter your address');
        $('#address').focus();
        return false
      }else if(email.trim() != '' && !reg.test(email)){
        alert('Please enter a valid email');
        $('#email').focus();
        return false;
      }else{
        $.ajax({
            type:'POST',
            url:'http://localhost/userform/add.php',
            data:'contactFrmSubmit=1&name='+name+'&email='+email+'&address='+address+'&gender='+gender,
            success:function(data){
                 location.reload();
            },
            error:function(err){
              alert("lose");
            }

        });
        }
      }
  </script>
  </div>
  <div class="container-fluid d-flex justify-content-between">
    <div class="">
      <?php include ('templates/footer.php') ?>
    </div>
    <div class="">
    <ul class="pagination ">
      <li><a href="?pageno=1">First</a></li> &nbsp;
      <li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">
          <a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>">Prev</a>&nbsp;
      </li>
      <?php  if ($total_pages <= 10){
                for ($counter = 1; $counter <= $total_pages; $counter++){
                    if ($counter == $pageno) {
                        echo "<li class='active'><a>$counter</a>&nbsp</li>";
                      }else{
                        echo "<li><a href='?pageno=$counter'>$counter</a>&nbsp</li>";
                      }
                    }
                  }?>
      <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
          <a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>">Next</a>&nbsp;
      </li>
      <li><a href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
  </ul>
  </div>

  </div>

</html>
