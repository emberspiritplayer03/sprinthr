<?php
class G_Timesheet extends Timesheet {
	protected $nightshift_overtime_excess_hours;
	protected $overtime_excess_hours;

	protected $restday_overtime_hours;
	protected $restday_overtime_excess_hours;
	protected $restday_overtime_ns_hours;
	protected $restday_overtime_ns_excess_hours;

    protected $restday_legal_overtime_hours;
    protected $restday_legal_overtime_excess_hours;
    protected $restday_legal_overtime_ns_hours;
    protected $restday_legal_overtime_ns_excess_hours;

    protected $restday_special_overtime_hours;
    protected $restday_special_overtime_excess_hours;
    protected $restday_special_overtime_ns_hours;
    protected $restday_special_overtime_ns_excess_hours;

    protected $legal_overtime_hours;
    protected $legal_overtime_excess_hours;
    protected $legal_overtime_ns_hours;
    protected $legal_overtime_ns_excess_hours;

    protected $special_overtime_hours;
    protected $special_overtime_excess_hours;
    protected $special_overtime_ns_hours;
    protected $special_overtime_ns_excess_hours;

	protected $regular_overtime_hours;
	protected $regular_overtime_excess_hours;
	protected $regular_overtime_ns_hours;
	protected $regular_overtime_ns_excess_hours;

    protected $undertime_hours;
    protected $late_hours;

    protected $overtime_date_in;
    protected $overtime_date_out;

    protected $scheduled_date_in;
    protected $scheduled_date_out;

    protected $total_schedule_hours;
    protected $total_overtime_hours;

    protected $total_deductible_breaktime_hours;

    //new ob timebased
     protected $ob_in;
     protected $ob_out;
     protected $ob_total_hrs;
			
	public function __construct() {
	}

     //for ob timebase
    public function setOBIn($value) {
        $this->ob_in = $value;
    }

    public function getOBIn() {
        return $this->ob_in;
    }

    public function setOBOut($value) {
        $this->ob_out = $value;
    }

    public function getOBOut() {
        return $this->ob_out;
    }

     public function setOBTotalHrs($value) {
        $this->ob_total_hrs = $value;
    }

    public function getOBTotalHrs() {
        return $this->ob_total_hrs;
    }

    //end ob timebased

    public function setTotalScheduleHours($value) {
        $this->total_schedule_hours = $value;
    }

    public function getTotalScheduleHours() {
        return $this->total_schedule_hours;
    }

    public function setTotalDeductibleBreaktimeHours($value) {
        $this->total_deductible_breaktime_hours = $value;
    }

    public function getTotalDeductibleBreaktimeHours() {
        return $this->total_deductible_breaktime_hours;
    }

    public function setTotalOvertimeHours($value) {
        $this->total_overtime_hours = $value;
    }

    public function getTotalOvertimeHours() {
        return $this->total_overtime_hours;
    }

    public function setOvertimeDateIn($value) {
        $this->overtime_date_in = $value;
    }

    public function getOvertimeDateIn() {
        return $this->overtime_date_in;
    }

    public function setOvertimeDateOut($value) {
        $this->overtime_date_out = $value;
    }

    public function getOvertimeDateOut() {
        return $this->overtime_date_out;
    }

    public function setScheduledDateIn($value) {
        $this->scheduled_date_in = $value;
    }

    public function getScheduledDateIn() {
        return $this->scheduled_date_in;
    }

    public function setScheduledDateOut($value) {
        $this->scheduled_date_out = $value;
    }

    public function getScheduledDateOut() {
        return $this->scheduled_date_out;
    }

    /*
    * Deprecated - Use setOvertimeNightShiftExcessHours()
    */
	public function setNightShiftOvertimeExcessHours($value) {
		$this->nightshift_overtime_excess_hours = $value;
	}

    /*
    * Deprecated - Use getOvertimeNightShiftExcessHours()
    */
	public function getNightShiftOvertimeExcessHours() {
		return $this->nightshift_overtime_excess_hours;
	}

	public function setOvertimeNightShiftExcessHours($value) {
		$this->nightshift_overtime_excess_hours = $value;
	}

	public function getOvertimeNightShiftExcessHours() {
		return $this->nightshift_overtime_excess_hours;
	}
	
	public function setOvertimeExcessHours($value) {
		$this->overtime_excess_hours = $value;	
	}
	
