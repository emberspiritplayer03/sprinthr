<?php
error_reporting(1);

define("BASE_PATH", str_replace("\\", "/", realpath(dirname(__FILE__))).'/');

/*interface ITest{
    public function show();
}

abstract class Test implements ITest  {
    protected $x;
    public function setValue($value) {
        $this->x = $value;
    }
    public function getValue() {
        return $this->x;
    }
    abstract function show();
}

class Testing extends Test {
    public function show() {
        echo $this->x;
    }
}

class Testing2 extends Test {
    public function show() {
        echo $this->x . $this->add();
    }
    private function add() {
        return '-adds';
    }
}

class MotherClass {
    protected $obj;
    public function __construct(Test $t) {
        $this->obj = $t;
    }

    public function show() {
        echo $this->obj->show();
    }
}*/

class TestAttendance_earning extends UnitTestCase {
    function test_DTR() {

        //$e = G_Employee_Finder::findByEmployeeCode('2014-A');
        //$a = $e->getAttendance('2014-02-15');

        //$file = BASE_PATH . "timesheet/testing2.xlsx";
        //G_Attendance_Helper::importTimesheet($file);

        //$logs = G_Attendance_Log_Finder::findAllByPeriod('2014-04-21', '2014-04-22');
        //echo '<pre>';
        //print_r($logs);
        //exit;
    }

    /*function test_earning() {
        $c = G_Company_Factory::get();
        $c->hireEmployee('2014-A', 'Jongjong', 'Jang', 'Jing', '1985-11-01', 'Male', 'Married',
            0, '2014-01-01', 'Marketing', 'Website Designer', 'Regular', 350, 'Daily');

        $e = G_Employee_Finder::findByEmployeeCode('2014-A');
        //echo '<pre>';
        //print_r($e);

        $date = '2014-02-06';
        //$e->goToWork($date, '', '');

        $date = '2014-02-07';
        $e->goToWork($date, '08:30:00', '19:00:00');

        $date = '2014-02-10';
        $e->goToWork($date, '09:00:00', '17:00:00');

        $date = '2014-02-11';
        $e->goToWork($date, '09:30:00', '18:00:00');

        $date = '2014-02-12';
        //$e->goToWork($date, '', '');

        $date = '2014-02-13';
        //$e->goToWork($date, '', '');

        $date = '2014-02-14';
        $e->goToWork($date, '09:30:00', '18:00:00');

        $date = '2014-02-15';
        $e->goToWork($date, '09:30:00', '18:00:00');

        $date = '2014-02-17';
        $e->goToWork($date, '09:30:00', '18:00:00');

        $date = '2014-02-18';
        $e->goToWork($date, '09:30:00', '18:00:00');

        $date = '2014-02-19';
        $e->goToWork($date, '09:30:00', '18:00:00');

        $date = '2014-02-20';
        $e->goToWork($date, '09:30:00', '18:00:00');

        //$p = $c->generatePayslipByEmployee($e, '2014', 2, 1);

        $p = $e->getPayslip('2', 1);
        //$d = $p->getOtherDeductions();
        $er = $p->getOtherEarnings();

        echo '<pre>';
        //print_r($p);
        //print_r($er);

        //$ee = G_Employee_Earnings_Finder::findById(5);
        //G_Employee_Earnings_Helper::addToPayslip($ee);

        //echo '<pre>';
        //print_r($ee);

        //echo '<pre>';
        //print_r($p);

        //exit;
    }*/
}
class TestAttendance_payslip extends UnitTestCase {
    function test_payslip_daily() {

        $e = G_Employee_Finder::findByEmployeeCode('2014-054');

        $date = '2014-02-06';
        $e->goToWork($date, '08:30:00', '19:00:00');

        $date = '2014-02-07';
        $e->goToWork($date, '08:30:00', '19:00:00');

        $date = '2014-02-10';
        $e->goToWork($date, '09:00:00', '17:00:00');

        $date = '2014-02-11';
        $e->requestLeave(G_Leave_Finder::findVacation(), $date, $date, $date);
        $leave = $e->getLeaveRequest($date);
        $leave->approve();

        $date = '2014-02-12';
        $e->goToWork($date, '08:00:00', '18:00:00');
        $e->requestOvertime($date, '18:00:00', '08:00:00');
        $o = $e->getOvertimeRequest($date);
        $o->approve();

        $date = '2014-02-13';
        $e->goToWork($date, '08:00:00', '18:00:00');

        $date = '2014-02-14';
        $e->goToWork($date, '09:30:00', '18:00:00');

        $c = G_Company_Factory::get();
        $p = $c->generatePayslipByEmployee($e, '2014', 2, 1);

        $total_earnings = $p->getTotalEarnings();
        //$this->assertEqual($total_earnings, 4804.28);

        $total_deductions = $p->getTotalDeductions();
        //$this->assertEqual($total_deductions, 308.8);

        $net_pay = $p->getNetPay();
        //$this->assertEqual($net_pay, 4495.48);

        $tax = $p->getWithheldTax();
        //$this->assertEqual($tax, 17.29);

        //echo '<pre>';
        //print_r($p);
        //exit;
    }

    function test_payslip_monthly2() {

        $e = G_Employee_Finder::findByEmployeeCode('2014-055');

        $date = '2014-02-06';
        //$e->goToWork($date, '', '');

        $date = '2014-02-07';
        $e->goToWork($date, '08:30:00', '19:00:00');

        $date = '2014-02-10';
        $e->goToWork($date, '09:00:00', '17:00:00');

        $date = '2014-02-11';
        $e->requestLeave(G_Leave_Finder::findVacation(), $date, $date, $date);
        $leave = $e->getLeaveRequest($date);
        $leave->approve();

        $date = '2014-02-12';
        //$e->goToWork($date, '', '');

        $date = '2014-02-13';
        //$e->goToWork($date, '', '');

        $date = '2014-02-14';
        $e->goToWork($date, '09:30:00', '18:00:00');

        $date = '2014-02-15';
        $e->goToWork($date, '09:30:00', '18:00:00');

        $date = '2014-02-17';
        $e->goToWork($date, '09:30:00', '18:00:00');

        $date = '2014-02-18';
        $e->goToWork($date, '09:30:00', '18:00:00');

        $date = '2014-02-19';
        $e->goToWork($date, '09:30:00', '18:00:00');

        $date = '2014-02-20';
        //$e->goToWork($date, '', '');

        $c = G_Company_Factory::get();
        $p = $c->generatePayslipByEmployee($e, '2014', 2, 1);

        $total_earnings = $p->getTotalEarnings();
        //$this->assertEqual($total_earnings, 8245.13);

        $total_deductions = $p->getTotalDeductions();
        //$this->assertEqual($total_deductions, 3164.58);

        $net_pay = $p->getNetPay();
        //$this->assertEqual($net_pay, 5080.55);

        $tax = $p->getWithheldTax();
        //$this->assertEqual($tax, 0);

        //echo '<pre>';
        //print_r($p);
        //exit;
    }

    function test_payslip_daily2() {

        $e = G_Employee_Finder::findByEmployeeCode('2014-051');

        $date = '2014-02-06';
        //$e->goToWork($date, '', '');

        $date = '2014-02-07';
        $e->goToWork($date, '08:30:00', '19:00:00');

        $date = '2014-02-10';
        $e->goToWork($date, '09:00:00', '17:00:00');

        $date = '2014-02-11';
        $e->requestLeave(G_Leave_Finder::findVacation(), $date, $date, $date);
        $leave = $e->getLeaveRequest($date);
        $leave->approve();

        $date = '2014-02-12';
        //$e->goToWork($date, '', '');

        $date = '2014-02-13';
        //$e->goToWork($date, '', '');

        $date = '2014-02-14';
        $e->goToWork($date, '09:30:00', '18:00:00');

        $date = '2014-02-15';
        $e->goToWork($date, '09:30:00', '18:00:00');

        $date = '2014-02-17';
        $e->goToWork($date, '09:30:00', '18:00:00');

        $date = '2014-02-18';
        $e->goToWork($date, '09:30:00', '18:00:00');

        $date = '2014-02-19';
        $e->goToWork($date, '09:30:00', '18:00:00');

        $date = '2014-02-20';
        //$e->goToWork($date, '', '');

        $c = G_Company_Factory::get();
        $p = $c->generatePayslipByEmployee($e, '2014', 2, 1);

        $total_earnings = $p->getTotalEarnings();
        //$this->assertEqual($total_earnings, 2905);

        $total_deductions = $p->getTotalDeductions();
        //$this->assertEqual($total_deductions, 422.76);

        $net_pay = $p->getNetPay();
        //$this->assertEqual($net_pay, 2482.24);

        $tax = $p->getWithheldTax();
        //$this->assertEqual($tax, 0);

        //echo '<pre>';
        //print_r($p);
    }

    function test_payslip_monthly() {

        $e = G_Employee_Finder::findByEmployeeCode('2014-555');
        //$e = G_Employee_Finder::findByEmployeeCode('2014-054');

        $date = '2014-02-06';
        $e->goToWork($date, '08:30:00', '19:00:00');

        $date = '2014-02-07';
        $e->goToWork($date, '08:30:00', '19:00:00');

        $date = '2014-02-10';
        $e->goToWork($date, '09:00:00', '17:00:00');

        $date = '2014-02-11';
        $e->requestLeave(G_Leave_Finder::findVacation(), $date, $date, $date);
        $leave = $e->getLeaveRequest($date);
        $leave->approve();

        $date = '2014-02-12';
        $e->goToWork($date, '08:00:00', '18:00:00');
        $e->requestOvertime($date, '18:00:00', '08:00:00');
        $o = $e->getOvertimeRequest($date);
        $o->approve();

        $date = '2014-02-13';
        $e->goToWork($date, '08:00:00', '18:00:00');

        $date = '2014-02-14';
        $e->goToWork($date, '09:30:00', '18:00:00');

        $c = G_Company_Factory::get();
        $p = $c->generatePayslipByEmployee($e, '2014', 2, 1);

        $total_earnings = $p->getTotalEarnings();
        //$this->assertEqual($total_earnings, 14314.36);

        $total_deductions = $p->getTotalDeductions();
        //$this->assertEqual($total_deductions, 5974.23);

        $net_pay = $p->getNetPay();
        //$this->assertEqual($net_pay, 8340.13);

        $tax = $p->getWithheldTax();
        //$this->assertEqual($tax, 1391.04);

        //echo '<pre>';
        //print_r($p);
    }
}

class TestAttendance_Leave extends UnitTestCase {
    function test_import_leaves_request_and_leave_credit() {
        // DELETE AVAILABLE CREDITS AND DELETE LEAVE REQUEST
        $es[] = G_Employee_Finder::findByEmployeeCode(67);
        $es[] = G_Employee_Finder::findByEmployeeCode(68);
        $es[] = G_Employee_Finder::findByEmployeeCode(69);
        $es[] = G_Employee_Finder::findByEmployeeCode(72);
        foreach ($es as $e) {
            $l = G_Leave_Finder::findById(G_Leave::ID_SICK);
            $la = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveIdYear($e->getId(), $l->getId(), '2015');
            if ($la) {
                $la->delete();
            }
            $l = G_Leave_Finder::findById(G_Leave::ID_VACATION);
            $la = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveIdYear($e->getId(), $l->getId(), '2015');
            if ($la) {
                $la->delete();
            }

            $lrds = G_Employee_Leave_Request_Finder::findByEmployeeId($e->getId());
            foreach ($lrds as $lrd) {
                if ($lrd) {
                    $lrd->delete();
                }
            }
        }

        $file = BASE_PATH . "leave/import_leave_credit.xlsx";
        $l = new G_Employee_Leave_Available_Importer($file);
        $l->import();

        $file2 = BASE_PATH . "leave/import_leave_requests.xls";
        $lr = new G_Employee_Leave_Request_Importer($file2);
        $lr->import();

        // CHECK
        $e = G_Employee_Finder::findById(562);
        $l = G_Leave_Finder::findById(G_Leave::ID_VACATION);
        $check = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveIdYear($e->getId(), $l->getId(), '2015');
        
        $this->assertEqual($check->getNoOfDaysAlloted(), 3.5);
        $this->assertEqual($check->getNoOfDaysAvailable(), 3.5);

        $e = G_Employee_Finder::findById(561);
        $lr = $e->getLeaveRequest('2015-02-04');
        $this->assertEqual($lr->getIsPaid(), G_Employee_Leave_Request::IS_PAID_NO); // NO AVAILABLE LEAVE CREDIT
    }
    function test_can_add_leave_credit_to_a_group() {
        $g = G_Group_Finder::findByName('Logistics');
        $l = G_Leave_Finder::findByName(G_Leave::NAME_VACATION);

        // RESET AVAILABLE CREDITS
        $es = $g->getEmployees();
        foreach ($es as $e) {
            $la = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveIdYear($e->getId(), $l->getId(), '2015');
            if ($la) {
                $la->delete();
            }
        }

        $g->addLeaveCredit($l, 2);
        $g->addLeaveCredit($l, 1.5);

        foreach ($es as $e) {
            $days = $e->getAvailableLeaveCredit($l);
            $this->assertEqual($days, 3.5);
        }
    }

