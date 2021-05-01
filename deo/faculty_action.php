<?php

//faculty_action.php

// connect database
include('../admin/database_connection.php');

session_start();

// if action is set
if(isset($_POST["action"]))
{
	// for main page of faculty
	if($_POST["action"] == "fetch")
	{
		// select query
		$query = "
		SELECT * FROM tbl_faculty 
		INNER JOIN tbl_course 
		ON tbl_course.course_id = tbl_faculty.faculty_course_id 
		";

		// search value
		if(isset($_POST["search"]["value"]))
		{
			$query .= '
			WHERE tbl_faculty.faculty_name LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_faculty.faculty_emailid LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_course.course_name LIKE "%'.$_POST["search"]["value"].'%" 
			';
		}

		// for ordering rows
		if(isset($_POST["order"]))
		{
			$query .= '
			ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].'
			';
		}
		else
		{
			$query .= '
			ORDER BY tbl_faculty.faculty_id
			';
		}

		if($_POST["length"] != -1)
		{
			$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

		// execute and fetch
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$data = array();
		$filtered_rows = $statement->rowCount();
		
		// for each row
		foreach($result as $row)
		{
			// add data in subarray
			$sub_array = array();
			$sub_array[] = $row["faculty_id"];
			$sub_array[] = $row["faculty_name"];
			$sub_array[] = $row["faculty_emailid"];
			$sub_array[] = $row["course_code"];
			$sub_array[] = '<button type="button" name="view_faculty" class="btn btn-info btn-sm view_faculty" id="'.$row["faculty_id"].'">View</button>';
			$sub_array[] = '<button type="button" name="edit_faculty" class="btn btn-primary btn-sm edit_faculty" id="'.$row["faculty_id"].'">Edit</button>';
			$sub_array[] = '<button type="button" name="delete_faculty" class="btn btn-danger btn-sm delete_faculty" id="'.$row["faculty_id"].'">Delete</button>';
			
			$data[] = $sub_array;
		}
		
		// final output array
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connect, 'tbl_faculty'),
			"data"				=>	$data
		);

		// send data to ajax request
		echo json_encode($output);
	}


// -----------------------------------------------------------------------------------------------------

