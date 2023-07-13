<?php
class Benchmark_Revina_Controller extends Controller
{
	function __construct() {
		parent::__construct();
        Loader::appMainUtilities();
		$this->c_date = Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila');
	}

    function _import_leave_credit() { //fromt attendance controller
    	ob_start();
        ini_set("memory_limit", "999M");
        set_time_limit(999999999999999999999);
        $file = $_FILES['file']['tmp_name'];
        $covered_year = $_POST['covered_year'];

        $l = new G_Employee_Leave_Available_Importer($file);
        $l->setYear($covered_year);
        $is_imported = $l->importLeaveCredit(); //function import

        if ($is_imported) {
            $return['is_imported'] = true;
            $return['message'] = 'Leave credits have been successfully added.';
        } else {
            $return['is_imported'] = false;
            $return['message'] = 'There was a problem importing the leave credits. Please contact the administrator.';
        }
        ob_clean();
		ob_end_flush();
        echo json_encode($return);
    }	

    function test_settings_leave_credit()
    {
        echo 'Test Class';
        //find and delete
        $slv = G_Settings_Leave_Credit_Finder::findById(1);        
        $slv->delete();

        exit;
        //find and update
        $slv = G_Settings_Leave_Credit_Finder::findById(1);
        $slv->setEmploymentYears('2st');
        $slv->setDefaultCredit(10);
        $slv->setLeaveId(2);       
        $slv->setEmploymentStatusId(9);
        $slv->setIsArchived("Yes"); 
        $update = $slv->save();        

        echo '<pre>';
        print_r($update);
        echo '</pre>';
        exit;

        //save
        $slv = new G_Settings_Leave_Credit();
        $slv->setEmploymentYears('1st');
        $slv->setDefaultCredit(5);
        $slv->setLeaveId(1);       
        $slv->setEmploymentStatusId(2);        
        $slv->setIsArchived("No"); 
        $saved = $slv->save();

        echo '<pre>';
        print_r($saved);
        echo '</pre>';
    }

    function test_settings_leave_general()
    {
        exit;
        echo "Test Leave General";
        $slv = G_Settings_Leave_General_Finder::findById(3);
        $slv->delete();
        exit;
        $slv = G_Settings_Leave_General_Finder::findById(1);
        $slv->setConvertLeaveCriteria(8);
        $slv->setLeaveId(2);       
        $saved = $slv->save();
        echo '<pre>';
        print_r($slv);
        echo '</pre>';
        exit;
        $slv = new G_Settings_Leave_General();
        $slv->setConvertLeaveCriteria(3);
        $slv->setLeaveId(1);       
        $saved = $slv->save();

        echo '<pre>';
        print_r($saved);
        echo '</pre>';        
    }

    function test_update_employee_leave_credit()
    {
        echo 'Employee Leave Credit Test <hr />';

        $gela = new G_Employee_Leave_Available();
        $gela->getAllEmployeesEntitledForLeaveIncrease()->saveAllEmployeeWithLeaveIncrease();
    }

    function test_all_update_employee_leave_credit()
    {
        //echo 'Employee Leave Credit With General Rule<hr />';
        $slg = new G_Settings_Leave_General();
        $apply_credits = $slg->getAllUnusedLeaveCreditLastYear()->applyGeneralRule()->applyCredits();   

        Utilities::displayArray($apply_credits);
    }

    function test_payslip_template()
    {
        echo 'Payslip Template Test <hr />';

        $d_template = G_Payslip_Template_Helper::defaultTemplate();
        echo '<pre>';
        print_r($d_template);
        echo '</pre>';
        exit;
        $template = G_Payslip_Template_Finder::findById(1);

        echo '<pre>';
        print_r($template);
        echo '</pre>';
        exit;

        $slv = new G_Payslip_Template();
        $slv->setTemplateName("Template 03");
        $slv->setIsDefault(G_Payslip_Template::IS_DEFAULT_NO);       
        $saved = $slv->save();

        echo '<pre>';
        print_r($saved);
        echo '</pre>';        
    }

