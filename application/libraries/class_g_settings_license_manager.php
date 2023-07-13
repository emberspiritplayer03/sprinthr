<?php
class G_Settings_License_Manager {
	public static function save(G_Settings_License $gsl, G_Company_Structure $gcs) {
		if (G_Settings_License_Helper::isIdExist($gsl) > 0 ) {
			$sql_start = "UPDATE ". LICENSE . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gsl->getId());		
		}else{
			$sql_start = "INSERT INTO ". LICENSE . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id = " . Model::safeSql($gcs->getId()) . ",
			license_type         = " . Model::safeSql($gsl->getLicenseType()) . ",
			description          = " . Model::safeSql($gsl->getDescription()) . " "
			. $sql_end ."	
		
		";
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Settings_Location $gsl){
		if(G_Settings_License_Helper::isIdExist($gsl) > 0){
			$sql = "
				DELETE FROM ". LICENSE ."
				WHERE id =" . Model::safeSql($gsl->getId());
			Model::runSql($sql);
		}	
	}
}
?>