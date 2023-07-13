<?php
error_reporting(1);
define("BASE_PATH", str_replace("\\", "/", realpath(dirname(__FILE__))).'/');

// Manually inserted in the database
/*
 * Employee Code 1 = "Flores, Rose Ann"
 * 2 = "Dimaculangan , Monica K."
 *
 * Name: Sample Schedule
 * Effectivity Date: 2013-08-01
 * Schedule: mon,tue,wed,thu,fri (7:00 am - 5:00 pm)
 * "Flores, Rose Ann" is in this schedule
 */
/*
 * Name: Sample Schedule 2
 * Effectivity Date: 2013-06-01
 * Schedule: mon,tue,wed,thu,fri (8:00 am - 5:00 pm)
 * "Flores, Rose Ann" is in this schedule
 * "Senior Employee" group is in this schedule
 */
/*
 * Name: Sample Schedule 3
 * Effectivity Date: 2013-04-01
 * Schedule: mon,tue,wed,thu,fri (8:30 am - 5:30 pm)
 * "Flores, Rose Ann" is in this schedule
 * "Dimaculangan , Monica K." is in schedule
 * "Senior Employee" group is in this schedule
 */
/*
 * Name: Sample Schedule 3.1
 * Effectivity Date: 2013-04-01
 * Schedule: mon,tue,thu,fri,sun (9:30 am - 6:30 pm)
 *         : sat (10:00 am - 7:00 pm)
 * "Dimaculangan , Monica K." is in schedule (2)
 * "Senior Employee" group is in this schedule
 */
/*
 * Name: Sample Schedule 4 (Week 1)
 * Effectivity Date: 2013-03-03
 * Schedule: mon,tue,thu,fri (7:00 pm - 4:00 am)
 * "Flores, Rose Ann" is in this schedule
 * Groups: Senior Employee
 * Department: Logistics
 */
/*
 * Name: Sample Schedule 4 (Week 2)
 * Effectivity Date: 2013-03-10
 * Schedule: mon,tue,wed,thu (9:00 am - 6:00 pm)
 * "Flores, Rose Ann" is in this schedule
 * Pasicolan, Jefferson G. (5)
 */
/*
 * Name: Sample Schedule 4 (Week 3)
 * Effectivity Date: 2013-03-17
 * Schedule: mon,tue,wed,thu,fri (10:00 am - 7:00 pm)
 * "Flores, Rose Ann" is in this schedule
 * Department: Logistics
 */
/*
 * Name: Sample Schedule 4 (Week 4)
 * Effectivity Date: 2013-03-24
 * Schedule: mon,tue,wed,thu,fri,sat (8:00 am - 5:00 pm)
 * "Flores, Rose Ann" is in this schedule
 * Pasicolan, Jefferson G. (5)
 */
/*
 * Name: Sample Schedule 5
 * Effectivity Date: 2013-03-24
 * Schedule: tue,wed,thu (10:00 am - 7:00 pm)
 *           mon (11:00 am - 8:00 pm)
 * Employees:
 *      Gurango, Leo (3)
 *      Aguila, Michelle May (8)
 * Groups:
 *      Newbie
 */
/*
 * Name: Sample Schedule 6
 * Effectivity Date: 2013-05-01
 * Schedule: mon,tue,wed,thu,fri (8:00 am - 5:00 pm)
 * Employees:
 *      Aguila, Michelle May (8)
 *      Bacayon, Lynette (11)
 *      Actub, Sarah Mae (7)
 */
