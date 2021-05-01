<?php

//attendance_action.php

// connect database
include('../admin/database_connection.php');

session_start();

if(isset($_POST["action"]))
{

	// edit attendance
	if($_POST["action"] == "edit")
	{
			$attendance_status = "";
			
			$query1 = "
			SELECT attendance_status from tbl_attendance
			WHERE attendance_id = '".$_POST["attendance_id"]."'
			";

			$statement1 = $connect->prepare($query1);
			$statement1->execute();
			$result = $statement1->fetchAll();
			$data = array();
			$filtered_rows = $statement1->rowCount();

			// for each row 
			foreach($result as $row)
			{
	
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

// --------------------------------------------------------------------------------------------------------


}


?>