<?php
error_reporting(0);
class Payroll extends UnitTestCase {
	
    function testCase01()
	{
		/*
		Employee Type : Monthly
		Rate : 12564
		Schedule : 6pm to 6am
		*/		
        
        echo "<pre>";
        
		$employee_type = "Monthly";
		$rate 		   = 12564;

        //Create Employee
        $c = G_Company_Factory::get();
        $e = G_Employee_Finder::findByEmployeeCode('2014-A');
        if( empty($e) ) {
            $c->hireEmployee('2014-A', 'Jongjong', 'Jang', 'Jing', '1985-11-01', 'Male', 'Married',
                0, '2014-01-01', 'Marketing', 'Website Designer', 'Regular', $rate, $employee_type);
            $e = G_Employee_Finder::findByEmployeeCode('2014-A');
        }else{

        }

        //Schedule
        $schedule_name = 'Schedule : Test Case 01';
        $working_days  = 'mon,tues,wed,thurs,fri';
        $time_in       = '18:00:00';
        $time_out      = '06:00:00';
        $schedule_id   = 1;

        //Attendance dummy db
        $attendance_start_date = '2014-08-21';
        $attendance_end_date   = '2014-09-05';
           
        $attendance = array(
        	0 => array(
        			"date" => '2014-08-21',
        			"time_in" => '18:00:00',
        			"time_out" => '04:00:00'
        		),
        	1 => array(
        			"date" => '2014-08-22',
        			"time_in" => '18:00:00',
                    "time_out" => '06:00:00'
        		),
        	2 => array(
        			"date" => '2014-08-23',
        			"time_in" => '18:00:00',
                    "time_out" => '02:00:00'
        		),
        	/*3 => array(
        			"date" => '2014-08-24',
        			"time_in" => '18:00:00',
                    "time_out" => '06:00:00'
        		), */
            4 => array(
        			"date" => '2014-08-25',
        			"time_in" => '18:00:00',
                    "time_out" => '06:00:00'
        		),
            5 => array(
        			"date" => '2014-08-26',
        			"time_in" => '18:00:00',
                    "time_out" => '06:00:00'
        		),
            6 => array(
        			"date" => '2014-08-27',
        			"time_in" => '18:00:00',
                    "time_out" => '06:00:00'
        		),
            7 => array(
        			"date" => '2014-08-28',
        			"time_in" => '18:00:00',
                    "time_out" => '06:00:00'
        		),
            8 => array(
        			"date" => '2014-08-29',
        			"time_in" => '18:00:00',
                    "time_out" => '06:00:00'
        		), 
            /*9 => array(
        			"date" => '2014-08-30',
        			"time_in" => '18:00:00',
                    "time_out" => '06:00:00'
        		),
            10 => array(
        			"date" => '2014-08-31',
        			"time_in" => '18:00:00',
                    "time_out" => '06:00:00'
        		),*/
            11 => array(
        			"date" => '2014-09-01',
        			"time_in" => '18:00:00',
                    "time_out" => '06:00:00'
        		), 
            12 => array(
        			"date" => '2014-09-02',
        			"time_in" => '18:00:00',
                    "time_out" => '06:00:00'
        		),
            13 => array(
        			"date" => '2014-09-03',
        			"time_in" => '18:00:00',
                    "time_out" => '06:00:00'
        		),
            14 => array(
        			"date" => '2014-09-04',
        			"time_in" => '18:00:00',
                    "time_out" => '06:00:00'
        		), 
            15 => array(
        			"date" => '2014-09-05',
        			"time_in" => '18:00:00',
                    "time_out" => '06:00:00'
        		)
        );
        
        //Schedule
        $s = G_Schedule_Finder::findById($schedule_id);  
        if( empty($s) ){
            
            $s = new G_Schedule;
            $s->setName($schedule_name);
            $s->setWorkingDays($working_days);
            $s->setTimeIn($time_in);
            $s->setTimeOut($time_out);
            $schedule_id = $s->save();   
            $s->assignToEmployee($e, '2012-10-25', '2020-10-30');//Assign to employee                     

            echo "New Schedule ID : {$schedule_id}";
            exit;

        }else{
            $s->assignToEmployee($e, '2012-10-25', '2020-10-30');//Assign to employee                                                                          
        }

        //Attendance
        foreach($attendance as $a){
        	$date     = date("Y-m-d",strtotime($a['date']));
        	$time_in  = date("H:i:s",strtotime($a['time_in']));
        	$time_out = date("H:i:s",strtotime($a['time_out']));
            $e->goToWork($date, $time_in, $time_out);//Record attendance           
        }

        $e_attendance = $e->getAttendance($attendance_start_date,$attendance_end_date);
        
        foreach($e_attendance as $a){            
            $timesheet = $a->getTimeSheet();            
            $total_hrs_worked += $timesheet->getTotalHoursWorked();
        }
        
        //$p  = $e->getPayslip('8', 2, '2014'); //Payslip Data
        //$er = $p->getOtherEarnings(); //Earnings
        //$payslip = G_Payslip_Helper::generatePayslip($e, $attendance_start_date, $attendance_end_date);
        
        $c      = G_Company_Factory::get();
        $period = G_Cutoff_Period_Finder::findByPeriod($attendance_start_date, $attendance_end_date);
        $p      = $c->generatePayslipByEmployee($e, '2014', '8', '2');
        
        print_r($p);    

           
	}		
}
?>