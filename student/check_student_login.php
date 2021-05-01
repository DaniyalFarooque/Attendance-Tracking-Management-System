<?php

//check_student_login.php
// called by login.php 

// connect database
include('../admin/database_connection.php');
session_start();

// declare variables
$student_emailid = '';
$student_password = '';
$error_student_emailid = '';
$error_student_password = '';
$error = 0;

// empty email
if(empty($_POST["student_emailid"]))
{
	$error_student_emailid = 'Username is required';
	$error++;
}
else
{
	$student_emailid = $_POST["student_emailid"];
}

// empty pasword
if(empty($_POST["student_password"]))
{	
	$error_student_password = 'Password is required';
	$error++;
}
else
{
	$student_password = $_POST["student_password"];
}

// if both are present
if($error == 0)
{
	// check in database
	$query = "
	SELECT * FROM tbl_student 
	WHERE student_emailid = '".$student_emailid."'
	";

	// match emailid by the databse
	$statement = $connect->prepare($query);
	if($statement->execute())
	{
		$total_row = $statement->rowCount();

		// if matched
		if($total_row > 0)
		{
			// fetch all results matched by emailid
			$result = $statement->fetchAll();
			foreach($result as $row)
			{
				// verify password
				if(password_verify($student_password, $row["student_password"]))
				{
					$_SESSION["student_id"] = $row["student_id"];
				}
				else			// else wrong password
				{
					$error_student_password = "Wrong Password";
					$error++;
				}
			}
		}
		else			// if not row matched then wrong email
		{
			$error_student_emailid = "Wrong Email Address";
			$error++;
		}
	}
}

// if there is error
if($error > 0)
{
	$output = array(
		'error'						=>	true,
		'error_student_emailid'		=>	$error_student_emailid,
		'error_student_password'	=>	$error_student_password
	);
}
else			// success
{
	$output = array(
		'success'		=>	true
	);
}

// send putput in json format
echo json_encode($output);

?>