<?php
class G_Settings_Membership_Type_Manager {
	public static function save(G_Settings_Membership_Type $gsmt, G_Company_Structure $gcs) {
		if (G_Settings_Membership_Type_Helper::isIdExist($gsmt) > 0 ) {
			$sql_start = "UPDATE ". MEMBERSHIP_TYPE . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gsmt->getId());		
		}else{
			$sql_start = "INSERT INTO ". MEMBERSHIP_TYPE . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id = " . Model::safeSql($gcs->getId()) . ",
			type                 = " . Model::safeSql($gsmt->getType()) . " "			
			. $sql_end ."	
		
		";
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Settings_Location $gsl){
		if(G_Settings_Membership_Type_Helper::isIdExist($gsl) > 0){
			$sql = "
				DELETE FROM ". MEMBERSHIP_TYPE ."
				WHERE id =" . Model::safeSql($gsl->getId());
			Model::runSql($sql);
		}	
	}
}
?>