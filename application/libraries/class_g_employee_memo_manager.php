<?php
class G_Employee_Memo_Manager {
	public static function save(G_Employee_Memo $e) {
		if (G_Employee_Memo_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_MEMO . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_MEMO . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id			= " . Model::safeSql($e->getEmployeeId()) .",
			memo_id				= " . Model::safeSql($e->getMemoId()) .",
			title		   		= " . Model::safeSql($e->getTitle()) .",
			memo		   		= " . Model::safeSql($e->getMemo()) .",
			attachment	   	= " . Model::safeSql($e->getAttachment()) .",
			date_of_offense	   	= " . Model::safeSql($e->getDateOfOffense()) .",
			offense_description	   	= " . Model::safeSql($e->getOffenseDescription()) .",
			remarks	   	= " . Model::safeSql($e->getRemarks()) .",
			date_created		= " . Model::safeSql($e->getDateCreated()) .",
			created_by			= " . Model::safeSql($e->getCreatedBy()) ." "
		
			. $sql_end ."	
		
		";			
		
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Employee_Memo $e){
		if(G_Employee_Memo_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_MEMO ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
}
?>