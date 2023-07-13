<?php
class G_Employee_Manager {
    /*
    * @param array $es Array instance of G_Employee
    */
    public static function saveMultiple($es) {

        $has_record = false;
        foreach ($es as $e) {
        
        
            $insert_sql_values[] = "
            (" . Model::safeSql($e->getId()) .",
            '" . $e->getEmployeeCode() ."',
			" . Model::safeSql($e->getHash()) .",
			'" . $e->getEmployeeDeviceId() . "',
			" . Model::safeSql($e->getPhoto()) .",
			" . Model::safeSql($e->getSalutation()) .",
			" . Model::safeSql($e->getLastname()) .",
			" . Model::safeSql($e->getFirstname()) .",
			" . Model::safeSql($e->getMiddlename()) .",
			" . Model::safeSql($e->getExtensionName()) .",
			" . Model::safeSql($e->getNickname()) .",
			" . Model::safeSql($e->getBirthdate()) .",
			" . Model::safeSql($e->getGender()) .",
			" . Model::safeSql($e->getMaritalStatus()) .",
			" . Model::safeSql($e->getNationality()) .",
			" . Model::safeSql($e->getNumberDependent()) .",
			'" . $e->getSssNumber() ."',
			'" . str_replace("'","",$e->getPagibigNumber())."',
			'" . $e->getTinNumber() ."',
			'" . $e->getPhilhealthNumber() ."',
			" . Model::safeSql($e->getHiredDate()) .",
			" . Model::safeSql($e->getEeoJobCategoryId()) .",
			" . Model::safeSql($e->getEmployeeStatusId()) .",
			" . Model::safeSql($e->getIsArchive()) .",
			" . Model::safeSql($e->getResignationDate()) . ",
			" . Model::safeSql($e->getEndoDate()) . ",
			" . Model::safeSql($e->getLeaveDate()) . ",
			" . Model::safeSql($e->getTerminatedDate()) . ",
			" . Model::safeSql($e->getInactiveDate()) . ",
			" . Model::safeSql($e->getIsConfidential()) . ",
			" . Model::safeSql($e->getYearWorkingDays()) . ",
			" . Model::safeSql($e->getWeekWorkingDays()) . ",
			" . Model::safeSql($e->getEmploymentStatusId()) .",
			" . Model::safeSql($e->getFrequencyId()) . ",
			" . Model::safeSql($e->getProjectSiteId()) . ",
			'" . $e->getCostCenter() . "')";


            $has_record = true;
		}
        

        if ($has_record) {

			$insert_sql_value = implode(',', $insert_sql_values);
            $sql_insert = "
                INSERT INTO ". EMPLOYEE ." (id, employee_code, hash, employee_device_id, photo, salutation, lastname, firstname,
                                            middlename, extension_name, nickname, birthdate, gender, marital_status, nationality,
                                            number_dependent, sss_number, pagibig_number, tin_number, philhealth_number, hired_date,
                                            eeo_job_category_id, employee_status_id, e_is_archive, resignation_date, endo_date, leave_date,
                                            terminated_date,inactive_date,is_confidential,year_working_days,week_working_days,employment_status_id,frequency_id,project_site_id,cost_center)
                VALUES ". $insert_sql_value ."
                ON DUPLICATE KEY UPDATE
                    employee_code = VALUES(employee_code),
                    hash = VALUES(hash),
                    employee_device_id = VALUES(employee_device_id),
                    photo = VALUES(photo),
                    salutation = VALUES(salutation),
                    lastname = VALUES(lastname),
                    firstname = VALUES(firstname),
                    middlename = VALUES(middlename),
                    extension_name = VALUES(extension_name),
                    nickname = VALUES(nickname),
                    birthdate = VALUES(birthdate),
                    gender = VALUES(gender),
                    marital_status = VALUES(marital_status),
                    nationality = VALUES(nationality),
                    number_dependent = VALUES(number_dependent),
                    sss_number = VALUES(sss_number),
                    pagibig_number = VALUES(pagibig_number),
                    tin_number = VALUES(tin_number),
                    philhealth_number = VALUES(philhealth_number),
                    hired_date = VALUES(hired_date),
                    eeo_job_category_id = VALUES(eeo_job_category_id),
                    employee_status_id = VALUES(employee_status_id),
                    e_is_archive = VALUES(e_is_archive),
                    resignation_date = VALUES(resignation_date),
                    endo_date = VALUES(endo_date),
                    leave_date = VALUES(leave_date),
                    terminated_date = VALUES(terminated_date),
                    inactive_date = VALUES(inactive_date),
                    is_confidential = VALUES(is_confidential),                   
                    year_working_days = VALUES(year_working_days),
                    week_working_days = VALUES(week_working_days),
                    employment_status_id = VALUES(employment_status_id),
                    frequency_id = VALUES(frequency_id),
                    project_site_id = VALUES(project_site_id),
                    cost_center = VALUES(cost_center)
            ";        
			 // echo $sql_insert ."============";
            Model::runSql($sql_insert);   
            //not sure about this 
           // exit();      
		}

        if (mysql_errno() > 0) {
            //echo mysql_error();
            return false;
        } else {
            // TODO Use wrapper
            return mysql_insert_id();
        }
    }

    public static function save(G_Employee $e) {
		$es[] = $e;
        return self::saveMultiple($es);
    }

