<?php
class OB_Controller extends Controller
{
	function __construct()
	{	
		parent::__construct();

		Loader::appStyle('style.css');
		Loader::appMainScript('ob_admin.js');
		Loader::appMainScript('ob_admin_base.js');		
		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
        Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');
		Loader::appMainUtilities();

		$frequency_id = 1;

		if( isset($_GET['selected_frequency']) && !empty($_GET['selected_frequency']) ) {
			$frequency_id = $_GET['selected_frequency'];
		}

		$this->sprintHdrMenu(G_Sprint_Modules::HR, 'attendance');

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
				$from  = $cutoff_data->getStartDate();
				$to    = $cutoff_data->getEndDate();
				$hpid  = Utilities::encrypt($cutoff_data->getId());
			}

			if($cutoff_data) {
				if($hpid){

					if ($frequency_id == 2) {
						$this->var['is_period_lock'] = $_SESSION['sprint_hr']['is_period_lock'] = G_Weekly_Cutoff_Period_Helper::isPeriodLock($hpid);
					}
					else if ($frequency_id == 3) {
						$this->var['is_period_lock'] = $_SESSION['sprint_hr']['is_period_lock'] = G_Monthly_Cutoff_Period_Helper::isPeriodLock($hpid);
					}
					else {
						$this->var['is_period_lock'] = $_SESSION['sprint_hr']['is_period_lock'] = G_Cutoff_Period_Helper::isPeriodLock($hpid);
					}
				}else{			
					$this->var['is_period_lock'] = $_SESSION['sprint_hr']['is_period_lock'];
				}
				
				if($from && $to && $hpid){
					$this->var['download_url']    = url('reports/download_ob_request?from=' . $from . '&to=' . $to . '&hpid=' . $hpid . '&selected_frequency=' . $frequency_id);
					$this->var['period_selected'] = '<small style="font-size:15px;">Period: <b>' . Tools::convertDateFormat($from) . ' </b> to <b>' . Tools::convertDateFormat($to) . '</b></small>';
				}
			}

		} else {

			if($_GET['hpid']){

				if ($frequency_id == 2) {
					$this->var['is_period_lock'] = $_SESSION['sprint_hr']['is_period_lock'] = G_Weekly_Cutoff_Period_Helper::isPeriodLock($_GET['hpid']);
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
			
			if($_GET['from'] && $_GET['to'] && $_GET['hpid']){
				$this->var['download_url']    = url('reports/download_ob_request?from=' . $_GET['from'] . '&to=' . $_GET['to'] . '&hpid=' . $_GET['hpid'] . '&selected_frequency=' . $frequency_id);
				$this->var['period_selected'] = '<small style="font-size:15px;">Period: <b>' . Tools::convertDateFormat($_GET['from']) . ' </b> to <b>' . Tools::convertDateFormat($_GET['to']) . '</b></small>';
			}

		}
		
		$this->eid                  = $this->global_user_eid;
		$this->company_structure_id = $this->global_user_ecompany_structure_id;
		$this->c_date  				= Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila');
		$this->default_method       = 'index';					
		$this->var['leave']         = 'selected';			
		$this->var['employee']      = 'selected';
		$this->var['eid']           = $this->eid;	

		$this->var['departments']   = G_Company_Structure_Finder::findByParentID(Utilities::decrypt($this->global_user_ecompany_structure_id));

		if(!isset($_GET['year_selected'])) {
			$this->var['year_selected']     = date("Y", strtotime($_GET['to']));
		} else {
			$this->var['year_selected']     = $_GET['year_selected'];	
		}
						
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

		$this->validatePermission(G_Sprint_Modules::HR,'attendance','');
	}
	
	function index()
	{			
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();		
		$enable_next_previous_link = false;

		$frequency_id = 1;

		if( isset($_GET['selected_frequency']) && !empty($_GET['selected_frequency']) ) {
			$frequency_id = $_GET['selected_frequency'];
		}

		$this->var['selected_frequency'] = $frequency_id;

		$this->var['pendings']   = 'class="selected"';				
		$this->var['module'] 	 = 'ob'; 		
				
		$period['to']   = $_GET['to'];
		$period['from'] = $_GET['from'];
		$period['hpid'] = $_GET['hpid'];
		
		$eid  = $_GET['hpid'];

		$btn_request_ob_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'attendance',
    		'child_index'			=> 'official_business',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:show_add_ob_request_form("'.$period['from'].'","'.$period['to'].'");',
    		'id' 					=> 'add_ob_button',
    		'class' 				=> 'add_button',
    		'icon' 					=> '',
    		'additional_attribute'	=> '',
    		'caption' 				=> '<strong>+</strong><b>Request OB</b>'
    		); 

    	$btn_import_ob_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'attendance',
    		'child_index'			=> 'official_business',
    		'href' 					=> '#',
    		'onclick' 				=> 'javascript:importOBRequest("'.$period['from'].'","'.$period['to'].'");',
    		'id' 					=> 'import_undertime',
    		'class' 				=> 'add_button pull-right',
    		'icon' 					=> '<i class="icon-arrow-left"></i>',
    		'additional_attribute'	=> '',
    		'caption' 				=> 'Import OB'
    		); 
		
		$this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::HR,'attendance','official_business');
		$this->var['btn_request_ob'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_request_ob_config);
        $this->var['btn_import_ob'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_import_ob_config);
		
		if($eid){	
			Jquery::loadMainTipsy();
			Jquery::loadMainJqueryDatatable();
			Jquery::loadMainTextBoxList();
			$this->var['eid'] 		  = $eid;
			$this->var['period']      = $period;			
			$this->var['page_title']  = 'Official Business Requests ';
			$this->view->setTemplate('template_leftsidebar.php');

            $cutoff_id = Utilities::decrypt($_GET['hpid']);
            $from_date = $_GET['from'];
       		$to_date   = $_GET['to'];
       		$this->var['cutoff_selected'] = $from_date."/".$to_date;

       		if($enable_next_previous_link) {
	            if ($frequency_id == 2) {
	            	$c  = new G_Weekly_Cutoff_Period();
	            }
	               else if ($frequency_id == 3) {
	            	$c  = new G_Monthly_Cutoff_Period();
	            }
	            else {
	            	$c  = new G_Cutoff_Period();
	            }

				$c->setId($cutoff_id);
				$next_cutoff_data = $c->getNextCutOff();
				$previous_cutoff_data = $c->getPreviousCutOff();

				$next_from = $next_cutoff_data['period_start'];
				$next_to   = $next_cutoff_data['period_end'];
				$next_id   = Utilities::encrypt($next_cutoff_data['id']);
				$this->var['next_cutoff_link'] = url("ob?from={$next_from}&to={$next_to}&hpid={$next_id}&selected_frequency={$frequency_id}");
				if( !empty($next_from) ){
					$this->var['next_cutoff_link'] = url("ob?from={$next_from}&to={$next_to}&hpid={$next_id}&selected_frequency={$frequency_id}");
				}else{
					$this->var['next_cutoff_link'] = url("ob?from={$from_date}&to={$to_date}&hpid={$eid}&selected_frequency={$frequency_id}");
				}

				$previous_from = $previous_cutoff_data['period_start'];
				$previous_to   = $previous_cutoff_data['period_end'];
				$previous_id   = Utilities::encrypt($previous_cutoff_data['id']);			
				if( !empty($previous_from) ){
					$this->var['previous_cutoff_link'] = url("ob?from={$previous_from}&to={$previous_to}&hpid={$previous_id}&selected_frequency={$frequency_id}");
				}else{
					$this->var['previous_cutoff_link'] = url("ob?from={$from_date}&to={$to_date}&hpid={$eid}&selected_frequency={$frequency_id}");
				}
				
			}

			$this->view->render('ob/index.php',$this->var);
		}else{
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

            redirect("ob/period?from={$from_date}&to={$to_date}&hpid={$hpid}&selected_frequency={$frequency_id}");
		}
	}

	function pending()
	{
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();		
		$enable_next_previous_link = false;

		$frequency_id = 1;

		if( isset($_GET['selected_frequency']) && !empty($_GET['selected_frequency']) ) {
			$frequency_id = $_GET['selected_frequency'];
		}

		$this->var['selected_frequency'] = $frequency_id;

		$this->var['pendings']   = 'class="selected"';				
		$this->var['module'] 	 = 'ob'; 	

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
				$period['from']  = $cutoff_data->getStartDate();
				$period['to'] 	 = $cutoff_data->getEndDate();
				$period['hpid']  = Utilities::encrypt($cutoff_data->getId());
				$eid             = Utilities::encrypt($cutoff_data->getId());
			}

			 //General Reports / Shr Audit Trail
	        list($p_year, $p_month, $p_day) = explode('-', $period_start);
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

			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_LOAD, ' Official Business Pending Requests of ',' Cut Off Period of '.$cut_of_period.' and '.$frequency_name.' Frequency', $period_start, $period_end, 1, '', '');	

		} else {
			$period['to']   = $_GET['to'];
			$period['from'] = $_GET['from'];
			$period['hpid'] = $_GET['hpid'];
			$eid  = $_GET['hpid'];
		}  

		$btn_request_ob_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'attendance',
    		'child_index'			=> 'official_business',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:show_add_ob_request_form("'.$period['from'].'","'.$period['to'].'");',
    		'id' 					=> 'add_ob_button',
    		'class' 				=> 'add_button',
    		'icon' 					=> '',
    		'additional_attribute'	=> '',
    		'caption' 				=> '<strong>+</strong><b>Request OB</b>'
    		); 

    	$btn_import_ob_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'attendance',
    		'child_index'			=> 'official_business',
    		'href' 					=> '#',
    		'onclick' 				=> 'javascript:importOBRequest("'.$period['from'].'","'.$period['to'].'");',
    		'id' 					=> 'import_undertime',
    		'class' 				=> 'add_button pull-right',
    		'icon' 					=> '<i class="icon-arrow-left"></i>',
    		'additional_attribute'	=> '',
    		'caption' 				=> 'Import OB'
    		); 
		
		$this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::HR,'attendance','official_business');
		$this->var['btn_request_ob'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_request_ob_config);
        $this->var['btn_import_ob'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_import_ob_config);		

		if($eid){	
			Jquery::loadMainTipsy();
			Jquery::loadMainJqueryDatatable();
			Jquery::loadMainTextBoxList();
			$this->var['eid'] 		  = $eid;
			$this->var['period']      = $period;			
			$this->var['page_title']  = 'Official Business Requests ';
			$this->view->setTemplate('template_leftsidebar.php');

            $cutoff_id = Utilities::decrypt($_GET['hpid']);
            $from_date = $_GET['from'];
       		$to_date   = $_GET['to'];

       		if($enable_next_previous_link) {
	            if ($frequency_id == 2) {
	            	$c  = new G_Weekly_Cutoff_Period();
													}
													else if ($frequency_id == 3) {
	            	$c  = new G_Monthly_Cutoff_Period();
													}
													else { 
	            	$c  = new G_Cutoff_Period();
													}

				$c->setId($cutoff_id);
				$next_cutoff_data = $c->getNextCutOff();
				$previous_cutoff_data = $c->getPreviousCutOff();

				$next_from = $next_cutoff_data['period_start'];
				$next_to   = $next_cutoff_data['period_end'];
				$next_id   = Utilities::encrypt($next_cutoff_data['id']);
				$this->var['next_cutoff_link'] = url("ob?from={$next_from}&to={$next_to}&hpid={$next_id}&selected_frequency={$frequency_id}");
				if( !empty($next_from) ){
					$this->var['next_cutoff_link'] = url("ob?from={$next_from}&to={$next_to}&hpid={$next_id}&selected_frequency={$frequency_id}");
				}else{
					$this->var['next_cutoff_link'] = url("ob?from={$from_date}&to={$to_date}&hpid={$eid}&selected_frequency={$frequency_id}");
				}

				$previous_from = $previous_cutoff_data['period_start'];
				$previous_to   = $previous_cutoff_data['period_end'];
				$previous_id   = Utilities::encrypt($previous_cutoff_data['id']);			
				if( !empty($previous_from) ){
					$this->var['previous_cutoff_link'] = url("ob?from={$previous_from}&to={$previous_to}&hpid={$previous_id}&selected_frequency={$frequency_id}");
				}else{
					$this->var['previous_cutoff_link'] = url("ob?from={$from_date}&to={$to_date}&hpid={$eid}&selected_frequency={$frequency_id}");
				}
				
			}

		}

		$this->view->render('ob/pending.php',$this->var);
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
		
		$this->var['approved']   = 'class="selected"';				
		$this->var['module'] 	 = 'ob'; 	

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
				$period['from']  = $cutoff_data->getStartDate();
				$period['to'] 	 = $cutoff_data->getEndDate();
				$period['hpid']  = Utilities::encrypt($cutoff_data->getId());
				$eid             = Utilities::encrypt($cutoff_data->getId());
			}

			 //General Reports / Shr Audit Trail
	        list($p_year, $p_month, $p_day) = explode('-', $period_start);
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

			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_LOAD, ' Official Business Approved Requests of ',' Cut Off Period of '.$cut_of_period.' and '.$frequency_name.' Frequency', $period_start, $period_end, 1, '', '');	

		} else {
			$period['from'] = $_GET['from'];
			$period['to']   = $_GET['to'];
			$period['hpid'] = $_GET['hpid'];
			$this->var['cutoff_selected'] = $_GET['from']."/".$_GET['to'];
			$eid  = $_GET['hpid'];
		}			
		
		if($eid){		
			$this->var['eid'] 		   = $eid;
			$this->var['period']       = $period;				
			$this->var['page_title']  = 'Official Business Requests ';
			$this->view->setTemplate('template_leftsidebar.php');

			if($enable_next_previous_link) {
	            $cutoff_id = Utilities::decrypt($_GET['hpid']);

										   if ($frequency_id == 2) {
		            $previous_cutoff = G_Weekly_Cutoff_Period_Finder::findPreviousByCutoffId($cutoff_id);
		            $next_cutoff = G_Weekly_Cutoff_Period_Finder::findNextByCutoffId($cutoff_id);
										   }
										   else if ($frequency_id == 3) {
		            $previous_cutoff = G_Monthly_Cutoff_Period_Finder::findPreviousByCutoffId($cutoff_id);
		            $next_cutoff = G_Monthly_Cutoff_Period_Finder::findNextByCutoffId($cutoff_id);
										   }
										   else {
		            $previous_cutoff = G_Cutoff_Period_Finder::findPreviousByCutoffId($cutoff_id);
		            $next_cutoff = G_Cutoff_Period_Finder::findNextByCutoffId($cutoff_id);
										   }

	            if ($previous_cutoff) {
	                $previous_from = $previous_cutoff->getStartDate();
	                $previous_to = $previous_cutoff->getEndDate();
	                $previous_id = Utilities::encrypt($previous_cutoff->getId());
	                $this->var['previous_cutoff_link'] = url("ob/approved?from={$previous_from}&to={$previous_to}&hpid={$previous_id}&selected_frequency={$frequency_id}");
	            }

	            if ($next_cutoff) {
	                $next_from = $next_cutoff->getStartDate();
	                $next_to = $next_cutoff->getEndDate();
	                $next_id = Utilities::encrypt($next_cutoff->getId());
	                $this->var['next_cutoff_link'] = url("ob/approved?from={$next_from}&to={$next_to}&hpid={$next_id}&selected_frequency={$frequency_id}");
	            }
        	}

			$this->view->render('ob/approved.php',$this->var);
		}else{
			redirect('ob');	
		}
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
		
		$this->var['disapproved'] = 'class="selected"';				
		$this->var['module'] 	  = 'ob'; 		

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
				$period['from']  = $cutoff_data->getStartDate();
				$period['to'] 	 = $cutoff_data->getEndDate();
				$period['hpid']  = Utilities::encrypt($cutoff_data->getId());
				$eid             = Utilities::encrypt($cutoff_data->getId());
			}

			 //General Reports / Shr Audit Trail
	        list($p_year, $p_month, $p_day) = explode('-', $period_start);
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

			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_LOAD, ' Official Business Disapproved Requests of ',' Cut Off Period of '.$cut_of_period.' and '.$frequency_name.' Frequency', $period_start, $period_end, 1, '', '');	

		} else {
			$period['from'] = $_GET['from'];
			$period['to']   = $_GET['to'];
			$period['hpid'] = $_GET['hpid'];
			$this->var['cutoff_selected'] = $_GET['from']."/".$_GET['to'];
			$eid  = $_GET['hpid'];
		}		
		
		if($eid){		
			$this->var['eid'] 		   = $eid;
			$this->var['period']       = $period;				
			$this->var['page_title']  = 'Official Business Requests ';
			$this->view->setTemplate('template_leftsidebar.php');

			if($enable_next_previous_link) { 
	            $cutoff_id = Utilities::decrypt($_GET['hpid']);

	            if ($frequency_id == 2) {
		            $previous_cutoff = G_Weekly_Cutoff_Period_Finder::findPreviousByCutoffId($cutoff_id);
		            $next_cutoff = G_Weekly_Cutoff_Period_Finder::findNextByCutoffId($cutoff_id);
													}
													else if ($frequency_id == 3) {
		            $previous_cutoff = G_Monthly_Cutoff_Period_Finder::findPreviousByCutoffId($cutoff_id);
		            $next_cutoff = G_Monthly_Cutoff_Period_Finder::findNextByCutoffId($cutoff_id);
													}
													else {
		            $previous_cutoff = G_Cutoff_Period_Finder::findPreviousByCutoffId($cutoff_id);
		            $next_cutoff = G_Cutoff_Period_Finder::findNextByCutoffId($cutoff_id);
													}


	            if ($previous_cutoff) {
	                $previous_from = $previous_cutoff->getStartDate();
	                $previous_to = $previous_cutoff->getEndDate();
	                $previous_id = Utilities::encrypt($previous_cutoff->getId());
	                $this->var['previous_cutoff_link'] = url("ob/disapproved?from={$previous_from}&to={$previous_to}&hpid={$previous_id}&selected_frequency={$frequency_id}");
	            }

	            if ($next_cutoff) {
	                $next_from = $next_cutoff->getStartDate();
	                $next_to = $next_cutoff->getEndDate();
	                $next_id = Utilities::encrypt($next_cutoff->getId());
	                $this->var['next_cutoff_link'] = url("ob/disapproved?from={$next_from}&to={$next_to}&hpid={$next_id}&selected_frequency={$frequency_id}");
	            }
        	}

			$this->view->render('ob/disapproved.php',$this->var);
		}else{
			redirect('ob');	
		}
	}
	
	function archives()
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
		
		$this->var['archives']   = 'class="selected"';				
		$this->var['module'] 	 = 'ob';

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
				$period['from']  = $cutoff_data->getStartDate();
				$period['to'] 	 = $cutoff_data->getEndDate();
				$period['hpid']  = Utilities::encrypt($cutoff_data->getId());
				$eid             = Utilities::encrypt($cutoff_data->getId());
			}

			 //General Reports / Shr Audit Trail
	        list($p_year, $p_month, $p_day) = explode('-', $period_start);
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

			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_LOAD, ' Official Business Archived Requests of ',' Cut Off Period of '.$cut_of_period.' and '.$frequency_name.' Frequency', $period_start, $period_end, 1, '', '');	
		} else {
			$period['to']   = $_GET['to'];
			$period['from'] = $_GET['from'];
			$period['hpid'] = $_GET['hpid'];
			$eid  = $_GET['hpid'];
			$this->var['cutoff_selected'] = $_GET['from']."/".$_GET['to'];
		}		 		
				
		if($eid){		
			$this->var['eid'] 		   = $eid;
			$this->var['period']       = $period;				
			$this->var['page_title']  = 'Official Business Requests ';
			$this->view->setTemplate('template_leftsidebar.php');
			$this->view->render('ob/archives.php',$this->var);
		}else{
			redirect('ob');	
		}
	}
	
	function html_import_ob() {
		$this->view->setTemplate('template_blank.php');
		$this->view->render('ob/html/html_import_ob.php', $this->var);	
	}	
	
	function import_ob_request()
	{
		//ob_start();
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		
		$file 	 = $_FILES['ob_file']['tmp_name'];
		$ob = new G_OB_Import($file);
		$ob->setCompanyStructureId(Utilities::decrypt($this->company_structure_id));
		
		$is_imported = $ob->import();		
		
		if ($is_imported) {
			$return['is_imported'] = true;
			$return['message']     = 'OB request has been successfully imported.';	

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
			$return['is_imported'] = false;
			$return['message']     = 'There was a problem importing ob request. Please contact the administrator.';
		}
		//ob_clean();
		//ob_end_flush();
		echo json_encode($return);		
	}	
	
	function ajax_add_new_request() 
	{
		sleep(1);
		$this->var['from']		 = $_POST['from'];
		$this->var['to']		 = $_POST['to'];	 
		$this->var['token']		 = Utilities::createFormToken();		
		$this->var['page_title'] = 'Add New Request';		
		$this->view->render('ob/form/add_request.php',$this->var);		
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
				$this->view->render('ob/form/_show_request_approvers.php',$this->var);
			}		
		}
	}
	
	function ajax_edit_ob_request() 
	{
		$gobr = G_Employee_Official_Business_Request_Finder::findById(Utilities::decrypt($_POST['eid']));
		if($gobr){
			$e = G_Employee_Finder::findById($gobr->getEmployeeId());
			if( $e ){
				$this->var['employee_name'] = $e->getLastname() . ', ' . $e->getFirstname();
				$this->var['eid']       = Utilities::encrypt($e->getId());
				$this->var['gobr']	    = $gobr;
				$this->var['from']		= $_POST['date_from'];
				$this->var['to']		= $_POST['date_to'];
				$this->var['token']		= Utilities::createFormToken();
				$this->var['page_title']= 'Edit Earning';		
				$this->view->render('ob/form/ajax_edit_request.php',$this->var);
			}else{
				echo "<div class=\"alert alert-error\">Employee Record not found</div><br />";
			}
		}
	}
	
	function ajax_import_ob_request() 
	{	
		$this->var['action']	 = url('ob/import_earnings');			
		$this->view->render('ob/form/ajax_import_ob_request.php',$this->var);
	}
	
	function ajax_get_employees_autocomplete() 
	{
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
	
	function _with_selected_action() 
	{
		if(!empty($_POST)) {
			$mArray = $_POST['dtChk'];
			foreach($mArray as $key => $value) {
			$d++;
			$gobr = G_Employee_Official_Business_Request_Finder::findById($value);		
                if($gobr){
                    if($_POST['chkAction'] == 'ob_approve'){
                        $gobr->approve();

                        /*$date_start = $gobr->getDateStart();
                        $date_end = $gobr->getDateEnd();
                        $employee_id = $gobr->getEmployeeId();
                        $e = G_Employee_Finder::findById($employee_id);
                        if ($e) {
                            G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $date_start, $date_end);
                        }*/

                        /*
                        $start_date = $gobr->getDateStart();
                        $end_date	= $gobr->getDateEnd();

                        $employee 	= G_Employee_Finder::findById($gobr->getEmployeeId());
                        $dates 		= Tools::getBetweenDates($start_date, $end_date);
                        foreach ($dates as $date) {
                            $a = G_Attendance_Finder::findByEmployeeAndDate($employee, $date);
                            if (!$a) {
                                $a = new G_Attendance;
                            }
                            $a->setDate($date);
                            $a->setAsPaid();
                            $a->setAsPresent();
                            $a->recordToEmployee($employee);
                        }*/

                        $json['message']    = 'Successfully <b>approved</b> ' . $d . ' record(s)';

                    }elseif($_POST['chkAction'] == 'ob_archive'){
                        $gobr->archive();
                        $json['message']    = 'Successfully <b>archived</b> ' . $d . ' record(s)';

                    }elseif($_POST['chkAction'] == 'ob_disapprove'){
                        $gobr->disapprove();

                        /*$date_start = $gobr->getDateStart();
                        $date_end = $gobr->getDateEnd();
                        $employee_id = $gobr->getEmployeeId();
                        $e = G_Employee_Finder::findById($employee_id);
                        if ($e) {
                            G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $date_start, $date_end);
                        }*/

                        $json['message']    = 'Successfully <b>disapproved</b> ' . $d . ' record(s)';

                    }elseif($_POST['chkAction'] == 'ob_restore'){
                        $gobr->restore_archived();
                        $json['message']    = 'Successfully <b>restored</b> ' . $d . ' archived record(s)';

                    }else {

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
				      
            }
		}
		
		$json['is_success'] = 1;
		$json['eid']        = $_POST['eid'];
			
		echo json_encode($json);
	}

	function _save_ob_request()
	{   

		Utilities::verifyFormToken($_POST['token']);	

		date_default_timezone_set('Asia/Manila');
		$current_date = date("Y-m-d H:i:s");

		if($_POST['employee_id']){			
			if($_POST['ob_request_id']){
				$gobr = G_Employee_Official_Business_Request_Finder::findById(Utilities::decrypt($_POST['ob_request_id']));
			}else{
				$gobr = new G_Employee_Official_Business_Request();				
			}
			
			if(!isset($_POST['has_time_logs'])){
				$is_whole_day = G_Employee_Official_Business_Request::YES;
			}
			else{
				$is_whole_day =  G_Employee_Official_Business_Request::NO;
			}

			//saving ob request
            G_Employee_Official_Business_Request_Helper::addNewRequest(Utilities::decrypt($_POST['employee_id']), $current_date, $_POST['ob_date_from'], $_POST['ob_date_to'], $_POST['comments'], $_POST['ob_time_start'],$_POST['ob_time_end'],$is_whole_day);
			
			$employee 	= G_Employee_Finder::findById(Utilities::decrypt($_POST['employee_id']));
            $obr = $employee->getOfficialBusinessRequest2($_POST['ob_date_from']);

            //utilities::displayArray($obr);exit();

            if( $obr ){
            	$request_id   = $obr->getId();
            	$approvers    = $_POST['approvers'];
				$requestor_id = Utilities::decrypt($_POST['employee_id']);
				$request_type = G_Request::PREFIX_OFFICIAL_BUSSINESS;

				$r = new G_Request();
		        $r->setRequestorEmployeeId($requestor_id);
		        $r->setRequestId($request_id);
		        $r->setRequestType($request_type);
		        $r->saveEmployeeRequest($approvers); //Save request approvers
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
			$json['message']    = 'Record was successfully saved.';			
						
		}else {
			$json['is_success'] = 0;
			$json['message']    = 'Error in sql';
		}
		$json['from'] = $_POST['date_from'];		
		$json['to']	  = $_POST['date_to'];
		echo json_encode($json);
	}

	function _update_ob_request()
	{
		Utilities::verifyFormToken($_POST['token']);		
		if($_POST['employee_id']){			
			if($_POST['ob_request_id']){
				$gobr = G_Employee_Official_Business_Request_Finder::findById(Utilities::decrypt($_POST['ob_request_id']));
				if( $gobr ){	
					$start_date = date("Y-m-d",strtotime($_POST['ob_date_from']));	
					$end_date   = date("Y-m-d",strtotime($_POST['ob_date_to']));			
            		$comment    = $_POST['comments'];

            		if(!isset($_POST['has_time_logs'])){
						$is_whole_day = G_Employee_Official_Business_Request::YES;
					}
					else{
						$is_whole_day =  G_Employee_Official_Business_Request::NO;
						$time_start = date("H:i:s", strtotime($_POST['ob_time_start']));
						$time_end= date("H:i:s", strtotime($_POST['ob_time_end']));
					}


            		$gobr->setDateStart($start_date);
            		$gobr->setDateEnd($end_date);
            		//new column
            		$gobr->setWholeDay($is_whole_day);
            		$gobr->setTimeStart($time_start);
            		$gobr->settimeEnd($time_end);
            		$gobr->setComments($comment);            		
            		$gobr->save();

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
					$json['message']    = 'Record was successfully saved.';		
				}else{
					$json['is_success'] = 0;
					$json['message']    = 'Record not found';
				}
			}
						
		}else {
			$json['is_success'] = 0;
			$json['message']    = 'Error in sql';
		}
		$json['from'] = $_POST['date_from'];		
		$json['to']	  = $_POST['date_to'];
		echo json_encode($json);
	}
	
	function _save_ob_request_depre()
	{
		Utilities::verifyFormToken($_POST['token']);		
		if($_POST['employee_id']){			
			if($_POST['ob_request_id']){
				$gobr = G_Employee_Official_Business_Request_Finder::findById(Utilities::decrypt($_POST['ob_request_id']));
			}else{
				$gobr = new G_Employee_Official_Business_Request();				
			}

            G_Employee_Official_Business_Request_Helper::addNewRequest(Utilities::decrypt($_POST['employee_id']), $this->c_date, $_POST['ob_date_from'], $_POST['ob_date_to'], $_POST['comments']);
			
			$json['is_success'] = 1;			
			$json['message']    = 'Record was successfully saved.';
			
			
			if($_POST['is_approved'] == G_Employee_Official_Business_Request::YES) {
				$employee 	= G_Employee_Finder::findById(Utilities::decrypt($_POST['employee_id']));
                $obr = $employee->getOfficialBusinessRequest($_POST['ob_date_from']);
                if ($obr) {
                    $obr->approve();
                }

				/*$dates 		= Tools::getBetweenDates($start_date, $end_date);
				foreach ($dates as $date) {
					$a = G_Attendance_Finder::findByEmployeeAndDate($employee, $date);
					if (!$a) {
						$a = new G_Attendance;
					}
					$a->setDate($date);
					$a->setAsPaid();
					$a->setAsPresent();
					$a->recordToEmployee($employee);
				}*/
			}
		}else {
			$json['is_success'] = 0;
			$json['message']    = 'Error in sql';
		}
		$json['from'] = $_POST['date_from'];		
		$json['to']	  = $_POST['date_to'];
		echo json_encode($json);
	}

	function ajax_view_ob_request_approvers() 
	{
		$date  		 = date("Y-m-d"); 		
		$cp          = new G_Cutoff_Period();
		$cutoff_data = $cp->getCurrentCutoffPeriod($date);				

		$request_id  = Utilities::decrypt($_GET['eid']);
		$approvers   = new G_Request();
		$approvers->setRequestId($request_id);
		$data = $approvers->getObRequestApproversStatus();

		if( $data['total_approvers'] > 0 ){	
			$this->var['eid']        = $_GET['eid'];		
			$this->var['is_lock']    = $cutoff_data['is_lock'];			
			$this->var['total_approvers'] = $data['total_approvers'];
			$this->var['approvers']  = $data['approvers'];	
			$this->var['token']		 = Utilities::createFormToken();			
			$this->var['page_title'] = 'Leave Request Approvers';		
			$this->view->render('ob/form/view_ob_request_approvers.php',$this->var);
		}else{
			echo "<div class=\"alert alert-error\">No approvers set for selected request</div><br />";
		}
	}

	function _update_ob_request_approvers(){    	
    	Utilities::verifyFormToken($_POST['token']);    	

    	$data = $_POST['approvers'];    	    	
    	$id   = Utilities::decrypt($_POST['eid']);
    	$json['is_success'] = false;
		$json['message']    = "Cannot save record";

    	if( !empty($data) ){
    		$date 		  = $this->c_date;
    		$request_type = G_Request::PREFIX_OFFICIAL_BUSSINESS;
    		$r = new G_Request();
    		$r->setRequestId($id);
    		$r->setActionDate($date);
    		$r->setRequestType($request_type);
			$json = $r->updateRequestApproversDataById($data);
			$r->updateRequestStatus();
    	}

    	echo json_encode($json);
    }
	
	function _approve_ob_request()
	{
		
		if($_POST['eid']){
			$gobr = G_Employee_Official_Business_Request_Finder::findById(Utilities::decrypt($_POST['eid']));
			if($gobr){
				$gobr->approve();

                $date_start = $gobr->getDateStart();
                $date_end = $gobr->getDateEnd();
                $employee_id = $gobr->getEmployeeId();
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
        		$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_APPROVED, ' Official Business Request of ', $emp_name, $date_start, $date_end, 1, $shr_emp['position'], $shr_emp['department']);
			
				$json['is_success'] = 1;			
				$json['message']    = 'Record was successfully updated.';
			}else{
				$json['is_success'] = 0;
				$json['message']    = 'Record not found...';

				$shr_emp = G_Employee_Helper::findByEmployeeId($employee_id);
				$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
        		$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_APPROVED, ' Official Business Request of ', $emp_name, $date_start, $date_end, 0, $shr_emp['position'], $shr_emp['department']);
			}
		}else{
			$json['is_success'] = 0;
			$json['message']    = 'Error in sql';

			$shr_emp = G_Employee_Helper::findByEmployeeId($employee_id);
			$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
        	$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_APPROVED, ' Official Business Request of ', $emp_name, $date_start, $date_end, 0, $shr_emp['position'], $shr_emp['department']);
		}
		
		$json['eid'] = $_POST['eid'];
		echo json_encode($json);
	}
	
	function _disapprove_ob_request()
	{
		if($_POST['eid']){
			$gobr = G_Employee_Official_Business_Request_Finder::findById(Utilities::decrypt($_POST['eid']));
			if($gobr){
				$gobr->hr_disapprove();

                $date_start = $gobr->getDateStart();
                $date_end = $gobr->getDateEnd();
                $employee_id = $gobr->getEmployeeId();
                $e = G_Employee_Finder::findById($employee_id);
                if ($e) {
                    G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $date_start, $date_end);
                }

				$json['is_success'] = 1;			
				$json['message']    = 'Record was successfully updated.';
			}else{
				$json['is_success'] = 0;
				$json['message']    = 'Record not found...';
			}
		}else{
			$json['is_success'] = 0;
			$json['message']    = 'Error in sql';
		}
		
		$json['eid'] = $_POST['eid'];
		echo json_encode($json);
	}
	
	function _archive_ob_request()
	{
		if($_POST['eid']){
			$gobr = G_Employee_Official_Business_Request_Finder::findById(Utilities::decrypt($_POST['eid']));
			if($gobr){
				$gobr->archive();
				$json['is_success'] = 1;			
				$json['message']    = 'Record was successfully sent to archive.';
			}else{
				$json['is_success'] = 0;
				$json['message']    = 'Record not found...';
			}
		}else{
			$json['is_success'] = 0;
			$json['message']    = 'Error in sql';
		}
		
		$json['eid'] = $_POST['eid'];
		echo json_encode($json);
	}
	
	function _restore_ob_request()
	{
		if($_POST['eid']){
			$gobr = G_Employee_Official_Business_Request_Finder::findById(Utilities::decrypt($_POST['eid']));
			if($gobr){
				$gobr->restore_archived();
				$json['is_success'] = 1;			
				$json['message']    = 'Archive record was successfully restored.';
			}else{
				$json['is_success'] = 0;
				$json['message']    = 'Record not found...';
			}
		}else{
			$json['is_success'] = 0;
			$json['message']    = 'Error in sql';
		}
		
		$json['eid'] = $_POST['eid'];
		echo json_encode($json);
	}	
	
	function _load_ob_list_dt() 
	{
		$this->var['permission_action'] = $this->validatePermission(G_Sprint_Modules::HR,'attendance','official_business');
		$this->var['from'] = $_POST['from'];
		$this->var['to']   = $_POST['to'];
		$this->var['frequency_id']   = $_POST['frequency_id'];
		$this->view->render('ob/_ob_pending_list_dt.php',$this->var);
	}
	
	function _load_approved_ob_list_dt() 
	{
		$this->var['permission_action'] = $this->validatePermission(G_Sprint_Modules::HR,'attendance','official_business');
		$this->var['from'] = $_POST['from'];
		$this->var['to']   = $_POST['to'];
		$this->var['frequency_id']   = $_POST['frequency_id'];
		$this->view->render('ob/_ob_approved_list_dt.php',$this->var);
	}

	function _load_disapproved_ob_list_dt() 
	{
		$this->var['permission_action'] = $this->validatePermission(G_Sprint_Modules::HR,'attendance','official_business');
		$this->var['from'] = $_POST['from'];
		$this->var['to']   = $_POST['to'];
		$this->var['frequency_id']   = $_POST['frequency_id'];
		$this->view->render('ob/_ob_disapproved_list_dt.php',$this->var);
	}
	
	function _load_archived_ob_list_dt() 
	{
		$this->var['permission_action'] = $this->validatePermission(G_Sprint_Modules::HR,'attendance','official_business');
		$this->var['from'] = $_POST['from'];
		$this->var['to']   = $_POST['to'];
		$this->var['frequency_id']   = $_POST['frequency_id'];
		$this->view->render('ob/_ob_archived_list_dt.php',$this->var);
	}
	
	function _load_server_ob_pending_list_dt() 
	{
		$frequency_id = $_GET['frequency_id'];
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR,'attendance','official_business');

		Utilities::ajaxRequest();		
		//$sqlcond 	 = "  AND jbh.end_date = ''";
		$sqlcond 	.= ' AND date_start BETWEEN ' . Model::safeSql($_GET['from']) . ' AND ' . Model::safeSql($_GET['to']);
		
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST);
		$dt->setCustomField(array('emp_name' => 'firstname,lastname'));
		$dt->setJoinTable("LEFT JOIN " . EMPLOYEE . " e");			
		$dt->setJoinFields(G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST . ".employee_id = e.id LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " jbh ON e.id = jbh.employee_id AND jbh.end_date = ''");
		$dt->setCondition(' ' . G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST . '.company_structure_id = ' . Utilities::decrypt($this->global_user_ecompany_structure_id) . ' AND is_archive = "' . G_Employee_Official_Business_Request::NO . '" AND is_approved="' . G_Employee_Official_Business_Request::STATUS_PENDING . '"' . $sqlcond);
		$dt->setColumns('date_start,date_end,time_start,time_end');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);
		if($permission_action == Sprint_Modules::PERMISSION_02) {
			if($_SESSION['sprint_hr']['is_period_lock'] == ((!isset($frequency_id) || $frequency_id != 2 ? G_Cutoff_Period::NO : G_Weekly_Cutoff_Period::NO))){
				$dt->setNumCustomColumn(1);
				$dt->setCustomColumn(	
				array(
				'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></li><li><a title=\"Edit\" id=\"edit\" class=\"ui-icon ui-icon-pencil g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:editOBRequest(\'e_id\');\"></a></li><li><a title=\"Approvers\" id=\"edit\" class=\"ui-icon ui-icon-person g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:viewObRequestApprovers(\'e_id\');\"></a></li><li><a title=\"Approve\" id=\"edit\" class=\"ui-icon ui-icon-check g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:approveOBRequest(\'e_id\',1);\"></a></li><li><a title=\"Disapproved\" id=\"edit\" class=\"ui-icon ui-icon-close g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:disapproveOBRequest(\'e_id\',1);\"></a></li><li><a title=\"Send to Archive\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:archivePendingOBRequest(\'e_id\')\"></a></li></ul></div>'));
			}else {
				$dt->setNumCustomColumn(0);	
			}
		}else{
			$dt->setNumCustomColumn(0);
		}

		echo $dt->constructDataTable();
	}
	
	function _load_server_ob_approved_list_dt() 
	{
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR,'attendance','official_business');

		Utilities::ajaxRequest();		
		$frequency_id = $_GET['frequency_id'];
		$sqlcond 	 = "  AND jbh.end_date = ''";
		$sqlcond 	.= ' AND date_start BETWEEN ' . Model::safeSql($_GET['from']) . ' AND ' . Model::safeSql($_GET['to']);
		
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST);
		$dt->setCustomField(array('emp_name' => 'firstname,lastname','job_name'=>'jbh.name'));
		$dt->setJoinTable("LEFT JOIN " . EMPLOYEE . " e");			
		$dt->setJoinFields(G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST . ".employee_id = e.id LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " jbh ON " . G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST . ".employee_id = jbh.employee_id");
		$dt->setCondition(' ' . G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST . '.company_structure_id = ' . Utilities::decrypt($this->global_user_ecompany_structure_id) . ' AND is_archive = "' . G_Employee_Official_Business_Request::NO . '" AND is_approved="' . G_Employee_Official_Business_Request::STATUS_APPROVED . '"' . $sqlcond);
		$dt->setColumns('date_start,date_end,time_start,time_end');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);
		if($permission_action == Sprint_Modules::PERMISSION_02) {
			if($_SESSION['sprint_hr']['is_period_lock'] == ((!isset($frequency_id) || $frequency_id != 2 ? G_Cutoff_Period::NO : G_Weekly_Cutoff_Period::NO))){
				$dt->setNumCustomColumn(1);
				$dt->setCustomColumn(	
				array(
				//'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></li><li><a title=\"Disapprove\" id=\"edit\" class=\"ui-icon ui-icon-close g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:disapproveOBRequest(\'e_id\');\"></a></li><li><a title=\"Send to Archive\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:archiveApprovedOBRequest(\'e_id\')\"></a></li></ul></div>'));
	                '1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></li><li><a title=\"Disapprove\" id=\"edit\" class=\"ui-icon ui-icon-close g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:disapproveOBRequest(\'e_id\',2);\"></a></li></ul></div>'));
			}else {
				$dt->setNumCustomColumn(0);	
			}
		}else{
			$dt->setNumCustomColumn(0);	
		}
		echo $dt->constructDataTable();
	}

	function _load_server_ob_disapproved_list_dt() 
	{
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR,'attendance','official_business');

		Utilities::ajaxRequest();		
		$frequency_id = $_GET['frequency_id'];
		$sqlcond 	 = "  AND jbh.end_date = ''";
		$sqlcond 	.= ' AND date_start BETWEEN ' . Model::safeSql($_GET['from']) . ' AND ' . Model::safeSql($_GET['to']);
		
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST);
		$dt->setCustomField(array('emp_name' => 'firstname,lastname','job_name'=>'jbh.name'));
		$dt->setJoinTable("LEFT JOIN " . EMPLOYEE . " e");			
		$dt->setJoinFields(G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST . ".employee_id = e.id LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " jbh ON e.id = jbh.employee_id");
		$dt->setCondition(' ' . G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST . '.company_structure_id = ' . Utilities::decrypt($this->global_user_ecompany_structure_id) . ' AND is_archive = "' . G_Employee_Official_Business_Request::NO . '" AND is_approved="' . G_Employee_Official_Business_Request::STATUS_DISAPPROVED . '"' . $sqlcond);
		$dt->setColumns('date_start,date_end,time_start,time_end');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);
		if($permission_action == Sprint_Modules::PERMISSION_02) {
			if($_SESSION['sprint_hr']['is_period_lock'] == ((!isset($frequency_id) || $frequency_id != 2 ? G_Cutoff_Period::NO : G_Weekly_Cutoff_Period::NO))){
				$dt->setNumCustomColumn(1);
				$dt->setCustomColumn(	
				array(
				//'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></li><li><a title=\"Disapprove\" id=\"edit\" class=\"ui-icon ui-icon-close g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:disapproveOBRequest(\'e_id\');\"></a></li><li><a title=\"Send to Archive\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:archiveApprovedOBRequest(\'e_id\')\"></a></li></ul></div>'));
	                '1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></li><li><a title=\"Approve\" id=\"edit\" class=\"ui-icon ui-icon-check g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:approveOBRequest(\'e_id\',2);\"></a></li></ul></div>'));
			}else {
				$dt->setNumCustomColumn(0);	
			}
		}else{
			$dt->setNumCustomColumn(0);	
		}
		echo $dt->constructDataTable();
	}
	
	function _load_server_ob_archived_list_dt() 
	{
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR,'attendance','official_business');

		Utilities::ajaxRequest();		
		$frequency_id = $_GET['frequency_id'];
		$sqlcond 	 = "  AND jbh.end_date = ''";
		$sqlcond 	.= ' AND date_start BETWEEN ' . Model::safeSql($_GET['from']) . ' AND ' . Model::safeSql($_GET['to']);
		
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST);
		$dt->setCustomField(array('emp_name' => 'firstname,lastname','job_name'=>'jbh.name'));
		$dt->setJoinTable("LEFT JOIN " . EMPLOYEE . " e");			
		$dt->setJoinFields(G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST . ".employee_id = e.id LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " jbh ON e.id = jbh.employee_id");
		$dt->setCondition(' ' . G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST . '.company_structure_id = ' . Utilities::decrypt($this->global_user_ecompany_structure_id) . ' AND is_archive = "' . G_Employee_Official_Business_Request::YES . '"' . $sqlcond);
		$dt->setColumns('date_start,date_end,time_start,time_end');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);
		if($permission_action == Sprint_Modules::PERMISSION_02) {
			if($_SESSION['sprint_hr']['is_period_lock'] == ((!isset($frequency_id) || $frequency_id != 2 ? G_Cutoff_Period::NO : G_Weekly_Cutoff_Period::NO))){
				$dt->setNumCustomColumn(1);
				$dt->setCustomColumn(	
				array(
				'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></li><li><a title=\"Restore Archived\" id=\"edit\" class=\"ui-icon ui-icon-refresh g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:restoreArchivedOBRequest(\'e_id\');\"></a></li></ul></div>'));
			}else {
				$dt->setNumCustomColumn(0);	
			}
		}else{
			$dt->setNumCustomColumn(0);	
		}
		echo $dt->constructDataTable();
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
		$this->view->render('ob/_payroll_period.php',$this->var);
	}	
}
?>