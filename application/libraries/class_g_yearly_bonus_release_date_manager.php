<?php
class G_Yearly_Bonus_Release_Date_Manager {
	public static function save(G_Yearly_Bonus $gybrd) {		
		if (G_Yearly_Bonus_Release_Date_Helper::isIdExist($gybrd) > 0 ) {
			$sql_start = "UPDATE ". YEARLY_BONUS_RELEASE_DATES . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gybrd->getId());	
			$action = 'update';	
		}else{
			$sql_start = "INSERT INTO ". YEARLY_BONUS_RELEASE_DATES . " ";
			$sql_end  = "";		
			$action = 'insert';
		}
		
		$sql = $sql_start ."
			SET
			employee_id        =" . Model::safeSql($gybrd->getEmployeeId()) . ",
			amount		       =" . Model::safeSql($gybrd->getAmount()) . ",
			taxable_amount 	   =" . Model::safeSql($gybrd->getTaxableAmount()) . ",
			tax 			   =" . Model::safeSql($gybrd->getTax()) . ",
			total_bonus_amount =" . Model::safeSql($gybrd->getTotalBonusAmount()) . ",
			year_released      =" . Model::safeSql($gybrd->getYearReleased()) . ",
			month_start        =" . Model::safeSql($gybrd->getMonthStart()) . ",
			month_end          =" . Model::safeSql($gybrd->getMonthEnd()) . ",
			cutoff_start_date  =" . Model::safeSql($gybrd->getCutoffStartDate()) . ",
			cutoff_end_date    =" . Model::safeSql($gybrd->getCutoffEndDate()) . ",
			percentage 	 	   =" . Model::safeSql($gybrd->getPercentage()) . ",						
			deducted_amount    =" . Model::safeSql($gybrd->getDeductedAmount()) . ",						
			created 	 	   =" . Model::safeSql($gybrd->getCreated()) . ",						
			modified	  	   =" . Model::safeSql($gybrd->getModified()) . " "				
			. $sql_end ."	
		
		";			
				
		Model::runSql($sql);

		if( $action == 'update' ){
			$id = $gybrd->getId();
		}else{
			$id = mysql_insert_id();
		}

		return $id;	
	}
		
	public static function delete(G_Yearly_Bonus $gybrd){
		if(G_Yearly_Bonus_Release_Date_Helper::isIdExist($gybrd) > 0){
			$sql = "
				DELETE FROM ". YEARLY_BONUS_RELEASE_DATES ."
				WHERE id =" . Model::safeSql($gybrd->getId());
			Model::runSql($sql);
		}	
	}

	/**
	*Delete existing data by employeeids and cutoff start/end date
	*
	*@param array employee_ids
	*@param string cutoff_start
	*@param string cutoff_end
	*
	*@return void
	*/

	public static function deleteExistingDataByEmployeeIdsAndCutoffStartAndEndDate($employee_ids = array(), $cutoff_start = '', $cutoff_end = ''){
		$sql_employee_ids = implode(",", $employee_ids);
		$sql = "
			DELETE FROM ". YEARLY_BONUS_RELEASE_DATES ."
			WHERE employee_id IN({$sql_employee_ids})
				AND cutoff_start_date =" . Model::safeSql($cutoff_start) . "
				AND cutoff_end_date =" . Model::safeSql($cutoff_end) . "
		";		
		Model::runSql($sql);
	}

	/**
	*Delete existing data by employeeids and cutoff start/end date
	*
	*@param array employee_ids
	*@param int start_month - month number
	*@param int end_month - month number
	*@param year int
	*
	*@return void
	*/

	public static function deleteExistingDataByEmployeeIdsAndByStartAndEndMonthAndYear($employee_ids = array(), $start_month = 0, $end_month = 0, $year = 0){
		$sql_employee_ids = implode(",", $employee_ids);
		$sql = "
			DELETE FROM ". YEARLY_BONUS_RELEASE_DATES ."
			WHERE employee_id IN({$sql_employee_ids})
				AND cutoff_start_date =" . Model::safeSql($start_month) . "
				AND cutoff_end_date =" . Model::safeSql($end_month) . "
				AND year_released =" . Model::safeSql($year) . "
		";		
		Model::runSql($sql);
	}

	//new
	public static function deleteExistingDataByEmployeeIdsAndByStartAndEndMonthAndYearRev($employee_ids = array(), $start_month = 0, $end_month = 0, $year = 0){
		$sql_employee_ids = implode(",", $employee_ids);
		$sql = "
			DELETE FROM ". YEARLY_BONUS_RELEASE_DATES ."
			WHERE cutoff_start_date =" . Model::safeSql($start_month) . "
				AND cutoff_end_date =" . Model::safeSql($end_month) . "
				AND year_released =" . Model::safeSql($year) . "
		";		
		Model::runSql($sql);
	}
	//new

	/**
	* Bulk insert 
	*
	*@param array a_bulk_insert
	*@param array fields
	*@return int
	*/
	public static function bulkInsertData( $a_bulk_insert = array(), $fields = array() ) {
        $total_records_inserted = 0;
        if( !empty($a_bulk_insert) && !empty($fields) ){
            $sql_values = implode(",", $a_bulk_insert);
            $sql_fields = implode(",", $fields);
            $sql        = "
                INSERT INTO " . YEARLY_BONUS_RELEASE_DATES . "({$sql_fields})
                VALUES{$sql_values}
            ";                                              
            Model::runSql($sql);
            $total_records_inserted = mysql_affected_rows();
           
        }
        return $total_records_inserted;
    }
}
?>