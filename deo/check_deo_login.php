<?php

//check_deo_login.php

// connect database
include('../admin/database_connection.php');

session_start();

// declare variables
$deo_user_name = '';
$deo_password = '';
$error_deo_user_name = '';
$error_deo_password = '';
$error = 0;

// empty username
if(empty($_POST["deo_user_name"]))
{
	$error_deo_user_name = 'Username is required';
	$error++;
}
else
{
	$deo_user_name = $_POST["deo_user_name"];
}

// empty pasword
if(empty($_POST["deo_password"]))
{
	$error_deo_password = 'Password is required';
	$error++;
}
else
{
	$deo_password = $_POST["deo_password"];
}

// no error
if($error == 0)
{
	// select query
	$query = "
	SELECT * FROM tbl_deo 
	WHERE deo_user_name = '".$deo_user_name."'
	";

	$statement = $connect->prepare($query);
 
	if($statement->execute())
	{
		// number of rows matched
		$total_row = $statement->rowCount();

		// rows count>0 - username matched
		if($total_row > 0)
		{
			$result = $statement->fetchAll();

			// for each matched row
			foreach($result as $row)
			{
				// verify password
				if(password_verify($deo_password, $row["deo_password"]))
				{
					$_SESSION["deo_id"] = $row["deo_id"];
				}
				else
				{
					$error_deo_password = "Wrong Password";
					$error++;
				}
			}
		}
		// no row matched - wrong username
		else
		{
			$error_deo_user_name = 'Wrong Username';
			$error++;
		}
	}
}

// any error is there
if($error > 0)
{
	$output = array(
		'error'					=>	true,
		'error_deo_user_name'	=>	$error_deo_user_name,
		'error_deo_password'	=>	$error_deo_password
	);
}
// no error
else
{
	$output = array(
		'success'		=>	true
	);	
}

// send data to ajax request in json format
echo json_encode($output);

?>