<?php
	require_once "model/controller.php";
	require_once "model/model.php";
	date_default_timezone_set('Asia/Manila');

	$auth = new Database;
	$token = $auth->generateAuth();
	$_SESSION['bookadd_token'] = $token;
	$_SESSION['current_page'] = "books_form.php";
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
			table {
				font-size: 12px;
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
                <div class="right_col" role="main" >
                    <div class="">
                        <div class="clearfix"></div>
                        <div class="row">
							<div class="x_panel">
							  <div class="x_title">
								<img src="images/add_books.png" width="50px" height="50px">
								<h3 style="margin-left:60px; margin-top:-35px">Register books</h3>
								  <div class=" btn-group pull-right" style="margin-left:60px; margin-top:-45px">
									   <button class="btn btn-primary btn-sm"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Search Books" onclick="search()"><i class="fa fa-search"></i> Search</button>
										<a href="listof_books.php" class="btn btn-primary btn-sm"  data-toggle="tooltip" data-placement="top" title="" data-original-title="View List of Books"><i class="fa fa-table"></i> View</a>
								  </div>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content">
								  <form id="addBook">
									  <div class="col-md-6 col-sm-6 col-xs-6">
											<label class="col-md-12 col-sm-12 col-xs-12 form-group">ISBN: </label>
											<div class="col-md-12 col-sm-12 col-xs-12 form-group">
												<input type="hidden" name="action" value="add" />
												<input type="hidden" name="bookadd_token" value="<?php echo $token; ?>" />
												<input type="text" class="form-control has-feedback-left" name="isbn" id="isbn" placeholder="ISBN" required="" onkeyup="validation('isbn')" onkeydown="validation('isbn')" onmouseout="validation('isbn')" />
												<span class="fa fa-barcode blue form-control-feedback left" aria-hidden="true" style="color:black"></span>
											</div>
											<label class="col-md-12 col-sm-12 col-xs-12 form-group"> Book Title: </label>
											<div class="col-md-12 col-sm-12 col-xs-12 form-group">
												 <textarea rows="2" required="required" class="form-control" name="title" id="title" style="text-transform:capitalize; background-color:#e2e2e2;" placeholder="Book Description" onkeyup="validation('title')" onkeydown="validation('title')" onmouseout="validation('title')"></textarea>

											</div>

											<label class="col-md-12 col-sm-12 col-xs-12 form-group">Author Name: </label>
											<div class="col-md-12 col-sm-12 col-xs-12 form-group">
												<input list="authors" class="form-control has-feedback-left" name="author" id="author" placeholder="Author Name" style="text-transform:capitalize; background-color:#e2e2e2;" required="" onkeyup="validation('author')" onkeydown="validation('author')" onmouseout="validation('author')" />
												<span class="fa fa-user form-control-feedback left" aria-hidden="true" style="color:black"></span>
												<datalist id="authors" class="form-group">
												</datalist>
											</div>
											<label class="col-md-12 col-sm-12 col-xs-12 form-group">Copyright: </label>
											<div class="col-md-12 col-sm-12 col-xs-12 form-group">
												<input type="text" class="form-control has-feedback-left" name="copyright" id="copyright"  placeholder="Copyright" required="" onkeyup="validation('copyright')" onkeydown="validation('copyright')" onmouseout="validation('copyright')" />
												<span class="fa fa-google-wallet blue form-control-feedback left" aria-hidden="true" style="color:black"></span>
											</div>
											<label class="col-md-12 col-sm-12 col-xs-12 form-group">Source of fund: </label>
											<div class="col-md-12 col-sm-12 col-xs-12 form-group">
												<input type="text" class="form-control has-feedback-left" name="fund" id="fund" placeholder="Source of fund" required="" onkeyup="validation('fund')" onkeydown="validation('fund')" onmouseout="validation('fund')" />
												<span class="fa fa-briefcase blue form-control-feedback left" aria-hidden="true" style="color:black"></span>
											</div>
										</div>
									  	<div class="col-md-6 col-sm-6 col-xs-6">
											<label class="col-md-12 col-sm-12 col-xs-12 form-group">Date Received: </label>
											<div class="col-md-12 col-sm-12 col-xs-12 form-group">
												<input type="text" class="form-control  has-feedback-left" name="date_received" placeholder="Date Received" id="single_cal4"  aria-describedby="inputSuccess2Status4" required />
												<span class="fa fa-calendar blue form-control-feedback left" aria-hidden="true" style="color:black"></span>
											</div>
											<label class="col-md-12 col-sm-12 col-xs-12 form-group">Publisher: </label>
											<div class="col-md-12 col-sm-12 col-xs-12 form-group">
												<input type="text" class="form-control has-feedback-left" name="publisher" id="publisher"  placeholder="Publisher" required="" onkeyup="validation('publisher')" onkeydown="validation('publisher')" onmouseout="validation('publisher')" />
												<span class="fa fa-building-o blue form-control-feedback left" aria-hidden="true" style="color:black"></span>
											</div>
											<label class="col-md-12 col-sm-12 col-xs-12 form-group">Classification Number: </label>
											<div class="col-md-12 col-sm-12 col-xs-12 form-group">
												<select class="form-control select2 has-feedback-left"  name="classification" id="classification" style="width:100%; background-color:#e2e2e2;">
													<option>000-099</option>
													<option>100-199</option>
													<option>200-299</option>
													<option>300-399</option>
													<option>400-499</option>
													<option>500-599</option>
													<option>600-699</option>
													<option>700-799</option>
													<option>800-899</option>
													<option>900-999</option>
												</select>
												<span class="fa fa-sitemap blue form-control-feedback left" aria-hidden="true" style="color:black"></span>
											</div>
											<label class="col-md-12 col-sm-12 col-xs-12 form-group">No of pages: </label>
											<div class="col-md-12 col-sm-12 col-xs-12 form-group">
												<input type="number" class="form-control has-feedback-left" name="pages" id="pages"  placeholder="No of pages" required="" onkeyup="validation_two('pages')" onkeydown="validation_two('pages')" onmouseout="validation_two('pages')" />
												<span class="fa fa-files-o blue form-control-feedback left" aria-hidden="true" style="color:black"></span>
											</div>
											
											<label class="col-md-12 col-sm-12 col-xs-12 form-group">No of copy: </label>
											<div class="col-md-12 col-sm-12 col-xs-12 form-group">
												<input type="number" class="form-control has-feedback-left" name="qty_in" id="qty_in" min="1" max="100"  placeholder="No of copy" required="" onkeyup="validation_two('qty_in')" onkeydown="validation_two('qty_in')" onmouseout="validation_two('qty_in')" />
												<span class="fa fa-sort-numeric-asc blue form-control-feedback left" aria-hidden="true" style="color:black"></span>
											</div>
										</div>
									  <div class="col-md-12 col-sm-12 col-xs-12">
										  <div class="col-md-6 col-sm-6 col-xs-6">
										  </div>
										  <div class="col-md-6 col-sm-6 col-xs-6">
												<button type="reset" class="btn btn-default pulse" style="font-size:17px; font-weight:bold; margin-left:10px; background-color: #f3b4b4; border: 2px solid #f98e8e" id="remove"><img src="images/removebook.png" width="50px" >Cancel
												</button>
												<button type="submit" class="btn btn-default pulse" style="font-size:17px; font-weight:bold; background-color:#7096d8; border: 2px solid #3667bd"><img src="images/addbook.png" width="50px">Add Book</button>
										  </div>
									  </div>
								  </form>
								  <form id="searchForm">
									  <div class="modal fade" id="myModal" role="dialog">
										<div class="modal-dialog">
										  <div class="modal-content">
											<div class="modal-header">
											  <button type="button" class="close" data-dismiss="modal">&times;</button>
											  <h4 class="modal-title" style="font-weight: bold;"><i class="fa fa-book"></i> Search Book</h4>
											</div>
											<div class="modal-body">
												<div class="row">
													<div class="col-md-12 col-sm-12 col-xs-12">
														<div class="col-md-12 col-sm-12 col-xs-12">
															<center>
																<img src="images/search_book.png" width="100px" height="100px">
															</center>
															<br>
															<div class="col-md-12 col-sm-12 col-xs-12 form-group" >
																<input type="text" class="form-control" name="search" id="search" placeholder="Search Books" value="" onkeyup="validation('search')" onkeydown="validation('search')" onmouseout="validation('search')" required />
															</div>
														</div>
													</div>
												<br>
												</div>
											</div>
											<div class="modal-footer">
											  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
												 <button type="submit" class="btn btn-primary"><i class="fa fa-search"> Search</i></button>
											</div>

											</div>
										  </div>
										</div>
								  </form>
								  <form id="selectForm">
									  <div class="modal fade" id="myModal2" role="dialog">
										<div class="modal-dialog modal-lg">
										  <div class="modal-content">
											<div class="modal-header">
											  <button type="button" class="close" data-dismiss="modal">&times;</button>
											  <h4 class="modal-title" style="font-weight: bold;"><i class="fa fa-book"></i> List of Book</h4>
											</div>
											<div class="modal-body">
												<div class="row">
													<div class="col-md-12">
														 <table class="table table-bordered table-striped jambo_table">
														  <thead>
															<tr>
															  <th>ISBN</th>
															  <th>Book Title</th>
															  <th>Author Name</th>
															  <th>Publisher</th>
															  <th width="150px">Option</th>
															</tr>
														  </thead>
														  <tbody class="searchable" id="data">

														  </tbody>
													</table>
													</div>
												</div>
											</div>
											<div class="modal-footer">
											  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
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
			function validation_two(id) {

				var textfield = document.getElementById(id);
				var regex = /[^0-9]/gi;
				textfield.value = textfield.value.replace(regex, "");
			}
			$("#addBook").on('submit',(function(e) {

				e.preventDefault();
				var jc = $.alert({
					title: 'Please wait...',
					draggable: false,
					icon: 'fa fa fa-hourglass-2',
					theme: 'bootstrap',
					 type: 'green',
					content: '<p><b>Avoid cancelation it can couse fatal error.</p></b><center><br><img src="images/loading.gif" width="100px" height=100px" style="opacity:0.8"></center><br>'
				});
				jc.open();
				$(".jconfirm-buttons").hide();
				$.ajax({
					url: "model/books.php",
					type: "POST",
					data: new FormData(this),
					contentType: false,
					cache: false,
					processData:false,
					success: function(data){
						console.log(data);
						if(data == "true") {
							toastr.info("New books are successfully added.");
							$("#addBook")[0].reset();
							$("#isbn").attr("readonly", false);
							$("#title").attr("readonly", false);
							$("#author").attr("readonly", false);
							$("#pages").attr("readonly", false);
							$("#fund").attr("readonly", false);
							$("#copyright").attr("readonly", false);
							$("#publisher").attr("readonly", false);
							jc.close();
						}
						else {
							alert("Book are not added.");
							jc.close();
						}
					},
					error: function(){
						alert("error");
						jc.close();
					}
				});

			}));
			function search() {
				$("#myModal").modal("show");
			}
			$("#searchForm").on('submit',(function(e) {

				e.preventDefault();
				$.ajax({
					url: "model/books.php",
					dataType:'json',
					type: "POST",
					data:{ 
						action:"search",
						search: $("#search").val()
					},
					success: function(data) {
						console.log(data);
						searchRecord(data);
					},
					error: function(){
						alert("error");
					}
				});

				$("#myModal").modal("hide");
				$("#myModal2").modal("show");
			}));
			function searchRecord(data) {
				var list = data.data;
				var length = list.length;
				var count = 0;
				var html = "";

				for(var x = 0; x < length; x++) {

					count++;
					var book_id = list[x].book_id;
					html = html +
					'<tr>' +
						'<td> ' + list[x].isbn + '</td>' +
						'<td>' + list[x].title + '</td>' +
						'<td>' + list[x].author + '</td>' +
						'<td>' + list[x].publisher + '</td>' +
						'<td><button type="button" class="btn btn-primary" onclick=selectBook("'+book_id+'")><i class="fa fa-hand-o-up"></i> Select</button></td>' +
					'</tr>';
				}

				if(length > 0) {
					$("#data").html(html);
				}
				else {
					$("#data").html('<tr class="danger"><td colspan="6"><h3 class="text-center">No records available.</h3></td></tr>');
				}
			}
			function selectBook(book_id) {
				$.ajax({
					url: "model/books.php",
					dataType:'json',
					type: "POST",
					data:{ 
						action:"select",
						book_id: book_id
					},
					success: function(data) {
						console.log(data);
						bookDetails(data);
					},
					error: function(){
						alert("error");
					}
				});
			}
			function bookDetails(data) {
				var list = data.data;
				$("#isbn").val(list[0].isbn);
				$("#isbn").attr("readonly", true);

				$("#title").val(list[0].title);
				$("#title").attr("readonly", true);

				$("#author").val(list[0].author);
				$("#author").attr("readonly", true);

				$("#pages").val(list[0].pages);
				$("#pages").attr("readonly", true);

				$("#fund").val(list[0].fund);
				$("#fund").attr("readonly", true);

				$("#copyright").val(list[0].copyright);
				$("#copyright").attr("readonly", true);

				$("#publisher").val(list[0].publisher);
				$("#publisher").attr("readonly", true);


				$("#myModal2").modal("hide");
			}

			function display() {

			}
		/*
			$("#importForm").on('submit',(function(e) {
				e.preventDefault();
				$.ajax({
					url: "model/books.php",
					type: "POST",
					data: new FormData(this),
					contentType: false,
					cache: false,
					processData:false,
					success: function(result){
						console.log(result);

						if(result == "true") {
							toastr.info('', 'Records are successfully imported.');
						}
						else if(result == "no_record") {
							swal({
								title: 'DepED Escalante',
								text: "Files are empty",
								type : 'error',
								showConfirmButton: false,
								timer: 2000
							});
						}
						else {
							swal({
								title: 'DepED Escalante',
								text: "Error.",
								type : 'error',
								showConfirmButton: false,
								timer: 2000
							});
						}
						display();
						$("#import").modal("hide");
					}
				});
			}));
			*/
		</script>
    </body>
</html>
