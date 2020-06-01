<?php
	//defined('BASEPATH') OR exit('No direct script access allowed');
	require_once "model.php";
	session_start();
	date_default_timezone_set('Asia/Manila');
	$date =  date("Y-m-d");
    $time = date("h:i:s");

	if(isset($_POST['action'])) {
		
		$user = new Model;
		$database = new Database;
		
		if($_POST['action'] == "display") {
			
			$sql = "SELECT * FROM tbl_employee JOIN tbl_user ON tbl_employee.user_id=tbl_user.user_id";
			$result = $user->displayRecord($sql);
			$sql2 = "SELECT firstname FROM tbl_employee ORDER BY firstname";
			$firstname = $user->displayRecord($sql2);
			$sql3 = "SELECT lastname FROM tbl_employee ORDER BY lastname";
			$lastname = $user->displayRecord($sql3);
			$sql4 = "SELECT position FROM tbl_employee ORDER BY position";
			$position = $user->displayRecord($sql4);
			
			echo json_encode(array("data" =>$result, "fname" =>$firstname, "lname" =>$lastname, "lposition" =>$position));
		}
		else if($_POST['action'] == "add") {
			
			if($_POST['useradd_token'] == $_SESSION['useradd_token']) {
				
				$user_id = $_SESSION['user_id'];
				$image = "";
				$employee_no = $_POST['employee_no'];
				$firstname = $_POST['firstname'];
				$lastname = $_POST['lastname'];
				$gender = $_POST['gender'];
				$position = $_POST['position'];
				$address = $_POST['address'];
				$contactno = $_POST['contactno'];
				$date_created = date("Y-m-d");

				$username = $_POST['username'];
				$email = $_POST['email'];
				$password = $_POST['password'];
				$role = 0;
				$status = "Active";
				
				// Check Data if set and not empty

				//Convert Capitalize of the Word
				$firstname = $database->convertData($firstname);
				$lastname = $database->convertData($lastname);
				$position = $database->convertData($position);
				$address = $database->convertData($address);

				//Sanitize User Input
				$employee_no = $database->cleanData($employee_no);
				$firstname = $database->cleanData($firstname);
				$lastname = $database->cleanData($lastname);
				$gender = $database->cleanData($gender);
				$position = $database->cleanData($position);
				$address = $database->cleanData($address);
				$contactno = $database->cleanData($contactno);
				$username = $database->cleanData($username);
				$email = $database->cleanData($email);
				$password = $database->cleanData($password);


				//$total = $database->validate($employee_no, "SELECT * FROM tbl_employee WHERE user_id = ?");
				//echo "total = ".$total;

				//if($total == 0) {

					if(!empty($_FILES['user_photo']['name'])) {

						$image = $_FILES['user_photo']['name'];
						$tmp_name = $_FILES['user_photo']['tmp_name'];
						$result = $database->uploadImage($image, $tmp_name);

					}
					else {

						if($gender == "Male") {
							$image = "Male.jpg";
						}
						else {
							$image = "Female.png";
						}
					}
				
					$sql = "INSERT INTO tbl_employee (user_id, image, firstname, lastname, gender, position, address, contactno, date_created) VALUES('$employee_no', '$image', '$firstname', '$lastname', '$gender', '$position', '$address', '$contactno', '$date_created')";
					$result = $user->addRecord($sql);
					$password = md5($password);
					$sql = "INSERT INTO tbl_user (user_id, username, email, password, role, status) VALUES('$employee_no', '$username', '$email', '$password', $role, '$status')";
					$result = $user->addRecord($sql);
				
					$sql = "SELECT MAX(log_id) as log_id FROM tbl_userlogs";
					$data_logs = $user->searchRecord($sql);
					$id = $data_logs[0]['log_id'];
					if($id == null) {
						$id = 1;
					}
					else {
						$id = $id + 1;
					}
					$action = "Add user (".$firstname." ".$lastname.")";
					$sql = "INSERT INTO tbl_userlogs SET log_id=$id, action='$action', date='$date', time='$time', user_id='$user_id'";
					$result = $user->addRecord($sql);
					echo $result;
			//	}
			//	else {
			//		echo "exist";
			//	}
			}
		}
		else if($_POST['action'] == "edit") {
			
		}
		else if($_POST['action'] == "update") {
			
			if($_POST['userupdate_token'] == $_SESSION['userupdate_token']) {
				$user_id = $_SESSION['user_id'];
				$employee_no = $_POST['employee_no'];
				$firstname = $_POST['firstname'];
				$lastname = $_POST['lastname'];
				$gender = $_POST['gender'];
				$position = $_POST['position'];
				$address = $_POST['address'];
				$contactno = $_POST['contactno'];
				$image = "";

				// Check Data if set and not empty

				//Convert Capitalize of the Word
				$firstname = $database->convertData($firstname);
				$lastname = $database->convertData($lastname);
				$position = $database->convertData($position);
				$address = $database->convertData($address);

				//Sanitize User Input
				$employee_no = $database->cleanData($employee_no);
				$firstname = $database->cleanData($firstname);
				$lastname = $database->cleanData($lastname);
				$gender = $database->cleanData($gender);
				$position = $database->cleanData($position);
				$address = $database->cleanData($address);
				$contactno = $database->cleanData($contactno);
				
				if($gender == "Male") {
					$image = "Male.jpg";
				}
				else {
					$image = "Female.png";
				}

				$sql = "UPDATE tbl_employee SET image='$image', firstname='$firstname', lastname='$lastname', gender='$gender', position='$position', address='$address', contactno='$contactno' WHERE user_id='$employee_no'";
				$result = $user->updateRecord($sql);
				
				$sql = "SELECT MAX(log_id) as log_id FROM tbl_userlogs";
				$data_logs = $user->searchRecord($sql);
				$id = $data_logs[0]['log_id'];
				if($id == null) {
					$id = 1;
				}
				else {
					$id = $id + 1;
				}
				$action = "Update user details(Name ".$firstname." ".$lastname.")";
				$sql = "INSERT INTO tbl_userlogs SET log_id=$id, action='$action', date='$date', time='$time', user_id='$user_id'";
				$result = $user->addRecord($sql);
				echo $result;
			}
		}
		else if($_POST['action'] == "update_profile") {
			
			if($_POST['profile_token'] == $_SESSION['profile_token']) {
				
				$user_id = $_SESSION['user_id'];
				$employee_no = $_POST['employee_no'];
				$firstname = $_POST['firstname'];
				$lastname = $_POST['lastname'];
				$gender = $_POST['gender'];
				$position = $_POST['position'];
				$address = $_POST['address'];
				$contactno = $_POST['contactno'];
				$image = "";

				// Check Data if set and not empty

				//Convert Capitalize of the Word
				$firstname = $database->convertData($firstname);
				$lastname = $database->convertData($lastname);
				$position = $database->convertData($position);
				$address = $database->convertData($address);

				//Sanitize User Input
				$employee_no = $database->cleanData($employee_no);
				$firstname = $database->cleanData($firstname);
				$lastname = $database->cleanData($lastname);
				$gender = $database->cleanData($gender);
				$position = $database->cleanData($position);
				$address = $database->cleanData($address);
				$contactno = $database->cleanData($contactno);
				
				if($gender == "Male") {
					$image = "Male.jpg";
				}
				else {
					$image = "Female.png";
				}

				$sql = "UPDATE tbl_employee SET image='$image', firstname='$firstname', lastname='$lastname', gender='$gender', position='$position', address='$address', contactno='$contactno' WHERE user_id='$employee_no'";
				$result = $user->updateRecord($sql);
				
				$sql = "SELECT MAX(log_id) as log_id FROM tbl_userlogs";
				$data_logs = $user->searchRecord($sql);
				$id = $data_logs[0]['log_id'];
				if($id == null) {
					$id = 1;
				}
				else {
					$id = $id + 1;
				}
				
				$action = "Update user profile details(".$firstname." ".$lastname.")";
				$sql = "INSERT INTO tbl_userlogs SET log_id=$id, action='$action', date='$date', time='$time', user_id='$user_id'";
				$result = $user->addRecord($sql);
				echo $result;
			}
		}
		else if($_POST['action'] == "updateA") {
			
			if($_POST['userupdate_token'] == $_SESSION['userupdate_token']) {
				
				$user_id = $_SESSION['user_id'];
				$user_id = $_POST['user_id'];
				$username = $_POST['username'];
				$email = $_POST['email'];
				$status = $_POST['status'];

				// Check Data if set and not empty


				//Sanitize User Input
				$user_id = $database->cleanData($user_id);
				$username = $database->cleanData($username);
				$email = $database->cleanData($email);

				$sql = "UPDATE tbl_user SET username='$username', email='$email', status='$status' WHERE user_id='$user_id'";
				$result = $user->updateRecord($sql);
				
				$sql = "SELECT MAX(log_id) as log_id FROM tbl_userlogs";
				$data_logs = $user->searchRecord($sql);
				$id = $data_logs[0]['log_id'];
				if($id == null) {
					$id = 1;
				}
				else {
					$id = $id + 1;
				}
				$action = "Update user account(".$username.")";
				$sql = "INSERT INTO tbl_userlogs SET log_id=$id, action='$action', date='$date', time='$time', user_id='$user_id'";
				$result = $user->addRecord($sql);
				echo $result;
			}
			
		}
		else if($_POST['action'] == "updateA_profile") {
			
			if($_POST['profile_token'] == $_SESSION['profile_token']) {
				$id = $_SESSION['user_id'];
				$user_id = $_POST['user_id'];
				$username = $_POST['username'];
				$email = $_POST['email'];
				$current_password = $_POST['current_password'];
				$new_password = $_POST['new_password'];
				// Check Data if set and not empty


				//Sanitize User Input
				$user_id = $database->cleanData($user_id);
				$username = $database->cleanData($username);
				$email = $database->cleanData($email);
				$current_password = $database->cleanData($current_password);
				$new_password = $database->cleanData($new_password);
				
				$sql = "SELECT * FROM tbl_user WHERE user_id='$user_id'";
				$data = $user->displayRecord($sql);
				if(count($data) == 1) {
					$password = $data[0]['password'];
					$current_password = md5($current_password);
					$new_password = md5($new_password);
					
					if($current_password == $password) {
						$sql = "UPDATE tbl_user SET username='$username', email='$email', password='$new_password' WHERE user_id='$user_id'";
						$result = $user->updateRecord($sql);
						
						$sql = "SELECT MAX(log_id) as log_id FROM tbl_userlogs";
						$data_logs = $user->searchRecord($sql);
						$id = $data_logs[0]['log_id'];
						if($id == null) {
							$id = 1;
						}
						else {
							$id = $id + 1;
						}
						$action = "Update user profile account(".$username.")";
						$sql = "INSERT INTO tbl_userlogs SET log_id=$id, action='$action', date='$date', time='$time', user_id='$user_id'";
						$result = $user->addRecord($sql);
						echo $result;
					}
					else {
						echo "Incorrect";
					}
				}
				else {
					echo "Error.";
				}
			}
		}
		else if($_POST['action'] == "book_received") {
			$booklogs_id = $_POST['booklogs_id'];
			$sql = "SELECT tbl_booklogs.booklogs_id, tbl_booklogs.quantity, tbl_books.title, tbl_books.author, tbl_books.pages, tbl_books.fund, tbl_books.copyright, tbl_books.isbn, tbl_books.publisher, tbl_books.classification, tbl_booklogs.date, tbl_booklogs.status FROM tbl_booklogs JOIN tbl_books ON tbl_booklogs.book_id=tbl_books.book_id WHERE tbl_booklogs.status='Received' AND tbl_booklogs.booklogs_id='$booklogs_id'";
			$result = $user->displayRecord($sql);
			$count = 0;
			foreach($result as $value) {
				
				$result[$count]['date'] = date('M d, Y',strtotime($value['date']));
				$count++;
			}
			echo  json_encode(array("data" =>$result));
		}
		else if($_POST['action'] == "book_borrowed") {
			$booklogs_id = $_POST['booklogs_id'];
			$sql = "SELECT tbl_booklogs.booklogs_id, tbl_booklogs.quantity, tbl_booklogs.date, tbl_booklogs.status, tbl_books.book_id, tbl_books.title, tbl_books.author, tbl_books.pages, tbl_books.fund, tbl_books.copyright, tbl_books.isbn, tbl_books.publisher, tbl_books.classification, tbl_borrowers.firstname, tbl_borrowers.lastname FROM tbl_booklogs JOIN tbl_borrowed ON tbl_booklogs.booklogs_id=tbl_borrowed.booklogs_id JOIN tbl_books ON tbl_booklogs.book_id=tbl_books.book_id JOIN tbl_borrowers ON tbl_booklogs.borrower_id=tbl_borrowers.borrower_id WHERE tbl_booklogs.status='Borrowed'  AND tbl_booklogs.booklogs_id='$booklogs_id' GROUP BY tbl_booklogs.booklogs_id";
			$result = $user->displayRecord($sql);
			$count = 0;
			foreach($result as $value) {
				
				$result[$count]['date'] = date('M d, Y',strtotime($value['date']));
				$count++;
			}
			echo  json_encode(array("data" =>$result));
		}
		else if($_POST['action'] == "book_returned") {
			$booklogs_id = $_POST['booklogs_id'];
			$sql = "SELECT tbl_booklogs.booklogs_id, tbl_booklogs.quantity, tbl_borrowed.date_borrowed, tbl_borrowed.date_returned, tbl_booklogs.status, tbl_books.book_id, tbl_books.title, tbl_books.author, tbl_books.pages, tbl_books.fund, tbl_books.copyright, tbl_books.isbn, tbl_books.publisher, tbl_books.classification, tbl_borrowers.firstname, tbl_borrowers.lastname, tbl_employee.firstname as is_fn, tbl_employee.lastname as is_lname FROM tbl_booklogs JOIN tbl_borrowed ON tbl_booklogs.booklogs_id=tbl_borrowed.booklogs_id2 JOIN tbl_books ON tbl_booklogs.book_id=tbl_books.book_id JOIN tbl_borrowers ON tbl_booklogs.borrower_id=tbl_borrowers.borrower_id JOIN tbl_employee ON tbl_borrowed.user_id=tbl_employee.user_id WHERE tbl_booklogs.status='Returned'  AND tbl_booklogs.booklogs_id='$booklogs_id' GROUP BY tbl_booklogs.booklogs_id";
			$result = $user->displayRecord($sql);
			$count = 0;
			foreach($result as $value) {
				
				$result[$count]['date_borrowed'] = date('M d, Y',strtotime($value['date_borrowed']));
				$result[$count]['date_returned'] = date('M d, Y',strtotime($value['date_returned']));
				$count++;
			}
			echo  json_encode(array("data" =>$result));
		}
	}
?>