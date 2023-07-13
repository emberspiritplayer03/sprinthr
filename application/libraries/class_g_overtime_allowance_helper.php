<?php
class G_Overtime_Allowance_Helper {

    public static function isIdExist(G_Overtime_Allowance $g) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . G_OVERTIME_ALLOWANCE ."
			WHERE id = ". Model::safeSql($g->getId()) ."
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . G_OVERTIME_ALLOWANCE			
		;
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function countOtAllowanceByObjectAndDateStart($object_id, $object_type, $date_start) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . G_OVERTIME_ALLOWANCE ." 
			WHERE object_id = ".Model::safeSql($object_id)." 
				AND object_type = ".Model::safeSql($object_type)." 
				AND date_start = ".Model::safeSql($date_start)." 
			"		
		;
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function getOvertimeAllowanceByObjectIdAndObjectTypeAndDateStart($object_id, $object_type, $date) {
	
		$sql = "
			SELECT *
			FROM " . G_OVERTIME_ALLOWANCE ." 
			WHERE object_id = ".Model::safeSql($object_id)." 
				AND object_type = ".Model::safeSql($object_type)." 
				AND date_start <= ".Model::safeSql($date)."
			ORDER BY date_start DESC
		";		
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row;
	}

	public static function getAllOvertimeAllowanceByObjectIdAndObjectTypeAndDateStart($object_id, $object_type, $date) {
	
		$sql = "
			SELECT *
			FROM " . G_OVERTIME_ALLOWANCE ." 
			WHERE object_id = ".Model::safeSql($object_id)." 
				AND object_type = ".Model::safeSql($object_type)." 
				AND date_start <= ".Model::safeSql($date)."
			ORDER BY date_start DESC
		";
		$records = Model::runSql($sql,true);
		return $records;
	}
	
}
?>