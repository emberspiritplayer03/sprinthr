<?php 
class G_Employee_Basic_Salary_History_Helper {
    /*
     * Ends basic salary of an employee
     */
    public static function ended(IEmployee $e, $date_ended) {
        $s = G_Employee_Basic_Salary_History_Finder::findCurrentSalary($e);
        if ($s) {
            $s->setEndDate($date_ended);
            $s->save();
        }
    }


    public static function resetActive(IEmployee $e, $date_ended) {
        $s = G_Employee_Basic_Salary_History_Finder::findCurrentSalary2($e);
        if ($s) {
            $s->setEndDate($date_ended);
            $s->save();
        }
    }

    /*
     * @param string $salary_type It's G_Employee_Basic_Salary_History::SALARY_TYPE_DAILY or G_Employee_Basic_Salary_History::SALARY_TYPE_MONTHLY
     */
    public static function addNewSalary($employee_id, $salary_amount, $salary_type, $effectivity_date,$frequency_id) {
        $salary = G_Employee_Basic_Salary_History_Finder::findByEmployeeIdStartDate($employee_id, $effectivity_date);
        if (!$salary) {
            $p = G_Settings_Pay_Period_Finder::findById($frequency_id);
            $s = G_Employee_Basic_Salary_History_Helper::generate($employee_id, $salary_type, $salary_amount, $frequency_id, $effectivity_date, '', $p->getId());
            return $s->save();
        }
    }

    public static function generate($employee_id, $type, $salary_amount, $frequency_id,$start_date, $end_date = '', $pay_period_id) {
        $employee_salary = new G_Employee_Basic_Salary_History;
        $employee_salary->setEmployeeId($employee_id);
        $employee_salary->setType($type);
        $employee_salary->setFrequencyId($frequency_id);
        $employee_salary->setBasicSalary($salary_amount);
        $employee_salary->setPayPeriodId($pay_period_id);
        $employee_salary->setStartDate($start_date);
        $employee_salary->setEndDate($end_date);
        return $employee_salary;
    }
		
	public static function isIdExist(G_Employee_Basic_Salary_History $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_BASIC_SALARY_HISTORY ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static  function countTotalHistoryByEmployeeId($employee_id) 
	{
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_BASIC_SALARY_HISTORY ."
			WHERE employee_id = ". Model::safeSql($employee_id) ."
			ORDER BY end_date 
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
		
	}
	
	public static function getEmployeeCurrentSalaryAndPayPeriod($employee_id) {
		$sql = "
			SELECT s.id,s.basic_salary,s.type,p.pay_period_name
			FROM " . G_EMPLOYEE_BASIC_SALARY_HISTORY ." s
			LEFT JOIN " . G_SETTINGS_PAY_PERIOD . " p
			ON s.pay_period_id = p.id
			WHERE
				s.end_date = '' AND
				s.employee_id = " . Model::safeSql($employee_id) . "
			LIMIT 1
		";
		$result = Model::runSql($sql,true);
		return $result[0];
	}

	public static function getEmployeeNoSalaryRate() {
        $sql = "
            SELECT e.id, CONCAT(e.lastname , ', ' , e.firstname) as employee_name, esh.name as department_name, 
            	e.hired_date
            FROM " . EMPLOYEE . " e
            	LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id 	
           	    WHERE e.id NOT IN (
            		SELECT employee_id FROM " . G_EMPLOYEE_BASIC_SALARY_HISTORY . "
			         WHERE end_date >= IF(end_date = '','',NOW())
                )
        ";
        $result = Model::runSql($sql,true);
		return $result;
    }
    
    public static function countEmployeeNoSalaryRate() {
        $sql = "
            SELECT COUNT(e.id) as total
            FROM " . EMPLOYEE . " e
           	    WHERE e.id NOT IN (
            		SELECT employee_id FROM " . G_EMPLOYEE_BASIC_SALARY_HISTORY . "
			         WHERE end_date >= IF(end_date = '','',NOW())
                ) AND e.employee_status_id = 1
        ";
        $result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
    }

}
?>