<?php
	require_once "model/controller.php";
	require_once "model/model.php";
	date_default_timezone_set('Asia/Manila');
	$year = date("Y");

	if(isset($_GET['user_id']) && !empty($_GET['user_id']) && isset($_GET['user_token']) && isset($_SESSION['user_token'])) {
		
		if($_SESSION['user_token'] == $_GET['user_token']) {
			$user_id = $_GET['user_id'];
			$pass_token = $_GET['user_token'];
			$sql = "SELECT * FROM tbl_employee JOIN tbl_user ON tbl_employee.user_id=tbl_user.user_id WHERE tbl_employee.user_id='$user_id' ";
			$user = new Model;
			$data2 = $user->displayRecord($sql);
			
			$sql = "SELECT tbl_booklogs.status, tbl_booklogs.book_id, tbl_booklogs.booklogs_id FROM tbl_booklogs WHERE tbl_booklogs.user_id='$user_id' AND EXTRACT(YEAR FROM tbl_booklogs.date) = '$year'";
			$data_userlogs = $user->displayRecord($sql);

			$_SESSION['book_id'] = $_GET['user_id'];
			$_SESSION['pass_token'] = $_GET['user_token'];
			$_SESSION['array'] = $data2;
			$_SESSION['array2'] = $data_userlogs;
			$_SESSION['current_page'] = "user_view.php";
		}
		else {
			header("location: dashboard.php");
		}
	}
	else {
		if(isset($_SESSION['array'])) {
			$data2 = $_SESSION['array'];
			$data_userlogs = $_SESSION['array2'];
			$pass_token = $_SESSION['pass_token'];
			$user_id = $_SESSION['book_id'];
			$user = new Model;
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
		<link href="include/mycss.css" rel="stylesheet">
		<style>
			
		</style>
        
    </head>
    <body class="nav-md"  onclick="resetTimer()" onmousedown="resetTimer()" onmousemove="resetTimer()" onscroll="resetTimer()" onkeypress="resetTimer()">
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
						 <div class="col-md-12 col-sm-12 col-xs-12" role="tabpanel" data-example-id="togglable-tabs"  >
							<ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist" >
							  <li role="presentation" class="active"><a href="#details" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true"><span class="badge bg-blue "><i class="glyphicon glyphicon-info-sign"></i></span>  User Details</a>
							  </li>
							  <li role="presentation" class=""><a href="#transaction" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false"><span class="badge bg-blue" id="total"><i class="fa fa-history"></i></span>  User logs</a>
							  </li>
							</ul>
							<div id="" class="tab-content">
								<div role="tabpanel" class="tab-pane fade active in"  id="details" aria-labelledby="home-tab">
									<div class="x_panel">
									  <div class="x_title">
										<img src="images/details.png" width="50px" height="50px">
											<h3 style="margin-left:60px; margin-top:-38px">User Details</h3>
											  <div class=" btn-group pull-right" style="margin-left:60px; margin-top:-45px">
												<a href="user_edit.php? user_id=<?php echo $user_id;?> & user_token=<?php echo $pass_token; ?>" class="btn btn-primary btn-sm"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit User Details"><i class="fa fa-edit"></i> Edit</a>
												<a href="user_account.php? action='tab2'" class="btn btn-primary btn-sm"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Back to user list"><i class="fa fa-mail-reply"></i> Back</a>
											  </div>
										<div class="clearfix"></div>
									  </div>
									  <div class="x_content">
										  <div class="col-md-4 col-sm-4 col-xs-4">
											<center>
												 <img src="<?php echo 'images_uploaded/'.$data2[0]['image']; ?>" style="width: 180px; height: 180px; padding: 3px; border: 3px solid #94979c;" id="image2"> 
											  </center>
											</div>
											<div class="col-md-8 col-sm-8 col-xs-8">
												<table class="table " style="font-size:14px">
												 <tr>
													<th>Employee No:</th>
													<td><?php echo $data2[0]['user_id']; ?></td>
												  </tr>
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
													<th>Position:</th>
													<td><?php echo $data2[0]['position']; ?></td>
												  </tr>
												<tr>
													<th>Address:</th>
													<td><?php echo $data2[0]['address']; ?></td>
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
									</div>
								</div>
								<div role="tabpanel" class="tab-pane fade"  id="transaction" aria-labelledby="home-tab">
									<div class="x_panel">
									  <div class="x_title">
										<img src="images/logs.png" width="50px" height="50px">
											<h3 style="margin-left:60px; margin-top:-38px">User Logs</h3>
											  <div class=" btn-group pull-right" style="margin-left:60px; margin-top:-45px">
												<a href="user_edit.php? user_id=<?php echo $user_id;?> & user_token=<?php echo $pass_token; ?>" class="btn btn-primary btn-sm"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit User Details"><i class="fa fa-edit"></i> Edit</a>
												<a href="user_account.php? action='tab2'" class="btn btn-primary btn-sm"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Back to user list"><i class="fa fa-mail-reply"></i> Back</a>
											  </div>
										<div class="clearfix"></div>
									  </div>
									  <div class="x_content">
										  <table class="table table-bordered table-striped jambo_table">
											  <thead>
												<tr>
												  <th class="text-center" width="10%">No.</th>
													<th width="30%">Book Title</th>
													<th width="10%" class="text-center">Quantity</th>
													<th width="20%">Description</th>
												  	<th width="20%">Date logs</th>
													<th>Option</th>
												</tr>
											  </thead>
											  <tbody class="searchable" >
												 <?php
												  	$count = 0;
												  	$data_array = array();
												  
													foreach($data_userlogs as $value) {
														$count++;
														$book_id = $value['book_id'];
														$booklogs_id = $value['booklogs_id'];
														if($value['status'] == "Received") {
															$sql = "SELECT tbl_booklogs.booklogs_id, tbl_booklogs.quantity, tbl_books.title, tbl_books.author, tbl_books.pages, tbl_books.fund, tbl_books.copyright, tbl_books.isbn, tbl_books.publisher, tbl_books.classification, tbl_booklogs.date, tbl_booklogs.status FROM tbl_booklogs JOIN tbl_books ON tbl_booklogs.book_id=tbl_books.book_id WHERE tbl_booklogs.status='Received' AND tbl_booklogs.booklogs_id='$booklogs_id' AND tbl_booklogs.user_id='$user_id'";
															$record = $user->displayRecord($sql);
															foreach($record as $value2) {
																echo 
																	'<tr>
																		<td class="text-center">'.$count.'</td>
																		<td>'.$value2['title'].'</td>
																		<td class="text-center"><span class="badge bg-green">'.$value2['quantity'].'</span></td>
																		<td>'.$value2['status'].' book(s)</td>
																		<td>'.date("M d, Y", strtotime($value2['date'])).'</td>
																		<td>
																		  <button type="button" class="btn btn-primary btn-xs" onclick=viewlogs("'.$booklogs_id.'","Received") ><i class="fa fa-eye"></i> View details</button>
																	  </td>
																  </tr>';
															}
															
														}
														else if($value['status'] == "Borrowed") {
															
															$sql = "SELECT tbl_booklogs.booklogs_id, tbl_booklogs.quantity, tbl_booklogs.date, tbl_booklogs.status, tbl_books.book_id, tbl_books.title, tbl_books.author, tbl_books.pages, tbl_books.fund, tbl_books.copyright, tbl_books.isbn, tbl_books.publisher, tbl_books.classification, tbl_borrowers.firstname, tbl_borrowers.lastname FROM tbl_booklogs JOIN tbl_borrowed ON tbl_booklogs.booklogs_id=tbl_borrowed.booklogs_id JOIN tbl_books ON tbl_booklogs.book_id=tbl_books.book_id JOIN tbl_borrowers ON tbl_booklogs.borrower_id=tbl_borrowers.borrower_id WHERE tbl_booklogs.user_id='$user_id' AND tbl_booklogs.status='Borrowed'  AND tbl_booklogs.booklogs_id='$booklogs_id' GROUP BY tbl_booklogs.booklogs_id";
															$record = $user->displayRecord($sql);
															foreach($record as $value2) {
																echo 
																	'<tr>
																		<td class="text-center">'.$count.'</td>
																		<td>'.$value2['title'].'</td>
																		<td class="text-center"><span class="badge bg-green">'.$value2['quantity'].'</span></td>
																		<td>'.$value2['status'].' book(s)</td>
																		<td>'.date("M d, Y", strtotime($value2['date'])).'</td>
																		<td>
																		  <button type="button" class="btn btn-primary btn-xs" onclick=viewlogs("'.$booklogs_id.'","Borrowed") ><i class="fa fa-eye"></i> View details</button>
																	  </td>
																  </tr>';
															}

														}
														else {
															
															$sql = "SELECT tbl_booklogs.booklogs_id, tbl_booklogs.quantity, tbl_borrowed.date_borrowed, tbl_borrowed.date_returned, tbl_booklogs.status, tbl_books.book_id, tbl_books.title, tbl_books.author, tbl_books.pages, tbl_books.fund, tbl_books.copyright, tbl_books.isbn, tbl_books.publisher, tbl_books.classification, tbl_borrowers.firstname, tbl_borrowers.lastname, tbl_employee.firstname as is_fn, tbl_employee.lastname as is_lname FROM tbl_booklogs JOIN tbl_borrowed ON tbl_booklogs.booklogs_id=tbl_borrowed.booklogs_id2 JOIN tbl_books ON tbl_booklogs.book_id=tbl_books.book_id JOIN tbl_borrowers ON tbl_booklogs.borrower_id=tbl_borrowers.borrower_id JOIN tbl_employee ON tbl_borrowed.user_id=tbl_employee.user_id WHERE tbl_booklogs.user_id='$user_id' AND tbl_booklogs.status='Returned'  AND tbl_booklogs.booklogs_id='$booklogs_id' GROUP BY tbl_booklogs.booklogs_id";
															$record = $user->displayRecord($sql);
															foreach($record as $value2) {
																echo 
																	'<tr>
																		<td class="text-center">'.$count.'</td>
																		<td>'.$value2['title'].'</td>
																		<td class="text-center"><span class="badge bg-green">'.$value2['quantity'].'</span></td>
																		<td>'.$value2['status'].' book(s)</td>
																		<td>'.date("M d, Y ", strtotime($value2['date_returned'])).'</td>
																		<td>
																		 <button type="button" class="btn btn-primary btn-xs" onclick=viewlogs("'.$booklogs_id.'","Returned") ><i class="fa fa-eye"></i> View details</button>
																	  </td>
																  </tr>';
															}
															
														}
													}
												  if($count == 0) {
													  echo '<tr class="danger"><td colspan="6"><h3 class="text-center">No records available.</h3></td></tr>';
												  }
															
												  ?>
											  </tbody>
										</table>
										  
										  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal_logs">
												<div class="modal-dialog modal-md">
												  <div class="modal-content">
													<div class="modal-header">
													  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
													  </button>
													  <h4 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-info-sign"></i> Received Details</h4>
													</div>
													<div class="modal-body">
														<ul class="list-group" >
													<li class="list-group-item list-group-item-success"><b>User Name: </b><b style="margin-left:77px" id="username"><?php echo $data2[0]['firstname']." ".$data2[0]['lastname']; ?></b></li>
													  <li class="list-group-item "><b>Book Title:</b><b style="margin-left:86px" id="title"></b></li>
													<li class="list-group-item"><b>Author Name: </b><b style="margin-left:65px" id="author"></b></li> 
													  <li class="list-group-item"><b>Pages: </b><b style="margin-left:107px" id="pages"></b></li>
													<li class="list-group-item"><b>Fund: </b><b style="margin-left:116px" id="fund"></b></li>
													<li class="list-group-item"><b>Copyright: </b><b style="margin-left:88px" id="copyright"></b></li>
													<li class="list-group-item"><b>ISBN: </b><b style="margin-left:117px" id="isbn"></b></li>
													<li class="list-group-item"><b>Publisher: </b><b style="margin-left:89px" id="publisher"></b></li>
													<li class="list-group-item"><b>Classification Number: </b><b style="margin-left:12px" id="classification"></b></li>
													<li class="list-group-item"><b>Received Quantity: </b><b style="margin-left:34px" id="quantity"></b></li>
													<li class="list-group-item"><b>Date Received: </b><b style="margin-left:59px" id="date_received"></b></li>
													</ul>
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
													  <h4 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-info-sign"></i> Borrowed Details</h4>
													</div>
													<div class="modal-body">
														<ul class="list-group" >
															<li class="list-group-item list-group-item-success"><b>User Name: </b><b style="margin-left:66px" id="username2"><?php echo $data2[0]['firstname']." ".$data2[0]['lastname']; ?></b></li>
															  <li class="list-group-item "><b>Book Title:</b><b style="margin-left:75px" id="title2"></b></li>
															<li class="list-group-item"><b>Borrower's Name: </b><b style="margin-left:27px" id="borrower2"></b></li> 
															<li class="list-group-item"><b>Quantity Borrowed: </b><b style="margin-left:16px" id="borrowed_qty2"></b></li>
															  <li class="list-group-item"><b>Date Borrowed: </b><b style="margin-left:41px" id="date_borrowed2"></b></li>
															</ul>
													</div>
													<div class="modal-footer">
													  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> Close</button>
													</div>
												  </div>
												</div>
											  </div>
										  
										  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal_logs3">
												<div class="modal-dialog modal-md">
												  <div class="modal-content">
													<div class="modal-header">
													  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
													  </button>
													  <h4 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-info-sign"></i> Returned Details</h4>
													</div>
													<div class="modal-body">
														<ul class="list-group" >
															<li class="list-group-item list-group-item-success"><b>User Name: </b><b style="margin-left:66px" id="username3"><?php echo $data2[0]['firstname']." ".$data2[0]['lastname']; ?></b></li>
															  <li class="list-group-item "><b>Book Title:</b><b style="margin-left:75px" id="title3"></b></li>
															<li class="list-group-item"><b>Borrower's Name: </b><b style="margin-left:28px" id="borrower3"></b></li> 
															  <li class="list-group-item"><b>Date Borrowed: </b><b style="margin-left:41px" id="date_borrowed3"></b></li>
															<li class="list-group-item"><b>Quantity Returned: </b><b style="margin-left:20px" id="borrowed_qty3"></b></li>
															<li class="list-group-item"><b>Issued By:</b><b style="margin-left:75px" ><?php echo $data2[0]['firstname']." ".$data2[0]['lastname']; ?></b> </li> 
															<li class="list-group-item"><b>Received By:</b><b style="margin-left:57px" id="receivedby3"></b> </li> 
															  <li class="list-group-item"><b>Date Returned: </b><b style="margin-left:42px" id="date_returned3"></b></li>
															<li class="list-group-item"><b>Remarks: </b><b style="margin-left:76px" id="remarks3"></b></li>
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
			function viewlogs(booklogs_id, remarks) {
				 if(remarks == "Received") {
					$("#modal_logs").modal("show");
					$.ajax({
						url: "model/user_account.php",
						dataType:'json',
						type: "POST",
						data:{ 
							action:"book_received",
							booklogs_id: booklogs_id
						},
						success: function(data) {
							console.log(data);
							
							$("#title").text(data.data[0].title);
							$("#author").text(data.data[0].author);
							$("#pages").text(data.data[0].pages);
							$("#fund").text(data.data[0].fund);
							$("#fund").text(data.data[0].fund);
							$("#copyright").text(data.data[0].copyright);
							$("#isbn").text(data.data[0].isbn);
							$("#publisher").text(data.data[0].publisher);
							$("#classification").text(data.data[0].classification);
							$("#quantity").text(data.data[0].quantity);
							$("#date_received").text(data.data[0].date);
							
						},
						error: function(){
							alert("error");
						}
					});
				}
				else if(remarks == "Borrowed") {
					$("#modal_logs2").modal("show");
					$.ajax({
						url: "model/user_account.php",
						dataType:'json',
						type: "POST",
						data:{ 
							action:"book_borrowed",
							booklogs_id: booklogs_id
						},
						success: function(data) {
							console.log(data);
							$("#borrower2").text(data.data[0].firstname + " " + data.data[0].lastname);
							$("#title2").text(data.data[0].title);
							$("#date_borrowed2").text(data.data[0].date);
							$("#borrowed_qty2").text(data.data[0].quantity);
						},
						error: function(){
							alert("error");
						}
					});
				}
				else {
					$("#modal_logs3").modal("show");
					$.ajax({
						url: "model/user_account.php",
						dataType:'json',
						type: "POST",
						data:{ 
							action:"book_returned",
							booklogs_id: booklogs_id
						},
						success: function(data) {
							console.log(data);
							$("#borrower3").text(data.data[0].firstname + " " + data.data[0].lastname);
							$("#title3").text(data.data[0].title);
							$("#receivedby3").text(data.data[0].is_fname + " " + data.data[0].is_lname);
							$("#date_borrowed3").text(data.data[0].date_borrowed);
							$("#date_returned3").text(data.data[0].date_returned);
							$("#remarks3").text(data.data[0].status);
							$("#borrowed_qty3").text(data.data[0].quantity);
						},
						error: function(){
							alert("error");
						}
					});
				}
				
			}
		</script>
    </body>
</html>
