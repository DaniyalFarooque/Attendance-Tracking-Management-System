<?php

//chart.php

// include header
include('header.php');

// declare variables
$present_percentage = 0;
$absent_percentage = 0;
$total_present = 0;
$total_absent = 0;

// initailize from and to date
$from_date = "2020-01-01";
$to_date = "2020-12-31";

$output = "";

// select data from database
$query = "
SELECT * FROM tbl_attendance 
WHERE student_id = '".$_SESSION['student_id']."' 
AND attendance_date >= '".$from_date."' 
AND attendance_date <= '".$to_date."'
";

// execute query
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$total_row = $statement->rowCount();

// for each matched row
foreach($result as $row)
{
	$status = '';

	// if present
	if($row["attendance_status"] == "Present")
	{
		$total_present++;
		$status = '<span class="badge badge-success">Present</span>';
	}

	// if absent
	if($row["attendance_status"] == "Absent")
	{
		$total_absent++;
		$status = '<span class="badge badge-danger">Absent</span>';
	}

	// store in output date and status
	$output .= '
		<tr>
			<td>'.$row["attendance_date"].'</td>
			<td>'.$status.'</td>
		</tr>
	';

	// calculate percentage
	$present_percentage = ($total_present/$total_row) * 100;
	$absent_percentage = ($total_absent/$total_row) * 100;

}

?>

<!-- --------------------------------------------------------------------------------------------------------- -->

<div class="container" style="margin-top:30px">
  <div class="card">
  	<div class="card-header"><b>Attendance Chart</b></div>
  	<div class="card-body">
      <div class="table-responsive">
		
	  <!-- table for details -->
        <table class="table table-bordered table-striped">
		  
		  <tr>
            <th>Student Name</th>
            <td><?php echo Get_student_name($connect, $_SESSION["student_id"]); ?></td>
          </tr>
		  
		  <tr>
            <th>Course</th>
            <td><?php echo Get_student_course_name($connect, $_SESSION["student_id"]); ?></td>
          </tr>
		 
		  <tr>
            <th>Faculty</th>
            <td><?php echo Get_student_faculty_name($connect, $_SESSION["student_id"]); ?></td>
          </tr>
		 
		  <tr>
            <th>Time Period</th>
            <td><?php echo $from_date . ' to '. $to_date ;?></td>
          </tr>
		
		</table>

        <div id="attendance_pie_chart" style="width: 100%; height: 400px;">

        </div>

        <div class="table-responsive">
        	<table class="table table-striped table-bordered">
		
		      <tr>
	            <th>Date</th>
	            <th>Attendance Status</th>
	          </tr>
			
			  <?php echo $output; ?>
	      </table>
        </div>
  		
      </div>
  	</div>
  </div>
</div>

</body>
</html>

<!-- ---------------------------------------------------------------------------------------------------------- -->

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">
	
	// load package
	google.charts.load('current', {'packages':['corechart']});

	google.charts.setOnLoadCallback(drawChart);

	// draw chart
	function drawChart()
	{
		var data = google.visualization.arrayToDataTable([
			['Attendance Status', 'Percentage'],
			['Present', <?php echo $present_percentage; ?>],
			['Absent', <?php echo $absent_percentage; ?>]
		]);

		var options = {
			title : 'Overall Attendance Analytics',
			hAxis : {
				title: 'Percentage',
		        minValue: 0,
		        maxValue: 100
			},
			vAxis : {
				title: 'Attendance Status'
			}
		};

		// initailize chart
		var chart = new google.visualization.PieChart(document.getElementById('attendance_pie_chart'));

		// draw chart
		chart.draw(data, options);
	}

</script>