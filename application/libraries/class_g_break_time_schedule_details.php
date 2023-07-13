<?php
class G_Break_Time_Schedule_Details extends Break_Time_Schedule_Details {
	protected $applied_to_legal_holiday = 0;
	protected $applied_to_special_holiday = 0;
	protected $applied_to_restday = 0;
	protected $applied_to_regular_day = 0;

	const PREFIX_EMPLOYEE   = 'e';
	const PREFIX_DEPARTMENT = 'd';
	const PREFIX_ALL = 'a';

	const OBJ_ID_ALL   = 0;

	const YES = 1;
	const NO  = 0;

	public function __construct() {
		
	}

	public function validDayTypeOptions() {
		$options = array('regular_day' => 'Apply to regular day','rest_day' => 'Apply to rest day','legal_holiday' => 'Apply to legal holiday','special_holiday' => 'Apply to special holiday' );
		return $options;
	}

	public function setAppliedToLegalHoliday( $value = 0 ){
		$this->applied_to_legal_holiday = $value;
	}

	public function getAppliedToLegalHoliday() {
		return $this->applied_to_legal_holiday;
	}

	public function setAppliedToSpecialHoliday( $value = 0 ) {
		$this->applied_to_special_holiday = $value;
	}

	public function getAppliedToSpecialHoliday() {
		return $this->applied_to_special_holiday;
	}

	public function setAppliedToRestDay( $value = 0 ) {
		$this->applied_to_restday = $value;
	}

	public function getAppliedToRestDay() {
		return $this->applied_to_restday;
	}

	public function setAppliedToRegularDay( $value = 0 ) {
		$this->applied_to_regular_day = $value;
	}

	public function getAppliedToRegularDay() {
		return $this->applied_to_regular_day;
	}

	public function applyToSpecialHoliday() {
		$this->applied_to_special_holiday = self::YES;
	}

	public function applyToAllHoliday() {
		$this->applied_to_legal_holiday   = self::YES;
		$this->applied_to_special_holiday = self::YES;
	}

	public function applyToLegalHoliday() {
		$this->applied_to_legal_holiday = self::YES;
	}

	public function applyToRegularDay() {
		$this->applied_to_regular_day = self::YES;
	}

	public function applyToRestDay() {
		$this->applied_to_restday = self::YES;
	}

	public function getAllObjType() {
		$object_types = array(self::PREFIX_EMPLOYEE, self::PREFIX_DEPARTMENT, self::PREFIX_ALL);

		return $object_types;
	}

	private function checkIfNotEmpty( $obj_variables = array() ) {
		foreach( $obj_variables as $variable ){
			if( trim($this->{$variable}) == '' ){
				return false;
			}
		}

		return true;
	}

	public function getToDeductSelections() {
		$selections = array(self::YES => "Yes", self::NO => "No");

		return $selections;
	}

	public function deleteAllByHeaderId() {
		$return['is_success'] = false;
		$return['message']    = 'Record not found';

		if( $this->header_id > 0 ){
			G_Break_Time_Schedule_Details_Manager::deleteByHeaderId($this->header_id);

			$return['is_success'] = true;
			$return['message']    = 'Record(s) deleted';
		}

		return $return;
	}

	public function getObjDataByHeaderId(){
		$obj_data = array();
		if( $this->header_id > 0 ){
			$obj_data = G_Break_Time_Schedule_Details_Helper::sqlObjDataByHeaderId($this->header_id);
		}

		return $obj_data;
	}

	public function getObjBreakTimeByScheduleInOut( $schedule = array(), $day_type = array() ){
		$data = array();

		$required_variables = array('obj_type','obj_id');
		if( self::checkIfNotEmpty($required_variables) ){						
			$data = G_Break_Time_Schedule_Details_Helper::sqlObjBreakTimeByScheduleInOut($schedule, $this->obj_type, $this->obj_id, $day_type);
		}

		return $data;
	}

	public function getObjDeductibleBreakTimeByScheduleInOut( $schedule = array(), $day_type = array() ){
		$data = array();

		$required_variables = array('obj_type','obj_id');
		if( self::checkIfNotEmpty($required_variables) ){			
			$data = G_Break_Time_Schedule_Details_Helper::sqlObjDeductibleBreakTimeByScheduleInOut($schedule, $this->obj_type, $this->obj_id, $day_type);
		}

		return $data;
	}

	public function getObjDeductibleBreakTimeByScheduleInOutDateStart( $schedule = array(), $day_type = array() ){
		$data = array();

		$required_variables = array('obj_type','obj_id');
		if( self::checkIfNotEmpty($required_variables) ){			
			$data = G_Break_Time_Schedule_Details_Helper::sqlObjDeductibleBreakTimeByScheduleInOutDateStart($schedule, $this->obj_type, $this->obj_id, $day_type);
		}

		return $data;
	}
							
	public function save() {
		return G_Break_Time_Schedule_Details_Manager::save($this);
	}
}
?>