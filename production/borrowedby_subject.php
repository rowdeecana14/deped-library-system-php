<?php
   	require_once "model/controller.php";
	require_once "model/model.php";
	date_default_timezone_set('Asia/Manila');
	$auth = new Database;
	$borrowed = new Model;
	$token = $auth->generateAuth();
	$_SESSION['bookupdate_token'] = $token;
	$_SESSION['current_page'] = "borrowedby_subject.php";
	$year = date("Y-m-d");
	$month = date("m");
	$month1_data = array();
	$month2_data = array();
	$month3_data = array();

	$month1_name = "";
	$month2_name = "";
	$month3_name = "";

	if($month == "01") {
		$month1_data = get_data($borrowed, "01", $year);
		$month2_data = array("", "", "", "", "", "", "", "", "", "", "");
		$month3_data = array("", "", "", "", "", "", "", "", "", "", "");
		
		$month1_name = "January";
	}
	else if($month == "02") {
		$month1_data = get_data($borrowed, "01", $year);
		$month2_data = get_data($borrowed, "02", $year);
		$month3_data = array("", "", "", "", "", "", "", "", "", "", "");
		$month1_name = "January";
		$month2_name = "February";
		$month3_name = "";
	}
	else if($month == "03") {
		$month1_data = get_data($borrowed, "01", $year);
		$month2_data = get_data($borrowed, "02", $year);
		$month3_data = get_data($borrowed, "03", $year);
		$month1_name = "January";
		$month2_name = "February";
		$month3_name = "March";
	}
	else {
		$month1_data = get_data($borrowed, "01", $year);
		$month2_data = get_data($borrowed, "02", $year);
		$month3_data = get_data($borrowed, "03", $year);
		$month1_name = "January";
		$month2_name = "February";
		$month3_name = "March";
	}
	function get_data($borrowed, $month, $year) {
		$borrowed_data = array();
		$total_qty = 0;
		$classification_list = array("000-099", "100-199", "200-299", "300-399", "400-499", "500-599", "600-699", "700-799", "800-899", "900-999");
		foreach($classification_list as $value) {
			$total = count($borrowed->displayRecord("SELECT tbl_borrowed.account_no, tbl_books.classification FROM tbl_borrowed JOIN tbl_copy ON tbl_borrowed.account_no=tbl_copy.account_no JOIN tbl_books ON tbl_copy.book_id=tbl_books.book_id WHERE tbl_books.classification='$value' AND EXTRACT(MONTH FROM tbl_borrowed.date_borrowed) = '$month' AND EXTRACT(YEAR FROM tbl_borrowed.date_borrowed) = '$year'"));
			$total_qty = $total_qty + $total;
			array_push($borrowed_data, $total);
		}
		array_push($borrowed_data, $total_qty);
		return $borrowed_data;
	}
	$sql = "SELECT EXTRACT(YEAR FROM tbl_borrowed.date_borrowed) as year FROM tbl_borrowed GROUP BY EXTRACT(YEAR FROM tbl_borrowed.date_borrowed) ORDER BY EXTRACT(YEAR FROM tbl_borrowed.date_borrowed) DESC";
	$year_list = $borrowed->displayRecord($sql);
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
			#title_name {
				font-size:16px; 
				font-weight: bold;
			}
			hr {
				border-top: 2px solid #D3D6DA;
			}
			.month_title>tbody>tr>td {
				padding:0px;
				line-height: 1.42857143;
				vertical-align: top;
				border-top: 1px solid #ddd;
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
						<div class="x_panel" id="editPanel">
					  <div class="x_title">
						<img src="images/report.png" width="50px" height="50px">
						<h3 style="margin-left:60px; margin-top:-38px">Borrowed by subject</h3>
						  <div class="btn-group pull-right" style="margin-top:-40px">
						  <button type="button" class="btn btn-primary" onclick="printTable()">
							 <i class="fa fa-print"></i> Print</button>
						  <a type="button" download="borrowedbysubject.xls" onclick="return ExcellentExport.excel(this, 'excel_table', 'Borrowedby_subject');" class="btn btn-primary"><i class="fa fa-file-excel-o"></i> Excel</a>
						</div> 
						<div class="clearfix"></div>
					  </div>
					  <div class="x_content">
						  <div class="row">
							 <div class="col-md-3 col-sm-3 col-xs-3">
								<label class="col-md-12 col-sm-12 col-xs-12 form-group">First month: </label>
								 <div class="col-md-12 col-sm-12 col-xs-12 form-group">
									 <select class="form-control select2 has-feedback-left"  name="month_1" id="month_1" onchange="selectMonth('month_1')" style="background-color:#e2e2e2; width:100%">
										<option value="01">January</option>
										<option value="02">February</option>
										<option value="03">March</option>
										<option value="04">April</option>
										<option value="05">May</option>
										<option value="06">June</option>
										<option value="07">July</option>
										<option value="08">August</option>
										<option value="09">September</option>
										<option value="10">October</option>
										 <option value="11">November</option>
										 <option value="12">December</option>
									</select>
									<span class="fa fa-calendar blue form-control-feedback left" aria-hidden="true" style="color:black"></span>
								 </div>
							 </div>
							  <div class="col-md-3 col-sm-3 col-xs-3">
								<label class="col-md-12 col-sm-12 col-xs-12 form-group">Second month: </label>
								 <div class="col-md-12 col-sm-12 col-xs-12 form-group">
									 <select class="form-control select2 has-feedback-left"  name="month_2" id="month_2" onchange="selectMonth('month_2')" style="background-color:#e2e2e2; width:100%">
										<option value="01">January</option>
										<option value="02" selected>February</option>
										<option value="03">March</option>
										<option value="04">April</option>
										<option value="05">May</option>
										<option value="06">June</option>
										<option value="07">July</option>
										<option value="08">August</option>
										<option value="09">September</option>
										<option value="10">October</option>
										 <option value="11">November</option>
										 <option value="12">December</option>
									</select>
									<span class="fa fa-calendar blue form-control-feedback left" aria-hidden="true" style="color:black"></span>
								 </div>
							 </div>
							  <div class="col-md-3 col-sm-3 col-xs-3">
								<label class="col-md-12 col-sm-12 col-xs-12 form-group">Third month: </label>
								 <div class="col-md-12 col-sm-12 col-xs-12 form-group">
									 <select class="form-control select2 has-feedback-left"  name="month_3" id="month_3" onchange="selectMonth('month_3')" style="background-color:#e2e2e2; width:100%">
										<option value="01">January</option>
										<option value="02">February</option>
										<option value="03" selected>March</option>
										<option value="04">April</option>
										<option value="05">May</option>
										<option value="06">June</option>
										<option value="07">July</option>
										<option value="08">August</option>
										<option value="09">September</option>
										<option value="10">October</option>
										 <option value="11">November</option>
										 <option value="12">December</option>
									</select>
									<span class="fa fa-calendar blue form-control-feedback left" aria-hidden="true" style="color:black"></span>
								 </div>
							 </div>
							  <div class="col-md-3 col-sm-3 col-xs-3">
								<label class="col-md-12 col-sm-12 col-xs-12 form-group">Year: </label>
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
							  <div class="col-md-12 col-sm-12 col-xs-12">
								  <br>
								  <hr>
								<center><h3 id="title_name">TOTAL NUMBER OF BOOKS BORROWED BY SUBJECT</h3></center>
							  	<br><br>
							  </div>
								  <div class="col-md-4 col-sm-4 col-xs-4">
									  <div id="ht1">
									  <table class="table month_title" style="margin-bottom:2px;" >
										  <tbody>
											  <tr>
												  <td style=" font-style: italic; font-weight:bold; border-top: 1px solid white" width="80px"><b>Month of</b></td>
												  <td style="border-bottom: 1px solid black; border-top: 1px solid white " class="month1_name"><?php echo $month1_name; ?></td>
											  </tr>
										  </tbody>
									  </table>
									</div>
									  <table class="table table-bordered table-striped jambo_table">
										<thead>
											<th class="text-center">Classification Number</th>
											 <th class="text-center">No. of Books</th>
										  </thead>
										  <tbody id="data1">
											  <tr>
												  <td class="text-center">000-099</td>
												  <td class="text-center zero_1" ><?php echo $month1_data[0]; ?></td>
											  </tr>
											  <tr>
												  <td class="text-center">100-199</td>
												  <td class="text-center one_1" ><?php echo $month1_data[1]; ?></td>
											  </tr>
											  <tr>
												  <td class="text-center">200-299</td>
												  <td class="text-center two_1" ><?php echo $month1_data[2]; ?></td>
											  </tr>
											  <tr>
												  <td class="text-center">300-399</td>
												  <td class="text-center three_1" ><?php echo $month1_data[3]; ?></td>
											  </tr>
											  <tr>
												  <td class="text-center">400-499</td>
												  <td class="text-center four_1"><?php echo $month1_data[4]; ?></td>
											  </tr>
											  <tr>
												  <td class="text-center">500-599</td>
												 <td class="text-center five_1" ><?php echo $month1_data[5]; ?></td>
											  </tr>
											  <tr>
												  <td class="text-center">600-699</td>
												  <td class="text-center six_1" ><?php echo $month1_data[6]; ?></td>
											  </tr>
											  <tr>
												  <td class="text-center">700-799</td>
												  <td class="text-center seven_1" id=""><?php echo $month1_data[7]; ?></td>
											  </tr>
											  <tr>
												  <td class="text-center">800-899</td>
												  <td class="text-center eight_1" id=""><?php echo $month1_data[8]; ?></td>
											  </tr>
											  <tr>
												  <td class="text-center">900-999</td>
												  <td class="text-center nine_1" id=""><?php echo $month1_data[9]; ?></td>
											  </tr>
											  <tr>
												  <td style="background-color:#333; color:white" class="text-center">TOTAL</td>
												  <td class="text-center total_1" style="font-weight:bold" id=""><?php echo $month1_data[10]; ?></td>
											  </tr>
										  </tbody>
									  </table>
								  </div>

								<div class="col-md-4 col-sm-4 col-xs-4">
									<div id="ht2">
										<table class="table month_title" style="margin-bottom:2px;" >
										  <tbody>
											  <tr>
												  <td style=" font-style: italic; font-weight:bold; border-top: 1px solid white" width="80px"><b>Month of</b></td>
												  <td style="border-bottom: 1px solid black; border-top: 1px solid white" class="month2_name" id=""><?php echo $month2_name; ?></td>
											  </tr>
										  </tbody>
									  </table>
									</div>
									  <table class="table table-bordered table-striped jambo_table">
										<thead>
											<th class="text-center">Classification Number</th>
											 <th class="text-center">No. of Books</th>
										  </thead>
										  <tbody id="data2">
											  <tr>
												  <td class="text-center">000-099</td>
												  <td class="text-center zero_2" id=""><?php echo $month2_data[0]; ?></td>
											  </tr>
											  <tr>
												  <td class="text-center">100-199</td>
												  <td class="text-center one_2" id=""><?php echo $month2_data[1]; ?></td>
											  </tr>
											  <tr>
												  <td class="text-center">200-299</td>
												  <td class="text-center two_2" id=""><?php echo $month2_data[2]; ?></td>
											  </tr>
											  <tr>
												  <td class="text-center">300-399</td>
												  <td class="text-center three_2" id=""><?php echo $month2_data[3]; ?></td>
											  </tr>
											  <tr>
												  <td class="text-center">400-499</td>
												  <td class="text-center four_2" id=""><?php echo $month2_data[4]; ?></td>
											  </tr>
											  <tr>
												  <td class="text-center">500-599</td>
												 <td class="text-center five_2" id=""><?php echo $month2_data[5]; ?></td>
											  </tr>
											  <tr>
												  <td class="text-center">600-699</td>
												  <td class="text-center six_2" id=""><?php echo $month2_data[6]; ?></td>
											  </tr>
											  <tr>
												  <td class="text-center">700-799</td>
												  <td class="text-center seven_2" id=""><?php echo $month2_data[7]; ?></td>
											  </tr>
											  <tr>
												  <td class="text-center">800-899</td>
												  <td class="text-center eight_2" id=""><?php echo $month2_data[8]; ?></td>
											  </tr>
											  <tr>
												  <td class="text-center">900-999</td>
												  <td class="text-center nine_2" id=""><?php echo $month2_data[9]; ?></td>
											  </tr>
											  <tr>
												  <td style="background-color:#333; color:white" class="text-center bc">TOTAL</td>
												  <td class="text-center total_2" style="font-weight:bold" id=""><?php echo $month2_data[10]; ?></td>
											  </tr>
										  </tbody>
									  </table>
								  </div>

								  <div class="col-md-4 col-sm-4 col-xs-4">
									  <div id="ht3">
										  <table class="table month_title" style="margin-bottom:2px;" >
											  <tbody>
												  <tr>
													  <td style=" font-style: italic; font-weight:bold; border-top: 1px solid white" width="80px"><b>Month of</b></td>
													  <td style="border-bottom: 1px solid black; border-top: 1px solid white" id="" class="month3_name"><?php echo $month3_name; ?></td>
												  </tr>
											  </tbody>
										  </table>
									  </div>
									  <table class="table table-bordered table-striped jambo_table">
										<thead>
											<th class="text-center">Classification Number</th>
											 <th class="text-center">No. of Books</th>
										  </thead>
										  <tbody id="data3">
											  <tr>
												  <td class="text-center">000-099</td>
												  <td class="text-center zero_3" id=""><?php echo $month3_data[0]; ?></td>
											  </tr>
											  <tr>
												  <td class="text-center">100-199</td>
												  <td class="text-center one_3" id=""><?php echo $month3_data[1]; ?></td>
											  </tr>
											  <tr>
												  <td class="text-center">200-299</td>
												  <td class="text-center two_3" id=""><?php echo $month3_data[2]; ?></td>
											  </tr>
											  <tr>
												  <td class="text-center">300-399</td>
												  <td class="text-center three_3" id=""><?php echo $month3_data[3]; ?></td>
											  </tr>
											  <tr>
												  <td class="text-center">400-499</td>
												  <td class="text-center four_3" id=""><?php echo $month3_data[4]; ?></td>
											  </tr>
											  <tr>
												  <td class="text-center">500-599</td>
												 <td class="text-center five_3" id=""><?php echo $month3_data[5]; ?></td>
											  </tr>
											  <tr>
												  <td class="text-center">600-699</td>
												  <td class="text-center six_3" id=""><?php echo $month3_data[6]; ?></td>
											  </tr>
											  <tr>
												  <td class="text-center">700-799</td>
												  <td class="text-center seven_3" id=""><?php echo $month3_data[7]; ?></td>
											  </tr>
											  <tr>
												  <td class="text-center">800-899</td>
												  <td class="text-center eight_3" id=""><?php echo $month3_data[8]; ?></td>
											  </tr>
											  <tr>
												  <td class="text-center">900-999</td>
												  <td class="text-center nine_3" id=""><?php echo $month3_data[9]; ?></td>
											  </tr>
											  <tr>
												  <td style="background-color:#333; color:white" class="text-center">TOTAL</td>
												  <td class="text-center total_3" style="font-weight:bold" id=""><?php echo $month3_data[10]; ?></td>
											  </tr>
										  </tbody>
									  </table>
								  </div>
							  
							  	<table class="table table-bordered table-striped jambo_table hide" id="excel_table">
									<tr>
									  <td colspan="9"><p style="font-size:20px">TOTAL NUMBER OF BOOKS BORROWED BY SUBJECT</p></td>
								  </tr>
									<tr>
									  <td colspan="3" class="month1_name text-center"><?php echo $month1_name; ?></td>
									 <td colspan="3"  class="month2_name text-center"><?php echo $month2_name; ?></td>
									 <td colspan="3"  class="month3_name text-center"><?php echo $month3_name; ?></td>
								   </tr>
									<tr>
										<th class="text-center">Classification Number</th>
										 <th class="text-center">No. of Books</th>
										<th width="50px"></th>
										<th class="text-center">Classification Number</th>
										 <th class="text-center">No. of Books</th>
										<th width="50px"></th>
										<th class="text-center">Classification Number</th>
										 <th class="text-center">No. of Books</th>
									  </tr>
									  <tbody>
										  <tr>
											  <td class="text-center">000-099</td>
											  <td class="text-center zero_1" id=""><?php echo $month1_data[0]; ?></td>
											   <td></td>
											  <td class="text-center">000-099</td>
											  <td class="text-center zero_2" id=""><?php echo $month2_data[0]; ?></td>
											   <td></td>
											  <td class="text-center">000-099</td>
											  <td class="text-center zero_3" id=""><?php echo $month3_data[0]; ?></td>
										  </tr>
										  <tr>
											  <td class="text-center">100-199</td>
											  <td class="text-center one_1" id=""><?php echo $month1_data[1]; ?></td>
											   <td></td>
											  <td class="text-center">100-199</td>
											  <td class="text-center one_2" id=""><?php echo $month2_data[1]; ?></td>
											   <td></td>
											  <td class="text-center">100-199</td>
											  <td class="text-center one_3" id=""><?php echo $month3_data[1]; ?></td>
										  </tr>
										  <tr>
											  <td class="text-center">200-299</td>
											  <td class="text-center two_1" id=""><?php echo $month1_data[2]; ?></td>
											  <td></td>
											  <td class="text-center">200-299</td>
											  <td class="text-center two_2" id=""><?php echo $month2_data[2]; ?></td>
											  <td></td>
											  <td class="text-center">200-299</td>
											  <td class="text-center two_3" id=""><?php echo $month3_data[2]; ?></td>
										  </tr>
										  <tr>
											  <td class="text-center">300-399</td>
											  <td class="text-center three_1" id=""><?php echo $month1_data[3]; ?></td>
											  <td></td>
											  <td class="text-center">300-399</td>
											  <td class="text-center three_2" id=""><?php echo $month2_data[3]; ?></td>
											  <td></td>
											  <td class="text-center">300-399</td>
											  <td class="text-center three_3" id=""><?php echo $month3_data[3]; ?></td>
										  </tr>
										  <tr>
											  <td class="text-center">400-499</td>
											  <td class="text-center four_1" id=""><?php echo $month1_data[4]; ?></td>
											  <td></td>
											  <td class="text-center">400-499</td>
											  <td class="text-center four_2" id=""><?php echo $month2_data[4]; ?></td>
											  <td></td>
											  <td class="text-center">400-499</td>
											  <td class="text-center four_3" id=""><?php echo $month3_data[4]; ?></td>
										  </tr>
										  <tr>
											  <td class="text-center">500-599</td>
											 <td class="text-center five_1" id=""><?php echo $month1_data[5]; ?></td>
											  <td></td>
											  <td class="text-center">500-599</td>
											 <td class="text-center five_2" id=""><?php echo $month2_data[5]; ?></td>
											  <td></td>
											  <td class="text-center">500-599</td>
											 <td class="text-center five_3" id=""><?php echo $month3_data[5]; ?></td>
										  </tr>
										  <tr>
											  <td class="text-center">600-699</td>
											  <td class="text-center six_1" id=""><?php echo $month1_data[6]; ?></td>
											  <td></td>
											  <td class="text-center">600-699</td>
											  <td class="text-center six_2" id=""><?php echo $month2_data[6]; ?></td>
											  <td></td>
											  <td class="text-center">600-699</td>
											  <td class="text-center six_3" id=""><?php echo $month3_data[6]; ?></td>
										  </tr>
										  <tr>
											  <td class="text-center">700-799</td>
											  <td class="text-center seven_1" id=""><?php echo $month1_data[7]; ?></td>
											  <td></td>
											  <td class="text-center">700-799</td>
											  <td class="text-center seven_2" id=""><?php echo $month2_data[7]; ?></td>
											  <td></td>
											  <td class="text-center">700-799</td>
											  <td class="text-center seven_3" id=""><?php echo $month3_data[7]; ?></td>
										  </tr>
										  <tr>
											  <td class="text-center">800-899</td>
											  <td class="text-center eight_1" id=""><?php echo $month1_data[8]; ?></td>
											  <td></td>
											  <td class="text-center">800-899</td>
											  <td class="text-center eight_2" id=""><?php echo $month2_data[8]; ?></td>
											  <td></td>
											  <td class="text-center">800-899</td>
											  <td class="text-center eight_3" id=""><?php echo $month3_data[8]; ?></td>
										  </tr>
										  <tr>
											  <td class="text-center">900-999</td>
											  <td class="text-center nine_1" id=""><?php echo $month1_data[9]; ?></td>
											  <td></td>
											  <td class="text-center">900-999</td>
											  <td class="text-center nine_2" id=""><?php echo $month2_data[9]; ?></td>
											  <td></td>
											  <td class="text-center">900-999</td>
											  <td class="text-center nine_3" id=""><?php echo $month3_data[9]; ?></td>
										  </tr>
										  <tr>
											  <td style="background-color:#333; color:white" class="text-center">TOTAL</td>
											  <td class="text-center total_1" style="font-weight:bold" id=""><?php echo $month1_data[10]; ?></td>
											  <td></td>
											  <td style="background-color:#333; color:white" class="text-center">TOTAL</td>
											  <td class="text-center total_2" style="font-weight:bold" id=""><?php echo $month2_data[10]; ?></td>
											  <td></td>
											  <td style="background-color:#333; color:white" class="text-center">TOTAL</td>
											  <td class="text-center total_3" style="font-weight:bold" id=""><?php echo $month3_data[10]; ?></td>
										  </tr>
									  </tbody>
								  </table>
						 </div>
					</div>
				  </div>
				</div>
			
            </div>
            <?php include'include/footer.php';?>
        </div>
         <?php include'include/js.php';?>
		<script>
			function selectMonth(month) {
				var get_month = $("#"+month).val();
				var get_year = $("#year").val();
				$.ajax({
					url: "model/borrowed.php",
					dataType:'json',
					type: "POST",
					data:{ 
						action:"borrowerby_subject",
						month: get_month,
						year: get_year
					},
					success: function(data) {
						console.log(data);
						display_record(data, month);
					},
					error: function(){
						alert("error");
					}
				});
			}
			function display_record(data, month) {
				
				var get_month = data.month_name;
				var data = data.data;
				if(month == "month_1") {
					$(".zero_1").text(data[0]);
					$(".one_1").text(data[1]);
					$(".two_1").text(data[2]);
					$(".three_1").text(data[3]);
					$(".four_1").text(data[4]);
					$(".five_1").text(data[5]);
					$(".six_1").text(data[6]);
					$(".seven_1").text(data[7]);
					$(".eight_1").text(data[8]);
					$(".nine_1").text(data[9]);
					$(".total_1").text(data[10]);
					$(".month1_name").text(get_month);
				}
				if(month == "month_2") {
					$(".zero_2").text(data[0]);
					$(".one_2").text(data[1]);
					$(".two_2").text(data[2]);
					$(".three_2").text(data[3]);
					$(".four_2").text(data[4]);
					$(".five_2").text(data[5]);
					$(".six_2").text(data[6]);
					$(".seven_2").text(data[7]);
					$(".eight_2").text(data[8]);
					$(".nine_2").text(data[9]);
					$(".total_2").text(data[10]);
					$(".month2_name").text(get_month);
				}
				if(month == "month_3") {
					$(".zero_3").text(data[0]);
					$(".one_3").text(data[1]);
					$(".two_3").text(data[2]);
					$(".three_3").text(data[3]);
					$(".four_3").text(data[4]);
					$(".five_3").text(data[5]);
					$(".six_3").text(data[6]);
					$(".seven_3").text(data[7]);
					$(".eight_3").text(data[8]);
					$(".nine_3").text(data[9]);
					$(".total_3").text(data[10]);
					$(".month3_name").text(get_month);
				}
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
				
				var ht1 = document.getElementById("ht1");
				var table1 = document.getElementById("data1");
				var ht2 = document.getElementById("ht2");
				var table2 = document.getElementById("data2");
				var ht3 = document.getElementById("ht3");
				var table3 = document.getElementById("data3");
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
							.month_title>tbody>tr>td {\
								padding:0px;\
								line-height: 1.42857143;\
								vertical-align: top;\
								border: 1px solid white;\
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
								 <center><h3 id="title_name">TOTAL NUMBER OF BOOKS BORROWED BY SUBJECT</h3></center>\
						  		<br>\
								<div class="col-md-4 col-sm-4 col-xs-4">'
									  + ht1.innerHTML + 
									  '<table class="table">\
										<thead>\
											<th class="text-center" style="border: 1px solid black">Classification Number</th>\
											 <th class="text-center" style="border: 1px solid black">No. of Books</th>\
										  </thead>\
										  <tbody style="border: 1px solid black">'+table1.innerHTML+'</tbody>\
									  </table>\
								  </div>\
								<div class="col-md-4 col-sm-4 col-xs-4">'
									  + ht2.innerHTML + 
									  '<table class="table">\
										<thead>\
											<th class="text-center" style="border: 1px solid black">Classification Number</th>\
											 <th class="text-center" style="border: 1px solid black">No. of Books</th>\
										  </thead>\
										  <tbody style="border: 1px solid black">'+table2.innerHTML+'</tbody>\
									  </table>\
								  </div>\
								<div class="col-md-4 col-sm-4 col-xs-4">'
									  + ht3.innerHTML + 
									  '<table class="table">\
										<thead>\
											<th class="text-center" style="border: 1px solid black">Classification Number</th>\
											 <th class="text-center" style="border: 1px solid black">No. of Books</th>\
										  </thead>\
										  <tbody style="border: 1px solid black">'+table3.innerHTML+'</tbody>\
									  </table>\
								  </div>\
							<div>\
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
