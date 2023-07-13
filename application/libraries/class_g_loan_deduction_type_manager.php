<?php
class G_Loan_Deduction_Type_Manager {
	public static function save(G_Loan_Deduction_Type $gldt) {
		if (G_Loan_Deduction_Type_Helper::isIdExist($gldt) > 0 ) {
			$sql_start = "UPDATE ". G_LOAN_DEDUCTION_TYPE . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gldt->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_LOAN_DEDUCTION_TYPE . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id =" . Model::safeSql($gldt->getCompanyStructureId()) . ",
			deduction_type       =" . Model::safeSql($gldt->getDeductionType()) . ",
			is_archive 	 		 =" . Model::safeSql($gldt->getIsArchive()) . ",									
			date_created 		 =" . Model::safeSql($gldt->getDateCreated()) . " "				
			. $sql_end ."	
		
		";			
		
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Loan_Deduction_Type $gldt){
		if(G_Loan_Deduction_Type_Helper::isIdExist($gldt) > 0){
			$sql = "
				DELETE FROM ". G_LOAN_DEDUCTION_TYPE ."
				WHERE id =" . Model::safeSql($gldt->getId());
			Model::runSql($sql);
		}	
	}
}
?>