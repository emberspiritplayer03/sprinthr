<?php
class Overtime_Clerk extends UnitTestCase {
	
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
					
		$o = new G_Overtime_Calculator;
		$o->setScheduleIn($scheduled_time_in);
		$o->setScheduleOut($scheduled_time_out);
		$o->setOvertimeIn($overtime_in);
		$o->setOvertimeOut($overtime_out);
		$ot_hours = $o->computeHours();

		$ot_excess_hours = $o->computeExcessHours();
		$ot_nd = $o->computeNightDiff();
		$ot_excess_nd = $o->computeExcessNightDiff();		
		
		$this->assertEqual($ns_hours, 0);
		$this->assertEqual($ot_hours, 8);
		$this->assertEqual($ot_nd, 3);		
		$this->assertEqual($ot_excess_hours, 4);
		$this->assertEqual($ot_excess_nd, 4);
	}	
		
    function testHalfDayScheduleButHasOvertime()
	{
		$scheduled_time_in = '08:00:00';
		$scheduled_time_out = '12:00:00';
		$overtime_in = '12:00:00';
		$overtime_out = '14:35:00';

		$o = new G_Overtime_Calculator;
		$o->setScheduleIn($scheduled_time_in);
		$o->setScheduleOut($scheduled_time_out);		
		$o->setOvertimeIn($overtime_in);
		$o->setOvertimeOut($overtime_out);
		$ot_hours = $o->computeHours();	
		
		$this->assertEqual($ot_hours, 1.5833);
	}		
	
    function testOTMustNotExcess8HoursMoreExample()
	{
		$scheduled_time_in = '20:00:00';
		$scheduled_time_out = '05:00:00';		
		$overtime_in = '20:31:00';
		$overtime_out = '05:24:00';	
					
		$o = new G_Overtime_Calculator;
		$o->setOvertimeIn($overtime_in);
		$o->setOvertimeOut($overtime_out);
		$ot_hours = $o->computeHours();
		$this->assertEqual($ot_hours, 8);
	}		
	
    function testOTMustNotExcess8HoursAnotherExample()
	{
		$overtime_in = '20:05:00';
		$overtime_out = '05:04:00';	
					
		$o = new G_Overtime_Calculator;
		$o->setOvertimeIn($overtime_in);
		$o->setOvertimeOut($overtime_out);
		$ot_hours = $o->computeHours();
		$this->assertEqual($ot_hours, 8);
	}	
	
    function testOTMustNotExcess8Hours()
	{
		$scheduled_time_in = '19:00:00';
		$scheduled_time_out = '04:00:00';
		$overtime_in = '20:20:00';
		$overtime_out = '05:06:00';	
					
		$o = new G_Overtime_Calculator;
		$o->setScheduleIn($scheduled_time_in);
		$o->setScheduleOut($scheduled_time_out);
		$o->setOvertimeIn($overtime_in);
		$o->setOvertimeOut($overtime_out);
		$ot_hours = $o->computeHours();
		$this->assertEqual($ot_hours, 8);
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
		
		$o = new G_Overtime_Calculator;
		$o->setOvertimeIn($overtime_in);
		$o->setOvertimeOut($overtime_out);
		$ot_hours = $o->computeHours();
		$ot_excess_hours = $o->computeExcessHours();
		$ot_nd = $o->computeNightDiff();		
		$ot_excess_nd = $o->computeExcessNightDiff();		
		
		$this->assertEqual($ns_hours, 7);
		$this->assertEqual($ot_hours, 4);
		$this->assertEqual($ot_nd, 1);
		$this->assertEqual($ot_excess_hours, 0);
		$this->assertEqual($ot_excess_nd, 0);
	}	
	
    function testNightShiftFromClient()
	{
		$scheduled_time_in = '19:00:00';
		$scheduled_time_out = '04:00:00';		
		$overtime_in = '04:00:00';
		$overtime_out = '07:13:00';
				
		$o = new G_Overtime_Calculator;
		$o->setScheduleIn($scheduled_time_in);
		$o->setScheduleOut($scheduled_time_out);		
		$o->setOvertimeIn($overtime_in);
		$o->setOvertimeOut($overtime_out);

		$ot_nd = $o->computeNightDiff();
		$this->assertEqual($ot_nd, 2);		
	}		
	
    function testActualDataFromClient()
	{
		$overtime_in = '07:29:00';
		$overtime_out = '18:08:00';
				
		$o = new G_Overtime_Calculator;
		$o->setOvertimeIn($overtime_in);
		$o->setOvertimeOut($overtime_out);
		
		$ot_hours = $o->computeHours();	
		$ot_excess_hours = $o->computeExcessHours();
		$ot_nd = $o->computeNightDiff();
		$ot_excess_nd = $o->computeExcessNightDiff();
		
		$this->assertEqual($ot_hours, 8);
		$this->assertEqual($ot_excess_hours, 1.65);
		$this->assertEqual($ot_nd, 0);		
		$this->assertEqual($ot_excess_nd, 0);
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
				
		$o = new G_Overtime_Calculator;
		$o->setOvertimeIn($overtime_in);
		$o->setOvertimeOut($overtime_out);
		$ot_hours = $o->computeHours();		
		$ot_nd = $o->computeNightDiff();
		$ot_excess_hours = $o->computeExcessHours();
		$ot_excess_nd = $o->computeExcessNightDiff();
		
		$this->assertEqual($ns_hours, 0);
		$this->assertEqual($ot_hours, 8);
		$this->assertEqual($ot_nd, 7);
		$this->assertEqual($ot_excess_hours, 4);
		$this->assertEqual($ot_excess_nd, 1);
	}		
	
    function testDayShiftWithLimit()
	{
		$overtime_in = '07:29:00';
		$overtime_out = '18:08:00';
		
		$o = new G_Overtime_Calculator;
		$o->setScheduleIn($scheduled_time_in);
		$o->setScheduleOut($scheduled_time_out);
		$o->setOvertimeIn($overtime_in);
		$o->setOvertimeOut($overtime_out);	
		$ot_hours = $o->computeHours();	
		$ot_excess_hours = $o->computeExcessHours();

		$ns1 = new Nightshift_Calculator;
		$ns1->setScheduledTimeIn($scheduled_time_in);
		$ns1->setScheduledTimeOut($scheduled_time_out);
		$ns1->setOvertimeIn($overtime_in);
		$ns1->setOvertimeOut($overtime_out);
		$ns1->setActualTimeIn($actual_time_in);
		$ns1->setActualTimeOut($actual_time_out);
		$ns_hours = $ns1->compute();		
				
		$this->assertEqual($ot_hours, 8);
		$this->assertEqual($ns_hours, 0);
		$this->assertEqual($ot_excess_hours, 1.6500);
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
	
				
		$o = new G_Overtime_Calculator;
		$o->setOvertimeIn($overtime_in);
		$o->setOvertimeOut($overtime_out);
		$ot_hours = $o->computeHours();

		$ot_excess_hours = $o->computeExcessHours();
		$ot_nd = $o->computeNightDiff();
		$ot_excess_nd = $o->computeExcessNightDiff();
		
		$this->assertEqual($ns_hours, 2);
		$this->assertEqual($ot_hours, 5);
		$this->assertEqual($ot_nd, 5);
		$this->assertEqual($ot_excess_hours, 0);
		$this->assertEqual($ot_excess_nd, 0);
	}	
	
	
				
}
?>