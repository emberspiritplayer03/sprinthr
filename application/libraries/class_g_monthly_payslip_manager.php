<?php
class G_Monthly_Payslip_Manager {
    /*
     * @param array $payslips Instance of G_Payslip
     */
    public static function saveMultiple($payslips) { 

        $has_record = false;
        foreach ($payslips as $payslip) {

            $earnings       = ($payslip->getEarnings()) ? serialize($payslip->getEarnings()) : '' ;
            $other_earnings = ($payslip->getOtherEarnings()) ? serialize($payslip->getOtherEarnings()) : '' ;
            $deductions     = ($payslip->getDeductions()) ? serialize($payslip->getDeductions()) : '' ;
            $other_deductions = ($payslip->getOtherDeductions()) ? serialize($payslip->getOtherDeductions()) : '' ;
            $labels           = ($payslip->getLabels()) ? serialize($payslip->getLabels()) : '';

            $insert_sql_values[] = "
                (". Model::safeSql($payslip->getId()) .",
                ". Model::safeSql($payslip->getEmployee()->getId()) .",
                ". Model::safeSql($payslip->getStartDate()) .",
                ". Model::safeSql($payslip->getEndDate()) .",
                ". Model::safeSql($payslip->getBasicPay()) .",
                ". Model::safeSql($payslip->getTardinessAmount()) .",
                ". Model::safeSql($payslip->getDeclaredDependents()) .",
                ". Model::safeSql($payslip->getOvertime()) .",
				". Model::safeSql($payslip->getGrossPay()) .",
				". Model::safeSql($payslip->getTotalEarnings()) .",
				". Model::safeSql($payslip->getTotalDeductions()) .",
				". Model::safeSql($payslip->getNetPay()) .",
				". Model::safeSql($payslip->getPayoutDate()) .",
				". Model::safeSql($earnings) .",
				". Model::safeSql($other_earnings) .",
				". Model::safeSql($deductions) .",
				". Model::safeSql($other_deductions) .",
				". Model::safeSql($labels) .",
				". Model::safeSql($payslip->getTaxable()) .",
                ". Model::safeSql($payslip->getTaxableBenefits()) .",
				". Model::safeSql($payslip->getNonTaxable()) .",
                ". Model::safeSql($payslip->getNonTaxableBenefits()) .",
				". Model::safeSql($payslip->getWithheldTax()) .",
				". Model::safeSql($payslip->get13thMonth()) .",
				". Model::safeSql($payslip->getSSS()) .",
                ". Model::safeSql($payslip->getSSSEr()) .",
				". Model::safeSql($payslip->getPagibig()) .",
                ". Model::safeSql($payslip->getPagibigEr()) .",
                 ". Model::safeSql($payslip->getPhilhealth()) .",
				". Model::safeSql($payslip->getPhilhealthEr()) .")";
            $has_record = true;
        }

        if ($has_record) { 

            $insert_sql_value = implode(',', $insert_sql_values);
            
            $sql_insert = "
                INSERT INTO ". G_EMPLOYEE_MONTHLY_PAYSLIP ." (id, employee_id, period_start, period_end, basic_pay, tardiness_amount, number_of_declared_dependents, overtime, gross_pay,
                                                        total_earnings, total_deductions, net_pay, payout_date, earnings, other_earnings, deductions,
                                                        other_deductions, labels, taxable, taxable_benefits, non_taxable, non_taxable_benefits, withheld_tax,
                                                        month_13th, sss,sss_er, pagibig, pagibig_er, philhealth, philhealth_er)
                VALUES ". $insert_sql_value ."
                ON DUPLICATE KEY UPDATE
                    period_start = VALUES(period_start),
                    period_end = VALUES(period_end),
                    basic_pay = VALUES(basic_pay),
                    overtime = VALUES(overtime),
                    gross_pay = VALUES(gross_pay),
                    total_earnings = VALUES(total_earnings),
                    total_deductions = VALUES(total_deductions),
                    net_pay = VALUES(net_pay),
                    payout_date = VALUES(payout_date),
                    earnings = VALUES(earnings),
                    other_earnings = VALUES(other_earnings),
                    deductions = VALUES(deductions),
                    other_deductions = VALUES(other_deductions),
                    labels = VALUES(labels),
                    taxable = VALUES(taxable),
                    taxable_benefits = VALUES(taxable_benefits),
                    non_taxable = VALUES(non_taxable),
                    non_taxable_benefits = VALUES(non_taxable_benefits),
                    withheld_tax = VALUES(withheld_tax),
                    month_13th = VALUES(month_13th),
                    sss = VALUES(sss),
                    sss_er = VALUES(sss_er),
                    pagibig = VALUES(pagibig),
                    pagibig_er = VALUES(pagibig_er),
                    philhealth = VALUES(philhealth),
                    philhealth_er = VALUES(philhealth_er)
            ";
           // echo $sql_insert;
            Model::runSql($sql_insert);
        }

        if (mysql_errno() > 0) {
            //echo mysql_error();
            
            return false;
        } else {

            return true;
        }
    }

