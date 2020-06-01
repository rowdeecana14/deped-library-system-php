<?php
   	require_once "model/controller.php";
	require_once "model/model.php";
	date_default_timezone_set('Asia/Manila');
	$database = new Database;
	$year =  date("Y");
	$damaged = new Model;
	$_SESSION['current_page'] = "damaged_books.php";

	$sql = "SELECT * FROM tbl_books JOIN tbl_copy ON tbl_books.book_id=tbl_copy.book_id JOIN tbl_borrowed ON tbl_copy.account_no=tbl_borrowed.account_no WHERE tbl_copy.status='Damaged' AND tbl_borrowed.status='Damaged' ORDER BY tbl_copy.book_id, tbl_copy.copy";
	$data_damaged = $damaged->displayRecord($sql);
	$total_damaged = count($data_damaged);


	$month_list = ['01', '02', '03', '04', '05', '06', '07','08', '09', '10', '11', '12'];
	$string_month = array('01' =>"January", '02' =>"February", '03' =>"March", '04' =>"April", '05' =>"May", '06' =>"June", '07' =>"July",'08' =>"August",'09' =>"Septempber", '10' =>"October", '11' =>"November", '12' =>"December");
	$data_graph = "";
	$counted = 0;
	foreach($month_list as $month) {
		$sql = "SELECT COUNT(tbl_copy.account_no) AS quantity FROM tbl_books JOIN tbl_copy ON tbl_books.book_id=tbl_copy.book_id JOIN tbl_borrowed ON tbl_copy.account_no=tbl_borrowed.account_no WHERE tbl_copy.status='Damaged' AND tbl_borrowed.status='Damaged' AND EXTRACT(YEAR FROM tbl_borrowed.date_returned)='$year' AND EXTRACT(MONTH FROM tbl_borrowed.date_returned)='$month'";
		$quantity = $damaged->displayRecord($sql);
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
		$sql = "SELECT COUNT(tbl_copy.account_no) AS quantity FROM tbl_books JOIN tbl_copy ON tbl_books.book_id=tbl_copy.book_id JOIN tbl_borrowed ON tbl_copy.account_no=tbl_borrowed.account_no WHERE tbl_copy.status='Damaged' AND tbl_borrowed.status='Damaged' AND EXTRACT(YEAR FROM tbl_borrowed.date_returned)='$year' AND EXTRACT(MONTH FROM tbl_borrowed.date_returned)='$month'";
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
    <body class="nav-md" onload="displaySetting()" onclick="resetTimer()" onmousedown="resetTimer()" onmousemove="resetTimer()" onscroll="resetTimer()" onkeypress="resetTimer()">
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
                <div class="right_col" role="main" >
				<div class="" role="main" style="">
					<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12" role="tabpanel" data-example-id="togglable-tabs"  >
						<ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
						  <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true"><span class="badge bg-blue "><i class="fa fa-table"></i></span>   Damaged</a>
						  </li>
							<li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false"><span class="badge bg-blue "><i class="fa fa-bar-chart"></i></span> Records & Graph</a>
							
						  </li>

						</ul>
						<div id="myTabContent" class="tab-content">
						  <div role="tabpanel" class="tab-pane fade active in"  id="tab_content1" aria-labelledby="home-tab">
							<div class="x_panel">
							  <div class="x_title">
								 <img src="images/book_damaged.png" width="50px" height="50px">
								<h3 style="margin-left:60px; margin-top:-35px">Damaged books</h3>
								  <div class="btn-group pull-right" style="margin-top:-38px">
									  <button type="button" class="btn btn-primary" onclick="printTable()">
										 <i class="fa fa-print"></i> Print</button>
									  <a type="button" download="Listofdamagedbooks.xls" onclick="return ExcellentExport.excel(this, 'table2', 'Listofdamagedbooks');" class="btn btn-primary"><i class="fa fa-file-excel-o"></i> Excel</a>
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
								<div  class="col-md-12 col-sm-12 col-xs-12">
									  <hr>
								  </div>
								  <div class="col-md-12 col-sm-12 col-xs-12">
									  <center><h3 id="title_name">LIST OF DAMAGED BOOKS</h3></center>
									  <br>
									  <table class="table table-bordered table-striped jambo_table">
										  <thead>
											<tr>
												<th>No</th>
											  <th width="10%">Acc. No</th>
											  <th width="25%">Book Title</th>
											  <th width="15%">Author Name</th>
											  <th width="5%">No of pages</th>
											  <th width="5%">Source of fund</th>
											  <th width="10%">Copyright</th>
											  <th width="15%">Publisher</th>
											  <th width="15%">ISBN</th>
											  <th width="" class="hidden-print">Option</th>
											</tr>
										  </thead>
										  <tbody class="searchable">
										  <?php
												$count = $start;
												$limit = 0;

												if(count($data_damaged) >= $end) {
													$limit = $end;
												}
												else {
													$limit = count($data_damaged);
												}
												if(count($data_damaged) > 0) {

													for($x = $start; $x < $limit; $x++) {

														$count++;
														$borrowed_id = $data_damaged[$x]['borrowed_id'];
														echo 
														'<tr>
															<td>'.$count.'</td>
															<td>'.$data_damaged[$x]['account_no'].'</td>
															<td>'.$data_damaged[$x]['title'].'</td>
															<td>'.$data_damaged[$x]['author'].'</td>
															<td>'.$data_damaged[$x]['pages'].'</td>
															<td>'.$data_damaged[$x]['fund'].'</td>
															<td>'.$data_damaged[$x]['copyright'].'</td>
															<td>'.$data_damaged[$x]['publisher'].'</td>
															<td>'.$data_damaged[$x]['isbn'].'</td>
															<td class="hidden-print">
																<button type="button" class="btn btn-primary btn-xs" onclick=viewlogs("'.$borrowed_id.'")><i class="fa fa-eye"></i> View Details</button>
															</td>
														</tr>';
													}
												}
												else {
													echo '<tr class="danger"><td colspan="10"><h3 class="text-center">No records available.</h3></td></tr>';
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
														<p>Showing '.$count.' of '.$total_damaged.' entries</p>
														<form method="get" style="margin-top: -30px">
															<input type="hidden" name="next_start" value="'.$next_start.'">
															<input type="hidden" name="next_end" value="'.$next_end.'">
															<button type="submit" name="next" class="btn btn-primary pull-right margin">Next <i class="fa fa-arrow-right"></i></button>
														</form>
													</div>';
												}
												else if(count($data_damaged) > $end) {
													echo '
													<div class="box-footer" style="border-top: 1px solid #afaaaa;">
														<p>Showing '.$count.' of '.$total_damaged.' entries</p>
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
														<p>Showing '.$count.' of '.$total_damaged.' entries</p>
														<form method="get" style="margin-top: -30px">
															<input type="hidden" name="previous_start" value="'.$previous_start.'">
															<input type="hidden" name="previous_end" value="'.$previous_end.'">
															<button type="submit" name="previous" class="btn btn-primary pull-right"><i class="fa fa-arrow-left"></i> Previous </button>
														</form>
													</div>';
												}
											}
											else {
												if(count($data_damaged) > 0 && $previous_end  > 0) {
													echo '
													<div class="box-footer" style="border-top: 1px solid #afaaaa;">
														<p>Showing '.$count.' of '.$total_damaged.' entries</p>
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
														<p>Showing '.$count.' of '.$total_damaged.' entries</p>
													</div>';
												}
											}
										  ?>
										<table class="table table-bordered table-striped jambo_table hide" id="table2">
											<tr>
											  <td colspan="9"><p style="font-size:20px">LIST OF DAMAGED BOOKS</p></td>
											</tr>
											<tr>
											  <th>No</th>
											  <th width="10%">Acc. No</th>
											  <th width="25%">Book Title</th>
											  <th width="15%">Author Name</th>
											  <th width="5%">No of pages</th>
											  <th width="5%">Source of fund</th>
											  <th width="10%">Copyright</th>
											  <th width="15%">Publisher</th>
											  <th width="15%">ISBN</th>
											</tr>
										  <tbody class="searchable" id="printData">
										   <?php
												$count = $start;
												$limit = 0;

												if(count($data_damaged) >= $end) {
													$limit = $end;
												}
												else {
													$limit = count($data_damaged);
												}
												if(count($data_damaged) > 0) {

													for($x = $start; $x < $limit; $x++) {

														$count++;
														$borrowed_id = $data_damaged[$x]['borrowed_id'];
														echo 
														'<tr>
															<td>'.$count.'</td>
															<td>'.$data_damaged[$x]['account_no'].'</td>
															<td>'.$data_damaged[$x]['title'].'</td>
															<td>'.$data_damaged[$x]['author'].'</td>
															<td>'.$data_damaged[$x]['pages'].'</td>
															<td>'.$data_damaged[$x]['fund'].'</td>
															<td>'.$data_damaged[$x]['copyright'].'</td>
															<td>'.$data_damaged[$x]['publisher'].'</td>
															<td>'.$data_damaged[$x]['isbn'].'</td>
														</tr>';
													}
												}
												else {
													echo '<tr class="danger"><td colspan="9"><h3 class="text-center">No records available.</h3></td></tr>';
												}
										  ?>
										  </tbody>
										</table>
									  <div class="modal fade" id="modal" role="dialog">
										<div class="modal-dialog modal-md">
										  <div class="modal-content">
											<div class="modal-header">
											  <button type="button" class="close" data-dismiss="modal">&times;</button>
											  <h4 class="modal-title" style="font-weight:bold;"><span class="badge bg-green"><i class="fa fa-history"></i></span> Logs Details</h4>
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
																</ul>
														</div>
													</div>
												</div>
											</div>
											<div class="modal-footer">
											  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
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
																	  <form action="monthly_damaged.php" method="post">
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
																	  <form action="monthly_damaged.php" method="post">
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
																	   <form action="monthly_damaged.php" method="post">
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
																	   <form action="monthly_damaged.php" method="post">
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
																	   <form action="monthly_damaged.php" method="post">
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
																	   <form action="monthly_damaged.php" method="post">
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
																	   <form action="monthly_damaged.php" method="post">
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
																	   <form action="monthly_damaged.php" method="post">
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
																	   <form action="monthly_damaged.php" method="post">
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
																	   <form action="monthly_damaged.php" method="post">
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
																	   <form action="monthly_damaged.php" method="post">
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
																	   <form action="monthly_damaged.php" method="post">
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
			function viewlogs(borrowed_id) {
				$("#modal").modal("show");
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
						$("#borrower2").text(data.data[0].firstname + " " + data.data[0].lastname);
						$("#accessionno2").text(data.data[0].account_no);
						$("#titlebook2").text(data.data[0].title);
						$("#issuedby2").text(data.data[0].emp_fname + " " + data.data[0].emp_lname);
						$("#date_borrowed2").text(data.data[0].date_borrowed);
						$("#receivedby2").text(data.data[0].re_fname + " " + data.data[0].re_lname);
						$("#date_returned2").text(data.data[0].date_returned);
					},
					error: function(){
						alert("error");
					}
				});
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
									<h6 style="margin-top:-18px; font-size:9px; font-weight:bold" id="line6">\
										<span id="tel_no" style="margin-left:1%">Tel. No. ' + tel_no + '</span>\
										<span id="telefax_no" style="margin-left:15%">Telefax No. ' + telefax_no + '</span>\
										<span id="email" style="margin-left:21%">Email: ' + email + '</span>\
										<span id="web" style="margin-left:10%">web: ' + web + '</span>\
									</h6>\
								</div>\
								<div class="col-xs-12" style="margin-top:-108px; margin-left:-40px">\
									<img src="images_uploaded/'+rightlogo+'" class="pull-right  right_logo" width="70px" height="70px" id="rl">\
								</div>\
							</div>\
							<div class="col-xs-12">\
								<br>\
								 <center><h3 id="title_name">LIST OF DAMAGED BOOKS</h3></center>\
						  		<br>\
								<table class="table">\
									<thead>\
									<th style="border: 1px solid black">No</th>\
									  <th width="10%" style="border: 1px solid black">Acc. No</th>\
									  <th width="30%" style="border: 1px solid black">Book Title</th>\
									  <th width="15%" style="border: 1px solid black">Author Name</th>\
									  <th width="5%" style="border: 1px solid black">No of pages</th>\
									  <th width="5%" style="border: 1px solid black">Source of fund</th>\
									  <th width="10%" style="border: 1px solid black">Copyright</th>\
									  <th width="10%" style="border: 1px solid black">Publisher</th>\
									  <th width="15%" style="border: 1px solid black">ISBN</th>\
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
