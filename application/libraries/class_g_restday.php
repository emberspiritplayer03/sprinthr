<?php
/*
	Usage:
	$e = G_Employee_Finder::findByEmployeeCode(2007);
	$o = new G_Restday;
	$o->setDate('2012-09-10');
	$o->setTimeIn('18:00:00');
	$o->setTimeOut('23:00:00');
	$o->setEmployeeId($e->getId());
	$o->save();
*/
class G_Restday extends Restday {
	protected $id;
	protected $employee_id;
	protected $reason;
	public $a_rest_day = array();
	protected $rd_objects;
	
	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = $value;	
	}
	
	public function getId() {
		return $this->id;	
	}
	
	public function setReason($value) {
		$this->reason = $value;	
	}
	
	public function getReason() {
		return $this->reason;	
	}		
	
	public function setEmployeeId($value) {
		$this->employee_id = $value;	
		return $this;
	}
	
	public function getEmployeeId() {
		return $this->employee_id;	
	}
	
	public function checkWeekNumberIfWithRestDayByEmployeeNumber($date,$e) {
		$date = date("Y-m-d",strtotime($date));		
		$wkc	= (int) date("W",strtotime($date));		
		$c  = G_Restday_Helper::countRestDayByEmployeeAndWeekNumber($e,$wkc);
			
		//$rds = G_Restday_Helper::getEmployeeRestDayByWeek($e,$wkc);
		//print_r($rds);
		
		return $c;		
	}

	public function deleteByDateAndEmployeeId() {
		$is_success = false;

		if( !empty( $this->date ) && $this->employee_id > 0 ){
			G_Restday_Manager::deleteByDateAndEmployeeId($this->date, $this->employee_id);
		}

		return $is_success;
	}

	public function getAllDefaultRestDay() {
		$fields   = array("date");
		$default_group_id = G_Company_Structure::PARENT_ID;
		$data     		  = G_Group_Restday_Helper::sqlGetRestDayByGroupId($default_group_id, $fields);		
		$this->a_rest_day = $data;

		return $this;
	}

	public function convertArrayToObject() {
		if( $this->employee_id > 0 && !empty($this->a_rest_day) ){
			$a_rest_days 	  = $this->a_rest_day;			
			$this->a_rest_day = array();
			$o_data      	  = array();
			foreach( $a_rest_days as $value ){
				$s_date     = date("Y-m-d",strtotime($value['date']));									
				$is_date_exists = G_Restday_Helper::countRestDayByEmployeeIdAndDate($this->employee_id, $s_date);		
				if( !$is_date_exists ){		
					$rd = new G_Restday();
					$rd->setDate($s_date);
					$rd->setEmployeeId($this->employee_id);							
					$o_data[]   = $rd;	
				}		
							
			}

			$this->rd_objects = $o_data;			
		}

		return $this;
	}
	
	public function saveDefaultRestDays() {
		$return['is_success'] = false;
		$return['message']    = 'No data to save';
		if( !empty($this->rd_objects) ){
			G_Restday_Manager::saveMultiple($this->rd_objects);	
			$return['is_success'] = true;
			$return['message']    = 'Default restday was successfully copied to this schedule';
		}

		return $return;
	}

	public function removeFromRestDay() {
		if( $this->id > 0 ){
			G_Restday_Manager::delete($this);
		}
	}
	
	public function save() {
		return G_Restday_Manager::save($this);	
	}

	public function delete() {
		return G_Restday_Manager::delete($this);	
	}
}
?>