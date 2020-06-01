<?php
	//defined('BASEPATH') OR exit('No direct script access allowed');
	require_once "model.php";
	session_start();
	date_default_timezone_set('Asia/Manila');
	$date =  date("Y-m-d");
    $time = date("h:i:s");
	$user_id = $_SESSION['user_id'];
	
	if(isset($_POST['action'])) {
		
		$book = new Model;
		$database = new Database;
		
		if($_POST['action'] == "display") {
			
			$sql = "SELECT * FROM tbl_books ORDER BY title";
			$result = $book->displayRecord($sql);
			$sql2 = "SELECT title FROM tbl_books GROUP BY title ORDER BY title";
			$title = $book->displayRecord($sql2);
			$sql3 = "SELECT author FROM tbl_books GROUP BY author ORDER BY author";
			$author = $book->displayRecord($sql3);
			$sql4 = "SELECT category FROM tbl_books GROUP BY category ORDER BY category";
			$category = $book->displayRecord($sql4);
			echo json_encode(array("data" =>$result, "title" =>$title, "author" =>$author, "category" =>$category));
		}
		if($_POST['action'] == "view_copy") {
			$booklogs_id = $_POST['id'];
			$sql = "SELECT * FROM tbl_copy JOIN tbl_booklogs ON tbl_copy.booklogs=tbl_booklogs.booklogs_id JOIN tbl_books ON tbl_booklogs.book_id=tbl_books.book_id WHERE tbl_booklogs.booklogs_id='$booklogs_id'";
			$result = $book->displayRecord($sql);
			echo json_encode(array("data" =>$result));
		}
		else if($_POST['action'] == "book logs") {
			
			$book_id = $_POST['book_id'];
			$month = $_POST['month'];
			$year = $_POST['year'];
			$sql = "SELECT tbl_books.isbn, tbl_books.title, SUM(tbl_booklogs.quantity) AS quantity, tbl_booklogs.date, tbl_employee.firstname, tbl_employee.lastname FROM tbl_booklogs JOIN tbl_books ON tbl_booklogs.book_id=tbl_books.book_id JOIN tbl_employee ON tbl_booklogs.user_id=tbl_employee.user_id  WHERE EXTRACT(YEAR FROM tbl_booklogs.date)='$year' AND EXTRACT(MONTH FROM tbl_booklogs.date)='$month' AND tbl_booklogs.status='Received' AND tbl_booklogs.book_id='$book_id' GROUP BY tbl_booklogs.date ORDER BY tbl_booklogs.date ASC";
			$data = $book->displayRecord($sql);
			$count = 0;
			foreach($data as $value) {
				
				$data[$count]['date'] = date('M d, Y',strtotime($value['date']));
				$count++;
			}
			echo json_encode(array("data" =>$data));
		}
		else if($_POST['action'] == "search logs") {
			
			$month = $_POST['month'];
			$year = $_POST['year'];
			$date = $year."-".$month;
			$date = date('F Y',strtotime($date));
			$sql = "SELECT tbl_copy.account_no, tbl_books.book_id, tbl_books.isbn, tbl_books.title, tbl_books.author, tbl_books.pages, tbl_books.fund, tbl_books.copyright, tbl_books.publisher FROM tbl_booklogs JOIN tbl_books ON tbl_booklogs.book_id=tbl_books.book_id JOIN tbl_copy ON tbl_booklogs.booklogs_id=tbl_copy.booklogs WHERE EXTRACT(YEAR FROM tbl_booklogs.date)='$year' AND EXTRACT(MONTH FROM tbl_booklogs.date)='$month' AND tbl_booklogs.status='Received' ORDER BY tbl_copy.book_id, tbl_copy.copy";
			$data = $book->searchRecord($sql); 
			echo json_encode(array("data" =>$data, "date" =>$date));
		}
		else if($_POST['action'] == "select") {
			
			$book_id = $_POST['book_id'];
			$sql = "SELECT * FROM tbl_books  WHERE tbl_books.book_id='$book_id'";
			$data = $book->searchRecord($sql); 
			echo json_encode(array("data" =>$data));
		}
		else if($_POST['action'] == "search") {
			
			$search = "%{$_POST['search']}%";
			$sql = "SELECT * FROM tbl_books  WHERE tbl_books.isbn LIKE '$search' OR tbl_books.title LIKE '$search' OR tbl_books.author LIKE '$search' OR tbl_books.publisher LIKE '$search'";
			$data = $book->searchRecord($sql); 
			echo json_encode(array("data" =>$data));
		}
		else if($_POST['action'] == "import" ) {
			
			$filename=$_FILES["file"]["tmp_name"];	
			 if($_FILES["file"]["size"] > 0)
			 {
				$file = fopen($filename, "r");
				 $count = 0;
				while (($row = fgetcsv($file, 10000, ",")) !== FALSE)
				{
					$count++;
					$isbn = $row[0];
					$title = $row[1];
					$description = $row[2];
					$quantity = $row[3];
					$author = $row[4];
					$category = $row[5];
					$idExist = true;
					$user_id = $_SESSION['user_id'];
					$date_received = date("Y-m-d");
					$status = "Received";
					
					$row = $database->validate("SELECT isbn FROM tbl_books WHERE isbn = '$isbn'");
					if($row == 0 && $count > 1) {
						
						if($isbn != "") {
							while ($idExist == true) {

								$book_id = $book->generateID();
								$total = $database->validate("SELECT book_id FROM tbl_books WHERE book_id = '$book_id'");
								if ($total == 0) {
									if(!empty($_FILES['book_photo']['name'])) {

										$image = $_FILES['book_photo']['name'];
										$tmp_name = $_FILES['book_photo']['tmp_name'];
										$result = $database->uploadImage($image, $tmp_name);
									}
									else {
										$image = "book.png";
									}

									$sql = "INSERT INTO tbl_books (book_id, image, isbn, title, grade, quantity, author, category) VALUES('$book_id', '$image', '$isbn', '$description', '$title', $quantity, '$author', '$category')";
									$result = $book->addRecord($sql);
									/*
									$sql = "INSERT INTO tbl_booklogs (quantity, date, book_id, user_id, status) VALUES($quantity, '$date_received', '$book_id', '$user_id', '$status')";
									$result = $book->addRecord($sql);
									*/

									$idExist = false;
									break;
								}
							}
						}
					}
					else {
						if($count > 1) {
							$sql = "SELECT * FROM tbl_books  WHERE isbn='$isbn'";
							$data = $book->searchRecord($sql); 
							$book_id = $data[0]['book_id'];
							$db_qty = $data[0]['quantity'];
							$total_qty = $db_qty + $quantity;

							$sql = "INSERT INTO tbl_booklogs (quantity, date, book_id, user_id, status) VALUES($quantity, '$date_received', '$book_id', '$user_id', '$status')";
							$result = $book->addRecord($sql);

							$sql = "UPDATE tbl_books SET quantity='$total_qty' WHERE book_id='$book_id'";
							$result = $book->updateRecord($sql);
						}
					}
					
					/*
					$search = "%{$unit_id}%";
					$sql = "SELECT * FROM unit WHERE description_unit LIKE '$search' OR unit_id LIKE '$search'";
					$data = display_records($sql, $conn);
					if(count($data) > 0) {
						$unit_id = $data[0]['unit_id'];
					}
					else {
						$unit_id = "Unknown";
					}
					*/
					/*
					$number = check("SELECT * FROM backlogs WHERE property_no=?", $conn, $property_no);
					
					if($number == 0 && $property_no != "") {
						$count++;
						$success = add_records2($conn, $accountable_id, $property_no, $description, $unit_id, $quantity, $cost, $remarks, $type, $date);
					}
					*/
				 }
				 if($count > 1) {
					echo "true";
				}
				 else if($count < 2) {
					 echo "no_record";
				 }
				else {
					echo "false";
				}
				 fclose($file);	
			 }
			else {
				echo "false";
			}
		}
		else if($_POST['action'] == "add" ) {
			
			if($_SESSION['bookadd_token'] == $_POST['bookadd_token']) {
				
				$book_id = "";
				$isbn = $_POST['isbn'];
				$title = $_POST['title'];
				$author = $_POST['author'];
				$qty_in = $_POST['qty_in'];
				$qty_out = 0;
				$pages = $_POST['pages'];
				$fund = $_POST['fund'];
				$copyright = $_POST['copyright'];
				$publisher = $_POST['publisher'];
				$classification = $_POST['classification'];
				$date_list =  explode("/", $_POST['date_received']);
				$date_received = $date_list[2]."-".$date_list[0]."-".$date_list[1];
				$date = date("Y-m-d H:i:s");
				$user_id = $_SESSION['user_id'];
				$idExist = true;
				$status = "Received";
				$status2 = "Okay";

				$row = $database->validate("SELECT isbn FROM tbl_books WHERE isbn = '$isbn'");
				if($row == 0) {
					$sql = "SELECT MAX(book_id) as book_id FROM tbl_books";
					$data = $book->displayRecord($sql);
					
					if($data[0]['book_id'] == null) {
						$book_id = 1;
					}
					else {
						$book_id = $data[0]['book_id'] + 1;
					}
					$sql = "INSERT INTO tbl_books (book_id, title, author, pages, fund, copyright, isbn, publisher, classification, qty_in, qty_out) VALUES('$book_id', '$title', '$author', $pages, '$fund', '$copyright', '$isbn', '$publisher','$classification', $qty_in, $qty_out)";
					$result = $book->addRecord($sql);
					
					$sql = "SELECT MAX(booklogs_id) as logid FROM tbl_booklogs";
					$data_logs = $book->searchRecord($sql);
					$log_id = $data_logs[0]['logid'];
					if($log_id == null) {
						$log_id = 1;
					}
					else {
						$log_id = $log_id + 1;
					}
					
					$sql = "INSERT INTO tbl_booklogs (booklogs_id,quantity, date, book_id, user_id, status) VALUES($log_id, $qty_in, '$date_received', '$book_id', '$user_id', '$status')";
					$result = $book->addRecord($sql);
					
					$row2 = $database->validate("SELECT book_id FROM tbl_copy WHERE book_id = '$book_id'");
					if($row2 == 0){
						for($x = 1; $x <= $qty_in; $x++) {
							$account_no = $book_id." c.".$x;
							$sql = "SELECT MAX(copy_id) as copy_id FROM tbl_copy";
							$data_logs = $book->searchRecord($sql);
							$id = $data_logs[0]['copy_id'];
							if($id == null) {
								$id = 1;
							}
							else {
								$id = $id + 1;
							}
							
							$sql = "INSERT INTO tbl_copy (copy_id, account_no, copy, book_id, booklogs, remarks, status) VALUES($id, '$account_no', $x, $book_id, $log_id, '$status', '$status2')";
							$result = $book->addRecord($sql);
						}
					}
					echo $result;
				}
				else {
					$sql = "SELECT * FROM tbl_books  WHERE isbn='$isbn'";
					$data = $book->searchRecord($sql); 
					$book_id = $data[0]['book_id'];
					$db_qty = $data[0]['qty_in'];
					$total_qty = $db_qty + $qty_in;
					
					$sql = "SELECT MAX(booklogs_id) as logid FROM tbl_booklogs";
					$data_logs = $book->searchRecord($sql);
					$log_id = $data_logs[0]['logid'];
					if($log_id == null) {
						$log_id = 1;
					}
					else {
						$log_id = $log_id + 1;
					}
					
					$sql = "INSERT INTO tbl_booklogs (booklogs_id,quantity, date, book_id, user_id, status) VALUES($log_id, $qty_in, '$date_received', '$book_id', '$user_id', '$status')";
					$result = $book->addRecord($sql);
					
					$sql = "SELECT MAX(copy) as copy FROM tbl_copy  WHERE book_id='$book_id'";
					$data_copy = $book->displayRecord($sql); 
					$copy = $data_copy[0]['copy'] + 1;
					
					for($x = $copy; $x <= $total_qty; $x++) {
						$account_no = $book_id." c.".$x;
						$sql = "INSERT INTO tbl_copy (account_no, copy, book_id, booklogs, remarks, status) VALUES('$account_no', $x, $book_id, $log_id, '$status', '$status2')";
						$result = $book->addRecord($sql);
					}
					
					$sql = "UPDATE tbl_books SET qty_in='$total_qty' WHERE book_id='$book_id'";
					$result = $book->updateRecord($sql);
					echo $result;
				}
				
				$sql = "SELECT MAX(log_id) as log_id FROM tbl_userlogs";
				$data_logs = $book->searchRecord($sql);
				$id = $data_logs[0]['log_id'];
				if($id == null) {
					$id = 1;
				}
				else {
					$id = $id + 1;
				}
				$action = "Add book (Title: ".$title.", quantity: ".$qty_in.")";
				$sql = "INSERT INTO tbl_userlogs SET log_id=$id, action='$action', date='$date', time='$time', user_id='$user_id'";
				$result = $book->addRecord($sql);
					
			}
			else {
				header("location:dashboard.php");
			}
		}
		else if($_POST['action'] == "edit") {
			
			$book_id = $_POST['book_id'];
			$sql = "SELECT * FROM tbl_books WHERE book_id='$book_id'";
			$data = $book->editRecord($sql);
			echo json_encode(array("success" =>true, "data" =>$data));
		}
		else if($_POST['action'] == "update") {
			
			if($_SESSION['bookupdate_token'] == $_POST['bookupdate_token']) {
				$book_id = $_POST['book_id'];
				$isbn = $_POST['isbn'];
				$title = $_POST['title'];
				$author = $_POST['author'];
				$pages = $_POST['pages'];
				$fund = $_POST['fund'];
				$copyright = $_POST['copyright'];
				$publisher = $_POST['publisher'];
				$user_id = $_SESSION['user_id'];

				$sql = "UPDATE tbl_books SET title='$title', author='$author', pages=$pages, fund='$fund', copyright='$copyright', isbn='isbn', publisher='$publisher' WHERE book_id='$book_id'";
				$result = $book->updateRecord($sql);
				echo $result;
			}
		}
		else if($_POST['action'] == "view_received_logs") {
			$booklogs_id = $_POST['booklogs_id'];
			$sql = "SELECT tbl_copy.account_no, tbl_books.title, tbl_books.author, tbl_booklogs.date, tbl_employee.firstname, tbl_employee.lastname, tbl_booklogs.status FROM tbl_booklogs JOIN tbl_books ON tbl_booklogs.book_id=tbl_books.book_id JOIN tbl_copy ON tbl_booklogs.booklogs_id=tbl_copy.booklogs JOIN tbl_employee ON tbl_booklogs.user_id=tbl_employee.user_id WHERE tbl_booklogs.status='Received' AND tbl_booklogs.booklogs_id='$booklogs_id' GROUP by tbl_copy.account_no ORDER BY tbl_copy.book_id, tbl_copy.copy";
			$data = $book->displayRecord($sql);
			$count = 0;
			
			if(count($data) > 0) {
				foreach($data as $value) {
				
					$data[$count]['date'] = date('M d, Y',strtotime($value['date']));
					$count++;
				}
			}
			echo json_encode(array("data" =>$data));
		}
		else if($_POST['action'] == "view_profile_logs") {
			$booklogs_id = $_POST['booklogs_id'];
			
			if($_POST['category'] == "Received") {
				$sql = "SELECT tbl_copy.account_no, tbl_books.title, tbl_books.author, tbl_booklogs.date, tbl_employee.firstname, tbl_employee.lastname, tbl_booklogs.status FROM tbl_booklogs JOIN tbl_books ON tbl_booklogs.book_id=tbl_books.book_id JOIN tbl_copy ON tbl_booklogs.booklogs_id=tbl_copy.booklogs JOIN tbl_employee ON tbl_booklogs.user_id=tbl_employee.user_id WHERE tbl_booklogs.status='Received' AND tbl_booklogs.booklogs_id='$booklogs_id' GROUP by tbl_copy.account_no ORDER BY tbl_copy.book_id, tbl_copy.copy";
				$data = $book->displayRecord($sql);
				$count = 0;

				if(count($data) > 0) {
					foreach($data as $value) {

						$data[$count]['date'] = date('M d, Y',strtotime($value['date']));
						$count++;
					}
				}
			}
			else if($_POST['category'] == "Borrowed") {
				$sql = "SELECT tbl_borrowed.account_no, tbl_books.title, tbl_books.author, tbl_booklogs.date, tbl_booklogs.status, tbl_employee.firstname as is_fname, tbl_employee.lastname as is_lname, tbl_borrowers.firstname as bo_fname, tbl_borrowers.lastname as bo_lname FROM tbl_booklogs JOIN tbl_borrowed ON tbl_booklogs.booklogs_id=tbl_borrowed.booklogs_id JOIN tbl_books ON tbl_booklogs.book_id=tbl_books.book_id JOIN tbl_employee ON tbl_borrowed.user_id=tbl_employee.user_id JOIN tbl_borrowers ON tbl_borrowed.borrower_id=tbl_borrowers.borrower_id WHERE tbl_booklogs.booklogs_id='$booklogs_id' GROUP BY tbl_borrowed.account_no";
				$data = $book->displayRecord($sql);
				$count = 0;

				if(count($data) > 0) {
					foreach($data as $value) {

						$data[$count]['date'] = date('M d, Y',strtotime($value['date']));
						$count++;
					}
				}
			}
			else {
				$sql = "SELECT tbl_borrowed.account_no, tbl_books.title, tbl_books.author, tbl_booklogs.date, tbl_borrowed.date_returned, tbl_booklogs.status, tbl_employee.firstname as is_fname, tbl_employee.lastname as is_lname, tbl_borrowers.firstname as bo_fname, tbl_borrowers.lastname as bo_lname, emp_re.firstname as re_fname, emp_re.lastname as re_lname FROM tbl_booklogs JOIN tbl_borrowed ON tbl_booklogs.booklogs_id=tbl_borrowed.booklogs_id2 JOIN tbl_books ON tbl_booklogs.book_id=tbl_books.book_id JOIN tbl_employee ON tbl_borrowed.user_id=tbl_employee.user_id JOIN tbl_borrowers ON tbl_borrowed.borrower_id=tbl_borrowers.borrower_id JOIN tbl_employee as emp_re ON tbl_borrowed.received_userid=emp_re.user_id WHERE tbl_booklogs.booklogs_id='4' GROUP BY tbl_borrowed.account_no";
				$data = $book->displayRecord($sql);
				$count = 0;

				if(count($data) > 0) {
					foreach($data as $value) {

						$data[$count]['date'] = date('M d, Y',strtotime($value['date']));
						$data[$count]['date_returned'] = date('M d, Y',strtotime($value['date_returned']));
						$count++;
					}
				}
			}
			
			echo json_encode(array("data" =>$data));
		}
		else if($_POST['action'] == "select_accession") {
			$accession_no = $_POST['accession_no'];
			$sql = "SELECT tbl_borrowed.account_no, tbl_borrowed.date_borrowed, tbl_borrowers.firstname, tbl_borrowers.lastname, tbl_borrowed.date_returned FROM tbl_borrowed JOIN tbl_copy ON tbl_borrowed.account_no=tbl_copy.account_no JOIN tbl_borrowers oN tbl_borrowed.borrower_id=tbl_borrowers.borrower_id WHERE tbl_borrowed.account_no='$accession_no' ORDER BY tbl_borrowed.date_borrowed DESC";
			$data = $book->displayRecord($sql);
			$count = 0;
			
			if(count($data) > 0) {
				foreach($data as $value) {
				
					$data[$count]['date_borrowed'] = date('M d, Y',strtotime($value['date_borrowed']));
					if($value['date_returned'] != null) {
						$data[$count]['date_returned'] = date('M d, Y',strtotime($value['date_returned']));
					}
					else {
						$data[$count]['date_returned'] = " ";
					}
					
					
					$count++;
				}
			}
			echo json_encode(array("data" =>$data));
		}
		else {
			header("location:dashboard.php");
		}
	}
?>