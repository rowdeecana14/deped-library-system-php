<?php
	require_once "model.php";
	session_start();
	date_default_timezone_set('Asia/Manila');
	$year =  date("Y");

	if(isset($_POST['action'])) {
		
		$dashboard = new Model;
		$database = new Database;
		
		if($_POST['action'] == "Available") {
			$sql = "SELECT tbl_books.title, tbl_books.author, tbl_books.pages, tbl_books.fund, tbl_books.copyright, tbl_books.isbn, tbl_books.publisher, (tbl_books.qty_in-tbl_books.qty_out) as quantity FROM tbl_books  WHERE (tbl_books.qty_in-tbl_books.qty_out) > 0 ORDER BY tbl_books.title";
			$data = $dashboard->displayRecord($sql);
			echo json_encode(array("data" =>$data));
		}
		else if($_POST['action'] == "Borrowed") {
			
			$data = array();
			$sql = "SELECT * FROM tbl_books JOIN tbl_copy ON tbl_books.book_id=tbl_copy.book_id WHERE tbl_copy.remarks='Borrowed' GROUP BY tbl_copy.book_id ORDER BY tbl_books.title";
			$data2 = $dashboard->displayRecord($sql);
			foreach($data2 as $value) {
				$book_id = $value['book_id'];
				$sql = "SELECT tbl_books.title, tbl_books.author, tbl_books.pages, tbl_books.fund, tbl_books.copyright, tbl_books.isbn, tbl_books.publisher FROM tbl_borrowed JOIN tbl_copy ON tbl_borrowed.account_no=tbl_copy.account_no JOIN tbl_books ON tbl_copy.book_id=tbl_books.book_id JOIN tbl_borrowers ON tbl_borrowed.borrower_id=tbl_borrowers.borrower_id WHERE tbl_copy.remarks='Borrowed' AND tbl_copy.book_id='$book_id' GROUP BY tbl_copy.account_no";
				$list = $dashboard->displayRecord($sql);
				$get_data = array("title" =>$list[0]['title'], "author" =>$list[0]['author'], "pages" =>$list[0]['pages'], "fund" =>$list[0]['fund'], "copyright" =>$list[0]['copyright'], "isbn" =>$list[0]['isbn'], "publisher" =>$list[0]['publisher'], "quantity" =>count($list));
				array_push($data, $get_data);
			}
			
			echo json_encode(array("data" =>$data));
		}
		else if ($_POST['action'] == "Damaged"){ 
			$sql = "SELECT tbl_books.title, tbl_books.author, tbl_books.pages, tbl_books.fund, tbl_books.copyright, tbl_books.isbn, tbl_books.publisher, COUNT(tbl_copy.account_no) as quantity FROM tbl_books JOIN tbl_copy ON tbl_books.book_id=tbl_copy.book_id JOIN tbl_borrowed ON tbl_copy.account_no=tbl_borrowed.account_no WHERE tbl_copy.status='Damaged' AND tbl_borrowed.status='Damaged' GROUP BY tbl_copy.book_id ORDER BY tbl_books.title";
			$data = $dashboard->displayRecord($sql);
			echo json_encode(array("data" =>$data));
		}
		else if ($_POST['action'] == "Lost"){ 
			$sql = "SELECT tbl_books.title, tbl_books.author, tbl_books.pages, tbl_books.fund, tbl_books.copyright, tbl_books.isbn, tbl_books.publisher, COUNT(tbl_copy.account_no) as quantity FROM tbl_books JOIN tbl_copy ON tbl_books.book_id=tbl_copy.book_id JOIN tbl_borrowed ON tbl_copy.account_no=tbl_borrowed.account_no WHERE tbl_copy.status='Lost' AND tbl_borrowed.status='Lost' GROUP BY tbl_copy.book_id ORDER BY tbl_books.title";
			$data = $dashboard->displayRecord($sql);
			echo json_encode(array("data" =>$data));
		}
		else if($_POST['action'] == "month") {
			$month = $_POST['month'];
			$string_month = array("January" =>"01", 'February' =>"02", 'March' =>"03", 'April' =>"04", 'May' =>"05", 'June' =>"06", '07' =>"July",'August' =>"08",'Septempber' =>"09", 'October' =>"10", 'November' =>"11", 'December' =>"12");
			$getmonth = $string_month[$month];
			$sql = "SELECT tbl_books.title, tbl_books.author, tbl_books.pages, tbl_books.fund, tbl_books.copyright, tbl_books.publisher, tbl_books.isbn, COUNT(tbl_borrowed.date_borrowed) as quantity FROM tbl_borrowed JOIN tbl_copy ON tbl_borrowed.account_no=tbl_copy.account_no JOIN tbl_books ON tbl_copy.book_id=tbl_books.book_id WHERE EXTRACT(YEAR FROM date_borrowed)='$year' AND EXTRACT(MONTH FROM date_borrowed)='$getmonth' GROUP BY tbl_books.book_id";
			$data = $dashboard->displayRecord($sql);
			echo json_encode(array("data" =>$data));
		}
		else if($_POST['action'] == "calendar") {
			$date = $_POST['date'];
			$sql = "SELECT tbl_books.isbn, tbl_books.title, tbl_books.grade, tbl_books.author, tbl_books.category, SUM(tbl_borrowed.quantity) as quantity FROM tbl_borrowed JOIN tbl_books ON tbl_borrowed.book_id=tbl_books.book_id WHERE tbl_borrowed.due_date='$date' AND tbl_borrowed.status='Borrowed'  GROUP BY tbl_borrowed.book_id";
			$data = $dashboard->displayRecord($sql);
			echo json_encode(array("data" =>$data));
		}
		else if($_POST['action'] == "add_schedule") {
			$date = $_POST['selected_date'];
			$title = $_POST['title'];
			$description = $_POST['description'];
			$date = explode(" ", $date);
			$date = $date[3]."-".$date[1]."-".$date[2];
			$date = date("Y-m-d", strtotime($date));
			$sql = "SELECT MAX(event_id) as event_id FROM tbl_events";
			$data_logs = $dashboard->searchRecord($sql);
			$id = $data_logs[0]['event_id'];
			if($id == null) {
				$id = 1;
			}
			else {
				$id = $id + 1;
			}
			
			$sql = "INSERT INTO tbl_events (event_id, title, description, date) VALUES($id, '$title', '$description', '$date')";
			$data = $dashboard->addRecord($sql);
			echo json_encode(array("data" =>$data));
		}
		else if($_POST['action'] == "calendar_view") {
			$id = $_POST['id'];
			$sql = "SELECT * FROM tbl_events WHERE event_id='$id'";
			$data = $dashboard->displayRecord($sql);
			echo json_encode(array("data" =>$data));
		}
		else if($_POST['action'] == "calendar_update") {
			$id = $_POST['id'];
			$title = $_POST['title2'];
			$description = $_POST['description2'];
			
			$sql = "UPDATE tbl_events SET title='$title', description='$description' WHERE event_id='$id'";
			$data = $dashboard->updateRecord($sql);
			echo json_encode(array("data" =>$data));
		}
		else if($_POST['action'] == "calendar_remove") {
			$id = $_POST['id'];
			
			$sql = "DELETE FROM tbl_events WHERE event_id='$id'";
			$data = $dashboard->deleteRecord($sql);
			echo json_encode(array("data" =>$data));
		}
		else {
			echo json_encode(array("data" =>"error"));
		}
	}
?>