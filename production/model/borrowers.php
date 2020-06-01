<?php
	//defined('BASEPATH') OR exit('No direct script access allowed');
	require_once "model.php";
	session_start();
	date_default_timezone_set('Asia/Manila');
	$date =  date("Y-m-d");
    $time = date("h:i:s");
	
	
	if(isset($_POST['action'])) {
		
		$borrower = new Model;
		$database = new Database;
		
		if($_POST['action'] == "display") {
			
			$sql = "SELECT * FROM tbl_borrowers WHERE approval='Yes' ORDER BY lastname";
			$result = $borrower->displayRecord($sql);
			$sql = "SELECT * FROM tbl_borrowers WHERE approval='Request' ORDER BY lastname";
			$result2 = $borrower->displayRecord($sql);
			$sql2 = "SELECT firstname FROM tbl_borrowers ORDER BY firstname";
			$firstname = $borrower->displayRecord($sql2);
			$sql3 = "SELECT lastname FROM tbl_borrowers ORDER BY lastname";
			$lastname = $borrower->displayRecord($sql3);
			$sql4 = "SELECT position FROM tbl_borrowers ORDER BY position";
			$position = $borrower->displayRecord($sql4);
			
			echo json_encode(array("data" =>$result, "data2" =>$result2, "fname" =>$firstname, "lname" =>$lastname, "lposition" =>$position));
		}
		else if($_POST['action'] == "request") {
		
			if($_POST['request_token'] == $_SESSION['request_token']) {
				$employee_no = $_POST['employee_no'];
				$firstname = $_POST['firstname'];
				$lastname = $_POST['lastname'];
				$gender = $_POST['gender'];
				$position = $_POST['position'];
				$schoolname = $_POST['schoolname'];
				$contactno = $_POST['contactno'];
				$date_created = date("Y-m-d");
				$status = "Inactive";
				$approval = "Request";
				// Check Data if set and not empty

				//Convert Capitalize of the Word
				$firstname = $database->convertData($firstname);
				$lastname = $database->convertData($lastname);
				$position = $database->convertData($position);
				$schoolname = $database->convertData($schoolname);

				//Sanitize User Input
				$employee_no = $database->cleanData($employee_no);
				$firstname = $database->cleanData($firstname);
				$lastname = $database->cleanData($lastname);
				$gender = $database->cleanData($gender);
				$position = $database->cleanData($position);
				$schoolname = $database->cleanData($schoolname);
				$contactno = $database->cleanData($contactno);


				$sql = "INSERT INTO tbl_borrowers (borrower_id, firstname, lastname, gender, position, contactno, schoolname, status, date_created, approval) VALUES('$employee_no', '$firstname', '$lastname', '$gender', '$position', '$contactno', '$schoolname', '$status', '$date_created','$approval')";
				$result = $borrower->addRecord($sql);
				
				
				echo $result;
			}
		}
		else if($_POST['action'] == "approved") {
			$borrower_id = $_POST['borrower_id'];
			$status = "Active";
			$approval = "Yes";
			$sql = "UPDATE tbl_borrowers SET status='$status', approval='$approval' WHERE borrower_id='$borrower_id'";
			$result = $borrower->updateRecord($sql);
			echo $result;
		}
		else if($_POST['action'] == "add") {
		
			if($_POST['borroweradd_token'] == $_SESSION['borroweradd_token']) {
				$user_id = $_SESSION['user_id'];
				$employee_no = $_POST['employee_no'];
				$firstname = $_POST['firstname'];
				$lastname = $_POST['lastname'];
				$gender = $_POST['gender'];
				$position = $_POST['position'];
				$schoolname = $_POST['schoolname'];
				$contactno = $_POST['contactno'];
				$date_created = date("Y-m-d");
				$status = "Active";
				$approval = "Yes";
				
				// Check Data if set and not empty

				//Convert Capitalize of the Word
				$firstname = $database->convertData($firstname);
				$lastname = $database->convertData($lastname);
				$position = $database->convertData($position);
				$schoolname = $database->convertData($schoolname);

				//Sanitize User Input
				$employee_no = $database->cleanData($employee_no);
				$firstname = $database->cleanData($firstname);
				$lastname = $database->cleanData($lastname);
				$gender = $database->cleanData($gender);
				$position = $database->cleanData($position);
				$schoolname = $database->cleanData($schoolname);
				$contactno = $database->cleanData($contactno);

				$sql = "INSERT INTO tbl_borrowers (borrower_id, firstname, lastname, gender, position, contactno, schoolname, status, date_created, approval) VALUES('$employee_no', '$firstname', '$lastname', '$gender', '$position', '$contactno', '$schoolname', '$status', '$date_created','$approval')";
				$result = $borrower->addRecord($sql);
				
				$sql = "SELECT MAX(log_id) as log_id FROM tbl_userlogs";
				$data_logs = $borrower->searchRecord($sql);
				$id = $data_logs[0]['log_id'];
				if($id == null) {
					$id = 1;
				}
				else {
					$id = $id + 1;
				}
				$action = "Add borrower (".$firstname." ".$lastname.")";
				$sql = "INSERT INTO tbl_userlogs SET log_id=$id, action='$action', date='$date', time='$time', user_id='$user_id'";
				$result = $borrower->addRecord($sql);
				echo $result;
			}
		}
		else if($_POST['action'] == "edit") {
			
		}
		else if($_POST['action'] == "update") {
			
			if($_POST['borrowerupdate_token'] == $_SESSION['borrowerupdate_token']) {
				$user_id = $_SESSION['user_id'];
				$employee_no = $_POST['employee_no'];
				$firstname = $_POST['firstname'];
				$lastname = $_POST['lastname'];
				$gender = $_POST['gender'];
				$position = $_POST['position'];
				$schoolname = $_POST['schoolname'];
				$contactno = $_POST['contactno'];
				$status = $_POST['status'];
				
				
				// Check Data if set and not empty

				//Convert Capitalize of the Word
				$firstname = $database->convertData($firstname);
				$lastname = $database->convertData($lastname);
				$position = $database->convertData($position);
				$schoolname = $database->convertData($schoolname);

				//Sanitize User Input
				$employee_no = $database->cleanData($employee_no);
				$firstname = $database->cleanData($firstname);
				$lastname = $database->cleanData($lastname);
				$gender = $database->cleanData($gender);
				$position = $database->cleanData($position);
				$schoolname = $database->cleanData($schoolname);
				$contactno = $database->cleanData($contactno);

				$sql = "UPDATE tbl_borrowers SET firstname='$firstname', lastname='$lastname', gender='$gender', position='$position', contactno='$contactno', status='$status' WHERE borrower_id='$employee_no'";
				$result = $borrower->updateRecord($sql);
				
				$sql = "SELECT MAX(log_id) as log_id FROM tbl_userlogs";
				$data_logs = $borrower->searchRecord($sql);
				$id = $data_logs[0]['log_id'];
				if($id == null) {
					$id = 1;
				}
				else {
					$id = $id + 1;
				}
				$action = "Update borrower information (".$firstname." ".$lastname.")";
				$sql = "INSERT INTO tbl_userlogs SET log_id=$id, action='$action', date='$date', time='$time', user_id='$user_id'";
				$result = $borrower->addRecord($sql);
				echo $result;
			}
		}
		else if($_POST['action'] == "delete") {
			
		}
	}
?>