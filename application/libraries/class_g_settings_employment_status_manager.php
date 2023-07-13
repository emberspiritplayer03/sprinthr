<?php
class G_Settings_Employment_Status_Manager {
	public static function save(G_Settings_Employment_Status $g, G_Company_Structure $gcs) {
		if (G_Settings_Employment_Status_Helper::isIdExist($g) > 0 ) {
			$sql_start = "UPDATE ". EMPLOYMENT_STATUS . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($g->getId());		
		}else{
			$sql_start = "INSERT INTO ". EMPLOYMENT_STATUS . " ";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			id						= " . Model::safeSql($g->getId()) .",
			company_structure_id	= " . Model::safeSql($gcs->getId()) .",
			code					= " . Model::safeSql($g->getCode()) .",
			status 					= ". Model::safeSql($g->getStatus()) ." "
			. $sql_end ."	
		
		";		
		
		Model::runSql($sql);
		return mysql_insert_id();		
	}
	
	public static function deleteAll() {		
		$sql = "
			DELETE  FROM ". EMPLOYMENT_STATUS .""; 
		Model::runSql($sql);		
	}
		
	public static function delete(G_Settings_Employment_Status $g){
		if(G_Settings_Employment_Status_Helper::isIdExist($g) > 0){
			$sql = "
				DELETE FROM ". EMPLOYMENT_STATUS ."
				WHERE id =" . Model::safeSql($g->getId());
			Model::runSql($sql);
		}
	
	}
	
	public static function setToEmployee(G_Settings_Employment_Status $s,G_Employee $e) {
	
			$sql_start = "UPDATE ". EMPLOYEE . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId()) .";
			";		
		
		$sql = $sql_start ."
			SET
			employment_status_id     = " . Model::safeSql($s->getId()) ." "
			
		. $sql_end ."	
		
		";	
	
		Model::runSql($sql);
		return mysql_insert_id();
	}
}
?>