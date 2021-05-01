<?php

//student_action.php

// include database
include('../admin/database_connection.php');

session_start();

if(isset($_POST["action"]))
{

	// for main page of student list
	if($_POST["action"] == "fetch")
	{

		// select query
		$query = "
		SELECT * FROM tbl_student 
		INNER JOIN tbl_course 
		ON tbl_course.course_id = tbl_student.student_course_id 
		";

		// for searching
		if(isset($_POST["search"]["value"]))
		{
			$query .= '
			WHERE tbl_student.student_name LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_student.student_roll_number LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_student.student_dob LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_course.course_code LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_student.student_emailid LIKE "%'.$_POST["search"]["value"].'%" 
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
			ORDER BY tbl_student.student_id
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
			$sub_array[] = $row["student_id"];
			$sub_array[] = $row["student_roll_number"];
			$sub_array[] = $row["student_name"];
			$sub_array[] = $row["student_dob"];
			$sub_array[] = $row["course_code"];
			$sub_array[] = $row["student_emailid"];
			$sub_array[] = '<button type="button" name="edit_student" class="btn btn-primary btn-sm edit_student" id="'.$row["student_id"].'">Edit</button>';
			$sub_array[] = '<button type="button" name="delete_student" class="btn btn-danger btn-sm delete_student" id="'.$row["student_id"].'">Delete</button>';
			$data[] = $sub_array;
		}
		
		// store in output
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connect, 'tbl_student'),
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
		$student_name = '';
		$student_roll_number = '';
		$student_dob = '';
		$student_course_id = '';
		$student_emailid = '';
		$student_password = '';

		$error_student_name = '';
		$error_student_roll_number = '';
		$error_student_dob = '';
		$error_student_course_id = '';
		$error_student_emailid = '';
		$error_student_password = '';

		$error = 0;

		// student name empty
		if(empty($_POST["student_name"]))
		{
			$error_student_name = 'Student Name is required';
			$error++;
		}
		else
		{
			$student_name = $_POST["student_name"];
		}
		
		// roll number empty
		if(empty($_POST["student_roll_number"]))
		{
			$error_student_roll_number = 'Student Roll Number is required';
			$error++;
		}
		else
		{
			$student_roll_number = $_POST["student_roll_number"];
		}
		
		// date of birth empty
		if(empty($_POST["student_dob"]))
		{
			$error_student_dob = 'Student Date of Birth is required';
			$error++;
		}
		else
		{
			$student_dob = $_POST["student_dob"];
		}
		
		// course id empty
		if(empty($_POST["student_course_id"]))
		{
			$error_student_course_id = "Course is required";
			$error++;
		}
		else
		{
			$student_course_id = $_POST["student_course_id"];
		}
		
		// email id empty
		if(empty($_POST["student_emailid"]))
		{
			$error_student_emailid = 'Student Email ID is required';
			$error++;
		}
		else
		{
				// check email format
				if(!filter_var($_POST["student_emailid"], FILTER_VALIDATE_EMAIL))
				{
					$error_student_emailid = 'Invalid email format';
					$error++;
				}
				else
				{
					$student_emailid = $_POST["student_emailid"];
				}
		}

		if($_POST["action"] == 'Add')
		{
				// password empty
			if(empty($_POST["student_password"]))
			{
				$error_student_password = 'Student Password is required';
				$error++;
			}
			else
			{
				$student_password = $_POST["student_password"];
			}
		}
		
		// if any validation error
		if($error > 0)
		{
			// output array
			$output = array(
				'error'							=>	true,
				'error_student_name'			=>	$error_student_name,
				'error_student_roll_number'		=>	$error_student_roll_number,
				'error_student_dob'				=>	$error_student_dob,
				'error_student_emailid'			=>	$error_student_emailid,
				'error_student_password'		=>	$error_student_password,
				'error_student_course_id'		=>	$error_student_course_id
			);
		}
		else
		{
		// for adding student
		if($_POST["action"] == 'Add')
		{

			// find if student is present already or not for that course 
			$query1 = '
			SELECT student_roll_number FROM tbl_student 
			WHERE student_course_id = "'.$student_course_id.'" 
			AND student_roll_number = "'.$student_roll_number.'"
			';
			
			$statement1 = $connect->prepare($query1);
			$statement1->execute();
			
			//data already present

			if($statement1->rowCount() > 0 )
			{
				$output = array(
					'error'							=>	true,
					'error_student_roll_number'		=>	'Roll number already exists of this course'
				);
			}
			else 	// add this data
			{

			
				$data = array(
					':student_name'			=>	$student_name,
					':student_roll_number'	=>	$student_roll_number,
					':student_dob'			=>	$student_dob,
					':student_emailid'		=>	$student_emailid,
					':student_password'		=>	password_hash($student_password, PASSWORD_DEFAULT),
					':student_course_id'	=>	$student_course_id
					);
			
				// checking for same emailid
				$query = "
				INSERT INTO tbl_student 
				(student_name, student_roll_number, student_dob, student_course_id, student_emailid, student_password) 
				SELECT * FROM (SELECT :student_name, :student_roll_number, :student_dob, :student_course_id, :student_emailid, :student_password) as temp 
				WHERE NOT EXISTS (
					SELECT student_emailid FROM tbl_student WHERE student_emailid = :student_emailid
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
							'error_student_emailid'	=>	'Email Already Exists'
						);
					}
				}

			}

			}
		}	
