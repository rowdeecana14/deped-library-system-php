<?php
	//defined('BASEPATH') OR exit('No direct script access allowed');
	require_once "model.php";
	include('../db_backup.php');
	session_start();
	
	if(isset($_POST['action'])) {
		
		$setting = new Model;
		$database = new Database;
		
		if($_POST['action'] == "display") {
			$sql = "SELECT * FROM tbl_settings";
			$data = $setting->displayRecord($sql);
			echo json_encode(array("data" =>$data));
		}
		else if($_POST['action'] == "updatebg") {
			if(!empty($_FILES['bg_image']['name'])) {

				$image = $_FILES['bg_image']['name'];
				$tmp_name = $_FILES['bg_image']['tmp_name'];
				$database->uploadImage($image, $tmp_name);
				$sql = "UPDATE tbl_settings SET bg_image='$image' WHERE id='1'";
				$result = $setting->updateRecord($sql);
				echo $result;
			}
			else {
				echo "false";
			}
		}
		else if($_POST['action'] == "updatelogo") {
			if(!empty($_FILES['logo']['name'])) {

				$image = $_FILES['logo']['name'];
				$tmp_name = $_FILES['logo']['tmp_name'];
				$database->uploadImage($image, $tmp_name);
				$sql = "UPDATE tbl_settings SET logo='$image' WHERE id='1'";
				$result = $setting->updateRecord($sql);
				echo $result;
			}
			else {
				echo "false";
			}
		}
		else if($_POST['action'] == "updatellogo") {
			if(!empty($_FILES['logo']['name'])) {

				$image = $_FILES['logo']['name'];
				$tmp_name = $_FILES['logo']['tmp_name'];
				$database->uploadImage($image, $tmp_name);
				$sql = "UPDATE tbl_settings SET left_logo='$image' WHERE id='1'";
				$result = $setting->updateRecord($sql);
				echo $result;
			}
			else {
				echo "false";
			}
		}
		else if($_POST['action'] == "updaterlogo") {
			if(!empty($_FILES['logo']['name'])) {

				$image = $_FILES['logo']['name'];
				$tmp_name = $_FILES['logo']['tmp_name'];
				$database->uploadImage($image, $tmp_name);
				$sql = "UPDATE tbl_settings SET right_logo='$image' WHERE id='1'";
				$result = $setting->updateRecord($sql);
				echo $result;
			}
			else {
				echo "false";
			}
		}
		else if($_POST['action'] == "updatename") {
			
			if(!empty($_POST['name'])) {
				
				$name = $_POST['name'];
				$system = $_POST['system'];
				$sql = "UPDATE tbl_settings SET school_name='$name', system_name='$system' WHERE id='1'";
				$result = $setting->updateRecord($sql);
				echo $result;
			}
			else {
				echo "false";
			}
		}
		else if($_POST['action'] == "updateheader") {
			$line1 = $_POST['line1'];
			$line2 = $_POST['line2'];
			$line3 = $_POST['line3'];
			$line4 = $_POST['line4'];
			$line5 = $_POST['line5'];
			$tel_no = $_POST['tel_no'];
			$telefax_no = $_POST['telefax_no'];
			$email = $_POST['email'];
			$web = $_POST['web'];
			$sql = "UPDATE tbl_settings SET line1='$line1', line2='$line2', line3='$line3', line4='$line4', line5='$line5', tel_no='$tel_no', telefax_no='$telefax_no', email='$email', web='$web' WHERE id='1'";
			$result = $setting->updateRecord($sql);
			echo $result;
		}
		else if($_POST['action'] == "default") {
			
			$sql = "UPDATE tbl_settings SET bg_image='bg.jpg' WHERE id='1'";
			$result = $setting->updateRecord($sql);
			echo $result;
		}
		else if($_POST['action'] == "reset") {
			/*$dbbackup = new db_backup;
			$data = $dbbackup->db_import("../../database/deped_library.sql");
			echo json_encode(array("data" =>$data));
			*/
			
			$file_data = file("../../database/deped_library.sql");
			$dbbackup = new db_backup;
			$data = $dbbackup->importDatabase($file_data);
			echo $data;
		}
		else if($_POST['action'] == "import") {
			if($_FILES['file']['name'] != "") {
				$array = explode(".", $_FILES['file']['name']);
				$ext = end($array);
				$filename=$_FILES["file"]["tmp_name"];	
				$message = "";
				if($ext == "sql" ) {
					$file_data = file($_FILES["file"]["tmp_name"]);
					$dbbackup = new db_backup;
					$data = $dbbackup->importDatabase($file_data);
					echo $data;
				}
				else {
				   echo "sql";
				}
			}
			else {
				echo "invalid";
			}
		}
	}
?>