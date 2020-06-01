

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
	 <style>
		 .box_shadow {
                box-shadow: 0 6px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 10px 0 #f5f5f5;
            }
	 </style>
  </head>
   <body style="background-color:#e2e2e2; background-image: url(production/images/wall_1.jpg); background-repeat: no-repeat; background-size: cover; " >
 <div class="container-fluid">
 	<div class="row">
		<nav class="navbar navbar-inverse">
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
        <li class="active"><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
         <li><a href="catalog.php"><i class="fa fa-table"></i> Catalog</a></li>
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
 <div class="container-fluid"  style="width:90%; margin-top:-20px; " >
	 <div class="row box_shadow" style="background-color: rgba(222, 220, 234, 0.2); padding-top:30px; height:700px" >
 	 <center>
		<div class="form-group">
			<h4 style="font-style:Sans; font-size:30px; color: black; text-shadow: 1px 1px 20px #f37f7f;"><b>LIBRARY MANAGEMENT & INFORMATION SYSTEM</b></h4>
		</div>
		 <br><br><br>
		 <br><br><br>
	</center>
	 
	  <div class="col-lg-4 col-md-4 col-sm-4">
		<center>
			<img id="default_logo" src="production/images_uploaded/right_logo.png" width="180px" style="margin-top:-50px"> 
			<p style="font-size: 25px; font-weight:bold;" id="date"></p>
			<p style="font-size: 18px; font-weight:bold;" id="timer"></p>
		</center>
	</div>
	  <div class="col-lg-8 col-md-8 col-sm-8">
		  <img src="production/images/b.jpg" height="350px" width="700px" style="margin-top:-80px; margin-left:-30px; border: 2px solid #555; opacity:0.6">
	 </div>
	 </div>
 </div>
	 <script src="vendors/jquery/dist/jquery.min.js" type="text/javascript"></script>
	<script src="vendors/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
	 <script src="production/include/js/toastr/build/toastr.min.js" type="text/javascript"></script>
	 <script src="production/include/js/sweetalert-master/dist/sweetalert-dev.js" type="text/javascript"></script>
	<script>
		var d = new Date();
		document.getElementById("date").innerHTML = d.toDateString();

		var myVar = setInterval(myTimer, 1000);

		function myTimer() {
			var d = new Date();
			document.getElementById("timer").innerHTML = d.toLocaleTimeString();
		}
	   </script>
  </body>
</html>
