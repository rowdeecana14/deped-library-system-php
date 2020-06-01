<?php
require_once "model/model.php";
class db_backup{
		private $exported_database;
		
	public function connect_server(){
			
			$servername = "localhost";
			$username = "root";
			$password = "";
			$conn = new mysqli($servername, $username, $password);
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			return $conn;
		}
		public function connect_db(){
			
			$servername = "localhost";
			$username = "root";
			$password = "";
			$dbname = "deped_library";
			
			$servername = "localhost";
			$username = "root";
			$password = "";
			$conn = new mysqli($servername, $username, $password, $dbname);
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			return $conn;
		}
		public function tables(){
			
			$tables = array();
			$mysqli = $this->connect_db();
			$result = $mysqli->query('SHOW TABLES' ) ; 
			while ($row = $result->fetch_row())  {
				$tables[] = $row[0] ;
			}
            
			return $tables;
		}
		function insert_database(){
			$conn = $this->connect_server();
			$data = "false";
			$sql = "CREATE DATABASE deped_library";
			if ($conn->query($sql) === TRUE) {
				$data = "true";
			}
			return $data;
		}
		function db_import($file_path){
			$conn = $this->connect_db();
			$tbl_query = null;
			foreach ($this->tables() as $key => $table) {
				if ($conn->query("DROP TABLE IF EXISTS ".$table) === TRUE) {
				}
			}
			
			//---------------------------------------------------------------------------
			//Forign code Start here
			//---------------------------------------------------------------------------
			$templine = '';
			// Read in entire file
			$lines = file($file_path);
			// Loop through each line
			foreach ($lines as $line)
			{
			// Skip it if it's a comment
				if (substr($line, 0, 2) == '--' || $line == '')
					continue;
				// Add this line to the current segment
				$templine .= $line;
				// If it has a semicolon at the end, it's the end of the query
				if (substr(trim($line), -1, 1) == ';')
				{
					// Perform the query
					if($conn->query($templine)) {
						
					}
					else {
						print('Error performing query \'<strong>' . $templine . '\': '.'<br /><br />');
					}
					$templine = '';
				}
			}
			 //echo "Database imported successfully <br/>";
			return true;
		}
		public function backup(){
			
			$fileName = 'download_dblibrary.sql' ;
			if (!file_exists("./myDownload")) mkdir("./myDownload" , 0700) ;
			if (!is_writable("./myDownload")) chmod("./myDownload" , 0700) ;
		
			$return='';
			$return .= "--\n";
			$return .= "-- A Mysql Backup System \n";
			$return .= "--\n";
			$return .= '-- Export created: ' . date("Y/m/d") . ' on ' . date("h:i") . "\n\n\n";
			$return = "--\n";
			$return .= "-- Database : " . "db_deped2k18" . "\n";
			$return .= "--\n";
			$return .= "-- --------------------------------------------------\n";
			$return .= "-- ---------------------------------------------------\n";
			$return .= 'SET AUTOCOMMIT = 0 ;' ."\n" ;
			$return .= 'SET FOREIGN_KEY_CHECKS=0 ;' ."\n" ;
			$tables = array() ;
			$mysqli = $this->connect_db();
			$result = $mysqli->query('SHOW TABLES' ) ; 
			while ($row = $result->fetch_row())  {
				$tables[] = $row[0] ;
			}
			foreach($tables as $table) { 
				$result = $mysqli->query('SELECT * FROM '. $table) ; 
				$num_fields = $mysqli->field_count  ;
				$return .= "--\n" ;
				$return .= '-- Tabel structure for table `' . $table . '`' . "\n" ;
				$return .= "--\n" ;
				$return.= 'DROP TABLE  IF EXISTS `'.$table.'`;' . "\n" ; 
				$shema = $mysqli->query('SHOW CREATE TABLE '.$table) ;
				$tableshema = $shema->fetch_row() ; 
				$return.= $tableshema[1].";" . "\n\n" ; 
				while($rowdata = $result->fetch_row()) {

					$return .= 'INSERT INTO `'.$table .'`  VALUES ( '  ;
					for($i=0; $i<$num_fields; $i++) {   
						$return .= '"'.$mysqli->real_escape_string($rowdata[$i]) . "\"," ;
					}
					$return = substr("$return", 0, -1) ; 
					$return .= ");" ."\n" ;
				} 
				$return .= "\n\n" ; 
			}
	
			$return .= 'SET FOREIGN_KEY_CHECKS = 1 ; '  . "\n" ; 
			$return .= 'COMMIT ; '  . "\n" ;
			$return .= 'SET AUTOCOMMIT = 1 ; ' . "\n"  ; 
			$zip = new ZipArchive() ;
			$resOpen = $zip->open("myDownload" . '/' .$fileName.".zip" , ZIPARCHIVE::CREATE) ;
			if( $resOpen ){
				$zip->addFromString( $fileName , "$return" ) ;
			}
			$zip->close() ;
			//$fileSize = $this->get_file_size_unit(filesize("myBackups" . "/". $fileName . '.zip')) ;
			header("location: myDownload/".$fileName.".zip");
		}
	
