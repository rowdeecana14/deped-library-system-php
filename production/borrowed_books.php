<?php
   	require_once "model/controller.php";
	require_once "model/model.php";
	date_default_timezone_set('Asia/Manila');
	$year =  date("Y");
	$borrowed = new Model;
	$database = new Database;
	$_SESSION['current_page'] = "borrowed_books.php";
	if(isset($_POST['select_year'])) {
		$year = $_POST['year'];
	}

	$sql = "SELECT EXTRACT(YEAR FROM tbl_borrowed.date_borrowed) as year FROM tbl_borrowed GROUP BY EXTRACT(YEAR FROM tbl_borrowed.date_borrowed) ORDER BY EXTRACT(YEAR FROM tbl_borrowed.date_borrowed) DESC";
	$year_list = $borrowed->displayRecord($sql);
	
	$sql = "SELECT tbl_borrowed.account_no, tbl_books.title, tbl_books.author, tbl_borrowers.firstname, tbl_borrowers.lastname, tbl_borrowed.date_borrowed FROM tbl_copy JOIN tbl_borrowed ON tbl_copy.account_no=tbl_borrowed.account_no JOIN tbl_books ON tbl_copy.book_id=tbl_books.book_id JOIN tbl_borrowers ON tbl_borrowed.borrower_id=tbl_borrowers.borrower_id WHERE tbl_copy.remarks='Borrowed' GROUP BY tbl_copy.account_no";
	$borrowed_data = $borrowed->displayRecord($sql);
	$total_borrowed = count($borrowed_data);


	$sql = "SELECT tbl_borrowed.borrowed_id, tbl_borrowed.account_no, tbl_books.title, tbl_books.author, tbl_borrowers.firstname, tbl_borrowers.lastname, tbl_borrowed.remarks, tbl_borrowed.date_borrowed, tbl_borrowed.received_userid FROM tbl_borrowed JOIN tbl_booklogs ON tbl_borrowed.booklogs_id=tbl_booklogs.booklogs_id JOIN tbl_copy ON tbl_borrowed.account_no=tbl_copy.account_no JOIN tbl_books ON tbl_copy.book_id=tbl_books.book_id JOIN tbl_borrowers ON tbl_borrowed.borrower_id=tbl_borrowers.borrower_id WHERE EXTRACT(YEAR FROM tbl_borrowed.date_borrowed) = '$year' ORDER BY tbl_booklogs.booklogs_id DESC";
	$status_data = $borrowed->displayRecord($sql);

	$month_list = ['01', '02', '03', '04', '05', '06', '07','08', '09', '10', '11', '12'];
	$string_month = array('01' =>"January", '02' =>"February", '03' =>"March", '04' =>"April", '05' =>"May", '06' =>"June", '07' =>"July",'08' =>"August",'09' =>"Septempber", '10' =>"October", '11' =>"November", '12' =>"December");
	$sql = "SELECT EXTRACT(YEAR FROM date_borrowed) AS year FROM tbl_borrowed GROUP BY EXTRACT(YEAR FROM date_borrowed) ORDER BY EXTRACT(YEAR FROM date_borrowed) DESC";
	$data_years = $borrowed->displayRecord($sql);

	$data_graph = "";
	$counted = 0;
	foreach($month_list as $month) {
		$sql = "SELECT COUNT(tbl_borrowed.account_no) AS quantity FROM tbl_borrowed WHERE EXTRACT(YEAR FROM tbl_borrowed.date_borrowed)='$year' AND EXTRACT(MONTH FROM tbl_borrowed.date_borrowed)='$month'";
		$quantity = $borrowed->displayRecord($sql);
		$qty = 0;
		if($quantity[0]['quantity'] == null) {
			$qty = 0;
		}
		else {
			$qty = $quantity[0]['quantity'];
		}
		$counted++;
		if($counted %2== 1) {
			$data_graph .= "{Month:'".$string_month[$month]."', Quantity:".$qty.", Color:'#FCD202'}, ";
		}
		else {
			$data_graph .= "{Month:'".$string_month[$month]."', Quantity:".$qty.", Color:'#FF6600'}, ";
		}
	}
	$data_graph = substr ($data_graph, 0, -2);
    $data_graph = "[".$data_graph."];";

	$data_month = array();
	foreach($month_list as $month) {
		$sql = "SELECT COUNT(account_no) as quantity FROM tbl_borrowed WHERE EXTRACT(YEAR FROM date_borrowed)='$year' AND EXTRACT(MONTH FROM date_borrowed)='$month'";
		$quantity = $database->totalRow($sql);
		array_push($data_month, $quantity);
	}
	$start = 0;
	$end = 100;
	if(isset($_GET['next'])) {
		$start = $_GET['next_start'];
		$end = $_GET['next_end'];
	}
	if(isset($_GET['previous'])) {
		$start = $_GET['previous_start'];
		$end = $_GET['previous_end'];
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
    <body class="nav-md" onload=" displaySetting();" onclick="resetTimer()" onmousedown="resetTimer()" onmousemove="resetTimer()" onscroll="resetTimer()" onkeypress="resetTimer()">
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
				<div class="" role="main" style="">
					<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12" role="tabpanel" data-example-id="togglable-tabs"  >
						<ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
						  <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true"><span class="badge bg-blue "><i class="fa fa-table"></i></span>   Borrowed</a>
						  </li>
							<li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false"><span class="badge bg-blue "><i class="fa fa-history"></i></span>  Borrowed Logs</a></li>
							<li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false"><span class="badge bg-blue "><i class="fa fa-bar-chart"></i></span> Records & Graph</a>
						  </li>

						</ul>
						<div id="myTabContent" class="tab-content">
						  <div role="tabpanel" class="tab-pane fade active in"  id="tab_content1" aria-labelledby="home-tab">
							  	<div class="x_panel" id="recordPanel" style=" ">
								  <div class="x_title">
									 <img src="images/book_borrow.png" width="50px" height="50px">
									<h3 style="margin-left:60px; margin-top:-35px">Borrowed books</h3>
									   <div class="btn-group pull-right" style="margin-top:-38px">
										  <button type="button" class="btn btn-primary" onclick="printTable()">
											 <i class="fa fa-print"></i> Print</button>
										  <a type="button" download="Listofborrowedbooks.xls" onclick="return ExcellentExport.excel(this, 'table2', 'Listofborrowedbooks');" class="btn btn-primary"><i class="fa fa-file-excel-o"></i> Excel</a>
										</div>
									<div class="clearfix"></div>
								  </div>
								  <div class="x_content">
									 <div class="row">
										  <br>
										  <div class="col-md-9 col-sm-9 col-xs-9">
											<div class="input-group ">
												<input type="text" class="form-control" style="height:45px; background-color:#d3ead9" placeholder="Search Borrower" id="filter">
												<span class="input-group-addon"><i class="fa fa-search" style="width:30px"></i></span>
											  </div>
										  </div>
										 <div class="col-md-3 col-sm-3 col-xs-3">
											 <a href="books_borrow.php" class="btn btn-default pull-right" style="margin-top:-10px; border: 2px solid #52b3a0"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Borrowed Books">
												<img src="images/add_books.png" width="50px" height="50px">
											</a>
											 <button type="button" class="btn btn-default pull-right" style="margin-top:-10px; border: 2px solid #52b3a0"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Refresh Records" id="refresh">
												<img src="images/refresh.png" width="50px" height="50px">
											 </button>
										  </div>
										 <div  class="col-md-12 col-sm-12 col-xs-12">
											  <hr>
										  </div>
										  <div class="col-md-12 col-sm-12 col-xs-12">
											  <div id="printdata">
												  <center><h3 id="title_name">LIST OF BORROWED BOOKS</h3></center>
												  <br>
												   	<table class="table table-bordered table-striped jambo_table">
													  <thead>
														<tr>
														  <th width="5%">No.</th>
														  <th width="15%">Accession No</th>
														  <th width="30%">Book Title</th>
														  <th width="18%">Author Name</th>
														  <th width="17%">Borrower's Name</th>
														  <th width="15%">Date Borrowed</th>
														</tr>
													  </thead>
													  <tbody class="searchable" id="printData">
													   <?php
															$count = $start;
															$limit = 0;

															if(count($borrowed_data) >= $end) {
																$limit = $end;
															}
															else {
																$limit = count($borrowed_data);
															}
															if(count($borrowed_data) > 0) {

																for($x = $start; $x < $limit; $x++) {

																	$count++;
																	echo
																	'<tr>
																		<td>'.$count.'</td>
																		<td>'.$borrowed_data[$x]['account_no'].'</td>
																		<td>'.$borrowed_data[$x]['title'].'</td>
																		<td>'.$borrowed_data[$x]['author'].'</td>
																		<td>'.$borrowed_data[$x]['firstname'].' '.$borrowed_data[$x]['lastname'].'</td>
																		<td >'.date('M d,Y',strtotime($borrowed_data[$x]['date_borrowed'])).'</td>
																	</tr>';
																}
															}
														  	else {
																echo '<tr class="danger"><td colspan="8"><h3 class="text-center">No records available.</h3></td></tr>';
															}
													  ?>
													  </tbody>
												</table>
												  <?php
													$previous_start = $start - 100;
													$previous_end = $end - 100;
													$next_start = $end;
													$next_end = $end + 100;

													if($count == $end) {

														if($count == 100) {
															echo '
															<div class="box-footer" style="border-top: 1px solid #afaaaa;">
																<p>Showing '.$count.' of '.$total_borrowed.' entries</p>
																<form method="get" style="margin-top: -30px">
																	<input type="hidden" name="next_start" value="'.$next_start.'">
																	<input type="hidden" name="next_end" value="'.$next_end.'">
																	<button type="submit" name="next" class="btn btn-primary pull-right margin">Next <i class="fa fa-arrow-right"></i></button>
																</form>
															</div>';
														}
														else if(count($borrowed_data) > $end) {
															echo '
															<div class="box-footer" style="border-top: 1px solid #afaaaa;">
																<p>Showing '.$count.' of '.$total_borrowed.' entries</p>
																<form method="get" style="margin-top: -30px">
																	<input type="hidden" name="next_start" value="'.$next_start.'">
																	<input type="hidden" name="next_end" value="'.$next_end.'">
																	<input type="hidden" name="previous_start" value="'.$previous_start.'">
																	<input type="hidden" name="previous_end" value="'.$previous_end.'">
																	<button type="submit" name="next" class="btn btn-primary pull-right margin">Next <i class="fa fa-arrow-right"></i></button>
																	<button type="submit" name="previous" class="btn btn-primary pull-right margin"><i class="fa fa-arrow-left"></i> Previous </button>
																</form>
															</div>';

														}
														else {
															echo '
															<div class="box-footer" style="border-top: 1px solid #afaaaa;">
																<p>Showing '.$count.' of '.$total_borrowed.' entries</p>
																<form method="get" style="margin-top: -30px">
																	<input type="hidden" name="previous_start" value="'.$previous_start.'">
																	<input type="hidden" name="previous_end" value="'.$previous_end.'">
																	<button type="submit" name="previous" class="btn btn-primary pull-right"><i class="fa fa-arrow-left"></i> Previous </button>
																</form>
															</div>';
														}
													}
													else {
														if(count($borrowed_data) > 0 && $previous_end  > 0) {
															echo '
															<div class="box-footer" style="border-top: 1px solid #afaaaa;">
																<p>Showing '.$count.' of '.$total_borrowed.' entries</p>
																<form method="get" style="margin-top: -30px">
																	<input type="hidden" name="previous_start" value="'.$previous_start.'">
																	<input type="hidden" name="previous_end" value="'.$previous_end.'">
																	<button type="submit" name="previous" class="btn btn-primary pull-right"><i class="fa fa-arrow-left"></i> Previous </button>
																</form>
															</div>';
														}
														else {
															echo '
															<div class="box-footer" style="border-top: 1px solid #afaaaa;">
																<p>Showing '.$count.' of '.$total_borrowed.' entries</p>
															</div>';
														}
													}
												  ?>
											  </div>
											  <table id="table2" class="table table-bordered table-striped jambo_table hide">
													   <tr>
														  <td colspan="6"><p style="font-size:20px">List of borrowed</p></td>
													  </tr>
														<tr>
														   <th width="5%">No.</th>
														  <th width="15%">Accession No</th>
														  <th width="30%">Book Title</th>
														  <th width="18%">Author Name</th>
														  <th width="17%">Borrower's Name</th>
														  <th width="15%">Date Borrowed</th>
														</tr>
													  <tbody class="searchable" id="data2">
														  <?php
														  $count = $start;
															$limit = 0;

															if(count($borrowed_data) >= $end) {
																$limit = $end;
															}
															else {
																$limit = count($borrowed_data);
															}
															if(count($borrowed_data) > 0) {

																for($x = $start; $x < $limit; $x++) {

																	$count++;
																	echo 
																	'<tr>
																		<td>'.$count.'</td>
																		<td>'.$borrowed_data[$x]['account_no'].'</td>
																		<td>'.$borrowed_data[$x]['title'].'</td>
																		<td>'.$borrowed_data[$x]['author'].'</td>
																		<td>'.$borrowed_data[$x]['firstname'].' '.$borrowed_data[$x]['lastname'].'</td>
																		<td >'.date('M d,Y',strtotime($borrowed_data[$x]['date_borrowed'])).'</td>
																	</tr>';
																}
															}
														  	else {
																echo '<tr class="danger"><td colspan="8"><h3 class="text-center">No records available.</h3></td></tr>';
															}
														  ?>
													  </tbody>
												</table>
										  </div>
									 </div>
								</div>
							  </div>
						  </div>
						  <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
							  <div class="x_panel" style="">
								  <div class="x_title">
									 <img src="images/report.png" width="50px" height="50px">
									<h3 style="margin-left:60px; margin-top:-38px">Borrowed logs</h3>
									  <div class="btn-group pull-right" style="margin-top:-38px">
										  <button type="button" class="btn btn-primary" onclick="select_year()">
											 <i class="fa fa-calendar"></i> Select year</button>
										</div>
									<div class="clearfix"></div>
								  </div>
								  <div class="x_content">
									   <div class="row">
										  <br>
										  <div class="col-md-9 col-sm-9 col-xs-9">
											<div class="input-group ">
												<input type="text" class="form-control" style="height:45px; background-color:#d3ead9;" placeholder="Search Books" id="filter2">
												<span class="input-group-addon"><i class="fa fa-search" style="width:30px"></i></span>
											  </div>
										  </div>
										  <div class="col-md-12 col-sm-12 col-xs-12">
												   <h3>List of logs</h3>
												    <table class="table table-bordered  jambo_table">
													  <thead>
														<tr>
														 <th width="5%">No.</th>
														  <th width="10%">Acc. No</th>
														  <th width="20%">Book Title</th>
														  <th width="15%">Author Name</th>
														  <th width="15%">Borrower's Name</th>
														  <th width="15%">Date Borrowed</th>
														  <th width="10%">Remarks</th>
														  <th width="10%">Option</th>
														</tr>
													  </thead>
													  <tbody class="searchable2" id="">
														<?php
														  $record2 = "";
														  	$count2 = 0;
															$record2 = $record2.'<tr class="success"><td colspan="10"><h4 class="text-left">Year '.$year.'</h4></td></tr>';
															$count = 0;

															foreach($status_data as $value2) {
																$count++;
																$status = $value2['remarks'];
																$action = "";
																$received_userid = $value2['received_userid'];
																$borrowed_id = $value2['borrowed_id'];
																if($status == "Borrowed") {
																	$status = '<span class="label label-success">Borrowed</span>';
																}
																else {
																	$status = '<span class="label label-warning">Returned</span>';
																}

																if($received_userid != null) {
																	$action = "returned";
																}
																else {
																	$action = "borrowed";
																}

																$record2 = $record2.'<tr>
																	<td>'.$count.'</td>
																	<td>'.$value2['account_no'].'</td>
																	<td>'.$value2['title'].'</td>
																	<td>'.$value2['author'].'</td>
																	<td>'.$value2['firstname'].' '.$value2['lastname'].'</td>
																	<td>'.date("M d, Y", strtotime($value2['date_borrowed'])).'</td>
																	<td>'.$status.'</td>
																	<td><button type="button" class="btn btn-primary btn-xs" onclick=viewlogs("'.$borrowed_id.'","'.$action.'") ><i class="fa fa-eye"></i> View</button></td>';
															}
														  	if($count == 0) {
																$record2 = $record2.'<tr class="danger"><td colspan="10"><h3 class="text-center">No records available.</h3></td></tr>';
															  }
														  	echo $record2;
														  ?>
													  </tbody>
												</table>
											  <form method="post">
												  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal_year">
													<div class="modal-dialog modal-md">
													  <div class="modal-content">
														<div class="modal-header">
														  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
														  </button>
														  <h4 class="modal-title" id="myModalLabel" style="font-weight:bold;"><i class="fa fa-calendar"></i> Calendar year</h4>
														</div>
														<div class="modal-body">
															<div class="row">
																<div class="col-md-12 col-sm-12 col-xs-12">
																	<div class="col-md-12 col-sm-12 col-xs-12">
																		<label class="col-md-12 col-sm-12 col-xs-12 form-group">Select Year: </label>
																		 <div class="col-md-12 col-sm-12 col-xs-12 form-group">
																			 <select class="form-control select2 has-feedback-left"  name="year" id="year" style="background-color:#e2e2e2; width:100%">
																			 <?php

																				 if(count($year_list) > 0) {
																					 foreach($year_list as $value) {
																						 echo "<option>".$value['year']."</option>";
																					 }
																				 }

																			 ?>
																			</select>
																			<span class="fa fa-calendar blue form-control-feedback left" aria-hidden="true" style="color:black"></span>
																		 </div>
																	</div>
																</div>
															</div>
															
														</div>
														<div class="modal-footer">
														  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> Close</button>
															<button type="submit" name="select_year" class="btn btn-primary" ><i class="fa fa-search"></i> Search</button>
														</div>
													  </div>
													</div>
												  </div>
											  </form>
											  
												  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal_logs">
														<div class="modal-dialog modal-md">
														  <div class="modal-content">
															<div class="modal-header">
															  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
															  </button>
															  <h4 class="modal-title" id="myModalLabel" style="font-weight:bold;"><i class="glyphicon glyphicon-info-sign"></i> Logs Details</h4>
															</div>
															<div class="modal-body">
																<div class="row">
																	<div class="col-md-12 col-sm-12 col-xs-12">
																		<div class="col-md-12 col-sm-12 col-xs-12">
																			<ul class="list-group" >
																				<li class="list-group-item list-group-item-success"><b>Borrower's Name: </b><b style="margin-left:22px" id="borrower"></b></li>
																				 <li class="list-group-item "><b>Accession No:</b><b style="margin-left:46px" id="accessionno"></b></li>
																				  <li class="list-group-item "><b>Book Title:</b><b style="margin-left:69px" id="title"></b></li>
																				<li class="list-group-item"><b>Issued By: </b><b style="margin-left:65px" id="issuedby"></b></li> 
																				  <li class="list-group-item"><b>Date Borrowed: </b><b style="margin-left:34px" id="date_borrowed"></b></li>
																			</ul>
																		</div>
																	</div>
																</div>
															</div>
															<div class="modal-footer">
															  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> Close</button>
															</div>
														  </div>
														</div>
													  </div>
												  
												  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal_logs2">
														<div class="modal-dialog modal-md">
														  <div class="modal-content">
															<div class="modal-header">
															  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
															  </button>
															  <h4 class="modal-title" id="myModalLabel" style="font-weight:bold;"><i class="glyphicon glyphicon-info-sign"></i> Logs Details</h4>
															</div>
															<div class="modal-body">
																<div class="row">
																	<div class="col-md-12 col-sm-12 col-xs-12">
																		<div class="col-md-12 col-sm-12 col-xs-12">
																			<ul class="list-group" >
																				<li class="list-group-item list-group-item-success"><b>Borrower's Name: </b><b style="margin-left:30px" id="borrower2"></b></li>
																				 <li class="list-group-item "><b>Accession No:</b><b style="margin-left:55px" id="accessionno2"></b></li>
																				  <li class="list-group-item "><b>Book Title:</b><b style="margin-left:75px" id="titlebook2"></b></li>
																				<li class="list-group-item"><b>Issued By: </b><b style="margin-left:72px" id="issuedby2"></b></li> 
																				  <li class="list-group-item"><b>Date Borrowed: </b><b style="margin-left:41px" id="date_borrowed2"></b></li>
																				  <li class="list-group-item"><b>Received By:</b><b style="margin-left:59px" id="receivedby2"></b> </li> 
																				  <li class="list-group-item"><b>Date Returned: </b><b style="margin-left:44px" id="date_returned2"></b></li>
																				<li class="list-group-item"><b>Remarks: </b><b style="margin-left:76px" id="remarks2"></b></li>
																				</ul>
																		</div>
																	</div>
																</div>
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
							<div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab">
							  <div class="x_panel" style="">
								  <div class="x_title">
									  <img src="images/bar_graph.png" width="50px" height="50px">
										<h3 style="margin-left:60px; margin-top:-37px">Monthly Records & Graph</h3>
									<div class="clearfix"></div>
								  </div>
								  <div class="x_content">
									  <div class="x_panel" style="">
										  <div class="x_title">
											<h4>Year <?php echo $year; ?></h4>
											<div class="clearfix"></div>
										  </div>
										  <div class="x_content">
											  <div id="chartdiv" style="width: 100%; height: 400px;"></div>
											  <br><br>
											  <div class="col-md-11 col-sm-12 col-xs-12" style="margin-left:40px">
												  <table class="table table-bordered  jambo_table">
													  <thead>
														<tr>
														  <th width="10%" class="text-center">No.</th>
														  <th width="40%"  class="text-center">Month</th>
														  <th width="30%"  class="text-center">Total</th>
														  <th width="20%"  class="text-center">Option</th>
														</tr>
													  </thead>
													  <tbody>
														  <tr>
															  <td class="text-center">1</td>
															  <td  class="text-center">January</td>
															  <td  class="text-center"><span class="badge bg-green "> <?php echo $data_month[0]; ?></span></td>
															  <td>
																  <center>
																	  <form action="monthly_borrowed.php" method="post">
																		  <input type="hidden" name="month" value="01">
																		  <button type="submit" class="btn btn-primary btn-xs" name="view_month"><i class="fa fa-eye"></i> View Books</button>
																	  </form>
																  </center>
															  </td>
														  </tr>
														  <tr>
															  <td class="text-center">2</td>
															  <td  class="text-center">February</td>
															  <td  class="text-center"><span class="badge bg-green "> <?php echo $data_month[1]; ?></span></td>
															  <td >
																  <center>
																	  <form action="monthly_borrowed.php" method="post">
																		  <input type="hidden" name="month" value="02">
																		  <button type="submit" class="btn btn-primary btn-xs" name="view_month"><i class="fa fa-eye"></i> View Books</button>
																	  </form>
																  </center>
															  </td>
														  </tr>
														  <tr>
															  <td class="text-center">3</td>
															  <td class="text-center">March</td>
															  <td  class="text-center"><span class="badge bg-green "> <?php echo $data_month[2]; ?></span></td>
															  <td>
																  <center>
																	   <form action="monthly_borrowed.php" method="post">
																		  <input type="hidden" name="month" value="03">
																		  <button type="submit" class="btn btn-primary btn-xs" name="view_month"><i class="fa fa-eye"></i> View Books</button>
																	  </form>
																  </center>
															  </td>
														  </tr>
														  <tr>
															  <td class="text-center">4</td>
															  <td class="text-center">April</td>
															  <td  class="text-center"><span class="badge bg-green "> <?php echo $data_month[3]; ?></span></td>
															  <td>
																  <center>
																	   <form action="monthly_borrowed.php" method="post">
																		  <input type="hidden" name="month" value="04">
																		  <button type="submit" class="btn btn-primary btn-xs" name="view_month"><i class="fa fa-eye"></i> View Books</button>
																	  </form>
																  </center>
															  </td>
														  </tr>
														  <tr>
															  <td class="text-center">5</td>
															  <td class="text-center">May</td>
															  <td  class="text-center"><span class="badge bg-green "> <?php echo $data_month[4]; ?></span></td>
															  <td>
																  <center>
																	   <form action="monthly_borrowed.php" method="post">
																		  <input type="hidden" name="month" value="05">
																		  <button type="submit" class="btn btn-primary btn-xs" name="view_month"><i class="fa fa-eye"></i> View Books</button>
																	  </form>
																  </center>
															  </td>
														  </tr>
														  <tr>
															  <td class="text-center">6</td>
															  <td class="text-center">June</td>
															  <td  class="text-center"><span class="badge bg-green "> <?php echo $data_month[5]; ?></span></td>
															  <td>
																  <center>
																	   <form action="monthly_borrowed.php" method="post">
																		  <input type="hidden" name="month" value="06">
																		  <button type="submit" class="btn btn-primary btn-xs" name="view_month"><i class="fa fa-eye"></i> View Books</button>
																	  </form>
																  </center>
															  </td>
														  </tr>
														  <tr>
															  <td class="text-center">7</td>
															  <td class="text-center">July</td>
															  <td  class="text-center"><span class="badge bg-green "> <?php echo $data_month[6]; ?></span></td>
															  <td>
																  <center>
																	   <form action="monthly_borrowed.php" method="post">
																		  <input type="hidden" name="month" value="07">
																		  <button type="submit" class="btn btn-primary btn-xs" name="view_month"><i class="fa fa-eye"></i> View Books</button>
																	  </form>
																  </center>
															  </td>
														  </tr>
														  <tr>
															  <td class="text-center">8</td>
															  <td class="text-center">August</td>
															  <td  class="text-center"><span class="badge bg-green "> <?php echo $data_month[7]; ?></span></td>
															  <td>
																  <center>
																	   <form action="monthly_borrowed.php" method="post">
																		  <input type="hidden" name="month" value="08">
																		  <button type="submit" class="btn btn-primary btn-xs" name="view_month"><i class="fa fa-eye"></i> View Books</button>
																	  </form>
																  </center>
															  </td>
														  </tr>
														  <tr>
															  <td class="text-center">9</td>
															  <td class="text-center">September</td>
															  <td  class="text-center"><span class="badge bg-green "> <?php echo $data_month[8]; ?></span></td>
															  <td>
																  <center>
																	   <form action="monthly_borrowed.php" method="post">
																		  <input type="hidden" name="month" value="09">
																		  <button type="submit" class="btn btn-primary btn-xs" name="view_month"><i class="fa fa-eye"></i> View Books</button>
																	  </form>
																  </center>
															  </td>
														  </tr>
														  <tr>
															  <td class="text-center">10</td>
															  <td class="text-center">October</td></tD></td>
															  <td  class="text-center"><span class="badge bg-green "> <?php echo $data_month[9]; ?></span></td>
															  <td>
																  <center>
																	   <form action="monthly_borrowed.php" method="post">
																		  <input type="hidden" name="month" value="10">
																		  <button type="submit" class="btn btn-primary btn-xs" name="view_month"><i class="fa fa-eye"></i> View Books</button>
																	  </form>
																  </center>
															  </td>
														  </tr>
															<tr>
															  <td class="text-center">11</td>
															  <td class="text-center">November</td>
															  <td  class="text-center"><span class="badge bg-green "> <?php echo $data_month[10]; ?></span></td>
															  <td>
																  <center>
																	   <form action="monthly_borrowed.php" method="post">
																		  <input type="hidden" name="month" value="11">
																		  <button type="submit" class="btn btn-primary btn-xs" name="view_month"><i class="fa fa-eye"></i> View Books</button>
																	  </form>
																  </center>
															  </td>
														  </tr>
															<tr>
															  <td class="text-center">12</td>
															  <td class="text-center">December</td>
															  <td  class="text-center"><span class="badge bg-green "> <?php echo $data_month[11]; ?></span></td>
															  <td>
																  <center>
																	   <form action="monthly_borrowed.php" method="post">
																		  <input type="hidden" name="month" value="12">
																		  <button type="submit" class="btn btn-primary btn-xs" name="view_month"><i class="fa fa-eye"></i> View Books</button>
																	  </form>
																  </center>
															  </td>
														  </tr>
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
		<script type="text/javascript">
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
					$('.searchable tr').show();
				});
				$('#filter2').keyup(function() {
				
					var rex = new RegExp($(this).val(), 'i');
					$('.searchable2 tr').hide();
					$('.searchable2 tr').filter(function() {

						return rex.test($(this).text());
					}).show();
				});
				$('#refresh2').click(function() {
					$("#filter2").val("");
					$('.searchable2 tr').show();
				});
			});
			
			function select_year() {
				$("#modal_year").modal("show");
			}
			function viewlogs(borrowed_id, remarks) {
				
				if(remarks == "borrowed") {
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
							$("#borrower").text(data.data[0].firstname + " " + data.data[0].lastname);
							$("#title").text(data.data[0].title);
							$("#accessionno").text(data.data[0].account_no);
							$("#issuedby").text(data.data[0].emp_fname + " " + data.data[0].emp_lname);
							$("#date_borrowed").text(data.data[0].date_borrowed);
						},
						error: function(){
							alert("error");
						}
					});
				}
				else {
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
							console.log(data);
							console.log(data);
							$("#borrower2").text(data.data[0].firstname + " " + data.data[0].lastname);
							$("#titlebook2").text(data.data[0].title);
							$("#accessionno2").text(data.data[0].account_no);
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
				
			}
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
								 <center><h3 id="title_name">LIST OF BORROWED BOOKS</h3></center>\
						  		<br>\
								<table class="table">\
									<thead>\
									  <th width="5%" style="border: 1px solid black">No.</th>\
									  <th width="15%" style="border: 1px solid black">Accession No</th>\
									  <th width="30%" style="border: 1px solid black">Book Title</th>\
									  <th width="18%" style="border: 1px solid black">Author Name</th>\
									  <th width="17%" style="border: 1px solid black">Borrowers Name</th>\
									  <th width="15%" style="border: 1px solid black">Date Borrowed</th>\
									</thead>\
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
			
			var chart;
            var chartData = <?php echo $data_graph; ?>;
            var chart = AmCharts.makeChart("chartdiv", {
                type: "serial",
                dataProvider: chartData,
                categoryField: "Month",
                depth3D: 15,
                angle: 30,

                categoryAxis: {
                    labelRotation: 30,
                    gridPosition: "start"
                },

                valueAxes: [{
                    title: "Quantity"
                }],

                graphs: [{

                    valueField: "Quantity",
                    colorField: "Color",
                    type: "column",
                    lineAlpha: 0,
                    fillAlphas: 1,
                    balloonText: "<span style='font-size:18px'>Month: <b>[[Month]]</b><br>Quantity: <b>[[value]]</b></span>"
                }],

                chartCursor: {
                    cursorAlpha: 0,
                    zoomable: false,
                    categoryBalloonEnabled: false
                }
            });
 		</script>
    </body>
</html>
