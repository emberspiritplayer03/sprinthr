<?php
class G_Employee_Leave_Credit_History_Helper {
	public static function isIdExist(G_Employee_Leave_Credit_History $glh) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . EMPLOYEE_LEAVE_CREDIT_HISTORY ."
			WHERE id = ". Model::safeSql($glh->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	/**
	* Get leave credit history by year
	*
	* @param string year
	* @return array
	*/
	public static function getAllLeaveCreditHistoryByYear($year = '') {
		$sql = "
			SELECT CONCAT(e.firstname, ' ', e.lastname)AS employee_name, l.name AS leave_type, lc.credits_added
			FROM " . EMPLOYEE_LEAVE_CREDIT_HISTORY ." lc 
				LEFT JOIN " . EMPLOYEE . " e ON lc.employee_id = e.id
				LEFT JOIN " . G_LEAVE . " l ON lc.leave_id = l.id 
			WHERE DATE_FORMAT(lc.date_added, '%Y') = ". Model::safeSql($year) ."
		";
		
		$result = Model::runSql($sql,true);		
		return $result;	
	}
}
?>