<?php
interface ICompany {
    public function hireEmployee($employee_code, $firstname, $lastname, $middlename, $birthdate, $gender, $marital_status, $number_of_dependent,
                                 $hired_date, $department_name, $position, $employment_status, $salary, $salary_type,
                                 $sss_number = '', $tin_number = '', $pagibig_number = '', $philhealth_number = '',
                                 $extension_name = '', $nickname = '');

    public function generatePayslip($year, $month, $cutoff_number);
    public function fireEmployee();
}
?>