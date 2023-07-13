<?php
class G_Employee_Earnings_Manager {
	public static function save(G_Employee_Earnings $gee) {
		if (G_Employee_Earnings_Helper::isIdExist($gee) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_EARNINGS . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gee->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_EARNINGS . " ";
			$sql_end  = " ";		
		}
		
		
		$sql = $sql_start ."
			SET
			company_structure_id =" . Model::safeSql($gee->getCompanyStructureId()) . ",
			object_id  	      	 =" . Model::safeSql($gee->getObjectId()) . ",
			object_description   =" . Model::safeSql($gee->getObjectDescription()) . ",
			frequency_id   		 =" . Model::safeSql($gee->getFrequencyId()) . ",
			applied_to 			 =" . Model::safeSql($gee->getAppliedTo()) . ",
			title  				 =" . Model::safeSql($gee->getTitle()) . ",
			remarks   		     =" . Model::safeSql($gee->getRemarks()) . ",					
			earning_type   		 =" . Model::safeSql($gee->getEarningType()) . ",					
			percentage   		 =" . Model::safeSql($gee->getPercentage()) . ",					
			percentage_multiplier =" . Model::safeSql($gee->getPercentageMultiplier()) . ",					
			amount 				  =" . Model::safeSql($gee->getAmount()) . ",					
			payroll_period_id     =" . Model::safeSql($gee->getPayrollPeriodId()) . ",	
			description 		  =" . Model::safeSql($gee->getDescription()) . ",					
			status   		  	  =" . Model::safeSql($gee->getStatus()) . ",											
			is_taxable   		  =" . Model::safeSql($gee->getIsTaxable()) . ",																						
			is_archive   		  =" . Model::safeSql($gee->getIsArchive()) . ",											
			date_created 		  =" . Model::safeSql($gee->getDateCreated()) . " "				
			. $sql_end ."	
		";						
		Model::runSql($sql);
		return mysql_insert_id();		
	}

	public static function bulkInsertData( $a_bulk_insert = array(), $fields = array() ) {
        $return = false;
        if( !empty( $a_bulk_insert ) ){
        	$sql_fields = "company_structure_id,object_id,object_description,frequency_id,applied_to,title,remarks,earning_type,percentage,percentage_multiplier,amount,payroll_period_id,description,status,is_taxable,is_archive,date_created";
        	
        	if( !empty($fields) ){
        		$sql_fields = implode(",", $fields);
        	}

            $sql_values = implode(",", $a_bulk_insert);
            $sql        = "
                INSERT INTO " . G_EMPLOYEE_EARNINGS . "({$sql_fields})
                VALUES{$sql_values}
            ";     
         		
            Model::runSql($sql);
            $return = true;
        }
        return $return;
    }
	
	public static function approve(G_Employee_Earnings $gee){
		if(G_Employee_Earnings_Helper::isIdExist($gee) > 0){
			$sql = "
				UPDATE ". G_EMPLOYEE_EARNINGS ."
				SET 
				status =" . Model::safeSql(G_Employee_Earnings::APPROVED) . " 
				WHERE id =" . Model::safeSql($gee->getId());
			Model::runSql($sql);
		}	
	}
	
	public static function disapprove(G_Employee_Earnings $gee){
		if(G_Employee_Earnings_Helper::isIdExist($gee) > 0){
			$sql = "
				UPDATE ". G_EMPLOYEE_EARNINGS ."
				SET 
				status =" . Model::safeSql(G_Employee_Earnings::PENDING) . " 
				WHERE id =" . Model::safeSql($gee->getId());
			Model::runSql($sql);
		}	
	}
	
	public static function archive(G_Employee_Earnings $gee){
		if(G_Employee_Earnings_Helper::isIdExist($gee) > 0){
			$sql = "
				UPDATE ". G_EMPLOYEE_EARNINGS ."
				SET 
				is_archive =" . Model::safeSql(G_Employee_Earnings::YES) . " 
				WHERE id =" . Model::safeSql($gee->getId());
			Model::runSql($sql);
		}	
	}
	
	public static function restore_archived(G_Employee_Earnings $gee){
		if(G_Employee_Earnings_Helper::isIdExist($gee) > 0){
			$sql = "
				UPDATE ". G_EMPLOYEE_EARNINGS ."
				SET 
				is_archive =" . Model::safeSql(G_Employee_Earnings::NO) . " 
				WHERE id =" . Model::safeSql($gee->getId());
			Model::runSql($sql);
		}	
	}
		
	public static function delete(G_Employee_Earnings $gee){
		if(G_Employee_Earnings_Helper::isIdExist($gee) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_EARNINGS ."
				WHERE id =" . Model::safeSql($gee->getId());
			Model::runSql($sql);
		}	
	}

	/**
	 * Delete all yearly bonus by cutoff id
	 *
	 * @param int cutoff_id
	 * @param string yearly_bonus_description	 
	*/
	public static function deleteAllYearlyBonusByCutoffId($cutoff_id = 0, $yearly_bonus_string = ''){
		$sql = "
			DELETE FROM ". G_EMPLOYEE_EARNINGS ."
			WHERE payroll_period_id =" . Model::safeSql($cutoff_id) . "
				AND title =" . Model::safeSql($yearly_bonus_string) . "
		";
		Model::runSql($sql);
	}

	/**
	 * Delete all by cutoff id and remarks string match
	 *
	 * @param int cutoff_id
	 * @param string string_match	 
	*/
	public static function deleteAllByCutoffIdAndRemarks($cutoff_id = 0, $string_match = ''){
		$sql = "
			DELETE FROM ". G_EMPLOYEE_EARNINGS ."
			WHERE payroll_period_id =" . Model::safeSql($cutoff_id) . "
				AND remarks LIKE '%{$string_match}%'
		";
		Model::runSql($sql);
	}
}
?>