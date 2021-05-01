
<?php

//defaulter.php

//  include header file 
include('header.php');

?>

<!-- main page of defaulter   -->
<div class="container" style="margin-top:30px">
  <div class="card">
  	
    <div class="card-header">
      <div class="row">
        <div class="col-md-9">Defaulter's Student Attendance Status</div>
      </div>
    </div>

  	<div class="card-body">
  		<div class="table-responsive">
    
        <table class="table table-striped table-bordered" id="student_table">
          <thead>
            <tr>
              <th>Student Name</th>
              <th>Roll Number</th>
              <th>Course</th>
              <th>Faculty</th>
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

<!-- --------------------------------------------------------------------------------------------------- -->

<script>
$(document).ready(function(){
	 
   // call defaulter action file
   var dataTable = $('#student_table').DataTable(
    {
    "processing":true,
    "serverSide":true,
    "order":[],
    "ajax":{
      url:"defaulter_action.php",
      type:"POST",
      data:
      {
        action:'index_fetch'
      }
    }
   });


});

</script>