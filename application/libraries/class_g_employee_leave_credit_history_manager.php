<?php
class G_Employee_Leave_Credit_History_Manager {
	public static function save(G_Employee_Leave_Credit_History $glh) {		
		if (G_Employee_Leave_Credit_History_Helper::isIdExist($glh) > 0 ) {
			$sql_start = "UPDATE ". EMPLOYEE_LEAVE_CREDIT_HISTORY . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($glh->getId());	
			$action    = 'update';	
		}else{
			$sql_start = "INSERT INTO ". EMPLOYEE_LEAVE_CREDIT_HISTORY . " ";
			$sql_end  = "";		
			$action   = 'insert';
		}
		
		$sql = $sql_start ."
			SET
			leave_id      =" . Model::safeSql($glh->getLeaveId()) . ",
			employee_id   =" . Model::safeSql($glh->getEmployeeId()) . ",
			credits_added =" . Model::safeSql($glh->getCreditsAdded()) . ",						
			date_added	  =" . Model::safeSql($glh->getDateAdded()) . " "				
			. $sql_end ."	
		";			
				
		Model::runSql($sql);
		if( $action == 'update' ){
			return $glh->getId();
		}else{
			return mysql_insert_id();		
		}
	}
		
	public static function delete(G_Employee_Leave_Credit_History $glh){
		if(G_Employee_Leave_Credit_History_Helper::isIdExist($glh) > 0){
			$sql = "
				DELETE FROM ". EMPLOYEE_LEAVE_CREDIT_HISTORY ."
				WHERE id =" . Model::safeSql($glh->getId());
			Model::runSql($sql);
		}	
	}
}
?>