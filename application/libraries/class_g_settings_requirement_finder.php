<?php
class G_Settings_Requirement_Finder {
	public static function findById($id) {
		$sql = "
			SELECT * 
				FROM " . G_SETTINGS_REQUIREMENTS . "
			WHERE id=" . Model::safeSql($id) . "
			LIMIT 1
		";

		return self::getRecord($sql);
	}
	
	public static function findAllIsArchiveByCompanyStructureId($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_REQUIREMENTS ." 	
			WHERE is_archive =" . Model::safeSql(G_Settings_Requirement::YES) . "		
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findAllIsNotArchiveByCompanyStructureId($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_REQUIREMENTS ." 	
			WHERE is_archive =" . Model::safeSql(G_Settings_Requirement::NO) . "		
			".$order_by."
			".$limit."		
		";				
		return self::getRecords($sql);
	}
	
	public static function findAllByCompanyStructureId($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_REQUIREMENTS ." 			
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
			FROM " . G_SETTINGS_REQUIREMENTS ." 			
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
		$gsr = new G_Settings_Requirement();
		$gsr->setId($row['id']);
		$gsr->setCompanyStructureId($row['company_structure_id']);
		$gsr->setName($row['title']);
		$gsr->setIsArchive($row['is_archive']);		
		$gsr->setDateCreated($row['date_created']);
		return $gsr;
	}
}
?>