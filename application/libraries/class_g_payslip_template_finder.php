<?php
class G_Payslip_Template_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT e.id, e.template_name, e.is_default 
			FROM ". G_PAYSLIP_TEMPLATE ." e
			WHERE e.id = ". Model::safeSql($id) ."	
			LIMIT 1		
		";
		return self::getRecord($sql);
	}
	
	public static function findByName($name) {
		$sql = "
			SELECT
				e.id, e.template_name, e.is_default 
			FROM ". G_PAYSLIP_TEMPLATE ." e
			WHERE e.template_name = ". Model::safeSql($name) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}
	
	public static function findAll() {
		$sql = "
			SELECT e.id, e.template_name, e.is_default 
			FROM ". G_PAYSLIP_TEMPLATE ." e
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
		$e = new G_Payslip_Template;
		$e->setId($row['id']);
		$e->setTemplateName($row['template_name']);
        $e->setIsDefault($row['is_default']);
		return $e;
	}
}
?>