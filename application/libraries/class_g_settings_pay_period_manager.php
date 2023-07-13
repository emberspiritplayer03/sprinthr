<?php
class G_Settings_Pay_Period_Manager {
	public static function save(G_Settings_Pay_Period $gspp, G_Company_Structure $gcs) {
		if (G_Settings_Pay_Period_Helper::isIdExist($gspp) > 0 ) {
			$sql_start = "UPDATE ". G_SETTINGS_PAY_PERIOD . "";
			$sql_end   = "WHERE id = ". Model::safeSql($gspp->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_SETTINGS_PAY_PERIOD . "";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id = " . Model::safeSql($gcs->getId()) . ",
			pay_period_code		 = " . Model::safeSql($gspp->getPayPeriodCode()) . ",
			pay_period_name		 = " . Model::safeSql($gspp->getPayPeriodName()) . ",
			cut_off				 = " . Model::safeSql($gspp->getCutOff()) . ",
			payout_day			 = " . Model::safeSql($gspp->getPayOutDay()) . ",
			is_default           = " . Model::safeSql($gspp->getIsDefault()) . " "
			. $sql_end ."	
		
		";
		Model::runSql($sql);
		return mysql_insert_id();		
	}

	public static function updateDefault(G_Settings_Pay_Period $gspp) {		
		if (G_Settings_Pay_Period_Helper::isIdExist($gspp) > 0 ) {
			$sql = "
				UPDATE " . G_SETTINGS_PAY_PERIOD . "
				SET
					company_structure_id = " . Model::safeSql($gspp->getCompanyStructureId()) . ",
					pay_period_code		 = " . Model::safeSql($gspp->getPayPeriodCode()) . ",
					pay_period_name		 = " . Model::safeSql($gspp->getPayPeriodName()) . ",
					cut_off				 = " . Model::safeSql($gspp->getCutOff()) . ",
					payout_day			 = " . Model::safeSql($gspp->getPayOutDay()) . ",
					is_default           = " . Model::safeSql($gspp->getIsDefault()) . " 
				WHERE id =" . Model::safeSql($gspp->getId()) . "
			";
						
			Model::runSql($sql);
			return true;
		}else{
			return false;
		}
		
	}
		
	public static function delete(G_Settings_Pay_Period $gspp){
		if(G_Settings_Pay_Period_Helper::isIdExist($gspp) > 0){
			$sql = "
				DELETE FROM ". G_SETTINGS_PAY_PERIOD ."
				WHERE id =" . Model::safeSql($gspp->getId());
			Model::runSql($sql);
		}	
	}
	
	public static function setAsDefault(G_Settings_Pay_Period $gspp, G_Company_Structure $gcs){
		if(G_Settings_Pay_Period_Helper::isIdExist($gspp) > 0){
			$sql = "
				UPDATE ". G_SETTINGS_PAY_PERIOD ."
				SET is_default = 1 
				WHERE company_structure_id =" . Model::safeSql($gcs->getId()) . "
				AND id =" . Model::SafeSql($gspp->getId());
				
			Model::runSql($sql);
		}	
	}
	
	public static function setAllNotDefault(G_Company_Structure $gcs){
		$sql = "
				UPDATE ". G_SETTINGS_PAY_PERIOD ."
				SET is_default = 0 
				WHERE company_structure_id =" . Model::safeSql($gcs->getId());			
		Model::runSql($sql);	
	}
}
?>