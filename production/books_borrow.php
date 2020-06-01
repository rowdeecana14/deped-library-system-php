<?php
	require_once "model/model.php";
	require_once "model/controller.php";
	$sql = "SELECT * FROM tbl_books";
	$book = new Model;
	$data2 = $book->displayRecord($sql);
	$sql2 = "SELECT * FROM tbl_borrowers ORDER BY lastname";
	$borrower = $book->displayRecord($sql2);
	$auth = new Database;
	$token = $auth->generateAuth();
	$_SESSION['borrowed_token'] = $token;
	date_default_timezone_set('Asia/Manila');
	$date = date("m/d/Y");
	$_SESSION['current_page'] = "books_borrow.php";
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
			.profile_view {
				border: 1px solid #94979c;
			}
			.profile_view:hover {
				
				background-color: #d3ead9;
			}
			#result {
				margin-top: 45px;
				margin-left: -100%;
				position: absolute;
				width: 100%;
				max-width: 670px;
				cursor: pointer;
				overflow-y: auto;
				max-height: 200px;
				box-sizing: border-box;
				z-index: 1001;
			}
			.link-class:hover {
				background-color: #d3ead9;
			}
		</style>
    </head>
   
    <body class="nav-md" onload="display()" onclick="resetTimer()" onmousedown="resetTimer()" onmousemove="resetTimer()" onscroll="resetTimer()" onkeypress="resetTimer()">
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
					<div class="clearfix"></div>
					<div class="row" >
								<div class="" role="tabpanel" data-example-id="togglable-tabs"  >
									<ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist" >
									  <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true"><span class="badge bg-blue "><i class="fa fa-table"></i></span>  Book catalog</a>
									  </li>
									  <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false"><span class="badge bg-blue" id="total">0</span>  Book bags</a>
									  </li>
									</ul>
									<div id="myTabContent" class="tab-content">
										<div role="tabpanel" class="tab-pane fade active in"  id="tab_content1" aria-labelledby="home-tab">
											<div class="x_panel" >
											  <div class="x_title">
												  <img src="images/book_list.png" width="50px" height="50px">
													<h3 style="margin-left:60px; margin-top:-40px">Book catalog</h3>
													<div class="clearfix"></div>
												  </div>
												  <div class="x_content">
														<div class="col-md-12 col-sm-12 col-xs-12">
															<div class="col-md-8 col-sm-8 col-xs-8">
																<div class="input-group ">
																	<input type="text" class="form-control" style="height:45px; background-color:#d3ead9;" placeholder="Search Books" id="filter" autofocus onkeyup="validation('filter')" onkeydown="validation('filter')" onmouseout="validation('filter')">
																	<span class="input-group-addon"><i class="fa fa-search" style="width:30px"></i></span>
																	<ul class="list-group" id="result">
																	</ul>
																  </div>
															  </div>
															  <div class="col-md-4 col-sm-4 col-xs-4">
																  <button type="button" class="btn btn-primary" onclick="borrow()" style="height:45px "><i class="fa fa-plus-circle"></i> Add to list</button>
															  </div>
															<div class="col-md-12 col-sm-12 col-xs-12">
																<br>
															</div>
															<div class="col-md-6 col-sm-6 col-xs-6">
																<div class="panel panel-success">
																  <div class="panel-heading"><label><i class="glyphicon glyphicon-info-sign"></i> Book details</label></div>
																  <div class="panel-body">
																	  <table class="table table-striped" style="font-size:13px">
																		 <tr>
																			<th width="35%">Book Title:</th>
																			<td id="title"></td>
																		  </tr>
																		  <tr>
																			<th>Author Name:</th>
																			<td id="author"></td>
																		  </tr>
																		  <tr>
																			<th>No. of Pages:</th>
																			<td id="pages"></td>
																		  </tr>
																		  <tr>
																			<th>Copyright:</th>
																			<td id="copyright"></td>
																		  </tr>
																		  <tr>
																			<th>ISBN:</th>
																			<td id="isbn"></td>
																		  </tr>
																			<tr>
																			<th>Publisher:</th>
																			<td id="publisher"></td>
																		  </tr>
																		  <tr>
																			<th>Classification No:</th>
																			<td id="classification"></td>
																		  </tr>
																		 <tr>
																			<th>Quantity:</th>
																			<td id="quantity"></td>
																		  </tr>
																		  <tr>
																			<th>Remarks:</th>
																			<td id="remarks"></td>
																		  </tr>
																		</table>
																  </div>
																</div>
															</div>
															<div class="col-md-6 col-sm-6 col-xs-6">
																<div class="panel panel-success" >
																  <div class="panel-heading"><label><i class="fa fa-table"></i> List of copy</label></div>
																  <div class="panel-body">
																	  <div class="input-group hide" id="search2">
																		<input type="text" class="form-control" placeholder="Search Here.." id="filter2" style="background-color:#d3ead9" onkeyup="validation('filter2')" onkeydown="validation('filter2')" onmouseout="validation('filter2')">
																		<span class="input-group-addon"><i class="fa fa-search" style="width:30px"></i></span>
																	</div>
																	  <table class="table table-striped" style="font-size:14px">
																		<thead>
																		  <tr>
																			<th class="text-center">Checkbox</th>
																			  <th class="text-center">No</th>
																			<th class="text-center">Accession No</th>
																		  </tr>
																		</thead>
																		<tbody class="searchable2" id="accession_data">
																			<tr>
																				<td colspan="3" class="text-center">No records availble.</td>
																			</tr>
																		</tbody>
																	  </table>	

																  </div>
																</div>
															</div>
														</div>
													</div>
												 </div>
											</div>	
											<div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
												<div class="x_panel" >
												  <div class="x_title">
													  <img src="images/book_bags.png" width="50px" height="50px">
													<h3 style="margin-left:60px; margin-top:-40px">Book bags</h3>
													<div class="clearfix"></div>
												  </div>
												  <div class="x_content">
													 <div class="row">
														  <div class="col-md-12 col-sm-12 col-xs-12">
															<table class="table table-bordered table-striped jambo_table" style="background-color:white">
															  <thead>
																<tr>
																<th >No.</th>
																  <th>Accession no</th>
																  <th>Book Title</th>
																  <th>Author Name</th>
																  <th >No of pages</th>
																  <th width="50px">Option</th>
																</tr>
															  </thead>
															  <tbody id="data">
																<tr class="danger"><td colspan="7"><h3 class="text-center">No records available.</h3></td></tr>
															  </tbody>
															</table>
														  </div>
														 <div class="col-md-12 col-sm-12 col-xs-12">
															 <br>
															<div class="pull-right">
																<button type="button" class="btn btn-danger" onclick="remove_all()"><i class="glyphicon glyphicon-remove-sign"></i> Remove all
																</button>
																<button type="button" onclick="borrow_form()" class="btn btn-primary" ><i class="glyphicon glyphicon-log-in"></i> Borrow</button>
															 </div>
														  </div>


														<form id="borrowForm">
														  <div class="modal fade" id="myModal" role="dialog">
															<div class="modal-dialog">
															  <div class="modal-content">
																<div class="modal-header">
																  <button type="button" class="close" data-dismiss="modal">&times;</button>
																  <h4 class="modal-title" style="font-weight: bold;"><i class="fa fa-edit"></i> Borrow Form</h4>
																</div>
																<div class="modal-body">
																	<div class="row">
																		<div class="col-md-12 col-sm-12 col-xs-12">
																			<div class="col-md-12 col-sm-12 col-xs-12">
																				<center>
																			<img src="images/book_return.png" width="100px" height="100px">
																		</center>
																		<br>
																		<label class="col-md-12 col-sm-12 col-xs-12 form-group">Borrower: </label>
																		<div class="col-md-12 col-sm-12 col-xs-12 form-group">
																			<select class="form-control select2 has-feedback-left" required="" name="borrower" id="borrower" style="background-color:#e2e2e2; width:100%">
																				<?php
																					foreach($borrower as $value) {
																						$borrower_id = $value['borrower_id'];
																						echo "<option value='$borrower_id'>".$value['lastname'].", ".$value['firstname']."</option>";
																					}
																				?>
																			  </select>

																		</div>
																		<label class="col-md-12 col-sm-12 col-xs-12 form-group">Purpose: </label>
																		<div class="col-md-12 col-sm-12 col-xs-12 form-group">
																			<select class="form-control"  name="purpose" id="purpose" required style="width:100%; background-color:#e2e2e2;">
																				<option>Study</option>
																				<option>Borrow</option>
																			</select>
																		</div>
																		<label class="col-md-12 col-sm-12 col-xs-12 form-group">Date Borrowed: </label>
																		<div class="col-md-12 col-sm-12 col-xs-12 form-group">
																			<input type="text" class="form-control " name="date_borrowed" placeholder="Date Borrowed" id="single_cal4"  aria-describedby="inputSuccess2Status4" required />
																		</div>
																			</div>
																		</div>
																	<br>
																	</div>
																</div>
																<div class="modal-footer">
																  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
																	 <button type="submit" class="btn btn-primary"><i class="fa fa-save"> Save</i></button>
																</div>

																</div>
															  </div>
															</div>
													  </form>
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
        </div>
		 <?php include'include/js.php';?>
		<script>
			function validation(id) {
				var textfield = document.getElementById(id);
				var regex = /[^a-z 0-9 _ -.]/gi;
				var bad = [/fuck/g,/gago/g,/abno/g,/pesti/g,/bobo/g,];

				for (var list = 0; list < bad.length; list++) {

					textfield.value = textfield.value.replace(bad[list], "");
				}

				textfield.value = textfield.value.replace(regex, "");
			}
			
			$('.select2').select2();
			$("#filter2").keyup(function() {
				var search = $(this).val();
				var rex = new RegExp($(this).val(), 'i');
					$('.searchable2 tr').hide();
					$('.searchable2 tr').filter(function() {

						return rex.test($(this).text());
					}).show();
			});
			$("#filter").keyup(function() {
				var search = $(this).val();
				
				if(search != "") {
					$.ajax({
						url: "model/borrowed.php",
						dataType:'json',
						type: "POST",
						data:{ 
							action:"search",
							search: search
						},
						success: function(data) {
							console.log(data);
							listDetails(data);
						},
						error: function(){
							alert("error");
						}
					});
				}
				else {
					$(".link-class").hide();
				}
			});
			function  listDetails(data) {
				
				var list = data.data;
				var length = data.data.length;
				var html = "";
				
				if(length > 0) {
					
					for(var x = 0; x < length; x++) {
						html = html + '<li class="list-group-item link-class out" onclick=searchBook("'+list[x].book_id+'")><b>' +list[x].title + ' | ' + list[x].author+' | </b><span class="text-muted">' + list[x].isbn +'</span>' + '</li>';
					}
				}
				else {
					html = '<li class="list-group-item link-class")>No result.</li>';
				}
				
				$("#result").html(html);
			}
			function searchBook(book_id) {
				var jc = $.alert({
					title: 'Please wait...',
					draggable: false,
					icon: 'fa fa fa-hourglass-2',
					theme: 'bootstrap',
					 type: 'green',
					content: '<br><center><img src="images/loading.gif" width="100px" height=100px" style="opacity:0.5"></center><br>'
				});
				jc.open();
				$(".jconfirm-buttons").hide();
				$("#modal3").modal("hide");
				$("#filter").val("");
				$.ajax({
					url: "model/borrowed.php",
					dataType:'json',
					type: "POST",
					data:{ 
						action:"select_book",
						book_id: book_id
					},
					success: function(data) {
						jc.close();
						console.log(data);
						searchRecord(data);
					},
					error: function(){
						jc.close();
						alert("error");
					}
				});
			}
			
			function searchRecord(data) {
				var list = data.data;
				var length = data.data.length;
				var list2 = data.data2;
				var length2 = data.data2.length;
				var status = "";
				var html = "";
				var html2 = "";
				
				if(length > 0) {
						
					var quantity = list[0].qty_in - list[0].qty_out;
					if(quantity > 0) {
						status = "Available";
					}
					else {
						status = "Not Availbale";
					}
					$("#title").text(list[0].title);
					$("#author").text(list[0].author);
					$("#pages").text(list[0].pages);
					$("#copyright").text(list[0].copyright);
					$("#title").text(list[0].title);
					$("#isbn").text(list[0].isbn);
					$("#publisher").text(list[0].publisher);
					$("#classification").text(list[0].classification);
					$("#quantity").text(quantity);
					$("#remarks").text(status);
				}
				if(length2 > 0) {
					count = 0;
					$("#search2").removeClass("hide");
					
					for(var x = 0; x < length2; x++) {
						count++;
						html2 = html2 + 
						'<tr>' +
							'<td>' +
								'<center>' +
									'<input type="checkbox"  value="'+list2[x].account_no+'" class="flat i">' +
								'</center>' +
							'</td>' +
							'<td class="text-center">' + count + '</td>' +
							'<td class="text-center"> ' + list2[x].account_no + '</td>' +
						'</tr>';
					}
				}
				else {
					$("#search2").removeClass("hide");
					$("#search2").addClass("hide");
					html2 = '<tr><td colspan="3" class="text-center">No records availble.</td></tr>';
				}
				$("#accession_data").html(html2);
				$('.i').iCheck({
					checkboxClass: 'icheckbox_flat-green'
				  });
				$("#record").html(html);
				$(".link-class").hide();
			}
			
			function borrow() {
				
				var token =  "<?php echo $token; ?>";
				var id = [];
				$(':checkbox:checked').each(function(){
					var get_id = $(this).val();
					id.push(get_id);
				});
				var total = id.length;
				if($("#title").text() != "") {
					if(total > 0) {
						var jc = $.alert({
							title: 'Please wait...',
							draggable: false,
							icon: 'fa fa fa-hourglass-2',
							theme: 'bootstrap',
							 type: 'green',
							content: '<p><b>Avoid cancelation it can couse fatal error.</b></p><br><center><img src="images/loading.gif" width="100px" height=100px" style="opacity:0.5"></center><br>'
						});
						jc.open();
						$(".jconfirm-buttons").hide();
						$.ajax({
							url: "model/borrowed.php",
							type: "POST",
							data:{
								action: "select",
								id: id,
								borrowed_token: token
							},
							success: function(data) {
								console.log(data);
								if(data == "true") {
									jc.close();
									toastr.info("Book is successfully added in list.");
								}
								else {
									error("Book not save.")
								}
								$("#accession_data").html('<tr><td colspan="3" class="text-center">No records availble.</td></tr>');
								$("#title").text("");
								$("#author").text("");
								$("#pages").text("");
								$("#copyright").text("");
								$("#title").text("");
								$("#isbn").text("");
								$("#publisher").text("");
								$("#classification").text("");
								$("#quantity").text("");
								$("#remarks").text("");
								jc.close();
								display();
							},
							error: function(){
								jc.close();
								alert("error");
							}
						});
					}
					else {
						error("Select accession no first.");
					}
				}
				else {
					error("Search books first.");
				}
			};
			
			function display(){
				
				$.ajax({
					url: "model/borrowed.php",
					dataType:'json',
					type: "POST",
					data:{ action:"display" },
					success: function(data) {
						console.log(data);
						records(data);
					},
					error: function(){
						alert("error");
					}
				});
			}
			
			function records(data) {
				
				var list = data.list;
				var length = list.length;
				var count = 0;
				var html = "";
				
				for(var x = 0; x < length; x++) {
					count++;
					html = html + 
					'<tr>' +
						'<td>' + count + '</td>' +
						'<td> ' + list[x].account_no + '</td>' +
						'<td>' + list[x].title + '</td>' +
						'<td>' + list[x].author + '</td>' +
						'<td>' + list[x].pages + '</td>' +
						'<td>' +
							'<button type="button" onclick=cancel("'+list[x].temp_id+'") class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Cancel</button>' +
						'</td>' +
					'</tr>'
				}
				$("#total").html(count);
				if(length > 0) {
					$("#data").html(html);
				}
				else {
					$("#data").html('<tr class="danger"><td colspan="7"><h3 class="text-center">No records available.</h3></td></tr>');
				}
				$('.i').iCheck({
					checkboxClass: 'icheckbox_flat-green'
				  });
			}
			
			function cancel(temp_id) {
				var token =  "<?php echo $token; ?>";
				$.ajax({
					url: "model/borrowed.php",
					dataType:'json',
					type: "POST",
					data:{ 
						action:"cancel",
						temp_id: temp_id,
						borrowed_token: token
					},
					success: function(data) {
						console.log(data);
						if(data.data == true) {
							toastr.info("Book was successfully removed in list.");
						}
						else {
							error("Book not remove.")
						}
						display();
					},
					error: function(){
						alert("error");
					}
				});
			}
			function remove_all() {
				var token =  "<?php echo $token; ?>";
				$.ajax({
					url: "model/borrowed.php",
					dataType:'json',
					type: "POST",
					data:{ 
						action:"remove_all",
						borrowed_token: token
					},
					success: function(data) {
						console.log(data);
						if(data.data == true) {
							toastr.info("Book was successfully removed in list.");
						}
						else {
							error("Book not remove.")
						}
						display();
					},
					error: function(){
						alert("error");
					}
				});
			}
			
			function error(message) {
				swal({
					title: 'DepED Escalante',
					text: message,
					type : 'error',
					showConfirmButton: true,
					confirmButtonColor: "#DD6B55",
					timer: 2000
				});
			}
			
			function borrow_form() {
				$("#myModal").modal("show");
			}
			$("#borrowForm").on('submit',(function(e) {
				
				$("#myModal").modal("hide");
				var jc = $.alert({
					title: 'Please wait...',
					draggable: false,
					icon: 'fa fa fa-hourglass-2',
					theme: 'bootstrap',
					 type: 'green',
					content: '<p><b>Avoid cancelation it can couse fatal error.</b></p><center><br><img src="images/loading.gif" width="100px" height=100px" style="opacity:0.8"></center><br><br>'
				});
				jc.open();
				$(".jconfirm-buttons").hide();
				
				var token =  "<?php echo $token; ?>";
				e.preventDefault();
				$.ajax({
					url: "model/borrowed.php",
					dataType:'json',
					type: "POST",
					data:{ 
						action:"borrow",
						borrowed_token: token,
						borrower: $("#borrower").val(),
						purpose: $("#purpose").val(),
						date_borrowed: $("#single_cal4").val()
					},
					success: function(data) {
						console.log(data);
						if(data.data == true) {
							jc.close();
							toastr.info("Record are successfully added.");
						}
						else if(data.data == false) {
							jc.close();
							alert("Record are not saved.");
						}
						else {
							jc.close();
							alert("error");
						}
						display();
					},
					error: function(){
						jc.close();
						alert("error");
					}
				});
			
			}));
		</script>
    </body>
</html>
