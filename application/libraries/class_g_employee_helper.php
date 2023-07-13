<?php
class G_Employee_Helper {
    public static function hireEmployee($employee_code, $firstname, $lastname, $middlename, $birthdate, $gender, $marital_status, $number_of_dependent,
                                  $hired_date, $department_name, $position, $employment_status, $salary, $salary_type,$frequency_id,
                                  $sss_number = '', $tin_number = '', $pagibig_number = '', $philhealth_number = '',
                                  $extension_name = '', $nickname = '', $section = '', $is_confidential = 0, $week_working_days = '', $year_working_days = 0, $nationality = '', $employee_status = 1, $cost_center = '', $project_site='') {


        $e = G_Employee_Finder::findByEmployeeCode($employee_code);
        if ($e) {
            return false;
        }
       
        $e = G_Employee_Helper::generate($employee_code, $firstname, $lastname, $middlename, $birthdate, $gender, $marital_status, $number_of_dependent, $hired_date,
                        $sss_number, $tin_number, $pagibig_number, $philhealth_number, $extension_name, $nickname, $is_confidential, $week_working_days, $year_working_days, $nationality, $employee_status,$frequency_id,$cost_center,$project_site);
        // echo "<pre>";
        // var_dump($e);
        // echo "</pre>";

        $employee_id = $e->save();        
        if (!$employee_id) {        	
            return false;
        }

        $e = G_Employee_Finder::findById($employee_id);

        // ADD HASH
        $hash = Utilities::createHash($e->getId());                       
        $e->addHash($hash);        
        $e->setHash($hash);
        $e->setFrequencyId($frequency_id);
        $e->setCostCenter($cost_center);

        // ADD MAIN COMPANY STRUCTURE TO EMPLOYEE
        $cs = G_Company_Structure_Finder::findByMainParent();
        $cs->addEmployee($e);

        // ADD TO BRANCH
        $default_branch_id = 1;
        $b = G_Company_Branch_Finder::findById($default_branch_id);
        if ($b) {
            $b->addEmployee($e, $hired_date);
        }

        // ADD NEW DEPARTMENT
        $dept = G_Company_Structure_Finder::findByTitle($department_name);
        if (!$dept) {
            $dept = G_Company_Structure_Helper::generate($department_name, $b->getId(), $cs->getId());
            $department_id = $dept->save();
        } else {
            $department_id = $dept->getId();
        }

        // ADD DEPARTMENT HISTORY TO EMPLOYEE
        $dept = G_Company_Structure_Finder::findById($department_id);
        $e_subdivision = G_Employee_Subdivision_History_Finder::findCurrentSubdivision($e);
        if ($e_subdivision) {
            $e_subdivision->setCompanyStructureId($dept->getId());
            $e_subdivision->setName($dept->getTitle());
            $e_subdivision->setStartDate($hired_date);
            $e_subdivision->save();
        }
        if ($dept) {
            $dept->addEmployeeToSubdivision($e, $hired_date);
        }



        // ADD SECTION
        if (!empty($section)) {
        	$sect = G_Company_Structure_Finder::findByParentIdTitleAndType($department_id, $section, G_Company_Structure::SECTION);
	        if (!$sect) {
	            $sect = G_Company_Structure_Helper::generateByType($section, $b->getId(), $department_id, G_Company_Structure::SECTION);
	            $section_id = $sect->save();
	        } else {
	            $section_id = $sect->getId();
	        }
	        $e->updateSectionId($section_id);
        }

        // ADD JOB POSITION
        $job = G_Job_Finder::findByTitle($position);
        if (!$job) {
            $job = G_Job_Helper::generate($cs->getId(), $position);
            $job_id = $job->save();
        } else {
            $job_id = $job->getId();
        }

        // ADD EMPLOYMENT STATUS
        $es = G_Settings_Employment_Status_Finder::findByStatus($employment_status);
        if(!$es) {
            $es = G_Settings_Employment_Status_Helper::generate($cs->getId(), $employment_status);
            $employment_status_id = $es->save($cs);
        }else {
            $employment_status_id = $es->getId();
        }

        $e->setEmploymentStatusId($employment_status_id);
        $e->save();

        //ADD DEFAULT DEPENDENTS
        $dependent = new G_Employee_Dependent();
        $dependent->setEmployeeId($e->getId());
        $dependent->setRelationship('Sibling');
        $dependent->setBirthdate($hired_date);
        $dependent->defaultDependents($number_of_dependent);

        // ADD JOB POSITION AND EMPLOYMENT STATUS TO EMPLOYEE
        $jh = G_Employee_Job_History_Finder::findByEmployeeIdStartDate($e->getId(), $hired_date);
        if (!$jh) {
            $jh = G_Employee_Job_History_Helper::generate($e->getId(), $job_id, $job->getTitle(), $employment_status, $hired_date);
            $jh->save();
        }

        // ADD SALARY TO EMPLOYEE
        $e->addNewSalary($salary, $salary_type, $hired_date,$frequency_id);

        // ADD CONTRIBUTION
        $e->addContribution($salary);

        // SET TAX EXEMPTED (DEFAULT VALUE : No)
        $e->setIsTaxExempted(G_Employee::NO);
        $e->updateIsTaxExempted();

        //add project site history
        if($project_site){

        $p = new G_Employee_Project_Site_History();

        $p->setEmployeeId($e->getId());
        $p->setStartDate($hired_date);
        $p->setEndDate('');
        $p->setProjectId($project_site);
        $p->setEmployeeStatus('');
        $p->setStatusDate('');                                                                                                                                                                     
        $p->saveEmployeeProjectSite();

        }
        //end project site 

        return $e;
    }

    public static function generate($employee_code, $firstname, $lastname, $middlename, $birthdate, $gender, $marital_status,
                                    $number_of_dependent,$hired_date, $sss_number = '', $tin_number = '', $pagibig_number = '',
                                    $philhealth_number = '', $extension_name = '', $nickname = '',  $is_confidential = 0, $week_working_days = '', $year_working_days = 0, $nationality = '', $employee_status_id = 1,$frequency_id = 1, $cost_center = '', $project_site) {

        $e = new G_Employee;
        $e->setEmployeeCode($employee_code);
        $e->setFirstname($firstname);
        $e->setLastname($lastname);
        $e->setMiddlename($middlename);
        $e->setBirthdate($birthdate);
        $e->setHiredDate($hired_date);
        $e->setGender($gender);
        $e->setMaritalStatus($marital_status);
        $e->setNumberDependent($number_of_dependent);
        $e->setSssNumber($sss_number);
        $e->setTinNumber($tin_number);
        $e->setPagibigNumber($pagibig_number);
        $e->setPhilhealthNumber($philhealth_number);
        $e->setExtensionName($extension_name);
        $e->setNickname($nickname);
        $e->setIsConfidential($is_confidential);
        $e->setWeekWorkingDays($week_working_days);
		$e->setYearWorkingDays($year_working_days);
		$e->setNationality($nationality);
        //default active
        if(!$employee_status_id){
            $employee_status_id = 1;
        }
        
		$e->setEmployeeStatusId($employee_status_id);
		$e->setFrequencyId($frequency_id);
		$e->setCostCenter($cost_center);
        $e->setProjectSiteId($project_site);
        return $e;
    }

    /*
     * @param array $es Array instance of G_Employee
     */
    public static function saveMultiple($es) {
        G_Employee_Manager::saveMultiple($es);
    }

    /**
	* Check if employee code exits
	*
	* @param string 
	* @return boolean
	*/
    public static function sqlIsEmployeeCodeExists($employee_code = '') {
    	$is_exists = false;
		$sql = "
			SELECT COUNT(*) as total
			FROM " . EMPLOYEE ."
			WHERE employee_code = ". Model::safeSql($employee_code) ."
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);

		if( $row['total'] > 0 ){
			$is_exists = true;
		}

