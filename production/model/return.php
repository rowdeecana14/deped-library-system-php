<?php
	//defined('BASEPATH') OR exit('No direct script access allowed');
	require_once "model.php";
	session_start();
	date_default_timezone_set('Asia/Manila');
	if(isset($_POST['action'])) {
		
		$return = new Model;
		$database = new Database;
		
		if($_POST['action'] == "display") {
			
			$sql = "SELECT br.book_id, bk.isbn, bk.title, br.quantity, brw.firstname, brw.lastname, br.date_borrowed, br.due_date, br.date_return FROM tbl_borrowed AS br JOIN tbl_books AS bk ON br.book_id=bk.book_id JOIN tbl_borrowers AS brw ON br.borrower_id=brw.borrower_id WHERE br.status='Return'";
			$data = $return->displayRecord($sql);
			$count = 0;
			
			foreach($data as $value) {
				
				$data[$count]['date_borrowed'] = date('M d, Y',strtotime($value['date_borrowed']));
				$data[$count]['due_date'] = date('M d, Y',strtotime($value['due_date']));
				$data[$count]['date_return'] = date('M d, Y',strtotime($value['date_return']));
				$count++;
			}
			
			echo json_encode(array("data" =>$data));
		}
		
		else if($_POST['action'] == "book logs") {
			
			$book_id = $_POST['book_id'];
			$month = $_POST['month'];
			$year = $_POST['year'];
			$sql = "SELECT emp.firstname as ei_fname, emp.lastname as ei_lname, tbl_employee.firstname as re_fname, tbl_employee.lastname as re_lname, tbl_borrowers.firstname b_fname, tbl_borrowers.lastname as b_lname,  SUM(tbl_borrowed.quantity) as quantity, tbl_borrowed.date_borrowed, tbl_borrowed.due_date, tbl_borrowed.date_return FROM tbl_borrowed JOIN tbl_books ON tbl_borrowed.book_id=tbl_books.book_id JOIN tbl_employee as emp ON tbl_borrowed.user_id=emp.user_id JOIN tbl_employee ON tbl_borrowed.received_userid=tbl_employee.user_id JOIN tbl_borrowers ON tbl_borrowed.borrower_id=tbl_borrowers.borrower_id WHERE EXTRACT(YEAR FROM tbl_borrowed.date_borrowed)='$year' AND EXTRACT(MONTH FROM tbl_borrowed.date_borrowed)='$month' AND tbl_borrowed.book_id='$book_id' AND tbl_borrowed.status='Returned' GROUP BY tbl_borrowed.user_id, tbl_borrowed.borrower_id, tbl_borrowed.received_userid, tbl_borrowed.date_borrowed, tbl_borrowed.due_date, tbl_borrowed.date_return";
			$data = $return->displayRecord($sql);
			$count = 0;
			foreach($data as $value) {
				
				$data[$count]['date_borrowed'] = date('M d, Y',strtotime($value['date_borrowed']));
				$data[$count]['due_date'] = date('M d, Y',strtotime($value['due_date']));
				$data[$count]['date_return'] = date('M d, Y',strtotime($value['date_return']));
				$count++;
			}
			echo json_encode(array("data" =>$data));
		}
		else if($_POST['action'] == "search logs") {
			
			$month = $_POST['month'];
			$year = $_POST['year'];
			$date = $year."-".$month;
			$date = date('F Y',strtotime($date));
			$sql = "SELECT tbl_books.book_id, tbl_books.isbn, tbl_books.title, tbl_books.grade, tbl_books.author, tbl_books.category, SUM(tbl_borrowed.quantity) as quantity FROM tbl_borrowed JOIN tbl_books ON tbl_borrowed.book_id=tbl_books.book_id  WHERE EXTRACT(YEAR FROM tbl_borrowed.date_borrowed)='$year' AND EXTRACT(MONTH FROM tbl_borrowed.date_borrowed)='$month' AND tbl_borrowed.status='Returned' GROUP BY tbl_borrowed.book_id";
			$data = $return->searchRecord($sql); 
			echo json_encode(array("data" =>$data, "date" =>$date));
		}
		else if($_POST['action'] == "damaged") {
			
			$sql = "SELECT br.book_id, bk.isbn, bk.title, br.quantity, brw.firstname, brw.lastname, br.date_borrowed, br.due_date, br.date_return FROM tbl_borrowed AS br JOIN tbl_books AS bk ON br.book_id=bk.book_id JOIN tbl_borrowers AS brw ON br.borrower_id=brw.borrower_id WHERE br.remarks='Damaged'";
			$data = $return->displayRecord($sql);
			$count = 0;
			
			foreach($data as $value) {
				
				$data[$count]['date_borrowed'] = date('M d, Y',strtotime($value['date_borrowed']));
				$data[$count]['due_date'] = date('M d, Y',strtotime($value['due_date']));
				$data[$count]['date_return'] = date('M d, Y',strtotime($value['date_return']));
				$count++;
			}
			
			echo json_encode(array("data" =>$data));
		}
		
		else if($_POST['action'] == "damaged view") {
			
			$book_id = $_POST['book_id'];
			$borrower_id = $_POST['borrower_id'];
			$sql = "SELECT br.book_id, bk.isbn, bk.title, SUM(br.quantity) as quantity, brw.firstname, brw.lastname, emp_is.firstname as is_fname, emp_is.lastname as is_lname, emp_re.firstname as re_fname, emp_re.lastname as re_lname, br.date_borrowed, br.due_date, br.date_return FROM tbl_borrowed AS br JOIN tbl_books AS bk ON br.book_id=bk.book_id JOIN tbl_borrowers AS brw ON br.borrower_id=brw.borrower_id JOIN tbl_employee as emp_is ON br.user_id=emp_is.user_id JOIN tbl_employee emp_re ON br.received_userid=emp_re.user_id  WHERE br.remarks='Damaged' AND br.book_id='$book_id' AND br.borrower_id='$borrower_id' GROUP BY br.user_id, br.date_borrowed, br.due_date, br.date_return";
			$data = $return->displayRecord($sql);
			$count = 0;
			
			foreach($data as $value) {
				
				$data[$count]['date_borrowed'] = date('M d, Y',strtotime($value['date_borrowed']));
				$data[$count]['due_date'] = date('M d, Y',strtotime($value['due_date']));
				$data[$count]['date_return'] = date('M d, Y',strtotime($value['date_return']));
				$count++;
			}
			
			echo json_encode(array("data" =>$data));
		}
		else if($_POST['action'] == "lost_view") {
			
			$book_id = $_POST['book_id'];
			$borrower_id = $_POST['borrower_id'];
			$sql = "SELECT br.book_id, bk.isbn, bk.title, SUM(br.quantity) as quantity, brw.firstname, brw.lastname, emp_is.firstname as is_fname, emp_is.lastname as is_lname, emp_re.firstname as re_fname, emp_re.lastname as re_lname, br.date_borrowed, br.due_date, br.date_return FROM tbl_borrowed AS br JOIN tbl_books AS bk ON br.book_id=bk.book_id JOIN tbl_borrowers AS brw ON br.borrower_id=brw.borrower_id JOIN tbl_employee as emp_is ON br.user_id=emp_is.user_id JOIN tbl_employee emp_re ON br.received_userid=emp_re.user_id  WHERE br.remarks='Lost' AND br.book_id='$book_id' AND br.borrower_id='$borrower_id' GROUP BY br.user_id, br.date_borrowed, br.due_date, br.date_return";
			$data = $return->displayRecord($sql);
			$count = 0;
			
			foreach($data as $value) {
				
				$data[$count]['date_borrowed'] = date('M d, Y',strtotime($value['date_borrowed']));
				$data[$count]['due_date'] = date('M d, Y',strtotime($value['due_date']));
				$data[$count]['date_return'] = date('M d, Y',strtotime($value['date_return']));
				$count++;
			}
			
			echo json_encode(array("data" =>$data));
		}
		else if($_POST['action'] == "search damaged") {
			
			$search = "%{$_POST['search']}%";
			$sql = "SELECT * FROM tbl_borrowed JOIN tbl_books ON tbl_borrowed.book_id=tbl_books.book_id WHERE (tbl_books.isbn LIKE '$search' OR tbl_books.title LIKE '$search' OR tbl_books.grade LIKE '$search' OR tbl_books.author LIKE '$search' OR tbl_books.category LIKE '$search') AND (tbl_borrowed.remarks='Damaged') GROUP BY tbl_borrowed.book_id";
			$data = $return->searchRecord($sql); 
			echo json_encode(array("data" =>$data));
		}
		else if($_POST['action'] == "search_lost") {
			
			$search = "%{$_POST['search']}%";
			$sql = "SELECT * FROM tbl_borrowed JOIN tbl_books ON tbl_borrowed.book_id=tbl_books.book_id WHERE (tbl_books.isbn LIKE '$search' OR tbl_books.title LIKE '$search' OR tbl_books.grade LIKE '$search' OR tbl_books.author LIKE '$search' OR tbl_books.category LIKE '$search') AND (tbl_borrowed.remarks='Lost') GROUP BY tbl_borrowed.book_id";
			$data = $return->searchRecord($sql); 
			echo json_encode(array("data" =>$data));
		}
		else if($_POST['action'] == "select book") {
			
			$book_id = $_POST['book_id'];
			$sql = "SELECT tbl_borrowed.borrower_id, tbl_books.isbn, tbl_books.title, tbl_books.grade, SUM(tbl_borrowed.quantity) as quantity, tbl_borrowers.firstname, tbl_borrowers.lastname, emp_is.firstname as ei_fname, emp_is.lastname as ei_lname, emp_re.firstname as er_fname, emp_re.lastname as er_lname, tbl_borrowed.date_borrowed, tbl_borrowed.due_date, tbl_borrowed.date_return FROM tbl_borrowed JOIN tbl_books ON tbl_borrowed.book_id=tbl_books.book_id JOIN tbl_borrowers ON tbl_borrowed.borrower_id=tbl_borrowers.borrower_id JOIN tbl_employee as emp_is ON tbl_borrowed.user_id=emp_is.user_id JOIN tbl_employee as emp_re ON tbl_borrowed.received_userid=emp_re.user_id WHERE tbl_borrowed.remarks='Damaged' AND tbl_borrowed.book_id='$book_id' GROUP BY tbl_borrowed.borrower_id ORDER BY tbl_borrowed.date_borrowed DESC";
			$data = $return->searchRecord($sql); 
			echo json_encode(array("data" =>$data));
		}
		else if($_POST['action'] == "select_lost") {
			
			$book_id = $_POST['book_id'];
			$sql = "SELECT tbl_borrowed.borrower_id, tbl_books.isbn, tbl_books.title, tbl_books.grade, SUM(tbl_borrowed.quantity) as quantity, tbl_borrowers.firstname, tbl_borrowers.lastname, emp_is.firstname as ei_fname, emp_is.lastname as ei_lname, emp_re.firstname as er_fname, emp_re.lastname as er_lname, tbl_borrowed.date_borrowed, tbl_borrowed.due_date, tbl_borrowed.date_return FROM tbl_borrowed JOIN tbl_books ON tbl_borrowed.book_id=tbl_books.book_id JOIN tbl_borrowers ON tbl_borrowed.borrower_id=tbl_borrowers.borrower_id JOIN tbl_employee as emp_is ON tbl_borrowed.user_id=emp_is.user_id JOIN tbl_employee as emp_re ON tbl_borrowed.received_userid=emp_re.user_id WHERE tbl_borrowed.remarks='Lost' AND tbl_borrowed.book_id='$book_id' GROUP BY tbl_borrowed.borrower_id ORDER BY tbl_borrowed.date_borrowed DESC";
			$data = $return->searchRecord($sql); 
			echo json_encode(array("data" =>$data));
		}
		else if($_POST['action'] == "lost_logs") {
			
			$book_id = $_POST['book_id'];
			$month = $_POST['month'];
			$year = $_POST['year'];
			$sql = "SELECT emp.firstname as ei_fname, emp.lastname as ei_lname, tbl_employee.firstname as re_fname, tbl_employee.lastname as re_lname, tbl_borrowers.firstname b_fname, tbl_borrowers.lastname as b_lname,  SUM(tbl_borrowed.quantity) as quantity, tbl_borrowed.date_borrowed, tbl_borrowed.due_date, tbl_borrowed.date_return FROM tbl_borrowed JOIN tbl_books ON tbl_borrowed.book_id=tbl_books.book_id JOIN tbl_employee as emp ON tbl_borrowed.user_id=emp.user_id JOIN tbl_employee ON tbl_borrowed.received_userid=tbl_employee.user_id JOIN tbl_borrowers ON tbl_borrowed.borrower_id=tbl_borrowers.borrower_id WHERE EXTRACT(YEAR FROM tbl_borrowed.date_borrowed)='$year' AND EXTRACT(MONTH FROM tbl_borrowed.date_borrowed)='$month' AND tbl_borrowed.book_id='$book_id' AND tbl_borrowed.remarks='Lost' GROUP BY tbl_borrowed.user_id, tbl_borrowed.borrower_id, tbl_borrowed.received_userid, tbl_borrowed.date_borrowed, tbl_borrowed.due_date, tbl_borrowed.date_return";
			$data = $return->displayRecord($sql);
			$count = 0;
			foreach($data as $value) {
				
				$data[$count]['date_borrowed'] = date('M d, Y',strtotime($value['date_borrowed']));
				$data[$count]['due_date'] = date('M d, Y',strtotime($value['due_date']));
				$data[$count]['date_return'] = date('M d, Y',strtotime($value['date_return']));
				$count++;
			}
			echo json_encode(array("data" =>$data));
		}
		
		else if($_POST['action'] == "damaged logs") {
			
			$book_id = $_POST['book_id'];
			$month = $_POST['month'];
			$year = $_POST['year'];
			$sql = "SELECT emp.firstname as ei_fname, emp.lastname as ei_lname, tbl_employee.firstname as re_fname, tbl_employee.lastname as re_lname, tbl_borrowers.firstname b_fname, tbl_borrowers.lastname as b_lname,  SUM(tbl_borrowed.quantity) as quantity, tbl_borrowed.date_borrowed, tbl_borrowed.due_date, tbl_borrowed.date_return FROM tbl_borrowed JOIN tbl_books ON tbl_borrowed.book_id=tbl_books.book_id JOIN tbl_employee as emp ON tbl_borrowed.user_id=emp.user_id JOIN tbl_employee ON tbl_borrowed.received_userid=tbl_employee.user_id JOIN tbl_borrowers ON tbl_borrowed.borrower_id=tbl_borrowers.borrower_id WHERE EXTRACT(YEAR FROM tbl_borrowed.date_borrowed)='$year' AND EXTRACT(MONTH FROM tbl_borrowed.date_borrowed)='$month' AND tbl_borrowed.book_id='$book_id' AND tbl_borrowed.remarks='Damaged' GROUP BY tbl_borrowed.user_id, tbl_borrowed.borrower_id, tbl_borrowed.received_userid, tbl_borrowed.date_borrowed, tbl_borrowed.due_date, tbl_borrowed.date_return";
			$data = $return->displayRecord($sql);
			$count = 0;
			foreach($data as $value) {
				
				$data[$count]['date_borrowed'] = date('M d, Y',strtotime($value['date_borrowed']));
				$data[$count]['due_date'] = date('M d, Y',strtotime($value['due_date']));
				$data[$count]['date_return'] = date('M d, Y',strtotime($value['date_return']));
				$count++;
			}
			echo json_encode(array("data" =>$data));
		}
		else if($_POST['action'] == "unreturned") {
			
			$sql = "SELECT tbl_books.isbn, tbl_books.title, tbl_borrowers.firstname, tbl_borrowers.lastname, tbl_borrowed.quantity, tbl_borrowed.date_borrowed, tbl_borrowed.due_date, DATEDIFF(tbl_borrowed.due_date, CURDATE()) AS days FROM tbl_borrowed JOIN tbl_borrowers ON tbl_borrowed.borrower_id=tbl_borrowers.borrower_id JOIN tbl_books ON tbl_borrowed.book_id=tbl_books.book_id WHERE tbl_borrowed.status='Borrow' ";
			$data = $return->displayRecord($sql);
			$count = 0;
			
			foreach($data as $value) {
				
				$data[$count]['date_borrowed'] = date('M d, Y',strtotime($value['date_borrowed']));
				$data[$count]['due_date'] = date('M d, Y',strtotime($value['due_date']));
				$count++;
			}
			
			echo json_encode(array("data" =>$data));
		}
		
		
		//okay
		else if($_POST['action'] == "search_lost_logs") {
			
			$month = $_POST['month'];
			$year = $_POST['year'];
			$date = $year."-".$month;
			$date = date('F Y',strtotime($date));
			$sql = "SELECT * FROM tbl_books JOIN tbl_copy ON tbl_books.book_id=tbl_copy.book_id JOIN tbl_borrowed ON tbl_copy.account_no=tbl_borrowed.account_no AND EXTRACT(YEAR FROM tbl_borrowed.date_returned)='$year' AND EXTRACT(MONTH FROM tbl_borrowed.date_returned)='$month' WHERE tbl_copy.status='Lost' AND tbl_borrowed.status='Lost' ORDER BY tbl_copy.book_id, tbl_copy.copy";
			$data = $return->searchRecord($sql); 
			echo json_encode(array("data" =>$data, "date" =>$date));
		}
		//okay
		else if($_POST['action'] == "search_damaged_logs") {
			
			$month = $_POST['month'];
			$year = $_POST['year'];
			$date = $year."-".$month;
			$date = date('F Y',strtotime($date));
			$sql = "SELECT * FROM tbl_books JOIN tbl_copy ON tbl_books.book_id=tbl_copy.book_id JOIN tbl_borrowed ON tbl_copy.account_no=tbl_borrowed.account_no AND EXTRACT(YEAR FROM tbl_borrowed.date_returned)='$year' AND EXTRACT(MONTH FROM tbl_borrowed.date_returned)='$month' WHERE tbl_copy.status='Damaged' AND tbl_borrowed.status='Damaged' ORDER BY tbl_copy.book_id, tbl_copy.copy";
			$data = $return->searchRecord($sql); 
			echo json_encode(array("data" =>$data, "date" =>$date));
		}
		
		//okay
		else if($_POST['action'] == "display_borrowed") {
			$borrower_id = $_POST['borrower_id'];
			$sql = "SELECT tbl_borrowed.borrower_id, tbl_borrowed.borrowed_id,  tbl_borrowed.account_no, tbl_books.title, tbl_books.author, tbl_books.pages, tbl_borrowers.firstname, tbl_borrowers.lastname, tbl_borrowed.date_borrowed, tbl_borrowed.remarks FROM tbl_borrowed JOIN tbl_copy ON tbl_borrowed.account_no=tbl_copy.account_no JOIN tbl_books ON tbl_copy.book_id=tbl_books.book_id JOIN tbl_borrowers ON tbl_borrowed.borrower_id=tbl_borrowers.borrower_id WHERE tbl_borrowed.remarks='Borrowed' AND tbl_borrowed.borrower_id='$borrower_id' ORDER BY tbl_copy.book_id, tbl_copy.copy";
			$data = $return->displayRecord($sql);
			
			echo json_encode(array("data" =>$data));
		}
		
		//okay
		else if($_POST['action'] == "return" ) {
			
			$bookid_data = array();
			$bookid_list = array();
			$bookid_unique = array();
			$logs = array();
			$date = date("Y-m-d H:i:s");
			$borrowed_id = $_POST['borrowed_id'];
			$date_return = date("Y-m-d", strtotime($_POST['date_return']));
			$remark_list = $_POST['remark_list'];
			$remarks = "Returned";
			$user_id = $_SESSION['user_id'];
			$borrower_id = "";
			$result = "";
			$book_id = "";
			
			$sql = "SELECT MAX(booklogs_id) as logid FROM tbl_booklogs";
			$data_logs = $return->searchRecord($sql);
			$log_id = $data_logs[0]['logid'];
			if($log_id == null) {
				$log_id = 1;
			}
			else {
				$log_id = $log_id + 1;
			}
			
			foreach($borrowed_id as $value) {
				
				$sql = "SELECT tbl_borrowed.account_no, tbl_copy.book_id , tbl_books.qty_out, tbl_borrowed.borrower_id FROM tbl_borrowed JOIN tbl_copy ON tbl_borrowed.account_no=tbl_copy.account_no JOIN tbl_books ON tbl_copy.book_id=tbl_books.book_id WHERE tbl_borrowed.borrowed_id='$value'";
				$data_get = $return->displayRecord($sql);
				$book_id = $data_get[0]['book_id'];
				$borrower_id = $data_get[0]['borrower_id'];;
				array_push($bookid_data, $book_id);
			}
			
			foreach($bookid_data as $value) {
				array_push($bookid_list, $value);
				if(!in_array($value, $bookid_unique)) {
					array_push($bookid_unique, $value);
				}
			}
			foreach($bookid_unique as $value) {
				$qty = 0;
				foreach($bookid_list as $value2) {
					if($value == $value2) {
						$qty++;
					}
				}
				$list = array("book_id" =>$value, "qty" =>$qty, "log_id" =>$log_id);
				array_push($logs, $list);
				$log_id++;
			}
			
			$length_array = count($borrowed_id);
			if($length_array > 0) {
				for($x = 0; $x < $length_array; $x++) {
					$value = $borrowed_id[$x];
					$status = $remark_list[$x];
					
					$sql = "SELECT tbl_borrowed.account_no, tbl_copy.book_id , tbl_books.qty_out FROM tbl_borrowed JOIN tbl_copy ON tbl_borrowed.account_no=tbl_copy.account_no JOIN tbl_books ON tbl_copy.book_id=tbl_books.book_id WHERE tbl_borrowed.borrowed_id='$value'";
					$data_get = $return->displayRecord($sql);
					$account_no = $data_get[0]['account_no'];
					$book_id = $data_get[0]['book_id'];
					$log_id = "";

					foreach ($logs as $value2) {
						if($value2['book_id'] == $book_id) {
							$log_id = $value2['log_id'];
						}
					}

					$sql = "UPDATE tbl_borrowed SET status='$status', remarks='$remarks', date_returned='$date_return', received_userid='$user_id', booklogs_id2=$log_id WHERE borrowed_id='$value'";
					$result = $return->updateRecord($sql);

					$sql = "UPDATE tbl_copy SET tbl_copy.remarks='Returned', status='$status' WHERE tbl_copy.account_no='$account_no'";
					$result = $return->updateRecord($sql);
				}
			}
			
			foreach($logs as $value) { 
				$log_id = $value['log_id'];
				$total_qty = $value['qty'];
				$bookid = $value['book_id'];
				
				$sql = "SELECT tbl_borrowed.account_no, tbl_copy.book_id , tbl_books.qty_out,tbl_borrowed.borrower_id FROM tbl_borrowed JOIN tbl_copy ON tbl_borrowed.account_no=tbl_copy.account_no JOIN tbl_books ON tbl_copy.book_id=tbl_books.book_id WHERE tbl_books.book_id='$bookid'";
				$data_get = $return->displayRecord($sql);
				$db_qtyout = $data_get[0]['qty_out'];
				$update_qty = $db_qtyout - $total_qty;
				
				if($status == "Okay") {
					
					$sql = "UPDATE tbl_books SET qty_out=$update_qty WHERE tbl_books.book_id='$bookid'";
					$result = $return->updateRecord($sql);
				}
				
				$sql = "INSERT INTO tbl_booklogs (booklogs_id,quantity, date, book_id, user_id, borrower_id, status) VALUES($log_id, $total_qty, '$date', '$bookid', '$user_id', '$borrower_id', '$remarks')";
				$result = $return->addRecord($sql);
				
				
			}
			echo json_encode(array("data" =>$result));
		}
	}
?>