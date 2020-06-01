<?php
   	require_once "model/controller.php";
	require_once "model/model.php";
	date_default_timezone_set('Asia/Manila');
	$auth = new Database;
	$token = $auth->generateAuth();
	$_SESSION['bookupdate_token'] = $token;
	
	if(isset($_GET['book_id']) && !empty($_GET['book_id']) && isset($_GET['book_token']) && isset($_SESSION['book_token'])) {
		
		if($_SESSION['book_token'] == $_GET['book_token']) {
			$book_id = $_GET['book_id'];
			$pass_token = $_GET['book_token'];
			$sql = "SELECT * FROM tbl_books WHERE book_id='$book_id'";
			$book = new Model;
			$data2 = $book->displayRecord($sql);
			$_SESSION['book_id'] = $_GET['book_id'];
			$_SESSION['pass_token'] = $_GET['book_token'];
			$_SESSION['array'] = $data2;
			$_SESSION['current_page'] = "books_edit.php";
		}
		else {
			header("location: dashboard.php");
		}
	}
	else {
		if(isset($_SESSION['array'])) {
			$pass_token = $_SESSION['pass_token'];
			$book_id = $_SESSION['book_id'];
			$sql = "SELECT * FROM tbl_books WHERE book_id='$book_id'";
			$book = new Model;
			$data2 = $book->displayRecord($sql);
			$_SESSION['array'] = $data2;
			$data2 = $_SESSION['array'];
		}
		else {
			header("location: dashboard.php");
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>E-Library</title>
		<?php require_once('include/css.php');  ?>
		<link href="include/mycss.css" rel="stylesheet">
		<style>
			input[type="file"] {
				display: none;
			}
			.custom-file-upload {
				border: 1px solid #ccc;
				display: inline-block;
				padding: 14px 12px;
				cursor: pointer;
				width: 100%;
				font-size: 15px;
				text-align: center;
				height: 115px;
			}
		</style>
        
    </head>
    <body class="nav-md" onclick="resetTimer()" onmousedown="resetTimer()" onmousemove="resetTimer()" onscroll="resetTimer()" onkeypress="resetTimer()">
        <div class="container body" ng-app="application">
            <div class="main_container" ng-controller="quickFeaturesController">
                <?php
                    if($record[0]['role'] == 1) {
						 require_once('include/sidemenu.php'); 
					}
					else {
						 require_once('include/sidemenu_client.php'); 
					}
                    require_once('include/topnav.php');
                ?>
                <div class="right_col" role="main" >
					 <div class="row">
						<div class="x_panel" id="editPanel">
					  <div class="x_title">
						<img src="images/book_edit.png" width="50px" height="50px">
						<h3 style="margin-left:60px; margin-top:-38px">Update Book</h3>
						  <div class=" btn-group pull-right" style="margin-left:60px; margin-top:-45px">
							   <a href="books_list.php? book_id=<?php echo $book_id;?> & book_token=<?php echo $pass_token;?>" class="btn btn-primary btn-sm"  data-toggle="tooltip" data-placement="top" title="" data-original-title="View Book Details"><i class="fa fa-book"></i> View</a>
								<a href="listof_books.php" class="btn btn-primary btn-sm"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Back to list of books"><i class="fa fa-mail-reply"></i> Back</a>
						  </div>
						<div class="clearfix"></div>
					  </div>
					  <div class="x_content">
						  <div class="row">
							  <form id="updateBook">
								  <div class="col-md-12 col-sm-12 col-xs-12">
										<div class="col-md-6 col-sm-12 col-xs-12">
											<label class="col-md-12 col-sm-12 col-xs-12 form-group">ISBN: </label>
											<div class="col-md-12 col-sm-12 col-xs-12 form-group">
												<input type="hidden" name="action" value="update" />
												<input type='hidden' class='form-control' name='bookupdate_token' value="<?php echo $token; ?>">
												<input type="hidden" name="book_id" value="<?php echo $data2[0]['book_id']; ?>" />
												<input type="text" class="form-control has-feedback-left" name="isbn" id="isbn" value="<?php echo $data2[0]['isbn']; ?>" placeholder="ISBN" required="" />
												<span class="fa fa-barcode blue form-control-feedback left" aria-hidden="true" style="color:black"></span>
											</div>
											<label class="col-md-12 col-sm-12 col-xs-12 form-group"> Book Title: </label>
											<div class="col-md-12 col-sm-12 col-xs-12 form-group">
												 <textarea rows="4" required="required" class="form-control" name="title" id="title" id="description" style="background-color:#e2e2e2; text-transform:capitalize" placeholder="Book Description"><?php echo $data2[0]['title']; ?></textarea>

											</div>

											<label class="col-md-12 col-sm-12 col-xs-12 form-group">Author Name: </label>
											<div class="col-md-12 col-sm-12 col-xs-12 form-group">
												<input list="authors" class="form-control has-feedback-left" name="author" id="author" id="author" placeholder="Author Name" style="background-color:#e2e2e2; text-transform:capitalize" value="<?php echo $data2[0]['author']; ?>" required="" />
												<span class="fa fa-user form-control-feedback left" aria-hidden="true" style="color:black"></span>
												<datalist id="authors" class="form-group">
												</datalist>
											</div>
											
										</div>

										<div class="col-md-6 col-sm-12 col-xs-12">
											<label class="col-md-12 col-sm-12 col-xs-12 form-group">Copyright: </label>
											<div class="col-md-12 col-sm-12 col-xs-12 form-group">
												<input type="text" class="form-control has-feedback-left" name="copyright" id="copyright" value="<?php echo $data2[0]['copyright']; ?>"  placeholder="Copyright"  required="" />
												<span class="fa fa-google-wallet blue form-control-feedback left" aria-hidden="true" style="color:black"></span>
											</div>
											<label class="col-md-12 col-sm-12 col-xs-12 form-group">No of pages: </label>
											<div class="col-md-12 col-sm-12 col-xs-12 form-group">
												<input type="number" class="form-control has-feedback-left" name="pages" id="pages" value="<?php echo $data2[0]['pages']; ?>" placeholder="No of pages" required="" />
												<span class="fa fa-files-o blue form-control-feedback left" aria-hidden="true" style="color:black"></span>
											</div>
											<label class="col-md-12 col-sm-12 col-xs-12 form-group">Source of fund: </label>
											<div class="col-md-12 col-sm-12 col-xs-12 form-group">
												<input type="text" class="form-control has-feedback-left" name="fund" id="fund" value="<?php echo $data2[0]['fund']; ?>" placeholder="Source of fund" required="" />
												<span class="fa fa-briefcase blue form-control-feedback left" aria-hidden="true" style="color:black"></span>
											</div>
											<label class="col-md-12 col-sm-12 col-xs-12 form-group">Publisher: </label>
											<div class="col-md-12 col-sm-12 col-xs-12 form-group">
												<input type="text" class="form-control has-feedback-left" name="publisher" id="publisher" value="<?php echo $data2[0]['publisher']; ?>"  placeholder="Publisher" required="" />
												<span class="fa fa-building-o blue form-control-feedback left" aria-hidden="true" style="color:black"></span>
											</div>
										</div>
							  </div>
					
							  <div class="col-md-12 col-sm-12 col-xs-12">
								  <br>
								   <div class="col-md-6 col-sm-12 col-xs-12">
								  </div>
									<div class="col-md-6 col-sm-12 col-xs-12">
								  	<button type="reset" class="btn btn-default pulse" style="font-size:17px; font-weight:bold; margin-left:10px; background-color: #f3b4b4; border: 2px solid #f98e8e"><img src="images/removebook.png" width="50px" >Cancel
									</button>
										<button type="submit" class="btn btn-default pulse" style="font-size:17px; font-weight:bold; background-color:#7096d8; border: 2px solid #3667bd"><img src="images/addbook.png" width="50px">Update</button>
								  </div>
							  </div>
							  </form>
							  
						 </div>
						
					</div>
				  </div>
					  </div>
			
            </div>
            <?php include'include/footer.php';?>
        </div>
         <?php include'include/js.php';?>
			<script>
				$("#updateBook").on('submit',(function(e) {

          			e.preventDefault();
					$.ajax({
						url: "model/books.php",
						type: "POST",
						data: new FormData(this),
						contentType: false,
						cache: false,
						processData:false,
						success: function(data){
							console.log(data);
							if(data == "true") {
								toastr.info("Book details is successfully updated.");
							}
							else {
								
							}
							setTimeout(function(){ location.reload(); }, 1500);
						},
						error: function(){
							alert("error");
						}
					});
				}));
			</script>
    </body>
</html>
