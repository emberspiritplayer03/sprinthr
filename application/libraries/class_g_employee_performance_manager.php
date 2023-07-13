<?php
class G_Employee_Performance_Manager {
	public static function save(G_Employee_Performance $e) {
		if (G_Employee_Performance_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_PERFORMANCE . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_PERFORMANCE . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id			= " . Model::safeSql($e->getEmployeeId()) .",
			company_structure_id= " . Model::safeSql($e->getCompanyStructureId()) .",
			performance_id 		= " . Model::safeSql($e->getPerformanceId()) .",
			performance_title	= " . Model::safeSql($e->getPerformanceTitle()) .",
			reviewer_id	   		= " . Model::safeSql($e->getReviewerId()) .",
			position	   		= " . Model::safeSql($e->getPosition()) .",
			created_by	   		= " . Model::safeSql($e->getCreatedBy()) .",
			created_date   		= " . Model::safeSql($e->getCreatedDate()) .",
			period_from	   		= " . Model::safeSql($e->getPeriodFrom()) .",
			period_to	   		= " . Model::safeSql($e->getPeriodTo()) .",
			due_date	   		= " . Model::safeSql($e->getDueDate()) .",
			status		   		= " . Model::safeSql($e->getStatus()) .",
			summary		   		= " . Model::safeSql($e->getSummary()) .",
			kpi		   			= " . Model::safeSql($e->getKpi()) ."
			
			"
		
			. $sql_end ."	
		
		";	

		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Employee_Performance $e){
		if(G_Employee_Performance_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_PERFORMANCE ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
}
?>