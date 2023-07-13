<?php
class G_Employee_Contact_Details_Manager {
	public static function save(G_Employee_Contact_Details $e) {
		if (G_Employee_Contact_Details_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_CONTACT_DETAILS . "";
			$sql_end   = "WHERE employee_id = ". Model::safeSql($e->getEmployeeId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_CONTACT_DETAILS . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id			= " . Model::safeSql($e->getEmployeeId()) .",
			address        		= " . Model::safeSql($e->getAddress()) .",
			city	        	= " . Model::safeSql($e->getCity()) .",
			province        	= " . Model::safeSql($e->getProvince()) .",
			zip_code        	= " . Model::safeSql($e->getZipCode()) .",
			country       	 	= " . Model::safeSql($e->getCountry()) .",
			home_telephone     	= " . Model::safeSql($e->getHomeTelephone()) .",
			mobile	        	= " . Model::safeSql($e->getMobile()) .",
			work_telephone     	= " . Model::safeSql($e->getWorkTelephone()) .",
			work_email        	= " . Model::safeSql($e->getWorkEmail()) .",
			other_email				= " . Model::safeSql($e->getOtherEmail()) ." "
		
			. $sql_end ."	
		
		";	

		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Employee_Contact_Details $e){
		if(G_Employee_Contact_Details_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_CONTACT_DETAILS ."
				WHERE employee_id =" . Model::safeSql($e->getEmployeeId());
			Model::runSql($sql);
		}
	
	}
}
?>