// for add and edit
	if($_POST["action"] == 'Add' || $_POST["action"] == "Edit")
	{
		// declare variables
		$faculty_name = '';
		$faculty_address = '';
		$faculty_emailid = '';
		$faculty_password = '';
		$faculty_course_id = '';
		$faculty_qualification = '';
		$faculty_doj = '';

		$error_faculty_name = '';
		$error_faculty_address = '';
		$error_faculty_emailid = '';
		$error_faculty_password = '';
		$error_faculty_course_id = '';
		$error_faculty_qualification = '';
		$error_faculty_doj = '';

		$error = 0;

		// empty name
		if(empty($_POST["faculty_name"]))
		{
			$error_faculty_name = 'faculty Name is required';
			$error++;
		}
		else
		{
			$faculty_name = $_POST["faculty_name"];
		}

		// empty address
		if(empty($_POST["faculty_address"]))
		{
			$error_faculty_address = 'faculty Address is required';
			$error++;
		}
		else
		{
			$faculty_address = $_POST["faculty_address"];
		}

			// empty emailid
			if(empty($_POST["faculty_emailid"]))
			{
				$error_faculty_emailid = 'Email Address is required';
				$error++;
			}
			else
			{
				// check email format
				if(!filter_var($_POST["faculty_emailid"], FILTER_VALIDATE_EMAIL))
				{
					$error_faculty_emailid = 'Invalid email format';
					$error++;
				}
				else
				{
					$faculty_emailid = $_POST["faculty_emailid"];
				}
			}

		if($_POST["action"] == "Add")
		{	
			// empty password
			if(empty($_POST["faculty_password"]))
			{
				$error_faculty_password = "Password is required";
				$error++;
			}
			else
			{
				$faculty_password = $_POST["faculty_password"];
			}
		}

		// empty course
		if(empty($_POST["faculty_course_id"]))
		{
			$error_faculty_course_id = "Course is required";
			$error++;
		}
		else
		{
			$faculty_course_id = $_POST["faculty_course_id"];
		}

		// empty qualification
		if(empty($_POST["faculty_qualification"]))
		{
			$error_faculty_qualification = 'Qualification Field is required';
			$error++;
		}
		else
		{
			$faculty_qualification = $_POST["faculty_qualification"];
		}

		// empty dae of joining
		if(empty($_POST["faculty_doj"]))
		{
			$error_faculty_doj = 'Date of Join Field is required';
			$error++;
		}
		else
		{
			$faculty_doj = $_POST["faculty_doj"];
		}

		// any validation error
		if($error > 0)
		{
			// output array
			$output = array(
				'error'							=>	true,
				'error_faculty_name'			=>	$error_faculty_name,
				'error_faculty_address'			=>	$error_faculty_address,
				'error_faculty_emailid'			=>	$error_faculty_emailid,
				'error_faculty_password'		=>	$error_faculty_password,
				'error_faculty_course_id'		=>	$error_faculty_course_id,
				'error_faculty_qualification'	=>	$error_faculty_qualification,
				'error_faculty_doj'				=>	$error_faculty_doj,
			);
		}
		// no error
		else
		{
			// add faculty
			if($_POST["action"] == 'Add')
			{

			// find if faculty is present already or not for that course 
			$query1 = '
			SELECT faculty_emailid FROM tbl_faculty 
			WHERE faculty_course_id = "'.$faculty_course_id.'" 
			';
			
			$statement1 = $connect->prepare($query1);
			$statement1->execute();
			
			//data already present
			if($statement1->rowCount() > 0)
			{
				$output = array(
					'error'							=>	true,
					'error_faculty_course_id'		=>	'Faculty already exists of this course'
				);
			}
			else 	// add this data
			{

				// data array
				$data = array(
					':faculty_name'				=>	$faculty_name,
					':faculty_address'			=>	$faculty_address,
					':faculty_emailid'			=>	$faculty_emailid,
					':faculty_password'			=>	password_hash($faculty_password, PASSWORD_DEFAULT),
					':faculty_qualification'	=>	$faculty_qualification,
					':faculty_doj'				=>	$faculty_doj,
					':faculty_course_id'		=>	$faculty_course_id
				);
		
				// insert query
				$query = "
				INSERT INTO tbl_faculty 
				(faculty_name, faculty_address, faculty_emailid, faculty_password, faculty_qualification, faculty_doj, faculty_course_id) 
				SELECT * FROM (SELECT :faculty_name, :faculty_address, :faculty_emailid, :faculty_password, :faculty_qualification, :faculty_doj, :faculty_course_id) as temp 
				WHERE NOT EXISTS (
					SELECT faculty_emailid FROM tbl_faculty 
					WHERE faculty_emailid = :faculty_emailid
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
							'error_faculty_emailid'	=>	'Email Already Exists'
						);
					}
				}
			}
			}

