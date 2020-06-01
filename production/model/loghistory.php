<?php
	
	require_once "model.php";
	$logs = new Model;
	$database = new Database;

	if (isset($_POST['action']) && !empty($_POST['action'])) 
    { 
		if($_POST['action'] == "display") {
			
			$data = array();
			$sql = "SELECT * FROM tbl_userlogs ORDER BY date, time DESC";
			$result = $logs->displayRecord($sql);
			
			foreach($result as $value) {
				
				if($value['user_id'] == "") {
					
					$fullname = "Unknown";
					$date=date('M d, Y',strtotime($value['date']));
					$time=date('h:i A',strtotime($value['time']));
					$list = array("image" =>"", "fullname" =>$fullname, "action" =>$value['action'], "date" =>$date, "time" =>$time);
					array_push($data, $list);
				}
				else {
					$getdata = userDetails($value['user_id']);
					$image = "images_uploaded/".$getdata[0]['image'];
					$fullname = $getdata[0]['firstname']." ".$getdata[0]['lastname'];
					$date=date('M d, Y',strtotime($value['date']));
					$time=date('h:i A',strtotime($value['time']));
					$list = array("image" =>$image, "fullname" =>$fullname, "action" =>$value['action'], "date" =>$date, "time" =>$time);
					array_push($data, $list);
				}
			}
			echo json_encode(array("data" =>$data));
		}
	}

	function userDetails($user_id) {
		
		$logs = new Model;
		$database = new Database;
		$data = array();
		$sql = "SELECT * FROM tbl_employee WHERE user_id='$user_id'";
		$data = $logs->displayRecord($sql);
		return $data;
	}
?>