    function test_clear_template() {
        echo 'Test Reset Template <hr />';
        $gpt = G_Payslip_Template_Finder::findById(2);
        $gpt->setIsDefault(G_Payslip_Template::IS_DEFAULT_YES);       

        echo '<pre>';
        print_r($gpt);
        echo '</pre>';

        $gpt->clearDefaultTemplate()->save();

    }

    function test_employee_new_class() {
        echo 'Test Employee Leave Request <hr />';

        $el = new G_Employee_Leave_Request();
        $el->checkGeneralRule();
        if(!empty($el->default_general_rule) || $el->default_general_rule > 0) {
            echo 'My General Rule';
        } else {
            echo 'Walang General Rule';
        }
    }

    function test_generate_payslip_array() {
        echo 'Generate Payslip Array <hr />';
        
        $p = new G_Payslip(); 

        $p->wrapPayslipArray();   
    }

    function employeeLeaveCreditHistory()
    {
        echo 'Employee Leave Credit History <hr />';

        $eid = 7;
        $leave_id = 7;
        $added = 99;
        $h = new G_Employee_Leave_Credit_History();
        $h->setEmployeeId($eid);
        $h->setLeaveId($leave_id);
        $h->setCreditsAdded($added);
        $return = $h->addToHistory();

        Utilities::displayArray($return);
    }  

    function update_attendance() {
        echo 'Update Attendance <hr />';
        // $emp_id = 582;

        // $from = "2019-01-28";
        // $to   = "2019-01-28";

        $emp_id = 921;
        $from = "2020-07-31";
        $to = "2020-08-06";

        $is_updated = G_Attendance_Helper::updateAttendanceByEmployeeIdPeriod($emp_id, $from, $to);
        if($is_updated) {
            echo 'Successfully';
        } else {
            echo 'Not';
        }



        //=============================[

// $test_date = "2019-06-11";
// $date = date("M d", strtotime($test_date));
// $date_start = date("M 01", strtotime($date));
// $date_end = date("M 10", strtotime($date));
// echo $date;
// echo "<br>";
// echo $date_start;
// echo "<br>";
// echo $date_end;
// echo "<br>"; 

//     if (($date >= $date_start) && ($date <= $date_end)){
//         echo "yes!";
//     }
//     else{
//         echo "no!";
//     }


        // $test = "2019-05-25";
        // $newDate = date("M d", strtotime($test));
     
        // $may = new DateTime('May 26');
        // $june = new DateTime('May 31');
        // $date3 = new DateTime($newDate);

        // if (($date3 >= $may) && ($date3 <= $june)){

        //     echo "yes";
        // }else{
        //     echo "no!";
        // }
        
        //echo date('m-d', $may123);
    }  

    function test_tardiness_with_breakin_breakout()
    {
        echo 'Tardiness with Breakin/Breakout <hr />';

        $scheduled_time_in = '07:30:00';
        $scheduled_time_out = '17:00:00';
        $actual_time_in = '07:49:00';
        $actual_time_out = '18:56:00';      
        
        if (Tools::isTimeNightShift($scheduled_time_in)) {
            $break_time_in = '00:00:00';
            $break_time_out = '01:00:00';
        } else {
            $break_time_in = '12:00:00';
            $break_time_out = '12:45:00';
        }
        $l = new Late_Calculator;
        $l->setGracePeriod(0);
        $l->setBreakTimeIn($break_time_in);
        $l->setBreakTimeOut($break_time_out);           
        $l->setScheduledTimeIn($scheduled_time_in);
        $l->setScheduledTimeOut($scheduled_time_out);
        $l->setActualTimeIn($actual_time_in);
        $l->setActualTimeOut($actual_time_out);
        echo $late_hours = $l->computeLateHours();
        
        echo '<pre>';
        print_r($l);      
        echo '<pre />';

        echo '<hr />';
        $employee_id = 6;
        $date_from   = '2016-06-21';
        $date_to     = '2016-06-21';

        $breaktime_late = G_Employee_Breaktime_Helper::getLateHoursByEmployeeIdPeriod($employee_id, $date_from, $date_to);
        echo $breaktime_late;
        echo '<br />';
        echo round($breaktime_late,2);
    }

