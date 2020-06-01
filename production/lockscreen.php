<?php
	require_once "model/controller_lock.php";
	require_once "model/model.php";
	if(isset($_GET['lock'])) {
		$_SESSION['lock'] = "true";
	}
	else {
		if(empty($_SESSION['lock'])) {
			header("location:dashboard.php");
		}
	}
	if(isset($_SERVER['REQUEST_METHOD'])) {
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			if(isset($_POST['login'])) {
				$user_id = $_SESSION['user_id'];
				$password = $_POST['password'];
				unlock($user_id, $password);
			}
		}
	}
	function unlock($user_id, $password) {
		$model = new Model;
		$password = md5($password);
		$data = $model->displayRecord("SELECT * FROM tbl_user WHERE user_id='$user_id'");
		$db_password = $data[0]['password'];
		if($db_password == $password) {
			$link = $_SESSION['current_page'];
			unset($_SESSION['lock']);
			header("location: $link");
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>E-Library</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	 <link rel="icon" href="images/logo.png"/>
	<link href="../vendors/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
    <link href="../vendors/font-awesome/css/font-awesome.css" rel="stylesheet">
  <link rel="stylesheet" href="../vendors/AdminLTE.css">
</head>
<body class="hold-transition lockscreen" style="background-image: url(images_uploaded/wall_1.jpg); background-repeat: no-repeat; background-size: cover;">
<form method="post">
<div class="lockscreen-wrapper" style="background-color:rgba(0, 0, 0, 0.2); padding-top:30px; padding-bottom:30px; padding-left:20px; padding-right:20px">
  <div class="lockscreen-logo">
	  <img src="images_uploaded/<?php echo $record[0]['image']; ?>" style="width: 130px; height: 130px; padding: 2px; border: 2px solid #4181ea; border-radius: 50%;">
  </div>
  <div class="lockscreen-name"><h3><?php echo $record[0]['firstname']." ".$record[0]['lastname']; ?></h3><br></div>
  <div class="lockscreen-item">
    <div class="lockscreen-image">
      <img src="images/password.png" alt="User Image">
    </div>
    <div class="lockscreen-credentials" >
      <div class="input-group">
        <input type="password" class="form-control" placeholder="password" name="password" id="password" style="font-size:18px" autofocus onkeyup="validation('password')" onkeydown="validation('password')" onmouseout="validation('password')">
        <div class="input-group-btn">
          <button type="button" class="btn"><i class="fa fa-arrow-right text-muted"></i></button>
        </div>
      </div>
    </div>
  </div>
  <div class="help-block text-center" style="color:black">
    Enter your password to retrieve your session
  </div>
	<?php
		if(isset($_POST['login'])) {
			echo '<div class="alert alert-danger"><i class="fa fa-close"></i> Wrong password...</div>';
		}
	?>
  <div class="text-center">
	  <a href="model/logout.php"  class="btn btn-danger"><i class="glyphicon glyphicon-log-out"></i> Logout</a>
	  <button type="submit" class="btn btn-primary" name="login"><i class="fa fa-key"></i> Login</button>
  </div>
  <div class="lockscreen-footer text-center">
	 
  </div>
</div>
	</form>
	<script src="../vendors/jquery/dist/jquery.min.js" type="text/javascript"></script>
	<script src="../vendors/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
	<script type="text/javascript">
		function validation(id) {
			var textfield = document.getElementById(id);
			var regex = /[^a-z 0-9 _ -]/gi;
			var bad = [/fuck/g,/gago/g,/abno/g,/pesti/g,/bobo/g,];

			for (var list = 0; list < bad.length; list++) {

				textfield.value = textfield.value.replace(bad[list], "");
			}

			textfield.value = textfield.value.replace(regex, "");
		}
	</script>
</body>
</html>
