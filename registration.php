
<?php
	session_start();
	require_once "production/model/model.php";
	$borrower = new Model;
	$database = new Database;

	$token = $borrower->generateAuth();
	$_SESSION['request_token'] = $token;
	
	$sql = "SELECT * FROM borrowers WHERE borrower_id=?";
	$id_exist = true;
	$borrower_id = "";

	while ($id_exist == true) {

		$borrower_id = $database->generateID();
		$row = $database->validate("SELECT * FROM tbl_borrowers WHERE borrower_id='$borrower_id'");

		if($row == 0) {

			$id_exist = false;
			break;
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="production/images/logo.png"/>
  	<title>E-Library</title>
    <link href="vendors/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
    <link href="vendors/font-awesome/css/font-awesome.css" rel="stylesheet">
	<link href="vendors/custom.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="production/include/js/sweetalert-master/dist/sweetalert.css">
	<link href="production/include/js/toastr/build/toastr.min.css" rel="stylesheet" type="text/css">
  <style>
	 .profile_view {
			border: 1px solid #94979c;
		}
		.profile_view:hover {

			background-color: #d3ead9;
		}
	  body {
		  font-size: 13px;
	  }
	
	  .box_shadow {
                box-shadow: 0 6px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 10px 0 #f5f5f5;
            }
  </style>
  </head>
   <body style="background-color:#e2e2e2; background-image: url(production/images/wall_1.jpg); background-repeat: no-repeat; background-size: cover; ">
 <div class="container-fluid" >
 	<div class="row" >
		<nav class="navbar navbar-inverse" >
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="home.php"  style="margin-left:40px; color: white;">E-Library System</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
         <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
         <li ><a href="catalog.php"><i class="fa fa-table"></i> Catalog</a></li>
		   <li class="active"><a href="registration.php"><i class="fa fa-edit"></i> Registration</a></li>
      </ul>
      <ul class="nav navbar-nav ">
        <li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
      </ul>
    </div>
  </div>
</nav>
	</div>
 </div>
 <div class="container-fluid" style="width:90%; margin-top:-20px" >
 	<div class="row " style="">
		<div class="panel panel-default" style="background-color: rgba(222, 220, 234, 0.9); padding-bottom:100px">
		  <div class="panel-heading"> 
			<img src="production/images/user_add.png" width="50px" height="50px">
			<h3 style="margin-left:60px; margin-top:-40px; color: black; text-shadow: 1px 1px 20px #f37f7f;">Borrower Registration</h3></div>
		  <div class="panel-body">
			  <div class="col-md-12 col-sm-12 col-xs-12" style="padding-bottom:230px">
			 <br><br>
			 <form id="addBorrower">
				   <div class="col-md-12 col-sm-12 col-xs-12">
					   <div class="col-md-6 col-sm-6 col-xs-6">
						  
						   <div class="form-group">
							  <label>First Name:</label>
							   <div class="input-group">
								<span class="input-group-addon"><i class="fa fa-user"></i></span>
								    <input type="hidden" name="request_token" value="<?php echo $token; ?>"/>
								 <input type="hidden" name="action" value="request"/>
								<input type="hidden" class="form-control" style="background-color:#e2e2e2" name="employee_no" value="<?php echo $borrower_id; ?>" placeholder="Employee No" required="">
								<input type="text" class="form-control"  name="firstname" id="firstname" onkeyup="validation('firstname')" onkeydown="validation('firstname')" onmouseout="validation('firstname')" placeholder="First Name" required="">
							  </div>
							</div>
						   <div class="form-group">
							  <label>Last Name:</label>
							   <div class="input-group">
								<span class="input-group-addon"><i class="fa fa-user"></i></span>
								<input type="text" class="form-control"  name="lastname" id="lastname" onkeyup="validation('lastname')" onkeydown="validation('lastname')" onmouseout="validation('lastname')" placeholder="Last Name" required="">
							  </div>
							</div>
						   <div class="form-group">
							  <label>Gender:</label>
							   <div class="input-group">
								<span class="input-group-addon"><i class="fa fa-venus-double"></i></span>
								<select class="form-control " required="" name="gender" style=" ">
								<option>Male</option>
								<option >Female</option>
							  </select>
							  </div>
							</div>
					   </div>
					   <div class="col-md-6 col-sm-6 col-xs-6">
						   <div class="form-group">
							  <label>Contact No:</label>
							   <div class="input-group">
								<span class="input-group-addon"><i class="fa fa-phone"></i></span>
								<input type="text" class="form-control"  name="contactno" id="contactno" onkeyup="validation_two('contactno')" onkeydown="validation_two('contactno')" onmouseout="validation_two('contactno')" placeholder="Contact No" required="">
							  </div>
							</div>
						   <div class="form-group">
							  <label>Grade Level:</label>
							   <div class="input-group">
								<span class="input-group-addon"><i class="fa fa-briefcase"></i></span>
								   <select class="form-control" required="" name="position">
									<option>Grade 1</option>
									<option>Grade 1</option>
									<option>Grade 2</option>
									<option>Grade 3</option>
									<option>Grade 4</option>
									<option>Grade 5</option>
									<option>Grade 6</option>
									<option>Grade 7</option>
									<option>Grade 8</option>
									<option>Grade 9</option>
									<option>Grade 10</option>
									<option>Grade 11</option>
									<option>Grade 12</option>
									<option>Teacher</option>
								  </select>
							  </div>
							</div>
						   <div class="form-group">
							  <label>School Name:</label>
							   <div class="input-group">
								<span class="input-group-addon"><i class="fa fa-building-o"></i></span>
								<input type="text" class="form-control"  name="schoolname" id="schoolname" onkeyup="validation('schoolname')" onkeydown="validation('schoolname')" onmouseout="validation('schoolname')" placeholder="School Name" required="">
							  </div>
							</div>
					   </div>
			  		</div>
				  <div class="col-md-12 col-sm-12 col-xs-12">
					<div class="col-md-6 col-sm-12 col-xs-12">
						<br>
						<button type="reset" class="btn btn-danger">
							<i class="glyphicon glyphicon-remove-sign"></i> Cancel register
						</button>
						<button type="submit" class="btn btn-primary">
							<i class="fa fa-send-o"></i> Make request
						</button>
					
					  </div>
				  </div>
			  </form>

		</div>
			</div>
		</div>
		</div>
	</div>
 </div>
	   
	<script src="vendors/jquery/dist/jquery.min.js" type="text/javascript"></script>
	<script src="vendors/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
   <script src="production/include/js/toastr/build/toastr.min.js" type="text/javascript"></script>
   <script src="production/include/js/sweetalert-master/dist/sweetalert-dev.js" type="text/javascript"></script>
   <script type="text/javascript">
	   function validation(id) {
			var textfield = document.getElementById(id);
			var regex = /[^a-z 0-9 _ -.]/gi;
			var bad = [/fuck/g,/gago/g,/abno/g,/pesti/g,/bobo/g,];

			for (var list = 0; list < bad.length; list++) {

				textfield.value = textfield.value.replace(bad[list], "");
			}

			textfield.value = textfield.value.replace(regex, "");
		}
		function validation_two(id) {

			var textfield = document.getElementById(id);
			var regex = /[^0-9 -]/gi;
			textfield.value = textfield.value.replace(regex, "");
		}
	   
		$("#addBorrower").on('submit',(function(e) {

			e.preventDefault();
			$.ajax({
				url: "production/model/borrowers.php",
				type: "POST",
				data: new FormData(this),
				contentType: false,
				cache: false,
				processData:false,
				success: function(data){
					console.log(data);
					if(data == "true") {
						toastr.info("Registration was successfully send.");
					}
					else if(data == "exist") {
						toastr.error("Employee is already exist.");
					}
					else {
						toastr.error("error");
					}
					$("#addBorrower")[0].reset();
				},
				error: function(){

				}
			});
		}));
   </script>
  </body>
</html>
