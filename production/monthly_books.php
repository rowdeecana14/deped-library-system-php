<?php
    require_once "model/controller.php";
	require_once "model/model.php";
	date_default_timezone_set('Asia/Manila');

	$year =  date("Y");
	$book = new Model;
	if(isset($_POST['view_month'])) {
		$month = $_POST['month'];
		$sql = "SELECT tbl_copy.account_no, tbl_books.book_id, tbl_books.isbn, tbl_books.title, tbl_books.author, tbl_books.pages, tbl_books.fund, tbl_books.copyright, tbl_books.publisher FROM tbl_booklogs JOIN tbl_books ON tbl_booklogs.book_id=tbl_books.book_id JOIN tbl_copy ON tbl_booklogs.booklogs_id=tbl_copy.booklogs WHERE EXTRACT(YEAR FROM tbl_booklogs.date)='$year' AND EXTRACT(MONTH FROM tbl_booklogs.date)='$month' AND tbl_booklogs.status='Received' ORDER BY tbl_copy.book_id, tbl_copy.copy";
		$data_books = $book->displayRecord($sql);
		$date = $year."-".$month;
		$date = date("F Y", strtotime($date));
		$_SESSION['current_page'] = "monthly_books.php";
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
    <body class="nav-md">
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
						<h3 style="margin-left:60px; margin-top:-38px" id="monthname">Received on <?php echo $date; ?></h3>
						  <div class=" btn-group pull-right" style="margin-left:60px; margin-top:-45px">
							   <button class="btn btn-primary btn-sm"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Search Date" onclick="search()"><i class="fa fa-search"></i> Search</button>
								<a href="listof_books.php" class="btn btn-primary btn-sm"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Back to List of books"><i class="fa fa-mail-reply"></i> Back</a>
						  </div>
					  </div>
					  <div class="x_content">
						  <div class="row">
							  <br>
							<table class="table table-bordered  jambo_table">
							  <thead>
								<tr>
									<th width="15%">Accession no</th>
								  	<th width="20%">Book Title</th>
									  <th width="15%">Author Name</th>
									  <th width="5%">No of pages</th>
									  <th width="5%">Source of fund</th>
									  <th width="10%">Copyright</th>
									  <th width="15%">Publisher</th>
									  <th width="15%">ISBN</th>
								</tr>
							  </thead>
							  <tbody id="data">
								<tr >
								<?php
									$count = 0;
									foreach($data_books as $value) {
										$count++;
								?>		<tr>
								  			<td ><?php echo $value['account_no']; ?></td>
											<td ><?php echo $value['title']; ?></td>
											<td ><?php echo $value['author']; ?></td>
											<td ><?php echo $value['pages']; ?></td>
											<td ><?php echo $value['fund']; ?></td>
											<td ><?php echo $value['copyright']; ?></td> 
							  				<td ><?php echo $value['publisher']; ?></td> 
											<td ><?php echo $value['isbn']; ?></td> 
								  		</tr>
										
								<?php	}
									if($count == 0) {
								?>
								  	<tr class="danger"><td colspan="8"><h3 class="text-center">No records available.</h3></td></tr>;
								<?php	}
								?>
							  </tbody>
							</table>
							 <form >
								  <div class="modal fade" id="modal" role="dialog">
									<div class="modal-dialog modal-lg">
									  <div class="modal-content">
										<div class="modal-header">
										  <button type="button" class="close" data-dismiss="modal">&times;</button>
										  <h4 class="modal-title"><span class="badge bg-green"><i class="fa fa-history"></i></span> Book Logs</h4>
										</div>
										<div class="modal-body">
											<div class="row">
												 <table class="table table-bordered table-striped jambo_table">
													  <thead>
														<tr>
														  <th>No.</th>
														  <th>Book Title</th>
															<th class="text-center">Quantity</th>
														  <th>Date</th>
														  <th>Received by</th>
														</tr>
													  </thead>
													  <tbody id="data2">

													  </tbody>
												</table>
											</div>
										</div>
										<div class="modal-footer">
										  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
										</div>

										</div>
									  </div>
									</div>
							  </form>
							  <div class="modal fade" id="modal2" role="dialog">
								<div class="modal-dialog">
								  <div class="modal-content">
									<div class="modal-header">
									  <button type="button" class="close" data-dismiss="modal">&times;</button>
									  <h4 class="modal-title" style="font-weight: bold;"><span class="badge bg-green"><i class="fa fa-calendar"></i></span> Search Month</h4>
									</div>
									<div class="modal-body">
										<div class="row">
											<div class="col-md-12 col-sm-12 col-xs-12">
												<div class="col-md-12 col-sm-12 col-xs-12">
													<center>
														<img src="images/day.png" width="100px" height="100px">
													</center>
													<br>

													<div class=" form-group">
														<select class="form-control" id="month" style="background-color:#e2e2e2;">
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
												</div>
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
		
			function search() {
				$("#modal2").modal("show");
			}
			function searchdata() {
				$("#modal2").modal("hide");
				var month = $("#month").val();
				var year = <?php echo $year; ?>;
				$.ajax({
					url: "model/books.php",
					dataType:'json',
					type: "POST",
					data:{ 
						action:"search logs",
						month: month,
						year: year
					},
					success: function(data) {
						console.log(data);
						$("#monthname").html("Received on " + data.date);
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
					var bookid = list[x].book_id;
					html = html + 
					'<tr>' +
						'<td>' + list[x].account_no + '</td>' +
						'<td>' + list[x].title + '</td>' +
						'<td>' + list[x].author + '</td>' +
						'<td>' + list[x].pages + '</td>' +
						'<td>' + list[x].fund + '</td>' +
						'<td>' + list[x].copyright + '</td>' +
						'<td>' + list[x].publisher + '</td>' +
						'<td>' + list[x].isbn + '</td>' +
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
