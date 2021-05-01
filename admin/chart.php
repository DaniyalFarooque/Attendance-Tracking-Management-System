<?php

//chart.php
// for creating chart of a student from a particular date - to a particular date

// include header file
include('header.php');

// declare variables
$present_percentage = 0;
$absent_percentage = 0;
$total_present = 0;
$total_absent = 0;
$output = "";

// select query
$query = "
SELECT * FROM tbl_attendance 
WHERE student_id = '".$_GET['student_id']."' 
AND attendance_date >= '".$_GET["from_date"]."' 
AND attendance_date <= '".$_GET["to_date"]."'
";

// execute and fetch query
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$total_row = $statement->rowCount();

// for each matched row
foreach($result as $row)
{
	// find the status of the student attendance - present or absent
	$status = '';

	if($row["attendance_status"] == "Present")
	{
		$total_present++;
		$status = '<span class="badge badge-success">Present</span>';
	}

	if($row["attendance_status"] == "Absent")
	{
		$total_absent++;
		$status = '<span class="badge badge-danger">Absent</span>';
	}

	// output variable
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

  <!-- heading -->
  	<div class="card-header"><b>Attendance Chart</b></div>
  	<div class="card-body">
      <div class="table-responsive">
        
        <table class="table table-bordered table-striped">
        
		  <tr>
            <th>Student Name</th>
            <td><?php echo Get_student_name($connect, $_GET["student_id"]); ?></td>
          </tr>
        
		  <tr>
            <th>Course</th>
            <td><?php echo Get_student_course_name($connect, $_GET["student_id"]); ?></td>
          </tr>
        
		  <tr>
            <th>faculty Name</th>
            <td><?php echo Get_student_faculty_name($connect, $_GET["student_id"]); ?></td>
          </tr>
        
		  <tr>
            <th>Time Period</th>
            <td><?php echo $_GET["from_date"] . ' to '. $_GET["to_date"]; ?></td>
          </tr>
        
		</table>

        <div id="attendance_pie_chart" style="width: 100%; height: 400px;">
			<!-- create pie chart -->
        </div>

		<!-- create table -->
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

<!-- ------------------------------------------------------------------------------------------------------ -->

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
	
	google.charts.load('current', {'packages':['corechart']});
	google.charts.setOnLoadCallback(drawChart);

// function to draw chart
	function drawChart()
	{
		// visualize status and percentage in pie chart
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

		var chart = new google.visualization.PieChart(document.getElementById('attendance_pie_chart'));

		// draw chart using data and options
		chart.draw(data, options);
	}

</script>