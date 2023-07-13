<?php
class Loan_Controller extends Controller
{
	function __construct()
	{	
		parent::__construct();
		
		Loader::appStyle('style.css');
		Loader::appMainScript('loan.js');
		Loader::appMainScript('loan_base.js');	

		$this->sprintHdrMenu(G_Sprint_Modules::PAYROLL, 'earnings_deductions');	
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
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainBootStrapDropDown();

		$btn_add_loans_config = array(
    		'module'				=> 'payroll',
    		'parent_index'			=> 'earnings_deductions',
    		'child_index'			=> 'loans',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:show_request_loan_form();',
    		'id' 					=> 'request_leave_button',
    		'class' 				=> 'add_button',
    		'icon' 					=> '',
    		'additional_attribute'	=> '',
    		'caption' 				=> '<strong>+</strong><b>Add New Loan</b>'
    		); 


		$btn_import_loan_config = array(
    		'module'				=> 'payroll',
    		'parent_index'			=> 'earnings_deductions',
    		'child_index'			=> 'loans',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:importLoans();',
    		'id' 					=> '',
    		'class' 				=> 'add_button pull-right',
    		'icon' 					=> '<i class="icon-arrow-left"></i>',
    		'additional_attribute' 	=> '',
    		'caption' 				=> 'Import Loans'
    		); 
		
		$this->var['permission_action'] = $this->validatePermission(G_Sprint_Modules::PAYROLL,'earnings_deductions','loans');
		$this->var['btn_add_loans'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_payroll_actions, $btn_add_loans_config);


		$this->var['btn_import_loans'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_payroll_actions, $btn_import_loan_config);
		
		$this->var['recent']        = 'class="selected"';		
		$this->var['page_title']    = 'Loans Management';
		$this->var['module'] 		= 'loan'; 		
		$this->view->setTemplate('payroll/template_leftsidebar.php');
		$this->view->render('loan/index.php',$this->var);		
	}
	
	function history()
	{		
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();
		
		$this->var['history']       = 'class="selected"';		
		$this->var['page_title']    = 'Loans Management';
		$this->var['module'] 		= 'loan'; 		
		$this->view->setTemplate('payroll/template_leftsidebar.php');
		$this->view->render('loan/history.php',$this->var);		
	}
	
	function archives()
	{		
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();
		
		$this->var['archives']      = 'class="selected"';		
		$this->var['page_title']    = 'Loans Management';
		$this->var['module'] 		= 'loan'; 		
		$this->view->setTemplate('payroll/template_leftsidebar.php');
		$this->view->render('loan/archives.php',$this->var);		
	}
	
	function schedule()
	{
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();
		
		$hid  = $_GET['hid'];
		$hash = $_GET['hash'];
		
		Utilities::verifyHash(Utilities::decrypt($hid),$hash);				
		$hid = Utilities::decrypt($hid);
		$gel = G_Employee_Loan_Finder::findById($hid);		
		if($gel){
			$this->var['gel']		    = $gel;
			$this->var['recent']        = 'class="selected"';
			$this->var['l_details']		= true;		
			$this->var['page_title']    = 'Loans Management';
			$this->var['module'] 		= 'loan'; 
			$this->view->setTemplate('payroll/template_leftsidebar.php');
			$this->view->render('loan/schedule.php',$this->var);		
		}else{
			redirect('loan');
		}
	}
	
	function details()
	{		
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();
		
		$hid  = $_GET['hid'];
		$hash = $_GET['hash'];
		
		Utilities::verifyHash(Utilities::decrypt($hid),$hash);				
		$hid = Utilities::decrypt($hid);
		$gel = G_Employee_Loan_Finder::findById($hid);
		if($gel){
			$this->var['gel']		    = $gel;
			$this->var['recent']        = 'class="selected"';
			$this->var['l_details']		= true;		
			$this->var['page_title']    = 'Loans Management';
			$this->var['module'] 		= 'loan'; 
			$this->view->setTemplate('payroll/template_leftsidebar.php');
			$this->view->render('loan/details.php',$this->var);		
		}else{
			redirect('loan');
		}
	}
	
	function e_history()
	{		
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();
		
		$hid  = $_GET['hid'];
		$hash = $_GET['hash'];
		
		Utilities::verifyHash(Utilities::decrypt($hid),$hash);				
		$hid = Utilities::decrypt($hid);	
		$this->employee_summary_photo($hid);
		$this->var['employee'] 		= $employee = G_Employee_Helper::findByEmployeeId($hid);			
		$this->var['employee_id']   = $hid;
 		$this->var['history']       = 'class="selected"';
		$this->var['e_history']		= true;		
		$this->var['page_title']    = 'Loans Management';
		$this->var['module'] 		= 'loan'; 
		$this->view->setTemplate('payroll/template_leftsidebar.php');
		$this->view->render('loan/employee_loan_details.php',$this->var);		
	}
	
	function loan_type()
	{		
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();
		//Jquery::loadCDropDown();
		$this->var['page_title']    = 'Loans Management';
		$this->var['module'] 		= 'loan'; 
		$this->var['loan_type']     = 'class="selected"';
		$this->var['type']			= 'loan_type';

		$btn_add_loan_type_config = array(
    		'module'				=> 'payroll',
    		'parent_index'			=> 'earnings_deductions',
    		'child_index'			=> 'loans',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:show_add_loan_type_form();',
    		'id' 					=> 'request_leave_button',
    		'class' 				=> 'add_button',
    		'icon' 					=> '',
    		'additional_attribute'	=> '',
    		'caption' 				=> '<strong>+</strong><b>Add New Loan</b>'
    		); 
		
		$this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::PAYROLL,'earnings_deductions','loans');
		$this->var['btn_add_loan_type'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_payroll_actions, $btn_add_loan_type_config);

