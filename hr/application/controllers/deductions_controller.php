<?php
class Deductions_Controller extends Controller
{
	function __construct()
	{	
		parent::__construct();
		
		Loader::appStyle('style.css');
		Loader::appMainScript('deductions.js');
		Loader::appMainScript('deductions_base.js');		
		Loader::appMainUtilities();
		$this->sprintHdrMenu(G_Sprint_Modules::PAYROLL, 'earnings_deductions');

		$this->sprintHdrMenu(G_Sprint_Modules::PAYROLL, 'earnings_deductions');

		if( isset($_GET['cutoff_period']) ) {

			$cutoff_period_arr = explode("/", $_GET['cutoff_period']);
			$period_start      = $cutoff_period_arr[0];
			$period_end   	   = $cutoff_period_arr[1];
			$this->var['cutoff_selected'] = $period_start."/".$period_end;
				
				
					$frequency_id = $_GET['selected_frequency'];

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

			if($cutoff_data) {
				$from  = $cutoff_data->getStartDate();
				$to    = $cutoff_data->getEndDate();
				$hpid  = Utilities::encrypt($cutoff_data->getId());				

				if($hpid){
					$this->var['is_period_lock'] = $_SESSION['sprint_hr']['is_period_lock'] = G_Cutoff_Period_Helper::isPeriodLock($hpid);
				}else{			
					$this->var['is_period_lock'] = $_SESSION['sprint_hr']['is_period_lock'];
				}

				if($from && $to && $hpid){
					$this->var['download_url']    = url('reports/download_deductions?from=' . $from . '&to=' . $to . '&hpid=' . $hpid);
					$this->var['period_selected'] = '<small style="font-size:15px;">Period: <b>' . Tools::convertDateFormat($from) . ' </b> to <b>' . Tools::convertDateFormat($to) . '</b></small>';
				}					
			}
		} else {
			if($_GET['hpid']){
				$this->var['is_period_lock'] = $_SESSION['sprint_hr']['is_period_lock'] = G_Cutoff_Period_Helper::isPeriodLock($_GET['hpid']);
			}else{			
				$this->var['is_period_lock'] = $_SESSION['sprint_hr']['is_period_lock'];
			}

			if($_GET['from'] && $_GET['to'] && $_GET['hpid']){
				$this->var['download_url']    = url('reports/download_deductions?from=' . $_GET['from'] . '&to=' . $_GET['to'] . '&hpid=' . $_GET['hpid']);
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
		
		$this->validatePermission(G_Sprint_Modules::PAYROLL,'earnings_deductions','');		
	}

	function index()
	{			
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();		
		
		$this->var['recent']     = 'class="selected"';
		$this->var['module'] 	 = 'deductions';	
				
		$period['to']   = $_GET['to'];
		$period['from'] = $_GET['from'];
		$period['hpid'] = $_GET['hpid'];
		
		$eid = $_GET['hpid'];
        $this->var['cutoff_id'] = Utilities::decrypt($eid);
        $this->var['location'] = 'deductions';
		
		if($eid){
			Jquery::loadMainTipsy();
			Jquery::loadMainJqueryDatatable();
			Jquery::loadMainTextBoxList();

			$this->var['eid'] 		  = $eid;
			$this->var['period']      = $period;			
			$this->var['page_title']  = 'Deductions Management';
			$this->view->setTemplate('payroll/template_leftsidebar.php');
			$this->view->render('deductions/index.php',$this->var);
		}else{
            /*
			$this->var['periods'] 	 = G_Payslip_Helper::getPeriods();	
			$this->var['page_title'] = 'Deductions Management';	
			$this->view->setTemplate('template.php');
			$this->view->render('deductions/payroll_period.php',$this->var);*/

            $now = date('Y-m-d');
            $p = G_Cutoff_Period_Finder::findByDate($now);
            if ($p) {
                $hpid = Utilities::encrypt($p->getId());
                $from_date = $p->getStartDate();
                $to_date = $p->getEndDate();
            }


            redirect("deductions/approved?from={$from_date}&to={$to_date}&hpid={$hpid}");
		}
	}
	
	function approved()
	{			
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();
		
		$this->var['approved']   = 'class="selected"';				
		$this->var['module'] 	 = 'deductions';	

		if( isset($_GET['cutoff_period']) ) {
			$cutoff_period_arr = explode("/", $_GET['cutoff_period']);
			$period_start      = $cutoff_period_arr[0];
			$period_end   	   = $cutoff_period_arr[1];
			$this->var['cutoff_selected'] = $period_start."/".$period_end;
			$frequency_id = $_GET['selected_frequency'];
			

			// $cutoff_data  	   = G_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);

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
			$eid    		= $_GET['hpid'];
			$period['to']   = $_GET['to'];
			$period['from'] = $_GET['from'];
			$period['hpid'] = $_GET['hpid'];
			$this->var['year_selected']   = date("Y", strtotime($_GET['to']));
			$this->var['cutoff_selected'] = $_GET['from']."/".$_GET['to'];
	        $this->var['cutoff_id'] 	  = Utilities::decrypt($eid);			
		}		
			$this->var['selected_frequency'] = $frequency_id;
        //$this->var['cutoff_id'] = Utilities::decrypt($eid);
        $this->var['location'] = 'deductions/approved';


        $btn_add_deductions_config = array(
    		'module'				=> 'payroll',
    		'parent_index'			=> 'earnings_deductions',
    		'child_index'			=> 'deductions',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:show_add_deductions_form("'.$eid.'","'.$frequency_id.'");',
    		'id' 					=> 'add_deduction_button',
    		'class' 				=> 'add_button',
    		'icon' 					=> '',
    		'additional_attribute'	=> '',
    		'caption' 				=> '<strong>+</strong><b>Add Deductions</b>'
    	); 

    	$btn_import_deductions = array(
    		'module'				=> 'payroll',
    		'parent_index'			=> 'earnings_deductions',
    		'child_index'			=> 'deductions',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:importDeductions("'.$eid.'");',
    		'id' 					=> 'import_deduction_button',
    		'class' 				=> 'add_button float-right',
    		'icon' 					=> '<i class="icon-arrow-left"></i>',
    		'additional_attribute'	=> '',
    		'caption' 				=> '<b>Import Deductions</b>'
    	);

		$this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::PAYROLL,'earnings_deductions','deductions');
		$this->var['btn_add_deductions'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_payroll_actions, $btn_add_deductions_config);
		$this->var['btn_import_deductions'] = G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_payroll_actions, $btn_import_deductions);

        $all_payroll_years 				= G_Cutoff_Period_Helper::sqlGetAllExistYearTags();
        $this->var['all_cutoff_years'] 	= $all_payroll_years;			
		
		if($eid){		
			$this->var['eid'] 		   = $eid;
			$this->var['period']       = $period;				
			$this->var['page_title']   = 'Deductions Management';
			$this->view->setTemplate('payroll/template_leftsidebar.php');
			$this->view->render('deductions/approved.php',$this->var);
		}else{
			redirect('deductions');	
		}
	}

