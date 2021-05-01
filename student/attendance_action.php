<?php

//attendance_action.php

include('../admin/database_connection.php');

session_start();


if(isset($_POST["action"]))
{
	// call from attendance.php
	if($_POST["action"] == "fetch")
	{

		// retrieve data from database
		$query = "
		SELECT * FROM tbl_attendance 
		INNER JOIN tbl_student 
		ON tbl_student.student_id = tbl_attendance.student_id 
		INNER JOIN tbl_course 
		ON tbl_course.course_id = tbl_student.student_course_id
		INNER JOIN tbl_faculty 
		ON tbl_faculty.faculty_id = tbl_attendance.faculty_id  
		WHERE tbl_attendance.student_id = '".$_SESSION["student_id"]."' AND (
		";

		// for searching 
		if(isset($_POST["search"]["value"]))
		{
			$query .= '
			tbl_faculty.faculty_name LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_attendance.attendance_status LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_attendance.attendance_date LIKE "%'.$_POST["search"]["value"].'%") 
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
			ORDER BY tbl_attendance.attendance_id 
			';
		}

		if($_POST["length"] != -1)
		{
			$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

		// execute query and fetch matched rows
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$data = array();
		$filtered_rows = $statement->rowCount();
		
		// for each matched row
		foreach($result as $row)
		{
			$sub_array = array();
			$status = '';
		
			// for present 
			if($row["attendance_status"] == "Present")
			{
				$status = '<label class="badge badge-success">Present</label>';
			}

			// for absent
			if($row["attendance_status"] == "Absent")
			{
				$status = '<label class="badge badge-danger">Absent</label>';
			}

			// put data in sub-array
			$sub_array[] = $row["faculty_name"];
			$sub_array[] = $row["course_code"];
			$sub_array[] = $status;
			$sub_array[] = $row["attendance_date"];
			
			// put sub-array in array
			$data[] = $sub_array;
		}

		// output that array
		$output = array(
			'draw'				=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connect, 'tbl_attendance'),
			"data"				=>	$data
		);

		// return output in json format to attendance.php
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
		INNER JOIN tbl_faculty 
		ON tbl_faculty.faculty_course_id = tbl_course.course_id 
		WHERE tbl_attendance.student_id = '".$_SESSION["student_id"]."' AND (
		";

		// for searching
		if(isset($_POST["search"]["value"]))
		{
			$query .= '
			 tbl_course.course_name LIKE "%'.$_POST["search"]["value"].'%" )
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

		// fetch rows by executing query
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$data = array();
		$filtered_rows = $statement->rowCount();

		// for each row add in array
		foreach($result as $row)
		{
			// add in sub array
			$sub_array = array();
			$sub_array[] = $row["faculty_name"];
			$sub_array[] = $row["course_name"];
			$sub_array[] = get_attendance_percentage($connect, $row["student_id"]);
			$sub_array[] = '<button type="button" name="report_button" id="'.$row["student_id"].'" class="btn btn-info btn-sm report_button">Report</button>';
			
			// add subarray in data
			$data[] = $sub_array;
		}
		
		// output array
		$output = array(
			'draw'				=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connect, 'tbl_student'),
			"data"				=>	$data
		);

		// return output in json format
		echo json_encode($output);

	}
}

?>