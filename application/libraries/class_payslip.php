<?php
class Payslip {
	public $period_start_date;
	public $period_end_date;
	public $payout_date;	
	
	public $basic_pay;
	public $declared_dependents;
	public $tardiness_amount;
	public $net_pay;
	public $gross_pay;
	public $taxable;
	public $taxable_benefits;
    public $non_taxable;
    public $non_taxable_benefits;
	public $withheld_tax;
	public $month_13th;
	public $sss;
	public $sss_er;
	public $pagibig;
	public $pagibig_er;
	public $philhealth;
	public $philhealth_er;
    public $total_deductions;
    public $total_earnings;
    public $overtime;
	
	// Object
	public $earnings;
	public $other_earnings;
	public $deductions;
	public $other_deductions;		
	
	public function __construct() {

	}
	
	public function setPeriod($period_start_date, $period_end_date) {
		$this->period_start_date = $period_start_date;
		$this->period_end_date = $period_end_date;
	}
	
	public function getStartDate() {
		return $this->period_start_date;
	}
	
	public function getEndDate() {
		return $this->period_end_date;		
	}
	
	public function setBasicPay($value) {
		$this->basic_pay = $value;	
	}
	
	public function setNetPay($value) {
		$this->net_pay = $value;	
	}
	
	public function setGrossPay($value) {
		$this->gross_pay = $value;	
	}

    public function setTotalDeductions($value) {
        $this->total_deductions = $value;
    }

    public function getTotalDeductions() {
        return $this->total_deductions;
    }

    public function setTotalEarnings($value) {
        $this->total_earnings = $value;
    }

    public function getTotalEarnings() {
        return $this->total_earnings;
    }
	
	public function setPayoutDate($date) {
		$this->payout_date = $date;	
	}
	
	public function getPayoutDate() {
		return $this->payout_date;	
	}
	
	public function getNetPay() {
		return $this->net_pay;	
	}
	
	public function getGrossPay() {
		return $this->gross_pay;
	}
	
	public function getBasicPay() {
		return $this->basic_pay;	
	}
	
	public function getTaxable() {
		return $this->taxable;
	}
	
	public function setTaxable($value) {
		$this->taxable = $value;
	}

	public function getTaxableBenefits() {
		return $this->taxable_benefits;
	}
	
	public function setTaxableBenefits($value) {
		$this->taxable_benefits = $value;
	}

    public function getNonTaxable() {
        return $this->non_taxable;
    }

    public function setNonTaxable($value) {
        $this->non_taxable = $value;
    }

     public function getNonTaxableBenefits() {
        return $this->non_taxable_benefits;
    }

    public function setNonTaxableBenefits($value) {
        $this->non_taxable_benefits = $value;
    }
	
	public function getWithheldTax() {
		return $this->withheld_tax;
	}
	
	public function setWithheldTax($value) {
		$this->withheld_tax = $value;
	}
	
	public function get13thMonth() {
		return $this->month_13th;
	}
	
	public function set13thMonth($value) {
		$this->month_13th = $value;
	}
	
	public function getSSS() {
		return $this->sss;
	}
	
	public function setSSS($value) {
		$this->sss = $value;
	}

	public function getSSSEr() {
		return $this->sss_er;
	}
	
	public function setSSSEr($value) {
		$this->sss_er = $value;
	}
	
	public function getPagibig() {
		return $this->pagibig;
	}
	
	public function setPagibig($value) {
		$this->pagibig = $value;
	}

	public function getPagibigEr() {
		return $this->pagibig_er;
	}
	
	public function setPagibigEr($value) {
		$this->pagibig_er = $value;
	}
	
	public function getPhilhealth() {
		return $this->philhealth;
	}
	
	public function setPhilhealth($value) {
		$this->philhealth = $value;
	}

	public function getPhilhealthEr() {
		return $this->philhealth_er;
	}
	
	public function setPhilhealthEr($value) { $this->philhealth_er = $value; }

	public function setOvertime($value){
		$this->overtime = $value;
	}

	public function getOvertime($value){
		return $this->overtime;
	}

	public function setDeclaredDependents($value){
		$this->declared_dependents = $value;
	}

	public function getDeclaredDependents($value){
		return $this->declared_dependents;
	}

	public function setTardinessAmount($value){
		$this->tardiness_amount = $value;
	}

	public function getTardinessAmount($value){
		return $this->tardiness_amount;
	}
	
	/*
		Usage:
		$p = new Payslip('2011-02-06', '2011-02-20');
		$p->setBasicPay(7500);
		$p->setEmployee($e);		
		$ers[] = new Earning('Reg OT Hrs', 20);
		$ers[] = new Earning('Holiday (HRS)', 2000);
		$ers[] = new Earning('Travel Allowance', 1000);
		$p->addEarnings($ers);
		$p->save();	
	*/
	public function addEarnings($earnings) {
		if (is_array($earnings)) {
			foreach ($earnings as $earning) {
				$this->addEarning($earning);
			}
		} else {
			$this->addEarning($earnings);	
		}
	}
	
