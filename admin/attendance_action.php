<?php

//student_action.php

// connect database
include('database_connection.php');

// start session
session_start();

// if action is set as post - to fetch data
if(isset($_POST["action"]))
{

	// for attendance page  
	if($_POST["action"] == "fetch")
	{
	
		$query = "
		SELECT * FROM tbl_attendance 
		INNER JOIN tbl_student 
		ON tbl_student.student_id = tbl_attendance.student_id 
		INNER JOIN tbl_course 
		ON tbl_course.course_id = tbl_student.student_course_id 
		INNER JOIN tbl_faculty 
		ON tbl_faculty.faculty_id = tbl_attendance.faculty_id 
		";
	
		// to search values accordingly
		if(isset($_POST["search"]["value"]))
		{
			$query .= '
				WHERE tbl_student.student_name LIKE "%'.$_POST["search"]["value"].'%" 
				OR tbl_student.student_roll_number LIKE "%'.$_POST["search"]["value"].'%" 
				OR tbl_attendance.attendance_status LIKE "%'.$_POST["search"]["value"].'%" 
				OR tbl_attendance.attendance_date LIKE "%'.$_POST["search"]["value"].'%" 
				OR tbl_faculty.faculty_name LIKE "%'.$_POST["search"]["value"].'%" 
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
			ORDER BY tbl_attendance.attendance_id 
			';
		}

		if($_POST["length"] != -1)
		{
			$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

		// execute and fetch data
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$data = array();			// store all in this 
		$filtered_rows = $statement->rowCount();

		// for each row store data in subarray
		foreach($result as $row)
		{
		
			$sub_array = array();
			$status = '';			// store attendance status
		
			if($row["attendance_status"] == "Present")
			{
				$status = '<label class="badge badge-success">Present</label>';
			}
		
			if($row["attendance_status"] == "Absent")
			{
				$status = '<label class="badge badge-danger">Absent</label>';
			}
		
			// storing data in subarray
			$sub_array[] = $row["student_roll_number"];
			$sub_array[] = $row["student_name"];
			$sub_array[] = $row["course_code"];
			$sub_array[] = $status;
			$sub_array[] = $row["attendance_date"];
			$sub_array[] = $row["faculty_name"];
			$sub_array[] = '<button type="button" name="edit_attendance" class="btn btn-primary btn-sm edit_attendance" id="'.$row["attendance_id"].'">Edit</button>';
			$sub_array[] = '<button type="button" name="delete_attendance" class="btn btn-danger btn-sm delete_attendance" id="'.$row["attendance_id"].'">Delete</button>';

			$data[] = $sub_array;
		}

		// final output data
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connect, 'tbl_attendance'),
			"data"				=>	$data
		);

		// return output in json format
		echo json_encode($output);
	}

// ----------------------------------------------------------------------------------------------------------

	// from index.html file for main page data
	if($_POST["action"] == "index_fetch")
	{

		// select from database
		$query = "
		SELECT * FROM tbl_student 
		LEFT JOIN tbl_attendance 
		ON tbl_attendance.student_id = tbl_student.student_id 
		INNER JOIN tbl_course 
		ON tbl_course.course_id = tbl_student.student_course_id 
		INNER JOIN tbl_faculty 
		ON tbl_faculty.faculty_course_id = tbl_course.course_id  
		";
		
		// for searching values
		if(isset($_POST["search"]["value"]))
		{
			$query .= '
			WHERE tbl_student.student_name LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_student.student_roll_number LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_course.course_code LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_faculty.faculty_name LIKE "%'.$_POST["search"]["value"].'%" 
			';
		}
		
		$query .= 'GROUP BY tbl_student.student_id ';
		
		// for ordering rows
		if(isset($_POST["order"]))
		{
			$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$query .= 'ORDER BY tbl_student.student_name ASC ';
		}

		if($_POST["length"] != -1)
		{
			$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

		// execute and fetch matched rows from query
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$data = array();			// store final data
		$filtered_rows = $statement->rowCount();		// count matched rows
		
		// for each matched row
		foreach($result as $row)
		{
			// store data in subarray
			$sub_array = array();
			$sub_array[] = $row["student_name"];
			$sub_array[] = $row["student_roll_number"];
			$sub_array[] = $row["course_code"];
			$sub_array[] = $row["faculty_name"];
			$sub_array[] = get_attendance_percentage($connect, $row["student_id"]);		// cal percentage
			$sub_array[] = '<button type="button" name="report_button" data-student_id="'.$row["student_id"].'" class="btn btn-info btn-sm report_button">Report</button>&nbsp;&nbsp;&nbsp;<button type="button" name="chart_button" data-student_id="'.$row["student_id"].'" class="btn btn-danger btn-sm report_button">Chart</button>
			';
			
			$data[] = $sub_array;
		}

		// final output data
		$output = array(
			'draw'				=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connect, 'tbl_student'),
			"data"				=>	$data
		);

		// return data in json format
		echo json_encode($output);
	}

