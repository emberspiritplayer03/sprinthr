<?php
error_reporting(0);
class TestPayslip extends UnitTestCase {
	
    function employeeVariables() {

        $return['employee_code']      = "160014";
        $return['cutoff_start_date']  = '2017-09-21';
        $return['cutoff_end_date']    = '2017-10-05';        

        return $return;
    }

    function testSalaryDetails()
	{
        $emp_data = $this->employeeVariables(); 
        /*
         * Expected output to be encode Here
        */
            $expected_salary_type   = "Monthly";
            $expected_monthly_rate  = "10200";
            $expected_daily_rate    = "391.10";
            $expected_hourly_rate   = "48.89";

        /*
         * Attendance Data to be encode here
        */        
		$employee_code 	    = $emp_data['employee_code'];
		$cutoff_start_date 	= $emp_data['cutoff_start_date'];
		$cutoff_end_date   	= $emp_data['cutoff_end_date'];

		$e = G_Employee_Finder::findByEmployeeCode($employee_code);
		$s = G_Employee_Basic_Salary_History_Finder::findByEmployeeAndDate($e, $cutoff_end_date);

		$salary_type   = $s->getType();
		$salary_amount = $s->getBasicSalary();

        //Employee number of days per year
        $working_days = $e->getYearWorkingDays();

        $sv = new G_Sprint_Variables(G_Sprint_Variables::FIELD_IS_COMPRESS);
        $is_compress = $sv->getVariableValue();        

        /*
         * Custom for NIDEC
         * All monthly employees default annual working days is 312.96
         * Change to false the $montly_custom_default_working_days if we will base the default working days to system 
        */

        $montly_custom_default_working_days = true;        

        if( $working_days <= 0 ){
            $sv = new G_Sprint_Variables(G_Sprint_Variables::FIELD_DEFAULT_TOTAL_WRKNG_DAYS);
            $working_days = $sv->getVariableValue();
        }		

        switch ($salary_type):
            case G_Employee_Basic_Salary_History::SALARY_TYPE_MONTHLY:                      
                if($montly_custom_default_working_days) {
                    $working_days = 312.96;    
                }

                $employee_monthly_rate = $salary_amount;
                $per_day               = ($salary_amount * 12) / $working_days;
                $monthly_rate_daily    = $salary_amount;        

                $per_hour = $per_day / 8;

                if($is_compress == "Yes" && $compressed_working_days == G_Attendance::COMPRESSED_DAYS_A_WEEK) {
                    $per_hour = $per_day / G_Attendance::COMPRESSED_DEFAULT_DAILY_HOURS;
                } else {
                    $per_hour = $per_day / G_Attendance::DEFAULT_DAILY_HOURS;
                }
                
                $ceta_sea_employee_rate = $salary_amount / ($working_days / 12);
                break;

            case G_Employee_Basic_Salary_History::SALARY_TYPE_DAILY:      
                $monthly_rate_daily    = ($salary_amount * $working_days) / 12;               
                $employee_monthly_rate = $monthly_rate_daily;
                $per_day               = ($monthly_rate_daily * 12) / $working_days;
                $per_hour              = $per_day / 8;

                if($is_compress == "Yes" && $compressed_working_days == G_Attendance::COMPRESSED_DAYS_A_WEEK) {
                    $monthly_rate_daily      = ( ($per_hour * G_Attendance::COMPRESSED_DEFAULT_DAILY_HOURS) * $working_days ) / 12; 
                    $employee_monthly_rate   = $monthly_rate_daily;
                    $per_day                 = ($monthly_rate_daily * 12) / $working_days;
                    $per_hour                = $per_day / G_Attendance::COMPRESSED_DEFAULT_DAILY_HOURS;
                }

                $employee_daily_rate    = $salary_amount;
                $ceta_sea_employee_rate = $monthly_rate_daily / ($working_days / 12);
                break;
        endswitch;

		$monthly_rate = $employee_monthly_rate;
		$daily_rate   = $per_day;
		$hourly_rate  = $per_hour;

		echo "<b style='color: #1873b7; text-shadow: 0 1px #ffffff; font-size: 24px;'>Payslip: " . $e->getLastName(). " " . $e->getFirstName() . "</b>";

		//Salary Type : Monthly / Monthly Rate : 10,200.00 / Daily Rate : 391.10 / Hourly Rate : 48.89
		echo '<div style="margin-top: 1em; background-color: #3e1d2c; color: white;">' . 'Salary Type: ' . $salary_type . ' / Monthly Rate: ' . $monthly_rate . ' / Daily Rate: ' . $daily_rate . ' / Hourly Rate: ' . $hourly_rate . '</div>';

        /*echo '<div style="padding: 8px; margin-top: 1em; background-color: blue; color: white;">';
        echo 'Output: ' . '-' . ' | ';
        echo 'Expected Output: ' . '-';
        echo '</div>';*/        

        $this->assertEqual($salary_type, $expected_salary_type);
        $this->assertEqual($monthly_rate, $expected_monthly_rate);
        $this->assertEqual(number_format($daily_rate,2), $expected_daily_rate );
        $this->assertEqual(number_format($hourly_rate,2), $expected_hourly_rate);

	}		

    function testEarningDetails()
    {
        echo "<br /><b style='color: #1873b7; text-shadow: 0 1px #ffffff; font-size: 24px;'>Earnings</b>";
    }   

}
?>