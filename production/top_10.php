<?php
	require_once "model/controller.php";
	require_once "model/model.php";
	$book = new Model;
	$_SESSION['current_page'] = "top_10.php";
	date_default_timezone_set('Asia/Manila');
	$year = date("Y");

	$sql = "SELECT tbl_books.title, tbl_books.author, SUM(tbl_booklogs.quantity) AS quantity FROM tbl_booklogs JOIN tbl_books ON tbl_booklogs.book_id=tbl_books.book_id WHERE tbl_booklogs.status='Borrowed' GROUP BY tbl_booklogs.book_id ORDER BY SUM(tbl_booklogs.quantity) DESC LIMIT 10";
	$data_top = $book->displayRecord($sql);

	$sql = "SELECT COUNT(tbl_borrowed.account_no) as quantity, tbl_borrowers.firstname, tbl_borrowers.lastname, tbl_borrowers.schoolname FROM tbl_borrowed JOIN tbl_borrowers ON tbl_borrowed.borrower_id=tbl_borrowers.borrower_id GROUP BY tbl_borrowed.borrower_id ORDER BY COUNT(tbl_borrowed.account_no) DESC LIMIT 10";
	$data2_top = $book->displayRecord($sql);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>E-Library</title>
		<?php require_once('include/css.php');  ?>
		<style>
			table {
				font-size: 12px;
				background-color: rgba(222, 220, 234, 0.8);
			}
		</style>
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
                <div class="right_col" role="main">
                    <div class="">
                       
                        <div class="clearfix"></div>
                        <div class="row">
							<div class="col-md-6 col-sm-6 col-xs-6">
							<div class="x_panel" >
						  <div class="x_title">
							<h2 style="font-weight:bold;"><i class="fa fa-book"></i> Top 10 books borrowed </h2>
							  <b class="pull-right">Year <?php echo $year; ?></b>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							  <div class="row">
								<table class="table table-bordered jambo_table">
								  <thead>
									<tr>
										<th class="text-center" width="5%">No.</th>
									  <th>Title</th>
										<th>Author</th>
									  <th>Total</th>
									</tr>
								  </thead>
								  <tbody>
									  <?php
									  $count = 0;
										foreach ($data_top as $value) {
											$count++;
										?>
										<tr>
											<td class="text-center"><?php echo $count; ?></td>
											<td><?php echo $value['title']; ?></td>
											<td><?php echo $value['author']; ?></td>
											<td><span class="badge bg-green"><?php echo $value['quantity']; ?></span> book(s)</td>
										</tr>
									<?php }
									  if($count < 10) {
											  $count++;
											  while($count < 11) {
											?>	 <tr >
													<td class="text-center"><?php echo $count; ?></td>
													<td></td>
													<td></td>
													<td></td>
									  				
												</tr>
										<?php
												  $count++;
												}
									  }
									  ?>

								  </tbody>
								</table>
							 </div>
						</div>
					  </div>
				 	</div>
						<div class="col-md-6 col-sm-6 col-xs-6">
						<div class="x_panel" >
						  <div class="x_title">
							<h2 style="font-weight:bold;"><i class="fa fa-users"></i> Top 10 borrowers</h2>
							  <b class="pull-right">Year <?php echo $year; ?></b>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							  <div class="row">
								<table class="table table-bordered jambo_table">
								  <thead>
									<tr>
									  <th class="text-center" width="5%">No</th>
									  <th>Borrower</th>
										<th>School Name</th>
									  <th>Total</th>
									</tr>
								  </thead>
								  <tbody>
									  <?php
									  $count2 = 0;
										foreach ($data2_top as $value) {
											$count2++;
										?>
										<tr>
											<td class="text-center"><?php echo $count2; ?></td>
											<td><?php echo $value['firstname']." ".$value['lastname']; ?></td>
											<td><?php echo $value['schoolname']; ?></td>
											<td><span class="badge bg-green" ><?php echo $value['quantity']; ?> </span> book(s)</td>
										</tr>
									<?php }
									  if($count2 < 10) {
										  $count2++;
										  while($count2 < 11) {
										?>	 <tr>
												<td class="text-center"><?php echo $count2; ?></td>
												<td></td>
												<td></td>
									  			<td></td>
											</tr>
									<?php
											  $count2++;
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
        </div>
		 <?php include'include/js.php';?>
			
			
    </body>
</html>
