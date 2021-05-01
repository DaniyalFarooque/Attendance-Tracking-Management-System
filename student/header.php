<?php

//header.php

include('../admin/database_connection.php');
session_start();

// call login.php if not logined

if(!isset($_SESSION["student_id"]))
{
  header('location:login.php');
}

?>

<!-- ------------------------------------------------------------------------------------------------------ -->

<!DOCTYPE html>
<html lang="en">
<head>

  <title>Student Attendance System </title>
  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- css files -->
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/dataTables.bootstrap4.min.css">

  <!-- js files -->
  <script src="../js/jquery.min.js"></script>
  <script src="../js/popper.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/jquery.dataTables.min.js"></script>
  <script src="../js/dataTables.bootstrap4.min.js"></script>

</head>
<body>

 <!-- heading -->
<div class="jumbotron-small text-center" style="margin-bottom:0">
  <h1>Attendance Tracking Management System</h1>
</div>

<!-- navbar -->
<nav class="navbar navbar-expand-sm bg-dark navbar-dark">

<!-- home -->
  <a class="navbar-brand" href="chart.php">Home</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>
  
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav">
      
      <!-- profile -->
      <li class="nav-item">
        <a class="nav-link" href="profile.php">Profile</a>
      </li>
      
      <!-- attendance -->
      <li class="nav-item">
        <a class="nav-link" href="attendance.php">Attendance</a>
      </li>
      
      <!-- logout -->
      <li class="nav-item">
        <a class="nav-link" href="logout.php">Logout</a>
      </li>  
    </ul>
  </div>  
</nav>