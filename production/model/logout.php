<?php
    session_start();
	require_once "model.php";
	$model = new Model;
	$login = new Login;
	$database = new Database;
	date_default_timezone_set('Asia/Manila');

	if(!empty($_SESSION['user_id'])) {
		$user_id = $_SESSION['user_id'];
		$date =  date("Y-m-d");
		$time = date("h:i:s");
		$action = "Logout";
		$sql = "SELECT MAX(log_id) as log_id FROM tbl_userlogs";
		$data_logs = $model->searchRecord($sql);
		$id = $data_logs[0]['log_id'];
		if($id == null) {
			$id = 1;
		}
		else {
			$id = $id + 1;
		}
		$login->addUserLogs("INSERT INTO tbl_userlogs SET log_id=$id, action='$action', date='$date', time='$time', user_id='$user_id'");
		unset ($_SESSION['conroller']);
		unset ($_SESSION['user_id']);
		unset ($_SESSION['lock']);
		unset ($_SESSION['current_page']);
		unset ($_SESSION['pass_token']);
		unset ($_SESSION['array']);
		unset ($_SESSION['array2']);
		unset ($_SESSION['array3']);
		unset ($_SESSION['book_id']);
		header("location: ../../home.php");
	}
	else {
		header("location: ../../home.php");
	}
?>
