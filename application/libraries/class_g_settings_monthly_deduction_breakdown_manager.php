<?php
class G_Settings_Monthly_Deduction_Breakdown_Manager {
	public static function save(G_Settings_Monthly_Deduction_Breakdown $e) {
		if (G_Settings_Monthly_Deduction_Breakdown_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_SETTINGS_MONTHLY_DEDUCTION_BREAKDOWN . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_SETTINGS_MONTHLY_DEDUCTION_BREAKDOWN . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			name		  =" . Model::safeSql($e->getName()) . ",
			breakdown	  =" . Model::safeSql($e->getBreakdown()) . ",
			salary_credit =" . Model::safeSql($e->getSalaryCredit()) . ",
			is_taxable    =" . Model::safeSql($e->getIsTaxable()) . ",
			is_active	  =" . Model::safeSql($e->getIsActive()) . "
			"
			. $sql_end ."	
		";	

		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Settings_Monthly_Deduction_Breakdown $e){
		if(G_Settings_Monthly_Deduction_Breakdown_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_SETTINGS_MONTHLY_DEDUCTION_BREAKDOWN ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
}
?>