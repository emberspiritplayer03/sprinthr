<?php
class G_Employee_Payable_Helper {
		
	public static function isIdExist(G_Employee_Payable $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_PAYABLE ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

}
?>