    function test_can_manually_add_leave_credit_to_employee2() {
        $e = G_Employee_Finder::findByEmployeeCode(4003);
        $l = G_Leave_Finder::findByName(G_Leave::NAME_VACATION);

        // RESET AVAILABLE CREDITS
        $la = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveIdYear($e->getId(), $l->getId(), '2015');
        if ($la) {
            $la->delete();
        }
        $e->addLeaveCredit($l, 2);
        $e->addLeaveCredit($l, 1.5);

        // CHECK
        $check = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveIdYear($e->getId(), $l->getId(), '2015');
        $this->assertEqual($check->getNoOfDaysAlloted(), 3.5);
        $this->assertEqual($check->getNoOfDaysAvailable(), 3.5);
    }

    function test_can_manually_add_leave_credit_to_employee() {
        $e = G_Employee_Finder::findById(3519);
        $l = G_Leave_Finder::findByName(G_Leave::NAME_VACATION);

        // RESET AVAILABLE CREDITS
        $la = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveIdYear($e->getId(), $l->getId(), '2015');
        if ($la) {
            $la->delete();
        }
        $e->addLeaveCredit($l, 2);
        $e->addLeaveCredit($l, 1.5);

        // CHECK
        $check = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveIdYear($e->getId(), $l->getId(), '2015');
        $this->assertEqual($check->getNoOfDaysAlloted(), 3.5);
        $this->assertEqual($check->getNoOfDaysAvailable(), 3.5);
    }

    function test_leave_process_with_different_leave_dates() {
        $e = G_Employee_Finder::findById(117);
        $employee_id = $e->getId();
        $start_date = '2014-03-04';
        $end_date = '2014-03-06';
        $year = '2014';
        $l = G_Leave_Finder::findByName(G_Leave::NAME_VACATION);
        $leave_id = $l->getId();

        // DELETE AVAILABLE CREDITS
        $la = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveIdYear($employee_id, $leave_id, $year);
        if ($la) {
            $la->delete();
        }

        // DELETE LEAVE REQUEST
        $lrd = G_Employee_Leave_Request_Finder::findByEmployeeIdAndStartDateAndEndDate($employee_id, $start_date, $end_date);
        if ($lrd) {
            $lrd->delete();
        }

        // ADD LEAVE CREDITS
        $e->addDefaultLeaveCredits($year);

        // REQUEST VACATION
        $e->requestLeave($l, '2014-03-02', $start_date, $end_date, "Go on vacation");

        // CHECK
        $check = G_Employee_Leave_Request_Finder::findByEmployeeIdAndStartDateAndEndDate($employee_id, $start_date, $end_date);
        $this->assertEqual($check->getIsApproved(), G_Employee_Leave_Request::PENDING);

        // APPROVE LEAVE REQUEST
        $lr = $e->getLeaveRequest($start_date);
        $lr->approve();

        // PART 2

        // DELETE LEAVE REQUEST
        $lrd = G_Employee_Leave_Request_Finder::findByEmployeeIdAndStartDateAndEndDate($employee_id, '2014-03-07', '2014-03-07');
        if ($lrd) {
            $lrd->delete();
        }

        // REQUEST VACATION AGAIN
        $e->requestLeave($l, '2014-03-07', '2014-03-07', '2014-03-07', "Go on vacation part 2");

        // APPROVE LEAVE REQUEST
        $lr = $e->getLeaveRequest('2014-03-07');
        $lr->approve();

        // PART 3

        // DELETE LEAVE REQUEST
        $lrd = G_Employee_Leave_Request_Finder::findByEmployeeIdAndStartDateAndEndDate($employee_id, '2014-04-05', '2014-04-05');
        if ($lrd) {
            $lrd->delete();
        }

        // REQUEST VACATION AGAIN BUT HALF DAY
        $e->requestLeave($l, '2014-04-05', '2014-04-05', '2014-04-05', "Go on vacation part 2", G_Employee_Leave_Request::YES);

        // APPROVE LEAVE REQUEST
        $lr = $e->getLeaveRequest('2014-04-05');
        $lr->approve();

        // CHECK
        $check = G_Employee_Leave_Request_Finder::findByEmployeeIdAndStartDateAndEndDate($employee_id, '2014-03-07', '2014-03-07');
        $this->assertEqual($check->getIsApproved(), G_Employee_Leave_Request::APPROVED);

        $check2 = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveIdYear($employee_id, $leave_id, $year);
        $this->assertEqual($check2->getNoOfDaysAvailable(), 0.5);
        $this->assertEqual($check2->getNoOfDaysUsed(), 4.5);
        $this->assertEqual($check2->getNoOfDaysAlloted(), 5);
    }
    function test_leave_process_other_year() {
        $e = G_Employee_Finder::findById(1275);
        $employee_id = $e->getId();
        $date = '2013-02-05';
        $year = '2013';
        $l = G_Leave_Finder::findByName(G_Leave::NAME_SICK);
        $leave_id = $l->getId();

        // DELETE AVAILABLE CREDITS
        $la = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveIdYear($employee_id, $leave_id, $year);
        if ($la) {
            $la->delete();
        }

        // DELETE LEAVE REQUEST
        $lrd = G_Employee_Leave_Request_Finder::findByEmployeeIdAndStartDateAndEndDate($employee_id, $date, $date);
        if ($lrd) {
            $lrd->delete();
        }

        // ADD LEAVE CREDITS
        $e->addDefaultLeaveCredits($year);

        // REQUEST SICK LEAVE BUT HALF DAY
        $e->requestLeave($l, '2013-02-03', $date, $date, "Got fever", G_Employee_Leave_Request::YES);

        // CHECK
        $check = G_Employee_Leave_Request_Finder::findByEmployeeIdAndStartDateAndEndDate($employee_id, $date, $date);
        $this->assertEqual($check->getIsApproved(), G_Employee_Leave_Request::PENDING);

        // APPROVE LEAVE REQUEST
        $lr = $e->getLeaveRequest($date);
        $lr->approve();

        // CHECK
        $check = G_Employee_Leave_Request_Finder::findByEmployeeIdAndStartDateAndEndDate($employee_id, $date, $date);
        $this->assertEqual($check->getIsApproved(), G_Employee_Leave_Request::APPROVED);

        $check2 = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveIdYear($employee_id, $leave_id, $year);
        $this->assertEqual($check2->getNoOfDaysAvailable(), 4.5);
        $this->assertEqual($check2->getNoOfDaysUsed(), 0.5);
        $this->assertEqual($check2->getNoOfDaysAlloted(), 5);
    }
    function test_leave_process_half_day() {
        $e = G_Employee_Finder::findById(1275);
        $employee_id = $e->getId();
        $date = '2014-02-05';
        $year = '2014';
        $l = G_Leave_Finder::findByName(G_Leave::NAME_SICK);
        $leave_id = $l->getId();

        // DELETE AVAILABLE CREDITS
        $la = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveIdYear($employee_id, $leave_id, $year);
        if ($la) {
            $la->delete();
        }

        // DELETE LEAVE REQUEST
        $lrd = G_Employee_Leave_Request_Finder::findByEmployeeIdAndStartDateAndEndDate($employee_id, $date, $date);
        if ($lrd) {
            $lrd->delete();
        }

        // ADD LEAVE CREDITS
        $e->addDefaultLeaveCredits($year);

        // REQUEST SICK LEAVE BUT HALF DAY
        $e->requestLeave($l, '2014-02-03', $date, $date, "Got fever", G_Employee_Leave_Request::YES);

        // CHECK
        $check = G_Employee_Leave_Request_Finder::findByEmployeeIdAndStartDateAndEndDate($employee_id, $date, $date);
        $this->assertEqual($check->getIsApproved(), G_Employee_Leave_Request::PENDING);

        // APPROVE LEAVE REQUEST
        $lr = $e->getLeaveRequest($date);
        $lr->approve();

        // CHECK
        $check = G_Employee_Leave_Request_Finder::findByEmployeeIdAndStartDateAndEndDate($employee_id, $date, $date);
        $this->assertEqual($check->getIsApproved(), G_Employee_Leave_Request::APPROVED);

        $check2 = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveIdYear($employee_id, $leave_id, $year);
        $this->assertEqual($check2->getNoOfDaysAvailable(), 4.5);
        $this->assertEqual($check2->getNoOfDaysUsed(), 0.5);
        $this->assertEqual($check2->getNoOfDaysAlloted(), 5);
    }

    function test_leave_process() {
        $e = G_Employee_Finder::findById(1275);
        $employee_id = $e->getId();
        $date = '2014-02-01';
        $l = G_Leave_Finder::findByName(G_Leave::NAME_VACATION);
        $leave_id = $l->getId();

        // DELETE AVAILABLE CREDITS
        $la = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveIdYear($employee_id, $leave_id, '2014');
        $la->delete();

        // DELETE LEAVE REQUEST
        $lrd = G_Employee_Leave_Request_Finder::findByEmployeeIdAndStartDateAndEndDate($employee_id, $date, $date);
        if ($lrd) {
            $lrd->delete();
        }

        // ADD LEAVE CREDITS
        $e->addDefaultLeaveCredits('2014');

        // REQUEST VACATION LEAVE
        $e->requestLeave($l, '2014-01-28', $date, $date, "Harney's wedding");

        // CHECK
        $check = G_Employee_Leave_Request_Finder::findByEmployeeIdAndStartDateAndEndDate($employee_id, $date, $date);
        $this->assertEqual($check->getIsApproved(), G_Employee_Leave_Request::PENDING);

        // APPROVE LEAVE REQUEST
        $lr = $e->getLeaveRequest($date);
        $lr->approve();

        // CHECK
        $check = G_Employee_Leave_Request_Finder::findByEmployeeIdAndStartDateAndEndDate($employee_id, $date, $date);
        $this->assertEqual($check->getIsApproved(), G_Employee_Leave_Request::APPROVED);
        $check2 = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveIdYear($employee_id, $leave_id, '2014');
        $this->assertEqual($check2->getNoOfDaysAvailable(), 4);
        $this->assertEqual($check2->getNoOfDaysUsed(), 1);
        $this->assertEqual($check2->getNoOfDaysAlloted(), 5);
    }

    function test_add_leave_credits() {
        $e = G_Employee_Finder::findById(1945);
        $e->addDefaultLeaveCredits('2014');
        $l = G_Leave_Finder::findByName(G_Leave::NAME_VACATION);
        $el = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveIdYear($e->getId(), $l->getId(), '2014');
        $this->assertEqual($el->getCoveredYear(), '2014');
        $this->assertEqual($el->getEmployeeId(), $e->getId());
    }

    function test_update_leave_request() {
        $l = G_Leave_Finder::findByName(G_Leave::NAME_VACATION);
        $emp = G_Employee_Finder::findByEmployeeCode(2416);
        $employee_id = $emp->getId();
        $r = G_Employee_Leave_Request_Finder::findByEmployeeIdAndStartDateAndEndDate($employee_id, '2014-01-29', '2014-01-30');
        if (!$r) {
            $r = new G_Employee_Leave_Request;
        }
		$r->setEmployeeId($emp->getId());
        $r->setLeaveId($l->getId());
		$r->setDateApplied('2014-01-28');
        $r->setTimeApplied('09:30:00');
		$r->setDateStart('2014-01-29');
		$r->setDateEnd('2014-01-30');
        $r->setLeaveComments('test comment');
        $r->save();

        $r = G_Employee_Leave_Request_Finder::findByEmployeeIdAndStartDateAndEndDate($employee_id, '2014-01-29', '2014-01-30');
        $this->assertEqual($r->getTimeApplied(), '09:30:00');

        // UPDATE
        $r = G_Employee_Leave_Request_Finder::findByEmployeeIdAndStartDateAndEndDate($employee_id, '2014-01-29', '2014-01-30');
        $r->setTimeApplied('10:30:00');
        $r->save();

        $r = G_Employee_Leave_Request_Finder::findByEmployeeIdAndStartDateAndEndDate($employee_id, '2014-01-29', '2014-01-30');
        $this->assertEqual($r->getTimeApplied(), '10:30:00');
    }

    function test_add_and_delete_leave_request() {
        $l = G_Leave_Finder::findByName(G_Leave::NAME_VACATION);
        $emp = G_Employee_Finder::findByEmployeeCode(2416);
        $employee_id = $emp->getId();
        $leave_request = G_Employee_Leave_Request_Finder::findByEmployeeIdAndStartDateAndEndDate($employee_id, '2014-01-28', '2014-01-29');
        if ($leave_request) {
            $leave_request->delete();
        }

		$e = new G_Employee_Leave_Request;
		$e->setEmployeeId($emp->getId());
        $e->setLeaveId($l->getId());
		$e->setDateApplied('2014-01-27');
        $e->setTimeApplied('09:30:00');
		$e->setDateStart('2014-01-28');
		$e->setDateEnd('2014-01-29');
        $e->setLeaveComments('test comment');
        $e->save();

        $leave_request = G_Employee_Leave_Request_Finder::findByEmployeeIdAndStartDateAndEndDate($employee_id, '2014-01-28', '2014-01-29');
        if ($leave_request) {
            $this->assertEqual($leave_request->getLeaveId(), 2);
            $this->assertEqual($leave_request->getEmployeeId(), 1945);
            $this->assertEqual($leave_request->getDateApplied(), '2014-01-27');
        } else {
            $this->assertEqual('Not inserted in the database', '.');
        }

    }
}

