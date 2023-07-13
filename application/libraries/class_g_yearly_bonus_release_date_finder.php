<?php
class G_Yearly_Bonus_Release_Date_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . YEARLY_BONUS_RELEASE_DATES ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";		
		return self::getRecord($sql);
	}	
	
	public static function findAll($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . YEARLY_BONUS_RELEASE_DATES ." 			
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}


	public static function FindByEmployeeIdAndYear($eid, $year) {
		$sql = "
			SELECT * 
			FROM " . YEARLY_BONUS_RELEASE_DATES ." 
			WHERE employee_id =". Model::safeSql($eid) ."
			AND year_released =". Model::safeSql($year)."
			LIMIT 1
		";		
		return self::getRecord($sql);
	}

	public static function FindByEmployeeIdAndYearAndStartandEndDate($eid, $year,$start,$end) {
		$sql = "
			SELECT * 
			FROM " . YEARLY_BONUS_RELEASE_DATES ." 
			WHERE employee_id =". Model::safeSql($eid) ."
			AND year_released =". Model::safeSql($year)."
			AND cutoff_start_date =". Model::safeSql($start)."
			AND cutoff_end_date =". Model::safeSql($end)."
			LIMIT 1
		";		
		return self::getRecord($sql);
	}

	
	public static function checkBonusGeneration($year){
		$sql = "
			SELECT * 
			FROM " . YEARLY_BONUS_RELEASE_DATES ." 
			WHERE year_released =". Model::safeSql($year)."
			ORDER BY cutoff_end_date DESC 
			LIMIT 1
		";		
		return self::getRecord($sql);
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
		$gybrd = new G_Yearly_Bonus();
		$gybrd->setId($row['id']);
		$gybrd->setEmployeeId($row['employee_id']);
		$gybrd->setAmount($row['amount']);
		$gybrd->setTaxableAmount($row['taxable_amount']);
		$gybrd->setTax($row['tax']);
		$gybrd->setTotalBonusAmount($row['total_bonus_amount']);
		$gybrd->setYearReleased($row['year_released']);
		$gybrd->setMonthStart($row['month_start']);
		$gybrd->setMonthEnd($row['month_end']);
		$gybrd->setCutoffStartDate($row['cutoff_start_date']);
		$gybrd->setCutoffEndDate($row['cutoff_end_date']);
		$gybrd->setPercentage($row['percentage']);
		$gybrd->setDeductedAmount($row['deducted_amount']);
		$gybrd->setCreated($row['created']);	
		$gybrd->setModified($row['modified']);	
		$gybrd->setPayrollStartDate($row['payroll_start_date']);	
							
		return $gybrd;
	}
}
?>