    public static function save($payslip) {
        $ps[] = $payslip;
        return self::saveMultiple($ps);
    }

    /*
     * DEPRECATED
     */
	public static function saveOLD($payslip) {
		$earnings = ($payslip->getEarnings()) ? serialize($payslip->getEarnings()) : '' ;
		$other_earnings = ($payslip->getOtherEarnings()) ? serialize($payslip->getOtherEarnings()) : '' ;
		$deductions = ($payslip->getDeductions()) ? serialize($payslip->getDeductions()) : '' ;
		$other_deductions = ($payslip->getOtherDeductions()) ? serialize($payslip->getOtherDeductions()) : '' ;
		$labels = ($payslip->getLabels()) ? serialize($payslip->getLabels()) : '';	
			
		if ($payslip->getId() == 0) {
			$sql_start = "INSERT INTO g_employee_monthly_payslip";
			$sql_end = ",employee_id = ". Model::safeSql($payslip->getEmployee()->getId()) .", period_start = ". Model::safeSql($payslip->getStartDate()) .", period_end = ". Model::safeSql($payslip->getEndDate());		
		} else {
			$sql_start = "UPDATE g_employee_monthly_payslip";
			$sql_end = "WHERE employee_id = ".  Model::safeSql($payslip->getEmployee()->getId()) ." 
				AND period_start = ". Model::safeSql($payslip->getStartDate()). "
				AND period_end = ". Model::safeSql($payslip->getEndDate()) ."
			";
		}
		$sql = "
			". $sql_start ."
			SET
				basic_pay = ". Model::safeSql($payslip->getBasicPay()) .",
				gross_pay = ". Model::safeSql($payslip->getGrossPay()) .",
				total_earnings = ". Model::safeSql($payslip->getTotalEarnings()) .",
				total_deductions = ". Model::safeSql($payslip->getTotalDeductions()) .",
				net_pay = ". Model::safeSql($payslip->getNetPay()) .",
				payout_date = ". Model::safeSql($payslip->getPayoutDate()) .",
				earnings = ". Model::safeSql($earnings) .",
				other_earnings = ". Model::safeSql($other_earnings) .",
				deductions = ". Model::safeSql($deductions) .",
				other_deductions = ". Model::safeSql($other_deductions) .",
				labels = ". Model::safeSql($labels) .",
				taxable = ". Model::safeSql($payslip->getTaxable()) .",
				non_taxable = ". Model::safeSql($payslip->getNonTaxable()) .",
				withheld_tax = ". Model::safeSql($payslip->getWithheldTax()) .",
				month_13th = ". Model::safeSql($payslip->get13thMonth()) .",
				sss = ". Model::safeSql($payslip->getSSS()) .",
				pagibig = ". Model::safeSql($payslip->getPagibig()) .",
				philhealth = ". Model::safeSql($payslip->getPhilhealth()) ."
			". $sql_end ."
		";
		Model::runSql($sql);
		return mysql_insert_id();						
	}

     public static function delete(G_Monthly_Payslip $p){
        if(G_Monthly_Payslip_Helper::isIdExist($p) > 0){
            $sql = "
                DELETE FROM g_employee_monthly_payslip 
                WHERE id =" . Model::safeSql($p->getId());
            Model::runSql($sql);
        }
    
    }   
}
?>