class TestAttendance_Overtime_Error_Reporting extends UnitTestCase {
    function test_import_ot_with_error_and_import_ot_to_fix() {
        $date = '2014-01-09';

        $employee_codes = array(3232, 101, 257, 3871);

        // UPLOAD ACTUAL TIME
        $file = BASE_PATH . "attendance/dtr_instance16.xlsx";
        G_Attendance_Helper::importTimesheet($file);

        // UPLOAD OT WITH ERROR
        $file2 = BASE_PATH . "attendance/ot_instance16.xlsx";
        $time = new G_Overtime_Import($file2);
        $time->import();

        //$e = G_Employee_Finder::findByEmployeeCode(3232);
        //$a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        //$o = G_Overtime_Finder::findByEmployeeAndDate($e, $date);

        //echo '<pre>';
        //print_r($a);
        //print_r($o);
        //exit;

        // CHECK. THERE SHOULD BE AN OVERTIME ERROR
        $emp_with_error = array(3232, 3871);
        $emp_no_error = array(101, 257);
        foreach ($employee_codes as $code) {
            $e = G_Employee_Finder::findByEmployeeCode($code);
            $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
            G_Overtime_Error_Helper::updateOvertimeError($a, $e);
            $err = G_Overtime_Error_Finder::findByEmployeeIdAndDate($e->getId(), $date);

            if ($code == 3232) {
                $this->assertEqual($err->isFixed(), G_Overtime_Error::ERROR_FIXED_NO);
            }
            if ($code == 3871) {
                $this->assertEqual($err->isFixed(), G_Overtime_Error::ERROR_FIXED_NO);
            }
        }
        $e = G_Employee_Finder::findByEmployeeCode(3871);
        $err = G_Overtime_Error_Finder::findByEmployeeIdAndDate($e->getId(), $date);
        //echo '<pre>';
        //print_r($err);
        //exit;

        // UPLOAD OT TO FIX ERROR
        $file2 = BASE_PATH . "attendance/ot_instance16-a.xlsx";
        $time = new G_Overtime_Import($file2);
        $time->import();

        // CHECK. OT SHOULD BE FIXED
        foreach ($employee_codes as $code) {
            $e = G_Employee_Finder::findByEmployeeCode($code);
            $err = G_Overtime_Error_Finder::findByEmployeeIdAndDate($e->getId(), $date);
            if ($code == 3232) {
                $this->assertEqual($err->isFixed(), G_Overtime_Error::ERROR_FIXED_YES);
            }
            if ($code == 3871) {
                $this->assertEqual($err->isFixed(), G_Overtime_Error::ERROR_FIXED_YES);
            }
        }
    }

    function test_import_ot_with_error_and_import_dtr_to_fix() {
        $date = '2014-01-08';

        // CLEAR FIRST ACTUAL TIME OF THESE EMPLOYEES
        $employee_codes = array(101, 3232, 257, 3871);
        foreach ($employee_codes as $code) {
            $e = G_Employee_Finder::findByEmployeeCode($code);
            if ($e) {
               G_Attendance_Helper::clearActualTimeAndDateIn($e, $date);
            }
        }

        // UPLOAD OT WITHOUT ACTUAL. THERE MUST BE AN ERROR
        $file2 = BASE_PATH . "attendance/ot_instance15.xlsx";
        $time = new G_Overtime_Import($file2);
        $time->import();

        // CHECK. THERE SHOULD BE AN OVERTIME ERROR
        foreach ($employee_codes as $code) {
            $e = G_Employee_Finder::findByEmployeeCode($code);
            if ($e) {
                $err = G_Overtime_Error_Finder::findByEmployeeIdAndDate($e->getId(), $date);
                if (!in_array($err->getEmployeeCode(), $employee_codes)) {
                    $this->assertEqual('employee not found', '');
                }
            }
        }

        // UPLOAD ACTUAL TIME TO FIX THE OVERTIME ERROR
        $file = BASE_PATH . "attendance/dtr_instance15.xlsx";
        G_Attendance_Helper::importTimesheet($file);

        foreach ($employee_codes as $code) {
            $e = G_Employee_Finder::findByEmployeeCode($code);
            $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
            G_Overtime_Error_Helper::updateOvertimeError($a, $e);
        }

        // CHECK. ACTUAL TIME MUST BE FIXED. BUT 1 EMPLOYEE STILL HAS ERROR
        foreach ($employee_codes as $code) {
            $e = G_Employee_Finder::findByEmployeeCode($code);
            $err = G_Overtime_Error_Finder::findByEmployeeIdAndDate($e->getId(), $date);
            if ($code == 101) {
                $this->assertEqual($err->isFixed(), G_Overtime_Error::ERROR_FIXED_YES);
            }
            if ($code == 3232) {
                $this->assertEqual($err->isFixed(), G_Overtime_Error::ERROR_FIXED_NO);
            }
            if ($code == 257) {
                $this->assertEqual($err->isFixed(), G_Overtime_Error::ERROR_FIXED_YES);
            }
            if ($code == 3871) {
                $this->assertEqual($err->isFixed(), G_Overtime_Error::ERROR_FIXED_YES);
            }
        }
    }
    function test_overtime_with_error_and_fix() {
        $date = '2014-01-07';

        $file = BASE_PATH . "attendance/dtr_instance14.xlsx";
        G_Attendance_Helper::importTimesheet($file);

        $file2 = BASE_PATH . "attendance/ot_instance14.xlsx";
        $time = new G_Overtime_Import($file2);
        $time->import();

        $e = G_Employee_Finder::findByEmployeeCode(257); // Awat, Henry

        $er = new G_Overtime_Error_Checker();
        $er->checkByEmployeeAndDate($e, $date);
        $errs = $er->getErrors();
        $err = $errs[0];
        $this->assertEqual($err->getDate(), '2014-01-07');
        $this->assertEqual($err->getErrorTypeId(), G_Overtime_Error::OT_OUT_GREATER_THAN_ACTUAL_OUT);
        $this->assertEqual($err->getTimeIn(), '17:30:00');
        $this->assertEqual($err->getTimeOut(), '21:00:00');

        $err = G_Overtime_Error_Finder::findByEmployeeIdAndDate($e->getId(), $date);
        $this->assertEqual($err->getDate(), '2014-01-07');
        $this->assertEqual($err->getErrorTypeId(), G_Overtime_Error::OT_OUT_GREATER_THAN_ACTUAL_OUT);
        $this->assertEqual($err->getTimeIn(), '17:30:00');
        $this->assertEqual($err->getTimeOut(), '21:00:00');

        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $this->assertEqual($a->getTimesheet()->getTimeIn(), '08:00:00');
        $this->assertEqual($a->getTimesheet()->getTimeOut(), '17:00:00');


        // FIX THE OVERTIME ERROR
        G_Attendance_Helper::recordTimeInOut($e, $date, '08:00:00', '21:00:00');
        G_Attendance_Helper::updateAttendance($e, $date);

        $er = new G_Overtime_Error_Checker();
        $err = $er->checkByEmployeeAndDate($e, $date);
        $this->assertEqual($err, false);

        $err = G_Overtime_Error_Finder::findByEmployeeIdAndDate($e->getId(), $date);
        if ($err) {
            $err->setAsFixed();
            $err->save();
        }

        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $this->assertEqual($a->getTimesheet()->getTimeIn(), '08:00:00');
        $this->assertEqual($a->getTimesheet()->getTimeOut(), '21:00:00');
    }

    function test_overtime_with_2_errors() {
        $date = '2014-01-06';

        $file = BASE_PATH . "attendance/dtr_instance13.xlsx";
        G_Attendance_Helper::importTimesheet($file);

        $file2 = BASE_PATH . "attendance/ot_instance13.xlsx";
        $time = new G_Overtime_Import($file2);
        $time->import();

        $e = G_Employee_Finder::findByEmployeeCode(257); // Awat, Henry
        $e2 = G_Employee_Finder::findByEmployeeCode(3232); // Jonathan

        $err = G_Overtime_Error_Finder::findByEmployeeIdAndDate($e->getId(), $date);
        $this->assertEqual($err->getDate(), '2014-01-06');
        $this->assertEqual($err->getErrorTypeId(), G_Overtime_Error::OT_OUT_GREATER_THAN_ACTUAL_OUT);
        $this->assertEqual($err->getTimeIn(), '17:30:00');
        $this->assertEqual($err->getTimeOut(), '21:00:00');

        $err = G_Overtime_Error_Finder::findByEmployeeIdAndDate($e2->getId(), $date);
        $this->assertEqual($err->getDate(), '2014-01-06');
        $this->assertEqual($err->getErrorTypeId(), G_Overtime_Error::OT_OUT_GREATER_THAN_ACTUAL_OUT);
        $this->assertEqual($err->getTimeIn(), '17:30:00');
        $this->assertEqual($err->getTimeOut(), '17:45:00');

        $er = new G_Overtime_Error_Checker();
        $er->checkByEmployeeAndDate($e, $date);
        $errors = $er->getErrors();
        $err = $errors[0];
        $this->assertEqual($err->getDate(), '2014-01-06');
        $this->assertEqual($err->getErrorTypeId(), G_Overtime_Error::OT_OUT_GREATER_THAN_ACTUAL_OUT);
        $this->assertEqual($err->getTimeIn(), '17:30:00');
        $this->assertEqual($err->getTimeOut(), '21:00:00');

    }

    function test_invalid_overtime_out() {
        $actual_in = '2014-01-20 08:00:00';
        $actual_out = '2014-01-21 20:00:00';
        $overtime_in = '2014-01-20 17:00:00';
        $overtime_out = '2014-01-21 22:00:00'; // error because overtime out is greater than actual time out

        $oe = new G_Overtime_Error_Checker;
        $oe->setDate('2014-01-20');
        $oe->setActualDateTime($actual_in, $actual_out);
        $oe->setOvertimeDateTime($overtime_in, $overtime_out);
        $oe->check();
        $es = $oe->getErrors();

        // CHECK ERRORS
        $errors = array(G_Overtime_Error::OT_OUT_GREATER_THAN_ACTUAL_OUT);
        if ($es) {
            foreach ($es as $err) {
                if (!in_array($err->getErrorTypeId(), $errors)) {
                    $this->assertEqual('Wrong', 'Yes');
                }
            }
        } else {
            $this->assertEqual(count($es), count($errors));
        }
    }
    function test_invalid_overtime_out2() {
        $overtime_in = '2014-01-21 01:00:00';
        $overtime_out = '2014-01-21 06:30:00'; // error because overtime out is greater than actual time out
        $actual_in = '2014-01-21 20:00:00';
        $actual_out = '2014-01-20 06:00:00';

        $oe = new G_Overtime_Error_Checker;
        $oe->setDate('2014-01-20');
        $oe->setActualDateTime($actual_in, $actual_out);
        $oe->setOvertimeDateTime($overtime_in, $overtime_out);
        $oe->check();
        $es = $oe->getErrors();

        // CHECK ERRORS
        $errors = array(G_Overtime_Error::OT_OUT_GREATER_THAN_ACTUAL_OUT);
        if ($es) {
            foreach ($es as $err) {
                if (!in_array($err->getErrorTypeId(), $errors)) {
                    $this->assertEqual('Wrong', 'Yes');
                }
            }
        } else {
            $this->assertEqual(count($es), count($errors));
        }
    }
}
class TestAttendance_Overtime_Import extends UnitTestCase {
    function testOvertime_Rest_Day_Night_Shift_NoSchedule() {
        $file = BASE_PATH . "attendance/dtr_instance9.xlsx";
        G_Attendance_Helper::importTimesheet($file);

        $file2 = BASE_PATH . "attendance/ot_instance9.xlsx";
        $time = new G_Overtime_Import($file2);
        $time->import();

        $e = G_Employee_Finder::findByEmployeeCode(8);

        G_Attendance_Helper::updateAttendance($e, '2013-07-06');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, '2013-07-06'); // Saturday (Rest Day)
        $t = $a->getTimesheet();

        $o = G_Overtime_Finder::findByEmployeeAndDate($e, '2013-07-06');

        $this->assertIdentical($t->getOvertimeIn(), $o->getTimeIn());
        $this->assertIdentical($t->getOvertimeOut(), $o->getTimeOut());

        $nd_hours = $t->getNightShiftHours();
        $this->assertEqual($nd_hours, 8);

        $ot_hours = $t->getRestDayOvertimeHours();
        $this->assertEqual($ot_hours, 8);
        $ot_hours = $t->getRegularOvertimeHours();
        $this->assertEqual($ot_hours, 0);

        $nd_ot_hours = $t->getRestDayOvertimeNightShiftHours();
        $this->assertEqual($nd_ot_hours, 6);
        $nd_ot_hours = $t->getRegularOvertimeNightShiftHours();
        $this->assertEqual($nd_ot_hours, 0);

        $ot_excess_hours = $t->getRestDayOvertimeExcessHours();
        $this->assertEqual($ot_excess_hours, 5);
        $ot_excess_hours = $t->getRegularOvertimeExcessHours();
        $this->assertEqual($ot_excess_hours, 0);