// ---------------------------------------------------------------------------------------------------------
			
			// for editting student
			if($_POST["action"] == "Edit")
			{
			
			// find if student is present already or not for that course 
			$query1 = '
			SELECT student_roll_number FROM tbl_student 
			WHERE student_course_id = "'.$student_course_id.'" ,
			student_roll_number = "'.$student_roll_number.'"
			AND student_id <> "'.$_POST["student_id"].'" 
			';
			
			$statement1 = $connect->prepare($query1);
			$statement1->execute();
			
			//data already present

			if($statement1->rowCount() > 0 )
			{
				$output = array(
					'error'							=>	true,
					'error_student_roll_number'		=>	'Roll number already exists of this course'
				);
			}
			else 	// add this data
			{

			// find if student is present already or not for that course 
			$query2 = '
			SELECT student_emailid FROM tbl_student 
			WHERE student_emailid = "'.$student_emailid.'" 
			AND student_id <> "'.$_POST["student_id"].'" 
			';
			
			$statement2 = $connect->prepare($query2);
			$statement2->execute();
			
			//data already present
			if($statement2->rowCount() > 0)
			{
				$output = array(
					'error'							=>	true,
					'error_student_emailid'			=>	'student emailid already exists'
				);
			}
			else 	// add this data
			{

				// data array
				$data = array(
					':student_name'			=>	$student_name,	
					':student_roll_number'	=>	$student_roll_number,
					':student_dob'			=>	$student_dob,
					':student_emailid'		=>	$student_emailid,
					':student_course_id'	=>	$student_course_id,
					':student_id'			=>	$_POST["student_id"]
				);

				// update query
				$query = "
				UPDATE tbl_student 
				SET student_name = :student_name, 
				student_roll_number = :student_roll_number, 
				student_dob = :student_dob, 
				student_emailid = :student_emailid, 
				student_course_id = :student_course_id 
				WHERE student_id = :student_id
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

		// send data in json format to ajax request
		echo json_encode($output);
	}


// -------------------------------------------------------------------------------------------------------

// before editing, fetch existing data
	if($_POST["action"] == "edit_fetch")
	{

		// select query
		$query = "
		SELECT * FROM tbl_student 
		WHERE student_id = '".$_POST["student_id"]."'
		";
		
		$statement = $connect->prepare($query);
	
		if($statement->execute())
		{
			$result = $statement->fetchAll();
			
			// for each matched row
			foreach($result as $row)
			{
				$output["student_name"] = $row["student_name"];
				$output["student_roll_number"] = $row["student_roll_number"];
				$output["student_dob"] = $row["student_dob"];
				$output["student_emailid"] = $row["student_emailid"];
				$output["student_course_id"] = $row["student_course_id"];
				$output["student_id"] = $row["student_id"];
			}
			// return output to ajax request
			echo json_encode($output);
		}
	}

// -------------------------------------------------------------------------------------------------------

// delete student
	if($_POST["action"] == "delete")
	{
		// delete query
		$query = "
		DELETE FROM tbl_student 
		WHERE student_id = '".$_POST["student_id"]."'
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