    function checkBirthdayLeave() {
        echo 'Check for Birthday Leave <hr />';
    }

    function testClassEmployeeStatusHistory()
    {
        echo "Employee Status History <hr />";

        $h = G_Employee_Status_History_Finder::findById(2);
        $h->delete();
        exit;

        //$h = new G_Employee_Status_History();
        $h = G_Employee_Status_History_Finder::findById(2);
        $h->setEmployeeId(111);
        $h->setEmployeeStatusId(111);
        $h->setStatus('InactiveUp');
        $h->setStartDate('Update');
        $h->setEndDate('testup');
        $return = $h->save();        
    }

    function checkInactiveEmployeeHistory()
    {
        echo 'Inactive Employee History Finder <hr />';

        $start_date = "2016-07-5";
        $end_date   = "2016-07-15";

        $inactive = G_Employee_Status_History_Finder::findInactiveEmployeeInBetweenDates($start_date, $end_date);

        echo '<br />';

        foreach($inactive as $pkey => $pkeyd) {
            $employee_id = $pkeyd->getEmployeeId();

            echo $employee_id;
            echo '<br />';
        }        

        echo '<pre>';
        print_r($inactive);
        echo '</pre>';
    }

    function checkAttendance()
    {
        echo 'Check Attendance <hr />';

        $e = G_Employee_Finder::findById(179);
        $date = "2016-08-04";
        $update = G_Attendance_Helper::updateAttendance($e, $date);

    }    

    function test_nighshift_calculator() {
        echo 'test nightshift<hr />';
        $ns = new Nightshift_Calculator;
        $ns->setScheduledTimeIn('00:30:00');
        $ns->setScheduledTimeOut('04:00:00');
        //$ns->setOvertimeIn($overtime_in);
        //$ns->setOvertimeOut($overtime_out);
        $ns->setActualTimeIn('00:28:00');
        $ns->setActualTimeOut('04:15:00');
        $ns_hours = $ns->compute();
       
        print_r($ns_hours);
    }    

    function test() {
        echo 'test break in out<hr />';
        $e = G_Employee_Finder::findById(67);
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, '2016-08-30');

        $break = G_Employee_Breaktime_Finder::findByEmployeeIdAndDate(67,'2016-08-30');

