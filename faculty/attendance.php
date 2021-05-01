<?php

//attendance.php

// inclue header nav bar
include('header.php');

?>

<div class="container" style="margin-top:30px">
  <div class="card">

  	<div class="card-header">
      <div class="row">
        <div class="col-md-9">Attendance List</div>
  
        <div class="col-md-3" align="right">
          <button type="button" id="report_button" class="btn btn-danger btn-sm">Report</button>
          <button type="button" id="add_button" class="btn btn-info btn-sm">Add</button>
        </div>
  
      </div>
    </div>

  	<div class="card-body">
  		<div class="table-responsive">
        <span id="message_operation"></span>
    
        <table class="table table-striped table-bordered" id="attendance_table">
          <thead>
            <tr>
              <th>Roll Number</th>
              <th>Student Name</th>
              <th>Course</th>
              <th>Attendance Status</th>
              <th>Attendance Date</th>
              <th>Edit</th>
              <th>Delete</th>
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

<script type="text/javascript" src="../js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="../css/datepicker.css" />

<style>
    .datepicker
    {
      z-index: 1600 !important; /* has to be larger than 1050 */
    }
</style>

<?php

// find course of faculty
$query = "
SELECT * FROM tbl_course WHERE course_id = (SELECT faculty_course_id FROM tbl_faculty 
    WHERE faculty_id = '".$_SESSION["faculty_id"]."')
";

$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();

?>

<!-- ------------------------------------------------------------------------------------------------------- -->
<!-- add attendance form-->

<div class="modal" id="formModal">
  <div class="modal-dialog">
    <form method="post" id="attendance_form">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title" id="modal_title"></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">

          <?php
          foreach($result as $row)
          {
          ?>

          <div class="form-group">
            <div class="row">

              <label class="col-md-4 text-right">Course <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <?php
                echo '<label>'.$row["course_name"].'</label>';
                ?>
              </div>

            </div>
          </div>

          <div class="form-group">
            <div class="row">

              <label class="col-md-4 text-right">Attendance Date <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="text" name="attendance_date" id="attendance_date" class="form-control" readonly />
                <span id="error_attendance_date" class="text-danger"></span>
              </div>

            </div>
          </div>

          <div class="form-group" id="student_details">
            <div class="table-responsive">

               <!-- options in form of table -->
              <table class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>Roll No.</th>
                    <th>Student Name</th>
                    <th>Present</th>
                    <th>Absent</th>
                  </tr>
                </thead>

                <?php
                // displays list of all students enrolled in that course
                $sub_query = "
                  SELECT * FROM tbl_student 
                  WHERE student_course_id = '".$row["course_id"]."'
                ";

                $statement = $connect->prepare($sub_query);
                $statement->execute();
                $student_result = $statement->fetchAll();
                
                // display for each student
                foreach($student_result as $student)
                {
                ?>
                  <tr>
                    <td><?php echo $student["student_roll_number"]; ?></td>
                    <td>
                      <?php echo $student["student_name"]; ?>
                      <input type="hidden" name="student_id[]" value="<?php echo $student["student_id"]; ?>" />
                    </td>
                    <td>
                      <input type="radio" name="attendance_status<?php echo $student["student_id"]; ?>" value="Present" />
                    </td>
                    <td>
                    <!-- by default absent -->
                      <input type="radio" name="attendance_status<?php echo $student["student_id"]; ?>" checked value="Absent" />
                    </td>
                  </tr>
                <?php
                }
                ?>
              </table>

            </div>
          </div>

          <?php
          }
          ?>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          <input type="hidden" name="action" id="action" value="Add" />
          <input type="submit" name="button_action" id="button_action" class="btn btn-success btn-sm" value="Add" />
          <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
        </div>

      </div>
    </form>
  </div>
</div>

<!-- ---------------------------------------------------------------------------------------------------- -->

 <!-- form for delete -->

 <div class="modal" id="deleteModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Delete Confirmation</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <h3 align="center">Are you sure you want to remove this?</h3>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" name="ok_button" id="ok_button" class="btn btn-primary btn-sm">OK</button>
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<!-- ---------------------------------------------------------------------------------------------------- -->
<script type="text/javascript" src="../js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="../css/datepicker.css" />

<style>
    .datepicker {
      z-index: 1600 !important; /* has to be larger than 1050 */
    }
</style>

<!-- -------------------------------------------------------------------------------------------- -->
 <!-- form for edit -->

 <div class="modal" id="editModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Edit Confirmation</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <h3 align="center">Are you sure you want to edit?</h3>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" name="oka_button" id="oka_button" class="btn btn-primary btn-sm">OK</button>
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<!-- ---------------------------------------------------------------------------------------------------- -->
<script type="text/javascript" src="../js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="../css/datepicker.css" />

