<?php
	//defined('BASEPATH') OR exit('No direct script access allowed');
	require_once "model.php";
	session_start();
	date_default_timezone_set('Asia/Manila');
	if(isset($_POST['action'])) {
		
		$borrowed = new Model;
		$database = new Database;
		//okay
		if($_POST['action'] == "display") {
			
			$sql2 = "SELECT tbl_temp.temp_id, tbl_copy.account_no, tbl_books.title, tbl_books.author, tbl_books.pages FROM tbl_temp JOIN tbl_copy ON tbl_temp.account_no=tbl_copy.account_no JOIN tbl_books ON tbl_copy.book_id=tbl_books.book_id ORDER BY tbl_copy.book_id, tbl_copy.copy";
			$list = $borrowed->displayRecord($sql2);
			echo json_encode(array("list" =>$list));
		}
		else if($_POST['action'] == "display_user") {
			
			
			$sql2 = "SELECT * FROM tbl_temp AS t JOIN tbl_books AS b ON t.book_id=b.book_id WHERE t.role='user'  ORDER BY b.title";
			$user = $borrowed->displayRecord($sql2);
			echo json_encode(array("user" =>$user));
		}
		
		else if($_POST['action'] == "check_user") {
			
			$emp_no = $_POST['emp_no'];
			$borrower = explode(" ", $_POST['borrower']);
			$fname = $borrower[0];
			$lname = $borrower[1];
			
			$sql = "SELECT * FROM tbl_borrowers WHERE firstname='$fname' AND lastname='$lname'";
			$user = $borrowed->displayRecord($sql);
			if(count($user) > 0) {
				if($user[0]['borrower_id'] == $emp_no) {
					echo json_encode(array("data" =>"exist"));
				}
				else {
					echo json_encode(array("data" =>"notexist"));
				}
			}
			else {
				echo json_encode(array("data" =>"notexist"));
			}
		}
		
		else if($_POST['action'] == "search") {
			
			$search = $_POST['search'];
			$search = "%{$search}%";
			$sql = "SELECT * FROM tbl_books WHERE tbl_books.book_id LIKE '$search' OR tbl_books.title LIKE '$search' OR tbl_books.author LIKE '$search' OR tbl_books.isbn LIKE '$search' OR tbl_books.publisher LIKE '$search'";
			$data = $borrowed->displayRecord($sql);
			echo json_encode(array("data" =>$data));
		}
		else if($_POST['action'] == "search_more") {
			
			$search = $_POST['search'];
			$search = "%{$search}%";
			$sql = "SELECT * FROM tbl_books WHERE tbl_books.isbn LIKE '$search' OR tbl_books.title LIKE '$search' OR tbl_books.grade LIKE '$search' OR tbl_books.author LIKE '$search' OR tbl_books.category LIKE '$search'";
			$data = $borrowed->displayRecord($sql);
			echo json_encode(array("data" =>$data));
		}
		else if($_POST['action'] == "select_book") {
			
			$book_id = $_POST['book_id'];
			$sql = "SELECT * FROM tbl_books WHERE tbl_books.book_id='$book_id'";
			$data = $borrowed->displayRecord($sql);
			$sql = "SELECT * FROM tbl_copy WHERE tbl_copy.book_id='$book_id' AND (tbl_copy.remarks='Received' OR tbl_copy.remarks='Returned' ) AND tbl_copy.status='Okay' ORDER BY tbl_copy.book_id, tbl_copy.copy";
			$data2 = $borrowed->displayRecord($sql);
			echo json_encode(array("data" =>$data, "data2" =>$data2));
		}
		else if($_POST['action'] == "book logs") {
			
			$book_id = $_POST['book_id'];
			$month = $_POST['month'];
			$year = $_POST['year'];
			$sql = "SELECT tbl_books.isbn, tbl_books.title, SUM(tbl_borrowed.quantity) as quantity, tbl_borrowers.firstname as b_fname, tbl_borrowers.lastname as b_lname, tbl_employee.firstname as e_fname, tbl_employee.lastname as e_lname, tbl_borrowed.date_borrowed, tbl_borrowed.due_date FROM tbl_borrowed JOIN tbl_books ON tbl_borrowed.book_id=tbl_books.book_id JOIN tbl_borrowers ON tbl_borrowed.borrower_id=tbl_borrowers.borrower_id JOIN tbl_employee ON tbl_borrowed.user_id=tbl_employee.user_id WHERE EXTRACT(YEAR FROM tbl_borrowed.date_borrowed)='$year' AND EXTRACT(MONTH FROM tbl_borrowed.date_borrowed)='$month' AND tbl_books.book_id='$book_id' GROUP BY tbl_books.book_id, tbl_borrowed.borrower_id, tbl_borrowed.user_id, tbl_borrowed.date_borrowed, tbl_borrowed.due_date";
			$data = $borrowed->displayRecord($sql);
			$count = 0;
			foreach($data as $value) {
				
				$data[$count]['date_borrowed'] = date('M d, Y',strtotime($value['date_borrowed']));
				$data[$count]['due_date'] = date('M d, Y',strtotime($value['due_date']));
				$count++;
			}
			echo json_encode(array("data" =>$data));
		}
		
		else if($_POST['action'] == "borrowed_view") {
			$borrower_id = $_POST['borrower_id'];
			$book_id = $_POST['book_id'];
			$sql = "SELECT tbl_borrowed.date_borrowed, tbl_borrowed.due_date, tbl_employee.firstname, tbl_employee.lastname, tbl_books.category, SUM(tbl_borrowed.quantity) as quantity FROM tbl_borrowed JOIN tbl_books ON tbl_borrowed.book_id=tbl_books.book_id  JOIN tbl_employee ON tbl_borrowed.user_id=tbl_employee.user_id WHERE tbl_borrowed.borrower_id='$borrower_id' AND tbl_borrowed.status='Borrowed' AND tbl_borrowed.book_id='$book_id' GROUP BY tbl_borrowed.book_id, tbl_borrowed.date_borrowed,tbl_borrowed.due_date, tbl_borrowed.user_id, tbl_borrowed.borrower_id";
			$data = $borrowed->searchRecord($sql);
			$count = 0;
			foreach($data as $value) {
				
				$data[$count]['date_borrowed'] = date('M d, Y',strtotime($value['date_borrowed']));
				$data[$count]['due_date'] = date('M d, Y',strtotime($value['due_date']));
				$count++;
			}
			echo json_encode(array("data" =>$data));
		}
		else if($_POST['action'] == "overdue_view") {
			$borrower_id = $_POST['borrower_id'];
			$book_id = $_POST['book_id'];
			$sql = "SELECT tbl_books.book_id, tbl_books.isbn, tbl_books.title,tbl_books.grade, tbl_employee.firstname, tbl_employee.lastname, SUM(tbl_borrowed.quantity) AS quantity, tbl_borrowed.date_borrowed, tbl_borrowed.due_date, DATEDIFF(tbl_borrowed.due_date, CURDATE()) AS days FROM tbl_borrowed JOIN tbl_borrowers ON tbl_borrowed.borrower_id=tbl_borrowers.borrower_id JOIN tbl_books ON tbl_borrowed.book_id=tbl_books.book_id JOIN tbl_employee ON tbl_borrowed.user_id=tbl_employee.user_id WHERE tbl_borrowed.status='Borrowed' AND tbl_borrowed.borrower_id='$borrower_id' AND DATEDIFF(tbl_borrowed.due_date, CURDATE()) < 1 AND tbl_borrowed.book_id='$book_id' GROUP BY tbl_borrowed.book_id, tbl_borrowed.date_borrowed, tbl_borrowed.due_date, tbl_borrowed.borrower_id, tbl_borrowed.user_id";
			$data = $borrowed->searchRecord($sql);
			$count = 0;
			foreach($data as $value) {
				
				$data[$count]['date_borrowed'] = date('M d, Y',strtotime($value['date_borrowed']));
				$data[$count]['due_date'] = date('M d, Y',strtotime($value['due_date']));
				$count++;
			}
			echo json_encode(array("data" =>$data));
		}
		else if($_POST['action'] == "lost_view") {
			$borrower_id = $_POST['borrower_id'];
			$book_id = $_POST['book_id'];
			$sql = "SELECT br.remarks, emp.firstname as is_lname, emp.firstname as is_fname, tbl_employee.firstname as re_fname, tbl_employee.lastname as re_lname, SUM(br.quantity) as quantity, br.date_borrowed, br.due_date, br.date_return FROM tbl_borrowed AS br JOIN tbl_books AS bk ON br.book_id=bk.book_id JOIN tbl_borrowers AS brw ON br.borrower_id=brw.borrower_id JOIN tbl_employee AS emp ON br.user_id=emp.user_id JOIN tbl_employee  ON br.received_userid=tbl_employee.user_id WHERE br.remarks='Lost' AND br.borrower_id='$borrower_id' AND br.book_id='$book_id' GROUP BY br.book_id, br.date_borrowed, br.due_date, br.date_return, br.borrower_id, br.user_id, br.received_userid";
			$data = $borrowed->searchRecord($sql);
			$count = 0;
			foreach($data as $value) {
				
				$data[$count]['date_borrowed'] = date('M d, Y',strtotime($value['date_borrowed']));
				$data[$count]['due_date'] = date('M d, Y',strtotime($value['due_date']));
				$data[$count]['date_return'] = date('M d, Y',strtotime($value['date_return']));
				$count++;
			}
			echo json_encode(array("data" =>$data));
		}
		else if($_POST['action'] == "damaged_view") {
			$borrower_id = $_POST['borrower_id'];
			$book_id = $_POST['book_id'];
			$sql = "SELECT br.remarks, emp.firstname as is_lname, emp.firstname as is_fname, tbl_employee.firstname as re_fname, tbl_employee.lastname as re_lname, SUM(br.quantity) as quantity, br.date_borrowed, br.due_date, br.date_return FROM tbl_borrowed AS br JOIN tbl_books AS bk ON br.book_id=bk.book_id JOIN tbl_borrowers AS brw ON br.borrower_id=brw.borrower_id JOIN tbl_employee AS emp ON br.user_id=emp.user_id JOIN tbl_employee  ON br.received_userid=tbl_employee.user_id WHERE br.remarks='Damaged' AND br.borrower_id='$borrower_id' AND br.book_id='$book_id' GROUP BY br.book_id, br.date_borrowed, br.due_date, br.date_return, br.borrower_id, br.user_id, br.received_userid";
			$data = $borrowed->searchRecord($sql);
			$count = 0;
			foreach($data as $value) {
				
				$data[$count]['date_borrowed'] = date('M d, Y',strtotime($value['date_borrowed']));
				$data[$count]['due_date'] = date('M d, Y',strtotime($value['due_date']));
				$data[$count]['date_return'] = date('M d, Y',strtotime($value['date_return']));
				$count++;
			}
			echo json_encode(array("data" =>$data));
		}
		//okay
		else if($_POST['action'] == "search logs") {
			
			$month = $_POST['month'];
			$year = $_POST['year'];
			$date = $year."-".$month;
			$date = date('F Y',strtotime($date));
			$sql = "SELECT tbl_borrowed.borrowed_id, tbl_borrowed.account_no, tbl_books.title, tbl_books.author, tbl_borrowers.firstname, tbl_borrowers.lastname, tbl_borrowed.date_borrowed, tbl_borrowed.remarks, tbl_borrowed.received_userid FROM tbl_borrowed JOIN tbl_copy ON tbl_borrowed.account_no=tbl_copy.account_no JOIN tbl_books ON tbl_copy.book_id=tbl_books.book_id JOIN tbl_borrowers ON tbl_borrowed.borrower_id=tbl_borrowers.borrower_id JOIN tbl_booklogs ON tbl_borrowed.booklogs_id=tbl_booklogs.booklogs_id WHERE EXTRACT(YEAR FROM tbl_borrowed.date_borrowed)='$year' AND EXTRACT(MONTH FROM tbl_borrowed.date_borrowed)='$month' ORDER BY tbl_booklogs.date DESC";
			$data = $borrowed->searchRecord($sql); 
			echo json_encode(array("data" =>$data, "date" =>$date));
		}
		//okay
		else if($_POST['action'] == "logs_datareturned" ) {
			
			$booklogs_id = $_POST['booklogs_id'];
			$sql = "SELECT tbl_borrowed.account_no, tbl_borrowed.status, tbl_books.title, tbl_books.author, tbl_employee.firstname, tbl_employee.lastname, emp_re.firstname as re_fname, emp_re.lastname as re_lname, tbl_borrowers.firstname as bo_fname, tbl_borrowers.lastname as bo_lname, tbl_borrowed.date_borrowed, tbl_borrowed.date_returned FROM tbl_booklogs JOIN tbl_books ON tbl_booklogs.book_id=tbl_books.book_id JOIN tbl_borrowed ON tbl_booklogs.booklogs_id=tbl_borrowed.booklogs_id2 JOIN tbl_employee ON tbl_booklogs.user_id=tbl_employee.user_id JOIN tbl_employee as emp_re ON tbl_borrowed.received_userid=emp_re.user_id JOIN tbl_borrowers ON tbl_booklogs.borrower_id=tbl_borrowers.borrower_id WHERE tbl_booklogs.booklogs_id='$booklogs_id'";
			$data = $borrowed->displayRecord($sql);
			$count = 0;
			if(count($data) > 0) {
				foreach($data as $value) {
				
					$data[$count]['date_borrowed'] = date('M d, Y',strtotime($value['date_borrowed']));
					$data[$count]['date_returned'] = date('M d, Y',strtotime($value['date_returned']));
					$count++;
				}
			}
			echo json_encode(array("data" =>$data));
		}
		//okay
		else if($_POST['action'] == "logs_databorrowed" ) {
			
			$booklogs_id = $_POST['booklogs_id'];
			$sql = "SELECT tbl_borrowed.account_no, tbl_books.title, tbl_books.author, tbl_booklogs.date, tbl_employee.firstname, tbl_employee.lastname FROM tbl_booklogs JOIN tbl_books ON tbl_booklogs.book_id=tbl_books.book_id JOIN tbl_borrowed ON tbl_booklogs.booklogs_id=tbl_borrowed.booklogs_id JOIN tbl_employee ON tbl_booklogs.user_id=tbl_employee.user_id WHERE tbl_booklogs.booklogs_id='$booklogs_id'";
			$data = $borrowed->displayRecord($sql);
			$count = 0;
			if(count($data) > 0) {
				foreach($data as $value) {
				
					$data[$count]['date'] = date('M d, Y',strtotime($value['date']));
					$count++;
				}
			}
			
			echo json_encode(array("data" =>$data));
		}
		//okay
		else if($_POST['action'] == "borrowed_logs" ) {
			
			$borrowed_id = $_POST['borrowed_id'];
			$sql = "SELECT tbl_borrowed.borrowed_id, tbl_borrowed.account_no, tbl_books.title, tbl_books.author, tbl_borrowers.firstname, tbl_borrowers.lastname, tbl_employee.firstname as emp_fname, tbl_employee.lastname as emp_lname, tbl_borrowed.remarks, tbl_borrowed.date_borrowed, tbl_borrowed.received_userid, tbl_copy.status FROM tbl_borrowed JOIN tbl_booklogs ON tbl_borrowed.booklogs_id=tbl_booklogs.booklogs_id JOIN tbl_copy ON tbl_borrowed.account_no=tbl_copy.account_no JOIN tbl_books ON tbl_copy.book_id=tbl_books.book_id JOIN tbl_borrowers ON tbl_borrowed.borrower_id=tbl_borrowers.borrower_id JOIN tbl_employee ON tbl_borrowed.user_id=tbl_employee.user_id WHERE tbl_borrowed.borrowed_id='$borrowed_id'";
			$data = $borrowed->displayRecord($sql);
			$count = 0;
			
			if(count($data) > 0) {
				foreach($data as $value) {
				
					$data[$count]['date_borrowed'] = date('M d, Y',strtotime($value['date_borrowed']));
					$count++;
				}
			}
			echo json_encode(array("data" =>$data));
		}
		//okay
		else if($_POST['action'] == "returned_logs" ) {
			
			$borrowed_id = $_POST['borrowed_id'];
			$sql = "SELECT tbl_borrowed.borrowed_id, tbl_borrowed.account_no, tbl_books.title, tbl_books.author, tbl_borrowers.firstname, tbl_borrowers.lastname, tbl_employee.firstname as emp_fname, tbl_employee.lastname as emp_lname, tbl_borrowed.remarks, tbl_borrowed.date_borrowed, tbl_borrowed.received_userid, tbl_borrowed.date_returned, emp_re.firstname as re_fname, emp_re.lastname as re_lname, tbl_copy.status FROM tbl_borrowed JOIN tbl_booklogs ON tbl_borrowed.booklogs_id=tbl_booklogs.booklogs_id JOIN tbl_copy ON tbl_borrowed.account_no=tbl_copy.account_no JOIN tbl_books ON tbl_copy.book_id=tbl_books.book_id JOIN tbl_borrowers ON tbl_borrowed.borrower_id=tbl_borrowers.borrower_id JOIN tbl_employee ON tbl_borrowed.user_id=tbl_employee.user_id JOIN tbl_employee as emp_re ON tbl_borrowed.received_userid=emp_re.user_id WHERE tbl_borrowed.borrowed_id='$borrowed_id'";
			$data = $borrowed->displayRecord($sql);
			$count = 0;
			
			if(count($data) > 0) {
				foreach($data as $value) {
				
					$data[$count]['date_borrowed'] = date('M d, Y',strtotime($value['date_borrowed']));
					$data[$count]['date_returned'] = date('M d, Y',strtotime($value['date_returned']));
					$count++;
				}
			}
			echo json_encode(array("data" =>$data));
		}
		
		//okay
		else if($_POST['action'] == "borrow" ) {
			
			if(isset($_POST['borrowed_token'])) {
				
				$borrower_id = $_POST['borrower'];
				$date_borrowed = date("Y-m-d",strtotime($_POST['date_borrowed']));
				$purpose = $_POST['purpose'];
				$user_id = $_SESSION['user_id'];
				$result = "false";
				$status = "Okay";
				$remarks = "Borrowed";
				$booklogs_list = array();
				$sql = "SELECT MAX(booklogs_id) as logid FROM tbl_booklogs";
				$data_logs = $borrowed->searchRecord($sql);
				$log_id = $data_logs[0]['logid'];
				if($log_id == null) {
					$log_id = 1;
				}
				else {
					$log_id = $log_id + 1;
				}
				
				$sql = "SELECT tbl_copy.book_id FROM tbl_temp JOIN tbl_copy ON tbl_temp.account_no=tbl_copy.account_no WHERE user_id='$user_id' GROUP BY tbl_copy.book_id";
				$data = $borrowed->displayRecord($sql);
				if(count($data) > 0){
					foreach($data as $value) {
						
						$list = array("book_id" =>$value['book_id'], "booklogs_id" =>$log_id);
						array_push($booklogs_list, $list);
						$log_id++;
					}
				}
				
			
				$sql = "SELECT * FROM tbl_temp JOIN tbl_copy ON tbl_temp.account_no=tbl_copy.account_no WHERE user_id='$user_id'";
				$data = $borrowed->displayRecord($sql);
				if(count($data) > 0){
					foreach($data as $value) {
						
						$account_no = $value['account_no'];
						$book_id = $value['book_id'];
						$booklogs_id = 0;
						
						$sql = "SELECT MAX(borrowed_id) as borrowed_id FROM tbl_borrowed";
						$data2 = $borrowed->displayRecord($sql);
						$borrowed_id = 0;

						if($data2[0]['borrowed_id'] == null) {
							$borrowed_id = 1;
						}
						else {
							$borrowed_id = $data2[0]['borrowed_id'] + 1;
						}
						
						if(count($booklogs_list) > 0) {
							foreach($booklogs_list as $value2) {
								if($value2['book_id'] == $book_id) {
									$booklogs_id = $value2['booklogs_id'];
								}
							}
						}
						
						$sql = "INSERT INTO tbl_borrowed (borrowed_id, status, remarks, purpose, date_borrowed, account_no, borrower_id, user_id, booklogs_id) VALUES($borrowed_id, '$status', '$remarks', '$purpose', '$date_borrowed', '$account_no', '$borrower_id', '$user_id', $booklogs_id)";
						$result = $borrowed->addRecord($sql);
						
						$sql = "UPDATE tbl_copy SET remarks='$remarks' WHERE account_no='$account_no'";
						$result = $borrowed->updateRecord($sql);
						
						
					}
				}
				
				$sql = "SELECT COUNT(tbl_temp.account_no) as total, tbl_copy.book_id, tbl_books.qty_out FROM tbl_temp JOIN tbl_copy ON tbl_temp.account_no=tbl_copy.account_no JOIN tbl_books ON tbl_copy.book_id=tbl_books.book_id WHERE tbl_temp.user_id='$user_id' GROUP BY tbl_copy.book_id";
				$data_inventory = $borrowed->displayRecord($sql);
				
				if(count($data_inventory)) {
					foreach($data_inventory as $value) {
						$qty_in = $value['total'];
						$db_qtyout = $value['qty_out'];
						$book_id = $value['book_id'];
						$quantity = 0;
						$booklogs_id = 0;
						
						if($db_qtyout == null || $db_qtyout == 0) {
							$quantity = $qty_in;
						}
						else {
							$quantity = $qty_in + $db_qtyout;
						}
						
						$sql = "UPDATE tbl_books SET qty_out=$quantity WHERE book_id='$book_id'";
						$result = $borrowed->updateRecord($sql);
						
						if(count($booklogs_list) > 0) {
							foreach($booklogs_list as $value2) {
								if($value2['book_id'] == $book_id) {
									$booklogs_id = $value2['booklogs_id'];
								}
							}
						}
						
						$sql = "INSERT INTO tbl_booklogs (booklogs_id,quantity, date, book_id, user_id, borrower_id, status) VALUES($booklogs_id, $qty_in, '$date_borrowed', '$book_id', '$user_id', '$borrower_id', '$remarks')";
						$result = $borrowed->addRecord($sql);
					
					}
				}
				
				$sql = "DELETE FROM tbl_temp WHERE user_id='$user_id'";
				$result = $borrowed->deleteRecord($sql);
				echo json_encode(array("data" =>$result));
				
				
				
			}
		}
		
		//okay
		else if($_POST['action'] == "select" ) {
			
			if(isset($_POST['borrowed_token'])) {
				
				$result = "";
				$user_id = $_SESSION['user_id'];
				$id = $_POST['id'];
				
				for ($count = 0; $count < count($id); $count++) {
					$account_no = $id[$count];
					$sql = "SELECT * FROM tbl_temp WHERE account_no='$account_no'";
					$total_row = $borrowed->validate($sql);
					if($total_row == 0) {
						$sql = "SELECT MAX(temp_id) as temp_id FROM tbl_temp";
						$data_logs = $borrowed->searchRecord($sql);
						$temp_id = $data_logs[0]['temp_id'];
						if($temp_id == null) {
							$temp_id = 1;
						}
						else {
							$temp_id = $temp_id + 1;
						}
						$sql = "INSERT INTO tbl_temp (temp_id, account_no, user_id) VALUES($temp_id, '$account_no', '$user_id')";
						$result = $borrowed->addRecord($sql);
					}
					else {
						$result = "true";
					}
				}
				echo $result;
			}
		}
		//okay
		else if($_POST['action'] == "cancel") {
			if(isset($_POST['borrowed_token'])) {
				
				$result = false;
				$temp_id = $_POST['temp_id'];
				$user_id = $_SESSION['user_id'];
				$sql = "DELETE FROM tbl_temp WHERE temp_id='$temp_id' and user_id='$user_id'";
				$result = $borrowed->deleteRecord($sql);
				echo json_encode(array("data" =>$result));
			}
		}
		
		//okay
		else if($_POST['action'] == "remove_all") {
			if(isset($_POST['borrowed_token'])) {
				$result = false;
				$user_id = $_SESSION['user_id'];
				$sql = "DELETE FROM tbl_temp WHERE user_id='$user_id'";
				$result = $borrowed->deleteRecord($sql);
				echo json_encode(array("data" =>$result));
			}
		}
		else if($_POST['action'] == "borrowerby_subject") {
			$month = $_POST['month'];
			$get_month = (int)date("m");
			$m = $_POST['month'];
			$year = $_POST['year'];
			$borrowed_data = array();
			$total_qty = 0;
			$string_month = array('01' =>"January", '02' =>"February", '03' =>"March", '04' =>"April", '05' =>"May", '06' =>"June", '07' =>"July",'08' =>"August",'09' =>"Septempber", '10' =>"October", '11' =>"November", '12' =>"December");
			$classification_list = array("000-099", "100-199", "200-299", "300-399", "400-499", "500-599", "600-699", "700-799", "800-899", "900-999");
			if($get_month >= $m){
				foreach($classification_list as $value) {
					$total = count($borrowed->displayRecord("SELECT tbl_borrowed.account_no, tbl_books.classification FROM tbl_borrowed JOIN tbl_copy ON tbl_borrowed.account_no=tbl_copy.account_no JOIN tbl_books ON tbl_copy.book_id=tbl_books.book_id WHERE tbl_books.classification='$value' AND EXTRACT(MONTH FROM tbl_borrowed.date_borrowed) = '$month' AND EXTRACT(YEAR FROM tbl_borrowed.date_borrowed) = '$year'"));
					$total_qty = $total_qty + $total;
					array_push($borrowed_data, $total);
				}
				array_push($borrowed_data, $total_qty);
			}
			else {
				$borrowed_data = array("", "", "", "", "", "", "", "", "", "", "");
			}
			
			
			echo json_encode(array("data" =>$borrowed_data, "month_name" =>$string_month[$month]));
		}
		else {
			header("location:dashboard.php");
		}
	}
?>