        $nd_ot_excess_hours = $t->getRestDayOvertimeNightShiftExcessHours();
        $this->assertEqual($nd_ot_excess_hours, 2);
        $nd_ot_excess_hours = $t->getRegularOvertimeNightShiftExcessHours();
        $this->assertEqual($nd_ot_excess_hours, 0);
    }

    function testOvertime_Auto_Overtime() {
        $e = G_Employee_Finder::findByEmployeeCode(428);

        $date = '2014-09-04';

        $schedule_time_in = '08:00:00';
        $schedule_time_out = '21:00:00';

        $actual_time_in = '08:00:00';
        $actual_time_out = '21:00:00';

        // Add Specific Schedule
        $s = G_Schedule_Specific_Finder::findByEmployeeAndDate($e, '2014-09-04');
        if (!$s) { $s = new G_Schedule_Specific; }
        $s->setDateStart('2014-09-04');
        $s->setDateEnd('2014-09-04');
        $s->setTimeIn($schedule_time_in);
        $s->setTimeOut($schedule_time_out);
        $s->setEmployeeId($e->getId());
        $s->save();

        $e->goToWork($date, $actual_time_in, $actual_time_out);

        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();

        $ot_hours = $t->getRegularOvertimeHours();
        $this->assertEqual($ot_hours, 4);

    }

    function testOvertime_Auto_Overtime_With_Manual_Overtime() {
        $e = G_Employee_Finder::findByEmployeeCode(428);

        $date = '2014-08-29';

        $schedule_time_in = '08:00:00';
        $schedule_time_out = '21:00:00';

        $actual_time_in = '08:00:00';
        $actual_time_out = '21:00:00';

        $overtime_in = '21:00:00';
        $overtime_out = '23:00:00';

        // Add Specific Schedule
        $s = G_Schedule_Specific_Finder::findByEmployeeAndDate($e, '2014-08-29');
        if (!$s) { $s = new G_Schedule_Specific; }
        $s->setDateStart('2014-08-29');
        $s->setDateEnd('2014-08-29');
        $s->setTimeIn($schedule_time_in);
        $s->setTimeOut($schedule_time_out);
        $s->setEmployeeId($e->getId());
        $s->save();

        // ADD OVERTIME
        $o = G_Overtime_Finder::findByEmployeeAndDate($e, $date);
        if (!$o) {
            $o = new G_Overtime;
        }
        $o->setDate($date);
        $o->setTimeIn($overtime_in);
        $o->setTimeOut($overtime_out);
        $o->setEmployeeId($e->getId());
        $o->save();

        $e->goToWork($date, $actual_time_in, $actual_time_out);

        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        
        $ot_hours = $t->getRegularOvertimeHours();
        $this->assertEqual($ot_hours, 6);

    }

    function testOvertime_Regular_Day_Night_Shift_WithRestDaySchedule() {
        $date = '2013-07-16'; // Tuesday with work schedule and rest day schedule

        $file = BASE_PATH . "attendance/dtr_instance11.xlsx";
        G_Attendance_Helper::importTimesheet($file);

        $file2 = BASE_PATH . "attendance/ot_instance11.xlsx";
        $time = new G_Overtime_Import($file2);
        $time->import();

        $file3 = BASE_PATH . "attendance/rd_instance11.xlsx";
        $rd = new G_Restday_Import($file3);
        $rd->import();

        $sched = new G_Schedule_Specific_Import($file3);
        $sched->import();

        $e = G_Employee_Finder::findByEmployeeCode(8);

        G_Attendance_Helper::updateAttendance($e, $date);
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();

        $o = G_Overtime_Finder::findByEmployeeAndDate($e, $date);

        $this->assertIdentical($t->getOvertimeIn(), $o->getTimeIn());
        $this->assertIdentical($t->getOvertimeOut(), $o->getTimeOut());

        $r = G_Restday_Finder::findByEmployeeAndDate($e, $date);
        $this->assertIdentical($r->getDate(), '2013-07-16');

        $nd_hours = $t->getNightShiftHours();
        $this->assertEqual($nd_hours, 0);

        $ot_hours = $t->getRestDayOvertimeHours();
        $this->assertEqual($ot_hours, 8);
        $ot_hours = $t->getRegularOvertimeHours();
        $this->assertEqual($ot_hours, 0);

        $nd_ot_hours = $t->getRestDayOvertimeNightShiftHours();
        $this->assertEqual($nd_ot_hours, 7);
        $nd_ot_hours = $t->getRegularOvertimeNightShiftHours();
        $this->assertEqual($nd_ot_hours, 0);

        $ot_excess_hours = $t->getRestDayOvertimeExcessHours();
        $this->assertEqual($ot_excess_hours, 5);
        $ot_excess_hours = $t->getRegularOvertimeExcessHours();
        $this->assertEqual($ot_excess_hours, 0);

        $nd_ot_excess_hours = $t->getRestDayOvertimeNightShiftExcessHours();
        $this->assertEqual($nd_ot_excess_hours, 1);
        $nd_ot_excess_hours = $t->getRegularOvertimeNightShiftExcessHours();
        $this->assertEqual($nd_ot_excess_hours, 0);
    }

    function testOvertime_Rest_Day_Night_Shift_WithRestDaySchedule() {
        $file = BASE_PATH . "attendance/dtr_instance9.xlsx";
        G_Attendance_Helper::importTimesheet($file);

        $file2 = BASE_PATH . "attendance/ot_instance9.xlsx";
        $time = new G_Overtime_Import($file2);
        $time->import();

        $file3 = BASE_PATH . "attendance/rd_instance9.xlsx";
        $rd = new G_Restday_Import($file3);
        $rd->import();

        $sched = new G_Schedule_Specific_Import($file3);
        $sched->import();

        $e = G_Employee_Finder::findByEmployeeCode(8);

        $a = G_Attendance_Finder::findByEmployeeAndDate($e, '2013-07-13'); // Saturday (Rest Day with Rest Day Schedule and No Work Schedule)
        $t = $a->getTimesheet();

        $r = G_Restday_Finder::findByEmployeeAndDate($e, '2013-07-13');

        $o = G_Overtime_Finder::findByEmployeeAndDate($e, '2013-07-13');

        $this->assertIdentical($t->getOvertimeIn(), $o->getTimeIn());
        $this->assertIdentical($t->getOvertimeOut(), $o->getTimeOut());

        $nd_hours = $t->getNightShiftHours();
        $this->assertEqual($nd_hours, 0);

        $ot_hours = $t->getRestDayOvertimeHours();
        $this->assertEqual($ot_hours, 8);
        $ot_hours = $t->getRegularOvertimeHours();
        $this->assertEqual($ot_hours, 0);

        $nd_ot_hours = $t->getRestDayOvertimeNightShiftHours();
        $this->assertEqual($nd_ot_hours, 7);
        $nd_ot_hours = $t->getRegularOvertimeNightShiftHours();
        $this->assertEqual($nd_ot_hours, 0);

        $ot_excess_hours = $t->getRestDayOvertimeExcessHours();
        $this->assertEqual($ot_excess_hours, 4);
        $ot_excess_hours = $t->getRegularOvertimeExcessHours();
        $this->assertEqual($ot_excess_hours, 0);

        $nd_ot_excess_hours = $t->getRestDayOvertimeNightShiftExcessHours();
        $this->assertEqual($nd_ot_excess_hours, 1);
        $nd_ot_excess_hours = $t->getRegularOvertimeNightShiftExcessHours();
        $this->assertEqual($nd_ot_excess_hours, 0);
    }

    function testOvertime_Import() {
        $file = BASE_PATH . "attendance/ot_instance12.xlsx";
        $g = new G_Overtime_Import($file);
        $g->import();
        $e = G_Employee_Finder::findByEmployeeCode(15);
        $e2 = G_Employee_Finder::findByEmployeeCode(16);

        $dates = array('2013-07-09', '2013-07-10','2013-07-11','2013-07-12','2013-07-13',);
        foreach ($dates as $date) {
            $o = G_Overtime_Finder::findByEmployeeAndDate($e, $date);
            $answer = false;
            if ($o) { $answer = true;}
            $this->assertIdentical($answer, true);
        }

        $dates = array('2013-07-09', '2013-07-10','2013-07-11','2013-07-12','2013-07-13',);
        foreach ($dates as $date) {
            $o = G_Overtime_Finder::findByEmployeeAndDate($e2, $date);
            $answer = false;
            if ($o) { $answer = true;}
            $this->assertIdentical($answer, true);
        }
    }

    function testOvertime_Regular_Night_Shift_Half_Day() {
        $file = BASE_PATH . "attendance/dtr_instance10.xlsx";
        G_Attendance_Helper::importTimesheet($file);

        $file2 = BASE_PATH . "attendance/ot_instance10.xlsx";
        $time = new G_Overtime_Import($file2);
        $time->import();

        $e = G_Employee_Finder::findByEmployeeCode(8);

        $a = G_Attendance_Finder::findByEmployeeAndDate($e, '2013-07-03'); // Wednesday (Half Day)
        $t = $a->getTimesheet();

        $o = G_Overtime_Finder::findByEmployeeAndDate($e, '2013-07-03');

        $this->assertIdentical($t->getOvertimeIn(), $o->getTimeIn());
        $this->assertIdentical($t->getOvertimeOut(), $o->getTimeOut());

        $nd_hours = $t->getNightShiftHours();
        $this->assertEqual($nd_hours, 2);

        $ot_hours = $t->getRegularOvertimeHours();
        $this->assertEqual($ot_hours, 5);
        $ot_hours = $t->getRestDayOvertimeHours();
        $this->assertEqual($ot_hours, 0);

        $nd_ot_hours = $t->getRegularOvertimeNightShiftHours();
        $this->assertEqual($nd_ot_hours, 5);
        $nd_ot_hours = $t->getRestDayOvertimeNightShiftHours();
        $this->assertEqual($nd_ot_hours, 0);

        $ot_excess_hours = $t->getRegularOvertimeExcessHours();
        $this->assertEqual($ot_excess_hours, 0);
        $ot_excess_hours = $t->getRestDayOvertimeExcessHours();
        $this->assertEqual($ot_excess_hours, 0);

        $nd_ot_excess_hours = $t->getRegularOvertimeNightShiftExcessHours();
        $this->assertEqual($nd_ot_excess_hours, 0);
        $nd_ot_excess_hours = $t->getRestDayOvertimeNightShiftExcessHours();
        $this->assertEqual($nd_ot_excess_hours, 0);
    }

    function testOvertime_Regular_Night_Shift() {
        $file = BASE_PATH . "attendance/dtr_instance8.xlsx";
        G_Attendance_Helper::importTimesheet($file);

        $file2 = BASE_PATH . "attendance/ot_instance8.xlsx";
        $time = new G_Overtime_Import($file2);
        $time->import();

        $e = G_Employee_Finder::findByEmployeeCode(8);

        $a = G_Attendance_Finder::findByEmployeeAndDate($e, '2013-07-01'); // Monday
        $t = $a->getTimesheet();

        $o = G_Overtime_Finder::findByEmployeeAndDate($e, '2013-07-01');

        $this->assertIdentical($t->getOvertimeIn(), $o->getTimeIn());
        $this->assertIdentical($t->getOvertimeOut(), $o->getTimeOut());

        $nd_hours = $t->getNightShiftHours();
        $this->assertEqual($nd_hours, 7);

        $ot_hours = $t->getRegularOvertimeHours();
        $this->assertEqual($ot_hours, 4);
        $ot_hours = $t->getRestDayOvertimeHours();
        $this->assertEqual($ot_hours, 0);

        $nd_ot_hours = $t->getRegularOvertimeNightShiftHours();
        $this->assertEqual($nd_ot_hours, 1);
        $nd_ot_hours = $t->getRestDayOvertimeNightShiftHours();
        $this->assertEqual($nd_ot_hours, 0);

        $ot_excess_hours = $t->getRegularOvertimeExcessHours();
        $this->assertEqual($ot_excess_hours, 0);
        $ot_excess_hours = $t->getRestDayOvertimeExcessHours();
        $this->assertEqual($ot_excess_hours, 0);

        $nd_ot_excess_hours = $t->getRegularOvertimeNightShiftExcessHours();
        $this->assertEqual($nd_ot_excess_hours, 0);
        $nd_ot_excess_hours = $t->getRestDayOvertimeNightShiftExcessHours();
        $this->assertEqual($nd_ot_excess_hours, 0);
    }
    function testOvertime_Regular_Day_Shift() {
        $file = BASE_PATH . "attendance/dtr_instance7.xlsx";
        G_Attendance_Helper::importTimesheet($file);

        $file2 = BASE_PATH . "attendance/ot_instance7.xlsx";
        $time = new G_Overtime_Import($file2);
        $time->import();

        $e = G_Employee_Finder::findByEmployeeCode(11);

        $a = G_Attendance_Finder::findByEmployeeAndDate($e, '2013-05-01'); // Wednesday
        $t = $a->getTimesheet();

        $o = G_Overtime_Finder::findByEmployeeAndDate($e, '2013-05-01');

        $this->assertIdentical($t->getOvertimeIn(), $o->getTimeIn());
        $this->assertIdentical($t->getOvertimeOut(), $o->getTimeOut());

        $nd_hours = $t->getNightShiftHours();
        $this->assertEqual($nd_hours, 0);

        $ot_hours = $t->getRegularOvertimeHours();
        $this->assertEqual($ot_hours, 8);

        $nd_ot_hours = $t->getRegularOvertimeNightShiftHours();
        $this->assertEqual($nd_ot_hours, 3);

        $ot_excess_hours = $t->getRegularOvertimeExcessHours();
        $this->assertEqual($ot_excess_hours, 4);

        $nd_ot_excess_hours = $t->getRegularOvertimeNightShiftExcessHours();
        $this->assertEqual($nd_ot_excess_hours, 4);
    }
}

