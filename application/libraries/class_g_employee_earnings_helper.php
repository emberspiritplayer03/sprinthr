<?php
class G_Employee_Earnings_Helper {
    /*
     * @return Array intance of Earning
     */
    public static function getOtherEarnings(IEmployee $e, G_Cutoff_Period $p) {
        $ees = G_Employee_Earnings_Finder::findAllIsNotArchiveAndISApprovedByPeriodId($p->getId());        
        $filtered_earnings = self::filterEmployeeEarningsByEmployeeId($e->getId(), $ees);               
        foreach ($filtered_earnings as $earning) {

            if ($earning->isApproved() && !$earning->isArchived()) {

                if ($earning->isTaxable()) {
                    $is_taxable = Earning::TAXABLE;
                } else {
                    $is_taxable = Earning::NON_TAXABLE;
                }
                $earn = new Earning($earning->getTitle(), $earning->getAmount(), $is_taxable);
                $earnings[] = $earn;
            }            
        }
        return $earnings;
    }

    public static function approve(G_Employee_Earnings $ee) {
        G_Employee_Earnings_Manager::approve($ee);
        $ee->setStatus(G_Employee_Earnings::APPROVED);
        G_Employee_Earnings_Helper::addToPayslip($ee);
    }

    public static function disapprove(G_Employee_Earnings $ee) {
        G_Employee_Earnings_Manager::disapprove($ee);
        $ee->setStatus(G_Employee_Earnings::PENDING);
        G_Employee_Earnings_Helper::addToPayslip($ee);
    }

    public static function archive(G_Employee_Earnings $ee) {
        G_Employee_Earnings_Manager::archive($ee);
        $ee->setIsArchive(G_Employee_Earnings::YES);
        //G_Employee_Earnings_Helper::addToPayslip($ee); //Will reprocess payslip
    }

    /*
     * Filters by employee id
     *
     * @param array $ees Array instance of G_Employee_Earnings
     * @return Array instance of G_Employee_Earnings
     */
    private static function filterEmployeeEarningsByEmployeeId($employee_id, $ees) {
        $found_earnings = array();
        foreach ($ees as $ee) {
            if ($ee->isApplyToAllEmployees()) {
                $found_earnings[] = $ee;
            } else {
            	$has_earnings = false;
            	// by EMPLOYEE ID
                $employee_ids = self::getEmployeeIds($ee);
                foreach ($employee_ids as $emp_id) {
                    if ($emp_id == $employee_id) {
                        $found_earnings[] = $ee;
                        $has_earnings = true;
                    }
                }

                $fields = array("id","department_company_structure_id","section_id","employment_status_id");
                $e = G_Employee_Helper::sqlGetEmployeeDetailsById($employee_id,$fields);
                if($e) {
                	// By Department Id
            		$dept_section = self::getDepartmentSectionIds($ee);
	                if(in_array($e["department_company_structure_id"],$dept_section) && !$has_earnings) {
	                	$found_earnings[] = $ee;
                    	$has_earnings = true;
	                }

	                // By Section Id
	                if(in_array($e["section_id"],$dept_section) && !$has_earnings) {
	                	$found_earnings[] = $ee;
                    	$has_earnings = true;
	                }
                	
                	// By Employement Status Id
            		$emp_status = self::getEmploymentStatusIds($ee);
	                if(in_array($e["employment_status_id"],$emp_status)  && !$has_earnings) {
	                	$found_earnings[] = $ee;
                    	$has_earnings = true;
	                }
                	
                }
                
            }
        }
        return $found_earnings;
    }

	public static function isIdExist(G_Employee_Earnings $gee) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_EARNINGS ."
			WHERE id = ". Model::safeSql($gee->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}	
	
