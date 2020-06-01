<?php
	require_once "model/controller.php";
	require_once "model/model.php";
	date_default_timezone_set('Asia/Manila');
	$database = new Database;
	$attendance = new Model;
	$date1 = date("Y-m-d");
	$date2 = date("Y-m-d");
	$_SESSION['current_page'] = "daily_attendance.php";
	$sql = "SELECT tbl_borrowed.date_borrowed, tbl_borrowers.position,  tbl_borrowers.firstname, tbl_borrowers.lastname, tbl_borrowed.purpose, tbl_books.title, tbl_books.author, tbl_borrowed.account_no, tbl_books.classification FROM tbl_borrowed JOIN tbl_copy ON tbl_borrowed.account_no=tbl_copy.account_no JOIN tbl_books ON tbl_copy.book_id=tbl_books.book_id JOIN tbl_borrowers ON tbl_borrowed.borrower_id=tbl_borrowers.borrower_id WHERE tbl_borrowed.date_borrowed='$date1' ORDER BY tbl_borrowed.date_borrowed, tbl_borrowed.borrowed_id";
	$attendance_data = $attendance->displayRecord($sql);
	
	if($_SERVER['REQUEST_METHOD'] == "POST") {
		if(isset($_POST['search'])) {
			
			$date1 = $_POST['date_start'];
			$date2 = $_POST['date_end'];
			$date_s = explode("/", $_POST['date_start']);
			$date_start = $date_s[2]."-".$date_s[0]."-".$date_s[1];
			$date_e = explode("/", $_POST['date_end']);
			$date_end = $date_e[2]."-".$date_e[0]."-".$date_e[1];

			$_SESSION['current_page'] = "daily_attendance.php";
			$sql = "SELECT tbl_borrowed.date_borrowed, tbl_borrowers.position, tbl_borrowers.firstname, tbl_borrowers.lastname, tbl_borrowed.purpose, tbl_books.title, tbl_books.author, tbl_borrowed.account_no, tbl_books.classification FROM tbl_borrowed JOIN tbl_copy ON tbl_borrowed.account_no=tbl_copy.account_no JOIN tbl_books ON tbl_copy.book_id=tbl_books.book_id JOIN tbl_borrowers ON tbl_borrowed.borrower_id=tbl_borrowers.borrower_id WHERE tbl_borrowed.date_borrowed BETWEEN '$date_start' AND '$date_end' ORDER BY tbl_borrowed.date_borrowed, tbl_borrowed.borrowed_id";
			$attendance_data = $attendance->displayRecord($sql);
		}
	}
	$total_attendance = count($attendance_data);
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
		<link href="include/mycss.css" rel="stylesheet">
		<style>
			table {
				font-size: 12px;
			}
			#title_name {
				font-size:16px; 
				font-weight: bold;
			}
			hr {
				border-top: 2px solid #D3D6DA;
			}
		</style>
    </head>
    <body class="nav-md" onload="displaySetting()" onclick="resetTimer()" onmousedown="resetTimer()" onmousemove="resetTimer()" onscroll="resetTimer()" onkeypress="resetTimer()">
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
						<img src="images/attendance.png" width="50px" height="50px">
						<h3 style="margin-left:60px; margin-top:-38px">Attendance and Book borrowed</h3>
						<div class="btn-group pull-right" style="margin-top:-40px">
						  <button type="button" class="btn btn-primary" onclick="printTable()">
							 <i class="fa fa-print"></i> Print</button>
						  <a type="button" download="dailyattendance.xls" onclick="return ExcellentExport.excel(this, 'excel_table', 'Daily_Attendance');" class="btn btn-primary"><i class="fa fa-file-excel-o"></i> Excel</a>
						</div> 
						<div class="clearfix"></div>
					  </div>
					  <div class="x_content">
						
						  <form action="daily_attendance.php" method="post">
						  <div  class="col-md-4 col-sm-4 col-xs-4">
							   <label class="col-md-12 col-sm-12 col-xs-12 form-group">Date start: </label>
								<div class="col-md-12 col-sm-12 col-xs-12 form-group">
									<input type="text" class="form-control  has-feedback-left" name="date_start" value="" placeholder="Date start" id="single_cal4"  aria-describedby="inputSuccess2Status4" required />
									<span class="fa fa-calendar blue form-control-feedback left" aria-hidden="true" style="color:black"></span>
								</div>
						  </div>
						  <div  class="col-md-4 col-sm-4 col-xs-4">
							   <label class="col-md-12 col-sm-12 col-xs-12 form-group">Date end: </label>
								<div class="col-md-12 col-sm-12 col-xs-12 form-group">
									<input type="text" class="form-control  has-feedback-left single_cal3" name="date_end" value="" placeholder="Date end" id="single_cal3"  aria-describedby="inputSuccess2Status4" required />
									<span class="fa fa-calendar blue form-control-feedback left" aria-hidden="true" style="color:black"></span>
								</div>
						  </div>
						   <div  class="col-md-4 col-sm-4 col-xs-4">
							   <button type="submit" name="search" class="btn btn-primary" style="margin-top:27px"><i class="fa fa-search"></i> Search</button>
						  </div>
						  <div  class="col-md-12 col-sm-12 col-xs-12">
							  <hr>
						  </div>
							</form>
					  <div id="printdata" class="col-md-12 col-sm-12 col-xs-12">
						   <center><h3 id="title_name">LC/LRC DAILY LOG ON ATTENDANCE AND BOOKS BORROWED</h3></center>
						  <br>
						  <table class="table table-bordered  jambo_table">
							  <thead>
								<tr>
								  <th width="15%">Date</th>
								  <th width="15%">Name</th>
								  <th width="5%">Grade Level</th>
								  <th width="5%">Study</th>
								  <th width="5%">Borrow</th>
								  <th width="30%">Book Title</th>
								  <th width="15%">Author/Editor</th>
								  <th width="10%">Accession Number</th>
								  <th width="10%">Classification Number</th>
								</tr>
							  </thead>
							  <tbody class="searchable" id="data">
								  <?php

									$count = 0;
									$total_s = 0;
									$total_b = 0;
								  
								  	$count = $start;
									$limit = 0;

									if(count($attendance_data) >= $end) {
										$limit = $end;
									}
									else {
										$limit = count($attendance_data);
									}
									if(count($attendance_data) > 0) {

										for($x = $start; $x < $limit; $x++) {

											$count++;
											$status_s = "";
											$status_b = "";
											if($attendance_data[$x]['purpose'] == "Study") {
												$status_s = "<center><p><i class='fa fa-check'></i></p></center>";
												$total_s++;
											}
											if($attendance_data[$x]['purpose'] == "Borrow") {
												$status_b = "<center><p><i class='fa fa-check'></i></p></center>";
												$total_b++;
											}
											echo 
											'<tr>
												<td>'.date("F d Y", strtotime($attendance_data[$x]['date_borrowed'])).'</td>
												<td>'.$attendance_data[$x]['firstname']. " ".$attendance_data[$x]['lastname'].'</td>
												<td>'.$attendance_data[$x]['position'].'</td>
												<td>'.$status_s.'</td>
												<td>'.$status_b.'</td>
												<td>'.$attendance_data[$x]['title'].'</td>
												<td>'.$attendance_data[$x]['author'].'</td>
												<td>'.$attendance_data[$x]['account_no'].'</td>
												<td>'.$attendance_data[$x]['classification'].'</td>
											</tr>';
										}
										echo '<tr>
											<td colspan="2" style="color:white;"></td>
											<td style="font-weight:bold">Subtotal</td>
											<td class="text-center" style="font-weight:bold">'.$total_s.'</td>
											<td class="text-center" style="font-weight:bold">'.$total_b.'</td>
											<td colspan="2" style="color:white;"></td>
											<td style="font-weight:bold">Subtotal</td>
											<td></td>
										</tr>';
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
										<p>Showing '.$count.' of '.$total_attendance.' entries</p>
										<form method="get" style="margin-top: -30px">
											<input type="hidden" name="next_start" value="'.$next_start.'">
											<input type="hidden" name="next_end" value="'.$next_end.'">
											<button type="submit" name="next" class="btn btn-primary pull-right margin">Next <i class="fa fa-arrow-right"></i></button>
										</form>
									</div>';
								}
								else if(count($attendance_data) > $end) {
									echo '
									<div class="box-footer" style="border-top: 1px solid #afaaaa;">
										<p>Showing '.$count.' of '.$total_attendance.' entries</p>
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
										<p>Showing '.$count.' of '.$total_attendance.' entries</p>
										<form method="get" style="margin-top: -30px">
											<input type="hidden" name="previous_start" value="'.$previous_start.'">
											<input type="hidden" name="previous_end" value="'.$previous_end.'">
											<button type="submit" name="previous" class="btn btn-primary pull-right"><i class="fa fa-arrow-left"></i> Previous </button>
										</form>
									</div>';
								}
							}
							else {
								if(count($attendance_data) > 0 && $previous_end  > 0) {
									echo '
									<div class="box-footer" style="border-top: 1px solid #afaaaa;">
										<p>Showing '.$count.' of '.$total_attendance.' entries</p>
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
										<p>Showing '.$count.' of '.$total_attendance.' entries</p>
									</div>';
								}
							}
						  ?>
					  </div>
						  <table class="table table-bordered  jambo_table hide" id="excel_table">
							  <tr>
								  <td colspan="9"><h3>LC/LRC DAILY LOG ON ATTENDANCE AND BOOKS BORROWED</h3></td>
							  </tr>
								<tr>
								 <th width="15%">Date</th>
								  <th width="15%">Name</th>
								  <th width="5%">Grade Level</th>
								  <th width="5%">Study</th>
								  <th width="5%">Borrow</th>
								  <th width="30%">Book Title</th>
								  <th width="15%">Author/Editor</th>
								  <th width="10%">Accession Number</th>
								  <th width="10%">Classification Number</th>
								</tr>
							  <tbody class="searchable" id="data">
								  <?php

									$record = "";
									$count = 0;
									$total_s = 0;
									$total_b = 0;
								  
								  	if(count($attendance_data) > 0) {
										foreach($attendance_data as $value) {
											$count++;
											$status_s = "";
											$status_b = "";
											if($value['purpose'] == "Study") {
												$status_s = "<center>/</center>";
												$total_s++;
											}
											if($value['purpose'] == "Borrow") {
												$status_b = "<center>/</center>";
												$total_b++;
											}
											$record = $record.
											'<tr>
												<td>'.date("F d Y", strtotime($value['date_borrowed'])).'</td>
												<td>'.$value['firstname']. " ".$value['lastname'].'</td>
												<td></td>
												<td>'.$status_s.'</td>
												<td>'.$status_b.'</td>
												<td>'.$value['title'].'</td>
												<td>'.$value['author'].'</td>
												<td>'.$value['account_no'].'</td>
												<td>'.$value['classification'].'</td>
												</tr>';

										}
										$record = $record.'
										<tr>
											<td colspan="2" style="color:white;">blank</td>
											<td style="font-weight:bold">Subtotal</td>
											<td class="text-center" style="font-weight:bold">'.$total_s.'</td>
											<td class="text-center" style="font-weight:bold">'.$total_b.'</td>
											<td colspan="2" style="color:white;">blank</td>
											<td style="font-weight:bold">Subtotal</td>
											<td></td>
										</tr>';
									}
									
								  if($count == 0) {
									  $record = $record.'<tr class="danger"><td colspan="9"><h3 class="text-center">No records available.</h3></td></tr>';
								  }
								  echo $record;


								  ?>
							  </tbody>
						</table>
					  </div>
					</div>
				  </div>
			
            </div>
            <?php include'include/footer.php';?>
        </div>
         <?php include'include/js.php';?>
		<script>
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
						
				var response = document.getElementById("data");
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
									<h4 style="margin-top:-70px; font-weight:bold"  align="center" id="line1">'+line1+'</h4>\
									<h4 style="margin-top:-5px; font-weight:bold"  align="center" id="line2">'+line2+'</h4>\
									<h4 style="margin-top:-5px; font-weight:bold"  align="center" id="line3">'+line3+'</h4>\
									<h3 style="margin-top:-5px; "  align="center" id="line4">'+line4+'</h3>\
									<i><h4 style="margin-top:-5px; margin-bottom:-20px; font-weight:bold"  align="center" id="line5">'+line5+'</h4></i>\
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
								 <center><h3 id="title_name">LC/LRC DAILY LOG ON ATTENDANCE AND BOOKS BORROWED</h3></center>\
						  		<br>\
								<table class="table">\
									<thead>\
									  <th width="15%" style="border: 1px solid black">Date</th>\
									  <th width="15%"  style="border: 1px solid black">Name</th>\
									  <th width="5%"  style="border: 1px solid black">Grade Level</th>\
									  <th width="5%"  style="border: 1px solid black">Study</th>\
									  <th width="5%"  style="border: 1px solid black">Borrow</th>\
									  <th width="30%"  style="border: 1px solid black">Book Title</th>\
									  <th width="15%"  style="border: 1px solid black">Author/Editor</th>\
									  <th width="10%"  style="border: 1px solid black">Accession Number</th>\
									  <th width="10%"  style="border: 1px solid black">Classification Number</th>\
									</thead>\
								<tbody style="border: 1px solid black">' + response.innerHTML + '</tbody>\
								</table>\
							</div>\
							<div class="col-xs-12">\
								<table class="table">\
									<tbody>\
										<tr>\
											<td width="80px" style="border-color:white; font-weight:bold;">Prepared by:</td>\
											<td width="150px" style="border-color:white"></td>\
											<td style="border-color:white"></td>\
										</tr>\
										<tr>\
											<td style="border-color:white"></td>\
											<td width="150px" style="border-right-color: white"></td>\
											<td style="border-color:white"></td>\
										</tr>\
										<tr>\
											<td style="border-color:white"></td>\
											<td  width="150px" class="text-center" style="border-right-color: white; border-bottom-color: white; font-weight:bold;;">LRC Coordinator</td>\
											<td style="border-color:white"></td>\
										</tr>\
										<tr>\
											<td width="80px" style="border-color:white; font-weight:bold;">Attested by:</td>\
											<td width="150px" style="border-color:white"></td>\
											<td style="border-color:white"></td>\
											<td width="80px" style="border-color:white; font-weight:bold;">Noted by:</td>\
											<td width="150px" style="border-color:white"></td>\
											<td style="border-color:white"></td>\
										</tr>\
										<tr>\
											<td style="border-color:white"></td>\
											<td width="150px" style="border-right-color: white"></td>\
											<td style="border-color:white"></td>\
											<td width="80px" style="border-color:white; font-weight:bold;"></td>\
											<td width="150px" style="border-right-color:white"></td>\
											<td style="border-color:white"></td>\
										</tr>\
										<tr>\
											<td style="border-color:white"></td>\
											<td  width="150px" class="text-center" style="border-right-color: white; border-bottom-color: white; font-weight:bold;">LR Coordinator</td>\
											<td style="border-color:white"></td>\
											<td width="80px" style="border-color:white"></td>\
											<td width="150px" style="border-color:white; font-weight:bold;" class="text-center">School Head</td>\
											<td style="border-color:white"></td>\
										</tr>\
									</tbody>\
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
