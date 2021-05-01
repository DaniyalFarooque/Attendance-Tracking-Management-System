<?php

//profile.php

// incude header.php
include('header.php');

// variables for data
$faculty_name = '';
$faculty_address = '';
$faculty_emailid = '';
$faculty_password = '';
$faculty_course_id = '';
$faculty_qualification = '';
$faculty_doj = '';

// variables for error
$error_faculty_name = '';
$error_faculty_address = '';
$error_faculty_emailid = '';
$error_faculty_course_id = '';
$error_faculty_qualification = '';
$error_faculty_doj = '';

$error = 0;
$success = '';

// retrieve data from database
$query = "
SELECT * FROM tbl_faculty 
WHERE faculty_id = '".$_SESSION["faculty_id"]."'
";

// execute and fetch 
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();

?>

<!-- -------------------------------------------------------------------------------------------------- -->

 <!-- display profile -->
<div class="container" style="margin-top:30px">
  <span><?php echo $success; ?></span>
  <div class="card">
    <form method="post" id="profile_form" enctype="multipart/form-data">

		<!-- profile -->
		<div class="card-header">
			<div class="row">
				<div class="col-md-9">Profile</div>
				<div class="col-md-3" align="right">
				</div>
			</div>
		</div>

		<div class="card-body">

			<!-- name -->
			<div class="form-group">
				<div class="row">
					<label class="col-md-4 text-right">Name <span class="text-danger">*</span></label>
					<div class="col-md-8">
						<input type="text" name="faculty_name" id="faculty_name" class="form-control" />
						<span class="text-danger"><?php echo $error_faculty_name; ?></span>
					</div>
				</div>
			</div>

			<!-- address -->
			<div class="form-group">
				<div class="row">
					<label class="col-md-4 text-right">Address <span class="text-danger">*</span></label>
					<div class="col-md-8">
						<textarea name="faculty_address" id="faculty_address" class="form-control"></textarea>
						<span class="text-danger"><?php echo $error_faculty_address; ?></span>
					</div>
				</div>
			</div>

			<!-- email id -->
			<div class="form-group">
				<div class="row">
					<label class="col-md-4 text-right">Email Address <span class="text-danger">*</span></label>
					<div class="col-md-8">
						<input type="text" name="faculty_emailid" id="faculty_emailid" class="form-control" />
						<span class="text-danger"><?php echo $error_faculty_emailid; ?></span>
					</div>
				</div>
			</div>

			<!-- qualification -->
			<div class="form-group">
				<div class="row">
					<label class="col-md-4 text-right">Qualification <span class="text-danger">*</span></label>
					<div class="col-md-8">
						<input type="text" name="faculty_qualification" id="faculty_qualification" class="form-control" />
						<span class="text-danger"><?php echo $error_faculty_qualification; ?></span>
					</div>
				</div>
			</div>

			<!-- course -->
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
						<span class="text-danger"><?php echo $error_faculty_course_id; ?></span>
					</div>
				</div>
			</div>

			<!-- date of joining -->
			<div class="form-group">
				<div class="row">
					<label class="col-md-4 text-right">Date of Joining <span class="text-danger">*</span></label>
					<div class="col-md-8">
						<input type="text" name="faculty_doj" id="faculty_doj" class="form-control" readonly />
						<span class="text-danger"><?php echo $error_faculty_doj; ?></span>
					</div>
				</div>
			</div>

		</div>
    </form>
  </div>
</div>

</body>
</html>

<!-- ----------------------------------------------------------------------------------------------------- -->

<script type="text/javascript" src="js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="css/datepicker.css" />

<style>
    .datepicker
    {
      z-index: 1600 !important; /* has to be larger than 1050 */
    }
</style>

<script>
	$(document).ready(function(){
	
<?php
	// display result
	foreach($result as $row)
	{
?>
// display result
	$('#faculty_name').val("<?php echo $row["faculty_name"]; ?>");
	$('#faculty_address').val("<?php echo $row["faculty_address"]; ?>");
	$('#faculty_emailid').val("<?php echo $row["faculty_emailid"]; ?>");
	$('#faculty_qualification').val("<?php echo $row["faculty_qualification"]; ?>");
	$('#faculty_course_id').val("<?php echo $row["faculty_course_id"]; ?>");
	$('#faculty_doj').val("<?php echo $row["faculty_doj"]; ?>");
	$('#faculty_id').val("<?php echo $row["faculty_id"];?>");

<?php
	}
?>
  
  // for date input
  	$('#faculty_doj').datepicker({
  		format: "yyyy-mm-dd",
    	autoclose: true
  	});

});

</script>