/*
 * Name: Sample Schedule 7
 * Effectivity Date: 2013-07-01
 * Schedule: mon,tue,thu,fri (8:00 pm - 5:00 am)
 *           wed (8:00 pm - 12:00 am)
 * Employees:
 *      Aguila, Michelle May (8)
 *      Actub, Sarah Mae (7)
 */
 /*
 * Name: Test for Restday
 * Effectivity Date: 2014-01-01
 * Schedule: mon,tue,wed,thu,fri,sat (8:00 am - 5:00 pm)
 *
 * Employees:
 *      Romulo, Carlos (2993)
 */
 /*
 * Name: Test for Restday 2
 * Effectivity Date: 2014-02-01
 * Schedule: mon,tue,wed,thu,fri (8:30 am - 5:30 pm)
 *
 * Employees:
 *      Romulo, Carlos (2993)
 */
 /*
 * Name: Test for Restday 3
 * Effectivity Date: 2014-03-02
 * Schedule:
    sat (9:00 am - 6:00 pm)
    sun (11:00 am - 8:00 pm)
    mon (10:00 am - 7:00 pm)
    tue (8:30 am - 5:30 pm)
    thu (7:00 am - 4:00 pm)
    fri (10:30 am - 7:30 pm)
 *
 * Employees:
 *      Romulo, Carlos (2993)
 */
/*  Name: Test for Import Employees
 *  Effectivity Date: 2014-01-06
 *  Schedule: mon,tue,wed,thu,fri (8:00 am - 5:00 pm)
*/
/*
*   Name: Test for Group
*   Effect Date: 2014-01-06
*   Schedule: mon,tue,wed,thu,fri (9:00 am - 6:00 pm)
*/
/*
*   Name: Test More Than 8 Hours
*   Effect Date: 2014-01-05
*   Schedule: mon,tue,wed,thu,fri (6:00 am - 5:00 pm)
*   Employees:
*       Millanes, Arjayson (4072)
*/
/*
 * Group: Senior Employee
 *      Baylon, Jennifer G. (13)
 *
 * Group: Newbie
 *      Actub, Sarah Mae (7)
 *
 * Group: Test Group
 *      Dinglasan, Corazon G. (394)
 *
 * Department: Logistics
 *      Pasicolan, Jefferson G. (5)
 */

class TestSchedule_Import_Changed_Schedule extends UnitTestCase {
    function test_Import_Changed_Schedule() {
        $file = BASE_PATH . "schedule/specific_schedule1.xlsx";
        $sched = new G_Schedule_Specific_Import($file);
        $sched->import();
    }

    function test_Import_Employees_In_Schedule() {
        $file = BASE_PATH . "schedule/employees_for_weekly_schedule.xlsx";
        $g = new G_Schedule_Import_Employees($file);
        $sg = G_Schedule_Group_Finder::findByName('Test for Import Employees');
        $g->import($sg);

        $es = G_Employee_Finder::findByScheduleGroup($sg);

        // CHECK EMPLOYEES
        $employees = array('Fabella, Rose Ann May', 'Fernando, Irene', 'Ternida, Gretchen');
        foreach ($es as $e) {
            $results[] = $e->getName();
        }
        //echo '<pre>';
        //print_r($employees);
        //print_r($results);
        $diff = array_diff($employees, $results);
        $this->assertEqual(count($diff), 3);
    }
}

