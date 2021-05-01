<?php

//check_admin_login.php

// connect database
include('database_connection.php');

session_start();

// declare variables
$admin_user_name = '';
$admin_password = '';
$error_admin_user_name = '';
$error_admin_password = '';
$error = 0;

// empty username
if(empty($_POST["admin_user_name"]))
{
	$error_admin_user_name = 'Username is required';
	$error++;
}
else
{
	$admin_user_name = $_POST["admin_user_name"];
}

// empty pasword
if(empty($_POST["admin_password"]))
{
	$error_admin_password = 'Password is required';
	$error++;
}
else
{
	$admin_password = $_POST["admin_password"];
}

// no error
if($error == 0)
{
	// select query
	$query = "
	SELECT * FROM tbl_admin 
	WHERE admin_user_name = '".$admin_user_name."'
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
				if(password_verify($admin_password, $row["admin_password"]))
				{
					$_SESSION["admin_id"] = $row["admin_id"];
				}
				else
				{
					$error_admin_password = "Wrong Password";
					$error++;
				}
			}
		}
		// no row matched - wrong username
		else
		{
			$error_admin_user_name = 'Wrong Username';
			$error++;
		}
	}
}

// any error is there
if($error > 0)
{
	$output = array(
		'error'					=>	true,
		'error_admin_user_name'	=>	$error_admin_user_name,
		'error_admin_password'	=>	$error_admin_password
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