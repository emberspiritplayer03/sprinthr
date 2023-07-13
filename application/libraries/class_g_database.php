<?php
class G_Database{

	protected $username;
	protected $hostname;
	protected $dbname;
	protected $password;
	protected $backup_path;
	protected $backup_name;

	const FILE_EXTENSION = ".sql";

	public function __construct() {}

	public function setUserName($value){
		$this->username = $value;
	}

	public function setBackupName($value){
		$this->backup_name = $value;
	}

	public function setHostName($value){
		$this->hostname = $value;
	}

	public function setDbName($value){
		$this->dbname = $value;
	}

	public function setPassword($value){
		$this->password = $value;
	}

	public function setBackupPath($value){
		$this->backup_path = $value;
	}

	/*
		Usage :
		$backup_path   = $_SERVER['DOCUMENT_ROOT'] . MAIN_FOLDER . "db_archives/";		
		$mdb = new G_Database();
		$mdb->setUserName(DB_USERNAME);
		$mdb->setHostName(DB_HOST);
		$mdb->setDbName(DB_DATABASE);
		$mdb->setPassword(DB_PASSWORD);
		$mdb->setBackupPath($backup_path); <- if not set will use default location
		$return = $mdb->backupDatabase();
	*/

	public function backupDatabase(){		
		if(!empty($this->username) && !empty($this->hostname) && !empty($this->dbname)){			
			if(!empty($this->backup_path)){
			$path = $this->backup_path;
			}else{
				//Default backup path
				$path   = $_SERVER['DOCUMENT_ROOT'] . MAIN_FOLDER . "db_archives/";		
			}
			
			$dbuser   = $this->username;
			$dbpass   = $this->password;
			$dbhost   = $this->hostname;
			$dbname   = $this->dbname; 

			$strtime = strtotime("now");

			if(!empty($this->backup_name)){
				$filename = $this->backup_name . "_{$strtime}";
			}else{
				$filename = "_backup_{$strtime}";
			}

			$filename = $filename . self::FILE_EXTENSION;
			$file     = $path . $filename;
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
				$command = MYSQLDUMP_APP_PATH . '\mysqldump --opt -h '.$dbhost.' -u '.$dbuser.' -p'.$dbpass.' '.$dbname.' > '.$file;						
			}else{
				$command = 'mysqldump --opt -h '.$dbhost.' -u '.$dbuser.' -p'.$dbpass.' '.$dbname.' > '.$file;						
			}
			
			exec($command, $output, $return_var);
			
			return true;
		}else{
			return false;
		}
	}

	public function importWikiDatabase($sql_file = ''){		
		$return = false;
		if(!empty($this->username) && !empty($this->hostname) && !empty($this->dbname)){						
			if( !empty($sql_file) ){

				$dbuser   = $this->username;
				$dbpass   = $this->password;
				$dbhost   = $this->hostname;
				$dbname   = $this->dbname; 
				
				if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
					$command = MYSQLDUMP_APP_PATH . '\mysql -h '.$dbhost.' -u '.$dbuser.' -p'.$dbpass.' '.$dbname.' < '.$sql_file;						
				}else{
					$command = 'mysql --opt -h '.$dbhost.' -u '.$dbuser.' -p'.$dbpass.' '.$dbname.' < '.$sql_file;						
				}
				
				exec($command, $output, $return_var);
				
				$return = true;
			}
		}

		return $return;
	}

	/*
		Usage :
		$backup_path   = $_SERVER['DOCUMENT_ROOT'] . MAIN_FOLDER . "db_archives/";		
		$mdb = new M_Database();
		$mdb->setUserName(DB_USERNAME);
		$mdb->setHostName(DB_HOST);
		$mdb->setDbName(DB_DATABASE);
		$mdb->setPassword(DB_PASSWORD);
		$mdb->setBackupPath($backup_path); <- if not set will use default location
		$return = $mdb->backupDatabase();
	*/
	public function backupDatabaseGleentServer(){
		

		if(!empty($this->username) && !empty($this->password) && !empty($this->hostname) && !empty($this->dbname)){
			if(!empty($this->backup_path)){
			$path = $this->backup_path;
			}else{
				//Default backup path
				$path   = "http://gleent.web/local-clients/iCirclebiz/apps/db_archives/";		
			}

			$dbuser   = $this->username;
			$dbpass   = $this->password;
			$dbhost   = $this->hostname;
			$dbname   = $this->dbname; 

			$strtime = strtotime("now");

			if(!empty($this->backup_name)){
				$filename = $this->backup_name . "_{$strtime}";
			}else{
				$filename = "_backup_{$strtime}";
			}

			$filename = $filename . self::FILE_EXTENSION;
			$file     = $path . $filename;
			$command  = "mysqldump --user=$dbuser --password=$dbpass --host=$dbhost $dbname --routines > " . $file;		
			exec($command);

			return true;
		}else{
			return false;
		}
	}
	
}
?>