    /*
     * DEPRECATED
     */
	public static function saveOLD(G_Employee $e) {
		if (G_Employee_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". EMPLOYEE . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$sql_start = "INSERT INTO ". EMPLOYEE . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_code       	= '" . $e->getEmployeeCode() ."',
			hash 					= " . Model::safeSql($e->getHash()) .",
			employee_device_id   	= '" . $e->getEmployeeDeviceId() . "',
			photo 					= " . Model::safeSql($e->getPhoto()) .",
			salutation 				= " . Model::safeSql($e->getSalutation()) .",
			lastname 				= " . Model::safeSql($e->getLastname()) .",
			firstname 				= " . Model::safeSql($e->getFirstname()) .",
			middlename 				= " . Model::safeSql($e->getMiddlename()) .",
			extension_name			= " . Model::safeSql($e->getExtensionName()) .",
			nickname 				= " . Model::safeSql($e->getNickname()) .",
			birthdate 				= " . Model::safeSql($e->getBirthdate()) .",
			gender 					= " . Model::safeSql($e->getGender()) .",
			marital_status 			= " . Model::safeSql($e->getMaritalStatus()) .",
			nationality 			= " . Model::safeSql($e->getNationality()) .",
			number_dependent		= " . Model::safeSql($e->getNumberDependent()) .",
			sss_number 				= " . Model::safeSql($e->getSssNumber()) .",
			pagibig_number			= " . Model::safeSql($e->getPagibigNumber()) .",
			tin_number 				= " . Model::safeSql($e->getTinNumber()) .",
			philhealth_number 		= " . Model::safeSql($e->getPhilhealthNumber()) .",
			is_tax_exempted 		= " . Model::safeSql($e->getIsTaxExempted()) .",
			hired_date 				= " . Model::safeSql($e->getHiredDate()) .",
			eeo_job_category_id		= " . Model::safeSql($e->getEeoJobCategoryId()) .",	
			employee_status_id		= " . Model::safeSql($e->getEmployeeStatusId()) .",	
			e_is_archive			= " . Model::safeSql($e->getIsArchive()) .",
			resignation_date		= " . Model::safeSql($e->getResignationDate()) . ", 
			endo_date				= " . Model::safeSql($e->getEndoDate()) . ", 	
			terminated_date			= " . Model::safeSql($e->getTerminatedDate()) .",
			inactive_date			= " . Model::safeSql($e->getInactiveDate()) ."
			 "
			. $sql_end ."	
		
		";	

		Model::runSql($sql);
		return mysql_insert_id();		
	}
	
	public static function updateEmployeeStatus(G_Employee $e){
		if(G_Employee_Helper::isIdExist($e) > 0){
			$sql = "
				UPDATE ". EMPLOYEE ."
				SET employee_status_id =" . Model::safeSql($e->getEmployeeStatusId()) . " 
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}

	public static function updateEmployeeSection(G_Employee $e){
		$total_updated_records = 0;
		if(G_Employee_Helper::isIdExist($e) > 0){
			$sql = "
				UPDATE ". EMPLOYEE ."
				SET section_id =" . Model::safeSql($e->getSectionId()) . " 
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
			$total_updated_records = mysql_affected_rows();
		}
		
		return $total_updated_records;
	
	}

	public static function updateEmployeeTotalDependentsByPkId($pkid = 0, $total_dependents = 0){
		if( $pkid > 0 ){
			$sql = "
				UPDATE ". EMPLOYEE ."
				SET number_dependent =" . Model::safeSql($total_dependents) . " 
				WHERE id =" . Model::safeSql($pkid);
			Model::runSql($sql);
			return true;			
		}else{
			return false;
		}
	}
		
	public static function delete(G_Employee $e){
		if(G_Employee_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". EMPLOYEE ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
	
	public static function archive(G_Employee $e){
		if(G_Employee_Helper::isIdExist($e) > 0){
			$sql = "
				UPDATE ". EMPLOYEE ."
				SET e_is_archive =" . Model::safeSql(G_Employee::YES) . " 
				WHERE id =" . Model::safeSql($e->getId());			
			Model::runSql($sql);
		}
	}

    public static function updateEmployeeDepartmentId(G_Employee $e, $department_company_structure_id) {
        $sql = "
            UPDATE ". EMPLOYEE ."
            SET department_company_structure_id =" . Model::safeSql($department_company_structure_id) . "
            WHERE id =" . Model::safeSql($e->getId());

        Model::runSql($sql);
    }
	
	public static function restore(G_Employee $e){
		if(G_Employee_Helper::isIdExist($e) > 0){
			$sql = "
				UPDATE ". EMPLOYEE ."
				SET e_is_archive =" . Model::safeSql(G_Employee::NO) . " 
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
	
	public static function addHash(G_Employee $e,$hash) {
		if (G_Employee_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". EMPLOYEE . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}
		
		$sql = $sql_start ."
			SET
			hash		        	= " . Model::safeSql($hash) .""
			. $sql_end ."
		";	

		Model::runSql($sql);
	}

	public static function updateIsTaxExempted(G_Employee $e){
		if(G_Employee_Helper::isIdExist($e) > 0){
			$sql = "
				UPDATE ". EMPLOYEE ."
				SET is_tax_exempted =" . Model::safeSql($e->getIsTaxExempted()) . " 
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}

	public static function updateSectionId($id, $section_id) {
		$sql = "
				UPDATE ". EMPLOYEE ."
				SET section_id =" . Model::safeSql($section_id) . " 
				WHERE id =" . Model::safeSql($id);
			Model::runSql($sql);
	}

}
?>