		public function autoBackup(){
			
			$fileName = 'backup_' . date('d-m-Y').'.sql';
			if(!file_exists($fileName)) {
				if (!file_exists("./myBackups")) mkdir("./myBackups" , 0700) ;
				if (!is_writable("./myBackups")) chmod("./myBackups" , 0700) ;
				//$content = 'Allow from all' ; 
				//$file = new SplFileObject("./myBackups" . '/.htaccess', "w") ;
				//$file->fwrite($content) ;

				$return='';
				$return .= "--\n";
				$return .= "-- A Mysql Backup System \n";
				$return .= "--\n";
				$return .= '-- Export created: ' . date("Y/m/d") . ' on ' . date("h:i") . "\n\n\n";
				$return = "--\n";
				$return .= "-- Database : " . "db_deped2k18" . "\n";
				$return .= "--\n";
				$return .= "-- --------------------------------------------------\n";
				$return .= "-- ---------------------------------------------------\n";
				$return .= 'SET AUTOCOMMIT = 0 ;' ."\n" ;
				$return .= 'SET FOREIGN_KEY_CHECKS=0 ;' ."\n" ;
				$tables = array() ;
				$mysqli = $this->connect_db();
				$result = $mysqli->query('SHOW TABLES' ) ; 
				while ($row = $result->fetch_row())  {
					$tables[] = $row[0] ;
				}
				foreach($tables as $table) { 
					$result = $mysqli->query('SELECT * FROM '. $table) ; 
					$num_fields = $mysqli->field_count  ;
					$return .= "--\n" ;
					$return .= '-- Tabel structure for table `' . $table . '`' . "\n" ;
					$return .= "--\n" ;
					$return.= 'DROP TABLE  IF EXISTS `'.$table.'`;' . "\n" ; 
					$shema = $mysqli->query('SHOW CREATE TABLE '.$table) ;
					$tableshema = $shema->fetch_row() ; 
					$return.= $tableshema[1].";" . "\n\n" ; 
					while($rowdata = $result->fetch_row()) {

						$return .= 'INSERT INTO `'.$table .'`  VALUES ( '  ;
						for($i=0; $i<$num_fields; $i++) {   
							$return .= '"'.$mysqli->real_escape_string($rowdata[$i]) . "\"," ;
						}
						$return = substr("$return", 0, -1) ; 
						$return .= ");" ."\n" ;
					} 
					$return .= "\n\n" ; 
				}

				$return .= 'SET FOREIGN_KEY_CHECKS = 1 ; '  . "\n" ; 
				$return .= 'COMMIT ; '  . "\n" ;
				$return .= 'SET AUTOCOMMIT = 1 ; ' . "\n"  ; 
				$zip = new ZipArchive() ;
				$resOpen = $zip->open("myBackups" . '/' .$fileName.".zip" , ZIPARCHIVE::CREATE) ;
				if( $resOpen ){
					$zip->addFromString( $fileName , "$return" ) ;
				}
				$zip->close();
				
				$setting = new Model;
				date_default_timezone_set('Asia/Manila');
				$date = date("Y-m-d h:i:s");
				
				$file = $fileName.".zip";
				$sql = "SELECT COUNT(file_name) as number FROM tbl_backup WHERE file_name='$file'";
				$data = $setting->displayRecord($sql);
				if(count($data))
				if($data[0]['number'] == 0) {
					$sql = "SELECT MAX(backup_id) as backup_id FROM tbl_backup";
					$data_logs = $setting->searchRecord($sql);
					$id = $data_logs[0]['backup_id'];
					if($id == null) {
						$id = 1;
					}
					else {
						$id = $id + 1;
					}
					$sql = "INSERT INTO tbl_backup (backup_id, file_name, date) VALUES($id, '$file', '$date')";
					$setting->addRecord($sql);
				}
			}
		}
		public function get_file_size_unit($file_size){
			switch (true) {
				case ($file_size/1024 < 1) :
					return intval($file_size ) ." Bytes" ;
					break;
				case ($file_size/1024 >= 1 && $file_size/(1024*1024) < 1)  :
					return intval($file_size/1024) ." KB" ;
					break;
				default:
				return intval($file_size/(1024*1024)) ." MB" ;
			}
		}
		public function getDownloads(){
			$dir = "./myBackups";
			if (is_dir($dir)){
				$dh  = opendir($dir);
				$files=array();
				$i=0;
				$xclude=array('.','..','.htaccess');
				while (false !== ($filename = readdir($dh))) {
				   if(!in_array($filename, $xclude))
				   {
					$files[$i]['name'] = $filename;
					$files[$i]['size'] = $this->get_file_size_unit(filesize($dir.'/'.$filename));
					$i++;
				   }
				}
				return $files;
			}
		}
	
		public function importDatabase($file_data) {
			$output = '';
			$message = "false";
			$count = 0;
			$mysqli = $this->connect_db();
			$tb_name = $mysqli->query("SHOW TABLES");
			$tables = array();
			while ($row = $tb_name->fetch_row())  {
				$tables[] = $row[0] ;
			}
			foreach ($tables as $key => $table) {
				$mysqli->query("DROP TABLE IF EXISTS ".$table);
			}

		   foreach($file_data as $row) {
				$start_character = substr(trim($row), 0, 2);
				if($start_character != '--' || $start_character != '/*' || $start_character != '//' || $row != '') {
					 $output = $output . $row;
					 $end_character = substr(trim($row), -1, 1);
					if($end_character == ';') {
						if(!$mysqli->query($output)) {
							$count++;
						}
						$output = '';
					}
				}
		   }
		   if($count > 0) {
				$message = "false";
		   }
		   else {
				$message = "true";
		   }
			return $message;
		}
		public function download($name='backup'){
			
			header('Content-Type: application/sql');
			header('Content-Disposition: attachment; filename='.$name.'.sql');
			echo $this->exported_database;
		}
		
		public function save($path,$name=""){
			$name = ($name != "") ? $name : 'backup_' . date('Y-m-d');
			$file = fopen($path.$name.".sql","w+");
			$fw = fwrite($file, $this->exported_database);	
			if(!$fw){
				return false;
			}
			else {
				return true; 
			}
		}
	}
?>