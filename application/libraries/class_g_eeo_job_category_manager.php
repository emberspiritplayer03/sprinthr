<?php
class G_Eeo_Job_Category_Manager {
	public static function save(G_Eeo_Job_Category $g) {
		if (G_Eeo_Job_Category_Helper::isIdExist($g) > 0 ) {
			$sql_start = "UPDATE ". G_EEO_JOB_CATEGORY . "";
			$sql_end   = "WHERE id = ". Model::safeSql($g->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EEO_JOB_CATEGORY . "";
			$sql_end   = " ";		
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id = " . Model::safeSql($g->getCompanyStructureId()) .",
			category_name 		 = " . Model::safeSql($g->getCategoryName()) .",
			description 		 = " . Model::safeSql($g->getDescription()) ." "
			. $sql_end ."	
		
		";		
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Eeo_Job_Category $g){
		if(G_Eeo_Job_Category_Helper::isIdExist($g) > 0){
			$sql = "
				DELETE FROM ". G_EEO_JOB_CATEGORY ."
				WHERE id =" . Model::safeSql($g->getId());
			Model::runSql($sql);
		}
	
	}
}
?>