<?php
class G_Excluded_Employee_Deduction_Manager {

    public static function save(G_Excluded_Employee_Deduction $o) {
        if (G_Excluded_Employee_Deduction_Helper::isIdExist($o) > 0) {
            $sql_start = "UPDATE " . EXCLUDED_EMPLOYEE_DEDUCTION . " ";
            $sql_end   = "WHERE id = " . Model::safeSql($o->getId());
        } else {
            $sql_start = "INSERT INTO " . EXCLUDED_EMPLOYEE_DEDUCTION . " ";
            $sql_end   = " ";
        }

        $sql = $sql_start . "

			SET
                employee_id                 =" . Model::safeSql($o->getEmployeeId()) . ",
                payroll_period_id           =" . Model::safeSql($o->getPayrollPeriodId()) . ",
                new_payroll_period_id       =" . Model::safeSql($o->getNewPayrollPeriodId()) . ",
                variable_name               =" . Model::safeSql($o->getVariableName()) . ",
                amount                      =" . Model::safeSql($o->getAmount()) . ",
                action                      =" . Model::safeSql($o->getAction()) . ",	
				date_created                =" . Model::safeSql($o->getDateCreated()) . " "
                . $sql_end . "	
		";
        Model::runSql($sql);
        return mysql_insert_id();
    }

    public static function saveBulk($values) {
        $sql = "INSERT INTO " . EXCLUDED_EMPLOYEE_DEDUCTION . " (employee_id,payroll_period_id,new_payroll_period_id,variable_name,amount,action,date_created) 
                    VALUES ".$values." ";
        Model::runSql($sql);
    }

    public static function deleteByPayrollPeriodId($payroll_period_id) {
        $sql = "
            DELETE FROM " . EXCLUDED_EMPLOYEE_DEDUCTION . "
            WHERE payroll_period_id =" . Model::safeSql($payroll_period_id);
        Model::runSql($sql);
    }

    public static function deleteByPayrollPeriodIdAndEmployeeId($payroll_period_id,$employee_id) {
        $sql = "
            DELETE FROM " . EXCLUDED_EMPLOYEE_DEDUCTION . "
            WHERE payroll_period_id =" . Model::safeSql($payroll_period_id) . "
                AND employee_id IN (".$employee_id.") 
            ";
        Model::runSql($sql);
    }

    public static function delete(G_Excluded_Employee_Deduction $o) {
        if (G_Excluded_Employee_Deduction_Helper::isIdExist($o) > 0) {
            $sql = "
				DELETE FROM " . EXCLUDED_EMPLOYEE_DEDUCTION . "
				WHERE id =" . Model::safeSql($o->getId());
            Model::runSql($sql);
        }
    }
}
?>