<?php
	
	session_start();
	require_once "model.php";
	$model = new Model;
	$login = new Login;
	$database = new Database;
	$token = $database->generateAuth();
	date_default_timezone_set('Asia/Manila');
	$date =  date("Y-m-d");
    $time = date("h:i:s");

	if (isset($_POST['action']) && !empty($_POST['action'])) 
    { 
        if($_POST['action'] == "login" && $_POST['login_token'] == $_SESSION['login_token']) {

        	if($_POST['username'] != "" && $_POST['password'] != "") {
				
				$data = array();
				$password = md5($_POST['password']);
                $row1 = $login->validateUsername($_POST['username']);
				$user_id = "";
                $row2 = $login->validateUserPass($_POST['username'], $password);
                $row3 = $login->validateStatus($_POST['username'], $password, "Active");
				$data = $login->userDetail($_POST['username'], $password, "Active");
				
				if($row1 > 0) {
					
					if($row2 > 0) {
						
						if($row3 > 0) {
							
							$action = "Login";
							$user_id = $data[0]['user_id'];
							$_SESSION['user_id'] = $data[0]['user_id'];
							$_SESSION['controller'] = $token;
							
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
							$result = $login->updateRecord("UPDATE tbl_user SET token='$token' WHERE user_id='$user_id'");
							$data = array('success' =>true,'' =>'', 'link' =>'production/dashboard.php');
						}
						else {
							$action = "Trying to login with inactive account.(Username: ".$_POST['username'].")";
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
                            $data = array('success' =>false,'message' =>'Your account is inactive.', 'link' =>'');
						}
					}
					else {
						$action = "Trying to login with incorrect password.(Username: ".$_POST['username'].")";
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
                        $data = array('success' =>false,'message' =>'Incorrect password.', 'link' =>'');
					}
				}
				else {
					$action = "Trying to login with unrecognize account.(Username: ".$_POST['username'].")";
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
                    $data = array('success' =>false,'message' =>'Account not exist.', 'link' =>'');
				}
				
                echo json_encode($data);
        	}
        }
    }
   
?>