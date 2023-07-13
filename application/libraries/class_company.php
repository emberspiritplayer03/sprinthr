<?php
abstract class Company implements ICompany {
    abstract function generatePayslipByEmployee(IEmployee $e, $year, $month, $cutoff_number);
    abstract function addNewCutoffPeriod();

    /*
     * Usage:
        $cs = G_Company_Factory::get();
        $cs->hireEmployee('2014-020', 'Harney', 'Cercado', 'Manaloto', '1985-11-01', 'Male', 'Married',
                2, '2014-01-01', 'Marketing', 'Website Designer', 'Regular', 350, 'Daily',
                'SSS123', 'TIN123', 'HDM123', 'PHIC123', 'Jr.', 'Harn');
     *
     * @return obj Instance of G_Employee
     */
    public function hireEmployee($employee_code, $firstname, $lastname, $middlename, $birthdate, $gender, $marital_status, $number_of_dependent,
                                 $hired_date, $department_name, $position, $employment_status, $salary, $salary_type,
                                 $sss_number = '', $tin_number = '', $pagibig_number = '', $philhealth_number = '',
                                 $extension_name = '', $nickname = '') {    
        return G_Employee_Helper::hireEmployee($employee_code, $firstname, $lastname, $middlename, $birthdate, $gender, $marital_status, $number_of_dependent,
            $hired_date, $department_name, $position, $employment_status, $salary, $salary_type,
            $sss_number, $tin_number, $pagibig_number, $philhealth_number,
            $extension_name, $nickname);
    }

    public function generatePayslip($year, $month, $cutoff_number) {

    }

    public function fireEmployee() {

    }
}
?>