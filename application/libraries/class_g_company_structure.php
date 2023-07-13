<?php
class G_Company_Structure {
	public $id;
	public $company_branch_id;
	public $title;	
	public $description;
	public $type;
	public $parent_id;
	public $is_archive = self::NO;
	
	const YES = 'Yes';
	const NO  = 'No';
	const PARENT_ID = 1;

	const BRANCH 		= 'Branch';
	const DEPARTMENT 	= 'Department';
	const GROUP 		= 'Group';
	const TEAM 			= 'Team';
	const SECTION		= 'Section';
		
	public function __construct($id) {
		$this->id = $id;
	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setCompanyBranchId($value) {
		$this->company_branch_id = $value;
	}
	
	public function getCompanyBranchId() {
		return $this->company_branch_id;
	}
	
	public function setTitle($value) {
		$this->title = $value;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function setIsArchive($value) {
		$this->is_archive = $value;
	}
	
	public function getIsArchive() {
		return $this->is_archive;
	}
	
	public function setDescription($value) {
		$this->description = $value;
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	public function setType($value) {
		$this->type = $value;
	}
	
	public function getType() {
		return $this->type;
	}
	
	public function getParentId() {
		return $this->parent_id;
	}
	
	public function setParentId($value) {
		$this->parent_id = $value;
	}

	public function getAllSections() {
		$sections  = array();
		$fields    = array("title");
		$order_by  = "ORDER BY title ASC";
		$sections = G_Company_Structure_Helper::sqlGetAllSections($fields, $order_by);
		return $sections;
	}

	public function getAllDepartmentSections() {
		$sections = array();

		if( $this->id > 0 ){
			$fields    = array("title");
			$order_by  = "ORDER BY title ASC";
			$sections = G_Company_Structure_Helper::sqlGetAllDepartmentSections($this->id, $fields, $order_by);
		}
		
		return $sections;
	}

	public function getDepartmentDetailsById($fields = array()) {
		$data = array();
		if( $this->id > 0 ){
			$data = G_Company_Structure_Helper::sqlDataById($this->id, $fields);
		}
		return $data;
	}

	public function getDepartmentDetailsByTitle($fields = array()) {
		$data = array();
		if( $this->title != '' ){
			$data = G_Company_Structure_Helper::sqlCompanyStructureDataByTitle($this->title, $fields);
		}
		return $data;
	}

	public function getAllIsNotArchiveDepartments($fields = array(), $order_by = '') {
		$data = G_Company_Structure_Helper::sqlAllIsNotArchiveDepartments($fields, $order_by);
		return $data;
	}

	public function getAllIsNotArchiveDepartmentSections($fields = array(), $order_by = ''){
		if( $this->parent_id > 0 ){  
			$data = G_Company_Structure_Helper::sqlAllIsNotArchiveDepartmentSections($this->parent_id , $fields, $order_by);
		}
		return $data;
	}

	/*
		Usage : 
		$group_id = 9;
		$month = 02;
        $day   = 06;
        $year  = 2015;
        $date  = date('Y-m-d', mktime(0, 0, 0, $month, $day, $year));
		$c = G_Company_Structure_Finder::findById($group_id);
		if( $c ){
			$data = $c->addRestDay($date); //returns array
		}
	*/

	public function addRestDay( $date = '' ){
		$return['is_success'] = false;
		$return['message']    = 'Rest Day has not been added.';

		if( $this->id > 0 && $date != '' ){
			$date = date("Y-m-d",strtotime($date));
			$is_date_group_id_exists = G_Group_Restday_Helper::sqlIsDateAndGroupIdExists( $date, $this->id );	
			if( $is_date_group_id_exists ){
				$return['message'] = "Selected date is already set as restday";
			}else{
				$rd = new G_Group_Restday();
				$rd->setGroupId($this->id);
				$rd->setDate($date);
				$rd->save();

				//Fetch all employees under object id and has no restday on the given date
				$employees = G_Employee_Finder::findAllEmployeesWithNoRestDayByDateAndDepartmentSectionId($date, $this->id);				
				foreach( $employees as $e ){				
					$o = new G_Restday;
		    		$o->setDate($date);
		    		$o->setEmployeeId($e->getId());
		            $is_added = $o->save(); //Add to employee restday
		            if( $is_added ){
						G_Attendance_Helper::updateAttendance($e, $date); //Update Attendance	
					}
				}

				$return['message'] = "Rest Day has been successfully added";
			}

			$return['is_success'] = true;
		}

		return $return;
	}

	public function deleteRestDay( $date = '' ) {
		$return['is_success'] = false;
		$return['message']    = 'Rest Day has not been deleted. Please contact the administrator';

		if( $this->id > 0 && $date != '' ){
			$date = date("Y-m-d",strtotime($date));
			$grd  = G_Group_Restday_Finder::findByGroupIdAndDate($this->id, $date);
			if( $grd ){
				$employees = G_Employee_Finder::findAllEmployeesByDepartmentSectionId($this->id);	

				foreach( $employees as $e ){
					$o = new G_Restday();
					$o->setDate($date);
					$o->setEmployeeId($e->getId());
					$is_deleted = $o->deleteByDateAndEmployeeId();

					if( $is_deleted ){
						G_Attendance_Helper::updateAttendance($e, $date); //Update Attendance	
					}
				}	

				$grd->delete();

				$return['is_success'] = true;
				$return['message']    = 'Rest Day has been deleted';			
			}			
		}

		return $return;
	}

	/*
		Usage:
		$data  = array(
			'geid' => 'zyiG86Ya90sOgfDl6iM8YH6zT2IEld3H9dnzaxkeESc',
		    'token' => '4fc72d0822d790f9b2c3406cbdcc0685',
		    'schedule' => Array(
			    'name' => 'TEST 123',		    
			    'start_date' => '2015-02-03',
			    'end_date' => '2015-03-31',
			    'time_in' => Array
			        (
			            'mon' => '8:00 am',
			            'tue' => '8:00 am',
			            'wed' => '8:00 am',
			            'thu' => '8:00 am',
			            'fri' => '8:00 am',
			            'sat' => '8:00 am',
			            'sun' => ''
			        ),
			    'time_out' => Array
			        (
			            'mon' => '5:00 pm',
			            'tue' => '5:00 pm',
			            'wed' => '5:00 pm',
			            'thu' => '5:00 pm',
			            'fri' => '5:00 pm',
			            'sat' => '5:00 pm',
			            'sun' => ''
			        )
			)
		);
		$eid   = $data['geid'];
		$id    = Utilities::decrypt($eid);
		$group = G_Company_Structure_Finder::findById($id);
		$schedule = $data['schedule'];
		if( $group ){
			$return = $group->addSchedule($schedule); // returns array
		}

	*/

	function addSchedule( $schedule = array() ){
		$return['is_success'] = false;
		$return['message']    = 'Invalid form entries';

 		if( !empty($schedule) ){
 			$name             = $schedule['name'];			
			$effectivity_date = $schedule['start_date'];
			$end_date         = $schedule['end_date'];
 			$merged_days      = array();

			foreach ($schedule['time_in'] as $day => $schedule_time) {		
				if (strtotime($schedule_time)) {
					$schedule_time_in = $schedule_time;
					$schedule_time_out = $schedule['time_out'][$day];
					$merged_days[$schedule_time_in .'-'. $schedule_time_out][] = $day;
				}
			}
			if (count($merged_days) > 0) {
				$group = new G_Schedule_Group;	
				$group->setEffectivityDate($effectivity_date);
				$group->setEndDate($end_date);
				$group->setName($name);						
				$group_id = $group->save();

				$group = G_Schedule_Group_Finder::findById($group_id);
				
				G_Schedule_Group_Helper::updateEmployeeStartAndEndDate($group, $effectivity_date);
							
				$s = G_Schedule_Finder::findAllByScheduleGroup($group);
				foreach ($s as $ss) {
					$old_time[] = $ss->getTimeIn() .'-'. $ss->getTimeOut();	
				}
				foreach ($merged_days as $time => $days) {
					list($time_in, $time_out) = explode('-', $time);
					$day = implode(',', $days);
					$time_in = date('H:i:s', strtotime($time_in));
					$time_out = date('H:i:s', strtotime($time_out));			
					$updated_time[] = $time_in .'-'. $time_out;
					
					$d = new G_Schedule;
					$d->setName($name);
					$d->setWorkingDays($day);
					$d->setTimeIn($time_in);					
					$d->setTimeOut($time_out);
					if ($d->getId() > 0) {
						$schedule_id = $d->getId();
						$d->save();				
					} else {
						$schedule_id = $d->save();
					}

					$sched = G_Schedule_Finder::findById($schedule_id);
					$sched->saveToScheduleGroup($group);
				}

				$to_be_deleted = array_diff($old_time, $updated_time);
				foreach ($to_be_deleted as $to_delete) {
					list($time_in, $time_out) = explode('-', $to_delete);
					$d = G_Schedule_Finder::findByScheduleGroupAndTimeInAndOut($group, $time_in, $time_out);
					if ($d) {
						$d->deleteSchedule();	
					}
				}

				//Add group to schedule
				$g = G_Group_Finder::findById($this->id);
				$s = $group;

				if (!G_Schedule_Helper::isGroupAlreadyAssigned($g, $s)) {
				    $effectivity_date = $s->getEffectivityDate();
					$s->assignToGroup($g, $effectivity_date, '');

    			    $c = G_Cutoff_Period_Finder::findByDate($effectivity_date);
    			    if ($c) {
    			        $start_date = $effectivity_date;
    				    $end_date = $c->getEndDate();

                        $es = G_Employee_Finder::findAllByGroup($g);
                        G_Attendance_Helper::updateAttendanceByEmployeesAndPeriod($es, $start_date, $end_date);
                    }
				}

				$return['is_success'] = true;
				$return['message']    = 'Schedule has been saved';
			}
		}

		return $return;
	}

	public function removeSchedule( $schedule_id = 0 ) {
		$return['is_success'] = false;
		$return['message']    = 'Record not found';

		$s = G_Schedule_Group_Finder::findById($schedule_id);

		if( $s && $this->id > 0 ){			
			$effectivity_date = $s->getEffectivityDate();
			$c = G_Cutoff_Period_Finder::findByDate($effectivity_date);
			$is_removed = $s->removeGroup($this);
			if ($is_removed && $c) {
		        $start_date = $effectivity_date;
			    $end_date   = $c->getEndDate();

                $es = G_Employee_Finder::findAllByGroup($this);
                G_Attendance_Helper::updateAttendanceByEmployeesAndPeriod($es, $start_date, $end_date);
            }

            $return['is_success'] = true;
			$return['message']    = 'Schedule was successfully removed from the group';
		}

		return $return;
	}

	public function getSchedules(){
		$data = array();
		$group_details   = array();
		$group_schedules = array();

		if( $this->id > 0 ){
			$fields = array("id","title","type","parent_id");
			$group_details = G_Company_Structure_Helper::sqlDataById( $this->id, $fields );		
			$group_details = Tools::encryptArrayIndexValue("id",$group_details);
			if( !empty($group_details) ){
				$group_schedules = G_Employee_Group_Schedule_Helper::sqlGroupSchedulesByEmployeeGroupId( $this->id, $fields );
				$group_schedules = Tools::encryptMulitDimeArrayIndexValue("group_schedule_id",$group_schedules);
				$group_schedules = Tools::encryptMulitDimeArrayIndexValue("schedule_id",$group_schedules);
				$group_schedules = Tools::encryptMulitDimeArrayIndexValue("schedule_group_id",$group_schedules);

				//Breaktime
				foreach($group_schedules as $key => $schedule){
					$fields         = array("id","breaktime");
					$schedule_in    = $schedule['time_in'];
					$schedule_out   = $schedule['time_out'];					

					$breakschedules = '';
					$new_breaktime  = array();
					$breaktime      = G_Break_Time_Schedule_Details_Helper::sqlGetAllGroupBreaktimeSchedulesByGroupIdAndScheduleInAndScheduleOut($this->id, $schedule_in, $schedule_out);	

					if( !empty($break_time) ){
						foreach( $breaktime as $br ){
							$new_breaktime[] = $br['break_in'] . " to " . $br['break_out'];
						}
						if( !empty($new_breaktime) ){
							$breakschedules = implode("<br />", $new_breaktime);
						}
					}else{
						$breakschedules = "No breaktime schedule set";
					}
					
					$group_schedules[$key]['breaktime'] = $breakschedules;

				}
				
			} 	
			
			$data['group_details'] = $group_details;
			$data['schedules']     = $group_schedules;
		}

		return $data;		
	}
			
	public function save () {
		return G_Company_Structure_Manager::save($this);
	}
	
	public function delete() {
		return G_Company_Structure_Manager::delete($this);
	}
	
	public function archive() {
		return G_Company_Structure_Manager::archive($this);
	}
	
	public function directArchive() {
		return G_Company_Structure_Manager::directArchive($this);
	}
	
	public function restore() {
		return G_Company_Structure_Manager::restore($this);
	}
	
	public function addEmployee(G_Employee $e) {
		G_Company_Structure_Manager::addEmployee($this,$e);	
	}
	
	public function addEmployeeToSubdivision(G_Employee $e,$start_date,$end_date = '') {
        return G_Company_Structure_Helper::addEmployeeToSubdivision($this,$e,$start_date,$end_date);
	}

    /*
     * Usage:
        $cs = G_Company_Structure_Finder::findByMainParent();
        $cs->hireEmployee('2014-020', 'Harney', 'Cercado', 'Manaloto', '1985-11-01', 'Male', 'Married',
                2, '2014-01-01', 'Marketing', 'Website Designer', 'Regular', 350, 'Daily',
                'SSS123', 'TIN123', 'HDM123', 'PHIC123', 'Jr.', 'Harn');
     *
     * @return obj Instance of G_Employee
     */
    public function hireEmployee($employee_code, $firstname, $lastname, $middlename, $birthdate, $gender, $marital_status, $number_of_dependent,
                                  $hired_date, $department_name, $position, $employment_status, $salary, $salary_type,$frequency_id,
                                  $sss_number = '', $tin_number = '', $pagibig_number = '', $philhealth_number = '',
                                  $extension_name = '', $nickname = '', $section = '', $is_confidential = 0, $week_working_days = '', $year_working_days = 0, $nationality = '', $employee_status = 1, $cost_center, $project_site ) {
    	
        return G_Employee_Helper::hireEmployee($employee_code, $firstname, $lastname, $middlename, $birthdate, $gender, $marital_status,
            $number_of_dependents, $hired_date, $department_name, $position, $employment_status, $salary, $salary_type, $frequency_id,
            $sss_number, $tin_number, $pagibig_number, $philhealth_number, $extension_name, $nickname, $section, $is_confidential, $week_working_days, $year_working_days, $nationality, $employee_status, $cost_center, $project_site);
    }

    public function getEmployeeByCode($code) {
        return G_Employee_Finder::findByEmployeeCode($code);
    }

    public function getEmployeeById($id) {
        return G_Employee_Finder::findById($id);
    }
}
?>