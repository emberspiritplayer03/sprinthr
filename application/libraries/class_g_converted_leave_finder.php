<?php
class G_Converted_Leave_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . CONVERTED_LEAVES ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}	
	
	public static function findAll($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . CONVERTED_LEAVES ." 			
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
		$gl = new G_Convert_Leave();
		$gl->setId($row['id']);
		$gl->setEmployeeId($row['employee_id']);
		$gl->setLeaveId($row['leave_id']);
		$gl->setYear($row['year']);	
		$gl->setTotalLeaveConverted($row['total_leave_converted']);						
		$gl->setAmount($row['amount']);						
		$gl->setDateConverted($row['date_converted']);						
		$gl->setCreated($row['created']);								
		return $gl;
	}
}
?>