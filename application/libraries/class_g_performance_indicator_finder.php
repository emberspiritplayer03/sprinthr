<?php
class G_Performance_Indicator_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_PERFORMANCE_INDICATOR ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}
	
	public static function findByPerformanceId($performance_id)
	{
		$sql = "
			SELECT * 
			FROM " . G_PERFORMANCE_INDICATOR ."
			WHERE performance_id=".Model::safeSql($performance_id)."
			ORDER BY order_by 

		";
		return self::getRecords($sql);
	}
	
	public static function findAll($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_PERFORMANCE_INDICATOR ."
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
		$gsl = new G_Performance_Indicator($row['id']);
		$gsl->setPerformanceId($row['performance_id']);
		$gsl->setTitle($row['title']);
		$gsl->setDescription($row['description']);
		$gsl->setRateMin($row['rate_min']);
		$gsl->setRateMax($row['rate_min']);	
		$gsl->setRateDefault($row['rate_default']);	
		$gsl->setOrderBy($row['order_by']);	
		$gsl->setIsActive($row['is_active']);	
		return $gsl;
	}
}
?>