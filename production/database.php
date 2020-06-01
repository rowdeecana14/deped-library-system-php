<?php
	require_once "model/controller.php";
	require_once "model/model.php";
	require_once "db_backup.php";
	$dbbackup = new db_backup;
	$setting = new Model;
	$sql = "SELECT * FROM tbl_backup ORDER BY date DESC";
	$result = $setting->displayRecord($sql);
	$_SESSION['current_page'] = "database.php";
	if(isset($_POST['download'])) {
		$dbbackup = new db_backup;
		$dbbackup->backup();
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
		
    </head>
   
    <body class="nav-md" onclick="resetTimer()" onmousedown="resetTimer()" onmousemove="resetTimer()" onscroll="resetTimer()" onkeypress="resetTimer()">
        <div class="container body">
            <div class="main_container">
              	<?php
                    require_once('include/sidemenu.php'); 
                    require_once('include/topnav.php');
                ?>
                <div class="right_col" role="main" >
                    <div class="">
                        <div class="clearfix"></div>
                        <div class="row">
							<div class="col-md-4 col-sm-4 col-xs-4">
							<div class="x_panel" style="background-color: rgba(222, 220, 234, 0.8);">
							  <div class="x_title">
								<h2><i class="fa fa-refresh"></i> Reset database</h2>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">
								  <div class="row">
									  <br><br>
									  <center>
										  <img src="images/reset.png">
										  <br><br>
											 <button type="submit" class="btn btn-danger" onclick="reset_now()"><i class="fa fa-refresh"></i> Reset now</button>
									  </center>
									  <br>
									</div>
								</div>
							  </div>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-4">
							<div class="x_panel" style="background-color: rgba(222, 220, 234, 0.8);">
							  <div class="x_title">
								<h2><i class="fa fa-upload"></i> Import database</h2>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">
								  <div class="row">
									  <br><br>
									  <center>
										  <img src="images/im.png">
										  <br><br>
											 <button  class="btn btn-warning" data-toggle="modal" data-target="#import_modal"><i class="fa fa-hand-o-up"></i> Select file</button>
									  </center>
									  <br>
									  <form id="import_form" enctype="multipart/form-data">
										<div class="modal fade" id="import_modal" tabindex="-1" role="dialog" aria-hidden="true">
										<div class="modal-dialog modal-md">
										  <div class="modal-content">
											<div class="modal-header">
											  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
											  </button>
											  <h3 class="modal-title" id="myModalLabel" style="font-size:18px"><img src="images/upload.png" width="35px" height="35px"> Import data</h3>
											</div>
											<div class="modal-body">
												<div class="row">
													<div class=" form-group">
														<label class="form-group  input-lg"><img src="images/sql.png" width="50px" height="50px"> Choose (.sql) file: </label>
													</div>
													<div class="col-md-12 col-sm-12 col-xs-12">
														<div class="col-md-12 col-sm-12 col-xs-12">
															<div class="form-group">
																<input type="hidden" name="action" value="import">
																<input type="file" class="form-control" id="file" style=" background-color:#e2e2e2; " name="file"  accept=".sql"  required>
															</div>
														</div>
													</div>
												</div>
												
												<br>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cancel</button>
												<button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Import</button>
											</div>
										  </div>
										</div>
									  </div>
									</form>
									</div>
								</div>
							  </div>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-4">
							<div class="x_panel" style="background-color: rgba(222, 220, 234, 0.8);">
							  <div class="x_title">
								<h2><i class="fa fa-download"></i> Backup database</h2>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">
								  <div class="row">
									   <br><br>
									  <center>
										  <img src="images/download.png">
										  <br><br>
										  <form method="post">
											   <button type="submit" class="btn btn-primary" name="download"><i class="fa fa-download"></i> Download </button>
										  </form>
									  </center>
									  <br>
									</div>
								</div>
							  </div>
							</div>
							
							<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel" style="">
							  <div class="x_title">
								<h2><i class="fa fa-folder"></i> List of backup</h2>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">
								  <div class="row">
									  <div class="col-md-11 col-sm-11 col-xs-11">
									   <table class="table table-bordered table-striped jambo_table" style="font-size:14px">
										  <thead>
											<tr>
											  <th >No</th>
											  <th>File name</th>
												<th>Date backup</th>
											  	<th>File size</th>
												<th>Option</th>
											</tr>
										  </thead>
										  <tbody>
										  <?php
											$count = 0;
										  	$table = '';
											  $dbbackup = new db_backup;
											 if(count($result)>0) {
												 foreach ($result as $value) {
													$count++;
													$link = "myBackups/".$value['file_name'];
													$file = "./myBackups/".$value['file_name'];
													$date = date("F d Y h:i A", strtotime($value['date']));
													$sized = $dbbackup->get_file_size_unit(filesize($file));
													$table.= '<tr>
														<td>'.$count.'</td>
														<td>'.$value['file_name'].'</td>
														<td>'.$date.'</td>
														<td>'.$sized.'</td>
														<td><a href="'.$link.'" class="btn btn-primary btn-xs"><i class="fa fa-download"></i> Download</a></td>
													</tr>';
												 }
											 }
											echo $table;
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
                <?php include'include/footer.php';?>
            </div>
        </div>
		 <?php include'include/js.php';?>
		<script type="text/javascript">
			var loading = false;
			
			function reset_now() {
				loading = true;
				$("#import_modal").modal("hide");
				var jc = $.alert({
					title: 'Please wait...',
					draggable: false,
					icon: 'fa fa fa-hourglass-2',
					theme: 'bootstrap',
					 type: 'green',
					content: '<p><b>Avoid cancelation it can couse fatal error.</b></p><br><center><img src="images/loading.gif" width="100px" height=100px" style="opacity:0.8"></center><br><br>'
				});
				jc.open();
				$(".jconfirm-buttons").hide();
				$.ajax({
					url: "model/setting.php",
					type: "POST",
					data:{ 
						action:"reset"
					},
					success: function(data) {
						console.log(data);
						if(data == "true") {
							toastr.info('', 'System was successfully reseted.');
							jc.close();
							setTimeout(function(){ location.reload(); }, 2000);
							loading = false;
						}
						else {
							toastr.info('', 'System not reseted.');
							jc.close();
							setTimeout(function(){ location.reload(); }, 2000);
							loading = false;
						}
					},
					error: function(){
						toastr.info('', 'System not successfully reseted.');
						jc.close();
						setTimeout(function(){ location.reload(); }, 2000);
						loading = false;
					}
				});
			}
			$("#import_form").on('submit',(function(e) {
				e.preventDefault();
				loading = true;
				$("#import_modal").modal("hide");
				var jc = $.alert({
					title: 'Please wait...',
					draggable: false,
					icon: 'fa fa fa-hourglass-2',
					theme: 'bootstrap',
					 type: 'green',
					content: '<p><b>Avoid cancelation it can couse fatal error.</b></p><br><center><img src="images/loading.gif" width="100px" height=100px" style="opacity:0.8"></center><br><br>'
				});
				jc.open();
				$(".jconfirm-buttons").hide();
				$.ajax({
					url: "model/setting.php",
					type: "POST",
					data: new FormData(this),
					contentType: false,
					cache: false,
					processData:false,
					success: function(result){
						console.log(result);
						toastr.info('', 'Files was successfully imported.');
						jc.close();
						setTimeout(function(){ location.reload(); }, 2000);
						loading = false;
						
					}
				});
			}));
		</script>
    </body>
</html>
