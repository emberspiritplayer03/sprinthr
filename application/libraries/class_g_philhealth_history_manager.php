<?php
class G_Philhealth_History_Manager {
	
	public static function save(G_Philhealth_History $gcp) {
		if (G_Philhealth_History_Helper::isIdExist($gcp) > 0 ) {
			$action    = "update";
			$sql_start = "UPDATE ". G_PHILHEALTH_HISTORY . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gcp->getId());		
		}else{
			$action    = "insert";
			$sql_start = "INSERT INTO ". G_PHILHEALTH_HISTORY . " ";
			$sql_end   = " ";		
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id = " . Model::safeSql($gcp->getCompanyStructureId()) .",
			salary_from	 	  	 = " . Model::safeSql($gcp->getSalaryFrom()) .",
			salary_to		 	 = " . Model::safeSql($gcp->getSalaryTo()) .",
			multiplier_employee	 = " . Model::safeSql($gcp->getMultiplierEmployee()) .",			
			multiplier_employer	 = " . Model::safeSql($gcp->getMultiplierEmployer()) .",
			is_fixed 			 = " . Model::safeSql($gcp->getIsFixed()) .",
			date_end		 = " . Model::safeSql($gcp->getDateEnd()) ."
			"	
			. $sql_end ."	
		";	
	
		Model::runSql($sql);
		
		if($action == "update") {
			return true;
		} else {
			return mysql_insert_id();	
		}
	}

	public static function update(G_Philhealth_History $e) {
		if ( $e ) {	
			$sql = "
				UPDATE ". G_PHILHEALTH_HISTORY . " 
				SET
					salary_from  	        =" . Model::safeSql($e->getSalaryFrom()) . ",
					salary_to		        =" . Model::safeSql($e->getSalaryTo()) . ",					
					multiplier_employee    	=" . Model::safeSql($e->getMultiplierEmployee()) . ",
					multiplier_employer	  	=" . Model::safeSql($e->getMultiplierEmployer()) . ",
					is_fixed 			  	=" . Model::safeSql($e->getIsFixed()) . ",
					date_end		 = " . Model::safeSql($gcp->getDateEnd()) ."
				 WHERE id = ". Model::safeSql($e->getId());		
			Model::runSql($sql);
			$return = true;
		}else{
			$return = false;
		}
		return $return;		
	}	
	
	public static function delete(G_Philhealth_History $gcb){
		if(G_Philhealth_History_Helper::isIdExist($gcb) > 0){
			$sql = "
				DELETE FROM ". G_PHILHEALTH_HISTORY ."
				WHERE id =" . Model::safeSql($gcb->getId());
			Model::runSql($sql);
		}	
	}	

}
?>