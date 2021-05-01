<!-- comments add krni hai aabhi iss file mein -->

<?php

//report.php

// call for forming report
if(isset($_GET["action"]))
{
	// include database
	include('database_connection.php');

	require_once 'pdf.php';
	
	session_start();
	
	$output = '';
	
	if($_GET["action"] == 'attendance_report')
	{
		// for course report
		if(isset($_GET["course_id"], $_GET["from_date"], $_GET["to_date"]))
		{

			$pdf = new Pdf();
			
			// select query
			$query = "
			SELECT tbl_attendance.attendance_date FROM tbl_attendance 
			INNER JOIN tbl_student 
			ON tbl_student.student_id = tbl_attendance.student_id 
			WHERE tbl_student.student_course_id = '".$_GET["course_id"]."' 
			AND (tbl_attendance.attendance_date BETWEEN '".$_GET["from_date"]."' AND '".$_GET["to_date"]."')
			GROUP BY tbl_attendance.attendance_date 
			ORDER BY tbl_attendance.attendance_date ASC
			";
			
			$statement = $connect->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll();
			
			// output variable
			$output .= '
				<style>
				@page { margin: 20px; }
				
				</style>
				<p>&nbsp;</p>
				<h3 align="center">Attendance Report</h3><br />';
			
			// for each matched row
			foreach($result as $row)
			{
				$output .= '
				<table width="100%" border="0" cellpadding="5" cellspacing="0">
			        <tr>
			        	<td><b>Date - '.$row["attendance_date"].'</b></td>
			        </tr>
			        <tr>
			        	<td>
			        		<table width="100%" border="1" cellpadding="5" cellspacing="0">
			        			<tr>
			        				<td><b>Student Name</b></td>
			        				<td><b>Roll Number</b></td>
			        				<td><b>Course</b></td>
			        				<td><b>faculty</b></td>
			        				<td><b>Attendance Status</b></td>
			        			</tr>
				';
			
				$sub_query = "
				SELECT * FROM tbl_attendance 
			    INNER JOIN tbl_student 
			    ON tbl_student.student_id = tbl_attendance.student_id 
			    INNER JOIN tbl_course 
			    ON tbl_course.course_id = tbl_student.student_course_id 
			    INNER JOIN tbl_faculty 
			    ON tbl_faculty.faculty_course_id = tbl_course.course_id 
			    WHERE tbl_student.student_course_id = '".$_GET["course_id"]."' 
				AND tbl_attendance.attendance_date = '".$row["attendance_date"]."'
				";

				$statement = $connect->prepare($sub_query);
				$statement->execute();
				$sub_result = $statement->fetchAll();
			
				foreach($sub_result as $sub_row)
				{
					$output .= '
					<tr>
						<td>'.$sub_row["student_name"].'</td>
						<td>'.$sub_row["student_roll_number"].'</td>
						<td>'.$sub_row["course_name"].'</td>
						<td>'.$sub_row["faculty_name"].'</td>
						<td>'.$sub_row["attendance_status"].'</td>
					</tr>
					';
				}
			
				$output .= 
					'</table>
					</td>
					</tr>
				</table><br />';
			}
			
			$file_name = 'Attendance Report.pdf';
			$pdf->loadHtml($output);
			$pdf->render();
			$pdf->stream($file_name, array("Attachment" => false));
			
			exit(0);
		}
	}

// ---------------------------------------------------------------------------------------------------------

// for student report
	if($_GET["action"] == "student_report")
	{

		if(isset($_GET["student_id"], $_GET["from_date"], $_GET["to_date"]))
		{

			$pdf = new Pdf();
			
			$query = "
			SELECT * FROM tbl_student 
			INNER JOIN tbl_course 
			ON tbl_course.course_id = tbl_student.student_course_id 
			WHERE tbl_student.student_id = '".$_GET["student_id"]."' 
			";
			
			$statement = $connect->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll();
			
			foreach($result as $row)
			{
			
				$output .= '
				<style>
				@page { margin: 20px; }
				
				</style>
				<p>&nbsp;</p>
				<h3 align="center">Attendance Report</h3><br /><br />
				<table width="100%" border="0" cellpadding="5" cellspacing="0">
			        <tr>
			            <td width="25%"><b>Student Name</b></td>
			            <td width="75%">'.$row["student_name"].'</td>
			        </tr>
			        <tr>
			            <td width="25%"><b>Roll Number</b></td>
			            <td width="75%">'.$row["student_roll_number"].'</td>
			        </tr>
			        <tr>
			            <td width="25%"><b>Course</b></td>
			            <td width="75%">'.$row["course_name"].'</td>
			        </tr>
			        <tr>
			        	<td colspan="2" height="5">
			        		<h3 align="center">Attendance Details</h3>
			        	</td>
			        </tr>
			        <tr>
			        	<td colspan="2">
			        		<table width="100%" border="1" cellpadding="5" cellspacing="0">
			        			<tr>
			        				<td><b>Attendance Date</b></td>
			        				<td><b>Attendance Status</b></td>
			        			</tr>
				';
			
				$sub_query = "
				SELECT * FROM tbl_attendance 
				WHERE student_id = '".$_GET["student_id"]."' 
				AND (attendance_date BETWEEN '".$_GET["from_date"]."' AND '".$_GET["to_date"]."') 
				ORDER BY attendance_date ASC
				";

				$statement = $connect->prepare($sub_query);
				$statement->execute();
				$sub_result = $statement->fetchAll();
			
				foreach($sub_result as $sub_row)
				{
					$output .= '
					<tr>
						<td>'.$sub_row["attendance_date"].'</td>
						<td>'.$sub_row["attendance_status"].'</td>
					</tr>
					';
				}

				$output .= '
						</table>
					</td>
					</tr>
				</table>
				';

				$file_name = "Attendance Report.pdf";
				$pdf->loadHtml($output);
				$pdf->render();
				$pdf->stream($file_name, array("Attachment" => false));

				exit(0);
			}
		}
	}
}

?>