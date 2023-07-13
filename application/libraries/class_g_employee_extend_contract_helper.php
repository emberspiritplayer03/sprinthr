<?php
class G_Employee_Extend_Contract_Helper {
		
	public static function isIdExist(G_Employee_Extend_Contract $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_EXTEND_CONTRACT ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

}
?>