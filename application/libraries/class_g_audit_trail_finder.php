<?php
class G_Audit_Trail_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . AUDIT_TRAIL ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}	
	
	public static function findBySearch($search) {
		$sql = "
			SELECT * 
			FROM " . AUDIT_TRAIL ." 
			WHERE {$search['field']} LIKE " .Model::safeSql('%'.$search['search'].'%'). "
		";
		return self::getRecords($sql);
		
	} 

	public static function findAll($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . AUDIT_TRAIL ." 			
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
		$at = new G_Audit_Trail();
		$at->setId($row['id']);
		$at->setUser($row['user']);
		$at->setAction($row['action']);
		$at->setEventStatus($row['event_status']);
		$at->setDetails($row['details']);
		$at->setAuditDate($row['audit_date']);
		$at->setIpAddress($row['ip_address']);								
		return $at;
	}
	
}
?>