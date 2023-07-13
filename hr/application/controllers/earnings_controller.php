<?php
class Earnings_Controller extends Controller
{
	function __construct()
	{	
		parent::__construct();
		
		Loader::appStyle('style.css');
		Loader::appMainScript('earnings.js');
		Loader::appMainScript('earnings_base.js');		
		Loader::appMainUtilities();

		$this->sprintHdrMenu(G_Sprint_Modules::PAYROLL, 'earnings_deductions');

		if( isset($_GET['cutoff_period']) ) {

			$cutoff_period_arr = explode("/", $_GET['cutoff_period']);
			$period_start      = $cutoff_period_arr[0];
			$period_end   	   = $cutoff_period_arr[1];
			$this->var['cutoff_selected'] = $period_start."/".$period_end;

			$frequency_id = $_GET['selected_frequency'];

			if ($frequency_id == 2) {
				$cutoff_data  	   = G_Weekly_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}

			else if ($frequency_id == 3) {
				$frequency_id = 3;
				$cutoff_data  	   = G_Monthly_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}

			else {
				$cutoff_data  	   = G_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}

			
		
			if($cutoff_data) {
				
				$from  = $cutoff_data->getStartDate();
				$to    = $cutoff_data->getEndDate();
				$hpid  = Utilities::encrypt($cutoff_data->getId());

				$data = G_Cutoff_Period_Helper::isPeriodLock($hpid);		

				if($hpid){
					$this->var['is_period_lock'] = $_SESSION['sprint_hr']['is_period_lock'] = $data;
				}else{			
					$this->var['is_period_lock'] = $_SESSION['sprint_hr']['is_period_lock'];
				}

				if($from && $to && $hpid){
					$this->var['download_url']    = url('reports/download_earnings?from=' . $from . '&to=' . $to . '&hpid=' . $hpid);
		            $this->var['period_selected'] = '<small style="font-size:15px;">Period: <b>' . Tools::convertDateFormat($from) . ' </b> to <b>' . Tools::convertDateFormat($to) . '</b></small>';
				}				
			}

		} else {
			$data = G_Cutoff_Period_Helper::isPeriodLock($_GET['hpid']);		
			if($_GET['hpid']){
				$this->var['is_period_lock'] = $_SESSION['sprint_hr']['is_period_lock'] = $data;
			}else{			
				$this->var['is_period_lock'] = $_SESSION['sprint_hr']['is_period_lock'];
			}
			
			if($_GET['from'] && $_GET['to'] && $_GET['hpid']){
				$this->var['download_url']    = url('reports/download_earnings?from=' . $_GET['from'] . '&to=' . $_GET['to'] . '&hpid=' . $_GET['hpid']);
	            $this->var['period_selected'] = '<small style="font-size:15px;">Period: <b>' . Tools::convertDateFormat($_GET['from']) . ' </b> to <b>' . Tools::convertDateFormat($_GET['to']) . '</b></small>';
			}
		}
			$this->frequency_id = $frequency_id;
		$this->eid                  = $this->global_user_eid;
		$this->company_structure_id = $this->global_user_ecompany_structure_id;				
		$this->c_date  				= Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila');
		$this->default_method       = 'index';					
		$this->var['leave']         = 'selected';			
		$this->var['employee']      = 'selected';
		$this->var['eid']           = $this->eid;	
		$this->var['departments']   = G_Company_Structure_Finder::findByParentID(Utilities::decrypt($this->global_user_ecompany_structure_id));		

		$this->validatePermission(G_Sprint_Modules::PAYROLL,'earnings_deductions','');			
	}

