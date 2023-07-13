<?php
class G_Settings_Leave_General_Helper {
	
	public static function isIdExist(G_Settings_Leave_General $u) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_SETTINGS_LEAVE_GENERAL ."
			WHERE id = ". Model::safeSql($u->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_SETTINGS_LEAVE_GENERAL ."
		";
		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function getAllResetLeave(){
		$sql = "
		insert into g_employee_leave_credit_tracking (`employee_id`,`leave_id`,`credit`,`date`)
		select employee_id, leave_id, (-1*no_of_days_available), CURDATE() from g_employee_leave_available
		";

		Model::runSql($sql);
	}

}
?>