class TestAttendance_Overtime extends UnitTestCase {
    function test_overtime6() {
        $schedule_in = '2014-01-20 20:00:00';
        $schedule_out = '2014-01-21 00:00:00';
        $overtime_in = '2014-01-21 01:00:00';
        $overtime_out = '2014-01-21 06:00:00';
        $actual_in = '2014-01-20 20:00:00';
        $actual_out = '2014-01-21 06:00:00';

        $o = new G_Overtime_Calculator_New($overtime_in, $overtime_out);
        $o->setScheduleDateTime($schedule_in, $schedule_out);
        //$o->debugMode();

        $ns = new Nightshift_Calculator;
        $ns->setScheduledTimeIn($schedule_in);
        $ns->setScheduledTimeOut($schedule_out);
        $ns->setOvertimeIn($overtime_in);
        $ns->setOvertimeOut($overtime_out);
        $ns->setActualTimeIn($actual_in);
        $ns->setActualTimeOut($actual_out);
        $ns_hours = $ns->compute();
        $this->assertEqual($ns_hours, 2);

        $ot_hours = $o->computeHours();
        $this->assertEqual($ot_hours, 5);

        $ot_nd = $o->computeNightDiff();
        $this->assertEqual($ot_nd, 5);

        $ot_excess_hours = $o->computeExcessHours();
        $this->assertEqual($ot_excess_hours, 0);

        $ot_excess_nd = $o->computeExcessNightDiff();
        $this->assertEqual($ot_excess_nd, 0);
    }
    function test_overtime5() {
        $schedule_in = '2014-01-20 20:00:00';
        $schedule_out = '2014-01-21 05:00:00';
        $overtime_in = '2014-01-21 05:00:00';
        $overtime_out = '2014-01-21 09:00:00';
        $actual_in = '2014-01-20 20:00:00';
        $actual_out = '2014-01-21 09:00:00';

        $o = new G_Overtime_Calculator_New($overtime_in, $overtime_out);
        $o->setScheduleDateTime($schedule_in, $schedule_out);
        //$o->debugMode();

        $ns = new Nightshift_Calculator;
        $ns->setScheduledTimeIn($schedule_in);
        $ns->setScheduledTimeOut($schedule_out);
        $ns->setOvertimeIn($overtime_in);
        $ns->setOvertimeOut($overtime_out);
        $ns->setActualTimeIn($actual_in);
        $ns->setActualTimeOut($actual_out);
        $ns_hours = $ns->compute();
        $this->assertEqual($ns_hours, 7);

        $ot_hours = $o->computeHours();
        $this->assertEqual($ot_hours, 4);

        $ot_nd = $o->computeNightDiff();
        $this->assertEqual($ot_nd, 1);

        $ot_excess_hours = $o->computeExcessHours();
        $this->assertEqual($ot_excess_hours, 0);

        $ot_excess_nd = $o->computeExcessNightDiff();
        $this->assertEqual($ot_excess_nd, 0);

    }
    function test_overtime4() {
        $schedule_in = '2014-01-20 08:00:00';
        $schedule_out = '2014-01-20 17:00:00';
        $overtime_in = '2014-01-20 17:00:00';
        $overtime_out = '2014-01-21 05:00:00';
        $actual_in = '2014-01-20 08:00:00';
        $actual_out = '2014-01-21 05:00:00';

        $o = new G_Overtime_Calculator_New($overtime_in, $overtime_out);
        $o->setScheduleDateTime($schedule_in, $schedule_out);
        //$o->debugMode();

        $ns = new Nightshift_Calculator;
        $ns->setScheduledTimeIn($schedule_in);
        $ns->setScheduledTimeOut($schedule_out);
        $ns->setOvertimeIn($overtime_in);
        $ns->setOvertimeOut($overtime_out);
        $ns->setActualTimeIn($actual_in);
        $ns->setActualTimeOut($actual_out);
        $ns_hours = $ns->compute();
        $this->assertEqual($ns_hours, 0);

        $ot_hours = $o->computeHours();
        $this->assertEqual($ot_hours, 8);

        $ot_nd = $o->computeNightDiff();
        $this->assertEqual($ot_nd, 3);

        $ot_excess_hours = $o->computeExcessHours();
        $this->assertEqual($ot_excess_hours, 4);

        $ot_excess_nd = $o->computeExcessNightDiff();
        $this->assertEqual($ot_excess_nd, 4);
    }
    function test_overtime3() {
        $overtime_in = '2014-01-20 20:00:00';
        $overtime_out = '2014-01-21 09:00:00';
        $schedule_in = '2014-01-20 20:00:00';
        $schedule_out = '2014-01-21 05:00:00';
        $actual_in = '2014-01-20 20:00:00';
        $actual_out = '2014-01-21 09:00:00';

        $o = new G_Overtime_Calculator_New($overtime_in, $overtime_out);
        $o->setScheduleDateTime($schedule_in, $schedule_out);
        //$o->debugMode();

        $ns = new Nightshift_Calculator;
        $ns->setScheduledTimeIn($schedule_in);
        $ns->setScheduledTimeOut($schedule_out);
        $ns->setOvertimeIn($overtime_in);
        $ns->setOvertimeOut($overtime_out);
        $ns->setActualTimeIn($actual_in);
        $ns->setActualTimeOut($actual_out);
        $ns_hours = $ns->compute();
        $this->assertEqual($ns_hours, 0);

        $ot_hours = $o->computeHours();
        $this->assertEqual($ot_hours, 8);

        $ot_nd = $o->computeNightDiff();
        $this->assertEqual($ot_nd, 7);

        $ot_excess_hours = $o->computeExcessHours();
        $this->assertEqual($ot_excess_hours, 4);

        $ot_excess_nd = $o->computeExcessNightDiff();
        $this->assertEqual($ot_excess_nd, 1);
    }

    function test_overtime2() {
        $overtime_in = '2014-01-20 05:00:00';
        $overtime_out = '2014-01-20 09:00:00';

        $o = new G_Overtime_Calculator_New($overtime_in, $overtime_out);

        $ot_hours = $o->computeHours();
        $this->assertEqual($ot_hours, 4);

        $ot_excess_hours = $o->computeExcessHours();
        $this->assertEqual($ot_excess_hours, 0);

        $ot_nd = $o->computeNightDiff();
        $this->assertEqual($ot_nd, 1);

        $ot_excess_nd = $o->computeExcessNightDiff();
        $this->assertEqual($ot_excess_nd, 0);

    }
    function test_overtime1() {
        $overtime_in = '2014-01-19 17:00:00';
        $overtime_out = '2014-01-20 05:00:00';

        $o = new G_Overtime_Calculator_New($overtime_in, $overtime_out);

        $ot_hours = $o->computeHours();
        $this->assertEqual($ot_hours, 8);

        $ot_excess_hours = $o->computeExcessHours();
        $this->assertEqual($ot_excess_hours, 4);

        $ot_nd = $o->computeNightDiff();
        $this->assertEqual($ot_nd, 3);

        $ot_excess_nd = $o->computeExcessNightDiff();
        $this->assertEqual($ot_excess_nd, 4);
    }

    function test_regular_overtime3() {
        $e = G_Employee_Finder::findByEmployeeCode(4072);

        $date = '2014-01-11'; // Saturday

        $schedule_time_in = '06:00:00';
        $schedule_time_out = '17:00:00';

        $actual_time_in = '06:00:00';
        $actual_time_out = '17:00:00';

        $overtime_in = '17:00:00';
        $overtime_out = '05:00:00';

        // ADD CHANGED SCHEDULE
        $s = G_Schedule_Specific_Finder::findByEmployeeAndDate($e, $date);
        if (!$s) { $s = new G_Schedule_Specific; }
        $s->setDateStart($date);
        $s->setDateEnd($date);
        $s->setTimeIn($schedule_time_in);
        $s->setTimeOut($schedule_time_out);
        $s->setEmployeeId($e->getId());
        $s->save();

        // ADD DTR
        G_Attendance_Helper::recordTimecard($e, $date, $actual_time_in, $actual_time_out, $date, $date);

        // ADD OVERTIME
        $o = G_Overtime_Finder::findByEmployeeAndDate($e, $date);
        if (!$o) {
            $o = new G_Overtime;
        }
        $o->setDate($date);
        $o->setTimeIn($overtime_in);
        $o->setTimeOut($overtime_out);
        $o->setEmployeeId($e->getId());
        $o->save();

        G_Attendance_Helper::updateAttendance($e, $date);

        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();

        $this->assertIdentical($t->getScheduledTimeIn(), $schedule_time_in);
        $this->assertIdentical($t->getScheduledTimeOut(), $schedule_time_out);

        $nd_hours = $t->getNightShiftHours();
        $this->assertEqual($nd_hours, 0);

        $ot_hours = $t->getRestDayOvertimeHours();
        $this->assertEqual($ot_hours, 0);
        $ot_hours = $t->getRegularOvertimeHours();
        $this->assertEqual($ot_hours, 10); // with 2 hours auto overtime

        $nd_ot_hours = $t->getRestDayOvertimeNightShiftHours();
        $this->assertEqual($nd_ot_hours, 0);
        $nd_ot_hours = $t->getRegularOvertimeNightShiftHours();
        $this->assertEqual($nd_ot_hours, 3);

        $ot_excess_hours = $t->getRestDayOvertimeExcessHours();
        $this->assertEqual($ot_excess_hours, 0);
        $ot_excess_hours = $t->getRegularOvertimeExcessHours();
        $this->assertEqual($ot_excess_hours, 4);

        $nd_ot_excess_hours = $t->getRestDayOvertimeNightShiftExcessHours();
        $this->assertEqual($nd_ot_excess_hours, 0);
        $nd_ot_excess_hours = $t->getRegularOvertimeNightShiftExcessHours();
        $this->assertEqual($nd_ot_excess_hours, 4);
    }

    function test_regular_overtime2() {
        $e = G_Employee_Finder::findByEmployeeCode(4072);

        $date = '2014-01-10'; // Friday

        $schedule_time_in = '06:00:00';
        $schedule_time_out = '17:00:00';

        $actual_time_in = '06:00:00';
        $actual_time_out = '17:00:00';

        $overtime_in = '17:00:00';
        $overtime_out = '05:00:00';

        // ADD DTR
        G_Attendance_Helper::recordTimecard($e, $date, $actual_time_in, $actual_time_out, $date, $date);

        // ADD OVERTIME
        $o = G_Overtime_Finder::findByEmployeeAndDate($e, $date);
        if (!$o) {
            $o = new G_Overtime;
        }
        $o->setDate($date);
        $o->setTimeIn($overtime_in);
        $o->setTimeOut($overtime_out);
        $o->setEmployeeId($e->getId());
        $o->save();

        G_Attendance_Helper::updateAttendance($e, $date);

        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();

        $this->assertIdentical($t->getScheduledTimeIn(), $schedule_time_in);
        $this->assertIdentical($t->getScheduledTimeOut(), $schedule_time_out);

        $nd_hours = $t->getNightShiftHours();
        $this->assertEqual($nd_hours, 0);

        $ot_hours = $t->getRestDayOvertimeHours();
        $this->assertEqual($ot_hours, 0);
        $ot_hours = $t->getRegularOvertimeHours();
        $this->assertEqual($ot_hours, 10); // with 2 hours auto overtime

        $nd_ot_hours = $t->getRestDayOvertimeNightShiftHours();
        $this->assertEqual($nd_ot_hours, 0);
        $nd_ot_hours = $t->getRegularOvertimeNightShiftHours();
        $this->assertEqual($nd_ot_hours, 3);

        $ot_excess_hours = $t->getRestDayOvertimeExcessHours();
        $this->assertEqual($ot_excess_hours, 0);
        $ot_excess_hours = $t->getRegularOvertimeExcessHours();
        $this->assertEqual($ot_excess_hours, 4);

        $nd_ot_excess_hours = $t->getRestDayOvertimeNightShiftExcessHours();
        $this->assertEqual($nd_ot_excess_hours, 0);
        $nd_ot_excess_hours = $t->getRegularOvertimeNightShiftExcessHours();
        $this->assertEqual($nd_ot_excess_hours, 4);
    }

