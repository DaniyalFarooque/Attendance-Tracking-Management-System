<?php

//course.php

// include header
include('header.php');

?>

<!-- -------------------------------------------------------------------------------------------------------- -->

<div class="container" style="margin-top:30px">
  <div class="card">

    <!-- heading -->
  	<div class="card-header">
      <div class="row">
        <div class="col-md-9">Course List</div>
        <div class="col-md-3" align="right">
        	<button type="button" id="add_button" class="btn btn-info btn-sm">Add</button>
        </div>
      </div>
    </div>

    <!-- list heading -->
  	<div class="card-body">
  		<div class="table-responsive">
        	<span id="message_operation"></span>
        	<table class="table table-striped table-bordered" id="course_table">
  				<thead>

  					<tr>
  						<th>Code</th>
  						<th>Name</th>
  						<th>Semester</th>
              <th>Credit</th>
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


<!-- -------------------------------------------------------------------------------------------- -->
 <!-- form for add, edit -->

<div class="modal" id="formModal">
  <div class="modal-dialog">
  	<form method="post" id="course_form">
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
              <label class="col-md-4 text-right">Code<span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="text" name="course_code" id="course_code" class="form-control" />
                <span id="error_course_code" class="text-danger"></span>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Name <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="text" name="course_name" id="course_name" class="form-control" />
                <span id="error_course_name" class="text-danger"></span>
              </div>
            </div>
          </div>
        
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Semester <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="text" name="course_semester" id="course_semester" class="form-control" />
                <span id="error_course_semester" class="text-danger"></span>
              </div>
            </div>
          </div>
        
        
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Credit<span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="text" name="course_credit" id="course_credit" class="form-control" />
                <span id="error_course_credit" class="text-danger"></span>
              </div>
            </div>
          </div>
        
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
        	<input type="hidden" name="course_id" id="course_id" />
        	<input type="hidden" name="action" id="action" value="Add" />
        	<input type="submit" name="button_action" id="button_action" class="btn btn-success btn-sm" value="Add" />
          	<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
        </div>

      </div>
  </form>
  </div>
</div>

<!-- -------------------------------------------------------------------------------------------- -->
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

<!-- ----------------------------------------------------------------------------------------------------- -->

<script>
$(document).ready(function(){
	
  // for main page of course
	var dataTable = $('#course_table').DataTable(
  {
		"processing":true,
		"serverSide":true,
		"order":[],
	
  	"ajax":{
			url:"course_action.php",
			type:"POST",
			data:
      {
        action:'fetch'
      },
		}
	});

// --------------------------------------------------------------------------------------------------------

// for clearing form
	function clear_field()
	{
		$('#course_form')[0].reset();
		$('#error_course_code').text('');
		$('#error_course_name').text('');
		$('#error_course_semester').text('');
		$('#error_course_credit').text('');
	}

// clciked on add button
	$('#add_button').click(function()
  {
		$('#modal_title').text('Add course');
		$('#button_action').val('Add');
		$('#action').val('Add');
		$('#formModal').modal('show');
		clear_field();
	});

// submitted details for adding course
	$('#course_form').on('submit', function(event){
		event.preventDefault();

		$.ajax({
			url:"course_action.php",
			method:"POST",
			data:$(this).serialize(),
			dataType:"json",

      // before sending request
			beforeSend:function(){
        // disable button
				$('#button_action').attr('disabled', 'disabled');
				$('#button_action').val('Validate...');
			},

			success:function(data)
			{
        // enable button
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
          // empty code
					if(data.error_course_code != '')
					{
						$('#error_course_code').text(data.error_course_code);
					}
					else
					{
						$('#error_course_code').text('');
					}

          // empty name
					if(data.error_course_name != '')
					{
						$('#error_course_name').text(data.error_course_name);
					}
					else
					{
						$('#error_course_name').text('');
					}
          					
          // empty semester
          if(data.error_course_semester != '')
					{
						$('#error_course_semester').text(data.error_course_semester);
					}
					else
					{
						$('#error_course_semester').text('');
					}
					
          // empty credit
          if(data.error_course_credit != '')
					{
						$('#error_course_credit').text(data.error_course_credit);
					}
					else
					{
						$('#error_course_credit').text('');
					}

				}
			}
		})
	});

// --------------------------------------------------------------------------------------------------------

// edit course
  var course_id = '';

  $(document).on('click', '.edit_course', function()
  {
    // take course id
    course_id = $(this).attr('id');
    clear_field();

    $.ajax({
      url:"course_action.php",
      method:"POST",
      data:
      {
        action:'edit_fetch', 
        course_id:course_id
      },

      dataType:"json",
      
      // successfully editted
      success:function(data)
      {
        $('#course_code').val(data.course_code);
        $('#course_name').val(data.course_name);
        $('#course_semester').val(data.course_semester);
        $('#course_credit').val(data.course_credit);
        $('#course_id').val(data.course_id);

        $('#modal_title').text('Edit Course');
        $('#button_action').val('Edit');
        $('#action').val('Edit');
        $('#formModal').modal('show');

      }
    })
  });

// --------------------------------------------------------------------------------------------------------

// delete course
  $(document).on('click', '.delete_course', function(){
    course_id = $(this).attr('id');
    $('#deleteModal').modal('show');
  });

// confirmed deletion
  $('#ok_button').click(function(){
    $.ajax({
      url:"course_action.php",
      method:"POST",
      data:
      {
        course_id:course_id, 
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

//-----------------------------------------------------------------------------------------------

});
</script>