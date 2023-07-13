<?php
/**
 * @Software			: HR & Payroll Web
 * @Company 			: Gleent Innovative Technologies
 * @Developement Team	: Marvin Dungog, Marlito Dungog, Bryan Bio, Jeniel Mangahis, Bryann Revina
 * @Design Team			: Jayson Alipala
 * @Author				: Bryann Revina
 */
 
class G_Settings_Dependent_Relationship_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . DEPENDENT_RELATIONSHIP ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}
	
	public static function findByCompanyStructureId($csid) {
		$sql = "
			SELECT * 
			FROM " . DEPENDENT_RELATIONSHIP ." 
			WHERE company_structure_id =". Model::safeSql($csid) ."			
		";
		return self::getRecords($sql);
	}
	
	public static function findByRelationship($name) {
		$sql = "
			SELECT * 
			FROM " . DEPENDENT_RELATIONSHIP ." 
			WHERE relationship =". Model::safeSql($name) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}
	
	public static function findAll($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . DEPENDENT_RELATIONSHIP ."
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
		$g = new G_Settings_Dependent_Relationship($row['id']);
		$g->setId($row['id']);
		$g->setCompanyStructureId($row['company_structure_id']);
		$g->setRelationship($row['relationship']);	
		return $g;
	}
}
?>