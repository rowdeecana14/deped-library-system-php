<?php
    require_once "model/controller.php";
	require_once "model/model.php";
	$borrowed = new Model;
	$database = new Database;
	date_default_timezone_set('Asia/Manila');
	$date = date("m/d/Y");
	$_SESSION['current_page'] = "books_return.php";
	$sql = "SELECT tbl_borrowed.borrower_id, tbl_borrowers.firstname, tbl_borrowers.lastname, tbl_borrowers.schoolname, tbl_borrowed.date_borrowed, COUNT(tbl_borrowed.account_no) AS total FROM tbl_borrowed JOIN tbl_borrowers ON tbl_borrowed.borrower_id=tbl_borrowers.borrower_id WHERE tbl_borrowed.remarks='Borrowed' GROUP BY tbl_borrowed.borrower_id";
	$borrowed_data = $borrowed->displayRecord($sql);

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
			hr {
				border-top: 2px solid #D3D6DA;
			}
			#title_name {
				font-size:16px; 
				font-weight: bold;
			}
		</style>
    </head>
    <body class="nav-md" onload="" onclick="resetTimer()" onmousedown="resetTimer()" onmousemove="resetTimer()" onscroll="resetTimer()" onkeypress="resetTimer()">
        <div class="container body">
            <div class="main_container">
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
						 <img src="images/book_return.png" width="50px" height="50px">
						<h3 style="margin-left:60px; margin-top:-35px">Returned books</h3>
						<div class="clearfix"></div>
					  </div>
					  <div class="x_content">
						  <div class="row">
							  <br>
							  <div class="col-md-9 col-sm-9 col-xs-9">
								<div class="input-group ">
									<input type="text" class="form-control" style="height:45px; background-color:#d3ead9" placeholder="Search Books" id="filter">
									<span class="input-group-addon"><i class="fa fa-search" style="width:30px"></i></span>
								  </div>
							  </div>
							  <div  class="col-md-12 col-sm-12 col-xs-12">
								  <hr>
							  </div>
							  <div class="col-md-12 col-sm-12 col-xs-12">
								 <center><h3 id="title_name">LIST OF BORROWERS</h3></center>
									  <br>
										<table class="table table-bordered table-striped jambo_table">
										  <thead>
											<tr>
											  <th width="">No.</th>
											  <th width="">Borrower's Name</th>
											  <th width="">School Name</th>
											  <th width="">Total Books</th>
											  <th width="">Date Borrowed</th>
											  <th width="">Action</th>
											</tr>
										  </thead>
										  <tbody class="searchable" id="printData">
										   <?php
												$record = "";
												$count = 0;
												foreach($borrowed_data as $value) {

													$count++;
													$borrower_id = $value['borrower_id'];
													$fullname = $value['firstname'].' '.$value['lastname'];
											?>
													<tr>
														<td><?php echo $count; ?></td>
														<td><?php echo $fullname; ?></td>
														<td><?php echo $value['schoolname']; ?></td>
														<td><?php echo $value['total'].' copy/copies';?></td>
														<td ><?php echo date('M d,Y',strtotime($value['date_borrowed'])); ?></td>
														<td><button type="button" class="btn btn-primary" onclick="returnModal('<?php echo $borrower_id; ?>','<?php echo $fullname; ?>')"><i class="glyphicon glyphicon-log-in"></i> Return</button></td>
													</tr>
												
											  <?php
													}
											  if($count == 0) {
												  echo '<tr class="danger"><td colspan="8"><h3 class="text-center">No records available.</h3></td></tr>';
											  }
										  ?>
										  </tbody>
									</table>
							  </div>
							  <form id="returnForm">
								  <div class="modal fade" id="myModal" role="dialog">
									<div class="modal-dialog modal-lg">
									  <div class="modal-content">
										<div class="modal-header">
										  <button type="button" class="close" data-dismiss="modal">&times;</button>
										  <h4 class="modal-title" id="myModalLabel2" style="font-weight:bold"><i class="fa fa-edit"></i> Return Form</h4>
										</div>
										<div class="modal-body">
											<div class="row">
													<div class="col-md-6 col-sm-6 col-xs-6">
														<label class="col-md-12 col-sm-12 col-xs-12 form-group">Borrower: </label>
														<div class="col-md-12 col-sm-12 col-xs-12 form-group">
															<input type="hidden" class="form-control has-feedback-left" name="borrower_id" id="borrower_id" required readonly />
															<input type="text" class="form-control has-feedback-left" name="borrower" id="borrower" required readonly />
															<span class="fa fa-user form-control-feedback left" aria-hidden="true" style="color:black"></span>
														</div>
													</div>
													<div class="col-md-6 col-sm-6 col-xs-6">
														<label class="col-md-12 col-sm-12 col-xs-12 xdisplay_inputx form-group">Date Return: </label>
														<div class="col-md-12 col-sm-12 col-xs-12 form-group">
															<input type="text" class="form-control has-feedback-left" name="date_return" id="single_cal4" placeholder="Date Borrowed" value="<?php echo $date; ?>" required />
															<span class="fa fa-calendar form-control-feedback left" aria-hidden="true" style="color:black"></span>
														</div>
													</div>
													<div class="col-md-12 col-sm-12 col-xs-12">
														<div class="col-md-12 col-sm-12 col-xs-12">
															<h4 style="font-weight:bold">List of books</h4>
															<table class="table table-bordered table-striped jambo_table">
															  <thead>
																<tr>
																  <th width="5%">No</th>
																  <th width="15%">Acc. No</th>
																  <th width="40%">Book Title</th>
																  <th width="20%">Author Name</th>
																<th width="15%">Remarks</th>
																<th width="5%"></th>
															  </thead>
															  <tbody class="searchable" id="borrowed_data">
															</tbody>
														</table>
														</div>
												</div>
												
											</div>
										</div>
										<div class="modal-footer">
										  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
											 <button type="submit" class="btn btn-primary"><i class="fa fa-save"> Save</i></button>
										</div>

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

         <?php include'include/js.php';?>
		<script type="text/javascript">
			function returnModal(borrower_id, fullname) {
				var jc = $.alert({
					title: 'Please wait...',
					draggable: false,
					icon: 'fa fa fa-hourglass-2',
					theme: 'bootstrap',
					 type: 'green',
					content: '<br><center><img src="images/loading.gif" width="100px" height=100px" style="opacity:0.5dpx"></center><br>'
				});
				jc.open();
				$(".jconfirm-buttons").hide();
				
				
				$("#borrower").val(fullname);
				$("#borrower_id").val(borrower_id);
				
				$.ajax({
					url: "model/return.php",
					dataType:'json',
					type: "POST",
					data:{ 
						action:"display_borrowed",
						borrower_id: borrower_id,
					},
					success: function(data) {
						console.log(data);
						jc.close();
						$("#myModal").modal("show");
						records(data);
						
					},
					error: function(){
						alert("error");
						jc.close();
					}
				});
			}
			function records(data) {
				
				var record = data.data;
				var length = record.length;
				var html = "";
				var count = 0;

				for(var x = 0; x < length; x++) {
					count++;
					html = html + 
					'<tr>' +
						'<td>' + count + '</td>' +
						'<td>' + record[x].account_no + '</td>' +
						'<td>' + record[x].title + '</td>' +
						'<td>' + record[x].author + '</td>' +
						'<td><center>' +
							'<select class="form-control" id="remark_'+record[x].borrowed_id+'">\
								<option selected>Okay</option>\
								<option >Damaged</option>\
								<option >Lost</option>\
							</select>' +
						'</center>' +
						'</td>' +
						'<td><center>' +
								'<input type="checkbox" value="'+record[x].borrowed_id+'" class="flat checkitem">' +
							'</center>' +
						'</td>' +
					'</tr>';
				}
				if(length > 0) {
					$("#borrowed_data").html(html);
				}
				$('#single_cal4').daterangepicker({
				  singleDatePicker: true,
				  singleClasses: "picker_4"
				}, function(start, end, label) {
				  console.log(start.toISOString(), end.toISOString(), label);
				});
				$('.checkitem').iCheck({
					checkboxClass: 'icheckbox_flat-green'
			  	});
			}
			
			$("#returnForm").on('submit',(function(e) {
				
				e.preventDefault();
				var borrowed_id = [];
				var remarks_list = [];
				
				$(':checkbox:checked').each(function(){
					var get_id = $(this).val();
					var remark = $("#remark_"+get_id).val();
					remarks_list.push(remark);
					borrowed_id.push(get_id);
				});
				var total = borrowed_id.length;
				if(total > 0) {
					var jc = $.alert({
						title: 'Please wait...',
						draggable: false,
						icon: 'fa fa fa-hourglass-2',
						theme: 'bootstrap',
						 type: 'green',
						content: '<br><center><img src="images/loading.gif" width="100px" height=100px" style="opacity:0.9px"></center><br><br>'
					});
					jc.open();
					$(".jconfirm-buttons").hide();

					$.ajax({
						url: "model/return.php",
						dataType:'json',
						type: "POST",
						data:{ 
							action:"return",
							borrowed_id: borrowed_id,
							date_return: $("#single_cal4").val(),
							remark_list: remarks_list
						},
						success: function(data) {
							console.log(data);
							if(data.data == "true") {
								jc.close();
								toastr.info("Books are successfully returned.");
								setTimeout(function(){ location.reload(); }, 1500);
							}
							else if(data.data == "false") {
								jc.close();
								alert("Books are not return.");
								setTimeout(function(){ location.reload(); }, 1500);
							}
							else {
								jc.close();
								alert("error");
								setTimeout(function(){ location.reload(); }, 1500);
							}
						},
						error: function(){
							jc.close();
							alert("error");
							setTimeout(function(){ location.reload(); }, 1500);
						}
					});
					$("#myModal").modal("hide");
					
				}
			}));
			$('#filter').keyup(function() {

				var rex = new RegExp($(this).val(), 'i');
				$('.searchable tr').hide();
				$('.searchable tr').filter(function() {
					return rex.test($(this).text());
				}).show();
			});
		</script>
    </body>
</html>
