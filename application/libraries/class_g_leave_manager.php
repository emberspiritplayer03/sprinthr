<?php
class G_Leave_Manager {
	public static function save(G_Leave $e) {
		if (G_Leave_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_LEAVE . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_LEAVE . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id= " . Model::safeSql($e->getCompanyStructureId()) .",
			name			   	= " . Model::safeSql($e->getName()) .",
			default_credit   	= " . Model::safeSql($e->getDefaultCredit()) .",
			is_paid				= " . Model::safeSql($e->getIsPaid()) .",
			gl_is_archive		= " . Model::safeSql($e->getIsArchive()) .",
			type        		= " . Model::safeSql($e->getType()) .",
			is_default     		= " . Model::safeSql($e->getIsDefault()) ."
			"
			. $sql_end ."	
		
		";	
	
		Model::runSql($sql);
		return mysql_insert_id();		
	}

	public static function bulkInsertData(G_Leave $e, $a_bulk_insert = array() ) {

		foreach($a_bulk_insert as $key => $l_data) {
			if(!empty($l_data) or $l_data != NULL) {
				if (G_Leave_Helper::isIdExist($e) > 0 ) {
					$sql_start = "UPDATE ". G_LEAVE . "";
					$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
				}else{
					$sql_start = "INSERT INTO ". G_LEAVE . "";
					$sql_end   = "";		
				}
				
				$sql = $sql_start ."
					SET
					company_structure_id= " . Model::safeSql($e->getCompanyStructureId()) .",
					name			   	= " . Model::safeSql($l_data) .",
					default_credit   	= " . Model::safeSql($e->getDefaultCredit()) .",
					is_paid				= " . Model::safeSql($e->getIsPaid()) .",
					gl_is_archive		= " . Model::safeSql($e->getIsArchive()) .",
					type        		= " . Model::safeSql($e->getType()) .",
					is_default     		= " . Model::safeSql($e->getIsDefault()) ."
					"
					. $sql_end ."	
				
				";	
			
				Model::runSql($sql);
			}
			
		}

		//return true;
	}	
		
	public static function delete(G_Leave_Available $e){
		if(G_Leave_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_LEAVE ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
}
?>