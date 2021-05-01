<?php

//attendance.php

include('header.php');

?>

 <!-- main page for attendance -->
<div class="container" style="margin-top:30px">
  <div class="card">

  	<div class="card-header">
      <div class="row">
        <div class="col-md-9">Attendance List</div>

        <div class="col-md-3" align="right">        
          <button type="button" id="add_button" class="btn btn-info btn-sm">Add</button>
          <button type="button" id="chart_button" class="btn btn-primary btn-sm">Chart</button>
          <button type="button" id="report_button" class="btn btn-danger btn-sm">Report</button>
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
              <th>Faculty</th>
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


<!-- ---------------------------------------------------------------------------------------------------- -->
<script type="text/javascript" src="../js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="../css/datepicker.css" />

<style>
    .datepicker {
      z-index: 1600 !important; /* has to be larger than 1050 */
    }
</style>

<!-- -------------------------------------------------------------------------------------------- -->
 <!-- form for add, edit -->

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
        
        <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Student ID <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="text" name="student_id" id="student_id" class="form-control" />
                <span id="error_student_id" class="text-danger"></span>
              </div>
            </div>
          </div>
        
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Faculty ID <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="text" name="faculty_id" id="faculty_id" class="form-control" />
                <span id="error_faculty_id" class="text-danger"></span>
              </div>
            </div>
          </div>
        
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Attendance Date<span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="text" name="attendance_date" id="attendance_date" class="form-control" />
                <span id="error_attendance_date" class="text-danger"></span>
              </div>
            </div>
          </div>
        
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Attendance Status<span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="text" name="attendance_status" id="attendance_status" class="form-control" />
                <span id="error_attendance_status" class="text-danger"></span>
              </div>
            </div>
          </div>

        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
        	<input type="hidden" name="attendance_id" id="attendance_id" />
        	<input type="hidden" name="action" id="action" value="Add" />
        	<input type="submit" name="button_action" id="button_action" class="btn btn-success btn-sm" value="Add" />
          	<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
        </div>

      </div>
  </form>
  </div>
</div>


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


<!-- -------------------------------------------------------------------------------------------------- -->

<script type="text/javascript" src="../js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="../css/datepicker.css" />

<style>
    .datepicker
    {
      z-index: 1600 !important; /* has to be larger than 1050 */
    }
</style>

<!-- --------------------------------------------------------------------------------------------------------- -->

 <!-- form for making report -->
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
          <select name="course_id" id="course_id" class="form-control">
            <option value="">Select Course</option>
            <?php
                echo load_course_list($connect);
            ?>
          </select>
          <span id="error_course_id" class="text-danger"></span>
        </div>

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

<!-- form for making chart -->
<div class="modal" id="chartModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Create Course Attandance Chart</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        
        <div class="form-group">
          <select name="chart_course_id" id="chart_course_id" class="form-control">
            <option value="">Select Course</option>
            <?php
            echo load_course_list($connect);
            ?>
          </select>
          <span id="error_chart_course_id" class="text-danger"></span>
        </div>

        <div class="form-group">
          <div class="input-daterange">
            <input type="text" name="attendance_date" id="attendance_date" class="form-control" placeholder="Select Date" readonly />
            <span id="error_attendance_date" class="text-danger"></span>
          </div>
        </div>
      
      </div>
      
      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" name="create_chart" id="create_chart" class="btn btn-success btn-sm">Create Chart</button>
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<!-- ---------------------------------------------------------------------------------------------------------- -->


