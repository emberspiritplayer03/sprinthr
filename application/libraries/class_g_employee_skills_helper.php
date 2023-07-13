<?php
class G_Employee_Skills_Helper {
		
	public static function isIdExist(G_Employee_Skills $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_SKILLS ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

}
?>