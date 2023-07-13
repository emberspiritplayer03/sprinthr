<?php
/*
 * Usage
        $overtime_in = '2014-01-20 20:00:00';
        $overtime_out = '2014-01-21 09:00:00';
        $schedule_in = '2014-01-20 20:00:00';
        $schedule_out = '2014-01-21 05:00:00';

        $o = new G_Overtime_Calculator_New($overtime_in, $overtime_out);
        $o->setScheduleDateTime($schedule_in, $schedule_out);

        $ot_hours = $o->computeHours();
        $ot_excess_hours = $o->computeExcessHours();
        $ot_nd = $o->computeNightDiff();
        $ot_excess_nd = $o->computeExcessNightDiff();
 */
class G_Overtime_Calculator_New {
    protected $limit_hours = 8;
    protected $overtime_in;
    protected $overtime_out;
    protected $date_overtime_in;
    protected $date_overtime_out;

    protected $has_schedule = false;
    protected $schedule_date_time_in;
    protected $schedule_date_time_out;
    protected $break_date_time_in;
    protected $break_date_time_out;

    private $is_debug_mode = false;

    public function __construct($ot_date_time_in, $ot_date_time_out) {
        $this->overtime_in = $this->getTime($ot_date_time_in);
        $this->date_overtime_in = $this->getDate($ot_date_time_in);

        $this->overtime_out = $this->getTime($ot_date_time_out);
        $this->date_overtime_out = $this->getDate($ot_date_time_out);
    }

    public function debugMode() {
        $this->is_debug_mode = true;
    }

    public function setScheduleDateTime($date_time_in, $date_time_out) {
        if (strtotime($date_time_in) && strtotime($date_time_out)) {
            $this->schedule_date_time_in = $date_time_in;
            $this->schedule_date_time_out = $date_time_out;
            $this->has_schedule = true;
            $this->autoSetBreakDateTime($date_time_in, $date_time_out);
        }
    }

    private function autoSetBreakDateTime($date_time_in, $date_time_out) {
        $time_in = $this->getTime($date_time_in);
        if ($this->isTimeNightShift($time_in)) {
            $date = $this->getTomorrowDate($date_time_in);
            $this->break_date_time_in = $date .' 00:00:00';
            $this->break_date_time_out = $date .' 01:00:00';
        } else {
            $date = $this->getDate($date_time_in);
            $this->break_date_time_in = $date .' 12:00:00';
            $this->break_date_time_out = $date .' 13:00:00';
        }
    }

    private function getTomorrowDate($date_time) {
        $d1 = new DateTime("{$date_time}");
        $d1->modify('+1 day');
        $date = $d1->format('Y-m-d');
        return $date;
    }

    private function isTimeNightShift($time) {
        $ns_time_start = strtotime('16:00:00');
        $ns_time_end = strtotime('23:59:00');
        $time = strtotime($time);

        if ($time >= $ns_time_start && $time <= $ns_time_end) {
            return true;
        } else {
            return false;
        }
    }

    public function setBreakTime($date_time_in, $date_time_out) {
        $this->break_date_time_in = $date_time_in;
        $this->break_date_time_out = $date_time_out;
    }

    private function getBreakTimeHours() {
        $hours = $this->getHoursDifference($this->break_date_time_in, $this->break_date_time_out);
        return $hours;
    }

    public function computeHours() {
        $ot_in = $this->getDateTimeOvertimeIn();
        $ot_out = $this->getDateTimeOvertimeOut();
        $limit_time_out = $this->getLimitDateTime($ot_in, $ot_out);
        $ot_hours = $this->getHoursDifference($ot_in, $limit_time_out);

        if ($this->has_schedule) {
            if ($this->isBreakTimeCovered($ot_in, $ot_out)) {
                $limit_time_out = $this->addHour(1, $limit_time_out);
            }
        }

        if ($this->is_debug_mode) {
            echo '<br>';
            echo "OT Hours:<br>{$ot_in} - {$limit_time_out}: ". $this->convertToFloat($ot_hours);
            echo '<br>';
            return $this->convertToFloat($ot_hours);
        } else {
            return $this->convertToFloat($ot_hours);
        }
    }

    private function addHour($hour_to_add, $date_time) {
        $d1 = new DateTime("{$date_time}");
        $date = $d1->modify("+{$hour_to_add} hour");
        return $date->format('Y-m-d H:i:s');
    }

    private function isBreakTimeCovered($date_time_in, $date_time_out) {
        $break_time_in = strtotime($this->break_date_time_in);
        $break_time_out = strtotime($this->break_date_time_out);
        $time_in = strtotime($date_time_in);
        $time_out = strtotime($date_time_out);

        if (($break_time_in >= $time_in && $break_time_in <= $time_out) && ($break_time_out >= $time_in && $break_time_out <= $time_out)) {
            return true;
        } else {
            return false;
        }
    }

    private function convertToFloat($value) {
        return (float) number_format($value, 4, '.', '');
    }

