
<?php

//student_action.php

// include database
include('database_connection.php');

session_start();

if(isset($_POST["action"]))
{

	// called from defaulter main page
	if($_POST["action"] == "index_fetch")
	{
		// select query
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
			OR tbl_course.course_name LIKE "%'.$_POST["search"]["value"].'%" 
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

		// execute and fetch rows
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$data = array();
        $filtered_rows = $statement->rowCount();
        $count = 0;
		


		// for each matched row
		foreach($result as $row)
		{
			$sub_array = array();
			$sub_array[] = $row["student_name"];
			$sub_array[] = $row["student_roll_number"];
			$sub_array[] = $row["course_code"];
			$sub_array[] = $row["faculty_name"];
			$sub_array[] = get_defatt_percentage($connect, $row["student_id"]);
			
			// if percentage is <75, then only add 
           if(get_defatt_percentage($connect, $row["student_id"]) === 'NULL'){}
           else
            {
                $data[] = $sub_array;
                $count += 1;
            }
		}

		// final output array
		$output = array(
			'draw'				=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connect, 'tbl_student'),
			"data"				=>	$data
		);

		// return output in json format to ajax request
		echo json_encode($output);
	}
}

?>


