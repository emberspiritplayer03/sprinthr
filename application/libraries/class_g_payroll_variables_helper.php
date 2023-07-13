<?php
class G_Payroll_Variables_Helper {

    public static function isIdExist(G_Payroll_Variables $gebm) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . PAYROLL_VARIABLES ."
			WHERE id = ". Model::safeSql($gebm->getId()) ."
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . PAYROLL_VARIABLES			
		;
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlGetAllSettings() {
		$sql = "
			SELECT * 
			FROM " . PAYROLL_VARIABLES . "	
			ORDERY BY id DESC	
			LIMIT 1
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}

	public static function sqlGetDefaultSettings() {
		$sql = "
			SELECT * 
			FROM " . PAYROLL_VARIABLES . "	
			WHERE id =" . Model::safeSql(G_Payroll_Variables::DEFAULT_ID) . "
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}
}
?>