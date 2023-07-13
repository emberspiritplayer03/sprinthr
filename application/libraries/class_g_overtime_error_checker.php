<?php
/*
 * Usage:
        $overtime_in = '2014-01-21 01:00:00';
        $overtime_out = '2014-01-21 06:00:00';
        $actual_in = '2014-01-21 20:00:00';
        $actual_out = '2014-01-20 06:00:00';

        $oe = new G_Overtime_Error_Checker;
        $oe->setDate('2014-01-20');
        $oe->setActualDateTime($actual_in, $actual_out);
        $oe->setOvertimeDateTime($overtime_in, $overtime_out);
        $oe->check();
        $es = $oe->getErrors();

        OR

        $oe = new G_Overtime_Error_Checker;
        $oe->checkByAttendance($a);
        $oe->checkByEmployeeAndDate($e, $date);
        $es = $oe->getErrors();
 */
class G_Overtime_Error_Checker {
    protected $date;
    protected $actual_date_time_in;
    protected $actual_date_time_out;
    protected $ot_date_time_in;
    protected $ot_date_time_out;

    protected $has_error = false;
    protected $employee;

    protected $overtime_errors = array(); // array of G_Overtime_Error

    public function __construct() {

    }

    public function check() {
        //if (!Tools::isTime1LessThanTime2($this->actual_date_time_in, $this->actual_date_time_out)) {
        //    $err = new G_Overtime_Error;
        //    $err->setDate($this->date);
        //    $error_id = G_Overtime_Error::ACTUAL_OUT_LESS_THAN_ACTUAL_IN;
        //    $err->setErrorTypeId($error_id);
        //    $err->setMessage($this->getMessage($error_id));
        //    $this->addError($err);
        //} else if (Tools::isTime1LessThanTime2($this->actual_date_time_out, $this->ot_date_time_out)) {
        if (trim($this->actual_date_time_in) == '' || trim($this->actual_date_time_out) == '') {
            $this->has_error = true;
            $err = new G_Overtime_Error;
            $err->setDate($this->date);
            $error_id = G_Overtime_Error::ERROR_NO_ACTUAL_TIME;
            $err->setErrorTypeId($error_id);
            $err->setMessage($this->getMessage($error_id));
            $this->addError($err);
        } else if (!Tools::isTime1LessThanTime2($this->ot_date_time_out,$this->actual_date_time_out)) {
            //echo "TIMEIN {$this->actual_date_time_out} OT_IN : {$this->ot_date_time_out}";
            $this->has_error = true;
            $err = new G_Overtime_Error;
            $err->setDate($this->date);
            $error_id = G_Overtime_Error::OT_OUT_GREATER_THAN_ACTUAL_OUT;
            $err->setErrorTypeId($error_id);
            $err->setMessage($this->getMessage($error_id));
            $this->addError($err);
        }
    }

    private function getMessage($error_id) {
       $message = '';
       switch($error_id):
           //case G_Overtime_Error::ACTUAL_OUT_LESS_THAN_ACTUAL_IN:
           //     $time_in = Tools::timeFormat($this->actual_date_time_in);
           //     $time_out = Tools::timeFormat($this->actual_date_time_out);
           //     $message = "Actual time in <b>({$time_in})</b> must be less than the actual time out <b>({$time_out})</b>.";
           //break;
           case G_Overtime_Error::ERROR_NO_ACTUAL_TIME:
               $ot_time_out = Tools::timeFormat($this->ot_date_time_out);
               $actual_time_out = Tools::timeFormat($this->actual_date_time_out);
               $message = "No actual time in and out";
               break;
           case G_Overtime_Error::OT_OUT_GREATER_THAN_ACTUAL_OUT:
               $ot_time_out = Tools::timeFormat($this->ot_date_time_out);
               $actual_time_out = Tools::timeFormat($this->actual_date_time_out);
               $message = "Overtime out <b>({$ot_time_out})</b> must be less than actual time out <b>({$actual_time_out})</b>";
           break;
       endswitch;

       return $message;
    }

