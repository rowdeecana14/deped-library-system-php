<?php
	require_once "model/controller.php";
	$_SESSION['current_page'] = "settings.php";
	if($record[0]['role'] == 1) {
		 
	}
	else {
		 header("location: dashboard.php");
	}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>E-Library</title>
		<?php require_once('include/css.php');  ?>
		<style>
			hr {
				margin-top:-10px;
				margin-bottom:-10px;
				border: 0;
				border-top: 2px solid black;
			}
		</style>
    </head>
    
    <script language="javascript" type="text/javascript">
		function previewImage() {
			document.getElementById("image-preview").style.display = "block";
			var oFReader = new FileReader();
			 oFReader.readAsDataURL(document.getElementById("image-source").files[0]);

			oFReader.onload = function(oFREvent) {
			  document.getElementById("image-preview").src = oFREvent.target.result;
			};
		  };
		function previewImage1() {
			document.getElementById("image-preview1").style.display = "block";
			var oFReader = new FileReader();
			 oFReader.readAsDataURL(document.getElementById("image-source1").files[0]);

			oFReader.onload = function(oFREvent) {
			  document.getElementById("image-preview1").src = oFREvent.target.result;
			};
		  };
    </script>
    
    <body class="nav-md" onload="display()" onclick="resetTimer()" onmousedown="resetTimer()" onmousemove="resetTimer()" onscroll="resetTimer()" onkeypress="resetTimer()">
        <div class="container body">
            <div class="main_container">
              	<?php
                    require_once('include/sidemenu.php'); 
                    require_once('include/topnav.php');
                ?>
                
                <!-- page content -->
                <div class="right_col" role="main" >
                    <div class="">
                       
                        <div class="clearfix"></div>
                        
                        <div class="row">
                            <div class="clearfix"></div>
                            <div class="col-md-4 col-sm-6 col-xs-12" >
                                <div class="x_panel" style="background-color: rgba(222, 220, 234, 0.8); ">
                                    <div class="x_title">
                                        <h2><img src="images/image.png" width="40px" height="40px"> System Logo</h2>
                                        <ul class="nav navbar-right panel_toolbox">
                                            <li><button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target=".bs-example-modal-md"><i class='glyphicon glyphicon-picture'> Change</i></button></li>
                                           
                                        </ul>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <div class="col-md-12 col-sm-12 col-xs-12"  style="margin-bottom:115px;">
                                            <br><br>
											<center>
												<img style="width: 70%; height: 70%;" class="display_logo">
											</center>
                                        </div>
                                    </div>
                                </div>
								<form id="logoform" enctype="multipart/form-data">
								<div class="modal fade bs-example-modal-md" tabindex="-1" role="dialog" aria-hidden="true">
									<div class="modal-dialog modal-md">
									  <div class="modal-content">
										<div class="modal-header">
										  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
										  </button>
										  <h4 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-picture"></i> Change Logo</h4>
										</div>
										<div class="modal-body">
											<center>
												<input type="hidden" name="action" value="updatelogo">
												<img class="display_logo" id="image-preview" style="width:260px; height: 260px; background-color: black; "/>
												<input type="file" class="form-control input-lg" id="image-source" style="width:300px; background-color:#e2e2e2; " onchange="previewImage();" name="logo"  accept="image/png, image/jpeg, image/gif"  required>
											</center>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cancel</button>
											<button type="submit" class="btn btn-primary"><i class="fa fa-check-square-o"></i> Saved</button>
										</div>
									  </div>
									</div>
								  </div>
								</form>
                       		</div>
                            <!-- form input mask -->
                            <div class="col-md-8 col-sm-6 col-xs-12">
                                <div class="x_panel" style="background-color: rgba(222, 220, 234, 0.8); ">
                                    <div class="x_title">
                                        <h2><img src="images/form.png" width="40px" height="40px">  Department Name & System Name</h2>
                                        <ul class="nav navbar-right panel_toolbox">
                                            <li><button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target=".bs-example-modal-2"><i class='fa fa-edit'> Change</i></button></li>
                                        </ul>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <div class="col-md-12 col-sm-12 col-xs-12" style="height: 350px;">
											<label style="font-size:20px">Department Name:</label>
                                            <h1 class="display_name2" style="font-family: Elephant; font-size: 50px; " align="center"></h1>
											<br>
											<label style="font-size:20px">System Name:</label>
											<h1 class="display_system" style="font-family: Elephant; font-size: 50px;" align="center"></h1>
                                        </div>                  
                                    </div>
                                </div>
                                <div class="modal fade bs-example-modal-2" tabindex="-1" role="dialog" aria-hidden="true">
                                    <form id="nameform">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                                                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-edit"></i> Change Name</h4>
                                                </div>
                                                <div class="modal-body">
													<input type="hidden" name="action" value="updatename">
													 <label class="form-group">Department Name: </label>
													<div class="form-group">
														<textarea rows="3" required="required" class="form-control display_name" name="name" id="name" style="background-color:#e2e2e2;" placeholder="Department Name"></textarea>
													</div>
													 <label class="form-group">System Name: </label>
													<div class="form-group">
														<textarea rows="3" required="required" class="form-control display_system" name="system" id="system" style="background-color:#e2e2e2;" placeholder="System Name"></textarea>
													</div>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cancel</button>
													<button type="submit" class="btn btn-primary"><i class="fa fa-check-square-o"></i> Saved</button>
												</div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
							
							<div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="x_panel" style="background-color: rgba(222, 220, 234, 0.8); ">
                                    <div class="x_title">
                                        <h2><img src="images/print.png" width="40px" height="40px"> Print Header</h2>
                                        <ul class="nav navbar-right panel_toolbox">
                                            <li><button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target=".header"><i class='fa fa-edit'> Change</i></button></li>
                                        </ul>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <div class="col-md-12 col-sm-12 col-xs-12" style="height: 350px;">
											<img class="left_logo" width="100px" height="100px" style="margin-top:50px">
											<div style="margin-top:-140px">
												<h4 style="color:black"  align="center" id="line1"></h4>
												<h4 style="color:black"  align="center" id="line2"></h4>
												<h4 style="color:black"  align="center" id="line3"></h4>
												<h3 style="color:black"  align="center" id="line4"></h3>
												<i><h4 style="color:black"  align="center" id="line5"></h4></i>
												<hr style="color:black">
												<h6 style="color:black; margin-top:15px; font-size:9px; font-weight:bold" id="">
													<span id="tel_no" style="margin-left:1%"></span>
													<span id="telefax_no" style="margin-left:15%"></span>
													<span id="email" style="margin-left:21%"></span>
													<span id="web" style="margin-left:10%"></span>
												</h6>
											</div>
											<img class="right_logo" width="100px" height="100px" id="rl">
                                        </div>                  
                                    </div>
                                </div>
                                <div class="modal fade header" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                                                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-print"></i> Change Header</h4>
                                                </div>
                                                <div class="modal-body">
													<div class="" role="tabpanel" data-example-id="togglable-tabs"  >
														<ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
														  <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Header</a>
														  </li>
														  <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Left Logo</a>
														  </li>
															<li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Right Logo</a>
														  </li>
														</ul>
														<div id="myTabContent" class="tab-content">
														  <div role="tabpanel" class="tab-pane fade active in"  id="tab_content1" aria-labelledby="home-tab">
															  <div class="x_panel" >
																  <div class="x_content">
                                    								<form id="headerform">
																	  <div class="row"><br>
																		  <label class="form-group">First Line: </label>
																		<div class="form-group">
																			<input type="hidden" name="action" value="updateheader">
																			<input type="text" class="form-control line1" name="line1" id="" placeholder="First Line" style="background-color:#e2e2e2;" required="" />
																		</div>
																		<label class="form-group">Second Line: </label>
																		<div class="form-group">
																			<input type="text" class="form-control line2" name="line2" id="" placeholder="Second Line" style="background-color:#e2e2e2;" required="" />
																		</div>
																		<label class="form-group">Third Line: </label>
																		<div class="form-group">
																			<input type="text" class="form-control line3" name="line3" id="" placeholder="Third Line" style="background-color:#e2e2e2;" required="" />
																		</div>
																		<label class="form-group">Fourth Line: </label>
																		<div class="form-group">
																			<input type="text" class="form-control line4" name="line4" id="" placeholder="Fourth Line" style="background-color:#e2e2e2;" required="" />
																		</div>
																		<label class="form-group">Fifth Line: </label>
																		<div class="form-group">
																			<input type="text" class="form-control line5" name="line5" id="" placeholder="Fifth Line" style="background-color:#e2e2e2;" required="" />
																		</div>
																		<label class="form-group">Six Line: </label>
																		<div class="form-group">
																			<input type="text" class="form-control tel_no" name="tel_no" id="" placeholder="Telephone No." style="background-color:#e2e2e2;" required="" />
																			<input type="text" class="form-control telefax_no" name="telefax_no" id="" placeholder="Telefax No." style="background-color:#e2e2e2;" required="" />
																			<input type="text" class="form-control email" name="email" id="" placeholder="Email" style="background-color:#e2e2e2;" required="" />
																			<input type="text" class="form-control web" name="web" id="" placeholder="Website" style="background-color:#e2e2e2;" required="" />
																			
																		</div>
																	</div>
																	  <div class="pull-right margin">
																		  <br>
																		  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cancel</button>
																			<button type="submit" class="btn btn-primary"><i class="fa fa-check-square-o"></i> Saved</button>
																	  </div>
																	 </form>
																</div>
															  </div>
														  </div>
														  <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
															  <div class="x_panel" >
																  <div class="x_content">
																	  <form id="leftlogo">
																	   <div class="row">
																		<center>
																			<input type="hidden" name="action" value="updatellogo">
																			<img class="left_logo" id="image-preview" style="width:260px; height: 260px; background-color: black; "/>
																			<input type="file" class="form-control input-lg" id="image-source" style="width:300px; background-color:#e2e2e2; " onchange="previewImage();" name="logo"  accept="image/png, image/jpeg, image/gif"  required>
																		</center>
																	  </div>
																		<div class="margin">
																		  <br>
																			<center>
																				  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cancel</button>
																				<button type="submit" class="btn btn-primary"><i class="fa fa-check-square-o"></i> Saved</button>
																			</center>
																	  </div>
																	  </form>
																	</div>
																 </div>
																</div>
															<div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab">
															  <div class="x_panel" >
																  <div class="x_content">
																	  <form id="rightlogo">
																	   <div class="row">
																		<center>
																			<input type="hidden" name="action" value="updaterlogo">
																			<img class="right_logo" id="image-preview" style="width:260px; height: 260px; background-color: black; "/>
																			<input type="file" class="form-control input-lg" id="image-source" style="width:300px; background-color:#e2e2e2; " onchange="previewImage();" name="logo"  accept="image/png, image/jpeg, image/gif"  required>
																		</center>
																	  </div>
																		<div class="margin">
																		  <br>
																			<center>
																				  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cancel</button>
																				<button type="submit" class="btn btn-primary"><i class="fa fa-check-square-o"></i> Saved</button>
																			</center>
																	  </div>
																	  </form>
																	</div>
																 </div>
																</div>
														  </div>
													  </div>
												</div>
												<div class="modal-footer">
												
												</div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <?php include'include/footer.php';?>
            </div>
        </div>
		 <?php include'include/js.php';?>
		<script type="text/javascript">
			
			function error(message) {
				swal({
					title: 'DepED Escalante',
					text: message,
					type : 'error',
					showConfirmButton: false,
					timer: 2000
				});
			}
			
			function display() {
				$.ajax({
					url: "model/setting.php",
					dataType:'json',
					type: "POST",
					data:{ 
						action:"display",
					},
					success: function(data) {
						var list = data.data;
						$(".display_logo").attr("src", "images_uploaded/"+list[0]['logo']);
						$(".left_logo").attr("src", "images_uploaded/"+list[0]['left_logo']);
						$(".right_logo").attr("src", "images_uploaded/"+list[0]['right_logo']);
						$("#rl").addClass("pull-right");
						$("#rl").css("margin-top", "-130px");
						$(".display_name").val(list[0]['school_name']);
						$(".display_name2").text(list[0]['school_name']);
						$(".display_system").text(list[0]['system_name']);
						$("#line1").text(list[0]['line1']);
						$("#line2").text(list[0]['line2']);
						$("#line3").text(list[0]['line3']);
						$("#line4").text(list[0]['line4']);
						$("#line5").text(list[0]['line5']);
						$(".line1").val(list[0]['line1']);
						$(".line2").val(list[0]['line2']);
						$(".line3").val(list[0]['line3']);
						$(".line4").val(list[0]['line4']);
						$(".line5").val(list[0]['line5']);
						
						$("#tel_no").text('Tel. No. ' + list[0]['tel_no']);
						$("#telefax_no").text('Telefax. No. ' +list[0]['telefax_no']);
						$("#email").text('Email: ' +list[0]['email']);
						$("#web").text('web: ' +list[0]['web']);
						
						$(".tel_no").val(list[0]['tel_no']);
						$(".telefax_no").val(list[0]['telefax_no']);
						$(".email").val(list[0]['email']);
						$(".web").val(list[0]['web']);
					},
					error: function(){
						alert("error");
					}
				});
			}

			
			$("#logoform").on('submit',(function(e) {

				e.preventDefault();
				$.ajax({
					url: "model/setting.php",
					type: "POST",
					data: new FormData(this),
					contentType: false,
					cache: false,
					processData:false,
					success: function(data){
						if(data == "true") {
							toastr.info("Logo was successfuly changed.");
						}
						else if(data = "false") {
							error("Logo not changed")
						}
						else {
							error("There was an error.");
						}
						display();
						$(".bs-example-modal-md").modal('hide');
					},
					error: function(){
						error("error");
					}
				});
			}));
			
			$("#leftlogo").on('submit',(function(e) {

				e.preventDefault();
				$.ajax({
					url: "model/setting.php",
					type: "POST",
					data: new FormData(this),
					contentType: false,
					cache: false,
					processData:false,
					success: function(data){
						console.log(data);
						if(data == "true") {
							toastr.info("Left header logo was successfuly changed.");
						}
						else if(data = "false") {
							error("Left header logo not changed")
						}
						else {
							error("There was an error.");
						}
						display();
						$(".header").modal('hide');
					},
					error: function(){
						error("error");
					}
				});
			}));
			$("#rightlogo").on('submit',(function(e) {

				e.preventDefault();
				$.ajax({
					url: "model/setting.php",
					type: "POST",
					data: new FormData(this),
					contentType: false,
					cache: false,
					processData:false,
					success: function(data){
						console.log(data);
						if(data == "true") {
							toastr.info("Right header logo is successfuly changed.");
						}
						else if(data = "false") {
							error("Right header logo not changed")
						}
						else {
							error("There was an error.");
						}
						display();
						$(".header").modal('hide');
					},
					error: function(){
						error("error");
					}
				});
			}));
			
			$("#nameform").on('submit',(function(e) {

				e.preventDefault();
				$.ajax({
					url: "model/setting.php",
					type: "POST",
					data:{ 
						action:"updatename",
						name: $("#name").val(),
						system: $("#system").val()
					},
					success: function(data) {
						console.log(data);
						if(data == "true") {
							toastr.info("Name was successfuly changed.");
						}
						else if(data = "false") {
							error("Name not changed")
						}
						else {
							error("There was an error.");
						}
						display();
						$(".bs-example-modal-2").modal("hide");
					},
					error: function(){
						alert("error");
					}
				});
			}));
			
			function setdefault() {
				$.ajax({
					url: "model/setting.php",
					type: "POST",
					data:{ 
						action:"default",
					},
					success: function(data) {
						console.log(data);
						if(data == "true") {
							toastr.info("Background image was succesfully reset.");
						}
						else if(data = "false") {
							error("Background image not reseted.")
						}
						else {
							error("There was an error.");
						}
						display();
					},
					error: function(){
						alert("error");
					}
				});
			}
			$("#headerform").on('submit',(function(e) {

				e.preventDefault();
				$.ajax({
					url: "model/setting.php",
					type: "POST",
					data: new FormData(this),
					contentType: false,
					cache: false,
					processData:false,
					success: function(data){
						console.log(data);
						if(data == "true") {
							toastr.info("Header was successfuly changed.");
						}
						else if(data = "false") {
							error("Header not changed")
						}
						else {
							error("There was an error.");
						}
						display();
						$(".header").modal('hide');
					},
					error: function(){
						error("error");
					}
				});
			}));
		</script>
    </body>
</html>
