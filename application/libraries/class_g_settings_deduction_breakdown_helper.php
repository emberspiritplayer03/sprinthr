<?php
class G_Settings_Deduction_Breakdown_Helper {

    /*
     * @param $deduction_type G_Settings_Deduction_Breakdown::SSS, G_Settings_Deduction_Breakdown::HDMF, G_Settings_Deduction_Breakdown::PHIL_HEALTH
     */
    public static function getDeductionPercentage($cutoff_number, $deduction_type) {
        $sdb = G_Settings_Deduction_Breakdown_Finder::findByIdAndIsActive($deduction_type);
        if($sdb){
            $d_percentage = $sdb->getBreakdown();
            $d_percentage = explode(":", $d_percentage);
            $d_percentage = $d_percentage[$cutoff_number - 1];
        } else {
            $d_percentage = 100;
        }
        return $d_percentage;
    }
		
	public static function isIdExist(G_Settings_Deduction_Breakdown $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_SETTINGS_DEDUCTION_BREAKDOWN ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlGetAllActiveDeductionBreakDown() {
		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_DEDUCTION_BREAKDOWN ."		
			WHERE is_active =". Model::safeSql(G_Settings_Deduction_Breakdown::YES) . "	
		";
		$rows = Model::runSql($sql,true);
		return $rows;
	}

	public static function sqlDeductionDataByIds( $ids = array(), $fields = array() ) {		
		if( !empty($fields) ){
			$sql_fields = implode(",", $fields);
		}else{
			$sql_fields = " * ";
		}

		if( !empty( $ids ) ){
			$sql_values = implode(",", $ids);
		}else{
			$sql_values = "";
		}

		$sql = "
			SELECT {$sql_fields} 
			FROM " . G_SETTINGS_DEDUCTION_BREAKDOWN . "
			WHERE id IN({$sql_values})
			ORDER BY id ASC
		";
		
		$rows = Model::runSql($sql,true);
		return $rows;
	} 
	
	public static function getCurrentPayPeriodPercentageDeductedByEmployeedAndDeductible(G_Employee $e,$deductible,$end_date){
		$salary = G_Employee_Basic_Salary_History_Finder::findCurrentSalary($e);
		if($salary){
			$pperiod 		 = G_Settings_Pay_Period_Finder::findById($salary->getPayPeriodId());						
		}else{
			$pperiod 		 = G_Settings_Pay_Period_Finder::findDefault(1);
		}	
		$current_cutoff  = G_Settings_Pay_Period_Helper::getCurrentCutOffPeriod($pperiod,$end_date);
		$sdb			 = G_Settings_Deduction_Breakdown_Finder::findByIdAndIsActive($deductible);
		if($sdb){
			$d_percentage = $sdb->getBreakdown();
			$d_percentage = explode(":",$d_percentage);
			$d_percentage = $d_percentage[$current_cutoff];
		}else{
			$d_percentage = 100;
		}			
		
		return $d_percentage;
	}

}
?>