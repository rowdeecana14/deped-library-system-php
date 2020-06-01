<?php
   	require_once "model/controller.php";
	require_once "model/model.php";
	$logs = new Model;
	$database = new Database;
	$_SESSION['current_page'] = "userlogs.php";

	$data_logs = array();
	$sql = "SELECT * FROM tbl_userlogs ORDER BY log_id DESC";
	$result = $logs->displayRecord($sql);

	foreach($result as $value) {

		if($value['user_id'] == "") {

			$fullname = "Unknown";
			$date=date('M d, Y',strtotime($value['date']));
			$time=date('h:i A',strtotime($value['time']));
			$list = array("image" =>"", "fullname" =>$fullname, "action" =>$value['action'], "date" =>$date, "time" =>$time);
			array_push($data_logs, $list);
		}
		else {
			$getdata = user($value['user_id'], $logs);
			$image = "images_uploaded/".$getdata[0]['image'];
			$fullname = $getdata[0]['firstname']." ".$getdata[0]['lastname'];
			$date=date('M d, Y',strtotime($value['date']));
			$time=date('h:i A',strtotime($value['time']));
			$list = array("image" =>$image, "fullname" =>$fullname, "action" =>$value['action'], "date" =>$date, "time" =>$time);
			array_push($data_logs, $list);
		}
	}
	
	function user($user_id, $logs) {
		
		$data = array();
		$sql = "SELECT * FROM tbl_employee WHERE user_id='$user_id'";
		$data = $logs->displayRecord($sql);
		return $data;
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
	$total_logs = count($data_logs);
	if($record[0]['role'] == 1) {
		 
	}
	else {
		 header("location: dashboard.php");
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
			
		</style>
    </head>
    <body class="nav-md" onclick="resetTimer()" onmousedown="resetTimer()" onmousemove="resetTimer()" onscroll="resetTimer()" onkeypress="resetTimer()">
        <div class="container body">
            <div class="main_container">
                <?php
                    require_once('include/sidemenu.php'); 
                    require_once('include/topnav.php');
                ?>
                <div class="right_col" role="main" >
					
                 <div class="row">
					<div class="x_panel">
					  <div class="x_title">
						 <img src="images/logs.png" width="50px" height="50px">
						<h3 style="margin-left:60px; margin-top:-38px">User Logs</h3>
						<div class="clearfix"></div>
					  </div>
					  <div class="x_content">
						  <div class="row">
							  <br>
							  <div class="col-md-9 col-sm-9 col-xs-9">
								<div class="input-group ">
									<input type="text" class="form-control" style="height:45px; background-color:#d3ead9" placeholder="Search Logs" id='filter'>
									<span class="input-group-addon"><i class="fa fa-search" style="width:30px"></i></span>
								  </div>
							  </div>
							   <div class="col-md-3 col-sm-3 col-xs-3">
								 <button type="button" class="btn btn-default pull-right" style="margin-top:-10px; border: 2px solid #52b3a0"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Refresh Records" id="refresh">
									<img src="images/refresh.png" width="50px" height="50px">
								 </button>
							  </div>
							  <div class="col-md-12 col-sm-12 col-xs-12">
								  <br>
								  <table class="table table-bordered table-striped jambo_table">
									  <thead>
										<tr>
										  <th class="text-center" width="100px">Image</th>
										  <th>Fullname</th>
										  <th width="40%">Action</th>
										<th>Date</th>
										<th>Time</th>
										</tr>
									  </thead>
									  <tbody class="searchable">
										  <?php 
										  	$count = $start;
											$limit = 0;

											if(count($data_logs) >= $end) {
												$limit = $end;
											}
											else {
												$limit = count($data_logs);
											}
											if(count($data_logs) > 0) {

												for($x = $start; $x < $limit; $x++) {

													$count++;
													$image = $data_logs[$x]['image'];
													if($data_logs[$x]['image'] == "") {
														echo 
														'<tr>
															<td>
																<center><img src="images_uploaded/unknown.png" style="width: 50px; height: 50px; padding: 2px; border: 2px solid red; border-radius: 50%;">
																</center>
															</td>
															<td ><br>'.$data_logs[$x]['fullname'].'</td>
															<td><br>'.$data_logs[$x]['action'].'</td>
															<td><br>'.$data_logs[$x]['date'].'</td>
															<td><br>'.$data_logs[$x]['time'].'</td>
															</td>
														</tr>';
													}
													else {
															echo 
															'<tr>
																<td><center><img src="'.$data_logs[$x]['image'].'" style="width: 50px; height: 50px; padding: 2px; border: 2px solid #4181ea; border-radius: 50%;"></center></td>
																<td ><br>'.$data_logs[$x]['fullname'].'</td>
																<td><br>'.$data_logs[$x]['action'].'</td>
																<td><br>'.$data_logs[$x]['date'].'</td>
																<td><br>'.$data_logs[$x]['time'].'</td>
															</tr>';
													}
												}
											}
										  	else {
												echo '<tr class="danger"><td colspan="8"><h3 class="text-center">No records available.</h3></td></tr>';
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
													<p>Showing '.$count.' of '.$total_logs.' entries</p>
													<form method="get" style="margin-top: -30px">
														<input type="hidden" name="next_start" value="'.$next_start.'">
														<input type="hidden" name="next_end" value="'.$next_end.'">
														<button type="submit" name="next" class="btn btn-primary pull-right margin">Next <i class="fa fa-arrow-right"></i></button>
													</form>
												</div>';
											}
											else if(count($data_logs) > $end) {
												echo '
												<div class="box-footer" style="border-top: 1px solid #afaaaa;">
													<p>Showing '.$count.' of '.$total_logs.' entries</p>
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
													<p>Showing '.$count.' of '.$total_logs.' entries</p>
													<form method="get" style="margin-top: -30px">
														<input type="hidden" name="previous_start" value="'.$previous_start.'">
														<input type="hidden" name="previous_end" value="'.$previous_end.'">
														<button type="submit" name="previous" class="btn btn-primary pull-right"><i class="fa fa-arrow-left"></i> Previous </button>
													</form>
												</div>';
											}
										}
										else {
											if(count($data_logs) > 0 && $previous_end  > 0) {
												echo '
												<div class="box-footer" style="border-top: 1px solid #afaaaa;">
													<p>Showing '.$count.' of '.$total_logs.' entries</p>
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
													<p>Showing '.$count.' of '.$total_logs.' entries</p>
												</div>';
											}
										}
									  ?>
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
		
			$('#filter').keyup(function() {
				
				var rex = new RegExp($(this).val(), 'i');
				$('.searchable tr').hide();
				$('.searchable tr').filter(function() {

					return rex.test($(this).text());
				}).show();
			});
			$('#refresh').click(function() {
				$("#filter").val("");
				$('.searchable tr').show();
			});
		</script>
    </body>
</html>