class TestSchedule_Import_Weekly extends UnitTestCase {
    function testSchedule_With_Space_Time_Format()
	{
        $file = BASE_PATH . "schedule/weekly_schedule3.xlsx";
        $effect_date = '2014-01-01';
        $name = 'New Schedule 3';

        // DELETE EXISTING FIRST
        $sg = G_Schedule_Group_Finder::findByNameAndEffectivityDate($name, $effect_date);
        if ($sg) {
            $sg->deleteAll();
        }

        // IMPORT SCHEDULES WITH EMPLOYEES
		$g = new G_Schedule_Import_Weekly($file);
        $g->setScheduleName($name);
		$g->setEffectivityDate($effect_date);
        $total = G_Schedule_Group_Helper::countByNameAndEffectivityDate($name, $effect_date);
        $g->import();

        $sg = G_Schedule_Group_Finder::findByNameAndEffectivityDate($name, $effect_date);
        $es = G_Employee_Finder::findByScheduleGroup($sg);

        // CHECK EMPLOYEES
        $employees = array('Reyes, Elizabeth', 'Reyes, Annalyn', 'De Los Reyes, Gelliza');
        foreach ($es as $e) {
            $results[] = $e->getName();
        }
        //echo '<pre>';
        //print_r($employees);
        //print_r($results);
        $diff = array_diff($employees, $results);
        $this->assertEqual(count($diff), 3);

        // CHECK SCHEDULES
        $correct_scheds = array('fri'=>'10:00:00-08:00:00','sat'=>'22:30:00-08:30:00', 'tue,wed,thu'=>'21:00:00-07:00:00', 'mon'=>'19:00:00-07:00:00');
        $scheds = $sg->getSchedules();
        foreach ($scheds as $sched) {
            $answer_scheds[$sched->getWorkingDays()] = $sched->getTimeIn() .'-'. $sched->getTimeOut();
        }
        //echo '<pre>';
        //print_r($correct_scheds);
        //print_r($answer_scheds);
        $this->assertEqual($answer_scheds, $correct_scheds);
	}

    function testSchedule_Different_Time_Format()
	{
        $file = BASE_PATH . "schedule/weekly_schedule2.xlsx";
        $effect_date = '2014-01-01';
        $name = 'New Schedule 2';

        // DELETE EXISTING FIRST
        $sg = G_Schedule_Group_Finder::findByNameAndEffectivityDate($name, $effect_date);
        if ($sg) {
            $sg->deleteAll();
        }

        // IMPORT SCHEDULES WITH EMPLOYEES
		$g = new G_Schedule_Import_Weekly($file);
        $g->setScheduleName($name);
		$g->setEffectivityDate($effect_date);
        $total = G_Schedule_Group_Helper::countByNameAndEffectivityDate($name, $effect_date);
        $g->import();

        $sg = G_Schedule_Group_Finder::findByNameAndEffectivityDate($name, $effect_date);
        $es = G_Employee_Finder::findByScheduleGroup($sg);

        // CHECK EMPLOYEES
        $employees = array('Reyes, Elizabeth', 'Reyes, Annalyn', 'De Los Reyes, Gelliza');
        foreach ($es as $e) {
            $results[] = $e->getName();
        }
        //echo '<pre>';
        //print_r($employees);
        //print_r($results);
        $diff = array_diff($employees, $results);
        $this->assertEqual(count($diff), 3);

        // CHECK SCHEDULES
        $correct_scheds = array('fri'=>'10:00:00-08:00:00','sat'=>'22:30:00-08:30:00', 'tue,wed,thu'=>'21:00:00-07:00:00', 'mon'=>'19:00:00-07:00:00');
        $scheds = $sg->getSchedules();
        foreach ($scheds as $sched) {
            $answer_scheds[$sched->getWorkingDays()] = $sched->getTimeIn() .'-'. $sched->getTimeOut();
        }
        //echo '<pre>';
        //print_r($correct_scheds);
        //print_r($answer_scheds);
        $this->assertEqual($answer_scheds, $correct_scheds);
	}

