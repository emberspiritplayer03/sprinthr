<?php
class G_Loan_Type_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_LOAN_TYPE ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}	

	public static function findByLoanType($loan_type) {
		$sql = "
			SELECT * 
			FROM " . G_LOAN_TYPE ." 
			WHERE loan_type =". Model::safeSql($loan_type) ."
			ORDER BY id DESC
			LIMIT 1
		";
		return self::getRecord($sql);
	}	
	
	public static function findAllByCompanyStructureId($company_structure_id, $order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_LOAN_TYPE ." 
			WHERE company_structure_id =" . Model::safeSql($company_structure_id) . "
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
			FROM " . G_LOAN_TYPE ." 
			WHERE is_archive =" . Model::safeSql(G_Loan_Type::NO) . "
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
			FROM " . G_LOAN_TYPE ." 
			WHERE is_archive =" . Model::safeSql(G_Employee_Loan_Type::YES) . "
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
			FROM " . G_LOAN_TYPE ." 			
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
		$glt = new G_Loan_Type();
		$glt->setId($row['id']);
		$glt->setCompanyStructureId($row['company_structure_id']);
		$glt->setLoanType($row['loan_type']);
		$glt->setIsArchive($row['is_archive']);					
		$glt->setDateCreated($row['date_created']);									
		return $glt;
	}
}
?>