<?php

// include header
include('header.php');

?>

<!-- ---------------------------------------------------------------------------------------------------- -->

<div class="container" style="margin-top:30px">
  <div class="card">
  	
    <div class="card-header">
      <div class="row">
        <div class="col-md-9">Faculty List</div>
        <div class="col-md-3" align="right">
          <button type="button" id="add_button" class="btn btn-info btn-sm">Add</button>
        </div>
      </div>
    </div>

  	<div class="card-body">
  		<div class="table-responsive">
        <span id="message_operation"></span>
  			<table class="table table-striped table-bordered" id="faculty_table">
  				<thead>
  	
    				<tr>
  						<th>ID</th>
  						<th>Name</th>
  						<th>Email Address</th>
              <th>Course</th>
  						<th>View</th>
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

<!-- ------------------------------------------------------------------------------------------------------- -->

<script type="text/javascript" src="../js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="../css/datepicker.css" />

<style>
    .datepicker {
      z-index: 1600 !important; /* has to be larger than 1050 */
    }
</style>

<!-- ------------------------------------------------------------------------------------------------------- -->

 <!-- form add or edit -->
<div class="modal" id="formModal">
  <div class="modal-dialog">
    <form method="post" id="faculty_form" enctype="multipart/form-data">
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
              <label class="col-md-4 text-right">Name <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="text" name="faculty_name" id="faculty_name" class="form-control" />
                <span id="error_faculty_name" class="text-danger"></span>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Address <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <textarea name="faculty_address" id="faculty_address" class="form-control"></textarea>
                <span id="error_faculty_address" class="text-danger"></span>
              </div>
            </div>
          </div>
          
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Email Address <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="text" name="faculty_emailid" id="faculty_emailid" class="form-control" />
                <span id="error_faculty_emailid" class="text-danger"></span>
              </div>
            </div>
          </div>
          
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Password <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="password" name="faculty_password" id="faculty_password" class="form-control" />
                <span id="error_faculty_password" class="text-danger"></span>
              </div>
            </div>
          </div>
          
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Qualification <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="text" name="faculty_qualification" id="faculty_qualification" class="form-control" />
                <span id="error_faculty_qualification" class="text-danger"></span>
              </div>
            </div>
          </div>
          
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Course <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <select name="faculty_course_id" id="faculty_course_id" class="form-control">
                  <option value="">Select Course</option>
                  <?php
                  echo load_course_list($connect);
                  ?>
                </select>
                <span id="error_faculty_course_id" class="text-danger"></span>
              </div>
            </div>
          </div>
          
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Date of Joining <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="text" name="faculty_doj" id="faculty_doj" class="form-control" />
                <span id="error_faculty_doj" class="text-danger"></span>
              </div>
            </div>
          </div>
          
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          
          <input type="hidden" name="faculty_id" id="faculty_id" />
          <input type="hidden" name="action" id="action" value="Add" />
          <input type="submit" name="button_action" id="button_action" class="btn btn-success btn-sm" value="Add" />
          <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
        
        </div>

      </div>
    </form>
  </div>
</div>

<!-- ----------------------------------------------------------------------------------------------------- -->

<!-- for viewing faculty details -->
<div class="modal" id="viewModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Faculty Details</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body" id="faculty_details">

      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<!-- ------------------------------------------------------------------------------------------------- -->

 <!-- delete faculty -->
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


<!-- ------------------------------------------------------------------------------------------------------- -->


