<?php
class G_Employee_Emergency_Contact_Manager {
	public static function save(G_Employee_Emergency_Contact $e) {
		if (G_Employee_Emergency_Contact_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_EMERGENCY_CONTACT . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_EMERGENCY_CONTACT . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id			= " . Model::safeSql($e->getEmployeeId()) .",
			person		   		= " . Model::safeSql($e->getPerson()) .",
			relationship   		= " . Model::safeSql($e->getRelationship()) .",
			home_telephone     	= " . Model::safeSql($e->getHomeTelephone()) .",
			mobile		     	= " . Model::safeSql($e->getMobile()) .",
			work_telephone     	= " . Model::safeSql($e->getWorkTelephone()) .",
			address				= " . Model::safeSql($e->getAddress()) ." "
		
			. $sql_end ."	
		
		";	

		Model::runSql($sql);
		return mysql_insert_id();		
	}

	public static function bulkInsert( $data = array() ){
		if( !empty($data) ){
			$sql_insert_values = implode(",", $data);
			$sql = "INSERT INTO " . G_EMPLOYEE_EMERGENCY_CONTACT . "(employee_id,person,relationship,address,home_telephone,mobile,work_telephone)VALUES{$sql_insert_values}";					
			Model::runSql($sql);
			
			return true;			
		}else{
			return false;
		}
	}
		
	public static function delete(G_Employee_Emergency_Contact $e){
		if(G_Employee_Emergency_Contact_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_EMERGENCY_CONTACT ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
}
?>