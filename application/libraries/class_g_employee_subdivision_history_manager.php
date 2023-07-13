<?php
class G_Employee_Subdivision_History_Manager {
	public static function save(G_Employee_Subdivision_History $e) {
		if (G_Employee_Subdivision_History_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_SUBDIVISION_HISTORY . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_SUBDIVISION_HISTORY . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id				= " . Model::safeSql($e->getEmployeeId()) .",
			company_structure_id	= " . Model::safeSql($e->getCompanyStructureId()) .",
			name					= " . Model::safeSql($e->getName()) .",
			type					= " . Model::safeSql($e->getType()) .",
			start_date				= " . Model::safeSql($e->getStartDate()) .",
			end_date				= " . Model::safeSql($e->getEndDate()) ."
			"
		
			. $sql_end ."	
		
		";	
		Model::runSql($sql);
		return mysql_insert_id();		
	}
	
	public static function resetEmployeePresentSubdivision(G_Employee_Subdivision_History $e){
		$sql = "
			UPDATE ". G_EMPLOYEE_SUBDIVISION_HISTORY ."
			SET end_date =" . Model::safeSql($e->getEndDate()) . " 
			WHERE employee_id =" . Model::safeSql($e->getEmployeeId());	
		Model::runSql($sql);
	}

	public static function resetEmployeePresentSubdivisionBySubdivisionHistory(G_Employee_Subdivision_History $e, $history_id){
		$sql = "
			UPDATE ". G_EMPLOYEE_SUBDIVISION_HISTORY ."
			SET end_date =" . Model::safeSql($e->getEndDate()) . " 
			WHERE employee_id =" . Model::safeSql($e->getEmployeeId()). " AND id = ".$history_id;
		Model::runSql($sql);
	}

	public static function updateSubdivisionBySubdivisionHistoryEndDate(G_Employee_Subdivision_History $e, $history_id,$end_date){
		$sql = "
			UPDATE ". G_EMPLOYEE_SUBDIVISION_HISTORY ."
			SET end_date =" . Model::safeSql($end_date) . " 
			WHERE employee_id =" . Model::safeSql($e->getEmployeeId()). " AND id = ".$history_id;
			
		Model::runSql($sql);
	}
		
	public static function delete(G_Employee_Subdivision_History $e){
		if(G_Employee_Subdivision_History_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_SUBDIVISION_HISTORY ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
}
?>