// ----------------------------------------------------------------------------------------------------
	// delete attendance
	if($_POST["action"] == "delete")
	{
		// delete query
		$query = "
		DELETE FROM tbl_attendance 
		WHERE attendance_id = '".$_POST["attendance_id"]."'
		";

		$statement = $connect->prepare($query);
	
		// data deleted
		if($statement->execute())
		{
			echo 'Data Deleted Successfully';
		}
	}	

// ---------------------------------------------------------------------------------------------------
	
	// for add and edit action 
	if($_POST["action"] == 'Add' || $_POST["action"] == "Edit")
	{
		// variables 
		$student_id = '';
		$faculty_id = '';
		$attendance_date = '';
		$attendance_status = '';

		$error_student_id = '';
		$error_faculty_id = '';
		$error_attendance_date = '';
		$error_attendance_status = '';

		$error = 0;

		// student id empty
		if(empty($_POST["student_id"]))
		{
			$error_student_id = 'Student ID is required';
			$error++;
		}
		else
		{
			$student_id = $_POST["student_id"];
		}
		
		// faculty id empty
		if(empty($_POST["faculty_id"]))
		{
			$error_faculty_id = 'Faculty id is required';
			$error++;
		}
		else
		{
			$faculty_id = $_POST["faculty_id"];
		}

		// attendance date empty
		if(empty($_POST["attendance_date"]))
		{
			$error_attendance_date = 'Attendance date is required';
			$error++;
		}
		else
		{
			$attendance_date = $_POST["attendance_date"];
		}

		// attendance status empty
		if(empty($_POST["attendance_status"]))
		{
			$error_attendance_status = 'Attendance status is required';
			$error++;
		}
		else
		{
				// check attendance status
				if($_POST["attendance_status"] == "Present" or $_POST["attendance_status"]== "Absent")
				{
					$attendance_status = $_POST["attendance_status"];
				}
				else
				{
					$error_attendance_status = 'Invalid attendance status';
					$error++;
				}
		}
		
		// if any validation error
		if($error > 0)
		{
			// output array
			$output = array(
				'error'								=>	true,
				'error_student_id'					=>	$error_student_id,
				'error_faculty_id'					=>	$error_faculty_id,
				'error_attendance_date'				=>	$error_attendance_date,
				'error_attendance_status'			=>	$error_attendance_status
			);
		}
		else
		{
		// for adding student
		if($_POST["action"] == 'Add')
		{
			
		// select query
			$query1 = "
			SELECT * FROM tbl_student 
			WHERE student_id =  '".$_POST["student_id"]."'
			";
		
			$statement1 = $connect->prepare($query1);
			$statement1->execute();
			
			//data already present

			if($statement1->rowCount() > 0 )
			{

				$query2 = "
				SELECT * FROM tbl_student
				INNER JOIN tbl_faculty
				ON tbl_faculty.course_id = tbl_student.course_id 
				WHERE student_id =  '".$_POST["student_id"]."'
				";

				$data = array(
					':student_id'			=>	$student_id,
					':faculty_id'			=>	$faculty_id,
					':attendance_date'		=>	$attendance_date,
					':attendance_status'	=>	$attendance_status
				);
	
				$query = "
				INSERT INTO tbl_attendance 
				(student_id, attendance_status, attendance_date, faculty_id) 
				SELECT * FROM (SELECT :student_id, :attendance_status, :attendance_date, :faculty_id) as temp 
				WHERE NOT EXISTS (
				SELECT * FROM tbl_attendance WHERE attendance_date = :attendance_date
				AND student_id = :student_id
				) LIMIT 1
				";


				$statement = $connect->prepare($query);
				
				if($statement->execute($data)){
					// data added
					$output = array(
						'success'		=>	'Data Added Successfully'
					);	

				}
				else{
					$output = array(
						'error'							=>	true,
						'error_attendance_status'		=>	'Attendance already exists of this student'
					);
	
				}		
			// }

			}
			else 	// add this data
			{
				$output = array(
					'error'							=>	true,
					'error_student_id'				=>	'Invalid student'
				);

			}
			}
			
		}
		// send data in json format to ajax request
		echo json_encode($output);
	}

}

?>