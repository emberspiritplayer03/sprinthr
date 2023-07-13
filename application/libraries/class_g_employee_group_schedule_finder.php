<?php
class G_Employee_Group_Schedule_Finder {
	public static function findId($id) {
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_GROUP_SCHEDULE . "
			WHERE id=" . Model::safeSql($id) . "
			LIMIT 1
		";

		return self::getRecord($sql);
	}
	
	public static function findAllByEmployeeGroupId($employee_group_id,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_GROUP_SCHEDULE ." 	
			WHERE employee_group_id =" . Model::safeSql($employee_group_id) . "		
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findAllByScheduleGroupId($schedule_group_id,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_GROUP_SCHEDULE ." 	
			WHERE schedule_group_id =" . Model::safeSql($schedule_group_id) . "		
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
			FROM " . G_EMPLOYEE_GROUP_SCHEDULE ." 			
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
			FROM " . G_EMPLOYEE_GROUP_SCHEDULE ." 			
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
		$gegs = new G_Employee_Group_Schedule();
		$gegs->setId($row['id']);
		$gegs->setEmployeeGroupId($row['employee_group_id']);
		$gegs->setScheduleGroupId($row['schedule_group_id']);
		$gegs->setScheduleId($row['schedule_id']);		
		$gegs->setDateStart($row['date_start']);
		$gegs->setDateEnd($row['date_end']);
		$gegs->setEmployeeGroup($row['employee_group']);
		return $gegs;
	}
}
?>