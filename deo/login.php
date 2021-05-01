<?php

//login.php


// connect database
include('../admin/database_connection.php');

session_start();

if(isset($_SESSION["deo_id"]))
{
  // if value is set, then it will redirect to index.php
  header('location:index.php');
  
}

?>

<!-- ------------------------------------------------------------------------------------------------------- -->

<!DOCTYPE html>
<html lang="en">

<head>

  <title>Student Attendance System</title>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</head>

<body>

<div class="jumbotron text-center" style="margin-bottom:0">
  <h1>Attendance Tracking Management System</h1>
</div>

<div class="container">
  <div class="row">
    <div class="col-md-4">

    </div>
    <div class="col-md-4" style="margin-top:20px;">
      <div class="card">
        <div class="card-header">Data Entry Operator Login</div>

         <!-- LOGIN FORM -->
        <div class="card-body">
           <form method="post" id="deo_login_form">

            <div class="form-group">
              <label>Enter Username</label>
              <input type="text" name="deo_user_name" id="deo_user_name" class="form-control" />
              <span id="error_deo_user_name" class="text-danger"></span>
            </div>

            <div class="form-group">
              <label>Enter Password</label>
              <input type="password" name="deo_password" id="deo_password" class="form-control" />
              <span id="error_deo_password" class="text-danger"></span>
            </div>

            <div class="form-group">
              <input type="submit" name="deo_login" id="deo_login" class="btn btn-info" value="Login" />
            </div>

          </form>
        </div>

      </div>
    </div>
    <div class="col-md-4">

    </div>
  </div>
</div>

</body>
</html>

<!-- --------------------------------------------------------------------------------------------------------- -->

<script>
$(document).ready(function(){

  $('#deo_login_form').on('submit', function(event){
    
    event.preventDefault();
    $.ajax({

      url:"check_deo_login.php",
      method:"POST",
      data:$(this).serialize(),     // in this format want to send data
      dataType:"json",              //  in this format want to recieve data
      
      // function called before sending ajax request
      beforeSend:function(){          
        $('#deo_login').val('Validate...');   // change tex of submit button
        $('#deo_login').attr('disabled', 'disabled');   // disable submit
      },

      // called after successful calling
      success:function(data)
      {
        // login successful
        if(data.success)
        {
          location.href = "<?php echo $base_url; ?>deo";
        }

        // login error
        if(data.error)
        {
          $('#deo_login').val('Login');
          $('#deo_login').attr('disabled', false);  // enable submit

          // empty username
          if(data.error_deo_user_name != '')
          {
            $('#error_deo_user_name').text(data.error_deo_user_name);
          }
          else
          {
            $('#error_deo_user_name').text('');
          }

          // empty password
          if(data.error_deo_password != '')
          {
            $('#error_deo_password').text(data.error_deo_password);
          }
          else
          {
            $('#error_deo_password').text('');
          }

        }
      }
    });
    
  });
});
</script>