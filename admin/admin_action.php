<?php

//admin_action.php

// include database
include('database_connection.php');
session_start();

if(isset($_POST["action"]))
{

	// for main page of admin list
	if($_POST["action"] == "fetch")
	{

		// select query
		$query = "
		SELECT * FROM tbl_admin 
		";

		// for searching
		if(isset($_POST["search"]["value"]))
		{
			$query .= '
			WHERE tbl_admin.admin_user_name LIKE "%'.$_POST["search"]["value"].'%" 
			';
		}

		// for ordering data 
		if(isset($_POST["order"]))
		{
			$query .= '
			ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].'
			';
		}
		else
		{
			$query .= '
			ORDER BY tbl_admin.admin_user_name
			';
		}

		if($_POST["length"] != -1)
		{
			$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

		// execute and fetch matched rows
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$data = array();
		$filtered_rows = $statement->rowCount();

		// for each row put data in array
		foreach($result as $row)
		{
			$sub_array = array();
			$sub_array[] = $row["admin_user_name"];
			$sub_array[] = '<button type="button" user_name="edit_admin" class="btn btn-primary btn-sm edit_admin" id="'.$row["admin_id"].'">Edit</button>';
			$sub_array[] = '<button type="button" user_name="delete_admin" class="btn btn-danger btn-sm delete_admin" id="'.$row["admin_id"].'">Delete</button>';
			$data[] = $sub_array;
		}
		
		// store in output
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connect, 'tbl_admin'),
			"data"				=>	$data
		);

		// return output data in json format
		echo json_encode($output);
	}


// ----------------------------------------------------------------------------------------------------------
	
	// for add and edit action 
	if($_POST["action"] == 'Add' || $_POST["action"] == "Edit")
	{
		// variables 
		$admin_user_name = '';
		$admin_password = '';

		$error_admin_user_name = '';
		$error_admin_password = '';

		$error = 0;
		
		// admin user_name empty
		if(empty($_POST["admin_user_name"]))
		{
			$error_admin_user_name = 'User Name is required';
			$error++;
		}
		else
		{
			$admin_user_name = $_POST["admin_user_name"];
		}
		
		// password empty
		if($_POST["action"] == 'Add')
		{

			if(empty($_POST["admin_password"]))
			{
				$error_admin_password = 'Password is required';
				$error++;
			}
			else
			{
				$admin_password = $_POST["admin_password"];
			}
		}

		// if any validation error
		if($error > 0)
		{
			// output array
			$output = array(
				'error'						=>	true,
				'error_admin_user_name'		=>	$error_admin_user_name,
				'error_admin_password'		=>	$error_admin_password,
			);
		}
		else
		{

			// for adding admin
			if($_POST["action"] == 'Add')
			{
				$data = array(
					':admin_user_name'			=>	$admin_user_name,
					':admin_password'			=>	password_hash($admin_password, PASSWORD_DEFAULT)
				);
			
				// insert query
				$query = "
				INSERT INTO tbl_admin 
				(admin_user_name, admin_password) 
				SELECT * FROM (SELECT :admin_user_name, :admin_password) as temp 
				WHERE NOT EXISTS (
				SELECT admin_user_name FROM tbl_admin WHERE admin_user_name = :admin_user_name
				) LIMIT 1
				";

				$statement = $connect->prepare($query);

				if($statement->execute($data))
				{
					if($statement->rowCount() > 0)
					{
						// data added
						$output = array(
						'success'		=>	'Data Added Successfully',
						);
					}
					else
					{
						// error
						$output = array(
						'error'					=>	true,
						'error_admin_user_name'	=>	'User Name Already Exists'
						);
					}
				}


			}
			
// ---------------------------------------------------------------------------------------------------------
			
			// for editting admin
			if($_POST["action"] == "Edit")
			{

				// if username exists or not
				$query1 = '
				SELECT admin_user_name FROM tbl_admin 
				WHERE admin_user_name = "'.$admin_user_name.'" 
				AND admin_id <> "'.$_POST["admin_id"].'" 				
				';
			
				$statement1 = $connect->prepare($query1);
				$statement1->execute();
				
				//data already present
				if($statement1->rowCount() > 0 )
				{
					$output = array(
						'error'							=>	true,
						'error_admin_user_name'			=>	'User Name already exists '
					);
				}
				else 	// update this data
				{

				if(empty($_POST["admin_password"])){
					// data array
					$data = array(
						':admin_user_name'		=>	$admin_user_name,	
						':admin_id'				=>	$_POST["admin_id"]
					);

					// update query
					$query = "
					UPDATE tbl_admin 
					SET  
					admin_user_name = :admin_user_name 
					WHERE admin_id = :admin_id
					";

					$statement = $connect->prepare($query);
					$statement->execute($data);
				
					// data edited 
					$output = array(
						'success'		=>	'Data Edited Successfully',
					);
				}
				else
				{
					// data array
					$data = array(
						':admin_user_name'		=>	$admin_user_name,	
						':admin_password'		=>	password_hash($admin_password, PASSWORD_DEFAULT),
						':admin_id'				=>	$_POST["admin_id"]
					);

					// update query
					$query = "
					UPDATE tbl_admin 
					SET  
					admin_user_name = :admin_user_name, 
					admin_password = :admin_password
					WHERE admin_id = :admin_id
					";

					$statement = $connect->prepare($query);
					$statement->execute($data);
				
					// data edited 
					$output = array(
						'success'		=>	'Data Edited Successfully',
					);

				}
				}
			}
		}

		// send data in json format to ajax request
		echo json_encode($output);
	}


// -------------------------------------------------------------------------------------------------------

// before editing, fetch existing data
	if($_POST["action"] == "edit_fetch")
	{

		// select query
		$query = "
		SELECT * FROM tbl_admin 
		WHERE admin_id = '".$_POST["admin_id"]."'
		";
		
		$statement = $connect->prepare($query);
	
		if($statement->execute())
		{
			$result = $statement->fetchAll();
			
			// for each matched row
			foreach($result as $row)
			{
				$output["admin_user_name"] = $row["admin_user_name"];
				$output["admin_id"] = $row["admin_id"];
			}

			// return output to ajax request
			echo json_encode($output);
		}
	}

// -------------------------------------------------------------------------------------------------------

// delete admin
	if($_POST["action"] == "delete")
	{
		// delete query
		$query = "
		DELETE FROM tbl_admin 
		WHERE admin_id = '".$_POST["admin_id"]."'
		";

		$statement = $connect->prepare($query);
		
		// data deleted
		if($statement->execute())
		{
			echo 'Data Delete Successfully';
		}
	}
}

?>