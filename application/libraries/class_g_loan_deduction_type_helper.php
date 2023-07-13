<?php
class G_Loan_Deduction_Type_Helper {
	public static function isIdExist(Loan_Deduction_Type $gldt) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_LOAN_DEDUCTION_TYPE ."
			WHERE id = ". Model::safeSql($gldt->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByCompanyStructureId($company_structure_id) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_LOAN_DEDUCTION_TYPE ."
			WHERE company_structure_id = ". Model::safeSql($company_structure_id) ."
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}	
}
?>