		$this->view->setTemplate('payroll/template_leftsidebar.php');
		$this->view->render('loan/loan_type.php',$this->var);		
	}
	
	function loan_deduction_type()
	{		
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();
		
		$this->var['page_title']    	  = 'Loans Management';
		$this->var['module'] 			  = 'loan'; 
		$this->var['loan_deduction_type'] = 'class="selected"';
		$this->var['type']			      = 'loan_deduction_type';
		$this->view->setTemplate('payroll/template_leftsidebar.php');
		$this->view->render('loan/loan_deduction_type.php',$this->var);		
	}
	
	function ajax_add_new_loan_type() 
	{
		sleep(1);
		$this->var['e']			 = $e;	
		$this->var['token']		 = Utilities::createFormToken();		
		$this->var['page_title'] = 'Add Loan Type';		
		$this->view->render('loan/form/add_loan_type.php',$this->var);
	}
	
	function ajax_show_add_loan_payment_form() 
	{
		sleep(1);
		$geld = G_Employee_Loan_Details_Finder::findById(Utilities::decrypt($_POST['e_id']));
		$this->var['geld']		 = $geld;	
		$this->view->render('loan/form/_add_loan_payment_form.php',$this->var);
	}
	
	function ajax_add_new_loan_payment() 
	{
		sleep(1);
		$this->var['eid']		 = $_POST['e_id'];
		$this->var['e']			 = $e;	
		$this->var['token']		 = Utilities::createFormToken();		
		$this->var['page_title'] = 'Add Loan Type';		
		$this->view->render('loan/form/add_loan_payment.php',$this->var);
	}

	function ajax_loan_payment_schedule_notification() 
	{
		$json['is_with_notification'] = false;
		$json['message'] = '';

		$loan_id = $_GET['loan_id'];
		if( $loan_id > 0 ){
			$loan = new G_Employee_Loan($loan_id);			
			$json = $loan->getLoanNotification();	
		}

		echo json_encode($json);
	}

	function ajax_loan_balance() 
	{		
		$json['amount'] = '';

		$loan_id = $_GET['loan_id'];
		if( $loan_id > 0 ){
			$l = G_Employee_Loan_Finder::findById($loan_id);
			if( $l ){
				$json['amount'] = number_format($l->loanBalance(),2);
			}
		}

		echo json_encode($json);
	}

	function ajax_selected_view_loan_details()
	{		
		$ids    = $_GET['dtChk'];
		$fields = array("l.employee_name","l.loan_title","l.interest_rate","FORMAT(l.loan_amount,2)AS loan_amount","FORMAT(l.amount_paid,2)AS amount_paid","l.months_to_pay","l.deduction_type","l.start_date","l.end_date","FORMAT(l.total_amount_to_pay,2)AS total_amount_to_pay","l.deduction_per_period","l.status");
		foreach($ids as $id){
			$l = new G_Employee_Loan();
			$l->setFields($fields);
			$l->setId($id);			
			$data[$id]['details'] = $l->getLoanDetails();
		}
		
		if( !empty($data) ){			
			//General Reports / Shr Audit Trail

			$dload = $l->getLoanDetails();
			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'PAYROLL', ACTION_VIEW, ' Selected Loan Details ', $dload['loan_title'], $dload['employee_name'], $dload['loan_amount'], 1, '', '');

			$this->var['data'] = $data;
			//$this->view->render('loan/form/_loan_details.php',$this->var);
		}else{
			echo "No data to show";

			//General Reports / Shr Audit Trail
			$dload = $l->getLoanDetails();
			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'PAYROLL', ACTION_VIEW, ' Selected Loan Details ', $dload['loan_title'], $dload['employee_name'], $dload['loan_amount'], 0, '', '');
		}
	}

	function ajax_selected_view_loan_payment_history()
	{		
		$ids    		= $_GET['dtChk'];
		$fields_history = array("id","loan_payment_scheduled_date","amount_to_pay","amount_paid","date_paid","remarks","is_lock");
		$fields_details = array("(l.loan_amount - l.amount_paid)AS loan_balance","l.employee_id","l.employee_name","l.loan_title","l.interest_rate","FORMAT(l.loan_amount,2)AS loan_amount","FORMAT(l.amount_paid,2)AS amount_paid","l.months_to_pay","l.deduction_type","l.start_date","l.end_date","FORMAT(l.total_amount_to_pay,2)AS total_amount_to_pay","l.deduction_per_period","l.status");
		foreach($ids as $id){
			$loan = new G_Employee_Loan($id);
			$loan->setFields($fields_details);
			$data[$id]['details'] = $loan->getLoanDetails();
			$data[$id]['history'] = $loan->getLoanSchedule($fields_history);	
			$data[$id]['notification_payment'] = $loan->getLoanNotification();		
		}				
		if( !empty($data) ){			

			//General Reports / Shr Audit Trail
			$dload = $loan->getLoanDetails();
			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'PAYROLL', ACTION_VIEW, ' Selected Loan Details ', $dload['loan_title'], $dload['employee_name'], $dload['loan_amount'], 1, '', '');

			$this->var['data'] = $data;
			$this->view->render('loan/form/_loan_payment_history.php',$this->var);
		}else{
			echo "No data to show";
			//General Reports / Shr Audit Trail
			$dload = $loan->getLoanDetails();
			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'PAYROLL', ACTION_VIEW, ' Selected Loan Details ', $dload['loan_title'], $dload['employee_name'], $dload['loan_amount'], 0, '', '');
		}
	}
	
	function ajax_add_new_loan() 
	{
		sleep(1);
		$cp    		 = new G_Settings_Pay_Period();
		$cutoff_days = $cp->getValidCutoffDays();

		$loan = new G_Employee_Loan();
		$options_deduction_type = $loan->getValidLoanDeductionTypeList();
		$default_interest       = $loan->getDefaultInterest();
		$options_bimonthly_frequency = $loan->getValidBiMonthlyFrequencyOptions();

		$year_tags    = G_Cutoff_Period_Helper::sqlGetAllUniqueYearTags();
		$current_year = date('Y');		

		$c    = new G_Cutoff_Period();		
		$data = $c->expectedCutoffPeriodByYear($current_year,true);				

		$this->var['start_year']     = date('Y',strtotime("-10 years"));
		$this->var['max_year']       = date('Y',strtotime("+10 years"));
		$this->var['months_tags']    = array("January","February","March","April","May","June","July","August","September","October","November","December");
		$this->var['year_tags']	     = $year_tags;	
		$this->var['cutoff_periods'] = $data;
		$this->var['options_bimonthly_frequency'] = $options_bimonthly_frequency;
		$this->var['options_deduction_type'] = $options_deduction_type;		
		$this->var['default_interest']       = $default_interest;		
		$this->var['loan_type']      = G_Loan_Type_Finder::findAllIsNotArchive();		
		$this->var['e']			     = $e;	
		$this->var['token']		     = Utilities::createFormToken();		
		$this->var['page_title']     = 'Add New Loan';		
		$this->view->render('loan/form/add_loan.php',$this->var);
	}

	function ajax_loan_breakdown()
	{	
		$data = $_GET;		

		$amount = $data['company_loan_amount'];
		$months_to_pay  = $data['months_to_pay'];
		$interest_rate  = $data['interest_rate'];
		$deduction_type = $data['deduction_frequency'];
		$period 		= $data['start_date'];
		$govt_period 		= $data['government_start_date'];
		// $govt_period = $data['start_date'];
		// var_dump($govt_period);

	 

		$lc = new Loan_Calculator($amount);
		$lc->setMonthsToPay($months_to_pay);
		$lc->setInterestRate($interest_rate);
		$lc->setDeductionType($deduction_type);		
		$loan_data = $lc->computeLoanNew($period,$govt_period);
	
		$data['loan_amount_with_interest'] = number_format($loan_data['total_amount_to_pay'],2);
		$data['expected_due']  = number_format($loan_data['monthly_due'],2);
		$data['loan_end_date'] = $loan_data['end_date']; 

  echo json_encode($data);
	}
	
	function ajax_add_new_loan_deduction_type() 
	{
		sleep(1);
		$this->var['e']			 = $e;	
		$this->var['token']		 = Utilities::createFormToken();		
		$this->var['page_title'] = 'Add Loan Deduction Type';		
		$this->view->render('loan/form/add_loan_deduction_type.php',$this->var);
	}
	
	function ajax_edit_loan_type() 
	{
		$glt = G_Loan_Type_Finder::findById(Utilities::decrypt($_POST['e_id']));
		
		$this->var['glt']	     = $glt;
		$this->var['token']		 = Utilities::createFormToken();
		$this->var['page_title'] = 'Edit Loan Type';		
		$this->view->render('loan/form/edit_loan_type.php',$this->var);
	}
	
	function ajax_edit_loan() 
	{
		$this->var['gel']			 = $gel = G_Employee_Loan_Finder::findById(Utilities::decrypt($_POST['e_id']));
		$this->var['loan_type']      = G_Loan_Type_Finder::findAllIsNotArchive();
		$this->var['deduction_type'] = G_Loan_Deduction_Type_Finder::findAllIsNotArchive();
		$this->var['e']			     = $e;	
		$this->var['token']		     = Utilities::createFormToken();		
		$this->var['page_title']     = 'Edit Loan';		
		$this->var['has_started']	 = (strtotime(date("Y-m-d")) >= strtotime($gel->getStartDate()) ? 1 : 0);
		$this->var['emp']			 = $emp = G_Employee_Finder::findById($gel->getEmployeeId());
		$this->view->render('loan/form/edit_loan.php',$this->var);
	}
	
	function ajax_edit_loan_deduction_type() 
	{
		$gldt = G_Loan_Deduction_Type_Finder::findById(Utilities::decrypt($_POST['e_id']));
		
		$this->var['gldt']	     = $gldt;
		$this->var['token']		 = Utilities::createFormToken();
		$this->var['page_title'] = 'Edit Loan Deduction Type';		
		$this->view->render('loan/form/edit_loan_deduction_type.php',$this->var);
	}
	
	function ajax_edit_loan_payment() 
	{
		$geld      = G_Employee_Loan_Details_Finder::findById(Utilities::decrypt($_POST['e_id']));
		$breakdown = G_Employee_Loan_Payment_Breakdown_Finder::findAllByLoanPaymentId($geld->getId());
		$this->var['breakdown']  = $breakdown;
		$this->var['geld']	     = $geld;
		$this->var['token']		 = Utilities::createFormToken();
		$this->var['page_title'] = 'Edit Loan Payment';		
		$this->view->render('loan/form/edit_loan_payment.php',$this->var);
	}
	
	function ajax_add_loan_payment() 
	{
		$gel  = G_Employee_Loan_Finder::findById(Utilities::decrypt($_POST['e_id']));
		$geld = G_Employee_Loan_Details_Finder::findAllDatePaymentByLoanId($gel->getId());
		$this->var['geld']	     = $geld;
		$this->var['gel']	     = $gel;
		$this->var['token']		 = Utilities::createFormToken();
		$this->var['page_title'] = 'Add Loan Payment';		
		$this->view->render('loan/form/add_loan_payment.php',$this->var);
	}
	
	function ajax_get_employees_autocomplete() 
	{

		// var_dump($_GET);
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

	function ajax_get_frequency_id(){
		$employee_id =  Utilities::decrypt($_POST['decypted_employee_id']);
		$e = G_Employee_Finder::findById($employee_id);
		$frequency_id = $e->getFrequencyId();
		
		//var_dump($_POST['decypted_employee_id']);
		header('Content-type: application/json');
		$response = array('frequency_id' => $frequency_id ); 

		echo $frequency_id;

	}
	
	function ajax_compute_no_of_installment()
	{
		
	}
	
	function ajax_get_end_date()
	{
		if($_POST){
			$n_installment 		 = $_POST['number_of_installment'];
			$n_start_date		 = $_POST['start_date'];
			$n_type_of_deduction = Utilities::decrypt($_POST['type_of_deduction']);
			
			$end_date = G_Employee_Loan_Helper::getLoanEndDate($n_installment,$n_start_date,$n_type_of_deduction);
			$json['end_date'] = $end_date;
			echo json_encode($json);
		}
	}
	
	function employee_summary_photo($hid)
	{
		$employee_id = $hid;
		$e 	  	     = G_Employee_Finder::findById($employee_id);
		$file 		 = PHOTO_FOLDER.$e->getPhoto();
		
		if(Tools::isFileExist($file)==true && $e->getPhoto()!='') {
			$this->var['filemtime'] = md5($e->getPhoto()).date("His");
			$this->var['filename']  = $file;			
		}else {
			$this->var['filename'] = BASE_FOLDER. 'images/profile_noimage.gif';		
		}			
	}
		
	function _load_loan_list_dt() 
	{	
		$this->var['permission_action'] = $this->validatePermission(G_Sprint_Modules::PAYROLL,'earnings_deductions','loans');	
		$this->view->render('loan/_loan_list_dt.php',$this->var);
	}
	
	function _load_loan_archive_list_dt() 
	{	
		$this->var['permission_action'] = $this->validatePermission(G_Sprint_Modules::PAYROLL,'earnings_deductions','loans');	
		$this->view->render('loan/_loan_archive_list_dt.php',$this->var);
	}
	
	function _load_loan_period_payment_breakdown() 
	{
		sleep(1);
		$breakdown = G_Employee_Loan_Payment_Breakdown_Finder::findAllByLoanPaymentId(Utilities::decrypt($_POST['e_id']));
		$this->var['hide_show'] = $_POST['hide_show'];
		$this->var['breakdown'] = $breakdown;	
		$this->view->render('loan/_loan_period_payment_breakdown.php',$this->var);
	}
	
	function _load_loan_type_archive_list_dt() 
	{	
		$this->var['permission_action'] = $this->validatePermission(G_Sprint_Modules::PAYROLL,'earnings_deductions','loans');		
		$this->view->render('loan/_loan_type_archive_list_dt.php',$this->var);
	}
	
	function _load_loan_deduction_type_archive_list_dt() 
	{		
		$this->view->render('loan/_loan_deduction_type_archive_list_dt.php',$this->var);
	}
	
	function _load_employee_list_dt() 
	{
		$this->var['dept_id'] = $_POST['dept_id'];
		$this->view->render('loan/_employee_list.php',$this->var);
	}
	
	function _load_loan_type_list_dt() 
	{		
		$this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::PAYROLL,'earnings_deductions','loans');
		$this->view->render('loan/_loan_type_list_dt.php',$this->var);
	}
	
	function _load_loan_details_list_dt() 
	{			
		$details = G_Employee_Loan_Details_Finder::findAllByLoanId(Utilities::decrypt($_POST['e_id']));
		$this->var['details']	= $details;
		$this->var['e_loan_id'] = $_POST['e_id'];
		$this->view->render('loan/_loan_details_list_dt.php',$this->var);
	}
	
	function _load_employee_loan_list_dt() 
	{	
		$this->var['e_employee_id'] = $_POST['e_id'];
		$this->view->render('loan/_employee_loan_list_dt.php',$this->var);
	}
	
	function _load_employee_loan_details_list_dt() 
	{	
		$this->var['e_loan_id'] = $_POST['e_id'];
		$this->view->render('loan/_employee_loan_details_list_dt.php',$this->var);
	}
	
	function _load_loan_deduction_type_list_dt() 
	{		
		$this->view->render('loan/_loan_deduction_type_list_dt.php',$this->var);
	}
	
	function _load_archive_loan_type() {
		if(!empty($_POST)) {
			$glt = G_Loan_Type_Finder::findById(Utilities::decrypt($_POST['e_id']));
			if($glt) {				
				$json['is_success'] = 1;
				$glt->setIsArchive(G_Loan_Type::YES);
				$glt->save();							
			}
		}else{$json['is_success'] = 0;}
		
		echo json_encode($json);
	}
	
	function _load_restore_archive_loan_type() {
		if(!empty($_POST)) {
			$glt = G_Loan_Type_Finder::findById(Utilities::decrypt($_POST['e_id']));
			if($glt) {				
				$json['is_success'] = 1;
				$glt->setIsArchive(G_Loan_Type::NO);
				$glt->save();							
			}
		}else{$json['is_success'] = 0;}
		
		echo json_encode($json);
	}
	
	function _load_archive_loan_deduction_type() {
		if(!empty($_POST)) {
			$gldt = G_Loan_Deduction_Type_Finder::findById(Utilities::decrypt($_POST['e_id']));
			if($gldt) {				
				$json['is_success'] = 1;
				$gldt->setIsArchive(G_Loan_Deduction_Type::YES);
				$gldt->save();							
			}
		}else{$json['is_success'] = 0;}
		
		echo json_encode($json);
	}
	
	function _load_restore_loan_deduction_type() {
		if(!empty($_POST)) {
			$gldt = G_Loan_Deduction_Type_Finder::findById(Utilities::decrypt($_POST['e_id']));
			if($gldt) {				
				$json['is_success'] = 1;
				$gldt->setIsArchive(G_Loan_Deduction_Type::NO);
				$gldt->save();							
			}
		}else{$json['is_success'] = 0;}
		
		echo json_encode($json);
	}

	function _delete_loan_payment_schedule() {
		$id   = $_POST['id'];

		$json['is_success'] = false;
		$json['message']    = "Cannot delete record";
		if( $id > 0 ){			
			$lh = G_Employee_Loan_Payment_History_Finder::findById($id);
			if( $lh ){
				$json = $lh->delete();
				$lh->updateLoanHeaderAmountPaid();
			}			
		}

		echo json_encode($json);
	}

	function _update_loan_amount() {
		
		$json['is_success'] = false;
		$json['message']    = "Cannot update record";
		$loan_id = $_POST['loan_id'];
		$employee_id = $_POST['employee_id'];
		$total_amount_to_pay = str_replace(',','',$_POST['total_amount_to_pay']);
		$deduction_per_period = str_replace(',','',$_POST['deduction_per_period']);

		if(!empty($loan_id)){
			$el = G_Employee_Loan_Finder::findById($loan_id);
			if($el){
				$el->setId($loan_id);
				$el->setLoanAmount($total_amount_to_pay);
				$el->setTotalAmountToPay($total_amount_to_pay);
				$el->setDeductionPerPeriod($deduction_per_period);
				$el->save();

				$ls = G_Employee_Loan_Payment_Schedule_Finder::findByLoanId($loan_id);

				if($ls){
					foreach ($ls as $key => $value) {
						$value->setLoanId($loan_id);
						$value->setAmountToPay($deduction_per_period);
						$value->save();
					}

					$json['is_success'] = true;
					$json['message']    = "update record";
				}
			}
		}

		echo json_encode($json);
		//exit();
	}


	function _update_loan_status() {
		
		$json['is_success'] = false;
		$json['message']    = "Cannot update record";
		$loan_id = $_POST['loan_id'];
		$employee_id = $_POST['employee_id'];

		if(!empty($loan_id)){
			$el = G_Employee_Loan_Finder::findById($loan_id);
			if($el){
				$el->setId($loan_id);
				$el->setAsStop();
				$el->setAsLock();
				$el->save();

				$json['is_success'] = true;
				$json['message']    = "update record";

				//General Reports / Shr Audit Trail
				$dload = $el->getLoanDetails();
				$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'PAYROLL', ACTION_STOP, 'Selected Loan ', $dload['loan_title'], $dload['employee_name'], $dload['loan_amount'], 1, '', '');
			}
		}

		echo json_encode($json);
	}




	function _update_loan_payment_schedule(){
		$data = $_POST['loan_data'];
		//Utilities::displayArray($data);

		$json['is_success'] = false;
		$json['message']    = "Cannot update record";
		if( !empty($data) ){
			$id = $data['id'];
			$loan_id = $data['loan_id'];
			$payment_schedule = date("Y-m-d",strtotime($data['payment_schedule']));
			$date_paid   = $data['date_paid'];
			$amount_paid = $data['amount_paid'];
			$amount_to_pay = $data['amount_to_pay'];

			$ls = G_Employee_Loan_Payment_Schedule_Finder::findById($id);

			//Utilities::displayArray($loan_id);

			if($ls){
				$ls->setLoanId($loan_id);
				$ls->setLoanPaymentScheduledDate($payment_schedule);
			}

			$lh = G_Employee_Loan_Payment_History_Finder::findById($id);
			if( $lh ){				
				if( $payment_schedule != '' ){
					$lh->setLoanPaymentScheduledDate($payment_schedule);	
				}
				$lh->setAmountToPay($amount_to_pay);
				$lh->setAmountPaid($amount_paid);
				$lh->setDatePaid($date_paid);
				$json = $lh->update();
				$lh->updateLoanHeaderAmountPaid();													
			}
		}

		echo json_encode($json);
	}

	function _add_loan_payment_schedule(){
		$data = $_POST['loan_data'];		
		$json['is_success'] = false;
		$json['message']    = "Cannot update record";
		if( !empty($data) ){
			$id = $data['loan_id'];
			$date_paid   = $data['date_paid'];
			$amount_paid = $data['amount_paid'];
			$expected_amount = $data['expected_amount'];
			$expected_date   = $data['expected_date'];
			$l = G_Employee_Loan_Finder::findById($id);
			if( $l ){				
				$data = array("amount_paid" => $amount_paid, "loan_payment_scheduled_date" => $expected_date, "amount_to_pay" => $expected_amount, "date_paid" => $date_paid);
				$json = $l->addPaymentSchedule($data);
				$l->updateAmountPaid();		
			}
		}

		echo json_encode($json);
	}

	function _update_loan_payment_schedule_status(){
		$id     = $_POST['loan_id'];
		$status = $_POST['status'];
		$json['is_success'] = false;
		$json['message']    = "Cannot update record";
		if( $id > 0 ){
			$l = G_Employee_Loan_Payment_History_Finder::findById($id);
			if( $l ){
				if( $status == 'paid' ){
					$l->setAsLock();
				}else{
					$l->setAsUnlock();
				}

				$json = $l->update();
			}			
		}
		
		echo json_encode($json);
	}
	
	function _load_delete_loan_payment() {
		if(!empty($_POST)) {			
			$geld = G_Employee_Loan_Details_Finder::findById(Utilities::decrypt($_POST['e_id']));
			if($geld) {			
				$loan_id = $geld->getLoanId();													
				$geld->delete();		
				
				//Update Loan Balance
					$gel            = G_Employee_Loan_Finder::findById($loan_id);
					if($gel){
						$total_payments = G_Employee_Loan_Helper::getTotalLoanPayments($gel);
						$new_balance    = $gel->getLoanAmount() - $total_payments;
						$gel->setBalance($new_balance);
						$gel->save();
					}
				//		
				$json['e_id']		= Utilities::encrypt($gel->getId());
				$json['balance']    = number_format($new_balance,2,".",",");
				$json['is_success'] = 1;
				$json['message']    = 'Record was successfully deleted';		
			}
		}else{$json['is_success'] = 0;}
		
		echo json_encode($json);
	}
	
	function _load_delete_loan_payment_breakdown() {
		if(!empty($_POST)) {			
			$gelpb = G_Employee_Loan_Payment_Breakdown_Finder::findById(Utilities::decrypt($_POST['e_id']));
			if($gelpb) {			
				$loan_id = $gelpb->getLoanId();	
				//Add to amount due and update loan current balance
					$amount_paid = $gelpb->getAmountPaid();
					$geld = G_Employee_Loan_Details_Finder::findById($gelpb->getLoanPaymentId());
					$new_total = $geld->getAmount() + $amount_paid;
					$geld->setAmountPaid($geld->getAmountPaid() - $amount_paid);
					$geld->setAmount($new_total);
					$geld->save();
				//												
				$gelpb->delete();		
				
				//Update Loan Balance
					$gel = G_Employee_Loan_Finder::findById($loan_id);
					if($gel){
						$total_payments = G_Employee_Loan_Helper::getTotalLoanPayments($gel);
						$interest 	   = ceil($gel->getLoanAmount() * ($gel->getNoOfInstallment() * ($gel->getInterestRate()/100)));  
						//computation with interest
						$new_balance_amount = $gel->getLoanAmount() + $interest;
						//$new_balance    = $gel->getLoanAmount() - $total_payments;
						$new_balance    = $new_balance_amount - $total_payments;
						$gel->setBalance($new_balance);
						$gel->save();
					}
				//		
				$json['e_id']		    = Utilities::encrypt($geld->getId());
				$json['e_loan_id']		= Utilities::encrypt($geld->getLoanId());
				$json['period_balance'] = number_format($new_total,2,".",",");
				$json['balance']        = number_format($new_balance,2,".",",");
				$json['is_success']     = 1;
				$json['message']        = 'Record was successfully deleted';		
			}
		}else{$json['is_success'] = 0;}
		
		echo json_encode($json);
	}
	
	function _load_archive_loan() {
		if(!empty($_POST)) {
			$gel = G_Employee_Loan_Finder::findById(Utilities::decrypt($_POST['e_id']));
			if($gel) {				
				$json['is_success'] = 1;
				$gel->setIsArchive(G_Employee_Loan::YES);
				$gel->save();							
			}
		}else{$json['is_success'] = 0;}
		
		echo json_encode($json);
	}
	
	function _load_restore_archive_loan() {
		if(!empty($_POST)) {
			$gel = G_Employee_Loan_Finder::findById(Utilities::decrypt($_POST['e_id']));
			if($gel) {				
				$json['is_success'] = 1;
				$gel->setIsArchive(G_Employee_Loan::NO);
				$gel->save();							
			}
		}else{$json['is_success'] = 0;}
		
		echo json_encode($json);
	}
	
	function loan_type_with_selected_action() 
	{
		if(!empty($_POST)) {
			$mArray = $_POST['dtChk'];
			foreach($mArray as $key => $value):
			$d++;
			if($_POST['chkAction'] == 'loan_type_archive'){	
				$glt = G_Loan_Type_Finder::findById($value);					
				$glt->setIsArchive(G_Loan_Type::YES);
				$glt->save();								

				$json['message']    = 'Successfully archived ' . $d . ' record(s)';	
				$json['is_success'] = 1;	
									
			}elseif($_POST['chkActionLoanType'] == 'loan_type_restore'){
				$glt = G_Loan_Type_Finder::findById($value);	
				$glt->setIsArchive(G_Employee_Change_Schedule_Request::NO);
				$glt->save();				
				
				$json['message']    = 'Successfully restored ' . $d . ' archived record(s)';	
				$json['is_success'] = 1;							
			}else {}		
			
			endforeach;
		}else{
			$json['is_success'] = 1;
		}
		echo json_encode($json);
	}
	
	function loan_deduction_type_with_selected_action() 
	{
		if(!empty($_POST)) {
			$mArray = $_POST['dtChk'];
			foreach($mArray as $key => $value):
			$d++;
			if($_POST['chkAction'] == 'loan_deduction_type_archive'){	
				$gldt = G_Loan_Deduction_Type_Finder::findById($value);					
				$gldt->setIsArchive(G_Loan_Deduction_Type::YES);
				$gldt->save();								

				$json['message']    = 'Successfully archived ' . $d . ' record(s)';	
				$json['is_success'] = 1;	
									
			}elseif($_POST['chkActionLoanDeductionType'] == 'loan_deduction_type_restore'){
				$gldt = G_Loan_Deduction_Type_Finder::findById($value);	
				$gldt->setIsArchive(G_Loan_Deduction_Type::NO);
				$gldt->save();				
				
				$json['message']    = 'Successfully restored ' . $d . ' archived record(s)';	
				$json['is_success'] = 1;							
			}else {}		
			
			endforeach;
		}else{
			$json['is_success'] = 1;
		}
		echo json_encode($json);
	}
	
	function loan_with_selected_action() 
	{
		if(!empty($_POST)) {
			$mArray = $_POST['dtChk'];
			foreach($mArray as $key => $value):
			$d++;
			if($_POST['chkAction'] == 'loan_archive'){	
				$geld = G_Employee_Loan_Finder::findById($value);	

				if($geld){
					$employee_id = $geld->getEmployeeId();
					$emp_det = G_Employee_Helper::findByEmployeeId($employee_id);
					$department = $emp_det['department'];
					$position = $emp_det['position'];
					$employee_name = $geld->getEmployeeName();	
					$loan_amount = $geld->getLoanAmount();	
					$start_date = $geld->getStartDate();
					$end_date = $geld->getEndDate();
				}
				else{
					$employee_name = '';
					$department = '';
					$position = '';
					$start_date = '';
					$end_date = '';
					$loan_amount = '';	
				}

				$geld->setIsArchive(G_Employee_Loan::YES);
				$geld->save();								

				$json['message']    = 'Successfully archived ' . $d . ' record(s)';	
				$json['is_success'] = 1;	

				//General Reports / Shr Audit Trail
				$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'PAYROLL', ACTION_ARCHIVE, 'Selected Loan of ', $employee_name.' with the amount of '.$loan_amount, $start_date, $end_date, 1, $position, $department);
									
			}elseif($_POST['chkAction'] == 'loan_download'){				
				
				$json['message']    = 'Successfully restored ' . $d . ' archived record(s)';	
				$json['is_success'] = 1;							
			}elseif($_POST['chkAction'] == 'loan_restore') {
				$geld = G_Employee_Loan_Finder::findById($value);					
				$geld->setIsArchive(G_Employee_Loan::NO);
				$geld->save();								

				$json['message']    = 'Successfully archived ' . $d . ' record(s)';	
				$json['is_success'] = 1;	
			}else{}		
			
			endforeach;
		}else{
			$json['is_success'] = 1;
		}
		echo json_encode($json);
	}
	
	function loan_payment_with_selected_action() 
	{
		if(!empty($_POST)) {
			$mArray = $_POST['dtChk'];
			foreach($mArray as $key => $value):
			$d++;
			if($_POST['chkAction'] == 'delete_loan_payment'){	
				$geldt   = G_Employee_Loan_Details_Finder::findById($value);	
				if($geldt){
					$loan_id = $geldt->getLoanId();
					$geldt->delete();
					
					//Update Loan Balance
						$gel 			= G_Employee_Loan_Finder::findById($loan_id);
						$total_payments = G_Employee_Loan_Helper::getTotalLoanPayments($gel);
						$new_balance    = $gel->getLoanAmount() - $total_payments;
						$gel->setBalance($new_balance);
						$gel->save();
						
						$json['e_id']   = Utilities::encrypt($gel->getId());
						$json['balance']= $new_balance;
					//									
				}
				
				$json['message']    = 'Successfully deleted ' . $d . ' record(s)';	
				$json['is_success'] = 1;	
									
			}else {}		
			
			endforeach;
		}else{
			$json['is_success'] = 1;
		}
		echo json_encode($json);
	}	
	
	function _insert_new_loan()
	{
		Utilities::verifyFormToken($_POST['token']);	

		$json['is_success'] = 0;
		$json['message']    = 'Cannot save record';
		$data = $_POST;		
		
		if( !empty($data) ){
			$employee_id = Utilities::decrypt($data['employee_id']);
			$loan_id     = $data['loan_type_id'];	
			$company_id  = Utilities::decrypt($this->company_structure_id);
			$interest_rate  = $data['interest_rate'];
			
			$lt = new G_Loan_Deduction_Type();
			$government_loan_ids = $lt->government_loan_type_ids;

			if( in_array($loan_id, $government_loan_ids) ){
				$amount 		= $data['loan_amount'];
				$deduction_type = $data['government_deduction_frequency'];
				$period = $data['government_start_date'];
				$deduction_per_period = $data['government_deduction_amount'];
				$months_to_pay  = $data['government_months_to_pay'];
				$loan = new G_Employee_Loan();
				$loan->setDateCreated($this->c_date);
				$loan->setCompanyStructureid($company_id);		
				$loan->setEmployeeId($employee_id);
				$loan->setMonthsToPay($months_to_pay);
				$loan->setDeductionPerPeriod($deduction_per_period);
				$loan->setLoanTypeId($loan_id);
				$loan->setInterestRate(0);
				$loan->setLoanAmount($amount);					
				$loan->setCutoffPeriod($period);		
				$loan->setDeductionType($deduction_type);			
				$loan->setAsPending();
				$loan->setAsLock();
				$loan->setAsIsNotArchive();					
				$json = $loan->createGovernmentLoanDetails()->createGovernmentLoanSchedule()->saveEmployeeLoanDetails()->saveEmployeeLoanSchedules();

				//General Reports / Shr Audit Trail
				$glt = G_Loan_Type_Finder::findById($loan_id);
				$shr_emp = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($data['employee_id']));
				$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
				$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'PAYROLL', ACTION_ADD, ' New ', $glt->loan_type, $emp_name, $data['company_loan_amount'], 1, $shr_emp['position'], $shr_emp['department']);

			}else{
				$amount 		= $data['company_loan_amount'];
				$deduction_type = $data['deduction_frequency'];
				$period = $data['start_date'];
				$months_to_pay = $data['months_to_pay'];
				$loan = new G_Employee_Loan();
				$loan->setDateCreated($this->c_date);
				$loan->setCompanyStructureid($company_id);		
				$loan->setEmployeeId($employee_id);
				$loan->setLoanTypeId($loan_id);
				$loan->setLoanAmount($amount);		
				$loan->setMonthsToPay($months_to_pay);		
				$loan->setInterestRate($interest_rate);
				$loan->setCutoffPeriod($period);		
				$loan->setDeductionType($deduction_type);			
				$loan->setAsPending();
				$loan->setAsLock();
				$loan->setAsIsNotArchive();			
				$json = $loan->createLoanDetails()->createLoanSchedule()->saveEmployeeLoanDetails()->saveEmployeeLoanSchedules();

				//General Reports / Shr Audit Trail
				$glt = G_Loan_Type_Finder::findById($loan_id);
				$shr_emp = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($data['employee_id']));
				$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
				$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'PAYROLL', ACTION_ADD, ' New ', $glt->loan_type, $emp_name, $data['company_loan_amount'], 1, $shr_emp['position'], $shr_emp['department']);

			}
		}
		
		$json['token'] = Utilities::createFormToken();
		echo json_encode($json);
	}
	
	function _update_loan()
	{
		Utilities::verifyFormToken($_POST['token']);
		if($_POST){			
			if($_POST['employee_loan_id']){			
			$gel = G_Employee_Loan_Finder::findById(Utilities::decrypt($_POST['employee_loan_id']));	
			$prev_installment       = $gel->getNoOfInstallment();	
			$prev_type_of_deduction = $gel->getTypeOfDeductionId();
				
			$gel->setCompanyStructureId(Utilities::decrypt($this->company_structure_id));
			$gel->setEmployeeId(Utilities::decrypt($_POST['employee_id']));
			$gel->setTypeOfLoanId(Utilities::decrypt($_POST['loan_type_id']));	
			$gel->setInterestRate($_POST['interest_rate']);					
			$gel->setBalance(str_replace(",","",$_POST['loan_amount']));	
			$gel->setLoanAmount(str_replace(",","",$_POST['loan_amount']));				
			$gel->setTypeOfDeductionId(Utilities::decrypt($_POST['type_of_deduction_id']));
			$gel->setNoOfInstallment($_POST['no_of_installment']);					
			$gel->setStartDate($_POST['start_date']);				
			$gel->setEndDate($_POST['end_date']);				
			$gel->setStatus($_POST['status']);				
			$gel->setIsArchive(G_Employee_Loan::NO);							
			$loan_id = $gel->save();		
			
			//Insert loan breakdown
				$gel  = G_Employee_Loan_Finder::findById(Utilities::decrypt($_POST['employee_loan_id']));
				//Reset Payment - delete all unpaid 
					//$geld = new G_Employee_Loan_Details();
					//$geld->deleteAllUnpaidPaymentByLoanId($gel);
				//
				
				if($prev_installment != $_POST['no_of_installment'] || $prev_type_of_deduction != Utilities::decrypt($_POST['type_of_deduction_id'])){
				//Reset Payment
					$geld = new G_Employee_Loan_Details();
					$geld->deleteAllByLoanId($gel);
					
					//Delete Breakdown
					$gelbp = new G_Employee_Loan_Payment_Breakdown();
					$gelbp->deleteAllByLoanId($gel);
					
					$gel->saveLoanPaymentBreakDown();
				//
				}
			//
			
			$json['eid']		= $_POST['employee_id'];
			$json['is_success'] = 1;
			$json['message']    = 'Record was successfully saved.';
			}else{
				$json['is_success'] = 0;
				$json['message']    = 'Error in sql';
			}
		}else {
			$json['is_success'] = 0;
			$json['message']    = 'Error in sql';
		}
		
		echo json_encode($json);
	}
	
	function _insert_new_loan_payment()
	{
		Utilities::verifyFormToken($_POST['token']);
		if($_POST['loan_id']){
			$gel = G_Employee_Loan_Finder::findById(Utilities::decrypt($_POST['loan_id']));
			if($gel){
				if($_POST['employee_loan_payment_id']){
					$geld 	     = G_Employee_Loan_Details_Finder::findById(Utilities::decrypt($_POST['employee_loan_payment_id']));					
				}else{
					$geld = new G_Employee_Loan_Details();				
					$geld->setDateCreated($this->c_date);						
				}
				
				//Validate if amount does not exceed balance
				if($geld->getAmount() >= $_POST['amount_paid']){
					
					//Save Payment Breakdown
					$gelpb = new G_Employee_Loan_Payment_Breakdown();					
					$gelpb->setLoanId(Utilities::decrypt($_POST['loan_id']));
					$gelpb->setEmployeeId($gel->getEmployeeId());
					$gelpb->setLoanPaymentId(Utilities::decrypt($_POST['employee_loan_payment_id']));					
					$gelpb->setReferenceNumber($_POST['reference_number']);					
					$gelpb->setAmountPaid($_POST['amount_paid']);					
					$gelpb->setDatePaid(date('Y-m-d'));								
					$gelpb->setRemarks($_POST['remarks']);	
					$gelpb->save();								
					//
					
					//Get Total Amount Paid and update period due amount
						$total_payments_made  = G_Employee_Loan_Payment_Breakdown_Helper::sumTotalPaymentsByLoanPaymentId($geld);
						//echo 'payment : ' . $total_payments_made;
						$new_period_due_amount= $geld->getAmount() - $_POST['amount_paid'];
						//echo ' new amount : ' . $new_period_due_amount;
					//
					
					$geld->setCompanyStructureId(Utilities::decrypt($this->company_structure_id));
					$geld->setEmployeeId($gel->getEmployeeId());
					$geld->setLoanId($gel->getId());	
					//$geld->setDateOfPayment($_POST['date_of_payment']);				
					$geld->setAmount($new_period_due_amount);	
					$geld->setAmountPaid($total_payments_made);
					
					if($total_payments_made >= $geld->getAmount()){
						$geld->setIsPaid(G_Employee_Loan_Details::YES);
					}else{
						$geld->setIsPaid(G_Employee_Loan_Details::NO);
					}
					if(empty($_POST['remarks'])){
						$geld->setRemarks($_POST['loan_remarks']);					
					}
					$geld->save();	
					
					//Update Loan Balance
						//$total_payments = G_Employee_Loan_Helper::getTotalLoanPayments($gel);
						$total_loan_payments_made = G_Employee_Loan_Details_Helper::sumTotalLoanPaymentsByLoanId($gel);
						$interest   = ceil($gel->getLoanAmount() * ($gel->getNoOfInstallment() * ($gel->getInterestRate()/100)));  
						//balance with interest
						$new_balance_amount = $gel->getLoanAmount() + $interest;
						//$new_balance = $gel->getLoanAmount() - $total_loan_payments_made;
						$new_balance = $new_balance_amount - $total_loan_payments_made;
						$gel->setBalance($new_balance);
						$gel->save();
					//		
										
					$json['balance']    = number_format($new_balance,2,".",",");
					$json['is_success'] = 1;
					$json['message']    = 'Record was successfully saved.';
				}else{
					$json['is_success'] = 2;
					$json['message']    = 'Error : Amount entered is greater than the remaining balance.';			
				}
				
			}else{
				$json['is_success'] = 0;
				$json['message']    = 'Error in sql';
			}
		}else {
			$json['is_success'] = 0;
			$json['message']    = 'Error in sql';
		}
		
		echo json_encode($json);
	}
	
	function _insert_new_loan_type()
	{
		Utilities::verifyFormToken($_POST['token']);
		if($_POST){
			if($_POST['loan_type_id']){
				$glt = G_Loan_Type_Finder::findById(Utilities::decrypt($_POST['loan_type_id']));	
			}else{
				$glt = new G_Loan_Type();
				$glt->setDateCreated($this->c_date);						
			}
			
			$glt->setCompanyStructureId(Utilities::decrypt($this->company_structure_id));
			$glt->setLoanType($_POST['loan_type']);
			$glt->setIsArchive(G_Loan_Type::NO);								
			$glt->save();
			
			$json['is_success'] = 1;
			$json['message']    = 'Record was successfully saved.';
		}else {
			$json['is_success'] = 0;
			$json['message']    = 'Error in sql';
		}
		
		echo json_encode($json);
	}
	
	function _insert_new_loan_deduction_type()
	{
		Utilities::verifyFormToken($_POST['token']);
		if($_POST){
			if($_POST['loan_deduction_type_id']){
				$gldt = G_Loan_Deduction_Type_Finder::findById(Utilities::decrypt($_POST['loan_deduction_type_id']));	
			}else{
				$gldt = new G_Loan_Deduction_Type();
				$gldt->setDateCreated($this->c_date);						
			}
			
			$gldt->setCompanyStructureId(Utilities::decrypt($this->company_structure_id));
			$gldt->setDeductionType($_POST['deduction_type']);
			$gldt->setIsArchive(G_Loan_Deduction_Type::NO);								
			$gldt->save();
			
			$json['is_success'] = 1;
			$json['message']    = 'Record was successfully saved.';
		}else {
			$json['is_success'] = 0;
			$json['message']    = 'Error in sql';
		}
		
		echo json_encode($json);
	}
	
	function _load_server_loan_list_dt() 
	{	
		$permission_action = $this->validatePermission(G_Sprint_Modules::PAYROLL,'earnings_deductions','loans');
		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_LOAN);
		$dt->setSQL("
			SELECT l.id, CONCAT(e.lastname, ', ', e.firstname)AS employee_name,
				lt.loan_type, CONCAT(l.interest_rate, '%')AS interest_rate, FORMAT(COALESCE(l.loan_amount,0),2)AS loan_amount, FORMAT(COALESCE(l.total_amount_to_pay,0),2)AS total_amount_to_pay, FORMAT(COALESCE(l.amount_paid,0),2)AS amount_paid, l.deduction_type,
				FORMAT(
					COALESCE(
						l.total_amount_to_pay - l.amount_paid
					,0)
				,2)AS balance, l.status
			FROM " . G_EMPLOYEE_LOAN . " l 
			  LEFT JOIN " . EMPLOYEE . " e 
			     ON l.employee_id = e.id
			  LEFT JOIN " . G_LOAN_TYPE . " lt
				 ON l.loan_type_id = lt.id
		");				
		$dt->setCountSQL("SELECT COUNT(l.id) as c FROM " . G_EMPLOYEE_LOAN . " l LEFT JOIN " . EMPLOYEE . " e ON l.employee_id = e.id LEFT JOIN " . G_LOAN_TYPE . " lt ON l.loan_type_id = lt.id");				
		$dt->setCondition('l.is_archive =' . Model::safeSql(G_Employee_Loan::NO));	
		$dt->setColumns('employee_name,loan_type,deduction_type,loan_amount,total_amount_to_pay,amount_paid,balance');            				
		$dt->setOrder('ASC');
		$dt->setPreDefineSearch(
		   array(
			"employee_name" => "e.firstname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' OR e.lastname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'",	
			"balance" => " FORMAT(l.total_amount_to_pay - l.amount_paid,2) =" . Model::safeSql(addslashes($_REQUEST['sSearch']))
		   )
		);	
		$dt->setSort(0);
		if($permission_action == Sprint_Modules::PERMISSION_02)	{						
			$dt->setCustomColumn(
				 array(		
					1 => '<div class=\"i_container\"><input type=\"checkbox\" name=\"dtChk[]\" class=\"dt-chkbox\" value=\"id\"></div>',		
					2 => '<div class=\"btn-group pull-right\"><a class=\"btn dropdown-toggle\" href=\"#\">Action <span class=\"caret\"></span></a><ul class=\"dropdown-menu\"><li><a href=\"' . url("cd_members/paid?eid=id") . '\"><i class=\"icon-ok\"></i> Paid</a></li></ul></div>'
			));
		}

		echo $dt->constructDataTableRightTools();
	}
	
	function _load_server_loan_archive_list_dt() 
	{
		$permission_action = $this->validatePermission(G_Sprint_Modules::PAYROLL,'earnings_deductions','loans');
		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_LOAN);
		$dt->setSQL("
			SELECT l.id, CONCAT(e.lastname, ', ', e.firstname)AS employee_name,
				lt.loan_type, CONCAT(l.interest_rate, '%')AS interest_rate, FORMAT(COALESCE(l.loan_amount,0),2)AS loan_amount, FORMAT(COALESCE(l.total_amount_to_pay,0),2)AS total_amount_to_pay, FORMAT(COALESCE(l.amount_paid,0),2)AS amount_paid, l.deduction_type,
				FORMAT(
					COALESCE(
						l.total_amount_to_pay - l.amount_paid
					,0)
				,2)AS balance, l.status
			FROM " . G_EMPLOYEE_LOAN . " l 
			  LEFT JOIN " . EMPLOYEE . " e 
			     ON l.employee_id = e.id
			  LEFT JOIN " . G_LOAN_TYPE . " lt
				 ON l.loan_type_id = lt.id
		");				
		$dt->setCountSQL("SELECT COUNT(l.id) as c FROM " . G_EMPLOYEE_LOAN . " l LEFT JOIN " . EMPLOYEE . " e ON l.employee_id = e.id LEFT JOIN " . G_LOAN_TYPE . " lt ON l.loan_type_id = lt.id");				
		$dt->setCondition('l.is_archive =' . Model::safeSql(G_Employee_Loan::YES));	
		$dt->setColumns('employee_name,loan_type,deduction_type,loan_amount,total_amount_to_pay,amount_paid,balance');            				
		$dt->setOrder('ASC');
		$dt->setPreDefineSearch(
		   array(
			"employee_name" => "e.firstname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' OR e.lastname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'",			
			"balance" => " FORMAT(es.total_in_amount - es.total_out_amount,2) =" . Model::safeSql(addslashes($_REQUEST['sSearch']))
		   )
		);	
		$dt->setSort(0);
		if($permission_action == Sprint_Modules::PERMISSION_02)	{						
			$dt->setCustomColumn(
				 array(		
					1 => '<div class=\"i_container\"><input onclick=\"javascript:archivesEnableDisableWithSelected(1);\" type=\"checkbox\" name=\"dtChk[]\" class=\"dt-chkbox\" value=\"id\"></div>',		
					2 => '<div class=\"btn-group pull-right\"><a class=\"btn dropdown-toggle\" href=\"#\">Action <span class=\"caret\"></span></a><ul class=\"dropdown-menu\"><li><a href=\"' . url("cd_members/paid?eid=id") . '\"><i class=\"icon-ok\"></i> Paid</a></li></ul></div>'
			));
		}

		//General Reports / Shr Audit Trail
		$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'PAYROLL', ACTION_RESTORE, 'Selected Loan ', '', '', '', 1, '', '');
		
		echo $dt->constructDataTableRightTools();
	}
	
	function _load_server_employee_loan_list_dt() 
	{

		Utilities::ajaxRequest();

		$employee_id = Utilities::decrypt($_GET['eid']);

		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_LOAN);
		$dt->setSQL("
			SELECT lt.loan_type, l.months_to_pay, l.loan_amount, l.total_amount_to_pay, l.amount_paid
			FROM " . G_EMPLOYEE_LOAN . " l 	
				LEFT JOIN " . G_LOAN_TYPE . " lt ON l.loan_type_id = lt.id					
		");				
		$dt->setCountSQL('SELECT COUNT(l.id) as c FROM ' . G_EMPLOYEE_LOAN . " l LEFT JOIN " . G_LOAN_TYPE . " lt ON l.loan_type_id = lt.id");	
		$dt->setCondition('l.employee_id = ' . Model::safeSql($employee_id));		     			
		$dt->setColumns('loan_type,months_to_pay,loan_amount,total_amount_to_pay,amount_paid');            				
		/*$dt->setPreDefineSearch(
		   array(		   
			"hidden_field" => " s.date_posted LIKE '%" . $_REQUEST['sSearch'] . "%'",
			"date_posted" => " DATE_FORMAT(s.date_posted,'%M %d, %Y') LIKE '%" . $_REQUEST['sSearch'] . "%'"			
		   )
		);		*/
		$dt->setOrder('ASC');
		$dt->setSort(0);							
		$dt->setCustomColumn(
			 array(		
				1 => '<div class=\"i_container\"><input type=\"checkbox\" name=\"dtChk[]\" value=\"id\"></div>',		
				2 => '<div class=\"btn-group pull-right\"><a class=\"btn dropdown-toggle\" href=\"#\">Action <span class=\"caret\"></span></a><ul class=\"dropdown-menu\"><li><a href=\"javascript:void(0);\" class=\"btn-transfer-to-bank\" id=btnid><i class=\"icon-ok\"></i> Edit</a></li><li><a href=\"javascript:void(0);\" class=\"btn-delete-mobile-number\" id=btnid><i class=\"icon-remove\"></i> Delete</a></li></ul></div>'
		));
		
		echo $dt->constructDataTableRightTools();
	}
	
	function _load_server_loan_type_list_dt() 
	{
		$permission_action = $this->validatePermission(G_Sprint_Modules::PAYROLL,'earnings_deductions','loans');	

		Utilities::ajaxRequest();
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_LOAN_TYPE);				
		$dt->setCondition(' is_archive = "' . G_Loan_Type::NO . '" AND company_structure_id="' . Utilities::decrypt($this->company_structure_id) . '"');
		$dt->setColumns('loan_type,id');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(2);

		if($permission_action == Sprint_Modules::PERMISSION_02)	{
			$dt->setCustomColumn(	
			array(
				'1' => '<ul class=\"dt_icons\"><li>&nbsp;<input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></li><li><a title=\"Edit\" id=\"edit\" class=\"ui-icon ui-icon-pencil g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:editLoanTypeForm(\'pkey\');\"></a></li><li><a title=\"Send to Archive\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:archiveLoanType(\'pkey\')\"></ul>')
				//'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li>&nbsp;<input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></li><li><a title=\"Edit\" id=\"edit\" class=\"ui-icon ui-icon-pencil g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:editLoanTypeForm(\'pkey\');\"></a></li><li><a title=\"Send to Archive\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:archiveLoanType(\'pkey\')\"></ul></div>')
			);
		}

		/*
		$dt->setCustomColumn(	
		array(		
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></li></ul></div><section class=\"main\"><div class=\"wrapper-demo\"><div id=\"dd\" class=\"wrapper-dropdown-5\" tabindex=\"1\">Action<ul class=\"dropdown\"><li><a href=\"javascript:void(0);\" onclick=\"javascript:editLoanTypeForm(\'e_id\');\"><i class=\"icon-pencil\"></i>Edit</a></li><li><a href=\"javascript:void(0);\" onclick=\"javascript:archiveLoanType(\'e_id\')\"><i class=\"icon-trash\"></i>Archive</a></li></ul></div></div></section>'));
		*/
		
		echo $dt->constructDataTable();
	}
	
	function _load_server_loan_type_archive_list_dt() 
	{
		$permission_action = $this->validatePermission(G_Sprint_Modules::PAYROLL,'earnings_deductions','loans');
		Utilities::ajaxRequest();
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_LOAN_TYPE);				
		$dt->setCondition(' is_archive = "' . G_Loan_Type::YES . '" AND company_structure_id="' . Utilities::decrypt($this->company_structure_id) . '"');
		$dt->setColumns('loan_type');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(1);
		if($permission_action == Sprint_Modules::PERMISSION_02)	{
			$dt->setCustomColumn(	
			array(
			'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li>&nbsp;<input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:archivesEnableDisableWithSelected(2);\" value=\"id\"></li><li><a title=\"Restore Archived\" id=\"delete\" class=\"ui-icon ui-icon-refresh g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:restoreArchiveLoanType(\'e_id\')\"></ul></div>'));
		}
		echo $dt->constructDataTable();
	}
	
	function _load_server_loan_details_dt() 
	{
		Utilities::ajaxRequest();
		$ld_id = Utilities::decrypt($_GET['eid']);		
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_LOAN_DETAILS);				
		$dt->setCondition('loan_id=' . $ld_id);		
		$dt->setColumns('date_of_payment,amount,is_paid,remarks');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(1);
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li>&nbsp;<input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></li><li><a title=\"Edit\" id=\"edit\" class=\"ui-icon ui-icon-pencil g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:editLoanPaymentForm(\'e_id\');\"></a></li><li><a title=\"Delete\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:deleteLoanPayment(\'e_id\')\"></ul></div>'));
		echo $dt->constructDataTable();
	}
	
	function _load_server_employee_loan_details_dt() 
	{
		Utilities::ajaxRequest();
		$ld_id = Utilities::decrypt($_GET['eid']);		
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_LOAN_DETAILS);				
		$dt->setCondition('loan_id=' . $ld_id);
		$dt->setColumns('date_of_payment,amount,is_paid,remarks');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(0);		
		echo $dt->constructDataTable();
	}
	
	function _load_server_loan_deduction_type_list_dt() 
	{
		Utilities::ajaxRequest();
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_LOAN_DEDUCTION_TYPE);				
		$dt->setCondition(' is_archive = "' . G_Loan_Deduction_Type::NO . '" AND company_structure_id="' . Utilities::decrypt($this->company_structure_id) . '"');
		$dt->setColumns('deduction_type');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(1);
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li>&nbsp;<input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></li><li><a title=\"Edit\" id=\"edit\" class=\"ui-icon ui-icon-pencil g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:editLoanDeductionTypeForm(\'e_id\');\"></a></li><li><a title=\"Send to Archive\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:archiveLoanDeductionType(\'e_id\')\"></ul></div>'));
		echo $dt->constructDataTable();
	}
	
	function _load_server_loan_deduction_type_archive_list_dt() 
	{
		Utilities::ajaxRequest();
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_LOAN_DEDUCTION_TYPE);				
		$dt->setCondition(' is_archive = "' . G_Loan_Deduction_Type::YES . '" AND company_structure_id="' . Utilities::decrypt($this->company_structure_id) . '"');
		$dt->setColumns('deduction_type');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(1);
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li>&nbsp;<input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:archivesEnableDisableWithSelected(3);\" value=\"id\"></li><li><a title=\"Restore Archived\" id=\"delete\" class=\"ui-icon ui-icon-refresh g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:restoreLoanDeductionType(\'e_id\')\"></ul></div>'));
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
		$dt->setJoinFields(EMPLOYEE . ".id = jbh.employee_id LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " gsh ON " . EMPLOYEE . ".id = gsh.employee_id");
		
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
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><a title=\"Loan History\" id=\"delete\" class=\"ui-icon ui-icon-search g_icon\" href=\"' . url('loan/e_history?hid=id') . '\"></a></li></ul></div>'));
		echo $dt->constructDataTable();
	}

	function ajax_load_payroll_period_by_year_and_month()
	{
		$selected_year = $_GET['selected_year'];
		$selected_month = $_GET['selected_month'];
		$selected_frequency = $_GET['selected_frequency'];
		$is_government = $_GET['is_government'];

		$month_number = date("m", strtotime($selected_month));

		if( $selected_year == '' || $selected_year <= 0 ){
			$selected_year = date("Y");
		}		

		$selected_year = $selected_year;

		if ($selected_frequency == 2) {
			$c = G_Weekly_Cutoff_Period_Finder::findAllByMonthYear($selected_year, $month_number);
		}
		else {
			$c = G_Cutoff_Period_Finder::findAllByMonthYear($selected_year, $month_number);
		}

		if($is_government == "false" || $is_government == false){
			$is_government = 0;
		}else{
			$is_government = 1;
		}

		

		$this->var['cutoff_periods'] = $c;
		$this->var['is_government'] = $is_government;
		$this->view->noTemplate();
		$this->view->render('loan/form/_payroll_period_cutoffs.php',$this->var);
	}

	//**********************
	//new import loan functions

	//import loan forms

	 function ajax_import_loan() {
        $this->var['form_id'] = 'import_loan_form';
        $this->var['action'] = url('loan/_import_employee_loan');        
        $this->view->render('loan/form/import_loan_form.php', $this->var);
    }


    function ajax_import_government_loan() {
        $this->var['form_id'] = 'import_government_loan_form';
        $this->var['action'] = url('loan/_import_employee_government_loan');        
        $this->view->render('loan/form/import_government_loan_form.php', $this->var);
    }


    function _import_employee_government_loan(){

    	//ob_start();
        ini_set("memory_limit", "999M");
        set_time_limit(999999999999999999999);
        $file = $_FILES['file']['tmp_name'];

        $g = new G_Employee_Loan_Importer($file);
		$is_imported = $g->importGovtLoan();
		$errorDuplicate = $g->getErrorDuplicate();
		$errorColumn = $g->getColumnError();

			$return['is_imported'] = true;
       		$return['message'] = "( ".$is_imported.' ) Employee Loan have been successfully added.';
       		if(count($errorDuplicate) > 0){
       			 $return['message'] .= '<br>DUPLICATE ENTRY:';
       			foreach($errorDuplicate as $e){
       				$return['message'] .= '<br> Employee Code: '.$e.'- loan already Exist.';
       			}
       		}

       		if(count($errorColumn) > 0){
       			 $return['message'] .= '<br>Error Found:';
       			 foreach($errorColumn as $key => $ee){
       			 	$return['message'] .= '<br>IN ROW-'.$key.':';
       			 	foreach($ee as $eee){
       			 		$return['message'] .= '<br>'.$eee.'.';
       			 	}
       			 }

       		}

		
       /* ob_clean();
		ob_end_flush();*/
        echo json_encode($return);    	

    }




     function _import_employee_loan() {
		
		//ob_start();
        ini_set("memory_limit", "999M");
        set_time_limit(999999999999999999999);
        $file = $_FILES['file']['tmp_name'];

        $g = new G_Employee_Loan_Importer($file);
		$is_imported = $g->import();
		$errorDuplicate = $g->getErrorDuplicate();
		$errorColumn = $g->getColumnError();

			$return['is_imported'] = true;
       		$return['message'] = "( ".$is_imported.' ) Employee Loan have been successfully added.';
       		if(count($errorDuplicate) > 0){
       			 $return['message'] .= '<br>DUPLICATE ENTRY:';
       			foreach($errorDuplicate as $e){
       				$return['message'] .= '<br> Employee Code: '.$e.'- loan already Exist.';
       			}
       		}

       		if(count($errorColumn) > 0){
       			 $return['message'] .= '<br>Error Found:';
       			 foreach($errorColumn as $key => $ee){
       			 	$return['message'] .= '<br>IN ROW-'.$key.':';
       			 	foreach($ee as $eee){
       			 		$return['message'] .= '<br>'.$eee.'.';
       			 	}
       			 }

       		}

		
       /* ob_clean();
		ob_end_flush();*/
        echo json_encode($return);    	
    }


}
?>