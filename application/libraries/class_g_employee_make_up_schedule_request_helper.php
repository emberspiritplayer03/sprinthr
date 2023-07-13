<?php
class G_Employee_Make_Up_Schedule_Request_Helper {
	public static function isIdExist(G_Employee_Make_Up_Schedule_Request $gemusr) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_MAKE_UP_SCHEDULE_REQUEST ."
			WHERE id = ". Model::safeSql($gemusr->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByIsArchive($is_archive) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_MAKE_UP_SCHEDULE_REQUEST ."
			WHERE is_archive = ". Model::safeSql($is_archive) ."
		";
		echo $sql;
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByIsApproved($is_approved) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_MAKE_UP_SCHEDULE_REQUEST ."
			WHERE is_approved = ". Model::safeSql($is_approved) ."
		";
		echo $sql;
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
}
?>