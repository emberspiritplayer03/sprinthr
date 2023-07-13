<?php
class G_Settings_Pay_Period_Helper {
	public static function isIdExist(G_Settings_Pay_Period $gspp) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_SETTINGS_PAY_PERIOD ."
			WHERE id = ". Model::safeSql($gspp->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlDefaultPayPeriod($fields = array()) {
		if(!empty($fields)){
			$sql_fields = implode(",",$fields);
		}else{
			$sql_fields = " * ";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . G_SETTINGS_PAY_PERIOD ."
			WHERE is_default =" . Model::safeSql(G_Settings_Pay_Period::IS_DEFAULT) . "
		";
		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}


	//monthly pay period
	public static function sqlMonthlyPayPeriod($fields = array()) {
		if(!empty($fields)){
			$sql_fields = implode(",",$fields);
		}else{
			$sql_fields = " * ";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . G_SETTINGS_PAY_PERIOD ."
			WHERE pay_period_code =" . Model::safeSql(G_Settings_Pay_Period::TYPE_MONTHLY2) . "
		";
		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}

	public static function findByCode($fields = array()) {
		if(!empty($fields)){
			$sql_fields = implode(",",$fields);
		}else{
			$sql_fields = " * ";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . G_SETTINGS_PAY_PERIOD ."
			WHERE  pay_period_code =" . Model::safeSql(G_Settings_Pay_Period::TYPE_MONTHLY2) . "
		";
		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}




		public static function getPayPeriodNameById($id){
			$sql = "
				SELECT pay_period_name 
				FROM ".G_SETTINGS_PAY_PERIOD."
				WHERE id = ".$id."
			";

			$result = Model::runSql($sql);
			$row    = Model::fetchAssoc($result);
			return $row;

	}
	
	public static function getCurrentCutOffPeriod(G_Settings_Pay_Period $gspp,$end_date) {
		$cutoff_end_date = self::getCutOffPeriodEndDate($gspp);
		$day 	 	     = date("j",strtotime($end_date));			
		if($day <= $cutoff_end_date[0]){
			$current_cutoff_index = 0;
		}else{
			$current_cutoff_index = 1;
		}
		return $current_cutoff_index;
	}
	
	public static function getCutOffPeriodEndDate(G_Settings_Pay_Period $gspp) {
		$cutoff = $gspp->getCutOff();
		$cutoff = explode(",",$cutoff);			
		foreach($cutoff as $key => $value){			
			$cvalue = explode("-",$value);
			$c_end_date[] = $cvalue[1];
		}
		
		return $c_end_date;
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_SETTINGS_PAY_PERIOD ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByCompanyStructureId(G_Company_Structure $gcs) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_SETTINGS_PAY_PERIOD ."
			WHERE company_structure_id = ". Model::safeSql($gcs->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlWeeklyPayPeriod($fields = array()) {
		if(!empty($fields)){
			$sql_fields = implode(",",$fields);
		}else{
			$sql_fields = " * ";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . G_SETTINGS_PAY_PERIOD ."
			WHERE pay_period_code =" . Model::safeSql(G_Settings_Pay_Period::TYPE_WEEKLY) . "
		";
		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}
	
	public static function objectToArray($data)
	{
	  if(is_array($data) || is_object($data))
	  {
			$result = array(); 
			foreach($data as $key => $value)
			{ 
			  $result[$key] = $value;
			}
			return $result;
	  }
	  return $data;
	}
}
?>