    function testSchedule_Original_Time_Format()
	{
        $file = BASE_PATH . "schedule/weekly_schedule1.xlsx";
        $effect_date = '2014-01-01';
        $name = 'New Schedule';

        $sg = G_Schedule_Group_Finder::findByNameAndEffectivityDate($name, $effect_date);
        if ($sg) {
            $sg->deleteAll();
        }

		$g = new G_Schedule_Import_Weekly($file);
        $g->setScheduleName($name);
		$g->setEffectivityDate($effect_date);
        $total = G_Schedule_Group_Helper::countByNameAndEffectivityDate($name, $effect_date);
        $g->import();

        $sg = G_Schedule_Group_Finder::findByNameAndEffectivityDate($name, $effect_date);
        $es = G_Employee_Finder::findByScheduleGroup($sg);

        // CHECK EMPLOYEES
        $employees = array('De Los Reyes, Julie Ann', 'De Los Reyes, Gelliza', 'Reyes, Annalyn', 'Reyes, Mabel', 'Reyes, Elizabeth');
        foreach ($es as $e) {
            $results[] = $e->getName();
        }
        //echo '<pre>';
        //print_r($employees);
        //print_r($results);
        $diff = array_diff($employees, $results);
        $this->assertEqual(count($diff), 5);

        // CHECK SCHEDULES
        $correct_scheds = array('fri'=>'10:00:00-08:00:00','sat'=>'22:30:00-08:30:00', 'tue,wed,thu'=>'21:00:00-07:00:00', 'mon'=>'19:00:00-07:00:00');
        $scheds = $sg->getSchedules();
        foreach ($scheds as $sched) {
            $answer_scheds[$sched->getWorkingDays()] = $sched->getTimeIn() .'-'. $sched->getTimeOut();
        }
        //echo '<pre>';
        //print_r($correct_scheds);
        //print_r($answer_scheds);
        $this->assertEqual($answer_scheds, $correct_scheds);
	}
}

class TestSchedule_By_List_Of_Assigned_Schedules extends UnitTestCase {
    function testScheduleList2()
	{
		$e = G_Employee_Finder::findByEmployeeCode(7); // Sarah Mae Actub
        $schedules = G_Schedule_Group_Helper::getAllScheduleGroupsByEmployee($e);

        $counter = 0;
        $assigned_schedules = array('Sample Schedule 7', 'Sample Schedule 6', 'Sample Schedule 5');
        foreach ($schedules as $s) {
            $name = $s->getName();
            $this->assertIdentical($name, $assigned_schedules[$counter]);
            $counter++;
        }
	}
    function testScheduleList1()
	{
		$e = G_Employee_Finder::findByEmployeeCode(1); // Flores, Rose Ann
        $schedules = G_Schedule_Group_Helper::getAllScheduleGroupsByEmployee($e);
        $counter = 0;
        $assigned_schedules = array('Sample Schedule', 'Sample Schedule 2', 'Sample Schedule 3', 'Sample Schedule 4 (Week 4)', 'Sample Schedule 4 (Week 3)', 'Sample Schedule 4 (Week 2)', 'Sample Schedule 4 (Week 1)');
        foreach ($schedules as $s) {
            $name = $s->getName();
            $this->assertIdentical($name, $assigned_schedules[$counter]);
            $counter++;
        }
	}
}
class TestSchedule_By_Effectivity_Date extends UnitTestCase {

    function testSchedule1()
	{
		$e = G_Employee_Finder::findByEmployeeCode(1); // Flores, Rose Ann
        $s = G_Schedule_Finder::findActiveByEmployee($e, '2013-11-01');
        $name = $s->getName();

		$this->assertIdentical($name, 'Sample Schedule');
	}

    function testSchedule2()
    {
        $e = G_Employee_Finder::findByEmployeeCode(1); // Flores, Rose Ann
        $s = G_Schedule_Finder::findActiveByEmployee($e, '2013-07-01');
        $name = $s->getName();

        $this->assertIdentical($name, 'Sample Schedule 2');
    }

