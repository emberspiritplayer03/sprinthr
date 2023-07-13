<?php
class G_Employee_Schedule_Type_Finder {
	public static function findId($id) {
		$sql = "
			SELECT * 
			FROM " . V2_EMPLOYEE_SCHEDULE_TYPE . "
			WHERE id=" . Model::safeSql($id) . "
			LIMIT 1
		";

		return self::getRecord($sql);
	}

	public static function findAllByEmployeeId($id) {
		$sql = "
			SELECT * 
			FROM " . V2_EMPLOYEE_SCHEDULE_TYPE . "
			WHERE employee_id=" . Model::safeSql($id) . "
		";

		return self::getRecord($sql);
	}

	public static function findAllByEmployeeIdAndScheduleId($id, $schedule_id) {
		$sql = "
			SELECT * 
			FROM " . V2_EMPLOYEE_SCHEDULE_TYPE . "
			WHERE employee_id	=" . Model::safeSql($id) . " AND
			schedule_id			=" . Model::safeSql($schedule_id) . "
			LIMIT 1
		";

		return self::getRecord($sql);
	}

	public static function findByScheduleTypeId($id) {
		$sql = "
			SELECT * 
			FROM " . V2_EMPLOYEE_SCHEDULE_TYPE . "
			WHERE schedule_type_id =" . Model::safeSql($id) . "
			LIMIT 1
		";

		return self::getRecord($sql);
	}

	public static function findByEmployeeAndDate(IEmployee $e, $date) {
		$sql = "
                SELECT *
                FROM " . V2_EMPLOYEE_SCHEDULE_TYPE . "
                WHERE date 			= " . Model::safeSql($date) . "
                AND employee_id 	= " . Model::safeSql($e->getId()) . "
                LIMIT 1
            ";
		return self::getRecord($sql);
    } 
	public static function findByEmployeeAndDateScheduleMain($e, $date) {
		$sql = "
                SELECT *
                FROM " . V2_EMPLOYEE_SCHEDULE_TYPE . "
                WHERE date 			= " . Model::safeSql($date) . "
                AND employee_id 	= " . Model::safeSql($e) . "
                LIMIT 1
            ";
		return self::getRecord($sql);
    } 
	public static function findByDate($date) {
		$sql = "
                SELECT *
                FROM " . V2_EMPLOYEE_SCHEDULE_TYPE . "
                WHERE date 	= " . Model::safeSql($date) . "
            ";
		return self::getRecords($sql);
    } 
	
	public static function findAllByEmployeeGroupId($employee_group_id,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . V2_EMPLOYEE_SCHEDULE_TYPE ." 	
			WHERE employee_id =" . Model::safeSql($employee_group_id) . "		
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}

	public static function findAllByEmployeeGroupIdErrorTab($employee_group_id,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . V2_EMPLOYEE_SCHEDULE_TYPE ." 	
			WHERE employee_id =" . Model::safeSql($employee_group_id) . "		
			".$order_by."
			LIMIT 1	
		";
		
		return self::getRecords($sql);
	}

	public static function findSchedule($employee_group_id, $schedule_type, $order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . V2_EMPLOYEE_SCHEDULE_TYPE ." 	
			WHERE employee_id =" . Model::safeSql($employee_group_id) . "
			AND schedule_type_id =" . Model::safeSql($schedule_type->getId()) . "		
			".$order_by."
			LIMIT 1	
		";
		
		return self::getRecords($sql);
	}
	
	public static function findAllByScheduleGroupId($schedule_group_id,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . V2_EMPLOYEE_SCHEDULE_TYPE ." 	
			WHERE schedule_id =" . Model::safeSql($schedule_group_id) . "		
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findAllByScheduleId($schedule_id,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . V2_EMPLOYEE_SCHEDULE_TYPE ." 			
			WHERE schedule_id =" . Model::safeSql($schedule_id) . " 
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}

	
	public static function findAll($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . V2_EMPLOYEE_SCHEDULE_TYPE ." 			
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	private static function getRecord($sql) {
		$result = Model::runSql($sql);
		$total = mysql_num_rows($result);
		if ($total == 0) {
			return false;	
		}		
		$row = Model::fetchAssoc($result);
		$records = self::newObject($row);	
		return $records;
	}	
	
	private static function getRecords($sql) {
		$result = Model::runSql($sql);
		$total = mysql_num_rows($result);
		if ($total == 0) {
			return false;	
		}
		while ($row = Model::fetchAssoc($result)) {
			$records[$row['id']] = self::newObject($row);
		}
		return $records;
	}
	
	private static function newObject($row) {
		$gegs = new G_Employee_Schedule_Type();
		$gegs->setId($row['id']);
		$gegs->setDate($row['date']);
		$gegs->setEmployeeId($row['employee_id']);
		$gegs->setScheduleType($row['schedule_type']);
		$gegs->setScheduleTemplateId($row['schedule_template_id']);		
		return $gegs;
	}
}
?>