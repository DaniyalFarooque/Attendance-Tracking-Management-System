<?php

//report.php

if(isset($_GET["action"]))
{
	// connect database
	include('../admin/database_connection.php');
	
	require_once '../admin/pdf.php';
	session_start();
	
	// call from attendance.php
	if($_GET["action"] == "attendance_report")
	{
		// from and to date are present
		if(isset($_GET["from_date"], $_GET["to_date"]))
		{
			// create pdf
			$pdf = new Pdf();

			// retrieve data
			$query = "
			SELECT attendance_date FROM tbl_attendance 
			WHERE faculty_id = '".$_SESSION["faculty_id"]."' 
			AND (attendance_date BETWEEN '".$_GET["from_date"]."' AND '".$_GET["to_date"]."')
			GROUP BY attendance_date 
			ORDER BY attendance_date ASC
			";

			// execute and fetch
			$statement = $connect->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll();

			// stores final output heading
			$output = '
				<style>
				@page { margin: 20px; }
				
				</style>
				<p>&nbsp;</p>
				<h3 align="center">Attendance Report</h3><br />';
			
			// for each matched row
			foreach($result as $row)
			{
				// add data in output
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
			        				<td><b>Course Code</b></td>
			        				<td><b>Attendance Status</b></td>
			        			</tr>
				';

				// select query
				$sub_query = "
				SELECT * FROM tbl_attendance 
			    INNER JOIN tbl_student 
			    ON tbl_student.student_id = tbl_attendance.student_id 
			    INNER JOIN tbl_course 
			    ON tbl_course.course_id = tbl_student.student_course_id 
			    WHERE faculty_id = '".$_SESSION["faculty_id"]."' 
				AND attendance_date = '".$row["attendance_date"]."'
				";
				
				// execute query
				$statement = $connect->prepare($sub_query);
				$statement->execute();
				$sub_result = $statement->fetchAll();
				
				// fetch result
				foreach($sub_result as $sub_row)
				{
					$output .= '
					<tr>
						<td>'.$sub_row["student_name"].'</td>
						<td>'.$sub_row["student_roll_number"].'</td>
						<td>'.$sub_row["course_code"].'</td>
						<td>'.$sub_row["attendance_status"].'</td>
					</tr>
					';
				}
				
				$output .= '
					</table>
					</td>
					</tr>
				</table><br />
				';
			}

			// file name 
			$file_name = 'Attendance Report.pdf';
			
			// load html 
			$pdf->loadHtml($output);
			$pdf->render();
			$pdf->stream($file_name, array("Attachment" => false));
			
			exit(0);
		}
	}

//-----------------------------------------------------------------------------------------------------

// cal from index.php for student report 
	if($_GET["action"] == "student_report")
	{

		// student id, from and to date are present
		if(isset($_GET["student_id"], $_GET["from_date"], $_GET["to_date"]))
		{

			$pdf = new Pdf();
			
			// select query
			$query = "
			SELECT * FROM tbl_student 
			INNER JOIN tbl_course 
			ON tbl_course.course_id = tbl_student.student_course_id 
			WHERE tbl_student.student_id = '".$_GET["student_id"]."' 
			";

			// execute query
			$statement = $connect->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll();
			$output = '';
			
			// find results
			foreach($result as $row)
			{
				// store data in output
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
			            <td width="25%"><b>Course Code</b></td>
			            <td width="75%">'.$row["course_code"].'</td>
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
				
				// sub query
				$sub_query = "
				SELECT * FROM tbl_attendance 
				WHERE student_id = '".$_GET["student_id"]."' 
				AND (attendance_date BETWEEN '".$_GET["from_date"]."' AND '".$_GET["to_date"]."') 
				ORDER BY attendance_date ASC
				";
			
				$statement = $connect->prepare($sub_query);
				$statement->execute();
				$sub_result = $statement->fetchAll();
			
				// add in output
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

				// report created
				$file_name = 'Attendance Report.pdf';
				$pdf->loadHtml($output);
				$pdf->render();
				$pdf->stream($file_name, array("Attachment" => false));

				exit(0);
			}
		}
	}
}


?>