<style>
    .datepicker {
      z-index: 1600 !important; /* has to be larger than 1050 */
    }
</style>

<!-- ------------------------------------------------------------------------------------------------------- -->

<!-- report form -->
<div class="modal" id="reportModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Make Report</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div class="form-group">
          <div class="input-daterange">
            <input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date" readonly />
            <span id="error_from_date" class="text-danger"></span>
            <br />
            <input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" readonly />
            <span id="error_to_date" class="text-danger"></span>
          </div>
        </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" name="create_report" id="create_report" class="btn btn-success btn-sm">Create Report</button>
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<!-- --------------------------------------------------------------------------------------------------------- -->

<script>
$(document).ready(function(){
	
  var dataTable = $('#attendance_table').DataTable({
    
    // call attendance_action.php
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

// for taking date as input in form
  $('#attendance_date').datepicker({
    format:'yyyy-mm-dd',
    autoclose:true,
    container: '#formModal modal-body'
  });

//-----------------------------------------------------------------------------------------------

// clear form details
  function clear_field()
  {
    $('#attendance_form')[0].reset();
    $('#error_attendance_date').text('');
  }

// on clicking add button
  $('#add_button').click(function(){
    $('#modal_title').text("Add Attendance");
    $('#formModal').modal('show');
    clear_field();
  });

// on submitting form for add
  $('#attendance_form').on('submit', function(event){
    
    event.preventDefault();
    
    // call atendance_action
    $.ajax({
      url:"attendance_action.php",
      method:"POST",
      data:$(this).serialize(),
      dataType:"json",

      // before going, disable submit button
      beforeSend:function(){
        $('#button_action').val('Validate...');
        $('#button_action').attr('disabled', 'disabled');
      },

      // on success 
      success:function(data)
      {
        $('#button_action').attr('disabled', false);
        $('#button_action').val($('#action').val());
        
        // process successful
        if(data.success)
        {
          $('#message_operation').html('<div class="alert alert-success">'+data.success+'</div>');
          clear_field();
          $('#formModal').modal('hide');
          dataTable.ajax.reload();
        }
        
        // error in data
        if(data.error)
        {
          if(data.error_attendance_date != '')
          {
            $('#error_attendance_date').text(data.error_attendance_date);
          }
          else
          {
            $('#error_attendance_date').text('');
          }
        }
      }
    })
  });

// ---------------------------------------------------------------------------------------------------
// edit attendance
$(document).on('click', '.edit_attendance', function(){
    attendance_id = $(this).attr('id');
    $('#editModal').modal('show');
  });

// confirmed updation
  $('#oka_button').click(function(){
    $.ajax({
      url:"attendance_action.php",
      method:"POST",
      data:
      {
        attendance_id:attendance_id, 
        action:"edit"
      },
      
      success:function(data)
      {
        $('#message_operation').html('<div class="alert alert-success">'+data+'</div>');
        $('#editModal').modal('hide');
        dataTable.ajax.reload();
      }
      
    })
  });


//--------------------------------------------------------------------------------------------------

// taking date as input
  $('.input-daterange').datepicker({
    todayBtn:"linked",
    format:"yyyy-mm-dd",
    autoclose:true,
    container: '#formModal modal-body'
  });

// on clicking report button
  $(document).on('click', '#report_button', function(){
    $('#reportModal').modal('show');
  });

// create report
  $('#create_report').click(function(){
    
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    
    var error = 0;
    
    // from date is empty
    if(from_date == '')
    {
      $('#error_from_date').text('From Date is Required');
      error++;
    }
    else
    {
      $('#error_from_date').text('');
    }

    // to date is empty
    if(to_date == '')
    {
      $('#error_to_date').text("To Date is Required");
      error++;
    }
    else
    {
      $('#error_to_date').text('');
    }

    // no error
    if(error == 0)
    {

      $('#from_date').val('');
      $('#to_date').val('');
      $('#formModal').modal('hide');
      
      window.open("report.php?action=attendance_report&from_date="+from_date+"&to_date="+to_date);
    
    }

  });

// ---------------------------------------------------------------------------------------------------
// delete attendance
$(document).on('click', '.delete_attendance', function(){
    attendance_id = $(this).attr('id');
    $('#deleteModal').modal('show');
  });

// confirmed deletion
  $('#ok_button').click(function(){
    $.ajax({
      url:"attendance_action.php",
      method:"POST",
      data:
      {
        attendance_id:attendance_id, 
        action:"delete"
      },
      
      success:function(data)
      {
        $('#message_operation').html('<div class="alert alert-success">'+data+'</div>');
        $('#deleteModal').modal('hide');
        dataTable.ajax.reload();
      }
      
    })
  });

});

</script>