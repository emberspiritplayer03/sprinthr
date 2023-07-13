<?php
class Schedule_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		//$this->login();
		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');
		//Loader::appMainScript('jquerytimepicker/base.js');
		//Loader::appMainStyle('jquerytimepicker/base.css');		
				
		Loader::appMainScript('schedule_base.js');
		Loader::appMainScript('schedule.js');
			
		Loader::appMainUtilities();		
		Loader::appStyle('style.css');
		$this->var['schedule'] = 'selected';
		$this->var['page_title'] = '<a href="'. url('schedule') .'">Schedule</a>';
		
		Utilities::checkModulePackageAccess('attendance','payroll');
	}

	function index()
	{
		Utilities::verifyAccessRights($employee_id,$module,$action);
	
		Jquery::loadMainTextBoxList();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();
		$this->var['page_title'] = 'Schedule';
		$this->var['action'] = url('schedule/show_employee');
		
		$this->var['schedules'] = G_Schedule_Finder::findAll();		
		$this->view->setTemplate('template_schedule.php');
		$this->view->render('schedule/index.php',$this->var);		
	}
	
	function show_employee() {	
		Jquery::loadMainTextBoxList();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();
		
		Loader::appMainScript('restday_base.js');
		Loader::appMainScript('restday.js');
				
		$this->var['query'] = $query = $_GET['query'];
		$this->var['action'] = url('schedule/show_employee');
		if ($query != '') {
			$this->var['employees'] = G_Employee_Finder::searchActiveByFirstnameAndLastnameAndEmployeeCode($query);
		}
		$this->view->setTemplate('template.php');
		$this->view->render('schedule/show_employee.php',$this->var);		
	}
	
	function show_schedule() {
		Jquery::loadMainTextBoxList();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainTipsy();				
		
		$public_id = (string) $_GET['id'];
		$s = G_Schedule_Group_Finder::findByPublicId($public_id);
		if ($s) {
			$this->var['public_id'] = $public_id;
			$this->var['schedule_id'] = $s->getId();
			$this->var['schedule_name'] = $title = $s->getName();
			$this->var['title'] = '- '. $title;
			$schedules = G_Schedule_Finder::findAllByScheduleGroup($s);
			$this->var['schedule_date_time'] = G_Schedule_Helper::showSchedules($schedules);		
			$this->view->setTemplate('template.php');
			if ($s->isDefault()) {
				$this->view->render('schedule/show_schedule_default.php',$this->var);
			} else {
				$this->view->render('schedule/show_schedule.php',$this->var);	
			}
		} else {
			display_error();	
		}
	}
	
	function _create_schedule() { // not used
		$error = 0;
		if (empty($_POST['schedule_name'])) { $error++; }
		if (empty($_POST['working_days'])) { $error++; }
		
		if ($error > 0) {
			exit('Failed to create schedule');	
		}
		
		$schedule_name = $_POST['schedule_name'];
		$working_days = implode(',', $_POST['working_days']);
		$time_in = date('H:i:00', strtotime(Tools::addLeadingZero($_POST['time_in']['hh']) .':'. Tools::addLeadingZero($_POST['time_in']['mm']) .' '. $_POST['time_in']['am']));
		$time_out = date('H:i:00', strtotime(Tools::addLeadingZero($_POST['time_out']['hh']) .':'. Tools::addLeadingZero($_POST['time_out']['mm']) .' '. $_POST['time_out']['am']));
				
		$s = new G_Schedule;
		$s->setName($schedule_name);
		$s->setWorkingDays($working_days);
		$s->setTimeIn($time_in);
		$s->setTimeOut($time_out);
		$id = $s->save();
		
		if ($id) {
			$return['schedule_id'] = $id;
			$return['schedule_name'] = $schedule_name;
			$return['working_days'] = $working_days;
			$return['time_in'] = Tools::timeFormat($time_in);
			$return['time_out'] = Tools::timeFormat($time_out);
			$return['message'] = 'Schedule has been created. <a href="javascript:void(0)" onclick="assignSchedule('. $id .')">Add groups or employees</a>';
			$return['is_created'] = true;
		} else {
			$return['message'] = "There's an error occured. Schedule has not been created. Please contact the developer.";
			$return['is_created'] = false;	
		}
		echo json_encode($return);		
	}
	
	function _edit_schedule() {
		$error = 0;
		$schedule_id = (int) $_POST['schedule_id'];
		if (empty($_POST['name'])) { $error++; }
		if (empty($_POST['working_day'])) { $error++; }
		if (empty($schedule_id)) { $error++; }
		
		if ($error > 0) {
			exit('Failed to edit schedule');	
		}
		
		$schedule_name = $_POST['name'];
		$working_days = implode(',', $_POST['working_day']);
		$time_in = date('H:i:00', strtotime(Tools::addLeadingZero($_POST['time_in']['hh']) .':'. Tools::addLeadingZero($_POST['time_in']['mm']) .' '. $_POST['time_in']['am']));
		$time_out = date('H:i:00', strtotime(Tools::addLeadingZero($_POST['time_out']['hh']) .':'. Tools::addLeadingZero($_POST['time_out']['mm']) .' '. $_POST['time_out']['am']));
				
		$s = G_Schedule_Finder::findById($schedule_id);
		$s->setName($schedule_name);
		$s->setWorkingDays($working_days);
		$s->setTimeIn($time_in);
		$s->setTimeOut($time_out);
		$is_saved = $s->save();
		
		if ($is_saved) {
			$return['schedule_name'] = $schedule_name;
			$return['working_days'] = $working_days;
			$return['time_in'] = Tools::timeFormat($time_in);
			$return['time_out'] = Tools::timeFormat($time_out);
			$return['message'] = 'Schedule has been saved';
			$return['is_saved'] = true;
		} else {
			$return['message'] = "There's an error occured. Schedule has not been saved. Please contact the developer.";
			$return['is_saved'] = false;	
		}
		echo json_encode($return);
	}
	
	function _add_weekly_schedule() {
		$this->_edit_weekly_schedule();
	}
	
	function _edit_weekly_schedule() {
		$name = $_POST['name'];
		$public_id = $_POST['id'];
		$effectivity_date = $_POST['effectivity_date'];
		$is_changed = $_POST['is_changed'];
//		$schedule['time_in'] = array(
//			'mon' => '8:00 am', 
//			'tue' => '8:00 am', 
//			'wed' => '8:00 am', 
//			'thu' => '8:00 am', 
//			'fri' => '8:00 am', 
//			'sat' => '8:00 am', 
//			'sun' => '8:00 am'
//		);		
//		$schedule['time_out'] = array(
//			'mon' => '5:00 pm', 
//			'tue' => '5:00 pm', 
//			'wed' => '5:00 pm', 
//			'thu' => '5:00 pm', 
//			'fri' => '5:00 pm', 
//			'sat' => '6:00 pm', 
//			'sun' => '6:00 pm'
//		);
		//print_r($schedule);
		$schedule = $_POST;
		$merged_days = array();
		foreach ($schedule['time_in'] as $day => $schedule_time) {		
			if (strtotime($schedule_time)) {
				$schedule_time_in = $schedule_time;
				$schedule_time_out = $schedule['time_out'][$day];
				$merged_days[$schedule_time_in .'-'. $schedule_time_out][] = $day;
			}
		}
		if (count($merged_days) > 0) {
			$group = G_Schedule_Group_Finder::findByPublicId($public_id);
			if (!$group) {
				$group = new G_Schedule_Group;	
			}
			$group->setEffectivityDate($effectivity_date);
			$group->setName($name);			
			if ($group->getId() > 0) {
				$group_id = $group->getId();
				$group->save();				
			} else {
				$group_id = $group->save();
			}
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
				$d = G_Schedule_Finder::findByScheduleGroupAndTimeInAndOut($group, $time_in, $time_out);
				if (!$d) {
					$d = new G_Schedule;				
				}
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
			$all_schedules = G_Schedule_Finder::findAllByScheduleGroup($group);
			foreach ($all_schedules as $all_schedule) {
				$schedule_string .=  '<div>'. Tools::timeFormat($all_schedule->getTimeIn()) .' - '. Tools::timeFormat($all_schedule->getTimeOut()) .' - '. $all_schedule->getWorkingDays().' </div>';		
			}
			
			
			//  UPDATE ATTENDANCE
			if ($is_changed == 'yes') {
				$c = G_Cutoff_Period_Finder::findByDate($effectivity_date);
				$start_date = $effectivity_date;//$c->getStartDate();
				$end_date = $c->getEndDate();
							
				$employees = G_Employee_Finder::findByScheduleGroup($group);	
				foreach ($employees as $e) {
					if ($e) {
						G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $start_date, $end_date);	
					}
				}
			}

			$return['is_saved'] = true;
			$return['message'] = 'Schedule has been saved';
			$return['title_string'] = $group->getName();
			$return['schedule_string'] = $schedule_string;
			$return['schedule_group_id'] = $group_id;
		} else {
			$return['message'] = 'You have to add at least 1 schedule';
			$return['error_type'] = 'no_entry';
			$return['is_saved'] = false;
		}						
		echo json_encode($return);	
	}
	
	function _assign_group_schedule_to_employee() {
		$employee_id = $_POST['employee_id'];
		$group_id = (int) $_POST['schedule_group_id'];
		$e = G_Employee_Finder::findById($employee_id);
		$is_assigned = false;
		if ($e) {
			$group = G_Schedule_Group_Finder::findById($group_id);
			if ($group) {
				$new_id = $group->assignToEmployee($e, Tools::getGmtDate('Y-m-d'));
				if ($new_id > 0) {
					$is_assigned = true;	
				}
			}					
		}
		if (!$is_assigned) {
			$return['message'] = 'Schedule has not been assigned';	
		} else {
			$return['message'] = 'Schedule has been assigned';
		}
		$return['is_assigned'] = $is_assigned;
		echo json_encode($return);
	}
	
	function _assign_schedule() {
		$error = 0;
		if (strlen($_POST['groups_autocomplete']) > 0) {
			$groups = explode(',', $_POST['groups_autocomplete']);
		}
		if (strlen($_POST['employees_autocomplete']) > 0) {
			$employees = explode(',', $_POST['employees_autocomplete']);
		}		
		if (empty($groups) && empty($employees)) {
			$error++;
		}		
		if ($error > 0) {
			$return['message'] = 'Error occured.';
			$return['saved'] = false;
			echo json_encode($return);
		} else {
			$schedule_id = $_POST['schedule_id'];
			if (!empty($groups)) {
				foreach ($groups as $group_id) {
					$g = G_Group_Finder::findById($group_id);
					$s = G_Schedule_Group_Finder::findByPublicId($schedule_id);
					if (!G_Schedule_Helper::isGroupAlreadyAssigned($g, $s)) {
						$s->assignToGroup($g, $s->getEffectivityDate(), '');
					}
				}
			}
			if (!empty($employees)) {
				foreach ($employees as $employee_id) {
					$e = G_Employee_Finder::findById($employee_id);
					$s = G_Schedule_Group_Finder::findByPublicId($schedule_id);
					$effectivity_date = $s->getEffectivityDate();
					if (!G_Schedule_Helper::isEmployeeAlreadyAssigned($e, $s)) {
						$s->assignToEmployee($e, $s->getEffectivityDate(), '');					
					}
					
					// UPDATE ATTENDANCE
					$c = G_Cutoff_Period_Finder::findByDate($effectivity_date);
					$start_date = $effectivity_date;//$c->getStartDate();
					$end_date = $c->getEndDate();
					G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $start_date, $end_date);						
				}
			}
			if ($s) {
				$return['public_id'] = $s->getPublicId();	
			}
			$return['saved'] = true;
			echo json_encode($return);
		}			
	}
	
	function _remove_schedule_member() {
		$employee_group_id = (int) $_POST['employee_group_id'];
		$schedule_id = $_POST['schedule_id'];
		$employee_or_group = (string) $_POST['employee_or_group'];
		
		$is_removed = false;
		$s = G_Schedule_Group_Finder::findByPublicId($schedule_id);
		if ($s) {
			$effectivity_date = $s->getEffectivityDate();
			if ($employee_or_group == 'employee') {
				$e = Employee_Factory::get($employee_group_id);
				if ($e) {
					$is_removed = $s->removeEmployee($e);
					
					if ($is_removed) {						
						// UPDATE ATTENDANCE
						$c = G_Cutoff_Period_Finder::findByDate($effectivity_date);
						$start_date = $effectivity_date;//$c->getStartDate();
						$end_date = $c->getEndDate();
						G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $start_date, $end_date);						
					}					
				}
				
			} else if ($employee_or_group == 'group') {
				$g = G_Group_Finder::findById($employee_group_id);
				if ($g) {
					$is_removed = $s->removeGroup($g);
				}
			}
		}			
		$return['is_removed'] = $is_removed;
		if ($is_removed) {
			$return['message'] = 'Member has been removed';
		} else {
			$return['message'] = 'An error occured. Member has not been removed. Please contact the developer';
		}
		echo json_encode($return);
	}
	
	function _delete_specific_schedule() {
		$schedule_id = (int) $_POST['schedule_id'];		
		$s = G_Schedule_Specific_Finder::findById($schedule_id);
		if ($s) {
			$start_date = $s->getDateStart();
			$end_date = $s->getDateEnd();
			$employee_id = $s->getEmployeeId();
			if ($s->delete()) {
				$e = G_Employee_Finder::findById($employee_id);
				if ($e) {
					G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $start_date, $end_date);
				}
				$return['is_deleted'] = true;
				$return['message'] = 'Schedule has been deleted';
			} else {
				$return['is_deleted'] = false;
				$return['message'] = 'Schedule has not been deleted. Please contact the administrator';
			}
		} else {
			$return['is_deleted'] = false;
			$return['message'] = 'Schedule was not found.';
		}
		echo json_encode($return);
	}
	
	function _delete_schedule() {
		$schedule_id = $_POST['schedule_id'];		
		$is_deleted = false;
		$s = G_Schedule_Group_Finder::findByPublicId($schedule_id);
		if ($s) {
			$date = $s->getEffectivityDate();
			if ($s->isDefault()) {
				$return['message'] = "This is the default schedule. You can't delete the default schedule.";
				$is_deleted = false;
			} else {
				if ($s->countMembers() == 0) {
					$is_deleted = $s->delete(); // delete group
					$s->deleteSchedule(); // delete schedules under this group
					
					//  UPDATE ATTENDANCE
					$c = G_Cutoff_Period_Finder::findByDate($date);
					$start_date = $date;//$c->getStartDate();
					$end_date = $c->getEndDate();
								
					$employees = G_Employee_Finder::findByScheduleGroup($s);	
					foreach ($employees as $e) {
						if ($e) {
							G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $start_date, $end_date);	
						}
					}
											
				} else {
					$return['message'] = 'You have to remove first all groups and employees before you can delete this schedule';
					$is_deleted = false;
				}
			}
		} else {
			$return['message'] = 'An error occured. Schedule has not been deleted. Please contact the developer';	
		}
		$return['is_deleted'] = $is_deleted;
		if ($is_deleted) {
			$return['message'] = 'Schedule has been deleted';
		}
		echo json_encode($return);
	}
	
	function _count_members() {
		$schedule_id = $_POST['schedule_id'];
		$s = G_Schedule_Group_Finder::findByPublicId($schedule_id);
		//$s = G_Schedule_Group_Finder::findById($schedule_id);
		if ($s) {
			$count = $s->countMembers();	
		}
		$return['count'] = $count;
		echo json_encode($return);	
	}
	
	function _import_employees_in_schedule() {
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		
		$sg = G_Schedule_Group_Finder::findByPublicId($_POST['public_id']);
		$is_imported = false;
		if ($sg) {
			$effectivity_date = $sg->getEffectivityDate();
			$schedule_group_id = $sg->getId();
			$file = $_FILES['import_employees']['tmp_name'];
			//$file = BASE_PATH . 'files/sample_import_files/import_schedule_by_employees.xlsx';
			$g = new G_Schedule_Import_Employees($file);	
			$g->setEffectivityDate($effectivity_date);	
			$is_true = $g->import($sg);
			if ($is_true) {
				$is_imported = true;	
			}
			
			$es = $g->getEmployees();
			$c = G_Cutoff_Period_Finder::findByDate($effectivity_date);
			$start_date = $effectivity_date;//$c->getStartDate();
			$end_date = $c->getEndDate();
			
			foreach ($es as $employee_id) {
				$e = G_Employee_Finder::findById($employee_id);
				if ($e) {
					G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $start_date, $end_date);	
				}				
			}			
		}
		
		if ($is_imported) {
			$return['message'] = 'Employees have been imported';	
		} else {
			$return['message'] = 'There was an error while importing. Please contact the administrator';	
		}
		$return['is_imported'] = $is_imported;
		echo json_encode($return);
	}
	
	function _import_schedule() {
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		$date_start = $_POST['date_start'];
		
		if (!strtotime($date_start)) {
			$date_start = date('Y-m-d');	
		}		
		$file = $_FILES['import_schedule_file']['tmp_name'];

		$g = new G_Schedule_Import_Weekly($file);	
		$g->setEffectivityDate($date_start);
			
		if ($g->import()) {
			$es = $g->getEmployees();
			$c = G_Cutoff_Period_Finder::findByDate($date_start);
			$start_date = $date_start;//$c->getStartDate();
			$end_date = $c->getEndDate();
			
			foreach ($es as $employee_code) {
				$e = G_Employee_Finder::findByEmployeeCode($employee_code);
				if ($e) {
					G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $start_date, $end_date);	
				}				
			}
			
			$return['message'] = 'Schedule has been imported';
			$return['is_imported'] = true;
		} else {
			$return['message'] = 'There was an error while importing. Please contact the administrator';
			$return['is_imported'] = false;
		}
		echo json_encode($return);
	}
	
	function _import_schedule_specific() {
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		
		$file = $_FILES['import_schedule_specific_file']['tmp_name'];
		//$file = BASE_PATH . 'files/sample_import_files/import_schedule_weekly.xlsx';
		
		$g = new G_Schedule_Import_Dates($file);	
		if ($g->import()) {
			$return['message'] = 'Schedule has been imported';
			$return['is_imported'] = true;
		} else {
			$return['message'] = 'There was an error while importing. Please contact the administrator';
			$return['is_imported'] = false;
		}
		echo json_encode($return);
	}
	
	function _add_specific_schedule() {
		$employee_id = (int) $_POST['employee_id'];
		$start_date = $_POST['schedule_date'];
		$end_date = $_POST['schedule_end_date'];
		$time_in = $_POST['schedule_time_in'];
		$time_out = $_POST['schedule_time_out'];
		
		if (Tools::isValidDate($start_date) && Tools::isValidTime($time_in) && Tools::isValidTime($time_out)) {
			$start_date = date('Y-m-d', strtotime($start_date));
			$time_in = date('H:i:s', strtotime($time_in));
			$time_out = date('H:i:s', strtotime($time_out));
			
			$e = G_Employee_Finder::findById($employee_id);
			if ($e) {
				if (Tools::isValidDate($end_date)) {
					$end_date = date('Y-m-d', strtotime($end_date));
				} else {
					$end_date = $start_date;	
				}
				
				if (strtotime($end_date) >= strtotime($start_date)) {																	
					$s = G_Schedule_Specific_Finder::findByEmployeeAndStartAndEndDate($e, $start_date, $end_date);
					if (!$s) {
						$s = new G_Schedule_Specific;
					}				
					$s->setDateStart($start_date);
					$s->setDateEnd($end_date);
					$s->setTimeIn($time_in);
					$s->setTimeOut($time_out);
					$s->setEmployeeId($e->getId());
					$is_saved = $s->save();
					
					if ($is_saved) {
						G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $start_date, $end_date);
						$return['is_saved'] = true;
						$return['message'] = 'Schedule has been saved';												
					} else {
						$return['is_saved'] = false;
						$return['message'] = 'There was a problem saving the schedule. Please contact the administrator';	
					}					
				} else {
					$return['is_saved'] = false;
					$return['message'] = 'Start Date must not greater than End Date';
				}				
			} else {
				$return['is_saved'] = false;
				$return['message'] = 'Employee was not found';
			}
		} else {
			$return['is_saved'] = false;
			$return['message'] = 'Schedule has not been saved. Invalid time or date format.';
		}
		
		echo json_encode($return);	
	}
	
	function ajax_show_schedule_list() {
		$s = G_Schedule_Finder::findAll();
		$this->var['schedules'] = G_Schedule_Helper::mergeByName($s);
		$this->view->noTemplate();
		$this->view->render('schedule/ajax_schedule_list.php',$this->var);
	}
	
	function ajax_show_weekly_schedule_list() {
		//$s = G_Schedule_Finder::findAll();
		//$this->var['schedules'] = G_Schedule_Helper::mergeByName($s);
		$this->var['schedule_groups'] = $s = G_Schedule_Group_Finder::findAll();
		$this->view->noTemplate();
		$this->view->render('schedule/ajax_weekly_schedule_list.php',$this->var);
	}	
	
	function ajax_show_schedule_members_list() {
		$id = $_GET['schedule_id'];
		$g = G_Schedule_Group_Finder::findByPublicId($id);
		$this->var['schedule_id'] = $id;//$g->getId();
		$this->var['employees'] = G_Employee_Finder::findByScheduleGroup($g);
		$this->var['groups'] = G_Group_Finder::findByScheduleGroup($g);
				
		$this->view->noTemplate();
		$this->view->render('schedule/ajax_schedule_members_list.php',$this->var);
	}
	
	function ajax_assign_schedule_groups() {
		$this->var['schedule_id'] = $_GET['schedule_id'];
		$this->view->noTemplate();
		$this->view->render('schedule/forms/ajax_assign_schedule_groups_form.php',$this->var);
	}
	
	function ajax_assign_schedule_employees() {
		$this->var['schedule_id'] = $_GET['schedule_id'];
		$this->view->noTemplate();
		$this->view->render('schedule/forms/ajax_assign_schedule_employees_form.php',$this->var);
	}
	
	function ajax_assign_schedule_groups_employees() {
		$this->var['schedule_id'] = (int) $_GET['schedule_id'];
		$this->view->noTemplate();
		$this->view->render('schedule/forms/ajax_assign_schedule_groups_employees_form.php',$this->var);
	}
	
	function ajax_edit_schedule_form() {
		$this->var['action'] = url('schedule/_edit_schedule');
		$this->var['schedule_id'] = $id = (int) $_GET['schedule_id'];
		$this->var['schedule'] = $s = G_Schedule_Finder::findById($id);		
		$this->view->noTemplate();
		$this->view->render('schedule/forms/ajax_edit_schedule_form.php',$this->var);
	}
	
	function ajax_edit_weekly_schedule_form() {
		$this->var['action'] = url('schedule/_edit_weekly_schedule');
		$this->var['public_id'] = $public_id = $_GET['public_id'];
		$group = G_Schedule_Group_Finder::findByPublicId($public_id);
		$this->var['group_name'] = $group->getName();
		$this->var['is_default'] = $group->isDefault();
		$effect_date = $group->getEffectivityDate();
		if (!strtotime($effect_date)) {
			$effect_date = date('Y-m-d');	
		}
		$this->var['effectivity_date'] = $effect_date;
		$this->var['schedules'] = G_Schedule_Finder::findAllByScheduleGroup($group);
		$this->view->noTemplate();
		$this->view->render('schedule/forms/ajax_edit_weekly_schedule_form.php',$this->var);
	}
	
	function ajax_add_weekly_schedule_form() {
		$this->var['action'] = url('schedule/_add_weekly_schedule');
		$this->view->noTemplate();
		$this->view->render('schedule/forms/ajax_add_weekly_schedule_form.php',$this->var);
	}
	
	function ajax_import_employees_in_schedule() {
		$this->var['action'] = url('schedule/_import_employees_in_schedule');
		$this->var['public_id'] = $_GET['public_id'];
		$this->view->render('schedule/ajax_import_employees_in_schedule.php', $this->var);	
	}
	
	function ajax_import_schedule() {
		$this->var['action'] = url('schedule/_import_schedule');
		$this->view->render('schedule/forms/ajax_import_schedule.php', $this->var);	
	}
	
	function ajax_import_schedule_specific() {
		$this->var['action'] = url('schedule/_import_schedule_specific');
		$this->view->render('schedule/forms/ajax_import_schedule_specific.php', $this->var);	
	}
	
	function ajax_add_specific_schedule() {
		$this->var['action'] = url('schedule/_add_specific_schedule');
		$this->var['employee_id'] = $_GET['employee_id'];
		$this->view->render('schedule/forms/ajax_add_specific_schedule_form.php', $this->var);
	}
	
	function ajax_add_restday_schedule() {
		$this->var['action'] = url('schedule/_add_restday_schedule');
		$this->var['employee_id'] = $_GET['employee_id'];
		$this->view->render('schedule/forms/ajax_add_restday_schedule_form.php', $this->var);
	}	
	
	function html_show_import_format() {
		$this->view->setTemplate('template_blank.php');
		$this->view->render('schedule/html/html_show_import_format.php', $this->var);	
	}
	
	function html_import_changed_schedule() {
		$this->view->setTemplate('template_blank.php');
		$this->view->render('schedule/html/html_import_changed_schedule.php', $this->var);	
	}	
	
	
}
?>