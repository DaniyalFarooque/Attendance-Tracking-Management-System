<?php

//chart1.php

// include header file
include('header.php');

// declare variables
$present_percentage = 0;
$absent_percentage = 0;
$total_present = 0;
$total_absent = 0;
$output = "";

// reterieve data
$query = "
SELECT * FROM tbl_attendance 
INNER JOIN tbl_student  
ON tbl_student.student_id = tbl_attendance.student_id 
INNER JOIN tbl_course 
ON tbl_course.course_id = tbl_student.student_course_id 
WHERE tbl_student.student_course_id = '".$_GET['course_id']."' 
AND tbl_attendance.attendance_date = '".$_GET["date"]."'
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
  
  // store in output 
	$output .= '
		<tr>
			<td>'.$row["student_name"].'</td>
			<td>'.$status.'</td>
		</tr>
	';
}

// calculate percentage
if($total_row > 0)
{
	$present_percentage = ($total_present / $total_row) * 100;
	$absent_percentage = ($total_absent / $total_row) * 100;
}

?>

<!-- -------------------------------------------------------------------------------------------------------- -->

<div class="container" style="margin-top:30px">
  <div class="card">
  	<div class="card-header"><b>Attendance Chart</b></div>
  	<div class="card-body">
      <div class="table-responsive">

        <table class="table table-bordered table-striped">
        
          <tr>
            <th>Course Name</th>
            <td><?php echo Get_course_name($connect, $_GET["course_id"]); ?></td>
          </tr>
          
          <tr>
            <th>Date</th>
            <td><?php echo $_GET["date"]; ?></td>
          </tr>
        
        </table>
      </div>

  		<div id="attendance_pie_chart" style="width: 100%; height: 400px;">
  		</div>

  		<div class="table-responsive">
        <table class="table table-striped table-bordered">
      
          <tr>
            <th>Student Name</th>
            <th>Attendance Status</th>
          </tr>
        
          <?php 
          echo $output;
          ?>

      </table></div>
  	</div>
  </div>
</div>

</body>
</html>

<!-- -------------------------------------------------------------------------------------------------------- -->

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

  // load packages
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
      title: 'Overall Attendance Analytics',
      hAxis: {
        title: 'Percentage',
        minValue: 0,
        maxValue: 100
      },
      vAxis: {
        title: 'Attendance Status'
      }
    };

    // draw chart
    var chart = new google.visualization.PieChart(document.getElementById('attendance_pie_chart'));
    chart.draw(data, options);
  }
</script>