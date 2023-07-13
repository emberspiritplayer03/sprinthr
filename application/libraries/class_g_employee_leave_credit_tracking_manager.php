<?php
class G_Employee_Leave_Credit_Tracking_Manager {
	public static function save(G_Employee_Leave_Credit_Tracking $lt) {
		if (G_Employee_Loan_Helper::isIdExist($lt) > 0 ) {
			$sql_start = "UPDATE ". EMPLOYEE_LEAVE_CREDIT_TRACKING . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($lt->getId());		
		}else{
			$sql_start = "INSERT INTO ". EMPLOYEE_LEAVE_CREDIT_TRACKING . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id    =" . Model::safeSql($lt->getEmployeeId()) . ",
			leave_id  =" . Model::safeSql($lt->getLeaveId()) . ",
			credit 	 	   =" . Model::safeSql($lt->getCredit()) . ",						
			date	  	   =" . Model::safeSql($lt->getDate()) . " "				
			. $sql_end ."	
		
		";			
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Employee_Leave_Credit_Tracking $lt){
		if(G_Employee_Loan_Helper::isIdExist($lt) > 0){
			$sql = "
				DELETE FROM ". EMPLOYEE_LEAVE_CREDIT_TRACKING ."
				WHERE id =" . Model::safeSql($lt->getId());
			Model::runSql($sql);
		}	
	}
}
?>