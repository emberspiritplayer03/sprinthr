<?php
class Leave_Controller extends Controller
{
	function __construct()
	{	
		parent::__construct();

		Loader::appStyle('style.css');
		Loader::appMainScript('leave.js');
		Loader::appMainScript('leave_base.js');

		$frequency_id = 1;

		if( isset($_GET['selected_frequency']) && !empty($_GET['selected_frequency']) ) {
			$frequency_id = $_GET['selected_frequency'];
		}
		
		$this->sprintHdrMenu(G_Sprint_Modules::HR, 'attendance');		

		if($_GET['hpid']){

			if ($frequency_id == 2) {
				$this->var['is_period_lock'] = $_SESSION['sprint_hr']['is_period_lock'] = G_Weekly_Cutoff_Period_Helper::isPeriodLock($hpid);
			}
			else if ($frequency_id == 3) {
				$this->var['is_period_lock'] = $_SESSION['sprint_hr']['is_period_lock'] = G_Monthly_Cutoff_Period_Helper::isPeriodLock($_GET['hpid']);
			}
			else {
				$this->var['is_period_lock'] = $_SESSION['sprint_hr']['is_period_lock'] = G_Cutoff_Period_Helper::isPeriodLock($_GET['hpid']);
			}

		}else{			
			$this->var['is_period_lock'] = $_SESSION['sprint_hr']['is_period_lock'];
		}
		
		if( isset($_GET['cutoff_period']) ) {
			$cutoff_period_arr = explode("/", $_GET['cutoff_period']);
			$period_start      = $cutoff_period_arr[0];
			$period_end   	   = $cutoff_period_arr[1];
			$this->var['cutoff_selected'] = $period_start."/".$period_end;

			if ($frequency_id == 2) {
				$cutoff_data  	   = G_Weekly_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}
				else if ($frequency_id == 3) {
				$cutoff_data  	   = G_Monthly_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}
			else {
				$cutoff_data  	   = G_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}

			if($cutoff_data) {
				$from 	   = $cutoff_data->getStartDate();
				$to        = $cutoff_data->getEndDate();
				$cutoff_id = $cutoff_data->getId();

				$this->var['get_from'] = $from;
				$this->var['get_to']   = $to;
				$this->var['get_hpid'] = Utilities::encrypt($cutoff_id);

				$this->var['download_url']    = url('leave/download_leave?from=' . $from . '&to=' . $to . '&hpid=' . Utilities::encrypt($cutoff_id) . '&selected_frequency=' . $frequency_id);
				$this->var['period_selected'] = '<small style="font-size:15px;">Period: <b>' . Tools::convertDateFormat($from) . ' </b> to <b>' . Tools::convertDateFormat($to) . '</b></small>';
			}
		} elseif($_GET['from'] && $_GET['to'] && $_GET['hpid']){			
			$this->var['download_url']    = url('leave/download_leave?from=' . $_GET['from'] . '&to=' . $_GET['to'] . '&hpid=' . $_GET['hpid'] . '&selected_frequency=' . $frequency_id);
			$this->var['period_selected'] = '<small style="font-size:15px;">Period: <b>' . Tools::convertDateFormat($_GET['from']) . ' </b> to <b>' . Tools::convertDateFormat($_GET['to']) . '</b></small>';
		}
		
		$this->eid                  = $this->global_user_eid;
		$this->c_date  				= Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila');
		$this->default_method       = 'index';					
		$this->var['leave']         = 'selected';			
		$this->var['employee']      = 'selected';
		$this->var['eid']           = $this->eid;	

		$this->var['departments']   = G_Company_Structure_Finder::findByParentID(Utilities::decrypt($this->global_user_ecompany_structure_id));
		$this->company_structure_id = Utilities::decrypt($this->global_user_ecompany_structure_id);

		$this->validatePermission(G_Sprint_Modules::HR,'attendance','attendance_leave');
	}
	
	function index() {
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();

		$frequency_id = 1;

		if( isset($_GET['selected_frequency']) && !empty($_GET['selected_frequency']) ) {
			$frequency_id = $_GET['selected_frequency'];
		}
		
		$this->var['selected_frequency'] = $frequency_id;

        $this->var['page_title'] = 'Leave Requests';
       
        $now = date('Y-m-d');

     if ($frequency_id == 2) {
	    	$p = G_Weekly_Cutoff_Period_Finder::findByDate($now);
     }
      else if ($frequency_id == 3) {
	    	$p = G_Monthly_Cutoff_Period_Finder::findByDate($now);
     }
     else {
	    	$p = G_Cutoff_Period_Finder::findByDate($now);
     }

	    if ($p) {
	        $hpid = Utilities::encrypt($p->getId());
	        $from_date = $p->getStartDate();
	        $to_date = $p->getEndDate();
	    }		
		
        redirect("leave/period?from={$from_date}&to={$to_date}&hpid={$hpid}&selected_frequency={$frequency_id}");
	}

	function period()
	{
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();
		$enable_next_previous_link = false;

		$frequency_id = 1;

		if( isset($_GET['selected_frequency']) && !empty($_GET['selected_frequency']) ) {
			$frequency_id = $_GET['selected_frequency'];
		}
		
		$this->var['selected_frequency'] = $frequency_id;

	
		if( isset($_GET['cutoff_period']) ) {
			$cutoff_period_arr = explode("/", $_GET['cutoff_period']);
			$period_start      = $cutoff_period_arr[0];
			$period_end   	   = $cutoff_period_arr[1];
			$this->var['cutoff_selected'] = $period_start."/".$period_end;

			if ($frequency_id == 2) {
				$cutoff_data  	   = G_Weekly_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}
			else if ($frequency_id == 3) {
				$cutoff_data  	   = G_Monthly_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}
			else {
				$cutoff_data  	   = G_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}

			if($cutoff_data) {
				$from 	   = $cutoff_data->getStartDate();
				$to        = $cutoff_data->getEndDate();
				$cutoff_id = $cutoff_data->getId();
			}


			//General Reports / Shr Audit Trail
	        list($p_year, $p_month, $p_day) = explode('-', $from);
	        $pmonthN = date("F", mktime(0, 0, 0, $p_month, 10));
	        if($p_day >= 16){
	        	$cut_of_period = $p_year.'-'.$pmonthN.'-B';
	        }
	        else{
	            $cut_of_period = $p_year.'-'.$pmonthN.'-A';	
	        }

			if($_GET['selected_frequency'] == 1){
				$frequency_name = 'Bi-Monthly';
			}
			else{
				$frequency_name = 'Weekly';
			}

			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_LOAD, ' Pending Leave Requests of ',' Cut Off Period of '.$cut_of_period.' and '.$frequency_name.' Frequency', $from, $to, 1, '', '');	

		} else {
			$from = $_GET['from'];
			$to   = $_GET['to'];
			$this->var['cutoff_selected'] = $from . "/" . $to;
		}

		$this->var['from_period']	= isset($_GET['from']) ? $_GET['from'] : $from;
		$this->var['to_period']		= isset($_GET['to']) ? $_GET['to'] : $to;
		
		$this->var['recent']        = 'class="selected"';
		$this->var['import_action'] = url('leave/_import_leave_excel');
		$this->var['page_title']    = 'Leave Requests';
		$this->var['module'] 		= 'leave'; 

		$date = date("Y-m-d",strtotime($from)); 

		if ($frequency_id == 2) {
			$cp   = new G_Weekly_Cutoff_Period();
			$cutoff_data = $cp->getCurrentCutoffPeriod($date);	

			$cutoff_d = G_Weekly_Cutoff_Period_Helper::sqlCutoffPeriodByPeriodStartAndPeriodEnd($_GET['from'], $_GET['to']);
		}
		else if ($frequency_id == 3) {
			$cp   = new G_Monthly_Cutoff_Period();
			$cutoff_data = $cp->getCurrentCutoffPeriod($date);	

			$cutoff_d = G_Monthly_Cutoff_Period_Helper::sqlCutoffPeriodByPeriodStartAndPeriodEnd($_GET['from'], $_GET['to']);
		}
		else {
			$cp   = new G_Cutoff_Period();
			$cutoff_data = $cp->getCurrentCutoffPeriod($date);	

			$cutoff_d = G_Cutoff_Period_Helper::sqlCutoffPeriodByPeriodStartAndPeriodEnd($_GET['from'], $_GET['to']);
		}

		if($cutoff_d) {
			$this->var['hdr_is_cutoff_period_lock'] = $cutoff_d['is_lock'];
		} else {
			if( $cutoff_data ){
				$this->var['hdr_is_cutoff_period_lock'] = $cutoff_data['is_lock'];
			}else{
				$this->var['hdr_is_cutoff_period_lock'] = "Yes";
			}			
		}

		$this->view->setTemplate('template_leftsidebar.php');

		$eid       = isset($_GET['hpid']) ? $_GET['hpid'] : Utilities::encrypt($cutoff_id);
        $cutoff_id = isset($_GET['hpid']) ? Utilities::decrypt($_GET['hpid']) : $cutoff_id; 
        $from_date = $from; //$_GET['from'];
        $to_date   = $to; //$_GET['to'];

        if($enable_next_previous_link) {
	        $cutoff_year = date('Y', strtotime($to_date));
			$date = $from_date;

			if ($frequency_id == 2) {
				$c = new G_Weekly_Cutoff_Period();
			}
			else if ($frequency_id == 3) {
				$c = new G_Monthly_Cutoff_Period();
			}
			else {
				$c = new G_Cutoff_Period();
			}

			$previous_cutoff_data = $c->getPreviousCutOffByDate($date, $cutoff_year);
			$next_cutoff_data = $c->getNextCutOffByDate($date, $cutoff_year);

			$next_from = $next_cutoff_data['start_date'];
			$next_to   = $next_cutoff_data['end_date'];
			$next_id   = $next_cutoff_data['eid'];
			if( !empty($next_from) ){
				$this->var['next_cutoff_link'] = url("leave/period?from={$next_from}&to={$next_to}&hpid={$next_id}&selected_frequency={$frequency_id}");
			}else{
				$this->var['next_cutoff_link'] = url("leave/period?from={$from_date}&to={$to_date}&hpid={$eid}&selected_frequency={$frequency_id}");
			}

			$previous_from = $previous_cutoff_data['start_date'];
			$previous_to   = $previous_cutoff_data['end_date'];
			$previous_id   = $previous_cutoff_data['eid'];
			if( !empty($previous_from) ){
				$this->var['previous_cutoff_link'] = url("leave/period?from={$previous_from}&to={$previous_to}&hpid={$previous_id}&selected_frequency={$frequency_id}");
			}else{
				$this->var['previous_cutoff_link'] = url("leave/period?from={$from_date}&to={$to_date}&hpid={$eid}&selected_frequency={$frequency_id}");
			}
        }

