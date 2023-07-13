<?php
class G_Employee_Deductions_Helper {
    public static function approve(G_Employee_Deductions $ee) {
        G_Employee_Deductions_Manager::approve($ee);
        $ee->setStatus(G_Employee_Deductions::APPROVED);
        G_Employee_Deductions_Helper::addToPayslip($ee);
    }

    public static function disapprove(G_Employee_Deductions $ee) {
        G_Employee_Deductions_Manager::disapprove($ee);
        $ee->setStatus(G_Employee_Deductions::PENDING);
        G_Employee_Deductions_Helper::addToPayslip($ee);
    }

    public static function archive(G_Employee_Deductions $ee) {
        G_Employee_Deductions_Manager::archive($ee);
        $ee->setIsArchive(G_Employee_Deductions::YES);
        //G_Employee_Deductions_Helper::addToPayslip($ee); //reprocess payslip
    }

    public static function addToPayslip(G_Employee_Deductions $ee) {
        if ($ee) {
            if ($ee->getApplyToAllEmployee() == G_Employee_Deductions::YES) {
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

    private static function getPeriod(G_Employee_Deductions $ee) {
        $period_id = $ee->getPayrollPeriodId();
        $period = G_Cutoff_Period_Finder::findById($period_id);
        if ($period) {
            return $period;
        }
    }

    /*
     * @return Array intance of Deduction
     */
    public static function getOtherDeductions(IEmployee $e, G_Cutoff_Period $p) {
        $ees = G_Employee_Deductions_Finder::findAllByPayrollPeriodId($p->getId());
        $filtered_deductions = self::filterEmployeeDeductionsByEmployeeId($e->getId(), $ees);
        foreach ($filtered_deductions as $deduction) {
            if ($deduction->isApproved() && !$deduction->isArchived()) {
            	$valid_variable = strtolower($deduction->getTitle());
                $valid_variable = trim($valid_variable);
                $valid_variable = str_replace(" ", "_", $valid_variable);    
                $deduct = new Deduction($deduction->getTitle(), $deduction->getAmount());                
                $deduct->setVariable($valid_variable);
                $deductions[] = $deduct;
            }
        }
        return $deductions;
    }

    /*
     * Filters by employee id
     *
     * @param array $ees Array instance of G_Employee_Deductions
     * @return Array instance of G_Employee_Deductions
     */
    private static function filterEmployeeDeductionsByEmployeeId($employee_id, $ees) {
        $found = array();
        foreach ($ees as $ee) {
            if ($ee->isApplyToAllEmployees()) {
                $found[] = $ee;
            } else {
            	$has_deduction = false;
            	// by EMPLOYEE ID
                $employee_ids = self::getEmployeeIds($ee);
                foreach ($employee_ids as $emp_id) {
                    if ($emp_id == $employee_id) {
                        $found[] = $ee;
                        $has_deduction = true;
                    }
                }

                $fields = array("id","department_company_structure_id","section_id","employment_status_id");
                $e = G_Employee_Helper::sqlGetEmployeeDetailsById($employee_id,$fields);
                if($e) {
                	// By Department Id
            		$dept_section = self::getDepartmentSectionIds($ee);
	                if(in_array($e["department_company_structure_id"],$dept_section) && !$has_deduction) {
	                	$found[] = $ee;
                    	$has_deduction = true;
	                }

	                // By Section Id
	                if(in_array($e["section_id"],$dept_section) && !$has_deduction) {
	                	$found[] = $ee;
                    	$has_deduction = true;
	                }
                	
                	// By Employement Status Id
            		$emp_status = self::getEmploymentStatusIds($ee);
	                if(in_array($e["employment_status_id"],$emp_status)  && !$has_deduction) {
	                	$found[] = $ee;
                    	$has_deduction = true;
	                }
                	
                }
            }
        }
        return $found;
    }

    private static function getEmployeeIds(G_Employee_Deductions $ee) {
        $eids = unserialize($ee->getEmployeeId());
        return explode(',', $eids);
    }

    private static function getDepartmentSectionIds(G_Employee_Deductions $ee) {
        $eids = unserialize($ee->getDepartmentSectionId());
        return explode(',', $eids);
    }

    private static function getEmploymentStatusIds(G_Employee_Deductions $ee) {
        $eids = unserialize($ee->getEmploymentStatusId());
        return explode(',', $eids);
    }

	public static function isIdExist(G_Employee_Deductions $gee) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_DEDUCTIONS ."
			WHERE id = ". Model::safeSql($gee->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}	
	
	public static function countTotalRecordsByCompanyStructureId($company_structure_id) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_DEDUCTIONS ."
			WHERE company_structure_id = ". Model::safeSql($company_structure_id) ."
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}	
	
	public static function sumTotalIsNotArchiveEarningByCompanyStructureIdAndPayrollPeriodId($payroll_period_id,$company_structure_id) {
		$sql = "
			SELECT SUM(amount) as total
			FROM " . G_EMPLOYEE_DEDUCTIONS ."
			WHERE company_structure_id = ". Model::safeSql($company_structure_id) ." 
				AND payroll_period_id = " . Model::safeSql($payroll_period_id) . "				
				AND is_archive =" . Model::safeSql(G_Employee_Deductions::NO) . "			
		";			
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);		
		return $row['total'];
	}	
	
	public static function sumTotalIsNotArchiveApproveEarningByCompanyStructureIdAndPayrollPeriodId($payroll_period_id,$company_structure_id) {
		$sql = "
			SELECT SUM(amount) as total
			FROM " . G_EMPLOYEE_DEDUCTIONS ."
			WHERE company_structure_id = ". Model::safeSql($company_structure_id) ." 
				AND payroll_period_id = " . Model::safeSql($payroll_period_id) . "
				AND status =" . Model::safeSql(G_Employee_Deductions::APPROVED) . "
				AND is_archive =" . Model::safeSql(G_Employee_Deductions::NO) . "			
		";			
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);		
		return $row['total'];
	}	
	
