<?php
class G_Tax_Table_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_TAX_TABLE ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}	
	
	public static function findAllByCompanyStructureId($company_structure_id,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_TAX_TABLE ." 
			WHERE company_structure_id =" . Model::safeSql($company_structure_id) . "			
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findAllMonthlyByCompanyStructure($company_structure_id,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_TAX_TABLE ." 
			WHERE company_structure_id =" . Model::safeSql($company_structure_id) . "
			AND pay_frequency =" . Model::safeSql(G_Tax_Table::MONTHLY) . "			
			".$order_by."
			".$limit."		
		";	
		return self::getRecords($sql);
	}
	
	public static function findAllSemiMonthlyByCompanyStructure($company_structure_id,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_TAX_TABLE ." 
			WHERE company_structure_id =" . Model::safeSql($company_structure_id) . "
			AND pay_frequency =" . Model::safeSql(G_Tax_Table::SEMI_MONTHLY) . "			
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
			FROM " . G_TAX_TABLE ." 			
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
		$gtt = new G_Tax_Table();
		$gtt->setId($row['id']);
		$gtt->setCompanyStructureId($row['company_structure_id']);
		$gtt->setPayFrequency($row['pay_frequency']);
		$gtt->setStatus($row['status']);				
		$gtt->setD0($row['d0']);				
		$gtt->setD1($row['d1']);
		$gtt->setD2($row['d2']);
		$gtt->setD3($row['d3']);
		$gtt->setD4($row['d4']);
		$gtt->setD5($row['d5']);
		$gtt->setD6($row['d6']);
		$gtt->setD7($row['d7']);
		$gtt->setD8($row['d8']);									
		return $gtt;
	}
}
?>