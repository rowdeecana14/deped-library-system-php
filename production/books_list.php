<?php
    
	require_once "model/controller.php";
	require_once "model/model.php";
	date_default_timezone_set('Asia/Manila');
	$book = new Model;
	$database = new Database;
	
	if(isset($_GET['book_id']) && !empty($_GET['book_id']) && isset($_GET['book_token']) && isset($_SESSION['book_token'])) {
		
		if($_SESSION['book_token'] == $_GET['book_token']) {
			$book_id = $_GET['book_id'];
			$pass_token = $_GET['book_token'];
			$sql = "SELECT * FROM tbl_books WHERE book_id='$book_id'";
			
			$data3 = $book->displayRecord($sql);
			
			$sql = "SELECT tbl_booklogs.booklogs_id, tbl_books.isbn, tbl_books.title, tbl_books.author, tbl_booklogs.quantity, tbl_booklogs.status, tbl_booklogs.date FROM tbl_booklogs JOIN tbl_books ON tbl_booklogs.book_id=tbl_books.book_id WHERE tbl_booklogs.book_id='$book_id' ORDER BY tbl_booklogs.date ASC";
			$data2= $book->displayRecord($sql);
			
			$damaged = $database->totalRow("SELECT COUNT(tbl_copy.account_no) as quantity FROM tbl_books JOIN tbl_copy ON tbl_books.book_id=tbl_copy.book_id JOIN tbl_borrowed ON tbl_copy.account_no=tbl_borrowed.account_no WHERE tbl_copy.status='Damaged' AND tbl_borrowed.status='Damaged' AND tbl_books.book_id='$book_id'");
			
			$lost = $database->totalRow("SELECT COUNT(tbl_copy.account_no) as quantity FROM tbl_books JOIN tbl_copy ON tbl_books.book_id=tbl_copy.book_id JOIN tbl_borrowed ON tbl_copy.account_no=tbl_borrowed.account_no WHERE tbl_copy.status='Lost' AND tbl_borrowed.status='Lost' AND tbl_books.book_id='$book_id'");
			
			$borrowed = count($book->displayRecord("SELECT * FROM tbl_borrowed JOIN tbl_copy ON tbl_borrowed.account_no=tbl_copy.account_no JOIN tbl_books ON tbl_copy.book_id=tbl_books.book_id JOIN tbl_borrowers ON tbl_borrowed.borrower_id=tbl_borrowers.borrower_id WHERE tbl_copy.remarks='Borrowed' AND tbl_books.book_id='$book_id' GROUP BY tbl_copy.account_no"));
			
			$sql = "SELECT tbl_books.book_id, tbl_books.title, tbl_books.author, tbl_books.pages, tbl_books.fund, tbl_books.copyright, tbl_books.isbn, tbl_books.publisher, tbl_copy.account_no, tbl_copy.remarks, tbl_borrowed.remarks as br_status, tbl_copy.status, tbl_books.qty_in, tbl_books.qty_out FROM tbl_books JOIN tbl_copy ON tbl_books.book_id=tbl_copy.book_id LEFT JOIN tbl_borrowed ON tbl_copy.account_no=tbl_borrowed.account_no WHERE tbl_books.book_id='$book_id' GROUP BY tbl_copy.account_no ORDER BY tbl_copy.book_id, tbl_copy.copy";
			$data4 = $book->displayRecord($sql);
			
			$sql = "SELECT * FROM tbl_copy WHERE tbl_copy.book_id='$book_id' ORDER BY tbl_copy.book_id, tbl_copy.copy";
			$accession_list = $book->displayRecord($sql);
			$sql = "SELECT * FROM tbl_copy ORDER BY tbl_copy.book_id, tbl_copy.copy LIMIT 1";
			$data_accession = $book->displayRecord($sql);
			$get_accession = $data_accession[0]['account_no'];
			$sql = "SELECT tbl_borrowed.account_no, tbl_borrowed.date_borrowed, tbl_borrowers.firstname, tbl_borrowers.lastname, tbl_borrowed.date_returned FROM tbl_borrowed JOIN tbl_copy ON tbl_borrowed.account_no=tbl_copy.account_no JOIN tbl_borrowers oN tbl_borrowed.borrower_id=tbl_borrowers.borrower_id WHERE tbl_borrowed.account_no='$get_accession' ORDER BY tbl_borrowed.date_borrowed DESC";
			$display_accession = $book->displayRecord($sql);
			
			$getarray = array();
			array_push($getarray, $damaged);
			array_push($getarray, $lost);
			array_push($getarray, $borrowed);
			$_SESSION['current_page'] = "books_list.php";
			$_SESSION['book_id'] = $_GET['book_id'];
			$_SESSION['pass_token'] = $_GET['book_token'];
			$_SESSION['array'] = $data2;
			$_SESSION['array2'] = $data3;
			$_SESSION['array3'] = $getarray;
			$_SESSION['array4'] = $data4;
		}
		else {
			header("location: dashboard.php");
		}
	}
	else {
		if(isset($_SESSION['array'])) {
			
			$data2 = $_SESSION['array'];
			$data3 = $_SESSION['array2'];
			$getarray = $_SESSION['array3'];
			$data4 = $_SESSION['array4'];
			$pass_token = $_SESSION['pass_token'];
			$book_id = $_SESSION['book_id'];
			$sql = "SELECT tbl_booklogs.booklogs_id, tbl_books.isbn, tbl_books.title, tbl_books.author, tbl_booklogs.quantity, tbl_booklogs.status, tbl_booklogs.date FROM tbl_booklogs JOIN tbl_books ON tbl_booklogs.book_id=tbl_books.book_id WHERE tbl_booklogs.book_id='$book_id' ORDER BY tbl_booklogs.date ASC";
			$data2= $book->displayRecord($sql);
			$sql = "SELECT * FROM tbl_copy WHERE tbl_copy.book_id='$book_id' ORDER BY tbl_copy.book_id, tbl_copy.copy";
			$accession_list = $book->displayRecord($sql);
			$sql = "SELECT * FROM tbl_copy ORDER BY tbl_copy.book_id, tbl_copy.copy LIMIT 1";
			$data_accession = $book->displayRecord($sql);
			$get_accession = $data_accession[0]['account_no'];
			$sql = "SELECT tbl_borrowed.account_no, tbl_borrowed.date_borrowed, tbl_borrowers.firstname, tbl_borrowers.lastname, tbl_borrowed.date_returned FROM tbl_borrowed JOIN tbl_copy ON tbl_borrowed.account_no=tbl_copy.account_no JOIN tbl_borrowers oN tbl_borrowed.borrower_id=tbl_borrowers.borrower_id WHERE tbl_borrowed.account_no='$get_accession' ORDER BY tbl_borrowed.date_borrowed DESC";
			$display_accession = $book->displayRecord($sql);
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
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>E-Library</title>
		<?php require_once('include/css.php');  ?>
		<link href="include/mycss.css" rel="stylesheet">
  		
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
				<div class="right_col" role="main" style="background-image: url(images_uploaded/<?php //echo $data[0]['bg_image'];?>); background-repeat: no-repeat; background-size: cover;">
				<div class="" role="main" style="">
					<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12" role="tabpanel" data-example-id="togglable-tabs"  >
						<ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
						  <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true"><span class="badge bg-blue "><i class="fa fa-user"></i></span> Book details</a>
						  </li>
							<li role="presentation"><a href="#tab_content4" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true"><span class="badge bg-blue "><i class="fa fa-book"></i></span> Book card</a>
						  </li>
						  <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false"><span class="badge bg-blue "><i class="fa fa-table"></i></span> List of books</a>
							</li>
							
						</ul>
						<div id="myTabContent" class="tab-content">
						  <div role="tabpanel" class="tab-pane fade active in"  id="tab_content1" aria-labelledby="home-tab">
							  	<div class="x_panel" id="recordPanel" style=" ">
								  <div class="x_title">
									 <img src="images/book_details.png" width="50px" height="50px">
									<h3 style="margin-left:60px; margin-top:-38px">Book details</h3>
									<div class="clearfix"></div>
									  <div class=" btn-group pull-right" style="margin-left:60px; margin-top:-45px">
										   <a href="books_edit.php? book_id=<?php echo $book_id;?> & book_token=<?php echo $pass_token;?>" class="btn btn-primary btn-sm"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit Book Details"><i class="fa fa-edit"></i> Edit</a>
											<a href="listof_books.php" class="btn btn-primary btn-sm"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Back to list of books"><i class="fa fa fa-mail-reply"></i> Back</a>
									  </div>
								  </div>
								  <div class="x_content">
									  <div class="row">
										 <div class="col-md-5 col-sm-5 col-xs-5">
											<table class="table" style="font-size:14px">
											 <tr>
												<th>Book Title:</th>
												<td><?php echo $data3[0]['title']; ?></td>
											  </tr>
											  <tr>
												<th>Author Name:</th>
												<td><?php echo $data3[0]['author']; ?></td>
											  </tr>
											  <tr>
												<th>No of pages:</th>
												<td><?php echo $data3[0]['pages']; ?></td>
											  </tr>
											  <tr>
												<th>Source of fund:</th>
												<td><?php echo $data3[0]['fund']; ?></td>
											  </tr>
												<tr>
												<th>Classification No:</th>
												<td><?php echo $data3[0]['classification']; ?></td>
											  </tr>
											<tr>
												<th>Copyright:</th>
												<td><?php echo $data3[0]['copyright']; ?></td>
											  </tr>
												<tr>
												<th>Publisher:</th>
												<td><?php echo $data3[0]['publisher']; ?></td>
											  </tr>
												<tr>
												<th>ISBN:</th>
												<td><?php echo $data3[0]['isbn']; ?></td>
											  </tr>
												<tr>
												<th>Status:</th>
												<td>
													<?php
														if(($data3[0]['qty_in'] - $data3[0]['qty_out']) > 0) {
															echo "Avalaible";
														}
														else {
															echo "Unavalaible";
														}
													?>
												</td>
											  </tr>
											
											</table>
										</div>
										  <div class="col-md-5 col-sm-5 col-xs-5">
											<table class="table" style="font-size:14px">
											 
											<tr>
												<th>Total:</th>
												<td><?php echo ($data3[0]['qty_in']); ?></td>
											  </tr>
												<tr>
												<th>Remaining:</th>
												<td><?php echo $data3[0]['qty_in']- $data3[0]['qty_out']; ?></td>
											  </tr>
												<tr>
												<th>Borrowed:</th>
												<td><?php echo $getarray[2]; ?></td>
											  </tr>
												
											 <tr>
												<th>Lost:</th>
												<td><?php echo $getarray[0]; ?></td>
											  </tr>
											 <tr>
												<th>Damaged:</th>
												<td><?php echo $getarray[1]; ?></td>
											  </tr>
											</table>
										</div>
									 </div>
								</div>
							  </div>
						  </div>
							<div role="tabpanel" class="tab-pane fade active"  id="tab_content4" aria-labelledby="home-tab">
							  	<div class="x_panel" id="recordPanel" style=" ">
								  <div class="x_title">
									 <img src="images/book_details.png" width="50px" height="50px">
									<h3 style="margin-left:60px; margin-top:-38px">Book card</h3>
									<div class="clearfix"></div>
									  <div class=" btn-group pull-right" style="margin-left:60px; margin-top:-45px">
										   <a href="books_edit.php? book_id=<?php echo $book_id;?> & book_token=<?php echo $pass_token;?>" class="btn btn-primary btn-sm"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit Book Details"><i class="fa fa-edit"></i> Edit</a>
											<a href="listof_books.php" class="btn btn-primary btn-sm"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Back to list of books"><i class="fa fa fa-mail-reply"></i> Back</a>
									  </div>
								  </div>
								  <div class="x_content">
									  <div class="row">
										  <div class="col-md-10 col-sm-10 col-xs-10">
											<table class="table" style="font-size:14px">
											<tr>
												<th>Author Name:</th>
												<td><?php echo $data3[0]['author']; ?></td>
											  </tr>
											 <tr>
												<th>Book Title:</th>
												<td><?php echo $data3[0]['title']; ?></td>
											  </tr>
											  
											  <tr>
												<th>Accession no:</th>
												<td><select class="form-control select2" name="accession" id="accession" style="background-color:#e2e2e2; width:100%" onchange="selectAcession()">
												<?php
													foreach($accession_list as $value) {
														echo "<option >".$value['account_no']."</option>";
														
													}
												?>
											  </select></td>
											  </tr>
											</table>
										   <table class="table table-bordered  jambo_table">
											  <thead>
												<tr>
												  <th>No</th>
												  <th>Date Borrowed</th>
												  <th>Borrower's Name</th>
												  <th>Date Returned</th>
												</tr>
											  </thead>
											  <tbody class="searchable" id="accession_data">

												  <?php
												  	$record = "";
												  	$count = 0;
												  	if(count($display_accession) > 0) {
														foreach($display_accession as $value) {
															$count++;
															$date_returned = "";
															if($value['date_returned'] != null) {
																$date_returned= date('M d, Y',strtotime($value['date_returned']));
															}
															else {
																$date_returned = "";
															}
															$record = $record.'<tr>
															<td>'.$count.'</td>
															<td>'.date("F m Y", strtotime($value['date_borrowed'])).'</td>
															<td>'.$value['firstname']." ".$value['lastname'].'</td>
															<td>'.$date_returned.'</td>
															</tr>';
														}
													}
												  	if($count == 0) {
														$record = $record.'<tr class="danger"><td colspan="4"><h3 class="text-center">No records available.</h3></td></tr>';
													}
												  	echo $record;
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
									  <img src="images/book_inventory.jpg" width="50px" height="50px">
									<h3 style="margin-left:60px; margin-top:-37px">List of books</h3>
									<div class="clearfix"></div>
									  <div class=" btn-group pull-right" style="margin-left:60px; margin-top:-45px">
										   <a href="books_edit.php? book_id=<?php echo $book_id;?> & book_token=<?php echo $pass_token;?>" class="btn btn-primary btn-sm"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit Book Details"><i class="fa fa-edit"></i> Edit</a>
											<a href="listof_books.php" class="btn btn-primary btn-sm"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Back to list of books"><i class="fa fa fa-mail-reply"></i> Back</a>
									  </div>
								  </div>
								  <div class="x_content">
									  <div class="row">
										  <table class="table table-bordered  jambo_table">
											  <thead>
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
											  </thead>
											  <tbody class="searchable" id="data">

												  <?php

													$record = "";
													$count = 0;	
													foreach($data4 as $value) {
														$count++;
														$book_id = $value['book_id'];
														$status = "";
														$remaining_qty = $value['qty_in'] - $value['qty_out'];

														if($value['remarks'] == "Borrowed") {
															$status = '<span class="label label-success">Borrowed</span>';
														}
														else {
															if($value['status'] == "Okay") {
																$status = '<span class="label label-primary">Available</span>';
															}
															else if($value['status'] == "Damaged") {
																$status = '<span class="label label-danger">Damaged</span>';
															}
															else {
																$status = '<span class="label label-warning">Lost</span>';
															}
														}
														$record = $record.
														'<tr>
															<td>'.$value['account_no'].'</td>
															<td>'.$value['title'].'</td>
															<td>'.$value['author'].'</td>
															<td>'.$value['pages'].'</td>
															<td>'.$value['fund'].'</td>
															<td>'.$value['copyright'].'</td>
															<td>'.$value['publisher'].'</td>
															<td>'.$value['isbn'].'</td>
															<td>'.$status.'</td>
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
				
						 </div>
					  </div>
					</div>
				  </div>
            </div>
            <?php include'include/footer.php';?>
        </div>

         <?php include'include/js.php';?>
		<script>
			$('.select2').select2();
			
			function selectAcession() {
				var accession_no = $("#accession").val();
				$.ajax({
					url: "model/books.php",
					dataType:'json',
					type: "POST",
					data:{ 
						action:"select_accession",
						accession_no: accession_no
					},
					success: function(data) {
						console.log(data);
						accession_record(data);
					},
					error: function(){
						alert("error");
					}
				});
			}
			function accession_record(data) {
				var list = data.data;
				var length = list.length;
				var html = "";
				var count = 0;

				for(var x = 0; x < length; x++) {
					count++;
					html = html + 
					'<tr>' +
						'<td class="text-center">' + count + '</td>' +
						'<td>' + list[x].date_borrowed + '</td>' +
						'<td>' + list[x].firstname + ' ' +list[x].lastname + '</td>' +
						'<td>' + list[x].date_returned + '</td>' +
					'</tr>';
				}
				if(length > 0) {
					$("#accession_data").html(html);
				}
				else {
					$("#accession_data").html('<tr class="danger"><td colspan="4"><h3 class="text-center">No records available.</h3></td></tr>');
				}

			}
			
			
 		</script>
    </body>
</html>
