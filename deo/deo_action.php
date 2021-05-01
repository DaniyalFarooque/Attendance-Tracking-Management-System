<?php

//deo_action.php

// include database
include('../admin/database_connection.php');

session_start();

if(isset($_POST["action"]))
{

	// for main page of deo list
	if($_POST["action"] == "fetch")
	{

		// select query
		$query = "
		SELECT * FROM tbl_deo 
		";

		// for searching
		if(isset($_POST["search"]["value"]))
		{
			$query .= '
			WHERE tbl_deo.deo_user_name LIKE "%'.$_POST["search"]["value"].'%" 
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
			ORDER BY tbl_deo.deo_user_name
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
			$sub_array[] = $row["deo_user_name"];
			$sub_array[] = '<button type="button" user_name="edit_deo" class="btn btn-primary btn-sm edit_deo" id="'.$row["deo_id"].'">Edit</button>';
			$sub_array[] = '<button type="button" user_name="delete_deo" class="btn btn-danger btn-sm delete_deo" id="'.$row["deo_id"].'">Delete</button>';
			$data[] = $sub_array;
		}
		
		// store in output
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connect, 'tbl_deo'),
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
		$deo_user_name = '';
		$deo_password = '';

		$error_deo_user_name = '';
		$error_deo_password = '';

		$error = 0;
		
		// deo user_name empty
		if(empty($_POST["deo_user_name"]))
		{
			$error_deo_user_name = 'user_name is required';
			$error++;
		}
		else
		{
			$deo_user_name = $_POST["deo_user_name"];
		}
		
		// password empty
		if($_POST["action"] == 'Add')
		{

			if(empty($_POST["deo_password"]))
			{
				$error_deo_password = 'password is required';
				$error++;
			}
			else
			{
				$deo_password = $_POST["deo_password"];
			}
		}

		// if any validation error
		if($error > 0)
		{
			// output array
			$output = array(
				'error'						=>	true,
				'error_deo_user_name'		=>	$error_deo_user_name,
				'error_deo_password'		=>	$error_deo_password,
			);
		}
		else
		{

			// for adding deo
			if($_POST["action"] == 'Add')
			{
				$data = array(
					':deo_user_name'		=>	$deo_user_name,
					':deo_password'			=>	$deo_password
				);
			
				// insert query
				$query = "
				INSERT INTO tbl_deo 
				(deo_user_name, deo_password) 
				SELECT * FROM (SELECT :deo_user_name, :deo_password) as temp 
				WHERE NOT EXISTS (
				SELECT deo_user_name FROM tbl_deo WHERE deo_user_name = :deo_user_name
				) LIMIT 1
				";

				// connect and execute query
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
						'error_deo_user_name'	=>	'User Name Already Exists'
						);
					}
				}


			}
			
// ---------------------------------------------------------------------------------------------------------
			
			// for editting deo
			if($_POST["action"] == "Edit")
			{

				// if username exists or not
				$query1 = '
				SELECT deo_user_name FROM tbl_deo 
				WHERE deo_user_name = "'.$deo_user_name.'" 
				AND deo_id <> "'.$_POST["deo_id"].'" 
				';
			
				$statement1 = $connect->prepare($query1);
				$statement1->execute();
				
				//data already present
				if($statement1->rowCount() > 0 )
				{
					$output = array(
						'error'							=>	true,
						'error_deo_user_name'			=>	'User Name already exists '
					);
				}
				else 	// update this data
				{
					// data array
					$data = array(
						':deo_user_name'		=>	$deo_user_name,	
						':deo_password'			=>	$deo_password,
						':deo_id'				=>	$_POST["deo_id"]
					);

					// update query
					$query = "
					UPDATE tbl_deo 
					SET  
					deo_user_name = :deo_user_name, 
					deo_password = :deo_password
					WHERE deo_id = :deo_id
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

		// send data in json format to ajax request
		echo json_encode($output);
	}


// -------------------------------------------------------------------------------------------------------

// before editing, fetch existing data
	if($_POST["action"] == "edit_fetch")
	{

		// select query
		$query = "
		SELECT * FROM tbl_deo 
		WHERE deo_id = '".$_POST["deo_id"]."'
		";
		
		$statement = $connect->prepare($query);
	
		if($statement->execute())
		{
			$result = $statement->fetchAll();
			
			// for each matched row
			foreach($result as $row)
			{
				$output["deo_user_name"] = $row["deo_user_name"];
				$output["deo_id"] = $row["deo_id"];
			}
	
			// return output to ajax request
			echo json_encode($output);
		}
	}

// -------------------------------------------------------------------------------------------------------

// delete deo
	if($_POST["action"] == "delete")
	{
		// delete query
		$query = "
		DELETE FROM tbl_deo 
		WHERE deo_id = '".$_POST["deo_id"]."'
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