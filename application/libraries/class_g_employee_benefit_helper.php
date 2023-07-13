<?php
class G_Employee_Benefit_Helper {

    public static function isIdExist(G_Employee_Benefit $geb) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_BENEFITS ."
			WHERE id = ". Model::safeSql($geb->getId()) ."
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_BENEFITS			
		;
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByBenefitId($benefit_id) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_BENEFITS . "
			WHERE benefit_id =" . Model::safeSql($benefit_id) . "
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByBenefitIdAndAppliedToAll($benefit_id) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_BENEFITS . "
			WHERE benefit_id =" . Model::safeSql($benefit_id) . "
				AND apply_to_all <> " . Model::safeSql(G_Employee_Benefit::NO) . "
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function getAllEmployeeBenefits($eid) {
		$sql = "
			SELECT gscb.benefit_code, gscb.benefit_name, gscb.benefit_type, gscb.is_taxable, gscb.benefit_description, gscb.benefit_amount 
			FROM " . G_EMPLOYEE_BENEFITS . " geb, " . G_SETTINGS_COMPANY_BENEFITS . " gscb 			
			WHERE (
					geb.obj_id =" . Model::safeSql($eid) ." 
					AND geb.obj_type =" . Model::safeSql(G_Employee_Benefit::EMPLOYEE) . " 
					OR geb.apply_to_all	=" . Model::safeSql(G_Employee_Benefit::EMPLOYEE) . ")
				AND(
					gscb.id = geb.benefit_id 
					AND gscb.is_archived =" . Model::safeSql(G_Settings_Company_Benefits::NO) . "
				)

		";
		
		$records = Model::runSql($sql, true);
		return $records;
		
	}
	
	public static function addToPayslip(G_Employee $e, $period) {
		$cp = G_Cutoff_Period_Finder::findByPeriod($period['start_date'],$period['end_date']);
		if($cp){
			$benefits = self::getAllEmployeeBenefits($e->getId());			
			if($benefits){
				foreach($benefits as $b){
					if($b['benefit_type'] == G_Employee_Benefit::EARNING){
						$is_removed = self::removeOtherEarnings($b['benefit_name'],$cp,$e);
						$is_saved   = self::addToOtherEarnings($b['benefit_name'],$b['benefit_amount'],$b['is_taxable'],$cp,$e);
					}else{
						$is_removed = self::removeOtherDeductions($b['benefit_name'],$cp,$e);
						$is_saved   = self::addToOtherDeductions($b['benefit_name'],$b['benefit_amount'],$b['is_taxable'],$cp,$e);				
					}
				}
			}
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
	
	private static function addToOtherEarnings($label,$amount,$is_taxable,$cp,$e) {
		$p = G_Payslip_Finder::findByEmployeeAndPeriod($e, $cp->getStartDate(), $cp->getEndDate());
		if($p){
			$ph    = new G_Payslip_Helper($p);
			$ear[] = $ear_obj = new Earning($label, $amount, $taxable, (int) $earning_type);	
			
			$gross_pay = $ph->computeTotalEarnings();
						
			if($is_taxable == G_Employee_Benefit::YES){
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
			
			$gross_pay = $ph->computeTotalEarnings();
			$net_pay   = $gross_pay - $ph->computeTotalDeductions();
			
			$p->setGrossPay($gross_pay);			
			$p->setNetPay($net_pay);			
			$p->save();
			return true;
		}
	}
	
	public static function assignBenefit(G_Employee_Benefit $geb, $employee_array, $criteria) {		
		$counter = 0;
		//Delete Benefit id from g_employee_benefit
			$geb->deleteAllByBenefitId($geb->getBenefitId());
		//
		
		//Load new data
		if($criteria['is_apply_to_all'] == G_Employee_Benefit::YES){
			$geb->setObjType($criteria['obj_type']);												
			$geb->setApplyToAll($criteria['obj_type']);
			$geb->save();
			$counter++;
		}else{
			$e_array = explode(",",$employee_array);
			
			foreach($e_array as $e){
				$eid = Utilities::decrypt($e);
				$e   = G_Employee_Finder::findById($eid);
				if($e){										
					$geb->setObjId($e->getId());
					$geb->setObjType($criteria['obj_type']);			
					$geb->setApplyToAll(G_Employee_Benefit::NO);					
					$geb->save();
					$counter++;
				}			
			}
		}
		return $counter;
	}
}
?>