<script>
$(document).ready(function(){
	
  // for main page of attendance
  var dataTable = $('#attendance_table').DataTable(
  {
    "processing":true,
    "serverSide":true,
    "order":[],
    "ajax":{
      url:"attendance_action.php",
      type:"POST",
      data:
      {
        action:'fetch'
      }
    }
  });

// -------------------------------------------------------------------------------------------------

// for selecting date
$('#attendance_date').datepicker({
		format:"yyyy-mm-dd",
		autoclose: true,
        container: '#formModal modal-body'
	});

// for clearing form
	function clear_field()
	{
		$('#attendance_form')[0].reset();
    $('#error_student_id').text('');
		$('#error_faculty_id').text('');
		$('#error_attendance_date').text('');
		$('#error_attendance_status').text('');
	}

// clicked on add button
	$('#add_button').click(function(){

    $('#student_id').attr('disabled', false);
    $('#faculty_id').attr('disabled', false);

		$('#modal_title').text('Add Attendance');
		$('#button_action').val('Add');
		$('#action').val('Add');
		$('#formModal').modal('show');
		clear_field();
	});

// submitted details for adding attendance
	$('#attendance_form').on('submit', function(event){
		event.preventDefault();

		$.ajax({
			url:"attendance_action.php",
			method:"POST",
			data:$(this).serialize(),
			dataType:"json",

      // before sending request
			beforeSend:function(){
				$('#button_action').val('Validate...');
				$('#button_action').attr('disabled', 'disabled');
			},

			success:function(data)
			{
				$('#button_action').attr('disabled', false);
				$('#button_action').val($('#action').val());

        // data success
				if(data.success)
				{
					$('#message_operation').html('<div class="alert alert-success">'+data.success+'</div>');
					clear_field();
					$('#formModal').modal('hide');
					dataTable.ajax.reload();
				}

        // if error
				if(data.error)
				{
          // empty student id
					if(data.error_student_id != '')
					{
						$('#error_student_id').text(data.error_student_id);
					}
					else
					{
						$('#error_student_id').text('');
					}
          
          // empty faculty id
					if(data.error_faculty_id != '')
					{
						$('#error_faculty_id').text(data.error_faculty_id);
					}
					else
					{
						$('#error_faculty_id').text('');
					}
					
          // empty date of attendance
          if(data.error_attendance_date != '')
					{
						$('#error_attendance_date').text(data.error_attendance_date);
					}
					else
					{
						$('#error_attendance_date').text('');
					}
					
          // empty attendance status
          if(data.error_attendance_status != '')
					{
						$('#error_attendance_status').text(data.error_attendance_status);
					}
					else
					{
						$('#error_attendance_status').text('');
					}

				}
			}
		})
	});

// --------------------------------------------------------------------------------------------------------

// edit attendance
$(document).on('click', '.edit_attendance', function(){
    attendance_id = $(this).attr('id');
    $('#editModal').modal('show');
  });

// confirmed updation
  $('#oka_button').click(function(){
    $.ajax({
      url:"edit_action.php",
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



// --------------------------------------------------------------------------------------------------------

// for taking date as input in form
  $('.input-daterange').datepicker({
    todayBtn: "linked",
    format: "yyyy-mm-dd",
    autoclose: true,
    container: '#formModal modal-body'
  });

// on lcicking report button
  $(document).on('click', '#report_button', function(){
    $('#reportModal').modal('show');
  });


// create report
  $('#create_report').click(function()
  {

    // variables
    var course_id = $('#course_id').val();
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    var error = 0;

    // empty course id
    if(course_id == '') 
    {
      $('#error_course_id').text('Course is Required');
      error++;
    }
    else
    {
      $('#error_course_id').text('');
    }

    // empty from date
    if(from_date == '')
    {
      $('#error_from_date').text('From Date is Required');
      error++;
    }
    else
    {
      $('#error_from_date').text('');
    }

    // empty to date
    if(to_date == '')
    {
      $('#error_to_date').text("To Date is Required");
      error++;
    }
    else
    {
      $('#error_to_date').text('');
    }

    // if no error - open new window and show report - call report.php
    if(error == 0)
    {
      $('#from_date').val('');
      $('#to_date').val('');
      $('#formModal').modal('hide');
      window.open("report.php?action=attendance_report&course_id="+course_id+"&from_date="+from_date+"&to_date="+to_date);
    }

  });

// --------------------------------------------------------------------------------------------------------

// for chart button click
  $('#chart_button').click(function()
  {
    $('#chart_course_id').val('');
    $('#attendance_date').val('');
    $('#chartModal').modal('show');
  });

// create chart
  $('#create_chart').click(function()
  {
    // variables
    var course_id = $('#chart_course_id').val();
    var attendance_date = $('#attendance_date').val();
    var error = 0;
  
  // course id is empty
    if(course_id == '')
    {
      $('#error_chart_course_id').text('Course is Required');
      error++;
    }
    else
    {
      $('#error_chart_course_id').text('');
    }
  
  // date for which chart is to be created is empty
    if(attendance_date == '')
    {
      $('#error_attendance_date').text('Date is Required');
      $error++;
    }
    else
    {
      $('#error_attendance_date').text('');
    }

    // if no error - call chart1.php and create chart of that course on that date
    if(error == 0)
    {
      $('#attendance_date').val('');
      $('#chart_course_id').val('');
      $('#chartModal').modal('show');
      
      window.open("chart1.php?action=attendance_report&course_id="+course_id+"&date="+attendance_date);
    }

  });

// ----------------------------------------------------------------------------------------------------




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