	function index()
	{			
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();		
		
		$this->var['recent']     = 'class="selected"';				
		$this->var['module'] 	 = 'earnings'; 		
				
		$period['to']   = $_GET['to'];
		$period['from'] = $_GET['from'];
		$period['hpid'] = $_GET['hpid'];
		
		$eid  = $_GET['hpid'];
        $this->var['cutoff_id'] = Utilities::decrypt($eid);
        $this->var['location'] = 'earnings';

		if($eid){	
			Jquery::loadMainTipsy();
			Jquery::loadMainJqueryDatatable();
			Jquery::loadMainTextBoxList();
			
			$this->var['eid'] 		  = $eid;
			$this->var['period']      = $period;			
			$this->var['page_title']  = 'Earnings Management';
			$this->view->setTemplate('payroll/template_leftsidebar.php');
			$this->view->render('earnings/index.php',$this->var);
		}else{
            /*
			$this->var['periods'] 	 = G_Payslip_Helper::getPeriods();	
			$this->var['page_title'] = 'Earnings Management';	
			$this->view->setTemplate('payroll/template.php');
			$this->view->render('earnings/payroll_period.php',$this->var);*/

            $now = date('Y-m-d');
            $p = G_Cutoff_Period_Finder::findByDate($now);
            if ($p) {
                $hpid = Utilities::encrypt($p->getId());
                $from_date = $p->getStartDate();
                $to_date = $p->getEndDate();
            }
            redirect("earnings/approved?from={$from_date}&to={$to_date}&hpid={$hpid}&selected_frequency=1");
		}
	}

	function yearly_bonus()
	{
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();	
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();	
		
		$this->var['recent'] = 'class="selected"';				
		$this->var['module'] = 'earnings'; 		
		
		$year = date("Y");
        $this->var['start_year']  = 2015;        
		$this->var['location']    = 'earnings';		
        $this->var['cutoff_id']   = $eid;   
		$this->var['page_title']  = "13th Month Summary";			
		$this->var['period']      = $period;			
		$this->view->setTemplate('payroll/template.php');
		$this->view->render('earnings/yearly_bonus.php',$this->var);
	}
	
