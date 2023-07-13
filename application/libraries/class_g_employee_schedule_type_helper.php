<?php
class G_Employee_Schedule_Type_Helper {
	public static function isIdExist(G_Employee_Schedule_Type $gegs) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . V2_EMPLOYEE_SCHEDULE_TYPE ."
			WHERE id = ". Model::safeSql($gegs->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}	
	
	public static function countTotalRecordsByEmployeeId(IEmployee $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . V2_EMPLOYEE_SCHEDULE_TYPE ."
			WHERE employee_id = ". Model::safeSql($e->getId()) ."
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}	

    public static function sqlAllEmployeeSchedulesByDateRange( $date_start = '', $date_end = '', $fields = array() ){
    	if( !empty($fields) ){
    		$sql_fields = implode(",", $fields);
    	}else{
    		$sql_fields = " * ";
    	}

    	$date_start = date("Y-m-d",strtotime($date_start));
    	$date_end   = date("Y-m-d",strtotime($date_end));

		$sql = "
	        	SELECT {$sql_fields}
				FROM " . V2_EMPLOYEE_SCHEDULE_TYPE . " g 	
					LEFT JOIN " . EMPLOYEE . " e ON g.employee_group_id = e.id			  
				WHERE g.employee_group =" . Model::safeSql(ENTITY_EMPLOYEE) . "
					AND g.date_start BETWEEN " . Model::safeSql($date_start) . " AND " . Model::safeSql($date_end) . "
				ORDER BY g.employee_group_id, g.date_start ASC
	        ";

	    $rows = Model::runSql($sql,true);
		return $rows;
    }

     public static function sqlAllEmployeeWithDuplicateSchedulesByDateRange( $date_start = '', $date_end = '', $fields = array() ){
    	if( !empty($fields) ){
    		$sql_fields = implode(",", $fields);
    	}else{
    		$sql_fields = " * ";
    	}

    	$date_start = date("Y-m-d",strtotime($date_start));
    	$date_end   = date("Y-m-d",strtotime($date_end));

		$sql = "
	        	SELECT {$sql_fields}
				FROM " . V2_EMPLOYEE_SCHEDULE_TYPE . " g 	
					LEFT JOIN " . EMPLOYEE . " e ON g.employee_group_id = e.id			  
				WHERE (
					SELECT COUNT(id)
					FROM " . V2_EMPLOYEE_SCHEDULE_TYPE . " gg 
					WHERE gg.employee_group =" . Model::safeSql(ENTITY_EMPLOYEE) . "
					AND gg.date_start BETWEEN " . Model::safeSql($date_start) . " AND " . Model::safeSql($date_end) . "
				) > 1
				ORDER BY g.employee_group_id, g.date_start ASC
	        ";    	    
	        echo $sql;
	    $rows = Model::runSql($sql,true);
		return $rows;
    }
}
?>