<script>
$(document).ready(function()
{
  // for main page of faculty call faculty_action file
	var dataTable = $('#faculty_table').DataTable({
		"processing":true,
		"serverSide":true,
		"order":[],
		"ajax":{
			url:"faculty_action.php",
			type:"POST",
			data:
      {
        action:'fetch'
      } 
		},
		"columnDefs":[
			{
				"targets":[0, 4, 5, 6],
				"orderable":false,
			},
		],
	});


// --------------------------------------------------------------------------------------------

// for taking date as input
  $('#faculty_doj').datepicker(
    {
        format: "yyyy-mm-dd",
        autoclose: true,
        container: '#formModal modal-body'
    });

// clearing form data
  function clear_field()
  {
    $('#faculty_form')[0].reset();
    $('#error_faculty_name').text('');
    $('#error_faculty_address').text('');
    $('#error_faculty_emailid').text('');
    $('#error_faculty_password').text('');
    $('#error_faculty_qualification').text('');
    $('#error_faculty_doj').text('');
    $('#error_faculty_course_id').text('');
  }

// click on add faculty
  $('#add_button').click(function()
  {
    $('#modal_title').text("Add faculty");
    $('#button_action').val('Add');
    $('#action').val('Add');
    $('#formModal').modal('show');
    clear_field();
  });

// submitted faculty details
  $('#faculty_form').on('submit', function(event)
  {
    // call eacher_action
    event.preventDefault();
    $.ajax({
      url:"faculty_action.php",
      method:"POST",
      data:new FormData(this),
      dataType:"json",
      contentType:false,
      processData:false,

      // before sending request 
      beforeSend:function()
      {        
        $('#button_action').val('Validate...');
        $('#button_action').attr('disabled', 'disabled');
      },

      // adding data
      success:function(data)
      {
        $('#button_action').attr('disabled', false);
        $('#button_action').val($('#action').val());
      
      // if data success
        if(data.success)
        {
          $('#message_operation').html('<div class="alert alert-success">'+data.success+'</div>');
          clear_field();
          $('#formModal').modal('hide');
          dataTable.ajax.reload();
        }

        // any error
        if(data.error)
        { 
          // empty name
          if(data.error_faculty_name != '')
          {
            $('#error_faculty_name').text(data.error_faculty_name);
          }
          else
          {
            $('#error_faculty_name').text('');
          }

          // empty address
          if(data.error_faculty_address != '')
          {
            $('#error_faculty_address').text(data.error_faculty_address);
          }
          else
          {
            $('#error_faculty_address').text('');
          }

          // empty emailid
          if(data.error_faculty_emailid != '')
          {
            $('#error_faculty_emailid').text(data.error_faculty_emailid);
          }
          else
          {
            $('#error_faculty_emailid').text('');
          }

          // empty password
          if(data.error_faculty_password != '')
          {
            $('#error_faculty_password').text(data.error_faculty_password);
          }
          else
          {
            $('#error_faculty_password').text('');
          }

          // empty course id
          if(data.error_faculty_course_id != '')
          {
            $('#error_faculty_course_id').text(data.error_faculty_course_id);
          }
          else
          {
            $('#error_faculty_course_id').text('');
          }
          
          // empty qualification
          if(data.error_faculty_qualification != '')
          {
            $('#error_faculty_qualification').text(data.error_faculty_qualification);
          }
          else
          {
            $('#error_faculty_qualification').text('');
          }
          
          // empty date of joining
          if(data.error_faculty_doj != '')
          {
            $('#error_faculty_doj').text(data.error_faculty_doj);
          }
          else
          {
            $('#error_faculty_doj').text('');
          }

        }
      }
    });
  });

// --------------------------------------------------------------------------------------------------

// view faculty
  var faculty_id = '';

  $(document).on('click', '.view_faculty', function()
  {
    faculty_id = $(this).attr('id');
    $.ajax({
      url:"faculty_action.php",
      method:"POST",
      data:
      {
        action:'single_fetch', 
        faculty_id:faculty_id
      },

      success:function(data)
      {
        $('#viewModal').modal('show');
        $('#faculty_details').html(data);
      }
    });
  });

// ---------------------------------------------------------------------------------------------------------

// edit faculty
  $(document).on('click', '.edit_faculty', function(){
  	faculty_id = $(this).attr('id');
  	clear_field();

  	$.ajax({
  		url:"faculty_action.php",
  		method:"POST",
  		data:
      {
        action:'edit_fetch', 
        faculty_id:faculty_id
      },
  		dataType:"json",
  		
      success:function(data)
  		{
  			$('#faculty_name').val(data.faculty_name);
  			$('#faculty_address').val(data.faculty_address);
        $('#faculty_emailid').val(data.faculty_emailid);
  			$('#faculty_course_id').val(data.faculty_course_id);
  			$('#faculty_qualification').val(data.faculty_qualification);
  			$('#faculty_doj').val(data.faculty_doj);
  			$('#faculty_id').val(data.faculty_id);

  			$('#modal_title').text('Edit faculty');
  			$('#button_action').val('Edit');
  			$('#action').val('Edit');
  			$('#formModal').modal('show');

        // any error
        if(data.error)
        { 
          // email id already exists
          if(data.error_faculty_emailid != '')
          {
            $('#error_faculty_emailid').text(data.error_faculty_emailid);
          }
          else
          {
            $('#error_faculty_emailid').text('');
          }
        }

  		}
  	});
  });


// -------------------------------------------------------------------------------------------------

// delete faculty
  $(document).on('click', '.delete_faculty', function()
  {
  	faculty_id = $(this).attr('id');
  	$('#deleteModal').modal('show');
  });

// confirmed deletion
  $('#ok_button').click(function()
  {
  	$.ajax({
  		url:"faculty_action.php",
  		method:"POST",
  		data:
      {
        faculty_id:faculty_id, 
        action:'delete'
      },

  		success:function(data)
  		{
  			$('#message_operation').html('<div class="alert alert-success">'+data+'</div>');
  			$('#deleteModal').modal('hide');
  			dataTable.ajax.reload();
  		}
  	})
  });

//-----------------------------------------------------------------------------------------------


});
</script>