	public function getOvertimeExcessHours() {
		return $this->overtime_excess_hours;	
	}

	public function setRegularOvertimeHours($value) {
		$this->regular_overtime_hours = $value;
	}

	public function getRegularOvertimeHours() {
		return $this->regular_overtime_hours;
	}

	public function setRegularOvertimeExcessHours($value) {
		$this->regular_overtime_excess_hours = $value;
	}

	public function getRegularOvertimeExcessHours() {
		return $this->regular_overtime_excess_hours;
	}

	public function setRegularOvertimeNightShiftHours($value) {
		$this->regular_overtime_ns_hours = $value;
	}

	public function getRegularOvertimeNightShiftHours() {
		return $this->regular_overtime_ns_hours;
	}

	public function setRegularOvertimeNightShiftExcessHours($value) {
		$this->regular_overtime_ns_excess_hours = $value;
	}

	public function getRegularOvertimeNightShiftExcessHours() {
		return $this->regular_overtime_ns_excess_hours;
	}
    //new
    public function getUnderTimeHours(){
        return $this->undertime_hours;
    }
    public function getLateHours(){
        return $this->late_hours;
    }
    //new
	public function setRestDayOvertimeHours($value) {
		$this->restday_overtime_hours = $value;
	}

	public function getRestDayOvertimeHours() {
		return $this->restday_overtime_hours;
	}

	public function setRestDayOvertimeExcessHours($value) {
		$this->restday_overtime_excess_hours = $value;	
	}
	
	public function getRestDayOvertimeExcessHours() {
		return $this->restday_overtime_excess_hours;
	}
	
	public function setRestDayOvertimeNightShiftHours($value) {
		$this->restday_overtime_ns_hours = $value;	
	}

	public function getRestDayOvertimeNightShiftHours() {
		return $this->restday_overtime_ns_hours;	
	}
	
	public function setRestDayOvertimeNightShiftExcessHours($value) {
		$this->restday_overtime_ns_excess_hours = $value;
	}

	public function getRestDayOvertimeNightShiftExcessHours() {
		return $this->restday_overtime_ns_excess_hours;
	}

    public function setRestDayLegalOvertimeHours($value) {
        $this->restday_legal_overtime_hours = $value;
    }

    public function getRestDayLegalOvertimeHours() {
        return $this->restday_legal_overtime_hours;
    }

    public function setRestDayLegalOvertimeExcessHours($value) {
        $this->restday_legal_overtime_excess_hours = $value;
    }

    public function getRestDayLegalOvertimeExcessHours() {
        return $this->restday_legal_overtime_excess_hours;
    }

    public function setRestDayLegalOvertimeNightShiftHours($value) {
        $this->restday_legal_overtime_ns_hours = $value;
    }

    public function getRestDayLegalOvertimeNightShiftHours() {
        return $this->restday_legal_overtime_ns_hours;
    }

    public function setRestDayLegalOvertimeNightShiftExcessHours($value) {
        $this->restday_legal_overtime_ns_excess_hours = $value;
    }

    public function getRestDayLegalOvertimeNightShiftExcessHours() {
        return $this->restday_legal_overtime_ns_excess_hours;
    }

    public function setRestDaySpecialOvertimeHours($value) {
        $this->restday_special_overtime_hours = $value;
    }

    public function getRestDaySpecialOvertimeHours() {
        return $this->restday_special_overtime_hours;
    }

    public function setRestDaySpecialOvertimeExcessHours($value) {
        $this->restday_special_overtime_excess_hours = $value;
    }

    public function getRestDaySpecialOvertimeExcessHours() {
        return $this->restday_special_overtime_excess_hours;
    }

    public function setRestDaySpecialOvertimeNightShiftHours($value) {
        $this->restday_special_overtime_ns_hours = $value;
    }

    public function getRestDaySpecialOvertimeNightShiftHours() {
        return $this->restday_special_overtime_ns_hours;
    }

    public function setRestDaySpecialOvertimeNightShiftExcessHours($value) {
        $this->restday_special_overtime_ns_excess_hours = $value;
    }

    public function getRestDaySpecialOvertimeNightShiftExcessHours() {
        return $this->restday_special_overtime_ns_excess_hours;
    }

    public function setLegalOvertimeHours($value) {
        $this->legal_overtime_hours = $value;
    }

    public function getLegalOvertimeHours() {
        return $this->legal_overtime_hours;
    }

