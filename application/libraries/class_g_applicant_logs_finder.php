<?php
class G_Applicant_Logs_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . APPLICANT_LOGS ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}	

	public static function findByEmail($email) {
		$sql = "
			SELECT * 
			FROM " . APPLICANT_LOGS ." 
			WHERE email =". Model::safeSql($email) ."
			LIMIT 1
		";		
		return self::getRecord($sql);
	}
	
	public static function findAll($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . APPLICANT_LOGS ." 			
			".$order_by."
			".$limit."		
		";
		return self::getRecords($sql);
	}
	
	private static function getRecord($sql) {
		$result = Model::runSql($sql);
		$total = mysql_num_rows($result);
		if ($total == 0) {
			return false;	
		}		
		$row = Model::fetchAssoc($result);
		$records = self::newObject($row);	
		return $records;
	}
	
	private static function getRecords($sql) {
		$result = Model::runSql($sql);
		$total = mysql_num_rows($result);
		if ($total == 0) {
			return false;	
		}
		while ($row = Model::fetchAssoc($result)) {
			$records[$row['id']] = self::newObject($row);
		}
		return $records;
	}

	private static function newObject($row) {
		$al = new G_Applicant_Logs();
		$al->setId($row['id']);
		$al->setIp($row['ip']);
		$al->setCountry($row['country']);
		$al->setFirstName($row['firstname']);
		$al->setLastName($row['lastname']);
		$al->setEmail($row['email']);
		$al->setPassword($row['password']);
		$al->setStatus($row['status']);
		$al->setDateTimeCreated($row['date_time_created']);
		$al->setDateTimeValidated($row['date_time_validated']);
		$al->setLink($row['link']);
		$al->setIsPasswordChange($row['is_password_change']);												
		return $al;
	}
	
}
?>