    function testSchedule3()
    {
        $e = G_Employee_Finder::findByEmployeeCode(1); // Flores, Rose Ann
        $s = G_Schedule_Finder::findActiveByEmployee($e, '2013-05-31');
        $name = $s->getName();

        $this->assertIdentical($name, 'Sample Schedule 3');
    }
    function testSchedule4()
    {
        $e = G_Employee_Finder::findByEmployeeCode(1); // Flores, Rose Ann
        $s = G_Schedule_Finder::findActiveByEmployee($e, '2013-06-01');
        $name = $s->getName();

        $this->assertIdentical($name, 'Sample Schedule 2');
    }
    function testSchedule_weekly()
    {
        $e = G_Employee_Finder::findByEmployeeCode(1); // Flores, Rose Ann
        $s = G_Schedule_Finder::findActiveByEmployee($e, '2013-03-18');
        $name = $s->getName();

        $this->assertIdentical($name, 'Sample Schedule 4 (Week 3)');
    }
    function testSchedule_weekly2()
    {
        $e = G_Employee_Finder::findByEmployeeCode(1); // Flores, Rose Ann
        $s = G_Schedule_Finder::findActiveByEmployee($e, '2013-03-16');
        $name = $s->getName();

        $this->assertIdentical($name, 'Sample Schedule 4 (Week 2)');
    }
    function testSchedule_weekly3()
    {
        $e = G_Employee_Finder::findByEmployeeCode(1); // Flores, Rose Ann
        $s = G_Schedule_Finder::findActiveByEmployee($e, '2013-03-10');
        $name = $s->getName();

        $this->assertIdentical($name, 'Sample Schedule 4 (Week 2)');
    }
    function testSchedule_weekly4()
    {
        $e = G_Employee_Finder::findByEmployeeCode(1); // Flores, Rose Ann
        $s = G_Schedule_Finder::findActiveByEmployee($e, '2013-03-05');
        $name = $s->getName();

        $this->assertIdentical($name, 'Sample Schedule 4 (Week 1)');
    }
    function testSchedule_weekly5()
    {
        $e = G_Employee_Finder::findByEmployeeCode(1); // Flores, Rose Ann

        $s = G_Schedule_Finder::findActiveByEmployee($e, '2013-03-31');
        $name = $s->getName();
        $this->assertIdentical($name, 'Sample Schedule 4 (Week 4)');

        $s2 = G_Schedule_Finder::findActiveByEmployee($e, '2013-03-23');
        $name2 = $s2->getName();
        $this->assertIdentical($name2, 'Sample Schedule 4 (Week 3)');

        $s3 = G_Schedule_Finder::findActiveByEmployee($e, '2013-04-01');
        $name3 = $s3->getName();
        $this->assertIdentical($name3, 'Sample Schedule 3');
    }
    function testSchedule5()
    {
        /*
         * Two schedules with same effectivity date
         */
        $e = G_Employee_Finder::findByEmployeeCode(2); // "Dimaculangan , Monica K."
        $s = G_Schedule_Finder::findActiveByEmployee($e, '2013-04-01');
        $name = $s->getName();
        $this->assertIdentical($name, 'Sample Schedule 3.1');
    }
    function testSchedule6()
    {
        /*
         * Has active schedule but no work on that date
         */
        $e = G_Employee_Finder::findByEmployeeCode(2); // "Dimaculangan , Monica K."
        $s = G_Schedule_Finder::findActiveByEmployee($e, '2013-04-03'); // has active schedule on this date
        $name = $s->getName();
        $this->assertIdentical($name, 'Sample Schedule 3.1');

        $s2 = G_Schedule_Finder::findByEmployeeAndDate($e, '2013-04-03'); // no work on this date
        $answer = 'no schedule';
        if ($s2) {
            $answer = 'has schedule';
        }
        $this->assertIdentical($answer, 'no schedule');
    }


}

class TestSchedule_By_Specific_Date extends UnitTestCase {

