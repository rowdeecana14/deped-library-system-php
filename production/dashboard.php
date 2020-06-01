<?php
    require_once "model/controller.php";
	require_once "model/model.php";
	require_once "db_backup.php";
	$dbbackup = new db_backup;
	$dbbackup->autoBackup();
	$dashboard = new Model;
	$database = new Database;
	$_SESSION['current_page'] = "dashboard.php";

	
	$user = count($dashboard->displayRecord("SELECT * FROM tbl_user"));
	$borrower = count($dashboard->displayRecord("SELECT * FROM tbl_borrowers"));
	$borrowed = count($dashboard->displayRecord("SELECT * FROM tbl_borrowed JOIN tbl_copy ON tbl_borrowed.account_no=tbl_copy.account_no JOIN tbl_books ON tbl_copy.book_id=tbl_books.book_id JOIN tbl_borrowers ON tbl_borrowed.borrower_id=tbl_borrowers.borrower_id WHERE tbl_copy.remarks='Borrowed' GROUP BY tbl_copy.account_no"));
	$damaged = $database->totalRow("SELECT COUNT(tbl_copy.account_no) as quantity FROM tbl_books JOIN tbl_copy ON tbl_books.book_id=tbl_copy.book_id JOIN tbl_borrowed ON tbl_copy.account_no=tbl_borrowed.account_no WHERE tbl_copy.status='Damaged' AND tbl_borrowed.status='Damaged'");

	$inventory = $database->totalRow("SELECT SUM(tbl_books.qty_in-tbl_books.qty_out) as quantity FROM tbl_books");
	$lost = $database->totalRow("SELECT COUNT(tbl_copy.account_no) as quantity FROM tbl_books JOIN tbl_copy ON tbl_books.book_id=tbl_copy.book_id JOIN tbl_borrowed ON tbl_copy.account_no=tbl_borrowed.account_no WHERE tbl_copy.status='Lost' AND tbl_borrowed.status='Lost'");

	date_default_timezone_set('Asia/Manila');
	$year =  date("Y");
	$month_list = ['01', '02', '03', '04', '05', '06', '07','08', '09', '10', '11', '12'];
	$string_month = array('01' =>"January", '02' =>"February", '03' =>"March", '04' =>"April", '05' =>"May", '06' =>"June", '07' =>"July",'08' =>"August",'09' =>"Septempber", '10' =>"October", '11' =>"November", '12' =>"December");
	$data_graph = "";
	$counted = 0;

	foreach($month_list as $month) {
	
		$sql = "SELECT COUNT(tbl_borrowed.account_no) AS quantity FROM tbl_borrowed WHERE EXTRACT(YEAR FROM tbl_borrowed.date_borrowed)='$year' AND EXTRACT(MONTH FROM tbl_borrowed.date_borrowed)='$month'";
		$quantity = $dashboard->displayRecord($sql);
		$qty = 0;
		if($quantity[0]['quantity'] == null) {
			$qty = 0;
		}
		else {
			$qty = $quantity[0]['quantity'];
		}
		$counted++;
		if($counted %2== 1) {
			$data_graph .= "{Month:'".$string_month[$month]."', Total:".$qty.", Color:'#FCD202'}, ";
		}
		else {
			$data_graph .= "{Month:'".$string_month[$month]."', Total:".$qty.", Color:'#FF6600'}, ";
		}
	}
	$data_graph = substr ($data_graph, 0, -2);
    $data_graph = "[".$data_graph."];";
	
	$calendar = "";
	$calendar_data = array();
	$get_event = $dashboard->displayRecord("SELECT tbl_events.event_id,tbl_events.date, DATEDIFF( tbl_events. date, CURDATE()) as diff, tbl_events.title FROM tbl_events");
	foreach($get_event as $value) {
		$date = $value['date'];
		$title = $value['title'];
		$id = $value['event_id'];
		$diff = $value['diff'];
		if($diff < 0) {
			$list = array("id" =>$id, "title" =>"Done: ".$title, "start" =>$date, "backgroundColor" =>"#CD0D74");
		} 
		else if($diff == 0) {
			$list = array("id" =>$id, "title" =>"Today: ".$title, "start" =>$date, "backgroundColor" =>"#4075af");
		}
		else {
			$list = array("id" =>$id, "title" =>"Incoming: ".$title, "start" =>$date, "backgroundColor" =>"#5cb85c;");
		}
		
		array_push($calendar_data, $list);
	}
	$count = 0;
    $len = count($calendar_data);
	foreach($calendar_data as $value) {
		$count++;
		$id = $value['id'];
		$title = $value['title'];
		$date = $value['start'];
		$color = $value['backgroundColor'];
		if($count == $len) {
			$calendar .= "{id:'".$id."', title:'".$title."', start:'".$date."', backgroundColor:'".$color."'}";
		}
		else {
			$calendar .= "{id:'".$id."', title:'".$title."', start:'".$date."', backgroundColor:'".$color."'},";
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
		<style>
			.fc-unthemed td.fc-today {
				background: #aedcde;
			}
			
		</style>
		
    </head>
    <body class="nav-md" onclick="resetTimer()" onmousedown="resetTimer()" onmousemove="resetTimer()" onscroll="resetTimer()" onkeypress="resetTimer()">
        <div class="container body" onload="notification();">
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

                <!-- page content -->
                <div class="right_col" role="main" >
					<div class="x_panel" >
						  <div class="x_title">
							  <img src="images/widget.png" width="50px" height="50px">
								<h3 style="margin-left:60px; margin-top:-38px">Widget</h3>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							  <div class="col-md-12 col-sm-12 col-xs-12 hidden-print">
								<div class="row tile_count">
									<div class="animated flipInY col-lg-4 col-md-4 col-sm-4 col-xs-12" >
										<div class="tile-stats" id="box" style="background-color: rgba(222, 220, 234, 0.9); ">
											<div class="icon"><img src="images/user.png" height="100px" width="100px" style="margin-left:-40px; margin-top:-12px">
											</div>
											<div class="count"><?php echo $user; ?></div>
											<h3 style="color:#73879C">Users</h3>
											<hr style="margin-bottom:-3px">
											<a href="user_account.php? action='display'" style="">
												<p  class="text-center">More info <i class="fa fa-arrow-circle-right"></i></p>
											</a>
										</div>
									</div>
									<div class="animated flipInY col-lg-4 col-md-4 col-sm-4 col-xs-12" >
										<div class="tile-stats" id="box" style="background-color: rgba(222, 220, 234, 0.9); ">
											<div class="icon"><img src="images/borrowed.png" height="100px" width="100px" style="margin-left:-40px; margin-top:-12px">
											</div>
											<div class="count"><?php echo $borrower; ?></div>
											<h3 style="color:#73879C">Borrowers</h3>
											<hr style="margin-bottom:-3px">
											<a href="borrowers.php? action='display'" style="">
												<p  class="text-center">More info <i class="fa fa-arrow-circle-right"></i></p>
											</a>
										</div>
									</div>
									<div class="animated flipInY col-lg-4 col-md-4 col-sm-4 col-xs-12" >
										<div class="tile-stats" id="box" style="background-color: rgba(222, 220, 234, 0.9); ">
											<div class="icon"><img src="images/book_return.png" height="80px" width="80px" style="margin-left:-30px; margin-top:-10px">
											</div>
											<div class="count"><?php echo $borrowed; ?></div>
											<h3 style="color:#73879C">Borrowed</h3>
											<hr style="margin-bottom:-3px">
											<a href="borrowed_books.php" style="">
												<p  class="text-center">More info <i class="fa fa-arrow-circle-right"></i></p>
											</a>
										</div>
									</div>
									
								</div>
								  </div>
							</div>
						</div>
						 <div class="x_panel">
						  <div class="x_title">
							<img src="images/charts.png" width="50px" height="50px">
								<h3 style="margin-left:60px; margin-top:-38px;">Graphical Chart</h3>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							  <div id="chartdiv2" style="width: 100%; height: 550px; background-color: rgba(222, 220, 234, 0.9);"></div>
							  <br>
							  <div id="chartdiv" style="width: 100%; height: 550px; background-color: rgba(222, 220, 234, 0.8);"></div>
						</div>
					</div>
					<div class="x_panel">
					  <div class="x_title">
						<img src="images/calendar.png" width="50px" height="50px">
						<h3 style="margin-left:60px; margin-top:-38px; ">Events Calendar</h3>
						<div class="clearfix"></div>
					  </div>
					  <div class="x_content">

						<div id='calendar' style="background-color: rgba(222, 220, 234, 0.8);"></div>

					  </div>
					</div>
                </div>
				<form id="borrowForm">
				  <div class="modal fade" id="myModal" role="dialog">
					<div class="modal-dialog modal-lg">
					  <div class="modal-content">
						<div class="modal-header">
						  <button type="button" class="close" data-dismiss="modal">&times;</button>
						  <h4 class="modal-title" id="mtitle">Title</h4>
						</div>
						<div class="modal-body">
							<div class="row">
							 <table class="table table-bordered table-striped jambo_table">
								  <thead>
									<tr>
									  <th>No.</th>
										<th>Book Title</th>
										<th>Author Name</th>
										<th>No of pages</th>
										<th>Copyright</th>
										<th>Publisher</th>
										<th>ISBN</th>
										<th class="text-center">Total</th>
									</tr>
								  </thead>
								  <tbody id="data">

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
				<div class="modal fade" id="myModal2" role="dialog">
					<div class="modal-dialog modal-lg">
					  <div class="modal-content">
						<div class="modal-header">
						  <button type="button" class="close" data-dismiss="modal">&times;</button>
						  <h4 class="modal-title" id="mtitle2">Title</h4>
						</div>
						<div class="modal-body">
							<div class="row">
							 <table class="table table-bordered table-striped jambo_table">
								  <thead>
									<tr>
									  <th>No.</th>
										 <th>ISBN</th>
									  <th>Book Title</th>
										<th>Grade</th>
										<th>Author Name</th>
										<th>Category</th>
										<th class="text-center">Total</th>
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
				<div class="modal fade" id="myModal3" role="dialog">
					<div class="modal-dialog modal-md">
					  <div class="modal-content">
						<div class="modal-header">
						  <button type="button" class="close" data-dismiss="modal">&times;</button>
						  <h4 class="modal-title" id="title3"><i class="fa fa-calendar"></i> Calendar Entry</h4>
						</div>
						<div class="modal-body">
							<form id="update_form" class="form-horizontal calender" role="form">
								<div class="form-group">
								  <label class="col-sm-3 control-label">Title</label>
								  <div class="col-sm-9">
									  <input type="hidden" class="form-control" id="event_id" name="event_id">
									  <input type="hidden" class="form-control" name="action" value="calendar_update">
									<input type="text" class="form-control" id="title2" name="title2" required onkeyup="validation('title2')" onkeydown="validation('title2')" onmouseout="validation('title2')">
								  </div>
								</div>
								<div class="form-group">
								  <label class="col-sm-3 control-label">Description</label>
								  <div class="col-sm-9">
									<textarea class="form-control" style="height:55px;" id="description2" name="description2" required onkeyup="validation('description2')" onkeydown="validation('description2')" onmouseout="validation('description2')"></textarea>
								  </div>
								</div>
								<div class="pull-right margin">
								  <br>
								  <button type="button" class="btn btn-danger" id="remove_btn"><i class="fa fa-times-circle"></i> Remove</button>
									<button type="submit" class="btn btn-primary"><i class="fa fa-check-square-o"></i> Update</button>
							  </div>
							 </form>
						 </div>
						<div class="modal-footer">
						</div>
						</div>
					  </div>
					</div>
				
				
				
				
			<div id="CalenderModalNew" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-dialog">
				<div class="modal-content">

				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title" id="myModalLabel"><i class="fa fa-calendar"></i> Calendar Entry</h4>
				  </div>
				  <div class="modal-body">
					<div id="testmodal" style="padding: 5px 20px;">
					  <form id="antoform" class="form-horizontal calender" role="form">
						<div class="form-group">
						  <label class="col-sm-3 control-label">Title</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control" id="title" name="title" required onkeyup="validation('title')" onkeydown="validation('title')" onmouseout="validation('title')">
						  </div>
						</div>
						<div class="form-group">
						  <label class="col-sm-3 control-label">Description</label>
						  <div class="col-sm-9">
							<textarea class="form-control" style="height:55px;" id="descr" name="descr" required onkeyup="validation('descr')" onkeydown="validation('descr')" onmouseout="validation('descr')"></textarea>
						  </div>
						</div>
						  <div class="pull-right margin">
						  <br>
						  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cancel</button>
							<button type="submit" class="btn btn-primary"><i class="fa fa-check-square-o"></i> Saved</button>
					  </div>
					  </form>
						
					</div>
				  </div>
				  <div class="modal-footer">
				  </div>
				</div>
			  </div>
			</div>


    <div id="fc_create" data-toggle="modal" data-target="#CalenderModalNew"></div>
    <div id="fc_edit" data-toggle="modal" data-target="#CalenderModalEdit"></div>
            </div>
            <?php include'include/footer.php';?>
        </div>
         <?php include'include/js.php';?>
		<script type="text/javascript">
			function validation(id) {
				var textfield = document.getElementById(id);
				var regex = /[^a-z 0-9 _ -.]/gi;
				var bad = [/fuck/g,/gago/g,/abno/g,/pesti/g,/bobo/g,];

				for (var list = 0; list < bad.length; list++) {

					textfield.value = textfield.value.replace(bad[list], "");
				}

				textfield.value = textfield.value.replace(regex, "");
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
                    title: "Total"
                }],

                graphs: [{

                    valueField: "Total",
                    colorField: "Color",
                    type: "column",
                    lineAlpha: 0,
                    fillAlphas: 1,
                    balloonText: "<span style='font-size:18px'>Month: <b>[[Month]]</b><br>Total: <b>[[value]]</b></span>"
                }],

                chartCursor: {
                    cursorAlpha: 0,
                    zoomable: false,
                    categoryBalloonEnabled: false
                }
            });
			var legend2 = new AmCharts.AmLegend();
			chart.addTitle("Monthly borrowed", 16);
			chart.addListener("clickGraphItem", function(event) {
				console.log(event);
				var category = event.item.dataContext.Month;
				var quantity = event.item.dataContext.Total;
				if(quantity > 0) {
					$("#myModal").modal("show");
					$("#mtitle").html('<i class="fa fa-book"></i> Borrowed on '+category);
					$.ajax({
						url: "model/dashboard.php",
						dataType:'json',
						type: "POST",
						data:{ 
							action:"month",
							month: category
						},
						success: function(data) {
							console.log(data);
							record(data);
						},
						error: function(){
							alert("error");
						}
					});
				}
			});
			
			
			var legend = "";
			var chart2 = "";
			var inventory = "<?php echo $inventory; ?>";
			var damaged = "<?php echo $damaged; ?>";
			var borrowed = "<?php echo $borrowed; ?>";
			var lost = "<?php echo $lost; ?>";
            var chartData2 = [
                {
                    "category": "Damaged",
                    "total": damaged,
					"color": "#FF6600"
                },
                {
                    "category": "Lost",
                    "total": lost,
					"color": "#CD0D74"
                },
                {
                    "category": "Borrowed",
                    "total": borrowed,
					"color": "#FCD202"
                },
                {
                    "category": "Available",
                    "total": inventory,
					"color": "#04D215"
                }
            ];
			


            AmCharts.ready(function () {
                // PIE CHART
                chart2 = new AmCharts.AmPieChart();

                // title of the chart
                chart2.addTitle("List of books", 16);

                chart2.dataProvider = chartData2;
                chart2.titleField = "category";
                chart2.valueField = "total";
				chart2.colorField = "color",
                chart2.sequencedAnimation = true;
                chart2.startEffect = "elastic";
                chart2.innerRadius = "30%";
                chart2.startDuration = 2;
                chart2.labelRadius = 15;
                chart2.balloonText = "<span style='font-size:18px'><b>Category: </b>[[title]]</span><br><span style='font-size:18px'><b>Total: </b>[[value]]</span>";
                // the following two lines makes the chart 3D
                chart2.depth3D = 10;
                chart2.angle = 15;
				legend = new AmCharts.AmLegend();
				legend.align = "center";
				legend.markerType = "square";
				chart2.addLegend(legend);
				chart2.addListener("clickSlice", function(event){
					//console.log(event);
					var category = event.dataItem.dataContext.category;
					$("#myModal").modal("show");
					$("#mtitle").html('<i class="fa fa-book"></i> '+category+" Books");

					$.ajax({
						url: "model/dashboard.php",
						dataType:'json',
						type: "POST",
						data:{ 
							action:category,
						},
						success: function(data) {
							console.log(data);
							record(data);
						},
						error: function(){
							alert("error");
						}
					});
				});
                // WRITE
                chart2.write("chartdiv2");
            });
			function record(data) {
				var list = data.data;
				var length = list.length;
				var count = 0;
				var html = "";
				var count = 0;
				for(var x = 0; x < length; x++) {
					count++;
					html = html + 
					'<tr>' +
						'<td class="text-center">' + count + '</td>' +
						'<td>' + list[x].title + '</td>' +
						'<td>' + list[x].author + '</td>' +
						'<td>' + list[x].pages + '</td>' +
						'<td>' + list[x].copyright + '</td>' +
						'<td>' + list[x].publisher + '</td>' +
						'<td>' + list[x].isbn + '</td>' +
						'<td class="text-center"><span class="badge bg-green ">' + list[x].quantity + '</span></td>' +
					'</tr>';
				}
				
				if(length > 0) {
					$("#data").html(html);
				}
			}
			
			
			function record2(data) {
				var list = data.data;
				var length = list.length;
				var count = 0;
				var html = "";
				var count = 0;
				for(var x = 0; x < length; x++) {
					count++;
					html = html + 
					'<tr>' +
						'<td class="text-center">' + count + '</td>' +
						'<td>' + list[x].isbn + '</td>' +
						'<td>' + list[x].title + '</td>' +
						'<td>' + list[x].grade + '</td>' +
						'<td>' + list[x].author + '</td>' +
						'<td>' + list[x].category + '</td>' +
						'<td class="text-center"><span class="badge bg-green ">' + list[x].quantity + '</span></td>' +
					'</tr>';
				}
				
				if(length > 0) {
					$("#data2").html(html);
				}
			}
			init_calendar();
			
			function  init_calendar() {
					
				if( typeof ($.fn.fullCalendar) === 'undefined'){ return; }
				//console.log('init_calendar');
					
				var date = new Date(),
					d = date.getDate(),
					m = date.getMonth(),
					y = date.getFullYear(),
					started,
					categoryClass;

				var calendar = $('#calendar').fullCalendar({
				  header: {
					left: 'prev,next today',
					center: 'title',
					right: 'month,agendaWeek,agendaDay,listMonth'
				  },
				  selectable: true,
				  selectHelper: true,
				  select: function(start, end, allDay) {
					$('#fc_create').click();

					started = start;
					ended = end;

					$("#antoform").on("submit", function(e) {
					
						e.preventDefault();
					  var title = $("#title").val();
					  var descr = $("#descr").val();
					  var selected_date = start._d;
					 addRecord(title, descr, selected_date);
						
						
					  if (end) {
						ended = end;
					  }

					  categoryClass = $("#event_type").val();

					  if (title) {
						calendar.fullCalendar('renderEvent', {
							title: "Event: "+title,
							start: started,
							end: end,
							allDay: allDay
						  },
						  true // make the event "stick"
						);
					  }

					  $('#title').val('');
						$('#descr').val('');

					  calendar.fullCalendar('unselect');

					  $('.antoclose').click();

					  return false;
					});
				  },
				  eventClick: function(calEvent, jsEvent, view) {
					  
					  console.log(calEvent);
					  var date = calEvent._start._i;
					  var color = calEvent.backgroundColor;
					  var id = calEvent.id;
					  $("#myModal3").modal("show");
					  $.ajax({
							url: "model/dashboard.php",
							dataType:'json',
							type: "POST",
							data:{ 
								action:"calendar_view",
								id:id
							},
							success: function(data) {
								//console.log(data);
								$("#event_id").val(id);
								$("#title2").val(data.data[0].title);
								$("#description2").val(data.data[0].description);
							},
							error: function(){
								alert("error");
							}
						});
					  
				  },
				  editable: false,
				  events: [<?php echo $calendar; ?>],
				   
				});
				
			};
			
			
			function addRecord(title, descr, selected_date) {
				
				if(title != "" && descr != "") {
					var dd = "";
					dd = selected_date;
					$("#CalenderModalNew").modal("hide");
					 $.ajax({
						url: "model/dashboard.php",
						dataType:'json',
						type: "POST",
						data:{ 
							action:"add_schedule",
							title: title,
							description: descr,
							selected_date: dd
						},
						success: function(data) {
							console.log(data);
							if(data.data == "true") {
								toastr.info("Events entry was succussfully saved.");
								setTimeout(function(){ location.reload(); }, 1000);

							}
							else {
								alert("Error..");
								setTimeout(function(){ location.reload(); }, 1000);
							}
						},
						error: function(){
							alert("error");
							setTimeout(function(){ location.reload(); }, 1000);
						}
					});
				}
				else {
					alert("Title and description must be filled out.");
					setTimeout(function(){ location.reload(); }, 1000);
				}
				
			}
			$("#update_form").on("submit", (function(e) {
				
				e.preventDefault();
				 $.ajax({
					url: "model/dashboard.php",
					dataType:'json',
					type: "POST",
					data:{
						id:$("#event_id").val(),
						title2:$("#title2").val(),
						description2:$("#description2").val(),
						action: "calendar_update"
						
					},
					success: function(data) {
						console.log(data);
						if(data.data == "true") {
							toastr.info("Events entry was succussfully updated.");
							setTimeout(function(){ location.reload(); }, 1000);
						}
						else {
							alert("Error..");
							setTimeout(function(){ location.reload(); }, 1000);
						}
					},
					error: function(){
						alert("error");
					}
				});
			}));
			$("#remove_btn").click(function() {
				$.ajax({
					url: "model/dashboard.php",
					dataType:'json',
					type: "POST",
					data:{
						id:$("#event_id").val(),
						action: "calendar_remove"
						
					},
					success: function(data) {
						console.log(data);
						if(data.data == true) {
							toastr.error("Events entry was succussfully removed.");
							setTimeout(function(){ location.reload(); }, 1000);
						}
						else {
							alert("Error..");
							setTimeout(function(){ location.reload(); }, 1000);
						}
					},
					error: function(){
						alert("error");
					}
				});
			});
		</script>
    </body>
</html>