        $btn_request_leave_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'attendance',
    		'child_index'			=> 'attendance_leave',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:show_request_leave_form('.$frequency_id.');',
    		'id' 					=> 'request_leave_button',
    		'class' 				=> 'add_button',
    		'icon' 					=> '',
    		'additional_attribute'	=> '',
    		'caption' 				=> '<strong>+</strong><b>Request Leave</b>'
    		); 

    	$btn_import_leave_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'attendance',
    		'child_index'			=> 'attendance_leave',
    		'href' 					=> '#',
    		'onclick' 				=> 'javascript:importLeave();',
    		'id' 					=> 'import_leave_button_wrapper',
    		'class' 				=> 'add_button pull-right',
    		'icon' 					=> '<i class="icon-arrow-left"></i>',
    		'additional_attribute'	=> '',
    		'caption' 				=> 'Import Leave'
    		); 
		
		$this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::HR,'attendance','attendance_leave');
		$this->var['btn_request_leave'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_request_leave_config);
        $this->var['btn_import_leave'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_import_leave_config);

        $this->var['year_selected']     = $_GET['year_selected'];

								if ($frequency_id == 2) {
        	$all_payroll_years 				= G_Weekly_Cutoff_Period_Helper::sqlGetAllExistYearTags();
								}
								else if ($frequency_id == 3) {
        	$all_payroll_years 				= G_Monthly_Cutoff_Period_Helper::sqlGetAllExistYearTags();
								}
								else {
        	$all_payroll_years 				= G_Cutoff_Period_Helper::sqlGetAllExistYearTags();
								}

        $this->var['all_cutoff_years'] 	= $all_payroll_years;

		$this->view->render('leave/period.php',$this->var);
	} 
	
	function approved()
	{		
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();
		$enable_next_previous_link = false;

		$frequency_id = 1;

		if( isset($_GET['selected_frequency']) && !empty($_GET['selected_frequency']) ) {
			$frequency_id = $_GET['selected_frequency'];
		}
		
		$this->var['selected_frequency'] = $frequency_id;

        $date = $_GET['from'];

		if( isset($_GET['cutoff_period']) ) {
			$cutoff_period_arr = explode("/", $_GET['cutoff_period']);
			$period_start      = $cutoff_period_arr[0];
			$period_end   	   = $cutoff_period_arr[1];
			$this->var['cutoff_selected'] = $period_start."/".$period_end;

			if ($frequency_id == 2) {
				$cutoff_data  	   = G_Weekly_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}
				else if ($frequency_id == 3) {
				$cutoff_data  	   = G_Monthly_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}
			else {
				$cutoff_data  	   = G_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}

			if($cutoff_data) {
				$from 	   = $cutoff_data->getStartDate();
				$to        = $cutoff_data->getEndDate();
				$cutoff_id = $cutoff_data->getId();
			}

			//General Reports / Shr Audit Trail
	        list($p_year, $p_month, $p_day) = explode('-', $from);
	        $pmonthN = date("F", mktime(0, 0, 0, $p_month, 10));
	        if($p_day >= 16){
	        	$cut_of_period = $p_year.'-'.$pmonthN.'-B';
	        }
	        else{
	            $cut_of_period = $p_year.'-'.$pmonthN.'-A';	
	        }

			if($_GET['selected_frequency'] == 1){
				$frequency_name = 'Bi-Monthly';
			}
			else{
				$frequency_name = 'Weekly';
			}

			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_LOAD, ' Approved Leave Requests of ',' Cut Off Period of '.$cut_of_period.' and '.$frequency_name.' Frequency', $from, $to, 1, '', '');	

		} else {
			$from = $_GET['from'];
			$to   = $_GET['to'];
			$this->var['cutoff_selected'] = $from . "/" . $to;
		}        

        if($enable_next_previous_link) {

        	if ($frequency_id == 2) {
				$c = new G_Weekly_Cutoff_Period();
			}
			else if ($frequency_id == 3) {
										$c = new G_Monthly_Cutoff_Period();
									}
			else {
				$c = new G_Cutoff_Period();
			}

			$previous_cutoff_data = $c->getPreviousCutOffByDate($date);
			$next_cutoff_data = $c->getNextCutOffByDate($date);

			$next_from = $next_cutoff_data['start_date'];
			$next_to   = $next_cutoff_data['end_date'];
			$next_id   = $next_cutoff_data['eid'];

			$previous_from = $previous_cutoff_data['start_date'];
			$previous_to   = $previous_cutoff_data['end_date'];
			$previous_id   = $previous_cutoff_data['eid'];
			$this->var['previous_cutoff_link'] = url("leave/approved?from={$previous_from}&to={$previous_to}&hpid={$previous_id}");
			$this->var['next_cutoff_link']     = url("leave/approved?from={$next_from}&to={$next_to}&hpid={$next_id}");			
        }

        $this->var['year_selected']     = $_GET['year_selected'];

       	if ($frequency_id == 2) {
        	$all_payroll_years 				= G_Cutoff_Period_Helper::sqlGetAllExistYearTags();
								}
								else if ($frequency_id == 3) {
        	$all_payroll_years 				= G_Monthly_Cutoff_Period_Helper::sqlGetAllExistYearTags();
								}
								else {
        	$all_payroll_years 				= G_Cutoff_Period_Helper::sqlGetAllExistYearTags();
								}

        $this->var['all_cutoff_years'] 	= $all_payroll_years;        

		$this->var['from_period']	= $from;
		$this->var['to_period']		= $to;		
		$this->var['approved']      = 'class="selected"';
		$this->var['import_action'] = url('leave/_import_leave_excel');
		$this->var['page_title']    = 'Leave Requests';
		$this->var['module'] 		= 'leave'; 

		$this->view->setTemplate('template_leftsidebar.php');
		$this->view->render('leave/approved.php',$this->var);		
	}

	function incentive_leave()
	{		
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();
		$enable_next_previous_link = false;
        $date = $_GET['from'];

		if( isset($_GET['cutoff_period']) ) {
			$cutoff_period_arr = explode("/", $_GET['cutoff_period']);
			$period_start      = $cutoff_period_arr[0];
			$period_end   	   = $cutoff_period_arr[1];
			$this->var['cutoff_selected'] = $period_start."/".$period_end;
			$cutoff_data  	   = G_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);

			if($cutoff_data) {
				$from 	   = $cutoff_data->getStartDate();
				$to        = $cutoff_data->getEndDate();
				$cutoff_id = $cutoff_data->getId();
			}
		} else {
			$from = $_GET['from'];
			$to   = $_GET['to'];
			$this->var['cutoff_selected'] = $from . "/" . $to;
		}  

		if($enable_next_previous_link) {
			$c = new G_Cutoff_Period();
			$previous_cutoff_data = $c->getPreviousCutOffByDate($date);
			$next_cutoff_data = $c->getNextCutOffByDate($date);

			$next_from = $next_cutoff_data['start_date'];
			$next_to   = $next_cutoff_data['end_date'];
			$next_id   = $next_cutoff_data['eid'];

			$previous_from = $previous_cutoff_data['start_date'];
			$previous_to   = $previous_cutoff_data['end_date'];
			$previous_id   = $previous_cutoff_data['eid'];	
			$this->var['previous_cutoff_link'] = url("leave/approved?from={$previous_from}&to={$previous_to}&hpid={$previous_id}");
			$this->var['next_cutoff_link']     = url("leave/approved?from={$next_from}&to={$next_to}&hpid={$next_id}");					
		}

        $this->var['year_selected']     = $_GET['year_selected'];
        $all_payroll_years 				= G_Cutoff_Period_Helper::sqlGetAllExistYearTags();
        $this->var['all_cutoff_years'] 	= $all_payroll_years;    		

		$this->var['from_period']	= $from;
		$this->var['to_period']		= $to;		
		$this->var['incentive']     = 'class="selected"';		
		$this->var['page_title']    = 'Incentive Leave';
		$this->var['module'] 		= 'leave'; 
		$this->var['months']		= array(1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April' , 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December');
		$this->var['end_year']      = date("Y");
		$this->var['start_year']    = 2015;
		$this->view->setTemplate('template_leftsidebar.php');
		$this->view->render('leave/incentive_leave.php',$this->var);		
	}

	function disapproved()
	{		
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();
		$enable_next_previous_link = false;

		$frequency_id = 1;

		if( isset($_GET['selected_frequency']) && !empty($_GET['selected_frequency']) ) {
			$frequency_id = $_GET['selected_frequency'];
		}
		
		$this->var['selected_frequency'] = $frequency_id;

		if( isset($_GET['cutoff_period']) ) {
			$cutoff_period_arr = explode("/", $_GET['cutoff_period']);
			$period_start      = $cutoff_period_arr[0];
			$period_end   	   = $cutoff_period_arr[1];
			$this->var['cutoff_selected'] = $period_start."/".$period_end;

			if ($frequency_id == 2) {
				$cutoff_data  	   = G_Weekly_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}
			else if ($frequency_id == 3) {
				$cutoff_data  	   = G_Monthly_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}
			else {
				$cutoff_data  	   = G_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}

			if($cutoff_data) {
				$from 	   = $cutoff_data->getStartDate();
				$to        = $cutoff_data->getEndDate();
				$cutoff_id = $cutoff_data->getId();
			}

			//General Reports / Shr Audit Trail
	        list($p_year, $p_month, $p_day) = explode('-', $from);
	        $pmonthN = date("F", mktime(0, 0, 0, $p_month, 10));
	        if($p_day >= 16){
	        	$cut_of_period = $p_year.'-'.$pmonthN.'-B';
	        }
	        else{
	            $cut_of_period = $p_year.'-'.$pmonthN.'-A';	
	        }

			if($_GET['selected_frequency'] == 1){
				$frequency_name = 'Bi-Monthly';
			}
			else{
				$frequency_name = 'Weekly';
			}

			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_LOAD, ' Disapproved Leave Requests of ',' Cut Off Period of '.$cut_of_period.' and '.$frequency_name.' Frequency', $from, $to, 1, '', '');	

		} else {
			$from = $_GET['from'];
			$to   = $_GET['to'];
			$this->var['cutoff_selected'] = $from . "/" . $to;
		}  

		if($enable_next_previous_link) {
	        $date = $_GET['from'];

	  if ($frequency_id == 2) {
				$c = new G_Weekly_Cutoff_Period();
			}
			else if ($frequency_id == 3) {
				$c = new G_Monthly_Cutoff_Period();
			}
			else {
				$c = new G_Cutoff_Period();
			}

			$previous_cutoff_data = $c->getPreviousCutOffByDate($date);
			$next_cutoff_data = $c->getNextCutOffByDate($date);

			$next_from = $next_cutoff_data['start_date'];
			$next_to   = $next_cutoff_data['end_date'];
			$next_id   = $next_cutoff_data['eid'];

			$previous_from = $previous_cutoff_data['start_date'];
			$previous_to   = $previous_cutoff_data['end_date'];
			$previous_id   = $previous_cutoff_data['eid'];

			$this->var['next_cutoff_link']	   = url("leave/disapproved?from={$next_from}&to={$next_to}&hpid={$next_id}");
	        $this->var['previous_cutoff_link'] = url("leave/disapproved?from={$previous_from}&to={$previous_to}&hpid={$previous_id}");			
		}		

        $this->var['year_selected']     = $_GET['year_selected'];

        if ($frequency_id == 2) {
	        $all_payroll_years 				= G_Weekly_Cutoff_Period_Helper::sqlGetAllExistYearTags();
								}
								else if ($frequency_id == 3) {
	        $all_payroll_years 				= G_Monthly_Cutoff_Period_Helper::sqlGetAllExistYearTags();
								}
								else {
	        $all_payroll_years 				= G_Cutoff_Period_Helper::sqlGetAllExistYearTags();
								}

        $this->var['all_cutoff_years'] 	= $all_payroll_years;    		

		$this->var['from_period']	= $from;
		$this->var['to_period']		= $to;		
		$this->var['disapproved']   = 'class="selected"';
		$this->var['import_action'] = url('leave/_import_leave_excel');
		$this->var['page_title']    = 'Leave Requests';
		$this->var['module'] 		= 'leave'; 
        $this->view->setTemplate('template_leftsidebar.php');
		$this->view->render('leave/disapproved.php',$this->var);		
	}
	
	function history()
	{		
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();

		$this->validatePermission(G_Sprint_Modules::HR,'attendance','');
		
		$this->var['history']       = 'class="selected"';
		$this->var['import_action'] = url('leave/_import_leave_excel');
		$this->var['page_title']    = 'Leave Requests';
		//$this->var['page_title']    = 'Leave Requests <div style="float:right;"><small style="font-size:15px;">Period: <b>' . $_GET['from'] . ' </b> to <b>' . $_GET['to'] . '</b></small></div>';
		$this->var['module'] 		= 'leave'; 
		$this->view->setTemplate('template_leftsidebar.php');
        $this->var['sub_page_title'] = 'Employee Leave History';
		$this->view->render('leave/history.php',$this->var);		
	}
	
	function credits()
	{
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();
		
		$btn_import_leave_credits_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'attendance',
    		'child_index'			=> 'attendance_leave',
    		'href' 					=> '#',
    		'onclick' 				=> 'javascript:importLeaveCredits();',
    		'id' 					=> '',
    		'class' 				=> 'add_button pull-right',
    		'icon' 					=> '<i class="icon-arrow-left"></i>',
    		'additional_attribute'	=> '',
    		'caption' 				=> 'Import Leave Credits'
    		); 
		
		$this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::HR,'attendance','attendance_leave');
		$this->var['btn_import_leave_credits'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_import_leave_credits_config);

		$this->var['credits']       = 'class="selected"';
		$this->var['import_action'] = url('leave/_import_leave_excel');
		$this->var['page_title']    = 'Leave Requests';
		//$this->var['page_title']    = 'Leave Requests <div style="float:right;"><small style="font-size:15px;">Period: <b>' . $_GET['from'] . ' </b> to <b>' . $_GET['to'] . '</b></small></div>';
		$this->var['module'] 		= 'leave'; 	
		$this->view->setTemplate('template_leftsidebar.php');
        $this->var['sub_page_title'] = 'Employee Leave Credits';
		$this->view->render('leave/credits.php',$this->var);
	}
	
	function type()
	{		
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();

		$btn_add_leave_type_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'attendance',
    		'child_index'			=> 'attendance_leave',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:show_add_leave_type_form();',
    		'id' 					=> 'request_leave_button',
    		'class' 				=> 'add_button',
    		'icon' 					=> '',
    		'additional_attribute'	=> '',
    		'caption' 				=> '<strong>+</strong><b>Add Leave Type</b>'
    		); 
		
		$this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::HR,'attendance','attendance_leave');
		$this->var['btn_add_leave_type'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_leave_type_config);
		
		$this->var['type']          = 'class="selected"';
		$this->var['import_action'] = url('leave/_import_leave_excel');
		$this->var['page_title']    = 'Leave Requests';
		//$this->var['page_title']    = 'Leave Requests <div style="float:right;"><small style="font-size:15px;">Period: <b>' . $_GET['from'] . ' </b> to <b>' . $_GET['to'] . '</b></small></div>';
		$this->var['module'] 		= 'leave'; 	
		$this->view->setTemplate('template_leftsidebar.php');

        $leaves = G_Leave_Finder::findAllIsNotArchive();
        $this->var['leaves'] = $leaves;
        $this->var['sub_page_title'] = 'Leave Type';

		$this->view->render('leave/leave_type.php',$this->var);
	}
	
	function archives()
	{		
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();
		
		$this->var['from_period']	= $_GET['from'];
		$this->var['to_period']		= $_GET['to'];
		
		$this->var['archives']      = 'class="selected"';
		$this->var['import_action'] = url('leave/_import_leave_excel');
		$this->var['page_title']    = 'Leave Requests';	
		$this->var['module'] 		= 'leave'; 	
		$this->view->setTemplate('template_leftsidebar.php');
		$this->view->render('leave/archives.php',$this->var);		
	}
	
	function _load_leave_list_archives_dt()
	{
		$this->var['permission_action'] = $this->validatePermission(G_Sprint_Modules::HR,'attendance','attendance_leave');
		$this->var['dept_id'] = $_POST['dept_id'];
		$this->view->render('leave/_leave_list_archives_dt.php',$this->var);
	}
	
	function _load_leave_type_archives_dt() 
	{	
		$this->var['permission_action'] = $this->validatePermission(G_Sprint_Modules::HR,'attendance','attendance_leave');	
		$this->view->render('leave/_leave_type_archive_list_dt.php',$this->var);
	}
	
	function _load_employee_leave_available_dt() 
	{
		$this->var['h_employee_id'] = Utilities::decrypt($_POST['employee_id']);
		$this->view->render('leave/leave_list/_leave_available_list_dt.php',$this->var);
	}
	
	function _load_employee_leave_list_dt() 
	{
		$this->var['permission_action'] = $this->validatePermission(G_Sprint_Modules::HR,'attendance','attendance_leave');
		$this->var['h_employee_id'] = Utilities::decrypt($_POST['employee_id']);
		$this->view->render('leave/leave_list/_leave_list_dt.php',$this->var);
	}
	
	function _load_leave_history_dt() 
	{
		$this->var['dept_id'] = $_POST['dept_id'];
		$this->view->render('leave/_leave_history_dt.php',$this->var);
		
	}
	
	function _load_employee_leave_credit_dt() 
	{
		$this->var['dept_id'] = $_POST['dept_id'];
		$this->view->render('leave/_leave_credit_dt.php',$this->var);
	}
	
	function _load_leave_type_list_dt() 
	{		
		$this->view->render('leave/_leave_type_list_dt.php',$this->var);
	}
	
	function _load_leave_list_dt() 
	{
		$this->var['errors'] = $errors = G_Leave_Error_Finder::countAllErrorsNotFixed();
		if($errors > 0) {
			//$this->var['total_errors'] = $errors;
		}
		$this->var['permission_action'] = $this->validatePermission(G_Sprint_Modules::HR,'attendance','attendance_leave');
		$this->var['dept_id'] = $_POST['dept_id'];
		$this->var['frequency_id'] = $_POST['frequency_id'];
		$this->view->render('leave/_leave_list_dt.php',$this->var);
	}
	
	function _load_approved_leave_list_dt() 
	{	
		$this->var['from_date'] = $_GET['from_date'];
		$this->var['to_date']   = $_GET['to_date'];
		$this->var['permission_action'] = $this->validatePermission(G_Sprint_Modules::HR,'attendance','attendance_leave');
		$this->var['dept_id'] = $_POST['dept_id'];
		$this->var['frequency_id'] = $_POST['frequency_id'];
		$this->view->render('leave/_leave_approved_list_dt.php',$this->var);
	}

	function _load_incentive_leave_list_dt() 
	{
		$month = $_GET['month'];
		$year  = $_GET['year'];

		$from = "{$year}-{$month}-01";
		$to   = date("Y-m-t",strtotime($from));

		$is_processed = G_Incentive_Leave_History_Helper::isMonthNumberAndYearExists($month, $year);

		if( $is_processed > 0 ){
			$att   = G_Attendance_Helper::perfectAttendanceDataByDateRange($from, $to);
		}

		$this->var['attendance'] = $att;		
		//$this->var['permission_action'] = $this->validatePermission(G_Sprint_Modules::HR,'attendance','attendance_leave');		
		$this->view->render('leave/_incentive_leave_list_dt.php',$this->var);
	}

	function _load_disapproved_leave_list_dt() 
	{	
		$this->var['from_date'] = $_GET['from_date'];
		$this->var['to_date']   = $_GET['to_date'];
		$this->var['permission_action'] = $this->validatePermission(G_Sprint_Modules::HR,'attendance','attendance_leave');
		$this->var['dept_id'] = $_POST['dept_id'];
		$this->var['frequency_id'] = $_POST['frequency_id'];
		$this->view->render('leave/_leave_disapproved_list_dt.php',$this->var);
	}
	
	function _load_server_approved_leave_list_dt()
	{
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR,'attendance','attendance_leave');
		Utilities::ajaxRequest();
		
		$sqlcond 	= "  AND jbh.end_date = '' AND gsh.end_date =''";
		$sqlcond 	.= ' AND date_start BETWEEN ' . Model::safeSql($_GET['from']) . ' AND ' . Model::safeSql($_GET['to']);
		
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_LEAVE_REQUEST);
		$dt->setCustomField(array('emp_name' => 'firstname,lastname','job_name'=>'jbh.name','leave_type' =>'l.name'));
		$dt->setJoinTable("LEFT JOIN " . EMPLOYEE . " e");			
		$dt->setJoinFields(G_EMPLOYEE_LEAVE_REQUEST . ".employee_id = e.id LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " jbh ON e.id = jbh.employee_id and jbh.end_date = '' LEFT JOIN " . G_LEAVE ." l ON " . G_EMPLOYEE_LEAVE_REQUEST . ".leave_id =l.id LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " gsh ON e.id = gsh.employee_id AND gsh.end_date = ''");
		if($_GET['dept_id']){
			$dt->setCondition(' (is_archive = "' . G_Employee_Leave_Request::NO . '" AND gsh.company_structure_id='. Utilities::decrypt($_GET['dept_id']) . ' AND is_approved = "' . G_Employee_Leave_Request::APPROVED . '") AND (jbh.end_date = "" AND gsh.end_date = "") AND date_start BETWEEN ' . Model::safeSql($_GET['from']) . ' AND ' . $_GET['to']);
		}else{
			$dt->setCondition(' (is_archive = "' . G_Employee_Leave_Request::NO . '" AND is_approved ="'. G_Employee_Leave_Request::APPROVED . '") AND date_start BETWEEN ' . Model::safeSql($_GET['from']) . " AND " . Model::safeSql($_GET['to']));
		}
		$dt->setColumns('date_start,date_end');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);	

		$from = $_GET['from'];
		$to   = $_GET['to'];
		if($permission_action == Sprint_Modules::PERMISSION_02) { 
			if($_SESSION['sprint_hr']['is_period_lock'] == ((!isset($frequency_id) || $frequency_id != 2 ? G_Cutoff_Period::NO : G_Weekly_Cutoff_Period::NO))){
				$dt->setNumCustomColumn(1);
				$dt->setCustomColumn(	
				array(
				'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></li><li><a title=\"Revert\" id=\"delete\" class=\"ui-icon ui-icon-close g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:revertLeaveRequest(\'e_id\')\"></a></li></ul></div>'));
			} else {
				$dt->setNumCustomColumn(0);	
			}
		}else{
			$dt->setNumCustomColumn(0);	
		}

				
		echo $dt->constructDataTable();
	}

	function _load_server_disapproved_leave_list_dt()
	{
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR,'attendance','attendance_leave');
		Utilities::ajaxRequest();
		
		$sqlcond 	= "  AND jbh.end_date = '' AND gsh.end_date =''";
		$sqlcond 	.= ' AND date_start BETWEEN ' . Model::safeSql($_GET['from']) . ' AND ' . Model::safeSql($_GET['to']);
		
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_LEAVE_REQUEST);
		$dt->setCustomField(array('emp_name' => 'firstname,lastname','job_name'=>'jbh.name','leave_type' =>'l.name'));
		$dt->setJoinTable("LEFT JOIN " . EMPLOYEE . " e");			
		$dt->setJoinFields(G_EMPLOYEE_LEAVE_REQUEST . ".employee_id = e.id LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " jbh ON e.id = jbh.employee_id AND jbh.end_date = '' LEFT JOIN " . G_LEAVE ." l ON " . G_EMPLOYEE_LEAVE_REQUEST . ".leave_id =l.id LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " gsh ON e.id = gsh.employee_id");
		if($_GET['dept_id']){
			$dt->setCondition(' (is_archive = "' . G_Employee_Leave_Request::NO . '" AND gsh.company_structure_id='. Utilities::decrypt($_GET['dept_id']) . ' AND is_approved = "' . G_Employee_Leave_Request::APPROVED . '") AND (jbh.end_date = "" AND gsh.end_date = "") AND date_start BETWEEN ' . Model::safeSql($_GET['from']) . ' AND ' . Model::safeSql($_GET['to']));
		}else{
			$dt->setCondition(' (is_archive = "' . G_Employee_Leave_Request::NO . '" AND is_approved ="'. G_Employee_Leave_Request::DISAPPROVED . '") AND date_start BETWEEN ' . Model::safeSql($_GET['from']) . ' AND ' . Model::safeSql($_GET['to']));
		}
		$dt->setColumns('date_start,date_end');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);	
		if($permission_action == Sprint_Modules::PERMISSION_02) { 
			if($_SESSION['sprint_hr']['is_period_lock'] == ((!isset($frequency_id) || $frequency_id != 2 ? G_Cutoff_Period::NO : G_Weekly_Cutoff_Period::NO))){
				$dt->setNumCustomColumn(1);
				$dt->setCustomColumn(	
				array(
				'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><a title=\"Revert\" id=\"edit\" class=\"ui-icon ui-icon-check  g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:revertLeaveRequest(\'e_id\')\"></a></li></ul></div>'));
			} else {
				$dt->setNumCustomColumn(0);	
			}
		}else{
			$dt->setNumCustomColumn(0);	
		}

				
		echo $dt->constructDataTable();
	}
	
	function _load_server_leave_list_dt()
	{
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR,'attendance','attendance_leave');
		Utilities::ajaxRequest();
		
		//$sqlcond 	= "  AND jbh.end_date = '' AND gsh.end_date = ''";
        //$sqlcond 	= "  AND gsh.end_date = ''";
		$sqlcond 	.= ' AND date_start BETWEEN ' . Model::safeSql($_GET['from']) . ' AND ' . Model::safeSql($_GET['to']);

		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_LEAVE_REQUEST);
		$dt->setCustomField(array('emp_name' => 'firstname,lastname','job_name'=>'jbh.name','leave_type' =>'l.name'));        
		$dt->setJoinTable("LEFT JOIN " . EMPLOYEE . " e");
		$dt->setJoinFields(G_EMPLOYEE_LEAVE_REQUEST . ".employee_id = e.id LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " jbh ON " . G_EMPLOYEE_LEAVE_REQUEST . ".employee_id = jbh.employee_id AND jbh.end_date = '' LEFT JOIN " . G_LEAVE ." l ON " . G_EMPLOYEE_LEAVE_REQUEST . ".leave_id =l.id LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " gsh ON e.id = gsh.employee_id AND gsh.end_date = ''");        
		if($_GET['dept_id']){
			$dt->setCondition(' is_archive = "' . G_Employee_Leave_Request::NO . '" AND gsh.company_structure_id='. Utilities::decrypt($_GET['dept_id']) . ' AND is_approved = "' . G_Employee_Leave_Request::PENDING . '"') . $sqlcond;
		}else{
			$dt->setCondition(' is_archive = "' . G_Employee_Leave_Request::NO . '" AND is_approved="' . G_Employee_Leave_Request::PENDING . '"' . $sqlcond);
		}
		$dt->setColumns('date_start,date_end');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);
		if($permission_action == Sprint_Modules::PERMISSION_02) {
			if($_SESSION['sprint_hr']['is_period_lock'] == ((!isset($frequency_id) || $frequency_id != 2 ? G_Cutoff_Period::NO : G_Weekly_Cutoff_Period::NO))){
				$dt->setNumCustomColumn(1);
				$dt->setCustomColumn(	
				array(
				'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></li><li><a title=\"Edit\" id=\"edit\" class=\"ui-icon ui-icon-pencil g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:editLeaveRequestForm(\'e_id\');\"></a></li><li><a title=\"Approvers\" id=\"edit\" class=\"ui-icon ui-icon-person g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:viewLeaveRequestApprovers(\'e_id\');\"></a></li><li><a title=\"Approve\" id=\"edit\" class=\"ui-icon ui-icon-check g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:approveLeaveRequest(\'e_id\');\"></a></li><li><a title=\"Disapprove\" id=\"edit\" class=\"ui-icon ui-icon-close g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:disApproveLeaveRequest(\'e_id\');\"></a></li><li><a title=\"Send to Archive\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:archiveLeaveRequest(\'e_id\')\"></a></li></ul></div>'));
			} else {
				$dt->setNumCustomColumn(0);	
			}
		}else{
			$dt->setNumCustomColumn(0);	
		}
		
		echo $dt->constructDataTable();
	}
	
	function _load_server_leave_type_list_dt() 
	{
		Utilities::ajaxRequest();
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_LEAVE);	
		$dt->setCustomField(array('leave_type' => 'name'));				
		$dt->setCondition('company_structure_id =' . $this->company_structure_id . ' AND gl_is_archive ="' . G_Leave::NO . '"');
		$dt->setColumns('default_credit,is_paid');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(1);
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></li><li><a title=\"Edit\" id=\"edit\" class=\"ui-icon ui-icon-pencil g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:editLeaveType(\'e_id\');\"></a></li><li><a title=\"Send to Archive\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:archiveLeaveType(\'e_id\')\"></a></li></ul></div>'));
		
		echo $dt->constructDataTable();
	}
	
	function _load_server_leave_type_archive_list_dt() 
	{
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR,'attendance','attendance_leave');	

		Utilities::ajaxRequest();
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_LEAVE);	
		$dt->setCustomField(array('leave_type' => 'name'));			
		$dt->setCondition('company_structure_id =' . $this->company_structure_id . ' AND gl_is_archive ="' . G_Leave::YES . '"');
		$dt->setColumns('is_paid');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);
		if($permission_action == Sprint_Modules::PERMISSION_02) {	
			$dt->setNumCustomColumn(1);
			$dt->setCustomColumn(	
			array(
			'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:archivesEnableDisableWithSelected(1);\" value=\"id\"></li><li><a title=\"Restore Archived\" id=\"delete\" class=\"ui-icon ui-icon-refresh g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:restoreLeaveType(\'e_id\')\"></a></li></ul></div>'));	
		}else{
			$dt->setNumCustomColumn(0);
		}
		echo $dt->constructDataTable();
	}
	
	function _load_server_leave_list_archives_dt() 
	{
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR,'attendance','attendance_leave');

		Utilities::ajaxRequest();
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_LEAVE_REQUEST);
		$dt->setCustomField(array('emp_name' => 'firstname,lastname','job_name'=>'jbh.name','leave_type' =>'l.name'));
		$dt->setJoinTable("LEFT JOIN " . EMPLOYEE . " e");			
		$dt->setJoinFields(G_EMPLOYEE_LEAVE_REQUEST . ".employee_id = e.id LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " jbh ON e.id = jbh.employee_id AND jbh.end_date = '' LEFT JOIN " . G_LEAVE ." l ON " . G_EMPLOYEE_LEAVE_REQUEST . ".leave_id =l.id LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " gsh ON e.id = gsh.employee_id AND gsh.end_date = ''");
		if($_GET['dept_id']){
			$dt->setCondition(' is_archive = "' . G_Employee_Leave_Request::NO . '" AND gsh.company_structure_id='. Utilities::decrypt($_GET['dept_id']));
		}else{
			$dt->setCondition(' is_archive = "' . G_Employee_Leave_Request::YES . '"');
		}
		$dt->setColumns('date_start,date_end,is_approved');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);

		if($permission_action == Sprint_Modules::PERMISSION_02) {			
			$dt->setNumCustomColumn(1);
			$dt->setCustomColumn(	
			array(
			'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:archivesEnableDisableWithSelected(2);\" value=\"id\"></li><li><a title=\"Restore Archived\" id=\"delete\" class=\"ui-icon ui-icon-refresh g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:restoreLeaveRequest(\'e_id\',\'is_approved\')\"></a></li></ul></div>'));	
		}else{
			$dt->setNumCustomColumn(0);
		}
		echo $dt->constructDataTable();
	}
	
	function _load_server_employee_leave_available_dt() 
	{
		Utilities::ajaxRequest();
		$year = date("Y");
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_LEAVE_AVAILABLE);		
		$dt->setJoinTable("LEFT JOIN " . G_LEAVE . " l");			
		$dt->setJoinFields(G_EMPLOYEE_LEAVE_AVAILABLE . ".leave_id =l.id");
		$dt->setCondition(G_EMPLOYEE_LEAVE_AVAILABLE . '.employee_id='. Utilities::decrypt($_GET['employee_id']) . " AND l.gl_is_archive =" . Model::safeSql(G_Leave::NO) );				
		$dt->setColumns('name,no_of_days_alloted,no_of_days_available');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(1);		
		echo $dt->constructDataTable();
	}
	
	function _load_server_employee_leave_list_dt() 
	{
		Utilities::ajaxRequest();
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_LEAVE_REQUEST);		
		$dt->setJoinTable("LEFT JOIN " . G_LEAVE . " l");			
		$dt->setJoinFields(G_EMPLOYEE_LEAVE_REQUEST . ".leave_id =l.id");
		$dt->setCondition('employee_id='. Utilities::decrypt($_GET['employee_id']));				
		$dt->setColumns('date_applied,date_start,date_end,name,leave_comments,is_approved');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(1);
		/*$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><a title=\"Leave History\" id=\"delete\" class=\"ui-icon ui-icon-search g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:load_show_leave_details(id)\"></a></li></ul></div>'));*/
		echo $dt->constructDataTable();
	}
	
	function _load_server_employee_list_dt() 
	{
		Utilities::ajaxRequest();
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(EMPLOYEE);
		$dt->setCustomField(array('employee_code'=>'employee_code','name' => 'firstname,lastname','job_name'=>'jbh.name'));
		
		$dt->setJoinTable("LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " jbh");			
		$dt->setJoinFields(EMPLOYEE . ".id = jbh.employee_id AND jbh.end_date = '' LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " gsh ON " . EMPLOYEE . ".id = gsh.employee_id");
		
		if($_GET['dept_id']){
			$dt->setCondition(' gsh.company_structure_id='. Utilities::decrypt($_GET['dept_id']));
		}else{
						
		}
		
		$dt->setColumns('employment_status');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(1);
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><a title=\"Leave History\" id=\"delete\" class=\"ui-icon ui-icon-search g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:load_show_leave_details(id)\"></a></li></ul></div>'));
		echo $dt->constructDataTable();
	}
	
	function _load_server_leave_credit_employee_list_dt() 
	{
		Utilities::ajaxRequest();
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(EMPLOYEE);
		$dt->setCustomField(array('employee_code'=>'employee_code','name' => 'firstname,lastname','job_name'=>'jbh.name'));
		
		$dt->setJoinTable("LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " jbh");			
		$dt->setJoinFields(EMPLOYEE . ".id = jbh.employee_id AND jbh.end_date = '' LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " gsh ON " . EMPLOYEE . ".id = gsh.employee_id AND gsh.end_date = ''");
		
		if($_GET['dept_id']){
			$dt->setCondition(' gsh.company_structure_id='. Utilities::decrypt($_GET['dept_id']));
		}else{
						
		}
		
		$dt->setColumns('employment_status');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(1);
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><a title=\"Leave Credits\" id=\"delete\" class=\"ui-icon ui-icon-search g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:load_show_leave_credit_details(id)\"></a></li></ul></div>'));
		echo $dt->constructDataTable();
	}
	
	function ajax_quick_add_leave_request() 
	{
		$e 					     = G_Employee_Finder::findById($_POST['h_employee_id']);
		$this->var['e']			 = $e;	
		$this->var['token']		 = Utilities::createFormToken();
		$this->var['leaves']     = $leave = G_Leave_Finder::findAll();		
		$this->var['page_title'] = 'Add New Leave Request';		
		$this->view->render('leave/form/quick_add_request_leave.php',$this->var);
	}
	
	function ajax_add_new_leave_request()
	{
		$date = date("Y-m-d",strtotime($_GET['start_cutoff']));

		$start_date = date("Y-m-d",strtotime($_GET['start_cutoff']));
		$end_date   = date("Y-m-d",strtotime($_GET['end_cutoff']));
		$fields     = array("is_lock");

		if($_GET['frequency_id'] == 2){
			$cp   = new G_Weekly_Cutoff_Period();
		}
		elseif($_GET['frequency_id'] == 3){
			$cp   = new G_Monthly_Cutoff_Period();
		}

		else{
			$cp   = new G_Cutoff_Period();
		}

		$cp->setStartDate($start_date);
		$cp->setEndDate($end_date);
		$cutoff_data = $cp->getCutoffDataByStartAndEndDate($fields);		
		if( $cutoff_data['is_lock'] == G_Cutoff_Period::NO ){
			
			$slv = G_Settings_Leave_General_Finder::findById(1);
		    $default_general_leave = $slv->getLeaveId();

		    if(!empty($default_general_leave) || $default_general_leave > 0) { //if has Leave General Rule	
		    	$this->var['is_have_general_rule'] = true;
		    }

			$this->var['e']			 = $e;	
			$this->var['token']		 = Utilities::createFormToken();
			$this->var['leaves']     = $leave = G_Leave_Finder::findAllIsNotArchive();		
			$this->var['page_title'] = 'Add New Leave Request';
	        $this->var['form_action'] = url('leave/_add_new_leave_request');
			$this->view->render('leave/form/request_leave.php',$this->var);
		}else{
			echo "<div class=\"alert alert-error\">Selected cutoff period is already locked for editing.</div><br />";
		}
	}
	
	function ajax_add_new_leave_type() 
	{
		
		$this->var['e']			 = $e;	
		$this->var['token']		 = Utilities::createFormToken();
		$this->var['leaves']     = $leave = G_Leave_Finder::findAll();		
		$this->var['page_title'] = 'Add New Leave Type';		
		$this->view->render('leave/form/add_leave_type.php',$this->var);
	}
	
	function ajax_edit_leave_request() 
	{
		$start_date = date("Y-m-d",strtotime($_POST['start_cutoff']));
		$end_date   = date("Y-m-d",strtotime($_POST['end_cutoff']));
		$fields     = array("is_lock");

		$cp   = new G_Cutoff_Period();
		$cp->setStartDate($start_date);
		$cp->setEndDate($end_date);
		$cutoff_data = $cp->getCutoffDataByStartAndEndDate($fields);

		if( $cutoff_data['is_lock'] == G_Cutoff_Period::NO ){			
			$l 					     = G_Employee_Leave_Request_Finder::findById(Utilities::decrypt($_POST['c_leave_id']));	
			$e 						 = G_Employee_Finder::findById($l->getEmployeeId());		
			$this->var['e']			 = $e;
			$this->var['leave']		 = $l;	
			$this->var['token']		 = Utilities::createFormToken();
			$this->var['leaves']     = $leave = G_Leave_Finder::findAllIsNotArchive();		
			$this->var['page_title'] = 'Edit Leave Request';		
			$this->view->render('leave/form/edit_request_leave.php',$this->var);
		}else{
			echo "<div class=\"alert alert-error\">Selected cutoff period is already locked for editing.</div><br />";
		}
	}

	function ajax_view_leave_request_approvers() 
	{
		$date  		 = date("Y-m-d"); 		
		$cp          = new G_Cutoff_Period();
		$cutoff_data = $cp->getCurrentCutoffPeriod($date);				

		$request_id  = Utilities::decrypt($_GET['eid']);
		$approvers   = new G_Request();
		$approvers->setRequestId($request_id);
		$data = $approvers->getLeaveRequestApproversStatus();

		if( $data['total_approvers'] > 0 ){	
			$this->var['eid']        = $_GET['eid'];		
			$this->var['is_lock']    = $cutoff_data['is_lock'];			
			$this->var['total_approvers'] = $data['total_approvers'];
			$this->var['approvers']  = $data['approvers'];	
			$this->var['token']		 = Utilities::createFormToken();			
			$this->var['page_title'] = 'Leave Request Approvers';		
			$this->view->render('leave/form/view_leave_request_approvers.php',$this->var);

		}else{
			echo "<div class=\"alert alert-error\">No approvers set for selected request</div><br />";
		}
	}
	
	function ajax_edit_leave_type() 
	{
		$l 					     = G_Leave_Finder::findById(Utilities::decrypt($_POST['c_leave_id']));				
		$this->var['l']		 	 = $l;	
		$this->var['token']		 = Utilities::createFormToken();
		$this->var['page_title'] = 'Edit Leave Type';		
		$this->view->render('leave/form/edit_leave_type.php',$this->var);
	}
	
	function ajax_add_leave_credit() 
	{		
		$e 						 = G_Employee_Finder::findById(Utilities::decrypt($_POST['employee_id']));		
		$this->var['e']			 = $e;		
		$this->var['token']		 = Utilities::createFormToken();
		$this->var['leaves']     = $leave = G_Leave_Finder::findAllIsNotArchive();		
		$this->var['page_title'] = 'Edit Leave Request';		
		$this->view->render('leave/form/add_leave_credit.php',$this->var);
	}
	
	function ajax_get_employees_autocomplete() {
		$q = Model::safeSql(strtolower($_GET["search"]), false);
		
		if ($q != '') {
			$employees = G_Employee_Finder::searchByFirstnameAndLastname($q);
			
			foreach ($employees as $e) {
				$response[] = array(Utilities::encrypt($e->getId()), $e->getFullname(), null);
			}
		}
		
		if(count($response) == 0) {
			$response = '';
		}
		header('Content-type: application/json');
		echo json_encode($response);		
	}
	
	function load_get_specific_schedule() {
		
		if(!empty($_POST)) {
			$employee  = G_Employee_Finder::findById(Utilities::decrypt($_POST['h_employee_id']));
			if($employee) {				
				$attendance = G_Attendance_Finder::findByEmployeeAndPeriod($employee, $_POST['start_date'],$_POST['end_date']);
				if($attendance) {
					$this->var['attendance'] = $attendance;
				}
			}
			$this->view->render('leave/form/_show_specific_schedule.php',$this->var);
		}
	}
	
	function _load_get_employee_leave_available() {
		
		if(!empty($_POST)) {
			$employee  = G_Employee_Finder::findById(Utilities::decrypt($_POST['h_employee_id']));
			if($employee) {				
				$leave_available = G_Employee_Leave_Available_Finder::findByEmployeeId($employee->getId());
			}
			$this->var['leave_available'] = $leave_available;
			$this->view->render('leave/form/_show_leave_available.php',$this->var);
		}
	}

	function _load_get_employee_request_approvers() {
		
		if(!empty($_POST)) {
			$employee  = G_Employee_Finder::findById(Utilities::decrypt($_POST['h_employee_id']));
			if($employee) {				
				$employee_id = $employee->getId();
				$gra = new G_Request_Approver();
				$gra->setEmployeeId($employee_id);
				$approvers = $gra->getEmployeeRequestApprovers();

				$this->var['approvers'] = $approvers;		
				$this->view->render('leave/form/_show_request_approvers.php',$this->var);
			}		
		}
	}

	function _add_new_leave_request_backup() {
    	
    	//Utilities::verifyFormToken($_POST['token']);    	
    	$employee_id  = Utilities::decrypt($_POST['employee_id']);    	
        $e = G_Employee_Finder::findById($employee_id);
        if ($e) {            
	        $leave_id     = Utilities::decrypt($_POST['leave_id']);
	        $date_start   = date("Y-m-d",strtotime($_POST['date_start']));
	        $date_end     = date("Y-m-d",strtotime($_POST['date_end']));
	        $date_applied = date("Y-m-d");
	        $time_applied = date("H:i:s");
	        $is_approved  = $_POST['is_approved'];
	        $comment 	  = $_POST['leave_comments'];	        
	        //$status       = $_POST['is_approved'];
	        $status       = G_Employee_Leave_Request::PENDING;
	        $is_paid      = $_POST['is_paid'];
	        $created_by   = G_Employee_Helper::getEmployeeNameById(Utilities::decrypt($_SESSION['sprint_hr']['employee_id']));

	        if( $_POST['start_halfday'] ){
	        	$is_halfday = G_Employee_Leave_Request::YES;
	        }else{
	        	$is_halfday = G_Employee_Leave_Request::NO;
	        }

	        $el = new G_Employee_Leave_Request();        
			$el->setCompanyStructureId($this->company_structure_id);
			$el->setEmployeeId($employee_id);
			$el->setLeaveId($leave_id);
			$el->setDateApplied($date_applied);
	        $el->setTimeApplied($time_applied);
			$el->setDateStart($date_start);
			$el->setDateEnd($date_end);
			$el->setApplyHalfDayDateStart($is_halfday);		
			$el->setLeaveComments($comment);
			$el->setAsPending();
			$el->setIsPaid($is_paid);
			$el->setCreatedBy($created_by);			
			$json = $el->saveRequest();

			if( $json['is_success'] ){

				$request_id   = $json['last_inserted_id'];
				$request_type = G_Request::PREFIX_LEAVE;
				$approvers    = $_POST['approvers'];
				$requestor_id = $e->getId();

				$r = new G_Request();
		        $r->setRequestorEmployeeId($requestor_id);
		        $r->setRequestId($request_id);
		        $r->setRequestType($request_type);
		        $r->saveEmployeeRequest($approvers); //Save request approvers

				$el->addLeaveToAttendance($e);
			}

        }else{
        	$json['is_success'] = false;
        	$json['message']    = "Invalid form entries";
        }

        $json['token'] = Utilities::createFormToken();
        echo json_encode($json);    		

    }	

    function _add_new_leave_request() {
    	$slv = G_Settings_Leave_General_Finder::findById(1);
    	$default_general_leave = $slv->getLeaveId(); 

    		date_default_timezone_set('Asia/Manila');  		

	    	//Utilities::verifyFormToken($_POST['token']);    	
	    	$employee_id  = Utilities::decrypt($_POST['employee_id']);    	
	        $e = G_Employee_Finder::findById($employee_id);
	        if ($e) {            
		        $leave_id     = Utilities::decrypt($_POST['leave_id']);
		        $date_start   = date("Y-m-d",strtotime($_POST['date_start']));
		        //$date_end     = date("Y-m-d",strtotime($_POST['date_end']));
		        $date_end     = empty($_POST['date_end']) ? date("Y-m-d",strtotime($_POST['date_start'])) : date("Y-m-d",strtotime($_POST['date_end'])); 
		        $date_applied = date("Y-m-d");
		        $time_applied = date("H:i:s");
		        $is_approved  = $_POST['is_approved'];
		        $comment 	  = $_POST['leave_comments'];	        
		        //$status       = $_POST['is_approved'];
		        $status       = G_Employee_Leave_Request::PENDING;
		        $is_paid      = $_POST['is_paid'];
		        $created_by   = G_Employee_Helper::getEmployeeNameById(Utilities::decrypt($_SESSION['sprint_hr']['employee_id']));

		        if( $_POST['start_halfday'] ){
		        	$is_halfday = G_Employee_Leave_Request::YES;
		        }else{
		        	$is_halfday = G_Employee_Leave_Request::NO;
		        }

		        $el = new G_Employee_Leave_Request();        
				$el->setCompanyStructureId($this->company_structure_id);
				$el->setEmployeeId($employee_id);
				$el->setLeaveId($leave_id);
				$el->setDateApplied($date_applied);
		        $el->setTimeApplied($time_applied);
				$el->setDateStart($date_start);
				$el->setDateEnd($date_end);
				$el->setApplyHalfDayDateStart($is_halfday);		
				$el->setLeaveComments($comment);
				$el->setIsApproved($status);
				$el->setIsPaid($is_paid);
				$el->setCreatedBy($created_by);			
				/*$json = $el->saveRequest();*/
				$json = $el->checkGeneralRule()->filterValidDates()->createLeaveBulkRequests()->deductLeaveToLeaveCredits()->bulkSaveRequests();

				if( $json['is_success'] ){

					$request_id   = $json['last_inserted_id'];
					$request_type = G_Request::PREFIX_LEAVE;
					$approvers    = $_POST['approvers'];
					$requestor_id = $e->getId();

					$r = new G_Request();
			        $r->setRequestorEmployeeId($requestor_id);
			        $r->setRequestId($request_id);
			        $r->setRequestType($request_type);
			        $r->saveEmployeeRequest($approvers); //Save request approvers

					$el->addLeaveToAttendance($e);

					//General Reports / Shr Audit Trail
					$shr_emp = G_Employee_Helper::findByEmployeeId($employee_id);
					$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
        			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_FILE, ' for Leave of ', $emp_name.' Leave Type:'. $request_type, $_POST['date_start'], $_POST['date_end'], 1, $shr_emp['position'], $shr_emp['department']);
				}

				/*
				 * Add notifications
				*/
				$has_update = false;
				$n = G_Notifications_Finder::findByEventType('Update Attendance');
				if($n) {
		            $n->setStatus(G_Notifications::STATUS_NEW); 
		            $n->setItem(1);
		            $has_update = true;
				}

		        if($has_update) {
		            $n->setDateModified(date('Y-m-d H:i:s'));
		            $n->save();
		        } 	
				/*
				 * Add notifications - End
				*/	

	        }else{
	        	$json['is_success'] = false;
	        	$json['message']    = "Invalid form entries";

	        	//General Reports / Shr Audit Trail
				$shr_emp = G_Employee_Helper::findByEmployeeId($employee_id);
				$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
        		$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_FILE, ' for Leave of ', $emp_name.' Leave Type:'. $request_type, $_POST['date_start'], $_POST['date_end'], 0, $shr_emp['position'], $shr_emp['department']);
	        }

	        $json['token'] = Utilities::createFormToken();
	        echo json_encode($json);    		
    }

    function _update_leave_request_approvers(){    	
    	Utilities::verifyFormToken($_POST['token']);    	

    	$data = $_POST['approvers'];    	    	
    	$id   = Utilities::decrypt($_POST['eid']);
    	$json['is_success'] = false;
		$json['message']    = "Cannot save record";

    	if( !empty($data) ){
    		$date 		  = $this->c_date;
    		$request_type = G_Request::PREFIX_LEAVE;
    		$r = new G_Request();
    		$r->setRequestId($id);
    		$r->setActionDate($date);
    		$r->setRequestType($request_type);
			$json = $r->updateRequestApproversDataById($data);
			$r->updateRequestStatus();
    	}

    	echo json_encode($json);
    }

    function _update_leave_request() {
    	
    	Utilities::verifyFormToken($_POST['token']);
    	$employee_id  = Utilities::decrypt($_POST['employee_id']);    	
    	$eid          = Utilities::decrypt($_POST['eid']);

        $e = G_Employee_Finder::findById($employee_id);
        if ($e) {            
	        $leave_id     = Utilities::decrypt($_POST['leave_id']);
	        $date_start   = date("Y-m-d",strtotime($_POST['date_start']));
	        $date_end     = date("Y-m-d",strtotime($_POST['date_end']));
	        $date_applied = date("Y-m-d");
	        $time_applied = date("H:i:s");
	        $is_approved  = $_POST['is_approved'];
	        $comment 	  = $_POST['leave_comments'];	        
	        //$status       = $_POST['is_approved'];
	        $is_paid      = $_POST['is_paid'];
	        $created_by   = G_Employee_Helper::getEmployeeNameById(Utilities::decrypt($_SESSION['sprint_hr']['employee_id']));

	        if( $_POST['start_halfday'] ){
	        	$is_halfday = G_Employee_Leave_Request::YES;
	        }else{
	        	$is_halfday = G_Employee_Leave_Request::NO;
	        }

	        $el = G_Employee_Leave_Request_Finder::findById($eid);
	        if($el){        
				$el->setLeaveId($leave_id);
				$el->setDateStart($date_start);
				$el->setDateEnd($date_end);
				$el->setApplyHalfDayDateStart($is_halfday);		
				$el->setLeaveComments($comment);
				//$el->setIsApproved($status);
				$el->setIsPaid($is_paid);				
				$json = $el->saveRequest();

				if( $json['is_success'] ){
					$el->addLeaveToAttendance($e);
				}

				/*
				 * Add notifications
				*/
				$has_update = false;
				$n = G_Notifications_Finder::findByEventType('Update Attendance');
				if($n) {
		            $n->setStatus(G_Notifications::STATUS_NEW); 
		            $n->setItem(1);
		            $has_update = true;
				}

		        if($has_update) {
		            $n->setDateModified(date('Y-m-d H:i:s'));
		            $n->save();
		        } 	
				/*
				 * Add notifications - End
				*/					

			}else{
				$json['is_success'] = false;
        		$json['message']    = "Record not found";
			}

        }else{
        	$json['is_success'] = false;
        	$json['message']    = "Invalid form entries";
        }

        $json['token'] = Utilities::createFormToken();
        echo json_encode($json);
    }
	
	function _insert_new_employee_leave()
	{

		Utilities::verifyFormToken($_POST['token']);

		$row = $_POST;
		if($_POST['employee_id']){
			$e = G_Employee_Finder::findById(Utilities::decrypt($_POST['employee_id']));
			if($_POST['leave_request_id']){
				$l = G_Employee_Leave_Request_Finder::findById(Utilities::decrypt($_POST['leave_request_id']));
			}else{
				$l = new G_Employee_Leave_Request();
				$l->setDateApplied($this->c_date);
			}
			$l->setEmployeeId(Utilities::decrypt($_POST['employee_id']));
			$l->setLeaveId(Utilities::decrypt($_POST['leave_id']));
			$l->setCompanyStructureId($this->company_structure_id);			
			$l->setDateStart($_POST['date_start']);
			$l->setDateEnd($_POST['date_end']);			
			$l->setApplyHalfDayDateStart($_POST['start_halfday'] ? G_Employee_Leave_Request::YES : G_Employee_Leave_Request::NO);
			$l->setApplyHalfDayDateEnd($_POST['end_halfday'] ? G_Employee_Leave_Request::YES : G_Employee_Leave_Request::NO);			
			
			$l->setLeaveComments($_POST['leave_comments']);
			//$l->setCreatedBy(Utilities::decrypt($this->eid));
			$l->setCreatedBy(Utilities::decrypt($this->eid));
			$l->setIsApproved($_POST['is_approved']);	
			$l->setIsArchive(G_Employee_Leave_Request::NO);
			
			
			//Validate if there are still leave credits
			if($_POST['is_paid'] == G_Employee_Leave_Request::YES && $_POST['is_approved'] == G_Employee_Leave_Request::APPROVED){
				$la = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveId(Utilities::decrypt($_POST['employee_id']),Utilities::decrypt($_POST['leave_id']));	
				if($la){
					if($la->getNoOfDaysAvailable() >= $_POST['number_of_days']){
						$l->setIsPaid($_POST['is_paid']);
						$available_leave = $la->getNoOfDaysAvailable();
						$available_leave-=$_POST['number_of_days'];
						$la->getNoOfDaysAvailable($available_leave);
						$err = '';
						
						//Update Attendance
							$start_date = $_POST['date_start'];		
							$end_date	= $_POST['date_end'];
							$dates 		= Tools::getBetweenDates($start_date, $end_date);
							foreach ($dates as $date) {
								$a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
								if (!$a) {
									$a = new G_Attendance;									
								}
								$a->setLeaveId(Utilities::decrypt($_POST['leave_id']));
								$a->setDate($date);
								$a->setAsLeave();
								$a->setAsPaid();
								$a->setAsNotRestday();
								$a->recordToEmployee($e);
							}	
						//
						
					}else{
						$l->setIsPaid(G_Employee_Leave_Request::NO);
						$err = '<br /><b>Note: Cannot set to with pay. Not enough available leave credits.</b>';
					}	
				}else{
					$l->setIsPaid(G_Employee_Leave_Request::NO);
					$err = '<br /><b>Note: Cannot set to with pay. No available leave credits.</b>';
				}
			}
			//
			
			$l->save();
			
			$es = G_Employee_Subdivision_History_Finder::findRecentHistoryByEmployeeId(Utilities::decrypt($_POST['employee_id']));
			
			if($es){
				$json['es_id']= $es->getCompanyStructureId();
			}

			/*
			 * Add notifications
			*/
			$has_update = false;
			$n = G_Notifications_Finder::findByEventType('Update Attendance');
			if($n) {
	            $n->setStatus(G_Notifications::STATUS_NEW); 
	            $n->setItem(1);
	            $has_update = true;
			}

	        if($has_update) {
	            $n->setDateModified(date('Y-m-d H:i:s'));
	            $n->save();
	        } 	
			/*
			 * Add notifications - End
			*/				
			
			$json['e_id']	  = $_POST['employee_id'];
			$json['is_saved'] = 1;
			$json['message']  = 'Record was successfully saved.' . $err;
		}else {
			$json['is_saved'] = 0;
			$json['message']  = 'Error in sql';
		}
		
		echo json_encode($json);
	}
	
	function _insert_employee_leave_credit()
	{
		Utilities::verifyFormToken($_POST['token']);
		
		if($_POST['employee_id']){			
			$a = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveId(Utilities::decrypt($_POST['employee_id']),Utilities::decrypt($_POST['leave_id']));
			
			if(empty($a)){
				$a = new G_Employee_Leave_Available();
				$available = $_POST['leave_credit'];
				$alloted   = $_POST['leave_credit'];				
			}else{				
				$available = $a->getNoOfDaysAvailable() + $_POST['leave_credit'];
				$alloted   = $a->getNoOfDaysAlloted() + $_POST['leave_credit'];
			}
			
			$a->setEmployeeId(Utilities::decrypt($_POST['employee_id']));
			$a->setLeaveId(Utilities::decrypt($_POST['leave_id']));
			$a->setNoOfDaysAlloted($alloted);
			$a->setNoOfDaysAvailable($available);
			$a->save();
		
			$json['e_id']	  = $_POST['employee_id'];
			$json['is_saved'] = 1;
			$json['message']  = 'Record was successfully updated.' . $err;
		}else {
			$json['is_saved'] = 0;
			$json['message']  = 'Error in sql';
		}
		
		echo json_encode($json);
	}
	
	function _insert_leave_type()
	{
		Utilities::verifyFormToken($_POST['token']);
		
		if($_POST['leave_title']){			
			if($_POST['leave_id']){
				$lt = G_Leave_Finder::findById(Utilities::decrypt($_POST['leave_id']));
			}else{
				$lt = new G_Leave();
			}
			
			$lt->setCompanyStructureId($this->company_structure_id);
			$lt->setName($_POST['leave_title']);
			$lt->setDefaultCredit($_POST['default_credit']);
			$lt->setIsPaid($_POST['is_paid']);
			$lt->setIsArchive(G_Leave::NO);
			$lt->save();		
			
			$json['is_success'] = 1;
			$json['message']    = 'Record was successfully updated.' . $err;
		}else {
			$json['is_saved'] = 0;
			$json['message']  = 'Error in sql';
		}
		
		echo json_encode($json);
	}
	
	function _update_new_employee_leave()
	{
		Utilities::verifyFormToken($_POST['token']);

		$row = $_POST;
		if($_POST['edit_employee_id'] && $_POST['edit_leave_request_id']){			
			$e = G_Employee_Leave_Request_Finder::findById($_POST['edit_leave_request_id']);			
			$e->setEmployeeId($_POST['edit_employee_id']);
			$e->setLeaveId(Utilities::decrypt($_POST['edit_leave_id']));
			$e->setCompanyStructureId($this->company_structure_id);
			$e->setDateApplied($_POST['edit_date_applied']);
			$e->setDateStart($_POST['edit_date_start']);
			$e->setDateEnd($_POST['edit_date_end']);
			$e->setLeaveComments($_POST['edit_leave_comments']);
			$e->setCreatedBy(Utilities::decrypt($this->eid));
			//$e->setIsApproved($_POST['is_approved']);	
			$e->setIsArchive(G_Employee_Leave_Request::NO);
			$e->save();
			
			/*if ($_POST['is_approved']) {
				$start_date = strtotime($_POST['date_start']);
				$end_date   = strtotime($_POST['date_end']);
				$emp = G_Employee_Finder::findById($_POST['employee_id']);
				if ($emp) {
					if ($start_date && $end_date) {
						$start_date = date('Y-m-d', $start_date);
						$end_date   = date('Y-m-d', $end_date);
						
						$dates = Tools::getBetweenDates($start_date, $end_date);
						foreach ($dates as $date) {
							G_Attendance_Helper::updateAttendance($emp, $date);								
						}	
					}
				}
			}*/
			$json['e_id']     = $_POST['edit_employee_id'];
			$json['message']  = 'Record was successfully updated.';
			$json['is_saved'] = 1;
		}else {
			$json['is_saved'] = 0;
			$json['message']  = 'Error in sql';
		}
		
		echo json_encode($json);
	}
	
	function _load_show_leave_credit_details() 
	{
		if(!empty($_POST['h_employee_id'])) {
			$this->load_summary_photo();

			$btn_add_leave_credits_config = array(
	    		'module'				=> 'hr',
	    		'parent_index'			=> 'attendance',
	    		'child_index'			=> 'attendance_leave',
	    		'type' 					=> 'button',
	    		'onclick' 				=> 'javascript:addLeaveCredit("'.Utilities::encrypt($_POST['h_employee_id']).'");',
	    		'id' 					=> 'import_leave_button_wrapper',
	    		'class' 				=> 'curve blue_button',
	    		'icon' 					=> '',
	    		'additional_attribute'	=> '',
	    		'caption' 				=> 'Add Leave Credit(s)'
	    		); 
		
			$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR,'attendance','attendance_leave');
			$this->var['btn_add_leave_credits'] 	= G_Button_Builder::createButtonTagWithPermissionValidation($this->global_user_hr_actions,$btn_add_leave_credits_config);
			$this->var['employee'] 					= $employee = G_Employee_Helper::findByEmployeeId($_POST['h_employee_id']);			
			$this->var['h_employee_id']				= $_POST['h_employee_id'];
			$this->view->render('leave/leave_list/show_leave_credit_details.php',$this->var);
		}
	}
	
	function _load_get_employee_leave_credits() 
	{		
		if(!empty($_POST['h_employee_id'])) {
			$a = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveId(Utilities::decrypt($_POST['h_employee_id']),Utilities::decrypt($_POST['leave_type']));
			if($a){
				if($a->getNoOfDaysAlloted() > 0){
					$json['alloted'] = $a->getNoOfDaysAlloted();
				}else{$json['alloted'] = 0;}
				
				if($a->getNoOfDaysAvailable() > 0){
					$json['available'] = $a->getNoOfDaysAvailable();
				}else{$json['available'] = 0;}				
			}else{
				$json['available'] = 'No Available Leave(s).';
				$json['alloted']   = 'No Available Leave(s).';
			}
		}else{
			$json['available'] = 'No Available Leave(s).';
			$json['alloted']   = 'No Available Leave(s).';
		}
		
		//Get Default Leave Credit
			$l = G_Leave_Finder::findById(Utilities::decrypt($_POST['leave_type']));
			if($l){
				$json['default_credit'] = $l->getDefaultCredit();
			}else{
				$json['default_credit'] = 0;
			}	
		//
		
		echo json_encode($json);
	}
	
	function _load_show_leave_details() 
	{
		if(!empty($_POST['h_employee_id'])) {
			$this->load_summary_photo();
			$this->var['employee'] 		= $employee = G_Employee_Helper::findByEmployeeId($_POST['h_employee_id']);			
			$this->var['h_employee_id'] = $_POST['h_employee_id'];
			$this->view->render('leave/leave_list/show_leave_details.php',$this->var);
		}
	}
	
	function load_summary_photo()
	{
		$employee_id = $_POST['h_employee_id'];
		$e = G_Employee_Finder::findById($employee_id);
		$file = PHOTO_FOLDER.$e->getPhoto();
		
		if(Tools::isFileExist($file)==true && $e->getPhoto()!='') {
			$this->var['filemtime'] = md5($e->getPhoto()).date("His");
			$this->var['filename'] = $file;			
		}else {
			$this->var['filename'] = BASE_FOLDER. 'images/profile_noimage.gif';		
		}			
	}
	
	function _load_overtime_list_dt() 
	{
		$this->var['h_employee_id'] = $_POST['h_employee_id'];
		$this->view->render('overtime/overtime_list/_overtime_list_dt.php',$this->var);
	}
		
	function _load_archive_leave_request() 
	{
		if(!empty($_POST)) {
			$l = G_Employee_Leave_Request_Finder::findById(Utilities::decrypt($_POST['e_id']));
			if($l) {
				$eid = $l->getEmployeeId();

				$json['e_id']     = $l->getEmployeeId();
				$json['is_success'] = 1;
				$l->setIsArchive(G_Employee_Leave_Request::YES);
				$l->save();
				
				$es = G_Employee_Subdivision_History_Finder::findRecentHistoryByEmployeeId($eid);
			
				if($es){
					$json['es_id']= $es->getCompanyStructureId();
				}
							
			}
		}else{$json['is_success'] = 0;}
		
		echo json_encode($json);
	}	

	function _approve_selected_requests(){
		$mArray  = $_POST['dtChk'];
		$failed  = 0;
		$success = 0;
		foreach($mArray as $key => $value){	
			$lr = G_Employee_Leave_Request_Finder::findById($value);
			if( $lr ){
				$employee_id = $lr->getEmployeeId();
				$e 			 = G_Employee_Finder::findById($employee_id);
				if( $e ){
					$json = $lr->approveRequest();		
					if( $json['is_success'] ){
						$lr->addLeaveToAttendance($e);
						$success++;
					}else{
						$failed++;
					}					
				}else{
					$failed++;
				}
			}else{
				$failed++;
			}
		}

		/*
		 * Add notifications
		*/
		$has_update = false;
		$n = G_Notifications_Finder::findByEventType('Update Attendance');
		if($n) {
            $n->setStatus(G_Notifications::STATUS_NEW); 
            $n->setItem(1);
            $has_update = true;
		}

        if($has_update) {
            $n->setDateModified(date('Y-m-d H:i:s'));
            $n->save();
        } 	
		/*
		 * Add notifications - End
		*/			

		$json['is_success'] = 1;
		$json['message']    = "Total successful update : <b>{$success}</b> record(s) <br /> Total failed update : <b>{$failed}</b> record(s)";

		echo json_encode($json);
	}

	function _disapprove_selected_requests(){		
		$mArray  = $_POST['dtChk'];
		$failed  = 0;
		$success = 0;
		foreach($mArray as $key => $value){	
			$lr = G_Employee_Leave_Request_Finder::findById($value);

			if( $lr ){
				$employee_id 			= $lr->getEmployeeId();
				$leave_request_status 	= $lr->getIsApproved();
				$rleave_id 				= $lr->getLeaveId();
				$num_days_to_add 		= Tools::getDayDifference($lr->getDateStart(),$lr->getDateEnd()) + 1;

				$e 			 = G_Employee_Finder::findById($employee_id);
				if( $e ){
					$data = $lr->hrDisApproveRequest();
					if( $data['is_success'] ){

						if($leave_request_status == 'Approved') {
							//revert or add the disapproved leave credit to leave credit table
							$elcr = new G_Employee_Leave_Available();
							$elcr->setEmployeeId($employee_id);
							$elcr->setLeaveId($rleave_id);
							$is_reverted = $elcr->addLeaveCredits($num_days_to_add); //Return leave credits 
							//$is_reverted['is_success']
						}

						$lr->addLeaveToAttendance($e);
						$success++;
					}else{
						$failed++;
					}				
				}else{
					$failed++;
				}
			}else{
				$failed++;
			}
		}

		/*
		 * Add notifications
		*/
		$has_update = false;
		$n = G_Notifications_Finder::findByEventType('Update Attendance');
		if($n) {
            $n->setStatus(G_Notifications::STATUS_NEW); 
            $n->setItem(1);
            $has_update = true;
		}

        if($has_update) {
            $n->setDateModified(date('Y-m-d H:i:s'));
            $n->save();
        } 	
		/*
		 * Add notifications - End
		*/			

		$json['is_success'] = 1;
		$json['message']    = "Total successful update : <b>{$success}</b> record(s) <br /> Total failed update : <b>{$failed}</b> record(s)";

		echo json_encode($json);
	}
	
	function _pending_leave_with_selected_action()
	{

		$mArray = $_POST['dtChk'];
		if($mArray){
			$cd = 0;
			$c  = 0;
			foreach($mArray as $key => $value){	
				//Get Record
				$lr = G_Employee_Leave_Request_Finder::findById($value);						
				if($lr){
					$d++;
					if($_POST['chkAction'] == 'archive'){
						//Archive Request//
						$lr->setIsArchive(G_Employee_Leave_Request::YES);
						$lr->save();
						$json['message'] = 'Successfully archived (' . $d . ') leave requests';
						$json['is_success'] = 1;
					} elseif ($_POST['chkAction'] == 'approve'){
                        $lr->approve();
                        $json['is_success'] = 1;
                        $json['message']    = 'Successfully approved (' . $d . ') leave requests';

                        $employee_id = $lr->getEmployeeId();
        				$start_date = $lr->getDateStart();
        				$end_date	= $lr->getDateEnd();
                        $e = G_Employee_Finder::findById($employee_id);
                        if ($e) {
                            G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $start_date, $end_date);
                        }
					}
				}
			}
		}
		echo json_encode($json);
	}

	function _pending_leave_with_selected_action_OLD()
	{
		$mArray = $_POST['dtChk'];
		if($mArray){
			$cd = 0;
			$c  = 0;
			foreach($mArray as $key => $value){
				//Get Record
				$lr = G_Employee_Leave_Request_Finder::findById($value);
				if($lr){
					$d++;
					if($_POST['chkAction'] == 'archive'){
						//Archive Request//
						$lr->setIsArchive(G_Employee_Leave_Request::YES);
						$lr->save();
						$json['message'] = 'Successfully archived ' . $d . ' record(s)';
						$json['is_success'] = 1;
						/////////////////

					}elseif($_POST['chkAction'] == 'approve'){
						//Validate if with pay
						if($lr->getIsPaid() == G_Employee_Leave_Request::YES){
							//Validate if with Leave Credits
							$la = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveId($lr->getEmployeeId(),$lr->getLeaveId());
							if($la){
								//Get Number of Days
								$num_days = Tools::getDayDifference($lr->getDateStart(),$lr->getDateEnd()) + 1;
								//Add Halfday
								if($lr->getApplyHalfDayDateStart() == G_Employee_Leave_Request::YES){
									$num_days = $num_days - 0.5;
								}

								if($lr->getApplyHalfDayDateEnd() == G_Employee_Leave_Request::YES){
									$num_days = $num_days - 0.5;
								}

								//Validate if enough leave credits
								if($la->getNoOfDaysAvailable() >= $num_days){
									//Update Leave Credits
									$new_leave_available = $la->getNoOfDaysAvailable() - $num_days;
									$la->setNoOfDaysAvailable($new_leave_available);
									$la->save();

									//Update Request
									$lr->setIsApproved(G_Employee_Leave_Request::APPROVED);
									$lr->save();
									$json['is_success'] = 1;
									$json['message']    = 'Successfully approved ' . $d . ' record(s)';
								}else{
									$error++;
									$json['is_success'] = 1;
								}

							}else{
								$error++;
								$json['is_success'] = 1;
							}
						}else{
							$lr->setIsApproved(G_Employee_Leave_Request::APPROVED);
							$lr->save();

							$json['is_success'] = 1;
							$json['message']    = 'Successfully approved ' . $d . ' record(s)';
						}

						$employee 	= G_Employee_Finder::findById($lr->getEmployeeId());
						$from		= $lr->getDateStart();
						$to			= $lr->getDateEnd();

						G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($employee, $from, $to);
						//
						if($error > 0){
							$json['message'] .= '<br><b>' . $error. ' record(s) were not approve due to not enough leave credits or no available leave credits.</b>';
						}
					}
				}
			}


		}else{
			$json['is_success'] = 1;
		}
		echo json_encode($json);
	}
	
	function archiveLeaveRequestsWithSelectedAction()
	{
	
		$mArray = $_POST['dtChk'];
		if($mArray){			
			$cd = 0;
			$c  = 0;
			foreach($mArray as $key => $value){	
				//Get Record	
				$lr = G_Employee_Leave_Request_Finder::findById($value);						
				if($lr){						
					$d++;
					if($_POST['chkAction'] == 'restore'){																	
						//Delete Category//
						$lr->setIsArchive(G_Employee_Leave_Request::NO);
						$lr->save();
						$json['message'] = 'Successfully restored ' . $d . ' archived record(s)';	
						$json['is_success'] = 1;
						/////////////////
					}
				}
			}						
						
		
		}else{
			$json['is_success'] = 1;
		}
		echo json_encode($json);
	}

	function _approve_request() {
		$json = array();

		if( !empty($_POST['e_id']) ){
			$lr = G_Employee_Leave_Request_Finder::findById(Utilities::decrypt($_POST['e_id']));
					
			if( $lr ) {
				$employee_id = $lr->getEmployeeId();
				$e 			 = G_Employee_Finder::findById($employee_id);
				if( $e ){

					$slv = G_Settings_Leave_General_Finder::findById(1);
			    	$default_general_leave = $slv->getLeaveId();

			     if( (!empty($default_general_leave) || $default_general_leave > 0) && ($default_general_leave == $lr->getLeaveId()) ){//if has Leave General Rule	 				

			    		$json = $lr->checkGeneralRule()->approveRequestWithGeneralRule();
			    						
			    	} else {		    		
			    		$json = $lr->approveRequest();						
			    	}
					
					if( $json['is_success'] ){
						$lr->addLeaveToAttendance($e);
						$json['message'] = "Record updated";

						//General Reports / Shr Audit Trail
						$shr_emp = G_Employee_Helper::findByEmployeeId($employee_id);
						$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
        				$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_APPROVED, ' Leave of', $emp_name, $lr->date_start, $lr->date_end, 1, $shr_emp['position'], $shr_emp['department']);
					}

					/*
					 * Add notifications
					*/
					$has_update = false;
					$n = G_Notifications_Finder::findByEventType('Update Attendance');
					if($n) {
			            $n->setStatus(G_Notifications::STATUS_NEW); 
			            $n->setItem(1);
			            $has_update = true;
					}

			        if($has_update) {
			            $n->setDateModified(date('Y-m-d H:i:s'));
			            $n->save();
			        } 	
					/*
					 * Add notifications - End
					*/	

				}else{					
					$json['is_success'] = false;
					$json['message']    = "Employee record not found";

					//General Reports / Shr Audit Trail
					$shr_emp = G_Employee_Helper::findByEmployeeId($employee_id);
					$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
        			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_APPROVED, ' Leave of ', $emp_name, $lr->date_start, $lr->date_end, 0, $shr_emp['position'], $shr_emp['department']);
				}

			}else{
				$json['is_success'] = false;
				$json['message']    = "Record not found";

				//General Reports / Shr Audit Trail
				$shr_emp = G_Employee_Helper::findByEmployeeId($employee_id);
				$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
        		$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_APPROVED, ' Leave of ', $emp_name, $lr->date_start, $lr->date_end, 0, $shr_emp['position'], $shr_emp['department']);
			}
		}else{
			$json['is_success'] = false;
			$json['message']    = "Record not found";

			//General Reports / Shr Audit Trail
			$shr_emp = G_Employee_Helper::findByEmployeeId($employee_id);
			$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
        	$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_APPROVED, ' Leave of ', $emp_name, $lr->date_start, $lr->date_end, 0, $shr_emp['position'], $shr_emp['department']);
		}

		echo json_encode($json);
	}

    function _approve_leave_request() {
        $json['message'] = 'An error occured. Please contact the administrator';
        $json['is_success'] = 0;
        if(!empty($_POST)) {
            $lr = G_Employee_Leave_Request_Finder::findById(Utilities::decrypt($_POST['e_id']));
            if ($lr) {
                $is_approved = $lr->approve();
                if ($is_approved) {
                    $json['is_success'] = 1;
                    $json['message']    = 'Leave request has been approved';
                    $date_start = $lr->getDateStart();
                    $date_end = $lr->getDateEnd();
                    $employee_id = $lr->getEmployeeId();
                    $e = G_Employee_Finder::findById($employee_id);
                    if ($e) {
                        G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $date_start, $date_end);
                    }

					/*
					 * Add notifications
					*/
					$has_update = false;
					$n = G_Notifications_Finder::findByEventType('Update Attendance');
					if($n) {
			            $n->setStatus(G_Notifications::STATUS_NEW); 
			            $n->setItem(1);
			            $has_update = true;
					}

			        if($has_update) {
			            $n->setDateModified(date('Y-m-d H:i:s'));
			            $n->save();
			        } 	
					/*
					 * Add notifications - End
					*/	

					//General Reports / Shr Audit Trail
					$shr_emp = G_Employee_Helper::findByEmployeeId($employee_id);
					$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
        			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_APPROVED, ' Leave of ', $emp_name, $lr->start_date, $lr->start_end, 1, $shr_emp['position'], $shr_emp['department']);
			

                } else {
                    $json['is_success'] = 0;
                    $json['message']    = 'Leave request has an error';

                    //General Reports / Shr Audit Trail
					$shr_emp = G_Employee_Helper::findByEmployeeId($employee_id);
					$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
        			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_APPROVED, ' Leave of ', $emp_name, $lr->start_date, $lr->start_end, 0, $shr_emp['position'], $shr_emp['department']);
                }
            }
        }
        echo json_encode($json);
    }
	
	function _load_approve_leave_request()
	{
		if(!empty($_POST)) {
			$lr = G_Employee_Leave_Request_Finder::findById(Utilities::decrypt($_POST['e_id']));
			if($lr) {

				$employee 	= G_Employee_Finder::findById($lr->getEmployeeId());
				$from		= $lr->getDateStart();
				$to			= $lr->getDateEnd();
				$leave_id   = $lr->getLeaveId();
				
				//Validate if with pay
				if($lr->getIsPaid() == G_Employee_Leave_Request::YES){
					//Validate if with Leave Credits
					$la = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveId($lr->getEmployeeId(),$lr->getLeaveId());
					if($la){
						//Get Number of Days
						$num_days = Tools::getDayDifference($lr->getDateStart(),$lr->getDateEnd()) + 1;
						//Add Halfday						
						if($lr->getApplyHalfDayDateStart() == G_Employee_Leave_Request::YES){
							$num_days = $num_days - 0.5;							
						}
						
						if($lr->getApplyHalfDayDateEnd() == G_Employee_Leave_Request::YES){
							$num_days = $num_days - 0.5;
						}
						
						//Validate if enough leave credits
						if($la->getNoOfDaysAvailable() >= $num_days){
							//Update Leave Credits
							$new_leave_available = $la->getNoOfDaysAvailable() - $num_days;
							$la->setNoOfDaysAvailable($new_leave_available);
							$la->save();
							
							//Update Request
							$lr->setIsApproved(G_Employee_Leave_Request::APPROVED);
							$lr->save();
							$json['is_success'] = 1;
							$json['message']    = 'Record was successfully updated.';							
							
							//Update Attendance							
								$dates 		= Tools::getBetweenDates($from, $to);
								foreach ($dates as $date) {
									$a = G_Attendance_Finder::findByEmployeeAndDate($employee, $date);
									if (!$a) {
										$a = new G_Attendance;									
									}
									$a->setLeaveId($leave_id);
									$a->setDate($date);
									$a->setAsLeave();
									$a->setAsPaid();
									$a->setAsNotRestday();
									$a->recordToEmployee($employee);
								}	
							//						
								
						}else{
							$json['message']    = 'Employee requested the leave does not have enough leave credits.';
							$json['is_success'] = 0;
						}
						
					}else{
						$json['message']    = 'Cannot Approve the selected leave with pay.Employee requested the leave does not have leave credit(s).';
						$json['is_success'] = 0;
					}
				}else{
					$lr->setIsApproved(G_Employee_Leave_Request::APPROVED);
					$lr->save();
					
					G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($employee, $from, $to);
					
					$json['is_success'] = 1;
					$json['message']    = 'Record was successfully updated.';
				}				
							
			}
		}else{$json['is_saved'] = 0;}
		
		echo json_encode($json);
	}
	
	function _leave_type_with_selected_action() 
	{	
		$mArray = $_POST['dtChk'];
		if($mArray){			
			$cd = 0;
			$c  = 0;
			foreach($mArray as $key => $value){	
				//Get Record	
				$lt = G_Leave_Finder::findById($value);		
				if($lt){						
					$d++;
					if($_POST['chkAction'] == 'archive'){																	
						//Archive Leave Type//
						$lt->setIsArchive(G_Leave::YES);
						$lt->save();
						$json['message']    = 'Successfully archived ' . $d . ' record(s)';	
						$json['is_success'] = 1;
						/////////////////						
					}					
				}
			}		
		
		}else{
			$json['is_success'] = 1;
		}		
		echo json_encode($json);
	}

	function _disapprove_request()
	{
		if(!empty($_POST)) {
			$lr = G_Employee_Leave_Request_Finder::findById(Utilities::decrypt($_POST['e_id']));
			if( $lr ){
				$employee_id = $lr->getEmployeeId();
				$e 			 = G_Employee_Finder::findById($employee_id);
				if( $e ){
					$date = date("Y-m-d",strtotime($lr->getDateStart()));
					$cp   = new G_Cutoff_Period();
					$cutoff_data = $cp->getCurrentCutoffPeriod($date);					
					if( $cutoff_data['is_lock'] == G_Cutoff_Period::NO ){
						$data = $lr->hrDisApproveRequest();
						if( $data['is_success'] ){
							$lr->addLeaveToAttendance($e);
						}
						$json = $data;
					}else{
						$json['is_success'] = false;
						$json['message']    = "Cutoff period for selected request is already locked for editing";	
					}
				}else{
					$json['is_success'] = false;
					$json['message']    = "Employee record not found";
				}				
			}else{
				$json['is_success'] = false;
				$json['message']    = "Record not found";
			}
		}else{
			$json['is_success'] = false;
			$json['message']    = "Record not found";
		}

		echo json_encode($json);
	}

	function _revert_request()
	{
		//date_default_timezone_set('America/Los_Angeles');
		if(!empty($_POST)) {
			$lr = G_Employee_Leave_Request_Finder::findById(Utilities::decrypt($_POST['e_id']));

			if( $lr ){
				$employee_id = $lr->getEmployeeId();
				$e 			 = G_Employee_Finder::findById($employee_id);
				if( $e ){
					$date = date("Y-m-d",strtotime($lr->getDateStart()));
					$cp   = new G_Cutoff_Period();

					$cutoff_data = $cp->getCurrentCutoffPeriod($date);					
					$cutoff = G_Cutoff_Period_Finder::findByDate($date);
					if($cutoff) {
						if($cutoff->getIsLock() == G_Cutoff_Period::NO ) {
							$data = $lr->resetToPendingRequest();
							if( $data['is_success'] ){
								$lr->addLeaveToAttendance($e);
							}
							$json = $data;							
						} else {
							$json['is_success'] = false;
							$json['message']    = "Cutoff period for selected request is already locked for editing";								
						}
				
					} else {
						if( $cutoff_data['is_lock'] == G_Cutoff_Period::NO ){						
							$data = $lr->resetToPendingRequest();
							if( $data['is_success'] ){
								$lr->addLeaveToAttendance($e);
							}
							$json = $data;
						}else{
							$json['is_success'] = false;
							$json['message']    = "Cutoff period for selected request is already locked for editing";	
						}
					}
					
				}else{
					$json['is_success'] = false;
					$json['message']    = "Employee record not found";
				}				
			}else{
				$json['is_success'] = false;
				$json['message']    = "Record not found";
			}
		}else{
			$json['is_success'] = false;
			$json['message']    = "Record not found";
		}

		echo json_encode($json);
	}

	function _load_revert_approved_leave_request()
	{
		if(!empty($_POST)) {
			$l = G_Employee_Leave_Request_Finder::findById(Utilities::decrypt($_POST['e_id']));
			if($l) {
				$eid = $l->getEmployeeId();
				$start_date = $l->getDateStart();
				$end_date	= $l->getDateEnd();

                $l->voidRequest();
                $e = G_Employee_Finder::findById($eid);
                if ($e) {
                    G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $start_date, $end_date);
                }
				$json['e_id']     = $l->getEmployeeId();
				$json['is_success'] = 1;
			}
		} else {
		  $json['is_success'] = 0;
        }

		echo json_encode($json);
	}
	
	function _load_revert_approved_leave_request_OLD()
	{
		if(!empty($_POST)) {
			$l = G_Employee_Leave_Request_Finder::findById(Utilities::decrypt($_POST['e_id']));
			if($l) {
				$eid = $l->getEmployeeId();
				
				$json['e_id']     = $l->getEmployeeId();
				$json['is_success'] = 1;
				$l->setIsApproved(G_Employee_Leave_Request::PENDING);
				$l->save();
				
				$start_date = $l->getDateStart();		
				$end_date	= $l->getDateEnd();
				$dates 		= Tools::getBetweenDates($start_date, $end_date);
				
				$e = G_Employee_Finder::findById($eid);

				foreach ($dates as $date) {
					$a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
					if (!$a) {
						$a = new G_Attendance;						 G_Attendance;									
					}
					$a->setLeaveId($l->setLeaveId());
					$a->setDate($date);
					$a->setAsAbsent();
					$a->setAsPaid();
					$a->setAsNotRestday();
					$a->recordToEmployee($e);
				}
				
				if($es){
					$json['es_id']= $es->getCompanyStructureId();
				}

			}
		}else{$json['is_success'] = 0;}

		echo json_encode($json);
	}
	
	function _archive_with_selected_action() 
	{
			
		$mArray = $_POST['dtChk'];
		if($mArray){			
			$cd = 0;
			$c  = 0;
			foreach($mArray as $key => $value){	
				//Get Record													
				$d++;
				
				if($_POST['chkAction'] == 'restore_leave_type'){																	
					$lt = G_Leave_Finder::findById($value);		
					if($lt){
						//Archive Leave Type//
						$lt->setIsArchive(G_Leave::NO);
						$lt->save();
						$json['message']    = 'Successfully restored ' . $d . ' archived record(s)';	
						$json['is_success'] = 1;
						$json['form']		= 1;						
						/////////////////		
					}else{
						$json['message']    = 'No record(s) to restore';	
						$json['is_success'] = 1;
						$json['form']		= 1;				
					}
				}	
				
				if($_POST['chkActionSub'] == 'restore_leave_request'){
					$l = G_Employee_Leave_Request_Finder::findById($value);
					if($l){																	
						//Archive Leave Type//
						$l->setIsArchive(G_Employee_Leave_Request::NO);
						$l->save();
						$json['message']    = 'Successfully restored ' . $d . ' archived record(s)';	
						$json['is_success'] = 1;
						$json['form']		= 2;
						/////////////////	
					}else{
						$json['message']    = 'No record(s) to restore';	
						$json['is_success'] = 1;
						$json['form']		= 2;	
					}
				}					
				
			}		
		
		}else{
			$json['is_success'] = 1;
		}		
		echo json_encode($json);
	}
	
	function _load_archive_leave_type() 
	{
		if(!empty($_POST)) {
			$l = G_Leave_Finder::findById(Utilities::decrypt($_POST['e_id']));
			if($l) {
				$json['is_success'] = 1;
				$l->setIsArchive(G_Leave::YES);
				$l->save();							
			}
		}else{$json['is_success'] = 0;}
		
		echo json_encode($json);
	}
	
	function _load_restore_leave_request() 
	{
		if(!empty($_POST)) {
			$l = G_Employee_Leave_Request_Finder::findById(Utilities::decrypt($_POST['e_id']));
			if($l) {
				$eid = $l->getEmployeeId();
				
				$json['e_id']     = $l->getEmployeeId();
				$json['is_success'] = 1;
				$l->setIsArchive(G_Employee_Leave_Request::NO);
				$l->save();
				
				$es = G_Employee_Subdivision_History_Finder::findRecentHistoryByEmployeeId($eid);
			
				if($es){
					$json['es_id']= $es->getCompanyStructureId();
				}
							
			}
		}else{$json['is_success'] = 0;}
		
		echo json_encode($json);
	}
	
	function _load_restore_leave_type() 
	{
		if(!empty($_POST)) {
			$l = G_Leave_Finder::findById(Utilities::decrypt($_POST['e_id']));
			if($l) {
				$json['is_success'] = 1;
				$l->setIsArchive(G_Leave::NO);
				$l->save();
			}
		}else{$json['is_success'] = 0;}
		
		echo json_encode($json);
	}
	
	function _load_token() 
	{
		$token = Utilities::createFormToken();
		$return['token'] = $token;
		echo json_encode($return);
	}
	
	function _import_leave_excel()
	{
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
				
		$file = $_FILES['leave']['tmp_name'];
		//$file = BASE_PATH . 'files/files/attendance.xls';
        $lr = new G_Employee_Leave_Request_Importer($file);
        $lr->import();
				
		if ($lr->imported_records > 0) {
			$return['is_imported'] = true;
			if ($lr->error_count > 0) {
                $lr->total_records = $lr->total_records - 1; // minus the excel title header
				$msg =  $lr->imported_records. ' of '.$lr->total_records .' records has been successfully imported.';
				if($lr->error_employee_code>0) {
					$msg .= '<br> '. $lr->error_employee_code.' error(s) found in Employee Code.<br>
							List of Employee Code does not exist<br>
					';	
					foreach($lr->code as $key=>$value) {
						$msg .= "Row: " .$value.'<br>';
					}
				}
	
				$return['message']= $msg;
			} else {
				$return['message'] = $lr->imported_records . ' Record(s) has been successfully imported.';
			}

			/*
			 * Add notifications
			*/
			$has_update = false;
			$n = G_Notifications_Finder::findByEventType('Update Attendance');
			if($n) {
	            $n->setStatus(G_Notifications::STATUS_NEW); 
	            $n->setItem(1);
	            $has_update = true;
			}

	        if($has_update) {
	            $n->setDateModified(date('Y-m-d H:i:s'));
	            $n->save();
	        } 	
			/*
			 * Add notifications - End
			*/	

		} else {
			$return['message'] = 'There was a problem importing the leave. Please contact the administrator.';
		}
		//echo json_encode($return);	
		echo $return['message'];
	}
	
	function download_leave_error_log() {
		$this->var['filename'] 	 = 'leave_errors_'.date('m-d-y').'.xls';
		$this->var['error_logs'] = $error_logs = G_Leave_Error_Finder::findAllErrorsNotFixed();
		$this->view->render('leave/error_log_report.html.php',$this->var);
	}
	
	function _load_clear_import_error() {
		$errors = G_Leave_Error_Finder::findAllErrorsNotFixed();
		foreach($errors as $e):
			$e->setAsFixed(YES);
			$e->addError();
		endforeach;	
	}
	
	function download_leave() {
		if(!empty($_GET)) {
		
			ini_set("memory_limit", "999M");
			set_time_limit(999999999999999999999);
			
			$frequency_id  = $_GET['selected_frequency'];
			$from 	= $_GET['from'];
			$to		= $_GET['to'];
			$this->var['filename'] = "leave_list_{$from}_{$to}.xls";
			$this->var['leave'] = $leave = G_Employee_Leave_Request_Finder::findAllActiveLeaveByFromTo($from,$to);
			
			$this->view->render('leave/download_leave.html.php',$this->var);
		}
	}
	
	function html_show_import_leave_format() {
		$this->view->setTemplate('template_blank.php');
		$this->view->render('leave/html/html_show_import_leave_format.php', $this->var);	
	}

	function process_incentive_leave() {
		$json = array(
			'is_success' => false,
			'message' => 'No data to process'
		);

		if( !empty($_POST) ){
			if( $_POST['month'] != '' && $_POST['year'] != '' ){
				$year  = $_POST['year'];
				$month = $_POST['month'];

				$is_processed = G_Incentive_Leave_History_Helper::isMonthNumberAndYearExists($month, $year);
				if( $is_processed > 0 ){
					$json = array(
						'is_success' => false,
						'message' => 'Incentive leave already processed.'
					);
				}else{
					$il = new G_Incentive_Leave_History();
					$il->setYear($year);
					$il->setMonthNumber($month);
					$json = $il->process()->addToCredits();
				}
			}			
		}

		echo json_encode($json);
	}

	function ajax_load_payroll_period_by_year()
	{
		$selected_year = $_GET['selected_year'];
		$selected_frequency = $_GET['selected_frequency'];
		if( $selected_year == '' || $selected_year <= 0 ){
			$selected_year = date("Y");
		}		

        $selected_year = $selected_year;

        if ($selected_frequency == 2) {
        	$c = G_Weekly_Cutoff_Period_Finder::findAllCutoffByYear($selected_year);
        }
          else if ($selected_frequency == 3) {
        	$c = G_Monthly_Cutoff_Period_Finder::findAllCutoffByYear($selected_year);
        }
        else {
        	$c = G_Cutoff_Period_Finder::findAllCutoffByYear($selected_year);
        }

        $this->var['selected_cutoff'] = $_GET['selected_cutoff'];
        $this->var['selected_year']   = $selected_year;
        $this->var['selected_frequency']   = $selected_frequency;
        $this->var['cutoff_periods']  = $c;
		$this->view->noTemplate();
		$this->view->render('leave/_payroll_period.php',$this->var);
	}		

}
?>