        echo '<pre>';
        print_r($break);
        echo '</pre>';

    }

    function udateNotification() {
        echo 'Update Notification <hr />';
        $end_of_contract = G_Employee_Helper::countEmployeeEndOfContract30Days();
        echo $end_of_contract;
    }

    function get_employee_section_in_employee_table() {
        //$r = G_Employee_Helper::getEmployeeSectionByGroup();
        $r = G_Company_Structure_Finder::findAllSectionsIsNotArchiveByBranchIdAndParentIdIncludeArchive(1,1);
        echo '<pre>';
        print_r($r);
        echo '</pre>';
    }

    function testComputeHourDifferenceClass() {
        echo 'Hours Diff<hr />';

        $date_start = '2017-08-28 07:30:00 ';
        $date_end   = '2017-08-28 13:45:00 ';

        $temp_total = Tools::computeHoursDifferenceByDateTime($date_start, $date_end);

        echo $temp_total;
    }

    function testCheckBreakTimeScheduleClass() {
        echo 'Breaktime Schedule<hr />';

        echo strtotime('12:00:00 PM');

        echo '<hr />';

        $e = G_Employee_Finder::findById(13);
        $day_type[]               = "applied_to_legal_holiday";

        $schedule['schedule_in']  = "2017-08-28 07:30:00";
        $schedule['schedule_out'] = "2017-08-28 16:30:00";

        $schedule['actual_in']    = "07:12:00";
        $schedule['actual_out']   = "13:55:00";   

        $deductible_breaktime     = $e->getTotalBreakTimeHrsDeductible($schedule, $day_type);

        echo 'deductable breaktime: ' . $deductible_breaktime;

    }    


    function testCutoffGeneration()
    {
        echo 'Test Cutoff Generation <hr />';

        $year   = date('Y');
        $total_cutoff = G_Cutoff_Period_Helper::countTotalCutoffByYear($year);
        if( $total_cutoff <= 0 ){
            $default_cutoff = "26-10,11-25";
            $payout         = "20,5";            
            if( $default_cutoff ){
                $cutoff   = explode(",", $default_cutoff);
                $cutoff_a = explode("-", $cutoff[0]); 
                $cutoff_b = explode("-", $cutoff[1]);
                $payout   = explode(",", $payout);
                
                $data[1]['a']      = $cutoff_a[0];
                $data[1]['b']      = $cutoff_a[1];
                $data[1]['payday'] = $payout[0];
                $data[2]['a']      = $cutoff_b[0];
                $data[2]['b']      = $cutoff_b[1];
                $data[2]['payday'] = $payout[1];

                $c = new G_Cutoff_Period();
                $return = $c->setNumberOfMonths(12)->generateIniCutOffPeriods($data);   
            }
        }        
    }

    function testPerfectAttendance() {
        echo 'Test Perfect Attendance <hr />';
        $from = "2018-02-01";
        $to   = "2018-02-28";
        $att   = G_Attendance_Helper::perfectAttendanceDataByDateRange($from, $to);

        echo '<pre>';
        print_r($att);
        echo '</pre>';
    }

    function testTaxComputation() {
        echo 'Revise Tax Computation (House Bill 5636) - 2018' . '<hr />';

        $taxable_income   = 85740.00;

        echo 'Taxable Income: ' . $taxable_income;
        echo '<hr />';

        $dependents       = 0;
        $tax_table = Tax_Table_Factory::getRevisedTax(Tax_Table::SEMI_MONTHLY);
        //$tax_table = Tax_Table_Factory::getRevisedTax(Tax_Table::MONTHLY);

        $tax = new Tax_Calculator;
        $tax->setTaxTable($tax_table);
        $tax->setTaxableIncome($taxable_income);
        $tax->setNumberOfDependent($dependents);
        $witholding_tax = round($tax->computeHB563(), 2);       

        echo 'Witholding Tax: ' . $witholding_tax;
    }  

    function testAnnualTaxComputation() {
        echo 'Revise Tax Computation (House Bill 5636) - 2018' . '<hr />';

        $taxable_income   = 377438.31;

        echo 'Taxable Income: ' . $taxable_income;
        echo '<hr />';

        $dependents       = 0;

        $tax_table = Tax_Table_Factory::getRevisedTax(Tax_Table::ANNUAL); //MONTHLY OR SEMI_MONTHLY OR ANNUAL

        /*echo '<pre>';
        print_r($tax_table);
        echo '</pre>';*/

        $tax = new Tax_Calculator;
        $tax->setTaxTable($tax_table);
        $tax->setTaxableIncome($taxable_income);
        $tax->setNumberOfDependent($dependents);
        $witholding_tax = round($tax->computeHB563(), 2);       

        echo 'Witholding Tax: ' . $witholding_tax;
    }    

    function getAllActiveCurrentEmployee() {
        $year = date("Y");
        $current_employee = G_Employee_Helper::getCurrentEmployeeByYear($year);

        echo '<pre>';
        print_r($current_employee);
        echo '</pre>';
    }

    function testUpdateNotificationRevise2()
    {
        echo 'This is test notification <hr />';
        $from = '2018-08-01';
        $to   = '2018-08-31';
        $n = new G_Notifications();
        $n->updateNotifications($from, $to);
    }  

    function testUnprocessedPayroll()
    {
        echo 'test unprocessed payroll <hr />';
        $date = date("Y-m-d");
        $current_period_array = array();
        $current_p      = G_Cutoff_Period_Helper::sqlGetCurrentCutoffPeriod($date);


        if($current_p) {
            $start_date     = $current_p['period_start'];
            $end_date       = $current_p['period_end'];
            $additional_qry = "";
            
            //$total_employees   = G_Employee_Helper::countEmployeeNotArchivedByDate($start_date, $additional_qry);
            //$processed_payroll = G_Employee_Helper::countProcessedEmployeePayrollByCutoff($start_date, $end_date, $additional_qry);

            $e = new G_Employee();
            $payroll_employee_details = $e->getProcessedAndUnprocessedEmployeeCount($start_date,$end_date, $additional_qry );            

            echo '<pre>';
            print_r($payroll_employee_details);
            echo '</pre>';

            //echo 'total unprocessed payroll: ' . $total_unprocessed_payroll = abs($total_employees - $processed_payroll);  
        }

    }

    function testGetUnprocessPayrollEmployee()
    {
        $date = date("Y-m-d");
        $current_period_array = array();
        $current_p      = G_Cutoff_Period_Helper::sqlGetCurrentCutoffPeriod($date);

        if($current_p) {
            $start_date     = $current_p['period_start'];
            $end_date       = $current_p['period_end'];
            $additional_qry = "";

            $active_employees           = G_Employee_Helper::sqlAllActiveEmployees($start_date, $additional_qry);
            $processed_payroll_employee = G_Employee_Helper::sqlProcessedEmployeePayrollByCutoff($start_date, $end_date, $additional_qry);
            $process_employee = array();
            foreach($processed_payroll_employee as $process_data) {
                $process_employee[] = $process_data['employee_id'];
            }

            $no_payslip_data = array();
            foreach($active_employees as $active_key => $active_data) {
                if (!in_array($active_key, $process_employee)) {
                  $no_payslip_data[$active_key]['employee_id']   = $active_key;
                  $no_payslip_data[$active_key]['full_name']     = $active_data['full_name'];
                  $no_payslip_data[$active_key]['employee_code'] = $active_data['employee_code'];
                }
            }

            echo '<pre>';
            print_r($no_payslip_data);
            echo '</pre>';

        }

        
    }

    function testIfEmployeeHaveSchedule() {
        echo 'Check employee schedule<hr />';

        $employee_id = 4;
        $date  = "2018-04-14";
        $e = G_Employee_Finder::findById($employee_id);
        $data = G_Schedule_Specific_Finder::findByEmployeeAndDate($e, $date);

        $schedules = G_Schedule_Group_Finder::findByEmployeeAndDateStartEnd($e, $date);        

        echo count($data);
        echo '<pre>';
        print_r($data);
        echo '</pre>';

        if($data) {
            echo 'maylaman';
        }
    }

    function testGetParam()
    {
        echo 'hello';
        echo get_param(2);
    }
    function alphaListReport(){
        $year = 2019;
        //$options['add_query'] = " AND (e.is_confidential = 1) ";  
        $options['add_query'] = " AND (e.is_confidential = 0) ";
         //$options['add_query'] = " AND (e.is_confidential = 1) ";

        $report = new G_Report();
        $alpha_data = $report->alphaListReportCustom($year, $options);
        
        // echo "<pre>";
        // var_dump($alpha_data);
        // echo "</pre>";
        //Utilities::displayArray($alpha_data);
    }

    function generateWeeklyPeriod($start_date = "Wednesday"){
        $year_now = date('Y');
        //start friday of current month
        //$given_year = strtotime($year_now);
        // start of year

        $created  = date("Y-m-d H:i:s");
        $given_year = strtotime("1 January 2020");

        $for_start = strtotime($start_date, $given_year);
        $for_end = strtotime('+1 year', $given_year);
        $year = date('Y' , $given_year);
        

        $array_months = array('January','February','March','April','May','June','July','August','September','October','November','December');

        $array_seperate = array();
        $array_cutoffs = array();
        $data = array();
        for ($i = $for_start; $i <= $for_end; $i = strtotime('+1 week', $i)) {               
                $get_year = date('Y',  $i);
                
                foreach ($array_months as  $value) {
                    $cutoff_number = 1;
                    if($get_year == $year_now){
                        if($value == date('F',$i)){
                          
                            $data = [ 
                                'month' => $value,
                                'date_start' => date('Y-m-d', $i),
                                'date_end' =>  date('Y-m-d', strtotime('+6 days',$i))
                            ];                         
                            array_push($array_cutoffs, $data);
                           
                        }
                       
                    }

                }
                

        }
        
        $weekly_by_group = $this->generateWeeklyByGroup($array_cutoffs, "month");
        

        $insert_values = array();
        foreach ($weekly_by_group as $key => $value_by_group) {
            foreach ($array_months as $value_months) {
                if($key == $value_months){
                    foreach ($value_by_group as $by_group_key => $value) {                       
                        $cutoff = $by_group_key + 1 ;
                        $values = "('".Model::safeSql($year)."',".Model::safeSql($value['date_start']).",".Model::safeSql($value['date_end']).",".Model::safeSql($value['date_end']).",".$cutoff.",".G_Salary_Cycle::TYPE_SEMI_MONTHLY.",'".G_Cutoff_Period::NO."','".G_Cutoff_Period::NO."')";
                         "<br>";
                         
                        array_push($insert_values, $values);
                    }
                }
            }
            
        }

        
        $insert_sql_queries = implode(",",$insert_values);

        $return = $this->bulkInsertWeeklyCutoff($insert_sql_queries);

        // var_dump($return);
        


        
    }

    function generateWeeklyPeriod2(){
        $given_year = strtotime("1 January 2020");
        $for_start = strtotime('Wednesday', $given_year);
        $for_end = strtotime('+1 year', $given_year);
        for ($i = $for_start; $i <= $for_end; $i = strtotime('+1 week', $i)) {
            echo date('l Y-m-d', $i) . ' - ';
            echo date('l Y-m-d', strtotime('+6 days',$i)) . ' <br> ';

        }
    }

    public function generateWeeklyByGroup($arr, $group, $preserveGroupKey = false, $preserveSubArrays = false) {
        $temp = array();
        foreach($arr as $key => $value) {
            $groupValue = $value[$group];
            if(!$preserveGroupKey)
            {
                unset($arr[$key][$group]);
            }
            if(!array_key_exists($groupValue, $temp)) {
                $temp[$groupValue] = array();
            }

            if(!$preserveSubArrays){
                $data = count($arr[$key]) == 1? array_pop($arr[$key]) : $arr[$key];
            } else {
                $data = $arr[$key];
            }
            $temp[$groupValue][] = $data;
        }
        return $temp;
    }

    public static function bulkInsertWeeklyCutoff($values){
        $sql = "INSERT INTO g_weekly_cutoff_period (year_tag,period_start,period_end,payout_date,cutoff_number,salary_cycle_id,is_lock,is_payroll_generated) 
            VALUES ".$values."
        ";
        
        Model::runSql($sql);
        return mysql_insert_id();    
    }

    public static function testmeplease(){
        

        $location       = G_Settings_Pay_Period_Finder::findAll($order_by,$limit);
        foreach ($location as $value) {
            echo "<pre>";
            var_dump($value);
            echo "</pre>";
            if(strtolower($value->pay_period_name) == "weekly"){
                $cut_off = $value->cut_off;
            }

            
        }
        
        

    }

// VALUES (
//                     ". Model::safeSql($current_year) .",
//                     ". Model::safeSql($start) .",
//                     ". Model::safeSql($end) .",
//                     ". Model::safeSql($type) .",
//                     ". Model::safeSql($payout_date) .",
//                     ". Model::safeSql($cutoff_number) ."
//                 )
//             ";  


	
}
?>