<?php
class G_Settings_Membership_Type_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . MEMBERSHIP_TYPE ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}
	
	public static function findByCompanyStructureId($csid) {
		$sql = "
			SELECT * 
			FROM " . MEMBERSHIP_TYPE ." 
			WHERE company_structure_id =". Model::safeSql($csid) ."			
		";
		return self::getRecords($sql);
	}
	
	public static function findAll($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';

		$sql = "
			SELECT * 
			FROM " . MEMBERSHIP_TYPE ." 
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
		$gsmt = new G_Settings_Membership_Type($row['id']);
		$gsmt->setCompanyStructureId($row['company_structure_id']);
		$gsmt->setType($row['type']);		
		return $gsmt;
	}
}
?>