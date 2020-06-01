<?php
   	require_once "model/controller.php";
	require_once "model/model.php";
	$database = new Database;
	$user = new Model;
	$sql = "SELECT * FROM tbl_employee JOIN tbl_user ON tbl_employee.user_id=tbl_user.user_id";
	$user_data = $user->displayRecord($sql);
	$auth = new Database;
	$token = $auth->generateAuth();
	$_SESSION['useradd_token'] = $token;
	$token2 = $auth->generateAuth();
	$_SESSION['user_token'] = $token2;
	$display = "";
	$_SESSION['current_page'] = "user_account.php";
	if(isset($_GET['action'])) {
		$display = $_GET['action'];
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
			hr {
				border-top: 2px solid #D3D6DA;
			}
			#title_name {
				font-size:16px; 
				font-weight: bold;
			}
		</style>
        <script>
            function showHint(str){
                if(str.length == 0){
                    document.getElementById("txtHint").innerHTML = "";
                    return;
                } else{
                    var xmlhttp = new XMLHttpRequest();
                    
                    xmlhttp.onreadystatechange = function(){
                        if(xmlhttp.readyState==4 && xmlhttp.status==200){
                            document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
                        }
                    }
                    xmlhttp.open("GET", "search.php?q=" + str, true);
                    xmlhttp.send();
                }
            }
        </script>        
    </head>
    <body class="nav-md" onload="display(); displaySetting()" onclick="resetTimer()" onmousedown="resetTimer()" onmousemove="resetTimer()" onscroll="resetTimer()" onkeypress="resetTimer()">
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
						<div class="col-md-12 col-sm-12 col-xs-12" role="tabpanel" data-example-id="togglable-tabs">
							<ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
							  <li role="presentation" class="active" id="1tab"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true"><span class="badge bg-blue "><i class="fa fa-edit"></i></span> Register</a>
							  </li>
							  <li role="presentation" class="" id="2tab"><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false"><span class="badge bg-blue "><i class="fa fa-table"></i></span> Records</a>
							  </li>
							</ul>
							<div id="myTabContent" class="tab-content">
							  <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
								  <div class="x_panel">
										  <div class="x_title">
											<img src="images/user_add.png" width="50px" height="50px">
											<h3 style="margin-left:60px; margin-top:-38px">User Registration</h3>
											<div class="clearfix"></div>
										  </div>
										  <div class="x_content">
											  <div class="row">
												  <form id="addUser">
													   <div class="col-md-12 col-sm-12 col-xs-12">
														<div class="col-md-6 col-sm-12 col-xs-12">
													<label class="col-md-12 col-sm-12 col-xs-12 form-group">Employee No: </label>
													<div class="col-md-12 col-sm-12 col-xs-12 form-group">
														<input type="text" class="form-control has-feedback-left" name="employee_no" id="employee_no" onkeyup="validation('employee_no')" onkeydown="validation('employee_no')" onmouseout="validation('employee_no')" placeholder="Employee No" required="" />
														<input type="hidden" name="action" value="add"/>
														<input type="hidden" name="useradd_token" value="<?php echo $token; ?>"/>
														<span class="fa fa-sort-numeric-asc form-control-feedback left" aria-hidden="true" style="color:black"></span>
													</div>
													<label class="col-md-12 col-sm-12 col-xs-12 form-group"> First Name: </label>
													<div class="col-md-12 col-sm-12 col-xs-12 form-group">
														<input list="list_fn" class="form-control has-feedback-left" name="firstname" id="firstname" placeholder="First Name" required="" onkeyup="validation('firstname')" onkeydown="validation('firstname')" onmouseout="validation('firstname')" style="background-color:#e2e2e2; text-transform:capitalize" />
														<span class="fa fa-user form-control-feedback left" aria-hidden="true" style="color:black"></span>
														<datalist id="list_fn" class="form-group">

														</datalist>
													</div>

													<label class="col-md-12 col-sm-12 col-xs-12 form-group"> Last Name: </label>
													<div class="col-md-12 col-sm-12 col-xs-12 form-group">
														<input list="list_ln" class="form-control has-feedback-left" name="lastname" id="lastname" onkeyup="validation('lastname')" onkeydown="validation('lastname')" onmouseout="validation('lastname')" placeholder="Last Name" required=""  style="background-color:#e2e2e2; text-transform:capitalize"/>
														<span class="fa fa-user form-control-feedback left" aria-hidden="true" style="color:black"></span>
														<datalist id="list_ln" class="form-group">

														</datalist>
													</div>
													<label class="col-md-12 col-sm-12 col-xs-12 form-group"> Gender: </label>
													<div class="col-md-12 col-sm-12 col-xs-12 form-group">
														<select class="form-control has-feedback-left" required="" name="gender" style="background-color:#e2e2e2; ">
															<option>Male</option>
															<option >Female</option>
														  </select>
														<span class="fa fa-venus-double form-control-feedback left" aria-hidden="true" style="color:black"></span>
													</div>
													<label class="col-md-12 col-sm-12 col-xs-12 form-group"> Position: </label>
													<div class="col-md-12 col-sm-12 col-xs-12 form-group">
														<input list="list_position" class="form-control has-feedback-left" name="position" id="position" onkeyup="validation('position')" onkeydown="validation('position')" onmouseout="validation('position')" placeholder="Position"  style="background-color:#e2e2e2; text-transform:capitalize" required="" />
														<span class="fa fa-briefcase form-control-feedback left" aria-hidden="true" style="color:black"></span>
														<datalist id="list_position" class="form-group">

														</datalist>
													</div>
													<label class="col-md-12 col-sm-12 col-xs-12 form-group"> Address: </label>
													<div class="col-md-12 col-sm-12 col-xs-12 form-group">
														 <textarea rows="2" required="required" class="form-control" name="address" id="address" onkeyup="validation('address')" onkeydown="validation('address')" onmouseout="validation('address')" style="background-color:#e2e2e2; text-transform:capitalize"  placeholder="Address"></textarea>
													</div>
													<label class="col-md-12 col-sm-12 col-xs-12 form-group"> Contact No: </label>
													<div class="col-md-12 col-sm-12 col-xs-12 form-group">
														<input type="text" class="form-control has-feedback-left" name="contactno" id="contactno" onkeyup="validation_two('contactno')" onkeydown="validation_two('contactno')" onmouseout="validation_two('contactno')" placeholder="Contact Number" required="" />
														<span class="fa fa-phone form-control-feedback left" aria-hidden="true" style="color:black"></span>
													</div>
												</div>
												  <div class="col-md-6 col-sm-12 col-xs-12">
													<div class="form-group photos">
														<div class="col-lg-6">
															<label class=" form-group">Choose photo <small>( Optional )</small></label>
															<center>
																<label for="file-upload" class="custom-file-upload">
																	<h3><i class="fa fa-cloud-upload"></i> Upload Photo</h3>
																</label>

																<input id="file-upload" name="user_photo" class="user_photo" type="file" accept="image/x-png,image/gif,image/jpeg">
															</center>
														</div>
														<div class="col-md-6 col-sm-12 col-xs-12">
															<center>
																<img class="pre_img" src="images/no_img.jpg" style="width: 100%; max-height: 250px;">
																<p class="image_view"></p>

																<button type="button" id="remove_photo" class="btn btn-danger pull-right form-control" style="display:none; "><span class="ladda-label">Remove?</span></button>
															</center>
														</div>
														<label class="col-md-12 col-sm-12 col-xs-12 form-group">Username: </label>
													<div class="col-md-12 col-sm-12 col-xs-12 form-group">
														<input type="text" class="form-control has-feedback-left" name="username" id="username" onkeyup="validation('username')" onkeydown="validation('username')" onmouseout="validation('username')" placeholder="Username" required="" />
														<span class="fa fa-users form-control-feedback left" aria-hidden="true" style="color:black"></span>
													</div>
													<label class="col-md-12 col-sm-12 col-xs-12 form-group">Email: </label>
													<div class="col-md-12 col-sm-12 col-xs-12 form-group">
														<input type="email" class="form-control has-feedback-left" name="email" placeholder="Email" required="" style="background-color:#e2e2e2" />
														<span class="fa fa-envelope form-control-feedback left" aria-hidden="true" style="color:black"></span>
													</div>
													<label class="col-md-12 col-sm-12 col-xs-12 form-group"> Password: </label>
													<div class="col-md-12 col-sm-12 col-xs-12 form-group">
														<input type="password" class="form-control has-feedback-left" name="password" id="password"  placeholder="Password" required="" style="background-color:#e2e2e2" />
														<span class="fa fa-key form-control-feedback left" aria-hidden="true" style="color:black"></span>
													</div>
													<label class="col-md-12 col-sm-12 col-xs-12 form-group"> Confirm Password: </label>
													<div class="col-md-12 col-sm-12 col-xs-12 form-group">
														<input type="password" class="form-control has-feedback-left" name="confirm_pass" id="confirm_pass"  placeholder="Confirm Password" required=""  style="background-color:#e2e2e2">
														<span class="fa fa-key form-control-feedback left" aria-hidden="true" style="color:black"></span>
													</div>
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
															<button type="submit" class="btn btn-default pulse" style="font-size:17px; font-weight:bold; background-color:#7096d8; border: 2px solid #3667bd"><img src="images/user_saved.png" width="50px"
															>Saved</button>
													  </div>
												  </div>
											 </form>
										</div>
									</div>
							  </div>
							  <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
								 <div class="x_panel">
								  <div class="x_title">
									 <img src="images/user_list.png"  width="50px" height="50px">
									<h3 style="margin-left:60px; margin-top:-38px">User Records</h3>
									  <div class="btn-group pull-right" style="margin-top:-38px">
										   <button type="button" class="btn btn-primary" onclick="printTable()">
											 <i class="fa fa-print"></i> Print</button>
										  <a type="button" download="Listoflostbooks.xls" onclick="return ExcellentExport.excel(this, 'table2', 'Listofemployee');" class="btn btn-primary"><i class="fa fa-file-excel-o"></i> Excel</a>
										</div>
									<div class="clearfix"></div>
								  </div>
								  <div class="x_content">
									  <div class="row">
										  <br>
										  <div class="col-md-9 col-sm-9 col-xs-9">
											<div class="input-group ">
												<input type="text" class="form-control" style="height:45px; background-color:#d3ead9;" placeholder="Search Users" id="filter">
												<span class="input-group-addon"><i class="fa fa-search" style="width:30px"></i></span>
											  </div>
										  </div>
										  <div class="col-md-3 col-sm-3 col-xs-3">
											  <a href="user_account.php" class="btn btn-default pull-right" style="margin-top:-10px; border: 2px solid #52b3a0"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Add User">
													<img src="images/user_add.png" width="50px" height="50px">
												</a>
											 <button type="button" class="btn btn-default pull-right" style="margin-top:-10px; border: 2px solid #52b3a0"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Refresh Records" id="refresh">
												<img src="images/refresh.png" width="50px" height="50px">
											 </button>
										  </div>
										  <div  class="col-md-12 col-sm-12 col-xs-12">
											  <hr>
										  </div>
										  <div class="col-md-12 col-sm-12 col-xs-12">
											  <center><h3 id="title_name">LIST OF EMPLOYEES</h3></center>
											  <br>
											  <table class="table table-bordered table-striped jambo_table">
												  <thead>
													<tr>
													<th>Image</th>
													  <th>Emp. No</th>
													  <th>Fullname</th>
													  <th>Gender</th>
													  <th>Position</th>
													  <th>Contact No</th>
													  <th>Option</th>
													</tr>
												  </thead>
												  <tbody class="searchable" id="data">

												  </tbody>
											</table>
											  <div id="" class="hide">
											  <table class="table table-bordered table-striped jambo_table" id="table2">
												<tr class="hidden-print">
													 <td colspan="8"><p style="font-size:20px">LIST OF EMPLOYEES</p></td>
												 </tr>
												  <th>Emp. No</th>
												  <th>Fullname</th>
												  <th>Gender</th>
												  <th>Position</th>
												  <th>Address</th>
												  <th>Contact No</th>
												  <th>Email</th>
												  <th>Account</th>
												</tr>
											  </thead>
											  <tbody class="searchable" id="printData" >
												  <?php
												  	if(count($user_data) > 0) {
														foreach($user_data as $value) {
															echo "
															<tr>
																<td>".$value['user_id']."</td>
																<td>".$value['firstname']." ".$value['lastname']."</td>
																<td>".$value['gender']."</td>
																<td>".$value['position']."</td>
																<td>".$value['address']."</td>
																<td>".$value['contactno']."</td>
																<td>".$value['email']."</td>
																<td>".$value['status']."</td>
															</tr>";
														}
													} 
												  ?>
											  </tbody>
											</table>
										  </div>
										  </div>
									 </div>
								</div>
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
				var d = "<?php echo $display; ?>";
				if(d != "") {
					$("#1tab").removeClass("active");
					$("#tab_content1").removeClass("active in");

					$("#2tab").addClass("active");
					$("#tab_content2").addClass("active in");
				}
				function error(message) {
					swal({
						title: 'DepED Escalante',
						text: message,
						type : 'error',
						showConfirmButton: false,
						timer: 2000
					});
				}
				
				function display(){
				
					$.ajax({
						url: "model/user_account.php",
						dataType:'json',
						type: "POST",
						data:{ action:"display" },
						success: function(data) {
							//console.log(data);
							firstname(data);
							lastname(data);
							position(data);
						},
						error: function(){
							alert("error");
						}
					});
				}
				
				function firstname(data) {
					var list = data.fname;
					var length = list.length;
					var html = "";

					for(var x = 0; x < length; x++) {
						html = "<option>" + list[x].firstname;
					}
					$("#list_fn").html(html);
				}
				function lastname(data) {
					var list = data.lname;
					var length = list.length;
					var html = "";

					for(var x = 0; x < length; x++) {
						html = "<option>" + list[x].lastname;
					}
					$("#list_ln").html(html);
				}
				function position(data) {
					var list = data.lposition;
					var length = list.length;
					var html = "";

					for(var x = 0; x < length; x++) {
						html = "<option>" + list[x].position;
					}
					$("#list_position").html(html);
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
				
				$("#addUser").on('submit',(function(e) {

          			e.preventDefault();
					var pass = $("#password").val();
					var confirmpass = $("#confirm_pass").val();
					
					if(pass == confirmpass) {
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
									toastr.info("New user was successfully added.");
								}
								else if(data == "exist") {
									toastr.error("Employee is already exist.");
								}
								else {
									toastr.error("error");
								}
								$('.photos img').hide();
								$('.pre_img').show();
								$('.book_photo').val('');
								$("#remove_photo").slideUp(100);
								$('.photos img').css('max-height','250px');
								$('.upload_photo').slideUp();
								$('.custom-file-upload').show();
								$("#addUser")[0].reset();
								display();
							},
							error: function(){

							}
						});
					}
					else {
						error("Password not match.");
					}
				}));
				
				$(document).ready(function(){
				$('#filter').keyup(function() {
				
					var rex = new RegExp($(this).val(), 'i');
					$('.searchable tr').hide();
					$('.searchable tr').filter(function() {

						return rex.test($(this).text());
					}).show();
				});
				
				$('#refresh').click(function() {
					$("#filter").val("");
					display();
				});
			});
			function printTable() {
				var response = document.getElementById("printData");
				var newWin = window.open('', 'Print-Window', 'width=1000,height=600, left=170');
				var leftlogo = settingdata[0]['left_logo'];
				var rightlogo = settingdata[0]['right_logo'];
				var line1 = settingdata[0]['line1'];
				var line2 = settingdata[0]['line2'];
				var line3 = settingdata[0]['line3'];
				var line4 = settingdata[0]['line4'];
				var line5 = settingdata[0]['line5'];
				var tel_no = settingdata[0]['tel_no'];
				var telefax_no = settingdata[0]['telefax_no'];
				var email = settingdata[0]['email'];
				var web = settingdata[0]['web'];
				
				var content = '<!DOCTYPE html>\
					<html >\
					<head>\
						<meta charset="utf-8">\
						<meta name="viewport" content="width=device-width, initial-scale=1.0">\
						<meta name="description" content="">\
						<meta name="author" content="">\
						<title>Print Records</title>\
						<link rel="stylesheet" type="text/css" href="bootstrap.css">\
						<link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">\
						<style>\
							table, th, td {\
								font-size:10px; \
								border: 1px solid black;\
							}\
							h4 { \
								font-size:12px; \
							} \
							h3 { \
								font-size:14px; \
								font-weight:bold; \
							} \
							hr {\
								border: 0;\
								border-top: 1px solid black;\
							}\
						</style>\
					</head>\
					<body onload="window.print()">\
					<div class="container">\
						<div class="row">\
							<div class="col-xs-12" style="margin-left:50px;">\
								<img src="images_uploaded/'+leftlogo+'" class="left_logo" width="70px" height="70px" style="margin-top:5px">\
							</div>\
							<div class="col-xs-12" style="">\
								<div style="">\
									<h4 style="margin-top:-70px"  align="center" id="line1">'+line1+'</h4>\
									<h4 style="margin-top:-5px"  align="center" id="line2">'+line2+'</h4>\
									<h4 style="margin-top:-5px"  align="center" id="line3">'+line3+'</h4>\
									<h3 style="margin-top:-5px"  align="center" id="line4">'+line4+'</h3>\
									<i><h4 style="margin-top:-5px; margin-bottom:-20px"  align="center" id="line5">'+line5+'</h4></i>\
									<hr style="color:black">\
									<h6 style="margin-top:-18px; font-size:9px;" id="line6">\
										<span id="tel_no" style="margin-left:1%">Tel. No.' + tel_no + '</span>\
										<span id="telefax_no" style="margin-left:15%">Telefax No.' + telefax_no + '</span>\
										<span id="email" style="margin-left:10%">Email: ' + email + '</span>\
										<span id="web" style="margin-left:10%">Web: ' + web + '</span>\
									</h6>\
								</div>\
								<div class="col-xs-12" style="margin-top:-108px; margin-left:-40px">\
									<img src="images_uploaded/'+rightlogo+'" class="pull-right  right_logo" width="70px" height="70px" id="rl">\
								</div>\
							</div>\
							<div class="col-xs-12">\
								<br>\
								 <center><h3 id="title_name">LIST OF EMPLOYESS</h3></center>\
						  		<br>\
								<table class="table">\
									<thead>\
									  <th style="border: 1px solid black">Emp. No</th>\
									  <th style="border: 1px solid black">Fullname</th>\
									  <th style="border: 1px solid black">Gender</th>\
									  <th style="border: 1px solid black">Position</th>\
									  <th style="border: 1px solid black">Address</th>\
									  <th style="border: 1px solid black">Contact No</th>\
									  <th style="border: 1px solid black">Email</th>\
									  <th style="border: 1px solid black">Account</th>\
									<thead>\
								<tbody style="border: 1px solid black">' + response.innerHTML + '</tbody>\
								</table>\
							</div>\
						</div>\
					</div>\
					</body>\
					</html>';

				newWin.document.open();
				newWin.document.write(content);
				newWin.document.close();

				 setTimeout(function() {
					 newWin.close();
				 }, 2000);
			}
			function displaySetting() {
				$.ajax({
					url: "model/setting.php",
					dataType:'json',
					type: "POST",
					data:{ 
						action:"display",
					},
					success: function(data) {
						console.log(data);
						var list = data.data;
						settingdata = list;
					},
					error: function(){
						alert("error");
					}
				});
			}
			function display(){
				//window.localStorage.setItem('lesson', "hello");
				$.ajax({
					url: "model/user_account.php",
					dataType:'json',
					type: "POST",
					data:{ action:"display" },
					success: function(data) {
						console.log(data);
						records(data);
					},
					error: function(){
						alert("error");
					}
				});
			}
			
			function records(data) {
				
				var list = data.data;
				var length = list.length;
				var count = 0;
				var html = "";
				var label = "";
				var name = "";
				var image = "";
				var token = "<?php echo $token2; ?>";
				
				for(var x = 0; x < length; x++) {
					
					count++;
					if(list[x].status == "Active") {
						label = "label-success";
					}
					else {
						label = "label-warning";
					}
					image = "images_uploaded/"+list[x].image;
					
					
					html = html + 
					'<tr>' +
						'<td><img src="'+image+'" style="width: 45px; height: 40px; padding: 2px; border: 2px solid #94979c; border-radius: 50%;"></td>' +
						'<td>' + list[x].user_id + '</td>' +
						'<td> ' + list[x].lastname + ", " + list[x].firstname + "  "+"<span class='label "+label+"'>" + list[x].status + "</span>" + '</td>' +
						'<td>' + list[x].gender + '</td>' +
						'<td>' + list[x].position + '</td>' +
						'<td>' + list[x].contactno + '</td>' +
						'<td>\
						<div class="btn-group">\
						  <a href="user_view.php? user_id='+list[x].user_id+' & user_token='+token+'" class="btn btn-primary btn-xs" >\
							 <i class="fa fa-folder"></i> View</a>\
							<a href="user_edit.php? user_id='+list[x].user_id+' & user_token='+token+'" class="btn btn-success btn-xs" >\
							 <i class="fa fa-edit"></i> Edit</a>\
						</div>\
						</td>';
				}
				
				$("#data").html(html);
			}
			
		</script>
    </body>
</html>
