<?php
    require_once "model/controller.php";
	require_once "model/model.php";
	date_default_timezone_set('Asia/Manila');

	$year =  date("Y");
	$book = new Model;
	if(isset($_POST['view_month'])) {
		$month = $_POST['month'];
		
		$sql = "SELECT tbl_borrowed.borrowed_id, tbl_borrowed.account_no, tbl_books.title, tbl_books.author, tbl_borrowers.firstname, tbl_borrowers.lastname, tbl_borrowed.date_borrowed, tbl_borrowed.remarks, tbl_borrowed.received_userid FROM tbl_borrowed JOIN tbl_copy ON tbl_borrowed.account_no=tbl_copy.account_no JOIN tbl_books ON tbl_copy.book_id=tbl_books.book_id JOIN tbl_borrowers ON tbl_borrowed.borrower_id=tbl_borrowers.borrower_id JOIN tbl_booklogs ON tbl_borrowed.booklogs_id=tbl_booklogs.booklogs_id WHERE EXTRACT(YEAR FROM tbl_borrowed.date_borrowed)='$year' AND EXTRACT(MONTH FROM tbl_borrowed.date_borrowed)='$month' ORDER BY tbl_booklogs.booklogs_id ASC";
		$data_books = $book->displayRecord($sql);
		
		$date = $year."-".$month;
		$date = date("F Y", strtotime($date));
		$_SESSION['current_page'] = "monthly_borrowed.php";
		$_SESSION['array'] = $data_books;
		$_SESSION['array2'] = $month;
	}
	else {
		if(isset($_SESSION['array'])) {
			$data_books = $_SESSION['array'];
			$month = $_SESSION['array2'];
			$date = $year."-".$month;
			$date = date("F Y", strtotime($date));
		}
		else {
			header("location: dashboard.php");
		}
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
		
    </head>
    <body class="nav-md" onclick="resetTimer()" onmousedown="resetTimer()" onmousemove="resetTimer()" onscroll="resetTimer()" onkeypress="resetTimer()">
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
						<img src="images/book_list.png" width="50px" height="50px">
						<h3 style="margin-left:60px; margin-top:-38px" id="title">Borrowed on <?php echo $date; ?></h3>
						  <div class=" btn-group pull-right" style="margin-left:60px; margin-top:-45px">
							   <button class="btn btn-primary btn-sm"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Search Date" onclick="search()"><i class="fa fa-search"></i> Search</button>
								<a href="borrowed_books.php" class="btn btn-primary btn-sm"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Back to List of books"><i class="fa fa-mail-reply"></i> Back</a>
						  </div>
					  </div>
					  <div class="x_content">
						  <div class="row">
							<table class="table table-bordered  jambo_table">
							  <thead>
								<tr>
								 	<th width="5%">No.</th>
								  <th width="10%">Accession No</th>
								  <th width="20%">Book Title</th>
								  <th width="15%">Author Name</th>
								  <th width="15%">Borrower's Name</th>
								  <th width="15%">Date Borrowed</th>
								  <th width="10%">Status</th>
								  <th width="10%">Option</th>
								</tr>
							  </thead>
							  <tbody id="data">
								<tr >
								<?php
									$count = 0;
									foreach($data_books as $value) {
										$count++;
										$action = "";
										if($value['remarks'] == "Borrowed") {
											$status = '<span class="label label-success">Borrowed</span>';
										}
										else {
											$status = '<span class="label label-warning">Returned</span>';
										}
										if($value['received_userid'] == null) {
											$action = "borrowed";
										}
										else {
											$action = "returned";
										}
								?>		<tr>
								  			<td ><?php echo $count; ?></td>
											<td ><?php echo $value['account_no']; ?></td>
											<td ><?php echo $value['title']; ?></td>
											<td ><?php echo $value['firstname']." ".$value['lastname']; ?></td>
											<td ><?php echo $value['author']; ?></td>
											<td ><?php echo date('M d, Y',strtotime($value['date_borrowed'])); ?></td>
								  			<td ><?php echo $status; ?></td>
											<td>
												<button type="button" class="btn btn-primary btn-xs" onclick="viewlogs('<?php echo $value['borrowed_id']; ?>', '<?php echo $action; ?>')"><i class="fa fa-eye"></i> View Logs</button>
											</td>
								  		</tr>
										
								<?php	}
									if($count == 0) {
								?>
								  	<tr class="danger"><td colspan="8"><h3 class="text-center">No records available.</h3></td></tr>;
								<?php	}
								?>
							  </tbody>
							</table>
							  <div class="modal fade" id="modal" role="dialog">
								<div class="modal-dialog modal-md">
								  <div class="modal-content">
									<div class="modal-header">
									  <button type="button" class="close" data-dismiss="modal">&times;</button>
									  <h4 class="modal-title"><span class="badge bg-green"><i class="fa fa-history"></i></span> Logs Details</h4>
									</div>
									<div class="modal-body">
										<ul class="list-group" >
										<li class="list-group-item list-group-item-success"><b>Borrower's Name: </b><b style="margin-left:22px" id="borrower"></b></li>
										 <li class="list-group-item "><b>Accession No:</b><b style="margin-left:46px" id="accessionno"></b></li>
										  <li class="list-group-item "><b>Book Title:</b><b style="margin-left:69px" id="titlebook"></b></li>
										<li class="list-group-item"><b>Issued By: </b><b style="margin-left:65px" id="issuedby"></b></li> 
										  <li class="list-group-item"><b>Date Borrowed: </b><b style="margin-left:34px" id="date_borrowed"></b></li>
										</ul>
									</div>
									<div class="modal-footer">
									  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
									</div>

									</div>
								  </div>
								</div>
							  <div class="modal fade" id="modal3" role="dialog">
								<div class="modal-dialog modal-md">
								  <div class="modal-content">
									<div class="modal-header">
									  <button type="button" class="close" data-dismiss="modal">&times;</button>
									  <h4 class="modal-title"><span class="badge bg-green"><i class="fa fa-history"></i></span> Logs Details</h4>
									</div>
									<div class="modal-body">
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
									<div class="modal-footer">
									  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
									</div>

									</div>
								  </div>
								</div>
							  <div class="modal fade" id="modal2" role="dialog">
								<div class="modal-dialog">
								  <div class="modal-content">
									<div class="modal-header">
									  <button type="button" class="close" data-dismiss="modal">&times;</button>
									  <h4 class="modal-title"><span class="badge bg-green"><i class="fa fa-calendar"></i></span> Search Month</h4>
									</div>
									<div class="modal-body">
										<div class="row">
											<center>
												<img src="images/view.png" width="100px" height="100px">
											</center>
											<br>

											<div class="col-md-12 col-sm-12 col-xs-12 form-group">
												<select class="form-control" id="month">
													<option selected disabled>Select Month</option>
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
											</div>
										<br>
										</div>
									</div>
									<div class="modal-footer">
									  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
										<button type="button" class="btn btn-primary" onclick="searchdata();"><i class="fa fa-search"></i> Submit</button>
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
			function viewlogs(borrowed_id, remarks) {
				if(remarks == "borrowed") {
					$("#modal").modal("show");
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
							$("#titlebook").text(data.data[0].title);
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
					$("#modal3").modal("show");
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
			function search() {
				$("#modal2").modal("show");
			}
			function searchdata() {
				$("#modal2").modal("hide");
				var month = $("#month").val();
				var year = <?php echo $year; ?>;
				$.ajax({
					url: "model/borrowed.php",
					dataType:'json',
					type: "POST",
					data:{ 
						action:"search logs",
						month: month,
						year: year
					},
					success: function(data) {
						console.log(data);
						$("#title").html("Borrowed on "+data.date);
						records2(data) 
					},
					error: function(){
						alert("error");
					}
				});
			}
			function records2(data) {
				
				var list = data.data;
				var length = list.length;
				var count = 0;
				var html = "";
				var html2 = "";
				
				for(var x = 0; x < length; x++) {
					
					count++;
					var status = "";
					var action = "";
					var borrowed_id = list[x].borrowed_id;
					
					if(list[x].remarks == "Borrowed") {
						status = '<span class="label label-success">Borrowed</span>';
					}
					else {
						status = '<span class="label label-warning">Returned</span>';
					}
					if(list[x].received_userid == null) {
						action = "borrowed";
					}
					else {
						action = "returned";
					}
					html = html + 
					'<tr>' +
						'<td>' + count + '</td>' +
						'<td>' + list[x].account_no + '</td>' +
						'<td>' + list[x].title + '</td>' +
						'<td>' + list[x].firstname + ' ' + list[x].lastname + '</td>' +
						'<td>' + list[x].author + '</td>' +
						'<td>' + list[x].date_borrowed + '</td>' +
						'<td>' + status + '</td>' +
						'<td>' +
							"<button type='button' class='btn btn-primary btn-xs' onclick=viewlogs('"+borrowed_id+"','"+action+"')><i class='fa fa-eye'></i> View Logs</button>" +
						'</td>' +
					'</tr>';
		
				}
				
				if(length > 0) {
					$("#data").html(html);
				}
				else {
					$("#data").html('<tr class="danger"><td colspan="8"><h3 class="text-center">No records available.</h3></td></tr>');
				}
			}
		</script>
    </body>
</html>
