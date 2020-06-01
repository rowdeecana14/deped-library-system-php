<div class="col-md-3 left_col menu_fixed">
    <div class="left_col scroll-view">
        <div class="navbar nav_title">
            <a href="dashboard.php" class="site_title"><img src="images_uploaded/<?php echo $data[0]['logo']; ?>" width="50px" style="margin-bottom: 7px;"><span style="font-family:stencil std;" id="flaming"><?php echo $data[0]['system_name']; ?></span></a>
        </div>

        <!-- menu profile quick info -->
        <div class="profile clearfix">
            <div class="profile_pic">
                <img src="images_uploaded/<?php echo $record[0]['image']; ?>" style="width:55px; height:55px;" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <h2><b><?php echo $record[0]['firstname']." ".$record[0]['lastname']; ?> </b></h2>
                <span><i class="fa fa-circle text-success"></i> Online</span>
            </div>
        </div>
        <br />
        <div id="sidebar-menu" class="main_menu_side main_menu hidden-print">
            <div class="menu_section">
                <h3>Menu</h3>
                <ul class="nav side-menu">
                    <li>
                        <a href="dashboard.php"><i class="fa fa-home"></i> Dashboard</a>
                    </li>
					<li>
                        <a href="top_10.php"><i class="fa fa-sort-numeric-asc"></i> Top 10</a>
                    </li>
                    <li>
                        <a><i class="fa fa-book"></i>Book Registration <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="books_form.php">New Books</a></li>
							<li><a href="books_borrow.php">Borrow Books</a></li>
							<li><a href="books_return.php">Returned Books</a></li>
                        </ul>
                    </li>
                    <li>
                        <a><i class="fa fa-table"></i> Book Records <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
							<li><a href="accession_record.php">Accession Record</a></li>
                            <li><a href="listof_books.php">Inventory Record</a></li>
							<li><a href="lost_books.php">Lost Record</a></li>
							<li><a href="damaged_books.php">Damaged Record</a></li>
                        </ul>
                    </li>
					<li>
                        <a><i class="fa fa-table"></i> Attendance & Borrowed <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
							<li><a href="daily_attendance.php">Daily Attendance</a></li>
							<li><a href="borrowed_books.php">Borrowed Record</a></li>
							<li><a href="borrowedby_subject.php">Borrowed by Subject</a></li>
                        </ul>
                    </li>
					<li>
                        <a><i class="fa fa-users"></i> System User <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
							
							<li><a href="borrowers.php">Borrowers</a></li>
                            <li><a href="user_account.php">User Accounts</a></li>
                        </ul>
                    </li>
					<li>
						<a><i class="fa fa-cogs"></i> Settings <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
							<li><a href="database.php">System Database </a></li>
                            <li><a href="settings.php">System Configuration</a></li>
                        </ul>
                    </li>
					<li>
                        <a href="userlogs.php"><i class="fa fa-history"></i> User Logs</a>
                    </li>
                </ul>
            </div>
        </div>
		
       
    </div>
</div>