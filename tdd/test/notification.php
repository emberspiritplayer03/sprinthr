<?php
error_reporting(0);
class Notification extends UnitTestCase {
	
    function testCaseIncompleteRequirements()
	{
	    $e = G_Employee_Finder::findByEmployeeCode('3336');

        $er = G_Employee_Requirements_Finder::findByEmployeeId($e->getId());
        $er->setIsComplete(1); // set as complete requirements
        $er->save();
        
        $n = new G_Notifications();
        $n->updateNotifications();  
        $event_type_array = $n->getEventTypeArray();    
        $incomplete_requirements = $n->getSingleNotification($event_type_array['INCOMPLETE_REQUIREMENTS']);

        $this->assertEqual($incomplete_requirements->getItem(),3690);
 
	}
    
    function testCaseNoSalaryRate()
    {
        $end_date = '2014-08-30';
        $e = G_Employee_Finder::findByEmployeeCode('GL001');

        $ebsh = G_Employee_Basic_Salary_History_Finder::findByEmployeeIdSingle($e->getId());
        $ebsh->setEndDate($end_date); // set salary end_date 
        $ebsh->save();

        $n = new G_Notifications();
        $n->updateNotifications();  
        $event_type_array = $n->getEventTypeArray();       
        $no_salary_rate = $n->getSingleNotification($event_type_array['NO_SALARY_RATE']);

        $this->assertEqual($no_salary_rate->getItem(),1810);
        
    }

    function testCaseEndOfContract()
    {
        $end_date = '2014-09-29';
        $e = G_Employee_Finder::findByEmployeeCode('GL001');

        $esh = G_Employee_Subdivision_History_Finder::findEmployeeCurrentDepartment($e->getId());
        if($esh) {
           $esh->setEndDate($end_date); // set end of contract date 
            $esh->save(); 
        }

        $n = new G_Notifications();
        $n->updateNotifications();    
        $event_type_array = $n->getEventTypeArray();    
        $end_of_contract = $n->getSingleNotification($event_type_array['END_OF_CONTRACT']);

        $this->assertEqual($end_of_contract->getItem(),1);
        
    }

    function testCaseTardiness()
    {
        $e = G_Employee_Finder::findByEmployeeCode(428);

        $date = date('Y-m-d'); // date today

        $schedule_time_in = '08:00:00';
        $schedule_time_out = '21:00:00';

        $actual_time_in = '09:00:00';
        $actual_time_out = '21:00:00';

        // Add Specific Schedule
        $s = G_Schedule_Specific_Finder::findByEmployeeAndDate($e, $date);
        if (!$s) { $s = new G_Schedule_Specific; }
        $s->setDateStart('2014-09-04');
        $s->setDateEnd('2014-09-04');
        $s->setTimeIn($schedule_time_in);
        $s->setTimeOut($schedule_time_out);
        $s->setEmployeeId($e->getId());
        $s->save();

        $e->goToWork($date, $actual_time_in, $actual_time_out);

        $n = new G_Notifications();
        $n->updateNotifications();     
        $event_type_array = $n->getEventTypeArray();    
        $tardiness = $n->getSingleNotification($event_type_array['TARDINESS']);

        $this->assertEqual($tardiness->getItem(),1);
        
    }

    function testCaseIncompleteDTR()
    {
        $e = G_Employee_Finder::findByEmployeeCode(428);

        $date = '2014-09-25';

        $schedule_time_in = '08:00:00';
        $schedule_time_out = '21:00:00';

        $actual_time_in = '08:00:00';
        $actual_time_out = '';

        // Add Specific Schedule
        $s = G_Schedule_Specific_Finder::findByEmployeeAndDate($e, $date);
        if (!$s) { $s = new G_Schedule_Specific; }
        $s->setDateStart('2014-09-04');
        $s->setDateEnd('2014-09-04');
        $s->setTimeIn($schedule_time_in);
        $s->setTimeOut($schedule_time_out);
        $s->setEmployeeId($e->getId());
        $s->save();

        $e->goToWork($date, $actual_time_in, $actual_time_out);

        $n = new G_Notifications();
        $n->updateNotifications();  
        $event_type_array = $n->getEventTypeArray();      
        $incomplete_dtr = $n->getSingleNotification($event_type_array['INCOMPLETE_DTR']);

        $this->assertEqual($incomplete_dtr->getItem(),1);
        
    }
      	
}
?>