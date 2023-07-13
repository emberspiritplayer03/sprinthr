<?php
class G_Employee_Dynamic_Field_Manager {
	public static function save(G_Employee_Dynamic_Field $e) {
		if (G_Employee_Dynamic_Field_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_DYNAMIC_FIELD . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_DYNAMIC_FIELD . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET			
			employee_id						= " . Model::safeSql($e->getEmployeeId()) .",
			title		   					= " . Model::safeSql($e->getTitle()) .",
			value		  					= " . Model::safeSql($e->getValue()) .",
			screen							= " . Model::safeSql($e->getScreen()) ." "
		
			. $sql_end ."	
		
		";	

		Model::runSql($sql);
		return mysql_insert_id();		
	}

	public static function bulkInsertDynamicField( $data = array() ) {
		$sql_start = "INSERT INTO ". G_EMPLOYEE_DYNAMIC_FIELD . "(employee_id,title,value)VALUES";		
		foreach( $data as $key => $value ){
			foreach( $value as $subValue ){
				$employee_id = Model::safeSql($key);
				$label = Model::safeSql($subValue['other_details_label']);
				$value = Model::safeSql($subValue['other_details_value']);
				if( $label != "" && $value != "" ){
					$values_array[] = "(" . $employee_id . "," . $label . "," . $value . ")";
				}
			}	
		}

		$sql_values = implode(",", $values_array);
		$sql 		= $sql_start . $sql_values;				
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Employee_Dynamic_Field $e){
		if(G_Employee_Dynamic_Field_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_DYNAMIC_FIELD ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}

	public static function deleteAllByEmployeeId($employee_id = 0){
		$sql = "
			DELETE FROM ". G_EMPLOYEE_DYNAMIC_FIELD ."
			WHERE employee_id =" . Model::safeSql($employee_id);		
		Model::runSql($sql);
	}
}
?>