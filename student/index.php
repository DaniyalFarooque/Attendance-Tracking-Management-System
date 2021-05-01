<?php

//index.php

// include header file
include('header.php');

?>

<!-- ----------------------------------------------------------------------------------------------------- -->

<div class="container" style="margin-top:30px">
  <div class="card">
    
    <div class="card-header">
      <div class="row">
        <div class="col-md-9">Overall Attendance Status</div>          
        </div>
      </div>
    </div>

  	<div class="card-body">
  		<div class="table-responsive">
        <table class="table table-striped table-bordered" id="student_table">
          <thead>

            <tr>
              <th>Faculty Name</th>
              <th>Course Code</th>
              <th>Attendance Percentage</th>
            </tr>
          
          </thead>
          <tbody>

          </tbody>
        </table>
  		</div>
  	</div>
  </div>
</div>

</body>
</html>

<!-- ---------------------------------------------------------------------------------------------------- -->

<script>
$(document).ready(function(){

	// call attendance_action.php for display of table
  var dataTable = $('#student_table').DataTable({
    
    "processing":true,
    "serverSide":true,
    "order":[],
    
    "ajax":{
      url:"attendance_action.php",
      type:"POST",
      data:
      {
        action:'index_fetch'
      }
    }
  
  });

});
</script>