<?php
class G_Employee_Work_Experience_Helper {
		
	public static function isIdExist(G_Employee_Work_Experience $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_WORK_EXPERIENCE ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

}
?>