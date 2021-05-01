<?php

//check_faculty_login.php

// connect database
include('../admin/database_connection.php');

session_start();

// variables for data
$faculty_emailid = '';
$faculty_password = '';

// variables for error in data
$error_faculty_emailid = '';
$error_faculty_password = '';

$error = 0;

// username is empty
if(empty($_POST["faculty_emailid"]))
{
	$error_faculty_emailid = 'Username is required';
	$error++;
}
else
{
	$faculty_emailid = $_POST["faculty_emailid"];
}

// password is empty
if(empty($_POST["faculty_password"]))
{	
	$error_faculty_password = 'Password is required';
	$error++;
}
else
{
	$faculty_password = $_POST["faculty_password"];
}

// both are present
if($error == 0)
{
	// fetch email from database
	$query = "
	SELECT * FROM tbl_faculty 
	WHERE faculty_emailid = '".$faculty_emailid."'
	";

	$statement = $connect->prepare($query);

	if($statement->execute())
	{
		$total_row = $statement->rowCount();

		// check is there is any email match
		if($total_row > 0)
		{
			$result = $statement->fetchAll();
			
			// for each row matched
			foreach($result as $row)
			{
				//verify password
				if(password_verify($faculty_password, $row["faculty_password"]))
				{
					$_SESSION["faculty_id"] = $row["faculty_id"];
				}
				else
				{
					$error_faculty_password = "Wrong Password";
					$error++;
				}
			}
		}
		// no email matched
		else
		{
			$error_faculty_emailid = "Wrong Username";
			$error++;
		}
	}
}

// error in login
if($error > 0)
{
	$output = array(
		'error'						=>	true,
		'error_faculty_emailid'		=>	$error_faculty_emailid,
		'error_faculty_password'	=>	$error_faculty_password
	);
}
else
{
	$output = array(
		'success'		=>	true
	);
}

// return output in json
echo json_encode($output);

?>