		return $is_exists;
	}	

    public static function countAllActiveByDate($date, $is_confidential_qry = "", $employee_ids_qry = "") {
        if ($date == '') {
            $date = Tools::getGmtDate('Y-m-d');
        }
        $sql = "
			SELECT COUNT(DISTINCT e.id) AS total
			FROM g_employee e, g_employee_job_history eh
			WHERE ".$is_confidential_qry.$employee_ids_qry." (e.e_is_archive =" . Model::safeSql(G_Employee::NO) . ")
			AND (
                e.employee_status_id <> " . Model::safeSql(G_Settings_Employee_Status::TERMINATED) . "
                 AND e.employee_status_id <> " . Model::safeSql(G_Settings_Employee_Status::RESIGNED) . "
				 AND e.employee_status_id <> " . Model::safeSql(G_Settings_Employee_Status::NULL) . "
                )
			AND (e.id = eh.employee_id
			OR
			(
				(
					". Model::safeSql($date) ." >= eh.start_date
					AND
					". Model::safeSql($date) ." <= eh.end_date
				)
				OR
				(
					". Model::safeSql($date) ." >= eh.start_date
					AND
					eh.end_date = ''
				)
			)) 
			
		";
        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);
        return $row['total'];
    }

    public static function countEmployeeNotArchivedByDate($date, $additional_qry = "", $employee_ids_qry = "") {
        if ($date == '') {
            $date = Tools::getGmtDate('Y-m-d');
        }
        $sql = "
			SELECT COUNT(DISTINCT e.id) AS total
			FROM g_employee e
			WHERE (e.e_is_archive =" . Model::safeSql(G_Employee::NO) . ")		
			AND (
					". Model::safeSql($date) ." >= e.hired_date
				)
			 
			".$additional_qry.$employee_ids_qry." 
		";

        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);
        return $row['total'];
    }

    public static function sqlAllActiveEmployees($date, $additional_qry = "") {
        if ($date == '') {
            $date = Tools::getGmtDate('Y-m-d');
        }
        $sql = "
			SELECT e.id AS eid, CONCAT(e.firstname, ' ' ,e.lastname) as full_name, e.employee_code as employee_code
			FROM g_employee e
			WHERE (e.e_is_archive =" . Model::safeSql(G_Employee::NO) . ")		
			AND (
					". Model::safeSql($date) ." >= e.hired_date
				)
			 
			".$additional_qry." 
			
		";

		$result = Model::runSql($sql,true);

		$employee_id_array = array();
		foreach($result as $result_key => $result_data) {
			$employee_id_array[$result_data['eid']]['full_name'] = $result_data['full_name'];
			$employee_id_array[$result_data['eid']]['employee_code'] = $result_data['employee_code'];
		}

		return $employee_id_array;
    }



    public static function isDateEmployeeRestDaySpecific($employee_id, $date) {
        $date = date("Y-m-d",strtotime($date));

        $sql = "
			SELECT COUNT(id) AS total
			FROM " . G_EMPLOYEE_RESTDAY . "
			WHERE employee_id =" . Model::safeSql($employee_id) . "
				AND `date` =" . Model::safeSql($date) . "
			
		";
        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);
        if( $row['total'] > 0 ){
        	$is_date_rest_day = true;
        }else{
        	$is_date_rest_day = false;
        }

        return $is_date_rest_day;
    }

	public static function getAllEmployeeCodes() {
		$sql = "
			SELECT employee_code
			FROM ". EMPLOYEE ."
		";
		$result = Model::runSql($sql);
		while ($row = Model::fetchAssoc($result)) {
			$data[$row['employee_code']] = $row['employee_code'];	
		}
		return $data;
	}

    public static function getAllEmployeeCostCenter() {
        $sql = "
            SELECT cost_center
            FROM ". EMPLOYEE ."
        ";
        $result = Model::runSql($sql);
        while ($row = Model::fetchAssoc($result)) {
            if($row['cost_center'] != '') {
                $data[$row['cost_center']] = $row['cost_center'];   
            }
        }
        return $data;
    }   
	
	public static function getAllEmployeeNames() {
		$sql = "
			SELECT employee_code, CONCAT(e.firstname, ' ',e.lastname) as name
			FROM ". EMPLOYEE ." e
		";
		$result = Model::runSql($sql);
		while ($row = Model::fetchAssoc($result)) {
			$data[$row['employee_code']] = $row['name'];	
		}
		return $data;
	}

    public static function getEmployeeNameById($employee_id) {
        $e = G_Employee_Finder::findById2($employee_id);
        if ($e) {
            return $e->getName();
        }
    }

    public static function getEmployeeNameWithCodeById($employee_id) {
        $e = G_Employee_Finder::findById2($employee_id);
        if ($e) {
            return $e->getName() .' ('. $e->getEmployeeCode() .')';
        }
    }
	
	public static function employeeAttachFile($attachment,$data,G_Employee $e) {
		$prefix = 'employee_';
		//print_r($attachment);
		$created_by = G_Employee_Finder::findById(Utilities::decrypt($_SESSION['sprint_hr']['employee_id']));	
		$added_by   = $created_by->lastname. ' ' . $created_by->firstname;	
		
		$len		    = strlen($attachment['attached_file']['name']);
		$pos 		    = strpos($attachment['attached_file']['name'],'.');
		$extension_name = substr($attachment['attached_file']['name'],$pos, 5);

		$handle = new upload($attachment['attached_file']);		
 		$path   = $_SERVER['DOCUMENT_ROOT'] . FILES_FOLDER;
	
	   if ($handle->uploaded) {		   
			$handle->file_new_name_body   = $filename = $prefix.'_'.date('Y-m-d-H-i-s');
			$handle->file_overwrite 	  = true;
		
	       $handle->process($path);
	       if ($handle->processed) {	         
				$image =  $filename . strtolower($extension_name); 				
		
				$gcb = new G_Employee_Attachment($row['id']);
				$gcb->setEmployeeId($e->getId());
				$gcb->setFilename($image);
				$gcb->setDescription($data['description']);
				$gcb->setSize($attachment['attached_file']['size']);
				$gcb->setType($attachment['attached_file']['type']);
				$gcb->setDateAttached(date("Y-m-d"));				
				$gcb->setAddedBy($created_by);
				//$gcb->setScreen($row['screen']);
				$gcb->save();

	           $handle->clean();
			   $return = true;
	       } else {	   		   		
			  $return =  $handle->error;
	       }
	   }
	}
	
	public static function resetTerminatedResignationEndoDate(G_Employee $e) {
		if($e){
			$e->setTerminatedDate("");
			$e->setEndoDate("");
			$e->setResignationDate("");
            $e->setLeaveDate('');
			$e->save();
		}
	}

    public static function resign(IEmployee $e, $date) {
        $e->setEndoDate('');
        $e->setResignationDate($date);
        $e->setTerminatedDate('');
        $e->setLeaveDate($date);
        $e->save();

        G_Employee_Subdivision_History_Helper::ended($e, $date);
        G_Employee_Job_History_Helper::ended($e, $date);
        G_Employee_Basic_Salary_History_Helper::ended($e, $date);
        G_Employee_Branch_History_Helper::ended($e, $date);
    }


     public static function resetActive(IEmployee $e, $date) {
        $e->setEndoDate('');
        $e->setResignationDate($date);
        $e->setTerminatedDate('');
        $e->setLeaveDate($date);
        $e->save();

        G_Employee_Subdivision_History_Helper::resetActive($e, $date);
        G_Employee_Job_History_Helper::resetActive($e, $date);
        G_Employee_Basic_Salary_History_Helper::resetActive($e, $date);
        G_Employee_Branch_History_Helper::resetActive($e, $date);
    }


    public static function terminate(IEmployee $e, $date) {
        $e->setEndoDate('');
        $e->setResignationDate('');
        $e->setTerminatedDate($date);
        $e->setLeaveDate($date);
        $e->save();

        G_Employee_Subdivision_History_Helper::ended($e, $date);
        G_Employee_Job_History_Helper::ended($e, $date);
        G_Employee_Basic_Salary_History_Helper::ended($e, $date);
        G_Employee_Branch_History_Helper::ended($e, $date);
    }

    public static function endo(IEmployee $e, $date) {
        $e->setEndoDate($date);
        $e->setResignationDate('');
        $e->setTerminatedDate('');
        $e->setLeaveDate($date);
        $e->save();

        G_Employee_Subdivision_History_Helper::ended($e, $date);
        G_Employee_Job_History_Helper::ended($e, $date);
        G_Employee_Basic_Salary_History_Helper::ended($e, $date);
        G_Employee_Branch_History_Helper::ended($e, $date);
    }

    public static function inactive(IEmployee $e, $date) {
    	$e->setInactiveDate($date);
        $e->setEndoDate('');
        $e->setResignationDate('');
        $e->setTerminatedDate('');
        $e->setLeaveDate('');
        $e->save();

        //G_Employee_Subdivision_History_Helper::ended($e, $date);
        //G_Employee_Job_History_Helper::ended($e, $date);
        //G_Employee_Basic_Salary_History_Helper::ended($e, $date);
        //G_Employee_Branch_History_Helper::ended($e, $date);
    }    

    public static function activeToTerminated($data,G_Employee $e) {
		if($e){			
			//Update Employee Info	
			$e->setEndoDate("");
			$e->setResignationDate("");		
			$e->setTerminatedDate($data['terminated_date']);
            $e->setLeaveDate($data['terminated_date']);
			$e->save();
			
			//Terminated Subdivision
				//self::employeeTerminateSubdivision($data,$e);
			//
			
			//Terminate Basic Salary
				//self::employeeTerminateBasicSalary($data,$e);
			//
			
			//Add Memo
				//self::employeeTerminateSubdivision($data,$e);
			//
		}
	}
	
	public static function activeToEndo($data,G_Employee $e) {
		if($e){			
			//Update Employee Info						
			$e->setResignationDate("");		
			$e->setTerminatedDate("");
			$e->setEndoDate($data['endo_date']);
            $e->setLeaveDate($data['endo_date']);
			$e->save();
			
			//Terminated Subdivision
				//self::employeeTerminateSubdivision($data,$e);
			//
			
			//Terminate Basic Salary
				//self::employeeTerminateBasicSalary($data,$e);
			//
			
			//Add Memo
				//self::employeeTerminateSubdivision($data,$e);
			//
		}
	}
	
	public static function activeToResigned($data,G_Employee $e) {
		if($e){			
			//Update Employee Info
			$e->setEndoDate("");
			$e->setTerminatedDate("");			
			$e->setResignationDate($data['resigned_date']);
            $e->setLeaveDate($data['resigned_date']);
			$e->save();
			
			//Terminated Subdivision
				//self::employeeTerminateSubdivision($data,$e);
			//
			
			//Terminate Basic Salary
				//self::employeeTerminateBasicSalary($data,$e);
			//
			
			//Add Memo
				//self::employeeTerminateSubdivision($data,$e);
			//
		}
	}
	
	public static function employeeTerminateSubdivision($data,G_Employee $e) {
		//Terminate the subdivision
		$current_subdivision = G_Employee_Subdivision_History_Finder::findCurrentSubdivision($e);
		if($current_subdivision) {
			$current_subdivision->setEndDate($data['terminated_date']);
			$current_subdivision->save();	
		}
	}
	
	public static function employeeAddMemo($data,G_Employee $e) {
		//Add Memo for termination						
		$created_by = G_Employee_Finder::findById(Utilities::decrypt($_SESSION['sprint_hr']['employee_id']));			
		$memo       = G_Settings_Memo_Finder::findById(G_Settings_Memo::TERMINATION);
		if($memo){
			$m = new G_Employee_Memo;			
			$m->setEmployeeId($e->getId());
			$m->setMemoId($memo->getId());
			$m->setTitle($memo->getTitle());
			$m->setMemo($data['memo']);
			$m->setDateCreated(date("Y-m-d"));
			$m->setCreatedBy($created_by->lastname. ' ' . $created_by->firstname);
			$m->save();	
		}
	}
	
	public static function employeeTerminateBasicSalary($data,G_Employee $e) {
		//Terminate the basic salarya
		if($e){
			$current_salary = G_Employee_Basic_Salary_History_Finder::findCurrentSalary($e);
			if($current_salary) {
				$current_salary->setEndDate($data['terminated_date']);
				$current_salary->save();	
			}
		}
	}

	public static function sqlGetEmployeeDetailsByEmployeeCode( $employee_code = '', $fields = array() ){
		if( !empty($fields) ){
			$sql_fields = implode(",",$fields);
		}else{
			$sql_fields = "*";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . EMPLOYEE . "
			WHERE employee_code =" . Model::safeSql($employee_code) . "
			ORDER BY id DESC 
			LIMIT 1
		";
		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;

	}


       //monthly payreg
      public static function sqlGetMonthlyPayslipPeriodWithCustomQuery($start_date, $end_date, $is_confidential_qry = "", $custom_qry = "", $fields = array() , $order_by = '') {
        $sql_fields = '';
        if( !empty($fields) ){
            $sql_fields = implode(",", $fields);
        }else{
            $sql_fields = "*";
        }

        if( !empty( $custom_qry ) ){
            $sql_custom_qry = " AND ({$custom_qry})";
        }else{
            $sql_custom_qry = "";
        }

        if( $order_by == '' ){
            $order_by = "ORDER BY e.lastname";
        }

        $sql = "
            SELECT {$sql_fields}
            FROM g_employee_monthly_payslip p, g_employee e, g_project_sites ps        
            WHERE (p.period_start = ". Model::safeSql($start_date) ." AND p.period_end = ". Model::safeSql($end_date) .")
            AND p.employee_id = e.id AND ps.id = e.project_site_id
            ".$is_confidential_qry."
            {$sql_custom_qry}

            {$order_by}                                 
        ";
       
        $result = Model::runSql($sql,true);
        return $result;


    }



	public static function sqlGetEmployeeDetailsById( $id = '', $fields = array() ){
		if( !empty($fields) ){
			$sql_fields = implode(",",$fields);
		}else{
			$sql_fields = "*";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . EMPLOYEE . "
			WHERE id =" . Model::safeSql($id) . "
			ORDER BY id DESC 
			LIMIT 1
		";
		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;

	}

	public static function sqlGetAllEmployees($fields = array(), $additional_qry = '' ){
		if( !empty($fields) ){
			$sql_fields = implode(",",$fields);
		}else{
			$sql_fields = "*";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . EMPLOYEE . " 
			WHERE e_is_archive = ".Model::safeSql(G_Employee::NO)."
			".$additional_qry." 
			ORDER BY lastname ASC 
		";
		$result = Model::runSql($sql,true);
		return $result;

	}

	/**
	* Get all regular active employees
	*
	* @param array fields - optional;
	* @return array
	*/
	public static function sqlGetAllActiveRegularEmployees( $fields = array() ){
		if( !empty($fields) ){
			$sql_fields = implode(",",$fields);
		}else{
			$sql_fields = "*";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . EMPLOYEE . " 
			WHERE e_is_archive =". Model::safeSql(G_Employee::NO) . "
				AND employment_status_id = 3
			ORDER BY id ASC
		";
		$result = Model::runSql($sql,true);
		return $result;

	}

	/**
	* Get all active employees whos years of service >= 1
	*
	* @param array fields - optional;
	* @return array
	*/
	public static function sqlGetAllEmployeesMoreThanAYearOfService( $fields = array() ){
		if( !empty($fields) ){
			$sql_fields = implode(",",$fields);
		}else{
			$sql_fields = "*";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . EMPLOYEE . " 
			WHERE e_is_archive =". Model::safeSql(G_Employee::NO) . "
				AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),hired_date)), '%Y') >= 1
			ORDER BY id ASC
		";
		$result = Model::runSql($sql,true);
		return $result;

	}

	public static function sqlGetAllEmployeesByEmployeeCode($employee_code = '', $fields = array()){
		if( !empty($fields) ){
			$sql_fields = implode(",",$fields);
		}else{
			$sql_fields = "*";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . EMPLOYEE . " 
			WHERE employee_code IN({$employee_code})
			ORDER BY employee_code ASC
		";		
		$result = Model::runSql($sql,true);
		return $result;
	}

	public static function sqlAllActiveEmployee($fields = array(), $additional_qry = '' ){
		if( !empty($fields) ){
			$sql_fields = implode(",",$fields);
		}else{
			$sql_fields = "*";

		}

		$sql = "
			SELECT {$sql_fields}

			FROM " . EMPLOYEE . " 
			WHERE e_is_archive = ".Model::safeSql(G_Employee::NO)."
			".$additional_qry." 
			ORDER BY lastname ASC 
		";	
		$result = Model::runSql($sql,true);
		return $result;
	}

	public static function sqlGetPayslipPeriodWithOptions($start_date, $end_date, $is_confidential_qry = "", $fields = array() , $order_by = '') {
		$sql_fields = '';
		if( !empty($fields) ){
			$sql_fields = implode(",", $fields);
		}else{
			$sql_fields = "*";
		}

		$sql = "
			SELECT {$sql_fields}

			FROM g_employee_payslip p, g_employee e			
			WHERE (p.period_start = ". Model::safeSql($start_date) ." AND p.period_end = ". Model::safeSql($end_date) .")
			AND p.employee_id = e.id 
			".$is_confidential_qry."
			{$order_by}			
		";
		
		$result = Model::runSql($sql,true);
		return $result;


	}

	public static function sqlGetPayslipPeriodWithCustomQuery($frequency_id,$start_date, $end_date, $is_confidential_qry = "", $custom_qry = "", $fields = array() , $order_by = '') {
		$sql_fields = '';
		if( !empty($fields) ){
			$sql_fields = implode(",", $fields);
		}else{
			$sql_fields = "*";
		}

		if( !empty( $custom_qry ) ){
			$sql_custom_qry = " AND ({$custom_qry})";
		}else{
			$sql_custom_qry = "";
		}

		if( $order_by == '' ){
			$order_by = "ORDER BY e.lastname";
		}


        if($frequency_id == "1"){
            $table_name = 'g_employee_payslip';
        }else{
            $table_name = 'g_employee_weekly_payslip';
        }

		$sql = "
			SELECT {$sql_fields}
			FROM g_employee_payslip p, g_employee e, g_project_sites ps
			WHERE (p.period_start = ". Model::safeSql($start_date) ." AND p.period_end = ". Model::safeSql($end_date) .")
			AND p.employee_id = e.id AND ps.id = e.project_site_id
			".$is_confidential_qry."
			{$sql_custom_qry}

			{$order_by}									
		";
		// $sql ="SELECT DISTINCT e.id,e.employee_code,e.lastname,e.firstname,(SELECT title FROM g_company_structure WHERE id = e.department_company_structure_id LIMIT 1)AS department_name,(SELECT title FROM g_company_structure WHERE id = e.section_id LIMIT 1)AS section_name ,(SELECT status FROM g_settings_employment_status WHERE id = e.employment_status_id) AS employment_status FROM g_employee_payslip p, g_employee e WHERE (p.period_start = '2019-03-26' AND p.period_end = '2019-04-10') AND p.employee_id = e.id   AND (e.endo_date = '0000-00-00' OR e.endo_date = '') AND (e.terminated_date = '0000-00-00' OR e.terminated_date = '') AND (e.inactive_date = '0000-00-00' OR e.inactive_date = '') AND e.id  IN ( 20, 23, 24, 29, 31, 45, 69, 170, 204, 324 , 94,3,5,12 )    ORDER BY e.lastname ";
		// echo $sql;
		//3a 5a 12a 20a 23a 24a 29a 31a 4a5 69a 170a 204a 324a 94a

		$result = Model::runSql($sql,true);
		return $result;


	}

    public static function sqlGetWeeklyPayslipPeriodWithCustomQuery($start_date, $end_date, $is_confidential_qry = "", $custom_qry = "", $fields = array() , $order_by = '') {
        $sql_fields = '';
        if( !empty($fields) ){
            $sql_fields = implode(",", $fields);
        }else{
            $sql_fields = "*";
        }

        if( !empty( $custom_qry ) ){
            $sql_custom_qry = " AND ({$custom_qry})";
        }else{
            $sql_custom_qry = "";
        }

        if( $order_by == '' ){
            $order_by = "ORDER BY e.lastname";
        }

        $sql = "
            SELECT {$sql_fields}
            FROM g_employee_weekly_payslip p, g_employee e ,g_project_sites ps         
            WHERE (p.period_start = ". Model::safeSql($start_date) ." AND p.period_end = ". Model::safeSql($end_date) .")
            AND p.employee_id = e.id AND ps.id = e.project_site_id
            ".$is_confidential_qry."
            {$sql_custom_qry}

            {$order_by}                                 
        ";
       
        $result = Model::runSql($sql,true);
        return $result;


    }
	// payreg
	
	//payreg
	
	public static function sqlGetAllEmployeeByDepartmentId( $department_id = '', $fields = array(), $additional_qry = '' ){
		if( !empty($fields) ){
			$sql_fields = implode(",",$fields);
		}else{
			$sql_fields = "*";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . EMPLOYEE . "
			WHERE department_company_structure_id IN({$department_id}) 
			AND e_is_archive = ".Model::safeSql(G_Employee::NO)."
			".$additional_qry."
			ORDER BY lastname ASC 
		";
		
		$result = Model::runSql($sql,true);
		return $result;

	}

	public static function sqlGetAllEmployeeByEmploymentStatusId( $employment_status_id = '', $fields = array(), $additional_qry = '' ){
		if( !empty($fields) ){
			$sql_fields = implode(",",$fields);
		}else{
			$sql_fields = "*";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . EMPLOYEE . "
			WHERE employment_status_id IN({$employment_status_id}) 
			AND e_is_archive = ".Model::safeSql(G_Employee::NO)."
			".$additional_qry."
			ORDER BY lastname ASC 
		";
		
		$result = Model::runSql($sql,true);
		return $result;

	}

	public static function sqlMultipleEmployeeDetailsById( $ids = '', $fields = array() ){
		if( !empty($fields) ){
			$sql_fields = implode(",",$fields);
		}else{
			$sql_fields = "*";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . EMPLOYEE . "
			WHERE id IN (" . $ids . ")
			ORDER BY lastname ASC 
		";
		
		$result = Model::runSql($sql,true);
		return $result;

	}

	public static function countTotalPayslipDateRange($start_date, $end_date) {
		$sql = "
			SELECT COUNT(DISTINCT e.id) as total
			FROM g_employee_payslip p, g_employee e			
			WHERE (p.period_start >= " . Model::safeSql($start_date) . " AND p.period_end <= " . Model::safeSql($end_date) . ") 
			AND (p.employee_id = e.id)
		";				
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalPayslipIsNotArchiveDateRange($start_date, $end_date) {
		$sql = "
			SELECT COUNT(DISTINCT e.id) as total
			FROM g_employee_payslip p, g_employee e			
			WHERE (p.period_start >= " . Model::safeSql($start_date) . " AND p.period_end <= " . Model::safeSql($end_date) . ") 
			AND (p.employee_id = e.id)
			AND e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "
		";				
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalPagibigDeductionByDateRange($start_date, $end_date) {
		$employees = G_Employee_Finder::findByPayslipDateRangeIsNotArchive($start_date, $end_date);	
		foreach($employees as $e){
			$p = G_Payslip_Finder::findByEmployeeAndDateRange($e, $start_date, $end_date);
			$ph = new G_Payslip_Helper($p);			
			$ee_amount = (float) $ph->getValue('pagibig');
			$er_amount = (float) $ph->getValue('pagibig_er');
			$e_total   = (float) $ee_amount + $er_amount; 
			$ee_gtotal += $ee_amount;
			$er_gtotal += $er_amount; 			
		}
			$g_total  = $ee_gtotal + $er_gtotal;
			$total = array("ee" => $ee_gtotal,"er" => $er_gtotal,"gtotal" => $g_total);
			return $total;
	}
		
	
	public static function countTotalRecordsByCompanyStructureId(G_Company_Structure $gcs) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . EMPLOYEE ."
			WHERE company_structure_id = ". Model::safeSql($gcs->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsIsNotArchiveByCompanyStructureId($csid, $search = "") {

		$sql = "
			SELECT COUNT(*) as total
			FROM " . EMPLOYEE ."
			WHERE company_structure_id = ". Model::safeSql($csid) ."
			AND e_is_archive =" . Model::safeSql(G_Employee::NO) . " 
			".$search."
		";
				
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsIsArchiveByCompanyStructureId($csid) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . EMPLOYEE ."
			WHERE company_structure_id = ". Model::safeSql($csid) ."
			AND e_is_archive =" . Model::safeSql(G_Employee::YES) . " 
		";
				
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function isUsernameFirstNameLastNameBirthdateByCompanyStructureId($eAr,$company_structure_id) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . EMPLOYEE ." e
				LEFT JOIN " . G_EMPLOYEE_USER . " u
					ON e.id = u.employee_id 
			WHERE (e.company_structure_id = ". Model::safeSql($company_structure_id) ." AND e.e_is_archive =" . Model::safeSql(G_Employee::NO) . ")
				AND (e.firstname =" . Model::safeSql($eAr['firstname']) . "
				AND e.lastname =" . Model::safeSql($eAr['lastname']) . "
				AND e.birthdate =" . Model::safeSql($eAr['birthdate']) . "
				AND e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "
				AND u.username =" . Model::safeSql($eAr['username']) . "
				)				
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function getTotalSalaryByDepartment() {
		$sql = "SELECT d.name AS department, SUM( s.basic_salary ) AS salary
			FROM g_employee_basic_salary_history s, g_employee_subdivision_history d
			WHERE s.employee_id = d.employee_id
			AND s.end_date = ''
			AND d.end_date = ''
			GROUP BY department";	
			
		$result = Model::runSql($sql,true);
		return $result;			
	}
	
	
	
	public static function getTotalSalaryByRange() {
		$sql = "SELECT 
				SUM( IF( basic_salary <3000, 1, 0 ) ) AS below_3k, 
				SUM( IF( basic_salary BETWEEN 3000 AND 8000 , 1, 0 ) ) AS between_3k_8k,
				SUM( IF( basic_salary BETWEEN 8001 AND 15000 , 1, 0 ) ) AS between_8k_15k, 
				SUM( IF( basic_salary BETWEEN 15001 AND 20000 , 1, 0 ) ) AS between_15k_20k, 
				SUM( IF( basic_salary BETWEEN 20001 AND 30000 , 1, 0 ) ) AS between_20k_30k, 
				SUM( IF( basic_salary BETWEEN 30001 AND 40000 , 1, 0 ) ) AS between_30k_40k, 
				SUM( IF( basic_salary BETWEEN 40001 AND 50000 , 1, 0 ) ) AS between_40k_50k,
				SUM( IF( basic_salary BETWEEN 50001 AND 60000 , 1, 0 ) ) AS between_50k_60k,
				SUM( IF( basic_salary > 60000 , 1, 0 ) ) AS above_60k
				FROM g_employee_basic_salary_history s
				WHERE s.end_date = '' ";
				
		$result = Model::runSql($sql,true);
		return $result;
	}
	
	public static function getTotalEmployeeByMonth($year,$month) {
		$month = ($month=='')? '' :  'AND MONTH( hired_date )='.$month ;
		$year = ($year=='')? date("Y") : $year ;
		$sql = "SELECT YEAR(hired_date) as year, MONTH( hired_date ) as month , 
				SUM(1) AS total_employee
				FROM g_employee
				WHERE YEAR( hired_date ) = ".$year."
				".$month."
				GROUP BY year,month";
		$result = Model::runSql($sql,true);
		return $result;
	}
	
	public static function getTotalTerminatedByYearAndMonth() {
		$sql = "
		SELECT YEAR( terminated_date ) AS year, MONTH( terminated_date ) AS
		month , SUM(IF(terminated_date>0,1,0 ) ) AS total_terminated
		FROM g_employee
		GROUP BY year,
		MONTH asc";	

		$result = Model::runSql($sql,true);
		return $result;
	}

	public static function getEmployeeListAndYearsOfService() {
		$sql = "
			SELECT id, employment_status_id, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),hired_date)), '%Y')+0 AS year_of_service,  
			PERIOD_DIFF(DATE_FORMAT(NOW(), '%Y%m') , DATE_FORMAT(hired_date, '%Y%m') ) AS total_months
			FROM " . EMPLOYEE ."
		";
		$result = Model::runSql($sql,true);
		return $result;
	}

	public static function getEmployeeYearsOfService($emp_id) {
		$sql = "
			SELECT employment_status_id, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),hired_date)), '%Y')+0 AS year_of_service, 
			PERIOD_DIFF(DATE_FORMAT(NOW(), '%Y%m') , DATE_FORMAT(hired_date, '%Y%m') ) AS total_months
			FROM " . EMPLOYEE ."
			WHERE id = ". Model::safeSql($emp_id) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}
	
	public static function getTotalHiredByYearAndMonth() {
		$sql = "
		SELECT YEAR( hired_date ) AS year, MONTH( hired_date ) AS
		month , SUM( 1 ) AS total_hired
		FROM g_employee
		GROUP BY year,
		MONTH asc";	
		$result = Model::runSql($sql,true);
		return $result;
	}
	
	public static function getHeadcountByDepartment() {
		$sql = "SELECT h.name AS department, SUM( 1 ) AS total_employee
			FROM g_employee_subdivision_history h
			WHERE h.end_date = ''
			GROUP BY department
			ORDER BY department
			";

		$result = Model::runSql($sql,true);
		return $result;
	}
	
	public static function findByDepartmentIdMonth($department_id,$month, $add_on_qry = '') {
		$where = '';
		if($department_id!='') {
		
			$where = 'WHERE ';
			$query .= $where . "  d.company_structure_id=".$department_id .' AND ' . $add_on_qry;
		}else {
			//all	
			if( $add_on_qry != '' ){
				$where = 'WHERE ';
				$query .=  $where . ' ' . $add_on_qry;
			}
		}
		
		if($month!=''){
			if( $where != '' ){
				$where = ' AND ';
				$query .= ($month)? $where . " MONTH(e.birthdate)=".Model::safeSql($month) : '' ;
			}else{
				$where = '';
				$query .= ($month)? $where . " MONTH(e.birthdate)=".Model::safeSql($month) : '' ;
			}
		}	
		
		$sql = "
		SELECT 
			d.name as department,
			e.employee_code,
			CONCAT(e.lastname, ', ', e.firstname, ' ', substr(e.middlename,1,1),'.') as employee_name,
			e.birthdate,
			year(curdate())-year(e.birthdate) - (right(curdate(),5) < right(e.birthdate,5)) as age,
			j.name as position,
			j.employment_status
		FROM g_employee_subdivision_history d
			LEFT JOIN g_employee e ON e.id=d.employee_id
			LEFT JOIN g_employee_job_history j ON j.employee_id=e.id 
		".$query."		
		ORDER BY e.birthdate ";		
		$rec = Model::runSql($sql,true);
		return $rec;
	}

	public static function findByDepartmentIdBirthMonth($department_id,$month, $add_on_qry = '') {
		$where = '';
		if($department_id!='') {
		
			$where = 'WHERE ';
			$query .= $where . "  d.company_structure_id=".$department_id .' AND ' . $add_on_qry;
			$query .= " AND d.end_date = ''";
		}else {
			//all	
			if( $add_on_qry != '' ){
				$where = 'WHERE ';
				$query .=  $where . ' ' . $add_on_qry;
			}
		}
		
		if($month!=''){
			if( $where != '' ){
				$where = ' AND ';
				$query .= ($month)? $where . " MONTH(e.birthdate)=".Model::safeSql($month) : '' ;
				$query .= " AND d.end_date = ''";
			}else{
				$where = '';
				$query .= ($month)? $where . " MONTH(e.birthdate)=".Model::safeSql($month) : '' ;
				$query .= " AND d.end_date = ''";
			}
		}else{
			$query .= " AND d.end_date =''";
		}	
		
		$sql = "
		SELECT 
			d.name as department,
			e.employee_code,
			CONCAT(e.lastname, ', ', e.firstname, ' ', substr(e.middlename,1,1),'.') as employee_name,
			e.birthdate,
			e.nickname,
			year(curdate())-year(e.birthdate) - (right(curdate(),5) < right(e.birthdate,5)) as age,
			j.name as position,
			j.employment_status,
			(SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name 
		FROM g_employee_subdivision_history d
			LEFT JOIN g_employee e ON e.id=d.employee_id
			LEFT JOIN g_employee_job_history j ON j.employee_id=e.id AND j.end_date = ''
		".$query."		
		ORDER BY e.birthdate ";	
		
		$rec = Model::runSql($sql,true);
		return $rec;
	}
	
	public static function findHashByEmployeeId($id) {
		$sql = "
			SELECT e.hash 
			FROM g_employee e
			WHERE e.id = ". $id ."	
			LIMIT 1		
		";
		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}
	public static function sqlEmployeeDetailsByEmployeeId( $employee_id = 0, $fields = array() ){

		if( !empty( $fields ) ){
			$sql_fields = implode(",", $fields);
		}else{
			$sql_fields = " * ";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . EMPLOYEE ."
			WHERE id = ". Model::safeSql($employee_id) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}
	
	
	public static function isIdExist(G_Employee $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . EMPLOYEE ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function findByEmployeeCode($q) {
		$sql = "
			SELECT u.id, CONCAT(u.firstname,' ', u.lastname) as name,u.photo, u.hash
			FROM g_employee u
			WHERE (u.employee_code  LIKE '{$q}%') LIMIT 10
			";

			$records = Model::runSql($sql, true);
			return $records;
	}
	
	public static function findByLastnameFirstname($q,$additional_qry) {
		$sql = "
			SELECT u.id, CONCAT(u.firstname,' ', u.lastname) as name,u.photo, u.hash
			FROM g_employee u
			WHERE (u.lastname LIKE '%{$q}%' OR u.firstname LIKE '%{$q}%') ".$additional_qry." LIMIT 10
			";

			$records = Model::runSql($sql, true);
			return $records;
	}
	
	public static function findByDynamicSearch(G_Company_Structure $gcs,$order_by,$limit,$search='') {
		$sql = "
			SELECT
			`e`.`id`,
			e.hash,
			company_branch.name as  branch_name,
			`d`.`name` AS `department`,
			`e`.`employee_code`,
			e.photo,
			e.salutation,

			CONCAT(e.lastname,', ',e.firstname,' ',substring(e.middlename,1,1),'. ', e.extension_name) AS `employee_name`,
			
			`j`.`name` AS `position`,
			j.employment_status AS employment_status
			FROM
			`g_employee` AS `e`
			Left Join `g_employee_subdivision_history` AS `d` ON `e`.`id` = `d`.`employee_id` AND `d`.`end_date` = ''
			Left Join `g_employee_branch_history` AS `b` ON `e`.`id` = `b`.`employee_id` AND `b`.`end_date` = ''
			Left Join g_company_branch as company_branch ON b.company_branch_id=company_branch.id
			Left Join `g_employee_job_history` AS `j` ON (`j`.`employee_id` = `e`.`id` AND `j`.`end_date` = '' ) OR (j.employee_id=e.id AND j.employment_status='Terminated')
			Inner Join `g_company_structure` AS `company` ON `e`.`company_structure_id` = `company`.`id`
			Left Join g_job AS job ON job.id=j.job_id

			WHERE company.id=".Model::safeSql($gcs->getId())." 
			".$search."
			".$order_by."
			".$limit."
		";
		
		$result = Model::runSql($sql,true);

		return $result;
	}
	
	public static function findBySearch(G_Company_Structure $gcs,$order_by,$limit,$search='') {
		$sql = "
			SELECT
			`e`.`id`,
			e.hash,
			company_branch.name as  branch_name,
			`d`.`name` AS `department`,
			`e`.`employee_code`,
			e.photo,
			e.salutation,

			CONCAT(e.lastname,', ',e.firstname,' ',substring(e.middlename,1,1),'. ', e.extension_name) AS `employee_name`,
			
			`j`.`name` AS `position`,
			j.employment_status AS employment_status
			FROM 
			`g_employee` AS `e`
			Left Join `g_employee_subdivision_history` AS `d` ON `e`.`id` = `d`.`employee_id` AND `d`.`end_date` = ''
			Left Join `g_employee_branch_history` AS `b` ON `e`.`id` = `b`.`employee_id` AND `b`.`end_date` = ''
			Left Join g_company_branch as company_branch ON b.company_branch_id=company_branch.id
			Left Join `g_employee_job_history` AS `j` ON (`j`.`employee_id` = `e`.`id` AND `j`.`end_date` = '' ) OR (j.employee_id=e.id AND j.employment_status='Terminated')
			Left Join `g_employee_basic_salary_history` AS `salary` ON `salary`.`end_date` = '' AND `salary`.`employee_id` = `e`.`id`
			Inner Join `g_company_structure` AS `company` ON `e`.`company_structure_id` = `company`.`id`
			Left Join `g_employee_contact_details` AS `contact` ON `e`.`id` = `contact`.`employee_id`
			Left Join g_eeo_job_category AS job_category ON job_category.id=e.eeo_job_category_id
			Left Join g_job AS job ON job.id=j.job_id
			Left Join g_job_specification AS job_specification ON job_specification.id=job.job_specification_id

			WHERE company.id=".Model::safeSql($gcs->getId())." 
			".$search."
			".$order_by."
			".$limit."
		";

		$result = Model::runSql($sql,true);

		return $result;
	}
	
	
	public static function findByCompanyStructureId(G_Company_Structure $gcs,$order_by,$limit,$search='') {
		$sql = "
			SELECT
			`e`.`id`,
			e.hash,
			`e`.`company_structure_id`,
			e.eeo_job_category_id,
			`salary`.`job_salary_rate_id`,
			(SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name,
			j.job_id AS job_id,
			j.id as job_history_id,
			b.id as branch_history_id,
			b.company_branch_id as branch_id,
			d.company_structure_id as department_id,
			`company`.`title` AS `company_name`,
			job_specification.id as job_specification_id,
			company_branch.name as  branch_name,
			`d`.`name` AS `department`,
			(SELECT title FROM `g_company_structure` WHERE id = e.section_id LIMIT 1)AS section_name,
			`e`.`employee_code`,
			e.photo,
			e.salutation,
			e.firstname,
			e.lastname,
			e.middlename,
			e.extension_name,
			CONCAT(e.lastname,', ',e.firstname,' ',substring(e.middlename,1,1),'. ', e.extension_name) AS `employee_name`,
			`salary`.`basic_salary`,
			`e`.`birthdate`,
			`e`.`gender`,
			`e`.`marital_status`,
			`e`.`nationality`,
			e.number_dependent,
			`e`.`sss_number`,
			`e`.`tin_number`,
			`e`.`pagibig_number`,
			e.philhealth_number,
			e.hired_date,
			e.terminated_date,
			`j`.`name` AS `position`,
			j.employment_status AS employment_status,
			jes.name AS employee_status,
			`contact`.`address`,
			`contact`.`city`,
			`contact`.`province`,
			`contact`.`zip_code`,
			`contact`.`country`,
			`contact`.`home_telephone`,
			`contact`.`mobile`,
			`contact`.`work_telephone`,
			`contact`.`work_email`,
			`contact`.`other_email`,
			contract.end_date,
			contract.start_date,
			job_category.category_name as job_category_name,
			job_specification.description as job_description,
			job_specification.duties as job_duties,
			t.tags
	
			FROM
			`g_employee` AS `e`
			Left Join `g_employee_subdivision_history` AS `d` ON `e`.`id` = `d`.`employee_id` AND `d`.`end_date` = ''
			Left Join `g_employee_branch_history` AS `b` ON `e`.`id` = `b`.`employee_id` AND `b`.`end_date` = ''
			Left Join g_company_branch as company_branch ON b.company_branch_id=company_branch.id
			Left Join `g_employee_job_history` AS `j` ON  (`j`.`employee_id` = `e`.`id` AND `j`.`end_date` = '' ) OR (j.employee_id=e.id AND j.employment_status='Terminated')
			Left Join `g_settings_employee_status` AS `jes` ON (`e`.`employee_status_id` = `jes`.`id`)
			Left Join `g_employee_basic_salary_history` AS `salary` ON `salary`.`end_date` = '' AND `salary`.`employee_id` = `e`.`id`
			Inner Join `g_company_structure` AS `company` ON `e`.`company_structure_id` = `company`.`id`
			Left Join `g_company_structure` AS `c_section` ON `e`.`section_id` = `c_section`.`id` AND `c_section`.`type` = 'Section' 
			Left Join `g_employee_contact_details` AS `contact` ON `e`.`id` = `contact`.`employee_id`
			Left Join g_eeo_job_category AS job_category ON job_category.id=e.eeo_job_category_id
			Left Join g_job AS job ON job.id=j.job_id
			Left Join g_job_specification AS job_specification ON job_specification.id=job.job_specification_id
			Left Join g_employee_tags AS `t` ON `e`.`id` = `t`.`employee_id` 
			Left Join g_employee_extend_contract AS contract ON contract.employee_id=e.id
			WHERE company.id=".Model::safeSql($gcs->getId())."
			".$search."
			GROUP BY e.id
			".$order_by."
					
			".$limit."
			
		";

		$result = Model::runSql($sql,true);

		return $result;
	}
	
	public static function findByEmployeeId($employee_id) {
		$current_date = date("Y-m-d");
		$sql = "
			SELECT
			`e`.`id`,
			e.hash,
			`e`.`company_structure_id`,
			e.eeo_job_category_id,
			`salary`.`job_salary_rate_id`,
			j.job_id AS job_id,
			j.id as job_history_id,
			b.id as branch_history_id,
			b.company_branch_id as branch_id,
			d.company_structure_id as department_id,
			`company`.`title` AS `company_name`,
			job_specification.id as job_specification_id,
			COALESCE(company_branch.name,(
				SELECT b.name 
				FROM `g_employee_branch_history` bh 
					LEFT JOIN `g_company_branch` b ON bh.company_branch_id = b.id 
				WHERE bh.employee_id = e.id 
					AND bh.end_date <> ''
				ORDER BY bh.end_date DESC 
				LIMIT 1
			))as  branch_name,
			COALESCE(`d`.`name`,(
				SELECT name FROM `g_employee_subdivision_history`
				WHERE employee_id = e.id 
					AND end_date <> ''
				ORDER BY end_date DESC
				LIMIT 1
			))AS `department`,
			(SELECT title FROM `g_company_structure` WHERE id = e.section_id LIMIT 1)AS section_name,
			`e`.`employee_code`,
			e.photo,
			e.employee_status_id,
			e.salutation,
			e.firstname,
			e.lastname,
			e.middlename,e.extension_name,
			CONCAT(e.lastname,', ',e.firstname,' ',e.middlename,' ', e.extension_name) AS `employee_name`,
			`salary`.`basic_salary`,
			`e`.`birthdate`,
			`e`.`gender`,
			`e`.`marital_status`,
			`e`.`nationality`,
			e.number_dependent,
			`e`.`sss_number`,
			`e`.`tin_number`,
			`e`.`pagibig_number`,
			e.philhealth_number,
			e.hired_date,
			e.terminated_date,
			e.inactive_date,
			e.resignation_date,
			e.endo_date,
			e.e_is_archive,
			COALESCE(`j`.`name`,(
				SELECT name FROM `g_employee_job_history`
				WHERE employee_id = e.id 
					AND end_date <> ''
				ORDER BY end_date DESC 
				LIMIT 1
				))AS `position`,	
			COALESCE(`j`.`employment_status`,(
				SELECT employment_status FROM `g_employee_job_history`
				WHERE employee_id = e.id 
					AND end_date <> ''
				ORDER BY end_date DESC 
				LIMIT 1
				))AS `employment_status`,	
			`contact`.`address`,
			`contact`.`city`,
			`contact`.`province`,
			`contact`.`zip_code`,
			`contact`.`country`,
			`contact`.`home_telephone`,
			`contact`.`mobile`,
			`contact`.`work_telephone`,
			`contact`.`work_email`,
			`contact`.`other_email`,
			job_category.category_name as job_category_name,
			job_specification.description as job_description,
			job_specification.duties as job_duties,
			is_confidential, section_id
	
			FROM
			`g_employee` AS `e`
			Left Join `g_employee_subdivision_history` AS `d` ON `e`.`id` = `d`.`employee_id` AND `d`.`end_date` = ''
			Left Join `g_employee_branch_history` AS `b` ON `e`.`id` = `b`.`employee_id` AND `b`.`end_date` = ''
			Left Join g_company_branch as company_branch ON b.company_branch_id=company_branch.id
			Left Join `g_employee_job_history` AS `j` ON `j`.`employee_id` = `e`.`id` AND `j`.`end_date` = ''
			Left Join `g_employee_basic_salary_history` AS `salary` ON `salary`.`end_date` = '' AND `salary`.`employee_id` = `e`.`id`
			Inner Join `g_company_structure` AS `company` ON `e`.`company_structure_id` = `company`.`id`
			Left Join `g_employee_contact_details` AS `contact` ON `e`.`id` = `contact`.`employee_id`
			Left Join g_eeo_job_category AS job_category ON job_category.id=e.eeo_job_category_id
			Left Join g_job AS job ON job.id=j.job_id
			Left Join g_job_specification AS job_specification ON job_specification.id=job.job_specification_id	
			WHERE e.id=".Model::safeSql($employee_id)." 
			
			".$order_by."
			GROUP BY e.id
			".$limit."
		";		
		
		$row = Model::runSql($sql);
		$result = Model::fetchAssoc($row);
		return $result;
	}
	
		public static function getNextHash($employee_id, $is_confidential_qry = "")
	{
		$sql = "
			SELECT
			a.hash
			FROM
			`g_employee` AS `a`
			
			WHERE a.id>".Model::safeSql($employee_id)."
			".$is_confidential_qry."
			ORDER BY a.id ASC		
			LIMIT 1
			";
		$result = Model::runSql($sql,true);

		return  $result[0]['hash'];
	}
	
		public static function getPreviousHash($employee_id, $is_confidential_qry = "")
	{
		$sql = "
			SELECT
			a.hash
			FROM
			`g_employee` AS `a`
			
			WHERE a.id<".Model::safeSql($employee_id)."
			".$is_confidential_qry."
			ORDER BY a.id DESC		
			LIMIT 1
			";

		$result = Model::runSql($sql,true);

		return $result[0]['hash'];
	}
	
	
	public static function getNextId($employee_id, $is_confidential_qry = "")
	{
		$sql = "
			SELECT
			a.id
			FROM
			`g_employee` AS `a`
			
			WHERE a.id>".Model::safeSql($employee_id)."
			".$is_confidential_qry."
			ORDER BY a.id ASC		
			LIMIT 1
			";
		$result = Model::runSql($sql,true);

		return  $result[0]['id'];
	}
	
		public static function getPreviousId($employee_id, $is_confidential_qry = "")
	{
		$sql = "
			SELECT
			a.id
			FROM
			`g_employee` AS `a`
			
			WHERE a.id<".Model::safeSql($employee_id)."
			".$is_confidential_qry."
			ORDER BY a.id DESC		
			LIMIT 1
			";

		$result = Model::runSql($sql,true);

		return $result[0]['id'];
	}
	
	public static function getNextIdAlphabetic($employee_id) {
		$sql = "
			SELECT
			a.id
			FROM
			". EMPLOYEE ." AS `a`
			
			WHERE a.lastname > (SELECT a.lastname FROM ". EMPLOYEE ." as a WHERE a.id = ".Model::safeSql($employee_id).")
			OR a.lastname = (SELECT a.lastname FROM ". EMPLOYEE ." as a WHERE a.id = ".Model::safeSql($employee_id).")
			AND a.id > ".Model::safeSql($employee_id)."
			ORDER BY a.lastname ASC, a.id ASC
			
			LIMIT 1
			";
		$result = Model::runSql($sql,true);

		return  $result[0]['id'];
	}	
	
	public static function getPreviousIdAlphabetic($employee_id) {
		$sql = "
			SELECT
			a.id
			FROM
			". EMPLOYEE ." AS `a`
			
			WHERE a.lastname < (SELECT a.lastname FROM ". EMPLOYEE ." as a WHERE a.id = ". Model::safeSql($employee_id) .")
			OR a.lastname = (SELECT a.lastname FROM ". EMPLOYEE ." as a WHERE a.id = ". Model::safeSql($employee_id) .")
			AND a.id < ". Model::safeSql($employee_id) ."
			ORDER BY a.lastname DESC, a.id DESC
			
			LIMIT 1
			";

		$result = Model::runSql($sql,true);

		return $result[0]['id'];
	}
	
	public static function getDynamicQueries($queries) {
				$field_list = array(
							'branch',
							'department',
							'position',
							'employment status',
							'employee id',
							'lastname',
							'firstname',
							'birthdate',
							'age','gender',
							'marital status',
							'address',
							'gender',
							'city',
							'home telephone',
							'mobile',
							'work email',
							'hired date',
							'terminated date',
							'section',
							'end of contract',
							'tags');
		
		
				$result = explode(':',$queries);
				$ctr=0;
				$query='';
				
				foreach($result as $key=>$value) {
					
					if(substr_count($value,',')==1) { //with comma
						$r = explode(',',$value);
						foreach($r as $key=>$vl){
							if($ctr==0) {/* add category */
								$ctr=1;
								$str = ($vl=='') ? "" : $vl ;	
								
								$field = Tools::searchInArray($field_list,strtolower($vl));
								$category = strtolower($field[0]);	
								
								$category = strtolower($str);
							}else { /* add value*/
								$ctr=0;$str = ($vl=='') ? "" : $vl ;
								$or = (strlen($query[$category])>0) ? ' /OR/ ': '' ; 
								$query[$category].= $or. strtolower($str);
							}	
						}
					}else { // no comma

						if($ctr==0) {/* add category*/
							$ctr=1;
							$field = Tools::searchInArray($field_list,strtolower($value));
							$y=0;
							foreach($field as $key=>$f) {
								if($y==0) {
									$field = $f;	
								}
								$y++;	
							}
							$category = strtolower($f);		
						}else { /* add value*/
							$ctr=0;	
							$or = (strlen($query[$category])>0) ? ' /OR/ ': '' ; 
							$query[$category].= $or. strtolower($value);
						}
					}
				}
			
			$field_list = array(
							'branch'=>'company_branch.name',
							'department'=>'d.name',
							'position'=>'j.name',
							'employment status'=>'j.employment_status',
							'employee id'=>'e.employee_code',
							'lastname'=>'e.lastname',
							'firstname'=>'e.firstname',
							'birthdate'=>'e.birthdate',
							'age'=>'(CURDATE() - e.birthdate)',
							'gender'=>'e.gender',
							'marital status'=>'e.marital_status',
							'address'=>'contact.address',
							'city'=>'contact.city',
							'home telephone'=>'contact.home_telephone',
							'mobile'=>'contact.mobile',
							'work email'=>'contact.work_email',
							'hired date'=>'e.hired_date',
							'terminated date'=>'e.terminated_date',
							'section'=>'c_section.title',
							'end of contract'=>'contract.end_date',
							'tags'=>'t.tags');
				$x=1;			
				$total_query = count($query);
				$has_basic=0;
				$has_more_queries=0;
				$is_first_time=1;
				
				foreach($query as $key=>$value) {
				
					if($value!='') {
						if($field_list[$key]!="") {
							$q[$field_list[$key]].=$value;
			
							if(substr_count($value, '/OR/')>0) {
								
								$has_more_queries=1;
									
								$v = explode("/OR/",$value);
								$r = count($v);
								$ctr=1;
								$xx='';
								foreach($v as $k=>$str) {
									if($field_list[$key]=='e.employee_code') {
										$comma = ($r==$ctr)? '': ' OR ' ;
										$xx.=$field_list[$key]."='".trim($str)."'".$comma;
									}else {
										$comma = ($r==$ctr)? '': ' OR ' ;
										$xx.=$field_list[$key]." LIKE '%".trim($str)."%'".$comma;
									}
									$ctr++;
								}		
								$sep.= " AND ". "(". $xx.")";	
							}else {
								
								$has_basic=1;

								if($field_list[$key]=='e.employee_code') {
									$and = ($x<=$total_query && $value=='')? '': ' AND ' ;
									$and = ($is_first_time==1)? '' : $and ;
									$search.= $where.$and. " $field_list[$key]='". $value ."' ";
								}elseif($field_list[$key]=='e.gender') {
									$and = ($x<=$total_query && $value=='' )? '': ' AND ' ;
									$and = ($is_first_time==1)? '' : $and ;
									$search.= $where.$and. " $field_list[$key] LIKE '". $value ."%' ";
								}else {
									$and = ($x<=$total_query && $value=='')? '': ' AND ' ;
									$and = ($is_first_time==1)? '' : $and ;
									$search.= $where.$and. " $field_list[$key] LIKE '%". $value ."%' ";	
								}
								
								if($is_first_time==1) {
									$is_first_time=0;
								}
							}
	
						}	
					}
					$x++;
				}
				
				if($total_query>1) {
					if($has_basic==1) {	
						$search = "AND (".$search.")";	
					}
				}else {
					if($has_basic==1) {
						$search = "AND (".$search.")";	
					}
				}
				$search.=$sep;					
				$search = "AND (e.e_is_archive = '" . G_Employee::NO . "') " .  $search;
		return $search;
	}
	
	public static function getSortValue($sort) {
		if($sort=='branch_name') {
				$sort = 'e.id';
		}elseif($sort=='department') {
			$sort = 'd.name';
		}elseif($sort=='employee_name') {
			$sort = 'e.lastname';
		}elseif($sort=='employee_code') {
			$sort = 'e.employee_code';
		}	
		return $sort;
	}
	
	public static function constructSqlFilterEmployeeAlreadyInTheGroup($company_structure_id) 
	{
		$specific_employee = array();
		$se = G_Employee_Subdivision_History_Finder::findAllCurrentEmployeesByCompanyStructureId($company_structure_id);
		foreach($se as $e):
			array_push($specific_employee, $e->getEmployeeId());
		endforeach;
		
		$implode = implode(' AND employee_id != ',$specific_employee);
		if($implode) {
			$construct_sql = "AND (employee_id != $implode)";
		}
		return $construct_sql;
		
	}

	public static function getManpowerCountData($query) {	
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}

		$sql = "
			SELECT COUNT(e.id) as total_manpower 
			FROM " . EMPLOYEE . " e 
			LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id 
			WHERE 
				e.hired_date >=  " . Model::safeSql($query['date_from']) . " 
				AND e.hired_date <=  " . Model::safeSql($query['date_to']) . "  
				AND ( e.resignation_date >= IF(e.resignation_date = '0000-00-00','0000-00-00',CURDATE()) ) 
				AND ( e.endo_date >= IF(e.endo_date = '0000-00-00','0000-00-00',CURDATE()) ) 
				AND ( e.terminated_date >= IF(e.terminated_date = '0000-00-00','0000-00-00',CURDATE()) ) 
				AND e.e_is_archive = ". Model::safeSql(G_Employee::NO) . "
				" . $search . "
		";

		$result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);
        return $row['total_manpower'];
	}
    
    public static function getEndOfContractDataDepre($query) {
		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			if($query['search_field'] == 'birthdate') {
				$query_search = $query['birthdate'];
			}else{
				$query_search = $query['search'];
			}
			$search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);				
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}
        
        $sql = "
            SELECT e.employee_code, CONCAT(e.lastname, ', ', e.firstname) as name, 
                esh.name AS department_name, esh.start_date, esh.end_date
            FROM " . EMPLOYEE . " e
            LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id 
            WHERE esh.start_date >= " . Model::safeSql($query['date_from']) . "
                AND esh.end_date <= " . Model::safeSql($query['date_to']) . "
                AND esh.end_date <> ''
                AND esh.type = ". Model::safeSql(G_Employee_Subdivision_History::DEPARTMENT). "
                " . $search . "
            ORDER BY esh.end_date DESC
        ";
        
		$result = Model::runSql($sql,true);		
		return $result;
	}

	public static function getEndOfContractData($query, $add_query = '') {
    	$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}

		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			if($query['search_field'] == 'birthdate') {
				$query_search = $query['birthdate'];
			}else{
				$query_search = $query['search'];
			}
			$search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);				
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}


        if ($query['project_site_id'] != '' && $query['project_site_id'] != 'all') {
            $search .= " AND e.project_site_id =" . Model::safeSql($query['project_site_id']);
        }

        $sql = "
            SELECT e.employee_code, CONCAT(e.lastname, ', ', e.firstname) as name, e.project_site_id,
	            COALESCE(`ejh`.`name`,(
	                SELECT name FROM `g_employee_job_history`
	                WHERE employee_id = e.id 
	                    AND end_date <> ''
	                ORDER BY end_date DESC 
	                LIMIT 1
	                ))AS `position_name`,   
            	COALESCE(esh.name,(
					SELECT name FROM `g_employee_subdivision_history`
					WHERE employee_id = e.id 
						AND end_date <> ''
					ORDER BY end_date DESC
					LIMIT 1
				))AS department_name,                 
                e.endo_date,
                (SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name
            FROM " . EMPLOYEE . " e
            LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
            LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON e.id = ejh.employee_id AND ejh.end_date = ''
            WHERE e.endo_date BETWEEN " . Model::safeSql($query['date_from']) . " AND " . Model::safeSql($query['date_to']) . "
            	AND e.endo_date <> '0000-00-00'
            	{$sql_add_query}
                " . $search . "
            ORDER BY esh.end_date DESC
        ";

		$result = Model::runSql($sql,true);		
		return $result;
	}

	public static function getResignedEmployees($query) {
		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			if($query['search_field'] == 'birthdate') {
				$query_search = $query['birthdate'];
			}else{
				$query_search = $query['search'];
			}
			$search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);				
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}

         if ($query['project_site_id'] != '' && $query['project_site_id'] != 'all') {
            $search .= " AND e.project_site_id =" . Model::safeSql($query['project_site_id']);
        }
        
		$sql = "
            SELECT e.employee_code, CONCAT(e.lastname, ', ' , e.firstname) as employee_name,  e.project_site_id,
            e.resignation_date,
            COALESCE(esh.name,(
				SELECT name FROM `g_employee_subdivision_history`
				WHERE employee_id = e.id 
					AND end_date <> ''
				ORDER BY end_date DESC
				LIMIT 1
			))AS department_name, 
			(SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name,
            COALESCE(ejh.name,(
			SELECT name FROM `g_employee_job_history`
			WHERE employee_id = e.id 
				AND end_date <> ''
			ORDER BY end_date DESC 
			LIMIT 1
			))AS position            	
			FROM ". EMPLOYEE ." e               
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON esh.employee_id = ejh.employee_id AND ejh.end_date = ''
			WHERE 
				e.resignation_date BETWEEN " . Model::safeSql($query['date_from']) . " AND " . Model::safeSql($query['date_to']) . "
				" . $search . "
            ORDER BY e.resignation_date, CONCAT(e.lastname, ', ' , e.firstname) ASC
        ";    
		$result = Model::runSql($sql,true);		
		return $result;
	}

	public static function getTerminatedEmployees($query) {
		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			if($query['search_field'] == 'birthdate') {
				$query_search = $query['birthdate'];
			}else{
				$query_search = $query['search'];
			}
			$search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);				
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}

         if ($query['project_site_id'] != '' && $query['project_site_id'] != 'all') {
            $search .= " AND e.project_site_id =" . Model::safeSql($query['project_site_id']);
        }
        
		$sql = "
            SELECT e.employee_code, CONCAT(e.lastname, ', ' , e.firstname) as employee_name, 
            e.terminated_date, e.project_site_id,
            COALESCE(esh.name,(
				SELECT name FROM `g_employee_subdivision_history`
				WHERE employee_id = e.id 
					AND end_date <> ''
				ORDER BY end_date DESC
				LIMIT 1
			))AS department_name, 
			(SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name,
            COALESCE(ejh.name,(
			SELECT name FROM `g_employee_job_history`
			WHERE employee_id = e.id 
				AND end_date <> ''
			ORDER BY end_date DESC 
			LIMIT 1
			))AS position            	
			FROM ". EMPLOYEE ." e               
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON esh.employee_id = ejh.employee_id AND ejh.end_date = ''
			WHERE 
				e.terminated_date BETWEEN " . Model::safeSql($query['date_from']) . " AND " . Model::safeSql($query['date_to']) . "
				" . $search . "
            ORDER BY e.terminated_date, CONCAT(e.lastname, ', ' , e.firstname) ASC
        ";    
		$result = Model::runSql($sql,true);		
		return $result;
	}
    
    public static function getDailyTimeRecordData($query, $add_query = '') {
    	$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}

		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			if($query['search_field'] == 'birthdate') {
				$query_search = $query['birthdate'];
			}else{
				$query_search = $query['search'];
			}
			$search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);				
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND e.department_company_structure_id =" . Model::safeSql($query['department_applied']);			
		}


        if ($query['project_site_id'] != '' && $query['project_site_id'] != 'all') {
            $search .= " AND ea.project_site_id =" . Model::safeSql($query['project_site_id']);
        }

        $sql = "
            SELECT e.employee_code, CONCAT(e.lastname, ', ' , e.firstname) as employee_name, cs.title as section,  ea.project_site_id, 
            COALESCE(esh.name,(
				SELECT name FROM `g_employee_subdivision_history`
				WHERE employee_id = e.id 
					AND end_date <> ''
				ORDER BY end_date DESC
				LIMIT 1
			))AS department_name, 
			COALESCE(ejh.name,(
			SELECT name FROM `g_employee_job_history`
			WHERE employee_id = e.id 
				AND end_date <> ''
			ORDER BY end_date DESC 
			LIMIT 1
			))AS position,     
				ea.is_present, is_paid, is_restday, is_holiday, is_leave, holiday_type, ea.scheduled_time_in, ea.scheduled_time_out, 
            	ea.date_attendance, ea.actual_time_in, ea.actual_time_out, ea.id as employee_attendance_id
			FROM ". G_EMPLOYEE_ATTENDANCE ." ea 
                LEFT JOIN " . EMPLOYEE . " e ON ea.employee_id = e.id  
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON esh.employee_id = ejh.employee_id AND ejh.end_date = ''
                LEFT JOIN " . G_COMPANY_STRUCTURE . " cs ON e.section_id = cs.id
			WHERE ea.date_attendance >= " . Model::safeSql($query['date_from']) . "
				AND ea.date_attendance <= " . Model::safeSql($query['date_to']) . "
				{$sql_add_query}
                " . $search . "
  				ORDER BY ea.date_attendance, e.lastname
        ";
               
		$result = Model::runSql($sql,true);		

		$records = array();
		
		foreach($result as $key => $value) {
			$is_restday_no_sched = 0;
			$time_in = "";
			$time_out = "";
			$remarks = "";

			$fp_logs = G_Attendance_Log_Finder::findByEmployeeCodeAndDate($value['employee_code'], $value['date_attendance']);

			if($value['is_present'] == 1 || $value['is_paid'] == 1 || $value['is_restday'] == 1 || $value['is_holiday'] == 1 || $value['is_leave'] == 1) {
                $time_in	= $value['actual_time_in'];
                $time_out 	= $value['actual_time_out'];

                if($value['is_holiday'] == 1) {
                	if($value['holiday_type'] == 1) {
                		$remarks = "LEGAL HOLIDAY";
                	}else if($value['holiday_type'] == 2){
                		$remarks = "SPECIAL HOLIDAY";
                	}
                }else if($value['is_restday'] == 1) {

	                if($fp_logs) {
	                    if($fp_logs->getType() == 'in') 
	                    {
	                        $time_in = $fp_logs->getTime();
	                        $remarks = " | NO OUT";
	                    } else {
	                        $time_out = $fp_logs->getTime();
	                        $remarks = " | NO IN";
	                    }
	                }                	

                	$remarks = "Restday" . $remarks;
                }else if($value['is_leave'] == 1 && $value['is_paid'] == 1) {
                	$remarks = "With approved leave";
                }else if($value['is_leave'] == 1) {
                	$remarks = "No paid leave";
                }

            }else{

				if($value['is_present'] == 0 && $value['is_paid'] == 0 && $value['actual_time_in'] != '' && $value['actual_time_out']  != ''){

	                $time_in	= $value['actual_time_in'];
	                $time_out 	= $value['actual_time_out'];
	                $remarks 	= "Incorrect Shift";

				} else {
	                $fp_logs = G_Attendance_Log_Finder::findByEmployeeCodeAndDate($value['employee_code'], $value['date_attendance']);
	                if($fp_logs) {
	                    if($fp_logs->getType() == 'in') 
	                    {
	                        $time_in = $fp_logs->getTime();
	                        $remarks = "NO OUT";
	                    } else {
	                        $time_out = $fp_logs->getTime();
	                        $remarks = "NO IN";

                            //check previous attendance
                            $e  = G_Employee_Finder::findByEmployeeCode($value['employee_code']);
                            if($e){
                                $prev_date = date('Y-m-d', strtotime($value['date_attendance'].'-1 day'));
                                $attendance = G_Attendance_Finder::findByEmployeeAndDate($e, $prev_date);
                                if($attendance){
                                    $t = $attendance->getTimesheet();
                                    $prev_timeout = $t->getTimeOut();

                                    $adjust_timeout = explode(":",$time_out);
                                    $adjust_timeout[2] = '00';
                                    $time_out = implode(":", $adjust_timeout);

                                    if($prev_timeout == $time_out){
                                        $time_out = "";
                                        $remarks = "ABSENT";
                                    }

                                }
                            }
                            

	                    } 
	                }else{
	                	$remarks = "ABSENT";
	                }					
				}

                
            } 

            if($value['is_restday'] == 1 && $value['scheduled_time_in'] == "" && $value['scheduled_time_out'] == "" && $value['is_holiday'] != 1) {
            	//$is_restday_no_sched = 1;
            }
           
           	if($is_restday_no_sched != 1) {
				$records[$value['employee_code']][$value['date_attendance']]['employee_code'] 		= $value['employee_code'];
				$records[$value['employee_code']][$value['date_attendance']]['employee_name'] 		= $value['employee_name'];
				$records[$value['employee_code']][$value['date_attendance']]['section'] 			= $value['section'];
				$records[$value['employee_code']][$value['date_attendance']]['department_name'] 	= $value['department_name'];
				$records[$value['employee_code']][$value['date_attendance']]['position'] 			= $value['position'];
				$records[$value['employee_code']][$value['date_attendance']]['date_attendance'] 	= $value['date_attendance'];
				$records[$value['employee_code']][$value['date_attendance']]['actual_time_in'] 		= $time_in;
				$records[$value['employee_code']][$value['date_attendance']]['actual_time_out'] 	= $time_out; 
				$records[$value['employee_code']][$value['date_attendance']]['remarks'] 			= $remarks;    
                $records[$value['employee_code']][$value['date_attendance']]['employee_attendance_id']  = $value['employee_attendance_id'];
                $records[$value['employee_code']][$value['date_attendance']]['project_site_id']  = $value['project_site_id']; 
            }

		}

		return $records;
	}
    
    public static function getDailyTimeRecordDataWithBreak($query, $add_query = '') {
    	$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}

		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			if($query['search_field'] == 'birthdate') {
				$query_search = $query['birthdate'];
			}else{
				$query_search = $query['search'];
			}
			$search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);				
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND e.department_company_structure_id =" . Model::safeSql($query['department_applied']);			
		}

        $sql = "
            SELECT e.employee_code, CONCAT(e.lastname, ', ' , e.firstname) as employee_name, cs.title as section,   
            COALESCE(esh.name,(
				SELECT name FROM `g_employee_subdivision_history`
				WHERE employee_id = e.id 
					AND end_date <> ''
				ORDER BY end_date DESC
				LIMIT 1
			))AS department_name, 
			COALESCE(ejh.name,(
			SELECT name FROM `g_employee_job_history`
			WHERE employee_id = e.id 
				AND end_date <> ''
			ORDER BY end_date DESC 
			LIMIT 1
			))AS position,     
				ea.is_present, is_paid, is_restday, is_holiday, is_leave, holiday_type, ea.scheduled_time_in, ea.scheduled_time_out, 
            	ea.date_attendance, ea.actual_time_in, ea.actual_time_out, ea.id as employee_attendance_id
			FROM ". G_EMPLOYEE_ATTENDANCE ." ea 
                LEFT JOIN " . EMPLOYEE . " e ON ea.employee_id = e.id  
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON esh.employee_id = ejh.employee_id AND ejh.end_date = ''
                LEFT JOIN " . G_COMPANY_STRUCTURE . " cs ON e.section_id = cs.id
			WHERE ea.date_attendance >= " . Model::safeSql($query['date_from']) . "
				AND ea.date_attendance <= " . Model::safeSql($query['date_to']) . "
				{$sql_add_query}
                " . $search . "
  				ORDER BY ea.date_attendance, e.lastname
        ";
               
		$result = Model::runSql($sql,true);		

		$records = array();
		$default_break_logs_headers = array(
			G_Employee_Break_Logs::TYPE_B1_OUT => 'Break1 OUT',
			G_Employee_Break_Logs::TYPE_B1_IN => 'Break1 IN',
			G_Employee_Break_Logs::TYPE_B2_OUT => 'Break2 OUT',
			G_Employee_Break_Logs::TYPE_B2_IN => 'Break2 IN',
			G_Employee_Break_Logs::TYPE_B3_OUT => 'Break3 OUT',
			G_Employee_Break_Logs::TYPE_B3_IN => 'Break3 IN',
			G_Employee_Break_Logs::TYPE_OT_B1_OUT => 'OT Break1 OUT',
			G_Employee_Break_Logs::TYPE_OT_B1_IN => 'OT Break1 IN',
			G_Employee_Break_Logs::TYPE_OT_B2_OUT => 'OT Break2 OUT',
			G_Employee_Break_Logs::TYPE_OT_B2_IN => 'OT Break2 IN'
		);
		$display_break_logs_headers = array();
		
		foreach($result as $key => $value) {
			$is_restday_no_sched = 0;
			$time_in = "";
			$time_out = "";
			$remarks = "";

			$fp_logs = G_Attendance_Log_Finder::findByEmployeeCodeAndDate($value['employee_code'], $value['date_attendance']);

			if($value['is_present'] == 1 || $value['is_paid'] == 1 || $value['is_restday'] == 1 || $value['is_holiday'] == 1 || $value['is_leave'] == 1) {
                $time_in	= $value['actual_time_in'];
                $time_out 	= $value['actual_time_out'];

                if($value['is_holiday'] == 1) {
                	if($value['holiday_type'] == 1) {
                		$remarks = "LEGAL HOLIDAY";
                	}else if($value['holiday_type'] == 2){
                		$remarks = "SPECIAL HOLIDAY";
                	}
                }else if($value['is_restday'] == 1) {

	                if($fp_logs) {
	                    if($fp_logs->getType() == 'in') 
	                    {
	                        $time_in = $fp_logs->getTime();
	                        $remarks = " | NO OUT";
	                    } else {
	                        $time_out = $fp_logs->getTime();
	                        $remarks = " | NO IN";
	                    }
	                }                	

                	$remarks = "Restday" . $remarks;
                }else if($value['is_leave'] == 1 && $value['is_paid'] == 1) {
                	$remarks = "With approved leave";
                }else if($value['is_leave'] == 1) {
                	$remarks = "No paid leave";
                }

            }else{

				if($value['is_present'] == 0 && $value['is_paid'] == 0 && $value['actual_time_in'] != '' && $value['actual_time_out']  != ''){

	                $time_in	= $value['actual_time_in'];
	                $time_out 	= $value['actual_time_out'];
	                $remarks 	= "Incorrect Shift";

				} else {
	                $fp_logs = G_Attendance_Log_Finder::findByEmployeeCodeAndDate($value['employee_code'], $value['date_attendance']);
	                if($fp_logs) {
	                    if($fp_logs->getType() == 'in') 
	                    {
	                        $time_in = $fp_logs->getTime();
	                        $remarks = "NO OUT";
	                    } else {
	                        $time_out = $fp_logs->getTime();
	                        $remarks = "NO IN";
	                    } 
	                }else{
	                	$remarks = "ABSENT";
	                }					
				}

                
            } 

            if($value['is_restday'] == 1 && $value['scheduled_time_in'] == "" && $value['scheduled_time_out'] == "" && $value['is_holiday'] != 1) {
            	//$is_restday_no_sched = 1;
			}
			
			$employee_break_logs = G_Employee_Break_logs_Summary_Finder::findByEmployeeAttendanceId($value['employee_attendance_id']);

           	if($is_restday_no_sched != 1) {
				$records[$value['employee_code']][$value['date_attendance']]['employee_code'] 			= $value['employee_code'];
				$records[$value['employee_code']][$value['date_attendance']]['employee_name'] 			= $value['employee_name'];
				$records[$value['employee_code']][$value['date_attendance']]['section'] 				= $value['section'];
				$records[$value['employee_code']][$value['date_attendance']]['department_name'] 		= $value['department_name'];
				$records[$value['employee_code']][$value['date_attendance']]['position'] 				= $value['position'];
				$records[$value['employee_code']][$value['date_attendance']]['date_attendance'] 		= $value['date_attendance'];
				$records[$value['employee_code']][$value['date_attendance']]['actual_time_in'] 			= $time_in;
				$records[$value['employee_code']][$value['date_attendance']]['actual_time_out'] 		= $time_out; 
				$records[$value['employee_code']][$value['date_attendance']]['remarks'] 				= $remarks;     
				$records[$value['employee_code']][$value['date_attendance']]['employee_attendance_id'] 	= $value['employee_attendance_id'];

				if ($employee_break_logs) {
					$records[$value['employee_code']][$value['date_attendance']]['employee_break_logs'][G_Employee_Break_Logs::TYPE_B1_OUT] = $employee_break_logs->getLogBreak1Out();
					$records[$value['employee_code']][$value['date_attendance']]['employee_break_logs'][G_Employee_Break_Logs::TYPE_B1_IN] = $employee_break_logs->getLogBreak1In();
					$records[$value['employee_code']][$value['date_attendance']]['employee_break_logs'][G_Employee_Break_Logs::TYPE_B2_OUT] = $employee_break_logs->getLogBreak2Out();
					$records[$value['employee_code']][$value['date_attendance']]['employee_break_logs'][G_Employee_Break_Logs::TYPE_B2_IN] = $employee_break_logs->getLogBreak2In();
					$records[$value['employee_code']][$value['date_attendance']]['employee_break_logs'][G_Employee_Break_Logs::TYPE_B3_OUT] = $employee_break_logs->getLogBreak3Out();
					$records[$value['employee_code']][$value['date_attendance']]['employee_break_logs'][G_Employee_Break_Logs::TYPE_B3_IN] = $employee_break_logs->getLogBreak3In();
					$records[$value['employee_code']][$value['date_attendance']]['employee_break_logs'][G_Employee_Break_Logs::TYPE_OT_B1_OUT] = $employee_break_logs->getLogOtBreak1Out();
					$records[$value['employee_code']][$value['date_attendance']]['employee_break_logs'][G_Employee_Break_Logs::TYPE_OT_B1_IN] = $employee_break_logs->getLogOtBreak1In();
					$records[$value['employee_code']][$value['date_attendance']]['employee_break_logs'][G_Employee_Break_Logs::TYPE_OT_B2_OUT] = $employee_break_logs->getLogOtBreak2Out();
					$records[$value['employee_code']][$value['date_attendance']]['employee_break_logs'][G_Employee_Break_Logs::TYPE_OT_B2_IN] = $employee_break_logs->getLogOtBreak2In();
				}
				else {
					$records[$value['employee_code']][$value['date_attendance']]['employee_break_logs'] = array();
				}
			}

			if ($employee_break_logs) {
				$available_break_types = G_Employee_Break_Logs_Summary_Helper::getAvailableBreakTypes($employee_break_logs);
			
				foreach ($available_break_types as $key => $available_break_type) {
					$display_break_logs_headers[$available_break_type] = $default_break_logs_headers[$available_break_type];
				}
			}

		}

		return array(
			'records' 						=> $records,
			'display_break_logs_headers' 	=> $display_break_logs_headers
		);
	}

	public static function getDailyTimeRecordSummarizedDataWithBreak($query, $add_query = '') {
    	$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}		
		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			if($query['search_field'] == 'birthdate') {
				$query_search = $query['birthdate'];
			}else{
				$query_search = $query['search'];
			}
			$search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);				
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}
        
        $sql = "
            SELECT e.employee_code, CONCAT(e.lastname, ', ' , e.firstname) as employee_name, cs.title as section, ea.is_present, 
            ea.is_restday, ea.is_holiday, ea.is_ob, ea.is_leave, ea.leave_id, ea.holiday_type, 
            COALESCE(esh.name,(
				SELECT name FROM `g_employee_subdivision_history`
				WHERE employee_id = e.id 
					AND end_date <> ''
				ORDER BY end_date DESC
				LIMIT 1
			))AS department_name, 
            COALESCE(ejh.name,(
			SELECT name FROM `g_employee_job_history`
			WHERE employee_id = e.id 
				AND end_date <> ''
			ORDER BY end_date DESC 
			LIMIT 1
			))AS position,    
            	ea.date_attendance, TIME_FORMAT(ea.actual_time_in, '%h:%i %p')AS actual_time_in, TIME_FORMAT(ea.actual_time_out, '%h:%i %p')AS actual_time_out, ea.id as employee_attendance_id, ea.total_breaktime_deductible_hours
			FROM ". G_EMPLOYEE_ATTENDANCE ." ea 
                LEFT JOIN " . EMPLOYEE . " e ON ea.employee_id = e.id  
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON esh.employee_id = ejh.employee_id AND ejh.end_date = ''
                LEFT JOIN " . G_COMPANY_STRUCTURE . " cs ON e.section_id = cs.id
			WHERE ea.date_attendance >= " . Model::safeSql($query['date_from']) . "
				{$sql_add_query}
                AND ea.date_attendance <= " . Model::safeSql($query['date_to']) . "
                " . $search . "
            ORDER BY CONCAT(e.lastname, ', ' , e.firstname) ,ea.date_attendance ASC
  	
        ";              
		$result = Model::runSql($sql,true);		

		$records = array();
		$default_break_logs_headers = array(
			G_Employee_Break_Logs::TYPE_B1_OUT => 'Break1 OUT',
			G_Employee_Break_Logs::TYPE_B1_IN => 'Break1 IN',
			G_Employee_Break_Logs::TYPE_B2_OUT => 'Break2 OUT',
			G_Employee_Break_Logs::TYPE_B2_IN => 'Break2 IN',
			G_Employee_Break_Logs::TYPE_B3_OUT => 'Break3 OUT',
			G_Employee_Break_Logs::TYPE_B3_IN => 'Break3 IN',
			G_Employee_Break_Logs::TYPE_OT_B1_OUT => 'OT Break1 OUT',
			G_Employee_Break_Logs::TYPE_OT_B1_IN => 'OT Break1 IN',
			G_Employee_Break_Logs::TYPE_OT_B2_OUT => 'OT Break2 OUT',
			G_Employee_Break_Logs::TYPE_OT_B2_IN => 'OT Break2 IN'
		);
		$display_break_logs_headers = array();

		foreach($result as $key => $value) {
			$records[$key] = $value;

			$employee_break_logs = G_Employee_Break_logs_Summary_Finder::findByEmployeeAttendanceId($value['employee_attendance_id']);

			if ($employee_break_logs) {
				$records[$key]['employee_break_logs'][G_Employee_Break_Logs::TYPE_B1_OUT] = $employee_break_logs->getLogBreak1Out();
				$records[$key]['employee_break_logs'][G_Employee_Break_Logs::TYPE_B1_IN] = $employee_break_logs->getLogBreak1In();
				$records[$key]['employee_break_logs'][G_Employee_Break_Logs::TYPE_B2_OUT] = $employee_break_logs->getLogBreak2Out();
				$records[$key]['employee_break_logs'][G_Employee_Break_Logs::TYPE_B2_IN] = $employee_break_logs->getLogBreak2In();
				$records[$key]['employee_break_logs'][G_Employee_Break_Logs::TYPE_B3_OUT] = $employee_break_logs->getLogBreak3Out();
				$records[$key]['employee_break_logs'][G_Employee_Break_Logs::TYPE_B3_IN] = $employee_break_logs->getLogBreak3In();
				$records[$key]['employee_break_logs'][G_Employee_Break_Logs::TYPE_OT_B1_OUT] = $employee_break_logs->getLogOtBreak1Out();
				$records[$key]['employee_break_logs'][G_Employee_Break_Logs::TYPE_OT_B1_IN] = $employee_break_logs->getLogOtBreak1In();
				$records[$key]['employee_break_logs'][G_Employee_Break_Logs::TYPE_OT_B2_OUT] = $employee_break_logs->getLogOtBreak2Out();
				$records[$key]['employee_break_logs'][G_Employee_Break_Logs::TYPE_OT_B2_IN] = $employee_break_logs->getLogOtBreak2In();
				$records[$key]['employee_break_logs']['total_break_hrs'] = $employee_break_logs->getTotalBreakHrs();
			}
			else {
				$records[$key]['employee_break_logs'] = array();
			}

			if ($employee_break_logs) {
				$available_break_types = G_Employee_Break_Logs_Summary_Helper::getAvailableBreakTypes($employee_break_logs);
			
				foreach ($available_break_types as $key => $available_break_type) {
					$display_break_logs_headers[$available_break_type] = $default_break_logs_headers[$available_break_type];
				}
			}
		}
		
		return array(
			'records' 						=> $records,
			'display_break_logs_headers' 	=> $display_break_logs_headers
		);
	}

	public static function getDailyTimeRecordSummarizedData($query, $add_query = '') {
    	$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}		
		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			if($query['search_field'] == 'birthdate') {
				$query_search = $query['birthdate'];
			}else{
				$query_search = $query['search'];
			}
			$search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);				
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}
        
        if ($query['project_site_id'] != '' && $query['project_site_id'] != 'all') {
            $search .= " AND ea.project_site_id =" . Model::safeSql($query['project_site_id']);
        }

        $sql = "
            SELECT e.employee_code, CONCAT(e.lastname, ', ' , e.firstname) as employee_name, cs.title as section, ea.is_present, 
            ea.is_restday, ea.is_holiday, ea.is_ob, ea.is_leave, ea.leave_id, ea.holiday_type, ea.project_site_id,
            COALESCE(esh.name,(
				SELECT name FROM `g_employee_subdivision_history`
				WHERE employee_id = e.id 
					AND end_date <> ''
				ORDER BY end_date DESC
				LIMIT 1
			))AS department_name, 
            COALESCE(ejh.name,(
			SELECT name FROM `g_employee_job_history`
			WHERE employee_id = e.id 
				AND end_date <> ''
			ORDER BY end_date DESC 
			LIMIT 1
			))AS position,    
            	ea.date_attendance, TIME_FORMAT(ea.actual_time_in, '%h:%i %p')AS actual_time_in, TIME_FORMAT(ea.actual_time_out, '%h:%i %p')AS actual_time_out,ea.id as employee_attendance_id, ea.total_breaktime_deductible_hours
			FROM ". G_EMPLOYEE_ATTENDANCE ." ea 
                LEFT JOIN " . EMPLOYEE . " e ON ea.employee_id = e.id  
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON esh.employee_id = ejh.employee_id AND ejh.end_date = ''
                LEFT JOIN " . G_COMPANY_STRUCTURE . " cs ON e.section_id = cs.id
			WHERE ea.date_attendance >= " . Model::safeSql($query['date_from']) . "
				{$sql_add_query}
                AND ea.date_attendance <= " . Model::safeSql($query['date_to']) . "
                " . $search . "
            ORDER BY CONCAT(e.lastname, ', ' , e.firstname) ,ea.date_attendance ASC
  	
        ";              
		$result = Model::runSql($sql,true);		
		return $result;
	}


	public static function getDailyTimeRecordIncompleteBreakLogs($query, $add_query = '') {
    	$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}

		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			if($query['search_field'] == 'birthdate') {
				$query_search = $query['birthdate'];
			}else{
				$query_search = $query['search'];
			}
			$search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);				
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND e.department_company_structure_id =" . Model::safeSql($query['department_applied']);			
		}

        if ($query['project_site_id'] != '' && $query['project_site_id'] != 'all') {
            $search .= " AND ea.project_site_id =" . Model::safeSql($query['project_site_id']);
        }

        $sql = "
            SELECT e.id as employee_id, e.employee_code, CONCAT(e.lastname, ', ' , e.firstname) as employee_name, cs.title as section, ea.project_site_id,  
            COALESCE(esh.name,(
				SELECT name FROM `g_employee_subdivision_history`
				WHERE employee_id = e.id 
					AND end_date <> ''
				ORDER BY end_date DESC
				LIMIT 1
			))AS department_name, 
			COALESCE(ejh.name,(
			SELECT name FROM `g_employee_job_history`
			WHERE employee_id = e.id 
				AND end_date <> ''
			ORDER BY end_date DESC 
			LIMIT 1
			))AS position,     
				ea.is_present, is_paid, is_restday, is_holiday, is_leave, holiday_type, ea.scheduled_time_in, ea.scheduled_time_out, 
            	ea.date_attendance, ea.actual_time_in, ea.actual_time_out, ea.id as employee_attendance_id
			FROM ". G_EMPLOYEE_ATTENDANCE ." ea 
                LEFT JOIN " . EMPLOYEE . " e ON ea.employee_id = e.id  
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON esh.employee_id = ejh.employee_id AND ejh.end_date = ''
                LEFT JOIN " . G_COMPANY_STRUCTURE . " cs ON e.section_id = cs.id
			WHERE ea.date_attendance >= " . Model::safeSql($query['date_from']) . "
				AND ea.date_attendance <= " . Model::safeSql($query['date_to']) . "
				{$sql_add_query}
                " . $search . "
  				ORDER BY ea.date_attendance, e.lastname
        ";
			   
		$result = Model::runSql($sql,true);		

		$break_logs  = G_Employee_Break_Logs_Helper::sqlGetAllLogsNotTransferredByDateRange($query['date_from'], $query['date_to']);   

		usort($break_logs,function($first,$second)
		{
			return (($first['date'] .' '.$first['time'])  >  ($second['date'] .' '.$second['time']));
		});	

		// Break logs on timesheet
		foreach( $break_logs as $break_log )
		{
			$employee_id = $break_log['employee_id'];
			$emp_code = $break_log['employee_code'];
			$date     = date("Y-m-d",strtotime($break_log['date']));
			$type     = strtolower($break_log['type']);
			$time     = date("H:i:s",strtotime($break_log['time']));

			$breaks[$emp_code]['breaks'][$type][$date . ' ' .$time] = (object)['id' => $break_log['id'], 'datetime' => ($date . ' ' .$time)];
		}

		$records = array();
		$matched_breaks_count = [];
		foreach($result as $key => $value) {
			$is_restday_no_sched = 0;
			$time_in = "";
			$time_out = "";
			$remarks = "";
			
			$employee_break_logs = $breaks[$value['employee_code']];
		
			if($employee_break_logs)
			{
				if($value['is_present'])
				{
					$log_breaks = $employee_break_logs['breaks'];
				
					$break_outs = $log_breaks[G_Employee_Break_Logs::TYPE_BOUT];
					$break_ins = $log_breaks[G_Employee_Break_Logs::TYPE_BIN];
					$break_outs_keys = array_keys($break_outs);
					$break_ins_keys = array_keys($break_ins);
	
					$ot_break_outs = $log_breaks[G_Employee_Break_Logs::TYPE_BOT_OUT];
					$ot_break_in = $log_breaks[G_Employee_Break_Logs::TYPE_BOT_IN];
					$ot_break_outs_keys = array_keys($ot_break_outs);
					$ot_break_in_keys = array_keys($ot_break_in);
	
					$break_pairs = G_Attendance_Log_Helper::getLogPairs($break_outs, $break_ins, $break_outs_keys, $break_ins_keys);
					$ot_break_pairs = G_Attendance_Log_Helper::getLogPairs($ot_break_outs, $ot_break_in, $ot_break_outs_keys, $ot_break_in_keys);
	

					$date = date('Y-m-d', strtotime($value['date_attendance'] . ' ' . $value['actual_time_in']));
					$time_in = date('Y-m-d H:i:s', strtotime($value['date_attendance'] . ' ' . $value['actual_time_in']));
					$estimated_tomorrow_time_in = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($value['date_attendance'] . ' ' . $value['schedule_time_in'])));

					$matched_breaks = [];
					foreach(array_merge($break_pairs, $ot_break_pairs) as $break_pair)
					{
						if(isset($break_pair->from))
						{
							$datetime = $break_pair->from->datetime;
							if (($datetime >= $time_in) && ($datetime <= $estimated_tomorrow_time_in))
							{
								$matched_breaks[] = $break_pair;
							}
						}
						else
						{
							$datetime = $break_pair->to->datetime;
							if (($datetime >= $time_in) && ($datetime <= $estimated_tomorrow_time_in))
							{
								$matched_breaks[] = $break_pair;
							}
						}
					}
					
					$matched_breaks_count[] = count($matched_breaks);

					$employee  = G_Employee_Finder::findByEmployeeCode($value['employee_code']);
					$attendance = G_Attendance_Helper::generateAttendance($employee, $date, true);
					$t = $attendance->getTimesheet();

					// $breaktime_data = G_Break_Time_Schedule_Details_Helper::sqlGetAllBreaktimeSchedulesByEmployeeIdAndScheduleInAndScheduleOut($value['employee_id'], $value['scheduled_time_in'], $value['scheduled_time_out']);
					$breaktime_data = G_Break_Time_Schedule_Details_Helper::sqlGetAllBreaktimeSchedulesByEmployeeIdAndScheduleInAndScheduleOut($employee->getId(), $t->getScheduledTimeIn(), $t->getScheduledTimeOut());
	
					if(count($matched_breaks) > 0)
					{
						foreach($breaktime_data as $btkey => $schedule_break)
						{
							$is_required_logs = $schedule_break['to_required_logs'];

							$log_break = $matched_breaks[$btkey];
							$log_break_from = isset($log_break->from) ? $log_break->from->datetime : null;
							$log_break_to = isset($log_break->to) ? $log_break->to->datetime : null;
							
							$break_from = date('Y-m-d H:i:s',strtotime($date . ', ' . $schedule_break['break_in']));
							if($break_from < $log_break->from->datetime)
							{
								$break_from = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($date . ', ' . $schedule_break['break_in'])));
							}

							$break_to = date('Y-m-d H:i:s',strtotime($date . ', ' . $schedule_break['break_out']));
							if($break_to < $log_break->from->datetime)
							{
								$break_to = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($date . ', ' . $schedule_break['break_out'])));
							}

							if($is_required_logs)
							{
								// if(isset($log_break_from))
								// {
								// 	if($log_break_from < $break_from)
								// 	{
								// 		$early_break_out_log_ids[] = $log_break->from->id;
								// 	}
								// }
								
								// if(isset($log_break_to))
								// {
								// 	if($log_break_to > $break_to)
								// 	{
								// 		$late_break_in_log_ids[] = $log_break->to->id;
								// 	}
								// }

								if(!isset($log_break->from))
								{
									if(isset($log_break->to))
									{
										// $incomplete_break_log_ids[] = $log_break->to->id;
										// Incomplete Break logs
										if(isset($remarks) && !empty($remarks))
										{
											$remarks = " | INCOMPLETE BREAK LOGS";

										}
										else
										{
											$remarks = "INCOMPLETE BREAK LOGS";
										}
									}
								}

								if(!isset($log_break->to))
								{
									if(isset($log_break->from))
									{
										// $incomplete_break_log_ids[] = $log_break->from->id;
										if(isset($remarks) && !empty($remarks))
										{
											$remarks = " | INCOMPLETE BREAK LOGS";

										}
										else
										{
											$remarks = "INCOMPLETE BREAK LOGS";
										}
									}
								}
								
								// if(!isset($log_break_from) && !isset($log_break_to))
								// {
								// 	$no_break_logs_ids[] = $pair->from->id;
								// }

							}
							
						}

						$fp_logs = G_Attendance_Log_Finder::findByEmployeeCodeAndDate($value['employee_code'], $value['date_attendance']);
	
						if($value['is_present'] == 1 || $value['is_paid'] == 1 || $value['is_restday'] == 1 || $value['is_holiday'] == 1 || $value['is_leave'] == 1) {
							$time_in	= $value['actual_time_in'];
							$time_out 	= $value['actual_time_out'];
		
							if($value['is_holiday'] == 1) {
								if($value['holiday_type'] == 1) {
									$remarks = "LEGAL HOLIDAY";
								}else if($value['holiday_type'] == 2){
									$remarks = "SPECIAL HOLIDAY";
								}
							}else if($value['is_restday'] == 1) {
		
								if($fp_logs) {
									if($fp_logs->getType() == 'in') 
									{
										$time_in = $fp_logs->getTime();
										$remarks = " | NO OUT";
									} else {
										$time_out = $fp_logs->getTime();
										$remarks = " | NO IN";
									}
								}                	
		
								$remarks = "Restday" . $remarks;
							}else if($value['is_leave'] == 1 && $value['is_paid'] == 1) {
								$remarks = "With approved leave";
							}else if($value['is_leave'] == 1) {
								$remarks = "No paid leave";
							}
		
						}else{
		
							if($value['is_present'] == 0 && $value['is_paid'] == 0 && $value['actual_time_in'] != '' && $value['actual_time_out']  != ''){
		
								$time_in	= $value['actual_time_in'];
								$time_out 	= $value['actual_time_out'];
								$remarks 	= "Incorrect Shift";
		
							} else {
								$fp_logs = G_Attendance_Log_Finder::findByEmployeeCodeAndDate($value['employee_code'], $value['date_attendance']);
								if($fp_logs) {
									if($fp_logs->getType() == 'in') 
									{
										$time_in = $fp_logs->getTime();
										$remarks = "NO OUT";
									} else {
										$time_out = $fp_logs->getTime();
										$remarks = "NO IN";
									} 
								}else{
									$remarks = "ABSENT";
								}					
							}
		
							
						} 
		
						if($value['is_restday'] == 1 && $value['scheduled_time_in'] == "" && $value['scheduled_time_out'] == "" && $value['is_holiday'] != 1) {
							//$is_restday_no_sched = 1;
						}
					
						if($is_restday_no_sched != 1) {
							$records[$value['employee_code']][$value['date_attendance']]['employee_code'] 		= $value['employee_code'];
							$records[$value['employee_code']][$value['date_attendance']]['employee_name'] 		= $value['employee_name'];
							$records[$value['employee_code']][$value['date_attendance']]['section'] 			= $value['section'];
							$records[$value['employee_code']][$value['date_attendance']]['department_name'] 	= $value['department_name'];
							$records[$value['employee_code']][$value['date_attendance']]['position'] 			= $value['position'];
							$records[$value['employee_code']][$value['date_attendance']]['date_attendance'] 	= $value['date_attendance'];
							$records[$value['employee_code']][$value['date_attendance']]['actual_time_in'] 		= $time_in;
							$records[$value['employee_code']][$value['date_attendance']]['actual_time_out'] 	= $time_out; 
							$records[$value['employee_code']][$value['date_attendance']]['remarks'] 			= $remarks;     
							$records[$value['employee_code']][$value['date_attendance']]['matched_breaks'] 		= $matched_breaks;  
                            $records[$value['employee_code']][$value['date_attendance']]['employee_attendance_id']       = $value['employee_attendance_id'];   
                            $records[$value['employee_code']][$value['date_attendance']]['project_site_id']    =  $value['project_site_id'];   
						}
					}

					
				}
				
			}
		}

		return [$records,  max($matched_breaks_count)];
	}
    
	public static function getDailyTimeRecordNoBreakLogs($query, $add_query = '') {
    	$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}

		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			if($query['search_field'] == 'birthdate') {
				$query_search = $query['birthdate'];
			}else{
				$query_search = $query['search'];
			}
			$search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);				
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND e.department_company_structure_id =" . Model::safeSql($query['department_applied']);			
		}

        if ($query['project_site_id'] != '' && $query['project_site_id'] != 'all') {
            $search .= " AND ea.project_site_id =" . Model::safeSql($query['project_site_id']);
        }

        $sql = "
            SELECT e.id as employee_id, e.employee_code, CONCAT(e.lastname, ', ' , e.firstname) as employee_name, cs.title as section, ea.project_site_id,  
            COALESCE(esh.name,(
				SELECT name FROM `g_employee_subdivision_history`
				WHERE employee_id = e.id 
					AND end_date <> ''
				ORDER BY end_date DESC
				LIMIT 1
			))AS department_name, 
			COALESCE(ejh.name,(
			SELECT name FROM `g_employee_job_history`
			WHERE employee_id = e.id 
				AND end_date <> ''
			ORDER BY end_date DESC 
			LIMIT 1
			))AS position,     
				ea.is_present, is_paid, is_restday, is_holiday, is_leave, holiday_type, ea.scheduled_time_in, ea.scheduled_time_out, 
            	ea.date_attendance, ea.actual_time_in, ea.actual_time_out, ea.id as employee_attendance_id
			FROM ". G_EMPLOYEE_ATTENDANCE ." ea 
                LEFT JOIN " . EMPLOYEE . " e ON ea.employee_id = e.id  
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON esh.employee_id = ejh.employee_id AND ejh.end_date = ''
                LEFT JOIN " . G_COMPANY_STRUCTURE . " cs ON e.section_id = cs.id
			WHERE ea.date_attendance >= " . Model::safeSql($query['date_from']) . "
				AND ea.date_attendance <= " . Model::safeSql($query['date_to']) . "
				{$sql_add_query}
                " . $search . "
  				ORDER BY ea.date_attendance, e.lastname
        ";
			   
		$result = Model::runSql($sql,true);		

		$break_logs  = G_Employee_Break_Logs_Helper::sqlGetAllLogsNotTransferredByDateRange($query['date_from'], $query['date_to']);   

		usort($break_logs,function($first,$second)
		{
			return (($first['date'] .' '.$first['time'])  >  ($second['date'] .' '.$second['time']));
		});	

		// Break logs on timesheet
		foreach( $break_logs as $break_log )
		{
			$employee_id = $break_log['employee_id'];
			$emp_code = $break_log['employee_code'];
			$date     = date("Y-m-d",strtotime($break_log['date']));
			$type     = strtolower($break_log['type']);
			$time     = date("H:i:s",strtotime($break_log['time']));

			$breaks[$emp_code]['breaks'][$type][$date . ' ' .$time] = (object)['id' => $break_log['id'], 'datetime' => ($date . ' ' .$time)];
		}

		$records = array();
		$matched_breaks_count = [];
		foreach($result as $key => $value) {
			$is_restday_no_sched = 0;
			$time_in = "";
			$time_out = "";
			$remarks = "";
			
			$employee_break_logs = $breaks[$value['employee_code']];
		
			// if($employee_break_logs)
			if(true)
			{
				if($value['is_present'])
				{
					$log_breaks = $employee_break_logs['breaks'];
				
					$break_outs = $log_breaks[G_Employee_Break_Logs::TYPE_BOUT];
					$break_ins = $log_breaks[G_Employee_Break_Logs::TYPE_BIN];
					$break_outs_keys = array_keys($break_outs);
					$break_ins_keys = array_keys($break_ins);
	
					$ot_break_outs = $log_breaks[G_Employee_Break_Logs::TYPE_BOT_OUT];
					$ot_break_in = $log_breaks[G_Employee_Break_Logs::TYPE_BOT_IN];
					$ot_break_outs_keys = array_keys($ot_break_outs);
					$ot_break_in_keys = array_keys($ot_break_in);

					$break_pairs = G_Attendance_Log_Helper::getLogPairs($break_outs, $break_ins, $break_outs_keys, $break_ins_keys);
					$ot_break_pairs = G_Attendance_Log_Helper::getLogPairs($ot_break_outs, $ot_break_in, $ot_break_outs_keys, $ot_break_in_keys);
	

					$date = date('Y-m-d', strtotime($value['date_attendance'] . ' ' . $value['actual_time_in']));
					$time_in = date('Y-m-d H:i:s', strtotime($value['date_attendance'] . ' ' . $value['actual_time_in']));
					$estimated_tomorrow_time_in = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($value['date_attendance'] . ' ' . $value['schedule_time_in'])));

					$matched_breaks = [];
					foreach(array_merge($break_pairs, $ot_break_pairs) as $break_pair)
					{
						if(isset($break_pair->from))
						{
							$datetime = $break_pair->from->datetime;
							if (($datetime >= $time_in) && ($datetime <= $estimated_tomorrow_time_in))
							{
								$matched_breaks[] = $break_pair;
							}
						}
						else
						{
							$datetime = $break_pair->to->datetime;
							if (($datetime >= $time_in) && ($datetime <= $estimated_tomorrow_time_in))
							{
								$matched_breaks[] = $break_pair;
							}
						}
					}
					
					$matched_breaks_count[] = count($matched_breaks);

					$employee  = G_Employee_Finder::findByEmployeeCode($value['employee_code']);
					$attendance = G_Attendance_Helper::generateAttendance($employee, $date, true);
					$t = $attendance->getTimesheet();

					// $breaktime_data = G_Break_Time_Schedule_Details_Helper::sqlGetAllBreaktimeSchedulesByEmployeeIdAndScheduleInAndScheduleOut($value['employee_id'], $value['scheduled_time_in'], $value['scheduled_time_out']);
					$breaktime_data = G_Break_Time_Schedule_Details_Helper::sqlGetAllBreaktimeSchedulesByEmployeeIdAndScheduleInAndScheduleOut($employee->getId(), $t->getScheduledTimeIn(), $t->getScheduledTimeOut());
	
					// if(count($matched_breaks) > 0)
					if(true)
					{
						
						foreach($breaktime_data as $btkey => $schedule_break)
						{
							$is_required_logs = $schedule_break['to_required_logs'];
							
							$log_break_from = null;
							$log_break_to = null;

							if(count($matched_breaks) > 0)
							{

								$log_break = $matched_breaks[$btkey];
						
								$log_break_from = isset($log_break->from) ? $log_break->from->datetime : null;
								$log_break_to = isset($log_break->to) ? $log_break->to->datetime : null;
							
							}

							// $break_from = date('Y-m-d H:i:s',strtotime($date . ', ' . $schedule_break['break_in']));
							// if($break_from < $log_break->from->datetime)
							// {
							// 	$break_from = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($date . ', ' . $schedule_break['break_in'])));
							// }

							// $break_to = date('Y-m-d H:i:s',strtotime($date . ', ' . $schedule_break['break_out']));
							// if($break_to < $log_break->from->datetime)
							// {
							// 	$break_to = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($date . ', ' . $schedule_break['break_out'])));
							// }

							if($is_required_logs)
							{
								// if(isset($log_break_from))
								// {
								// 	if($log_break_from < $break_from)
								// 	{
								// 		$early_break_out_log_ids[] = $log_break->from->id;
								// 	}
								// }
								
								// if(isset($log_break_to))
								// {
								// 	if($log_break_to > $break_to)
								// 	{
								// 		$late_break_in_log_ids[] = $log_break->to->id;
								// 	}
								// }

								// if(!isset($log_break->from))
								// {
								// 	if(isset($log_break->to))
								// 	{
								// 		// $incomplete_break_log_ids[] = $log_break->to->id;
								// 		// Incomplete Break logs
								// 		if(isset($remarks) && !empty($remarks))
								// 		{
								// 			$remarks = " | INCOMPLETE BREAK LOGS";

								// 		}
								// 		else
								// 		{
								// 			$remarks = "INCOMPLETE BREAK LOGS";
								// 		}
								// 	}
								// }

								// if(!isset($log_break->to))
								// {
								// 	if(isset($log_break->from))
								// 	{
								// 		// $incomplete_break_log_ids[] = $log_break->from->id;
								// 		if(isset($remarks) && !empty($remarks))
								// 		{
								// 			$remarks = " | INCOMPLETE BREAK LOGS";

								// 		}
								// 		else
								// 		{
								// 			$remarks = "INCOMPLETE BREAK LOGS";
								// 		}
								// 	}
								// }
								
								if(!isset($log_break_from) && !isset($log_break_to))
								{
									// $no_break_logs_ids[] = $pair->from->id;
									if(isset($remarks) && !empty($remarks))
									{
										$remarks = " | NO BREAK LOGS";
									}
									else
									{
										$remarks = "NO BREAK LOGS";
									}

									
									$fp_logs = G_Attendance_Log_Finder::findByEmployeeCodeAndDate($value['employee_code'], $value['date_attendance']);
				
									if($value['is_present'] == 1 || $value['is_paid'] == 1 || $value['is_restday'] == 1 || $value['is_holiday'] == 1 || $value['is_leave'] == 1) {
										$time_in	= $value['actual_time_in'];
										$time_out 	= $value['actual_time_out'];
					
										if($value['is_holiday'] == 1) {
											if($value['holiday_type'] == 1) {
												// $remarks = "LEGAL HOLIDAY";
											}else if($value['holiday_type'] == 2){
												// $remarks = "SPECIAL HOLIDAY";
											}
										}else if($value['is_restday'] == 1) {
					
											if($fp_logs) {
												if($fp_logs->getType() == 'in') 
												{
													$time_in = $fp_logs->getTime();
													// $remarks = " | NO OUT";
												} else {
													$time_out = $fp_logs->getTime();
													// $remarks = " | NO IN";
												}
											}                	
					
											// // $remarks = "Restday" . $remarks;
										}else if($value['is_leave'] == 1 && $value['is_paid'] == 1) {
											// $remarks = "With approved leave";
										}else if($value['is_leave'] == 1) {
											// $remarks = "No paid leave";
										}
					
									}else{
					
										if($value['is_present'] == 0 && $value['is_paid'] == 0 && $value['actual_time_in'] != '' && $value['actual_time_out']  != ''){
					
											$time_in	= $value['actual_time_in'];
											$time_out 	= $value['actual_time_out'];
											// $remarks 	= "Incorrect Shift";
					
										} else {
											$fp_logs = G_Attendance_Log_Finder::findByEmployeeCodeAndDate($value['employee_code'], $value['date_attendance']);
											if($fp_logs) {
												if($fp_logs->getType() == 'in') 
												{
													$time_in = $fp_logs->getTime();
													// $remarks = "NO OUT";
												} else {
													$time_out = $fp_logs->getTime();
													// $remarks = "NO IN";
												} 
											}else{
												// $remarks = "ABSENT";
											}					
										}
					
										
									} 
					
									if($value['is_restday'] == 1 && $value['scheduled_time_in'] == "" && $value['scheduled_time_out'] == "" && $value['is_holiday'] != 1) {
										//$is_restday_no_sched = 1;
									}
								
									if($is_restday_no_sched != 1) {
										$records[$value['employee_code']][$value['date_attendance']]['employee_code'] 		= $value['employee_code'];
										$records[$value['employee_code']][$value['date_attendance']]['employee_name'] 		= $value['employee_name'];
										$records[$value['employee_code']][$value['date_attendance']]['section'] 			= $value['section'];
										$records[$value['employee_code']][$value['date_attendance']]['department_name'] 	= $value['department_name'];
										$records[$value['employee_code']][$value['date_attendance']]['position'] 			= $value['position'];
										$records[$value['employee_code']][$value['date_attendance']]['date_attendance'] 	= $value['date_attendance'];
										$records[$value['employee_code']][$value['date_attendance']]['actual_time_in'] 		= $time_in;
										$records[$value['employee_code']][$value['date_attendance']]['actual_time_out'] 	= $time_out; 
										$records[$value['employee_code']][$value['date_attendance']]['remarks'] 			= $remarks;     
										$records[$value['employee_code']][$value['date_attendance']]['matched_breaks'] 		= $matched_breaks;  
                                        $records[$value['employee_code']][$value['date_attendance']]['employee_attendance_id']      = $value['employee_attendance_id'];
                                         $records[$value['employee_code']][$value['date_attendance']]['project_site_id']      = $value['project_site_id'];
                                  
									}

								}

							}
							
						}

					}

					
				}
				
			}
		}

		return [$records,  max($matched_breaks_count)];
	}
	
	public static function getDailyTimeRecordEarlyBreakOut($query, $add_query = '') {
    	$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}

		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			if($query['search_field'] == 'birthdate') {
				$query_search = $query['birthdate'];
			}else{
				$query_search = $query['search'];
			}
			$search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);				
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND e.department_company_structure_id =" . Model::safeSql($query['department_applied']);			
		}

        if ($query['project_site_id'] != '' && $query['project_site_id'] != 'all') {
            $search .= " AND ea.project_site_id =" . Model::safeSql($query['project_site_id']);
        }

        $sql = "
            SELECT e.id as employee_id, e.employee_code, CONCAT(e.lastname, ', ' , e.firstname) as employee_name, cs.title as section,  ea.project_site_id, 
            COALESCE(esh.name,(
				SELECT name FROM `g_employee_subdivision_history`
				WHERE employee_id = e.id 
					AND end_date <> ''
				ORDER BY end_date DESC
				LIMIT 1
			))AS department_name, 
			COALESCE(ejh.name,(
			SELECT name FROM `g_employee_job_history`
			WHERE employee_id = e.id 
				AND end_date <> ''
			ORDER BY end_date DESC 
			LIMIT 1
			))AS position,     
				ea.is_present, is_paid, is_restday, is_holiday, is_leave, holiday_type, ea.scheduled_time_in, ea.scheduled_time_out, 
            	ea.date_attendance, ea.actual_time_in, ea.actual_time_out, ea.id as employee_attendance_id
			FROM ". G_EMPLOYEE_ATTENDANCE ." ea 
                LEFT JOIN " . EMPLOYEE . " e ON ea.employee_id = e.id  
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON esh.employee_id = ejh.employee_id AND ejh.end_date = ''
                LEFT JOIN " . G_COMPANY_STRUCTURE . " cs ON e.section_id = cs.id
			WHERE ea.date_attendance >= " . Model::safeSql($query['date_from']) . "
				AND ea.date_attendance <= " . Model::safeSql($query['date_to']) . "
				{$sql_add_query}
                " . $search . "
  				ORDER BY ea.date_attendance, e.lastname
        ";
			   
		$result = Model::runSql($sql,true);		

		$break_logs  = G_Employee_Break_Logs_Helper::sqlGetAllLogsNotTransferredByDateRange($query['date_from'], $query['date_to']);   

		usort($break_logs,function($first,$second)
		{
			return (($first['date'] .' '.$first['time'])  >  ($second['date'] .' '.$second['time']));
		});	

		// Break logs on timesheet
		foreach( $break_logs as $break_log )
		{
			$employee_id = $break_log['employee_id'];
			$emp_code = $break_log['employee_code'];
			$date     = date("Y-m-d",strtotime($break_log['date']));
			$type     = strtolower($break_log['type']);
			$time     = date("H:i:s",strtotime($break_log['time']));

			$breaks[$emp_code]['breaks'][$type][$date . ' ' .$time] = (object)['id' => $break_log['id'], 'datetime' => ($date . ' ' .$time)];
		}

		$records = array();
		$matched_breaks_count = [];
		foreach($result as $key => $value) {
			$is_restday_no_sched = 0;
			$time_in = "";
			$time_out = "";
			$remarks = "";
			
			$employee_break_logs = $breaks[$value['employee_code']];
		
			if($employee_break_logs)
			// if(true)
			{
				if($value['is_present'])
				{
					$log_breaks = $employee_break_logs['breaks'];
				
					$break_outs = $log_breaks[G_Employee_Break_Logs::TYPE_BOUT];
					$break_ins = $log_breaks[G_Employee_Break_Logs::TYPE_BIN];
					$break_outs_keys = array_keys($break_outs);
					$break_ins_keys = array_keys($break_ins);
	
					$ot_break_outs = $log_breaks[G_Employee_Break_Logs::TYPE_BOT_OUT];
					$ot_break_in = $log_breaks[G_Employee_Break_Logs::TYPE_BOT_IN];
					$ot_break_outs_keys = array_keys($ot_break_outs);
					$ot_break_in_keys = array_keys($ot_break_in);

					$break_pairs = G_Attendance_Log_Helper::getLogPairs($break_outs, $break_ins, $break_outs_keys, $break_ins_keys);
					$ot_break_pairs = G_Attendance_Log_Helper::getLogPairs($ot_break_outs, $ot_break_in, $ot_break_outs_keys, $ot_break_in_keys);
	

					$date = date('Y-m-d', strtotime($value['date_attendance'] . ' ' . $value['actual_time_in']));
					$time_in = date('Y-m-d H:i:s', strtotime($value['date_attendance'] . ' ' . $value['actual_time_in']));
					$estimated_tomorrow_time_in = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($value['date_attendance'] . ' ' . $value['schedule_time_in'])));

					$matched_breaks = [];
					foreach(array_merge($break_pairs, $ot_break_pairs) as $break_pair)
					{
						if(isset($break_pair->from))
						{
							$datetime = $break_pair->from->datetime;
							if (($datetime >= $time_in) && ($datetime <= $estimated_tomorrow_time_in))
							{
								$matched_breaks[] = $break_pair;
							}
						}
						else
						{
							$datetime = $break_pair->to->datetime;
							if (($datetime >= $time_in) && ($datetime <= $estimated_tomorrow_time_in))
							{
								$matched_breaks[] = $break_pair;
							}
						}
					}
					
					$matched_breaks_count[] = count($matched_breaks);

					$employee  = G_Employee_Finder::findByEmployeeCode($value['employee_code']);
					$attendance = G_Attendance_Helper::generateAttendance($employee, $date, true);
					$t = $attendance->getTimesheet();

					// $breaktime_data = G_Break_Time_Schedule_Details_Helper::sqlGetAllBreaktimeSchedulesByEmployeeIdAndScheduleInAndScheduleOut($value['employee_id'], $value['scheduled_time_in'], $value['scheduled_time_out']);
					$breaktime_data = G_Break_Time_Schedule_Details_Helper::sqlGetAllBreaktimeSchedulesByEmployeeIdAndScheduleInAndScheduleOut($employee->getId(), $t->getScheduledTimeIn(), $t->getScheduledTimeOut());
	
					if(count($matched_breaks) > 0)
					// if(true)
					{
				 

						foreach($breaktime_data as $btkey => $schedule_break)
						{
							$is_required_logs = $schedule_break['to_required_logs'];
							
							$log_break_from = null;
							$log_break_to = null;

							if(count($matched_breaks) > 0)
							{

								$log_break = $matched_breaks[$btkey];
						
								$log_break_from = isset($log_break->from) ? $log_break->from->datetime : null;
								$log_break_to = isset($log_break->to) ? $log_break->to->datetime : null;
							
							}

							$break_from = date('Y-m-d H:i:s',strtotime($date . ', ' . $schedule_break['break_in']));
							if($break_from < $log_break->from->datetime)
							{
								$break_from = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($date . ', ' . $schedule_break['break_in'])));
							}

							$break_to = date('Y-m-d H:i:s',strtotime($date . ', ' . $schedule_break['break_out']));
							if($break_to < $log_break->from->datetime)
							{
								$break_to = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($date . ', ' . $schedule_break['break_out'])));
							}

							if($is_required_logs)
							{
								if(isset($log_break_from))
								{
									if($log_break_from < $break_from)
									{
										// $early_break_out_log_ids[] = $log_break->from->id;

										if(isset($remarks) && !empty($remarks))
										{
											$remarks = " | Early Break Out";

										}
										else
										{
											$remarks = "Early Break Out";
										}

										$fp_logs = G_Attendance_Log_Finder::findByEmployeeCodeAndDate($value['employee_code'], $value['date_attendance']);
				
										if($value['is_present'] == 1 || $value['is_paid'] == 1 || $value['is_restday'] == 1 || $value['is_holiday'] == 1 || $value['is_leave'] == 1) {
											$time_in	= $value['actual_time_in'];
											$time_out 	= $value['actual_time_out'];
						
											if($value['is_holiday'] == 1) {
												if($value['holiday_type'] == 1) {
													// $remarks = "LEGAL HOLIDAY";
												}else if($value['holiday_type'] == 2){
													// $remarks = "SPECIAL HOLIDAY";
												}
											}else if($value['is_restday'] == 1) {
						
												if($fp_logs) {
													if($fp_logs->getType() == 'in') 
													{
														$time_in = $fp_logs->getTime();
														// $remarks = " | NO OUT";
													} else {
														$time_out = $fp_logs->getTime();
														// $remarks = " | NO IN";
													}
												}                	
						
												// // $remarks = "Restday" . $remarks;
											}else if($value['is_leave'] == 1 && $value['is_paid'] == 1) {
												// $remarks = "With approved leave";
											}else if($value['is_leave'] == 1) {
												// $remarks = "No paid leave";
											}
						
										}else{
						
											if($value['is_present'] == 0 && $value['is_paid'] == 0 && $value['actual_time_in'] != '' && $value['actual_time_out']  != ''){
						
												$time_in	= $value['actual_time_in'];
												$time_out 	= $value['actual_time_out'];
												// $remarks 	= "Incorrect Shift";
						
											} else {
												$fp_logs = G_Attendance_Log_Finder::findByEmployeeCodeAndDate($value['employee_code'], $value['date_attendance']);
												if($fp_logs) {
													if($fp_logs->getType() == 'in') 
													{
														$time_in = $fp_logs->getTime();
														// $remarks = "NO OUT";
													} else {
														$time_out = $fp_logs->getTime();
														// $remarks = "NO IN";
													} 
												}else{
													// $remarks = "ABSENT";
												}					
											}
						
											
										} 
						
										if($value['is_restday'] == 1 && $value['scheduled_time_in'] == "" && $value['scheduled_time_out'] == "" && $value['is_holiday'] != 1) {
											//$is_restday_no_sched = 1;
										}
									
										if($is_restday_no_sched != 1) {
											$records[$value['employee_code']][$value['date_attendance']]['employee_code'] 		= $value['employee_code'];
											$records[$value['employee_code']][$value['date_attendance']]['employee_name'] 		= $value['employee_name'];
											$records[$value['employee_code']][$value['date_attendance']]['section'] 			= $value['section'];
											$records[$value['employee_code']][$value['date_attendance']]['department_name'] 	= $value['department_name'];
											$records[$value['employee_code']][$value['date_attendance']]['position'] 			= $value['position'];
											$records[$value['employee_code']][$value['date_attendance']]['date_attendance'] 	= $value['date_attendance'];
											$records[$value['employee_code']][$value['date_attendance']]['actual_time_in'] 		= $time_in;
											$records[$value['employee_code']][$value['date_attendance']]['actual_time_out'] 	= $time_out; 
											$records[$value['employee_code']][$value['date_attendance']]['remarks'] 			= $remarks;     
											$records[$value['employee_code']][$value['date_attendance']]['matched_breaks'] 		= $matched_breaks;
                                            $records[$value['employee_code']][$value['date_attendance']]['employee_attendance_id']      = $value['employee_attendance_id'];

                                            $records[$value['employee_code']][$value['date_attendance']]['project_site_id']      = $value['project_site_id'];
                                         
										}
									}
								}
								
								// if(isset($log_break_to))
								// {
								// 	if($log_break_to > $break_to)
								// 	{
								// 		$late_break_in_log_ids[] = $log_break->to->id;
								// 	}
								// }

								// if(!isset($log_break->from))
								// {
								// 	if(isset($log_break->to))
								// 	{
								// 		// $incomplete_break_log_ids[] = $log_break->to->id;
								// 		// Incomplete Break logs
								// 		if(isset($remarks) && !empty($remarks))
								// 		{
								// 			$remarks = " | INCOMPLETE BREAK LOGS";

								// 		}
								// 		else
								// 		{
								// 			$remarks = "INCOMPLETE BREAK LOGS";
								// 		}
								// 	}
								// }

								// if(!isset($log_break->to))
								// {
								// 	if(isset($log_break->from))
								// 	{
								// 		// $incomplete_break_log_ids[] = $log_break->from->id;
								// 		if(isset($remarks) && !empty($remarks))
								// 		{
								// 			$remarks = " | INCOMPLETE BREAK LOGS";

								// 		}
								// 		else
								// 		{
								// 			$remarks = "INCOMPLETE BREAK LOGS";
								// 		}
								// 	}
								// }
								
								if(!isset($log_break_from) && !isset($log_break_to))
								{
									// $no_break_logs_ids[] = $pair->from->id;
									// if(isset($remarks) && !empty($remarks))
									// {
									// 	$remarks = " | NO BREAK LOGS";
									// }
									// else
									// {
									// 	$remarks = "NO BREAK LOGS";
									// }

									
									

								}

							}
							
						}

					}

					
				}
				
			}
		}

		return [$records,  max($matched_breaks_count)];
	}


	public static function getDailyTimeRecordLateBreakIn($query, $add_query = '') {
    	$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}

		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			if($query['search_field'] == 'birthdate') {
				$query_search = $query['birthdate'];
			}else{
				$query_search = $query['search'];
			}
			$search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);				
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND e.department_company_structure_id =" . Model::safeSql($query['department_applied']);			
		}

        if ($query['project_site_id'] != '' && $query['project_site_id'] != 'all') {
            $search .= " AND ea.project_site_id =" . Model::safeSql($query['project_site_id']);
        }

        $sql = "
            SELECT e.id as employee_id, e.employee_code, CONCAT(e.lastname, ', ' , e.firstname) as employee_name, cs.title as section, ea.project_site_id,  
            COALESCE(esh.name,(
				SELECT name FROM `g_employee_subdivision_history`
				WHERE employee_id = e.id 
					AND end_date <> ''
				ORDER BY end_date DESC
				LIMIT 1
			))AS department_name, 
			COALESCE(ejh.name,(
			SELECT name FROM `g_employee_job_history`
			WHERE employee_id = e.id 
				AND end_date <> ''
			ORDER BY end_date DESC 
			LIMIT 1
			))AS position,     
				ea.is_present, is_paid, is_restday, is_holiday, is_leave, holiday_type, ea.scheduled_time_in, ea.scheduled_time_out, 
            	ea.date_attendance, ea.actual_time_in, ea.actual_time_out, ea.id as employee_attendance_id
			FROM ". G_EMPLOYEE_ATTENDANCE ." ea 
                LEFT JOIN " . EMPLOYEE . " e ON ea.employee_id = e.id  
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON esh.employee_id = ejh.employee_id AND ejh.end_date = ''
                LEFT JOIN " . G_COMPANY_STRUCTURE . " cs ON e.section_id = cs.id
			WHERE ea.date_attendance >= " . Model::safeSql($query['date_from']) . "
				AND ea.date_attendance <= " . Model::safeSql($query['date_to']) . "
				{$sql_add_query}
                " . $search . "
  				ORDER BY ea.date_attendance, e.lastname
        ";
			   
		$result = Model::runSql($sql,true);		

		$break_logs  = G_Employee_Break_Logs_Helper::sqlGetAllLogsNotTransferredByDateRange($query['date_from'], $query['date_to']);   

		usort($break_logs,function($first,$second)
		{
			return (($first['date'] .' '.$first['time'])  >  ($second['date'] .' '.$second['time']));
		});	

		// Break logs on timesheet
		foreach( $break_logs as $break_log )
		{
			$employee_id = $break_log['employee_id'];
			$emp_code = $break_log['employee_code'];
			$date     = date("Y-m-d",strtotime($break_log['date']));
			$type     = strtolower($break_log['type']);
			$time     = date("H:i:s",strtotime($break_log['time']));

			$breaks[$emp_code]['breaks'][$type][$date . ' ' .$time] = (object)['id' => $break_log['id'], 'datetime' => ($date . ' ' .$time)];
		}

		$records = array();
		$matched_breaks_count = [];
		foreach($result as $key => $value) {
			$is_restday_no_sched = 0;
			$time_in = "";
			$time_out = "";
			$remarks = "";
			
			$employee_break_logs = $breaks[$value['employee_code']];
		
			if($employee_break_logs)
			// if(true)
			{
				if($value['is_present'])
				{
					$log_breaks = $employee_break_logs['breaks'];
				
					$break_outs = $log_breaks[G_Employee_Break_Logs::TYPE_BOUT];
					$break_ins = $log_breaks[G_Employee_Break_Logs::TYPE_BIN];
					$break_outs_keys = array_keys($break_outs);
					$break_ins_keys = array_keys($break_ins);
	
					$ot_break_outs = $log_breaks[G_Employee_Break_Logs::TYPE_BOT_OUT];
					$ot_break_in = $log_breaks[G_Employee_Break_Logs::TYPE_BOT_IN];
					$ot_break_outs_keys = array_keys($ot_break_outs);
					$ot_break_in_keys = array_keys($ot_break_in);

					$break_pairs = G_Attendance_Log_Helper::getLogPairs($break_outs, $break_ins, $break_outs_keys, $break_ins_keys);
					$ot_break_pairs = G_Attendance_Log_Helper::getLogPairs($ot_break_outs, $ot_break_in, $ot_break_outs_keys, $ot_break_in_keys);
	

					$date = date('Y-m-d', strtotime($value['date_attendance'] . ' ' . $value['actual_time_in']));
					$time_in = date('Y-m-d H:i:s', strtotime($value['date_attendance'] . ' ' . $value['actual_time_in']));
					$estimated_tomorrow_time_in = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($value['date_attendance'] . ' ' . $value['schedule_time_in'])));

					$matched_breaks = [];
					foreach(array_merge($break_pairs, $ot_break_pairs) as $break_pair)
					{
						if(isset($break_pair->from))
						{
							$datetime = $break_pair->from->datetime;
							if (($datetime >= $time_in) && ($datetime <= $estimated_tomorrow_time_in))
							{
								$matched_breaks[] = $break_pair;
							}
						}
						else
						{
							$datetime = $break_pair->to->datetime;
							if (($datetime >= $time_in) && ($datetime <= $estimated_tomorrow_time_in))
							{
								$matched_breaks[] = $break_pair;
							}
						}
					}
					
					$matched_breaks_count[] = count($matched_breaks);

					$employee  = G_Employee_Finder::findByEmployeeCode($value['employee_code']);
					$attendance = G_Attendance_Helper::generateAttendance($employee, $date, true);
					$t = $attendance->getTimesheet();

					// $breaktime_data = G_Break_Time_Schedule_Details_Helper::sqlGetAllBreaktimeSchedulesByEmployeeIdAndScheduleInAndScheduleOut($value['employee_id'], $value['scheduled_time_in'], $value['scheduled_time_out']);
					$breaktime_data = G_Break_Time_Schedule_Details_Helper::sqlGetAllBreaktimeSchedulesByEmployeeIdAndScheduleInAndScheduleOut($employee->getId(), $t->getScheduledTimeIn(), $t->getScheduledTimeOut());
	
					if(count($matched_breaks) > 0)
					// if(true)
					{
				 

						foreach($breaktime_data as $btkey => $schedule_break)
						{
							$is_required_logs = $schedule_break['to_required_logs'];
							
							$log_break_from = null;
							$log_break_to = null;

							if(count($matched_breaks) > 0)
							{

								$log_break = $matched_breaks[$btkey];
						
								$log_break_from = isset($log_break->from) ? $log_break->from->datetime : null;
								$log_break_to = isset($log_break->to) ? $log_break->to->datetime : null;
							
							}

							$break_from = date('Y-m-d H:i:s',strtotime($date . ', ' . $schedule_break['break_in']));
							if($break_from < $log_break->from->datetime)
							{
								$break_from = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($date . ', ' . $schedule_break['break_in'])));
							}

							$break_to = date('Y-m-d H:i:s',strtotime($date . ', ' . $schedule_break['break_out']));
							if($break_to < $log_break->from->datetime)
							{
								$break_to = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($date . ', ' . $schedule_break['break_out'])));
							}

							if($is_required_logs)
							{
								// if(isset($log_break_from))
								// {
								// 	if($log_break_from < $break_from)
								// 	{
								// 		// $early_break_out_log_ids[] = $log_break->from->id;

								// 		if(isset($remarks) && !empty($remarks))
								// 		{
								// 			$remarks = " | Early Break Out";

								// 		}
								// 		else
								// 		{
								// 			$remarks = "Early Break Out";
								// 		}
 
								// 	}
								// }
								
								if(isset($log_break_to))
								{
									if($log_break_to > $break_to)
									{
										// $late_break_in_log_ids[] = $log_break->to->id;

										if(isset($remarks) && !empty($remarks))
										{
											$remarks = " | Late Break In";

										}
										else
										{
											$remarks = "Late Break In";
										}
 
										$fp_logs = G_Attendance_Log_Finder::findByEmployeeCodeAndDate($value['employee_code'], $value['date_attendance']);
				
										if($value['is_present'] == 1 || $value['is_paid'] == 1 || $value['is_restday'] == 1 || $value['is_holiday'] == 1 || $value['is_leave'] == 1) {
											$time_in	= $value['actual_time_in'];
											$time_out 	= $value['actual_time_out'];
						
											if($value['is_holiday'] == 1) {
												if($value['holiday_type'] == 1) {
													// $remarks = "LEGAL HOLIDAY";
												}else if($value['holiday_type'] == 2){
													// $remarks = "SPECIAL HOLIDAY";
												}
											}else if($value['is_restday'] == 1) {
						
												if($fp_logs) {
													if($fp_logs->getType() == 'in') 
													{
														$time_in = $fp_logs->getTime();
														// $remarks = " | NO OUT";
													} else {
														$time_out = $fp_logs->getTime();
														// $remarks = " | NO IN";
													}
												}                	
						
												// // $remarks = "Restday" . $remarks;
											}else if($value['is_leave'] == 1 && $value['is_paid'] == 1) {
												// $remarks = "With approved leave";
											}else if($value['is_leave'] == 1) {
												// $remarks = "No paid leave";
											}
						
										}else{
						
											if($value['is_present'] == 0 && $value['is_paid'] == 0 && $value['actual_time_in'] != '' && $value['actual_time_out']  != ''){
						
												$time_in	= $value['actual_time_in'];
												$time_out 	= $value['actual_time_out'];
												// $remarks 	= "Incorrect Shift";
						
											} else {
												$fp_logs = G_Attendance_Log_Finder::findByEmployeeCodeAndDate($value['employee_code'], $value['date_attendance']);
												if($fp_logs) {
													if($fp_logs->getType() == 'in') 
													{
														$time_in = $fp_logs->getTime();
														// $remarks = "NO OUT";
													} else {
														$time_out = $fp_logs->getTime();
														// $remarks = "NO IN";
													} 
												}else{
													// $remarks = "ABSENT";
												}					
											}
						
											
										} 
						
										if($value['is_restday'] == 1 && $value['scheduled_time_in'] == "" && $value['scheduled_time_out'] == "" && $value['is_holiday'] != 1) {
											//$is_restday_no_sched = 1;
										}
									
										if($is_restday_no_sched != 1) {
											$records[$value['employee_code']][$value['date_attendance']]['employee_code'] 		= $value['employee_code'];
											$records[$value['employee_code']][$value['date_attendance']]['employee_name'] 		= $value['employee_name'];
											$records[$value['employee_code']][$value['date_attendance']]['section'] 			= $value['section'];
											$records[$value['employee_code']][$value['date_attendance']]['department_name'] 	= $value['department_name'];
											$records[$value['employee_code']][$value['date_attendance']]['position'] 			= $value['position'];
											$records[$value['employee_code']][$value['date_attendance']]['date_attendance'] 	= $value['date_attendance'];
											$records[$value['employee_code']][$value['date_attendance']]['actual_time_in'] 		= $time_in;
											$records[$value['employee_code']][$value['date_attendance']]['actual_time_out'] 	= $time_out; 
											$records[$value['employee_code']][$value['date_attendance']]['remarks'] 			= $remarks;     
											$records[$value['employee_code']][$value['date_attendance']]['matched_breaks'] 		= $matched_breaks;
                                            $records[$value['employee_code']][$value['date_attendance']]['employee_attendance_id']      = $value['employee_attendance_id']; 

                                            $records[$value['employee_code']][$value['date_attendance']]['project_site_id']      = $value['project_site_id'];     
										}

									}
								}

								// if(!isset($log_break->from))
								// {
								// 	if(isset($log_break->to))
								// 	{
								// 		// $incomplete_break_log_ids[] = $log_break->to->id;
								// 		// Incomplete Break logs
								// 		if(isset($remarks) && !empty($remarks))
								// 		{
								// 			$remarks = " | INCOMPLETE BREAK LOGS";

								// 		}
								// 		else
								// 		{
								// 			$remarks = "INCOMPLETE BREAK LOGS";
								// 		}
								// 	}
								// }

								// if(!isset($log_break->to))
								// {
								// 	if(isset($log_break->from))
								// 	{
								// 		// $incomplete_break_log_ids[] = $log_break->from->id;
								// 		if(isset($remarks) && !empty($remarks))
								// 		{
								// 			$remarks = " | INCOMPLETE BREAK LOGS";

								// 		}
								// 		else
								// 		{
								// 			$remarks = "INCOMPLETE BREAK LOGS";
								// 		}
								// 	}
								// }
								
								if(!isset($log_break_from) && !isset($log_break_to))
								{
									// $no_break_logs_ids[] = $pair->from->id;
									// if(isset($remarks) && !empty($remarks))
									// {
									// 	$remarks = " | NO BREAK LOGS";
									// }
									// else
									// {
									// 	$remarks = "NO BREAK LOGS";
									// }

									
									

								}

							}
							
						}

					}

					
				}
				
			}
		}

		return [$records,  max($matched_breaks_count)];
	}
	


	public static function sqlCountIncompleteDTR( $date_from = '', $date_to = '' ){
		/*$sql = "
			SELECT  COUNT(id) AS total
			FROM ". G_ATTENDANCE_LOG ."
			WHERE date >= ". Model::safeSql($date_from) ."
				AND date <= ". Model::safeSql($date_to) ."				
				AND is_transferred =" . Model::safeSql(G_Attendance_Log::ISNOT_TRANSFERRED) . "
				AND (employee_name != '' OR user_id != 0)
		";
		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];*/

        $query['date_from'] = $date_from;
        $query['date_to']   = $date_to;
        $query['remark'] 	= 'all';

    	$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}	

		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			if($query['search_field'] == 'birthdate') {
				$query_search = $query['birthdate'];
			}else{
				$query_search = $query['search'];
			}
			$search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);				
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}

		$query_no_in = false;
		$query_no_out = false;
		if($query['remark'] != '' && $query['remark'] != 'all'){
			if($query['remark'] == 'no_in') {
				$query_no_out = true;		
			}else{
				$query_no_in = true;		
			}
		}
        
        $sql = "
        	SELECT fal.*,
        		COALESCE(esh.name,(
					SELECT name FROM `g_employee_subdivision_history`
					WHERE employee_id = e.id 
						AND end_date <> ''
					ORDER BY end_date DESC
					LIMIT 1
				))AS department_name, 
        		COALESCE(ejh.name,(
				SELECT name FROM `g_employee_job_history`
				WHERE employee_id = e.id 
					AND end_date <> ''
				ORDER BY end_date DESC 
				LIMIT 1
				))AS position,
				(SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name 
        	FROM g_fp_attendance_log fal 
        		LEFT JOIN " . EMPLOYEE . " e ON fal.employee_code = e.employee_code  
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON e.id = ejh.employee_id AND ejh.end_date = ''
            WHERE fal.date >= " . Model::safeSql($query['date_from']) . "
            	{$sql_add_query}
                AND fal.date <= " . Model::safeSql($query['date_to']) . "          
                " . $search . "
        ";       

		$result = Model::runSql($sql,true);	

		$data = array();
		foreach($result as $key => $value) {
			if($value['type'] == 'in' && !$query_no_out) {
				//NO TIME OUT
				$date = $value['date'];
				$date_tomorrow = date('Y-m-d', strtotime($date . ' +1 day'));
				$has_time_out = self::hasTimeOut($value['employee_code'],$date,$date_tomorrow);
				if($has_time_out == 0) {
					$data[$key]['employee_code'] 	= $value['employee_code'];
					$data[$key]['employee_name'] 	= $value['employee_name'];
					$data[$key]['department_name'] 	= $value['department_name'];
					$data[$key]['section_name'] 	= $value['section_name'];
					$data[$key]['position'] 		= $value['position'];
					$data[$key]['date_attendance'] 	= $value['date'];
					$data[$key]['actual_time_in'] 	= date("h:i:s a",strtotime($value['time']));
					$data[$key]['actual_time_out'] 	= "";
				}
			}elseif($value['type'] == 'out' && !$query_no_in) {
				//NO TIME IN
				$date = $value['date'];
				$date_yesterday = date('Y-m-d', strtotime($date . ' -1 day'));
				$has_time_in = self::hasTimeIn($value['employee_code'],$date,$date_yesterday);
				if($has_time_in == 0) {
					$data[$key]['employee_code'] 	= $value['employee_code'];
					$data[$key]['employee_name'] 	= $value['employee_name'];
					$data[$key]['department_name'] 	= $value['department_name'];
					$data[$key]['section_name'] 	= $value['section_name'];
					$data[$key]['position'] 		= $value['position'];
					$data[$key]['date_attendance'] 	= $value['date'];
					$data[$key]['actual_time_in'] 	= "";
					$data[$key]['actual_time_out'] 	= date("h:i:s a",strtotime($value['time']));
				}

			}
		}

		return count($data);		
	}
    
    public static function getIncompleteTimeInOutData($query, $add_query = '') {
    	$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}	

		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			if($query['search_field'] == 'birthdate') {
				$query_search = $query['birthdate'];
			}else{
				$query_search = $query['search'];
			}
			$search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);				
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}

		$query_no_in = false;
		$query_no_out = false;
		if($query['remark'] != '' && $query['remark'] != 'all'){
			if($query['remark'] == 'no_in') {
				$query_no_out = true;		
			}else{
				$query_no_in = true;		
			}
		}
        
        $sql = "
        	SELECT fal.*,
        		COALESCE(esh.name,(
					SELECT name FROM `g_employee_subdivision_history`
					WHERE employee_id = e.id 
						AND end_date <> ''
					ORDER BY end_date DESC
					LIMIT 1
				))AS department_name, 
        		COALESCE(ejh.name,(
				SELECT name FROM `g_employee_job_history`
				WHERE employee_id = e.id 
					AND end_date <> ''
				ORDER BY end_date DESC 
				LIMIT 1
				))AS position,
				(SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name 
        	FROM g_fp_attendance_log fal 
        		LEFT JOIN " . EMPLOYEE . " e ON fal.employee_code = e.employee_code  
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON e.id = ejh.employee_id AND ejh.end_date = ''
            WHERE fal.date >= " . Model::safeSql($query['date_from']) . "
            	{$sql_add_query}
                AND fal.date <= " . Model::safeSql($query['date_to']) . "          
                " . $search . "
        ";       

		$result = Model::runSql($sql,true);	

		$data = array();
		foreach($result as $key => $value) {
			if($value['type'] == 'in' && !$query_no_out) {
				//NO TIME OUT
				$date = $value['date'];
				$date_tomorrow = date('Y-m-d', strtotime($date . ' +1 day'));
				$has_time_out = self::hasTimeOut($value['employee_code'],$date,$date_tomorrow);
				if($has_time_out == 0) {
					$data[$key]['employee_code'] 	= $value['employee_code'];
					$data[$key]['employee_name'] 	= $value['employee_name'];
					$data[$key]['department_name'] 	= $value['department_name'];
					$data[$key]['section_name'] 	= $value['section_name'];
					$data[$key]['position'] 		= $value['position'];
					$data[$key]['date_attendance'] 	= $value['date'];
					$data[$key]['actual_time_in'] 	= date("h:i:s a",strtotime($value['time']));
					$data[$key]['actual_time_out'] 	= "";
				}
			}elseif($value['type'] == 'out' && !$query_no_in) {
				//NO TIME IN
				$date = $value['date'];
				$date_yesterday = date('Y-m-d', strtotime($date . ' -1 day'));
				$has_time_in = self::hasTimeIn($value['employee_code'],$date,$date_yesterday);
				if($has_time_in == 0) {
					$data[$key]['employee_code'] 	= $value['employee_code'];
					$data[$key]['employee_name'] 	= $value['employee_name'];
					$data[$key]['department_name'] 	= $value['department_name'];
					$data[$key]['section_name'] 	= $value['section_name'];
					$data[$key]['position'] 		= $value['position'];
					$data[$key]['date_attendance'] 	= $value['date'];
					$data[$key]['actual_time_in'] 	= "";
					$data[$key]['actual_time_out'] 	= date("h:i:s a",strtotime($value['time']));
				}

			}
		}

        $data2 = array();

        if ($query['project_site_id'] != '' && $query['project_site_id'] != 'all') {

               $project_site_id = $_POST['project_site_id'];

                foreach($data as $key => $value){
                    $employee_code = $value['employee_code'];
                    $date = $value['date_attendance'];

                    $e = G_Employee_Finder::findByEmployeeCode($employee_code);
                    if($e){
                        $p = G_Employee_Project_Site_History_Finder::getProjectSiteByEmployeeAndDate($e, $date);
                        if($p){
                            if($p->getProjectId() == $project_site_id){
                                $data2[$key]['employee_code']    = $value['employee_code'];
                                $data2[$key]['employee_name']    = $value['employee_name'];
                                $data2[$key]['department_name']  = $value['department_name'];
                                $data2[$key]['section_name']     = $value['section_name'];
                                $data2[$key]['position']         = $value['position'];
                                $data2[$key]['date_attendance']  = $value['date_attendance'];
                                $data2[$key]['actual_time_in']   = $value['actual_time_in'];
                                $data2[$key]['actual_time_out']  = $value['actual_time_out'];
                                $data2[$key]['project_site_id']  = $value['project_site_id'];
                            }
                        }
                    }

                }

                return $data2;

        }
        else{


            foreach($data as $key => $value){
                    $employee_code = $value['employee_code'];
                    $date = $value['date_attendance'];

                    $e = G_Employee_Finder::findByEmployeeCode($employee_code);
                    if($e){
                        $p = G_Employee_Project_Site_History_Finder::getProjectSiteByEmployeeAndDate($e, $date);
                        if($p){

                            $data[$key]['project_site_id'] = $p->getProjectId();
                        }
                        else{
                            $data[$key]['project_site_id'] = 0;
                        }
                    }
                }

            return $data;
        }
		
	}
    
    public static function getIncompleteTimeInOutWithBreakLogsData($query, $add_query = '') {
    	$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}	

		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			if($query['search_field'] == 'birthdate') {
				$query_search = $query['birthdate'];
			}else{
				$query_search = $query['search'];
			}
			$search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);				
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}

		$where_condition = " 
			AND (
				(
					ea.actual_time_in= '' || 
					(
						ebl.required_log_break1 = 1 &&
						ebl.log_break1_in  = ''
					) ||
					(
						ebl.required_log_break2 = 1 &&
						ebl.log_break2_in  = ''
					) ||
					(
						ebl.required_log_break3 = 1 &&
						ebl.log_break3_in  = ''
					)
				) ||
				(
					ea.actual_time_out= '' || 
					(
						ebl.required_log_break1 = 1 &&
						ebl.log_break1_out  = ''
					) ||
					(
						ebl.required_log_break2 = 1 &&
						ebl.log_break2_out  = ''
					) ||
					(
						ebl.required_log_break3 = 1 &&
						ebl.log_break3_out  = ''
					)
				) 
			) 
		";
		if($query['remark'] != '' && $query['remark'] != 'all'){
			if($query['remark'] == 'no_in') {
				$where_condition = " 
					AND (
						ea.actual_time_in= '' || 
						(
							ebl.required_log_break1 = 1 &&
							ebl.log_break1_in  = ''
						) ||
						(
							ebl.required_log_break2 = 1 &&
							ebl.log_break2_in  = ''
						) ||
						(
							ebl.required_log_break3 = 1 &&
							ebl.log_break3_in  = ''
						)
					) 
				";
			}else{
				$where_condition = " 
					AND (
						ea.actual_time_out= '' || 
						(
							ebl.required_log_break1 = 1 &&
							ebl.log_break1_out  = ''
						) ||
						(
							ebl.required_log_break2 = 1 &&
							ebl.log_break2_out  = ''
						) ||
						(
							ebl.required_log_break3 = 1 &&
							ebl.log_break3_out  = ''
						)
					) 
				";	
			}
		}
        
        $sql = "
        	SELECT e.employee_code, CONCAT(e.lastname, ', ' , e.firstname) as employee_name, ea.date_attendance, ea.actual_time_in, ea.actual_time_out,
        		COALESCE(esh.name,(
					SELECT name FROM `g_employee_subdivision_history`
					WHERE employee_id = e.id 
						AND end_date <> ''
					ORDER BY end_date DESC
					LIMIT 1
				))AS department_name, 
        		COALESCE(ejh.name,(
				SELECT name FROM `g_employee_job_history`
				WHERE employee_id = e.id 
					AND end_date <> ''
				ORDER BY end_date DESC 
				LIMIT 1
				))AS position,
				(SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name ,
				ebl.log_break1_out, ebl.log_break1_in, ebl.log_break2_out, ebl.log_break2_in, ebl.log_break3_out, ebl.log_break3_in, ebl.employee_attendance_id
			FROM ". G_EMPLOYEE_ATTENDANCE." ea
				LEFT JOIN " . G_EMPLOYEE_BREAK_LOGS_SUMMARY . " ebl ON ea.id = ebl.employee_attendance_id
                LEFT JOIN " . EMPLOYEE . " e ON ea.employee_id = e.id
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON e.id = ejh.employee_id AND ejh.end_date = ''
			WHERE ea.date_attendance >= " . Model::safeSql($query['date_from']) . "
                AND ea.date_attendance <= " . Model::safeSql($query['date_to']) . " 
            	{$sql_add_query}
				" . $search . "
				" . $where_condition . "
		";   

		$result = Model::runSql($sql,true);	

		$data = array();
        
		$default_break_logs_headers = array(
			G_Employee_Break_Logs::TYPE_B1_OUT => 'Break1 OUT',
			G_Employee_Break_Logs::TYPE_B1_IN => 'Break1 IN',
			G_Employee_Break_Logs::TYPE_B2_OUT => 'Break2 OUT',
			G_Employee_Break_Logs::TYPE_B2_IN => 'Break2 IN',
			G_Employee_Break_Logs::TYPE_B3_OUT => 'Break3 OUT',
			G_Employee_Break_Logs::TYPE_B3_IN => 'Break3 IN'
		);
		$display_break_logs_headers = array();
		
		foreach($result as $key => $value) {
			$data[$key]['employee_code'] 	= $value['employee_code'];
			$data[$key]['employee_name'] 	= $value['employee_name'];
			$data[$key]['department_name'] 	= $value['department_name'];
			$data[$key]['section_name'] 	= $value['section_name'];
			$data[$key]['position'] 		= $value['position'];
			$data[$key]['date_attendance'] 	= $value['date_attendance'];
			$data[$key]['actual_time_in'] 	= $value['actual_time_in'] ? date("h:i:s a",strtotime($value['actual_time_in'])) : '';
			$data[$key]['actual_time_out'] 	= $value['actual_time_out'] ? date("h:i:s a",strtotime($value['actual_time_out'])) : '';
			$data[$key]['employee_break_logs'] = array(
				G_Employee_Break_Logs::TYPE_B1_OUT  => $value['log_break1_out'] ? date("h:i:s a",strtotime($value['log_break1_out'])) : '',
				G_Employee_Break_Logs::TYPE_B1_IN  => $value['log_break1_in'] ? date("h:i:s a",strtotime($value['log_break1_in'])) : '',
				G_Employee_Break_Logs::TYPE_B2_OUT  => $value['log_break2_out'] ? date("h:i:s a",strtotime($value['log_break2_out'])) : '',
				G_Employee_Break_Logs::TYPE_B2_IN  => $value['log_break2_in'] ? date("h:i:s a",strtotime($value['log_break2_in'])) : '',
				G_Employee_Break_Logs::TYPE_B3_OUT  => $value['log_break3_out'] ? date("h:i:s a",strtotime($value['log_break3_out'])) : '',
				G_Employee_Break_Logs::TYPE_B3_IN  => $value['log_break3_in'] ? date("h:i:s a",strtotime($value['log_break3_in'])) : ''
			); 
            
			$employee_break_logs = G_Employee_Break_logs_Summary_Finder::findByEmployeeAttendanceId($value['employee_attendance_id']);

			if ($employee_break_logs) {
				$required_break_types = G_Employee_Break_Logs_Summary_Helper::getRequiredBreakTypes($employee_break_logs);
			
				foreach ($required_break_types as $key => $required_break_type) {
                    if ( $default_break_logs_headers[$required_break_type]) {
					    $display_break_logs_headers[$required_break_type] = $default_break_logs_headers[$required_break_type];
                    }
				}
			}
		}
		
		return array(
			'records' 						=> $data,
			'display_break_logs_headers' 	=> $display_break_logs_headers
		);
	}

	public static function hasTimeOut($employee_code,$date,$date_tomorrow) {
		$sql ="
			SELECT COUNT(id) as total FROM `g_fp_attendance_log` 
			WHERE employee_code = ".Model::safeSql($employee_code)." 
			AND type = 'out'
			AND (date = ".Model::safeSql($date)." 
				OR (date = IF( 
					(SELECT date FROM g_fp_attendance_log WHERE employee_code = ".Model::safeSql($employee_code)." 
			                AND date = ".Model::safeSql($date_tomorrow)."  AND type = 'in' LIMIT 1) = ".Model::safeSql($date_tomorrow)." ,".Model::safeSql($date)." ,".Model::safeSql($date_tomorrow)." )
			        )
			) 
		";

		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function hasTimeIn($employee_code,$date,$date_yesterday) {
		$sql ="
			SELECT COUNT(id) as total FROM `g_fp_attendance_log` 
			WHERE employee_code = ".Model::safeSql($employee_code)." 
			AND type = 'in'
			AND (date = ".Model::safeSql($date)." 
				OR (date = IF( 
					(SELECT date FROM g_fp_attendance_log WHERE employee_code = ".Model::safeSql($employee_code)." 
			                AND date = ".Model::safeSql($date_yesterday)."  AND type = 'out' LIMIT 1) = ".Model::safeSql($date_yesterday)." ,".Model::safeSql($date)." ,".Model::safeSql($date_yesterday)." )
			        )
			) 
		";

		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
    
    public static function getTimesheetData($query, $add_query = '') {
    	$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}

		if($query['timesheet_search_field'] != '' && $query['timesheet_search_field'] != 'all'){
			if($query['timesheet_search_field'] == 'birthdate') {
				$query_search = $query['timesheet_birthdate'];
			}else{
				$query_search = $query['timesheet_search'];
			}
			$search = " AND e." . $query['timesheet_search_field'] . "=" . Model::safeSql($query_search);			
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}

        if ($query['project_site_id'] != '' && $query['project_site_id'] != 'all') {
            $search .= " AND ea.project_site_id =" . Model::safeSql($query['project_site_id']);
        }
        
        $sql = "
            SELECT e.employee_code, CONCAT(e.lastname, ', ' , e.firstname) as employee_name, e.hired_date, ea.project_site_id,
            	COALESCE(esh.name,(
					SELECT name FROM `g_employee_subdivision_history`
					WHERE employee_id = e.id 
						AND end_date <> ''
					ORDER BY end_date DESC
					LIMIT 1
				))AS department_name, 
				COALESCE(ejh.name,(
				SELECT name FROM `g_employee_job_history`
				WHERE employee_id = e.id 
					AND end_date <> ''
				ORDER BY end_date DESC 
				LIMIT 1
				))AS position,                               
                ea.*,
                (SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name             
			FROM ". G_EMPLOYEE_ATTENDANCE ." ea 
                LEFT JOIN " . EMPLOYEE . " e ON ea.employee_id = e.id  
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON ea.employee_id = ejh.employee_id AND ejh.end_date = ''                
			WHERE ea.date_attendance >= " . Model::safeSql($query['date_from']) . "
				{$sql_add_query}
                AND ea.date_attendance <= " . Model::safeSql($query['date_to']) . "
                " . $search . "
            ORDER BY ea.employee_id ASC, ea.date_attendance ASC
        ";

		$result = Model::runSql($sql,true);		
		return $result;
	}
    
    public static function getTimesheetDataWithBreak($query, $add_query = '') {
    	$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}

		if($query['timesheet_search_field'] != '' && $query['timesheet_search_field'] != 'all'){
			if($query['timesheet_search_field'] == 'birthdate') {
				$query_search = $query['timesheet_birthdate'];
			}else{
				$query_search = $query['timesheet_search'];
			}
			$search = " AND e." . $query['timesheet_search_field'] . "=" . Model::safeSql($query_search);			
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}
        
        $sql = "
            SELECT e.employee_code, CONCAT(e.lastname, ', ' , e.firstname) as employee_name, e.hired_date, 
            	COALESCE(esh.name,(
					SELECT name FROM `g_employee_subdivision_history`
					WHERE employee_id = e.id 
						AND end_date <> ''
					ORDER BY end_date DESC
					LIMIT 1
				))AS department_name, 
				COALESCE(ejh.name,(
				SELECT name FROM `g_employee_job_history`
				WHERE employee_id = e.id 
					AND end_date <> ''
				ORDER BY end_date DESC 
				LIMIT 1
				))AS position,                               
                ea.*,
                (SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name, ea.id as employee_attendance_id           
			FROM ". G_EMPLOYEE_ATTENDANCE ." ea 
                LEFT JOIN " . EMPLOYEE . " e ON ea.employee_id = e.id  
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON ea.employee_id = ejh.employee_id AND ejh.end_date = ''                
			WHERE ea.date_attendance >= " . Model::safeSql($query['date_from']) . "
				{$sql_add_query}
                AND ea.date_attendance <= " . Model::safeSql($query['date_to']) . "
                " . $search . "
            ORDER BY ea.employee_id ASC, ea.date_attendance ASC
        ";

		$result = Model::runSql($sql,true);			

		$records = array();
		$default_break_logs_headers = array(
			G_Employee_Break_Logs::TYPE_B1_OUT => 'Break1 OUT',
			G_Employee_Break_Logs::TYPE_B1_IN => 'Break1 IN',
			G_Employee_Break_Logs::TYPE_B2_OUT => 'Break2 OUT',
			G_Employee_Break_Logs::TYPE_B2_IN => 'Break2 IN',
			G_Employee_Break_Logs::TYPE_B3_OUT => 'Break3 OUT',
			G_Employee_Break_Logs::TYPE_B3_IN => 'Break3 IN',
			G_Employee_Break_Logs::TYPE_OT_B1_OUT => 'OT Break1 OUT',
			G_Employee_Break_Logs::TYPE_OT_B1_IN => 'OT Break1 IN',
			G_Employee_Break_Logs::TYPE_OT_B2_OUT => 'OT Break2 OUT',
			G_Employee_Break_Logs::TYPE_OT_B2_IN => 'OT Break2 IN'
		);
		$display_break_logs_headers = array();

		foreach($result as $key => $value) {
			$records[$key] = $value;

			$employee_break_logs = G_Employee_Break_logs_Summary_Finder::findByEmployeeAttendanceId($value['employee_attendance_id']);

			if ($employee_break_logs) {
				$records[$key]['employee_break_logs'][G_Employee_Break_Logs::TYPE_B1_OUT] = $employee_break_logs->getLogBreak1Out();
				$records[$key]['employee_break_logs'][G_Employee_Break_Logs::TYPE_B1_IN] = $employee_break_logs->getLogBreak1In();
				$records[$key]['employee_break_logs'][G_Employee_Break_Logs::TYPE_B2_OUT] = $employee_break_logs->getLogBreak2Out();
				$records[$key]['employee_break_logs'][G_Employee_Break_Logs::TYPE_B2_IN] = $employee_break_logs->getLogBreak2In();
				$records[$key]['employee_break_logs'][G_Employee_Break_Logs::TYPE_B3_OUT] = $employee_break_logs->getLogBreak3Out();
				$records[$key]['employee_break_logs'][G_Employee_Break_Logs::TYPE_B3_IN] = $employee_break_logs->getLogBreak3In();
				$records[$key]['employee_break_logs'][G_Employee_Break_Logs::TYPE_OT_B1_OUT] = $employee_break_logs->getLogOtBreak1Out();
				$records[$key]['employee_break_logs'][G_Employee_Break_Logs::TYPE_OT_B1_IN] = $employee_break_logs->getLogOtBreak1In();
				$records[$key]['employee_break_logs'][G_Employee_Break_Logs::TYPE_OT_B2_OUT] = $employee_break_logs->getLogOtBreak2Out();
				$records[$key]['employee_break_logs'][G_Employee_Break_Logs::TYPE_OT_B2_IN] = $employee_break_logs->getLogOtBreak2In();
				$records[$key]['employee_break_logs']['total_break_hrs'] = $employee_break_logs->getTotalBreakHrs();
			}
			else {
				$records[$key]['employee_break_logs'] = array();
			}

			if ($employee_break_logs) {
				$available_break_types = G_Employee_Break_Logs_Summary_Helper::getAvailableBreakTypes($employee_break_logs);
			
				foreach ($available_break_types as $key => $available_break_type) {
					$display_break_logs_headers[$available_break_type] = $default_break_logs_headers[$available_break_type];
				}
			}
		}
		
		return array(
			'records' 						=> $records,
			'display_break_logs_headers' 	=> $display_break_logs_headers
		);
	}

	public static function getEmployeeTimesheetDataByEmployeeIdAndDateRange($employee_id = 0, $date_from = '', $date_to = '') {		
        $sql = "
            SELECT e.employee_code, CONCAT(e.lastname, ', ' , e.firstname) as employee_name, e.hired_date, 
            	COALESCE(esh.name,(
					SELECT name FROM `g_employee_subdivision_history`
					WHERE employee_id = e.id 
						AND end_date <> ''
					ORDER BY end_date DESC
					LIMIT 1
				))AS department_name,   
                COALESCE(ejh.name,(
				SELECT name FROM `g_employee_job_history`
				WHERE employee_id = e.id 
					AND end_date <> ''
				ORDER BY end_date DESC 
				LIMIT 1
				))AS position,	
                ea.*
			FROM ". G_EMPLOYEE_ATTENDANCE ." ea 
                LEFT JOIN " . EMPLOYEE . " e ON ea.employee_id = e.id  
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON ea.employee_id = ejh.employee_id AND ejh.end_date = ''                                
			WHERE ea.date_attendance >= " . Model::safeSql($date_from) . "
                AND ea.date_attendance <= " . Model::safeSql($date_to) . "
                AND ea.employee_id =" . Model::safeSql($employee_id) . "
        ";

		$result = Model::runSql($sql,true);		
		return $result;
	}

	public static function getOvertimeData($query, $add_query = '') {
    	$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}

		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			if($query['search_field'] == 'birthdate') {
				$query_search = $query['birthdate'];
			}else{
				$query_search = $query['search'];
			}
			$search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);				
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}
        
        if ($query['project_site_id'] != '' && $query['project_site_id'] != 'all') {
            $search .= " AND ea.project_site_id =" . Model::safeSql($query['project_site_id']);
        }


        $sql = "
            SELECT e.employee_code, CONCAT(e.lastname, ', ' , e.firstname) as employee_name, e.employment_status_id, 
            	COALESCE(esh.name,(
					SELECT name FROM `g_employee_subdivision_history`
					WHERE employee_id = e.id 
						AND end_date <> ''
					ORDER BY end_date DESC
					LIMIT 1
				))AS department_name,    
                COALESCE(ejh.name,(
				SELECT name FROM `g_employee_job_history`
				WHERE employee_id = e.id 
					AND end_date <> ''
				ORDER BY end_date DESC 
				LIMIT 1
				))AS position,	
                ea.scheduled_time_in, ea.scheduled_time_out, ea.date_attendance,
                ea.actual_time_in, ea.actual_time_out, ea.overtime_time_in, ea.overtime_time_out, ea.total_overtime_hours, ea.restday_overtime_hours, ea.legal_overtime_hours, ea.late_hours, ea.undertime_hours, ea.total_hours_worked, 
                ea.is_present, ea.is_restday, ea.is_holiday, ea.total_schedule_hours, ea.project_site_id,
                (SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name 
			FROM ". G_EMPLOYEE_ATTENDANCE ." ea 
                LEFT JOIN " . EMPLOYEE . " e ON ea.employee_id = e.id  
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON ea.employee_id = ejh.employee_id AND ejh.end_date = ''
			WHERE ( 
					(ea.date_attendance >= " . Model::safeSql($query['date_from']) . "
	                AND ea.date_attendance <= " . Model::safeSql($query['date_to']) . ") 
	                {$sql_add_query}                
	                AND (ea.overtime_time_in <> '' AND ea.overtime_time_out <> '' AND ea.total_overtime_hours > 0)
	                " . $search . "
	            ) || (
	            	(
	            		ea.date_attendance >= " . Model::safeSql($query['date_from']) . "
                		AND ea.date_attendance <= " . Model::safeSql($query['date_to']) . "
                	) AND (ea.is_restday = 1 AND ea.is_present = 1) 
	            )
                " . $search . "
            ORDER BY ea.date_attendance ASC
        ";    
         
		$result = Model::runSql($sql,true);		
		return $result;
	}

	public static function countOvertimeData($query, $add_query = '') {
    	$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}

		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			if($query['search_field'] == 'birthdate') {
				$query_search = $query['birthdate'];
			}else{
				$query_search = $query['search'];
			}
			$search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);				
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}
        
        $sql = "
            SELECT e.employee_code, CONCAT(e.lastname, ', ' , e.firstname) as employee_name,               
                COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name,
				COALESCE(ejh.name,(
	                SELECT name FROM `g_employee_job_history`
	                WHERE employee_id = e.id 
	                    AND end_date <> ''
	                ORDER BY end_date DESC 
	                LIMIT 1
                ))AS position,
                ea.total_overtime_hours,
                SUM(ea.total_overtime_hours)AS total_ot_hrs,
                ea.is_present, ea.is_restday, ea.is_holiday, ea.total_schedule_hours              
			FROM ". G_EMPLOYEE_ATTENDANCE." ea
                LEFT JOIN " . EMPLOYEE . " e ON ea.employee_id = e.id
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON e.id = ejh.employee_id AND ejh.end_date = ''
			WHERE (ea.date_attendance >= " . Model::safeSql($query['date_from']) . "
                AND ea.date_attendance <= " . Model::safeSql($query['date_to']) . ") 
                {$sql_add_query}
                AND ((ea.overtime_time_in <> '' AND ea.overtime_time_out <> '' AND ea.total_overtime_hours > 0) || (ea.is_restday = 1 AND ea.is_present = 1)) 
                " . $search . "
                GROUP BY e.id 
        ";

		$result = Model::runSql($sql,true);		
		return $result;
	}

	public static function getUndertimeData($query, $add_query = '') {
    	$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}

		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			if($query['search_field'] == 'birthdate') {
				$query_search = $query['birthdate'];
			}else{
				$query_search = $query['search'];
			}
			$search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);				
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}

        if ($query['project_site_id'] != '' && $query['project_site_id'] != 'all') {
            $search .= " AND ea.project_site_id =" . Model::safeSql($query['project_site_id']);
        }

        
        $sql = "
            SELECT e.employee_code, CONCAT(e.lastname, ', ' , e.firstname) as employee_name, ea.project_site_id,
            	COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name,     
                (SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name,            
                COALESCE(ejh.name,(
                SELECT name FROM `g_employee_job_history`
                WHERE employee_id = e.id 
                    AND end_date <> ''
                ORDER BY end_date DESC 
                LIMIT 1
                ))AS position, 
				(SELECT es.name FROM `g_settings_employee_status` es WHERE es.id = e.employee_status_id ORDER BY es.id DESC LIMIT 1 )AS employee_status, 
				ea.scheduled_time_in, ea.scheduled_time_out, ea.date_attendance,
                ea.actual_time_in, ea.actual_time_out, ea.undertime_hours
			FROM ". G_EMPLOYEE_ATTENDANCE ." ea 
                LEFT JOIN " . EMPLOYEE . " e ON ea.employee_id = e.id  
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON ea.employee_id = ejh.employee_id AND ejh.end_date = ''
			WHERE ea.date_attendance >= " . Model::safeSql($query['date_from']) . "
                AND ea.date_attendance <= " . Model::safeSql($query['date_to']) . " 
                {$sql_add_query}
                AND ea.undertime_hours > 0
                " . $search . "
        ";

		$result = Model::runSql($sql,true);		
		return $result;
	}

	public static function getUndertimeWithBreakLogsData($query, $add_query = '') {
    	$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}

		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			if($query['search_field'] == 'birthdate') {
				$query_search = $query['birthdate'];
			}else{
				$query_search = $query['search'];
			}
			$search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);				
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}
        
        $sql = "
            SELECT e.employee_code, CONCAT(e.lastname, ', ' , e.firstname) as employee_name, 
            	COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name,     
                (SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name,            
                COALESCE(ejh.name,(
                SELECT name FROM `g_employee_job_history`
                WHERE employee_id = e.id 
                    AND end_date <> ''
                ORDER BY end_date DESC 
                LIMIT 1
                ))AS position, 
				(SELECT es.name FROM `g_settings_employee_status` es WHERE es.id = e.employee_status_id ORDER BY es.id DESC LIMIT 1 )AS employee_status, 
				ea.scheduled_time_in, ea.scheduled_time_out, ea.date_attendance,
				ea.actual_time_in, ea.actual_time_out, ea.undertime_hours, 
				ebl.total_early_break_out_hrs, ebl.log_break1_out, ebl.log_break1_in, ebl.log_break2_out, ebl.log_break2_in, ebl.log_break3_out, ebl.log_break3_in,
                ea.id as employee_attendance_id
			FROM ". G_EMPLOYEE_ATTENDANCE ." ea 
				LEFT JOIN " . G_EMPLOYEE_BREAK_LOGS_SUMMARY . " ebl ON ea.id = ebl.employee_attendance_id
                LEFT JOIN " . EMPLOYEE . " e ON ea.employee_id = e.id  
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON ea.employee_id = ejh.employee_id AND ejh.end_date = ''
			WHERE ea.date_attendance >= " . Model::safeSql($query['date_from']) . "
                AND ea.date_attendance <= " . Model::safeSql($query['date_to']) . " 
                {$sql_add_query}
                AND (ea.undertime_hours > 0 || ebl.total_early_break_out_hrs > 0)
                " . $search . "
        ";

		$result = Model::runSql($sql,true);	
        
		$default_break_logs_headers = array(
			G_Employee_Break_Logs::TYPE_B1_OUT => 'Break1 OUT',
			G_Employee_Break_Logs::TYPE_B1_IN => 'Break1 IN',
			G_Employee_Break_Logs::TYPE_B2_OUT => 'Break2 OUT',
			G_Employee_Break_Logs::TYPE_B2_IN => 'Break2 IN',
			G_Employee_Break_Logs::TYPE_B3_OUT => 'Break3 OUT',
			G_Employee_Break_Logs::TYPE_B3_IN => 'Break3 IN'
		);
		$display_break_logs_headers = array();
		
		foreach ($result as $key => $data) {
			$result[$key]['employee_break_logs'] = array(
				G_Employee_Break_Logs::TYPE_B1_OUT  => $data['log_break1_out'],
				G_Employee_Break_Logs::TYPE_B1_IN  => $data['log_break1_in'],
				G_Employee_Break_Logs::TYPE_B2_OUT  => $data['log_break2_out'],
				G_Employee_Break_Logs::TYPE_B2_IN  => $data['log_break2_in'],
				G_Employee_Break_Logs::TYPE_B3_OUT  => $data['log_break3_out'],
				G_Employee_Break_Logs::TYPE_B3_IN  => $data['log_break3_in']
			); 
            
			$employee_break_logs = G_Employee_Break_logs_Summary_Finder::findByEmployeeAttendanceId($data['employee_attendance_id']);

			if ($employee_break_logs) {
				$available_break_types = G_Employee_Break_Logs_Summary_Helper::getAvailableBreakTypes($employee_break_logs);
			
				foreach ($available_break_types as $key => $available_break_type) {
                    if ( $default_break_logs_headers[$available_break_type]) {
					    $display_break_logs_headers[$available_break_type] = $default_break_logs_headers[$available_break_type];
                    }
				}
			}
		}

		return array(
			'records' 						=> $result,
			'display_break_logs_headers' 	=> $display_break_logs_headers
		);
	}

	public static function countUndertimeData($query, $add_query = '') {
    	$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}

		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			if($query['search_field'] == 'birthdate') {
				$query_search = $query['birthdate'];
			}else{
				$query_search = $query['search'];
			}
			$search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);			
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}
        

        if ($query['project_site_id'] != '' && $query['project_site_id'] != 'all') {
            $search .= " AND ea.project_site_id =" . Model::safeSql($query['project_site_id']);
        }

        $sql = "
            SELECT e.employee_code, CONCAT(e.lastname, ', ' , e.firstname) as employee_name, ea.project_site_id,
            	COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name,
                (SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name,  
                COALESCE(ejh.name,(
	                SELECT name FROM `g_employee_job_history`
	                WHERE employee_id = e.id 
	                    AND end_date <> ''
	                ORDER BY end_date DESC 
	                LIMIT 1
                ))AS position,    
                (SELECT es.name FROM `g_settings_employee_status` es WHERE es.id = e.employee_status_id ORDER BY es.id DESC LIMIT 1 )AS employee_status,             
                ea.undertime_hours,
                COUNT(ea.id)as total_number_undertime,
                SUM(ea.undertime_hours)as total_undertime_hrs
			FROM ". G_EMPLOYEE_ATTENDANCE." ea
                LEFT JOIN " . EMPLOYEE . " e ON ea.employee_id = e.id
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON ea.employee_id = ejh.employee_id AND ejh.end_date = ''
			WHERE ea.date_attendance >= " . Model::safeSql($query['date_from']) . "
                AND ea.date_attendance <= " . Model::safeSql($query['date_to']) . " 
                {$sql_add_query}
                AND ea.undertime_hours > 0 
                " . $search . "
                GROUP BY e.id 
        ";
        
		$result = Model::runSql($sql,true);		
		return $result;
	}

	public static function countUndertimeWithBreakLogsData($query, $add_query = '') {
    	$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}

		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			if($query['search_field'] == 'birthdate') {
				$query_search = $query['birthdate'];
			}else{
				$query_search = $query['search'];
			}
			$search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);			
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}
        
        $sql = "
            SELECT e.employee_code, CONCAT(e.lastname, ', ' , e.firstname) as employee_name, 
            	COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name,
                (SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name,  
                COALESCE(ejh.name,(
	                SELECT name FROM `g_employee_job_history`
	                WHERE employee_id = e.id 
	                    AND end_date <> ''
	                ORDER BY end_date DESC 
	                LIMIT 1
                ))AS position,    
                (SELECT es.name FROM `g_settings_employee_status` es WHERE es.id = e.employee_status_id ORDER BY es.id DESC LIMIT 1 )AS employee_status,             
                ea.undertime_hours,
                COUNT(ea.id)as total_number_undertime,
                SUM(ea.undertime_hours)as total_undertime_hrs, 
                SUM(ebl.total_early_break_out_hrs) as total_early_break_hrs
			FROM ". G_EMPLOYEE_ATTENDANCE." ea
				LEFT JOIN " . G_EMPLOYEE_BREAK_LOGS_SUMMARY . " ebl ON ea.id = ebl.employee_attendance_id
                LEFT JOIN " . EMPLOYEE . " e ON ea.employee_id = e.id
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON ea.employee_id = ejh.employee_id AND ejh.end_date = ''
			WHERE ea.date_attendance >= " . Model::safeSql($query['date_from']) . "
                AND ea.date_attendance <= " . Model::safeSql($query['date_to']) . " 
                {$sql_add_query}
                AND (ea.undertime_hours > 0 || ebl.total_early_break_out_hrs > 0)
                " . $search . "
                GROUP BY e.id 
        ";
        
		$result = Model::runSql($sql,true);		
		return $result;
	}
    
    public static function getLeaveData($query, $add_query = '') {
    	$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}

		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			if($query['search_field'] == 'birthdate') {
				$query_search = $query['birthdate'];
			}else{
				$query_search = $query['search'];
			}
			$search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);				
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}
        
        if($query['status'] != '' && $query['status'] != 'all'){
			$search .= " AND elr.is_approved =" . Model::safeSql($query['status']);			
		}
        
        $sql = "
            SELECT e.id,e.employee_code, CONCAT(e.lastname, ', ' , e.firstname) as employee_name, 
            	COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name,  
               	COALESCE(ejh.name,(
	                SELECT name FROM `g_employee_job_history`
	                WHERE employee_id = e.id 
	                    AND end_date <> ''
	                ORDER BY end_date DESC 
	                LIMIT 1
                ))AS position,
                elr.date_applied, elr.time_applied,
                elr.date_start, elr.date_end, elr.leave_comments, elr.is_approved, elr.is_paid, l.name as leave_type, elr.apply_half_day_date_start,
                (SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name 
			FROM ". G_EMPLOYEE ." e
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON esh.employee_id = ejh.employee_id AND ejh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_LEAVE_REQUEST . " elr ON e.id = elr.employee_id
                LEFT JOIN " . G_LEAVE. " l ON elr.leave_id = l.id
			WHERE (elr.date_start <= " . Model::safeSql($query['date_from']) . "
                AND elr.date_end >= " . Model::safeSql($query['date_to']) . ")
                OR
                (elr.date_start BETWEEN " . Model::safeSql($query['date_from']) . "  AND " . Model::safeSql($query['date_to']) . ")
                
                OR (elr.date_end BETWEEN " . Model::safeSql($query['date_from']) . "  AND " . Model::safeSql($query['date_to']) . ")
                {$sql_add_query}
                " . $search . "
        ";
 
		$result = Model::runSql($sql,true);	
        $result2 = array();

        if ($query['project_site_id'] != '' && $query['project_site_id'] != 'all') {

            foreach($result as $key => $value){

                 $date = $value['date_start'];
                 $e = G_Employee_Finder::findById($value['id']);
                 if($e){

                    $p = G_Employee_Project_Site_History_Finder::getProjectSiteByEmployeeAndDate($e, $date);


                    if($p){
                        if($query['project_site_id'] == $p->getProjectId()){

                         $result2[$key]['id'] = $value['id'];
                         $result2[$key]['employee_code'] =   $value['employee_code'];
                         $result2[$key]['employee_name'] =   $value['employee_name'];
                         $result2[$key]['department_name'] =  $value['department_name'];
                         $result2[$key]['position'] =          $value['position'];
                         $result2[$key]['date_applied'] =     $value['date_applied'];
                         $result2[$key]['time_applied'] =      $value['time_applied'];
                         $result2[$key]['date_start'] =       $value['date_start'];
                         $result2[$key]['date_end'] =        $value['date_end'];
                         $result2[$key]['leave_comments'] =   $value['leave_comments'];
                         $result2[$key]['is_approved'] =     $value['is_approved'];
                         $result2[$key]['is_paid'] =      $value['is_paid'];
                         $result2[$key]['leave_type'] =      $value['leave_type'];
                         $result2[$key]['apply_half_day_date_start'] =  $value['apply_half_day_date_start'];
                         $result2[$key]['section_name'] =    $value['section_name'];
                         $result2[$key]['project_site_id'] =    $p->getProjectId();
                        
                        }
                    }

                 }

            }



        return $result2;

        }
        else{

             foreach($result as $key => $value){

                 $date = $value['date_start'];
                 $e = G_Employee_Finder::findById($value['id']);
                 if($e){

                    $p = G_Employee_Project_Site_History_Finder::getProjectSiteByEmployeeAndDate($e, $date);


                    if($p){
                        $result[$key]['project_site_id'] =  $p->getProjectId();
                    }
                    else{
                        $result[$key]['project_site_id'] = 0;
                    }

                }
            }

            return $result;
        }

        //utilities::displayArray($result);exit();
		
	}

	public static function getEmploymentStatusData($query, $add_query = '') {
    	$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}

		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			if($query['search_field'] == 'birthdate') {
				$query_search = $query['birthdate'];
			}else{
				$query_search = $query['search'];
			}
			$search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);			
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}

		if($query['status'] != '' && $query['status'] != 'all'){
			$search .= " AND ejh.employment_status =" . Model::safeSql($query['status']);			
		}else{
			$search .= " AND ejh.employment_status <> '' ";
		}

        if ($query['project_site_id'] != '' && $query['project_site_id'] != 'all') {
            $search .= " AND e.project_site_id =" . Model::safeSql($query['project_site_id']);
        }
  
       $sql = "
            SELECT e.employee_code, CONCAT(e.lastname, ', ' , e.firstname) as employee_name, e.project_site_id,
            	COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name,
                COALESCE(ejh.name,(
                SELECT name FROM `g_employee_job_history`
                WHERE employee_id = e.id 
                    AND end_date <> ''
                ORDER BY end_date DESC 
                LIMIT 1
                ))AS position,                
                ejh.employment_status, e.hired_date,
                (SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name             
			FROM ". G_EMPLOYEE ." e
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON e.id = ejh.employee_id AND ejh.end_date = ''
			WHERE e.hired_date >= " . Model::safeSql($query['date_from']) . "
                AND e.hired_date <= " . Model::safeSql($query['date_to']) . "
                {$sql_add_query} 
                " . $search . "
        ";
 	
		$result = Model::runSql($sql,true);		
		return $result;
	}

	public static function getEeErContributionData($query, $add_query = '') {
    	$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}

		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			if($query['search_field'] == 'birthdate') {
				$query_search = $query['birthdate'];
			}else{
				$query_search = $query['search'];
			}
			$search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);			
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}			
  
        /*$sql = "
            SELECT e.employee_code, CONCAT(e.lastname, ', ' , e.firstname) as employee_name, 
                esh.name as department_name, ejh.name as position, ep.labels,
                 (SELECT SUM(sss) FROM " . G_EMPLOYEE_PAYSLIP . "
		         WHERE employee_id = e.id 
		         	AND period_start >= " . Model::safeSql($query['date_from']) . "
		         	AND period_end <= " . Model::safeSql($query['date_to']) . "  
		         ) as ee_sss,
		         (SELECT SUM(philhealth) FROM " . G_EMPLOYEE_PAYSLIP . "
		         WHERE employee_id = e.id 
		         	AND period_start >= " . Model::safeSql($query['date_from']) . "
		         	AND period_end <= " . Model::safeSql($query['date_to']) . "  
		         ) as ee_philhealth,
		         (SELECT SUM(pagibig) FROM " . G_EMPLOYEE_PAYSLIP . "
		         WHERE employee_id = e.id 
		         	AND period_start >= " . Model::safeSql($query['date_from']) . "
		         	AND period_end <= " . Model::safeSql($query['date_to']) . " 
		         ) as ee_pagibig,
		         (SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name             
			FROM ". G_EMPLOYEE ." e
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id 
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON esh.employee_id = ejh.employee_id 
                LEFT JOIN " . G_EMPLOYEE_PAYSLIP . " ep ON e.id = ep.employee_id 
			WHERE ep.period_start >= " . Model::safeSql($query['date_from']) . "
                AND ep.period_end <= " . Model::safeSql($query['date_to']) . " 
                {$sql_add_query} 
                " . $search . "
        ";*/

		$sql = "
            SELECT e.employee_code, CONCAT(e.lastname, ', ' , e.firstname) as employee_name, 
                esh.name as department_name, ejh.name as position, ep.labels,
                CONCAT(ep.period_start , '|' , ep.period_end , '-', ep.employee_id) as cutoff_period_emp_id,
                 (SELECT SUM(sss) FROM " . G_EMPLOYEE_PAYSLIP . "
		         WHERE employee_id = e.id 
		         	AND period_start >= " . Model::safeSql($query['date_from']) . "
		         	AND period_end <= " . Model::safeSql($query['date_to']) . "  
		         ) as ee_sss,
		         (SELECT SUM(philhealth) FROM " . G_EMPLOYEE_PAYSLIP . "
		         WHERE employee_id = e.id 
		         	AND period_start >= " . Model::safeSql($query['date_from']) . "
		         	AND period_end <= " . Model::safeSql($query['date_to']) . "  
		         ) as ee_philhealth,
		         (SELECT SUM(pagibig) FROM " . G_EMPLOYEE_PAYSLIP . "
		         WHERE employee_id = e.id 
		         	AND period_start >= " . Model::safeSql($query['date_from']) . "
		         	AND period_end <= " . Model::safeSql($query['date_to']) . " 
		         ) as ee_pagibig,
		         (SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name             
			FROM ". G_EMPLOYEE ." e
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id 
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON esh.employee_id = ejh.employee_id 
                LEFT JOIN " . G_EMPLOYEE_PAYSLIP . " ep ON e.id = ep.employee_id 
			WHERE ep.period_start >= " . Model::safeSql($query['date_from']) . "
                AND ep.period_end <= " . Model::safeSql($query['date_to']) . " 
                {$sql_add_query} 
                " . $search . "
                GROUP BY cutoff_period_emp_id
        ";        

		$result = Model::runSql($sql,true);		
		//Utilities::displayArray($result);
		$data = array();
		//RECONSTRUCT ARRAY TO GET ER_SSS, ER_PHILHEALTH, ER_PAGIBIG from labels field
		foreach($result as $key => $value) {
			$data[$value['employee_code']]['employee_name'] 	= $value['employee_name'];
			$data[$value['employee_code']]['department_name'] 	= $value['department_name'];
			$data[$value['employee_code']]['section_name'] 		= $value['section_name'];
			$data[$value['employee_code']]['position'] 			= $value['position'];
			$data[$value['employee_code']]['ee_sss'] 			= $value['ee_sss'];
			$data[$value['employee_code']]['ee_pagibig'] 		= $value['ee_pagibig'];
			$data[$value['employee_code']]['ee_philhealth'] 	= $value['ee_philhealth'];
			$labels = unserialize($value['labels']);
			//Utilities::displayArray($labels);
			foreach($labels as $l_key => $l_value) {
				if($l_value->variable == 'sss_er') {
					//$data[$value['employee_code']]['er_sss'] += str_replace(",", "", $l_value->value) / 2;
					$data[$value['employee_code']]['er_sss'] += str_replace(",", "", $l_value->value);
				}elseif($l_value->variable == 'pagibig_er') {
					//$data[$value['employee_code']]['er_pagibig'] += str_replace(",", "", $l_value->value) / 2;
					$data[$value['employee_code']]['er_pagibig'] += str_replace(",", "", $l_value->value);
				}elseif($l_value->variable == 'philhealth_er') {
					//$data[$value['employee_code']]['er_philhealth'] += str_replace(",", "", $l_value->value) / 2;
					$data[$value['employee_code']]['er_philhealth'] += str_replace(",", "", $l_value->value);
				}
			}
		}
		//Utilities::displayArray($data);
		//exit;
		return $data;
	}

	public static function getEmployeeEndOfContractByCurrentMonth() {
        $sql = "
            SELECT e.id, CONCAT(e.lastname , ', ' , e.firstname) as employee_name, esh.name as department_name, e.hired_date,
				esh.end_date as date_end_of_contract
            FROM " . EMPLOYEE . " e
            LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id 
            WHERE MONTH(esh.end_date) = MONTH(NOW())
                AND esh.end_date <> ''
                AND esh.type = ". Model::safeSql(G_Employee_Subdivision_History::DEPARTMENT). " 
        ";

        $result = Model::runSql($sql,true);
		return $result;
    }
    
    public static function countEmployeeEndOfContractByCurrentMonth() {
        $sql = "
            SELECT COUNT(e.id) as total
            FROM " . EMPLOYEE . " e
            LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id 
            WHERE MONTH(esh.end_date) = MONTH(NOW())
                AND esh.end_date <> ''
                AND esh.type = ". Model::safeSql(G_Employee_Subdivision_History::DEPARTMENT). " 
        ";

        $result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
    }

    public static function countEmployeeEndOfContract30Days() {
    	$start_date = date("Y-m-01");
		$end_date = date("Y-m-d", strtotime("+30 days"));

        $sql = "
            SELECT COUNT(e.id) as total
            FROM " . EMPLOYEE . " e
            LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id 
            WHERE (esh.end_date >= '{$start_date}' AND esh.end_date <= '{$end_date}')
                AND esh.end_date <> ''
                AND esh.type = ". Model::safeSql(G_Employee_Subdivision_History::DEPARTMENT). " 
                AND e.employee_status_id = ". Model::safeSql(4). " 
        ";

        $result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
    }

	public static function countEmployeeEndOfContractProbi30Days() {
    	$date = date("Y-m-d", strtotime("+30 days"));

        $sql = "
		SELECT count(*) as total
		FROM (
		select * from (
		select * from g_employee_extend_contract
		order by end_date desc) ge
		group by ge.employee_id
		order by ge.end_date desc
		) geec
		LEFT JOIN g_employee e ON geec.employee_id = e.id
		WHERE e.employment_status_id =4
		AND geec.end_date <= '".$date."'
		AND geec.is_done != 1
		order by geec.end_date desc
        ";

        $result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
    }

	public static function employeeEndOfContractProbi30Days() {
    	$date = date("Y-m-d", strtotime("+30 days"));

        $sql = "
		SELECT e.employee_code, concat(e.lastname,' ',e.firstname) as employee_name,
		hired_date,
		geec.end_date
		FROM (
		select * from (
		select * from g_employee_extend_contract
		order by end_date desc) ge
		group by ge.employee_id
		order by ge.end_date desc
		) geec
		LEFT JOIN g_employee e ON geec.employee_id = e.id
		WHERE e.employment_status_id =4
		AND geec.end_date <= '".$date."'
		AND geec.is_done != 1
		order by geec.end_date desc
        ";

        $result = Model::runSql($sql, true);
		return $result;
    }


    public static function getEmployeeNoEmployeeStatus() {
        $sql = "
            SELECT e.id, CONCAT(e.lastname , ', ' , e.firstname) as employee_name, e.hired_date, esh.name as department_name
            FROM " . EMPLOYEE . " e 
            LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id 
            WHERE e.employee_status_id = 0
        ";

        $result = Model::runSql($sql,true);
		return $result;
    }

    public static function countEmployeeNoEmployeeStatus() {
        $sql = "
            SELECT COUNT(e.id) as total
            FROM " . EMPLOYEE . " e
            WHERE e.employee_status_id = 0
        ";

        $result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
    }

    public static function countEmployeeNoBankAccount() {
        $sql = "
            SELECT COUNT(e.id) AS total 
			FROM " . EMPLOYEE . " e 
			WHERE NOT EXISTS(
				SELECT null 
				FROM " . G_EMPLOYEE_DIRECT_DEPOSIT . " dd 
			  WHERE dd.employee_id = e.id
			)
        ";        
        $result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
    }

    public static function employeeWithNoBankAccount() {
        $sql = "
            SELECT *
			FROM " . EMPLOYEE . " e 
			WHERE NOT EXISTS(
				SELECT null 
				FROM " . G_EMPLOYEE_DIRECT_DEPOSIT . " dd 
			  WHERE dd.employee_id = e.id
			)
        ";
        $result = Model::runSql($sql,true);		
		return $result;
    }

    public static function sqlEmployeeDetailsById( $id = 0, $fields = array() ){
    	if( !empty( $fields ) ){
    		$sql_fields = implode(",", $fields);
    	}else{
    		$sql_fields =  " * ";
    	}

    	$sql = "
            SELECT {$sql_fields} 
            FROM " . EMPLOYEE . " 
            WHERE id = " . Model::safeSql($id) . "
            ORDER BY id DESC
            LIMIT 1
        ";
                
        $result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
    }

    public static function getEmployeeTardinessByCurrentDate() {
        $sql = "
            SELECT e.id, CONCAT(e.lastname , ', ' , e.firstname) as employee_name, ea.late_hours, esh.name as department_name 
            FROM " . EMPLOYEE . " e 
            LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id 	
            LEFT JOIN " . G_EMPLOYEE_ATTENDANCE . " ea ON ea.employee_id = e.id
            WHERE ea.date_attendance = CURDATE()
				AND ea.late_hours <> '' 
        ";

        $result = Model::runSql($sql,true);
		return $result;
    }
    
    public static function countEmployeeTardinessByCurrentDate() {
        $sql = "
            SELECT COUNT(e.id) as total
            FROM " . EMPLOYEE . " e 
            LEFT JOIN " . G_EMPLOYEE_ATTENDANCE . " ea ON ea.employee_id = e.id
            WHERE ea.date_attendance = CURDATE()
				AND ea.late_hours <> '' 
        ";

        $result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
    }

    public static function countEmployeeTardinessByFromAndTo($from = '', $to = '') {
    	$sql_from = date("Y-m-d",strtotime($from));
    	$sql_to   = date("Y-m-d",strtotime($to));
    	
        $sql = "
            SELECT COUNT(e.id) as total
            FROM " . EMPLOYEE . " e 
            LEFT JOIN " . G_EMPLOYEE_ATTENDANCE . " ea ON ea.employee_id = e.id
            WHERE ea.date_attendance BETWEEN " . Model::safeSql($sql_from) . " AND " . Model::safeSql($sql_to) . "
				AND ea.late_hours <> '' 
			AND e.employee_status_id = 1
        ";

        $result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
    }    

    public static function countEmployeeUndertimeByFromAndToDate($s_from = '', $s_to = '') {
    	$sql_from = date("Y-m-d",strtotime($s_from));
    	$sql_to   = date("Y-m-d",strtotime($s_to));
        $sql = "
            SELECT COUNT(e.id) as total
            FROM " . EMPLOYEE . " e 
            LEFT JOIN " . G_EMPLOYEE_ATTENDANCE . " ea ON ea.employee_id = e.id
            WHERE ea.date_attendance BETWEEN " . Model::safeSql($sql_from) . " AND " . Model::safeSql($sql_to) . "
				AND ea.undertime_hours <> '' 
        ";

        $result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
    }

    public static function getEmployeeIncompleteDTRByCurrentMonth() {      
        $sql = "
            SELECT e.id, CONCAT(e.lastname , ', ' , e.firstname) as employee_name, esh.name as department_name, 
				ea.date_attendance, 
				ea.actual_time_in, ea.actual_time_out
			FROM ". G_EMPLOYEE_ATTENDANCE ." ea 
                LEFT JOIN " . EMPLOYEE . " e ON ea.employee_id = e.id
            	LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id 	  
			WHERE MONTH(ea.date_attendance) = MONTH(NOW())
                AND ((ea.actual_time_in = '' AND ea.actual_time_out <> '') 
                OR (ea.actual_time_out = '' AND ea.actual_time_in <> ''))
        ";

        $result = Model::runSql($sql,true);
		return $result;
    }
    
    public static function countEmployeeIncompleteDTRByCurrentMonth() {      
        $sql = "
            SELECT COUNT(e.id) as total
			FROM ". G_EMPLOYEE_ATTENDANCE ." ea 
                LEFT JOIN " . EMPLOYEE . " e ON ea.employee_id = e.id  
			WHERE MONTH(ea.date_attendance) = MONTH(NOW())
                AND ((ea.actual_time_in = '' AND ea.actual_time_out <> '') 
                OR (ea.actual_time_out = '' AND ea.actual_time_in <> ''))
        ";

        $result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
    }

    public static function sqlIsIdExists($id) {
    	 $sql = "
            SELECT COUNT(id) as total
			FROM ". EMPLOYEE ."              
			WHERE id =" . Model::safeSql($id) . "
        ";

        $result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
    }
    
    public static function getEmployeeCurrentSalary() {
    	$sql = "
            SELECT e.id, ebh.basic_salary, ebh.type, ebh.pay_period_id, ebh.start_date, ebh.end_date
			FROM ". EMPLOYEE ." e  
			INNER JOIN g_employee_basic_salary_history ebh
				ON e.id = ebh.employee_id    
			WHERE ebh.start_date <= NOW() AND (ebh.end_date = '' OR ebh.end_date >= NOW()) 
			GROUP BY e.id
        ";

        $result = Model::runSql($sql,true);
		return $result;
	}

	public static function countProcessedEmployeePayrollByCutoff($start_date, $end_date, $additional_qry, $employee_ids_qry = "") {
		$sql = "
			SELECT COUNT(DISTINCT e.id) as total
			FROM g_employee_payslip p	
				LEFT JOIN ".EMPLOYEE." e 
				ON 	p.employee_id = e.id
			WHERE p.period_start >= " . Model::safeSql($start_date) . " AND p.period_end <= " . Model::safeSql($end_date) . " 
			".$additional_qry.$employee_ids_qry."
			
		";

		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

    public static function countProcessedEmployeeWeeklyPayrollByCutoff($start_date, $end_date, $additional_qry, $employee_ids_qry = "") {
        $sql = "
            SELECT COUNT(DISTINCT e.id) as total
            FROM g_employee_weekly_payslip p   
                LEFT JOIN ".EMPLOYEE." e 
                ON  p.employee_id = e.id
            WHERE p.period_start >= " . Model::safeSql($start_date) . " AND p.period_end <= " . Model::safeSql($end_date) . " 
            ".$additional_qry.$employee_ids_qry."
            
        ";

        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);
        return $row['total'];
    }


	public static function sqlProcessedEmployeePayrollByCutoff($start_date, $end_date, $additional_qry, $employee_ids_qry = "") {
		$sql = "
			SELECT *
			FROM g_employee_payslip p	
				LEFT JOIN ".EMPLOYEE." e 
				ON 	p.employee_id = e.id
			WHERE p.period_start >= " . Model::safeSql($start_date) . " AND p.period_end <= " . Model::safeSql($end_date) . " 
			".$additional_qry.$employee_ids_qry."
			
		";

		$result = Model::runSql($sql,true);
		return $result;
	}


    public static function sqlProcessedEmployeeWeeklyPayrollByCutoff($start_date, $end_date, $additional_qry, $employee_ids_qry = "") {
        $sql = "
            SELECT *
            FROM g_employee_weekly_payslip p   
                LEFT JOIN ".EMPLOYEE." e 
                ON  p.employee_id = e.id
            WHERE p.period_start >= " . Model::safeSql($start_date) . " AND p.period_end <= " . Model::safeSql($end_date) . " 
            ".$additional_qry.$employee_ids_qry."
            
        ";

        $result = Model::runSql($sql,true);
        return $result;
    }

	public static function sqlProcessedEmployeePayrollByCutoffGetEmployeeId($start_date, $end_date, $additional_qry) {
		$sql = "
			SELECT p.employee_id 
			FROM g_employee_payslip p	
				LEFT JOIN ".EMPLOYEE." e 
				ON 	p.employee_id = e.id
			WHERE p.period_start >= " . Model::safeSql($start_date) . " AND p.period_end <= " . Model::safeSql($end_date) . " 
			".$additional_qry."
			
		";

		$result = Model::runSql($sql,true);
		return $result;
	}	

	public static function sqlGetEmployeesByDepartmentByEmploymentStatusByField($department, $employment_status, $field) {
		$sql = "
			SELECT *
			FROM  ".EMPLOYEE." e 
			WHERE e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "
		";	

		$result = Model::runSql($sql,true);
		return $result;
	}

 	/**
	 * @param array query
	 * @param string add_query
	 * @return array
	 *  
	*/
	public static function getEmployeesYearlyBonusByYear($query = array(), $add_query = '') {
		$year = $query['year'];

    	$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}

		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			if($query['search_field'] == 'birthdate') {
				$query_search = $query['birthdate'];
			}else{
				$query_search = $query['search'];
			}
			$search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);				
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}

		if( !empty($query['employee_ids']) && isset($query['employee_ids']) ){
			$employee_ids = implode(",", $query['employee_ids']);			
			$search .= " AND e.id IN ({$employee_ids})";
		}
        
        /*$sql = "
            SELECT e.id as employee_pkid, e.number_dependent, e.company_structure_id, e.employee_code, e.lastname, e.firstname, e.middlename, e.hired_date, es.status AS employee_status,
            	cs.title AS section_name,
            	COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name,  
               	COALESCE(ejh.name,(
	                SELECT name FROM `g_employee_job_history`
	                WHERE employee_id = e.id 
	                    AND end_date <> ''
	                ORDER BY end_date DESC 
	                LIMIT 1
                ))AS position,
				SUM(ep.tardiness_amount) AS sum_tardiness,
            	SUM(ep.withheld_tax) AS year_to_date_tax, 
            	SUM(ep.gross_pay) AS year_to_date_gross, 
            	SUM(ep.month_13th) AS sum_yearly_bonus
			FROM ". G_EMPLOYEE ." e          			
				INNER JOIN " . EMPLOYMENT_STATUS . " es ON e.employee_status_id = es.id	
                INNER JOIN " . G_EMPLOYEE_PAYSLIP . " ep ON e.id = ep.employee_id   
                INNER JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id
                INNER JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON esh.employee_id = ejh.employee_id 
                INNER JOIN " . COMPANY_STRUCTURE . " cs ON e.section_id = cs.id              
			WHERE 
				ep.period_start >=" . Model::safeSql($query['start_date']) . " 
					AND ep.period_end <=" . Model::safeSql($query['end_date']) . "
					AND e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "               
				{$sql_add_query}				
                " . $search . "
            GROUP BY ep.employee_id
        ";*/

        $sql = "
            SELECT e.id as employee_pkid, e.number_dependent, e.company_structure_id, e.employee_code, e.lastname, e.firstname, e.middlename, e.hired_date, es.status AS employee_status,
            	cs.title AS section_name,
            	COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name,  
               	COALESCE(ejh.name,(
	                SELECT name FROM `g_employee_job_history`
	                WHERE employee_id = e.id 
	                    AND end_date <> ''
	                ORDER BY end_date DESC 
	                LIMIT 1
                ))AS position,
                ep.period_end,
				ep.tardiness_amount,
				ep.withheld_tax,
				ep.gross_pay,
				ep.month_13th
			FROM ". G_EMPLOYEE ." e          			
				INNER JOIN " . EMPLOYMENT_STATUS . " es ON e.employee_status_id = es.id	
                INNER JOIN " . G_EMPLOYEE_PAYSLIP . " ep ON e.id = ep.employee_id   
                INNER JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id
                INNER JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON esh.employee_id = ejh.employee_id 
                INNER JOIN " . COMPANY_STRUCTURE . " cs ON e.section_id = cs.id              
			WHERE 
				ep.period_start >=" . Model::safeSql($query['start_date']) . " 
					AND ep.period_end <=" . Model::safeSql($query['end_date']) . "
					AND e.e_is_archive =" . Model::safeSql(G_Employee::NO) . " 
					AND (e.employment_status_id = 1 OR e.employment_status_id = 3 OR e.employment_status_id = 4)
				{$sql_add_query}				
                " . $search . "
            GROUP BY ep.id
        ";
        
		$result = Model::runSql($sql,true);	

		$data = array();
		foreach( $result as $key => $value ){
			$skip = false;
			if($query['percentage'] > 0) {
				// Override 13month bonus computation
				$percentage = $query['percentage'] / 100;
				$e = G_Employee_Finder::findById($value['employee_pkid']);
				$s = G_Employee_Basic_Salary_History_Finder::findByEmployeeAndDate($e, date('Y-12-31'));
				
				if(!$s) {
					$skip = true;
				}else{
					$salary_amount = $s->getBasicSalary();
					$salary_type = $s->getType();
				}

				$working_days = $e->getYearWorkingDays();
		        if( $working_days <= 0 ){
		            $sv = new G_Sprint_Variables(G_Sprint_Variables::FIELD_DEFAULT_TOTAL_WRKNG_DAYS);
		            $working_days = $sv->getVariableValue();
		        }

				switch ($salary_type):
		            case G_Employee_Basic_Salary_History::SALARY_TYPE_MONTHLY:                      
		                $monthly_rate = $salary_amount;
		                break;

		            case G_Employee_Basic_Salary_History::SALARY_TYPE_DAILY:      
		                $monthly_rate    = ($salary_amount * $working_days) / 12;    
		                break;
		        endswitch;

		        // Override value here
		        $sum_yearly_bonus = $monthly_rate * $percentage;
		        $deducted_amount = 0;
		        if($query['deduct_tardiness'] >= 1) {
		        	$sum_yearly_bonus = $sum_yearly_bonus - $value['tardiness_amount'];
		        	$deducted_amount = $value['tardiness_amount'];
		        }
		        
				// ----------------------------------
			}else{
				$sum_yearly_bonus = $value['month_13th'];
			}

			if(!$skip) {
				if(array_key_exists($value['employee_pkid'], $data)) {
					//$data[$value['employee_pkid']]['sum_yearly_bonus'] += $sum_yearly_bonus;
					$data[$value['employee_pkid']]['sum_tardiness'] += $value['tardiness_amount'];
					$data[$value['employee_pkid']]['year_to_date_tax'] += $value['withheld_tax'];
					$data[$value['employee_pkid']]['year_to_date_gross'] += $value['gross_pay'];
					$data[$value['employee_pkid']]['deducted_amount'] += $deducted_amount;
					if($deducted_amount > 0) {
						$data[$value['employee_pkid']]['sum_yearly_bonus'] -= $deducted_amount;
					}
				}else{
					//condition 

					$data[$value['employee_pkid']] = array (
						'employee_pkid' => $value['employee_pkid'],
						'number_dependent' => $value['number_dependent'],
						'company_structure_id' => $value['company_structure_id'],
						'employee_code' => $value['employee_code'],
						'lastname' => $value['lastname'],
						'firstname' => $value['firstname'],
						'middlename' => $value['middlename'],
						'hired_date' => $value['hired_date'],
						'employee_status' => $value['employee_status'],
						'section_name' => $value['section_name'],
						'department_name' => $value['department_name'],
						'position' => $value['position'],
						'sum_tardiness' => $value['tardiness_amount'],
						'year_to_date_tax' => $value['withheld_tax'],
						'year_to_date_gross' => $value['gross_pay'],
						'sum_yearly_bonus' => $sum_yearly_bonus,
						'deducted_amount' => $deducted_amount
					);
				}
			}
				
		}

		return $data;
	}	

	//new