// -----------------------------------------------------------------------------------------------------

			// edit faculty
			if($_POST["action"] == "Edit")
			{

			// find if faculty is present already or not for that course 
			$query1 = '
			SELECT faculty_emailid FROM tbl_faculty 
			WHERE faculty_course_id = "'.$faculty_course_id.'" 
			AND faculty_id <> "'.$_POST["faculty_id"].'" 
			';
			
			$statement1 = $connect->prepare($query1);
			$statement1->execute();
			
			//data already present
			if($statement1->rowCount() > 0)
			{
				$output = array(
					'error'							=>	true,
					'error_faculty_course_id'		=>	'Faculty already exists of this course'
				);
			}
			else 	// add this data
			{

			// find if faculty is present already or not for that course 
			$query2 = '
			SELECT faculty_emailid FROM tbl_faculty 
			WHERE faculty_emailid = "'.$faculty_emailid.'" 
			AND faculty_id <> "'.$_POST["faculty_id"].'" 
			';
			
			$statement2 = $connect->prepare($query2);
			$statement2->execute();
			
			//data already present
			if($statement2->rowCount() > 0)
			{
				$output = array(
					'error'							=>	true,
					'error_faculty_emailid'		=>	'Faculty emailid already exists'
				);
			}
			else 	// add this data
			{

				// data array
				$data = array(
					':faculty_name'				=>	$faculty_name,
					':faculty_address'			=>	$faculty_address,
					':faculty_emailid'			=>	$faculty_emailid,
					':faculty_qualification'	=>	$faculty_qualification,
					':faculty_doj'				=>	$faculty_doj,
					':faculty_course_id'		=>	$faculty_course_id,
					':faculty_id'				=>	$_POST["faculty_id"]
				);

				// update query
				$query = "
				UPDATE tbl_faculty 
				SET faculty_name = :faculty_name, 
				faculty_address = :faculty_address,
				faculty_emailid = :faculty_emailid,  
				faculty_course_id = :faculty_course_id, 
				faculty_qualification = :faculty_qualification, 
				faculty_doj = :faculty_doj
				WHERE faculty_id = :faculty_id
				";

				$statement = $connect->prepare($query);
				
				if($statement->execute($data))
				{
					// data edited
					$output = array(
						'success'		=>	'Data Edited Successfully',
					);
				}
			}

			}
			}
		}

		// send final output 
		echo json_encode($output);
	}

// ------------------------------------------------------------------------------------------------------

// for viewing faculty data
	if($_POST["action"] == "single_fetch")
	{
		// select query
		$query = "
		SELECT * FROM tbl_faculty 
		INNER JOIN tbl_course 
		ON tbl_course.course_id = tbl_faculty.faculty_course_id 
		WHERE tbl_faculty.faculty_id = '".$_POST["faculty_id"]."'";
		
		$statement = $connect->prepare($query);
		
		if($statement->execute())
		{
			$result = $statement->fetchAll();
			$output = '
			<div class="row">
			';
		
			// store in html format
			foreach($result as $row)
			{
				$output .= '
		
				<div class="col-md-9">
					<table class="table">
						<tr>
							<th>Name</th>
							<td>'.$row["faculty_name"].'</td>
						</tr>
						<tr>
							<th>Address</th>
							<td>'.$row["faculty_address"].'</td>
						</tr>
						<tr>
							<th>Email Address</th>
							<td>'.$row["faculty_emailid"].'</td>
						</tr>
						<tr>
							<th>Qualification</th>
							<td>'.$row["faculty_qualification"].'</td>
						</tr>
						<tr>
							<th>Date of Joining</th>
							<td>'.$row["faculty_doj"].'</td>
						</tr>
						<tr>
							<th>course</th>
							<td>'.$row["course_code"].'</td>
						</tr>
					</table>
				</div>
				';
			}
		
			$output .= '</div>';
			echo $output;
		}
	}

// --------------------------------------------------------------------------------------------------

// before editing data, fech existing data
	if($_POST["action"] == "edit_fetch")
	{
		// select query
		$query = "
		SELECT * FROM tbl_faculty WHERE faculty_id = '".$_POST["faculty_id"]."'
		";
		
		$statement = $connect->prepare($query);
		
		if($statement->execute())
		{
			$result = $statement->fetchAll();
		
			// for matched row
			foreach($result as $row)
			{
		
				$output["faculty_name"] = $row["faculty_name"];
				$output["faculty_address"] = $row["faculty_address"];
				$output["faculty_emailid"] = $row["faculty_emailid"];
				$output["faculty_qualification"] = $row["faculty_qualification"];
				$output["faculty_doj"] = $row["faculty_doj"];
				$output["faculty_course_id"] = $row["faculty_course_id"];
				$output["faculty_id"] = $row["faculty_id"];
			}
		
			echo json_encode($output);
		}
	}

// -------------------------------------------------------------------------------------------------

// for deleting faculty
	if($_POST["action"] == "delete")
	{
		// delete query
		$query = "
		DELETE FROM tbl_faculty 
		WHERE faculty_id = '".$_POST["faculty_id"]."'
		";

		$statement = $connect->prepare($query);

		if($statement->execute())
		{
			// data deleted
			echo 'Data Deleted Successfully';
		}

	}

}

?>