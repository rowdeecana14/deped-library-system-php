
<?php
	require_once "production/model/model.php";
	$sql = "SELECT * FROM tbl_books";
	$book = new Model;
	$data2 = $book->displayRecord($sql);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="production/images/logo.png"/>
  	<title>E-Library</title>
    <link href="vendors/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
    <link href="vendors/font-awesome/css/font-awesome.css" rel="stylesheet">
	<link href="vendors/custom.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="production/include/js/sweetalert-master/dist/sweetalert.css">
	<link href="production/include/js/toastr/build/toastr.min.css" rel="stylesheet" type="text/css">
	  <link rel="stylesheet" type="text/css" href="production/include/js/confirmation/jquery-confirm.css">
  <style>
	 .profile_view {
			border: 1px solid #94979c;
		}
		.profile_view:hover {

			background-color: #d3ead9;
		}
	  body {
		  font-size: 13px;
	  }
	  #result {
			margin-top: 45px;
			margin-left: -100%;
			position: absolute;
			width: 100%;
			max-width: 800px;
			cursor: pointer;
			overflow-y: auto;
			max-height: 200px;
			box-sizing: border-box;
			z-index: 1001;
		}
		.link-class:hover {
			background-color: #d3ead9;
		}
	  .box_shadow {
                box-shadow: 0 6px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 10px 0 #f5f5f5;
            }
  </style>
  </head>
   <body style="background-color:#e2e2e2; background-image: url(production/images/wall_1.jpg); background-repeat: no-repeat; background-size: cover;">
 <div class="container-fluid" >
 	<div class="row" >
		<nav class="navbar navbar-inverse" >
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="home.php"  style="margin-left:40px; color: white">E-Library System</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
         <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
         <li class="active"><a href="catalog.php"><i class="fa fa-table"></i> Catalog</a></li>
		   <li><a href="registration.php"><i class="fa fa-edit"></i> Registration</a></li>
      </ul>
      <ul class="nav navbar-nav ">
        <li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
      </ul>
    </div>
  </div>
</nav>
	</div>
 </div>
 <div class="container-fluid" style="width:90%; margin-top:-20px" >
 	<div class="row " style="">
		<div class="panel panel-default" style="background-color: rgba(222, 220, 234, 0.9); padding-bottom:100px">
		  <div class="panel-heading"> <img src="production/images/book_list.png" width="50px" height="50px">
			<h3 style="margin-left:60px; margin-top:-40px; color: black; text-shadow: 1px 1px 20px #f37f7f;">Book Catalog</h3></div>
		  <div class="panel-body">
			  <div class="col-md-12 col-sm-12 col-xs-12" >
			
		</div>
		<div class="col-md-12 col-sm-12 col-xs-12" >
			<div class="col-md-9 col-sm-9 col-xs-9">
			<br>
			  <form id="searchForm">
				<div class="input-group ">
					<input type="text" class="form-control" style="height:45px;" placeholder="Search Books" id="filter" onkeyup="validation('filter')" onkeydown="validation('filter')" onmouseout="validation('filter')">
					<span class="input-group-addon"><i class="fa fa-search" style="width:30px"></i></span>
					<ul class="list-group" id="result">
					</ul>
				 </div>
			</form>
		</div>
		</div>
		<div class="col-md-3 col-sm-3 col-xs-3"></div>
		 <div class="col-md-12 col-sm-12 col-xs-12" style="padding-bottom:40px">
			 <br><br>
			<div class="col-md-6 col-sm-6 col-xs-6">
				<div class="panel panel-success">
				  <div class="panel-heading">
					  <label><i class="glyphicon glyphicon-info-sign"></i> Book details</label>
					</div>
				  <div class="panel-body">
					  <table class="table table-striped" style="font-size:14px">
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
					<div class="panel panel-success">
				  <div class="panel-heading">
					  <label><i class="fa fa-table"></i> List of copy</label>
					</div>
				  <div class="panel-body">
					  <div class="input-group hide" id="search2">
						<input type="text" class="form-control" placeholder="Search Here.." id="filter2" onkeyup="validation('filter2')" onkeydown="validation('filter2')" onmouseout="validation('filter2')" style="background-color:#d3ead9">
						<span class="input-group-addon"><i class="fa fa-search" style="width:30px"></i></span>
					</div>
					  <table class="table table-striped" style="font-size:14px">
						<thead>
						  <tr>
							  <th class="text-center">No</th>
							<th class="text-center">Accession No</th>
						  </tr>
						</thead>
						<tbody class="searchable2" id="accession_data">
							<tr>
								<td colspan="2" class="text-center">No records availble.</td>
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
 </div>
	   
	<script src="vendors/jquery/dist/jquery.min.js" type="text/javascript"></script>
	<script src="vendors/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
   <script src="production/include/js/toastr/build/toastr.min.js" type="text/javascript"></script>
   <script src="production/include/js/sweetalert-master/dist/sweetalert-dev.js" type="text/javascript"></script>
	<script src="production/include/js/confirmation/jquery-confirm.js"></script>
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
	   function error(message) {
			swal({
				title: 'DepED Escalante',
				text: message,
				type : 'error',
				showConfirmButton: false,
				timer: 2000
			});
		}
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
					url: "production/model/borrowed.php",
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
					content: '<center><br><img src="production/images/loading.gif" width="100px" height=100px" style="opacity:0.8"></center><br><br>'
				});
			jc.open();
			$(".jconfirm-buttons").hide();
		   	$("#filter").val("");
			$.ajax({
				url: "production/model/borrowed.php",
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
					alert("error");
					jc.close();
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
				var remarks = "";
				if(quantity > 0) {
					remarks = "Available";
				}
				else {
					remarks = "Not Availbale";
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
				$("#remarks").text(remarks);
			}
			if(length2 > 0) {
				count = 0;
				$("#search2").removeClass("hide");

				for(var x = 0; x < length2; x++) {
					count++;
					html2 = html2 + 
					'<tr>' +
						'<td class="text-center">' + count + '</td>' +
						'<td class="text-center"> ' + list2[x].account_no + '</td>' +
					'</tr>';
				}
			}
			else {
				$("#search2").removeClass("hide");
				$("#search2").addClass("hide");
				html2 = '<tr class="danger"><td colspan="3" class="text-center">No records availble.</td></tr>';
			}
			$("#accession_data").html(html2);
			$("#record").html(html);
			$(".link-class").hide();
		}
   </script>
  </body>
</html>
