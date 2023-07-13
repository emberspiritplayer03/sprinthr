<?php
class G_Settings_Pay_Period_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_PAY_PERIOD ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}

	public static function findByName($name) {
		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_PAY_PERIOD ." 
			WHERE pay_period_name=". Model::safeSql($name) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}

	
	
	public static function findByCompanyStructureId($csid,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM ". G_SETTINGS_PAY_PERIOD ." 
			WHERE company_structure_id=".Model::safeSql($csid)."
			".$order_by."
			".$limit."		
		";
		return self::getRecords($sql);
	}
	
	public static function findByPayPeriodCode($csid, $pay_period_code) {
		
		$sql = "
			SELECT * 
			FROM ". G_SETTINGS_PAY_PERIOD ." 
			WHERE company_structure_id=".Model::safeSql($csid)."
			AND pay_period_code=".Model::safeSql($pay_period_code)."
			".$order_by."
			".$limit."		
		";

		return self::getRecord($sql);
	}
	
	public static function findDefault() {
		$sql = "
			SELECT * 
			FROM ". G_SETTINGS_PAY_PERIOD ." 
			WHERE is_default = " . Model::safeSql(G_Settings_Pay_Period::IS_DEFAULT) . "
			ORDER BY id DESC
			LIMIT 1			
		";
		return self::getRecord($sql);
	}
	
	public static function findAll($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_PAY_PERIOD ." 
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
		$gspp = new G_Settings_Pay_Period($row['id']);
		$gspp->setCompanyStructureId($row['company_structure_id']);
		$gspp->setPayPeriodCode($row['pay_period_code']);
		$gspp->setPayPeriodName($row['pay_period_name']);
		$gspp->setCutOff($row['cut_off']);
		$gspp->setPayOutDay($row['payout_day']);
		$gspp->setIsDefault($row['is_default']);
		return $gspp;
	}
	
}
?>