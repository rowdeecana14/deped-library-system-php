<?php
	
	require_once "model.php";
	session_start();
	$database = new Database;
	$model = new Model;
	$controller = new Controller;
	$record= array();
		
	if(!isset($_SESSION['controller'])) {
			
		header("location:../home.php");
	}
	else {
		if(!empty($_SESSION['lock'])) {
			if($_SESSION['lock'] == "true") {
				//echo "<script>alert('s');</script>";
				header("location:lockscreen.php");
			}
		}
		
		$sql = "SELECT * FROM tbl_settings";
		$data = $model->displayRecord($sql);
		
		$num_rows = $controller->checkUser($_SESSION['user_id'], $_SESSION['controller']);
		$record  = $controller->userDetails($_SESSION['user_id']);
		
		if($num_rows == 0) {
			header("location:../home.php");
		}
	}

?>