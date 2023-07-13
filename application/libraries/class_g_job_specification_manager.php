<?php
class G_Job_Specification_Manager {
	public static function save(G_Job_Specification $g) {
		if (G_Job_Specification_Helper::isIdExist($g) > 0 ) {
			$sql_start = "UPDATE ". G_JOB_SPECIFICATION . "";
			$sql_end   = "WHERE id = ". Model::safeSql($g->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_JOB_SPECIFICATION . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id        = " . Model::safeSql($g->getCompanyStructureId()) .",
			name = " . Model::safeSql($g->getName()) .",
			description  = " . Model::safeSql($g->getDescription()) .",
			duties = ". Model::safeSql($g->getDuties()) . ""
			. $sql_end ."	
		
		";	
		echo $sql;	
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Job_Specification $g){
		if(G_Job_Specification_Helper::isIdExist($g) > 0){
			$sql = "
				DELETE FROM ". G_JOB_SPECIFICATION ."
				WHERE id =" . Model::safeSql($g->getId());
			Model::runSql($sql);
		}
	
	}
}
?>