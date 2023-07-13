<?php
class G_Employee_Request_Helper {
	public static function isIdExist(G_Employee_Request $ger) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_REQUEST ."
			WHERE id = ". Model::safeSql($ger->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByEmployeeId(G_Employee $ge) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_REQUEST ."
			WHERE employee_id = ". Model::safeSql($ge->getId()) ."
		";
		echo $sql;
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByStatus(G_Employee_Request $ger) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_REQUEST ."
			WHERE status = ". Model::safeSql($ger->getStatus()) ."
		";
		echo $sql;
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsBySettingsRequestId(G_Settings_Request $gsr) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_REQUEST ."
			WHERE settings_request_id = ". Model::safeSql($gsr->getType()) ."
		";
		echo $sql;
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
}
?>