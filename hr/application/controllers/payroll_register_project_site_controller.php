<?php
class Payroll_Register_Project_Site_Controller extends Controller
{
	function __construct()
	{		
        $this->module = 'payroll';

		parent::__construct();
        Loader::appMainScript('payslip_base.js');
        Loader::appMainScript('payslip.js');
        Loader::appMainUtilities();
		Loader::appStyle('style.css');

		Loader::appMainScript('notifications.js');

		$this->sprintHdrMenu(G_Sprint_Modules::PAYROLL, 'payroll');
		$this->generateCurrentCutOffPeriod();
		//$this->redirectNoAccessModule(G_Sprint_Modules::PAYROLL, 'payroll');

		$this->var['is_enable_popup_notification'] = true;
		$this->var['count_payroll_new_notifications'] = 1;
		$this->var['payroll_register'] = 'selected';

		$this->validatePermission(G_Sprint_Modules::PAYROLL,'payroll','');
	}

	function index() {
		Jquery::loadMainTipsy();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();

		$this->var['page_title'] = 'Payroll Register';
		$this->var['periods'] = G_Payslip_Helper::getPeriods();
		$this->view->setTemplate('payroll/template.php');
		$this->view->render('payroll_register/index.php',$this->var);		
	}

	function generation() {
		Jquery::loadMainTextBoxList();		
		Jquery::loadMainBootStrapDropDown();

		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();		

        Loader::appMainScript('settings_base.js');
        Loader::appMainScript('attendance_base.js');
        Loader::appMainScript('attendance.js');

        $month = (int) $_GET['month'];
        $year  = (int) $_GET['year'];
        $frequency = (int) $_GET['frequency']; 

        if ($frequency == 0) {
        	$frequency = 1;
        }

        if( $month <= 0 ) {
           $month = Tools::getGmtDate('n');
        }

        if ($year <= 0) {
            $year = Tools::getGmtDate('Y');
        } 

        $sv = new G_Cutoff_Period();

        //weekly
        $weekly_periods = new G_Weekly_Cutoff_Period();

        //check if cutoff period exist and if not add new - start
        $expected_cutoff = $sv->expectedCutOffPeriodsByMonthAndYear($month, $year);    
       
        //weekly
        $expected_weekly_cutoff = $weekly_periods->expectedWeeklyCutOffPeriodsByMonthAndYear($month,$year);


         //monthly -alex
		 $monthly_periods = new G_Monthly_Cutoff_Period();
		 $expected_monthly_cutoff = $monthly_periods->expectedMonthlyCutOffPeriodsByMonthAndYear($month, $year);  


        // echo "<pre>";
        // var_dump($expected_weekly_cutoff);
        // echo "</pre>";
		$fields = array("payout_day");
		$default_pay_out = G_Settings_Pay_Period_Helper::sqlDefaultPayPeriod($fields);            
		$payout_days = explode(",", $default_pay_out['payout_day']);

        foreach($expected_cutoff as $exp_key => $exp_c) {
        	$start_cutoff_date = $exp_c['start_date'];
        	$end_cutoff_date   = $exp_c['end_date'];

        	$cutoff_number = 1;
        	$payout_date   = $year . "-" . $month . "-" . $payout_days[0];
        	if($exp_key == 1) {
        		$cutoff_number = 2;
        		$payout_date   = $year . "-" . $month . "-" . $payout_days[1];
        	}

        	$cutoff_exist = G_Cutoff_Period_Helper::isCutoffPeriodStartAndEndExists($start_cutoff_date, $end_cutoff_date);
        	if(!$cutoff_exist) {
        		//echo $start_cutoff_date . ' : ' . $end_cutoff_date;
				$sv->setYearTag($year);
				$sv->setStartDate($start_cutoff_date);
				$sv->setEndDate($end_cutoff_date);
				$sv->setPayoutDate($payout_date);
		        $sv->setCutoffNumber($cutoff_number);
				$sv->setSalaryCycleId(2);
		        $sv->setIsPayrollGenerated(G_Cutoff_Period::NO);
				$sv->setIsLock(G_Cutoff_Period::NO);     
				$sv->save();   		
        	}
        }
        //check if cutoff period exist and if not add new - end

        /*
        $sv->generateCutoffPeriodByMonthAndYear( $month, $year );
        $periods = $sv->getValidCutOffPeriodsByMonthAndYear($month, $year);        
        $periods = G_Cutoff_Period_Finder::findByMonthYear($month, $year);        
        if( empty($periods) ){
        	$cutoff  = new G_Cutoff_Period();
			$data    = $cutoff->generateCutoffPeriodByMonthAndYear( $month, $year );
			$periods = $sv->getValidCutOffPeriodsByMonthAndYear($month, $year);
        }
        */

        if($year == date("Y")) {
        
        	$periods = $sv->getMonthCutoffPeriods($month, $year); 
        	$weekly_cutoff_periods  = $weekly_periods->getMonthWeeklyCutoffPeriods($month,$year);  
        	$monthly_cutoff_periods  = $monthly_periods->getMonthMonthlyCutoffPeriods($month,$year);  

        } else {
        
        	// $periods = G_Cutoff_Period_Finder::findByMonthlyCutoffsAndYear($month, $year);
			// $weekly_cutoff_periods = G_Weekly_Cutoff_Period_Finder::findByMonthlyCutoffsAndYear($month, $year);
			
			$periods = $sv->getMonthCutoffPeriods($month, $year); 
        	$weekly_cutoff_periods  = $weekly_periods->getMonthWeeklyCutoffPeriods($month,$year);  
        	$monthly_cutoff_periods  = $monthly_periods->getMonthMonthlyCutoffPeriods($month,$year);

        	if(count($periods) <= 1) {

        		$periods = $sv->getMonthCutoffPeriods($month, $year);        
        	}
        	if(count($weekly_cutoff_periods) <= 1) {

        		$weekly_cutoff_periods = $weekly_periods->getMonthWeeklyCutoffPeriods($month, $year);        
        	}

        	if(count($monthly_cutoff_periods) <= 1) {

        		$monthly_cutoff_periods  = $monthly_periods->getMonthMonthlyCutoffPeriods($month,$year);

        	}


        }			
        
        if($frequency == 1){        	
        	$frequency_type = $periods;
        }elseif ($frequency == 2) {
        	$frequency_type = $weekly_cutoff_periods;
        }
        elseif ($frequency == 3) {
        	$frequency_type = $monthly_cutoff_periods;
        }
        else{
        		$frequency_type = $periods;
        }

        $frequencies		= G_Settings_Pay_Period_Finder::findAll($order_by,$limit);


       
        $btns_dl_payroll       = array();
		$btns_dl_payslip       = array();
		$btns_view_payslip     = array();
		$btns_generate_payroll = array();
		$btns_lock_unlock      = array();
							//testing old $periods
        foreach( $frequency_type as $p ){
        	$period_encrypted_id  = Utilities::encrypt($p->getId());
		    $start_date 		  = $p->getStartDate();
		    $end_date 			  = $p->getEndDate();
		    $cutoff_character     = $p->getCutoffCharacter();
		    $cutoff_number	      = $p->getCutoffNumber();
		    $is_locked 		      = $p->isLocked();
		    $is_payroll_generated = $p->isPayrollGenerated();

		    if( $is_locked ){		    	
		    	$action  = "javascript:unlockPeriod('{$period_encrypted_id}',{$frequency})"; 
		    	$class   = "red_button";
		    	$caption = "Unlock";
		    }else{
		    	$action  = "javascript:lockPeriod('{$period_encrypted_id}',{$frequency})"; 
		    	$class   = "blue_button";
		    	$caption = "Lock";		    	
		    }

		    $btn_lock_unlock_config = array(
				'module'				=> 'payroll',
	    		'parent_index'			=> 'payroll',
	    		'child_index'			=> '',
	    		'required_permission'	=> Sprint_Modules::PERMISSION_02,        		
	    		'event' 				=> 'onmouseup',
	    		'action' 				=> $action,
	    		'id' 					=> '',
	    		'class' 				=> $class,
	    		'icon' 					=> '',
	    		'additional_attribute' 	=> '',
	    		'wrapper_start'			=> '',
	    		'wrapper_end'			=> '',
	    		'caption' 				=> $caption
			); 

			$btns_lock_unlock[$p->getId()] = G_Button_Builder::createButtonWithPermissionValidation($this->global_user_payroll_actions, $btn_lock_unlock_config);

			$employee_access = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_access');
			$has_option = false;
			if($employee_access == Sprint_Modules::PERMISSION_05) {
				$q = "both";
				$has_option = true;
			}elseif($employee_access == Sprint_Modules::PERMISSION_06) {
				$q = "confidential";
				$has_option = false;
			}elseif($employee_access == Sprint_Modules::PERMISSION_07) {
				$q = "non-confidential";
				$has_option = false;
			}else{
				$q = "both";
				$has_option = true;
			}

			if( $is_payroll_generated ){							
				//$action  = "location.href='" . url("payslip/download_payslip?from={$start_date}&to={$end_date}'");
				$action  = url("payroll_reports/payroll_management#payslip");
				$caption = "Download Payslip";         
				/*if($has_option) {
					$action = url("payslip/download_payslip?from={$start_date}&to={$end_date}");
					$wrapper_start = '<div class="btn-group" style="display:inline;">';
					$dropdown_btn_wrapper = '<ul class="dropdown-menu " style="margin-top:20px;margin-left:4px;"><li><a class="" href="'.$action.'&q=confidential"><i class=""></i> Download Confidential </a></li><li><a class="" id="" href="'.$action.'&q=non-confidential"><i class=""></i> Download Non-Confidential </a></li><li><a class="" id="" href="'.$action.'&q=both"><i class=""></i> Download Both </a></li></ul></div>';
					$caption_caret = ' <span class="icon icon-chevron-down icon-white"></span>';
					$action = "javscript:void(0);";
				}else{
					$wrapper_start = '';
					$dropdown_btn_wrapper = '';
					$caption_caret = '';
				}*/

				$wrapper_start = '';
				$dropdown_btn_wrapper = '';
				$caption_caret = '';

				$btn_dl_payslip_config = array(
					'module'				=> 'payroll',
		    		'parent_index'			=> 'payroll',
		    		'child_index'			=> '',
		    		'required_permission'	=> Sprint_Modules::PERMISSION_01,        		
		    		'event' 				=> 'onmouseup',
		    		//'action' 				=> $action,
		    		'href' 					=> $action,
		    		'id' 					=> '',
		    		//'class' 				=> 'blue_button dropdown-toggle',
		    		'class' 				=> 'blue_button',
		    		'icon' 					=> '',
		    		'additional_attribute' 	=> '',
		    		'wrapper_start'			=> $wrapper_start,
		    		'wrapper_end'			=> $dropdown_btn_wrapper,
		    		'caption' 				=> $caption . $caption_caret
				); 				

				//$action  = "location.href='" . url("payroll/payroll_download_payroll_register?from={$start_date}&to={$end_date}'");
				$action  = url("payroll_reports/payroll_management#cost_center");
				$caption = "Download Payroll Register";
				/*if($has_option) {
					$action = url("payroll/payroll_download_payroll_register?from={$start_date}&to={$end_date}");
					$wrapper_start = '<div class="btn-group" style="display:inline;">';
					$dropdown_btn_wrapper = '<ul class="dropdown-menu " style="margin-top:20px;margin-left:4px;"><li><a class="" href="'.$action.'&q=confidential"><i class=""></i> Download Confidential </a></li><li><a class="" id="" href="'.$action.'&q=non-confidential"><i class=""></i> Download Non-Confidential </a></li><li><a class="" id="" href="'.$action.'&q=both"><i class=""></i> Download Both </a></li></ul></div>';
					$caption_caret = ' <span class="icon icon-chevron-down icon-white"></span>';
					$action = "javscript:void(0);";
				}else{
					$wrapper_start = '';
					$dropdown_btn_wrapper = '';
					$caption_caret = '';
				}*/    
				$wrapper_start = '';
				$dropdown_btn_wrapper = '';
				$caption_caret = '';

				$btn_dl_payroll_config = array(
					'module'				=> 'payroll',
		    		'parent_index'			=> 'payroll',
		    		'child_index'			=> '',
		    		'required_permission'	=> Sprint_Modules::PERMISSION_01,        		
		    		'event' 				=> 'onmouseup',
		    		//'action' 				=> $action,
		    		'href' 					=> $action,
		    		'id' 					=> '',
		    		//'class' 				=> 'blue_button dropdown-toggle',
		    		'class' 				=> 'blue_button',
		    		'icon' 					=> '',
		    		'additional_attribute' 	=> '',
		    		'wrapper_start'			=> $wrapper_start,
		    		'wrapper_end'			=> $dropdown_btn_wrapper,
		    		'caption' 				=> $caption . $caption_caret
				); 
				
				$action  = "location.href='" . url("payslip/manage?from={$start_date}&to={$end_date}&frequency={$frequency}'");
				$caption = "View Payslip";         
				$btn_view_payslip_config = array(
					'module'				=> 'payroll',
		    		'parent_index'			=> 'payroll',
		    		'child_index'			=> '',
		    		'required_permission'	=> Sprint_Modules::PERMISSION_01,        		
		    		'event' 				=> 'onmouseup',
		    		'action' 				=> $action,
		    		'id' 					=> '',
		    		'class' 				=> 'blue_button',
		    		'icon' 					=> '',
		    		'additional_attribute' 	=> '',
		    		'wrapper_start'			=> '',
		    		'wrapper_end'			=> '',
		    		'caption' 				=> $caption
				); 	

				$action  = "location.href='" . url("payslip/processed_payroll?from={$start_date}&to={$end_date}'");
				$caption = "Processed Payroll";
				if($has_option) {
					$action = url("payslip/processed_payroll?from={$start_date}&to={$end_date}");
					$wrapper_start = '<div class="btn-group" style="display:inline;">';
					$dropdown_btn_wrapper = '<ul class="dropdown-menu " style="margin-top:20px;margin-left:4px;"><li><a class="" href="'.$action.'&q=confidential"><i class=""></i> View Confidential </a></li><li><a class="" id="" href="'.$action.'&q=non-confidential"><i class=""></i> View Non-Confidential </a></li><li><a class="" id="" href="'.$action.'&q=both"><i class=""></i> View All </a></li></ul></div>';
					$caption_caret = ' <span class="icon icon-chevron-down icon-white"></span>';
					$action = "javscript:void(0);";
				}else{
					$wrapper_start = '';
					$dropdown_btn_wrapper = '';
					$caption_caret = '';
				}    

				$btn_processed_payroll_config = array(
					'module'				=> 'payroll',
		    		'parent_index'			=> 'payroll',
		    		'child_index'			=> '',
		    		'required_permission'	=> Sprint_Modules::PERMISSION_01,        		
		    		'event' 				=> 'onmouseup',
		    		'action' 				=> $action,
		    		'id' 					=> '',
		    		'class' 				=> 'blue_button dropdown-toggle',
		    		'icon' 					=> '',
		    		'additional_attribute' 	=> '',
		    		'wrapper_start'			=> $wrapper_start,
		    		'wrapper_end'			=> $dropdown_btn_wrapper,
		    		'caption' 				=> $caption . $caption_caret
				); 

				$btns_dl_payslip[$p->getId()]   		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_payroll_actions, $btn_dl_payslip_config);
				$btns_dl_payroll[$p->getId()]   		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_payroll_actions, $btn_dl_payroll_config);
				$btns_view_payslip[$p->getId()] 		= G_Button_Builder::createButtonWithPermissionValidation($this->global_user_payroll_actions, $btn_view_payslip_config);
				$btn_processed_payroll[$p->getId()]   	= G_Button_Builder::createButtonWithPermissionValidation($this->global_user_payroll_actions, $btn_processed_payroll_config);

			}
			