    public function getErrors() {
        return $this->overtime_errors;
    }

    public function hasError() {
        return $this->has_error;
    }

    public function setDate($value) {
        $this->date = $value;
    }

    public function setActualDateTime($date_time_in, $date_time_out) {
        $this->actual_date_time_in = $date_time_in;
        $this->actual_date_time_out = $date_time_out;
    }

    public function setOvertimeDateTime($date_time_in, $date_time_out) {
        $this->ot_date_time_in = $date_time_in;
        $this->ot_date_time_out = $date_time_out;
    }

    public function saveErrors() {
        if ($this->has_error) {
            $errors = $this->overtime_errors;
            return G_Overtime_Error_Manager::saveMultiple($errors);
        } else {
            return false;
        }
    }

    public function fixErrors() {
        $employee_id = $this->employee->getId();
        $o = G_Overtime_Error_Finder::findByEmployeeIdAndDate($employee_id, $this->date);
        if ($o) {
            $o->setAsFixed();
            $o->save();
        }
    }

    public function checkByAttendanceAndEmployee($a, $e) {
        $this->employee = $e;
        if (!$a) {
            $this->has_error = true;
            $err = new G_Overtime_Error;
            $err->setDate($this->date);
            $error_id = G_Overtime_Error::ERROR_NO_ACTUAL_TIME;
            $err->setErrorTypeId($error_id);
            $err->setMessage($this->getMessage($error_id));
            $err->setEmployeeId($e->getId());
            $err->setEmployeeCode($e->getEmployeeCode());
            $err->setEmployeeName($e->getName());
            $this->addError($err);
        } else {
            $o = G_Overtime_Finder::findByEmployeeAndDate($e, $a->getDate());
            $t = $a->getTimesheet();
            $actual_in = $t->getDateIn() .' '. $t->getTimeIn();
            $actual_out = $t->getDateOut() .' '. $t->getTimeOut();
            //$overtime_in = $t->getOvertimeDateIn() .' '. $t->getOverTimeIn();
            //$overtime_out = $t->getOvertimeDateOut() .' '. $t->getOverTimeOut();
            $overtime_in = $o->getDateIn() .' '. $o->getTimeIn();
            $overtime_out = $o->getDateOut() .' '. $o->getTimeOut();

            $oe = $this;
            $oe->setDate($a->getDate());
            $oe->setActualDateTime($actual_in, $actual_out);
            $oe->setOvertimeDateTime($overtime_in, $overtime_out);
            $oe->check();
            $es = $oe->getErrors();

            foreach ($es as $ot_error) {
                $ef = G_Overtime_Error_Finder::findByEmployeeIdAndDate($a->getEmployeeId(), $a->getDate());
                if ($ef) {
                    $ot_error->setId($ef->getId());
                }
                $ot_error->setEmployeeId($a->getEmployeeId());
                $ot_error->setTimeIn($o->getTimeIn());
                $ot_error->setTimeOut($o->getTimeOut());
                $ot_error->setAsNotFixed();
                if ($e) {
                    $ot_error->setEmployeeCode($e->getEmployeeCode());
                    $ot_error->setEmployeeName($e->getName());
                }
                $new_errors[] = $ot_error;
            }
            if ($new_errors) {
                $oe->overtime_errors = $new_errors;
            }
        }
    }

    /*
     * param $a - Instance of G_Attendance
     */
    public function checkByAttendance($a) {
        return $this->checkByAttendanceAndEmployee($a, '');
    }

    public function checkByEmployeeAndDate($e, $date) {
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        if ($a) {
            return self::checkByAttendanceAndEmployee($a, $e);
        }
    }

    /*
     * param $overtime_error - Instance of G_Overtime_Error
     */
    private function addError($overtime_error) {
        $this->overtime_errors[] = $overtime_error;
    }
}
?>