	function approved()
	{			
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();
		
		$this->var['approved']   = 'class="selected"';				
		$this->var['module'] 	 = 'earnings'; 		
		$frequency_id = $_GET['selected_frequency'];
		if( isset($_GET['cutoff_period']) ) {
			$cutoff_period_arr = explode("/", $_GET['cutoff_period']);
			$period_start      = $cutoff_period_arr[0];
			$period_end   	   = $cutoff_period_arr[1];
			$this->var['cutoff_selected'] = $period_start."/".$period_end;

			if ($frequency_id == 2) {
				$frequency_id = 2;
				$cutoff_data  	   = G_Weekly_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}

			else if ($frequency_id == 3) {
				$frequency_id = 3;
				$cutoff_data  	   = G_Monthly_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}

			else {
				$frequency_id = 1;
				$cutoff_data  	   = G_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}
			
			// $cutoff_data  	   = G_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);

			if($cutoff_data) {
				
				$eid        = Utilities::encrypt($cutoff_data->getId());
				$cutoff_id 	= $cutoff_data->getId();
				$from_date  = $cutoff_data->getStartDate();
				$to_date 	= $cutoff_data->getEndDate();

				$period['from'] = $from_date;
				$period['to']   = $to_date;
				$period['hpid'] = $eid;
				$this->var['year_selected'] = $_GET['year_selected'];	
		        $this->var['cutoff_id'] 	= Utilities::decrypt($eid);
			}
		} 
		else {
		
			$period['to']   = $_GET['to'];
			$period['from'] = $_GET['from'];
			$period['hpid'] = $_GET['hpid'];
			$this->var['year_selected']   = date("Y", strtotime($_GET['to']));
			$eid  						  = $_GET['hpid'];
			$this->var['cutoff_selected'] = $_GET['from']."/".$_GET['to'];
	        $this->var['cutoff_id'] 	  = Utilities::decrypt($eid);
		}	

		$this->var['selected_frequency'] = $frequency_id;
		
        $this->var['location'] = 'earnings/approved';

        $btn_add_earnings_config = array(
    		'module'				=> 'payroll',
    		'parent_index'			=> 'earnings_deductions',
    		'child_index'			=> 'earnings',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:show_add_earnings_form("'.$eid.'","'.$frequency_id.'");',
    		'id' 					=> 'add_earning_button',
    		'class' 				=> 'add_button',
    		'icon' 					=> '',
    		'additional_attribute'	=> '',
    		'caption' 				=> '<strong>+</strong><b>Add Earnings</b>'
    		); 

        $btn_import_earnings_config = array(
    		'module'				=> 'payroll',
    		'parent_index'			=> 'earnings_deductions',
    		'child_index'			=> 'earnings',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:importEarnings("'.$eid.'");',
    		'id' 					=> 'import_earning_button',
    		'class' 				=> 'add_button float-right',
    		'icon' 					=> '<i class="icon-arrow-left"></i>',
    		'additional_attribute'	=> '',
    		'caption' 				=> '<b>Import Earnings</b>'
    	);
		
		$this->var['permission_action']   = $this->validatePermission(G_Sprint_Modules::PAYROLL,'earnings_deductions','earnings');
		$this->var['btn_add_earnings'] 	  = G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_payroll_actions, $btn_add_earnings_config);
		$this->var['btn_import_earnings'] = G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_payroll_actions, $btn_import_earnings_config);

        $all_payroll_years 				= G_Cutoff_Period_Helper::sqlGetAllExistYearTags();

        $this->var['all_cutoff_years'] 	= $all_payroll_years;	


		if($eid){		
			$this->var['eid'] 		   = $eid;
			$this->var['period']       = $period;				
			$this->var['page_title']   = 'Earnings Management';
			$this->view->setTemplate('payroll/template_leftsidebar.php');
			$this->view->render('earnings/approved.php',$this->var);
		}else{
			redirect('earnings');	
		}
	}
	
	function archives()
	{			
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();
		
		$this->var['archives']   = 'class="selected"';				
		$this->var['module'] 	 = 'earnings'; 		

		if( isset($_GET['cutoff_period']) ) {
			$cutoff_period_arr = explode("/", $_GET['cutoff_period']);
			$period_start      = $cutoff_period_arr[0];
			$period_end   	   = $cutoff_period_arr[1];
			$this->var['cutoff_selected'] = $period_start."/".$period_end;
			$cutoff_data  	   = G_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);

			if($cutoff_data) {
				$eid        = Utilities::encrypt($cutoff_data->getId());
				$cutoff_id 	= $cutoff_data->getId();
				$from_date  = $cutoff_data->getStartDate();
				$to_date 	= $cutoff_data->getEndDate();

				$period['from'] = $from_date;
				$period['to']   = $to_date;
				$period['hpid'] = $eid;
				$this->var['year_selected'] = $_GET['year_selected'];	
		        $this->var['cutoff_id'] 	= Utilities::decrypt($eid);
			}
		} else {
			$period['to']   = $_GET['to'];
			$period['from'] = $_GET['from'];
			$period['hpid'] = $_GET['hpid'];
			$this->var['year_selected']   = date("Y", strtotime($_GET['to']));
			$eid  						  = $_GET['hpid'];
			$this->var['cutoff_selected'] = $_GET['from']."/".$_GET['to'];
	        $this->var['cutoff_id'] 	  = Utilities::decrypt($eid);			
		}
		
		if($eid){		
			$this->var['eid'] 		   = $eid;
			$this->var['period']       = $period;				
			$this->var['page_title']   = 'Earnings Management';
			$this->view->setTemplate('payroll/template_leftsidebar.php');
			$this->view->render('earnings/archives.php',$this->var);
		}else{
			redirect('earnings');	
		}
	}
	
	function import_earnings()
	{
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		
		$file 	 = $_FILES['earning_file']['tmp_name'];

		$earning = new G_Earnings_Import($file);
		$earning->setPayrollPeriodId(Utilities::decrypt($_POST['eid']));
		$is_imported = $earning->setImportFile($file)->createImportBulkData()->bulkSave();	
		//utilities::displayArray($is_imported);

		if ($is_imported) {
			$return['is_imported'] = true;
			$return['message']     = 'Earnings has been successfully imported.';	
		} else {
			$return['is_imported'] = false;
			$return['message']     = 'There was a problem importing earnings. Please contact the administrator.';
		}

		echo json_encode($return);		
	}	
	
	function decrypt_array($string)
	{	
		if($string){
			$arr = explode(",",$string);
			foreach($arr as $p){										
				$new_array[] = Utilities::decrypt($p);				
			}
			return implode(",",$new_array);
		}else{return 0;}
	}
	
	function html_import_earnings() {
		$this->view->setTemplate('payroll/template_blank.php');
		$this->view->render('earnings/html/html_import_earnings.php', $this->var);	
	}	
		
	function ajax_edit_earning() 
	{
		$gee = G_Employee_Earnings_Finder::findById(Utilities::decrypt($_POST['eid']));
		if($gee){
			$period = G_Cutoff_Period_Finder::findById($gee->getPayrollPeriodId());
			$this->var['gee']	        = $gee;
			$this->var['cutoff_period'] = $period->getStartDate() . ' to ' . $period->getEndDate();
			$this->var['token']		    = Utilities::createFormToken();
			$this->var['page_title']    = 'Edit Earning';		
			$this->view->render('earnings/form/ajax_edit_earnings.php',$this->var);
		}
	}
	
	function ajax_add_new_earning() 
	{
		sleep(1);
		$current_year = date("Y");
		$cutoff = new G_Cutoff_Period();
		$cutoff_w = new G_Weekly_Cutoff_Period();
		if($_POST['frequency_id'] == 2){
			$cutoff_periods = $cutoff->setYearTag($current_year)->getCutoffPeriodsByYearWeeklyNotLock();
		}

		else if($_POST['frequency_id'] == 3){
			$cutoff_periods = $cutoff->setYearTag($current_year)->getCutoffPeriodsByYearMonthly();
		}

		else{
				$cutoff_periods = $cutoff->setYearTag($current_year)->getCutoffPeriodsByYearNotLock();
		}
	
		
		// $cutoff_periods_w = $cutoff->setYearTag($current_year)->getCutoffPeriodsByYear();
		
		$cutoff_periods = Tools::encryptMulitDimeArrayIndexValue('id', $cutoff_periods);

		$cutoff_periods_previous_year_not_lock = $cutoff->setYearTag($current_year)->getCutoffPeriodsByPreviousNotLockYear();	
		$cutoff_periods_previous_year_not_lock = Tools::encryptMulitDimeArrayIndexValue('id', $cutoff_periods_previous_year_not_lock);

		if($_POST['frequency_id'] == 2){
				$current_cutoff_data = $cutoff->getCurrentCutoffPeriodWeekly($this->c_date);		
				if( isset($current_cutoff_data['id']) ){
					$current_cutoff_id = $current_cutoff_data['id'];
				}else{
					$current_cutoff_id = 0;
				}
		}

		else if($_POST['frequency_id'] == 3){
				$current_cutoff_data = $cutoff->getCurrentCutoffPeriodMonthly($this->c_date);	

				if( isset($current_cutoff_data['id']) ){
					$current_cutoff_id = $current_cutoff_data['id'];
				}else{
					$current_cutoff_id = 0;
				}
		}



		else{
					$current_cutoff_data = $cutoff->getCurrentCutOffPeriod($this->c_date);		
				if( isset($current_cutoff_data['id']) ){
					$current_cutoff_id = $current_cutoff_data['id'];
				}else{
					$current_cutoff_id = 0;
				}
		}
	
			// echo "--->";
			// echo $current_cutoff_id;

		$earnings = new G_Employee_Earnings();
		$percentage_selections   = $earnings->getValidPercentageSelections();
		$earning_type_selections = $earnings->getValidEarningTypeSelections();

		if(!empty($cutoff_periods_previous_year_not_lock)) {
			$cutoff_periods_merge = array_merge($cutoff_periods, $cutoff_periods_previous_year_not_lock);
		} else {
			$cutoff_periods_merge = $cutoff_periods;
		}
 
 
 		/*echo '<pre>';
 		print_r($cutoff_periods_merge);
		print_r($cutoff_periods_previous_year_not_lock);
		print_r($cutoff_periods);
		echo '</pre>';*/

		$this->var['frequency_id'] = $_POST['frequency_id'];
		$this->var['earning_type_selections'] = $earning_type_selections;		
		$this->var['cutoff_periods']        = $cutoff_periods_merge; //$cutoff_periods;
		$this->var['percentage_selections'] = $percentage_selections;
		$this->var['current_cutoff_id']     = $current_cutoff_id;
		$this->var['token']		     = Utilities::createFormToken();		
		$this->var['page_title']     = 'Add Earnings';		
		$this->view->render('earnings/form/add_earnings.php',$this->var);
	}
	
	function ajax_import_earning() 
	{		
		if($_POST['eid']){			
			$this->var['eid']		 = $_POST['eid'];	
			$this->var['action']	 = url('earnings/import_earnings');			
			$this->view->render('earnings/form/ajax_import_earnings.php',$this->var);
		}
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
	
	function _save_earning()
	{
	
		Utilities::verifyFormToken($_POST['token']);		
		if( !empty($_POST) && isset($_POST['e_title'])){
			$data = $_POST;
			$apply_to_ids['employee']   = $data['e_employee_id'];
			$apply_to_ids['department'] = $data['e_department_section_id'];
			$apply_to_ids['employment_status'] = $data['e_employment_status_id'];				

			$ea = new G_Employee_Earnings();	
			$ea->setCompanyStructureId(Utilities::decrypt($this->company_structure_id));	
			$ea->setTitle($data['e_title']);
			$ea->setAmount($data['e_amount']);
			$ea->setPercentage($data['e_percentage']);
			$ea->setPercentageMultiplier($data['e_percentage_selection']);
			$ea->setEarningType($data['e_earning_type']);
			if( isset($data['e_is_taxable']) ){
				$ea->setAsTaxable();
			}else{
				$ea->setAsIsNotTaxable();
			}
			$ea->setPayrollPeriodId(Utilities::decrypt($data['e_cutoff_period']));
			$ea->setRemarks($data['e_remarks']);
			$ea->setFrequencyId($data['frequency_id']);
			$ea->setAsNotArchive();
			$ea->setAsApproved();
			$ea->setDateCreated($this->c_date);
			if( isset($data['e_apply_to_all']) && $data['e_apply_to_all'] == 1 ){
				$json = $ea->createApplyToAllEarningData()->save();
			}else{
				$json = $ea->setApplyToIds($apply_to_ids)->createBulkEarningData()->bulkInsertData();			
			}
		}else{
			$json['is_success'] = 0;
			$json['message']    = 'Cannot save record';
		}

		//Current cutoff
		$date = $this->c_data;
		$c = new G_Cutoff_Period();
		$cutoff = $c->getCurrentCutoffPeriod($this->c_date);

		/*if( !empty($cutoff) ){
			$json['eid']   = $cutoff['id'];
		}else{
			$json['eid']   = $data['e_cutoff_period'];
		}*/

		$json['eid']   = $data['e_cutoff_period'];

		$json['token'] = Utilities::createFormToken();
		echo json_encode($json);
	}
	
	function _with_selected_pending_action() 
	{
		if(!empty($_POST)) {
			$mArray = $_POST['dtChk'];
			foreach($mArray as $key => $value):
			$d++;
			$gee = G_Employee_Earnings_Finder::findById(Utilities::decrypt($value));		
			if($gee){
				if($_POST['chkAction'] == 'earning_approve'){								
					$gee->approve();								
					$json['message']    = 'Successfully <b>approved</b> ' . $d . ' record(s)';	
					
				}elseif($_POST['chkAction'] == 'earning_archive'){
					$gee->archive();							
					$json['message']    = 'Successfully <b>archived</b> ' . $d . ' record(s)';	
										
				}elseif($_POST['chkAction'] == 'earning_disapprove'){
					$gee->disapprove();							
					$json['message']    = 'Successfully <b>disapproved</b> ' . $d . ' record(s)';	
										
				}elseif($_POST['chkAction'] == 'earning_restore'){
					$gee->restore_archived();							
					$json['message']    = 'Successfully <b>restored </b> ' . $d . ' archived record(s)';	
										
				}else {
				
				}		
			}
			endforeach;
		}
		
		$json['is_success'] = 1;
		$json['eid']        = $_POST['eid'];
			
		echo json_encode($json);
	}

	function _with_selected_earnings_action() 
	{
		Utilities::displayArray($_POST);

		/*if(!empty($_POST)) {
			$mArray = $_POST['dtChk'];
			foreach($mArray as $key => $value):
			$d++;
			$gee = G_Employee_Earnings_Finder::findById(Utilities::decrypt($value));		
			if($gee){
				if($_POST['chkAction'] == 'earning_approve'){								
					$gee->approve();								
					$json['message']    = 'Successfully <b>approved</b> ' . $d . ' record(s)';	
					
				}elseif($_POST['chkAction'] == 'earning_archive'){
					$gee->archive();							
					$json['message']    = 'Successfully <b>archived</b> ' . $d . ' record(s)';	
										
				}elseif($_POST['chkAction'] == 'earning_disapprove'){
					$gee->disapprove();							
					$json['message']    = 'Successfully <b>disapproved</b> ' . $d . ' record(s)';	
										
				}elseif($_POST['chkAction'] == 'earning_restore'){
					$gee->restore_archived();							
					$json['message']    = 'Successfully <b>restored </b> ' . $d . ' archived record(s)';	
										
				}else {
				
				}		
			}
			endforeach;
		}
		
		$json['is_success'] = 1;
		$json['eid']        = $_POST['eid'];
			
		echo json_encode($json);*/
	}

	function _process_yearly_bonus()
	{
		$data = $_POST;

		//utilities::displayArray($data);exit();

		if(!isset($data['deduct_tardiness'])) {
			$data['deduct_tardiness'] = 0;
		}

		$year   = date('Y');
		$bonus  = new G_Yearly_Bonus();
		$bonus->setMonthStart($data['start_month']);
		$bonus->setMonthEnd($data['end_month']);
		$bonus->setFrequency($data['frequency']);		

		if( $data['use-import-file'] == 'Yes' ){	
		 	$yearly_bonus_data = [
		 		'year' => $year, 
		 		'cutoff' => $data['cutoff_period'], 
		 		'file' => $_FILES['yearly_bonus_file']['tmp_name'], 
		 		'percentage' => $data['percentage'], 
		 		'deduct_tardiness' => $data['deduct_tardiness'],
				'frequency' => $data['frequency'] 
		 	];					
			$json   = $bonus->importYearlyBonus($yearly_bonus_data);
		}else{			
			$yearly_bonus_data = [
				'year' => $year, 
				'cutoff' => $data['cutoff_period'], 
				'action' => 2, 
				'selected' => array(), 
				'percentage' => $data['percentage'], 
				'deduct_tardiness' => $data['deduct_tardiness'],
				'frequency' => $data['frequency'],
				'payroll_start' => $data['payroll_start_month'] 
			];					
			$json   = $bonus->processYearlyBonus($yearly_bonus_data);
		}

		echo json_encode($json);
	}

	function process_yearly_bonus()
	{
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();	
		Jquery::loadMainTipsy();		

		$year = date("Y");
		$c    = G_Cutoff_Period_Finder::findAllByYear($year);        		
		
		$this->var['current_year'] = $year;

		$this->var['recent'] = 'class="selected"';				
		$this->var['module'] = 'earnings'; 				
		$this->var['cutoff_periods'] = $c;
		$this->var['token']			 = Utilities::createFormToken();
        $this->var['start_year']     = 2015;     
        $this->var['months']         = array(1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December');  		
		$this->var['page_title']     = "Process 13th Month";					
		$this->view->setTemplate('payroll/template.php');
		$this->view->render('earnings/form/process_yearly_bonus.php',$this->var);
	}


	function _approve_earning()
	{
		if($_POST['keid']){
			$ea = G_Employee_Earnings_Finder::findById(Utilities::decrypt($_POST['keid']));
			if($ea){
				$ea->approve();
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
	
	function _archive_earning()
	{
		if($_POST['keid']){
			$ea = G_Employee_Earnings_Finder::findById(Utilities::decrypt($_POST['keid']));
			if($ea){
				$ea->archive();
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
	
	function _restore_archived_earning()
	{
		if($_POST['keid']){
			$ea = G_Employee_Earnings_Finder::findById(Utilities::decrypt($_POST['keid']));
			if($ea){
				$ea->restore_archived();
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
	
	function _disapprove_earning()
	{
		if($_POST['keid']){
			$ea = G_Employee_Earnings_Finder::findById(Utilities::decrypt($_POST['keid']));
			if($ea){
				$ea->disapprove();
				$json['is_success'] = 1;			
				$json['message']    = 'Record was successfully saved.';
			}
		}else{
			$json['is_success'] = 0;
			$json['message']    = 'Error in sql';
		}
		
		$json['eid'] = $_POST['eid'];
		echo json_encode($json);
	}
	
	function _load_sum_approved_earnings() 
	{
		$this->var['approved_sum'] = G_Employee_Earnings_Helper::sumTotalIsNotArchiveApproveEarningByCompanyStructureIdAndPayrollPeriodId(Utilities::decrypt($_POST['eid']),Utilities::decrypt($this->company_structure_id));		
		$this->view->render('earnings/_sum_approved_earnings.php',$this->var);
	}	
	
	function _load_sum_archived_earnings() 
	{
		$this->var['archived_sum'] = G_Employee_Earnings_Helper::sumTotalIsArchiveEarningByCompanyStructureIdAndPayrollPeriodId(Utilities::decrypt($_POST['eid']),Utilities::decrypt($this->company_structure_id));		
		$this->view->render('earnings/_sum_archive_earnings.php',$this->var);
	}	
	
	function _load_sum_pending_earnings() 
	{
		$this->var['pending_sum'] = G_Employee_Earnings_Helper::sumTotalIsNotArchivePendingEarningByCompanyStructureIdAndPayrollPeriodId(Utilities::decrypt($_POST['eid']),Utilities::decrypt($this->company_structure_id));
		$this->view->render('earnings/_sum_pending_earnings.php',$this->var);
	}	
	
	function _load_earnings_list_dt() 
	{		
		$earnings = G_Employee_Earnings_Finder::findAllPendingsByPayrollPeriodIdAndCompanyStructureIdAndIsNotArchive(Utilities::decrypt($_POST['eid']),Utilities::decrypt($this->company_structure_id));
		$this->var['pid']         = Utilities::decrypt($_POST['eid']);		
		$this->var['earnings']    = $earnings;		
		$this->view->render('earnings/_pending_earnings_list_dt.php',$this->var);
	}

	function _load_yearly_bonus_list_dt() 
	{
		$e = new G_Employee();
		$query['year'] = $_GET['year'];
		$add_query 	   = '';
		$data = $e->getEmployeesYearlyBonusByYear($query, $add_query);
		
		$this->var['yearly_bonus_data'] = $data;		
		$this->view->render('earnings/_yearly_bonus_list_dt.php',$this->var);
	}

	function _load_leave_converted_list_dt() 
	{
		$year = $_GET['year'];
		$e    = new G_Employee();
		$data = $e->getConvertedLeavesByYear($year);

		$group_array = array();

		foreach($data as $dkey => $d) {
			$group_array[$d['employee_code']][] = $d;
		}
		$converted_leave_array = array();
		foreach($group_array as $dgkey => $dg) {
			foreach($dg as $d) {
				$converted_leave_array[$dgkey]['firstname'] = $d['firstname'];
				$converted_leave_array[$dgkey]['lastname']  = $d['lastname'];

				if($d['leave_type'] == "Service Incentive Leave") // from Incentive Leave to Service Incentive Leave
				{
					$converted_leave_array[$dgkey]['incentive'] += $d['total_leave_converted'];	
				}elseif($d['leave_type'] == "General leave") {
					$converted_leave_array[$dgkey]['general'] += $d['total_leave_converted'];	
				}
				
				$converted_leave_array[$dgkey]['total_leave_converted'] += $d['total_leave_converted'];
				$converted_leave_array[$dgkey]['amount'] += $d['amount'];

			}
			
		}		

		$this->var['leave_data'] = $data;		
		$this->var['leave_data_group'] = $converted_leave_array;
		$this->view->render('earnings/_converted_leave_list_dt.php',$this->var);
	}
	
	function _load_approved_earnings_list_dt() 
	{
		
		
		$frequency_id = $_POST['frequency_id'];
		
		$this->var['permission_action'] = $this->validatePermission(G_Sprint_Modules::PAYROLL,'earnings_deductions','earnings');
		$earnings = G_Employee_Earnings_Finder::findAllApprovedByPayrollPeriodIdAndCompanyStructureIdAndIsNotArchive(Utilities::decrypt($_POST['eid']),Utilities::decrypt($this->company_structure_id),$frequency_id);		
		$this->var['pid']          = Utilities::decrypt($_POST['eid']);
		$this->var['earnings']     = $earnings;		
		$this->view->render('earnings/_approved_earnings_list_dt.php',$this->var);
	}	
	
	function _load_archived_earnings_list_dt() 
	{
		$this->var['permission_action'] = $this->validatePermission(G_Sprint_Modules::PAYROLL,'earnings_deductions','earnings');
		$earnings = G_Employee_Earnings_Finder::findAllIsArchiveByPayrollPeriodIdAndCompanyStructureId(Utilities::decrypt($_POST['eid']),Utilities::decrypt($this->company_structure_id));		
		$this->var['pid']          = Utilities::decrypt($_POST['eid']);
		$this->var['earnings']     = $earnings;		
		$this->view->render('earnings/_archives_earnings_list_dt.php',$this->var);
	}

	function converted_list() 
	{
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();	
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();	
		
		$this->var['recent'] = 'class="selected"';				
		$this->var['module'] = 'earnings'; 		
		
		$year = date("Y");
        $this->var['start_year']  = 2015;        
		$this->var['location']    = 'earnings';		          
		$this->var['page_title']  = "Converted Leaves";							
		$this->view->setTemplate('payroll/template.php');
		$this->view->render('earnings/converted_leave.php',$this->var);
	}

	function convert_leave()
	{
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();	
		Jquery::loadMainTipsy();		

		$year = date("Y");
		$c    = G_Cutoff_Period_Finder::findAllByYear($year);        		

		$this->var['recent'] = 'class="selected"';				
		$this->var['module'] = 'earnings'; 				
		$this->var['cutoff_periods'] = $c;
		$this->var['token']			 = Utilities::createFormToken();
        $this->var['start_year']     = 2015;     
        $this->var['months']         = array(1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December');  		
		$this->var['page_title']     = "Leave";					
		$this->view->setTemplate('payroll/template.php');
		$this->view->render('earnings/form/convert_leave.php',$this->var);
	}

	function _process_leave_conversion()
	{
		$data = $_POST;
		
		$cutoff_period = $data['cutoff_period'];
		
		$slg  = new G_Settings_Leave_General();        	
        $json = $slg->getAllUnusedLeaveCreditLastYear()->isCutoffPeriodLock($cutoff_period)->applyGeneralRule(); 

        $slg 	= new G_Settings_Leave_General();
    	$slg->applyCredits(true);	   
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
        $this->var['cutoff_periods']  = $c;
		$this->view->noTemplate();
		$this->view->render('earnings/_payroll_period.php',$this->var);
	}	


	//for yearly bonus
	function ajax_load_payroll_period_by_year2()
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
        else {
       	 $c = G_Cutoff_Period_Finder::findAllCutoffByYear($selected_year);
        }
        

        $this->var['selected_cutoff'] = $_GET['selected_cutoff'];
        $this->var['selected_year']   = $selected_year;
        $this->var['cutoff_periods']  = $c;
		$this->view->noTemplate();
		$this->view->render('earnings/_payroll_period.php',$this->var);
	}	
	
}
?>