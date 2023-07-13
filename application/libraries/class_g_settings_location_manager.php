<?php
class G_Settings_Location_Manager {
	public static function save(G_Settings_Location $gsl, G_Company_Structure $gcs) {
		if (G_Settings_Location_Helper::isIdExist($gsl) > 0 ) {
			$sql_start = "UPDATE ". LOCATION . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gsl->getId());		
		}else{
			$sql_start = "INSERT INTO ". LOCATION . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id = " . Model::safeSql($gcs->getId()) . ",
			code                 = " . Model::safeSql($gsl->getCode()) . ",
			location             = " . Model::safeSql($gsl->getLocation()) . " "
			. $sql_end ."	
		
		";
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Settings_Location $gsl){
		if(G_Settings_Location_Helper::isIdExist($gsl) > 0){
			$sql = "
				DELETE FROM ". LOCATION ."
				WHERE id =" . Model::safeSql($gsl->getId());
			Model::runSql($sql);
		}	
	}
}
?>