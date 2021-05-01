<?php

//course_action.php

// include database
include('../admin/database_connection.php');

session_start();

if(isset($_POST["action"]))
{

	// for main page of course list
	if($_POST["action"] == "fetch")
	{

		// select query
		$query = "
		SELECT * FROM tbl_course 
		";

		// for searching
		if(isset($_POST["search"]["value"]))
		{
			$query .= '
			WHERE tbl_course.course_name LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_course.course_code LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_course.course_semester LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_course.course_credit LIKE "%'.$_POST["search"]["value"].'%" 
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
			ORDER BY tbl_course.course_code
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
			// add data in subarray
			$sub_array = array();
			$sub_array[] = $row["course_code"];
			$sub_array[] = $row["course_name"];
			$sub_array[] = $row["course_semester"];
			$sub_array[] = $row["course_credit"];
			$sub_array[] = '<button type="button" name="edit_course" class="btn btn-primary btn-sm edit_course" id="'.$row["course_id"].'">Edit</button>';
			$sub_array[] = '<button type="button" name="delete_course" class="btn btn-danger btn-sm delete_course" id="'.$row["course_id"].'">Delete</button>';
			$data[] = $sub_array;
		}
		
		// store in output
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connect, 'tbl_course'),
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
		$course_name = '';
		$course_code = '';
		$course_semester = '';
		$course_credit = '';

		$error_course_name = '';
		$error_course_code = '';
		$error_course_semester = '';
		$error_course_credit = '';

		$error = 0;
		
		// code empty
		if(empty($_POST["course_code"]))
		{
			$error_course_code = 'Code is required';
			$error++;
		}
		else
		{
			$course_code = $_POST["course_code"];
		}
		
		// course name empty
		if(empty($_POST["course_name"]))
		{
			$error_course_name = 'Name is required';
			$error++;
		}
		else
		{
			$course_name = $_POST["course_name"];
		}

		// semester empty
		if(empty($_POST["course_semester"]))
		{
			$error_course_semester = 'Semester is required';
			$error++;
		}
		else
		{
			$course_semester = $_POST["course_semester"];
		}
		
		// credit empty
		if(empty($_POST["course_credit"]))
		{
			$error_course_credit = 'Credit is required';
			$error++;
		}
		else
		{
			$course_credit = $_POST["course_credit"];
		}

		// if any validation error
		if($error > 0)
		{
			// output array
			$output = array(
				'error'						=>	true,
				'error_course_code'			=>	$error_course_code,
				'error_course_name'			=>	$error_course_name,
				'error_course_semester'		=>	$error_course_semester,
				'error_course_credit'		=>	$error_course_credit
			);
		}
		else
		{

			// for adding course
			if($_POST["action"] == 'Add')
			{
				$data = array(
					':course_code'			=>	$course_code,
					':course_name'			=>	$course_name,
					':course_semester'		=>	$course_semester,
					':course_credit'		=>	$course_credit
				);
			
			
				// insert query
				$query = "
				INSERT INTO tbl_course 
				(course_code, course_name, course_semester, course_credit) 
				SELECT * FROM (SELECT :course_code, :course_name, :course_semester, :course_credit) as temp 
				WHERE NOT EXISTS (
					SELECT course_code FROM tbl_course WHERE course_code = :course_code
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
							'error_course_code'		=>	'Course Code Already Exists'
						);
					}
				}

			}
			
// ---------------------------------------------------------------------------------------------------------
			
			// for editting course
			if($_POST["action"] == "Edit")
			{

				// if username exists or not
				$query1 = '
				SELECT course_id FROM tbl_course 
				WHERE course_code = "'.$course_code.'" 
				AND course_id <> "'.$_POST["course_id"].'" 
				';
	
				// execute and connect query
				$statement1 = $connect->prepare($query1);
				$statement1->execute();
				
				//data already present
				if($statement1->rowCount() > 0 )
				{
					$output = array(
						'error'							=>	true,
						'error_course_code'				=>	'Course Code already exists '
					);
				}
				else 	// update this data
				{
					// data array
					$data = array(
						':course_code'			=>	$course_code,
						':course_name'			=>	$course_name,	
						':course_semester'		=>	$course_semester,
						':course_credit'		=>	$course_credit,
						':course_id'			=>	$_POST["course_id"]
					);

					// update query
					$query = "
					UPDATE tbl_course 
					SET course_code = :course_code, 
					course_name = :course_name, 
					course_semester = :course_semester, 
					course_credit = :course_credit
					WHERE course_id = :course_id
					";

					// connect and execute query
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
		SELECT * FROM tbl_course 
		WHERE course_id = '".$_POST["course_id"]."'
		";
		
		// connect and execute query
		$statement = $connect->prepare($query);
	
		if($statement->execute())
		{
			$result = $statement->fetchAll();
			
			// for each matched row
			foreach($result as $row)
			{
				$output["course_code"] = $row["course_code"];
				$output["course_name"] = $row["course_name"];
				$output["course_semester"] = $row["course_semester"];
				$output["course_credit"] = $row["course_credit"];
				$output["course_id"] = $row["course_id"];
			}
			// return output to ajax request
			echo json_encode($output);
		}
	}

// -------------------------------------------------------------------------------------------------------

// delete course
	if($_POST["action"] == "delete")
	{
		// delete query
		$query = "
		DELETE FROM tbl_course 
		WHERE course_id = '".$_POST["course_id"]."'
		";

		$statement = $connect->prepare($query);
		
		// data deleted
		if($statement->execute())
		{
			echo 'Data Delete Successfully';
		}
	}

// ------------------------------------------------------------------------------------------------------
}
?>