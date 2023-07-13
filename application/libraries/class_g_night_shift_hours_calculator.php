<?php
/*
 * Usage:
        $ns = new G_Night_Shift_Hours_Calculator('2014-01-19 05:00:00', '2014-01-19 09:00:00');
        $ns->compute();
 */
class G_Night_Shift_Hours_Calculator {
    protected $night_shift_start = '22:00:00';
    protected $night_shift_end = '06:00:00';
    protected $actual_date_time_in;
    protected $actual_date_time_out;
    protected $night_shift_start_date_time;
    protected $night_shift_end_date_time;

    protected $has_schedule = false;
    protected $schedule_date_time_in;
    protected $schedule_date_time_out;

    protected $covered_date_time_in;
    protected $covered_date_time_out;

    public function __construct($date_time_in, $date_time_out) {

        //Get NS hours in sprint variables
         $sv = new G_Sprint_Variables(G_Sprint_Variables::FIELD_NIGHTSHIFT_HOUR);
         $value = $sv->getVariableValue(); 

         if( $value != "" ){
             $sv = new G_Sprint_Variables(G_Sprint_Variables::FIELD_NIGHTSHIFT_HOUR);
             $value = $sv->getVariableValue(); 
             if( $value != "" ){
                $a_time_in_out = explode("to", $value);         
                if( count($a_time_in_out) >= 2){                
                    $time_in  = trim($a_time_in_out[0]);
                    $time_out = trim($a_time_in_out[1]);
                    $format   = "H:i:s";
                    if( Tools::isValidDateTime($time_in,$format) && Tools::isValidDateTime($time_out,$format) ){
                        $this->night_shift_start = $time_in;
                        $this->night_shift_end   = $time_out;
                    }
                }
             }
         }

        $this->actual_date_time_in = $date_time_in;
        $this->actual_date_time_out = $date_time_out;
        $this->night_shift_start_date_time = $this->getNightShiftStartDateTime();
        $this->night_shift_end_date_time = $this->getNightShiftEndDateTime();
    }

    public function setScheduleDateTime($date_time_in, $date_time_out) {
        $this->schedule_date_time_in = $date_time_in;
        $this->schedule_date_time_out = $date_time_out;
        $this->has_schedule = true;
    }

    public function compute() {
        $date_time_in = $this->actual_date_time_in;
        $date_time_out = $this->actual_date_time_out;

        $limit_night_shift_hours = $this->getHoursDifference($this->night_shift_start_date_time, $this->night_shift_end_date_time);
        $is_start_between        = $this->isBetweenNightShiftDateTime($date_time_in);
        $is_end_between          = $this->isBetweenNightShiftDateTime($date_time_out);
       
        if (!$is_start_between && !$is_end_between) {
            $total_actual_hours = $this->getHoursDifference($date_time_in, $date_time_out);
            if ($total_actual_hours > 15) {
                return 0;
            } else {
                $ns_hours = Tools::getBetweenHours($this->night_shift_start, $this->night_shift_end);
                $actual_hours = Tools::getBetweenHours($date_time_in, $date_time_out);
                $temp_first_time = '';
                $temp_second_time = '';
                foreach ($actual_hours as $actual_hour) {
                    if (in_array($actual_hour, $ns_hours)) {
                        if ($temp_first_time == '') {
                            $temp_first_time = $actual_hour;
                        } else {
                            $temp_second_time =  $actual_hour;
                        }
                    }
                }

                $first_date_time = $temp_first_time;
                $second_date_time = $temp_second_time;
            }
        } else if ($is_start_between && $is_end_between) {
            $first_date_time = $date_time_in;
            $second_date_time = $date_time_out;
        } else if ($is_start_between && !$is_end_between) {
            $first_date_time = $date_time_in;
            $second_date_time = $this->night_shift_end_date_time;
        } else if (!$is_start_between && $is_end_between) {
            $first_date_time = $this->night_shift_start_date_time;
            $second_date_time = $date_time_out;
        } else {
            return 0;
        }

        $hours = $this->getHoursDifference($first_date_time, $second_date_time);

        if ($hours > $limit_night_shift_hours) {
            $hours = $limit_night_shift_hours;
        }

        $this->covered_date_time_in = $first_date_time;
        $this->covered_date_time_out = $second_date_time;
        return $hours;
    }

    public function getCoveredDateTimeIn() {
        return $this->covered_date_time_in;
    }

    public function getCoveredDateTimeOut() {
        return $this->covered_date_time_out;
    }

    private function getHoursDifference($start_date_time, $end_date_time) {
        $d1 = new DateTime("{$start_date_time}");
        $d2 = new DateTime("{$end_date_time}");
        $d = $d1->diff($d2);
        $hours = $d->h;
        $minutes_to_hours = $d->i / 60; // convert to hours
        return (float) $hours + $minutes_to_hours;
    }

    private function getNightShiftStartDateTime() {
        $next_day_start = strtotime('00:00:00');
        $next_day_end = strtotime('11:59:00');
        $time_start = strtotime($this->getTime($this->actual_date_time_in));
        if ($this->isTimeBetweenTimes($time_start, $next_day_start, $next_day_end)) {
            $date = $this->getYesterdayDate($this->actual_date_time_in);
        } else {
            $date = $this->getDate($this->actual_date_time_in);
        }
        return $date . ' ' . $this->night_shift_start;
    }

    private function getNightShiftEndDateTime() {
        $date = $this->getNextDate($this->getNightShiftStartDateTime());
        return $date . ' ' . $this->night_shift_end;
    }

    private function isTimeBetweenTimes($time, $start_time, $end_time) {
        if ($time >= $start_time && $time <= $end_time) {
            return true;
        } else {
            return false;
        }
    }

    private function getYesterdayDate($date_time) {
        $d1 = new DateTime("{$date_time}");
        $d1->modify('-1 day');
        $date = $d1->format('Y-m-d');
        return $date;
    }

    private function getNextDate($date_time) {
        $d1 = new DateTime("{$date_time}");
        $d1->modify('+1 day');
        $date = $d1->format('Y-m-d');
        return $date;
    }

    private function isBetweenNightShiftDateTime($date_time) {
        $start = strtotime($this->night_shift_start_date_time);
        $end   = strtotime($this->night_shift_end_date_time);
        $time  = strtotime($date_time);
        if ($time >= $start && $time <= $end) {
            return true;
        } else {
            return false;
        }
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