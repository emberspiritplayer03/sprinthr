<?php
class G_Settings_Subdivision_Type_Manager {
	public static function save(G_Settings_Subdivision_Type $gsst, G_Company_Structure $gcs) {
		if (G_Settings_Subdivision_Type_Helper::isIdExist($gsst) > 0 ) {
			$sql_start = "UPDATE ". SUBDIVISION_TYPE . "";
			$sql_end   = "WHERE id = ". Model::safeSql($gsst->getId());		
		}else{
			$sql_start = "INSERT INTO ". SUBDIVISION_TYPE . "";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id = " . Model::safeSql($gcs->getId()) . ",
			type                 = " . Model::safeSql($gsst->getType()) . " "
			. $sql_end ."	
		
		";
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Settings_Subdivision_Type $gsst){
		if(G_Settings_Subdivision_Type_Helper::isIdExist($gsst) > 0){
			$sql = "
				DELETE FROM ". SUBDIVISION_TYPE ."
				WHERE id =" . Model::safeSql($gsst->getId());
			Model::runSql($sql);
		}	
	}
}
?>