	function hold()
	{			
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();
		
		$this->var['hold']   = 'class="selected"';				
		$this->var['module'] 	 = 'deductions';	

		if( isset($_GET['cutoff_period']) ) {
			$cutoff_period_arr = explode("/", $_GET['cutoff_period']);
			$period_start      = $cutoff_period_arr[0];
			$period_end   	   = $cutoff_period_arr[1];
			$this->var['cutoff_selected'] = $period_start."/".$period_end;


			$frequency_id = 1;

		if( isset($_GET['selected_frequency']) && !empty($_GET['selected_frequency']) ) {
			$frequency_id = $_GET['selected_frequency'];
		}

			if ($frequency_id == 2) {
				$cutoff_data  	   = G_Weekly_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}


			elseif ($frequency_id == 3) {
				$cutoff_data  	   = G_Monthly_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}


			else {
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
		} else {
			$eid    		= $_GET['hpid'];
			$period['to']   = $_GET['to'];
			$period['from'] = $_GET['from'];
			$period['hpid'] = $_GET['hpid'];
			$this->var['year_selected']   = date("Y", strtotime($_GET['to']));
			$this->var['cutoff_selected'] = $_GET['from']."/".$_GET['to'];
	        $this->var['cutoff_id'] 	  = Utilities::decrypt($eid);
		}
        
        $this->var['location'] = 'deductions/hold';

        /*
        $btn_add_deductions_config = array(
    		'module'				=> 'payroll',
    		'parent_index'			=> 'earnings_deductions',
    		'child_index'			=> 'deductions',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:show_add_deductions_form("'.$eid.'");',
    		'id' 					=> 'add_deduction_button',
    		'class' 				=> 'add_button',
    		'icon' 					=> '',
    		'additional_attribute'	=> '',
    		'caption' 				=> '<strong>+</strong><b>Add Deductions</b>'
    		);
    	*/

        $all_payroll_years 				= G_Cutoff_Period_Helper::sqlGetAllExistYearTags();
        $this->var['all_cutoff_years'] 	= $all_payroll_years;		    		
		$this->var['permission_action'] = $this->validatePermission(G_Sprint_Modules::PAYROLL,'earnings_deductions','deductions');
		
		if($eid){		
			$this->var['eid'] 		   = $eid;
			$this->var['period']       = $period;				
			$this->var['page_title']   = 'Hold Deductions';
			$this->view->setTemplate('payroll/template_leftsidebar.php');
			$this->view->render('deductions/hold.php',$this->var);
		}else{
			redirect('deductions');	
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
		$this->var['module'] 	 = 'deductions';
		
		$period['to']   = $_GET['to'];
		$period['from'] = $_GET['from'];
		$period['hpid'] = $_GET['hpid'];
		
		$eid  = $_GET['hpid'];
		
		if($eid){		
			$this->var['eid'] 		   = $eid;
			$this->var['period']       = $period;				
			$this->var['page_title']   = 'Deductions Management';
			$this->view->setTemplate('payroll/template_leftsidebar.php');
			$this->view->render('deductions/archives.php',$this->var);
		}else{
			redirect('deductions');	
		}
	}
	
	function import_deductions()
	{
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		
		$file 	 = $_FILES['deduction_file']['tmp_name'];
		$deduction = new G_Deductions_Import($file);
		$deduction->setPayrollPeriodId(Utilities::decrypt($_POST['eid']));
		
		$is_imported = $deduction->setImportFile($file)->createImportBulkData()->bulkSave();	
		//Utilities::displayArray($is_imported);

		if ($is_imported) {
			$return['is_imported'] = true;
			$return['message']     = 'Deductions has been successfully imported.';	
		} else {
			$return['is_imported'] = false;
			$return['message']     = 'There was a problem importing deductions. Please contact the administrator.';
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
	
	function html_import_deductions() {
		$this->view->setTemplate('payroll/template_blank.php');
		$this->view->render('deductions/html/html_import_deductions.php', $this->var);	
	}	
		
	function ajax_edit_deduction() 
	{
		$gee = G_Employee_Deductions_Finder::findById(Utilities::decrypt($_POST['eid']));
		if($gee){
			$period = G_Cutoff_Period_Finder::findById($gee->getPayrollPeriodId());
			$this->var['gee']	        = $gee;
			$this->var['cutoff_period'] = $period->getStartDate() . ' to ' . $period->getEndDate();
			$this->var['token']		    = Utilities::createFormToken();
			$this->var['page_title']    = 'Edit Deduction';		
			$this->view->render('deductions/form/ajax_edit_deductions.php',$this->var);
		}
	}
	
	function ajax_add_new_deduction() 
	{
	
		sleep(1);
		if($_POST['frequency_id'] == 2){
			$cutoff_periods				 = G_Weekly_Cutoff_Period_Finder::findAllIsUnLock();
		if($_POST['eid']){
			$period = G_Weekly_Cutoff_Period_Finder::findById(Utilities::decrypt($_POST['eid']));
			$this->var['period']		 = $period;
			$this->var['eid']			 = $_POST['eid'];
			$this->var['cutoff_period'] = $period->getStartDate() . ' to ' . $period->getEndDate();
			$this->var['e']			     = $e;	
			$this->var['token']		     = Utilities::createFormToken();		
			$this->var['page_title']     = 'Add Deductions';		
			$this->view->render('deductions/form/add_deductions.php',$this->var);
		}
		}


		elseif($_POST['frequency_id'] == 3){
			$cutoff_periods				 = G_Monthly_Cutoff_Period_Finder::findAllIsUnLock();
			if($_POST['eid']){
				$period = G_Monthly_Cutoff_Period_Finder::findById(Utilities::decrypt($_POST['eid']));
				$this->var['period']		 = $period;
				$this->var['eid']			 = $_POST['eid'];
				$this->var['cutoff_period'] = $period->getStartDate() . ' to ' . $period->getEndDate();
				$this->var['e']			     = $e;	
				$this->var['token']		     = Utilities::createFormToken();		
				$this->var['page_title']     = 'Add Deductions';		
				$this->view->render('deductions/form/add_deductions.php',$this->var);
			}
		}


		else{
			
			$cutoff_periods				 = G_Cutoff_Period_Finder::findAllIsUnLock();
		if($_POST['eid']){
			$period = G_Cutoff_Period_Finder::findById(Utilities::decrypt($_POST['eid']));
			$this->var['period']		 = $period;
			$this->var['eid']			 = $_POST['eid'];
			$this->var['cutoff_period'] = $period->getStartDate() . ' to ' . $period->getEndDate();
			$this->var['e']			     = $e;	
			$this->var['token']		     = Utilities::createFormToken();		
			$this->var['page_title']     = 'Add Deductions';		
			$this->view->render('deductions/form/add_deductions.php',$this->var);
		}
		}
		
		
	}
	
	function ajax_import_deduction() 
	{		
		if($_POST['eid']){			
			$this->var['eid']		 = $_POST['eid'];	
			$this->var['action']	 = url('deductions/import_deductions');			
			$this->view->render('deductions/form/ajax_import_deductions.php',$this->var);
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
	
	function _save_deduction()
	{
		//Utilities::verifyFormToken($_POST['token']);		
		if($_POST['cutoff_period_id']){			
			if($_POST['deduction_id']){
				$gee = G_Employee_Deductions_Finder::findById(Utilities::decrypt($_POST['deduction_id']));
			}else{
				$gee = new G_Employee_Deductions();				
				$gee->setDateCreated($this->c_date);						
			}
			
			$gee->setCompanyStructureId(Utilities::decrypt($this->company_structure_id));
			$gee->setTitle($_POST['e_title']);		
			$gee->setRemarks($_POST['remarks']);				
			$gee->setAmount(str_replace(",","",$_POST['amount']));				
			$gee->setPayrollPeriodId(Utilities::decrypt($_POST['cutoff_period_id']));		
			if($_POST['apply_to_all_employee']){
				$gee->setEmployeeId(serialize('All Employee'));		
				$gee->setApplyToAllEmployee(G_Employee_Deductions::YES);				
			}else{
				// Employee id
				if(!empty($_POST['employee_id'])) {
					$did = $this->decrypt_array($_POST['employee_id']);
					$e = G_EMPLOYEE_FINDER::findById($did);
						$frequency_id = $e->getFrequencyId();
						$gee->setFrequencyId($frequency_id);
					$gee->setEmployeeId(serialize($did));
				}
				
				// Department/Section id
				if(!empty($_POST['department_section_id'])) {
					$did = $this->decrypt_array($_POST['department_section_id']);
					$gee->setDepartmentSectionId(serialize($did));
				}

				// Employment Status id
				if(!empty($_POST['employment_status_id'])) {
					// echo ",.re";
					$did = $this->decrypt_array($_POST['employment_status_id']);
					$gee->setEmploymentStatusId(serialize($did));	
				}
		
			}
			if($_POST['is_taxable']){
				$gee->setTaxable(G_Employee_Deductions::YES);
			}else{
				$gee->setTaxable(G_Employee_Deductions::NO);
			}
			$gee->setStatus(G_Employee_Deductions::APPROVED);				
			$gee->setIsArchive(G_Employee_Deductions::NO);								
			$gee->save();

            //G_Employee_Deductions_Helper::addToPayslip($gee);
			
			$json['is_success'] = 1;			
			$json['message']    = 'Record was successfully saved.';
		}else {
			$json['is_success'] = 0;
			$json['message']    = 'Error in sql';
		}
		$json['eid'] = $_POST['cutoff_period_id'];		
		echo json_encode($json);
	}
	
	function _with_selected_pending_action() 
	{
		if(!empty($_POST)) {
			$mArray = $_POST['dtChk'];
			foreach($mArray as $key => $value):
			$d++;
			$gee = G_Employee_Deductions_Finder::findById(Utilities::decrypt($value));		
			if($gee){
				if($_POST['chkAction'] == 'deduction_approve'){								
					$gee->approve();								
					$json['message']    = 'Successfully <b>approved</b> ' . $d . ' record(s)';	
					
				}elseif($_POST['chkAction'] == 'deduction_archive'){
					$gee->archive();							
					$json['message']    = 'Successfully <b>archived</b> ' . $d . ' record(s)';	
										
				}elseif($_POST['chkAction'] == 'deduction_disapprove'){
					$gee->disapprove();							
					$json['message']    = 'Successfully <b>disapproved</b> ' . $d . ' record(s)';	
										
				}elseif($_POST['chkAction'] == 'deduction_restore'){
					$gee->restore_archived();							
					$json['message']    = 'Successfully <b>restored /b> ' . $d . ' archived record(s)';	
										
				}else {
				
				}		
			}
			endforeach;
		}
		
		$json['is_success'] = 1;
		$json['eid']        = $_POST['eid'];
			
		echo json_encode($json);
	}
	
	function _approve_deduction()
	{
		if($_POST['keid']){
			$ea = G_Employee_Deductions_Finder::findById(Utilities::decrypt($_POST['keid']));
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
	
	function _archive_deduction()
	{
		if($_POST['keid']){
			$ea = G_Employee_Deductions_Finder::findById(Utilities::decrypt($_POST['keid']));
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
	
	function _restore_archived_deduction()
	{
		if($_POST['keid']){
			$ea = G_Employee_Deductions_Finder::findById(Utilities::decrypt($_POST['keid']));
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
	
	function _disapprove_deduction()
	{
		if($_POST['keid']){
			$ea = G_Employee_Deductions_Finder::findById(Utilities::decrypt($_POST['keid']));
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
	
	function _load_sum_approved_deductions() 
	{
		$this->var['approved_sum'] = G_Employee_Deductions_Helper::sumTotalIsNotArchiveApproveEarningByCompanyStructureIdAndPayrollPeriodId(Utilities::decrypt($_POST['eid']),Utilities::decrypt($this->company_structure_id));		
		$this->view->render('deductions/_sum_approved_deductions.php',$this->var);
	}	
	
	function _load_sum_archived_deductions() 
	{
		$this->var['archived_sum'] = G_Employee_Deductions_Helper::sumTotalIsArchiveEarningByCompanyStructureIdAndPayrollPeriodId(Utilities::decrypt($_POST['eid']),Utilities::decrypt($this->company_structure_id));		
		$this->view->render('deductions/_sum_archive_deductions.php',$this->var);
	}	
	
	function _load_sum_pending_deductions() 
	{
		$this->var['pending_sum'] = G_Employee_Deductions_Helper::sumTotalIsNotArchivePendingEarningByCompanyStructureIdAndPayrollPeriodId(Utilities::decrypt($_POST['eid']),Utilities::decrypt($this->company_structure_id));
		$this->view->render('deductions/_sum_pending_deductions.php',$this->var);
	}	
	
	function _load_deductions_list_dt() 
	{
		$deductions = G_Employee_Deductions_Finder::findAllPendingsByPayrollPeriodIdAndCompanyStructureIdAndIsNotArchive(Utilities::decrypt($_POST['eid']),Utilities::decrypt($this->company_structure_id));
		$this->var['pid']         = Utilities::decrypt($_POST['eid']);		
		$this->var['deductions']    = $deductions;		
		$this->view->render('deductions/_pending_deductions_list_dt.php',$this->var);
	}
	
	function _load_approved_deductions_list_dt() 
	{
		$this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::PAYROLL,'earnings_deductions','deductions');
		$deductions = G_Employee_Deductions_Finder::findAllApprovedByPayrollPeriodIdAndCompanyStructureIdAndIsNotArchive(Utilities::decrypt($_POST['eid']),Utilities::decrypt($this->company_structure_id));		
		$this->var['pid']          = Utilities::decrypt($_POST['eid']);
		$this->var['deductions']     = $deductions;		
		$this->view->render('deductions/_approved_deductions_list_dt.php',$this->var);
	}	
	
	function _load_archived_deductions_list_dt() 
	{
		$this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::PAYROLL,'earnings_deductions','deductions');
		$deductions = G_Employee_Deductions_Finder::findAllIsArchiveByPayrollPeriodIdAndCompanyStructureId(Utilities::decrypt($_POST['eid']),Utilities::decrypt($this->company_structure_id));		
		$this->var['pid']          = Utilities::decrypt($_POST['eid']);
		$this->var['deductions']     = $deductions;		
		$this->view->render('deductions/_archives_deductions_list_dt.php',$this->var);
	}

	function _load_hold_deductions_list_dt() 
	{
		$this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::PAYROLL,'earnings_deductions','deductions');
		$hold_deductions = G_Excluded_Employee_Deduction_Finder::findAllByPayrollPeriodIdAndAction(Utilities::decrypt($_POST['eid']),G_Excluded_Employee_Deduction::HOLD);		
		$this->var['pid']          = Utilities::decrypt($_POST['eid']);
		$this->var['from']          = $_POST['from'];
		$this->var['to']          	= $_POST['to'];
		$this->var['hold_deductions']     = $hold_deductions;		
		$this->view->render('deductions/_hold_deductions_list_dt.php',$this->var);
	}	

	function _show_move_deduction_form() {
		$period = G_Cutoff_Period_Finder::findByPeriod($_POST['from'],$_POST['to']);
		$eed = G_Excluded_Employee_Deduction_Finder::findById(Utilities::decrypt($_POST['id']));
		if($period && $eed){
			$cutoff_id = $period->getId();
			for($i = 0; $i <= 5; $i++) {
				$c  = new G_Cutoff_Period();
				$c->setId($cutoff_id);
				$cutoff_data = $c->getNextCutOff();

				$cutoff_arr_data[] = array(
					"cutoff_id" => Utilities::encrypt($cutoff_data['id']),
					"label"		=> Tools::convertDateFormat($cutoff_data['period_start']) . ' to ' . Tools::convertDateFormat($cutoff_data['period_end'])
					);

				$cutoff_id = $cutoff_data['id'];
			}

			$this->var['pid'] = $_POST['pid'];
			$this->var['from'] = $_POST['from'];
			$this->var['to'] = $_POST['to'];
			$this->var['eed'] = $eed;
			$this->var['cutoff_arr_data'] = $cutoff_arr_data;
			$this->view->render('deductions/form/_show_move_deduction_form.php', $this->var);
		}else{
			echo '<div class="alert alert-error">Invalid cutoff period.</div>';
		}
	}

	function _move_excluded_deduction() {
		$eed = G_Excluded_Employee_Deduction_Finder::findById(Utilities::decrypt($_POST['eid']));
		if($eed) {
			$new_payroll_period_id = Utilities::decrypt($_POST['select_payroll_period']);
			$employee_id = $eed->getEmployeeId();
			$variable_name = $eed->getVariableName();
			$amount = $eed->getAmount();

			$fields = array("id","company_structure_id");
			$e = G_Employee_Helper::sqlGetEmployeeDetailsById($employee_id,$fields);
			$company_structure_id = $e['company_structure_id'];

			$eed->setNewPayrollPeriodId($new_payroll_period_id);
			$eed->setAction(G_Excluded_Employee_Deduction::MOVE);
			$eed->save();

			$emp_deduction_values[] = "(".
				Model::safeSql($company_structure_id) . ",".
				Model::safeSql(serialize($employee_id)) . ",".
				Model::safeSql("Moved Deduction : ".ucfirst(str_replace("_"," ",$variable_name))) . ",".
				Model::safeSql("Moved Deduction from ".Tools::convertDateFormat($_POST['from']) . ' to ' . Tools::convertDateFormat($_POST['to'])) . ",".
				Model::safeSql($amount) . ",".
				Model::safeSql($new_payroll_period_id) . ",".
				Model::safeSql(G_Employee_Deductions::NO) . ",".
				Model::safeSql(G_Employee_Deductions::APPROVED) . ",".
				Model::safeSql(G_Employee_Deductions::NO) . ",".
				Model::safeSql(G_Employee_Deductions::NO) . ",".
				Model::safeSql(date("Y-m-d H:i:s")) . ",".
				Model::safeSql(1) 
			.")";
	
			G_Employee_Deductions_Manager::saveBulk(implode(",",$emp_deduction_values));

			$return['message'] = "Record successfully updated.";
			$return['is_success'] = true;
		}else{
			$return['message'] = "Unable to update record.";
			$return['is_success'] = false;
		}

		$return['from'] = $_POST['from'];
		$return['to'] = $_POST['to'];
		$return['pid'] = $_POST['pid'];

		echo json_encode($return);
	}

	function ajax_load_payroll_period_by_year()
	{
		$selected_year = $_GET['selected_year'];
		$selected_frequency = $_GET['selected_frequency'];
		if( $selected_year == '' || $selected_year <= 0 ){
			$selected_year = date("Y");
		}		


        $selected_year = $selected_year;
        // $c = G_Cutoff_Period_Finder::findAllCutoffByYear($selected_year);
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
	
}
?>