    function test_rest_day_overtime1() {
        $e = G_Employee_Finder::findByEmployeeCode(4072);
 
        $date = '2014-01-09'; // Thursday

        $schedule_time_in = '05:00:00';
        $schedule_time_out = '16:00:00';

        $actual_time_in = '06:00:00';
        $actual_time_out = '17:00:00';

        $overtime_in = '17:00:00';
        $overtime_out = '05:00:00';

        // ADD CHANGED SCHEDULE
        $s = G_Schedule_Specific_Finder::findByEmployeeAndDate($e, $date);
        if (!$s) { $s = new G_Schedule_Specific; }
        $s->setDateStart($date);
        $s->setDateEnd($date);
        $s->setTimeIn($schedule_time_in);
        $s->setTimeOut($schedule_time_out);
        $s->setEmployeeId($e->getId());
        $s->save();

        // ADD DTR
        G_Attendance_Helper::recordTimecard($e, $date, $actual_time_in, $actual_time_out, $date, $date);

        // ADD OVERTIME
        $o = G_Overtime_Finder::findByEmployeeAndDate($e, $date);
        if (!$o) {
            $o = new G_Overtime;
        }
        $o->setDate($date);
        $o->setTimeIn($overtime_in);
        $o->setTimeOut($overtime_out);
        $o->setEmployeeId($e->getId());
        $o->save();

        // ADD REST DAY
        $r = G_Restday_Finder::findByEmployeeAndDate($e, $date);
        if (!$r) { $r = new G_Restday; }
        $r->setDate($date);
        $r->setEmployeeId($e->getId());
        $r->save();

        G_Attendance_Helper::updateAttendance($e, $date);

        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();

        $this->assertIdentical($t->getScheduledTimeIn(), $schedule_time_in);
        $this->assertIdentical($t->getScheduledTimeOut(), $schedule_time_out);

        $nd_hours = $t->getNightShiftHours();
        $this->assertEqual($nd_hours, 0);

        $ot_hours = $t->getRestDayOvertimeHours();
        $this->assertEqual($ot_hours, 8);
        $ot_hours = $t->getRegularOvertimeHours();
        $this->assertEqual($ot_hours, 0);

        $nd_ot_hours = $t->getRestDayOvertimeNightShiftHours();
        $this->assertEqual($nd_ot_hours, 3);
        $nd_ot_hours = $t->getRegularOvertimeNightShiftHours();
        $this->assertEqual($nd_ot_hours, 0);

        $ot_excess_hours = $t->getRestDayOvertimeExcessHours();
        $this->assertEqual($ot_excess_hours, 4);
        $ot_excess_hours = $t->getRegularOvertimeExcessHours();
        $this->assertEqual($ot_excess_hours, 0);

        $nd_ot_excess_hours = $t->getRestDayOvertimeNightShiftExcessHours();
        $this->assertEqual($nd_ot_excess_hours, 4);
        $nd_ot_excess_hours = $t->getRegularOvertimeNightShiftExcessHours();
        $this->assertEqual($nd_ot_excess_hours, 0);
    }

    function test_regular_day_overtime3() {
        $e = G_Employee_Finder::findByEmployeeCode(4072);

        $date = '2014-01-08'; // Wednesday

        $schedule_time_in = '06:00:00';
        $schedule_time_out = '17:00:00';

        $actual_time_in = '06:00:00';
        $actual_time_out = '17:00:00';

        $overtime_in = '17:00:00';
        $overtime_out = '05:00:00';

        // ADD DTR
        G_Attendance_Helper::recordTimecard($e, $date, $actual_time_in, $actual_time_out, $date, $date);

        // ADD OVERTIME
        $o = G_Overtime_Finder::findByEmployeeAndDate($e, $date);
        if (!$o) {
            $o = new G_Overtime;
        }
        $o->setDate($date);
        $o->setTimeIn($overtime_in);
        $o->setTimeOut($overtime_out);
        $o->setEmployeeId($e->getId());
        $o->save();

        G_Attendance_Helper::updateAttendance($e, $date);

        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();

        $this->assertIdentical($t->getScheduledTimeIn(), $schedule_time_in);
        $this->assertIdentical($t->getScheduledTimeOut(), $schedule_time_out);

        $nd_hours = $t->getNightShiftHours();
        $this->assertEqual($nd_hours, 0);

        $ot_hours = $t->getRestDayOvertimeHours();
        $this->assertEqual($ot_hours, 0);
        $ot_hours = $t->getRegularOvertimeHours();
        $this->assertEqual($ot_hours, 10); // with 2 hours auto overtime 

        $nd_ot_hours = $t->getRestDayOvertimeNightShiftHours();
        $this->assertEqual($nd_ot_hours, 0);
        $nd_ot_hours = $t->getRegularOvertimeNightShiftHours();
        $this->assertEqual($nd_ot_hours, 3);

        $ot_excess_hours = $t->getRestDayOvertimeExcessHours();
        $this->assertEqual($ot_excess_hours, 0);
        $ot_excess_hours = $t->getRegularOvertimeExcessHours();
        $this->assertEqual($ot_excess_hours, 4);

        $nd_ot_excess_hours = $t->getRestDayOvertimeNightShiftExcessHours();
        $this->assertEqual($nd_ot_excess_hours, 0);
        $nd_ot_excess_hours = $t->getRegularOvertimeNightShiftExcessHours();
        $this->assertEqual($nd_ot_excess_hours, 4);
    }

    function test_regular_day_overtime2() {
        $e = G_Employee_Finder::findByEmployeeCode(4072);

        $date = '2014-01-07'; // Tuesday

        $schedule_time_in = '06:00:00';
        $schedule_time_out = '17:00:00';

        $actual_time_in = '05:00:00';
        $actual_time_out = '17:00:00';

        $overtime_in = '18:00:00';
        $overtime_out = '20:30:00';

        // ADD DTR
        G_Attendance_Helper::recordTimecard($e, $date, $actual_time_in, $actual_time_out, $date, $date);

        // ADD OVERTIME
        $o = G_Overtime_Finder::findByEmployeeAndDate($e, $date);
        if (!$o) {
            $o = new G_Overtime;
        }
        $o->setDate($date);
        $o->setTimeIn($overtime_in);
        $o->setTimeOut($overtime_out);
        $o->setEmployeeId($e->getId());
        $o->save();

        G_Attendance_Helper::updateAttendance($e, $date);

        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), $schedule_time_in);
        $this->assertIdentical($t->getScheduledTimeOut(), $schedule_time_out);

        $nd_hours = $t->getNightShiftHours();
        $this->assertEqual($nd_hours, 0);

        $ot_hours = $t->getRestDayOvertimeHours();
        $this->assertEqual($ot_hours, 0);
        $ot_hours = $t->getRegularOvertimeHours();
        $this->assertEqual($ot_hours, 4.5); // with 2 hours auto overtime 

        $nd_ot_hours = $t->getRestDayOvertimeNightShiftHours();
        $this->assertEqual($nd_ot_hours, 0);
        $nd_ot_hours = $t->getRegularOvertimeNightShiftHours();
        $this->assertEqual($nd_ot_hours, 0);

        $ot_excess_hours = $t->getRestDayOvertimeExcessHours();
        $this->assertEqual($ot_excess_hours, 0);
        $ot_excess_hours = $t->getRegularOvertimeExcessHours();
        $this->assertEqual($ot_excess_hours, 0);

        $nd_ot_excess_hours = $t->getRestDayOvertimeNightShiftExcessHours();
        $this->assertEqual($nd_ot_excess_hours, 0);
        $nd_ot_excess_hours = $t->getRegularOvertimeNightShiftExcessHours();
        $this->assertEqual($nd_ot_excess_hours, 0);
    }

    function test_regular_day_overtime1() {
        $e = G_Employee_Finder::findByEmployeeCode(4072);

        $date = '2014-01-06'; // Monday

        $schedule_time_in = '06:00:00';
        $schedule_time_out = '17:00:00';

        $actual_time_in = '06:00:00';
        $actual_time_out = '17:00:00';

        $overtime_in = '17:00:00';
        $overtime_out = '18:30:00';

        // ADD DTR
        G_Attendance_Helper::recordTimecard($e, $date, $actual_time_in, $actual_time_out, $date, $date);

        // ADD OVERTIME
        $o = G_Overtime_Finder::findByEmployeeAndDate($e, $date);
        if (!$o) {
            $o = new G_Overtime;
        }
        $o->setDate($date);
        $o->setTimeIn($overtime_in);
        $o->setTimeOut($overtime_out);
        $o->setEmployeeId($e->getId());
        $o->save();

        G_Attendance_Helper::updateAttendance($e, $date);

        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();

        $this->assertIdentical($t->getScheduledTimeIn(), $schedule_time_in);
        $this->assertIdentical($t->getScheduledTimeOut(), $schedule_time_out);

        $nd_hours = $t->getNightShiftHours();
        $this->assertEqual($nd_hours, 0);

        $ot_hours = $t->getRestDayOvertimeHours();
        $this->assertEqual($ot_hours, 0);
        $ot_hours = $t->getRegularOvertimeHours();
        $this->assertEqual($ot_hours, 3.5); // with 2 hours auto overtime 

        $nd_ot_hours = $t->getRestDayOvertimeNightShiftHours();
        $this->assertEqual($nd_ot_hours, 0);
        $nd_ot_hours = $t->getRegularOvertimeNightShiftHours();
        $this->assertEqual($nd_ot_hours, 0);

        $ot_excess_hours = $t->getRestDayOvertimeExcessHours();
        $this->assertEqual($ot_excess_hours, 0);
        $ot_excess_hours = $t->getRegularOvertimeExcessHours();
        $this->assertEqual($ot_excess_hours, 0);

        $nd_ot_excess_hours = $t->getRestDayOvertimeNightShiftExcessHours();
        $this->assertEqual($nd_ot_excess_hours, 0);
        $nd_ot_excess_hours = $t->getRegularOvertimeNightShiftExcessHours();
        $this->assertEqual($nd_ot_excess_hours, 0);
    }
}

class TestAttendance_Night_Shift_Calculator extends UnitTestCase {
    function test_ns() {
        $ns = new G_Night_Shift_Hours_Calculator('2014-01-19 05:00:00', '2014-01-19 09:00:00');
        $ns_hours = $ns->compute();
        $this->assertEqual($ns_hours, 1);
    }
    function test_ns2() {
        $ns = new G_Night_Shift_Hours_Calculator('2014-01-19 17:00:00', '2014-01-20 05:00:00');
        $ns_hours = $ns->compute();
        $this->assertEqual($ns_hours, 7);
    }
    function test_ns3() {
        $ns = new G_Night_Shift_Hours_Calculator('2014-01-19 17:00:00', '2014-01-20 06:00:00');
        $ns_hours = $ns->compute();
        $this->assertEqual($ns_hours, 8);
    }
    function test_ns4() {
        $ns = new G_Night_Shift_Hours_Calculator('2014-01-19 17:00:00', '2014-01-20 08:00:00');
        $ns_hours = $ns->compute();
        $this->assertEqual($ns_hours, 8);
    }
    function test_ns5() {
        $ns = new G_Night_Shift_Hours_Calculator('2014-01-20 01:00:00', '2014-01-20 08:00:00');
        $ns_hours = $ns->compute();
        $this->assertEqual($ns_hours, 5);
    }
    function test_ns6() {
        $ns = new G_Night_Shift_Hours_Calculator('2014-01-19 23:30:00', '2014-01-20 08:00:00');
        $ns_hours = $ns->compute();
        $this->assertEqual($ns_hours, 6.5);
    }
    function test_ns7() {
        $ns = new G_Night_Shift_Hours_Calculator('2014-01-20 00:00:00', '2014-01-20 02:00:00');
        $ns_hours = $ns->compute();
        $this->assertEqual($ns_hours, 2);
    }
    function test_ns8() {
        $ns = new G_Night_Shift_Hours_Calculator('2014-01-20 00:00:00', '2014-01-20 09:00:00');
        $ns_hours = $ns->compute();
        $this->assertEqual($ns_hours, 6);
    }
    function test_ns9() {
        $ns = new G_Night_Shift_Hours_Calculator('2014-01-20 00:00:00', '2014-01-20 06:30:00');
        $ns_hours = $ns->compute();
        $this->assertEqual($ns_hours, 6);
    }
    function test_ns10() {
        $ns = new G_Night_Shift_Hours_Calculator('2014-01-20 23:00:00', '2014-01-20 06:30:00');
        $ns_hours = $ns->compute();
        $this->assertEqual($ns_hours, 7);
    }
    function test_ns11() {
        $ns = new G_Night_Shift_Hours_Calculator('2014-01-20 09:00:00', '2014-01-20 09:00:00');
        $ns_hours = $ns->compute();
        $this->assertEqual($ns_hours, 0);
    }
}

class TestAttendance_Employee_Group_Schedule extends UnitTestCase {
    function test_Employee_Group() {
        $g = G_Group_Finder::findByName('Test Group');
    	$s = G_Schedule_Group_Finder::findByName('Test for Group');
    }
}