	public static function countTotalRecordsByCompanyStructureId($company_structure_id) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_EARNINGS ."
			WHERE company_structure_id = ". Model::safeSql($company_structure_id) ."
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}	
	
	public static function sumTotalIsNotArchiveEarningByCompanyStructureIdAndPayrollPeriodId($payroll_period_id,$company_structure_id) {
		$sql = "
			SELECT SUM(amount) as total
			FROM " . G_EMPLOYEE_EARNINGS ."
			WHERE company_structure_id = ". Model::safeSql($company_structure_id) ." 
				AND payroll_period_id = " . Model::safeSql($payroll_period_id) . "				
				AND is_archive =" . Model::safeSql(G_Employee_Earnings::NO) . "			
		";			
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);		
		return $row['total'];
	}	
	
	public static function sumTotalIsNotArchiveApproveEarningByCompanyStructureIdAndPayrollPeriodId($payroll_period_id,$company_structure_id) {
		$sql = "
			SELECT SUM(amount) as total
			FROM " . G_EMPLOYEE_EARNINGS ."
			WHERE company_structure_id = ". Model::safeSql($company_structure_id) ." 
				AND payroll_period_id = " . Model::safeSql($payroll_period_id) . "
				AND status =" . Model::safeSql(G_Employee_Earnings::APPROVED) . "
				AND is_archive =" . Model::safeSql(G_Employee_Earnings::NO) . "			
		";			
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);		
		return $row['total'];
	}	
	
	public static function sumTotalIsArchiveEarningByCompanyStructureIdAndPayrollPeriodId($payroll_period_id,$company_structure_id) {
		$sql = "
			SELECT SUM(amount) as total
			FROM " . G_EMPLOYEE_EARNINGS ."
			WHERE company_structure_id = ". Model::safeSql($company_structure_id) ." 
				AND payroll_period_id = " . Model::safeSql($payroll_period_id) . "				
				AND is_archive =" . Model::safeSql(G_Employee_Earnings::YES) . "			
		";			
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);		
		return $row['total'];
	}	
	
	public static function sumTotalIsNotArchivePendingEarningByCompanyStructureIdAndPayrollPeriodId($payroll_period_id,$company_structure_id) {
		$sql = "
			SELECT SUM(amount) as total
			FROM " . G_EMPLOYEE_EARNINGS ."
			WHERE company_structure_id = ". Model::safeSql($company_structure_id) ." 
				AND payroll_period_id = " . Model::safeSql($payroll_period_id) . "
				AND status =" . Model::safeSql(G_Employee_Earnings::PENDING) . "
				AND is_archive =" . Model::safeSql(G_Employee_Earnings::NO) . "			
		";			
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);		
		return $row['total'];
	}	
	
	public static function countTotalRecordsByEmployeeId(G_Employee $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_EARNINGS ."
			WHERE employee_id = ". Model::safeSql($e->getId()) ."
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

    /*
     * Add values of G_Employee_Earnings to payslip
     */
    public static function addToPayslip(G_Employee_Earnings $ee) {
        if ($ee) {
            if ($ee->getApplyToAllEmployee() == G_Employee_Earnings::YES) {
                $period = self::getPeriod($ee);
                $pg = new G_Payslip_Generator($period);
                $employees = G_Employee_Finder::findAllActiveByDate($period->getStartDate());
                $pg->setEmployees($employees);
                $payslips = $pg->generate();
                $pg->save($payslips);
                return $payslips;
            } else {
                $employee_ids = self::getEmployeeIds($ee);
                $period = self::getPeriod($ee);
                return G_Payslip_Helper::generatePayslipByEmployeeIdsPeriod($employee_ids, $period);
            }
        }
    }

    private static function getPeriod(G_Employee_Earnings $ee) {
        $period_id = $ee->getPayrollPeriodId();
        $period = G_Cutoff_Period_Finder::findById($period_id);
        if ($period) {
            return $period;
        }
    }

    private static function getEmployeeIds(G_Employee_Earnings $ee) {
        $eids = unserialize($ee->getEmployeeId());
        return explode(',', $eids);
    }

    private static function getDepartmentSectionIds(G_Employee_Earnings $ee) {
        $eids = unserialize($ee->getDepartmentSectionId());
        return explode(',', $eids);
    }

    private static function getEmploymentStatusIds(G_Employee_Earnings $ee) {
        $eids = unserialize($ee->getEmploymentStatusId());
        return explode(',', $eids);
    }
	
	public static function addEarningsToPayslip($e,$from,$to) {
		
		$cp = G_Cutoff_Period_Finder::findByPeriod($from,$to);
		
		if($cp){			
			$earnings = G_Employee_Earnings_Finder::findAllApprovedByPayrollPeriodIdAndCompanyStructureIdAndIsNotArchive($cp->getId(),$_SESSION['sprint_hr']['company_structure_id']);
			//print_r($earnings);
			foreach($earnings as $ea){
				//Convert to array employee id
				$eid = unserialize($ea->getEmployeeId());
				$eAr = explode(",",$eid);				
				//print_r($eAr);
				//Search in array 				
				$is_removed = self::removeOtherEarnings($ea->getTitle(),$cp,$e);
				if(in_array("All Employee", $eAr)){	
					$is_saved = self::addToOtherEarnings($ea->getTitle(),$ea->getAmount(),$ea->getTaxable(),$cp,$e);
				}else{				
					if(in_array($e->getId(), $eAr)){						
						$is_saved = self::addToOtherEarnings($ea->getTitle(),$ea->getAmount(),$ea->getTaxable(),$cp,$e);
					}
				}
			}
		}
	}	
	
	private static function addToOtherEarnings($label,$amount,$is_taxable,$cp,$e) {
		$p = G_Payslip_Finder::findByEmployeeAndPeriod($e, $cp->getStartDate(), $cp->getEndDate());
		if($p){
			$ph    = new G_Payslip_Helper($p);
			$ear[] = $ear_obj = new Earning($label, $amount, $taxable, (int) $earning_type);	
			
			$gross_pay = $ph->computeTotalEarnings();
						
			if($is_taxable == G_Employee_Earnings::YES){
				$taxable = Earning::TAXABLE;
			}else{
				$taxable = 0;
			}
					
			$p->addOtherEarnings($ear);			
			$p->setGrossPay($gross_pay);
			$p->setNetPay($net_pay);
			$p->save();
			
			$ph    = new G_Payslip_Helper($p);
			$gross_pay = $ph->computeTotalEarnings();
			$net_pay   = $gross_pay - $ph->computeTotalDeductions();
			$p->setNetPay($net_pay);
			$p->setGrossPay($gross_pay);
			$p->save();
			return true;
		}
	}
	
	private static function removeOtherEarnings($label,$cp,$e) {
		$p = G_Payslip_Finder::findByEmployeeAndPeriod($e, $cp->getStartDate(), $cp->getEndDate());
		if($p){
			$ph = new G_Payslip_Helper($p);
			if($ph){
				$p->removeOtherEarning($label);
		
				$gross_pay = $ph->computeTotalEarnings();
				$p->setGrossPay($gross_pay);	
				
				$net_pay = $gross_pay - $ph->computeTotalDeductions();
				$p->setNetPay($net_pay);	
					
				$p->save();
			}			
		}
		return true;
	}

	public static function sqlAllApprovedAndIsNotArchivedEearningsAppliedByEmployeeIdAndByCutoffPeriodId($employee_id = 0, $cutoff_period = 0, $fields = array()){

		if( !empty($fields) ){
			$sql_fields = implode(",", $fields);
		}else{
			$sql_fields = " * ";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . G_EMPLOYEE_EARNINGS . "
			WHERE object_id =" . Model::safeSql($employee_id) . "
				AND applied_to =" . Model::safeSql(G_Employee_Earnings::APPLIED_TO_EMPLOYEE) . "
				AND payroll_period_id =" . Model::safeSql($cutoff_period) . "
				AND is_archive =" . Model::safeSql(G_Employee_Earnings::NO) . "
				AND status =" . Model::safeSql(G_Employee_Earnings::APPROVED) . "
			{$order_by}
			{$limit}
		";					
		$records = Model::runSql($sql,true);
		return $records;
	}

	public static function sqlAllUniqueEmployeeEarnings(){

		$sql = "
			SELECT DISTINCT(title)
			FROM " . G_EMPLOYEE_EARNINGS . "			
			{$order_by}
			{$limit}
		";							
		$records = Model::runSql($sql,true);
		return $records;
	}

	public static function sqlGetAllEmployeesLeaveConversionByYear($year = '', $employee_ids = array(), $fields = array(), $group_by = ''){

		if( !empty($fields) ){
			$sql_fields = implode(",", $fields);
		}else{
			$sql_fields = " * ";
		}

		$sql_add_query = "";
		if( !empty($employee_ids) ){
			$string_employee_ids = implode(",", $employee_ids);
			$sql_add_query = "AND ea.object_id IN({$string_employee_ids})";
		}

		$leave_conversion_tag = G_Settings_Leave_General::CONVERTIBLE_TITLE;
		$applied_to           = G_Employee_Earnings::APPLIED_TO_EMPLOYEE;
		$is_archive 		  = G_Employee_Earnings::NO;

		$sql = "
			SELECT {$sql_fields}
			FROM " . G_EMPLOYEE_EARNINGS . " ea
				LEFT JOIN " . G_CUTOFF_PERIOD . " cp ON ea.payroll_period_id = cp.id 
			WHERE cp.year_tag =" . Model::safeSql($year) . "
				AND ea.title LIKE '%{$leave_conversion_tag}%'
				AND ea.applied_to =" . Model::safeSql($applied_to) . "
				AND ea.is_archive =" . Model::safeSql($is_archive) . "
				{$sql_add_query}
			{$group_by}		
		";				
		$records = Model::runSql($sql,true);
		return $records;
	}

	public static function sqlGetEmployeesLeaveConvertedToCashByYearAndDateRange($year = '', $employee_ids = array(), $range = array(), $fields = array(), $group_by = ''){

		if( !empty($fields) ){
			$sql_fields = implode(",", $fields);
		}else{
			$sql_fields = " * ";
		}

		$sql_add_query = "";
		if( !empty($employee_ids) ){
			$string_employee_ids = implode(",", $employee_ids);
			$sql_add_query = "AND ea.object_id IN({$string_employee_ids})";
		}

		if( !empty($range) ){
			$start_date = date("Y-m-d",strtotime($range['from']));
			$end_date   = date("Y-m-d",strtotime($range['to']));
			$sql_add_query .= " AND cp.period_end BETWEEN " . Model::safeSql($start_date) . " AND " . Model::safeSql($end_date);
		}

		$leave_conversion_tag = G_Settings_Leave_General::CONVERTIBLE_TITLE;
		$applied_to           = G_Employee_Earnings::APPLIED_TO_EMPLOYEE;
		$is_archive 		  = G_Employee_Earnings::NO;

		$sql = "
			SELECT {$sql_fields}
			FROM " . G_EMPLOYEE_EARNINGS . " ea
				LEFT JOIN " . G_CUTOFF_PERIOD . " cp ON ea.payroll_period_id = cp.id 
			WHERE cp.year_tag =" . Model::safeSql($year) . "
				AND ea.title LIKE '%{$leave_conversion_tag}%'
				AND ea.applied_to =" . Model::safeSql($applied_to) . "
				AND ea.is_archive =" . Model::safeSql($is_archive) . "
				{$sql_add_query}
			{$group_by}		
		";				
		$records = Model::runSql($sql,true);
		return $records;
	}

	public static function sqlGetEmployeesLeaveTaxableConvertedToCashByYearAndDateRange($year = '', $employee_ids = array(), $range = array(), $fields = array(), $group_by = ''){

		if( !empty($fields) ){
			$sql_fields = implode(",", $fields);
		}else{
			$sql_fields = " * ";
		}

		$sql_add_query = "";
		if( !empty($employee_ids) ){
			$string_employee_ids = implode(",", $employee_ids);
			$sql_add_query = "AND ea.object_id IN({$string_employee_ids})";
		}

		if( !empty($range) ){
			$start_date = date("Y-m-d",strtotime($range['from']));
			$end_date   = date("Y-m-d",strtotime($range['to']));
			$sql_add_query .= " AND cp.period_end BETWEEN " . Model::safeSql($start_date) . " AND " . Model::safeSql($end_date);
		}

		$leave_conversion_tag = 'Taxable Converted Leave';
		$applied_to           = G_Employee_Earnings::APPLIED_TO_EMPLOYEE;
		$is_archive 		  = G_Employee_Earnings::NO;

		$sql = "
			SELECT {$sql_fields}
			FROM " . G_EMPLOYEE_EARNINGS . " ea
				LEFT JOIN " . G_CUTOFF_PERIOD . " cp ON ea.payroll_period_id = cp.id 
			WHERE cp.year_tag =" . Model::safeSql($year) . "
				AND ea.title LIKE '%{$leave_conversion_tag}%'
				AND ea.applied_to =" . Model::safeSql($applied_to) . "
				AND ea.is_taxable ='Yes'
				AND ea.is_archive =" . Model::safeSql($is_archive) . "
				{$sql_add_query}
			{$group_by}		
		";				
		$records = Model::runSql($sql,true);
		return $records;
	}

	public static function sqlGetEmployeesServiceAwardTaxableByYearAndDateRange($year = '', $employee_ids = array(), $range = array(), $fields = array(), $group_by = ''){

		if( !empty($fields) ){
			$sql_fields = implode(",", $fields);
		}else{
			$sql_fields = " * ";
		}

		$sql_add_query = "";
		if( !empty($employee_ids) ){
			$string_employee_ids = implode(",", $employee_ids);
			$sql_add_query = "AND ea.object_id IN({$string_employee_ids})";
		}

		if( !empty($range) ){
			$start_date = date("Y-m-d",strtotime($range['from']));
			$end_date   = date("Y-m-d",strtotime($range['to']));
			$sql_add_query .= " AND cp.period_end BETWEEN " . Model::safeSql($start_date) . " AND " . Model::safeSql($end_date);
		}

		$service_award_tag = 'Service Award';
		$applied_to           = G_Employee_Earnings::APPLIED_TO_EMPLOYEE;
		$is_archive 		  = G_Employee_Earnings::NO;

		$sql = "
			SELECT {$sql_fields}
			FROM " . G_EMPLOYEE_EARNINGS . " ea
				LEFT JOIN " . G_CUTOFF_PERIOD . " cp ON ea.payroll_period_id = cp.id 
			WHERE cp.year_tag =" . Model::safeSql($year) . "
				AND ea.title LIKE '%{$service_award_tag}%'
				AND ea.applied_to =" . Model::safeSql($applied_to) . "
				AND ea.is_taxable ='Yes'
				AND ea.is_archive =" . Model::safeSql($is_archive) . "
				{$sql_add_query}
			{$group_by}		
		";				
		$records = Model::runSql($sql,true);
		return $records;
	}

	public static function sqlGetEmployeesBonusTaxableByYearAndDateRange($year = '', $employee_ids = array(), $range = array(), $fields = array(), $group_by = ''){

		if( !empty($fields) ){
			$sql_fields = implode(",", $fields);
		}else{
			$sql_fields = " * ";
		}

		$sql_add_query = "";
		if( !empty($employee_ids) ){
			$string_employee_ids = implode(",", $employee_ids);
			$sql_add_query = "AND ea.object_id IN({$string_employee_ids})";
		}

		if( !empty($range) ){
			$start_date = date("Y-m-d",strtotime($range['from']));
			$end_date   = date("Y-m-d",strtotime($range['to']));
			$sql_add_query .= " AND cp.period_end BETWEEN " . Model::safeSql($start_date) . " AND " . Model::safeSql($end_date);
		}

		$bonus_tag = 'Bonus';
		$applied_to           = G_Employee_Earnings::APPLIED_TO_EMPLOYEE;
		$is_archive 		  = G_Employee_Earnings::NO;

		$sql = "
			SELECT {$sql_fields}
			FROM " . G_EMPLOYEE_EARNINGS . " ea
				LEFT JOIN " . G_CUTOFF_PERIOD . " cp ON ea.payroll_period_id = cp.id 
			WHERE cp.year_tag =" . Model::safeSql($year) . "
				AND ea.title LIKE '%{$bonus_tag}%'
				AND ea.applied_to =" . Model::safeSql($applied_to) . "
				AND ea.is_taxable ='Yes'
				AND ea.is_archive =" . Model::safeSql($is_archive) . "
				{$sql_add_query}
			{$group_by}		
		";				

		$records = Model::runSql($sql,true);
		return $records;
	}


	public static function sqlAllApprovedAndIsNotArchivedEearningsAppliedByDepartmentSectionIdAndByCutoffPeriodId($dept_section_id = 0, $cutoff_period = 0, $fields = array()){
		
		if( !empty($fields) ){
			$sql_fields = implode(",", $fields);
		}else{
			$sql_fields = " * ";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . G_EMPLOYEE_EARNINGS . "
			WHERE object_id =" . Model::safeSql($dept_section_id) . "
				AND applied_to =" . Model::safeSql(G_Employee_Earnings::APPLIED_TO_DEPARTMENT) . "
				AND payroll_period_id =" . Model::safeSql($cutoff_period) . "
				AND is_archive =" . Model::safeSql(G_Employee_Earnings::NO) . "
				AND status =" . Model::safeSql(G_Employee_Earnings::APPROVED) . "
			{$order_by}
			{$limit}
		";						
		$records = Model::runSql($sql,true);
		return $records;
	}

	public static function sqlAllApprovedAndIsNotArchivedEearningsAppliedByEmploymentStatusIdIdAndByCutoffPeriodId($employment_status_id = 0, $cutoff_period = 0, $fields = array()){
		
		if( !empty($fields) ){
			$sql_fields = implode(",", $fields);
		}else{
			$sql_fields = " * ";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . G_EMPLOYEE_EARNINGS . "
			WHERE object_id =" . Model::safeSql($employment_status_id) . "
				AND applied_to =" . Model::safeSql(G_Employee_Earnings::APPLIED_TO_EMPLOYMENT_STATUS) . "
				AND payroll_period_id =" . Model::safeSql($cutoff_period) . "
				AND is_archive =" . Model::safeSql(G_Employee_Earnings::NO) . "
				AND status =" . Model::safeSql(G_Employee_Earnings::APPROVED) . "
			{$order_by}
			{$limit}
		";						
		$records = Model::runSql($sql,true);
		return $records;
	}

	public static function sqlAllApprovedAndIsNotArchivedEearningsAppliedToAllEmployeesAndByCutoffPeriodId($cutoff_period = 0, $fields = array()){
		
		if( !empty($fields) ){
			$sql_fields = implode(",", $fields);
		}else{
			$sql_fields = " * ";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . G_EMPLOYEE_EARNINGS . "
			WHERE object_id =" . Model::safeSql(G_Employee_Earnings::APPLY_TO_ALL_ID) . "
				AND applied_to =" . Model::safeSql(G_Employee_Earnings::APPLIED_TO_ALL) . "
				AND payroll_period_id =" . Model::safeSql($cutoff_period) . "
				AND is_archive =" . Model::safeSql(G_Employee_Earnings::NO) . "
				AND status =" . Model::safeSql(G_Employee_Earnings::APPROVED) . "
			{$order_by}
			{$limit}
		";						
		$records = Model::runSql($sql,true);
		return $records;
	}
}
?>