	public static function sumTotalIsArchiveEarningByCompanyStructureIdAndPayrollPeriodId($payroll_period_id,$company_structure_id) {
		$sql = "
			SELECT SUM(amount) as total
			FROM " . G_EMPLOYEE_DEDUCTIONS ."
			WHERE company_structure_id = ". Model::safeSql($company_structure_id) ." 
				AND payroll_period_id = " . Model::safeSql($payroll_period_id) . "				
				AND is_archive =" . Model::safeSql(G_Employee_Deductions::YES) . "			
		";			
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);		
		return $row['total'];
	}	
	
	public static function sumTotalIsNotArchivePendingEarningByCompanyStructureIdAndPayrollPeriodId($payroll_period_id,$company_structure_id) {
		$sql = "
			SELECT SUM(amount) as total
			FROM " . G_EMPLOYEE_DEDUCTIONS ."
			WHERE company_structure_id = ". Model::safeSql($company_structure_id) ." 
				AND payroll_period_id = " . Model::safeSql($payroll_period_id) . "
				AND status =" . Model::safeSql(G_Employee_Deductions::PENDING) . "
				AND is_archive =" . Model::safeSql(G_Employee_Deductions::NO) . "			
		";			
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);		
		return $row['total'];
	}	
	
	public static function countTotalRecordsByEmployeeId(G_Employee $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_DEDUCTIONS ."
			WHERE employee_id = ". Model::safeSql($e->getId()) ."
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}	
	
	public static function addDeductionsToPayslip($e,$from,$to){
		$cp = G_Cutoff_Period_Finder::findByPeriod($from,$to);
		if($cp){
			$deductions = G_Employee_Deductions_Finder::findAllApprovedByPayrollPeriodIdAndCompanyStructureIdAndIsNotArchive($cp->getId(),$_SESSION['sprint_hr']['company_structure_id']);				
			foreach($deductions as $de){
				//Convert to array employee id
				$eid 	    = unserialize($de->getEmployeeId());
				$eAr	    = explode(",",$eid);					
				
				$is_removed = self::removeOtherDeductions($de->getTitle(),$cp,$e);				
				
				if(in_array("All Employee", $eAr)){									
					$is_saved = self::addToOtherDeductions($de->getTitle(),$de->getAmount(),$de->getTaxable(),$cp,$e);
				}else{	
					if(in_array($e->getId(), $eAr)){											
						$is_saved = self::addToOtherDeductions($de->getTitle(),$de->getAmount(),$de->getTaxable(),$cp,$e);						
					}
				}		
			}
		}
	}
	
	public static function addEarningsToPayslip($e,$from,$to) {
		
		$cp = G_Cutoff_Period_Finder::findByPeriod($from,$to);
		
		if($cp){			
			$earnings = G_Employee_Deductions_Finder::findAllApprovedByPayrollPeriodIdAndCompanyStructureIdAndIsNotArchive($cp->getId(),$_SESSION['sprint_hr']['company_structure_id']);
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
						
			if($is_taxable == G_Employee_Deductions::YES){
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
	
	private static function addToOtherDeductions($label,$amount,$is_taxable,$cp,$e) {		
		$p = G_Payslip_Finder::findByEmployeeAndPeriod($e, $cp->getStartDate(), $cp->getEndDate());		
		if($p){
			$ph    = new G_Payslip_Helper($p);
			$de[]  = new Deduction($label, $amount, $deduction_type);
			
			$p->addOtherDeductions($de);
			//$ear[] = $ear_obj = new Earning($label, $amount, $taxable, (int) $earning_type);	
						
			$gross_pay = $ph->computeTotalEarnings();
			$net_pay   = $gross_pay - $ph->computeTotalDeductions();
			
			$p->setGrossPay($gross_pay);			
			$p->setNetPay($net_pay);			
			$p->save();
			return true;
		}
	}
	
	private static function removeOtherDeductions($label,$cp,$e) {
		$p = G_Payslip_Finder::findByEmployeeAndPeriod($e, $cp->getStartDate(), $cp->getEndDate());
		if($p){
			$ph = new G_Payslip_Helper($p);
			if($ph){
				$p->removeOtherDeduction($label);				
				$net_pay   = $gross_pay - $ph->computeTotalDeductions();
				$gross_pay = $ph->computeTotalEarnings();
				
				$p->setGrossPay($gross_pay);	
				$p->setNetPay($net_pay);	
				$p->save();				
			}			
		}
		return true;
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
}
?>