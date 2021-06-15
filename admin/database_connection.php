<?php

	//database_connection.php

	// connect database
	
	// Development Database Connection
	// $host = "localhost";
	// $db = "attendance";
	// $user = "root";
	// $pass = "";
	
	$host = "remotemysql.com";
	$db = "YBnZN6PgeQ";
	$user = "YBnZN6PgeQ";
	$pass = "6aIbX19xmw";
	
	$connect = new PDO("mysql:host=$host;dbname=$db",$user,$pass);
	$base_url = $_SERVER['DOCUMENT_ROOT'];

	// count number of total rows
	function get_total_records($connect, $table_name)
	{
		$query = "SELECT * FROM $table_name";
		$statement = $connect->prepare($query);
		$statement->execute();
		return $statement->rowCount();
	}

	// ------------------------------------------------------------------------------------------------------

	// load course list
	function load_course_list($connect)
	{
		// select query
		$query = "
		SELECT * FROM tbl_course ORDER BY course_code ASC
		";

		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$output = '';
		
		foreach($result as $row)
		{
			$output .= '<option value="'.$row["course_id"].'">'.$row["course_code"].'</option>';
		}

		return $output;
	}

	// -------------------------------------------------------------------------------------------------------

	// calculate attendance percentage
	function get_attendance_percentage($connect, $student_id)
	{
		// select query
		$query = "
		SELECT 
			ROUND((SELECT COUNT(*) FROM tbl_attendance 
			WHERE attendance_status = 'Present' 
			AND student_id = '".$student_id."') 
		* 100 / COUNT(*)) AS percentage FROM tbl_attendance 
		WHERE student_id = '".$student_id."'
		";

		// execute and fetch
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		
		// for each row calculate percentage
		foreach($result as $row)
		{
			if($row["percentage"] > 0)
			{
				return $row["percentage"] . '%';
			}
			else
			{
				return 'NA';
			}
		}
	}

	// --------------------------------------------------------------------------------------------------------

	// calculate percentage for defaulters
	function get_defatt_percentage($connect, $student_id)
	{
		// select query
		$query = "
		SELECT 
			ROUND((SELECT COUNT(*) FROM tbl_attendance 
			WHERE attendance_status = 'Present' 
			AND student_id = '".$student_id."') 
		* 100 / COUNT(*)) AS percentage FROM tbl_attendance 
		WHERE student_id = '".$student_id."'
		";

		// execute and fetch
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		
		// for each row calculate percentage
		foreach($result as $row)
		{
			if($row["percentage"] >75)
			{
				return 'NULL';			
			}
			else if($row["percentage"] >0)
			{
				return $row["percentage"] . '%';
			}
			else
			{
				return 'NA';
			}
		}
	}

	// ------------------------------------------------------------------------------------------------------

	// get name of that student
	function Get_student_name($connect, $student_id)
	{
		// select query
		$query = "
		SELECT student_name FROM tbl_student 
		WHERE student_id = '".$student_id."'
		";

		// execute and fetch
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();

		foreach($result as $row)
		{
			return $row["student_name"];
		}
	}

	// -----------------------------------------------------------------------------------------------------

	// get course name from student id
	function Get_student_course_name($connect, $student_id)
	{
		// select query
		$query = "
		SELECT tbl_course.course_name FROM tbl_student 
		INNER JOIN tbl_course 
		ON tbl_course.course_id = tbl_student.student_course_id 
		WHERE tbl_student.student_id = '".$student_id."'
		";

		// execute and fecth
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		
		foreach($result as $row)
		{
			return $row['course_name'];
		}
	}

	// --------------------------------------------------------------------------------------------------

	// get faculty name from student id
	function Get_student_faculty_name($connect, $student_id)
	{
		// select query
		$query = "
		SELECT tbl_faculty.faculty_name 
		FROM tbl_student 
		INNER JOIN tbl_course 
		ON tbl_course.course_id = tbl_student.student_course_id 
		INNER JOIN tbl_faculty 
		ON tbl_faculty.faculty_course_id = tbl_course.course_id 
		WHERE tbl_student.student_id = '".$student_id."'
		";

		// fetch and execute
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		
		foreach($result as $row)
		{
			return $row["faculty_name"];
		}
	}

	// ------------------------------------------------------------------------------------------------------

	// get course name from course id
	function Get_course_name($connect, $course_id)
	{
		// select query
		$query = "
		SELECT course_name FROM tbl_course 
		WHERE course_id = '".$course_id."'
		";

		// execute and fetch
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		
		foreach($result as $row)
		{
			return $row["course_name"];
		}
	}

?>