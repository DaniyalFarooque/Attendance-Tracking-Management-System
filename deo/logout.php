<?php

//logout.php

session_start();

session_destroy();

// call login if not logined
header('location:login.php');


?>