class TestAttendance_Rest_Day extends UnitTestCase {
    function testRestDay_Import() {
        $file = BASE_PATH . "attendance/rd_instance13.xlsx"; // March
        $rd = new G_Restday_Import($file);
		$rd->import();

        $sched = new G_Schedule_Specific_Import($file);
        $sched->import();

        $e = G_Employee_Finder::findByEmployeeCode(2993);

        // Add Specific Schedule
        $s = G_Schedule_Specific_Finder::findByEmployeeAndDate($e, '2014-03-19');
        if (!$s) { $s = new G_Schedule_Specific; }
		$s->setDateStart('2014-03-19');
		$s->setDateEnd('2014-03-19');
		$s->setTimeIn('09:00:00');
		$s->setTimeOut('18:00:00');
		$s->setEmployeeId($e->getId());
		$s->save();

        // Sunday (Regular Day)
        $date = '2014-03-02';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 3');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '11:00:00');
        $this->assertIdentical($t->getScheduledTimeOut(), '20:00:00');
        $this->assertIdentical($a->isRestday(), false);

        // Monday (Regular Day)
        $date = '2014-03-03';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 3');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '10:00:00');
        $this->assertIdentical($t->getScheduledTimeOut(), '19:00:00');
        $this->assertIdentical($a->isRestday(), false);

        // Tuesday (Regular Day)
        $date = '2014-03-04';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 3');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '08:30:00');
        $this->assertIdentical($t->getScheduledTimeOut(), '17:30:00');
        $this->assertIdentical($a->isRestday(), false);

        // Wednesday (Rest Day)
        $date = '2014-03-05';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 3');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '');
        $this->assertIdentical($t->getScheduledTimeOut(), '');
        $this->assertIdentical($a->isRestday(), true);

        // Thursday (Regular Day)
        $date = '2014-03-06';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 3');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '07:00:00');
        $this->assertIdentical($t->getScheduledTimeOut(), '16:00:00');
        $this->assertIdentical($a->isRestday(), false);

        // Friday (Regular Day)
        $date = '2014-03-07';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 3');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '10:30:00');
        $this->assertIdentical($t->getScheduledTimeOut(), '19:30:00');
        $this->assertIdentical($a->isRestday(), false);

        // Saturday (Regular Day)
        $date = '2014-03-08';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 3');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '09:00:00');
        $this->assertIdentical($t->getScheduledTimeOut(), '18:00:00');
        $this->assertIdentical($a->isRestday(), false);

        //====================

        // Monday (Rest Day)
        $date = '2014-03-10';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 3');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '08:30:00');
        $this->assertIdentical($t->getScheduledTimeOut(), '17:00:00');
        $this->assertIdentical($a->isRestday(), true);

        // Wednesday (Regular Day)
        $date = '2014-03-12';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 3');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '');
        $this->assertIdentical($t->getScheduledTimeOut(), '');
        $this->assertIdentical($a->isRestday(), false);

        //=======================

        // Wednesday (Rest Day)
        $date = '2014-03-19';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 3');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '09:00:00');
        $this->assertIdentical($t->getScheduledTimeOut(), '18:00:00');
        $this->assertIdentical($a->isRestday(), true);

        // Saturday (Regular Day)
        $date = '2014-03-22';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 3');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '08:30:00');
        $this->assertIdentical($t->getScheduledTimeOut(), '18:00:00');
        $this->assertIdentical($a->isRestday(), false);

        //=====================

        // Wednesday (Rest Day)
        $date = '2014-03-26';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 3');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '08:30:00');
        $this->assertIdentical($t->getScheduledTimeOut(), '20:00:00');
        $this->assertIdentical($a->isRestday(), true);

    }
    function testRestDay_Import_Dates_Only() {
        $file = BASE_PATH . "attendance/rd_instance15.xlsx"; // May
        $rd = new G_Restday_Import($file);
		$rd->import();
        $sched = new G_Schedule_Specific_Import($file);
        $sched->import();
        $e = G_Employee_Finder::findByEmployeeCode(2993);

        // This will use March Schedule since no set weekly schedule for May

        // Tuesday (Regular Day)
        $date = '2014-05-06';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 3');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '08:30:00');
        $this->assertIdentical($t->getScheduledTimeOut(), '17:30:00');
        $this->assertIdentical($a->isRestday(), false);

    }
    function testRestDay_Import_with_Specific_Schedule_and_Use_Last_Schedule() {
        $file = BASE_PATH . "attendance/rd_sched_instance14.xlsx"; // April
        $rd = new G_Restday_Import($file);
		$rd->import();
        $sched = new G_Schedule_Specific_Import($file);
        $sched->import();

        // This will use March Schedule since no set weekly schedule for April
        $e = G_Employee_Finder::findByEmployeeCode(2993);

        // Tuesday (Regular Day)
        $date = '2014-04-01';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 3');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '08:30:00');
        $this->assertIdentical($t->getScheduledTimeOut(), '17:30:00');
        $this->assertIdentical($a->isRestday(), false);

        // Wed (Rest Day)
        $date = '2014-04-02';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 3');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '');
        $this->assertIdentical($t->getScheduledTimeOut(), '');
        $this->assertIdentical($a->isRestday(), true);

        //===================

        // Wed (Regular Day)
        $date = '2014-04-09';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 3');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '');
        $this->assertIdentical($t->getScheduledTimeOut(), '');
        $this->assertIdentical($a->isRestday(), false);

        // Thu (Rest Day)
        $date = '2014-04-10';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 3');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '08:30:00');
        $this->assertIdentical($t->getScheduledTimeOut(), '17:00:00');
        $this->assertIdentical($a->isRestday(), true);

        //======================

        // Tue (Rest Day)
        $date = '2014-04-15';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 3');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '');
        $this->assertIdentical($t->getScheduledTimeOut(), '');
        $this->assertIdentical($a->isRestday(), true);

        // Wed (Regular Day)
        $date = '2014-04-16';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 3');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '');
        $this->assertIdentical($t->getScheduledTimeOut(), '');
        $this->assertIdentical($a->isRestday(), false);

        //=====================

        // Tue (Rest Day)
        $date = '2014-04-22';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 3');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '');
        $this->assertIdentical($t->getScheduledTimeOut(), '');
        $this->assertIdentical($a->isRestday(), true);

        // Wed (Regular Day)
        $date = '2014-04-23';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 3');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '');
        $this->assertIdentical($t->getScheduledTimeOut(), '');
        $this->assertIdentical($a->isRestday(), false);

        // Thu (Rest Day)
        $date = '2014-04-24';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 3');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '08:30:00');
        $this->assertIdentical($t->getScheduledTimeOut(), '19:00:00');
        $this->assertIdentical($a->isRestday(), true);

        // Fri (Rest Day)
        $date = '2014-04-25';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 3');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '08:30:00');
        $this->assertIdentical($t->getScheduledTimeOut(), '19:00:00');
        $this->assertIdentical($a->isRestday(), true);
    }

    function testRestDay_from_changed_restday_week4() {
        $e = G_Employee_Finder::findByEmployeeCode(2993); // Romulo, Carlos

        // Set as Rest Day
        $restdays = array('2014-02-24', '2014-02-27');
        foreach ($restdays as $rest_date) {
            $r = G_Restday_Finder::findByEmployeeAndDate($e, $rest_date);
            if (!$r) { $r = new G_Restday; }
    		$r->setDate($rest_date);
    		$r->setEmployeeId($e->getId());
            $r->save();
        }

        // Add Specific Schedule
        $s = G_Schedule_Specific_Finder::findByEmployeeAndDate($e, '2014-02-25');
        if (!$s) { $s = new G_Schedule_Specific; }
		$s->setDateStart('2014-02-25');
		$s->setDateEnd('2014-02-25');
		$s->setTimeIn('09:00:00');
		$s->setTimeOut('18:00:00');
		$s->setEmployeeId($e->getId());
		$s->save();

        // Add Specific Schedule
        $s = G_Schedule_Specific_Finder::findByEmployeeAndDate($e, '2014-02-27');
        if (!$s) { $s = new G_Schedule_Specific; }
		$s->setDateStart('2014-02-27');
		$s->setDateEnd('2014-02-27');
		$s->setTimeIn('10:00:00');
		$s->setTimeOut('19:00:00');
		$s->setEmployeeId($e->getId());
		$s->save();

        // Sunday (Regular Day)
        $date = '2014-02-23';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 2');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '');
        $this->assertIdentical($t->getScheduledTimeOut(), '');
        $this->assertIdentical($a->isRestday(), false);

        // Monday (Rest Day)
        $date = '2014-02-24';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 2');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '');
        $this->assertIdentical($t->getScheduledTimeOut(), '');
        $this->assertIdentical($a->isRestday(), true);

        // Tuesday (Regular Day)
        $date = '2014-02-25';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 2');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '09:00:00');
        $this->assertIdentical($t->getScheduledTimeOut(), '18:00:00');
        $this->assertIdentical($a->isRestday(), false);

        // Wednesday (Regular Day)
        $date = '2014-02-26';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 2');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '08:30:00');
        $this->assertIdentical($t->getScheduledTimeOut(), '17:30:00');
        $this->assertIdentical($a->isRestday(), false);

        // Thursday (Rest Day)
        $date = '2014-02-27';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 2');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '10:00:00');
        $this->assertIdentical($t->getScheduledTimeOut(), '19:00:00');
        $this->assertIdentical($a->isRestday(), true);

        // Friday (Regular Day)
        $date = '2014-02-28';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 2');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $this->assertIdentical($a->isRestday(), false);

        // Saturday (Regular Day)
        $date = '2014-03-01';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 2');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $this->assertIdentical($a->isRestday(), false);
    }

    function testRestDay_from_changed_restday_week3() {
        $e = G_Employee_Finder::findByEmployeeCode(2993); // Romulo, Carlos

        // Set as Rest Day
        $restdays = array('2014-02-21');
        foreach ($restdays as $rest_date) {
            $r = G_Restday_Finder::findByEmployeeAndDate($e, $rest_date);
            if (!$r) { $r = new G_Restday; }
    		$r->setDate($rest_date);
    		$r->setEmployeeId($e->getId());
            $r->save();
        }

        // Add Specific Schedule
        $s = G_Schedule_Specific_Finder::findByEmployeeAndDate($e, '2014-02-22');
        if (!$s) { $s = new G_Schedule_Specific; }
		$s->setDateStart('2014-02-22');
		$s->setDateEnd('2014-02-22');
		$s->setTimeIn('10:00:00');
		$s->setTimeOut('19:00:00');
		$s->setEmployeeId($e->getId());
		$s->save();

        // Sunday (Rest Day)
        $date = '2014-02-16';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 2');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '');
        $this->assertIdentical($t->getScheduledTimeOut(), '');
        $this->assertIdentical($a->isRestday(), false);

        // Monday (Regular Day)
        $date = '2014-02-17';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 2');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '08:30:00');
        $this->assertIdentical($t->getScheduledTimeOut(), '17:30:00');
        $this->assertIdentical($a->isRestday(), false);

        // Tuesday (Regular Day)
        $date = '2014-02-18';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 2');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '08:30:00');
        $this->assertIdentical($t->getScheduledTimeOut(), '17:30:00');
        $this->assertIdentical($a->isRestday(), false);

        // Wednesday (Regular Day)
        $date = '2014-02-19';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 2');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '08:30:00');
        $this->assertIdentical($t->getScheduledTimeOut(), '17:30:00');
        $this->assertIdentical($a->isRestday(), false);

        // Thursday (Regular Day)
        $date = '2014-02-20';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 2');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '08:30:00');
        $this->assertIdentical($t->getScheduledTimeOut(), '17:30:00');
        $this->assertIdentical($a->isRestday(), false);

        // Friday (Rest Day)
        $date = '2014-02-21';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 2');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '');
        $this->assertIdentical($t->getScheduledTimeOut(), '');
        $this->assertIdentical($a->isRestday(), true);

        // Saturday (Regular Day)
        $date = '2014-02-22';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 2');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '10:00:00');
        $this->assertIdentical($t->getScheduledTimeOut(), '19:00:00');
        $this->assertIdentical($a->isRestday(), false);
    }

    function testRestDay_from_changed_restday_week2() {
        $e = G_Employee_Finder::findByEmployeeCode(2993); // Romulo, Carlos

        // Set as Rest Day
        $restdays = array('2014-02-09', '2014-02-15');
        foreach ($restdays as $rest_date) {
            $r = G_Restday_Finder::findByEmployeeAndDate($e, $rest_date);
            if (!$r) { $r = new G_Restday; }
    		$r->setDate($rest_date);
    		$r->setEmployeeId($e->getId());
            $r->save();
        }

        // Add Specific Schedule
        $s = G_Schedule_Specific_Finder::findByEmployeeAndDate($e, '2014-02-15');
        if (!$s) { $s = new G_Schedule_Specific; }
		$s->setDateStart('2014-02-15');
		$s->setDateEnd('2014-02-15');
		$s->setTimeIn('10:00:00');
		$s->setTimeOut('19:00:00');
		$s->setEmployeeId($e->getId());
		$s->save();

        // Sunday (Rest Day)
        $date = '2014-02-09';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 2');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '');
        $this->assertIdentical($t->getScheduledTimeOut(), '');
        $this->assertIdentical($a->isRestday(), true);

        // Monday (Regular Day)
        $date = '2014-02-10';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 2');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $this->assertIdentical($a->isRestday(), false);

        // Tuesday (Regular Day)
        $date = '2014-02-11';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 2');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $this->assertIdentical($a->isRestday(), false);

        // Wednesday (Regular Day)
        $date = '2014-02-12';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 2');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $this->assertIdentical($a->isRestday(), false);

        // Thursday (Regular Day)
        $date = '2014-02-13';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 2');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $this->assertIdentical($a->isRestday(), false);

        // Friday (Regular Day)
        $date = '2014-02-14';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 2');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $this->assertIdentical($a->isRestday(), false);

        // Saturday (Rest Day)
        $date = '2014-02-15';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 2');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $t = $a->getTimesheet();
        $this->assertIdentical($t->getScheduledTimeIn(), '10:00:00');
        $this->assertIdentical($t->getScheduledTimeOut(), '19:00:00');
        $this->assertIdentical($a->isRestday(), true);
    }

    function testRestDay_from_changed_restday_week1() {
        $e = G_Employee_Finder::findByEmployeeCode(2993); // Romulo, Carlos

        // Sunday (Rest Day)
        $date = '2014-02-02';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 2');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $this->assertIdentical($a->isRestday(), true);

        // Monday (Regular Day)
        $date = '2014-02-03';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 2');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $this->assertIdentical($a->isRestday(), false);

        // Tuesday (Regular Day)
        $date = '2014-02-04';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 2');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $this->assertIdentical($a->isRestday(), false);

        // Wednesday (Regular Day)
        $date = '2014-02-05';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 2');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $this->assertIdentical($a->isRestday(), false);

        // Thursday (Regular Day)
        $date = '2014-02-06';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 2');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $this->assertIdentical($a->isRestday(), false);

        // Friday (Regular Day)
        $date = '2014-02-07';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 2');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $this->assertIdentical($a->isRestday(), false);

        // Saturday (Rest Day)
        $date = '2014-02-08';
        G_Attendance_Helper::updateAttendance($e, $date);
        $s = G_Schedule_Finder::findActiveByEmployee($e, $date);
        $this->assertIdentical($s->getName(), 'Test for Restday 2');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        $this->assertIdentical($a->isRestday(), true);
    }

