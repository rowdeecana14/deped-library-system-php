<?php
	require_once "model/controller.php";
	require_once "model/model.php";
	date_default_timezone_set('Asia/Manila');
	$year = date('Y');

	if(isset($_GET['borrower_id']) && !empty($_GET['borrower_id']) && isset($_GET['borrower_token']) && isset($_SESSION['borrower_token'])) {
		
		if($_SESSION['borrower_token'] == $_GET['borrower_token']) {
			$borrower_id = $_GET['borrower_id'];
			$pass_token = $_GET['borrower_token'];
			$sql = "SELECT * FROM tbl_borrowers WHERE borrower_id='$borrower_id' ";
			$borrower = new Model;
			$database = new Database;
			$data2 = $borrower->displayRecord($sql);
			
			$sql = "SELECT COUNT(tbl_borrowed.borrowed_id) as quantity FROM tbl_copy JOIN tbl_borrowed ON tbl_copy.account_no=tbl_borrowed.account_no WHERE tbl_borrowed.remarks='Borrowed' AND tbl_borrowed.borrower_id='$borrower_id'";
			$totalborrowed = $database->totalRow($sql);
			
			$sql = "SELECT COUNT(tbl_borrowed.account_no) AS quantity FROM tbl_books JOIN tbl_copy ON tbl_books.book_id=tbl_copy.book_id JOIN tbl_borrowed ON tbl_copy.account_no=tbl_borrowed.account_no WHERE tbl_copy.status='Lost' AND tbl_borrowed.status='Lost' AND tbl_borrowed.borrower_id='$borrower_id'";
			$totallost = $database->totalRow($sql);
			
			$sql = "SELECT COUNT(tbl_borrowed.account_no) AS quantity FROM tbl_books JOIN tbl_copy ON tbl_books.book_id=tbl_copy.book_id JOIN tbl_borrowed ON tbl_copy.account_no=tbl_borrowed.account_no WHERE tbl_copy.status='Damaged' AND tbl_borrowed.status='Damaged' AND tbl_borrowed.borrower_id='$borrower_id'";
			$totaldamaged = $database->totalRow($sql);
			
			$sql = "SELECT tbl_borrowed.borrowed_id, tbl_borrowed.account_no, tbl_books.title, tbl_books.author, tbl_borrowers.firstname, tbl_borrowers.lastname, tbl_borrowed.date_borrowed, tbl_copy.remarks FROM tbl_copy JOIN tbl_borrowed ON tbl_copy.account_no=tbl_borrowed.account_no JOIN tbl_books ON tbl_copy.book_id=tbl_books.book_id JOIN tbl_borrowers ON tbl_borrowed.borrower_id=tbl_borrowers.borrower_id WHERE tbl_copy.remarks='Borrowed' AND tbl_borrowed.borrower_id='$borrower_id' GROUP BY tbl_copy.account_no";
			$data_borrowed = $borrower->displayRecord($sql);
			
			$sql = "SELECT * FROM tbl_books JOIN tbl_copy ON tbl_books.book_id=tbl_copy.book_id JOIN tbl_borrowed ON tbl_copy.account_no=tbl_borrowed.account_no WHERE tbl_copy.status='Lost' AND tbl_borrowed.status='Lost' AND tbl_borrowed.borrower_id='$borrower_id' ORDER BY tbl_copy.book_id, tbl_copy.copy";
			$data_lost = $borrower->displayRecord($sql);
			
			$sql = "SELECT * FROM tbl_books JOIN tbl_copy ON tbl_books.book_id=tbl_copy.book_id JOIN tbl_borrowed ON tbl_copy.account_no=tbl_borrowed.account_no WHERE tbl_copy.status='Damaged' AND tbl_borrowed.status='Damaged' AND tbl_borrowed.borrower_id='$borrower_id' ORDER BY tbl_copy.book_id, tbl_copy.copy";
			$data_damaged = $borrower->displayRecord($sql);
		
		}
		else {
			header("location: dashboard.php");
		}
		
	}
	else {
		
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
			table {
				font-size: 12px;
			}
			#title_name {
				font-size:16px; 
				font-weight:bold;
			}
			hr {
				border-top: 2px solid #D3D6DA;
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
						 <br>
						<div class="x_panel">
						  <div class="x_title">
							<img src="images/user_profile.png" width="50px" height="50px">
								<h3 style="margin-left:60px; margin-top:-38px">Borrower Profile</h3>
								  <div class=" btn-group pull-right" style="margin-left:60px; margin-top:-45px">
									  	<a href="borrower_edit.php? borrower_id=<?php echo $borrower_id;?> & borrower_token=<?php echo $pass_token;?>" class="btn btn-primary btn-sm"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit Borrower Details"><i class="fa fa-edit"></i> Edit</a>
										<a href="borrowers.php? action='tab2'" class="btn btn-primary btn-sm"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Back to borrower list"><i class="fa fa-mail-reply"></i> Back</a>
								  </div>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">

							<div class="col-xs-3">
							  <!-- required for floating -->
							  <!-- Nav tabs -->
							  <ul class="nav nav-tabs tabs-left">
								<li class="active"><a href="#details" data-toggle="tab"><i class="glyphicon glyphicon-info-sign" ></i> Borrower Details</a>
								</li>
								<li><a href="#logs" data-toggle="tab"><i class="fa fa-history" ></i> Borrower Logs <span class="badge bg-green pull-right"></span></a>
								</li>
								  <li><a href="#borrowed" data-toggle="tab"><i class="fa fa-exchange" ></i> Borrowed Books <span class="badge bg-blue pull-right"><?php echo $totalborrowed; ?></span></a>
								</li>
								  <li><a href="#lost" data-toggle="tab"><i class="fa fa-gbp" ></i> Lost Books
									  <span class="badge bg-purple pull-right"><?php echo $totallost; ?></span></a>
								</li>
								   <li><a href="#damaged" data-toggle="tab"><i class="fa fa-trash" ></i> Damaged Books <span class="badge bg-red pull-right"><?php echo $totaldamaged; ?></span> </a>
								</li>
							  </ul>
							</div>

							<div class="col-xs-9">
							  <!-- Tab panes -->
							  <div class="tab-content">
								<div class="tab-pane active" id="details">
								  <p class="lead">Borrower details</p>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<table class="table table-striped" style="font-size:14px">
										 
										  <tr>
											<th>First Name:</th>
											<td><?php echo $data2[0]['firstname']; ?></td>
										  </tr>
										  <tr>
											<th>Last Name:</th>
											<td><?php echo $data2[0]['lastname']; ?></td>
										  </tr>
										  <tr>
											<th>Gender:</th>
											<td><?php echo $data2[0]['gender']; ?></td>
										  </tr>
										  <tr>
											<th>Grade Level:</th>
											<td><?php echo $data2[0]['position']; ?></td>
										  </tr>
											<tr>
											<th>Contact No:</th>
											<td><?php echo $data2[0]['contactno']; ?></td>
										  </tr>
											<tr>
											<th>Status:</th>
											<td><?php echo $data2[0]['status']; ?></td>
										  </tr>
										 <tr>
											<th>Date Created:</th>
											<td><?php echo date('M d, Y',strtotime($data2[0]['date_created'])); ?></td>
										  </tr>
										</table>
								
									</div>
									
								</div>
								<div class="tab-pane" id="logs">
									 <p class="lead">Borrowed logs</p>
									
										<table class="table table-bordered table-striped jambo_table">
											  <thead>
												<tr>
													<th >Action</th>
												  <th>Book Title</th>
													<th class="text-center">Quantity</th>
													<th>Date</th>
													<th>Remarks</th>
													<th>Option</th>
												</tr>
											  </thead>
											  <tbody class="searchable">
											  <?php
													$sql = "SELECT * FROM tbl_booklogs WHERE (tbl_booklogs.status='Borrowed' OR tbl_booklogs.status='Returned') AND tbl_booklogs.borrower_id='$borrower_id' AND EXTRACT(YEAR FROM tbl_booklogs.date) = '$year' ORDER BY tbl_booklogs.booklogs_id ASC";
													$data_borrowerlogs = $borrower->displayRecord($sql);
												  	$count = 0;
													if(count($data_borrowerlogs) > 0) {
														foreach($data_borrowerlogs as $value) {
															$count++;
															$booklogs_id = $value['booklogs_id'];
															$status = $value['status'];
															if($status == "Borrowed") {
																$d = '<span class="label label-success">Borrowed</span>';
																$s = '<span class="badge bg-blue"><i class="fa fa-mail-reply"></i></span>';
															}
															else {
																$s = '<span class="badge bg-green"><i class="fa fa-mail-forward"></i></span>';
																$d = '<span class="label label-warning">Returned</span>';
															}

															if($status == "Borrowed") {
																$sql2 = "SELECT tbl_booklogs.booklogs_id, tbl_books.title, tbl_booklogs.quantity, tbl_booklogs.date, tbl_borrowers.firstname, tbl_borrowers.lastname , tbl_booklogs.status FROM tbl_booklogs JOIN tbl_books ON tbl_booklogs.book_id=tbl_books.book_id JOIN tbl_borrowed ON tbl_booklogs.booklogs_id=tbl_borrowed.booklogs_id JOIN tbl_borrowers ON tbl_borrowed.borrower_id=tbl_borrowers.borrower_id WHERE tbl_booklogs.status='Borrowed' AND tbl_booklogs.booklogs_id='$booklogs_id' AND tbl_borrowed.borrower_id='$borrower_id' GROUP BY tbl_booklogs.booklogs_id";
																$data_logs1 = $borrower->displayRecord($sql2);

																echo "<tr>
																		<td>".$s."</td>
																		<td>".$data_logs1[0]['title']."</td>
																		<td class='text-center'>".$data_logs1[0]['quantity']."</td>
																		<td>".date("F d Y", strtotime($data_logs1[0]['date']))."</td>
																		<td>".$d."</td>
																		<td>
																			<button type='button' onclick=logs('$booklogs_id','Borrowed') class='btn btn-primary btn-xs' ><i class='fa fa-folder'> View</i></button>
																		</td>
																	</tr>";
															}
															else {
																$sql2 = "SELECT tbl_booklogs.booklogs_id, tbl_books.title, tbl_booklogs.quantity, tbl_booklogs.date, tbl_borrowers.firstname, tbl_borrowers.lastname , tbl_booklogs.status FROM tbl_booklogs JOIN tbl_books ON tbl_booklogs.book_id=tbl_books.book_id JOIN tbl_borrowed ON tbl_booklogs.booklogs_id=tbl_borrowed.booklogs_id2 JOIN tbl_borrowers ON tbl_borrowed.borrower_id=tbl_borrowers.borrower_id WHERE tbl_booklogs.status='Returned' AND tbl_booklogs.booklogs_id='$booklogs_id' AND tbl_borrowed.borrower_id='$borrower_id' GROUP BY tbl_booklogs.booklogs_id";
																$data_logs2 = $borrower->displayRecord($sql2);

																echo "<tr>
																		<td>".$s."</td>
																		<td>".$data_logs2[0]['title']."</td>
																		<td class='text-center'>".$data_logs2[0]['quantity']."</td>
																		<td>".date("F d Y", strtotime($data_logs2[0]['date']))."</td>
																		<td>".$d."</td>
																		<td>
																			<button type='button' onclick=logs('$booklogs_id','Returned') class='btn btn-primary btn-xs' ><i class='fa fa-folder'> View</i></button>
																		</td>
																	</tr>";
															}
														}
													}
												  if($count == 0) {
													  echo '<tr class="danger"><td colspan="6"><h3 class="text-center">No records available.</h3></td></tr>';
												  }
												?>
											  </tbody>
										</table>
								  		<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal_logs4">
											<div class="modal-dialog modal-lg">
											  <div class="modal-content">
												<div class="modal-header">
												  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
												  </button>
												  <h4 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-info-sign"></i> Log details</h4>
												</div>
												<div class="modal-body">
													<table class="table table-bordered table-striped jambo_table">
													  <thead>
														<tr>
														  	<th class="text-center" width="5%">No.</th>
															<th width="15%">Accession No.</th>
														  	<th width="35%">Book Title</th>
															<th width="15%">Author Name</th>
															<th width="15%">Issued By</th>
															<th width="15%">Date Borrowed</th>
														</tr>
													  </thead>
													  <tbody class="searchable" id="logs_data">

													  </tbody>
												</table>
												</div>
												<div class="modal-footer">
												  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> Close</button>
												</div>
											  </div>
											</div>
										  </div>
									<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal_logs5">
											<div class="modal-dialog modal-lg">
											  <div class="modal-content">
												<div class="modal-header">
												  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
												  </button>
												  <h4 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-info-sign"></i> Log details</h4>
												</div>
												<div class="modal-body">
													<table class="table table-bordered table-striped jambo_table">
													  <thead>
														<tr>
														  	<th class="text-center" width="5%">No.</th>
															<th width="15%">Accession No.</th>
															<th width="15%">Issued By</th>
															<th width="15%">Received By</th>
															<th width="15%">Date Borrowed</th>
															<th width="15%">Date Returned</th>
															<th width="15%">Remarks</th>
														</tr>
													  </thead>
													  <tbody class="searchable" id="logs_data2">

													  </tbody>
												</table>
												</div>
												<div class="modal-footer">
												  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> Close</button>
												</div>
											  </div>
											</div>
										  </div>
								 </div>
								  <div class="tab-pane" id="borrowed">
									 <p class="lead">Borrowed books</p>
										<table class="table table-bordered table-striped jambo_table">
											  <thead>
												<tr>
												  <th class="text-center">No.</th>
													<th>Accession No.</th>
												  <th>Book Title</th>
													<th>Author Name</th>
													<th>Option</th>
												</tr>
											  </thead>
											  <tbody class="searchable">
											  <?php
													$record3 = "";
													$count = 0;	
													foreach($data_borrowed as $value) {
														$count++;
														$borrowed_id = $value['borrowed_id'];
														$record3 = $record3.
														'<tr>
															<td class="text-center">'.$count.'</td>
															<td>'.$value['account_no'].'</td>
															<td>'.$value['title'].'</td>
															<td>'.$value['author'].'</td>
															<td>'."
															  <button type='button' onclick=borrowedlogs('$borrowed_id') class='btn btn-primary btn-xs' ><i class='fa fa-folder'> View</i></button>
														  </td>".'
														</tr>';
													}
													 if($count == 0) {
														 $record3 = $record3.'<tr class="danger"><td colspan="6"><h3 class="text-center">No records available.</h3></td></tr>';
													 }
													echo $record3;
												  ?>
											  </tbody>
										</table>
									  	<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal_logs">
											<div class="modal-dialog modal-md">
											  <div class="modal-content">
												<div class="modal-header">
												  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
												  </button>
												  <h4 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-info-sign"></i> Borrowed details</h4>
												</div>
												<div class="modal-body">
													<ul class="list-group" >
												<li class="list-group-item list-group-item-success"><b>Borrower's Name: </b><b style="margin-left:30px" id="borrower"></b></li>
												 <li class="list-group-item "><b>Accession No:</b><b style="margin-left:55px" id="accessionno"></b></li>
												  <li class="list-group-item "><b>Book Title:</b><b style="margin-left:78px" id="title"></b></li>
												  <li class="list-group-item"><b>Author Name:</b><b style="margin-left:58px" id="author"></b></li> 
												<li class="list-group-item"><b>Issued By: </b><b style="margin-left:75px" id="issuedby"></b></li> 
												  <li class="list-group-item"><b>Date Borrowed: </b><b style="margin-left:43px" id="date_borrowed"></b></li>
												</ul>
												</div>
												<div class="modal-footer">
												  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> Close</button>
												</div>
											  </div>
											</div>
										  </div>
									
								 </div>
						
								  <div class="tab-pane" id="lost">
									 <p class="lead">Lost books</p>
										<table class="table table-bordered table-striped jambo_table">
											  <thead>
												<tr>
												  <th>No.</th>
													<th>Acc. No</th>
												  <th>Book Title</th>
													<th>Author Name</th>
													<th>Option</th>
												</tr>
											  </thead>
											  <tbody class="searchable">
											  <?php
												$record = "";
												$count = 0;	
												foreach($data_lost as $value) {
													$count++;
													$borrowed_id = $value['borrowed_id'];
													$record = $record.
													'<tr>
														<td class="text-center">'.$count.'</td>
														<td>'.$value['account_no'].'</td>
														<td>'.$value['title'].'</td>
														<td>'.$value['author'].'</td>
														<td>'."
															  <button type='button' onclick=lostlogs('$borrowed_id') class='btn btn-primary btn-xs' ><i class='fa fa-folder'> View</i></button>
														  </td>".'
													</tr>';
												}
												 if($count == 0) {
													 $record = $record.'<tr class="danger"><td colspan="5"><h3 class="text-center">No records available.</h3></td></tr>';
												 }
												echo $record;
											  ?>
											 </tbody>
										</table>
									  	<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal_logs2">
											<div class="modal-dialog modal-md">
											  <div class="modal-content">
												<div class="modal-header">
												  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
												  </button>
												  <h4 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-info-sign"></i> Borrowed details</h4>
												</div>
												<div class="modal-body">
													<ul class="list-group" >
													<li class="list-group-item list-group-item-success"><b>Borrower's Name: </b><b style="margin-left:30px" id="borrower2"></b></li>
													 <li class="list-group-item "><b>Accession No:</b><b style="margin-left:55px" id="accessionno2"></b></li>
													  <li class="list-group-item "><b>Book Title:</b><b style="margin-left:78px" id="title2"></b></li>
													  <li class="list-group-item"><b>Author Name:</b><b style="margin-left:58px" id="author2"></b></li> 
													<li class="list-group-item"><b>Issued By: </b><b style="margin-left:75px" id="issuedby2"></b></li> 
													  <li class="list-group-item"><b>Date Borrowed: </b><b style="margin-left:43px" id="date_borrowed2"></b></li>
														<li class="list-group-item"><b>Received By:</b><b style="margin-left:60px" id="receivedby2"></b> </li> 
													  <li class="list-group-item"><b>Date Returned: </b><b style="margin-left:45px" id="date_returned2"></b></li>
														<li class="list-group-item"><b>Remarks: </b><b style="margin-left:80px" id="remarks2"></b></li>
												</ul>
												</div>
												<div class="modal-footer">
												  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> Close</button>
												</div>
											  </div>
											</div>
										  </div>
								 </div>
								  <div class="tab-pane" id="damaged">
									 <p class="lead">Damaged books</p>
										<table class="table table-bordered table-striped jambo_table">
											  <thead>
												<tr>
												  <th>No.</th>
													<th>Acc. No</th>
												  <th>Book Title</th>
													<th>Author Name</th>
													<th>Option</th>
												</tr>
											  </thead>
											  <tbody class="searchable">
											  <?php
												$record = "";
												$count = 0;	
												foreach($data_damaged as $value) {
													$count++;
													$borrowed_id = $value['borrowed_id'];
													$record = $record.
													'<tr>
														<td class="text-center">'.$count.'</td>
														<td>'.$value['account_no'].'</td>
														<td>'.$value['title'].'</td>
														<td>'.$value['author'].'</td>
														<td>'."
															  <button type='button' onclick=damagedlogs('$borrowed_id') class='btn btn-primary btn-xs' ><i class='fa fa-folder'> View</i></button>
														  </td>".'
													</tr>';
												}
												 if($count == 0) {
													 $record = $record.'<tr class="danger"><td colspan="5"><h3 class="text-center">No records available.</h3></td></tr>';
												 }
												echo $record;
											  ?>
											 </tbody>
										</table>
										<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal_logs3">
											<div class="modal-dialog modal-md">
											  <div class="modal-content">
												<div class="modal-header">
												  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
												  </button>
												  <h4 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-info-sign"></i> Borrowed details</h4>
												</div>
												<div class="modal-body">
													<ul class="list-group" >
													<li class="list-group-item list-group-item-success"><b>Borrower's Name: </b><b style="margin-left:30px" id="borrower3"></b></li>
													 <li class="list-group-item "><b>Accession No:</b><b style="margin-left:55px" id="accessionno3"></b></li>
													  <li class="list-group-item "><b>Book Title:</b><b style="margin-left:78px" id="title3"></b></li>
													  <li class="list-group-item"><b>Author Name:</b><b style="margin-left:58px" id="author3"></b></li> 
													<li class="list-group-item"><b>Issued By: </b><b style="margin-left:75px" id="issuedby3"></b></li> 
													  <li class="list-group-item"><b>Date Borrowed: </b><b style="margin-left:43px" id="date_borrowed3"></b></li>
														<li class="list-group-item"><b>Received By:</b><b style="margin-left:60px" id="receivedby3"></b> </li> 
													  <li class="list-group-item"><b>Date Returned: </b><b style="margin-left:45px" id="date_returned3"></b></li>
														<li class="list-group-item"><b>Remarks: </b><b style="margin-left:80px" id="remarks3"></b></li>
												</ul>
												</div>
												<div class="modal-footer">
												  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> Close</button>
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
			function logs(booklogs_id, selection) {
				if(selection == "Borrowed") {
					
					$("#modal_logs4").modal("show");
					$.ajax({
						url: "model/borrowed.php",
						dataType:'json',
						type: "POST",
						data:{ 
							action:"logs_databorrowed",
							booklogs_id: booklogs_id
						},
						success: function(data) {
							console.log(data);
							records(data);
						},
						error: function(){
							alert("error");
						}
					});
				}
				else {
					
					$("#modal_logs5").modal("show");
					$.ajax({
						url: "model/borrowed.php",
						dataType:'json',
						type: "POST",
						data:{ 
							action:"logs_datareturned",
							booklogs_id: booklogs_id
						},
						success: function(data) {
							console.log(data);
							records2(data);
						},
						error: function(){
							alert("error");
						}
					});
				}
				
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
						'<td>' + record[x].firstname + ' ' + record[x].lastname + '</td>' +
						'<td>' + record[x].date + '</td>' +
						'</td>' +
					'</tr>';
				}
				if(length > 0) {
					$("#logs_data").html(html);
				}
			}
			function records2(data) {
				
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
						'<td>' + record[x].firstname + ' ' + record[x].lastname + '</td>' +
						'<td>' + record[x].re_fname + ' ' + record[x].re_lname + '</td>' +
						'<td>' + record[x].date_borrowed + '</td>' +
						'<td>' + record[x].date_returned + '</td>' +
						'<td>' + record[x].status + '</td>' +
						'</td>' +
					'</tr>';
				}
				if(length > 0) {
					$("#logs_data2").html(html);
				}
			}
			function borrowedlogs(borrowed_id) {
				$("#modal_logs").modal("show");
				$.ajax({
					url: "model/borrowed.php",
					dataType:'json',
					type: "POST",
					data:{ 
						action:"borrowed_logs",
						borrowed_id: borrowed_id
					},
					success: function(data) {
						console.log(data);
						$("#accessionno").text(data.data[0].account_no);
						$("#borrower").text(data.data[0].firstname + " " + data.data[0].lastname);
						$("#title").text(data.data[0].title);
						$("#author").text(data.data[0].author);
						$("#issuedby").text(data.data[0].emp_fname + " " + data.data[0].emp_lname);
						$("#date_borrowed").text(data.data[0].date_borrowed);
					},
					error: function(){
						alert("error");
					}
				});
			}
			function lostlogs(borrowed_id) {
			
				$("#modal_logs2").modal("show");
				$.ajax({
					url: "model/borrowed.php",
					dataType:'json',
					type: "POST",
					data:{ 
						action:"returned_logs",
						borrowed_id: borrowed_id
					},
					success: function(data) {
						$("#accessionno2").text(data.data[0].account_no);
						$("#borrower2").text(data.data[0].firstname + " " + data.data[0].lastname);
						$("#title2").text(data.data[0].title);
						$("#author2").text(data.data[0].author);
						$("#issuedby2").text(data.data[0].emp_fname + " " + data.data[0].emp_lname);
						$("#date_borrowed2").text(data.data[0].date_borrowed);
						$("#receivedby2").text(data.data[0].re_fname + " " + data.data[0].re_lname);
						$("#date_returned2").text(data.data[0].date_returned);
						$("#remarks2").text(data.data[0].status);
					},
					error: function(){
						alert("error");
					}
				});
			}
			function damagedlogs(borrowed_id) {
			
				$("#modal_logs3").modal("show");
				$.ajax({
					url: "model/borrowed.php",
					dataType:'json',
					type: "POST",
					data:{ 
						action:"returned_logs",
						borrowed_id: borrowed_id
					},
					success: function(data) {
						$("#accessionno3").text(data.data[0].account_no);
						$("#borrower3").text(data.data[0].firstname + " " + data.data[0].lastname);
						$("#title3").text(data.data[0].title);
						$("#author3").text(data.data[0].author);
						$("#issuedby3").text(data.data[0].emp_fname + " " + data.data[0].emp_lname);
						$("#date_borrowed3").text(data.data[0].date_borrowed);
						$("#receivedby3").text(data.data[0].re_fname + " " + data.data[0].re_lname);
						$("#date_returned3").text(data.data[0].date_returned);
						$("#remarks3").text(data.data[0].status);
					},
					error: function(){
						alert("error");
					}
				});
			}
		</script>
    </body>
</html>
