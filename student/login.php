<?php

//login.php

// connect database
include('../admin/database_connection.php');

session_start();

// login successful, then call index.php
if(isset($_SESSION["student_id"]))
{
//  header('location:index.php');
  header('location.href = "chart.php?action=student_chart&student_id="+student_id";');
}

?>

<!-- ---------------------------------------------------------------------------------------------------- -->

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
        <div class="card-header">Student Login</div>
        
        <div class="card-body">
          <form method="post" id="student_login_form">
            
            <div class="form-group">
              
            <!-- username -->
              <label>Enter Username</label>
              <input type="text" name="student_emailid" id="student_emailid" class="form-control" />
              <span id="error_student_emailid" class="text-danger"></span>
            </div>
            
            <div class="form-group">
            
              <!-- password -->
              <label>Enter Password</label>
              <input type="password" name="student_password" id="student_password" class="form-control" />
              <span id="error_student_password" class="text-danger"></span>
            </div>
            
            <!-- submit -->
            <div class="form-group">
              <input type="submit" name="student_login" id="student_login" class="btn btn-info" value="Login" />
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
  
  // login page submit clicked
  $('#student_login_form').on('submit', function(event){
    
    event.preventDefault();
    $.ajax({
    
    // call check login file
      url:"check_student_login.php",
      method:"POST",
      data:$(this).serialize(),
      dataType:"json",

      // change submit to validate and then disable button
      beforeSend:function(){
        $('#student_login').val('Validate...');
        $('#student_login').attr('disabled','disabled');
      },

      // login check
      success:function(data)
      {

        // if success, call index.php
        if(data.success)
        {
          location.href="chart.php";
        }

        // if error, enable button and display error
        if(data.error)
        {
          $('#student_login').val('Login');
          $('#student_login').attr('disabled', false);      // enable submit button

          // if email error not empty
          if(data.error_student_emailid != '')
          {
            $('#error_student_emailid').text(data.error_student_emailid);
          }
          else    
          {
            $('#error_student_emailid').text('');
          }

          // if password error not empty
          if(data.error_student_password != '')
          {
            $('#error_student_password').text(data.error_student_password);
          }
          else
          {
            $('#error_student_password').text('');
          }
        }
      }
    })
  });
});
</script>