    function testSchedule1()
    {
        $e = G_Employee_Finder::findByEmployeeCode(1); // Flores, Rose Ann
        $s = G_Schedule_Finder::findByEmployeeAndDate($e, '2013-03-17'); // Sunday - Sample Schedule 4 (Week 3)

        $answer = 'no schedule';
        if ($s) {
            $answer = 'has schedule';
        }
        $this->assertIdentical($answer, 'no schedule');
    }
    function testSchedule2()
    {
        $e = G_Employee_Finder::findByEmployeeCode(1); // Flores, Rose Ann
        $s = G_Schedule_Finder::findByEmployeeAndDate($e, '2013-03-11'); // Monday - ample Schedule 4 (Week 2)

        $answer = 'no schedule';
        if ($s) {
            $answer = 'has schedule';
        }
        $this->assertIdentical($answer, 'has schedule');
    }
    function testSchedule3()
    {
        $e = G_Employee_Finder::findByEmployeeCode(1); // Flores, Rose Ann
        $s = G_Schedule_Finder::findByEmployeeAndDate($e, '2013-03-06'); // Wed - Sample Schedule 4 (Week 1)

        $answer = 'no schedule';
        if ($s) {
            $answer = 'has schedule';
        }
        $this->assertIdentical($answer, 'no schedule');
    }
    function testSchedule4()
    {
        $e = G_Employee_Finder::findByEmployeeCode(2); // "Dimaculangan , Monica K."
        $s = G_Schedule_Finder::findByEmployeeAndDate($e, '2013-04-03'); // Wed - No work
        $answer = 'no schedule';
        if ($s) {
            $answer = 'has schedule';
        }
        $this->assertIdentical($answer, 'no schedule');
    }
    function testSchedule5()
    {
        $e = G_Employee_Finder::findByEmployeeCode(2); // "Dimaculangan , Monica K."
        $s = G_Schedule_Finder::findByEmployeeAndDate($e, '2013-04-20'); // Sat - Has work
        $answer = 'no schedule';
        if ($s) {
            $answer = 'has schedule';
        }
        $this->assertIdentical($answer, 'has schedule');
    }
}

class TestSchedule_By_Group extends UnitTestCase {
    function testSchedule1()
    {
        $e = G_Employee_Finder::findByEmployeeCode(13); // Baylon, Jennifer G. (Senior Employee)
        $g = G_Group_Finder::findLatestByEmployee($e);
        $s = G_Schedule_Finder::findActiveByGroup($g, '2013-05-25');
        $name = $s->getName();

        $this->assertIdentical($name, 'Sample Schedule 3.1');
    }
    function testSchedule2()
    {
        $e = G_Employee_Finder::findByEmployeeCode(13); // Baylon, Jennifer G. (Senior Employee)
        $g = G_Group_Finder::findLatestByEmployee($e);
        $s = G_Schedule_Finder::findActiveByGroup($g, '2013-06-15');
        $name = $s->getName();

        $this->assertIdentical($name, 'Sample Schedule 2');
    }
    function testSchedule3()
    {
        $e = G_Employee_Finder::findByEmployeeCode(13); // Baylon, Jennifer G. (Senior Employee)
        $g = G_Group_Finder::findLatestByEmployee($e);
        $s = G_Schedule_Finder::findActiveByGroup($g, '2013-06-15'); // Sat - has active schedule
        $answer = 'no schedule';
        if ($s) {
            $answer = 'has schedule';
        }
        $this->assertIdentical($answer, 'has schedule');

        $s2 = G_Schedule_Finder::findByGroupAndDate($g, '2013-06-17'); // Mon - has work
        $answer2 = 'no schedule';
        if ($s2) {
            $answer2 = 'has schedule';
        }
        $this->assertIdentical($answer2, 'has schedule');
    }
    function testSchedule4()
    {
        $e = G_Employee_Finder::findByEmployeeCode(13); // Baylon, Jennifer G. (Senior Employee)
        $g = G_Group_Finder::findLatestByEmployee($e);
        $s = G_Schedule_Finder::findActiveByGroup($g, '2013-06-15'); // Sat - has active schedule
        $answer = 'no schedule';
        if ($s) {
            $answer = 'has schedule';
        }
        $this->assertIdentical($answer, 'has schedule');

        $s2 = G_Schedule_Finder::findByGroupAndDate($g, '2013-06-15'); // Sat - no work
        $answer2 = 'no schedule';
        if ($s2) {
            $answer2 = 'has schedule';
        }
        $this->assertIdentical($answer2, 'no schedule');
    }
}
