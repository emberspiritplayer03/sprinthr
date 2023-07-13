<?php
class G_Settings_Location_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . LOCATION ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}
	
	public static function findByCompanyStructureId($csid) {
		$sql = "
			SELECT * 
			FROM " . LOCATION ." 
			WHERE company_structure_id =". Model::safeSql($csid) ."			
		";
		return self::getRecords($sql);
	}
	
	public static function findAllByCompanyStructureId($company_structure_id,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . LOCATION ." 
			WHERE company_structure_id =" . Model::safeSql($company_structure_id) . "
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
			FROM " . LOCATION ." 
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
		$gsl = new G_Settings_Location($row['id']);
		$gsl->setCompanyStructureId($row['company_structure_id']);
		$gsl->setCode($row['code']);
		$gsl->setLocation($row['location']);		
		return $gsl;
	}
}
?>