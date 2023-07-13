<?php
class G_Employee_License_Manager {
	public static function save(G_Employee_License $e) {
		if (G_Employee_License_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_LICENSE . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_LICENSE . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id			= " . Model::safeSql($e->getEmployeeId()) .",
			license_type	   	= " . Model::safeSql($e->getLicenseType()) .",
			license_number	   	= " . Model::safeSql($e->getLicenseNumber()) .",
			issued_date		   	= " . Model::safeSql($e->getIssuedDate()) .",
			expiry_date		   	= " . Model::safeSql($e->getExpiryDate()) .",
			notes		   	= " . Model::safeSql($e->getNotes()) ."
			"
		
			. $sql_end ."	
		
		";	
		
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Employee_License $e){
		if(G_Employee_License_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_LICENSE ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
}
?>