public static function getEmployeesYearlyBonusByYearRev($query = array(), $add_query = '', $cutoff_start = '') {
        $year = $query['year'];

        $sql_add_query = '';
        if( $add_query != '' ){
            $sql_add_query = $add_query;
        }

        if($query['search_field'] != '' && $query['search_field'] != 'all'){
            if($query['search_field'] == 'birthdate') {
                $query_search = $query['birthdate'];
            }else{
                $query_search = $query['search'];
            }
            $search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);             
        }
        
        if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
            $search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);            
        }

        if( !empty($query['employee_ids']) && isset($query['employee_ids']) ){
            $employee_ids = implode(",", $query['employee_ids']);           
            $search .= " AND e.id IN ({$employee_ids})";
        }
        
        /*$sql = "
            SELECT e.id as employee_pkid, e.number_dependent, e.company_structure_id, e.employee_code, e.lastname, e.firstname, e.middlename, e.hired_date, es.status AS employee_status,
                cs.title AS section_name,
                COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name,  
                COALESCE(ejh.name,(
                    SELECT name FROM `g_employee_job_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC 
                    LIMIT 1
                ))AS position,
                SUM(ep.tardiness_amount) AS sum_tardiness,
                SUM(ep.withheld_tax) AS year_to_date_tax, 
                SUM(ep.gross_pay) AS year_to_date_gross, 
                SUM(ep.month_13th) AS sum_yearly_bonus
            FROM ". G_EMPLOYEE ." e                     
                INNER JOIN " . EMPLOYMENT_STATUS . " es ON e.employee_status_id = es.id 
                INNER JOIN " . G_EMPLOYEE_PAYSLIP . " ep ON e.id = ep.employee_id   
                INNER JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id
                INNER JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON esh.employee_id = ejh.employee_id 
                INNER JOIN " . COMPANY_STRUCTURE . " cs ON e.section_id = cs.id              
            WHERE 
                ep.period_start >=" . Model::safeSql($query['start_date']) . " 
                    AND ep.period_end <=" . Model::safeSql($query['end_date']) . "
                    AND e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "               
                {$sql_add_query}                
                " . $search . "
            GROUP BY ep.employee_id
        ";*/
        if($query['frequency'] == 1){

        $sql = "
            SELECT e.id as employee_pkid, e.number_dependent, e.company_structure_id, e.employee_code, e.lastname, e.firstname, e.middlename, e.hired_date,e.inactive_date, es.status AS employee_status,
                cs.title AS section_name,
                COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name,  
                COALESCE(ejh.name,(
                    SELECT name FROM `g_employee_job_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC 
                    LIMIT 1
                ))AS position,
                ep.period_start,
                ep.period_end,
                ep.tardiness_amount,
                ep.withheld_tax,
                ep.gross_pay,
                ep.month_13th,
                ep.basic_pay
            FROM ". G_EMPLOYEE ." e                     
                INNER JOIN " . EMPLOYMENT_STATUS . " es ON e.employee_status_id = es.id 
                INNER JOIN " . G_EMPLOYEE_PAYSLIP . " ep ON e.id = ep.employee_id   
                INNER JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id
                INNER JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON esh.employee_id = ejh.employee_id 
                LEFT JOIN " . COMPANY_STRUCTURE . " cs ON e.section_id = cs.id              
            WHERE 
                ep.period_start >=" . Model::safeSql($query['start_date']) . " 
                    AND ep.period_end <=" . Model::safeSql($query['end_date']) . "
                    AND e.e_is_archive =" . Model::safeSql(G_Employee::NO) . " 
                    AND (e.employment_status_id = 1 OR e.employment_status_id = 3 OR e.employment_status_id = 4)
                {$sql_add_query}                
                " . $search . "
            GROUP BY ep.id
        ";

         }else{


             $sql = "
            SELECT e.id as employee_pkid, e.number_dependent, e.company_structure_id, e.employee_code, e.lastname, e.firstname, e.middlename, e.hired_date,e.inactive_date, es.status AS employee_status,
                cs.title AS section_name,
                COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name,  
                COALESCE(ejh.name,(
                    SELECT name FROM `g_employee_job_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC 
                    LIMIT 1
                ))AS position,
                ep.period_start,
                ep.period_end,
                ep.tardiness_amount,
                ep.withheld_tax,
                ep.gross_pay,
                ep.month_13th,
                ep.basic_pay
            FROM ". G_EMPLOYEE ." e                     
                INNER JOIN " . EMPLOYMENT_STATUS . " es ON e.employee_status_id = es.id 
                INNER JOIN g_employee_weekly_payslip ep ON e.id = ep.employee_id   
                INNER JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id
                INNER JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON esh.employee_id = ejh.employee_id 
                LEFT JOIN " . COMPANY_STRUCTURE . " cs ON e.section_id = cs.id              
            WHERE 
                ep.period_start >=" . Model::safeSql($query['start_date']) . " 
                    AND ep.period_end <=" . Model::safeSql($query['end_date']) . "
                    AND e.e_is_archive =" . Model::safeSql(G_Employee::NO) . " 
                    
                {$sql_add_query}                
                " . $search . "
            GROUP BY ep.id
        ";
         }
        
        $result = Model::runSql($sql,true); 

        $data = array();
        foreach( $result as $key => $value ){
            $skip = false;
            /*if($query['percentage'] > 0) {
                // Override 13month bonus computation
                $percentage = $query['percentage'] / 100;
                $e = G_Employee_Finder::findById($value['employee_pkid']);
                $s = G_Employee_Basic_Salary_History_Finder::findByEmployeeAndDate($e, date('Y-12-31'));
                
                if(!$s) {
                    $skip = true;
                }else{
                    $salary_amount = $s->getBasicSalary();
                    $salary_type = $s->getType();
                }

                $working_days = $e->getYearWorkingDays();
                if( $working_days <= 0 ){
                    $sv = new G_Sprint_Variables(G_Sprint_Variables::FIELD_DEFAULT_TOTAL_WRKNG_DAYS);
                    $working_days = $sv->getVariableValue();
                }

                switch ($salary_type):
                    case G_Employee_Basic_Salary_History::SALARY_TYPE_MONTHLY:                      
                        $monthly_rate = $salary_amount;
                        break;

                    case G_Employee_Basic_Salary_History::SALARY_TYPE_DAILY:  

                        $monthly_rate    = ($salary_amount * $working_days) / 12; 


                        break;
                endswitch;

                // Override value here
                $sum_yearly_bonus = $monthly_rate * $percentage;
                $deducted_amount = 0;
                if($query['deduct_tardiness'] >= 1) {
                    $sum_yearly_bonus = $sum_yearly_bonus - $value['tardiness_amount'];
                    $deducted_amount = $value['tardiness_amount'];
                }
                
                // ----------------------------------
            }else{
                $sum_yearly_bonus = $value['month_13th'];
            }*/
              $deducted = 0;

              //var_dump($query['deduction_month']);exit();

              if($query['deduct_tardiness'] >= 1){

                    //$tardiness_amount = 0;
                    //deduction_month
                    //kukunin yung cutoff ng payslip then check if anung month
                    //if nasa deduction month array deduct tardiness
                    if($query['frequency'] == 1){
                        $cutoff = G_Cutoff_Period_Finder::findByPeriod($value['period_start'], $value['period_end']); 
                    }
                    else{
                         $cutoff = G_Weekly_Cutoff_Period_Finder::findByPeriod($value['period_start'], $value['period_end']);
                    }

                    if($cutoff){

                        $month = $cutoff->getMonth();
                        $month_number = date("m", strtotime($month));
                    }

                    //checking if kasama yung payslip sa deduction
                    if(!empty($query['deduction_month'])){
                         $deduction = $query['deduction_month'];
                         if(in_array($month_number, $deduction)){
                            $tardiness_amount = $value['tardiness_amount'];
                         }
                         else{
                            $tardiness_amount = 0;
                         }
                        
                    }
                    else{

                           $tardiness_amount = 0;
                    }

                      $new_basic   = $value['basic_pay'] - $tardiness_amount;

                      //adjust 13th month
                       $new_yearly_bonus = $new_basic / 12;
                       $deducted_amount = $tardiness_amount;
                  

                }
                else{

                   $new_yearly_bonus = $value['basic_pay'] / 12;
                }



            if(!$skip) {
                if(array_key_exists($value['employee_pkid'], $data)) {
                    //$data[$value['employee_pkid']]['sum_yearly_bonus'] += $sum_yearly_bonus;
                    $data[$value['employee_pkid']]['sum_tardiness'] += $value['tardiness_amount'];
                    $data[$value['employee_pkid']]['year_to_date_tax'] += $value['withheld_tax'];
                    $data[$value['employee_pkid']]['year_to_date_gross'] += $value['gross_pay'];
                    $data[$value['employee_pkid']]['deducted_amount'] += $deducted_amount;
                    $data[$value['employee_pkid']]['total_basic_pay'] += $value['basic_pay'];

                    //if($deducted_amount > 0) {
                        $data[$value['employee_pkid']]['sum_yearly_bonus'] += $new_yearly_bonus;
                    //}
                }else{
                    //condition 
                    // if()

                    if($value['inactive_date'] != '0000-00-00'){
                            if(strtotime($value['inactive_date']) < strtotime($cutoff_start)){
                                
                            }else{
                                    $data[$value['employee_pkid']] = array (
                                    'employee_pkid' => $value['employee_pkid'],
                                    'number_dependent' => $value['number_dependent'],
                                    'company_structure_id' => $value['company_structure_id'],
                                    'employee_code' => $value['employee_code'],
                                    'lastname' => $value['lastname'],
                                    'firstname' => $value['firstname'],
                                    'middlename' => $value['middlename'],
                                    'hired_date' => $value['hired_date'],
                                    'employee_status' => $value['employee_status'],
                                    'section_name' => $value['section_name'],
                                    'department_name' => $value['department_name'],
                                    'position' => $value['position'],
                                    'sum_tardiness' => $value['tardiness_amount'],
                                    'year_to_date_tax' => $value['withheld_tax'],
                                    'year_to_date_gross' => $value['gross_pay'],
                                    'sum_yearly_bonus' => $new_yearly_bonus,
                                    'deducted_amount' => $deducted_amount,
                                    'total_basic_pay' => $value['basic_pay'],
                                    'generate_based' => 'system generated'
                                );

                            }
                    }else{
                        $data[$value['employee_pkid']] = array (
                        'employee_pkid' => $value['employee_pkid'],
                        'number_dependent' => $value['number_dependent'],
                        'company_structure_id' => $value['company_structure_id'],
                        'employee_code' => $value['employee_code'],
                        'lastname' => $value['lastname'],
                        'firstname' => $value['firstname'],
                        'middlename' => $value['middlename'],
                        'hired_date' => $value['hired_date'],
                        'employee_status' => $value['employee_status'],
                        'section_name' => $value['section_name'],
                        'department_name' => $value['department_name'],
                        'position' => $value['position'],
                        'sum_tardiness' => $value['tardiness_amount'],
                        'year_to_date_tax' => $value['withheld_tax'],
                        'year_to_date_gross' => $value['gross_pay'],
                        'sum_yearly_bonus' => $new_yearly_bonus,
                        'deducted_amount' => $deducted_amount,
                        'total_basic_pay' => $value['basic_pay'],
                        'generate_based' => 'system generated'
                    );
                    }
                
                    // if(strtotime($value['inactive_date']) > strtotime($cutoff_end)){
                    //  echo $value['lastname'];
                    //  echo ",";
                    // }else{
                    //  echo $value['lastname'];
                    //  echo "1";
                    // }
                    //echo strtotime($cutoff_end);
                    
                }
            }
                
        }

        $data2 = array();

        foreach($data as $key => $value){      

                $sum_yearly_bonus = $data[$key]['sum_yearly_bonus'];

                if($query['percentage'] > 0){
                    $percentage = $query['percentage'] / 100;
                    $new_sum_yearly_bonus =  $sum_yearly_bonus * $percentage;
                }
                else{
                     $new_sum_yearly_bonus =  $sum_yearly_bonus;
                }

                

                $data[$key]['sum_yearly_bonus'] = $new_sum_yearly_bonus;
                //$data[$key]['deducted_amount'] = $deducted;

        }


        return $data;
    }
    //new

	public static function getEmployeeIncentiveReport($query, $add_query = '') {
		$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}

		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			if($query['search_field'] == 'birthdate') {
				$query_search = $query['birthdate'];
			}else{
				$query_search = $query['search'];
			}
			$search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);				
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}

		if( $query['leave_id'] > 0 ){
			//$search .= " AND lt.leave_id =" . Model::safeSql($query['leave_id']);
			$search .= " AND l.id =" . Model::safeSql($query['leave_id']);
		}

        $sql = "
            SELECT e.id AS employee_pkid, e.employee_code, e.lastname, e.firstname, e.middlename, e.hired_date, es.name AS employee_status,
            	cs.title AS section_name,
            	COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name,  
               	COALESCE(ejh.name,(
	                SELECT name FROM `g_employee_job_history`
	                WHERE employee_id = e.id 
	                    AND end_date <> ''
	                ORDER BY end_date DESC 
	                LIMIT 1
                ))AS position,
                lt.credits_added, lt.date_added,
                l.name as leave_type
			FROM ". G_EMPLOYEE ." e
				INNER JOIN " . COMPANY_STRUCTURE . " cs ON e.section_id = cs.id 
				INNER JOIN " . G_SETTINGS_EMPLOYEE_STATUS . " es ON e.employee_status_id = es.id
                INNER JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
                INNER JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON esh.employee_id = ejh.employee_id AND ejh.end_date = ''
                INNER JOIN " . EMPLOYEE_LEAVE_CREDIT_HISTORY . " lt ON e.id = lt.employee_id
                INNER JOIN " . G_LEAVE. " l ON lt.leave_id = l.id
			WHERE YEAR(lt.date_added) =" . Model::safeSql($query['incentive_leave_year']) . "
				{$sql_add_query}                
                " . $search . "
            ORDER BY lt.date_added ASC
        ";
	
		$result = Model::runSql($sql,true);		
		return $result;
	}

	public static function getEmployeeBasicDetails($query, $add_query = '') {
		$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}

		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			if($query['search_field'] == 'birthdate') {
				$query_search = $query['birthdate'];
			}else{
				$query_search = $query['search'];
			}
			$search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);				
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}

		/*if( $query['leave_id'] > 0 ){
			$search .= " AND l.id =" . Model::safeSql($query['leave_id']);
		}*/

        $sql = "
            SELECT e.id AS employee_pkid, e.employee_code, e.lastname, e.firstname, e.middlename, e.hired_date, es.name AS employee_status,
            	cs.title AS section_name,
            	COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name,  
               	COALESCE(ejh.name,(
	                SELECT name FROM `g_employee_job_history`
	                WHERE employee_id = e.id 
	                    AND end_date <> ''
	                ORDER BY end_date DESC 
	                LIMIT 1
                ))AS position
			FROM ". G_EMPLOYEE ." e
				INNER JOIN " . COMPANY_STRUCTURE . " cs ON e.section_id = cs.id 
				INNER JOIN " . G_SETTINGS_EMPLOYEE_STATUS . " es ON e.employee_status_id = es.id
                INNER JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
                INNER JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON esh.employee_id = ejh.employee_id AND ejh.end_date = ''
				{$sql_add_query}                
                " . $search . "
            ORDER BY e.lastname ASC
        ";
	
		$result = Model::runSql($sql,true);		
		return $result;
	}	

	public static function getEmployeeIncentiveReportB($query, $add_query = '') {
		$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}

		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			if($query['search_field'] == 'birthdate') {
				$query_search = $query['birthdate'];
			}else{
				$query_search = $query['search'];
			}
			$search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);				
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}

		if( $query['leave_id'] > 0 ){
			$search .= " AND lt.leave_id =" . Model::safeSql($query['leave_id']);
		}

        $sql = "
            SELECT e.id AS employee_pkid, e.employee_code, e.lastname, e.firstname, e.middlename, e.hired_date, es.name AS employee_status,
            	cs.title AS section_name,
            	COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name,  
               	COALESCE(ejh.name,(
	                SELECT name FROM `g_employee_job_history`
	                WHERE employee_id = e.id 
	                    AND end_date <> ''
	                ORDER BY end_date DESC 
	                LIMIT 1
                ))AS position,
                lt.credits_added, lt.date_added,
                l.name as leave_type
			FROM ". G_EMPLOYEE ." e
				INNER JOIN " . COMPANY_STRUCTURE . " cs ON e.section_id = cs.id 
				INNER JOIN " . G_SETTINGS_EMPLOYEE_STATUS . " es ON e.employee_status_id = es.id
                INNER JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
                INNER JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON esh.employee_id = ejh.employee_id AND ejh.end_date = ''
                INNER JOIN " . EMPLOYEE_LEAVE_CREDIT_HISTORY . " lt ON e.id = lt.employee_id
                INNER JOIN " . G_LEAVE. " l ON lt.leave_id = l.id
			WHERE YEAR(lt.date_added) =" . Model::safeSql($query['incentive_leave_year']) . "
				{$sql_add_query}                
                " . $search . "
            ORDER BY lt.date_added ASC
        ";

        // echo $sql;
        
		$result = Model::runSql($sql,true);		
		return $result;
	}	

	public static function getEmployeesTotalLeaveCreditsGeneralIncentive($query, $add_query = '') {
		$year = $query['year'];

    	$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}

		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			if($query['search_field'] == 'birthdate') {
				$query_search = $query['birthdate'];
			}else{
				$query_search = $query['search'];
			}
			$search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);				
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}
        
        if($query['status'] != '' && $query['status'] != 'all'){
			$search .= " AND elr.is_approved =" . Model::safeSql($query['status']);			
		}

        if ($query['project_site_id'] != '' && $query['project_site_id'] != 'all') {
            $search .= " AND e.project_site_id =" . Model::safeSql($query['project_site_id']);
        }
        
        $sql = "
            SELECT e.id as employee_pkid, SUM(la.no_of_days_alloted)AS total_leave_credits
			FROM ". G_EMPLOYEE ." e                
                INNER JOIN " . G_EMPLOYEE_LEAVE_AVAILABLE . " la ON la.employee_id = e.id
                INNER JOIN " . G_LEAVE. " l ON la.leave_id = l.id
			WHERE 
				l.gl_is_archive =" . Model::safeSql(G_Leave::NO) . " 
				AND e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "               
				AND (la.leave_id = 10 OR la.leave_id = 11)
				{$sql_add_query}				
                " . $search . "
            GROUP BY la.employee_id
        ";

		$result = Model::runSql($sql,true);		
		return $result;
	}	

	public static function getEmployeesTotalLeaveCredits($query, $add_query = '') {
		$year = $query['year'];

    	$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}

		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			if($query['search_field'] == 'birthdate') {
				$query_search = $query['birthdate'];
			}else{
				$query_search = $query['search'];
			}
			$search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);				
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}
        
        if($query['status'] != '' && $query['status'] != 'all'){
			$search .= " AND elr.is_approved =" . Model::safeSql($query['status']);			
		}

        if ($query['project_site_id'] != '' && $query['project_site_id'] != 'all') {
            $search .= " AND e.project_site_id =" . Model::safeSql($query['project_site_id']);
        }
        
        $sql = "
            SELECT e.id as employee_pkid, SUM(la.no_of_days_alloted)AS total_leave_credits
			FROM ". G_EMPLOYEE ." e                
                INNER JOIN " . G_EMPLOYEE_LEAVE_AVAILABLE . " la ON la.employee_id = e.id
                INNER JOIN " . G_LEAVE. " l ON la.leave_id = l.id
			WHERE 
				la.covered_year =" . Model::safeSql($year) . " AND l.gl_is_archive =" . Model::safeSql(G_Leave::NO) . " 
				AND e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "               
				{$sql_add_query}				
                " . $search . "
            GROUP BY la.employee_id
        ";

		$result = Model::runSql($sql,true);		
		return $result;
	}

	public static function getLeaveCreditsData($query, $add_query = '') {
		$year = $query['year'];

    	$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}

		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			if($query['search_field'] == 'birthdate') {
				$query_search = $query['birthdate'];
			}else{
				$query_search = $query['search'];
			}
			$search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);				
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}
        
        if($query['status'] != '' && $query['status'] != 'all'){
			$search .= " AND elr.is_approved =" . Model::safeSql($query['status']);			
		}

        if ($query['project_site_id'] != '' && $query['project_site_id'] != 'all') {
            $search .= " AND e.project_site_id =" . Model::safeSql($query['project_site_id']);
        }
        
        $sql = "
            SELECT e.id AS employee_pkid, e.employee_code, e.lastname, e.firstname, e.middlename, e.hired_date, es.name AS employee_status,
            	cs.title AS section_name,e.project_site_id,
            	COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name,  
               	COALESCE(ejh.name,(
	                SELECT name FROM `g_employee_job_history`
	                WHERE employee_id = e.id 
	                    AND end_date <> ''
	                ORDER BY end_date DESC 
	                LIMIT 1
                ))AS position,
                l.id AS leave_id , l.name as leave_type, la.no_of_days_available, la.no_of_days_used, la.no_of_days_alloted, l.name as leave_type
			FROM ". G_EMPLOYEE ." e
				LEFT JOIN " . COMPANY_STRUCTURE . " cs ON e.section_id = cs.id 
				INNER JOIN " . G_SETTINGS_EMPLOYEE_STATUS . " es ON e.employee_status_id = es.id
                INNER JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
                INNER JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON esh.employee_id = ejh.employee_id AND ejh.end_date = ''                
                INNER JOIN " . G_EMPLOYEE_LEAVE_AVAILABLE . " la ON la.employee_id = e.id
                INNER JOIN " . G_LEAVE. " l ON la.leave_id = l.id
                
			WHERE 
				l.gl_is_archive =" . Model::safeSql(G_Leave::NO) . " 
				AND e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "               
				{$sql_add_query}				
                " . $search . "
        ";

		$result = Model::runSql($sql,true);		
		return $result;
	}

	public static function getLeaveCreaditsDataGeneralIncentive($query, $add_query = '') {
		$year = $query['year'];

    	$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}

		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			if($query['search_field'] == 'birthdate') {
				$query_search = $query['birthdate'];
			}else{
				$query_search = $query['search'];
			}
			$search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);				
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}
        
        if($query['status'] != '' && $query['status'] != 'all'){
			$search .= " AND elr.is_approved =" . Model::safeSql($query['status']);			
		}
        
        $sql = "
            SELECT e.id AS employee_pkid, e.employee_code, e.lastname, e.firstname, e.middlename, e.hired_date, es.name AS employee_status,
            	cs.title AS section_name,
            	COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name,  
               	COALESCE(ejh.name,(
	                SELECT name FROM `g_employee_job_history`
	                WHERE employee_id = e.id 
	                    AND end_date <> ''
	                ORDER BY end_date DESC 
	                LIMIT 1
                ))AS position,
                l.id AS leave_id , l.name as leave_type, la.no_of_days_available, la.no_of_days_used, la.no_of_days_alloted, l.name as leave_type
			FROM ". G_EMPLOYEE ." e
				INNER JOIN " . COMPANY_STRUCTURE . " cs ON e.section_id = cs.id 
				INNER JOIN " . G_SETTINGS_EMPLOYEE_STATUS . " es ON e.employee_status_id = es.id
                INNER JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
                INNER JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON esh.employee_id = ejh.employee_id AND ejh.end_date = ''                
                INNER JOIN " . G_EMPLOYEE_LEAVE_AVAILABLE . " la ON la.employee_id = e.id
                INNER JOIN " . G_LEAVE. " l ON la.leave_id = l.id
                
			WHERE 
				la.covered_year =" . Model::safeSql($year) . " AND l.gl_is_archive =" . Model::safeSql(G_Leave::NO) . " 
				AND e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "           
				AND (l.name LIKE '%General%' OR l.name LIKE '%Incentive%')   
				{$sql_add_query}				
                " . $search . "
        ";

		$result = Model::runSql($sql,true);		
		return $result;
	}	

	public static function sqlGetAllAnnualizedTaxByYear($year = 0) {
		$sql = "
			SELECT e.id as employee_pkid, e.company_structure_id, e.employee_code, e.lastname, e.firstname, e.middlename, e.hired_date, es.status AS employee_status,
            	cs.title AS section_name,
            	COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id                         
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name,  
               	COALESCE(ejh.name,(
	                SELECT name FROM `g_employee_job_history`
	                WHERE employee_id = e.id 	                    
	                ORDER BY end_date DESC 
	                LIMIT 1
                ))AS position,
				t.cutoff_start_date, t.cutoff_end_date, t.gross_income_tax, t.less_personal_exemption, t.taxable_income, t.tax_due, t.tax_withheld_payroll, t.tax_refund_payable
			FROM  " . EMPLOYEE . " e
				LEFT JOIN " . ANNUALIZE_TAX . " t ON e.id = t.employee_id
				INNER JOIN " . EMPLOYMENT_STATUS . " es ON e.employee_status_id = es.id	               
                INNER JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id 
                INNER JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON e.id = ejh.employee_id 
                INNER JOIN " . COMPANY_STRUCTURE . " cs ON e.section_id = cs.id      			
			WHERE t.year =" . Model::safeSql($year) . "
			GROUP BY e.employee_code
			ORDER BY e.lastname
		";	
		
		$result = Model::runSql($sql,true);
		return $result;
	}

	public static function getEmployeeDataByIdDepre02092017( $id, $year = null ) {

		if(empty($year))  {
			$year = date('Y');
		}

        $sql = "
            SELECT e.id AS employee_pkid, e.employee_code, e.lastname, e.firstname, e.middlename, e.hired_date, es.status AS employment_status, est.name AS employee_status, e.sss_number, e.sss_number, e.philhealth_number,e.year_working_days,
            	cd.address, cd.city, cd.province, cd.zip_code,
            	e.extension_name,e.resignation_date,e.endo_date,e.terminated_date, e.birthdate, 
            	cs.title AS section_name,
            	COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id                        
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name,  
                COALESCE((
	                SELECT basic_salary FROM `g_employee_basic_salary_history`
	                WHERE employee_id = e.id 	                    
	                ORDER BY start_date DESC 
	                LIMIT 1
                ),0)AS present_salary,
               	COALESCE(ejh.name,(
	                SELECT name FROM `g_employee_job_history`
	                WHERE employee_id = e.id 	                    
	                ORDER BY end_date DESC 
	                LIMIT 1
                ))AS position, e.tin_number, e.hired_date, e.number_dependent, e.marital_status             
			FROM ". G_EMPLOYEE ." e
				LEFT JOIN " . COMPANY_STRUCTURE . " cs ON e.section_id = cs.id 
				LEFT JOIN " . G_EMPLOYEE_CONTACT_DETAILS . " cd ON e.id = cd.employee_id
				LEFT JOIN " . EMPLOYMENT_STATUS . " es ON e.employment_status_id = es.id	        
				LEFT JOIN " . G_SETTINGS_EMPLOYEE_STATUS . " est ON e.employee_status_id = est.id	        
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON esh.employee_id = ejh.employee_id
			WHERE e.id =" . Model::safeSql($id) . "
			AND 
				(
					e.employee_status_id = 1
					OR 
					( e.employee_status_id = 2 AND YEAR(e.resignation_date) = " . $year . ")
					OR
					( e.employee_status_id = 3 AND YEAR(e.terminated_date) = " . $year . ")
					OR
					( e.employee_status_id = 4 AND YEAR(e.endo_date) = " . $year . ")
					OR
					( e.employee_status_id = 5 AND YEAR(e.inactive_date) = " . $year . ")
				)
            ORDER BY e.id DESC
            LIMIT 1
        ";

        // echo $sql;
        // exit;
 
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}

	public static function getEmployeeDataById( $id, $year = null ) {

		if(empty($year))  {
			$year = date('Y');
		}

        $sql = "
            SELECT e.id AS employee_pkid, e.employee_code, e.lastname, e.firstname, e.middlename, e.hired_date, es.status AS employment_status, est.name AS employee_status, e.sss_number, e.sss_number, e.philhealth_number,e.year_working_days,
            	cd.address, cd.city, cd.province, cd.zip_code,
            	e.extension_name,e.resignation_date,e.endo_date,e.terminated_date, e.birthdate, 
            	cs.title AS section_name,
            	COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id                        
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name,  
                COALESCE((
	                SELECT basic_salary FROM `g_employee_basic_salary_history`
	                WHERE employee_id = e.id 	                    
	                ORDER BY start_date DESC 
	                LIMIT 1
                ),0)AS present_salary,
               	COALESCE(ejh.name,(
	                SELECT name FROM `g_employee_job_history`
	                WHERE employee_id = e.id 	                    
	                ORDER BY end_date DESC 
	                LIMIT 1
                ))AS position, e.tin_number, e.hired_date, e.number_dependent, e.marital_status             
			FROM ". G_EMPLOYEE ." e
				LEFT JOIN " . COMPANY_STRUCTURE . " cs ON e.section_id = cs.id 
				LEFT JOIN " . G_EMPLOYEE_CONTACT_DETAILS . " cd ON e.id = cd.employee_id
				LEFT JOIN " . EMPLOYMENT_STATUS . " es ON e.employment_status_id = es.id	        
				LEFT JOIN " . G_SETTINGS_EMPLOYEE_STATUS . " est ON e.employee_status_id = est.id	        
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON esh.employee_id = ejh.employee_id
			WHERE e.id =" . Model::safeSql($id) . "
			AND 
				(
					e.employee_status_id = 1
					OR 
					( e.employee_status_id = 2)
					OR
					( e.employee_status_id = 3)
					OR
					( e.employee_status_id = 4)
					OR
					( e.employee_status_id = 5)
				)
            ORDER BY e.id DESC
            LIMIT 1
        ";
 
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}	

	public static function getAllActiveEmployee( $sql_add_query = '' ) {

        $sql = "
            SELECT e.id, e.employee_code, e.lastname, e.firstname, 
            	es.status, 
            	COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name,  
               	COALESCE(ejh.name,(
	                SELECT name FROM `g_employee_job_history`
	                WHERE employee_id = e.id 
	                    AND end_date <> ''
	                ORDER BY end_date DESC 
	                LIMIT 1
                ))AS position,
                (SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name 			
			FROM ". G_EMPLOYEE ." e
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON esh.employee_id = ejh.employee_id AND ejh.end_date = ''
                LEFT JOIN " . EMPLOYMENT_STATUS . " es ON e.employment_status_id = es.id                               
			WHERE e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "
				{$sql_add_query}                               
        "; 		
		$result = Model::runSql($sql,true);		
		return $result;
	}

	public static function countEmployeeWithUpcomingBirthday() {
		$start_date = date("m-d", strtotime("+1 day"));
		$end_date = date("m-d", strtotime("+30 days"));

        $sql = "
            SELECT COUNT(e.id) as total
            FROM " . EMPLOYEE . " e
            WHERE DATE_FORMAT(e.birthdate,'%m-%d') >= '{$start_date}'
            	AND DATE_FORMAT(e.birthdate,'%m-%d') <= '{$end_date}' 
            	AND e.employee_status_id =" . Model::safeSql(1) . "
            	AND e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "
        ";

        $result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
    }

    public static function countEmployeeWithBirthdayToday() {
		$date = date("m-d");

        $sql = "
            SELECT COUNT(e.id) as total
            FROM " . EMPLOYEE . " e
            WHERE DATE_FORMAT(e.birthdate,'%m-%d') = '{$date}' 
            	AND e.employee_status_id =" . Model::safeSql(1) . "
            	AND e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "
        ";

        $result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
    }

    public static function getGovernmentRemittances($query) {
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}

        /*$sql = "
            SELECT e.employee_code, CONCAT(e.lastname, ', ' , e.firstname) as employee_name, 
                ejh.name as position, ep.labels, esh.name as department_name,
                ep.sss,
		        ep.philhealth,
		        ep.pagibig,
				ep.period_start,
				ep.period_end,
				sss.company_share,
				sss.company_ec
			FROM ". G_EMPLOYEE ." e
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id 
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON esh.employee_id = ejh.employee_id 
                LEFT JOIN " . G_EMPLOYEE_PAYSLIP . " ep ON e.id = ep.employee_id 
                LEFT JOIN p_sss sss ON ep.sss = sss.employee_share
			WHERE ep.period_start >= " . Model::safeSql($query['date_from']) . "
                AND ep.period_end <= " . Model::safeSql($query['date_to']) . " 
                AND e.id = " . Model::safeSql($query['employee_id']) . "
        ";*/ 			

        $sql = "
            SELECT e.employee_code, CONCAT(e.lastname, ', ' , e.firstname) as employee_name, 
                ep.labels,
                ep.deductions,
                ep.other_deductions,
                ep.sss,
		        ep.philhealth,
		        ep.pagibig,
				ep.period_start,
				ep.period_end,
				sss.company_share,
				sss.company_ec
			FROM ". G_EMPLOYEE_PAYSLIP ." ep
				LEFT JOIN " . G_EMPLOYEE . " e ON ep.employee_id = e.id
				LEFT JOIN p_sss sss ON ep.sss = sss.employee_share
			WHERE ep.period_start >= " . Model::safeSql($query['date_from']) . "
                AND ep.period_end <= " . Model::safeSql($query['date_to']) . " 
                AND e.id = " . Model::safeSql($query['employee_id']) . "
        ";

		$result = Model::runSql($sql,true);		

		$data = array();
		foreach($result as $key => $value) {
			$date = date('Y-m',strtotime($value['period_end']));

			$labels = unserialize($value['labels']);
			$deductions = unserialize($value['deductions']);
			$o_deductions = unserialize($value['other_deductions']);
			
			foreach($labels as $l_key => $l_value) {
				if($l_value->variable == 'sss_er') {
					//$er_sss= $l_value->value / 2;
					//$er_sss= $l_value->value;					
				}elseif($l_value->variable == 'pagibig_er') {
					$er_pagibig = $l_value->value; 
				}elseif($l_value->variable == 'philhealth_er') {
					$er_philhealth = $l_value->value;
				}			
			}		

			if(array_key_exists($date, $data)) {
				$data[$date]['ee_sss'] 			+= $value['sss'];
				$data[$date]['ee_philhealth'] 	+= $value['philhealth'];
				$data[$date]['ee_pagibig'] 		+= $value['pagibig'];
				$data[$date]['er_sss'] 			+= $value['company_share'] + $value['company_ec'];
				$data[$date]['er_philhealth'] 	+= $er_philhealth;
				$data[$date]['er_pagibig'] 		+= $er_pagibig;
			}else{
				$data[$date] = array(
					'year' 			=> date('Y',strtotime($value['period_end'])),
					'month' 		=> date('F',strtotime($value['period_end'])),
					'date_paid' 	=> $value['period_end'],
					'ee_sss' 		=> $value['sss'],
					'ee_philhealth' => $value['philhealth'],
					'ee_pagibig' 	=> ($value['pagibig']),
					'er_sss' 		=> $value['company_share'] + $value['company_ec'],
					'er_philhealth' => $er_philhealth,
					'er_pagibig' 	=> $er_pagibig,
					);
			}

		}

		return $data;
	}

	public static function getGovernmentRemittancesLoans($query) {
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}

		if($query['remittance_type'] == 'sss_loan') {
			$loan_id = 4; // SSS Loan
		}else{
			$loan_id = 3; // Pagibig Loan
		}
  
        /*$sql = "
            SELECT e.employee_code, CONCAT(e.lastname, ', ' , e.firstname) as employee_name, 
                esh.name as department_name, ejh.name as position,
                elps.amount_paid, elps.date_paid
			FROM g_employee_loan_payment_schedule elps 
				LEFT JOIN g_employee e ON elps.employee_id = e.id
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id 
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON esh.employee_id = ejh.employee_id 
			WHERE elps.date_paid >= " . Model::safeSql($query['date_from']) . "
                AND elps.date_paid <= " . Model::safeSql($query['date_to']) . " 
                AND elps.employee_id = " . Model::safeSql($query['employee_id']) . " 
                AND elps.is_lock = 'Yes' 
                AND elps.loan_id = " . Model::safeSql($loan_id) . " 
            GROUP BY elps.id
        ";*/

        $sql = "
            SELECT e.employee_code, CONCAT(e.lastname, ', ' , e.firstname) as employee_name, 
                esh.name as department_name, ejh.name as position,
                elps.amount_paid, elps.date_paid
			FROM g_employee_loan_payment_schedule elps 
				LEFT JOIN g_employee_loan el ON elps.loan_id = el.id
				LEFT JOIN g_employee e ON el.employee_id = e.id
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id 
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON esh.employee_id = ejh.employee_id 
			WHERE elps.date_paid >= " . Model::safeSql($query['date_from']) . "
                AND elps.date_paid <= " . Model::safeSql($query['date_to']) . " 
                AND elps.employee_id = " . Model::safeSql($query['employee_id']) . " 
                AND elps.is_lock = 'Yes' 
                AND el.loan_type_id = " . Model::safeSql($loan_id) . " 
                AND el.is_archive = " . Model::safeSql('No') . " 
            GROUP BY elps.id
        ";

		$result = Model::runSql($sql,true);		

		$data = array();
		foreach($result as $key => $value) {
			$date = date('Y-m',strtotime($value['date_paid']));
			if(array_key_exists($date, $data)) {
				$data[$date]['amount'] 			+= $value['amount_paid'];
			}else{
				$data[$date] = array(
					'year' 			=> date('Y',strtotime($value['date_paid'])),
					'month' 		=> date('F',strtotime($value['date_paid'])),
					'amount' 		=> $value['amount_paid']
					);
			}
		}

		return $data;
	}

	public static function getEmployeeSectionByGroup($date = NULL, $add_query = NULL) {

		$date   = date("Y-m-d",strtotime($date));

		$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}		

		/*$sql = "
				SELECT COUNT(e.id) as total_employees, e.section_id, cs.title FROM g_employee e 
				LEFT JOIN g_company_structure cs ON e.section_id = cs.id 
				WHERE e.hired_date <= " . Model::safeSql($date) . " AND e.e_is_archive ='No'
				{$sql_add_query} 
				GROUP BY e.section_id 
			";*/

		$sql = "
				SELECT e.section_id, e.department_company_structure_id, cs.title FROM g_employee e 
				LEFT JOIN g_company_structure cs ON e.section_id = cs.id 
				WHERE e.hired_date <= " . Model::safeSql($date) . " AND e.e_is_archive ='No'
				{$sql_add_query} 
				GROUP BY e.section_id, e.department_company_structure_id 
			";

		$result = Model::runSql($sql,true);		
		return $result;

	}

	public static function getCurrentEmployeeByYear($year = '') {
		$sql_active_only = "
			SELECT id, firstname, lastname 
			FROM " . EMPLOYEE . "
			WHERE (resignation_date = ". Model::safeSql("0000-00-00") .") AND 
			(terminated_date = ". Model::safeSql("0000-00-00") .") AND 
			(endo_date = ". Model::safeSql("0000-00-00") .") AND 
			(inactive_date = ". Model::safeSql("0000-00-00") .")
			ORDER BY id

		";

		$sql = "
			SELECT id, firstname, lastname 
			FROM " . EMPLOYEE . "
			WHERE (resignation_date = ". Model::safeSql("0000-00-00") ." OR YEAR(resignation_date) = ". $year ." ) AND 
			(terminated_date = ". Model::safeSql("0000-00-00") ." OR YEAR(terminated_date) = ". $year .") AND 
			(endo_date = ". Model::safeSql("0000-00-00") ." OR YEAR(endo_date) = ". $year .") AND 
			(inactive_date = ". Model::safeSql("0000-00-00") ." OR YEAR(inactive_date) = ". $year .")
			ORDER BY id
		";

		$result = Model::runSql($sql);
		$count = 1;
		while ($row = Model::fetchAssoc($result)) {
			$data[] = $row['id'];	
			//$data[$count]['id'] = $row['id'];	
			//$data[$count]['full_name'] = $row['firstname'] . " " . $row['lastname'];	
		$count++;
		}

		return $data;
	}	

	public static function findAllEmployeesByEmploymentStatus($employement_status_id = []){
		$employement_status_id = join(',', $employement_status_id);
		$sql = "
		SELECT id, concat(lastname,' ',firstname) as name
		FROM  g_employee e
		WHERE e.e_is_archive =" . Model::safeSql(G_Employee::NO) . " 
		AND e.employment_status_id in (".$employement_status_id.")
		AND (e.terminated_date = '0000-00-00' OR e.terminated_date = '')
		AND (e.endo_date = '0000-00-00' OR e.endo_date = '')
		AND (e.inactive_date = '0000-00-00' OR e.inactive_date = '')
		AND (e.resignation_date = '0000-00-00' OR e.resignation_date = '')
		ORDER BY concat(lastname,' ',firstname) ASC
		";
		
		//var_dump($sql);
		
		$result = Model::runSql($sql,true);		
		return $result;
	}

	public static function findAllExcludedEmployeeById($employee_ids = ""){
		$employement_status_id = join(',', $employement_status_id);
		$sql = "
		SELECT id, employee_code, concat(lastname,' ',firstname) as name
		FROM  g_employee e
		WHERE e.e_is_archive =" . Model::safeSql(G_Employee::NO) . " 
		AND id in (".$employee_ids.")
		";
		
		//var_dump($sql);
		
		$result = Model::runSql($sql,true);		
		return $result;
	}


    public static function getAllDeviceNo()
    {
         $sql = "
            SELECT 
                id,
                machine_no,
                device_name
            from
            zk_device";
        $data =  Model::runSql($sql, true);
        return $data;
    }

    public static function findDeviceNo($device_no)
    {
         $sql = "
            SELECT 
                machine_no
            from
            zk_device
            where machine_no = ". Model::safeSql($device_no).
            " LIMIT 1";
        $data =  Model::runSql($sql, true);
        return $data;
    }


}
?>