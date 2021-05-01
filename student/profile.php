<?php

//profile.php

include('header.php');

// variables for values
$student_name = '';
$student_emailid = '';
$student_password = '';
$student_course_id = '';
$student_dob = '';

// variables for errors
$error_student_name = '';
$error_student_emailid = '';
$error_student_course_id = '';
$error_student_dob = '';

$error = 0;
$success = '';

// clicked on profile button
if(isset($_POST["button_action"]))
{

	//empty name
	if(empty($_POST["student_name"]))
	{
		$error_student_name = "Student Name is required";
		$error++;
	}
	else
	{
		$student_name = $_POST["student_name"];
	}

	// empty emailid
	if(empty($_POST["student_emailid"]))
	{
		$error_student_emailid = "Email Address is required";
		$error++;
	}
	else
	{
		// verify emailid
		if(!filter_var($_POST["student_emailid"], FILTER_VALIDATE_EMAIL))
		{
			$error_student_emailid = "Invalid email format";
			$error;
		}
		else
		{
			$student_emailid = $_POST["student_emailid"];
		}
	}

	// empty course id
	if(empty($_POST["student_course_id"]))
	{
		$error_student_course_id = 'Course is required';
		$error++;
	}
	else
	{
		$student_course_id = $_POST["student_course_id"];
	}

	// empty dob
	if(empty($_POST["student_dob"]))
	{
		$error_student_dob = "Date of Birth is required";
		$error++;
	}
	else
	{
		$student_dob = $_POST["student_dob"];
	}

}

// retrieve  data of that student
$query = "
SELECT * FROM tbl_student 
WHERE student_id = '".$_SESSION["student_id"]."'
";

$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();

?>

<!-- ------------------------------------------------------------------------------------------------------ -->

<div class="container" style="margin-top:30px">
  <span><?php echo $success; ?></span>
  <div class="card">
    <form method="post" id="profile_form" enctype="multipart/form-data">
	
		<div class="card-header">
			<div class="row">
				<div class="col-md-9">Profile</div>
				<div class="col-md-3" align="right">
				</div>
			</div>
		</div>
		
		<div class="card-body">

			<!-- student name -->
			<div class="form-group">
				<div class="row">
					<label class="col-md-4 text-right">Student Name <span class="text-danger">*</span></label>
					<div class="col-md-8">
						<input type="text" name="student_name" id="student_name" class="form-control" readonly/>
						<span class="text-danger"><?php echo $error_student_name; ?></span>
					</div>
				</div>
			</div>
			
			<!-- email id -->
			<div class="form-group">
				<div class="row">
					<label class="col-md-4 text-right">Email Address <span class="text-danger">*</span></label>
					<div class="col-md-8">
						<input type="text" name="student_emailid" id="student_emailid" class="form-control" readonly/>
						<span class="text-danger"><?php echo $error_student_emailid; ?></span>
					</div>
				</div>
			</div>
			
			<!-- course -->
			<div class="form-group">
				<div class="row">
					<label class="col-md-4 text-right">Course <span class="text-danger">*</span></label>
					<div class="col-md-8">
						<select name="student_course_id" id="student_course_id" class="form-control" readonly>
                			<option value="" >Select Course</option>
                			<?php
                			echo load_course_list($connect);
                			?>
                		</select>
						<span class="text-danger"><?php echo $error_student_course_id; ?></span>
					</div>
				</div>
			</div>

			<!-- date of birth -->
			<div class="form-group">
				<div class="row">
					<label class="col-md-4 text-right">Date of Birth <span class="text-danger">*</span></label>
					<div class="col-md-8">
						<input type="text" name="student_dob" id="student_dob" class="form-control" readonly />
						<span class="text-danger"><?php echo $error_student_dob; ?></span>
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

<script type="text/javascript" src="../js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="../css/datepicker.css" />

<style>
    .datepicker
    {
      z-index: 1600 !important; /* has to be larger than 1050 */
    }
</style>

<script>
$(document).ready(function(){
	
<?php
	foreach($result as $row)
	{
?>
	// for each entry display result
		$('#student_name').val("<?php echo $row["student_name"]; ?>");
		$('#student_emailid').val("<?php echo $row["student_emailid"]; ?>");
		$('#student_course_id').val("<?php echo $row["student_course_id"]; ?>");
		$('#student_dob').val("<?php echo $row["student_dob"]; ?>");
		$('#student_id').val("<?php echo $row["student_id"];?>");

<?php
	}
?>
  // for dob
  	$('#student_dob').datepicker({
  		format: "yyyy-mm-dd",
    	autoclose: true
  	});

});
</script>