	public function addOtherEarnings($earnings) {
		if (is_array($earnings)) {
			foreach ($earnings as $earning) {
				$this->addOtherEarning($earning);
			}
		} else {
			$this->addOtherEarning($earnings);	
		}
	}			

	public function getEarnings() {
		return $this->earnings;	
	}

    public function setOtherEarnings($value) {
        $this->other_earnings = $value;
    }

    public function setOtherDeductions($value) {
        $this->other_deductions = $value;
    }
	
	public function setEarnings($value) {
		$this->earnings = $value;	
	}

    /*
     * Get total amount of other earnings
     *
     * @return float
     */
    public function getTotalOtherEarnings() {
        $total = 0;
        $earnings = $this->getOtherEarnings();
        foreach ($earnings as $earning) {
            if (is_object($earning)) {
                $total = $total + (int) $earning->getAmount();
            }
        }
        return $total;
    }

    /*
     * Get total amount of other deductions
     *
     * @return float
     */
    public function getTotalOtherDeductions() {
        $total = 0;
        $deductions = $this->getOtherDeductions();
        foreach ($deductions as $deduction) {
            if (is_object($deduction)) {
                $total = $total + (int) $deduction->getAmount();
            }
        }
        return $total;
    }
	
	public function getOtherEarnings($earning_type = '') {
		if ($earning_type == '') {
			return $this->other_earnings;	
		} else {
			foreach ($this->other_earnings as $earning) {
				if (is_object($earning) && $earning->getEarningType() == $earning_type) {
					$earnings[] = $earning;
				}	
			}
			return $earnings;
		}
	}
	
	public function getAllEarnings() {
		$earnings = (array) $this->getEarnings();
		$other_earnings = (array) $this->getOtherEarnings();
		return array_merge($earnings, $other_earnings);		
	}
	
	/*
		Usage:
		$e = Employee_Factory::get(3);
		$ps = $e->getPayslip('2011-02-06', '2011-02-20');
		$er = $ps->getEarnings();
		$ps->removeEarning('Holiday (HRS)');
		$ps->save();	
	*/
	public function removeEarning($label) {
		foreach ($this->earnings as $key => $er) {
			if (strtolower($label) == strtolower($er->getLabel())) {
				unset($this->earnings[$key]);
			}
		}
	}
	
	public function removeOtherEarning($label) {
		foreach ($this->other_earnings as $key => $er) {
			if (strtolower($label) == strtolower($er->getLabel())) {
				unset($this->other_earnings[$key]);
			}
		}
	}	
	
	/*
		Usage:
		$e = Employee_Factory::get(3);
		$ps = $e->getPayslip('2011-02-06', '2011-02-20');
		$ps->removeDeduction('hdmf');
		$ps->save();	
	*/
	public function removeDeduction($label) {
		foreach ($this->deductions as $key => $d) {
			if (strtolower($label) == strtolower($d->getLabel()) || strtolower($label) == strtolower($d->getVariable())) {
				unset($this->deductions[$key]);
			}
		}		
	}
	
	public function removeOtherDeduction($label) {
		foreach ($this->other_deductions as $key => $d) {
			if (strtolower($label) == strtolower($d->getLabel())) {
				unset($this->other_deductions[$key]);
			}
		}	
	}
	
	/*
		Usage:
		$p = new Payslip('2011-02-06', '2011-02-20');
		$p->setBasicPay(7500);
		$p->setEmployee($e);
		$deds[] = new Deduction('Absent (AMT)', 500);
		$deds[] = new Deduction('SSS', 500);
		$deds[] = new Deduction('HDMF', 500);
		$deds[] = new Deduction('Excess lines');
		$p->addDeductions($deds);
		$p->save();			
	*/
	public function addDeductions($deductions) {
		if (is_array($deductions)) {
			foreach ($deductions as $deduction) {
				$this->addDeduction($deduction);
			}
		} else {
			$this->addDeduction($deductions);	
		}
	}
	
	public function getDeductions() {
		return $this->deductions;	
	}
	
	public function setDeductions($value) {
		$this->deductions = $value;	
	}	
	
	public function addOtherDeductions($deductions) {
		if (is_array($deductions)) {
			foreach ($deductions as $d) {
				$this->addOtherDeduction($d);
			}
		} else {
			$this->addOtherDeduction($deductions);	
		}
	}	
	
	public function getOtherDeductions() {
		return $this->other_deductions;	
	}
	
	public function getAllDeductions() {
		$deductions = (array) $this->getDeductions();
		$other_deductions = (array) $this->getOtherDeductions();
		return array_merge($deductions, $other_deductions);		
	}	
		
	private function addEarning($earning) {
		$this->earnings[] = $earning;	
	}
	
	private function addOtherEarning($earning) {
		$this->other_earnings[] = $earning;	
	}	
	
	private function addDeduction($deduction) {
		$this->deductions[] = $deduction;	
	}
	
	private function addOtherDeduction($deduction) {
		$this->other_deductions[] = $deduction;	
	}	
}
?>