<?php

//admin.php

// include header
include('header.php');

?>

<!-- -------------------------------------------------------------------------------------------------------- -->

<div class="container" style="margin-top:30px">
  <div class="card">

  	<div class="card-header">
      <div class="row">
        <div class="col-md-9">Admin List</div>
        <div class="col-md-3" align="right">
        	<button type="button" id="add_button" class="btn btn-info btn-sm">Add</button>
        </div>
      </div>
    </div>


  	<div class="card-body">
  		<div class="table-responsive">
        	<span id="message_operation"></span>
        	<table class="table table-striped table-bordered" id="admin_table">
  				<thead>

  					<tr>
  						<th>User Name</th>
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
  	<form method="post" id="admin_form">
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
              <label class="col-md-4 text-right">user_name <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="text" name="admin_user_name" id="admin_user_name" class="form-control" />
                <span id="error_admin_user_name" class="text-danger"></span>
              </div>
            </div>
          </div>
                
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Password<span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="password" name="admin_password" id="admin_password" class="form-control" />
                <span id="error_admin_password" class="text-danger"></span>
              </div>
            </div>
          </div>
        
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
        	<input type="hidden" name="admin_id" id="admin_id" />
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
        <button type="button" user_name="ok_button" id="ok_button" class="btn btn-primary btn-sm">OK</button>
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<!-- ----------------------------------------------------------------------------------------------------- -->

<script>
$(document).ready(function(){
	
  // for main page of admin
	var dataTable = $('#admin_table').DataTable(
  {
		"processing":true,
		"serverSide":true,
		"order":[],
	
  	"ajax":{
			url:"admin_action.php",
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
		$('#admin_form')[0].reset();
		$('#error_admin_user_name').text('');
		$('#error_admin_password').text('');
	}

// clciked on add button
	$('#add_button').click(function()
  {
		$('#modal_title').text('Add admin');
		$('#button_action').val('Add');
		$('#action').val('Add');
		$('#formModal').modal('show');
		clear_field();
	});

// submitted details for adding admin
	$('#admin_form').on('submit', function(event){
		event.preventDefault();

		$.ajax({
			url:"admin_action.php",
			method:"POST",
			data:$(this).serialize(),
			dataType:"json",

      // before sending request
			beforeSend:function(){

				$('#button_action').attr('disabled', 'disabled');
				$('#button_action').val('Validate...');
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

          // empty user_name
					if(data.error_admin_user_name != '')
					{
						$('#error_admin_user_name').text(data.error_admin_user_name);
					}
					else
					{
						$('#error_admin_user_name').text('');
					}
          										
          // empty password
          if(data.error_admin_password != '')
					{
						$('#error_admin_password').text(data.error_admin_password);
					}
					else
					{
						$('#error_admin_password').text('');
					}

				}
			}
		})
	});

// --------------------------------------------------------------------------------------------------------

// edit admin
  var admin_id = '';

  $(document).on('click', '.edit_admin', function()
  {
    admin_id = $(this).attr('id');
    clear_field();

    $.ajax({
      url:"admin_action.php",
      method:"POST",
      data:
      {
        action:'edit_fetch', 
        admin_id:admin_id
      },

      dataType:"json",
      
      // successfully editted
      success:function(data)
      {
        $('#admin_user_name').val(data.admin_user_name);
        $('#admin_password').val(data.admin_password);
        $('#admin_id').val(data.admin_id);

        $('#modal_title').text('Edit admin');
        $('#button_action').val('Edit');
        $('#action').val('Edit');
        $('#formModal').modal('show');


      }
    })
  });

// --------------------------------------------------------------------------------------------------------

// delete admin
  $(document).on('click', '.delete_admin', function(){
    admin_id = $(this).attr('id');
    $('#deleteModal').modal('show');
  });

// confirmed deletion
  $('#ok_button').click(function(){
    $.ajax({
      url:"admin_action.php",
      method:"POST",
      data:
      {
        admin_id:admin_id, 
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