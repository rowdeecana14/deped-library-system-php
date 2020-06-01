<?php
    
	require_once "model/controller.php";
	require_once "model/model.php";
	date_default_timezone_set('Asia/Manila');
	$year =  date("Y");
	$auth = new Database;
	$book = new Model;
	$_SESSION['current_page'] = "accession_record.php";
	$sql = "SELECT tbl_books.book_id, tbl_books.title, tbl_books.author, tbl_books.pages, tbl_books.fund, tbl_books.copyright, tbl_books.isbn, tbl_books.publisher, tbl_copy.account_no, tbl_copy.remarks, tbl_borrowed.remarks as br_status, tbl_copy.status, tbl_books.qty_in, tbl_books.qty_out FROM tbl_books JOIN tbl_copy ON tbl_books.book_id=tbl_copy.book_id LEFT JOIN tbl_borrowed ON tbl_copy.account_no=tbl_borrowed.account_no GROUP BY tbl_copy.account_no ORDER BY tbl_copy.book_id, tbl_copy.copy";
	$data_books = $book->displayRecord($sql);
	$accession_all = count($book->displayRecord("SELECT * FROM tbl_copy GROUP BY tbl_copy.account_no"));
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
    <body class="nav-md" onload="displaySetting();" onclick="resetTimer()" onmousedown="resetTimer()" onmousemove="resetTimer()" onscroll="resetTimer()" onkeypress="resetTimer()">
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
						
						<div class="x_panel" id="recordPanel" style=" ">
								  <div class="x_title">
									 <img src="images/book_inventory.jpg" width="50px" height="50px">
									<h3 style="margin-left:60px; margin-top:-38px">Accession record</h3>
									  <div class="btn-group pull-right" style="margin-top:-38px">
										  <button type="button" class="btn btn-primary" onclick="print('table')">
											 <i class="fa fa-print"></i> Print</button>
										  <a type="button" download="Accessionrecord.xls" onclick="return ExcellentExport.excel(this, 'table2', 'Accession_records');" class="btn btn-primary"><i class="fa fa-file-excel-o"></i> Excel</a>
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
											   <center><h3 id="title_name">ACCESSION RECORD</h3></center>
											  <br>
											  <table class="table table-bordered  jambo_table">
												  <thead>
													<tr>
													  <th width="10%">Accession No</th>
													  <th width="25%">Book Title</th>
													  <th width="15%">Author Name</th>
													  <th width="5%">No of pages</th>
													  <th width="5%">Source of fund</th>
													  <th width="10%">Copyright</th>
													  <th width="15%">Publisher</th>
													  <th width="10%">ISBN</th>
													  <th width="5%">Remarks</th>
													</tr>
												  </thead>
												  <tbody class="searchable">
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
																if($data_books[$x]['remarks'] == "Borrowed") {
																	$status = '<span class="label label-success">Borrowed</span>';
																}
																else {
																	if($data_books[$x]['status'] == "Okay") {
																		$status = '<span class="label label-primary">Available</span>';
																	}
																	else if($data_books[$x]['status'] == "Damaged") {
																		$status = '<span class="label label-danger">Damaged</span>';
																	}
																	else {
																		$status = '<span class="label label-warning">Lost</span>';
																	}
																}
																echo 
																'<tr>
																	<td>'.$data_books[$x]['account_no'].'</td>
																	<td>'.$data_books[$x]['title'].'</td>
																	<td>'.$data_books[$x]['author'].'</td>
																	<td>'.$data_books[$x]['pages'].'</td>
																	<td>'.$data_books[$x]['fund'].'</td>
																	<td>'.$data_books[$x]['copyright'].'</td>
																	<td>'.$data_books[$x]['publisher'].'</td>
																	<td>'.$data_books[$x]['isbn'].'</td>
																	<td>'.$status.'</td>
																</tr>';

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
																<p>Showing '.$count.' of '.$accession_all.' entries</p>
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
																<p>Showing '.$count.' of '.$accession_all.' entries</p>
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
																<p>Showing '.$count.' of '.$accession_all.' entries</p>
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
																<p>Showing '.$count.' of '.$accession_all.' entries</p>
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
																<p>Showing '.$count.' of '.$accession_all.' entries</p>
															</div>';
														}
													}
												  ?>
											  <table id="table2" class="table table-bordered  jambo_table hide">
												  <tr>
													  <td colspan="9"><p style="font-size:20px">Accession Record</p></td>
												  </tr>
													<tr>
													 <th width="10%">Acc. No</th>
													  <th width="20%">Title</th>
													  <th width="15%">Author</th>
													  <th width="5%">No of pages</th>
													  <th width="5%">Source of fund</th>
													  <th width="10%">Copyright</th>
													  <th width="15%">Publisher</th>
													  <th width="15%">ISBN</th>
													  <th width="5%">Remarks</th>
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
																$remaining_qty = $data_books[$x]['qty_in'] - $data_books[$x]['qty_out'];
																$status = "";
																if($data_books[$x]['br_status'] == "Borrowed") {
																	$status = 'Borrowed';
																}
																else {
																	if($data_books[$x]['status'] == "Okay") {
																		$status = 'Available';
																	}
																	else if($data_books[$x]['status'] == "Damaged") {
																		$status = 'Damaged';
																	}
																	else {
																		$status = 'Lost';
																	}
																}
																echo 
																'<tr>
																	<td>'.$data_books[$x]['account_no'].'</td>
																	<td>'.$data_books[$x]['title'].'</td>
																	<td>'.$data_books[$x]['author'].'</td>
																	<td class="text-center">'.$data_books[$x]['pages'].'</td>
																	<td>'.$data_books[$x]['fund'].'</td>
																	<td>'.$data_books[$x]['copyright'].'</td>
																	<td>'.$data_books[$x]['publisher'].'</td>
																	<td>'.$data_books[$x]['isbn'].'</td>
																	<td>'.$status.'</td>
																	</tr>';

															}
															 if($count == 0) {
															  	echo '<tr class="danger"><td colspan="9"><h3 class="text-center">No records available.</h3></td></tr>';
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
            <?php include'include/footer.php';?>
        </div>

         <?php include'include/js.php';?>
		<script>
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
			
			function print() {
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
								 <center><h3 id="title_name">ACCESION RECORD</h3></center>\
						  		<br>\
								<table class="table">\
									<thead>\
									 <th width="10%" style="border: 1px solid black">Acc. No</th>\
									  <th width="30%" style="border: 1px solid black">Title</th>\
									  <th width="10%" style="border: 1px solid black">Author</th>\
									  <th width="5%" style="border: 1px solid black" class="text-center">No of pages</th>\
									  <th width="5%" style="border: 1px solid black">Source of fund</th>\
									  <th width="10%" style="border: 1px solid black">Copyright</th>\
									  <th width="10%" style="border: 1px solid black">Publisher</th>\
									  <th width="15%" style="border: 1px solid black">ISBN</th>\
									  <th width="5%" style="border: 1px solid black">Remarks</th>\
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
 		</script>
    </body>
</html>
