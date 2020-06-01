<?php
	require_once "model/controller.php";
	require_once "model/model.php";
	$auth = new Database;
	$token = $auth->generateAuth();

	$_SESSION['borrowerupdate_token'] = $token;
	date_default_timezone_set('Asia/Manila');
	
	if(isset($_GET['borrower_id']) && !empty($_GET['borrower_id']) && isset($_GET['borrower_token']) && isset($_SESSION['borrower_token'])) {
		
		if($_SESSION['borrower_token'] == $_GET['borrower_token']) {
			
			$borrower_id = $_GET['borrower_id'];
			$gettoken = $_GET['borrower_token'];
			$sql = "SELECT * FROM tbl_borrowers WHERE borrower_id='$borrower_id' ";
			$borrower = new Model;
			$data2 = $borrower->displayRecord($sql);
			$_SESSION['book_id'] = $_GET['borrower_id'];
			$_SESSION['pass_token'] = $_GET['borrower_token'];
			$_SESSION['current_page'] = "borrower_edit.php";
		}
		else {
			header("location: dashboard.php");
		}
	}
	else {
		if(isset($_SESSION['book_id'])) {
			$gettoken = $_SESSION['pass_token'];
			$borrower_id = $_SESSION['book_id'];
			$sql = "SELECT * FROM tbl_borrowers WHERE borrower_id='$borrower_id' ";
			$borrower = new Model;
			$data2 = $borrower->displayRecord($sql);
		}
		else {
			header("location: dashboard.php");
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>E-Library</title>
		<?php require_once('include/css.php');  ?>
		<link href="include/mycss.css" rel="stylesheet">
		<style>
			input[type="file"] {
				display: none;
			}
			.custom-file-upload {
				border: 1px solid #ccc;
				display: inline-block;
				padding: 14px 12px;
				cursor: pointer;
				width: 100%;
				font-size: 15px;
				text-align: center;
				height: 115px;
			}
		</style>
        
    </head>
    <body class="nav-md" onclick="resetTimer()" onmousedown="resetTimer()" onmousemove="resetTimer()" onscroll="resetTimer()" onkeypress="resetTimer()">
        <div class="container body" ng-app="application">
            <div class="main_container" ng-controller="quickFeaturesController">
                <?php
                    if($record[0]['role'] == 1) {
						 require_once('include/sidemenu.php'); 
					}
					else {
						 require_once('include/sidemenu_client.php'); 
					}
                    require_once('include/topnav.php');
                ?>
                <div class="right_col" role="main" style="background-image: url(images/<?php //echo $set_data['bg_image'];?>); background-repeat: no-repeat; background-size: cover;">
					 <div class="col-md-12 col-sm-12 col-xs-12">
						 <br>
						<div class="x_panel">
					  <div class="x_title">
						<img src="images/user_edit.png" width="50px" height="50px">
						<h3 style="margin-left:60px; margin-top:-38px">Update Form</h3>
						  <div class=" btn-group pull-right" style="margin-left:60px; margin-top:-45px">
								<a href="borrower_view.php? borrower_id=<?php echo $borrower_id;?> & borrower_token=<?php echo $gettoken;?>" class="btn btn-primary btn-sm"  data-toggle="tooltip" data-placement="top" title="" data-original-title="View borrower details"><i class="fa fa-user"></i> View</a>
							  <a href="borrowers.php? action='tab2'" class="btn btn-primary btn-sm"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Back to borrower list"><i class="fa fa-mail-reply"></i> Back</a>
						  </div>
						<div class="clearfix"></div>
					  </div>
					  <div class="x_content">
						  <div class="row">
							  <form id="updateBorrower">
								   <div class="col-md-12 col-sm-12 col-xs-12">
									<div class="col-md-6 col-sm-12 col-xs-12">
								<label class="col-md-12 col-sm-12 col-xs-12 form-group"> First Name: </label>
								<div class="col-md-12 col-sm-12 col-xs-12 form-group">
									<input type="hidden" class="form-control has-feedback-left" name="employee_no" value="<?php echo $data2[0]['borrower_id']; ?>"  placeholder="Employee No" required=""  />
									<input type="hidden" name="action" value="update"/>
									<input type="hidden" name="borrowerupdate_token" value="<?php echo $token; ?>"/>
									<input list="list_fn" class="form-control has-feedback-left" name="firstname" id="firstname" value="<?php echo $data2[0]['firstname']; ?>"  onkeyup="validation('firstname')" onkeydown="validation('firstname')" onmouseout="validation('firstname')" placeholder="First Name" required="" style="background-color:#e2e2e2; text-transform:capitalize" />
									<span class="fa fa-user form-control-feedback left" aria-hidden="true" style="color:black"></span>
									<datalist id="list_fn" class="form-group">
										
									</datalist>
								</div>
								
								<label class="col-md-12 col-sm-12 col-xs-12 form-group"> Last Name: </label>
								<div class="col-md-12 col-sm-12 col-xs-12 form-group">
									<input list="list_ln" class="form-control has-feedback-left" name="lastname" id="lastname" value="<?php echo $data2[0]['lastname']; ?>" onkeyup="validation('lastname')" onkeydown="validation('lastname')" onmouseout="validation('lastname')"  placeholder="Last Name" required=""  style="background-color:#e2e2e2; text-transform:capitalize"/>
									<span class="fa fa-user form-control-feedback left" aria-hidden="true" style="color:black"></span>
									<datalist id="list_ln" class="form-group">
										
									</datalist>
								</div>
								<label class="col-md-12 col-sm-12 col-xs-12 form-group"> Gender: </label>
								<div class="col-md-12 col-sm-12 col-xs-12 form-group">
									<select class="form-control has-feedback-left" required="" name="gender" style="background-color:#e2e2e2; ">
										<?php
											if($data2[0]['gender'] == "Male") {
												echo "<option selected>Male</option>
														<option >Female</option>";
											}
											else {
												echo "<option >Male</option>
														<option selected>Female</option>";
											}
										?>
									  </select>
									<span class="fa fa-venus-double  form-control-feedback left" aria-hidden="true" style="color:black"></span>
								</div>
									<label class="col-md-12 col-sm-12 col-xs-12 form-group"> Grade Level: </label>
									<div class="col-md-12 col-sm-12 col-xs-12 form-group">
										<select class="form-control has-feedback-left" required="" name="position" style="background-color:#e2e2e2; ">
										<?php
											$grade_list = array("Grade 1", "Grade 2", "Grade 3", "Grade 4", "Grade 5", "Grade 6", "Grade 7","Grade 8", "Grade 9", "Grade 10", "Grade 11", "Grade 12");
											if(count($grade_list) > 0) {
												foreach ($grade_list as $value) {
													if($value == $data2[0]['position']) {
														echo "<option selected>$value</option>";
													}
													else {
														echo "<option>$value</option>";
													}
												}
											}
										?>
										</select>
										<span class="fa fa-briefcase form-control-feedback left" aria-hidden="true" style="color:black"></span>
									</div>
							</div>
							  <div class="col-md-6 col-sm-12 col-xs-12">
								<label class="col-md-12 col-sm-12 col-xs-12 form-group"> Contact No: </label>
								<div class="col-md-12 col-sm-12 col-xs-12 form-group">
									<input type="text" class="form-control has-feedback-left" name="contactno" id="contactno" value="<?php echo $data2[0]['contactno']; ?>" placeholder="Contact Number" required="" onkeyup="validation_two('contactno')" onkeydown="validation_two('contactno')" onmouseout="validation_two('contactno')" />
									<span class="fa fa-phone form-control-feedback left" aria-hidden="true" style="color:black"></span>
								</div>
									
								  <label class="col-md-12 col-sm-12 col-xs-12 form-group"> School Name: </label>
								<div class="col-md-12 col-sm-12 col-xs-12 form-group">
									<input type="text" class="form-control has-feedback-left" name="schoolname" id="schoolname" value="<?php echo $data2[0]['schoolname']; ?>" onkeyup="validation('schoolname')" onkeydown="validation('schoolname')" onmouseout="validation('schoolname')" placeholder="School Name" required="" />
									<span class="fa fa-building-o form-control-feedback left" aria-hidden="true" style="color:black"></span>
								</div>
								  <label class="col-md-12 col-sm-12 col-xs-12 form-group"> Status: </label>
									<div class="col-md-12 col-sm-12 col-xs-12 form-group">
										<select class="form-control has-feedback-left" required="" name="status" style="background-color:#e2e2e2; ">
											<?php
												if($data2[0]['status'] == "Active") {
													echo "<option selected>Active</option>
															<option >Inactive</option>";
												}
												else {
													echo "<option >Active</option>
															<option selected>Inactive</option>";
												}
											?>

										  </select>
										<span class="fa fa-users form-control-feedback left" aria-hidden="true" style="color:black"></span>
									</div>
								</div>
							</div>
							
							  </div>
							  <div class="col-md-12 col-sm-12 col-xs-12">
								  <br>
								   <div class="col-md-6 col-sm-12 col-xs-12">
								  </div>
									<div class="col-md-6 col-sm-12 col-xs-12">
								  	<button type="reset" class="btn btn-default pulse" style="font-size:17px; font-weight:bold; margin-left:10px; background-color: #f3b4b4; border: 2px solid #f98e8e"><img src="images/cancel_user.png" width="50px" >Cancel
									</button>
										<button type="submit" class="btn btn-default pulse" style="font-size:17px; font-weight:bold; background-color:#7096d8; border: 2px solid #3667bd"><img src="images/user_saved.png" width="50px">Update</button>
								  </div>
							  </div>
							  </form>
							  
						 </div>
						
					</div>
					  </div>
			
            </div>
            <?php include'include/footer.php';?>
        </div>
         <?php include'include/js.php';?>
			<script>
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
				$(function () {
					//logo image preview
					function filePreview(input){
						if(input.files && input.files[0]){
							var reader = new FileReader();
							reader.onload = function(e){
								$('.pre_img').hide();
								$('.image_view').after('<img src="'+e.target.result+'" />');
								$('.photos img').css('max-width','100%');
								$('.photos img').css('max-height','100px');
								$("#remove_photo").show(200);
							}
							reader.readAsDataURL(input.files[0]);
						}
					}$('.photos img').css('max-height','250px');

					$('.user_photo').change(function(){
						filePreview(this);
						$('.custom-file-upload').hide();
					});

					//remove logo img
					$("#remove_photo").click(function(){
						$('.photos img').hide();
						$('.pre_img').show();
						$('.user_photo').val('');
						$("#remove_photo").slideUp(300);
						$('.photos img').css('max-height','250px');
						$('.upload_photo').slideUp();
						$('.custom-file-upload').show();
						
					});

					//show or hide pdf book upload field 
					$('[name=type]').change(function(){
						if($(this).val()=='digital'){
							$('.bookUpload').slideDown();
							$('[name=book_pdf]').show();
							$("input[name='book_pdf']").prop('required',true);
						}else{$('.bookUpload').slideUp();$("input[name='book_pdf']").prop('required',false);}
					});

				})
				
				$("#updateBorrower").on('submit',(function(e) {

          			e.preventDefault();
					$.ajax({
						url: "model/borrowers.php",
						type: "POST",
						data: new FormData(this),
						contentType: false,
						cache: false,
						processData:false,
						success: function(data){
							console.log(data);
							if(data == "true") {
								toastr.info("Borrower details is successfully updated.");
								setTimeout(function(){ location.reload(); }, 1000);
							}
							
							else {
								toastr.error("error");
							}
							$("#updateBorrower")[0].reset();
						},
						error: function(){
							alert("error");
						}
					});
				}));
				
				
			</script>
    </body>
</html>