			if( !$is_locked || !$is_payroll_generated ){				
				$action = "javascript:generatePayslipByMonthCutoffNumberYear({$month},{$cutoff_number},{$year},'{$q}','{$frequency}')";     
				$caption = "Generate Payroll";  

				if($has_option) {
					$conf_action = "javascript:generatePayslipByMonthCutoffNumberYear({$month},{$cutoff_number},{$year},'confidential','{$frequency}')"; 
					$non_conf_action = "javascript:generatePayslipByMonthCutoffNumberYear({$month},{$cutoff_number},{$year},'non-confidential','{$frequency}')"; 
					$both_action = "javascript:generatePayslipByMonthCutoffNumberYear({$month},{$cutoff_number},{$year},'both','{$frequency}')"; 
					$wrapper_start = '<div class="btn-group" style="display:inline;">';
					$dropdown_btn_wrapper = '<ul class="dropdown-menu " style="margin-top:20px;margin-left:4px;"><li><a class="" href="'.$conf_action.'"><i class=""></i> Generate Confidential </a></li><li><a class="" id="" href="'.$non_conf_action.'"><i class=""></i> Generate Non-Confidential </a></li><li><a class="" id="" href="'.$both_action.'"><i class=""></i> Generate Both </a></li></ul></div>';
					$caption_caret = ' <span class="icon icon-chevron-down icon-white"></span>';
					$action = "javscript:void(0);";
				}else{
					$wrapper_start = '';
					$dropdown_btn_wrapper = '';
					$caption_caret = '';
				} 

				$btn_generate_payroll_config = array(
					'module'				=> 'payroll',
		    		'parent_index'			=> 'payroll',
		    		'child_index'			=> '',
		    		'required_permission'	=> Sprint_Modules::PERMISSION_02,        		
		    		'event' 				=> 'onmouseup',
		    		'action' 				=> $action,
		    		'id' 					=> '',
		    		'class' 				=> 'blue_button dropdown-toggle',
		    		'icon' 					=> '',
		    		'additional_attribute' 	=> '',
		    		'wrapper_start'			=> $wrapper_start,
		    		'wrapper_end'			=> $dropdown_btn_wrapper,
		    		'caption' 				=> $caption . $caption_caret
				); 
				$btns_generate_payroll[$p->getId()] = G_Button_Builder::createButtonWithPermissionValidation($this->global_user_payroll_actions, $btn_generate_payroll_config);
			}
        }

        $all_payroll_years = G_Cutoff_Period_Helper::sqlGetAllExistYearTags();

        if(!empty($all_payroll_years)) { 
        	$payroll_years = $all_payroll_years;
        }else{ $payroll_years = array(Tools::getGmtDate('Y') - 1, Tools::getGmtDate('Y')); }

        $this->var['frequency'] = $frequency;
        $this->var['month'] 		= $month;
        $this->var['year'] 			= $year;
        $this->var['current_year']  = Tools::getGmtDate('Y');
        $this->var['previous_year'] = (int) Tools::getGmtDate('Y') - 1;
        $this->var['payroll_years'] = $payroll_years;
        $this->var['page_title']    = 'Payroll Generation';
        $this->var['frequencies'] = $frequencies;
        $this->var['months']        = Tools::getMonthNames();
        //old periods
        $this->var['periods']       = $frequency_type;
        $this->var['btns_dl_payroll']       = $btns_dl_payroll;
		$this->var['btns_dl_payslip']       = $btns_dl_payslip;
		$this->var['btns_view_payslip']     = $btns_view_payslip;
		$this->var['btns_generate_payroll'] = $btns_generate_payroll;
		$this->var['btn_processed_payroll'] = $btn_processed_payroll;
		$this->var['btns_lock_unlock']      = $btns_lock_unlock;
  $this->view->setTemplate('payroll/template.php');
  // $this->view->render('payroll_register/generation.php', $this->var);
  if($frequency == 1){        	
    $this->view->render('payroll_register/generation.php', $this->var);
  }elseif ($frequency == 2) {
        		$this->view->render('payroll_register/generation_weekly.php', $this->var);
  }else{
  	 $this->view->render('payroll_register/generation.php', $this->var);
  }
	
	}
	
	function history() {
		Jquery::loadMainTipsy();
		
		$this->var['page_title'] = 'Payroll History';
		$this->var['periods'] = G_Payslip_Helper::getPeriods();
		$this->view->setTemplate('payroll/template.php');

        $this->var['months'] = Tools::getMonthNames();
		$this->view->render('payroll_register/history.php',$this->var);		
	}

	function _load_generate_payroll_option() {
		if(!empty($_POST['month']) && !empty($_POST['cutoff_number']) && !empty($_POST['year']) ){
			$this->var['data']	= $_POST;
			$this->view->render('payroll_register/forms/generate_payroll_form.php',$this->var);
		}
	}

	function generate_payroll_option() {
		if(!empty($_GET['month']) && !empty($_GET['cutoff_number']) && !empty($_GET['year']) ){
			Jquery::loadMainJqueryDatatable();
			Jquery::loadMainTipsy();
			Jquery::loadMainInlineValidation2();
			Jquery::loadMainJqueryFormSubmit();
			Jquery::loadMainSelect2();

			$employee_access = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_access');
			if($employee_access == Sprint_Modules::PERMISSION_05) {
				$additional_qry = "";	
			}elseif($employee_access == Sprint_Modules::PERMISSION_06) {
				if(strtolower($employee_access) != strtolower($_GET['q']." Employees")) {
					redirect("payroll_register/generation");
				}	
				$additional_qry = " AND e.is_confidential = 1  ";	
			}elseif($employee_access == Sprint_Modules::PERMISSION_07) {
				if(strtolower($employee_access) != strtolower($_GET['q']." Employees")) {
					redirect("payroll_register/generation");
				}	
				$additional_qry = " AND e.is_confidential = 0  ";	
			}else{
				$additional_qry = "";	
			}

			if($employee_access == Sprint_Modules::PERMISSION_05 || $employee_access == 1) {
				if($_GET['q'] == "confidential") {
					$additional_qry = " AND e.is_confidential = 1  ";	
				}elseif($_GET['q'] == "non-confidential"){
					$additional_qry = " AND e.is_confidential = 0  ";
				}else{
					$additional_qry = "";
				}
			}

	       	/*$datec   = date("Y-m-d");
	        $current_p      = G_Cutoff_Period_Helper::sqlGetCurrentCutoffPeriod($datec);

	        if($current_p) {
	            $e = new G_Employee();
	            $additional_qry .= " AND ( e.employee_status_id != 4 ) ";
	            $payroll_employee_details = $e->getProcessedAndUnprocessedEmployeePayrollByCutoff($current_p['period_start'],$current_p['period_end'], $additional_qry );
	            $this->var['payroll_employee_details'] = $payroll_employee_details;	        	
	        } else {
				$period = G_Cutoff_Period_Finder::findByYearMonthAndCutoffNumber($_GET['year'], $_GET['month'], $_GET['cutoff_number']);  
		        if ($period) {
		            $additional_qry .= " AND ( e.employee_status_id != 4 ) ";
		            $e = new G_Employee();
		            $payroll_employee_details = $e->getProcessedAndUnprocessedEmployeePayrollByCutoff($period->getStartDate(),$period->getEndDate(), $additional_qry );
		            $this->var['payroll_employee_details'] = $payroll_employee_details;
		        }		        	
	        }*/

	   if ($_GET['frequency'] == 2) {
					$period = G_Weekly_Cutoff_Period_Finder::findByYearMonthAndCutoffNumber($_GET['year'], $_GET['month'], $_GET['cutoff_number']);  
	   }

	   elseif($_GET['frequency'] == 3){

	   		$period = G_Monthly_Cutoff_Period_Finder::findByYearMonthAndCutoffNumber($_GET['year'], $_GET['month'], $_GET['cutoff_number']); 
	   }

	   else {
					$period = G_Cutoff_Period_Finder::findByYearMonthAndCutoffNumber($_GET['year'], $_GET['month'], $_GET['cutoff_number']);  
	   }

	        if ($period) {
        					$s = G_Employee_Basic_Salary_History_Finder::findByDateAndFrequency($period->getEndDate(), $_GET['frequency']);

        					$employee_ids = array();
					        $employee_ids_qry = "";

					        foreach ($s as $key => $data) {
					        	$employee_ids[] = $data->employee_id;
					        }

					       	if (count($employee_ids) > 0) {
					       		$employee_ids_qry = " AND e.id IN (".implode (",", $employee_ids).") ";
					       	}

	            $additional_qry .= " AND ( e.employee_status_id != 4 ) ";
	            $e = new G_Employee();
	            $payroll_employee_details = $e->getProcessedAndUnprocessedEmployeePayrollByCutoff($period->getStartDate(),$period->getEndDate(), $additional_qry, $employee_ids_qry, $_GET['frequency'] );
	            $this->var['payroll_employee_details'] = $payroll_employee_details;
	        }

			$company_structure_id = $this->global_user_ecompany_structure_id;
			$b = G_Company_Branch_Finder::findMain();
			$this->var['departments'] = G_Company_Structure_Finder::findAllDepartmentsIsNotArchiveByBranchIdAndParentId($b->getId(),Utilities::decrypt($company_structure_id));
			$this->var['data']	= $_GET;
			$this->var['page_title']    = 'Generate Payroll';
			$this->view->setTemplate('payroll/template.php');
			$this->view->render('payroll_register/forms/generate_payroll_form.php',$this->var);
		}else{
			redirect('payroll_register/generation');
		}
	}

	function _load_employee_select() {
		if( $_POST['department_id'] ){
			if($_POST['q'] == "confidential") {
				$additional_qry = " AND is_confidential = 1  ";	
			}elseif($_POST['q'] == "non-confidential"){
				$additional_qry = " AND is_confidential = 0 ";
			}else{
				$additional_qry = "";
			}

			$fields = array("id,CONCAT(lastname,' ', firstname, ' ',middlename) as fullname");
			if($_POST['department_id'] == "all") {
				$this->var['employees'] =$x= G_Employee_Helper::sqlGetAllEmployees($fields,$additional_qry);	
			}else{
				$this->var['employees'] =$x= G_Employee_Helper::sqlGetAllEmployeeByDepartmentId(Utilities::decrypt($_POST['department_id']), $fields, $additional_qry);	
			}
		}

		$this->view->render('payroll_register/forms/_employee_select.php',$this->var);
	}

	function _load_selected_employee() {
		$selected_employee = explode(",", $_POST['selected_employee_id']);
		$selected_employee = array_values(array_filter(array_unique($selected_employee)));
		foreach($selected_employee as $key => $value) {
			$ids[] = Utilities::decrypt($value); 
		}
		$selected_employee = implode(",",$ids);
		$fields = array("id,CONCAT(lastname,' ', firstname, ' ',middlename) as fullname");
		$this->var['employees'] = G_Employee_Helper::sqlMultipleEmployeeDetailsById($selected_employee, $fields);	
		$this->view->render('payroll_register/forms/_selected_employee_dt.php',$this->var);
		
	}

	function _remove_from_selected_employee() {
		$selected_employee = explode(",", $_POST['selected_employee_id']);
		$selected_employee = array_values(array_filter(array_unique($selected_employee)));
		foreach($selected_employee as $key => $value) {
			if($value != $_POST['employee_id']) {
				$new_ids[] = $value; 
			}
			
		}
		$return['new_selected_employee_id'] = implode(",",$new_ids);
		echo json_encode($return);
		
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

				if( isset($_GET['cutoff_01']) && isset($_GET['cutoff_02']) ) {
					$this->var['cutoff_01'] = $_GET['cutoff_01'];
					$this->var['cutoff_02'] = $_GET['cutoff_02'];
				} else {
					$this->var['from'] = $_GET['from'];
					$this->var['to'] = $_GET['to'];
				}

				$this->var['n'] = $n;
				$this->var['page_title'] = 'Notification';
				$this->view->setTemplate('payroll/template.php');
				$this->view->render('payroll_register/view_notification.php',$this->var);  
			}else{
				redirect("payroll_register/generation");
			}
		}else{
			redirect("payroll_register/generation");
		}
	}

	function _load_view_notification_item_list()
	{		
		sleep(1);
		$nid = Utilities::decrypt($_POST['notification_id']);
		$n   = G_Notifications_Finder::findById($nid);
		if($n) {
			$filename = strtolower($n->getEventType());
			$filename = str_replace(' ','_',$filename);
			$filename = "_{$filename}_dt.php";
			$this->var['n'] = $n;

			//FOR INCOMPLETE DTR ONLY

			$from = $_POST['from'];
			$to   = $_POST['to'];

			$cutoff_01 = $_POST['cutoff_01'];
			$cutoff_02 = $_POST['cutoff_02'];

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

	        if($filename == '_no_payslip_found_dt.php' || $filename == '_unprocessed_payroll_dt.php') {

	        	$additional_qry 			    = "";
	        	$remove_resigned_terminated_endo_qry = " AND (e.employee_status_id NOT IN (2,3))";
	        	$remove_resigned_terminated_endo_qry .= " AND e.employee_status_id != 4";
	            $active_employees           = G_Employee_Helper::sqlAllActiveEmployees($query['date_from'], $remove_resigned_terminated_endo_qry);

				if(!empty($cutoff_01) && !empty($cutoff_02)) {

					$cutoff_01_explode = explode("/", $cutoff_01);
					$cutoff_02_explode = explode("/", $cutoff_02);

					$date_from_1 = $cutoff_01_explode[0];
					$date_to_1   = $cutoff_01_explode[1];

					$date_from_2 = $cutoff_02_explode[0];
					$date_to_2   = $cutoff_02_explode[1];

					$processed_payroll_employee_01 = G_Employee_Helper::sqlProcessedEmployeePayrollByCutoffGetEmployeeId($date_from_1, $date_to_1, $additional_qry);
					$processed_payroll_employee_02 = G_Employee_Helper::sqlProcessedEmployeePayrollByCutoffGetEmployeeId($date_from_2, $date_to_2, $additional_qry);

		            $process_employee = array();
		            foreach($processed_payroll_employee_01 as $process_data) {
		                $process_employee[] = $process_data['employee_id'];
		            }

		            $process_employee2 = array();
		            foreach($processed_payroll_employee_02 as $process_data2) {
		                $process_employee2[] = $process_data2['employee_id'];
		            }		            

		            $no_payslip_data1 = array();
		            $key = 1;
		            foreach($active_employees as $active_key => $active_data) {
		                if (!in_array($active_key, $process_employee)) {
		                		$this->var['from'] = $date_from_1;
		                		$this->var['to']   = $date_to_1;
			                  	$no_payslip_data1[$key]['employee_id']   = $active_key;
			                  	$no_payslip_data1[$key]['full_name']     = $active_data['full_name'];
			                  	$no_payslip_data1[$key]['employee_code'] = $active_data['employee_code'];
			                  	$no_payslip_data1[$key]['cutoff_period'] = $date_from_1 . " to " . $date_to_1;
		                }
		                $key++;
		            }

		            $no_payslip_data2 = array();
		            $key2 = 1;
		            foreach($active_employees as $active_key2 => $active_data) {
		                if (!in_array($active_key2, $process_employee2)) {
		                		$this->var['from'] = $date_from_2;
		                		$this->var['to']   = $date_to_2;
			                  	$no_payslip_data2[$key2]['employee_id']   = $active_key2;
			                  	$no_payslip_data2[$key2]['full_name']     = $active_data['full_name'];
			                  	$no_payslip_data2[$key2]['employee_code'] = $active_data['employee_code'];
			                  	$no_payslip_data2[$key2]['cutoff_period'] = $date_from_2 . " to " . $date_to_2;
		                }
		                $key2++;
		            }		

		            $no_payslip_data = array_merge($no_payslip_data1, $no_payslip_data2); 		            

				} else {

		            $processed_payroll_employee = G_Employee_Helper::sqlProcessedEmployeePayrollByCutoff($query['date_from'], $query['date_to'], $additional_qry);
		            $process_employee = array();
		            foreach($processed_payroll_employee as $process_data) {
		                $process_employee[] = $process_data['employee_id'];
		            }

		            $no_payslip_data = array();
		            foreach($active_employees as $active_key => $active_data) {
		                if (!in_array($active_key, $process_employee)) {
		                		$this->var['from'] = $query['date_from'];
		                		$this->var['to']   = $query['date_to'];
			                  	$no_payslip_data[$active_key]['employee_id']   = $active_key;
			                  	$no_payslip_data[$active_key]['full_name']     = $active_data['full_name'];
			                  	$no_payslip_data[$active_key]['employee_code'] = $active_data['employee_code'];
			                  	$no_payslip_data[$active_key]['cutoff_period'] = $query['date_from'] . " to " . $query['date_to'];
		                }
		            } 

				}  

	            $this->var['no_payslip_data'] = $no_payslip_data;
	        }
			
			$this->view->render('notifications/'.$filename,$this->var);
		}	
	}		
}
?>