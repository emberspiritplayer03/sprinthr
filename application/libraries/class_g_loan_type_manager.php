<?php
class G_Loan_Type_Manager {
	public static function save(G_Loan_Type $glt) {
		if (G_Loan_Type_Helper::isIdExist($glt) > 0 ) {
			$sql_start = "UPDATE ". G_LOAN_TYPE . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($glt->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_LOAN_TYPE . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id =" . Model::safeSql($glt->getCompanyStructureId()) . ",
			loan_type  		     =" . Model::safeSql($glt->getLoanType()) . ",
			is_archive 	 		 =" . Model::safeSql($glt->getIsArchive()) . ",									
			date_created 		 =" . Model::safeSql($glt->getDateCreated()) . " "				
			. $sql_end ."	
		
		";			
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Loan_Type $glt){
		if(G_Loan_Type_Helper::isIdExist($glt) > 0){
			$sql = "
				DELETE FROM ". G_LOAN_TYPE ."
				WHERE id =" . Model::safeSql($glt->getId());
			Model::runSql($sql);
		}	
	}
}
?>