    public function computeExcessHours() {
        $ot_in = $this->getDateTimeOvertimeIn();
        $ot_out = $this->getDateTimeOvertimeOut();
        $limit_time_in = $this->getLimitDateTime($ot_in, $ot_out);
        $ot_hours = $this->getHoursDifference($limit_time_in, $ot_out);

        if ($this->has_schedule) {
            if ($this->isBreakTimeCovered($ot_in, $ot_out)) {
                $limit_time_in = $this->addHour(1, $limit_time_in);
                $ot_hours = $this->getHoursDifference($limit_time_in, $ot_out);
            }
        }

        if ($this->is_debug_mode) {
            echo '<br>';
            echo "Excess Hours:<br> {$limit_time_in} - {$ot_out}: ". $this->convertToFloat($ot_hours);
            echo '<br>';
            return $this->convertToFloat($ot_hours);
        } else {
            return $this->convertToFloat($ot_hours);
        }
    }

    private function getDateTimeOvertimeIn() {
        return $this->date_overtime_in .' '. $this->overtime_in;
    }

    private function getDateTimeOvertimeOut() {
        return $this->date_overtime_out .' '. $this->overtime_out;
    }

    public function computeNightDiffHours() {
        $ot_in = $this->getDateTimeOvertimeIn();
        $ot_out = $this->getDateTimeOvertimeOut();
        $limit_time_out = $this->getLimitDateTime($ot_in, $ot_out);

        if ($this->has_schedule) {
            if ($this->isBreakTimeCovered($ot_in, $ot_out)) {
                $limit_time_out = $this->addHour(1, $limit_time_out);
            }
        }

        $ns = new G_Night_Shift_Hours_Calculator($ot_in, $limit_time_out);
        $ot_hours = $ns->compute();
        $time_in = $ns->getCoveredDateTimeIn();
        $time_out = $ns->getCoveredDateTimeOut();

        if ($this->is_debug_mode) {
            echo '<br>';
            echo "NS Hours:<br> {$time_in} - {$time_out}: ". $this->convertToFloat($ot_hours);
            echo '<br>';
            return $this->convertToFloat($ot_hours);
        } else {
            return $this->convertToFloat($ot_hours);
        }
    }

    public function computeNightDiff() {
        return $this->computeNightDiffHours();
    }

    public function computeExcessNightDiffHours() {
        $ot_in = $this->getDateTimeOvertimeIn();
        $ot_out = $this->getDateTimeOvertimeOut();
        $limit_time_in = $this->getLimitDateTime($ot_in, $ot_out);

        if ($this->has_schedule) {
            if ($this->isBreakTimeCovered($ot_in, $ot_out)) {
                $limit_time_in = $this->addHour(1, $limit_time_in);
            }
        }

        $ns = new G_Night_Shift_Hours_Calculator($limit_time_in, $ot_out);
        $ot_hours = $ns->compute();
        $time_in = $ns->getCoveredDateTimeIn();
        $time_out = $ns->getCoveredDateTimeOut();

        if ($this->is_debug_mode) {
            echo '<br>';
            echo "Excess NS Hours:<br> {$time_in} - {$time_out}: ". $this->convertToFloat($ot_hours);
            echo '<br>';
            return $this->convertToFloat($ot_hours);
        } else {
            return $this->convertToFloat($ot_hours);
        }
    }

    private function isGreaterThan2ndDateTime($date_time_one, $date_time_two) {
        $mktime_date_one = strtotime($date_time_one);
        $mktime_date_two = strtotime($date_time_two);
        if ($mktime_date_one > $mktime_date_two) {
            return true;
        } else {
            return false;
        }
    }

    public function computeExcessNightDiff() {
        return $this->computeExcessNightDiffHours();
    }

    private function getLimitDateTime($start_date_time, $end_date_time) {
        $d1 = new DateTime("{$start_date_time}");
        $d2 = new DateTime("{$end_date_time}");
        $date = $d1->diff($d2);
        $hours = (float) $date->h + ($date->i / 60); // convert to hours
        if ($hours > $this->limit_hours) {
            $excess_hours = $hours - $this->limit_hours;
            $excess_minutes = $excess_hours * 60;
            $d2->modify("-{$excess_minutes} minutes");
        }
        $date_time = $d2->format('Y-m-d H:i:s');
        return $date_time;
    }

    private function getHoursDifference($start_date_time, $end_date_time) {
        $d1 = new DateTime("{$start_date_time}");
        $d2 = new DateTime("{$end_date_time}");
        $d = $d1->diff($d2);
        $hours = $d->h;
        $minutes_to_hours = $d->i / 60; // convert to hours
        return (float) $hours + $minutes_to_hours;
    }
    private function getTime($date_time) {
        $d1 = new DateTime("{$date_time}");
        $time = $d1->format('H:i:s');
        return $time;
    }

    private function getDate($date_time) {
        $d1 = new DateTime("{$date_time}");
        $date = $d1->format('Y-m-d');
        return $date;
    }
}
?>