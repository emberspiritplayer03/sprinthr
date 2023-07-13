<?php
class G_Settings_Skills_Manager {
	public static function save(G_Settings_Skills $gss, G_Company_Structure $gcs) {
		if (G_Settings_Skills_Helper::isIdExist($gss) > 0 ) {
			$sql_start = "UPDATE ". SKILLS . "";
			$sql_end   = "WHERE id = ". Model::safeSql($gss->getId());		
		}else{
			$sql_start = "INSERT INTO ". SKILLS . "";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id = " . Model::safeSql($gcs->getId()) . ",
			skill                = " . Model::safeSql($gss->getSkill()) . " "
			. $sql_end ."	
		
		";
		
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Settings_Skills $gss){
		if(G_Settings_Skills_Helper::isIdExist($gss) > 0){
			$sql = "
				DELETE FROM ". SKILLS ."
				WHERE id =" . Model::safeSql($gss->getId());
			Model::runSql($sql);
		}	
	}
}
?>