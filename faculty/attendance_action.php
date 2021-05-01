<?php

//attendance_action.php

// connect database
include('../admin/database_connection.php');

session_start();

if(isset($_POST["action"]))
{
	// call from attendance page
	if($_POST["action"] == "fetch")
	{
		// retrieve data from database
		$query = "
		SELECT * FROM tbl_attendance 
		INNER JOIN tbl_student 
		ON tbl_student.student_id = tbl_attendance.student_id 
		INNER JOIN tbl_course 
		ON tbl_course.course_id = tbl_student.student_course_id 
		WHERE tbl_attendance.faculty_id = '".$_SESSION["faculty_id"]."' AND (
		";

		// for searching
		if(isset($_POST["search"]["value"]))
		{
			$query .= '
			tbl_student.student_name LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_student.student_roll_number LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_attendance.attendance_status LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_attendance.attendance_date LIKE "%'.$_POST["search"]["value"].'%") 
			';
		}

		// for ordering
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

		// execute and fetch matched rows
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$data = array();
		$filtered_rows = $statement->rowCount();

		// for each row 
		foreach($result as $row)
		{
			// put data in sub array
			$sub_array = array();
			$status = '';

			// for present
			if($row["attendance_status"] == "Present")
			{
				$status = '<label class="badge badge-success">Present</label>';
			}

			// fo absent
			if($row["attendance_status"] == "Absent")
			{
				$status = '<label class="badge badge-danger">Absent</label>';
			}

			// add all in order wise of columns
			$sub_array[] = $row["student_roll_number"];
			$sub_array[] = $row["student_name"];
			$sub_array[] = $row["course_name"];
			$sub_array[] = $status;
			$sub_array[] = $row["attendance_date"];
			$sub_array[] = '<button type="button" name="edit_attendance" class="btn btn-primary btn-sm edit_attendance" id="'.$row["attendance_id"].'">Edit</button>';
			$sub_array[] = '<button type="button" name="delete_attendance" class="btn btn-danger btn-sm delete_attendance" id="'.$row["attendance_id"].'">Delete</button>';


			// add subarray in array
			$data[] = $sub_array;
		}

		// output array
		$output = array(
			'draw'				=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connect, 'tbl_attendance'),
			"data"				=>	$data
		);

		// send output in json format
		echo json_encode($output);
	}


//---------------------------------------------------------------------------------------------------------

// from attendance - add 
	if($_POST["action"] == "Add")
	{
		// variables
		$attendance_date = '';
		$error_attendance_date = '';
		
		$error = 0;
		
		// if date is empty
		if(empty($_POST["attendance_date"]))
		{
			$error_attendance_date = 'Attendance Date is required';
			$error++;
		}
		else
		{
			$attendance_date = $_POST["attendance_date"];
		}

		// error is present
		if($error > 0)
		{
			$output = array(
				'error'							=>	true,
				'error_attendance_date'			=>	$error_attendance_date
			);
		}
		else		// no error
		{

			$student_id = $_POST["student_id"];

			// find if data is present already or not for that date 
			$query = '
			SELECT attendance_date FROM tbl_attendance 
			WHERE faculty_id = "'.$_SESSION["faculty_id"].'" 
			AND attendance_date = "'.$attendance_date.'"
			';
			
			$statement = $connect->prepare($query);
			$statement->execute();
			
			// data already present
			if($statement->rowCount() > 0)
			{
				$output = array(
					'error'					=>	true,
					'error_attendance_date'	=>	'Attendance Data Already Exists on this date'
				);
			}
			else 	// add this data
			{
				for($count = 0; $count < count($student_id); $count++)
				{
					// extract input 
					$data = array(
						':student_id'			=>	$student_id[$count],
						':attendance_status'	=>	$_POST["attendance_status".$student_id[$count].""],
						':attendance_date'		=>	$attendance_date,
						':faculty_id'			=>	$_SESSION["faculty_id"]
					);

					// insert in dataabse
					$query = "
					INSERT INTO tbl_attendance 
					(student_id, attendance_status, attendance_date, faculty_id) 
					VALUES (:student_id, :attendance_status, :attendance_date, :faculty_id)
					";

					$statement = $connect->prepare($query);
					$statement->execute($data);
				}
				// data is added
				$output = array(
					'success'		=>	'Data Added Successfully',
				);
			}
		}
		// return in json format
		echo json_encode($output);
	}


// ---------------------------------------------------------------------------------------------------------

// call from index.php
	if($_POST["action"] == "index_fetch")
	{
		// retrieve data
		$query = "
		SELECT * FROM tbl_attendance 
		INNER JOIN tbl_student 
		ON tbl_student.student_id = tbl_attendance.student_id 
		INNER JOIN tbl_course 
		ON tbl_course.course_id = tbl_student.student_course_id 
		WHERE tbl_attendance.faculty_id = '".$_SESSION["faculty_id"]."' AND (
		";

		// for searching
		if(isset($_POST["search"]["value"]))
		{
			$query .= '
			tbl_student.student_name LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_student.student_roll_number LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_course.course_name LIKE "%'.$_POST["search"]["value"].'%" )
			';
		}

		$query .= 'GROUP BY tbl_student.student_id ';
		
		// for ordering
		if(isset($_POST["order"]))
		{
			$query .= '
			ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' 
			';
		}
		else
		{
			$query .= '
			ORDER BY tbl_student.student_roll_number ASC 
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
			// put data in subarray
			$sub_array = array();
			$sub_array[] = $row["student_name"];
			$sub_array[] = $row["student_roll_number"];
			$sub_array[] = $row["course_name"];
			
			// cal percentage
			$sub_array[] = get_attendance_percentage($connect, $row["student_id"]);
			$sub_array[] = '<button type="button" name="report_button" id="'.$row["student_id"].'" class="btn btn-info btn-sm report_button">Report</button>';
			
			//  put subarray in data
			$data[] = $sub_array;
		}

		// final output
		$output = array(
			'draw'				=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connect, 'tbl_student'),
			"data"				=>	$data
		);

		// send data in json format 
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

// --------------------------------------------------------------------------------------------------------

	// edit attendance
	if($_POST["action"] == "edit")
	{
			$attendance_status = "";
			
			// check for current status
			$query1 = "
			SELECT attendance_status from tbl_attendance
			WHERE attendance_id = '".$_POST["attendance_id"]."'
			";

			// execute query
			$statement1 = $connect->prepare($query1);
			$statement1->execute();
			$result = $statement1->fetchAll();
			$data = array();
			$filtered_rows = $statement1->rowCount();

			// for each row 
			foreach($result as $row)
			{
				// change status
				// for present
				if($row["attendance_status"] == "Present")
				{
					$attendance_status = "Absent";
				}

				// fo absent
				if($row["attendance_status"] == "Absent")
				{
					$attendance_status = "Present";
				}
			}
			// data array
        	$data = array(
        		':attendance_status'		=>	$attendance_status,
        		':attendance_id'			=>	$_POST["attendance_id"]
        	);

			// update query
			$query2 = "
			UPDATE tbl_attendance
			SET attendance_status = :attendance_status 
			WHERE attendance_id = :attendance_id
			";

			$statement2 = $connect->prepare($query2);
			$statement2->execute($data);

			echo 'Data Updated Successfully';

		}	

}

?>