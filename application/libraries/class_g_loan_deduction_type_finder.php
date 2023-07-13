<?php
class G_Loan_Deduction_Type_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_LOAN_DEDUCTION_TYPE ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}	
	
	public static function findAllByCompanyStructureId($company_structure_id, $order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_LOAN_DEDUCTION_TYPE ." 
			WHERE company_structure_id =" . Model::safeSql($company_structure_id) . "
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}

	public static function findAllByIds($ids, $order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_LOAN_DEDUCTION_TYPE ." 
			WHERE id IN({$ids})
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findAllIsNotArchive($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_LOAN_DEDUCTION_TYPE ." 
			WHERE is_archive =" . Model::safeSql(G_Loan_Deduction_Type::NO) . "
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findAllIsArchive($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_LOAN_DEDUCTION_TYPE ." 
			WHERE is_archive =" . Model::safeSql(G_Loan_Deduction_Type::YES) . "
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
			FROM " . G_LOAN_DEDUCTION_TYPE ." 			
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
		$gldt = new G_Loan_Deduction_Type();
		$gldt->setId($row['id']);
		$gldt->setCompanyStructureId($row['company_structure_id']);
		$gldt->setDeductionType($row['deduction_type']);
		$gldt->setIsArchive($row['is_archive']);					
		$gldt->setDateCreated($row['date_created']);									
		return $gldt;
	}
}
?>