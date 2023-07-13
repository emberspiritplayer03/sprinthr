<?php
class G_Employee_Deductions_Manager {
	public static function save(G_Employee_Deductions $gee) {
		if (G_Employee_Deductions_Helper::isIdExist($gee) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_DEDUCTIONS . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gee->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_DEDUCTIONS . " ";
			$sql_end  = " ";		
		}
		
	
		
		$sql = $sql_start ."
			SET
			company_structure_id  =" . Model::safeSql($gee->getCompanyStructureId()) . ",
			employee_id  	      =" . Model::safeSql($gee->getEmployeeId()) . ",
			department_section_id =" . Model::safeSql($gee->getDepartmentSectionId()) . ",
			employment_status_id  =" . Model::safeSql($gee->getEmploymentStatusId()) . ",
			title   		      =" . Model::safeSql($gee->getTitle()) . ",					
			remarks   		      =" . Model::safeSql($gee->getRemarks()) . ",					
			amount   			  =" . Model::safeSql($gee->getAmount()) . ",					
			payroll_period_id     =" . Model::safeSql($gee->getPayrollPeriodId()) . ",					
			apply_to_all_employee =" . Model::safeSql($gee->getApplyToAllEmployee()) . ",					
			status   			  =" . Model::safeSql($gee->getStatus()) . ",	
			is_taxable 			  =" . Model::safeSql($gee->getTaxable()) . ",
			frequency_id 			  =" . Model::safeSql($gee->getFrequencyId()) . ",					
			is_archive   		  =" . Model::safeSql($gee->getIsArchive()) . ",											
			date_created 		  =" . Model::safeSql($gee->getDateCreated()) . ",
			is_moved_deduction	  =" . Model::safeSql($gee->getIsMovedDeduction()) . " "				
			. $sql_end ."	
		
		";
		
		// 	frequency_id   		  =" . Model::safeSql($gee->getFrequencyId()) . ",			
		Model::runSql($sql);
		return mysql_insert_id();		
	}

	public static function saveBulk($values) {
        $sql = "INSERT INTO " . G_EMPLOYEE_DEDUCTIONS . " (company_structure_id,employee_id,title,remarks,amount,payroll_period_id,apply_to_all_employee,status,is_taxable,is_archive,date_created,is_moved_deduction) 
                    VALUES ".$values." ";
        Model::runSql($sql);
    }

    public static function bulkInsertData( $a_bulk_insert = array() ) {
        $return = false;
        if( !empty( $a_bulk_insert ) ){
            $sql_values = implode(",", $a_bulk_insert);
            $sql        = "
                INSERT INTO " . G_EMPLOYEE_DEDUCTIONS . "(company_structure_id,employee_id,department_section_id,employment_status_id,title,remarks,amount,payroll_period_id,apply_to_all_employee,status,is_taxable,is_archive,date_created,is_moved_deduction) 
                VALUES{$sql_values}
            ";      
                  
            Model::runSql($sql);
            $return = true;
        }
        return $return;
    }

    public static function deleteMovedEmployeeDeductionByPayrollPeriodId($payroll_period_id) {
        $sql = "
            DELETE FROM " . G_EMPLOYEE_DEDUCTIONS . "
            WHERE payroll_period_id =" . Model::safeSql($payroll_period_id) . "
            	AND is_moved_deduction = 1
            ";
        Model::runSql($sql);
    }
	
	public static function approve(G_Employee_Deductions $gee){
		if(G_Employee_Deductions_Helper::isIdExist($gee) > 0){
			$sql = "
				UPDATE ". G_EMPLOYEE_DEDUCTIONS ."
				SET 
				status =" . Model::safeSql(G_Employee_Deductions::APPROVED) . " 
				WHERE id =" . Model::safeSql($gee->getId());
			Model::runSql($sql);
		}	
	}
	
	public static function disapprove(G_Employee_Deductions $gee){
		if(G_Employee_Deductions_Helper::isIdExist($gee) > 0){
			$sql = "
				UPDATE ". G_EMPLOYEE_DEDUCTIONS ."
				SET 
				status =" . Model::safeSql(G_Employee_Deductions::PENDING) . " 
				WHERE id =" . Model::safeSql($gee->getId());
			Model::runSql($sql);
		}	
	}
	
	public static function archive(G_Employee_Deductions $gee){
		if(G_Employee_Deductions_Helper::isIdExist($gee) > 0){
			$sql = "
				UPDATE ". G_EMPLOYEE_DEDUCTIONS ."
				SET 
				is_archive =" . Model::safeSql(G_Employee_Deductions::YES) . " 
				WHERE id =" . Model::safeSql($gee->getId());
			Model::runSql($sql);
		}	
	}
	
	public static function restore_archived(G_Employee_Deductions $gee){
		if(G_Employee_Deductions_Helper::isIdExist($gee) > 0){
			$sql = "
				UPDATE ". G_EMPLOYEE_DEDUCTIONS ."
				SET 
				is_archive =" . Model::safeSql(G_Employee_Deductions::NO) . " 
				WHERE id =" . Model::safeSql($gee->getId());
			Model::runSql($sql);
		}	
	}
		
	public static function delete(G_Employee_Deductions $gee){
		if(G_Employee_Deductions_Helper::isIdExist($gee) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_DEDUCTIONS ."
				WHERE id =" . Model::safeSql($gee->getId());
			Model::runSql($sql);
		}	
	}


}
?>