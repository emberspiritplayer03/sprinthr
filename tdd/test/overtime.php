<?php
error_reporting(0);
class TestOvertime extends UnitTestCase {	
	
    function testRegularDayShift()
	{
		$scheduled_time_in 	= '08:00:00';
		$scheduled_time_out = '17:00:00';
		$overtime_in 		= '17:00:00';
		$overtime_out 		= '20:00:00';			
		echo '<div style="margin-top: 1em; background-color: #c52b71; color: white;">Dayshift : ' .$scheduled_time_in. ' to ' .$scheduled_time_out. ' | Overtime IN/OUT: ' .$overtime_in. ' to ' .$overtime_out. '</div>';
					
		$o = new G_Overtime_Calculator;
		$o->setLimitHours(8);
		$o->setScheduleIn($scheduled_time_in);
		$o->setScheduleOut($scheduled_time_out);
		$o->setOvertimeIn($overtime_in);
		$o->setOvertimeOut($overtime_out);

		$expected_ot_hours        = 3;
		$expected_ot_excess_hours = 10;
		$expected_ot_nd 		  = 0;
		$expected_ot_excess_nd 	  = 0;

		$ot_hours 		 = $o->computeHours();
		$ot_excess_hours = $o->computeExcessHours();
		$ot_nd 			 = $o->computeNightDiff();
		$ot_excess_nd 	 = $o->computeExcessNightDiff();		

		echo '<div style="padding: 8px; margin-top: 1em; background-color: blue; color: white;">';
		echo 'OT Hours: ' . $ot_hours . ' | ';
		echo 'Expected Output: ' . $expected_ot_hours;
		echo '<br />';
		echo 'OT Excess Hours: ' . $ot_excess_hours . ' | ';
		echo 'Expected Output: ' . $expected_ot_excess_hours;
		echo '<br />';
		echo 'OT ND Hours: ' . $ot_nd . ' | ';
		echo 'Expected Output: ' . $expected_ot_nd;
		echo '<br />';
		echo 'OT Excess ND Hours: ' . $ot_excess_nd . ' | ';
		echo 'Expected Output: ' . $expected_ot_excess_nd;
		echo '</div>';
		echo '<hr />';

		$this->assertEqual($ot_hours, $expected_ot_hours);
		$this->assertEqual($ot_excess_hours, $expected_ot_excess_hours);		
		$this->assertEqual($ot_nd, $expected_ot_nd);
		$this->assertEqual($ot_excess_nd, $expected_ot_excess_nd);
	}	

    function testRegularDayShiftClassNew()
	{
		$scheduled_time_in 	= '08:00:00';
		$scheduled_time_out = '17:00:00';
		$overtime_in 		= '17:00:00';
		$overtime_out 		= '20:00:00';	

		echo '<div style="margin-top: 1em; background-color: #c52b71; color: white;">Dayshift : ' .$scheduled_time_in. ' to ' .$scheduled_time_out. ' | Overtime IN/OUT: ' .$overtime_in. ' to ' .$overtime_out. '</div>';

        $o = new G_Overtime_Calculator_New($overtime_in, $overtime_out);
        $o->setScheduleDateTime($scheduled_time_in, $scheduled_time_out);
        //$o->debugMode();;

		$expected_ot_hours        = 3;
		$expected_ot_excess_hours = 0;
		$expected_ot_nd 		  = 0;
		$expected_ot_excess_nd 	  = 0;

		$ot_hours 		 = $o->computeHours();
		$ot_excess_hours = $o->computeExcessHours();
		$ot_nd 			 = $o->computeNightDiff();
		$ot_excess_nd 	 = $o->computeExcessNightDiff();		

		echo '<div style="padding: 8px; margin-top: 1em; background-color: blue; color: white;">';
		echo 'OT Hours: ' . $ot_hours . ' | ';
		echo 'Expected Output: ' . $expected_ot_hours;
		echo '<br />';
		echo 'OT Excess Hours: ' . $ot_excess_hours . ' | ';
		echo 'Expected Output: ' . $expected_ot_excess_hours;
		echo '<br />';
		echo 'OT ND Hours: ' . $ot_nd . ' | ';
		echo 'Expected Output: ' . $expected_ot_nd;
		echo '<br />';
		echo 'OT Excess ND Hours: ' . $ot_excess_nd . ' | ';
		echo 'Expected Output: ' . $expected_ot_excess_nd;
		echo '</div>';
		echo '<hr />';

		$this->assertEqual($ot_hours, $expected_ot_hours);
		$this->assertEqual($ot_excess_hours, $expected_ot_excess_hours);		
		$this->assertEqual($ot_nd, $expected_ot_nd);
		$this->assertEqual($ot_excess_nd, $expected_ot_excess_nd);
	}	
		
}
?>