    function testRestDay_from_changed_restday() {
        $e = G_Employee_Finder::findByEmployeeCode(2993); // Romulo, Carlos

        // Rest Day (Sunday) changed to Monday. Sunday becomes regular day for this week and Monday is rest day
        $r = G_Restday_Finder::findByEmployeeAndDate($e, '2014-01-20'); // Change Monday as Restday
        if (!$r) { $r = new G_Restday; }
		$r->setDate('2014-01-20');
		$r->setEmployeeId($e->getId());
        $r->save();

         // Sunday (Regular Day)
        G_Attendance_Helper::updateAttendance($e, '2014-01-19');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, '2014-01-19');
        $is_restday = $a->isRestday();
        $s = G_Schedule_Finder::findActiveByEmployee($e, '2014-01-19');
        $this->assertIdentical($s->getName(), 'Test for Restday');
        $this->assertIdentical($is_restday, false);

        // Monday (Rest Day)
        G_Attendance_Helper::updateAttendance($e, '2014-01-20');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, '2014-01-20');
        $is_restday = $a->isRestday();
        $this->assertIdentical($is_restday, true);

        $s = G_Schedule_Finder::findActiveByEmployee($e, '2014-01-20');
        $this->assertIdentical($s->getName(), 'Test for Restday');
    }

    function testRestDay_from_weekly_schedule() {
        $e = G_Employee_Finder::findByEmployeeCode(2993); // Romulo, Carlos

        G_Attendance_Helper::updateAttendance($e, '2014-01-05');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, '2014-01-05'); // Sunday Rest Day
        $is_restday = $a->isRestday();
        $s = G_Schedule_Finder::findActiveByEmployee($e, '2014-01-05');
        $this->assertIdentical($s->getName(), 'Test for Restday');
        $this->assertIdentical($is_restday, true);

        G_Attendance_Helper::updateAttendance($e, '2014-01-06');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, '2014-01-06'); // Monday (Regular Day)
        $is_restday = $a->isRestday();
        $s = G_Schedule_Finder::findActiveByEmployee($e, '2014-01-06');
        $this->assertIdentical($s->getName(), 'Test for Restday');
        $this->assertIdentical($is_restday, false);

        G_Attendance_Helper::updateAttendance($e, '2014-01-07');
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, '2014-01-07'); // Tuesday (Regular Day)
        $is_restday = $a->isRestday();
        $s = G_Schedule_Finder::findActiveByEmployee($e, '2014-01-07');
        $this->assertIdentical($s->getName(), 'Test for Restday');
        $this->assertIdentical($is_restday, false);
    }
}


class TestAttendance_Undertime extends UnitTestCase {
    /*
     * Schedule used - Sample Schedule 7 (night shift)
     */
    function testAttendance2() {
        $file = BASE_PATH . "attendance/dtr_instance6.xlsx";
        G_Attendance_Helper::importTimesheet($file);

        $e = G_Employee_Finder::findByEmployeeCode(8);

        $a = G_Attendance_Finder::findByEmployeeAndDate($e, '2013-07-01'); // Monday
        $t = $a->getTimesheet();
        $ut_hours = $t->getUndertimeHours();
        $ut_time = Tools::convertHourToTime($ut_hours);
        $this->assertIdentical($ut_hours, 0.0167);
        $this->assertIdentical($ut_time, '0:1');

        $a = G_Attendance_Finder::findByEmployeeAndDate($e, '2013-07-02'); // Tuesday
        $t = $a->getTimesheet();
        $ut_hours = $t->getUndertimeHours();
        $ut_time = Tools::convertHourToTime($ut_hours);
        $this->assertIdentical($ut_hours, 7.00);
        $this->assertIdentical($ut_time, '7:0');

        $a = G_Attendance_Finder::findByEmployeeAndDate($e, '2013-07-03'); // Wednesday
        $t = $a->getTimesheet();
        $ut_hours = $t->getUndertimeHours();
        $ut_time = Tools::convertHourToTime($ut_hours);
        $this->assertIdentical($ut_hours, 2.2333);
        $this->assertIdentical($ut_time, '2:14');
    }
    /*
     * Schedule used - Sample Schedule 6
     */
    function testAttendance1() {
        $file = BASE_PATH . "attendance/dtr_instance5.xlsx";
        G_Attendance_Helper::importTimesheet($file);

        $e = G_Employee_Finder::findByEmployeeCode(8);

        $a = G_Attendance_Finder::findByEmployeeAndDate($e, '2013-05-01'); // Wednesday
        $t = $a->getTimesheet();
        $ut_hours = $t->getUndertimeHours();
        $answer = Tools::convertHourToTime($ut_hours);
        $this->assertIdentical($ut_hours, 0.0333);
        $this->assertIdentical($answer, '0:2');

        $a = G_Attendance_Finder::findByEmployeeAndDate($e, '2013-05-02'); // Thursday
        $t = $a->getTimesheet();
        $ut_hours = $t->getUndertimeHours();
        $answer = Tools::convertHourToTime($ut_hours);
        $this->assertIdentical($answer, '0:22');

        $a = G_Attendance_Finder::findByEmployeeAndDate($e, '2013-05-03'); // Friday
        $t = $a->getTimesheet();
        $ut_hours = $t->getUndertimeHours();
        $answer = Tools::convertHourToTime($ut_hours);
        $this->assertIdentical($answer, '0:1');

        $a = G_Attendance_Finder::findByEmployeeAndDate($e, '2013-05-04'); // Saturday (Rest Day)
        $t = $a->getTimesheet();
        $ut_hours = $t->getUndertimeHours();
        $answer = Tools::convertHourToTime($ut_hours);
        $this->assertIdentical($answer, '0:0');

        $a = G_Attendance_Finder::findByEmployeeAndDate($e, '2013-05-06'); // Monday
        $t = $a->getTimesheet();
        $ut_hours = $t->getUndertimeHours();
        $answer = Tools::convertHourToTime($ut_hours);
        $this->assertIdentical($answer, '2:1');

        $a = G_Attendance_Finder::findByEmployeeAndDate($e, '2013-05-07'); // Tuesday
        $t = $a->getTimesheet();
        $ut_hours = $t->getUndertimeHours();
        $answer = Tools::convertHourToTime($ut_hours);
        $this->assertIdentical($answer, '8:0');
    }
}

class TestAttendance extends UnitTestCase {
    function testAttendance1()
	{
		$e = G_Employee_Finder::findByEmployeeCode(3); // Gurango, Leo
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, '2013-03-25');
        $b = G_Attendance_Helper::generateAttendance($e, '2013-03-25');
        $found = false;
        if ($a) {
            $found = true;
        }
        $generated = false;
        if ($b) {
            $generated = true;
        }

		$this->assertIdentical($found, $generated);
	}

    /*
     * The schedule must be the default schedule
     */
    function testAttendance2()
    {
        $e = G_Employee_Finder::findByEmployeeCode(3); // Gurango, Leo
        $b = G_Attendance_Helper::generateAttendance($e, '2012-08-01');

        $t = $b->getTimesheet();
        $time_in = $t->getScheduledTimeIn();
        $time_out = $t->getScheduledTimeOut();

        $this->assertIdentical($time_in, '08:30:00');
        $this->assertIdentical($time_out, '17:30:00');
    }
}

class TestAttendance_Late extends UnitTestCase {
    /*
     * Schedule used - Default schedule
     */
    function testAttendance1() {
        $file = BASE_PATH . 'attendance/dtr_instance1.xlsx';
        G_Attendance_Helper::importTimesheet($file);

        $e = G_Employee_Finder::findByEmployeeCode(3);

        $a = G_Attendance_Finder::findByEmployeeAndDate($e, '2012-08-01');
        $t = $a->getTimesheet();
        $late_hours = $t->getLateHours();
        $answer = Tools::convertHourToTime($late_hours);

        $a2 = G_Attendance_Finder::findByEmployeeAndDate($e, '2012-08-02');
        $t = $a2->getTimesheet();
        $late_hours = $t->getLateHours();
        $answer2 = Tools::convertHourToTime($late_hours);

        $a3 = G_Attendance_Finder::findByEmployeeAndDate($e, '2012-08-06');
        $t = $a3->getTimesheet();
        $late_hours = $t->getLateHours();
        $answer3 = Tools::convertHourToTime($late_hours);

        $a4 = G_Attendance_Finder::findByEmployeeAndDate($e, '2012-08-07');
        $t = $a4->getTimesheet();
        $late_hours = $t->getLateHours();
        $answer4 = Tools::convertHourToTime($late_hours);

        $this->assertIdentical($answer, '0:5');
        $this->assertIdentical($answer2, '0:3');
        $this->assertIdentical($answer3, '0:15');
        $this->assertIdentical($answer4, '1:15');
    }
    /*
     * Schedule used - Sample Schedule 5
     */
    function testAttendance2() {
        $file = BASE_PATH . 'attendance/dtr_instance2.xlsx';
        G_Attendance_Helper::importTimesheet($file);

        $e = G_Employee_Finder::findByEmployeeCode(3);

        $a = G_Attendance_Finder::findByEmployeeAndDate($e, '2013-03-25'); // Monday
        $t = $a->getTimesheet();
        $late_hours = $t->getLateHours();
        $answer = Tools::convertHourToTime($late_hours);

        $a2 = G_Attendance_Finder::findByEmployeeAndDate($e, '2013-03-26'); // Tuesday
        $t = $a2->getTimesheet();
        $late_hours = $t->getLateHours();
        $answer2 = Tools::convertHourToTime($late_hours);

        $a3 = G_Attendance_Finder::findByEmployeeAndDate($e, '2013-03-27'); // Wednesday
        $t = $a3->getTimesheet();
        $late_hours = $t->getLateHours();
        $answer3 = Tools::convertHourToTime($late_hours);

        $a4 = G_Attendance_Finder::findByEmployeeAndDate($e, '2013-03-28'); // Thursday
        $t = $a4->getTimesheet();
        $late_hours = $t->getLateHours();
        $answer4 = Tools::convertHourToTime($late_hours);

        $a5 = G_Attendance_Finder::findByEmployeeAndDate($e, '2013-03-29'); // Friday (Rest day)
        $t = $a5->getTimesheet();
        $late_hours = $t->getLateHours();
        $answer5 = Tools::convertHourToTime($late_hours);

        $a6 = G_Attendance_Finder::findByEmployeeAndDate($e, '2013-04-01'); // Monday
        $t = $a6->getTimesheet();
        $late_hours = $t->getLateHours();
        $answer6 = Tools::convertHourToTime($late_hours);

        $this->assertIdentical($answer, '0:35');
        $this->assertIdentical($answer2, '1:35');
        $this->assertIdentical($answer3, '1:20');
        $this->assertIdentical($answer4, '0:0');
        $this->assertIdentical($answer5, '0:0');
        $this->assertIdentical($answer6, '1:0');
    }
    /*
    * Schedule used - Sample Schedule 5
    * Find schedule by group (Newbie) of employee Sarah Mae Actub (7)
    */
    function testAttendance3() {
        $file = BASE_PATH . 'attendance/dtr_instance3.xlsx';
        G_Attendance_Helper::importTimesheet($file);

        $e = G_Employee_Finder::findByEmployeeCode(7);

        $a = G_Attendance_Finder::findByEmployeeAndDate($e, '2013-03-25'); // Monday
        $t = $a->getTimesheet();
        $late_hours = $t->getLateHours();
        $answer = Tools::convertHourToTime($late_hours);

        $a2 = G_Attendance_Finder::findByEmployeeAndDate($e, '2013-03-26'); // Tuesday
        $t = $a2->getTimesheet();
        $late_hours = $t->getLateHours();
        $answer2 = Tools::convertHourToTime($late_hours);

        $a3 = G_Attendance_Finder::findByEmployeeAndDate($e, '2013-03-27'); // Wednesday
        $t = $a3->getTimesheet();
        $late_hours = $t->getLateHours();
        $answer3 = Tools::convertHourToTime($late_hours);

        $a4 = G_Attendance_Finder::findByEmployeeAndDate($e, '2013-03-28'); // Thursday
        $t = $a4->getTimesheet();
        $late_hours = $t->getLateHours();
        $answer4 = Tools::convertHourToTime($late_hours);

        $a5 = G_Attendance_Finder::findByEmployeeAndDate($e, '2013-03-29'); // Friday (Rest day)
        $t = $a5->getTimesheet();
        $late_hours = $t->getLateHours();
        $answer5 = Tools::convertHourToTime($late_hours);

        $a6 = G_Attendance_Finder::findByEmployeeAndDate($e, '2013-04-01'); // Monday
        $t = $a6->getTimesheet();
        $late_hours = $t->getLateHours();
        $answer6 = Tools::convertHourToTime($late_hours);

        $this->assertIdentical($answer, '0:35');
        $this->assertIdentical($answer2, '1:35');
        $this->assertIdentical($answer3, '1:20');
        $this->assertIdentical($answer4, '0:0');
        $this->assertIdentical($answer5, '0:0');
        $this->assertIdentical($answer6, '1:0');
    }
}

