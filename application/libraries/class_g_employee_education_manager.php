<?php
class G_Employee_Education_Manager {
	public static function save(G_Employee_Education $e) {
		if (G_Employee_Education_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_EDUCATION . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_EDUCATION . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id			= " . Model::safeSql($e->getEmployeeId()) .",
			institute		   	= " . Model::safeSql($e->getInstitute()) .",
			course		   		= " . Model::safeSql($e->getCourse()) .",
			year		   		= " . Model::safeSql($e->getYear()) .",
			start_date		   	= " . Model::safeSql($e->getStartDate()) .",
			end_date		   	= " . Model::safeSql($e->getEndDate()) .",
			gpa_score		   	= " . Model::safeSql($e->getGpaScore()) .",
			attainment		   	= " . Model::safeSql($e->getAttainment()) ."
			
			"
	
			. $sql_end ."	
		
		";	
	
		Model::runSql($sql);
		return mysql_insert_id();		
	}

	public static function bulkInsert( $data = array() ){
		if( !empty($data) ){
			$sql_insert_values = implode(",", $data);
			$sql = "INSERT INTO " . G_EMPLOYEE_EDUCATION . "(employee_id,institute,course,start_date,end_date,gpa_score)VALUES{$sql_insert_values}";		
			Model::runSql($sql);
			
			return true;			
		}else{
			return false;
		}
	}
		
	public static function delete(G_Employee_Education $e){
		if(G_Employee_Education_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_EDUCATION ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
}
?>