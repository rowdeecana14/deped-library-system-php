<?php
    
	require_once "model/controller.php";
	require_once "model/model.php";
	date_default_timezone_set('Asia/Manila');
	$year =  date("Y");
	$auth = new Database;
	$book = new Model;
	$token = $auth->generateAuth();
	$_SESSION['book_token'] = $token;
	$_SESSION['current_page'] = "listof_books.php";

	if(isset($_POST['select_year'])) {
		$year = $_POST['year'];
	}

	$sql = "SELECT * FROM tbl_books ORDER BY title";
	$data_books = $book->displayRecord($sql);
	$total_inventory = count($book->displayRecord("SELECT * FROM tbl_books ORDER BY title"));

	$sql = "SELECT EXTRACT(YEAR FROM date) AS year FROM tbl_booklogs WHERE status='Received' GROUP BY EXTRACT(YEAR FROM date) ORDER BY EXTRACT(YEAR FROM date) DESC";
	$data_years = $book->displayRecord($sql);

	$month_list = ['01', '02', '03', '04', '05', '06', '07','08', '09', '10', '11', '12'];
	$string_month = array('01' =>"January", '02' =>"February", '03' =>"March", '04' =>"April", '05' =>"May", '06' =>"June", '07' =>"July",'08' =>"August",'09' =>"Septempber", '10' =>"October", '11' =>"November", '12' =>"December");
	$data_graph = "";
	$counted = 0;
	foreach($month_list as $month) {
		$sql = "SELECT SUM(tbl_booklogs.quantity) AS quantity FROM tbl_booklogs JOIN tbl_books ON tbl_booklogs.book_id=tbl_books.book_id JOIN tbl_employee ON tbl_booklogs.user_id=tbl_employee.user_id WHERE EXTRACT(YEAR FROM date)='$year' AND EXTRACT(MONTH FROM date)='$month' AND tbl_booklogs.status='Received'";
		$quantity = $book->displayRecord($sql);
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
		$sql = "SELECT SUM(quantity) as quantity FROM tbl_booklogs WHERE EXTRACT(YEAR FROM date)='$year' AND EXTRACT(MONTH FROM date)='$month' AND status='Received'";
		$quantity = $auth->totalRow($sql);
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
				<div class="right_col" role="main" style="background-image: url(images_uploaded/<?php //echo $data[0]['bg_image'];?>); background-repeat: no-repeat; background-size: cover;">
				<div class="" role="main" style="">
					<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12" role="tabpanel" data-example-id="togglable-tabs"  >
						<ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
						  <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true"><span class="badge bg-blue "><i class="fa fa-table"></i></span>  Inventory</a>
						  </li>
						  <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false"><span class="badge bg-blue "><i class="fa fa-history"></i></span>  Receiving Logs</a>
							<li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false"><span class="badge bg-blue "><i class="fa fa-bar-chart"></i></span> Records & Graph</a>
						  </li>

						</ul>
						<div id="myTabContent" class="tab-content">
						  <div role="tabpanel" class="tab-pane fade active in"  id="tab_content1" aria-labelledby="home-tab">
							  	<div class="x_panel" id="recordPanel" style=" ">
								  <div class="x_title">
									 <img src="images/book_inventory.jpg" width="50px" height="50px">
									<h3 style="margin-left:60px; margin-top:-38px">Inventory record</h3>
									   <div class="btn-group pull-right" style="margin-top:-38px">
										  <button type="button" class="btn btn-primary" onclick="printTable()">
											 <i class="fa fa-print"></i> Print</button>
										  <a type="button" download="Inventory.xls" onclick="return ExcellentExport.excel(this, 'excel_table', 'Inventory');" class="btn btn-primary"><i class="fa fa-file-excel-o"></i> Excel</a>
										</div>
									<div class="clearfix"></div>
								  </div>
								  <div class="x_content">
									  <div class="row">
										  <br>
										  <div class="col-md-9 col-sm-9 col-xs-9">
											<div class="input-group ">
												<input type="text" class="form-control" style="height:45px; background-color:#d3ead9;" placeholder="Search Books" id="filter">
												<span class="input-group-addon"><i class="fa fa-search" style="width:30px"></i></span>
											  </div>
										  </div>
										  <div class="col-md-3 col-sm-3 col-xs-3">
											 <a href="books_form.php" class="btn btn-default pull-right" style="margin-top:-10px; border: 2px solid #52b3a0"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Register Books">
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
											   <center><h3 id="title_name">INVENTORY BOOKS REPORT</h3></center>
											  <br>
											  <table class="table table-bordered  jambo_table" >
												  <thead>
													<tr>
													 <th width="5%">No.</th>
													  <th width="25%">Book Title</th>
													  <th width="15%">Author Name</th>
													  <th width="5%">Total</th>
													  <th width="5%">Remaining</th>
													   <th width="5%">Borrowed</th>
													  <th width="5%">Lost</th>
													  <th width="5%">Damage</th>
													  <th width="10%"class="hidden-print text-center">Option</th>
													</tr>
												  </thead>
												  <tbody class="searchable" id="">

													  <?php

														$count = $start;
														$limit = 0;
													  
														if(count($data_books) >= $end) {
															$limit = $end;
														}
														else {
															$limit = count($data_books);
														}
														if(count($data_books) > 0) {
															
															for($x = $start; $x < $limit; $x++) {
																
																$count++;
																$book_id = $data_books[$x]['book_id'];
																$status = "";
																$remaining_qty = $data_books[$x]['qty_in'] - $data_books[$x]['qty_out'];
																$borrowed = count($book->displayRecord("SELECT * FROM tbl_borrowed JOIN tbl_copy ON tbl_borrowed.account_no=tbl_copy.account_no JOIN tbl_books ON tbl_copy.book_id=tbl_books.book_id JOIN tbl_borrowers ON tbl_borrowed.borrower_id=tbl_borrowers.borrower_id WHERE tbl_copy.remarks='Borrowed' AND tbl_copy.book_id='$book_id' GROUP BY tbl_copy.account_no"));
																$lost = $auth->totalRow("SELECT COUNT(tbl_copy.account_no) as quantity FROM tbl_books JOIN tbl_copy ON tbl_books.book_id=tbl_copy.book_id JOIN tbl_borrowed ON tbl_copy.account_no=tbl_borrowed.account_no WHERE tbl_copy.status='Lost' AND tbl_borrowed.status='Lost' AND tbl_copy.book_id='$book_id'");
																$damaged = $auth->totalRow("SELECT COUNT(tbl_copy.account_no) as quantity FROM tbl_books JOIN tbl_copy ON tbl_books.book_id=tbl_copy.book_id JOIN tbl_borrowed ON tbl_copy.account_no=tbl_borrowed.account_no WHERE tbl_copy.status='Damaged' AND tbl_borrowed.status='Damaged' AND tbl_copy.book_id='$book_id'");
																
																echo 
																'<tr>
																	<td>'.$count.'</td>
																	<td>'.$data_books[$x]['title'].'</td>
																	<td>'.$data_books[$x]['author'].'</td>
																	<td class="text-center">'.$data_books[$x]['qty_in'].'</td>
																	<td class="text-center">'.$remaining_qty.'</td>
																	<td class="text-center">'.$borrowed.'</td>
																	<td class="text-center">'.$lost.'</td>
																	<td class="text-center">'.$damaged.'</td>
																	<td class="hidden-print">
																	<center>
																	<div class="btn-group">
																		  <a href="books_list.php? book_id='.$book_id.' & book_token='.$token.'" class="btn btn-primary btn-xs" >
																			 <i class="fa fa-folder"></i> View</a>
																			<a href="books_edit.php? book_id='.$book_id.' & book_token='.$token.'" class="btn btn-success btn-xs" >
																			 <i class="fa fa-edit"></i> Edit</a>
																		</div>
																		</center>
																	</td></tr>';
															}
														}
													  	else {
															echo '<tr class="danger"><td colspan="9"><h3 class="text-center">No records available.</h3></td></tr>';
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
																<p>Showing '.$count.' of '.$total_inventory.' entries</p>
																<form method="get" style="margin-top: -30px">
																	<input type="hidden" name="next_start" value="'.$next_start.'">
																	<input type="hidden" name="next_end" value="'.$next_end.'">
																	<button type="submit" name="next" class="btn btn-primary pull-right margin">Next <i class="fa fa-arrow-right"></i></button>
																</form>
															</div>';
														}
														else if(count($data_books) > $end) {
															echo '
															<div class="box-footer" style="border-top: 1px solid #afaaaa;">
																<p>Showing '.$count.' of '.$total_inventory.' entries</p>
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
																<p>Showing '.$count.' of '.$total_inventory.' entries</p>
																<form method="get" style="margin-top: -30px">
																	<input type="hidden" name="previous_start" value="'.$previous_start.'">
																	<input type="hidden" name="previous_end" value="'.$previous_end.'">
																	<button type="submit" name="previous" class="btn btn-primary pull-right"><i class="fa fa-arrow-left"></i> Previous </button>
																</form>
															</div>';
														}
													}
													else {
														if(count($data_books) > 0 && $previous_end  > 0) {
															echo '
															<div class="box-footer" style="border-top: 1px solid #afaaaa;">
																<p>Showing '.$count.' of '.$total_inventory.' entries</p>
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
																<p>Showing '.$count.' of '.$total_inventory.' entries</p>
															</div>';
														}
													}
												  ?>
											  <table id="excel_table" class="table table-bordered  jambo_table hide">
												  <tr>
													  <td colspan="8"><p style="font-size:20px">INVENTORY BOOKS REPORT</p></td>
												  </tr>
													<tr>
													  <th width="5%">No.</th>
													  <th width="30%">Book Title</th>
													  <th width="15%">Author Name</th>
													  <th width="10%">Total</th>
													  <th width="10%">Remaining</th>
													   <th width="10%">Borrowed</th>
													  <th width="10%">Lost</th>
													  <th width="10%">Damage</th>
													</tr>
												  <tbody class="searchable" id="printData">
												   <?php

														$count = $start;
														$limit = 0;
													  
														if(count($data_books) >= $end) {
															$limit = $end;
														}
														else {
															$limit = count($data_books);
														}
														if(count($data_books) > 0) {
															
															for($x = $start; $x < $limit; $x++) {
																
																$count++;
																$book_id = $data_books[$x]['book_id'];
																$status = "";
																$remaining_qty = $data_books[$x]['qty_in'] - $data_books[$x]['qty_out'];
																$borrowed = count($book->displayRecord("SELECT * FROM tbl_borrowed JOIN tbl_copy ON tbl_borrowed.account_no=tbl_copy.account_no JOIN tbl_books ON tbl_copy.book_id=tbl_books.book_id JOIN tbl_borrowers ON tbl_borrowed.borrower_id=tbl_borrowers.borrower_id WHERE tbl_copy.remarks='Borrowed' AND tbl_copy.book_id='$book_id' GROUP BY tbl_copy.account_no"));
																$lost = $auth->totalRow("SELECT COUNT(tbl_copy.account_no) as quantity FROM tbl_books JOIN tbl_copy ON tbl_books.book_id=tbl_copy.book_id JOIN tbl_borrowed ON tbl_copy.account_no=tbl_borrowed.account_no WHERE tbl_copy.status='Lost' AND tbl_borrowed.status='Lost' AND tbl_copy.book_id='$book_id'");
																$damaged = $auth->totalRow("SELECT COUNT(tbl_copy.account_no) as quantity FROM tbl_books JOIN tbl_copy ON tbl_books.book_id=tbl_copy.book_id JOIN tbl_borrowed ON tbl_copy.account_no=tbl_borrowed.account_no WHERE tbl_copy.status='Damaged' AND tbl_borrowed.status='Damaged' AND tbl_copy.book_id='$book_id'");
																
																echo
																'<tr>
																	<td>'.$count.'</td>
																	<td>'.$data_books[$x]['title'].'</td>
																	<td>'.$data_books[$x]['author'].'</td>
																	<td class="text-center">'.$data_books[$x]['qty_in'].'</td>
																	<td class="text-center">'.$remaining_qty.'</td>
																	<td class="text-center">'.$borrowed.'</td>
																	<td class="text-center">'.$lost.'</td>
																	<td class="text-center">'.$damaged.'</td>
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
									<h3 style="margin-left:60px; margin-top:-37px">Receiving Logs</h3>
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
											  <div id="printdata">
												   <h3>List of logs</h3>
												  <table class="table table-bordered  jambo_table">
													  <thead>
														<tr>
														  <th width="10px">No.</th>
														  <th>ISBN</th>
														  <th>Book Title</th>
														  <th>Author Name</th>
														  <th>Quantity</th>
														  <th>Date Received</th>
														  <th>Received By</th>
															<th>Option</th>
														</tr>
													  </thead>
													  <tbody class="searchable2" id="">
														  <?php
														  
														  	$record = "";
														  	$count = 0;
															$record = $record.'<tr class="success"><td colspan="8"><h4 class="text-left">Year '.$year.'</h4></td></tr>';
															$count2 = 0;
															$count++;
															$sql = "SELECT tbl_booklogs.booklogs_id,tbl_books.book_id, tbl_books.isbn, tbl_books.title, tbl_books.author, tbl_books.pages, tbl_booklogs.quantity, tbl_booklogs.date, tbl_employee.firstname, tbl_employee.lastname FROM tbl_booklogs JOIN tbl_books ON tbl_booklogs.book_id=tbl_books.book_id JOIN tbl_employee ON tbl_booklogs.user_id=tbl_employee.user_id WHERE EXTRACT(YEAR FROM date)='$year' AND tbl_booklogs.status='Received' ORDER BY tbl_booklogs.date DESC";
															$data_logs = $book->displayRecord($sql);

															foreach($data_logs as $value) {

																$count2++;
																$book_id = $value['book_id'];
																$id = $value['booklogs_id'];
																$date = $value['date'];
																$record = $record.
																"<tr>
																	<td>".$count2."</td>
																	<td>".$value['isbn']."</td>
																	<td>".$value['title']."</td>
																	<td>".$value['author']."</td>
																	<td class='text-center'>".$value['quantity']."</td>
																	<td>".date("M d, Y", strtotime($value['date']))."</td>
																	<td>".$value['firstname']." ".$value['lastname']."</td>
																	<td> <button class='btn btn-primary btn-xs' onclick=viewlogs('$id')><i class='fa fa-eye'> View</i></button></td>
																</tr>";
															}
														  	if($count == 0) {
															  $record = $record.'<tr class="danger"><td colspan="8"><h3 class="text-center">No records available.</h3></td></tr>';
														  }
														  	echo $record;
															
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
																				 
																				 if(count($data_years) > 0) {
																					 foreach($data_years as $value) {
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
														  <h4 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-info-sign"></i> Logs Details</h4>
														</div>
														<div class="modal-body">
															  <table class="table table-bordered  jambo_table">
																  <thead>
																	<tr>
																	  <th width="100px">No.</th>
																	  <th>Acc. no</th>
																		<th>Title</th>
																	</tr>
																  </thead>
																  <tbody id="data_view">
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
																	  <form action="monthly_books.php" method="post">
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
																	  <form action="monthly_books.php" method="post">
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
																	   <form action="monthly_books.php" method="post">
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
																	   <form action="monthly_books.php" method="post">
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
																	   <form action="monthly_books.php" method="post">
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
																	   <form action="monthly_books.php" method="post">
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
																	   <form action="monthly_books.php" method="post">
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
																	   <form action="monthly_books.php" method="post">
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
																	   <form action="monthly_books.php" method="post">
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
																	   <form action="monthly_books.php" method="post">
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
																	   <form action="monthly_books.php" method="post">
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
																	   <form action="monthly_books.php" method="post">
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
		<script>
			function select_year() {
				$("#modal_year").modal("show");
			}
			function viewlogs(id) {
				$("#modal_logs").modal("show");
				$.ajax({
					url: "model/books.php",
					dataType:'json',
					type: "POST",
					data:{ 
						action:"view_copy",
						id: id
					},
					success: function(data) {
						console.log(data);
						viewrecord(data);
					},
					error: function(){
						alert("error");
					}
				});
			}
			function viewrecord(data) {
				var list = data.data;
				var length = list.length;
				var count = 0;
				var html = "";
				
				for(var x = 0; x < length; x++) {
					
					count++;
					html = html + 
					'<tr>' +
						'<td>' + count + '</td>' +
						'<td> ' + list[x].account_no + '</td>' +
						'<td> ' + list[x].title + '</td>' +
					'</tr>';
				}
				$("#data_view").html(html);
			}
			
			$(document).ready(function(){
				
				
				$('#filter').keyup(function() {
				
					var rex = new RegExp($(this).val(), 'i');
					$('.searchable tr').hide();
					$('.searchable tr').filter(function() {

						return rex.test($(this).text());
					}).show();
				});
				$('#filter2').keyup(function() {
				
					var rex = new RegExp($(this).val(), 'i');
					$('.searchable2 tr').hide();
					$('.searchable2 tr').filter(function() {

						return rex.test($(this).text());
					}).show();
				});
				
				$('#refresh').click(function() {
					$("#filter").val("");
					$('.searchable tr').show();
				});
				$('#refresh2').click(function() {
					$("#filter2").val("");
					$('.searchable2 tr').show();
				});
			});
			
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
								 <center><h3 id="title_name">INVENTORY OF BOOKS REPORT</h3></center>\
						  		<br>\
								<table class="table">\
									<thead>\
									<th width="5%" style="border: 1px solid black">No.</th>\
									  <th width="30%" style="border: 1px solid black">Book Title</th>\
									  <th width="15%" style="border: 1px solid black">Author Name</th>\
									  <th width="10%" style="border: 1px solid black">Total</th>\
									  <th width="10%" style="border: 1px solid black">Remaining</th>\
									   <th width="10%" style="border: 1px solid black">Borrowed</th>\
									  <th width="10%" style="border: 1px solid black">Lost</th>\
									  <th width="10%" style="border: 1px solid black">Damage</th>\
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