    public function setLegalOvertimeExcessHours($value) {
        $this->legal_overtime_excess_hours = $value;
    }

    public function getLegalOvertimeExcessHours() {
        return $this->legal_overtime_excess_hours;
    }

    public function setLegalOvertimeNightShiftHours($value) {
        $this->legal_overtime_ns_hours = $value;
    }

    public function getLegalOvertimeNightShiftHours() {
        return $this->legal_overtime_ns_hours;
    }

    public function setLegalOvertimeNightShiftExcessHours($value) {
        $this->legal_overtime_ns_excess_hours = $value;
    }

    public function getLegalOvertimeNightShiftExcessHours() {
        return $this->legal_overtime_ns_excess_hours;
    }

    public function setSpecialOvertimeHours($value) {
        $this->special_overtime_hours = $value;
    }

    public function getSpecialOvertimeHours() {
        return $this->special_overtime_hours;
    }

    public function setSpecialOvertimeExcessHours($value) {
        $this->special_overtime_excess_hours = $value;
    }

    public function getSpecialOvertimeExcessHours() {
        return $this->special_overtime_excess_hours;
    }

    public function setSpecialOvertimeNightShiftHours($value) {
        $this->special_overtime_ns_hours = $value;
    }

    public function getSpecialOvertimeNightShiftHours() {
        return $this->special_overtime_ns_hours;
    }

    public function setSpecialOvertimeNightShiftExcessHours($value) {
        $this->special_overtime_ns_excess_hours = $value;
    }

    public function getSpecialOvertimeNightShiftExcessHours() {
        return $this->special_overtime_ns_excess_hours;
    }
	
	public function computeTotalScheduledHours() {
		//return Tools::computeHoursDifference($this->scheduled_time_in, $this->scheduled_time_out);	
		return Tools::getHoursDifference($this->scheduled_time_in, $this->scheduled_time_out);	
	}
	
	public function computeTotalActualHours() {
		//return Tools::computeHoursDifference($this->getTimeIn(), $this->getTimeOut());	
		return Tools::getHoursDifference($this->getTimeIn(), $this->getTimeOut());
	}	

    /*
     * Total hrs worked base on schedule in
    */

    public function totalHrsWorkedBaseOnSchedule() {
        $date_in  = $this->date_in;
        $date_out = $this->date_out;
        $time_in  = $this->time_in;
        $time_out = $this->time_out;

        $schedule_date_in  = $this->scheduled_date_in;
        $schedule_date_out = $this->scheduled_date_out;
        $schedule_time_in  = $this->scheduled_time_in;
        $schedule_time_out = $this->scheduled_time_out;

        $actual_date_time_in  = "{$date_in} {$time_in}";
        $actual_date_time_out = "{$date_out} {$time_out}";

        $schedule_date_time_in  = "{$schedule_date_in} {$schedule_time_in}";
        $schedule_date_time_out = "{$schedule_date_out} {$schedule_time_out}";

        //Will only compute valid time in base on schedule
        if( strtotime($schedule_date_time_in) >= strtotime($actual_date_time_in) ){
            if( strtotime($actual_date_time_out) >= strtotime($schedule_date_time_out) ){                
                $hours_difference = $this->total_schedule_hours;             
            }else{
                $hours_difference = Tools::computeHoursDifferenceByDateTime($schedule_date_time_in, $actual_date_time_out);   
                if( $hours_difference >= $this->total_deductible_breaktime_hours ){
                    $hours_difference = $hours_difference - $this->total_deductible_breaktime_hours;
                }   
            }
        }else{
            if( strtotime($actual_date_time_out) >= strtotime($schedule_date_time_out) ){                
                $hours_difference = $this->total_schedule_hours;             
            }else{
                $hours_difference = Tools::computeHoursDifferenceByDateTime($actual_date_time_in, $actual_date_time_out); 
                if( $hours_difference >= $this->total_deductible_breaktime_hours ){
                    $hours_difference = $hours_difference - $this->total_deductible_breaktime_hours;
                }
            }                
        }

        //echo "Schedule In : {$actual_date_time_in} / Break hrs : {$this->total_deductible_breaktime_hours} <br />";
        //$hours_difference = Tools::computeHoursDifferenceByDateTime($actual_date_time_in, $actual_date_time_out);      

        return $hours_difference;
    }
}
?>