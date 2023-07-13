<?php
class G_Settings_Subdivision_Type_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . SUBDIVISION_TYPE ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}
	
	public static function findByIdInArray($id) {
		$sql = "
			SELECT * 
			FROM " . SUBDIVISION_TYPE ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getArrayRecord($sql);
	}
	
	public static function findByCompanyStructureId($csid) {
		$sql = "
			SELECT * 
			FROM " . SUBDIVISION_TYPE ." 
			WHERE company_structure_id =". Model::safeSql($csid) ."			
		";		
		return self::getRecords($sql);
	}
	
	public static function findAllByCompanyStructureId($csid,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . SUBDIVISION_TYPE ." 
			WHERE company_structure_id =". Model::safeSql($csid) ."	
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
			FROM " . SUBDIVISION_TYPE ." 
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findAllInArray() {
		$sql = "
			SELECT * 
			FROM " . SUBDIVISION_TYPE ." 			
			ORDER BY type ASC			
		";
		return self::getArrayRecords($sql);
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
	
	private static function getArrayRecord($sql) {
		$result = Model::runSql($sql);
		$total = mysql_num_rows($result);
		if ($total == 0) {
			return false;	
		}		
		return $row = Model::fetchAssoc($result);
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

	private static function getArrayRecords($sql) {
		$result = Model::runSql($sql);
		$total = mysql_num_rows($result);
		if ($total == 0) {
			return false;	
		}
		while ($row = Model::fetchAssoc($result)) {
			$records[$row['id']] = $row;
		}
		return $records;
	}

	private static function newObject($row) {
		$gsst = new G_Settings_Subdivision_Type($row['id']);
		$gsst->setCompanyStructureId($row['company_structure_id']);
		$gsst->setType($row['type']);		
		return $gsst;
	}
	
}
?>