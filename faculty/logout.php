<?php

//logout.php

session_start();

session_destroy();

// go to login.php after logout
header('location:login.php');

?>