<?php

//attendance.php

// include header file
include('header.php');

?>


<div class="container" style="margin-top:30px">
  <div class="card">

    <!-- heading -->
    <div class="card-header">
      <div class="row">
        <div class="col-md-9">Attendance List</div>
      </div>
    </div>
  
    <div class="card-body">
  		<div class="table-responsive">
        <span id="message_operation"></span>
  
        <table class="table table-striped table-bordered" id="attendance_table">
          <thead>

          <!-- table headings -->
            <tr>
              <th>Faculty Name</th>
              <th>Course Code</th>
              <th>Attendance Status</th>
              <th>Attendance Date</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>

  		</div>
  	</div>
  </div>
</div>

<!-- ------------------------------------------------------------------------------------------------------- -->

<?php

  // find course from id
  $query = "
  SELECT * FROM tbl_course WHERE course_id = (SELECT student_course_id FROM tbl_student 
    WHERE student_id = '".$_SESSION["student_id"]."')
  ";

  // execute query
  $statement = $connect->prepare($query);
  $statement->execute();
  $result = $statement->fetchAll();

?>

<!-- -------------------------------------------------------------------------------------------------------- -->

<script>

$(document).ready(function(){
	
  // call attendance_action.php to retrieve data
  var dataTable = $('#attendance_table').DataTable({
    
    "processing":true,
    "serverSide":true,
    "order":[],

    "ajax":{
      url:"attendance_action.php",
      method:"POST",
      data:
      {
        action:"fetch"
      }
    }

  });

});
</script>