<?php
class G_Employee_Contribution_Manager {
	public static function save(G_Employee_Contribution $e) {
		if (G_Employee_Contribution_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_CONTRIBUTION . "";
			$sql_end   = "WHERE employee_id = ". Model::safeSql($e->getEmployeeId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_CONTRIBUTION . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id			= " . Model::safeSql($e->getEmployeeId()) .",
			sss_ee		     	= " . Model::safeSql($e->getSssEe()) .",
			pagibig_ee	     	= " . Model::safeSql($e->getPagibigEe()) .",
			philhealth_ee     	= " . Model::safeSql($e->getPhilhealthEe()) .",
			sss_er		     	= " . Model::safeSql($e->getSssEr()) .",
			pagibig_er	     	= " . Model::safeSql($e->getPagibigEr()) .",
			philhealth_er     	= " . Model::safeSql($e->getPhilhealthEr()) .",
			to_deduct	     	= " . Model::safeSql($e->getToDeduct()) ." "
		
			. $sql_end ."	
		
		";	

		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Employee_Contribution $e){
		if(G_Employee_Contribution_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_CONTRIBUTION ."
				WHERE employee_id =" . Model::safeSql($e->getEmployeeId());
			Model::runSql($sql);
		}
	
	}
}
?>