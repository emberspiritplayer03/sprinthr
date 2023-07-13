<?php
class G_Employee_Group_Schedule_Helper {
	public static function isIdExist(G_Employee_Group_Schedule $gegs) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_GROUP_SCHEDULE ."
			WHERE id = ". Model::safeSql($gegs->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}	
	
	public static function countTotalRecordsByEmployeeId(IEmployee $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_GROUP_SCHEDULE ."
			WHERE employee_group_id = ". Model::safeSql($e->getId()) ."
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}	

	public static function sqlGroupSchedulesByEmployeeGroupId( $employee_group_id = 0 ){
		$rows = array();
		if( $employee_group_id > 0 ){
	    	$sql = "
	        	SELECT g.id AS group_schedule_id, s.id AS schedule_id, g.schedule_group_id, s.schedule_name, s.working_days, sg.public_id,
				 DATE_FORMAT(s.time_in,'%r')AS time_in, DATE_FORMAT(s.time_out,'%r')AS time_out, g.date_start
				FROM " . G_EMPLOYEE_GROUP_SCHEDULE . " g 
				 LEFT JOIN " . G_SCHEDULE . " s ON g.schedule_group_id = s.id 
				 LEFT JOIN " . G_SCHEDULE_GROUP . " sg ON g.schedule_group_id = sg.id			 
				WHERE g.employee_group_id =" . Model::safeSql($employee_group_id) . "
	        ";	       
	       $rows = Model::runSql($sql,true);
	    }
		return $rows;
    }

    public static function sqlEmployeeSchedulesByDateRange( $ids = '', $date_start = '', $date_end = '', $fields = array() ){
    	if( !empty($fields) ){
    		$sql_fields = implode(",", $fields);
    	}else{
    		$sql_fields = " * ";
    	}

    	$date_start = date("Y-m-d",strtotime($date_start));
    	$date_end   = date("Y-m-d",strtotime($date_end));

		$sql = "
	        	SELECT {$sql_fields}
				FROM " . G_EMPLOYEE_GROUP_SCHEDULE . " g 	
					LEFT JOIN " . EMPLOYEE . " e ON g.employee_group_id = e.id			  
				WHERE g.employee_group_id IN({$ids}) 
					AND g.employee_group =" . Model::safeSql(ENTITY_EMPLOYEE) . "
					AND g.date_start BETWEEN " . Model::safeSql($date_start) . " AND " . Model::safeSql($date_end) . "
				ORDER BY g.employee_group_id, g.date_start ASC
	        ";	       	    
	    $rows = Model::runSql($sql,true);
		return $rows;
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
				FROM " . G_EMPLOYEE_GROUP_SCHEDULE . " g 	
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
				FROM " . G_EMPLOYEE_GROUP_SCHEDULE . " g 	
					LEFT JOIN " . EMPLOYEE . " e ON g.employee_group_id = e.id			  
				WHERE (
					SELECT COUNT(id)
					FROM " . G_EMPLOYEE_GROUP_SCHEDULE . " gg 
					WHERE gg.employee_group =" . Model::safeSql(ENTITY_EMPLOYEE) . "
					AND gg.date_start BETWEEN " . Model::safeSql($date_start) . " AND " . Model::safeSql($date_end) . "
				) > 1
				ORDER BY g.employee_group_id, g.date_start ASC
	        ";    	    
	        echo $sql;
	    $rows = Model::runSql($sql,true);
		return $rows;
    }
	
	public static function getAllScheduleByEmployeeGroupId($employee_group_id) {
		$sql = "
			SELECT egs.employee_group_id, egs.schedule_group_id, 
			       gs.schedule_group_id, gs.schedule_name, gs.grace_period, gs.working_days, gs.time_in, gs.time_out,				 				   
			       gsg.effectivity_date, gsg.id 
			FROM " . G_EMPLOYEE_GROUP_SCHEDULE . " egs, " . G_SCHEDULE . " gs
				LEFT JOIN " . G_SCHEDULE_GROUP . " gsg
				ON  gs.schedule_group_id = gsg.id 
			WHERE egs.employee_group_id = " . Model::safeSql($employee_group_id) . " 
				AND gs.schedule_group_id = egs.schedule_group_id				
				OR gs.is_default = 1 
			ORDER BY egs.employee_group_id ASC
		";
		
		$result = Model::runSql($sql);
		$counter = 0;
		while ($row = Model::fetchAssoc($result)) {
			$return[$counter]['schedule_name']    = $row['schedule_name'];
			$return[$counter]['effectivity_date'] = $row['effectivity_date'];
			$return[$counter]['working_days']     = $row['working_days'];
			$return[$counter]['grace_period']     = $row['grace_period'];
			$return[$counter]['time_in'] 	      = $row['time_in'];
			$return[$counter]['time_out']         = $row['time_out'];
			$counter++;
		}
		return $return;
	}
}
?>