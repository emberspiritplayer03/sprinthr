<?php
error_reporting(0);
class TestNightShift extends UnitTestCase {
	
	function testNightshift() {
		$scheduled_time_in = '20:00:00';
		$scheduled_time_out = '0:00:00';
		$actual_time_in = '18:50:00';
		$actual_time_out = '7:30:00';

		$ns1 = new Nightshift_Calculator;
		$ns1->setScheduledTimeIn($scheduled_time_in);
		$ns1->setScheduledTimeOut($scheduled_time_out);
		$ns1->setActualTimeIn($actual_time_in);
		$ns1->setActualTimeOut($actual_time_out);
		echo $ns_hours = $ns1->compute();
		$this->assertEqual($ns_hours, 7);		
	}		
	
	function testNightshiftMinHrs() {		
		$scheduled_time_in = '20:00:00';
		$scheduled_time_out = '00:00:00';
		$actual_time_in = '20:00:00';
		$actual_time_out = '23:00:00';

		$ns1 = new Nightshift_Calculator;
		$ns1->setScheduledTimeIn($scheduled_time_in);
		$ns1->setScheduledTimeOut($scheduled_time_out);
		$ns1->setActualTimeIn($actual_time_in);
		$ns1->setActualTimeOut($actual_time_out);
		//echo $ns_hours = $ns1->compute();
		$this->assertEqual($ns_hours, 7);		
	}		
	
	function testNightshiftUntilMidnight() {
		$scheduled_time_in = '20:00:00';
		$scheduled_time_out = '05:00:00';
		$actual_time_in = '19:12:00';
		$actual_time_out = '00:13:00';

		$ns1 = new Nightshift_Calculator;
		$ns1->setScheduledTimeIn($scheduled_time_in);
		$ns1->setScheduledTimeOut($scheduled_time_out);
		$ns1->setActualTimeIn($actual_time_in);
		$ns1->setActualTimeOut($actual_time_out);
		$ns_hours = $ns1->compute();
		$this->assertEqual($ns_hours, 2.2167);
		
	}	
	
	function testNightshiftLessThanTheSchedule() {
		$scheduled_time_in = '20:00:00';
		$scheduled_time_out = '05:00:00';
		$actual_time_in = '21:25:00';
		$actual_time_out = '04:02:00';

		$ns1 = new Nightshift_Calculator;
		$ns1->setScheduledTimeIn($scheduled_time_in);
		$ns1->setScheduledTimeOut($scheduled_time_out);
		$ns1->setActualTimeIn($actual_time_in);
		$ns1->setActualTimeOut($actual_time_out);
		$ns_hours = $ns1->compute();
		$this->assertEqual($ns_hours, 6.0333);
		
	}
	
    function testRegularDayShift()
	{
		$scheduled_time_in = '08:00:00';
		$scheduled_time_out = '17:00:00';
		$actual_time_in = '08:00:00';
		$actual_time_out = '05:00:00';
		$overtime_in = '17:00:00';
		$overtime_out = '05:00:00';	

		$ns1 = new Nightshift_Calculator;
		$ns1->setScheduledTimeIn($scheduled_time_in);
		$ns1->setScheduledTimeOut($scheduled_time_out);
		$ns1->setOvertimeIn($overtime_in);
		$ns1->setOvertimeOut($overtime_out);
		$ns1->setActualTimeIn($actual_time_in);
		$ns1->setActualTimeOut($actual_time_out);
		$ns_hours = $ns1->compute();	
		
		$this->assertEqual($ns_hours, 0);
	}	
	
    function testRestdayNightShift()
	{
		$scheduled_time_in = '20:00:00';
		$scheduled_time_out = '05:00:00';
		$actual_time_in = '20:00:00';
		$actual_time_out = '09:00:00';
		$overtime_in = '20:00:00';
		$overtime_out = '09:00:00';		
		
		$ns1 = new Nightshift_Calculator;
		$ns1->setScheduledTimeIn($scheduled_time_in);
		$ns1->setScheduledTimeOut($scheduled_time_out);
		$ns1->setOvertimeIn($overtime_in);
		$ns1->setOvertimeOut($overtime_out);
		$ns1->setActualTimeIn($actual_time_in);
		$ns1->setActualTimeOut($actual_time_out);
		$ns_hours = $ns1->compute();
		
		$this->assertEqual($ns_hours, 0);
	}			
	
    function testRegularNightShift()
	{
		$scheduled_time_in = '20:00:00';
		$scheduled_time_out = '05:00:00';
		$actual_time_in = '20:00:00';
		$actual_time_out = '09:00:00';
		$overtime_in = '05:00:00';
		$overtime_out = '09:00:00';

		$ns1 = new Nightshift_Calculator;
		$ns1->setScheduledTimeIn($scheduled_time_in);
		$ns1->setScheduledTimeOut($scheduled_time_out);
		$ns1->setOvertimeIn($overtime_in);
		$ns1->setOvertimeOut($overtime_out);
		$ns1->setActualTimeIn($actual_time_in);
		$ns1->setActualTimeOut($actual_time_out);	
		$ns_hours = $ns1->compute();	
		
		$this->assertEqual($ns_hours, 7);

	}	

    function testDayShiftWithLimit()
	{
		$overtime_in = '07:29:00';
		$overtime_out = '18:08:00';

		$ns1 = new Nightshift_Calculator;
		$ns1->setScheduledTimeIn($scheduled_time_in);
		$ns1->setScheduledTimeOut($scheduled_time_out);
		$ns1->setOvertimeIn($overtime_in);
		$ns1->setOvertimeOut($overtime_out);
		$ns1->setActualTimeIn($actual_time_in);
		$ns1->setActualTimeOut($actual_time_out);
		$ns_hours = $ns1->compute();		
				
		$this->assertEqual($ns_hours, 0);
	}	
	
    function testRegularNightShiftHalfDay()
	{
		$scheduled_time_in = '20:00:00';
		$scheduled_time_out = '00:00:00';
		$actual_time_in = '20:00:00';
		$actual_time_out = '06:00:00';
		$overtime_in = '01:00:00';
		$overtime_out = '06:00:00';		
		
		$ns1 = new Nightshift_Calculator;
		$ns1->setScheduledTimeIn($scheduled_time_in);
		$ns1->setScheduledTimeOut($scheduled_time_out);
		$ns1->setOvertimeIn($overtime_in);
		$ns1->setOvertimeOut($overtime_out);
		$ns1->setActualTimeIn($actual_time_in);
		$ns1->setActualTimeOut($actual_time_out);
		$ns_hours = $ns1->compute();
		
		$this->assertEqual($ns_hours, 2);

	}				
}
?>