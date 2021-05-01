<?php

//login.php

include('../admin/database_connection.php');

session_start();

// call index.php after successful login
if(isset($_SESSION["faculty_id"]))
{
  header('location:index.php');
}

?>

<!-- --------------------------------------------------------------------------------------------------- -->

<!DOCTYPE html>
<html lang="en">
<head>

  <title>Attendance Tracking Management System</title>

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

    <!-- Login page -->
    <div class="col-md-4" style="margin-top:20px;">
      <div class="card">

        <!-- heading -->
        <div class="card-header">Faculty Login</div>
        
        <div class="card-body">
          <form method="post" id="faculty_login_form">
            
            <div class="form-group">
              
            <!-- username -->
              <label>Enter Username</label>
              <input type="text" name="faculty_emailid" id="faculty_emailid" class="form-control" />
              <span id="error_faculty_emailid" class="text-danger"></span>
            </div>
            
            <div class="form-group">
            
              <!-- password -->
              <label>Enter Password</label>
              <input type="password" name="faculty_password" id="faculty_password" class="form-control" />
              <span id="error_faculty_password" class="text-danger"></span>
            </div>
            
            <!-- submit -->
            <div class="form-group">
              <input type="submit" name="faculty_login" id="faculty_login" class="btn btn-info" value="Login" />
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-4">

    </div>
  </div>
</div>

<!-- ---------------------------------------------------------------------------------------------------- -->

<script>

$(document).ready(function(){

  $('#faculty_login_form').on('submit', function(event){

    event.preventDefault();

    // call check faculty login to validate login
    $.ajax(
      {
      url:"check_faculty_login.php",
      method:"POST",
      data:$(this).serialize(),
      dataType:"json",
      
      // before that, change submit button to validate
      beforeSend:function(){
        $('#faculty_login').val('Validate...');
        $('#faculty_login').attr('disabled','disabled');  // and disable submit
      },
      
      success:function(data)
      {
        // login successful, then go to index.php
        if(data.success)
        {
          location.href="index.php";
        }

        // login error
        if(data.error)
        {

          $('#faculty_login').val('Login');
          $('#faculty_login').attr('disabled', false);    // enable submit button

          // if error in email
          if(data.error_faculty_emailid != '')
          {
            $('#error_faculty_emailid').text(data.error_faculty_emailid);
          }
          else
          {
            $('#error_faculty_emailid').text('');
          }

          // if error in password
          if(data.error_faculty_password != '')
          {
            $('#error_faculty_password').text(data.error_faculty_password);
          }
          else
          {
            $('#error_faculty_password').text('');
          }
        }
      }
    })

  });

});

</script>