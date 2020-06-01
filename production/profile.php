<?php
	require_once "model/controller.php";
	require_once "model/model.php";
	date_default_timezone_set('Asia/Manila');
	$auth = new Database;
	$user = new Model;
	$token = $auth->generateAuth();
	$_SESSION['profile_token'] = $token;
	$user_id = $_SESSION['user_id'];
	$sql = "SELECT * FROM tbl_employee JOIN tbl_user ON tbl_employee.user_id=tbl_user.user_id WHERE tbl_employee.user_id='$user_id' ";
	$data_user= $user->displayRecord($sql);
	$image = 'images_uploaded/'.$data_user[0]['image'];
	$_SESSION['current_page'] = "profile.php";
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
					 <div class="row">
						<div class="x_panel">
						  <div class="x_title">
							<img src="images/user_profile%20(2).png" width="50px" height="50px">
								<h3 style="margin-left:60px; margin-top:-38px">User profile</h3>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">

							<div class="col-xs-3">
							  <!-- required for floating -->
							  <!-- Nav tabs -->
							  <ul class="nav nav-tabs tabs-left">
								<li class="active" id="1tab"><a href="#details" data-toggle="tab"><i class="glyphicon glyphicon-info-sign" ></i> User Details</a>
								</li>
								<li><a href="#transaction" data-toggle="tab"><i class="fa fa-user" ></i> User Account</a>
								</li>
							  </ul>
							</div>

							<div class="col-xs-9">
							  <!-- Tab panes -->
							  <div class="tab-content">
								<div class="tab-pane active" id="details">
								  <p class="lead">User Details</p>
									<div class="row">
									<form id="userEdit">
											<label class="col-md-10 col-sm-10 col-xs-12 form-group">Employee No: </label>
											<div class="col-md-10 col-sm-10 col-xs-12 form-group">
												<input type="text" class="form-control has-feedback-left" name="employee_no" id="employee_no" onkeyup="validation('employee_no')" onkeydown="validation('employee_no')" onmouseout="validation('employee_no')" placeholder="Employee No" value="<?php echo $data_user[0]['user_id']; ?>" required="" />
												<input type="hidden" name="action" value="update_profile"/>
												<input type="hidden" name="profile_token" value="<?php echo $token; ?>"/>
												<span class="fa fa-sort-numeric-asc form-control-feedback left" aria-hidden="true" style="color:black"></span>
											</div>
											<label class="col-md-10 col-sm-10 col-xs-12 form-group"> First Name: </label>
											<div class="col-md-10 col-sm-10 col-xs-12 form-group">
												<input list="list_fn" class="form-control has-feedback-left" name="firstname" id="firstname" onkeyup="validation('firstname')" onkeydown="validation('firstname')" onmouseout="validation('firstname')" placeholder="First Name" value="<?php echo $data_user[0]['firstname']; ?>" required="" style="background-color:#e2e2e2; text-transform:capitalize" />
												<span class="fa fa-user form-control-feedback left" aria-hidden="true" style="color:black"></span>
												<datalist id="list_fn" class="form-group">

												</datalist>
											</div>

											<label class="col-md-10 col-sm-10 col-xs-12 form-group"> Last Name: </label>
											<div class="col-md-10 col-sm-10 col-xs-12 form-group">
												<input list="list_ln" class="form-control has-feedback-left" name="lastname" id="lastname" onkeyup="validation('lastname')" onkeydown="validation('lastname')" onmouseout="validation('lastname')" placeholder="Last Name" value="<?php echo $data_user[0]['lastname']; ?>" required=""  style="background-color:#e2e2e2; text-transform:capitalize"/>
												<span class="fa fa-user form-control-feedback left" aria-hidden="true" style="color:black"></span>
												<datalist id="list_ln" class="form-group">

												</datalist>
											</div>
											<label class="col-md-10 col-sm-10 col-xs-12 form-group"> Gender: </label>
											<div class="col-md-10 col-sm-10 col-xs-12 form-group">
												<select class="form-control has-feedback-left" required="" name="gender" style="background-color:#e2e2e2; ">
													<?php
														if($data_user[0]['gender'] == "Male") {
															echo "<option selected>Male</option>
																	<option >Female</option>";
														}
														else {
															echo "<option >Male</option>
																	<option selected>Female</option>";
														}
													?>
													
												  </select>
												<span class="fa fa-venus-double form-control-feedback left" aria-hidden="true" style="color:black"></span>
											</div>
											<label class="col-md-10 col-sm-10 col-xs-12 form-group"> Position: </label>
											<div class="col-md-10 col-sm-10 col-xs-12 form-group">
												<input list="list_position" class="form-control has-feedback-left" name="position" id="position" onkeyup="validation('position')" onkeydown="validation('position')" onmouseout="validation('position')"  value="<?php echo $data_user[0]['position']; ?>" placeholder="Position"  style="background-color:#e2e2e2; text-transform:capitalize" required="" />
												<span class="fa fa-briefcase form-control-feedback left" aria-hidden="true" style="color:black"></span>
												<datalist id="list_position" class="form-group">

												</datalist>
											</div>
											<label class="col-md-10 col-sm-10 col-xs-12 form-group"> Address: </label>
											<div class="col-md-10 col-sm-10 col-xs-12 form-group">
												 <textarea rows="2" required="required" class="form-control" name="address" id="address" onkeyup="validation('address')" onkeydown="validation('address')" onmouseout="validation('address')" style="background-color:#e2e2e2; text-transform:capitalize"  placeholder="Address"><?php echo $data_user[0]['address']; ?></textarea>
											</div>
											<label class="col-md-10 col-sm-10 col-xs-12 form-group"> Contact No: </label>
											<div class="col-md-10 col-sm-10 col-xs-12 form-group">
												<input type="text" class="form-control has-feedback-left" name="contactno" id="contactno" onkeyup="validation_two('contactno')" onkeydown="validation_two('contactno')" onmouseout="validation_two('contactno')" value="<?php echo $data_user[0]['contactno']; ?>" placeholder="Contact Number" required="" />
												<span class="fa fa-phone form-control-feedback left" aria-hidden="true" style="color:black"></span>
											</div>
											<div class="col-md-10 col-sm-10 col-xs-12">
												<br>
												<button type="reset" class="btn btn-default pulse" style="font-size:17px; font-weight:bold; margin-left:10px; background-color: #f3b4b4; border: 2px solid #f98e8e"><img src="images/cancel_user.png" width="50px" >Cancel
												</button>
													<button type="submit" class="btn btn-default pulse" style="font-size:17px; font-weight:bold; background-color:#7096d8; border: 2px solid #3667bd"><img src="images/user_saved.png" width="50px">Update</button>
											  </div>
									</form>
									</div>
								</div>
								<div class="tab-pane" id="transaction">
									 <p class="lead">User Account</p>
									
									<form id="accountEdit">
											<div class="col-md-10 col-sm-10 col-xs-12 form-group photos">
												<div class="col-md-5 col-sm-12 col-xs-12">
													<label class=" form-group">Choose photo <small>( Optional )</small></label>
													<center>
														<label for="file-upload" class="custom-file-upload">
															<h3><i class="fa fa-cloud-upload"></i> Upload Photo</h3>
														</label>

														<input id="file-upload" name="user_photo" class="user_photo" type="file" accept="image/x-png,image/gif,image/jpeg">
													</center>
												</div>
												<div class="col-md-7 col-sm-12 col-xs-12">
													<center>
														<img class="pre_img" src="<?php echo $image; ?>" style="width: 170px; height:150px; max-height: 100px;">
														<p class="image_view"></p>

														<button type="button" id="remove_photo" class="btn btn-danger pull-right form-control" style="display:none; "><span class="ladda-label">Remove?</span></button>
													</center>
												</div>
												
										</div>
												<label class="col-md-10 col-sm-10 col-xs-12 form-group">Username: </label>
											<div class="col-md-10 col-sm-10 col-xs-12 form-group">
												<input type="hidden" name="action" value="updateA_profile">
												<input type="hidden" name="profile_token" value="<?php echo $token; ?>"/>
												<input type="hidden" name="user_id" value="<?php echo $data_user[0]['user_id']; ?>">
												<input type="text" class="form-control has-feedback-left" name="username" id="username" onkeyup="validation('username')" onkeydown="validation('username')" onmouseout="validation('username')" placeholder="Username" value="<?php echo $data_user[0]['username']; ?>" required="" />
												<span class="fa fa-users form-control-feedback left" aria-hidden="true" style="color:black"></span>
											</div>
											<label class="col-md-10 col-sm-10 col-xs-12 form-group">Email: </label>
											<div class="col-md-10 col-sm-10 col-xs-12 form-group">
												<input type="email" class="form-control has-feedback-left" name="email" placeholder="Email" value="<?php echo $data_user[0]['email']; ?>" required="" style="background-color:#e2e2e2" />
												<span class="fa fa-envelope form-control-feedback left" aria-hidden="true" style="color:black"></span>
											</div>
											<label class="col-md-10 col-sm-10 col-xs-12 form-group">Current Password: </label>
													<div class="col-md-10 col-sm-10 col-xs-12 form-group">
														<input type="password" class="form-control has-feedback-left" name="current_password" id="current_password"  placeholder="Current Password" required="" style="background-color:#e2e2e2" />
														<span class="fa fa-key form-control-feedback left" aria-hidden="true" style="color:black"></span>
													</div>
													<label class="col-md-10 col-sm-10 col-xs-12 form-group">New Password: </label>
													<div class="col-md-10 col-sm-10 col-xs-12 form-group">
														<input type="password" class="form-control has-feedback-left" name="new_password" id="confirm_pass"  placeholder="New Password" required=""  style="background-color:#e2e2e2">
														<span class="fa fa-key form-control-feedback left" aria-hidden="true" style="color:black"></span>
													</div>
											<div class="col-md-10 col-sm-10 col-xs-12">
												<br>
												<button type="reset" class="btn btn-default pulse" style="font-size:17px; font-weight:bold; margin-left:10px; background-color: #f3b4b4; border: 2px solid #f98e8e"><img src="images/cancel_user.png" width="50px" >Cancel
												</button>
													<button type="submit" class="btn btn-default pulse" style="font-size:17px; font-weight:bold; background-color:#7096d8; border: 2px solid #3667bd"><img src="images/user_saved.png" width="50px">Update</button>
											  </div>
									</form>
								 </div>
							  </div>
							</div>

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
				
				$("#userEdit").on('submit',(function(e) {

          			e.preventDefault();
					$.ajax({
						url: "model/user_account.php",
						type: "POST",
						data: new FormData(this),
						contentType: false,
						cache: false,
						processData:false,
						success: function(data){
							console.log(data);
							if(data == "true") {
								toastr.info("User details is successfully updated.");
								setTimeout(function(){ location.reload(); }, 1000);
							}
							
							else {
								toastr.error("error");
							}
							$("#userEdit")[0].reset();
						},
						error: function(){
							alert("error");
						}
					});
				}));
				
				$("#accountEdit").on('submit',(function(e) {

          			e.preventDefault();
					$.ajax({
						url: "model/user_account.php",
						type: "POST",
						data: new FormData(this),
						contentType: false,
						cache: false,
						processData:false,
						success: function(data){
							console.log(data);
							if(data == "true") {
								toastr.info("Account details is successfully updated.");
								setTimeout('reloadNow()','1000');
								
							}
							else if(data == "Incorrect") {
								toastr.error("Incorrect current pasword.");
							}
							else {
								toastr.error("error");
							}
							$("#accountEdit")[0].reset();
						},
						error: function(){
							alert("error");
						}
					});
				}));
				function reloadNow() {
					location.reload();
				}
			</script>
    </body>
</html>
