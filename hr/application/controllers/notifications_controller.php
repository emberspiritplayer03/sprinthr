<?php
class Notifications_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		$this->sprintHdrMenu(G_Sprint_Modules::HR, 'reports');


		$this->login();
		Loader::appStyle('style.css');
		$this->company_structure_id = $_SESSION['sprint_hr']['company_structure_id'];
		$this->c_date  				= Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila');
		$this->validatePermission(G_Sprint_Modules::HR,'reports','reports_notifications');
		$this->var['is_enable_popup_notification'] = false;
	}

	function index()
	{
		$this->var['page_title'] = 'Notification';
		$this->view->setTemplate('template_notifications.php');
		$this->view->render('notifications/index.php',$this->var);    
	}

	function view_notification() {

		Jquery::loadMainJTags();
		//Yui::loadMainDatatable();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTextBoxList();

		if(!empty($_GET['hash']) && !empty($_GET['eid'])){

			$eid = $_GET['eid'];
			$hid = $_GET['hash'];
			
			Utilities::verifyHash(Utilities::decrypt($eid),$hid);				
			$id = Utilities::decrypt($eid);						
			$n  = G_Notifications_Finder::findById($id);

			if($n){
				// Update status to SEEN
				if($n->getStatus() == G_Notifications::STATUS_NEW) {					
					$n->setStatus(G_Notifications::STATUS_SEEN);
					$n->save();
				}

				if($n->getEventType() == 'Update Attendance') {
					redirect("attendance");
				}
				
				Jquery::loadMainJqueryDatatable();

				$this->var['from'] = $_GET['from'];
				$this->var['to']   = $_GET['to'];

				$this->var['n'] = $n;
				$this->var['page_title'] = 'Notification';
				$this->view->setTemplate('template_notifications.php');
				$this->view->render('notifications/view_notification.php',$this->var);  
			}else{
				redirect("notifications");
			}
		}else{
			redirect("notifications");
		}
	}

	function view_payroll_notification() {
		if(!empty($_GET['hash']) && !empty($_GET['eid'])){

			$eid = $_GET['eid'];
			$hid = $_GET['hash'];
			
			Utilities::verifyHash(Utilities::decrypt($eid),$hid);				
			$id = Utilities::decrypt($eid);						
			$n  = G_Notifications_Finder::findById($id);

			if($n){
				// Update status to SEEN
				if($n->getStatus() == G_Notifications::STATUS_NEW) {					
					$n->setStatus(G_Notifications::STATUS_SEEN);
					$n->save();
				}

				if($n->getEventType() == 'Update Attendance') {
					redirect("attendance");
				}
				
				Jquery::loadMainJqueryDatatable();

				$this->var['n'] = $n;
				$this->var['page_title'] = 'Notification';
				//$this->view->setTemplate('template_notifications.php');
				$this->view->setTemplate('payroll/template.php');
				$this->view->render('notifications/view_notification.php',$this->var);  
			}else{
				redirect("payroll_register/generation");
			}
		}else{
			redirect("payroll_register/generation");
		}		
	}

	function ini_user_modal() {
		//Cutoff Period
		$cp = new G_Cutoff_Period();
		$cutoff_periods = $cp->getAllCutOffPeriods();
		$this->var['cutoff_periods'] = $cutoff_periods;

		$this->view->noTemplate();
		$this->view->render('notifications/_ajax_ini_notifications.php',$this->var);  
	}

	function generate_excel() {
		if( !empty($_GET['eid'])){
			$id = Utilities::decrypt($_GET['eid']);			
		
			$n = G_Notifications_Finder::findById($id);
			if($n){				
				$filename = strtolower($n->getEventType());
				$filename = str_replace(' ','_',$filename);
				$excel_filename = "$filename.xls";
				$filename = "_{$filename}_excel.php";
				
				$this->var['report_name'] = $n->getEventType();
				$this->var['filename']    = $excel_filename; 
				$this->var['data']        = $n->getNotificationItems();
				$this->view->noTemplate();
				$this->view->render('notifications/excel/'.$filename,$this->var);  
			}else{
				redirect("notifications");
			}
		}else{
			redirect("notifications");
		}
	}

	function get_link() {
		if( !empty($_GET['module']) && (!empty($_GET['emp_code']) || !empty($_GET['eid'])) ) {
			$emp_code = $_GET['emp_code'];
			if(!empty($emp_code)) {
				$e = G_Employee_Finder::findByEmployeeCode($_GET['emp_code']);
			} else {
				$e_id = $_GET['eid'];
				$h_id = $_GET['hash'];
				Utilities::verifyHash(Utilities::decrypt($e_id),$h_id);				
				$id = Utilities::decrypt($e_id);	
				$e  = G_Employee_Finder::findById($id);
			}

			$from = $_GET['from'];
			$to   = $_GET['to'];
			
			if($e) {
				$eid  = Utilities::encrypt($e->getId());
				$hash = Utilities::createHash($e->getId());

				if($_GET['module'] == 'tardiness') {
					G_Cutoff_Period_Helper::addNewPeriod();

			        $now = date('Y-m-d');
			        $p = G_Cutoff_Period_Finder::findByDate($now);

			        if( !empty($from) && !empty($to) ) {
			            $from_date = $from;
			            $to_date   = $to;
			        } else {
				        if ($p) {
				            $hpid = Utilities::encrypt($p->getId());
				            $from_date = $p->getStartDate();
				            $to_date = $p->getEndDate();
				        }			        	
			        }

			        if(!empty($_GET['sub_module'])) {
						if($_GET['sub_module'] == 'tardiness_dtr') {
							if( !empty($_GET['attendance_date']) ) {
								$from_date = date('Y-m-d', strtotime($_GET['attendance_date']));
								$to_date   = date('Y-m-d', strtotime($_GET['attendance_date'] . ' +1 day'));
							} 
							redirect('attendance/attendance_logs_period?hpid_n='.$eid.'&from='.$from_date.'&to='.$to_date.'&dtr_popup_show=false');
						} elseif($_GET['sub_module'] == 'tardiness_timesheet') {
							redirect('attendance/show_attendance?employee_id='.$eid.'&hash='.$hash.'&from='.$from_date.'&to='.$to_date);
						}
			        } else {
			        	redirect('attendance/show_attendance?employee_id='.$eid.'&hash='.$hash.'&from='.$from_date.'&to='.$to_date);	
			        }
			    }elseif($_GET['module'] == 'absent') {
					G_Cutoff_Period_Helper::addNewPeriod();

			        $now = date('Y-m-d');
			        $p = G_Cutoff_Period_Finder::findByDate($now);

			        if( !empty($from) && !empty($to) ) {
			            $from_date = $from;
			            $to_date   = $to;
			        } else {
				        if ($p) {
				            $hpid 	   = Utilities::encrypt($p->getId());
				            $from_date = $p->getStartDate();
				            $to_date   = $p->getEndDate();
				        }			        	
			        }

			        if(!empty($_GET['sub_module'])) {
						if($_GET['sub_module'] == 'absent_dtr') {
							if( !empty($_GET['attendance_date']) ) {
								$from_date = date('Y-m-d', strtotime($_GET['attendance_date']));
								$to_date   = date('Y-m-d', strtotime($_GET['attendance_date'] . ' +1 day'));
							} 
							redirect('attendance/attendance_logs_period?hpid_n='.$eid.'&from='.$from_date.'&to='.$to_date.'&dtr_popup_show=false');
						} elseif($_GET['sub_module'] == 'absent_timesheet') {
							redirect('attendance/show_attendance?employee_id='.$eid.'&hash='.$hash.'&from='.$from_date.'&to='.$to_date);
						}
			        } else {
			        	redirect('attendance/show_attendance?employee_id='.$eid.'&hash='.$hash.'&from='.$from_date.'&to='.$to_date);	
			        }
				}elseif($_GET['module'] == 'undertime') {

					$now = date('Y-m-d');
			        if( !empty($from) && !empty($to) ) {
			            $from_date = $from;
			            $to_date   = $to;
			        } else {
				        $p = G_Cutoff_Period_Finder::findByDate($now);
				        if ($p) {
				            $hpid 	   = Utilities::encrypt($p->getId());
				            $from_date = $p->getStartDate();
				            $to_date   = $p->getEndDate();
				        }	
			        }

					if(!empty($_GET['sub_module'])) {
						if($_GET['sub_module'] == 'undertime_dtr') {
							if( !empty($_GET['attendance_date']) ) {
								$from_date = date('Y-m-d', strtotime($_GET['attendance_date']));
								$to_date   = date('Y-m-d', strtotime($_GET['attendance_date'] . ' +1 day'));
							} 
							redirect('attendance/attendance_logs_period?hpid_n='.$eid.'&from='.$from_date.'&to='.$to_date.'&dtr_popup_show=false');
						} elseif($_GET['sub_module'] == 'undertime_timesheet') {
							redirect('attendance/show_attendance?employee_id='.$eid.'&hash='.$hash.'&from='.$from_date.'&to='.$to_date);
						}
					} else {
						redirect('attendance/show_attendance?employee_id='.$eid.'&hash='.$hash.'&from='.$from_date.'&to='.$to_date);					
					}
					
				}elseif($_GET['module'] == 'incomplete_dtr') {

					$now 	   = date('Y-m-d');
					$from_date = date('Y-m-01',strtotime($now));
					$to_date   = date('Y-m-t',strtotime($now));

					if( !empty($_GET['attendance_date']) ) {

				        $current_period_array = array();
				        $current_p      = G_Cutoff_Period_Helper::sqlGetCurrentCutoffPeriod($_GET['attendance_date']);

				        if($current_p) {
							$from_date = date('Y-m-d', strtotime($current_p['period_start']));
							$to_date   = date('Y-m-d', strtotime($current_p['period_end']));
				        }

					} 

					if(!empty($_GET['sub_module'])) {
						if($_GET['sub_module'] == 'incomplete_dtr_timesheet') {
							redirect('attendance/show_attendance?employee_id='.$eid.'&hash='.$hash.'&from='.$from_date.'&to='.$to_date);
						}else {
							$from_date = date('Y-m-d', strtotime($_GET['attendance_date']));
							$to_date   = date('Y-m-d', strtotime($_GET['attendance_date'] . ' +1 day'));							
							redirect('attendance/attendance_logs_period?hpid_n='.$eid.'&from='.$from_date.'&to='.$to_date.'&dtr_popup_show=false');	
						}
					} else {
						$from_date = date('Y-m-d', strtotime($_GET['attendance_date']));
						$to_date   = date('Y-m-d', strtotime($_GET['attendance_date'] . ' +1 day'));						
						redirect('attendance/attendance_logs_period?hpid_n='.$eid.'&from='.$from_date.'&to='.$to_date.'&dtr_popup_show=false');	
					}
					
				}elseif($_GET['module'] == 'employee_with_no_schedule') {
					if(!empty($from) && !empty($to)) {
						$year  = date("Y", strtotime($from));
						$month = date("m", strtotime($from));
						redirect('schedule/show_employee_schedule?eid='.$eid.'&hash='.$hash.'&month='.$month.'&year='.$year.'');
					} else {
						redirect('schedule/show_employee_schedule?eid='.$eid.'&hash='.$hash);	
					}
				}elseif($_GET['module'] == 'emp_with_incorrect_shift'){
			        $now = date('Y-m-d');

			        if( !empty($from) && !empty($to) ) {
			            $from_date = $from;
			            $to_date   = $to;
			        } else {
				        $p = G_Cutoff_Period_Finder::findByDate($now);
				        if ($p) {
				            $hpid = Utilities::encrypt($p->getId());
				            $from_date = $p->getStartDate();
				            $to_date = $p->getEndDate();
				        } else {
							$from_date = $_GET['from'];
							$to_date   = $_GET['to'];			        	
				        }	
			        }

					if(!empty($_GET['sub_module'])) {
						if($_GET['sub_module'] == 'emp_with_incorrect_shift_dtr') {
							if( !empty($_GET['attendance_date']) ) {
								$from_date = date('Y-m-d', strtotime($_GET['attendance_date']));
								$to_date   = date('Y-m-d', strtotime($_GET['attendance_date'] . ' +1 day'));
							} 
							redirect('attendance/attendance_logs_period?hpid_n='.$eid.'&from='.$from_date.'&to='.$to_date.'&dtr_popup_show=false');
						}elseif($_GET['sub_module'] == 'emp_with_incorrect_shift_timesheet') {
							redirect('attendance/show_attendance?employee_id='.$eid.'&hash='.$hash.'&from='.$from_date.'&to='.$to_date);
						}elseif($_GET['sub_module'] == 'emp_with_incorrect_shift_schedule') {
							if(!empty($from) && !empty($to)) {
								$year  = date("Y", strtotime($from));
								$month = date("m", strtotime($from));
								redirect('schedule/show_employee_schedule?eid='.$eid.'&hash='.$hash.'&month='.$month.'&year='.$year.'');
							} else {
								redirect('schedule/show_employee_schedule?eid='.$eid.'&hash='.$hash);
							}							
							
						}
					} else {
						redirect('attendance/show_attendance?employee_id='.$eid.'&hash='.$hash.'&from='.$from_date.'&to='.$to_date);					
					}
				}elseif($_GET['module'] == 'payroll') {
					if(!empty($_GET['sub_module'])) {
						if($_GET['sub_module'] == 'payroll_profile') {
							$emp_sub_profile = "employment_status";
							redirect('employee/profile?eid='.$eid.'&hash='.$hash.'#'.$emp_sub_profile);
						}elseif($_GET['sub_module'] == 'payroll_dtr') {
							$from_date = $_GET['from'];
							$to_date   = $_GET['to'];
							redirect('attendance/attendance_logs_period?hpid_n='.$eid.'&from='.$from_date.'&to='.$to_date.'&dtr_popup_show=false');
						}elseif($_GET['sub_module'] == 'payroll_timesheet') {
							$from_date = $_GET['from'];
							$to_date   = $_GET['to'];
							redirect('attendance/show_attendance?employee_id='.$eid.'&hash='.$hash.'&from='.$from_date.'&to='.$to_date);
						}elseif($_GET['sub_module'] == 'payroll_schedule') {
							$from_date = $_GET['from'];
							$to_date   = $_GET['to'];
							redirect('schedule/show_employee_schedule?eid='.$eid.'&hash='.$hash);
						}
					}
				}else{
					redirect('employee/profile?eid='.$eid.'&hash='.$hash.'#'.$_GET['module']);
				}

				
			}else{
				redirect('notifications');
			}
		}else{
			redirect('notifications');
		}
	}

	function _count_new_notifications() {
		$n = new G_Notifications();
        $n->updateNotifications();
        $return['new_notifications'] = $n->countNotifications();
        
        echo json_encode($return);
	}

	function _load_notification_list() {
		sleep(1);

		$n = new G_Notifications();
        $n->updateNotifications();        
        $this->var['employee'] 		= $n->getNotifications(G_Notifications::TYPE_EMPLOYEE);
        $this->var['attendance'] 	= $n->getNotifications(G_Notifications::TYPE_ATTENDANCE);
        $this->var['schedule'] 		= $n->getNotifications(G_Notifications::TYPE_SCHEDULE);
        $this->view->noTemplate();
		$this->view->render('notifications/_ajax_notification_list.php',$this->var);  
	}

	function _load_important_notification_list() {
		$n = new G_Notifications();
        $n->updateNotifications();

        $this->var['attendance'] 	= $n->getNotificationsImportant(G_Notifications::TYPE_ATTENDANCE);
        $this->var['schedule'] 		= $sched = $n->getNotificationsImportant(G_Notifications::TYPE_SCHEDULE);
        //$this->var['employee'] 		= $n->getNotificationsImportant(G_Notifications::TYPE_EMPLOYEE);

        $this->view->noTemplate();
		$this->view->render('notifications/_ajax_important_notifications.php',$this->var);  				
	}

	function _load_schedule_notification_list() {
		$from = $_POST['from'];
		$to   = $_POST['to'];		

		if(empty($from) && empty($to)) {
        	$date   = date("Y-m-d");
	        $current_period_array = array();
	        $current_p      = G_Cutoff_Period_Helper::sqlGetCurrentCutoffPeriod($date);

	        if($current_p) {
				$from = date('Y-m-d', strtotime($current_p['period_start']));
				$to   = date('Y-m-d', strtotime($current_p['period_end']));
	        }	        
		}

		$n = new G_Notifications();
        $n->updateNotifications($from, $to);
        $this->var['schedule'] 		= $sched = $n->getSelectedNotification(G_Notifications::TYPE_SCHEDULE);

        $this->var['from'] = $from;
        $this->var['to']   = $to;
        $this->view->noTemplate();
		$this->view->render('notifications/_ajax_notifications.php',$this->var); 		
	}

	function _load_employee_notification_list() {
		$n = new G_Notifications();
        $n->updateNotifications();
        $this->var['employee'] 		= $n->getSelectedNotification(G_Notifications::TYPE_EMPLOYEE);
        $this->view->noTemplate();
		$this->view->render('notifications/_ajax_notifications.php',$this->var); 		
	}	

	function _load_attendance_notification_list() {
		$from = $_POST['from'];
		$to   = $_POST['to'];
		$n = new G_Notifications();
        $n->updateNotifications($from, $to);
        $this->var['attendance']   = $n->getSelectedNotification(G_Notifications::TYPE_ATTENDANCE);

        $this->var['cutoff'] = date('M j',strtotime($from)) . ' to ' . date('M j, Y',strtotime($to));
        $this->var['from'] = $from;
        $this->var['to']   = $to;
        $this->view->noTemplate();
		$this->view->render('notifications/_ajax_notifications.php',$this->var); 		
	}

	function _load_dtr_notification_list() {
		$from = $_POST['from'];
		$to   = $_POST['to'];		
		$n = new G_Notifications();
        $n->updateNotifications($from, $to);
        $this->var['dtr'] 		= $n->getSelectedNotification(G_Notifications::TYPE_DTR);
        $this->view->noTemplate();
		$this->view->render('notifications/_ajax_notifications.php',$this->var); 	
	}
	
	function _load_important_payroll_notification_list() {

		$from = isset($_POST['from']) ? $_POST['from'] : null;
		$to   = isset($_POST['to']) ? $_POST['to'] : null;	

		$cutoff_01 = isset($_POST['cutoff_01']) ? $_POST['cutoff_01'] : null; 
		$cutoff_02 = isset($_POST['cutoff_02']) ? $_POST['cutoff_02'] : null; 

		$n = new G_Notifications();
        $n->updateNotifications($from, $to, $cutoff_01, $cutoff_02);
        $this->var['payroll'] = $sched = $n->getNotificationsImportant(G_Notifications::TYPE_PAYROLL);
        $this->view->noTemplate();
		$this->view->render('notifications/_ajax_payroll_important_notifications.php',$this->var); 	
	}

	function _load_view_notification_item_list()
	{		
		sleep(1);
		$nid = Utilities::decrypt($_POST['notification_id']);
		$n   = G_Notifications_Finder::findById($nid);
		$event_type_array = $n->getEventTypeArray();
		if($n) {
			$filename = strtolower($n->getEventType());
			$filename = str_replace(' ','_',$filename);
			$filename = "_{$filename}_dt.php";

			$this->var['n'] = $n;

			if($filename == '_multiple_in/out_records_dt.php') {
				$filename = '_multiple_inout_records_dt.php';
			}

			//FOR INCOMPLETE DTR ONLY

			$from = $_POST['from'];
			$to   = $_POST['to'];

	        if($from != null && $to != null) {

	            $current_period_array = array();
	            $current_p      = G_Cutoff_Period_Helper::sqlGetCutoffPeriodByStartEndDate($from, $to);
	            if($current_p) {
					$query['date_from'] = $current_p['period_start'];
					$query['date_to'] 	= $current_p['period_end'];
	            } else {
		            $cutoff = new G_Cutoff_Period();
		            $expected_current_period = $cutoff->getCurrentCutoffPeriod($date);   
		            $current_period = $expected_current_period;	   
			        $query['date_from'] = $current_period['current_cutoff']['start'];
			        $query['date_to']   = $current_period['current_cutoff']['end'];	            	
	            }

	        } else {
				$date   = date("Y-m-d");
		        $current_period_array = array();
		        $current_p      = G_Cutoff_Period_Helper::sqlGetCurrentCutoffPeriod($date);
		        if($current_p) {
					$query['date_from'] = $current_p['period_start'];
					$query['date_to'] 	= $current_p['period_end'];
		        } else {
		            $cutoff = new G_Cutoff_Period();
		            $expected_current_period = $cutoff->getCurrentCutoffPeriod($date);   
		            $current_period = $expected_current_period;	   
			        $query['date_from'] = $current_period['current_cutoff']['start'];
			        $query['date_to']   = $current_period['current_cutoff']['end'];
		        }
	        }

			$query['remark'] 	= 'all';
			if( $nid == 10 || $nid == 9 ){
				$this->var['inc_dtr_data'] = G_Employee_Helper::getIncompleteTimeInOutData($query);
			}elseif($nid == 56 || $filename == '_multiple_inout_records_dt.php') {

				$date   = date("Y-m-d");		        
		        $current_period_array = array();
		        $current_p      = G_Cutoff_Period_Helper::sqlGetCurrentCutoffPeriod($date);
		        if($current_p) {
		            $current_period_array['current_cutoff']['start'] = $current_p['period_start'];
		            $current_period_array['current_cutoff']['end']   = $current_p['period_end'];
		            $current_period_array['is_lock']                 = $current_p['is_lock'];
		            $current_period_array['date']                    = $date;
		            $current_period                                  = $current_period_array;
		        } else {
		            $cutoff = new G_Cutoff_Period();
		            $expected_current_period = $cutoff->getCurrentCutoffPeriod($date);   
		            $current_period = $expected_current_period;
		        }

	        	if($from != null && $to != null) {
			        $from = $from;
			        $to   = $to;
	        	} else {
			        $from = $current_period['current_cutoff']['start'];
			        $to   = $current_period['current_cutoff']['end']; 		        
	        	}		        


				$this->var['multi_in_out_data'] = G_Fp_Attendance_Logs_Helper::sqlGetMultipleInOutByDateRange($from, $to);
			}

			//FOR NO BANK ACCOUNT
			if( $nid == 14 ){
					$this->var['employees'] = G_Employee_Helper::employeeWithNoBankAccount();
			}
			
			if($n->getEventType() == $event_type_array['END_OF_CONTRACT_PROB']){
				$this->var['endo_employees'] = G_Employee_Helper::employeeEndOfContractProbi30Days();
			}
			
			$this->var['from'] = $from;
			$this->var['to']   = $to;
			$this->view->render('notifications/'.$filename,$this->var);
		}	
	}

	function ajax_load_incomplete_requirements_list_dt()
	{
		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(EMPLOYEE);
		$dt->setSQL("
			SELECT e.id, er.id as requirement_id, CONCAT(' <a target=\"_blank\" href=" . url("notifications/get_link?module=requirements&emp_code=' , e.employee_code , '") . "  > ', e.lastname , ', ' , e.firstname , '</a>') as employee_name, esh.name as department_name, er.requirements as incomplete_requirement
            FROM " . EMPLOYEE . " e
            	LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id 
            	LEFT JOIN " . G_EMPLOYEE_REQUIREMENTS . " er ON e.id = er.employee_id 		
		");		
		$dt->setCountSQL("SELECT COUNT(e.id) as c FROM " . EMPLOYEE . " e LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id LEFT JOIN " . G_EMPLOYEE_REQUIREMENTS . " er ON e.id = er.employee_id ");	
		$dt->setCondition(" e.id NOT IN (SELECT employee_id FROM ". G_EMPLOYEE_REQUIREMENTS ." WHERE is_complete = 1)");
		$dt->setColumns('employee_name,department_name,incomplete_requirement');	
		$dt->setPreDefineSearch(
			array(				
				"employee_name" => "e.lastname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' OR e.firstname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'",
				"incomplete_requirement" => "er.requirements LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'",
				"department_name" => "esh.name LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'"
			)
		); 	 
		$dt->setOrder('ASC');
		$dt->setSort(0);							
		$dt->setCustomColumn(
				array(		
						1 => '<div class=\"i_container\"><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></div>',		
						2 => '<div class=\"btn-group pull-right\"><a class=\"btn dropdown-toggle\" href=\"#\">Action <span class=\"caret\"></span></a><ul class=\"dropdown-menu\"><li><a href=\"' . url("company/edit_company?eid=id") . '\"><i class=\"icon-edit\"></i> Edit Company</a></li><li><a href=\"' . url("company/emp_list?eid=id") . '\"><i class=\"icon-list\"></i> View participants List</a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"company_archive_btn\" ><i class=\"icon-trash\"></i> Archive </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"company_mark_as_blacklisted_btn\" ><i class=\"icon-tag\"></i> Mark as Blacklisted </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"view_company_attended_btn\" ><i class=\"icon-list\"></i>  View Program Attended</a></li></ul></div>'
		));
		//echo "<pre>"; print_r($dt);
		echo $dt->constructDataTableRightTools();
	}

	function ajax_load_no_salary_rate_list_dt()
	{
		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(EMPLOYEE);
		$dt->setSQL("
			SELECT e.id, CONCAT(' <a target=\"_blank\" href=" . url("notifications/get_link?module=compensation&emp_code=' , e.employee_code , '") . "  > ', e.lastname , ', ' , e.firstname , '</a>') as employee_name, esh.name as department_name, DATE_FORMAT(e.hired_date,'%M %d, %Y') as hired_date, e.employee_status_id
            FROM " . EMPLOYEE . " e
            	LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id 		
		");		
		$dt->setCountSQL("SELECT COUNT(e.id) as c FROM " . EMPLOYEE . " e LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id ");	
		$dt->setCondition(" e.employee_status_id = 1 AND e.id NOT IN (SELECT employee_id FROM " . G_EMPLOYEE_BASIC_SALARY_HISTORY . " WHERE end_date >= IF(end_date = '','',NOW()) ) ");
		$dt->setColumns('employee_name,department_name,hired_date');	
		$dt->setPreDefineSearch(
			array(				
				"employee_name" => "e.lastname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' OR e.firstname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'",
				"department_name" => "esh.name LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'",
				"hired_date" => "DATE_FORMAT(e.hired_date,'%M %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'"
			)
		); 	 
		$dt->setOrder('ASC');
		$dt->setSort(0);							
		$dt->setCustomColumn(
				array(		
						1 => '<div class=\"i_container\"><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></div>',		
						2 => '<div class=\"btn-group pull-right\"><a class=\"btn dropdown-toggle\" href=\"#\">Action <span class=\"caret\"></span></a><ul class=\"dropdown-menu\"><li><a href=\"' . url("company/edit_company?eid=id") . '\"><i class=\"icon-edit\"></i> Edit Company</a></li><li><a href=\"' . url("company/emp_list?eid=id") . '\"><i class=\"icon-list\"></i> View participants List</a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"company_archive_btn\" ><i class=\"icon-trash\"></i> Archive </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"company_mark_as_blacklisted_btn\" ><i class=\"icon-tag\"></i> Mark as Blacklisted </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"view_company_attended_btn\" ><i class=\"icon-list\"></i>  View Program Attended</a></li></ul></div>'
		));
		//echo "<pre>"; print_r($dt);
		echo $dt->constructDataTableRightTools();
	}
	
	function ajax_load_end_of_contract_list_dt()
	{
		Utilities::ajaxRequest(); 
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(EMPLOYEE);
		$dt->setSQL("
			SELECT e.id, CONCAT(' <a target=\"_blank\" href=" . url("notifications/get_link?module=employment_status&emp_code=' , e.employee_code , '") . "  > ', e.lastname , ', ' , e.firstname , '</a>') as employee_name, esh.name as department_name, DATE_FORMAT(e.hired_date,'%M %d, %Y') as hired_date,
				DATE_FORMAT(esh.end_date ,'%M %d, %Y') as date_end_of_contract
            FROM " . EMPLOYEE . " e
            	LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id 		
		");		
		$dt->setCountSQL("SELECT COUNT(e.id) as c FROM " . EMPLOYEE . " e LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id ");	

		$start_date = date("Y-m-01");
		$end_date = date("Y-m-d", strtotime("+30 days"));
		$dt->setCondition(" (esh.end_date >= '{$start_date}' AND esh.end_date <= '{$end_date}') AND esh.type = ". Model::safeSql(G_Employee_Subdivision_History::DEPARTMENT). " AND e.employee_status_id = ". Model::safeSql(4). " ");
		$dt->setColumns('employee_name,department_name,hired_date,date_end_of_contract');	
		$dt->setPreDefineSearch(
			array(				
				"employee_name" => "e.lastname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' OR e.firstname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'",
				"department_name" => "esh.name LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'",
				"hired_date" => "DATE_FORMAT(e.hired_date,'%M %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'",
				"date_end_of_contract" => "DATE_FORMAT(esh.end_date,'%M %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'"
			)
		); 	 
		$dt->setOrder('ASC');
		$dt->setSort(0);							
		$dt->setCustomColumn(
				array(		
						1 => '<div class=\"i_container\"><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></div>',		
						2 => '<div class=\"btn-group pull-right\"><a class=\"btn dropdown-toggle\" href=\"#\">Action <span class=\"caret\"></span></a><ul class=\"dropdown-menu\"><li><a href=\"' . url("company/edit_company?eid=id") . '\"><i class=\"icon-edit\"></i> Edit Company</a></li><li><a href=\"' . url("company/emp_list?eid=id") . '\"><i class=\"icon-list\"></i> View participants List</a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"company_archive_btn\" ><i class=\"icon-trash\"></i> Archive </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"company_mark_as_blacklisted_btn\" ><i class=\"icon-tag\"></i> Mark as Blacklisted </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"view_company_attended_btn\" ><i class=\"icon-list\"></i>  View Program Attended</a></li></ul></div>'
		));
		//echo "<pre>"; print_r($dt);
		echo $dt->constructDataTableRightTools();
	}

	function ajax_load_no_department_list_dt()
	{
		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(EMPLOYEE);
		$dt->setSQL("
			SELECT e.id, CONCAT(' <a target=\"_blank\" href=" . url("notifications/get_link?module=employment_status&emp_code=' , e.employee_code , '") . "  > ', e.lastname , ', ' , e.firstname , '</a>') as employee_name, DATE_FORMAT(e.hired_date,'%M %d, %Y') as hired_date
            FROM " . EMPLOYEE . " e	
		");		
		$dt->setCountSQL("SELECT COUNT(e.id) as c FROM " . EMPLOYEE . " e ");	
		$dt->setCondition(" e.employee_status_id = 1 AND e.id NOT IN (SELECT employee_id FROM " . G_EMPLOYEE_SUBDIVISION_HISTORY . " WHERE end_date >= IF( end_date = '', '', NOW( ) ) AND type = ". Model::safeSql(G_Employee_Subdivision_History::DEPARTMENT). ")");
		$dt->setColumns('employee_name,hired_date');	
		$dt->setPreDefineSearch(
			array(				
				"employee_name" => "e.lastname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' OR e.firstname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'",
				"hired_date" => "DATE_FORMAT(e.hired_date,'%M %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'"
			)
		); 	 
		$dt->setOrder('ASC');
		$dt->setSort(0);							
		$dt->setCustomColumn(
				array(		
						1 => '<div class=\"i_container\"><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></div>',		
						2 => '<div class=\"btn-group pull-right\"><a class=\"btn dropdown-toggle\" href=\"#\">Action <span class=\"caret\"></span></a><ul class=\"dropdown-menu\"><li><a href=\"' . url("company/edit_company?eid=id") . '\"><i class=\"icon-edit\"></i> Edit Company</a></li><li><a href=\"' . url("company/emp_list?eid=id") . '\"><i class=\"icon-list\"></i> View participants List</a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"company_archive_btn\" ><i class=\"icon-trash\"></i> Archive </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"company_mark_as_blacklisted_btn\" ><i class=\"icon-tag\"></i> Mark as Blacklisted </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"view_company_attended_btn\" ><i class=\"icon-list\"></i>  View Program Attended</a></li></ul></div>'
		));
		//echo "<pre>"; print_r($dt);
		echo $dt->constructDataTableRightTools();
	}

	function ajax_load_no_job_title_list_dt()
	{
		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(EMPLOYEE);
		$dt->setSQL("
			SELECT e.id, CONCAT(' <a target=\"_blank\" href=" . url("notifications/get_link?module=employment_status&emp_code=' , e.employee_code , '") . "  > ', e.lastname , ', ' , e.firstname , '</a>') as employee_name, DATE_FORMAT(e.hired_date,'%M %d, %Y') as hired_date, esh.name as department_name
            FROM " . EMPLOYEE . " e	
            LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id 	
		");		
		$dt->setCountSQL("SELECT COUNT(e.id) as c FROM " . EMPLOYEE . " e LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id ");	
		$dt->setCondition(" e.id NOT IN (SELECT employee_id FROM " . G_EMPLOYEE_JOB_HISTORY . " WHERE end_date >= IF( end_date = '', '', '' ) ) ");
		$dt->setColumns('employee_name,department_name,hired_date');	
		$dt->setPreDefineSearch(
			array(				
				"employee_name" => "e.lastname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' OR e.firstname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'",
				"department_name" => "esh.name LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'",
				"hired_date" => "DATE_FORMAT(e.hired_date,'%M %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'"
			)
		); 	 
		$dt->setOrder('ASC');
		$dt->setSort(0);							
		$dt->setCustomColumn(
				array(		
						1 => '<div class=\"i_container\"><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></div>',		
						2 => '<div class=\"btn-group pull-right\"><a class=\"btn dropdown-toggle\" href=\"#\">Action <span class=\"caret\"></span></a><ul class=\"dropdown-menu\"><li><a href=\"' . url("company/edit_company?eid=id") . '\"><i class=\"icon-edit\"></i> Edit Company</a></li><li><a href=\"' . url("company/emp_list?eid=id") . '\"><i class=\"icon-list\"></i> View participants List</a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"company_archive_btn\" ><i class=\"icon-trash\"></i> Archive </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"company_mark_as_blacklisted_btn\" ><i class=\"icon-tag\"></i> Mark as Blacklisted </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"view_company_attended_btn\" ><i class=\"icon-list\"></i>  View Program Attended</a></li></ul></div>'
		));
		//echo "<pre>"; print_r($dt);
		echo $dt->constructDataTableRightTools();
	}

	function ajax_load_no_employment_status_list_dt()
	{
		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(EMPLOYEE);
		$dt->setSQL("
			SELECT e.id, CONCAT(' <a target=\"_blank\" href=" . url("notifications/get_link?module=employment_status&emp_code=' , e.employee_code , '") . "  > ', e.lastname , ', ' , e.firstname , '</a>') as employee_name, DATE_FORMAT(e.hired_date,'%M %d, %Y') as hired_date, esh.name as department_name
            FROM " . EMPLOYEE . " e	
            LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id 	
		");		
		$dt->setCountSQL("SELECT COUNT(e.id) as c FROM " . EMPLOYEE . " e LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id ");	
		$dt->setCondition(" e.id NOT IN (SELECT employee_id FROM " . G_EMPLOYEE_JOB_HISTORY . " WHERE employment_status <> '' ) ");
		$dt->setColumns('employee_name,department_name,hired_date');	
		$dt->setPreDefineSearch(
			array(				
				"employee_name" => "e.lastname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' OR e.firstname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'",
				"department_name" => "esh.name LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'",
				"hired_date" => "DATE_FORMAT(e.hired_date,'%M %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'"
			)
		); 	 
		$dt->setOrder('ASC');
		$dt->setSort(0);							
		$dt->setCustomColumn(
				array(		
						1 => '<div class=\"i_container\"><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></div>',		
						2 => '<div class=\"btn-group pull-right\"><a class=\"btn dropdown-toggle\" href=\"#\">Action <span class=\"caret\"></span></a><ul class=\"dropdown-menu\"><li><a href=\"' . url("company/edit_company?eid=id") . '\"><i class=\"icon-edit\"></i> Edit Company</a></li><li><a href=\"' . url("company/emp_list?eid=id") . '\"><i class=\"icon-list\"></i> View participants List</a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"company_archive_btn\" ><i class=\"icon-trash\"></i> Archive </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"company_mark_as_blacklisted_btn\" ><i class=\"icon-tag\"></i> Mark as Blacklisted </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"view_company_attended_btn\" ><i class=\"icon-list\"></i>  View Program Attended</a></li></ul></div>'
		));
		//echo "<pre>"; print_r($dt);
		echo $dt->constructDataTableRightTools();
	}

	function ajax_load_no_employee_status_list_dt()
	{
		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(EMPLOYEE);
		$dt->setSQL("
			SELECT e.id, CONCAT(' <a target=\"_blank\" href=" . url("notifications/get_link?module=employment_status&emp_code=' , e.employee_code , '") . "  > ', e.lastname , ', ' , e.firstname , '</a>') as employee_name, DATE_FORMAT(e.hired_date,'%M %d, %Y') as hired_date, esh.name as department_name
            FROM " . EMPLOYEE . " e	
            LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id 	
		");		
		$dt->setCountSQL("SELECT COUNT(e.id) as c FROM " . EMPLOYEE . " e LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id ");	
		$dt->setCondition(" e.employee_status_id = 0 ");
		$dt->setColumns('employee_name,department_name,hired_date');	
		$dt->setPreDefineSearch(
			array(				
				"employee_name" => "e.lastname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' OR e.firstname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'",
				"department_name" => "esh.name LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'",
				"hired_date" => "DATE_FORMAT(e.hired_date,'%M %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'"
			)
		); 	 
		$dt->setOrder('ASC');
		$dt->setSort(0);							
		$dt->setCustomColumn(
				array(		
						1 => '<div class=\"i_container\"><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></div>',		
						2 => '<div class=\"btn-group pull-right\"><a class=\"btn dropdown-toggle\" href=\"#\">Action <span class=\"caret\"></span></a><ul class=\"dropdown-menu\"><li><a href=\"' . url("company/edit_company?eid=id") . '\"><i class=\"icon-edit\"></i> Edit Company</a></li><li><a href=\"' . url("company/emp_list?eid=id") . '\"><i class=\"icon-list\"></i> View participants List</a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"company_archive_btn\" ><i class=\"icon-trash\"></i> Archive </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"company_mark_as_blacklisted_btn\" ><i class=\"icon-tag\"></i> Mark as Blacklisted </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"view_company_attended_btn\" ><i class=\"icon-list\"></i>  View Program Attended</a></li></ul></div>'
		));
		//echo "<pre>"; print_r($dt);
		echo $dt->constructDataTableRightTools();
	}

	function ajax_load_employee_with_undertime_list_dt()
	{
		Utilities::ajaxRequest();

        $date   = date("Y-m-d");

        $from  = $_GET['from'];
        $to    = $_GET['to'];

        if($from != '' && $to != '') {

            $current_period_array = array();
            $current_p      = G_Cutoff_Period_Helper::sqlGetCutoffPeriodByStartEndDate($from, $to);
            if($current_p) {
				$from_ut = date('Y-m-d', strtotime($current_p['period_start']));
				$to_ut   = date('Y-m-d', strtotime($current_p['period_end']));		               	
            } else {
		        $cutoff = new G_Cutoff_Period();
		        $current_period = $cutoff->getCurrentCutoffPeriod($date);  

		        $from_ut = $current_period['current_cutoff']['start'];
		        $to_ut   = $current_period['current_cutoff']['end'];              	
            }      	

        } else {

	        $current_p      = G_Cutoff_Period_Helper::sqlGetCurrentCutoffPeriod($date);
	        if($current_p) {
				$from_ut = date('Y-m-d', strtotime($current_p['period_start']));
				$to_ut   = date('Y-m-d', strtotime($current_p['period_end']));
	        } else {
		        $cutoff = new G_Cutoff_Period();
		        $current_period = $cutoff->getCurrentCutoffPeriod($date);  

		        $from_ut = $current_period['current_cutoff']['start'];
		        $to_ut   = $current_period['current_cutoff']['end'];        	
	        }

        }

    	$s_from = date("Y-m-d",strtotime($from_ut . "-1 day"));
    	$s_to   = date("Y-m-d",strtotime($to_ut));

		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(EMPLOYEE);		

		$dt->setSQL("
			SELECT a.id as aid, e.id as id, CONCAT(' <a target=\"_blank\" href=" . url("notifications/get_link?module=undertime&emp_code=' , e.employee_code , '") . "  > ', e.lastname , ', ' , e.firstname , '</a>') as employee_name, DATE_FORMAT(a.date_attendance,'%M %d, %Y') date_attendance, a.scheduled_time_in, a.scheduled_time_out,a.actual_time_in, a.actual_time_out,a.undertime_hours
            FROM " . G_EMPLOYEE_ATTENDANCE . " a 	
            	LEFT JOIN " . EMPLOYEE . " e ON a.employee_id = e.id 
		");	

		$dt->setCountSQL("SELECT COUNT(aid) as c FROM " . G_EMPLOYEE_ATTENDANCE . " a LEFT JOIN " . EMPLOYEE . " e ON a.employee_id = e.id ");	
		$dt->setCondition("((a.undertime_hours <> 0 OR a.undertime_hours <> '' )AND (STR_TO_DATE(CONCAT(a.scheduled_date_out, ' ', a.scheduled_time_out), '%Y-%m-%d %H:%i:%s') > STR_TO_DATE(CONCAT(a.actual_date_out, ' ', a.actual_time_out), '%Y-%m-%d %H:%i:%s'))) AND a.date_attendance BETWEEN " . Model::safeSql($s_from) . " AND " . Model::safeSql($s_to));
		$dt->setColumns('employee_name,date_attendance,scheduled_time_in,scheduled_time_out,actual_time_in,actual_time_out,undertime_hours');	
		$dt->setPreDefineSearch(
			array(				
				"employee_name" => "e.lastname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' OR e.firstname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'"
			)
		); 	 
		$dt->setOrder('ASC');
		$dt->setSort(0);	

		$dt->setCustomColumn(
				array(		
						1 => '<div class=\"i_container\"><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></div>',		
						2 => '<a target=\"_blank\" href=\"' . url("notifications/get_link?module=undertime&sub_module=undertime_dtr&eid=id&attendance_date=date_attendance") . '\"><i class=\"icon-edit\"></i> DTR</a><br /><a target=\"_blank\" href=\"' . url("notifications/get_link?module=undertime&sub_module=undertime_timesheet&eid=id&from=" . $from . "&to=" . $to . "") . '\"><i class=\"icon-list\"></i> Timesheet</a>'
		));
		echo $dt->constructDataTableRightTools();
	}

	function ajax_load_employee_with_yearly_leave_increase_dt()
	{
		Utilities::ajaxRequest();

		$current_year = date("Y");

		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(EMPLOYEE_LEAVE_CREDIT_HISTORY);		
		$dt->setSQL("
			SELECT lc.id, CONCAT(e.firstname, ' ', e.lastname)AS employee_name, l.name AS leave_type, lc.credits_added
            FROM " . EMPLOYEE_LEAVE_CREDIT_HISTORY . " lc 	
            	LEFT JOIN " . EMPLOYEE . " e ON lc.employee_id = e.id 
            	LEFT JOIN " . G_LEAVE . " l ON lc.leave_id = l.id
		");		
		$dt->setCountSQL("SELECT COUNT(lc.id) as c FROM " . EMPLOYEE_LEAVE_CREDIT_HISTORY . " lc LEFT JOIN " . EMPLOYEE . " e ON lc.employee_id = e.id LEFT JOIN " . G_LEAVE . " l ON lc.leave_id = l.id");	
		$dt->setCondition("DATE_FORMAT(lc.date_added,'%Y') = " . Model::safeSql($current_year));		
		$dt->setColumns('employee_name,leave_type,credits_added');	
		$dt->setPreDefineSearch(
			array(				
				"employee_name" => "e.lastname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' OR e.firstname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'",
				"leave_type" => "l.name LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'"
			)
		); 	 
		$dt->setOrder('ASC');
		$dt->setSort(0);							
		$dt->setCustomColumn(
				array(		
						1 => '<div class=\"i_container\"><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></div>',		
						2 => '<div class=\"btn-group pull-right\"></div>'
		));		
		echo $dt->constructDataTableRightTools();
	}

	function ajax_load_employee_with_early_in_list_dt()
	{
		Utilities::ajaxRequest();

		$s_from = date("Y-m-d",strtotime("-1 day"));
        $s_to   = date("Y-m-d");

		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(EMPLOYEE);
		/*$dt->setSQL("
			SELECT a.id, CONCAT(e.firstname, ' ', e.lastname)AS employee_name, DATE_FORMAT(a.date_attendance,'%M %d, %Y') date_attendance, CONCAT(a.scheduled_date_in, ' ', a.scheduled_time_in)AS scheduled_in, CONCAT(a.scheduled_date_out, ' ', a.scheduled_time_out)AS scheduled_out,
				CONCAT(a.actual_date_in, ' ', a.actual_time_in)AS actual_in, CONCAT(a.actual_date_out, ' ', a.actual_time_out)AS actual_out,
				a.undertime_hours
            FROM " . G_EMPLOYEE_ATTENDANCE . " a 	
            	LEFT JOIN " . EMPLOYEE . " e ON a.employee_id = e.id 
		");*/
		$dt->setSQL("
			SELECT a.id, CONCAT(e.firstname, ' ', e.lastname)AS employee_name, DATE_FORMAT(a.date_attendance,'%M %d, %Y') date_attendance, a.scheduled_time_in, a.scheduled_time_out,a.actual_time_in, a.actual_time_out
            FROM " . G_EMPLOYEE_ATTENDANCE . " a 	
            	LEFT JOIN " . EMPLOYEE . " e ON a.employee_id = e.id 
		");		
		$dt->setCountSQL("SELECT COUNT(a.id) as c FROM " . G_EMPLOYEE_ATTENDANCE . " a LEFT JOIN " . EMPLOYEE . " e ON a.employee_id = e.id ");	
		$dt->setCondition("STR_TO_DATE(CONCAT(a.scheduled_date_in, ' ', a.scheduled_time_in), '%Y-%m-%d %H:%i:%s') >
                        STR_TO_DATE(CONCAT(a.actual_date_in, ' ', a.actual_time_in), '%Y-%m-%d %H:%i:%s') AND a.date_attendance BETWEEN " . Model::safeSql($s_from) . " AND " . Model::safeSql($s_to));
		//$dt->setColumns('employee_name,date_attendance,scheduled_in,scheduled_out,actual_in,actual_out,undertime_hours');	
		$dt->setColumns('employee_name,date_attendance,scheduled_time_in,scheduled_time_out,actual_time_in,actual_time_out');	
		$dt->setPreDefineSearch(
			array(				
				"employee_name" => "e.lastname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' OR e.firstname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'"
			)
		); 	 
		$dt->setOrder('ASC');
		$dt->setSort(0);							
		$dt->setCustomColumn(
				array(		
						1 => '<div class=\"i_container\"><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></div>',		
						2 => '<div class=\"btn-group pull-right\"><a class=\"btn dropdown-toggle\" href=\"#\">Action <span class=\"caret\"></span></a><ul class=\"dropdown-menu\"><li><a href=\"' . url("company/edit_company?eid=id") . '\"><i class=\"icon-edit\"></i> Edit Company</a></li><li><a href=\"' . url("company/emp_list?eid=id") . '\"><i class=\"icon-list\"></i> View participants List</a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"company_archive_btn\" ><i class=\"icon-trash\"></i> Archive </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"company_mark_as_blacklisted_btn\" ><i class=\"icon-tag\"></i> Mark as Blacklisted </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"view_company_attended_btn\" ><i class=\"icon-list\"></i>  View Program Attended</a></li></ul></div>'
		));
		//echo "<pre>"; print_r($dt);
		echo $dt->constructDataTableRightTools();
	}

	function ajax_load_employee_with_no_bank_account_dt()
	{
		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(EMPLOYEE);		
		$dt->setSQL("
			SELECT e.id, CONCAT(' <a target=\"_blank\" href=" . url("notifications/get_link?module=profile&emp_code=' , e.employee_code , '") . "  > ', e.lastname , ', ' , e.firstname , '</a>') as employee_name
            FROM " . EMPLOYEE . " e 	            	
		");		
		$dt->setCountSQL("SELECT COUNT(e.id) as c FROM " . EMPLOYEE . " e");	
		$dt->setCondition("
			NOT EXISTS(
				SELECT null 
				FROM " . G_EMPLOYEE_DIRECT_DEPOSIT . " dd 
			  WHERE dd.employee_id = e.id
			)
		");		
		$dt->setColumns('employee_name');	
		$dt->setPreDefineSearch(
			array(				
				"employee_name" => "e.lastname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' OR e.firstname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'"
			)
		); 	 
		$dt->setOrder('ASC');
		$dt->setSort(0);							
		$dt->setCustomColumn(
				array(		
						1 => '<div class=\"i_container\"><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></div>',		
						2 => '<div class=\"btn-group pull-right\"><a class=\"btn dropdown-toggle\" href=\"#\">Action <span class=\"caret\"></span></a><ul class=\"dropdown-menu\"><li><a href=\"' . url("company/edit_company?eid=id") . '\"><i class=\"icon-edit\"></i> Edit Company</a></li><li><a href=\"' . url("company/emp_list?eid=id") . '\"><i class=\"icon-list\"></i> View participants List</a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"company_archive_btn\" ><i class=\"icon-trash\"></i> Archive </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"company_mark_as_blacklisted_btn\" ><i class=\"icon-tag\"></i> Mark as Blacklisted </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"view_company_attended_btn\" ><i class=\"icon-list\"></i>  View Program Attended</a></li></ul></div>'
		));
		//echo "<pre>"; print_r($dt);
		echo $dt->constructDataTableRightTools();
	}

	function ajax_load_tardiness_list_dt()
	{
        $date  = date("Y-m-d");
        $from  = $_GET['from'];
        $to    = $_GET['to'];

        if($from != '' && $to != '') {

            $current_period_array = array();
            $current_p      = G_Cutoff_Period_Helper::sqlGetCutoffPeriodByStartEndDate($from, $to);
            if($current_p) {
		        $from_tardi = $current_p['period_start'];
		        $to_tardi   = $current_p['period_end'];            	
            } else {
		        $cutoff = new G_Cutoff_Period();
		        $current_period = $cutoff->getCurrentCutoffPeriod($date);  

		        $from_tardi = $current_period['current_cutoff']['start'];
		        $to_tardi   = $current_period['current_cutoff']['end'];             	
            }      	

        } else {

	        $current_period_array = array();
	        $current_p      = G_Cutoff_Period_Helper::sqlGetCurrentCutoffPeriod($date);
	        if($current_p) {
		        $from_tardi = $current_p['period_start'];
		        $to_tardi   = $current_p['period_end'];
	        } else {
		        $cutoff = new G_Cutoff_Period();
		        $current_period = $cutoff->getCurrentCutoffPeriod($date);  

		        $from_tardi = $current_period['current_cutoff']['start'];
		        $to_tardi   = $current_period['current_cutoff']['end'];         	
	        }

        }

    	$sql_from = date("Y-m-d",strtotime($from_tardi));
    	$sql_to   = date("Y-m-d",strtotime($to_tardi));                

		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(EMPLOYEE); 
		$dt->setSQL("
			SELECT e.id, CONCAT(' <a target=\"_blank\" href=" . url("notifications/get_link?module=tardiness&emp_code=' , e.employee_code , '") . "  > ', e.lastname , ', ' , e.firstname , '</a>') as employee_name, ea.late_hours, esh.name as department_name, DATE_FORMAT(ea.date_attendance,'%M %d, %Y') date_attendance
            FROM " . EMPLOYEE . " e	
            LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id 	
            LEFT JOIN " . G_EMPLOYEE_ATTENDANCE . " ea ON ea.employee_id = e.id
		");		
		$dt->setCountSQL("SELECT COUNT(e.id) as c FROM " . EMPLOYEE . " e LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id LEFT JOIN " . G_EMPLOYEE_ATTENDANCE . " ea ON ea.employee_id = e.id ");	
		$dt->setCondition(" ea.date_attendance BETWEEN " . Model::safeSql($sql_from) . " AND " . Model::safeSql($sql_to) . " AND ea.late_hours <> '' AND esh.end_date = '' ");
		$dt->setColumns('employee_name,date_attendance,department_name,late_hours');	
		$dt->setPreDefineSearch(
			array(				
				"employee_name" => "e.lastname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' OR e.firstname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'",
				"department_name" => "esh.name LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'"
			)
		); 	 
		$dt->setOrder('ASC');
		$dt->setSort(0);	

		/*$dt->setCustomColumn(
				array(		
						1 => '<div class=\"i_container\"><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></div>',		
						2 => '<div class=\"btn-group pull-right\"><a class=\"btn dropdown-toggle\" href=\"#\">Action <span class=\"caret\"></span></a><ul class=\"dropdown-menu\"><li><a href=\"' . url("company/edit_company?eid=id") . '\"><i class=\"icon-edit\"></i> Edit Company</a></li><li><a href=\"' . url("company/emp_list?eid=id") . '\"><i class=\"icon-list\"></i> View participants List</a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"company_archive_btn\" ><i class=\"icon-trash\"></i> Archive </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"company_mark_as_blacklisted_btn\" ><i class=\"icon-tag\"></i> Mark as Blacklisted </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"view_company_attended_btn\" ><i class=\"icon-list\"></i>  View Program Attended</a></li></ul></div>'
		));*/

		$dt->setCustomColumn(
				array(		
						1 => '<div class=\"i_container\"><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></div>',		
						2 => '<a target=\"_blank\" href=\"' . url("notifications/get_link?module=tardiness&sub_module=tardiness_dtr&eid=id&attendance_date=date_attendance") . '\"><i class=\"icon-edit\"></i> DTR</a><br /><a target=\"_blank\" href=\"' . url("notifications/get_link?module=tardiness&sub_module=tardiness_timesheet&eid=id&from=" . $from . "&to=" . $to . "") . '\"><i class=\"icon-list\"></i> Timesheet</a>'
		));

		echo $dt->constructDataTableRightTools();
	}

	function ajax_load_multiple_in_out_list_dt() 
	{
        $date   = date("Y-m-d");
        $cutoff = new G_Cutoff_Period();
        $current_period = $cutoff->getCurrentCutoffPeriod($date);  

        $from = $current_period['current_cutoff']['start'];
        $to   = $current_period['current_cutoff']['end'];   

    	$sql_from = date("Y-m-d",strtotime($from));
    	$sql_to   = date("Y-m-d",strtotime($to));                

		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(EMPLOYEE); 
		$dt->setSQL("
			SELECT e.id, CONCAT(' <a target=\"_blank\" href=" . url("notifications/get_link?module=tardiness&emp_code=' , fp_employee_code , '") . "  > ', e.lastname , ', ' , e.firstname , '</a>') as employee_name, al.date as attendance_date, al.type as atype, COUNT(eal.id) AS total_in_out, al.employee_code as fp_employee_code 
            FROM " . EMPLOYEE . " e	
            LEFT JOIN " . G_ATTENDANCE_LOG . " al ON e.id = al.user_id 	
		");		
		$dt->setCountSQL("SELECT COUNT(e.id) as c FROM " . EMPLOYEE . " e LEFT JOIN " . G_ATTENDANCE_LOG . " al ON e.id = al.user_id ");	
		$dt->setCondition(" al.date BETWEEN " . Model::safeSql($sql_from) . " AND al.employee_name != '' ");
		$dt->setColumns('employee_name,attendance_date,atype,total_in_out');	
		$dt->setPreDefineSearch(
			array(				
				"employee_name" => "e.lastname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' OR e.firstname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'"
			)
		); 	 
		$dt->setSQLGroupBy('GROUP BY attendance_date, fp_employee_code');
		$dt->setOrder('ASC');
		$dt->setSort(0);	

		$dt->setCustomColumn(
				array(		
						1 => '<div class=\"i_container\"><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></div>',		
						2 => '<a target=\"_blank\" href=\"' . url("notifications/get_link?module=tardiness&sub_module=tardiness_dtr&eid=id&attendance_date=date_attendance") . '\"><i class=\"icon-edit\"></i> DTR</a><br /><a target=\"_blank\" href=\"' . url("notifications/get_link?module=tardiness&sub_module=tardiness_timesheet&eid=id") . '\"><i class=\"icon-list\"></i> Timesheet</a>'
		));

		echo $dt->constructDataTableRightTools();		
	}

	function ajax_load_absent_list_dt() 
	{
        $date   = date("Y-m-d");
        $from  = $_GET['from'];
        $to    = $_GET['to'];

        if($from != '' && $to != '') {

            $current_period_array = array();
            $current_p      = G_Cutoff_Period_Helper::sqlGetCutoffPeriodByStartEndDate($from, $to);
            if($current_p) {
		        $from_abs = $current_p['period_start'];
		        $to_abs   = $current_p['period_end'];         	
            } else {
		        $cutoff = new G_Cutoff_Period();
		        $current_period = $cutoff->getCurrentCutoffPeriod($date);  

		        $from_abs = $current_period['current_cutoff']['start'];
		        $to_abs   = $current_period['current_cutoff']['end'];            	
            }      	

        } else {

	        $current_p      = G_Cutoff_Period_Helper::sqlGetCurrentCutoffPeriod($date);
	        if($current_p) {
		        $from_abs = $current_p['period_start'];
		        $to_abs   = $current_p['period_end'];
	        } else {
		        $cutoff = new G_Cutoff_Period();
		        $current_period = $cutoff->getCurrentCutoffPeriod($date);  

		        $from_abs = $current_period['current_cutoff']['start'];
		        $to_abs   = $current_period['current_cutoff']['end'];         	
	        }

        }

    	$sql_from = date("Y-m-d",strtotime($from_abs));
    	$sql_to   = date("Y-m-d",strtotime($to_abs));  

		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(EMPLOYEE); 
		$dt->setSQL("
			SELECT e.id, CONCAT(' <a target=\"_blank\" href=" . url("notifications/get_link?module=absent&emp_code=' , e.employee_code , '") . "  > ', e.lastname , ', ' , e.firstname , '</a>') as employee_name, DATE_FORMAT(ea.date_attendance,'%M %d, %Y') date_attendance, esh.name as department_name
            FROM " . G_EMPLOYEE_ATTENDANCE . " ea	
            LEFT JOIN " . EMPLOYEE . " e ON ea.employee_id = e.id
            LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id
		");		
		$dt->setCountSQL("SELECT COUNT(ea.id) as c FROM " . G_EMPLOYEE_ATTENDANCE . " ea LEFT JOIN " . EMPLOYEE . " e ON ea.employee_id = e.id LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id ");	
		$dt->setCondition(" ea.date_attendance BETWEEN " . Model::safeSql($sql_from) . " AND " . Model::safeSql($sql_to) . " AND (ea.is_present = 0 AND ea.is_restday = 0 AND ea.is_holiday = 0 AND ea.is_ob = 0 AND ea.is_leave = 0) AND e.employee_status_id = 1 AND esh.end_date = '' ");
		$dt->setColumns('employee_name,date_attendance,department_name');	
		$dt->setPreDefineSearch(
			array(				
				"employee_name" => "e.lastname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' OR e.firstname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'",
			)
		); 	 
		$dt->setOrder('ASC');
		$dt->setSort(0);	

		$dt->setCustomColumn(
				array(		
						1 => '<div class=\"i_container\"><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></div>',		
						2 => '<a target=\"_blank\" href=\"' . url("notifications/get_link?module=absent&sub_module=absent_dtr&eid=id&attendance_date=date_attendance") . '\"><i class=\"icon-edit\"></i> DTR</a><br /><a target=\"_blank\" href=\"' . url("notifications/get_link?module=absent&sub_module=absent_timesheet&eid=id&attendance_date=date_attendance&from=" . $from . "&to=" . $to . "") . '\"><i class=\"icon-list\"></i> Timesheet</a>'
		));

		echo $dt->constructDataTableRightTools();
	}

	function ajax_load_incomplete_dtr_list_dt()
	{
		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_ATTENDANCE);
		$dt->setSQL("
			SELECT e.id, CONCAT(' <a target=\"_blank\" href=" . url("notifications/get_link?module=incomplete_dtr&emp_code=' , e.employee_code , '") . "  > ', e.lastname , ', ' , e.firstname , '</a>') as employee_name, esh.name as department_name, 
				DATE_FORMAT(ea.date_attendance,'%M %d, %Y') as date_attendance, 
				ea.actual_time_in, ea.actual_time_out
            FROM " . G_EMPLOYEE_ATTENDANCE . " ea	
            LEFT JOIN " . EMPLOYEE . " e ON ea.employee_id = e.id
            LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id 	   
		");		
		$dt->setCountSQL("SELECT COUNT(e.id) as c FROM " . G_EMPLOYEE_ATTENDANCE . " ea	LEFT JOIN " . EMPLOYEE . " e ON ea.employee_id = e.id LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id ");	
		$dt->setCondition(" MONTH(ea.date_attendance) = MONTH(NOW()) AND ((ea.actual_time_in = '' AND ea.actual_time_out <> '') OR (ea.actual_time_out = '' AND ea.actual_time_in <> '')) ");
		$dt->setColumns('employee_name,department_name,date_attendance,actual_time_in,actual_time_out');	
		$dt->setPreDefineSearch(
			array(				
				"employee_name" => "e.lastname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' OR e.firstname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'",
				"department_name" => "esh.name LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'",
				"date_attendance" => "DATE_FORMAT(ea.date_attendance,'%M %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'"
			)
		); 	 
		$dt->setOrder('ASC');
		$dt->setSort(0);							
		$dt->setCustomColumn(
				array(		
						1 => '<div class=\"i_container\"><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></div>',		
						2 => '<div class=\"btn-group pull-right\"><a class=\"btn dropdown-toggle\" href=\"#\">Action <span class=\"caret\"></span></a><ul class=\"dropdown-menu\"><li><a href=\"' . url("company/edit_company?eid=id") . '\"><i class=\"icon-edit\"></i> Edit Company</a></li><li><a href=\"' . url("company/emp_list?eid=id") . '\"><i class=\"icon-list\"></i> View participants List</a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"company_archive_btn\" ><i class=\"icon-trash\"></i> Archive </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"company_mark_as_blacklisted_btn\" ><i class=\"icon-tag\"></i> Mark as Blacklisted </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"view_company_attended_btn\" ><i class=\"icon-list\"></i>  View Program Attended</a></li></ul></div>'
		));
		//echo "<pre>"; print_r($dt);
		echo $dt->constructDataTableRightTools();
	}

	function ajax_load_employee_with_no_schedule_list_dt()
	{
		$from = isset($_GET['from']) ? $_GET['from'] : '';
		$to   = isset($_GET['to']) ? $_GET['to'] : '';

		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(EMPLOYEE);

		if(!empty($from) && !empty($to)) {
			$dt->setSQL("
				SELECT e.id, CONCAT(' <a target=\"_blank\" href=" . url("notifications/get_link?&from=". $from ."&to=" .$to. "&module=employee_with_no_schedule&emp_code=' , e.employee_code , '") . "  > ', e.lastname , ', ' , e.firstname , '</a>') as employee_name, esh.name as department_name
	            FROM " . EMPLOYEE . " e	
	            LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id 	
			");
		} else {
			$dt->setSQL("
				SELECT e.id, CONCAT(' <a target=\"_blank\" href=" . url("notifications/get_link?module=employee_with_no_schedule&emp_code=' , e.employee_code , '") . "  > ', e.lastname , ', ' , e.firstname , '</a>') as employee_name, esh.name as department_name
	            FROM " . EMPLOYEE . " e	
	            LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id 	
			");				
		}

		$dt->setCountSQL("SELECT COUNT(e.id) as c FROM " . EMPLOYEE . " e LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id ");	
		$dt->setCondition(" 
				e.department_company_structure_id NOT IN (
            		SELECT employee_group_id
					FROM ". G_EMPLOYEE_GROUP_SCHEDULE ." es, ". G_SCHEDULE_GROUP ." g
					WHERE es.schedule_group_id = g.id
					AND es.employee_group = ". Model::safeSql(ENTITY_GROUP) ."
		            AND g.end_date >= NOW()
                ) AND e.id NOT IN (
            		SELECT employee_group_id
					FROM ". G_EMPLOYEE_GROUP_SCHEDULE ." es, ". G_SCHEDULE_GROUP ." g
					WHERE es.schedule_group_id = g.id
					AND es.employee_group = ". Model::safeSql(ENTITY_EMPLOYEE) ."
		            AND g.end_date >= NOW() )
		        AND e.employee_status_id = 1
		 ");
		$dt->setColumns('employee_name,department_name');	
		$dt->setPreDefineSearch(
			array(				
				"employee_name" => "e.lastname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' OR e.firstname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'",
				"department_name" => "esh.name LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'"
			)
		); 	 
		$dt->setSQLGroupBy('GROUP BY employee_name');
		$dt->setOrder('ASC');
		$dt->setSort(0);							
		$dt->setCustomColumn(
				array(		
						1 => '<div class=\"i_container\"><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></div>',		
						2 => '<div class=\"btn-group pull-right\"><a class=\"btn dropdown-toggle\" href=\"#\">Action <span class=\"caret\"></span></a><ul class=\"dropdown-menu\"><li><a href=\"' . url("company/edit_company?eid=id") . '\"><i class=\"icon-edit\"></i> Edit Company</a></li><li><a href=\"' . url("company/emp_list?eid=id") . '\"><i class=\"icon-list\"></i> View participants List</a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"company_archive_btn\" ><i class=\"icon-trash\"></i> Archive </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"company_mark_as_blacklisted_btn\" ><i class=\"icon-tag\"></i> Mark as Blacklisted </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"view_company_attended_btn\" ><i class=\"icon-list\"></i>  View Program Attended</a></li></ul></div>'
		));
		//echo "<pre>"; print_r($dt);
		echo $dt->constructDataTableRightTools();
	}

	function ajax_load_employee_with_incorrect_shift_list_dt()
	{
		Utilities::ajaxRequest();

		$from = isset($_GET['from']) ? $_GET['from'] : '';
		$to   = isset($_GET['to']) ? $_GET['to'] : '';

		$date   = date("Y-m-d",strtotime($this->c_date));

		if(!empty($from) && !empty($to)) {
			$date_from = $from;
			$date_to   = $to;
		} else {
	        $current_p      = G_Cutoff_Period_Helper::sqlGetCurrentCutoffPeriod($date);
	        if($current_p) {
	            $date_from = $current_p['period_start'];
	            $date_to   = $current_p['period_end'];
	        } else {
		        $cutoff = new G_Cutoff_Period();
		        $period = $cutoff->getCurrentCutoffPeriod($date);
		        $date_from = $period['current_cutoff']['start'];
		        $date_to   = $period['current_cutoff']['end'];
	        }			
		}

		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(EMPLOYEE);
		$dt->setSQL("
			SELECT e.id, e.employee_code, CONCAT(' <a target=\"_blank\" href=" . url("notifications/get_link?module=emp_with_incorrect_shift&from=" . $date_from . "&to=" . $date_to . "&emp_code=' , e.employee_code , '") . "  > ', e.lastname , ', ' , e.firstname , '</a>') as employee_name,DATE_FORMAT(ea.date_attendance,'%M %d, %Y')AS date_attendance, esh.name as department_name, ea.scheduled_time_in, ea.scheduled_time_out, ea.actual_time_in, ea.actual_time_out
			FROM " . G_EMPLOYEE_ATTENDANCE . " ea 
			LEFT JOIN " . EMPLOYEE . " e ON ea.employee_id = e.id 
			LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON ea.employee_id = esh.employee_id
		");

		$dt->setCountSQL("
			SELECT COUNT(ea.id) as c 
			FROM " . G_EMPLOYEE_ATTENDANCE . " ea 
			LEFT JOIN " . EMPLOYEE . " e ON ea.employee_id = e.id 
			LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON ea.employee_id = esh.employee_id
		");

		$dt->setCondition("
				esh.end_date = ''
				AND (ea.date_attendance BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . " AND ea.scheduled_time_in != '' AND ea.scheduled_time_out != '' )
			 	AND (ea.is_present = 1 AND ea.is_restday = 1 AND (ea.scheduled_date_in = '' OR ea.scheduled_date_out = '' ) )
			 	OR ( (ea.date_attendance BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to)  . ") 
			 	AND ea.scheduled_date_out <> '' AND ea.actual_date_in <> '' AND ea.scheduled_time_in != '' AND ea.scheduled_time_out != '' 
			 	AND STR_TO_DATE(CONCAT(ea.scheduled_date_out, ' ', ea.scheduled_time_out), '%Y-%m-%d %H:%i:%s') <= STR_TO_DATE(CONCAT(ea.actual_date_in, ' ', ea.actual_time_in), '%Y-%m-%d %H:%i:%s') ) 
		");

		$dt->setColumns('employee_name,department_name,date_attendance, ea.scheduled_time_in, ea.scheduled_time_out, ea.actual_time_in, ea.actual_time_out');	
		$dt->setPreDefineSearch(
			array(				
				"employee_name" => "e.lastname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' OR e.firstname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'",
				"department_name" => "esh.name LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'"
			)
		); 	 
		$dt->setOrder('ASC');
		$dt->setSQLGroupBy('GROUP BY e.id, ea.date_attendance');
		$dt->setSort(0);							
		$dt->setCustomColumn(
				array(		
						1 => '<div class=\"i_container\"><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></div>',		
						2 => '<a target=\"_blank\" href=\"' . url("notifications/get_link?module=emp_with_incorrect_shift&sub_module=emp_with_incorrect_shift_dtr&eid=id&attendance_date=date_attendance&from=" . $from . "&to=" . $to . "") . '\"><i class=\"icon-edit\"></i> DTR</a><br /><a target=\"_blank\" href=\"' . url("notifications/get_link?module=emp_with_incorrect_shift&sub_module=emp_with_incorrect_shift_timesheet&eid=id&from=" . $from . "&to=" . $to . "") . '\"><i class=\"icon-list\"></i> Timesheet</a><br /><a target=\"_blank\" href=\"' . url("notifications/get_link?module=emp_with_incorrect_shift&sub_module=emp_with_incorrect_shift_schedule&eid=id&from=" . $from . "&to=" . $to . "") . '\"><i class=\"icon-calendar\"></i> Schedule</a>'
		));
		echo $dt->constructDataTableRightTools();
	}

	/*
	 * Deprecated Date: 01/31/2017
	*/
	function ajax_load_employee_with_incorrect_shift_list_dtDepre()
	{
		Utilities::ajaxRequest();

		$date   = date("Y-m-d",strtotime($this->c_date));
        $cutoff = new G_Cutoff_Period();
        $period = $cutoff->getCurrentCutoffPeriod($date);
        $date_from = $period['current_cutoff']['start'];
        $date_to   = $period['current_cutoff']['end'];

		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(EMPLOYEE);
		$dt->setSQL("
			SELECT e.employee_code, CONCAT(' <a target=\"_blank\" href=" . url("notifications/get_link?module=employee_with_incorrect_shift&from=" . $date_from . "&to=" . $date_to . "&emp_code=' , e.employee_code , '") . "  > ', e.lastname , ', ' , e.firstname , '</a>') as employee_name,DATE_FORMAT(ea.date_attendance,'%M %d, %Y')AS date_attendance, esh.name as department_name 
			FROM " . G_EMPLOYEE_ATTENDANCE . " ea 
				LEFT JOIN " . EMPLOYEE . " e ON ea.employee_id = e.id 
				LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON ea.employee_id = esh.employee_id
		");		
		$dt->setCondition("(ea.date_attendance BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . ") AND (ea.is_present = 1 AND ea.is_restday = 1 AND esh.end_date = '') OR ( ea.scheduled_date_out <> '' AND ea.actual_date_in <> '' AND STR_TO_DATE(CONCAT(ea.scheduled_date_out, ' ', ea.scheduled_time_out), '%Y-%m-%d %H:%i:%s') <= STR_TO_DATE(CONCAT(ea.actual_date_in, ' ', ea.actual_time_in), '%Y-%m-%d %H:%i:%s' AND esh.end_date = '') )");
		$dt->setColumns('employee_name,department_name,date_attendance');	
		$dt->setPreDefineSearch(
			array(				
				"employee_name" => "e.lastname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' OR e.firstname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'",
				"department_name" => "esh.name LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'"
			)
		); 	 
		$dt->setOrder('ASC');
		$dt->setSort(0);							
		$dt->setCustomColumn(
				array(		
						1 => '<div class=\"i_container\"><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></div>',		
						2 => '<div class=\"btn-group pull-right\"><a class=\"btn dropdown-toggle\" href=\"#\">Action <span class=\"caret\"></span></a><ul class=\"dropdown-menu\"><li><a href=\"' . url("company/edit_company?eid=id") . '\"><i class=\"icon-edit\"></i> Edit Company</a></li><li><a href=\"' . url("company/emp_list?eid=id") . '\"><i class=\"icon-list\"></i> View participants List</a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"company_archive_btn\" ><i class=\"icon-trash\"></i> Archive </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"company_mark_as_blacklisted_btn\" ><i class=\"icon-tag\"></i> Mark as Blacklisted </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"view_company_attended_btn\" ><i class=\"icon-list\"></i>  View Program Attended</a></li></ul></div>'
		));
		//echo "<pre>"; print_r($dt);
		echo $dt->constructDataTableRightTools();
	}

	function ajax_load_employee_upcoming_birthday_list_dt()
	{
		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(EMPLOYEE);
		
		/*
		$dt->setSQL("
			SELECT e.id, CONCAT(e.lastname , ', ' , e.firstname , '</a>') as employee_name, DATE_FORMAT(e.birthdate,'%M %d, %Y') as birthdate, esh.name as department_name
            FROM " . EMPLOYEE . " e	
            LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id 	
		");		
		$dt->setCountSQL("SELECT COUNT(e.id) as c FROM " . EMPLOYEE . " e LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id ");
		*/	


		$dt->setSQL("
			SELECT e.id, CONCAT(e.lastname , ', ' , e.firstname , '</a>') as employee_name, DATE_FORMAT(e.birthdate,'%M %d, %Y') as birthdate, 
			COALESCE((
                    SELECT name FROM `g_employee_subdivision_history` esh
                    WHERE employee_id = e.id 
                    ORDER BY end_date ASC
                    LIMIT 1
                ))AS department_name
            FROM " . EMPLOYEE . " e	
            LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id 	
		");		

		//$dt->setCountSQL("SELECT COUNT(e.id) as c FROM " . EMPLOYEE . " e LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id ");	
		$dt->setCountSQL("SELECT COUNT(e.id) as c FROM " . EMPLOYEE . " e LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id ");	
		
		$start_date = date("m-d", strtotime("+1 day"));
		$end_date = date("m-d", strtotime("+30 days"));

		$dt->setCondition(" DATE_FORMAT(e.birthdate,'%m-%d') >= '{$start_date}' AND DATE_FORMAT(e.birthdate,'%m-%d') <= '{$end_date}' AND e.employee_status_id = 1 ");
		$dt->setColumns('employee_name,department_name,birthdate');	
		$dt->setPreDefineSearch(
			array(				
				"employee_name" => "e.lastname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' OR e.firstname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'",
				"department_name" => "esh.name LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'",
				"birthdate" => "DATE_FORMAT(e.birthdate,'%M %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'"
			)
		); 	 
		$dt->setSQLGroupBy('GROUP BY employee_name');
		$dt->setOrder('ASC');
		$dt->setSort(0);							
		$dt->setCustomColumn(
				array(		
						1 => '<div class=\"i_container\"><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></div>',		
						2 => '<div class=\"btn-group pull-right\"><a class=\"btn dropdown-toggle\" href=\"#\">Action <span class=\"caret\"></span></a><ul class=\"dropdown-menu\"><li><a href=\"' . url("company/edit_company?eid=id") . '\"><i class=\"icon-edit\"></i> Edit Company</a></li><li><a href=\"' . url("company/emp_list?eid=id") . '\"><i class=\"icon-list\"></i> View participants List</a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"company_archive_btn\" ><i class=\"icon-trash\"></i> Archive </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"company_mark_as_blacklisted_btn\" ><i class=\"icon-tag\"></i> Mark as Blacklisted </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"view_company_attended_btn\" ><i class=\"icon-list\"></i>  View Program Attended</a></li></ul></div>'
		));
		//echo "<pre>"; print_r($dt);
		echo $dt->constructDataTableRightTools();
	}

	function ajax_load_employee_birthday_today_list_dt()
	{
		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(EMPLOYEE);
		$dt->setSQL("
			SELECT e.id, CONCAT(e.lastname , ', ' , e.firstname , '</a>') as employee_name, esh.name as department_name
            FROM " . EMPLOYEE . " e	
            LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id 	
		");		
		$dt->setCountSQL("SELECT COUNT(e.id) as c FROM " . EMPLOYEE . " e LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id ");	
		
		$date = date("m-d");

		$dt->setCondition(" DATE_FORMAT(e.birthdate,'%m-%d') = '{$date}' AND e.employee_status_id = 1");
		$dt->setColumns('employee_name,department_name');	
		$dt->setPreDefineSearch(
			array(				
				"employee_name" => "e.lastname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' OR e.firstname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'",
				"department_name" => "esh.name LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'",
				"birthdate" => "DATE_FORMAT(e.birthdate,'%M %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'"
			)
		); 	 
		$dt->setSQLGroupBy('GROUP BY employee_name');
		$dt->setOrder('ASC');
		$dt->setSort(0);							
		$dt->setCustomColumn(
				array(		
						1 => '<div class=\"i_container\"><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></div>',		
						2 => '<div class=\"btn-group pull-right\"><a class=\"btn dropdown-toggle\" href=\"#\">Action <span class=\"caret\"></span></a><ul class=\"dropdown-menu\"><li><a href=\"' . url("company/edit_company?eid=id") . '\"><i class=\"icon-edit\"></i> Edit Company</a></li><li><a href=\"' . url("company/emp_list?eid=id") . '\"><i class=\"icon-list\"></i> View participants List</a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"company_archive_btn\" ><i class=\"icon-trash\"></i> Archive </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"company_mark_as_blacklisted_btn\" ><i class=\"icon-tag\"></i> Mark as Blacklisted </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"view_company_attended_btn\" ><i class=\"icon-list\"></i>  View Program Attended</a></li></ul></div>'
		));
		//echo "<pre>"; print_r($dt);
		echo $dt->constructDataTableRightTools();
	}

	function _leave_credit_update_notication()
	{
		$this->view->render('notifications/_leave_credit_update_notication.php',$this->var);  		
	}	

	function ajax_no_payslip_found_list_dt()
	{
		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(EMPLOYEE);
		$dt->setSQL("
			SELECT e.id, CONCAT(e.lastname , ', ' , e.firstname , '</a>') as employee_name, esh.name as department_name
            FROM " . EMPLOYEE . " e	
            LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id 	
		");		
		$dt->setCountSQL("SELECT COUNT(e.id) as c FROM " . EMPLOYEE . " e LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id ");	
		
		$date = date("m-d");

		$dt->setCondition(" DATE_FORMAT(e.birthdate,'%m-%d') = '{$date}' AND e.employee_status_id = 1");
		$dt->setColumns('employee_name,department_name');	
		$dt->setPreDefineSearch(
			array(				
				"employee_name" => "e.lastname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' OR e.firstname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'",
				"department_name" => "esh.name LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'",
				"birthdate" => "DATE_FORMAT(e.birthdate,'%M %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'"
			)
		); 	 
		$dt->setSQLGroupBy('GROUP BY employee_name');
		$dt->setOrder('ASC');
		$dt->setSort(0);							
		$dt->setCustomColumn(
				array(		
						1 => '<div class=\"i_container\"><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></div>',		
						2 => '<div class=\"btn-group pull-right\"><a class=\"btn dropdown-toggle\" href=\"#\">Action <span class=\"caret\"></span></a><ul class=\"dropdown-menu\"><li><a href=\"' . url("company/edit_company?eid=id") . '\"><i class=\"icon-edit\"></i> Edit Company</a></li><li><a href=\"' . url("company/emp_list?eid=id") . '\"><i class=\"icon-list\"></i> View participants List</a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"company_archive_btn\" ><i class=\"icon-trash\"></i> Archive </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"company_mark_as_blacklisted_btn\" ><i class=\"icon-tag\"></i> Mark as Blacklisted </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"view_company_attended_btn\" ><i class=\"icon-list\"></i>  View Program Attended</a></li></ul></div>'
		));
		//echo "<pre>"; print_r($dt);
		echo $dt->constructDataTableRightTools();
	}



	function _ajax_load_employee_eval_dt(){


		$date = date('Y-m-d', strtotime('now'));

		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_EVALUATION);
		$dt->setSQL("
				SELECT
				ev.id,
			ev.score as score,
			ev.next_evaluation_date as evaluation_date,
			company_branch.name as  branch_name,
			`d`.`name` AS `department`,
			(SELECT title FROM `g_company_structure` WHERE id = e.section_id LIMIT 1)AS section_name,
			`e`.`employee_code`,
			CONCAT(e.lastname,', ',e.firstname,' ',substring(e.middlename,1,1),'. ', e.extension_name) AS `employee_name`,
			`j`.`name` AS `position`, e.employee_code as employee_id
			
	
			FROM `g_employee_evaluation` AS `ev`
			inner Join `g_employee` AS `e`  ON `e`.`id` = `ev`.`employee_id`
			Left Join `g_employee_subdivision_history` AS `d` ON `ev`.`employee_id` = `d`.`employee_id` AND `d`.`end_date` = ''
			Left Join `g_employee_branch_history` AS `b` ON `ev`.`employee_id` = `b`.`employee_id` AND `b`.`end_date` = ''
			Left Join g_company_branch as company_branch ON b.company_branch_id=company_branch.id
			Left Join `g_employee_job_history` AS `j` ON  (`j`.`employee_id` = `ev`.`employee_id` AND `j`.`end_date` = '' )
			
			Inner Join `g_company_structure` AS `company` ON `e`.`company_structure_id` = `company`.`id`
			 
			where ev.is_archive = ". Model::safeSql(G_Employee_Evaluation::NO)." AND ev.employee_id = e.id
			 AND ev.next_evaluation_date='".$date."' AND is_updated != ". Model::safeSql(G_Employee_Evaluation::YES)."
			");		
		$dt->setCountSQL("SELECT COUNT(c.id) as c FROM " . G_EMPLOYEE_EVALUATION . " c");	
		

		//$dt->setCondition("eob.is_archive = ". Model::safeSql(G_Employee_Evaluation::NO)." AND eob.employee_id = e.id");
		$dt->setColumns('branch_name,department,section_name,employee_id,employee_name, position, evaluation_date');	
		$dt->setOrder('ASC');
		$dt->setSort(0);							
		$dt->setCustomColumn(
				array(		
						1 => '<div class=\"i_container\"></div>',
						2 => '<div class=\"i_container\"><ul class=\"dt_icons\" style=\"display:inline-flex\"><li><a title=\"Edit\" id=\"edit\" href=\"javascript:void(0);\" onclick=\"javascript:createEvaluationNotif(\'e_id\');\">Evaluate Now</a></li><li><a href=\"' . url("evaluation") . '\" target=\"_blank\"> View List</a></li></ul></div>'
		));
		//echo "<pre>"; print_r($dt);
		echo $dt->constructDataTableRightTools();

	}	



	function ajax_create_employee_evaluation(){

			

			$this->var['eval']			 = $eval = G_Employee_Evaluation_Finder::findById(Utilities::decrypt($_POST['evid']));
			$this->var['token']		     = Utilities::createFormToken();		
			$this->var['page_title']     = 'Edit Employee Evaluation';		
			//$this->var['has_started']	 = (strtotime(date("Y-m-d")) >= strtotime($eval->getStartDate()) ? 1 : 0);
			$emp = G_Employee_Finder::findById($eval->getEmployeeId());

			$this->var['emp']	 = 	$emp->getFullname();

			$this->view->render('employee/evaluation/form/edit_evaluation.php',$this->var);

	}


	function _ajax_load_employee_upcoming_eval_dt(){

		$dateNow = date('Y-m-d', strtotime('+1 day'));
		$date = date('Y-m-d', strtotime($dateNow."+5 days"));

		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_EVALUATION);
		$dt->setSQL("
				SELECT
				ev.id,
			ev.score as score,
			ev.next_evaluation_date as evaluation_date,
			company_branch.name as  branch_name,
			`d`.`name` AS `department`,
			(SELECT title FROM `g_company_structure` WHERE id = e.section_id LIMIT 1)AS section_name,
			`e`.`employee_code`,
			CONCAT(e.lastname,', ',e.firstname,' ',substring(e.middlename,1,1),'. ', e.extension_name) AS `employee_name`,
			`j`.`name` AS `position`, e.employee_code as employee_id
			
	
			FROM `g_employee_evaluation` AS `ev`
			inner Join `g_employee` AS `e`  ON `e`.`id` = `ev`.`employee_id`
			Left Join `g_employee_subdivision_history` AS `d` ON `ev`.`employee_id` = `d`.`employee_id` AND `d`.`end_date` = ''
			Left Join `g_employee_branch_history` AS `b` ON `ev`.`employee_id` = `b`.`employee_id` AND `b`.`end_date` = ''
			Left Join g_company_branch as company_branch ON b.company_branch_id=company_branch.id
			Left Join `g_employee_job_history` AS `j` ON  (`j`.`employee_id` = `ev`.`employee_id` AND `j`.`end_date` = '' )
			
			Inner Join `g_company_structure` AS `company` ON `e`.`company_structure_id` = `company`.`id`
			 
			where ev.is_archive = ". Model::safeSql(G_Employee_Evaluation::NO)." AND ev.employee_id = e.id AND
			  ev.next_evaluation_date >= '".$dateNow."'  AND  ev.next_evaluation_date <= '".$date."' 
			");		
		$dt->setCountSQL("SELECT COUNT(c.id) as c FROM " . G_EMPLOYEE_EVALUATION . " c");	
		

		//$dt->setCondition("eob.is_archive = ". Model::safeSql(G_Employee_Evaluation::NO)." AND eob.employee_id = e.id");
		$dt->setColumns('branch_name,department,section_name,employee_id,employee_name, position, evaluation_date');	
		$dt->setOrder('ASC');
		$dt->setSort(0);							
		$dt->setCustomColumn(
				array(		
						1 => '<div class=\"i_container\"></div>',
						2 => '<div class=\"i_container\"><ul class=\"dt_icons\" style=\"display:inline-flex\"><li><a href=\"' . url("evaluation") . '\" target=\"_blank\"> View List</a></li></ul></div>'
		));
		//echo "<pre>"; print_r($dt);
		echo $dt->constructDataTableRightTools();

	}	




}
?>