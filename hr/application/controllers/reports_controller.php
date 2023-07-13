<?php
class Reports_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		
		//ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);		
		
		Loader::appMainScript('reports.js');
		Loader::appStyle('style.css');

		$this->sprintHdrMenu(G_Sprint_Modules::HR, 'reports');	
		
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainTextBoxList();
		//$this->validatePermission(G_Sprint_Modules::HR,'reports','');

		$this->company_structure_id = Utilities::decrypt($this->global_user_ecompany_structure_id);

		//general reports / audit trail

		$this->var['at_hr_download_url']  = url('reports/download_audit_trail_hr');
		$this->var['at_payroll_download_url']  = url('reports/download_audit_trail_payroll');
		$this->var['at_timekeeping_download_url']  = url('reports/download_audit_trail_timekeeping');

	}

	function index()
	{
		$this->var['page_title'] = 'Reports';
		$this->view->setTemplate('template_reports.php');
		$this->view->render('reports/index.php',$this->var);
        
	}
	// recruitment menu
	function recruitment()
	{
		
		Utilities::checkModulePackageAccess('hr','recruitment');
		$this->var['page_title'] = 'Reports';
		$this->var['sub_menu_recruitment'] = true;
		$this->view->setTemplate('template_reports.php');
		$this->view->render('reports/index.php',$this->var);		
	}
	
	//load applicant list
	function _load_applicant_list()
	{
		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Applicant List";
		$this->view->noTemplate();
		$this->view->render('reports/recruitment/applicant_list/index.php',$this->var);	
	}
	
	function download_loan()
	{
		$hid  = $_GET['hid'];
		$hash = $_GET['hash'];
		
		Utilities::verifyHash(Utilities::decrypt($hid),$hash);				
		$hid = Utilities::decrypt($hid);
		
		$gel     = G_Employee_Loan_Finder::findById($hid);
		$details = G_Employee_Loan_Details_Finder::findAllByLoanId($hid);
		$e       = G_Employee_Finder::findById($gel->getEmployeeId());
		$this->var['e']			= $e;
		$this->var['gel']       = $gel;
		$this->var['details']	= $details;
		$this->var['filename']  = 'deduction.xls';		
		$this->view->render('reports/loans/loan_download.php', $this->var);
	}
	
	function download_earnings()
	{
		$eid  = $_GET['hpid'];
		$from = $_GET['from'];
		$to   = $_GET['to'];
		
		//$esum['gtotal']         = G_Employee_Earnings_Helper::sumTotalIsNotArchiveEarningByCompanyStructureIdAndPayrollPeriodId(Utilities::decrypt($eid),$this->company_structure_id);
		//$esum['total_pendings'] = G_Employee_Earnings_Helper::sumTotalIsNotArchivePendingEarningByCompanyStructureIdAndPayrollPeriodId(Utilities::decrypt($eid),$this->company_structure_id);
		//$esum['total_approved'] = G_Employee_Earnings_Helper::sumTotalIsNotArchiveApproveEarningByCompanyStructureIdAndPayrollPeriodId(Utilities::decrypt($eid),$this->company_structure_id);
		
		$earnings = G_Employee_Earnings_Finder::findAllIsNotArchiveByPayrollPeriodIdAndCompanyStructureId(Utilities::decrypt($eid),Utilities::decrypt($this->company_structure_id));		
		
		$this->var['earnings']  = $earnings;
		$this->var['esum']      = $esum;
		$this->var['from']      = $from;
		$this->var['to']		= $to;
		$this->var['filename']  = 'earnings_' . $from . '_to_' . $to . '.xls';		
		$this->view->render('reports/earnings/earnings_download.php', $this->var);
	}
	
	function download_deductions()
	{
		$eid  = $_GET['hpid'];
		$from = $_GET['from'];
		$to   = $_GET['to'];
		
		$esum['gtotal']         = G_Employee_Deductions_Helper::sumTotalIsNotArchiveEarningByCompanyStructureIdAndPayrollPeriodId(Utilities::decrypt($eid),$this->company_structure_id);
		$esum['total_pendings'] = G_Employee_Deductions_Helper::sumTotalIsNotArchivePendingEarningByCompanyStructureIdAndPayrollPeriodId(Utilities::decrypt($eid),$this->company_structure_id);
		$esum['total_approved'] = G_Employee_Deductions_Helper::sumTotalIsNotArchiveApproveEarningByCompanyStructureIdAndPayrollPeriodId(Utilities::decrypt($eid),$this->company_structure_id);
		
		$deductions = G_Employee_Deductions_Finder::findAllIsNotArchiveByPayrollPeriodIdAndCompanyStructureId(Utilities::decrypt($eid),Utilities::decrypt($this->company_structure_id));		
		
		$this->var['deductions']  = $deductions;
		$this->var['esum']      = $esum;
		$this->var['from']      = $from;
		$this->var['to']		= $to;
		$this->var['filename']  = 'deductions_' . $from . '_to_' . $to . '.xls';		
		$this->view->render('reports/deductions/deductions_download.php', $this->var);
	}
	
	function download_ob_request()
	{
		$frequency_id  = $_GET['selected_frequency'];
		$eid  = $_GET['hpid'];
		$from = $_GET['from'];
		$to   = $_GET['to'];
		
		$requests = G_Employee_Official_Business_Request_Helper::getAllByPeriodAndCompanyStructureId($from,$to,$this->company_structure_id);		
		
		$this->var['total_pending']  = G_Employee_Official_Business_Request_Helper::countTotalRecordsByPeriodCompanyStructureIdPendingAndIsNotArchive($from,$to,$this->company_structure_id);
		$this->var['total_approved'] = G_Employee_Official_Business_Request_Helper::countTotalRecordsByPeriodCompanyStructureIdApprovedAndIsNotArchive($from,$to,$this->company_structure_id);
		$this->var['requests']       = $requests;		
		$this->var['from']           = $from;
		$this->var['to']		     = $to;
		$this->var['filename']       = 'ob_request_' . $from . '_to_' . $to . '.xls';		
		$this->view->render('reports/ob_request/ob_download.php', $this->var);
	}
	
	function download_undertime_request()
	{
		$eid  = $_GET['hpid'];
		$from = $_GET['from'];
		$to   = $_GET['to'];
		
		$requests = G_Employee_Undertime_Request_Helper::getAllByPeriodAndCompanyStructureId($from,$to,$this->company_structure_id);		
		
		$this->var['total_pending']  = G_Employee_Undertime_Request_Helper::countTotalRecordsByPeriodCompanyStructureIdPendingAndIsNotArchive($from,$to,$this->company_structure_id);
		$this->var['total_approved'] = G_Employee_Undertime_Request_Helper::countTotalRecordsByPeriodCompanyStructureIdApprovedAndIsNotArchive($from,$to,$this->company_structure_id);
		$this->var['requests']       = $requests;		
		$this->var['from']           = $from;
		$this->var['to']		     = $to;
		$this->var['filename']       = 'undertime_request_' . $from . '_to_' . $to . '.xls';		
		$this->view->render('reports/undertime/undertime_download.php', $this->var);
	}
	
	function download_applicant_list() {

		
		$search_by_date = "WHERE a.applied_date_time between '".$_POST['from']."' 
			AND '".$_POST['to']."' AND a.job_id=j.id ";
		
		
		if($_POST['search_field']=='all') {
			$search_field='';
			$search='';
			$query='';
		}else if($_POST['search_field']=='birthdate'){
			$search_field='a.birthdate';
			$search=$_POST['birthdate'];
			if($search_by_date=='') {
				$query = ($search=='')? '' : " WHERE ". $search_field." like '%".$search."%'";
			}else {
				$query = ($search=='')? '' :" AND ". $search_field." like '%".$search."%'";
			}
		}else {
			$search_field=$_POST['search_field'];
			$search=$_POST['search'];
			if($search_by_date=='') {
				$query = ($search=='')? '' : " WHERE ". $search_field." like '%".$search."%'";
			}else {
				$query = ($search=='')? '' : " AND ". $search_field." like '%".$search."%'";	
			}
		}
		
		$position_applied = ($_POST['position_applied']=='all')? '' : ' a.job_id ='.$_POST['position_applied'].'' ;
		if($search_by_date!='' || $query!='') {
				$applied= ($position_applied!='')? ' AND '. $position_applied : '';
		}else {
			if($_POST['position_applied']!='all'){
				$applied= ' WHERE '. $position_applied;
			}
				
		}
		
		$sql = "SELECT 
		j.title as applied_position,
		a.applied_date_time as date_applied,
		a.lastname, 
		a.firstname, 
		a.middlename,  
		a.extension_name,
		a.birthdate,
		a.gender,
		a.marital_status,
		a.address,
		a.city,
		a.province,
		a.email_address,
		a.home_telephone,
		a.mobile,
		a.application_status_id
		FROM g_applicant a,
		g_job j
		".$search_by_date."
		".$query." 
		".$applied."
		ORDER BY a.applied_date_time 
		";
		
		$rec = Model::runSql($sql,true);
				
		$this->var['data'] = $rec;

		$this->view->render('reports/recruitment/applicant_list/excel_download.php', $this->var);
	}
	
	//load applicant by schedule
	function _load_applicant_by_schedule()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Applicant By Schedule";
		$this->view->noTemplate();
		$this->view->render('reports/recruitment/applicant_by_schedule/index.php',$this->var);	
	}
	
	function download_applicant_by_schedule()
	{
	//	echo "<pre>";
		//print_r($_POST);
		$from = $_POST['from_field'];
		$to = $_POST['to_field'];
		$position_applied = $_POST['position_applied'];
		
		$position_query = ($position_applied!='all') ? ' AND a.job_id='.$position_applied : '' ;
		$sql = "SELECT 	
				e.id,
				e.company_structure_id,
				
				e.applicant_id,
				CONCAT(a.lastname,', ' , a.firstname) as applicant_name,
				e.date_time_event,
				e.event_type,
				e.notes,
				e.date_time_created,
				CONCAT(emp.lastname, ', ', emp.firstname) as hiring_manager,
				
				a.application_status_id,
				j.title as position_applied
				
				FROM g_job_application_event e
				LEFT JOIN g_applicant a ON a.id=e.applicant_id
				LEFT JOIN g_employee emp ON emp.id=e.hiring_manager_id
				LEFT JOIN g_job j ON j.id=a.job_id
				WHERE e.date_time_event between ".Model::safeSql($from)." AND ".Model::safeSql($to)."
				AND e.event_type>0
				".$position_query."
				
				GROUP BY e.id
				ORDER BY e.date_time_event 
				
		";

		$rec = Model::runSql($sql,true);

		$this->var['data'] = $rec;

		$this->view->render('reports/recruitment/applicant_by_schedule/excel_download.php', $this->var);
	}
	
	//load applicant education training
	function _load_applicants_education_training()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Applicants Education Training";
		$this->view->noTemplate();
		$this->view->render('reports/recruitment/applicants_education_training/index.php',$this->var);	
	}
	
	function download_applicants_education_training()
	{
	
		$sql = "SELECT 	e.id,a.firstname,
						a.lastname,
						a.middlename, 
						a.extension_name,
						a.applied_date_time,
						e.hiring_manager,
						e.date_time_event,
						e.event_type,
						e.application_status_id,
						e.notes,
						j.title as position_applied
				
				FROM g_applicant a, g_job_application_event e, g_job j
				".$search_by_date."
				".$query." 
				".$applied."
				AND j.id=a.job_id
				GROUP BY e.id
				ORDER BY e.date_time_event 
				
		";
	//	echo $sql;
		$rec = Model::runSql($sql,true);
		//echo "<pre>";
		//print_r($rec);
		$this->var['data'] = $rec;

		$this->view->render('reports/recruitment/applicants_education_training/excel_download.php', $this->var);
	}
	
	//load applications received
	function _load_applications_received()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Applications Received";
		$this->view->noTemplate();
		$this->view->render('reports/recruitment/applications_received/index.php',$this->var);	
	}
	
	function download_applications_received()
	{
		
		$sql = "SELECT 	e.id,a.firstname,
						a.lastname,
						a.middlename, 
						a.extension_name,
						a.applied_date_time,
						e.hiring_manager,
						e.date_time_event,
						e.event_type,
						e.application_status_id,
						e.notes,
						j.title as position_applied
				
				FROM g_applicant a, g_job_application_event e, g_job j
				".$search_by_date."
				".$query." 
				".$applied."
				AND j.id=a.job_id
				GROUP BY e.id
				ORDER BY e.date_time_event 
				
		";
	//	echo $sql;
		$rec = Model::runSql($sql,true);
		//echo "<pre>";
		//print_r($rec);
		$this->var['data'] = $rec;

		$this->view->render('reports/recruitment/applications_received/excel_download.php', $this->var);
	}
	
	//load applicants_statistics
	function _load_applicants_statistics()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Applicant Statistics";
		$this->view->noTemplate();
		$this->view->render('reports/recruitment/applicant_statistics/index.php',$this->var);	
	}
	
	function download_applicants_statistics()
	{
		//print_r($_POST);
		$year = $_POST['year'];
		$search = ($_POST['month']==0)? '' : 'AND MONTH( date_time_event )='.$_POST['month'] ;
		$sql = "SELECT YEAR(date_time_event) as year, MONTH( date_time_event ) as month , 
				SUM( IF( event_type =0, 1, 0 ) ) AS application_submitted, 
				SUM( IF( event_type =5, 1, 0 ) ) AS hired, 
				SUM( IF( event_type =4 or event_type=3, 1, 0 ) ) AS  declined
				FROM g_job_application_event
				WHERE YEAR( date_time_event ) =  ".$year."
				".$search."
				GROUP BY year,month
		";
		//	echo $sql;
		$rec = Model::runSql($sql,true);
		//echo "<pre>";
		//print_r($rec);
		$this->var['data'] = $rec;
		$this->view->render('reports/recruitment/applicant_statistics/excel_download.php', $this->var);
	}
	
	//load planned_activities
	function _load_planned_activities()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Planned Activities";
		$this->view->noTemplate();
		$this->view->render('reports/recruitment/planned_activities/index.php',$this->var);	
	}
	
	function download_planned_activities()
	{
		
		$sql = "SELECT 	e.id,a.firstname,
						a.lastname,
						a.middlename, 
						a.extension_name,
						a.applied_date_time,
						e.hiring_manager,
						e.date_time_event,
						e.event_type,
						e.application_status_id,
						e.notes,
						j.title as position_applied
				
				FROM g_applicant a, g_job_application_event e, g_job j
				".$search_by_date."
				".$query." 
				".$applied."
				AND j.id=a.job_id
				GROUP BY e.id
				ORDER BY e.date_time_event 
				
		";
	//	echo $sql;
		$rec = Model::runSql($sql,true);
		//echo "<pre>";
		//print_r($rec);
		$this->var['data'] = $rec;

		$this->view->render('reports/recruitment/planned_activities/excel_download.php', $this->var);
	}
	
	//load pending applicants
	function _load_pending_applicants()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Pending Applicants";
		$this->view->noTemplate();
		$this->view->render('reports/recruitment/pending_applicants/index.php',$this->var);	
	}
	
	function download_pending_applicants()
	{
		$search = ($_POST['position_applied']=='all') ? '' : "AND a.job_id=".Model::safeSql($_POST['position_applied']) ;

		$sql = "
		SELECT 
		j.title as job_applied,
		a.applied_date_time, 
		a.lastname, 
		a.middlename, 
		a.firstname, 
		a.extension_name, 
		a.gender,
		a.birthdate,
		a.marital_status,
		a.address,
		a.city,
		a.province,
		a.home_telephone,
		a.mobile,
		a.email_address
		
		FROM g_applicant a 
		LEFT JOIN g_job j ON j.id=a.job_id 
		WHERE a.application_status_id=0 
		".$search."
		ORDER BY a.applied_date_time 
		";
		
		
		//echo $sql;
		$rec = Model::runSql($sql,true);
		//echo "<pre>";
		//print_r($rec);
		$this->var['data'] = $rec;

		$this->view->render('reports/recruitment/pending_applicants/excel_download.php', $this->var);
	}
	
	//load job_advertisements
	function _load_job_advertisements()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Job Advertisements";
		$this->view->noTemplate();
		$this->view->render('reports/recruitment/job_advertisements/index.php',$this->var);	
	}
	
	function download_job_advertisement()
	{
		$sql = "SELECT 	
				v.id,
				j.title as job_vacancy,
				CONCAT(e.lastname, ', ', e.firstname) as hiring_manager,
				v.publication_date,
				v.advertisement_end,
				v.hiring_manager_id,
				v.is_active
				FROM g_job_vacancy v
				Left Join `g_employee` AS `e` ON e.id=v.hiring_manager_id 
				Left Join `g_job` AS `j` ON `j`.`id` = `v`.`job_id` 
				WHERE 
				v.is_active=1
		";
		$rec = Model::runSql($sql,true);
		$this->var['data'] = $rec;
		$this->view->render('reports/recruitment/job_advertisements/excel_download.php', $this->var);
	}
	
	//load task_overview
	function _load_task_overview()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Task Overview";
		$this->view->noTemplate();
		$this->view->render('reports/personnel_administration/task_overview/index.php',$this->var);	
	}
	
	function download_task_overview()
	{
		$sql = "SELECT 	e.id,a.firstname,
						a.lastname,
						a.middlename, 
						a.extension_name,
						a.applied_date_time,
						e.hiring_manager,
						e.date_time_event,
						e.event_type,
						e.application_status_id,
						e.notes,
						j.title as position_applied
				
				FROM g_applicant a, g_job_application_event e, g_job j
				".$search_by_date."
				".$query." 
				".$applied."
				AND j.id=a.job_id
				GROUP BY e.id
				ORDER BY e.date_time_event 
				
		";
	//	echo $sql;
		$rec = Model::runSql($sql,true);
		//echo "<pre>";
		//print_r($rec);
		$this->var['data'] = $rec;

		$this->view->render('reports/personnel_administration/task_overview/excel_download.php', $this->var);
	}
	
	//load anniversaries
	function _load_anniversaries()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Anniversaries";
		$this->view->noTemplate();
		$this->view->render('reports/personnel_administration/anniversaries/index.php',$this->var);	
	}
	
	function download_anniversaries()
	{
		
		$sql = "SELECT 	e.id,a.firstname,
						a.lastname,
						a.middlename, 
						a.extension_name,
						a.applied_date_time,
						e.hiring_manager,
						e.date_time_event,
						e.event_type,
						e.application_status_id,
						e.notes,
						j.title as position_applied
				
				FROM g_applicant a, g_job_application_event e, g_job j
				".$search_by_date."
				".$query." 
				".$applied."
				AND j.id=a.job_id
				GROUP BY e.id
				ORDER BY e.date_time_event 
				
		";
	//	echo $sql;
		$rec = Model::runSql($sql,true);
		//echo "<pre>";
		//print_r($rec);
		$this->var['data'] = $rec;

		$this->view->render('reports/personnel_administration/anniversaries/excel_download.php', $this->var);
	}
	
	//load power_of_attorney
	function _load_power_of_attorney()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Power of Attorney";
		$this->view->noTemplate();
		$this->view->render('reports/personnel_administration/power_of_attorney/index.php',$this->var);	
	}
	
	function download_power_of_attorney()
	{
		
		$sql = "SELECT 	e.id,a.firstname,
						a.lastname,
						a.middlename, 
						a.extension_name,
						a.applied_date_time,
						e.hiring_manager,
						e.date_time_event,
						e.event_type,
						e.application_status_id,
						e.notes,
						j.title as position_applied
				
				FROM g_applicant a, g_job_application_event e, g_job j
				".$search_by_date."
				".$query." 
				".$applied."
				AND j.id=a.job_id
				GROUP BY e.id
				ORDER BY e.date_time_event 
				
		";
	//	echo $sql;
		$rec = Model::runSql($sql,true);
		//echo "<pre>";
		//print_r($rec);
		$this->var['data'] = $rec;

		$this->view->render('reports/personnel_administration/power_of_attorney/excel_download.php', $this->var);
	}
	
	//load education
	function _load_education()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Education";
		$this->view->noTemplate();
		$this->view->render('reports/personnel_administration/education/index.php',$this->var);	
	}
	
	function download_education()
	{
		
		$sql = "SELECT 	e.id,a.firstname,
						a.lastname,
						a.middlename, 
						a.extension_name,
						a.applied_date_time,
						e.hiring_manager,
						e.date_time_event,
						e.event_type,
						e.application_status_id,
						e.notes,
						j.title as position_applied
				
				FROM g_applicant a, g_job_application_event e, g_job j
				".$search_by_date."
				".$query." 
				".$applied."
				AND j.id=a.job_id
				GROUP BY e.id
				ORDER BY e.date_time_event 
				
		";
	//	echo $sql;
		$rec = Model::runSql($sql,true);
		//echo "<pre>";
		//print_r($rec);
		$this->var['data'] = $rec;

		$this->view->render('reports/personnel_administration/education/excel_download.php', $this->var);
	}
	
	//load employee_entered_left
	function _load_employee_entered_left()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Employees Who Entered/Left the Company";
		$this->view->noTemplate();
		$this->view->render('reports/personnel_administration/employee_entered_left/index.php',$this->var);	
	}
	
	function download_employee_entered_left()
	{
	
		$sql = "SELECT 	e.id,a.firstname,
						a.lastname,
						a.middlename, 
						a.extension_name,
						a.applied_date_time,
						e.hiring_manager,
						e.date_time_event,
						e.event_type,
						e.application_status_id,
						e.notes,
						j.title as position_applied
				
				FROM g_applicant a, g_job_application_event e, g_job j
				".$search_by_date."
				".$query." 
				".$applied."
				AND j.id=a.job_id
				GROUP BY e.id
				ORDER BY e.date_time_event 
				
		";
	//	echo $sql;
		$rec = Model::runSql($sql,true);
		//echo "<pre>";
		//print_r($rec);
		$this->var['data'] = $rec;

		$this->view->render('reports/personnel_administration/employee_entered_left/excel_download.php', $this->var);
	}
	
	//load family_members
	function _load_family_members()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Family Members";
		$this->view->noTemplate();
		$this->view->render('reports/personnel_administration/family_members/index.php',$this->var);	
	}
	
	function download_family_members()
	{
	
		$sql = "SELECT 	e.id,a.firstname,
						a.lastname,
						a.middlename, 
						a.extension_name,
						a.applied_date_time,
						e.hiring_manager,
						e.date_time_event,
						e.event_type,
						e.application_status_id,
						e.notes,
						j.title as position_applied
				
				FROM g_applicant a, g_job_application_event e, g_job j
				".$search_by_date."
				".$query." 
				".$applied."
				AND j.id=a.job_id
				GROUP BY e.id
				ORDER BY e.date_time_event 
				
		";

		$rec = Model::runSql($sql,true);
		$this->var['data'] = $rec;

		$this->view->render('reports/personnel_administration/family_members/excel_download.php', $this->var);
	}
	
	//load birthday_list
	function _load_birthday_list()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();
		$company_id = (int) $this->company_structure_id;
		
		$c = G_Company_Structure_Finder::findParentChildByBranchId($company_id);
		
		$this->var['department'] = $c;
		
		$this->var['job'] = $j;
		$this->var['title'] = "Birthday List";
		$this->view->noTemplate();
		$this->view->render('reports/personnel_administration/birthday_list/index.php',$this->var);	
	}

	//load shift schedule report
	function _load_shift_schedule()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();
		$company_id = (int) $this->company_structure_id;
		
		$c = G_Company_Structure_Finder::findParentChildByBranchId($company_id);
		
		$this->var['department'] = $c;
		
		$this->var['job'] = $j;
		$this->var['title'] = "Shift Schedule";
		$this->view->noTemplate();
		$this->view->render('reports/time_management/shift_schedule/index.php',$this->var);	
	}

	//load shift final pay report
	function _load_final_pay()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();
		$company_id = (int) $this->company_structure_id;
		
		$c = G_Company_Structure_Finder::findParentChildByBranchId($company_id);
		
		$this->var['department'] = $c;
		
		$this->var['job'] = $j;
		$this->var['title'] = "Resigned Accountability Report";
		$this->view->noTemplate();
		$this->view->render('reports/final_pay/index.php',$this->var);	
	}
	
	function download_birthday_list()
	{		
		if( $_POST['leave_converted_remove_resigned'] == 1){
			$qry_add_on[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
		}

		if( $_POST['leave_converted_remove_terminated'] == 1 ){
			$qry_add_on[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
		}

		if( $_POST['leave_converted_remove_endo'] == 1){
			$qry_add_on[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
		}

		if( $_POST['leave_converted_remove_inactive'] == 1 ){
			$qry_add_on[] = "(e.inactive_date = '0000-00-00' OR e.inactive_date = '')";
		}

		$is_additional_qry = '';
		if( !empty($qry_add_on) ){
			$is_additional_qry .= implode(" AND ", $qry_add_on);
		}

		$department_id = $_POST['department_id'];
		$month_birthday = $_POST['month_birthday'];
	
		//$rec = G_Employee_Helper::findByDepartmentIdMonth($department_id,$month_birthday, $is_additional_qry);	
		$rec = G_Employee_Helper::findByDepartmentIdBirthMonth($department_id,$month_birthday, $is_additional_qry);	

		/*echo '<pre>';
		print_r($rec);
		echo '</pre>';
		exit;*/

		$this->var['month'] = $_POST['month_birthday'];
		$this->var['data']  = $rec;

		$this->view->render('reports/personnel_administration/birthday_list/excel_download.php', $this->var);
	}
	
	//load vehicle_list
	function _load_vehicle_list()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Vehicle List";
		$this->view->noTemplate();
		$this->view->render('reports/personnel_administration/vehicle_list/index.php',$this->var);	
	}
	
	function download_vehicle_list()
	{
		$sql = "SELECT 	e.id,a.firstname,
						a.lastname,
						a.middlename, 
						a.extension_name,
						a.applied_date_time,
						e.hiring_manager,
						e.date_time_event,
						e.event_type,
						e.application_status_id,
						e.notes,
						j.title as position_applied
				
				FROM g_applicant a, g_job_application_event e, g_job j
				".$search_by_date."
				".$query." 
				".$applied."
				AND j.id=a.job_id
				GROUP BY e.id
				ORDER BY e.date_time_event 
				
		";
	//	echo $sql;
		$rec = Model::runSql($sql,true);
		//echo "<pre>";
		//print_r($rec);
		$this->var['data'] = $rec;

		$this->view->render('reports/personnel_administration/vehicle_list/excel_download.php', $this->var);
	}
	
	//load telephone_directory
	function _load_telephone_directory()
	{
		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();
		$company_id = (int) $this->company_structure_id;
		
		$c = G_Company_Structure_Finder::findParentChildByBranchId($company_id);
		
		$this->var['department'] = $c;
		$this->var['job'] = $j;
		$this->var['title'] = "Telephone Directory";
		$this->view->noTemplate();
		$this->view->render('reports/personnel_administration/telephone_directory/index.php',$this->var);	
	}
	
	function download_telephone_directory()
	{
		$department_id = (int) $_POST['department_id'];
		$query = ($department_id=="") ? "" : "WHERE d.company_structure_id=".$department_id; 
		$sql = "SELECT 
			c.title as department,
			e.id,
			e.employee_code,
			concat(e.lastname, ', ', e.firstname) as employee_name,
			e.hired_date,
			j.name as position,
			j.employment_status,
			cd.home_telephone,
			cd.mobile,
			cd.work_telephone,
			cd.work_email
			 FROM 
			 g_employee e
			 LEFT JOIN g_employee_subdivision_history as d ON d.employee_id=e.id
		     LEFT JOIN g_company_structure as c ON c.id=d.company_structure_id
			 LEFT JOIN g_employee_contact_details as cd ON cd.employee_id=e.id 
			 LEFT JOIN g_employee_job_history AS j ON (j.employee_id=e.id AND j.end_date='') OR ( j.employee_id=e.id AND j.employment_status='Terminated')
			 ".$query."
			 GROUP BY e.id
			 ORDER BY c.title, e.hired_date , e.lastname,e.firstname
			 
		 ";
		
		$rec = Model::runSql($sql,true);
		
		$this->var['data'] = $rec;

		$this->view->render('reports/personnel_administration/telephone_directory/excel_download.php', $this->var);
	}
	
	//load time_spend_pay_scale
	function _load_time_spend_pay_scale()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Time Spend Pay Scale";
		$this->view->noTemplate();
		$this->view->render('reports/personnel_administration/time_spend_pay_scale/index.php',$this->var);	
	}
	
	function download_time_spend_pay_scale()
	{

		$sql = "SELECT 	e.id,a.firstname,
						a.lastname,
						a.middlename, 
						a.extension_name,
						a.applied_date_time,
						e.hiring_manager,
						e.date_time_event,
						e.event_type,
						e.application_status_id,
						e.notes,
						j.title as position_applied
				
				FROM g_applicant a, g_job_application_event e, g_job j
				".$search_by_date."
				".$query." 
				".$applied."
				AND j.id=a.job_id
				GROUP BY e.id
				ORDER BY e.date_time_event 
				
		";
	//	echo $sql;
		$rec = Model::runSql($sql,true);
		//echo "<pre>";
		//print_r($rec);
		$this->var['data'] = $rec;

		$this->view->render('reports/personnel_administration/time_spend_pay_scale/excel_download.php', $this->var);
	}
	
	//load hr_master_data_sheet
	function _load_hr_master_data_sheet()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "HR Master Data Sheet";
		$this->view->noTemplate();
		$this->view->render('reports/personnel_administration/hr_master_data_sheet/index.php',$this->var);	
	}
	
	function download_hr_master_data_sheet()
	{

		$sql = "SELECT 	e.id,a.firstname,
						a.lastname,
						a.middlename, 
						a.extension_name,
						a.applied_date_time,
						e.hiring_manager,
						e.date_time_event,
						e.event_type,
						e.application_status_id,
						e.notes,
						j.title as position_applied
				
				FROM g_applicant a, g_job_application_event e, g_job j
				".$search_by_date."
				".$query." 
				".$applied."
				AND j.id=a.job_id
				GROUP BY e.id
				ORDER BY e.date_time_event 
				
		";
	//	echo $sql;
		$rec = Model::runSql($sql,true);
		//echo "<pre>";
		//print_r($rec);
		$this->var['data'] = $rec;

		$this->view->render('reports/personnel_administration/hr_master_data_sheet/excel_download.php', $this->var);
	}
	
	//load flexible_employee_data
	function _load_flexible_employee_data()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Flexible Employee Data";
		$this->view->noTemplate();
		$this->view->render('reports/personnel_administration/flexible_employee_data/index.php',$this->var);	
	}
	
	function download_flexible_employee_data()
	{

		$sql = "SELECT 	e.id,a.firstname,
						a.lastname,
						a.middlename, 
						a.extension_name,
						a.applied_date_time,
						e.hiring_manager,
						e.date_time_event,
						e.event_type,
						e.application_status_id,
						e.notes,
						j.title as position_applied
				
				FROM g_applicant a, g_job_application_event e, g_job j
				".$search_by_date."
				".$query." 
				".$applied."
				AND j.id=a.job_id
				GROUP BY e.id
				ORDER BY e.date_time_event 
				
		";
	//	echo $sql;
		$rec = Model::runSql($sql,true);
		//echo "<pre>";
		//print_r($rec);
		$this->var['data'] = $rec;

		$this->view->render('reports/personnel_administration/flexible_employee_data/excel_download.php', $this->var);
	}
	
	//load list_of_employees
	function _load_list_of_employees()
	{

		Utilities::ajaxRequest();
		$company_id = (int) $this->company_structure_id;
		$c = G_Company_Structure_Finder::findParentChildByBranchId($company_id);
		
		$this->var['department'] = $c;
		$this->var['title'] = "List of Employees";
		$this->view->noTemplate();
		$this->view->render('reports/personnel_administration/list_of_employees/index.php',$this->var);	
	}
	
	function download_employee_list()
	{
		$department_id = $_POST['department_id'];
		$x=0;
		while($x<11) {
			$x++;
			if($_POST['checkbox'.$x]) {
				$field .=  $_POST['checkbox'.$x].",";
				$title_field[] = $_POST['checkbox'.$x];
			}	
		}
				
		$title = array('lastname','firstname','middlename','extension_name','job_applied');
		if($title_field) {
			$excel_title = array_merge($title,$title_field);	
		}else {
			$excel_title = $title;
		}
		
		$where = ($department_id=='all') ?  '' : 'WHERE d.company_structure_id='.$department_id .' AND j.end_date="" AND d.end_date=""';
		
		$sql = "SELECT 
			e.id,
			e.company_structure_id,
			d.name as department,
			e.employee_code,
			e.lastname, 
			e.firstname, 
			e.middlename,  
			e.extension_name,
			e.nickname,
			e.birthdate,
			e.gender,
			e.marital_status,
			e.number_dependent,
			e.sss_number,
			e.tin_number,
			e.pagibig_number,
			e.philhealth_number,
			e.hired_date,
			".$field."
			j.name as position
			FROM g_employee_subdivision_history d
			LEFT JOIN g_employee e ON e.id=d.employee_id 
			LEFT JOIN g_employee_job_history j ON j.employee_id=e.id 
			".$where."
			ORDER BY e.hired_date DESC
		";
		//e.hired_date
		$data = Model::runSql($sql,true);
		
		//Tools::showArray($data);
		$this->var['data'] = $data;
		$this->view->render('reports/personnel_administration/list_of_employees/download_excel.php', $this->var);
	}
	
	//load leave_overview
	function _load_leave_overview()
	{

		Utilities::ajaxRequest();
		$company_id = (int) $this->company_structure_id;
		$c = G_Company_Structure_Finder::findParentChildByBranchId($company_id);
		
		$this->var['department'] = $c;
		
		
		$this->var['title'] = "Leave Overview";
		$this->view->noTemplate();
		$this->view->render('reports/personnel_administration/leave_overview/index.php',$this->var);	
	}
	
	function download_leave_overview()
	{	
		$dept_id    = ($_POST['department_id']!='')? $_POST['department_id'] : '' ; 
		$date_start = $_POST['date_applied_from'];
		$date_to    = $_POST['date_applied_to'];
		$rec        = G_Employee_Leave_Request_Helper::findAllByPeriodAndDepartmentId($date_start,$date_to,$dept_id);		
		$this->var['data'] = $rec;

		$this->view->render('reports/personnel_administration/leave_overview/excel_download.php', $this->var);
	}
	
	//load headcount_development
	function _load_headcount_development()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Headcount Development";
		$this->view->noTemplate();
		$this->view->render('reports/personnel_administration/headcount_development/index.php',$this->var);	
	}
	
	function download_headcount_development()
	{
		
		$sql = "SELECT 	e.id,a.firstname,
						a.lastname,
						a.middlename, 
						a.extension_name,
						a.applied_date_time,
						e.hiring_manager,
						e.date_time_event,
						e.event_type,
						e.application_status_id,
						e.notes,
						j.title as position_applied
				
				FROM g_applicant a, g_job_application_event e, g_job j
				".$search_by_date."
				".$query." 
				".$applied."
				AND j.id=a.job_id
				GROUP BY e.id
				ORDER BY e.date_time_event 
				
		";
	//	echo $sql;
		$rec = Model::runSql($sql,true);
		//echo "<pre>";
		//print_r($rec);
		$this->var['data'] = $rec;

		$this->view->render('reports/personnel_administration/headcount_development/excel_download.php', $this->var);
	}
	
	//load nationalities
	function _load_nationalities()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Nationalities";
		$this->view->noTemplate();
		$this->view->render('reports/personnel_administration/nationalities/index.php',$this->var);	
	}
	
	function download_nationality()
	{
		$sql = "SELECT
			`e`.`id`,
			`e`.`employee_code`,
			CONCAT(e.lastname,', ',e.firstname,' ',e.middlename,' ', e.extension_name) AS `employee_name`,
			e.nationality,
			j.name as position,
			j.employment_status			
			FROM `g_employee` AS `e`
			Left Join `g_employee_job_history` AS `j` ON `j`.`employee_id` = `e`.`id` AND `j`.`end_date` = ''
			WHERE e.nationality like '%".$_POST['nationality']."%'
		";
		
		$rec = Model::runSql($sql,true);
		//echo "<pre>";
		//print_r($rec);
		$this->var['data'] = $rec;

		$this->view->render('reports/personnel_administration/nationalities/excel_download.php', $this->var);
	}
	
	//load salary_list
	function _load_salary_list()
	{

		Utilities::ajaxRequest();
		$company_id = (int) $this->company_structure_id;
		$c = G_Company_Structure_Finder::findParentChildByBranchId($company_id);

		$this->var['department'] = $c;

		$this->var['title'] = "Salary According to Seniority";
		$this->view->noTemplate();
		$this->view->render('reports/personnel_administration/salary_list/index.php',$this->var);	
	}
	
	function download_salary_list()
	{
		$department_id =  $_POST['department_id'];
		if($department_id=='all') {
			$all = 1;
			$sql = "SELECT 
			c.title as department,
			e.employee_code,
			concat(e.lastname, ', ', e.firstname, ' ', e.middlename) as employee_name,
			e.hired_date,
			s.basic_salary,
			s.type,
			j.name as position,
			j.employment_status
			 FROM 
			 g_employee e
			 LEFT JOIN g_employee_subdivision_history as d ON d.employee_id=e.id
		     LEFT JOIN g_company_structure as c ON c.id=d.company_structure_id
			 LEFT JOIN g_employee_basic_salary_history as s ON s.employee_id=e.id AND s.end_date=''
			 LEFT JOIN g_employee_job_history AS j ON (j.employee_id=e.id AND j.end_date='') OR ( j.employee_id=e.id AND j.employment_status='Terminated')
			 ORDER BY c.title, e.hired_date , e.lastname,e.firstname
			 ";

			$salary =  Model::runSql($sql,true);
			$filename = 'excel_all_download.php';
			$this->var['file'] = 'salary_list_all.xls';
			
		}else {
			$all = 0;
			$d = G_Company_Structure_Finder::findById($department_id);
			$this->var['department'] = $d->getTitle();

			$sql = "SELECT 
			c.title as department,
			e.employee_code,
			concat(e.lastname, ', ', e.firstname, ' ', e.middlename) as employee_name,
			e.hired_date,
			s.basic_salary,
			s.type,
			j.name as position,
			j.employment_status
			 FROM 
			 g_employee e
			 LEFT JOIN g_employee_subdivision_history as d ON d.employee_id=e.id 
		     LEFT JOIN g_company_structure as c ON c.id=d.company_structure_id
			 LEFT JOIN g_employee_basic_salary_history as s ON s.employee_id=e.id AND s.end_date=''
			 LEFT JOIN g_employee_job_history AS j ON (j.employee_id=e.id AND j.end_date='') OR ( j.employee_id=e.id AND j.employment_status='Terminated')
			 WHERE d.company_structure_id=".$department_id."
			 ORDER BY c.title, e.hired_date , e.lastname,e.firstname
			 ";
			// echo $sql;
			$salary =  Model::runSql($sql,true);		
			$filename = 'excel_by_department_download.php';
			$this->var['file'] = 'salary_list_by_department.xls';
			
		}
		$this->var['data'] = $salary;

		$this->view->render('reports/personnel_administration/salary_list/'.$filename, $this->var);
	}
	
	function download_salary_list_old()
	{
		$department_id =  $_POST['department_id'];
		if($department_id=='all') {
			$all = 1;
			$sql = "SELECT 
			c.title as department,
			e.employee_code,
			concat(e.lastname, ', ', e.firstname, ' ', e.middlename) as employee_name,
			e.hired_date,
			s.basic_salary,
			s.type,
			j.name as position,
			j.employment_status
			 FROM 
			 g_employee e
			 LEFT JOIN g_employee_subdivision_history as d ON d.employee_id=e.id
		     LEFT JOIN g_company_structure as c ON c.id=d.company_structure_id
			 LEFT JOIN g_employee_basic_salary_history as s ON s.employee_id=e.id AND s.end_date=''
			 LEFT JOIN g_employee_job_history AS j ON (j.employee_id=e.id AND j.end_date='') OR ( j.employee_id=e.id AND j.employment_status='Terminated')
			 ORDER BY c.title, e.hired_date , e.lastname,e.firstname
			 ";

			$salary =  Model::runSql($sql,true);
			$filename = 'excel_all.php';
			
			
		}else {
			$all = 0;
			$d = G_Company_Structure_Finder::findById($department_id);
			$this->var['department'] = $d->getTitle();

			$sql = "SELECT 
			c.title as department,
			e.employee_code,
			concat(e.lastname, ', ', e.firstname, ' ', e.middlename) as employee_name,
			e.hired_date,
			s.basic_salary,
			s.type,
			j.name as position,
			j.employment_status
			 FROM 
			 g_employee e
			 LEFT JOIN g_employee_subdivision_history as d ON d.employee_id=e.id 
		     LEFT JOIN g_company_structure as c ON c.id=d.company_structure_id
			 LEFT JOIN g_employee_basic_salary_history as s ON s.employee_id=e.id AND s.end_date=''
			 LEFT JOIN g_employee_job_history AS j ON (j.employee_id=e.id AND j.end_date='') OR ( j.employee_id=e.id AND j.employment_status='Terminated')
			 WHERE d.company_structure_id=".$department_id."
			 ORDER BY c.title, e.hired_date , e.lastname,e.firstname
			 ";
			// echo $sql;
			$salary =  Model::runSql($sql,true);
			$filename = 'excel_by_department.php';
		}
		
		$this->var['data'] = $salary;

		$this->view->render('reports/personnel_administration/salary_list/'.$filename, $this->var);
	}
	
	//load salary_list
	function _load_certificate_of_employment()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Certificate of Employment";
		$this->view->noTemplate();
		$this->view->render('reports/personnel_administration/certificate_of_employment/index.php',$this->var);	
	}
	
	function download_download_certificate_of_employment()
	{

		$sql = "SELECT 	e.id,a.firstname,
						a.lastname,
						a.middlename, 
						a.extension_name,
						a.applied_date_time,
						e.hiring_manager,
						e.date_time_event,
						e.event_type,
						e.application_status_id,
						e.notes,
						j.title as position_applied
				
				FROM g_applicant a, g_job_application_event e, g_job j
				".$search_by_date."
				".$query." 
				".$applied."
				AND j.id=a.job_id
				GROUP BY e.id
				ORDER BY e.date_time_event 
				
		";
	//	echo $sql;
		$rec = Model::runSql($sql,true);
		//echo "<pre>";
		//print_r($rec);
		$this->var['data'] = $rec;

		$this->view->render('reports/personnel_administration/certificate_of_employment/excel_download.php', $this->var);

	}
	
	//load profile_matchup
	function _load_profile_matchup()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Profile Matchup";
		$this->view->noTemplate();
		$this->view->render('reports/personnel_development/profile_matchup/index.php',$this->var);	
	}
	
	function download_profile_matchup()
	{

		$sql = "SELECT 	e.id,a.firstname,
						a.lastname,
						a.middlename, 
						a.extension_name,
						a.applied_date_time,
						e.hiring_manager,
						e.date_time_event,
						e.event_type,
						e.application_status_id,
						e.notes,
						j.title as position_applied
				
				FROM g_applicant a, g_job_application_event e, g_job j
				".$search_by_date."
				".$query." 
				".$applied."
				AND j.id=a.job_id
				GROUP BY e.id
				ORDER BY e.date_time_event 
				
		";
	//	echo $sql;
		$rec = Model::runSql($sql,true);
		//echo "<pre>";
		//print_r($rec);
		$this->var['data'] = $rec;

		$this->view->render('reports/personnel_development/personnel_development/excel_download.php', $this->var);
	}
	
	//load profile_evaluation
	function _load_profile_evaluation()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Profile Evalualation";
		$this->view->noTemplate();
		$this->view->render('reports/personnel_development/profile_evaluation/index.php',$this->var);	
	}
	
	function download_profile_evaluation()
	{
	
		$sql = "SELECT 	e.id,a.firstname,
						a.lastname,
						a.middlename, 
						a.extension_name,
						a.applied_date_time,
						e.hiring_manager,
						e.date_time_event,
						e.event_type,
						e.application_status_id,
						e.notes,
						j.title as position_applied
				
				FROM g_applicant a, g_job_application_event e, g_job j
				".$search_by_date."
				".$query." 
				".$applied."
				AND j.id=a.job_id
				GROUP BY e.id
				ORDER BY e.date_time_event 
				
		";
	//	echo $sql;
		$rec = Model::runSql($sql,true);
		//echo "<pre>";
		//print_r($rec);
		$this->var['data'] = $rec;

		$this->view->render('reports/personnel_development/profile_evaluation/excel_download.php', $this->var);
	}
	
	//load qualification
	function _load_qualification()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Qualification";
		$this->view->noTemplate();
		$this->view->render('reports/personnel_development/qualification/index.php',$this->var);	
	}
	
	function download_qualification()
	{

		$sql = "SELECT 	e.id,a.firstname,
						a.lastname,
						a.middlename, 
						a.extension_name,
						a.applied_date_time,
						e.hiring_manager,
						e.date_time_event,
						e.event_type,
						e.application_status_id,
						e.notes,
						j.title as position_applied
				
				FROM g_applicant a, g_job_application_event e, g_job j
				".$search_by_date."
				".$query." 
				".$applied."
				AND j.id=a.job_id
				GROUP BY e.id
				ORDER BY e.date_time_event 
				
		";
	//	echo $sql;
		$rec = Model::runSql($sql,true);
		//echo "<pre>";
		//print_r($rec);
		$this->var['data'] = $rec;

		$this->view->render('reports/personnel_development/qualification/excel_download.php', $this->var);
	}
	
	//load qualification_template
	function _load_qualification_template()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Qualification Template";
		$this->view->noTemplate();
		$this->view->render('reports/personnel_development/qualification_template/index.php',$this->var);	
	}
	
	function download_qualification_template()
	{

		$sql = "SELECT 	e.id,a.firstname,
						a.lastname,
						a.middlename, 
						a.extension_name,
						a.applied_date_time,
						e.hiring_manager,
						e.date_time_event,
						e.event_type,
						e.application_status_id,
						e.notes,
						j.title as position_applied
				
				FROM g_applicant a, g_job_application_event e, g_job j
				".$search_by_date."
				".$query." 
				".$applied."
				AND j.id=a.job_id
				GROUP BY e.id
				ORDER BY e.date_time_event 
				
		";
	//	echo $sql;
		$rec = Model::runSql($sql,true);
		//echo "<pre>";
		//print_r($rec);
		$this->var['data'] = $rec;

		$this->view->render('reports/personnel_development/qualification_template/excel_download.php', $this->var);
	}
	
	//load development_plan
	function _load_development_plan()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Development Plan";
		$this->view->noTemplate();
		$this->view->render('reports/personnel_development/development_plan/index.php',$this->var);	
	}
	
	function download_development_plan()
	{
	
		$sql = "SELECT 	e.id,a.firstname,
						a.lastname,
						a.middlename, 
						a.extension_name,
						a.applied_date_time,
						e.hiring_manager,
						e.date_time_event,
						e.event_type,
						e.application_status_id,
						e.notes,
						j.title as position_applied
				
				FROM g_applicant a, g_job_application_event e, g_job j
				".$search_by_date."
				".$query." 
				".$applied."
				AND j.id=a.job_id
				GROUP BY e.id
				ORDER BY e.date_time_event 
				
		";
	//	echo $sql;
		$rec = Model::runSql($sql,true);
		//echo "<pre>";
		//print_r($rec);
		$this->var['data'] = $rec;

		$this->view->render('reports/personnel_development/development_plan/excel_download.php', $this->var);
	}
	
	//load development_item
	function _load_development_item()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Development Item";
		$this->view->noTemplate();
		$this->view->render('reports/personnel_development/development_item/index.php',$this->var);	
	}
	
	function download_development_item()
	{


		$sql = "SELECT 	e.id,a.firstname,
						a.lastname,
						a.middlename, 
						a.extension_name,
						a.applied_date_time,
						e.hiring_manager,
						e.date_time_event,
						e.event_type,
						e.application_status_id,
						e.notes,
						j.title as position_applied
				
				FROM g_applicant a, g_job_application_event e, g_job j
				".$search_by_date."
				".$query." 
				".$applied."
				AND j.id=a.job_id
				GROUP BY e.id
				ORDER BY e.date_time_event 
				
		";
	//	echo $sql;
		$rec = Model::runSql($sql,true);
		//echo "<pre>";
		//print_r($rec);
		$this->var['data'] = $rec;

		$this->view->render('reports/personnel_development/development_item/excel_download.php', $this->var);
	}
	
	//load appraisal_evaluation
	function _load_appraisal_evaluation()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Appraisal Evaluation";
		$this->view->noTemplate();
		$this->view->render('reports/personnel_development/appraisal_evaluation/index.php',$this->var);	
	}
	
	function download_appraisal_evaluation()
	{

		
		$sql = "SELECT 	e.id,a.firstname,
						a.lastname,
						a.middlename, 
						a.extension_name,
						a.applied_date_time,
						e.hiring_manager,
						e.date_time_event,
						e.event_type,
						e.application_status_id,
						e.notes,
						j.title as position_applied
				
				FROM g_applicant a, g_job_application_event e, g_job j
				".$search_by_date."
				".$query." 
				".$applied."
				AND j.id=a.job_id
				GROUP BY e.id
				ORDER BY e.date_time_event 
				
		";
	//	echo $sql;
		$rec = Model::runSql($sql,true);
		//echo "<pre>";
		//print_r($rec);
		$this->var['data'] = $rec;

		$this->view->render('reports/personnel_development/appraisal_evaluation/excel_download.php', $this->var);
	}
		
	//load development_plan_template
	function _load_development_plan_template()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Development Template";
		$this->view->noTemplate();
		$this->view->render('reports/personnel_development/development_plan_template/index.php',$this->var);	
	}
	
	function download_development_plan_template()
	{

		$sql = "SELECT 	e.id,a.firstname,
						a.lastname,
						a.middlename, 
						a.extension_name,
						a.applied_date_time,
						e.hiring_manager,
						e.date_time_event,
						e.event_type,
						e.application_status_id,
						e.notes,
						j.title as position_applied
				
				FROM g_applicant a, g_job_application_event e, g_job j
				".$search_by_date."
				".$query." 
				".$applied."
				AND j.id=a.job_id
				GROUP BY e.id
				ORDER BY e.date_time_event 
				
		";
	//	echo $sql;
		$rec = Model::runSql($sql,true);
		//echo "<pre>";
		//print_r($rec);
		$this->var['data'] = $rec;

		$this->view->render('reports/personnel_development/development_plan_template/excel_download.php', $this->var);
	}
	
	//load appraisal_template
	function _load_appraisal_template()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Appraisal Template";
		$this->view->noTemplate();
		$this->view->render('reports/personnel_development/appraisal_template/index.php',$this->var);	
	}
	
	function download_appraisal_template()
	{

		$sql = "SELECT 	e.id,a.firstname,
						a.lastname,
						a.middlename, 
						a.extension_name,
						a.applied_date_time,
						e.hiring_manager,
						e.date_time_event,
						e.event_type,
						e.application_status_id,
						e.notes,
						j.title as position_applied
				
				FROM g_applicant a, g_job_application_event e, g_job j
				".$search_by_date."
				".$query." 
				".$applied."
				AND j.id=a.job_id
				GROUP BY e.id
				ORDER BY e.date_time_event 
				
		";
	//	echo $sql;
		$rec = Model::runSql($sql,true);
		//echo "<pre>";
		//print_r($rec);
		$this->var['data'] = $rec;

		$this->view->render('reports/personnel_development/appraisal_template/excel_download.php', $this->var);
	}
	
	//load careers
	function _load_careers()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Careers";
		$this->view->noTemplate();
		$this->view->render('reports/personnel_development/careers/index.php',$this->var);	
	}
	
	function download_careers()
	{
	
		$sql = "SELECT 	e.id,a.firstname,
						a.lastname,
						a.middlename, 
						a.extension_name,
						a.applied_date_time,
						e.hiring_manager,
						e.date_time_event,
						e.event_type,
						e.application_status_id,
						e.notes,
						j.title as position_applied
				
				FROM g_applicant a, g_job_application_event e, g_job j
				".$search_by_date."
				".$query." 
				".$applied."
				AND j.id=a.job_id
				GROUP BY e.id
				ORDER BY e.date_time_event 
				
		";
	//	echo $sql;
		$rec = Model::runSql($sql,true);
		//echo "<pre>";
		//print_r($rec);
		$this->var['data'] = $rec;

		$this->view->render('reports/personnel_development/careers/excel_download.php', $this->var);
	}
	
	//load vacant_obselete_position
	function _load_vacant_obselete_position()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Vacant Obselete Position";
		$this->view->noTemplate();
		$this->view->render('reports/personnel_development/vacant_obselete_position/index.php',$this->var);	
	}
	
	function download_vacant_obselete_position()
	{


		$sql = "SELECT 	e.id,a.firstname,
						a.lastname,
						a.middlename, 
						a.extension_name,
						a.applied_date_time,
						e.hiring_manager,
						e.date_time_event,
						e.event_type,
						e.application_status_id,
						e.notes,
						j.title as position_applied
				
				FROM g_applicant a, g_job_application_event e, g_job j
				".$search_by_date."
				".$query." 
				".$applied."
				AND j.id=a.job_id
				GROUP BY e.id
				ORDER BY e.date_time_event 
				
		";
	//	echo $sql;
		$rec = Model::runSql($sql,true);
		//echo "<pre>";
		//print_r($rec);
		$this->var['data'] = $rec;

		$this->view->render('reports/personnel_development/vacant_obselete_position/excel_download.php', $this->var);
	}
	
	//load qualification_overview
	function _load_qualification_overview()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Qualification Overview";
		$this->view->noTemplate();
		$this->view->render('reports/personnel_development/qualification_overview_for_department/index.php',$this->var);	
	}
	
	function download_qualification_overview()
	{

		$sql = "SELECT 	e.id,a.firstname,
						a.lastname,
						a.middlename, 
						a.extension_name,
						a.applied_date_time,
						e.hiring_manager,
						e.date_time_event,
						e.event_type,
						e.application_status_id,
						e.notes,
						j.title as position_applied
				
				FROM g_applicant a, g_job_application_event e, g_job j
				".$search_by_date."
				".$query." 
				".$applied."
				AND j.id=a.job_id
				GROUP BY e.id
				ORDER BY e.date_time_event 
				
		";
	//	echo $sql;
		$rec = Model::runSql($sql,true);
		//echo "<pre>";
		//print_r($rec);
		$this->var['data'] = $rec;

		$this->view->render('reports/personnel_development/qualification_overview_for_department/excel_download.php', $this->var);
	}
	
	//load eligible_employee
	function _load_eligible_employee()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Eligible Employee";
		$this->view->noTemplate();
		$this->view->render('reports/benefits/eligible_employee/index.php',$this->var);	
	}
	
	function download_eligible_employee()
	{

		$sql = "SELECT 	e.id,a.firstname,
						a.lastname,
						a.middlename, 
						a.extension_name,
						a.applied_date_time,
						e.hiring_manager,
						e.date_time_event,
						e.event_type,
						e.application_status_id,
						e.notes,
						j.title as position_applied
				
				FROM g_applicant a, g_job_application_event e, g_job j
				".$search_by_date."
				".$query." 
				".$applied."
				AND j.id=a.job_id
				GROUP BY e.id
				ORDER BY e.date_time_event 
				
		";
	//	echo $sql;
		$rec = Model::runSql($sql,true);
		//echo "<pre>";
		//print_r($rec);
		$this->var['data'] = $rec;

		$this->view->render('reports/benefits/eligible_employee/excel_download.php', $this->var);
	}
	
	//load participation
	function _load_participation()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Participation";
		$this->view->noTemplate();
		$this->view->render('reports/benefits/participation/index.php',$this->var);	
	}
	
	function download_participation()
	{

		$sql = "SELECT 	e.id,a.firstname,
						a.lastname,
						a.middlename, 
						a.extension_name,
						a.applied_date_time,
						e.hiring_manager,
						e.date_time_event,
						e.event_type,
						e.application_status_id,
						e.notes,
						j.title as position_applied
				
				FROM g_applicant a, g_job_application_event e, g_job j
				".$search_by_date."
				".$query." 
				".$applied."
				AND j.id=a.job_id
				GROUP BY e.id
				ORDER BY e.date_time_event 
				
		";
	//	echo $sql;
		$rec = Model::runSql($sql,true);
		//echo "<pre>";
		//print_r($rec);
		$this->var['data'] = $rec;

		$this->view->render('reports/benefits/participation/excel_download.php', $this->var);
	}
	
	//load total_compensation_statement
	function _load_total_compensation_statement()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Total Compensation Statement";
		$this->view->noTemplate();
		$this->view->render('reports/compensation_management/total_compensation_statement/index.php',$this->var);	
	}
	
	function download_total_compensation_statement()
	{

		$sql = "SELECT 	e.id,a.firstname,
						a.lastname,
						a.middlename, 
						a.extension_name,
						a.applied_date_time,
						e.hiring_manager,
						e.date_time_event,
						e.event_type,
						e.application_status_id,
						e.notes,
						j.title as position_applied
				
				FROM g_applicant a, g_job_application_event e, g_job j
				".$search_by_date."
				".$query." 
				".$applied."
				AND j.id=a.job_id
				GROUP BY e.id
				ORDER BY e.date_time_event 
				
		";
	//	echo $sql;
		$rec = Model::runSql($sql,true);
		//echo "<pre>";
		//print_r($rec);
		$this->var['data'] = $rec;

		$this->view->render('reports/compensation_management/total_compensation_statement/excel_download.php', $this->var);
	}
	
	//load job_salary_rate
	function _load_job_salary_rate()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Job Salary Rate";
		$this->view->noTemplate();
		$this->view->render('reports/compensation_management/job_salary_rate/index.php',$this->var);	
	}
	
	function download_job_salary_rate()
	{

		$sql = "SELECT 	e.id,a.firstname,
						a.lastname,
						a.middlename, 
						a.extension_name,
						a.applied_date_time,
						e.hiring_manager,
						e.date_time_event,
						e.event_type,
						e.application_status_id,
						e.notes,
						j.title as position_applied
				
				FROM g_applicant a, g_job_application_event e, g_job j
				".$search_by_date."
				".$query." 
				".$applied."
				AND j.id=a.job_id
				GROUP BY e.id
				ORDER BY e.date_time_event 
				
		";
	//	echo $sql;
		$rec = Model::runSql($sql,true);
		//echo "<pre>";
		//print_r($rec);
		$this->var['data'] = $rec;

		$this->view->render('reports/compensation_management/job_salary_rate/excel_download.php', $this->var);
	}
	
	//load plan_labor_cost
	function _load_plan_labor_cost()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Plan Labor Cost";
		$this->view->noTemplate();
		$this->view->render('reports/compensation_management/plan_labor_costs/index.php',$this->var);	
	}
	
	function download_plan_labor_cost()
	{

		$sql = "SELECT 	e.id,a.firstname,
						a.lastname,
						a.middlename, 
						a.extension_name,
						a.applied_date_time,
						e.hiring_manager,
						e.date_time_event,
						e.event_type,
						e.application_status_id,
						e.notes,
						j.title as position_applied
				
				FROM g_applicant a, g_job_application_event e, g_job j
				".$search_by_date."
				".$query." 
				".$applied."
				AND j.id=a.job_id
				GROUP BY e.id
				ORDER BY e.date_time_event 
				
		";
	//	echo $sql;
		$rec = Model::runSql($sql,true);
		//echo "<pre>";
		//print_r($rec);
		$this->var['data'] = $rec;

		$this->view->render('reports/compensation_management/plan_labor_costs/excel_download.php', $this->var);
	}
	
	//load personal_work_schedule
	function _load_personal_work_schedule()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Personal Work Schedule";
		$this->view->noTemplate();
		$this->view->render('reports/time_management/personal_work_schedule/index.php',$this->var);	
	}
	
	function download_personal_work_schedule()
	{
		if($_POST['employee_id']){
			$eid     = $_POST['employee_id'];
			$e_array = explode(",",$eid);
			
			$schedule  = new G_Schedule();
			$schedules = $schedule->loadArrayEmployeeSchedule($e_array);
			
			$this->var['schedules'] = $schedules;					
			$this->view->render('reports/time_management/personal_work_schedule/excel_download.php', $this->var);	
		}
	}
	
	function download_personal_work_schedule_deprecated()
	{
		$employee_id = (int) $_POST['employee_id'];		
		$e = G_Employee_Finder::findById($employee_id);
		$g = G_Employee_Subdivision_History_Finder::findCurrentSubdivision($e);
		
		$employee_name =  $e->getLastname() . " " . $e->getFirstname();

		//check group schedule // 2 group
		$sql = "SELECT * FROM g_employee_group_schedule WHERE employee_group=2 AND employee_group_id=".Model::safeSql($group_id)."";
		$grp_sched = Model::runSql($sql,true);

		if($grp_sched)
		{
			foreach($grp_sched as $key=>$value) {
				$object = G_Schedule_Finder::findById($value['schedule_id']);	
				$temp['id'] = $object->getId();
				$temp['schedule_name'] = $object->getName();
				$temp['working_days'] = $object->getWorkingDays();
				$temp['time_in'] = $object->getTimeIn();
				$temp['time_out'] = $object->getTimeOut();
				$schedule[] = $temp;
			}		
		}

		//check individual schedule // 1 group
		$sql = "SELECT * FROM g_employee_group_schedule WHERE employee_group=1 AND employee_group_id=".Model::safeSql($employee_id)."";
		$e_sched = Model::runSql($sql,true);

		if($e_sched)
		{
			foreach($e_sched as $key=>$value) {
				
				$object = G_Schedule_Finder::findById($value['schedule_id']);	
				$temp['id'] = $object->getId();
				$temp['schedule_name'] = $object->getName();
				$temp['working_days'] = $object->getWorkingDays();
				$temp['time_in'] = $object->getTimeIn();
				$temp['time_out'] = $object->getTimeOut();
				$schedule[] = $temp;
			}
			
		}
				
		$unique = array_map('unserialize', array_unique(array_map('serialize', $schedule)));
		if(count($unique)>0) {
			$my_schedule = $unique;
		}else {
			$object = G_Schedule_Finder::findDefaultByDate(date("Y-m-d"));
			$temp['id'] = $object->getId();
			$temp['schedule_name'] = $object->getName();
			$temp['working_days'] = $object->getWorkingDays();
			$temp['time_in'] = $object->getTimeIn();
			$temp['time_out'] = $object->getTimeOut();
			$d = $temp;
			$my_schedule[] = $d;
		}
		
		$this->var['data'] = $my_schedule;
		
		$this->var['employee_name'] = $employee_name;
		$this->view->render('reports/time_management/personal_work_schedule/excel_download.php', $this->var);
	}
	
	//load daily_work_schedule
	function _load_daily_work_schedule()
	{

		Utilities::ajaxRequest();
		
		$company_id = (int) $this->company_structure_id;
		$c = G_Company_Structure_Finder::findParentChildByBranchId($company_id);

		$this->var['department'] = $c;
		$this->var['title'] = "Daily Work Schedule";
		$this->view->noTemplate();
		$this->view->render('reports/time_management/daily_work_schedule/index.php',$this->var);	
	}
	
 	function download_daily_work_schedule() {
		if(!empty($_POST)) {
			ini_set("memory_limit", "999M");
			set_time_limit(99999999999999999999999);
			
			$department_id	= $_POST['department_id'];
			$dept			= G_Employee_Subdivision_History_Finder::findByCompanyStructureId($department_id);
			if($department_id == "all") {
				$this->var['employees']	= G_Employee_Subdivision_History_Helper::getAllEmployeeCurrentSubdivision();
				$this->var['filename']	= 'daily_work_schedule_alldepartment.xls';
			} else {
				$this->var['employees']	= G_Employee_Subdivision_History_Helper::getAllEmployeeByCurrentSubdivision($department_id);
				if($dept){$dept_name = $dept->getName();}else{}
				$this->var['filename']	= 'daily_work_schedule_alldepartment_'.str_replace('/','',$dept_name).'.xls';
				
			}
			$this->view->render('reports/time_management/daily_work_schedule/download_daily_work_schedule.php', $this->var);
		}
	}
	
	function download_daily_work_scheduleBckup()
	{
		$department_id =  $_POST['department_id'];
		if($department_id=='all') {
			$all = 1;
			$sql = "SELECT 
			c.title as department,
			e.employee_code,
			concat(e.lastname, ', ', e.firstname, ' ', e.middlename) as employee_name,
			s.schedule_name,
			s.working_days,
			s.time_in,
			s.time_out
			
			 FROM 
			 g_employee e, 
			 g_employee_group_schedule gs,
			 g_schedule s, 
			 g_employee_subdivision_history d,
			 g_company_structure c
			 WHERE 
			 d.company_structure_id=c.id 
			 AND d.employee_id=e.id 
			 AND d.company_structure_id=gs.employee_group_id 
			 AND gs.employee_group=2 
			 AND gs.schedule_id=s.id
			 ORDER BY c.title, s.schedule_name, e.lastname,e.firstname
			 ";
			$schedule =  Model::runSql($sql,true);
			$filename = 'excel_all.php';
			
		}else {
			$all = 0;
			$d = G_Company_Structure_Finder::findById($department_id);
			$this->var['department'] = $d->getTitle();

			$sql = "SELECT 
			e.employee_code,
			concat(e.lastname, ', ', e.firstname, ' ', e.middlename) as employee_name,
			s.schedule_name,
			s.working_days,
			s.time_in,
			s.time_out
			 FROM 
			 g_employee e, 
			 g_employee_group_schedule gs,
			 g_schedule s, 
			 g_employee_subdivision_history d
			 WHERE 
			 d.company_structure_id=".$department_id."
			 AND d.employee_id=e.id 
			 AND d.company_structure_id=gs.employee_group_id AND gs.employee_group=2 AND gs.schedule_id=s.id
			 ORDER BY s.schedule_name, e.lastname,e.firstname
			 ";
			$schedule =  Model::runSql($sql,true);
			$filename = 'excel_by_department.php';
		}
		echo $sql;
		exit;
		$this->var['is_all'] = $all;
		$this->var['data'] = $schedule;
		$this->view->render('reports/time_management/daily_work_schedule/'.$filename, $this->var);
	}
	
	//load attendance_absence_data
	function _load_attendance_absence_data()
	{
		$this->validatePermission(G_Sprint_Modules::HR,'reports','absences');

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();
		$project_site = G_PROJECT_SITE::findAllProjectSite();
		$this->var['departments'] = G_Company_Structure_Finder::findAllDepartmentsIsNotArchiveByParentId($this->company_structure_id);
		$this->var['job']         = $j;
		$this->var['title']       = "Absences";
		$this->var['project_site'] = $project_site;
		$this->view->noTemplate();
		$this->view->render('reports/time_management/attendance_absence_data/index.php',$this->var);	
	}
	
	function download_attendance_absence_data()
	{
		$data = $_POST;

		$remove_resigned   = false;
		$remove_terminated = false;
		$remove_endo       = false;
		$remove_inactive   = false;
		$qry_employee_type = '';

	    if( isset($data['absences_remove_resigned']) && $data['absences_remove_resigned'] == 1 ){
	      $remove_resigned   = true;
	    }
	    if( isset($data['absences_remove_terminated']) && $data['absences_remove_terminated'] == 1 ){
	      $remove_terminated = true;  
	    }
	    if( isset($data['absences_remove_endo']) && $data['absences_remove_endo'] == 1 ){
	      $remove_endo = true;  
	    }
	    if( isset($data['absences_remove_inactive']) && $data['absences_remove_inactive'] == 1 ){
	      $remove_inactive = true;  
	    }		

		if($_POST['date_from'] && $_POST['date_to']){

			if( $remove_resigned ){       
				$qry_add_on[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
			}

			if( $remove_terminated ){
				$qry_add_on[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
			}

			if( $remove_endo ){
				$qry_add_on[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
			}

			if( $remove_inactive ){
				$qry_add_on[] = "(e.inactive_date = '0000-00-00' OR e.inactive_date = '')";
			}

			if( !empty($qry_add_on) ){
				$is_additional_qry .= " AND " . implode(" AND ", $qry_add_on);
			}

			$attendance = new G_Attendance();			
			if($_POST['report_type'] == DETAILED){			
				$halfday = $attendance->getAttendanceHalfdayDataDistinct($_POST, $is_additional_qry);
				$abs = $attendance->getAttendanceAbsenceData($_POST, $is_additional_qry);
				$halfday_and_absent = array_merge($abs,$halfday);
				$absences = $halfday_and_absent;
				if($halfday == null && !$halfday){
					$absences = $abs;
				}elseif ($abs == null && !$abs) {
					$absences = $halfday;
				}
				
			}else{
				$absences = $attendance->countAttendanceAbsenceData($_POST, $is_additional_qry);
			}		
		

			if($data['project_site_id'] != 'all'){
				 $project = G_Project_Site_Finder::findById($data['project_site_id']);
				 if($project){
				 	$this->var['filter_by'] = "<strong>".$project->getprojectname()."</strong>";
				 }
			}
			else{
				$this->var['filter_by'] = "<strong>All</strong>";
			}

			$this->var['filename']         = 'absences.xls';
			$this->var['date_from']		   = $_POST['date_from'];
			$this->var['date_to']		   = $_POST['date_to'];
			$this->var['report_body_type'] = $_POST['report_type'];			
			$this->var['absences']         = $absences;
			$this->view->render('reports/time_management/attendance_absence_data/excel_download.php', $this->var);	
			
			//General Reports / Shr Audit Trail
	        $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_GENERATE, ' Absences Reports ', 'Absences', $_POST['date_from'], $_POST['date_to'], 1, $_POST['position_applied'], $_POST['department_applied']);
		}
	}
	
	function download_tardiness_data()
	{
		$data = $_POST;

		$remove_resigned   = false;
		$remove_terminated = false;
		$remove_endo       = false;
		$remove_inactive   = false;
		$qry_employee_type = '';		

		if( isset($data['tardiness_remove_resigned']) && $data['tardiness_remove_resigned'] == 1 ){
			$remove_resigned   = true;
		}
		if( isset($data['tardiness_remove_terminated']) && $data['tardiness_remove_terminated'] == 1 ){
			$remove_terminated = true;  
		}
		if( isset($data['tardiness_remove_endo']) && $data['tardiness_remove_endo'] == 1 ){
			$remove_endo = true;  
		}
		if( isset($data['tardiness_remove_inactive']) && $data['tardiness_remove_inactive'] == 1 ){
			$remove_inactive = true;  
		}


		if($_POST['date_from'] && $_POST['date_to']){

			if( $remove_resigned ){       
				$qry_add_on[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
			}

			if( $remove_terminated ){
				$qry_add_on[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
			}

			if( $remove_endo ){
				$qry_add_on[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
			}

			if( $remove_inactive ){
				$qry_add_on[] = "(e.inactive_date = '0000-00-00' OR e.inactive_date = '')";
			}

			if( !empty($qry_add_on) ){
				$is_additional_qry .= " AND " . implode(" AND ", $qry_add_on);
			}

			$attendance = new G_Attendance();			
			if($_POST['report_type'] == DETAILED){			
				$absences = $attendance->getTardinessData($_POST, $is_additional_qry);
			}else{
				$absences = $attendance->countTardinessData($_POST, $is_additional_qry);
			}



			if($data['project_site_id'] != 'all'){
				 $project = G_Project_Site_Finder::findById($data['project_site_id']);
				 if($project){
				 	$this->var['filter_by'] = "<strong>".$project->getprojectname()."</strong>";
				 }
			}
			else{
				$this->var['filter_by'] = "<strong>All</strong>";
			}

			$this->var['filename']         = 'tardiness.xls';
			$this->var['date_from']		   = $_POST['date_from'];
			$this->var['date_to']		   = $_POST['date_to'];
			$this->var['report_body_type'] = $_POST['report_type'];			
			$this->var['absences']         = $absences;
			$this->view->render('reports/time_management/tardiness/excel_download.php', $this->var);	

			//General Reports / Shr Audit Trail
	        $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_GENERATE, ' Late Reports ', 'Late', $_POST['date_from'], $_POST['date_to'], 1, $_POST['position_applied'], $_POST['department_applied']);
			
		}
	}

	function download_shift_schedule_data()
	{
		$data = $_POST;

		$remove_resigned   = false;
		$remove_terminated = false;
		$remove_endo       = false;
		$remove_inactive   = false;
		$qry_employee_type = '';		

		if( isset($data['shift_schedule_remove_resigned']) && $data['shift_schedule_remove_resigned'] == 1 ){
			$remove_resigned   = true;
		}
		if( isset($data['shift_schedule_remove_terminated']) && $data['shift_schedule_remove_terminated'] == 1 ){
			$remove_terminated = true;  
		}
		if( isset($data['shift_schedule_remove_endo']) && $data['shift_schedule_remove_endo'] == 1 ){
			$remove_endo = true;  
		}
		if( isset($data['shift_schedule_remove_inactive']) && $data['shift_schedule_remove_inactive'] == 1 ){
			$remove_inactive = true;  
		}


		if($_POST['shift_schedule_date_from'] && $_POST['shift_schedule_date_to']){
			$date_from = date("Y-m-d",strtotime($_POST['shift_schedule_date_from']));
			$date_to   = date("Y-m-d",strtotime($_POST['shift_schedule_date_to']));

			if( $remove_resigned ){       
				$qry_add_on[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
			}

			if( $remove_terminated ){
				$qry_add_on[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
			}

			if( $remove_endo ){
				$qry_add_on[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
			}

			if( $remove_inactive ){
				$qry_add_on[] = "(e.inactive_date = '0000-00-00' OR e.inactive_date = '')";
			}

			if( !empty($qry_add_on) ){
				$is_additional_qry .= " AND " . implode(" AND ", $qry_add_on);
			}

			$report = new G_Report();
			$data   = $report->shiftScheduleReport($date_from, $date_to, $is_additional_qry);

			$this->var['data']			   = $data;
			$this->var['report_type']      = $_POST['shift_type'];	
			$this->var['filename']         = 'shift_schedule.xls';
			$this->var['date_from']		   = $date_from;						
			$this->var['date_to']		   = $date_to;						
			$this->view->render('reports/time_management/shift_schedule/excel_download.php', $this->var);

			if($_POST['shift_type'] == 'ds'){
				$shift_type = 'Day Shift';
			}
			elseif($_POST['shift_type'] == 'ns'){
				$shift_type = 'Night Shift';
			}
			elseif($_POST['shift_type'] == 'ns_ds'){
				$shift_type = 'Both';
			}

			//General Reports / Shr Audit Trail
	        $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_GENERATE, ' Shift Schedule Reports ', $shift_type, $_POST['shift_schedule_date_from'], $_POST['shift_schedule_date_to'], 1, '','');	
			
		}
	}

	function download_manpower_count_data_depre()
	{
		if($_POST['date_from'] && $_POST['date_to']){
			$employee = new G_Employee();	
			$manpower_count = $employee->getManpowerCountData($_POST);		
			
			$this->var['filename']         = 'manpower_count.xls';
			$this->var['date_from']		   = $_POST['date_from'];
			$this->var['date_to']		   = $_POST['date_to'];		
			$this->var['manpower_count']   = $manpower_count;
			$this->view->render('reports/time_management/manpower_count/excel_download.php', $this->var);	
			
		}
	}

	function download_manpower_count_data_depre2()
	{			
		if($_POST['date_from'] && $_POST['date_to']){	
			
			$data          = $_POST;
			$dept_id       = Utilities::decrypt($_POST['manpower']['department_id']);
			$date_from     = $data['date_from'];
			$date_to       = $data['date_to'];
			$manpower_data = $data['manpower'];
			$report_type   = $data['manpower']['report_type'];

			$report = new G_Report();
			$report->setFromDate($date_from);
			$report->setToDate($date_to);
			$result = $report->generateManpowerReport($manpower_data);
			
			
			$fields = array('title');
			$cs = new G_Company_Structure();
			$cs->setId($dept_id);
			$dept = $cs->getDepartmentDetailsById($fields);

			$educations_array = $data['manpower']['educational_courses'];
			$educations       = implode(", ", $educations_array);

			$skills_array = $data['manpower']['skills'];
			$skills       = implode(", ", $skills_array);

			$employment_status_array = $data['manpower']['employment_status'];
			$employment_status       = implode(", ", $employment_status_array);

			$gender_array    = $data['manpower']['gender'];

			$header_title    = ($report_type != G_Report::REPORT_OPT1 ? "{$report_type} Manpower Count" : "Manpower Count");
			$department_name = strtoupper($dept['title']);			

			$this->var['num_gender']   = count($gender_array);		
			$this->var['gender_array'] = $gender_array;			
			$this->var['header_department_name'] = $department_name;
			$this->var['header_title'] 			 = strtoupper($header_title);
			$this->var['employment_status']      = strtoupper($employment_status);
			$this->var['cols_employment_status'] = $employment_status_array;
			$this->var['educations']   = strtoupper($educations);
			$this->var['skills']	   = strtoupper($skills);
			$this->var['filename']     = 'manpower_count.xls';
			$this->var['date_from']	   = $date_from;
			$this->var['date_to']	   = $date_to;		
			$this->var['data']         = $result;
			$this->var['employee_listing'] = $manpower_data['employee_listing'];
			$this->view->render('reports/time_management/manpower_count/excel_download.php', $this->var);	
			
		}
	}

	function download_manpower_count_data()
	{		
		
		if($_POST['date_from'] && $_POST['date_to']){				
			$data          = $_POST;
			$dept_id       = Utilities::decrypt($_POST['manpower']['department_id']);
			$date_from     = $data['date_from'];
			$date_to       = $data['date_to'];
			$manpower_data = $data['manpower'];
			$report_type   = $data['r_type'];

			if( isset($data['manpower_remove_resigned']) && $data['manpower_remove_resigned'] == 1 ){
				$remove_resigned   = true;
			}
			if( isset($data['manpower_remove_terminated']) && $data['manpower_remove_terminated'] == 1 ){
				$remove_terminated = true;  
			}
			if( isset($data['manpower_remove_endo']) && $data['manpower_remove_endo'] == 1 ){
				$remove_endo = true;  
			}
			if( isset($data['manpower_remove_inactive']) && $data['manpower_remove_inactive'] == 1 ){
				$remove_inactive = true;  
			}		

			if( $remove_resigned ){
				$qry_add_on[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
			}

			if( $remove_terminated ){
				$qry_add_on[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
			}

			if( $remove_endo ){
				$qry_add_on[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
			}

			if( $remove_inactive ){
				$qry_add_on[] = "(e.inactive_date = '0000-00-00' OR e.inactive_date = '')";
			}		

			if( !empty($qry_add_on) ){
				$is_additional_qry .= " AND " . implode(" AND ", $qry_add_on);
			}		

			$report = new G_Report();
			$report->setFromDate($date_from);
			$report->setToDate($date_to);
			$result = $report->generateManpowerReport($manpower_data, $is_additional_qry);

			if($report_type == 'detailed'){

				$remove_resigned    = false;
				$remove_terminated  = false;
				$remove_endo        = false;
				$remove_inactive    = false;
				$is_additional_qryd2 = '';
				$qry_add_ond2        = '';

				$qry_add_ond[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
				$qry_add_ond[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
				$qry_add_ond[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
				$qry_add_ond[] = "(e.inactive_date = '0000-00-00' OR e.inactive_date = '')";				

				if( isset($data['manpower_remove_resigned']) && $data['manpower_remove_resigned'] == 1 ){
					$remove_resigned   = true;
				}
				if( isset($data['manpower_remove_terminated']) && $data['manpower_remove_terminated'] == 1 ){
					$remove_terminated = true;  
				}
				if( isset($data['manpower_remove_endo']) && $data['manpower_remove_endo'] == 1 ){
					$remove_endo = true;  
				}
				if( isset($data['manpower_remove_inactive']) && $data['manpower_remove_inactive'] == 1 ){
					$remove_inactive = true;  
				}		

				$sql_from = date("Y-m-d",strtotime($date_from));
				$sql_to   = date("Y-m-d",strtotime($date_to));				

				if( !$remove_resigned ){
					$qry_add_ond2[] = "( (e.resignation_date != '0000-00-00' OR e.resignation_date != '') AND (e.resignation_date <=  ". Model::safeSql($sql_to) ."  AND e.resignation_date >= " . Model::safeSql($sql_from) . ") )";
				}

				if( !$remove_terminated ){
					$qry_add_ond2[] = "( (e.terminated_date != '0000-00-00' OR e.terminated_date != '') AND (e.terminated_date <=  ". Model::safeSql($sql_to) ."  AND e.terminated_date >= " . Model::safeSql($sql_from) . ") )";
				}

				if( !$remove_endo ){
					$qry_add_ond2[] = "( (e.endo_date != '0000-00-00' OR e.endo_date != '') AND (e.endo_date <=  ". Model::safeSql($sql_to) ."  AND e.endo_date >= " . Model::safeSql($sql_from) . ") )";
				}

				if( !$remove_inactive ){
					$qry_add_ond2[] = "( (e.inactive_date != '0000-00-00' OR e.inactive_date != '') AND (e.inactive_date <=  ". Model::safeSql($sql_to) ."  AND e.inactive_date >= " . Model::safeSql($sql_from) . ") )";
				}						

				if( !empty($qry_add_ond) ){
					$is_additional_qryd .= " AND " . implode(" AND ", $qry_add_ond);
				}		

				if( !empty($qry_add_ond2) ){
					$is_additional_qryd2 .= " AND " . implode(" OR ", $qry_add_ond2);
				}									

				//$result = $report->generateManpowerReportDetailed($manpower_data, $is_additional_qryd, $is_additional_qryd2);
				$result = $report->generateManpowerReportDetailedPerSection($manpower_data, $is_additional_qryd, $is_additional_qryd2);

			}else{				

				if( isset($data['manpower_remove_resigned']) && $data['manpower_remove_resigned'] == 1 ){
					$remove_resigned   = true;
				}
				if( isset($data['manpower_remove_terminated']) && $data['manpower_remove_terminated'] == 1 ){
					$remove_terminated = true;  
				}
				if( isset($data['manpower_remove_endo']) && $data['manpower_remove_endo'] == 1 ){
					$remove_endo = true;  
				}
				if( isset($data['manpower_remove_inactive']) && $data['manpower_remove_inactive'] == 1 ){
					$remove_inactive = true;  
				}		

				if( $remove_resigned ){
					$qry_add_on[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
				}

				if( $remove_terminated ){
					$qry_add_on[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
				}

				if( $remove_endo ){
					$qry_add_on[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
				}

				if( $remove_inactive ){
					$qry_add_on[] = "(e.inactive_date = '0000-00-00' OR e.inactive_date = '')";
				}		

				if( !empty($qry_add_on) ){
					$is_additional_qry .= " AND " . implode(" AND ", $qry_add_on);
				}	

				$result = $report->generateManpowerReport($manpower_data, $is_additional_qry);

			}			

			$company_name = G_Company_Structure_Helper::sqlCompanyName();	
	    	$company_info = G_Company_Structure_Helper::sqlCompanyInfo();

			$date_prev = date("F Y",strtotime($date_from . " -1 month"));

			$this->var['report_type']  = $report_type;
			$this->var['date_prev']    = $date_prev;
			$this->var['data']         = $result;
			$this->var['filename']     = 'manpower_count.xls';
			$this->var['header_title'] = "MANPOWER REPORT";
			$this->var['date_from']	   = $date_from;
			$this->var['date_to']	   = $date_to;		
			$this->var['company_name'] = $company_name;
			$this->var['company_info'] = $company_info;					
			$this->view->render('reports/time_management/manpower_count/excel_download.php', $this->var);	

			//General Reports / Shr Audit Trail
	        $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_GENERATE, 'Manpower Count Reports ', $report_type, $date_from, $date_to, 1, '','');
			
		}
	}
    
    function download_end_of_contract_data()
	{
		$data = $_POST;

	    $remove_resigned   = false;
	    $remove_terminated = false;
	    $remove_endo       = false;
	    $remove_inactive   = false;
	    $qry_employee_type = '';

		if( isset($data['eoc_remove_resigned']) && $data['eoc_remove_resigned'] == 1 ){
			$remove_resigned   = true;
		}
		if( isset($data['eoc_remove_terminated']) && $data['eoc_remove_terminated'] == 1 ){
			$remove_terminated = true;  
		}
		if( isset($data['eoc_remove_endo']) && $data['eoc_remove_endo'] == 1 ){
			$remove_endo = true;  
		}
		if( isset($data['eoc_remove_inactive']) && $data['eoc_remove_inactive'] == 1 ){
			$remove_inactive = true;  
		}	    

		if($_POST['date_from'] && $_POST['date_to']){

			if( $remove_resigned ){
				$qry_add_on[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
			}

			if( $remove_terminated ){
				$qry_add_on[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
			}

			if( $remove_endo ){
				$qry_add_on[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
			}

			if( $remove_inactive ){
				$qry_add_on[] = "(e.inactive_date = '0000-00-00' OR e.inactive_date = '')";
			}

			if( !empty($qry_add_on) ){
			$is_additional_qry .= " AND " . implode(" AND ", $qry_add_on);
			}

			$e = new G_Employee();			
			if($_POST['report_type'] == DETAILED){			
				$end_of_contract = $e->getEndOfContractData($_POST, $is_additional_qry);
			}else{
				//$end_of_contract = $e->countEndOfContractData($_POST);
			}

			if($data['project_site_id'] != 'all'){
				 $project = G_Project_Site_Finder::findById($data['project_site_id']);
				 if($project){
				 	$this->var['filter_by'] = "<strong>".$project->getprojectname()."</strong>";
				 }
			}
			else{
				$this->var['filter_by'] = "<strong>All</strong>";
			}

			$this->var['filename']         = 'end_of_contract.xls';
			$this->var['date_from']		   = $_POST['date_from'];
			$this->var['date_to']		   = $_POST['date_to'];
			$this->var['report_body_type'] = $_POST['report_type'];			
			$this->var['end_of_contract']  = $end_of_contract;
			$this->view->render('reports/time_management/end_of_contract/excel_download.php', $this->var);	

			//General Reports / Shr Audit Trail
	        $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_GENERATE, 'End of Contract Reports ', $_POST['search_field'], $_POST['date_from'], $_POST['date_to'], 1, '',$_POST['department_applied']);
			
		}
	}

	function download_resigned_employees_data()
	{
		if($_POST['date_from'] && $_POST['date_to']){
			$e = new G_Employee();			
			$resigned_employees = $e->getResignedEmployeesData($_POST);	

			if($_POST['project_site_id'] != 'all'){
				 $project = G_Project_Site_Finder::findById($_POST['project_site_id']);
				 if($project){
				 	$this->var['filter_by'] = "<strong>".$project->getprojectname()."</strong>";
				 }
			}
			else{
				$this->var['filter_by'] = "<strong>All</strong>";
			}


			$this->var['filename']  = 'resigned_employees.xls';
			$this->var['date_from']	= $_POST['date_from'];
			$this->var['date_to']	= $_POST['date_to'];				
			$this->var['data']  	= $resigned_employees;
			$this->view->render('reports/time_management/resigned_employees/excel_download.php', $this->var);	

			//General Reports / Shr Audit Trail
	        $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_GENERATE, 'Resigned Employees Reports ', $_POST['search_field'], $_POST['date_from'], $_POST['date_to'], 1, '','');
			
		}
	}

	function download_terminated_employees_data()
	{
		if($_POST['date_from'] && $_POST['date_to']){
			$e = new G_Employee();			
			$terminated_employees = $e->getTerminatedEmployeesData($_POST);		

			if($_POST['project_site_id'] != 'all'){
				 $project = G_Project_Site_Finder::findById($_POST['project_site_id']);
				 if($project){
				 	$this->var['filter_by'] = "<strong>".$project->getprojectname()."</strong>";
				 }
			}
			else{
				$this->var['filter_by'] = "<strong>All</strong>";
			}

			$this->var['filename']  = 'terminated_employees.xls';
			$this->var['date_from']	= $_POST['date_from'];
			$this->var['date_to']	= $_POST['date_to'];				
			$this->var['data']  	= $terminated_employees;
			$this->view->render('reports/time_management/terminated_employees/excel_download.php', $this->var);	

			//General Reports / Shr Audit Trail
	        $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_GENERATE, 'Terminated Employees Reports', $_POST['search_field'], $_POST['date_from'], $_POST['date_to'], 1, '','');
			
		}
	}
    
    function download_daily_time_record_data()
	{
		$data = $_POST;

		$remove_resigned   = false;
		$remove_terminated = false;
		$remove_endo       = false;
		$remove_inactive   = false;
		$qry_employee_type = '';

		if( isset($data['dtr_remove_resigned']) && $data['dtr_remove_resigned'] == 1 ){
			$remove_resigned   = true;
		}
		if( isset($data['dtr_remove_terminated']) && $data['dtr_remove_terminated'] == 1 ){
			$remove_terminated = true;  
		}
		if( isset($data['dtr_remove_endo']) && $data['dtr_remove_endo'] == 1 ){
			$remove_endo = true;  
		}
		if( isset($data['dtr_remove_inactive']) && $data['dtr_remove_inactive'] == 1 ){
			$remove_inactive = true;  
		}		

		if($_POST['date_from'] && $_POST['date_to']){

			if( $remove_resigned ){
				$qry_add_on[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
			}

			if( $remove_terminated ){
				$qry_add_on[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
			}

			if( $remove_endo ){
				$qry_add_on[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
			}

			if( $remove_inactive ){
			$qry_add_on[] = "(e.inactive_date = '0000-00-00' OR e.inactive_date = '')";
			}

			if( !empty($qry_add_on) ){
				$is_additional_qry .= " AND " . implode(" AND ", $qry_add_on);
			}

			$e = new G_Employee();			

			switch($_POST['report_type'])
			{
				case DETAILED:
					$daily_time_record = $e->getDailyTimeRecordData($_POST, $is_additional_qry);				
					break;
				case SUMMARIZED:
					$daily_time_record = $e->getDailyTimeRecordSummarizedData($_POST, $is_additional_qry);	
					break;
				case INCOMPLETE_BREAK_LOGS:
					list($daily_time_record, $max_breaks) = $e->getDailyTimeRecordIncompleteBreakLogs($_POST, $is_additional_qry);	
					break;
				case NO_BREAK_LOGS:
					list($daily_time_record, $max_breaks) = $e->getDailyTimeRecordNoBreakLogs($_POST, $is_additional_qry);	
					break;
				case EARLY_BREAK_OUT:
					list($daily_time_record, $max_breaks) = $e->getDailyTimeRecordEarlyBreakOut($_POST, $is_additional_qry);	
					break;
				case LATE_BREAK_IN:
					list($daily_time_record, $max_breaks) = $e->getDailyTimeRecordLateBreakIn($_POST, $is_additional_qry);	
					break;
				default;
					$daily_time_record = $e->getDailyTimeRecordSummarizedData($_POST, $is_additional_qry);	
					break;
			}
		
			$form_name = "excel_download.php";


			if($data['project_site_id'] != 'all'){
				 $project = G_Project_Site_Finder::findById($data['project_site_id']);
				 if($project){
				 	$this->var['filter_by'] = "<strong>".$project->getprojectname()."</strong>";
				 }
			}
			else{
				$this->var['filter_by'] = "<strong>All</strong>";
			}

			$this->var['filename']         = 'dtr.xls';
			$this->var['date_from']		   = $_POST['date_from'];
			$this->var['date_to']		   = $_POST['date_to'];
			$this->var['report_body_type'] = $_POST['report_type'];			
			$this->var['daily_time_record']  = $daily_time_record;
			$this->var['max_breaks']  = $max_breaks;

			$this->view->render("reports/time_management/daily_time_record/{$form_name}", $this->var);

			//General Reports / Shr Audit Trail
	        $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_GENERATE, 'Daily Time Record Reports ', $_POST['report_type'], $_POST['date_from'], $_POST['date_to'], 1, '',$_POST['department_applied']);	
			
		}
	}
    
    function download_incomplete_time_in_out_data()
	{
		$data = $_POST;

	    $remove_resigned   = false;
	    $remove_terminated = false;
	    $remove_endo       = false;
	    $remove_inactive   = false;
	    $qry_employee_type = '';

		if( isset($data['incinout_remove_resigned']) && $data['incinout_remove_resigned'] == 1 ){
			$remove_resigned   = true;
		}
		if( isset($data['incinout_remove_terminated']) && $data['incinout_remove_terminated'] == 1 ){
			$remove_terminated = true;  
		}
		if( isset($data['incinout_remove_endo']) && $data['incinout_remove_endo'] == 1 ){
			$remove_endo = true;  
		}
		if( isset($data['incinout_remove_inactive']) && $data['incinout_remove_inactive'] == 1 ){
			$remove_inactive = true;  
		}	    		

		if($_POST['date_from'] && $_POST['date_to']){

			if( $remove_resigned ){
				$qry_add_on[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
			}

			if( $remove_terminated ){
				$qry_add_on[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
			}

			if( $remove_endo ){
				$qry_add_on[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
			}

			if( $remove_inactive ){
				$qry_add_on[] = "(e.inactive_date = '0000-00-00' OR e.inactive_date = '')";
			}

			if( !empty($qry_add_on) ){
				$is_additional_qry .= " AND " . implode(" AND ", $qry_add_on);
			}

			$incomplete_time_in_out = array();
			$e = new G_Employee();			
			if($_POST['report_type'] == DETAILED){			
				$incomplete_time_in_out = $e->getIncompleteTimeInOutData($_POST, $is_additional_qry);
			}

			//utilities::displayArray($incomplete_time_in_out);exit();
			if($data['project_site_id'] != 'all'){
				 $project = G_Project_Site_Finder::findById($data['project_site_id']);
				 if($project){
				 	$this->var['filter_by'] = "<strong>".$project->getprojectname()."</strong>";
				 }
			}
			else{
				$this->var['filter_by'] = "<strong>All</strong>";
			}


			$this->var['date_from']		   = $_POST['date_from'];
			$this->var['date_to']		   = $_POST['date_to'];
			$this->var['report_body_type'] = $_POST['report_type'];			
			$this->var['incomplete_time_in_out']  = $incomplete_time_in_out;
			$this->var['filename']         = "incomplete_time_in_out.xls";
			$this->view->render('reports/time_management/incomplete_time_in_out/excel_download.php', $this->var);

			//General Reports / Shr Audit Trail
	        $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_GENERATE, 'Incomplete Time In / Out Reports ', $_POST['search_field'], $_POST['date_from'], $_POST['date_to'], 1, '',$_POST['department_applied']);		
			
		}
	}

	function download_incorrect_shift_data()
	{		
		$data = $_POST;

		$remove_resigned   = false;
		$remove_terminated = false;
		$remove_endo       = false;
		$remove_inactive   = false;
		$qry_employee_type = '';

		if( isset($data['incshift_remove_resigned']) && $data['incshift_remove_resigned'] == 1 ){
			$remove_resigned   = true;
		}
		if( isset($data['incshift_remove_terminated']) && $data['incshift_remove_terminated'] == 1 ){
			$remove_terminated = true;  
		}
		if( isset($data['incshift_remove_endo']) && $data['incshift_remove_endo'] == 1 ){
			$remove_endo = true;  
		}
		if( isset($data['incshift_remove_inactive']) && $data['incshift_remove_inactive'] == 1 ){
			$remove_inactive = true;  
		}		

		if($_POST['date_from'] && $_POST['date_to']){
			$employee_ids 		   = $data['employee_id'];
			$dept_section_ids      = $data['dept_section_id'];
			$employment_status_ids = $data['employment_status_id'];
			$date_from = $data['date_from'];
			$date_to   = $data['date_to'];

			if( $remove_resigned ){
				$qry_add_on[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
			}

			if( $remove_terminated ){
				$qry_add_on[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
			}

			if( $remove_endo ){
				$qry_add_on[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
			}

			if( $remove_inactive ){
				$qry_add_on[] = "(e.inactive_date = '0000-00-00' OR e.inactive_date = '')";
			}

			if( !empty($qry_add_on) ){
				$is_additional_qry .= " AND " . implode(" AND ", $qry_add_on);
			}			

			$rep = new G_Report();
			$rep->setFromDate($date_from);
			$rep->setToDate($date_to);
			$rep->setEmployeeIds($employee_ids);
			$rep->setDepartmentIds($dept_section_ids);
			$rep->setEmploymentStatusIds($employment_status_ids);
			
			if( isset($data['all_employees']) && $data['all_employees'] ){
				//$incorrect_shifts = $rep->allIncorrectShift();
				$incorrect_shifts = $rep->allIncorrectShiftWithAddQuery( $is_additional_qry );
			}else{						
				//$incorrect_shifts = $rep->incorrectShift();	
				$incorrect_shifts = $rep->incorrectShiftWithAddQuery( $is_additional_qry );	
			}

			$this->var['date_from']	= $data['date_from'];
			$this->var['date_to']	= $data['date_to'];		
			$this->var['data']  	= $incorrect_shifts;
			$this->var['filename']  = "incorrect_shift.xls";
			$this->view->render('reports/time_management/incorrect_shift/excel_download.php', $this->var);	

			//General Reports / Shr Audit Trail
		    $shr_emp = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($employee_ids));
			$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
	        $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_GENERATE, 'Incorrect Shift Reports', $emp_name, $_POST['date_from'], $_POST['date_to'], 1, $shr_emp['position'],$shr_emp['department']);
			
		}
	}

	function download_loan_data_depre()
	{		
		$data = $_POST;

	    $remove_resigned   = false;
	    $remove_terminated = false;
	    $remove_endo       = false;
	    $remove_inactive   = false;
	    $qry_employee_type = '';

	    if( isset($data['loan_remove_resigned']) && $data['loan_remove_resigned'] == 1 ){
	    	$remove_resigned   = true;
	    }
	    if( isset($data['loan_remove_terminated']) && $data['loan_remove_terminated'] == 1 ){
	    	$remove_terminated = true;  
	    }
	    if( isset($data['loan_remove_endo']) && $data['loan_remove_endo'] == 1 ){
	    	$remove_endo = true;  
	    }
	    if( isset($data['loan_remove_inactive']) && $data['loan_remove_inactive'] == 1 ){
	    	$remove_inactive = true;  
	    }   

	    if( $remove_resigned ){
	    	$qry_add_on[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
	    }

	    if( $remove_terminated ){
	    	$qry_add_on[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
	    }

	    if( $remove_endo ){
	    	$qry_add_on[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
	    }

	    if( $remove_inactive ){
	    	$qry_add_on[] = "(e.inactive_date = '0000-00-00' OR e.inactive_date = '')";
	    }

	    if ( $data['cutoff_period'] ) {
	    	$cutoff_period = $data['cutoff_period'];
	    }

	    if( !empty($qry_add_on) ){
	    	$is_additional_qry .= " AND " . implode(" AND ", $qry_add_on);
	    }

	    $report = new G_Report();
	    $loans_data = $report->getEmployeesLoanData($data, $is_additional_qry, $cutoff_period);   
	    $loans_header_date = array_pop($loans_data);  

	    $a_cutoff   = explode("/", $cutoff_period);	    

	    if( $data['cutoff_period'] != '' ) {
	    	$hdr_period = date('F Y',strtotime($a_cutoff[0]));	    	
	    } else {
	    	$hdr_period = '';	
	    }
	    
	    if($data['loan_type'] != 'all'){
	    	$fields = array('loan_type');
	    	$lt     = G_Loan_Type_Helper::sqlGetLoanTypeDetailsById($data['loan_type'], $fields);	    	
	    	switch ($data['loan_type']) {
	    		case 4://SSS Loan
	    			$company_name = G_Company_Structure_Helper::sqlCompanyName();		    			
	    			$header       = "
	    				<table width=\"100%\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\" style=\"font-size:8pt; width:836pt; line-height:16pt;\">	
							<tr>
						    	<td colspan=\"5\" style=\"border:none; font-size:16pt;\" align=\"left\">
						        <strong>{$company_name}</strong>
						        </td>						    	
						    </tr>
						    <tr>
						    	<td style=\"border:none; font-size:16pt;\" align=\"left\">
						        <strong>" . $lt['loan_type'] . "</strong>
						        </td>						    	
						    </tr>      
						    <tr>
						    	<td style=\"border:none; font-size:16pt;mso-number-format:'\@';\" align=\"left\">
						        <strong>" . $hdr_period . "</strong>
						        </td>						    	
						    </tr>      
						</table>	
	    			";  
	    			$view_file = "sss_loan";			
	    			break;
	    		case 3://Pagibig Loan
	    			$company_name = G_Company_Structure_Helper::sqlCompanyName();	
	    			$company_info = G_Company_Structure_Helper::sqlCompanyInfo();

	    			$header = "
	    				<table width=\"100%\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\" style=\"font-size:8pt; width:836pt; line-height:16pt;\">	
							<tr>
						    	<td colspan=\"2\" style=\"border:none; font-size:16pt;\" align=\"left\">
						        <strong>Employer ID</strong>
						        </td>						    	
						        <td style=\"border:none; font-size:16pt;\" align=\"left\">
						        <strong>" . $company_info['pagibig_number'] . "</strong>
						        </td>						    	
						    </tr>
						    <tr>
						    	<td colspan=\"2\" style=\"border:none; font-size:16pt;\" align=\"left\">
						        <strong>Employer Name</strong>
						        </td>						    	
						        <td style=\"border:none; font-size:16pt;\" align=\"left\">
						        <strong>" . $company_name . "</strong>
						        </td>						    	
						    </tr>      
						    <tr>
						    	<td colspan=\"2\" style=\"border:none; font-size:16pt;\" align=\"left\">
						        <strong>Address</strong>
						        </td>						    	
						        <td colspan=\"6\" style=\"border:none; font-size:16pt;\" align=\"left\">
						        <strong>" . $company_info['address'] . "</strong>
						        </td>						    	
						    </tr>
						    <tr>
						    	<td style=\"border:none; font-size:16pt;\" align=\"left\">
						        <strong>" . $lt['loan_type'] . "</strong>
						        </td>						    	
						    </tr>						    
						    <tr>
						    	<td style=\"border:none; font-size:16pt;mso-number-format:'\@';\" align=\"left\">
						        <strong>" . $hdr_period . "</strong>
						        </td>						    	
						    </tr> 						          
						</table>
	    			";
	    			$view_file = "pagibig_loan";
	    			break;
	    		default:	  
	    			$company_name = G_Company_Structure_Helper::sqlCompanyName();	  			
	    			$header  = "
	    				<table width=\"100%\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\" style=\"font-size:8pt; width:836pt; line-height:16pt;\">	
							<tr>
						    	<td style=\"border:none; font-size:16pt;\" align=\"left\">
						        <strong>{$company_name}</strong>
						        </td>						    	
						    </tr>
						    <tr>
						    	<td style=\"border:none; font-size:16pt;\" align=\"left\">
						        <strong>" . $lt['loan_type'] . "</strong>
						        </td>						    	
						    </tr>      
						    <tr>
						    	<td style=\"border:none; font-size:16pt;mso-number-format:'\@';\" align=\"left\">
						        <strong>" . $hdr_period . "</strong>
						        </td>						    	
						    </tr>      
						</table>	
	    			";  
	    			$view_file = "default";
	    			break;
	    	}
	    }else{
	    	$company_name = G_Company_Structure_Helper::sqlCompanyName();	  			
			$header  = "
				<table width=\"100%\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\" style=\"font-size:8pt; width:836pt; line-height:16pt;\">	
					<tr>
				    	<td style=\"border:none; font-size:16pt;\" align=\"left\">
				        <strong>{$company_name}</strong>
				        </td>						    	
				    </tr>
				    <tr>
				    	<td style=\"border:none; font-size:16pt;\" align=\"left\">
				        <strong>All Loans</strong>
				        </td>						    	
				    </tr>      
				    <tr>
				    	<td style=\"border:none; font-size:16pt;mso-number-format:'\@';\" align=\"left\">
				        <strong>" . $hdr_period . "</strong>
				        </td>						    	
				    </tr>      
				</table>	
			";
			$view_file = "default";
	    }	 
	    $this->var['view_file']			= $view_file;
	    $this->var['header']            = $header;
	    $this->var['loans_header_date'] = $loans_header_date;
	    $this->var['filename']          = 'loan_report.xls';      
	    $this->var['loans_data']        = $loans_data;
	    $this->view->render('reports/loans/excel_download.php', $this->var);	
	}

	function download_loan_data()
	{
		$data = $_POST;

		$remove_resigned   = false;
		$remove_terminated = false;
		$remove_endo       = false;
		$remove_inactive   = false;
		$qry_employee_type = '';

		if( isset($data['loan_remove_resigned']) && $data['loan_remove_resigned'] == 1 ){
		$remove_resigned   = true;
		}
		if( isset($data['loan_remove_terminated']) && $data['loan_remove_terminated'] == 1 ){
		$remove_terminated = true;  
		}
		if( isset($data['loan_remove_endo']) && $data['loan_remove_endo'] == 1 ){
		$remove_endo = true;  
		}
		if( isset($data['loan_remove_inactive']) && $data['loan_remove_inactive'] == 1 ){
		$remove_inactive = true;  
		}   

		if( $remove_resigned ){
		$qry_add_on[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
		}

		if( $remove_terminated ){
		$qry_add_on[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
		}

		if( $remove_endo ){
		$qry_add_on[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
		}

		if($data['position_type'] != '' && $data['position_type'] != 'all'){
			$qry_add_on[] = "(gejh.end_date = '0000-00-00' OR gejh.end_date = '')";
		}	

		/*if($data['filter'] == 'all_managerial'){
			$qry_add_on[] = "(e.employee_type = 1)";
		}elseif($data['filter'] == 'all_non_managerial'){
			$qry_add_on[] = "(e.employee_type = 0)";
		}*/			

		/*
		if( $remove_inactive ){
		$qry_add_on[] = "(e.inactive_date = '0000-00-00' OR e.inactive_date = '')";
		}
		*/

		if( !empty($qry_add_on) ){
		$is_additional_qry .= " AND " . implode(" AND ", $qry_add_on);
		}

		$this->var['loan_type']	= $_POST['loan_type'];
		$this->var['filename']  = 'loan.xls';

		if($data['loan_report_type'] == 'default') {

			$data = $_POST;

			if($data['month'] != 'All' && $data['year'] != 'All') {
				
				$sdate = $data['year'] . '-' . str_pad($data['month'], 2, "0", STR_PAD_LEFT) . '-' . '01';
				$edate = $data['year'] . '-' . str_pad($data['month'], 2, "0", STR_PAD_LEFT) . '-' . '31';

				if($sdate) {
					$data['startdate'] = $sdate;
				}
				if($edate) {
					$data['enddate']   = $edate;
				}		

				$this->var['loans_start_date'] = date("F Y", strtotime($data['year'] . "-" . str_pad($data['month'], 2, "0", STR_PAD_LEFT) . "-01") ); 
			}			

			$l = new G_Employee_Loan();     
			$loans = $l->getLoanData($data, $is_additional_qry);

			/*
			//Group data by loan type
			$grouped_data = array();
			foreach( $loans as $loan ){
				$grouped_data[$loan['loan_type']][] = $loan;
			}
			*/

			//Group data by employee
			$grouped_data = array();
			foreach( $loans as $loan ){
				$grouped_data[$loan['employee_code']][] = $loan;
			}			

			$this->var['loans']            	= $grouped_data;
			$this->view->render('reports/time_management/loan/excel_download.php', $this->var); 

			//General Reports / Shr Audit Trail
	        $month = date("F", strtotime($_POST['month']));
	        $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_GENERATE, ' Loans Reports', $_POST['loan_report_type'].'-'.$_POST['loan_type'], $data['year'], $month, 1, '','');		

		}elseif($data['loan_report_type'] == 'semi_month_loan_reg') {

			if($data['payroll_period']) {
				$period = explode("/", $data['payroll_period']);

				if($period[0]) {
					$data['cutoff01_startdate'] = $period[0];
				}
				if($period[1]) {
					$data['cutoff02_enddate']   = $period[1];
				}					
			}

			/*$cutoff = G_Cutoff_Period::getValidCutOffPeriodsByMonthAndYear($data['month'], $data['year']);			
			if($cutoff && count($cutoff) == 2) {
				foreach($cutoff as $c_key => $c) {
					if($c->getCutoffNumber() == 1) {
						$data['cutoff01_startdate'] = $c->getStartDate();
					}
					if($c->getCutoffNumber() == 2) {
						$data['cutoff02_enddate']   = $c->getEndDate();
					}					
				}	
			}*/

			$l = new G_Employee_Loan();     
			$loans = $l->getLoanDataSemiMonthLoanRegister($data, $is_additional_qry);

			$grouped_data = array();
			foreach( $loans as $loan ){
				$grouped_data[$loan['loan_type']][] = $loan;
			}				

			$this->var['period'] = $data['cutoff01_startdate'] . " to " . $data['cutoff02_enddate'];
			$this->var['loans']  = $grouped_data;
			$this->view->render('reports/time_management/loan/semi_month_loan_reg_download.php', $this->var); 

			//General Reports / Shr Audit Trail
	        $month = date("F", strtotime($_POST['month']));
	        $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_GENERATE, ' Loans Reports ', $_POST['loan_report_type'].'-'.$_POST['loan_type'], $data['year'], $month, 1, '','');


		}elseif($data['loan_report_type'] == 'monthly_loan_reg') {

			$cutoff = G_Cutoff_Period::expectedCutOffPeriodsByMonthAndYear($data['month'], $data['year']);					
			//$cutoff = G_Cutoff_Period::getValidCutOffPeriodsByMonthAndYear($data['month'], $data['year']);					
			if($cutoff && count($cutoff) == 2) {
				foreach($cutoff as $c_key => $c) {
					if($c_key == 0) {
						$data['cutoff01_startdate'] = $c['start_date'];
					}
					if($c_key == 1) {
						$data['cutoff02_enddate']   = $c['end_date'];
					}					
				}
		
			}

			$l = new G_Employee_Loan();     
			$loans = $l->getLoanDataMonthlyLoanRegister($data, $is_additional_qry);			

			$grouped_data = array();
			foreach( $loans as $loan ){
				$grouped_data[$loan['loan_type']][] = $loan;
				//$grouped_data[$loan['loan_type']][$loan['deduction_type']][] = $loan;
			}

			$this->var['month'] = $data['month'];
			$this->var['year']  = $data['year'];
			$this->var['loans'] = $grouped_data;
			$this->view->render('reports/time_management/loan/monthly_loan_reg_download.php', $this->var); 		
			
			//General Reports / Shr Audit Trail
	        $month = date("F", strtotime($_POST['month']));
	        $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_GENERATE, ' Loans Reports ', $_POST['loan_report_type'].'-'.$_POST['loan_type'], $data['year'], $month, 1, '','');			
		}

	}
    
    function download_timesheet_data()
	{
		if($_POST['date_from'] && $_POST['date_to']){
			$e = new G_Employee();						
			$timesheet = $e->getTimesheetData($_POST);

			$this->var['filename']         = "timesheet.xls";
			$this->var['date_from']		   = $_POST['date_from'];
			$this->var['date_to']		   = $_POST['date_to'];
			$this->var['report_body_type'] = $_POST['report_type'];			
			$this->var['timesheet']  = $timesheet;			
			$this->view->render('reports/time_management/timesheet/excel_download.php', $this->var);

		}
	}
	
	function reports_download_timesheet_data()
	{
		$data = $_POST;

		$remove_resigned   = false;
		$remove_terminated = false;
		$remove_endo       = false;
		$remove_inactive   = false;
		$qry_employee_type = '';

		if( isset($data['timesheet_remove_resigned']) && $data['timesheet_remove_resigned'] == 1 ){
			$remove_resigned   = true;
		}
		if( isset($data['timesheet_remove_terminated']) && $data['timesheet_remove_terminated'] == 1 ){
			$remove_terminated = true;  
		}
		if( isset($data['timesheet_remove_endo']) && $data['timesheet_remove_endo'] == 1 ){
			$remove_endo = true;  
		}
		if( isset($data['timesheet_remove_inactive']) && $data['timesheet_remove_inactive'] == 1 ){
			$remove_inactive = true;  
		}		

		if($_POST['timesheet_date_from'] && $_POST['timesheet_date_to']){

			if( $remove_resigned ){
				$qry_add_on[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
			}

			if( $remove_terminated ){
				$qry_add_on[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
			}

			if( $remove_endo ){
				$qry_add_on[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
			}

			if( $remove_inactive ){
				$qry_add_on[] = "(e.inactive_date = '0000-00-00' OR e.inactive_date = '')";
			}

			if( !empty($qry_add_on) ){
				$is_additional_qry .= " AND " . implode(" AND ", $qry_add_on);
			}


			if($data['project_site_id'] != 'all'){
				 $project = G_Project_Site_Finder::findById($data['project_site_id']);
				 if($project){
				 	$this->var['filter_by'] = "<strong>".$project->getprojectname()."</strong>";
				 }
			}
			else{
				$this->var['filter_by'] = "<strong>All</strong>";
			}

			$e = new G_Employee();						
			$data = $_POST;
			$data['date_from'] = $data['timesheet_date_from'];
			$data['date_to']   = $data['timesheet_date_to'];

			$timesheet = $e->getTimesheetData($data, $is_additional_qry);
			$this->var['filename']         = "timesheet.xls";
			$this->var['date_from']		   = $_POST['timesheet_date_from'];
			$this->var['date_to']		   = $_POST['timesheet_date_to'];
			$this->var['report_body_type'] = $_POST['report_type'];			
			$this->var['timesheet']  = $timesheet;			
			$this->view->render('reports/time_management/timesheet/excel_download.php', $this->var);	

			//General Reports / Shr Audit Trail
	        $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_GENERATE, 'Timesheet Reports', $_POST['report_type'], $_POST['timesheet_date_from'], $_POST['timesheet_date_to'], 1, '',$_POST['department_applied']);	
		}
	}

	function download_disciplinary_data()
	{
		$data = $_POST;

	    $remove_resigned   = false;
	    $remove_terminated = false;
	    $remove_endo       = false;
	    $remove_inactive   = false;
	    $qry_employee_type = '';	
	    
		if( isset($data['disciplinary_remove_resigned']) && $data['disciplinary_remove_resigned'] == 1 ){
			$remove_resigned   = true;
		}
		if( isset($data['disciplinary_remove_terminated']) && $data['disciplinary_remove_terminated'] == 1 ){
			$remove_terminated = true;  
		}
		if( isset($data['disciplinary_remove_endo']) && $data['disciplinary_remove_endo'] == 1 ){
			$remove_endo = true;  
		}
		if( isset($data['disciplinary_remove_inactive']) && $data['disciplinary_remove_inactive'] == 1 ){
			$remove_inactive = true;  
		}	    	

		if($_POST['date_from'] && $_POST['date_to']){

			if( $remove_resigned ){
				$qry_add_on[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
			}

			if( $remove_terminated ){
				$qry_add_on[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
			}

			if( $remove_endo ){
				$qry_add_on[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
			}

			if( $remove_inactive ){
			$qry_add_on[] = "(e.inactive_date = '0000-00-00' OR e.inactive_date = '')";
			}

			if( !empty($qry_add_on) ){
			$is_additional_qry .= " AND " . implode(" AND ", $qry_add_on);
			}			

			$e = new G_Employee_Memo();
			$disciplinary_action = $e->getDisciplinaryData($_POST, $is_additional_qry);

			if($data['project_site_id'] != 'all'){
				 $project = G_Project_Site_Finder::findById($data['project_site_id']);
				 if($project){
				 	$this->var['filter_by'] = "<strong>".$project->getprojectname()."</strong>";
				 }
			}
			else{
				$this->var['filter_by'] = "<strong>All</strong>";
			}

			$this->var['filename']         = "disciplinary_action.xls";
			$this->var['date_from']		   = $_POST['date_from'];
			$this->var['date_to']		   = $_POST['date_to'];
			$this->var['disciplinary_action']  = $disciplinary_action;		
			$this->view->render('reports/disciplinary_action/excel_download.php', $this->var);

			//General Reports / Shr Audit Trail
			$shr_emp = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($_POST['employee_id']));
			$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
	        $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_GENERATE, 'Disciplinary Action Reports', $emp_name, $_POST['date_from'], $_POST['date_to'], 1, '',$shr_emp['department']);		
		}
	}

	function download_final_pay()
	{
		$data = $_POST;
		$is_additional_qry = array();
	    
		$e = new G_Employee_Memo();
		$disciplinary_action = $e->getDisciplinaryData($data, $is_additional_qry);

		$c = G_Company_Branch_Finder::findById(1);
		$this->var['company_name'] = strtoupper($c->getName());		
		$this->var['filename']     = "final_pay.xls";					
		$this->view->render('reports/final_pay/excel_download.php', $this->var);	
	}

	function download_overtime_data()
	{
		$data = $_POST;

		$remove_resigned   = false;
		$remove_terminated = false;
		$remove_endo       = false;
		$remove_inactive   = false;
		$qry_employee_type = '';	
		
	    if( isset($data['overtime_remove_resigned']) && $data['overtime_remove_resigned'] == 1 ){
	      $remove_resigned   = true;
	    }
	    if( isset($data['overtime_remove_terminated']) && $data['overtime_remove_terminated'] == 1 ){
	      $remove_terminated = true;  
	    }
	    if( isset($data['overtime_remove_endo']) && $data['overtime_remove_endo'] == 1 ){
	      $remove_endo = true;  
	    }
	    if( isset($data['overtime_remove_inactive']) && $data['overtime_remove_inactive'] == 1 ){
	      $remove_inactive = true;  
	    }  			

		if($_POST['date_from'] && $_POST['date_to']){

			if( $remove_resigned ){       
				$qry_add_on[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
			}

			if( $remove_terminated ){
				$qry_add_on[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
			}

			if( $remove_endo ){
				$qry_add_on[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
			}

			if( $remove_inactive ){
				$qry_add_on[] = "(e.inactive_date = '0000-00-00' OR e.inactive_date = '')";
			}

			if( !empty($qry_add_on) ){
				$is_additional_qry .= " AND " . implode(" AND ", $qry_add_on);
			}

			$e = new G_Employee();			
			if($_POST['report_type'] == 'summarized_dept') {
				$overtimed = $e->getOvertimeData($_POST, $is_additional_qry);
				
				$ot_group_by_dept = array();
				foreach($overtimed as $otkey => $otdata) {
					if($otdata['department_name'] == $otdata['section_name']) {
						$ot_group_by_dept[$otdata['department_name']][] = $otdata;
					} else {
						$ot_group_by_dept[$otdata['department_name'].'-'.$otdata['section_name']][] = $otdata;	
					}
				}			

				$overtime = $ot_group_by_dept;				

			}elseif($_POST['report_type'] == DETAILED){			
				$overtime = $e->getOvertimeData($_POST, $is_additional_qry);
			}else{
				//$overtime = $e->countOvertimeData($_POST, $is_additional_qry);
				$overtime = $e->getOvertimeData($_POST, $is_additional_qry);
			}
			//utilities::displayArray($overtime);exit;

			if($data['project_site_id'] != 'all'){
				 $project = G_Project_Site_Finder::findById($data['project_site_id']);
				 if($project){
				 	$this->var['filter_by'] = "<strong>".$project->getprojectname()."</strong>";
				 }
			}
			else{
				$this->var['filter_by'] = "<strong>All</strong>";
			}


			$this->var['filename']         = 'overtime.xls';
			$this->var['date_from']		   = $_POST['date_from'];
			$this->var['date_to']		   = $_POST['date_to'];
			$this->var['report_body_type'] = $_POST['report_type'];			
			$this->var['overtime']         = $overtime;
			$this->view->render('reports/time_management/overtime/excel_download.php', $this->var);	

			//General Reports / Shr Audit Trail
	        $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_GENERATE, 'Overtime Reports ', $_POST['report_type'], $_POST['date_from'], $_POST['date_to'], 1, '', $_POST['department_applied']);


		}
	}

	function download_employment_status()
	{
		$data = $_POST;

	    $remove_resigned   = false;
	    $remove_terminated = false;
	    $remove_endo       = false;
	    $remove_inactive   = false;
	    $qry_employee_type = '';

		if( isset($data['empstatus_remove_resigned']) && $data['empstatus_remove_resigned'] == 1 ){
			$remove_resigned   = true;
		}
		if( isset($data['empstatus_remove_terminated']) && $data['empstatus_remove_terminated'] == 1 ){
			$remove_terminated = true;  
		}
		if( isset($data['empstatus_remove_endo']) && $data['empstatus_remove_endo'] == 1 ){
			$remove_endo = true;  
		}
		if( isset($data['empstatus_remove_inactive']) && $data['empstatus_remove_inactive'] == 1 ){
			$remove_inactive = true;  
		}

		if($_POST['date_from'] && $_POST['date_to']){

			if( $remove_resigned ){
				$qry_add_on[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
			}

			if( $remove_terminated ){
				$qry_add_on[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
			}

			if( $remove_endo ){
				$qry_add_on[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
			}

			if( $remove_inactive ){
				$qry_add_on[] = "(e.inactive_date = '0000-00-00' OR e.inactive_date = '')";
			}

			if( !empty($qry_add_on) ){
				$is_additional_qry .= " AND " . implode(" AND ", $qry_add_on);
			}

			$e = new G_Employee();					
			$employment_status = $e->getEmploymentStatusData($_POST, $is_additional_qry);

			if($data['project_site_id'] != 'all'){
				 $project = G_Project_Site_Finder::findById($data['project_site_id']);
				 if($project){
				 	$this->var['filter_by'] = "<strong>".$project->getprojectname()."</strong>";
				 }
			}
			else{
				$this->var['filter_by'] = "<strong>All</strong>";
			}


			$this->var['filename']         = 'employment_status.xls';
			$this->var['date_from']		   = $_POST['date_from'];
			$this->var['date_to']		   = $_POST['date_to'];
			$this->var['report_body_type'] = DETAILED;			
			$this->var['employment_status']  = $employment_status;
			$this->view->render('reports/time_management/employment_status/excel_download.php', $this->var);

			//General Reports / Shr Audit Trail
	        $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_GENERATE, 'Employment Status Reports ', $_POST['search_field'], $_POST['date_from'], $_POST['date_to'], 1, '', $_POST['department_applied']);
	
		}
	}

	function download_employee_details()
	{
		if($_POST){
			$department 	    = $_POST['department_applied'];
			$employement_status = $_POST['status'];
			$search 		    = $_POST['search'];			

			$project_site_id = $_POST['project_site_id'];	

			$tags_query = array();
			if( isset($_POST['tags']) ){
				$tags_query = explode(",", $_POST['tags']);
			}
			
			
			if( isset($_POST['ed_remove_resigned']) && $_POST['ed_remove_resigned'] == 1 ){
				$remove_resigned   = true;
			}
			if( isset($_POST['ed_remove_terminated']) && $_POST['ed_remove_terminated'] == 1 ){
				$remove_terminated = true;  
			}
			if( isset($_POST['ed_remove_endo']) && $_POST['ed_remove_endo'] == 1 ){
				$remove_endo = true;  
			}
			if( isset($_POST['ed_remove_inactive']) && $_POST['ed_remove_inactive'] == 1 ){
				$remove_inactive = true;  
			}	

			if( $remove_resigned ){
				$qry_add_on[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
			}

			if( !empty( $tags_query ) ){
				$qry_tags = array();
				foreach( $tags_query as $t ){
					if($t != '') {
						$qry_tags[] = "et.tags LIKE '%" . $t . "%'";		
					}
					
				}				
				if( !empty($qry_tags) ){
					$is_additional_qry .= " AND " . implode(" OR ", $qry_tags);					
				}
			}

			if( $remove_terminated ){
				$qry_add_on[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
			}

			if( $remove_endo ){
				$qry_add_on[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
			}

			if( $remove_inactive ){
				$qry_add_on[] = "(e.inactive_date = '0000-00-00' OR e.inactive_date = '')";
			}

			if( !empty($qry_add_on) ){
				$is_additional_qry .= " AND " . implode(" AND ", $qry_add_on);
			}

			$emp = G_Employee_Finder::fidAllEmployeesByDepartmentByEmploymentStatusByFieldWithEmployeeTags($department,$employement_status,$search,$is_additional_qry, $project_site_id);	
				
			if( $_POST['report_type'] == 'detailed' ){
				$this->var['filename']         = 'employee_details_detailed.xls';
				$this->var['emp']  			   = $emp;		
				$this->view->render('reports/employee_details/excel_download.php', $this->var);	

				//General Reports / Shr Audit Trail
				$dept = G_Company_Structure_Finder::findById($_POST['department_applied']);
				foreach ($emp as $value) {
					$emp_name = $value->firstname.'-'.$value->lastname;
				}
	        	$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_GENERATE, 'Employment Details Reports ', $emp_name, '', '', 1, '', $dept->title);

			}else{
				$this->var['filename']         = 'employee_details_summarized.xls';
				$this->var['emp']  			   = $emp;		
				$this->view->render('reports/employee_details/excel_download_summarized.php', $this->var);

				$dept = G_Company_Structure_Finder::findById($_POST['department_applied']);
				foreach ($emp as $value) {
					$emp_name = $value->firstname.'-'.$value->lastname;
				}
	        	$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_GENERATE, 'Employment Details Reports ', $emp_name, '', '', 1, '', $dept->title);

			}			
		}
	}

	function download_ee_er_contribution()
	{
		$data = $_POST;

		$remove_resigned   = false;
		$remove_terminated = false;
		$remove_endo       = false;
		$remove_inactive   = false;
		$qry_employee_type = '';

		if( isset($data['eeer_remove_resigned']) && $data['eeer_remove_resigned'] == 1 ){
			$remove_resigned   = true;
		}
		if( isset($data['eeer_remove_terminated']) && $data['eeer_remove_terminated'] == 1 ){
			$remove_terminated = true;  
		}
		if( isset($data['eeer_remove_endo']) && $data['eeer_remove_endo'] == 1 ){
			$remove_endo = true;  
		}
		if( isset($data['eeer_remove_inactive']) && $data['eeer_remove_inactive'] == 1 ){
			$remove_inactive = true;  
		}		

		if($_POST['date_from'] && $_POST['date_to']){

			if( $remove_resigned ){
				$qry_add_on[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
			}

			if( $remove_terminated ){
				$qry_add_on[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
			}

			if( $remove_endo ){
				$qry_add_on[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
			}

			if( $remove_inactive ){
				$qry_add_on[] = "(e.inactive_date = '0000-00-00' OR e.inactive_date = '')";
			}

			if( !empty($qry_add_on) ){
				$is_additional_qry .= " AND " . implode(" AND ", $qry_add_on);
			}


			$e = new G_Employee();					
			$ee_er_contribution = $e->getEeErContributionData($_POST, $is_additional_qry);
		

			$show_all 			= false;
			$show_sss 			= false;
			$show_philhealth 	= false;
			$show_pagibig 		= false;
			$colspan = 0;

			foreach($_POST['contribution_type'] as $key => $value) {
				if($value == "sss") {
					$show_sss = true;
					$excel_name[] = "SSS";
					$colspan++;
				}elseif($value == "pagibig") {
					$show_pagibig = true;
					$excel_name[] = "Pagibig";
					$colspan++;
				}elseif($value == "philhealth") {
					$show_philhealth = true;
					$excel_name[] = "Philhealth";
					$colspan++;
				}
			}

			if($show_sss && $show_philhealth && $show_pagibig){
				$excel_name = array();
				$excel_name[] = "All";
			}

			$this->var['colspan'] 			= $colspan;
			$this->var['show_sss'] 			= $show_sss;
			$this->var['show_philhealth'] 	= $show_philhealth;
			$this->var['show_pagibig'] 		= $show_pagibig;
			$this->var['additional_name']  	= $fn = "(".implode(",",$excel_name).")";
			$this->var['filename']         	= 'Contribution_Report'.$fn.'.xls';
			$this->var['date_from']		   	= $_POST['date_from'];
			$this->var['date_to']		   	= $_POST['date_to'];
			$this->var['report_body_type'] 	= DETAILED;			
			$this->var['ee_er_contribution']  = $ee_er_contribution;
			$this->view->render('reports/time_management/ee_er_contribution/excel_download.php', $this->var);	

			//General Reports / Shr Audit Trail
			if($_POST['search_field'] == 'all'){
				$searchBy = $_POST['search_field'];
			}
			else{
				$searchBy = $_POST['search_field'].' = '.$_POST['search'];
			}
			
			$db_dept = G_Company_Structure_Finder::findById($_POST['department_applied']);

			if($_POST['department_applied'] == 'all'){
				$department = $_POST['department_applied'];
			}
			else{
				
				$department =  $db_dept->title;
			}
			
	        $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_GENERATE, 'Employment Details Reports ', $searchBy, $_POST['date_from'], $_POST['date_to'], 1, '', $department);	

		}
	}

	function download_undertime_data()
	{
		//echo '<pre>';
		//print_r($_POST);
		//echo '</pre>';
		//exit;

		$data = $_POST;

		$remove_resigned   = false;
		$remove_terminated = false;
		$remove_endo       = false;
		$remove_inactive   = false;
		$qry_employee_type = '';	
		
		if( isset($data['undertime_remove_resigned']) && $data['undertime_remove_resigned'] == 1 ){
			$remove_resigned   = true;
		}
		if( isset($data['undertime_remove_terminated']) && $data['undertime_remove_terminated'] == 1 ){
			$remove_terminated = true;  
		}
		if( isset($data['undertime_remove_endo']) && $data['undertime_remove_endo'] == 1 ){
			$remove_endo = true;  
		}
		if( isset($data['undertime_remove_inactive']) && $data['undertime_remove_inactive'] == 1 ){
			$remove_inactive = true;  
		}			

		if($_POST['date_from'] && $_POST['date_to']){

		if( $remove_resigned ){
			$qry_add_on[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
		}

		if( $remove_terminated ){
			$qry_add_on[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
		}

		if( $remove_endo ){
			$qry_add_on[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
		}

		if( $remove_inactive ){
			$qry_add_on[] = "(e.inactive_date = '0000-00-00' OR e.inactive_date = '')";
		}

		if( !empty($qry_add_on) ){
			$is_additional_qry .= " AND " . implode(" AND ", $qry_add_on);
		}

			$e = new G_Employee();			
			if($_POST['report_type'] == DETAILED){			
				$undertime = $e->getUndertimeData($_POST, $is_additional_qry);
			}else{
				$undertime = $e->countUndertimeData($_POST, $is_additional_qry);
			}

			if($data['project_site_id'] != 'all'){
				 $project = G_Project_Site_Finder::findById($data['project_site_id']);
				 if($project){
				 	$this->var['filter_by'] = "<strong>".$project->getprojectname()."</strong>";
				 }
			}
			else{
				$this->var['filter_by'] = "<strong>All</strong>";
			}

			$this->var['filename']         = 'undertime.xls';
 			$this->var['date_from']		   = $_POST['date_from'];
			$this->var['date_to']		   = $_POST['date_to'];
			$this->var['report_body_type'] = $_POST['report_type'];			
			$this->var['undertime']  = $undertime;
			$this->view->render('reports/time_management/undertime/excel_download.php', $this->var);	

			//General Reports / Shr Audit Trail
	        $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_GENERATE, 'Undertime Reports ', 'Undertime', $_POST['date_from'], $_POST['date_to'], 1, '', $_POST['department_applied']);
			
		}
	}
    
    function download_leave_data()
	{
		$data = $_POST;

		$remove_resigned   = false;
		$remove_terminated = false;
		$remove_endo       = false;
		$remove_inactive   = false;
		$qry_employee_type = '';

		if( isset($data['leave_remove_resigned']) && $data['leave_remove_resigned'] == 1 ){
			$remove_resigned   = true;
		}
		if( isset($data['leave_remove_terminated']) && $data['leave_remove_terminated'] == 1 ){
			$remove_terminated = true;  
		}
		if( isset($data['leave_remove_endo']) && $data['leave_remove_endo'] == 1 ){
			$remove_endo = true;  
		}
		if( isset($data['leave_remove_inactive']) && $data['leave_remove_inactive'] == 1 ){
			$remove_inactive = true;  
		}		

		if($_POST['date_from'] && $_POST['date_to']){

			if( $remove_resigned ){
				$qry_add_on[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
			}

			if( $remove_terminated ){
				$qry_add_on[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
			}

			if( $remove_endo ){
				$qry_add_on[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
			}

			if( $remove_inactive ){
				$qry_add_on[] = "(e.inactive_date = '0000-00-00' OR e.inactive_date = '')";
			}
			
			$qry_add_on[] = "(elr.is_archive = 'No')";

			if( !empty($qry_add_on) ){
				$is_additional_qry .= " AND " . implode(" AND ", $qry_add_on);
			}

			$e = new G_Employee();			
			if($_POST['report_type'] == DETAILED){	
				$leave = $e->getLeaveData($_POST, $is_additional_qry);
			}else{				
				//$leave = $e->countLeaveData($_POST);
			}			

			//utilities::displayArray($leave);exit();

			if($data['project_site_id'] != 'all'){
				 $project = G_Project_Site_Finder::findById($data['project_site_id']);
				 if($project){
				 	$this->var['filter_by'] = "<strong>".$project->getprojectname()."</strong>";
				 }
			}
			else{
				$this->var['filter_by'] = "<strong>All</strong>";
			}

			$this->var['filename']         = 'leave.xls';
			$this->var['date_from']		   = $_POST['date_from'];
			$this->var['date_to']		   = $_POST['date_to'];
			$this->var['report_body_type'] = $_POST['report_type'];			
			$this->var['leave']  = $leave;
			$this->view->render('reports/time_management/leave/excel_download.php', $this->var);	

			//General Reports / Shr Audit Trail
	        $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_GENERATE, ' Leave Reports ', 'Leave', $_POST['date_from'], $_POST['date_to'], 1, '
	        	', $_POST['department_applied']);
			
		}
	}

	function download_leave_balance_data()
	{
		$data = $_POST;

		$remove_resigned   = false;
		$remove_terminated = false;
		$remove_endo       = false;
		$remove_inactive   = false;
		$qry_employee_type = '';

		//$data['year']      = date("Y");
		if( isset($data['leave_balance_remove_resigned']) && $data['leave_balance_remove_resigned'] == 1 ){
			$remove_resigned   = true;
		}
		if( isset($data['leave_balance_remove_terminated']) && $data['leave_balance_remove_terminated'] == 1 ){
			$remove_terminated = true;  
		}
		if( isset($data['leave_balance_remove_endo']) && $data['leave_balance_remove_endo'] == 1 ){
			$remove_endo = true;  
		}
		if( isset($data['leave_balance_remove_inactive']) && $data['leave_balance_remove_inactive'] == 1 ){
			$remove_inactive = true;  
		}		

		if( $remove_resigned ){
			$qry_add_on[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
		}

		if( $remove_terminated ){
			$qry_add_on[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
		}

		if( $remove_endo ){
			$qry_add_on[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
		}

		if( $remove_inactive ){
			$qry_add_on[] = "(e.inactive_date = '0000-00-00' OR e.inactive_date = '')";
		}

		//$qry_add_on[] = "e.employee_code = '4' "; //for testing only

		if( !empty($qry_add_on) ){
			$is_additional_qry .= " AND " . implode(" AND ", $qry_add_on);
		}

		$e = new G_Employee();			

		if($data['r_type'] == 'general_incentive_leave') {

			//$leave_type = G_Leave_Finder::findAllGeneralAndIncentiveIsNotArchive();
			//$leave_credits = $e->getEmployeesTotalLeaveCreditsGeneralIncentive($data, $is_additional_qry);
			//$leave = $e->getLeaveCreaditsDataGeneralIncentive($data, $is_additional_qry);				

			$leave_type = G_Leave_Finder::findAllIsNotArchive();
			$leave_credits = $e->getEmployeesTotalLeaveCredits($data, $is_additional_qry);
			$leave_credits_general_incentive = $e->getEmployeesTotalLeaveCreditsGeneralIncentive($data, $is_additional_qry);
			$leave = $e->getLeaveCreaditsData($data, $is_additional_qry);						
		} else {
			$leave_type = G_Leave_Finder::findAllIsNotArchive();
			$leave_credits = $e->getEmployeesTotalLeaveCredits($data, $is_additional_qry);
			$leave = $e->getLeaveCreaditsData($data, $is_additional_qry);				

		}

		//utilities::displayArray($leave);exit;

		//Need to group data for viewing
		$group_employee_leave = array();		
		foreach( $leave as $lt ){
			$group_employee_leave[$lt['employee_pkid']]['employee_details'] = array(
				'employee_code' => $lt['employee_code'],
				'lastname' => $lt['lastname'],
				'firstname' => $lt['firstname'],
				'middlename' => $lt['middlename'],
				'hired_date' => $lt['hired_date'],
				'employee_status' => $lt['employee_status'],
				'section_name' => $lt['section_name'],
				'position' => $lt['position'],
				'department_name' => $lt['department_name'],
				'project_site_id' => $lt['project_site_id']
			);


			$group_employee_leave[$lt['employee_pkid']]['leave_details'][$lt['leave_id']] = array(
				'leave_type' => $lt['leave_type'],
				'no_of_days_available' => $lt['no_of_days_available'],
				'no_of_days_alloted' => $lt['no_of_days_alloted'],
				'no_of_days_used' => $lt['no_of_days_used']
			);
		}

		$grouped_leave_credits = array();
		if($data['r_type'] == 'general_incentive_leave') {
			foreach( $leave_credits_general_incentive as $lc ){
				$grouped_leave_credits[$lc['employee_pkid']] = $lc['total_leave_credits'];
			}
		} else {
			foreach( $leave_credits as $lc ){
				$grouped_leave_credits[$lc['employee_pkid']] = $lc['total_leave_credits'];
			}			
		}

		if($data['project_site_id'] != 'all'){
				 $project = G_Project_Site_Finder::findById($data['project_site_id']);
				 if($project){
				 	$this->var['filter_by'] = "<strong>".$project->getprojectname()."</strong>";
				 }
			}
			else{
				$this->var['filter_by'] = "<strong>All</strong>";
			}
		
		//$header = "As of " . date("F") . " " . date("Y");
		//$this->var['header']           = "As of <b>" . date("F") . " " . date("Y") . "</b>";
		//utilities::displayArray($group_employee_leave);exit;
		$header 					   = "As of " . $data['year'];
		$this->var['header']           = "As of <b>" . $data['year'] . "</b>";
		$this->var['filename']         = 'leave_balance.xls';
		$this->var['date_from']		   = $_POST['date_from'];
		$this->var['date_to']		   = $_POST['date_to'];
		$this->var['report_body_type'] = $_POST['report_type'];			
		$this->var['r_type']           = $data['r_type'];
		$this->var['leave_credits']    = $grouped_leave_credits;
		$this->var['leave']  		   = $group_employee_leave;
		$this->var['leave_type'] 	   = $leave_type;
		$this->view->render('reports/time_management/leave_balance/excel_download.php', $this->var);	

		//General Reports / Shr Audit Trail
	    $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_GENERATE, ' Leave Balance Reports ', $_POST['search_field'], $_POST['year'], $_POST['year'], 1, '', $_POST['department_applied']);
		
	}

	function download_incentive_leave_dataBackup09062017()
	{
		$data = $_POST;

		$remove_resigned   = false;
		$remove_terminated = false;
		$remove_endo       = false;
		$remove_inactive   = false;
		$qry_employee_type = '';

		if( isset($data['incentive_leave_remove_resigned']) && $data['incentive_leave_remove_resigned'] == 1 ){
			$remove_resigned   = true;
		}
		if( isset($data['incentive_leave_remove_terminated']) && $data['incentive_leave_remove_terminated'] == 1 ){
			$remove_terminated = true;  
		}
		if( isset($data['incentive_leave_remove_endo']) && $data['incentive_leave_remove_endo'] == 1 ){
			$remove_endo = true;  
		}
		if( isset($data['incentive_leave_remove_inactive']) && $data['incentive_leave_remove_inactive'] == 1 ){
			$remove_inactive = true;  
		}		

		if( $remove_resigned ){
			$qry_add_on[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
		}

		if( $remove_terminated ){
			$qry_add_on[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
		}

		if( $remove_endo ){
			$qry_add_on[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
		}

		if( $remove_inactive ){
			$qry_add_on[] = "(e.inactive_date = '0000-00-00' OR e.inactive_date = '')";
		}

		if( !empty($qry_add_on) ){
			$is_additional_qry .= " AND " . implode(" AND ", $qry_add_on);
		}

		$data['leave_id'] = 11;

		$e = new G_Employee();	
		$incentive_leave = $e->getEmployeeIncentiveReport($data, $is_additional_qry);	

		$group_incentive_leave = array();
		foreach( $incentive_leave as $il ){
			$month  = date("F",strtotime($il['date_added']));

			$group_incentive_leave[$il['employee_pkid']]['employee_details'] = array(
				'employee_code' 	=> $il['employee_code'],
				'lastname' 			=> $il['lastname'],
				'firstname' 		=> $il['firstname'],
				'middlename' 		=> $il['middlename'],
				'hired_date' 		=> $il['hired_date'],
				'employee_status' 	=> $il['employee_status'],
				'section_name' 		=> $il['section_name'],
				'department_name' 	=> $il['department_name'],
				'position' 			=> $il['position']
			);

			if( !isset($group_incentive_leave[$il['employee_pkid']]['leave_credit'][$month]) && $group_incentive_leave[$il['employee_pkid']]['leave_credit'][$month] < 1 ) {

				$month_number = date('m', strtotime($month));

				$from 		 = date('Y-'.$month_number.'-01');
				$to   		 = date('Y-'.$month_number.'-t');
				$employee_id = $il['employee_pkid'];

				$p   = G_Attendance_Helper::perfectAttendanceDataByDateRangeAndEmployeeId($from, $to, $employee_id);		

				$is_perfect_attendance = count($p);
				if($is_perfect_attendance > 0) {
					$group_incentive_leave[$il['employee_pkid']]['leave_credit'][$month] += $il['credits_added'];			
				}

			}

		}	

		$months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
		$this->var['header']   = "Incentive Leave Report for the year of " . $data['incentive_leave_year'];
		$this->var['filename'] = 'incentive_leave.xls';
		$this->var['incentive_leave_year'] = $_POST['incentive_leave_year'];
		$this->var['months']   = $months;
		$this->var['data']     = $group_incentive_leave;
		$this->view->render('reports/time_management/incentive_leave/excel_download.php', $this->var);	
	}	

	function download_incentive_leave_data()
	{
		$data = $_POST;

		$remove_resigned   = false;
		$remove_terminated = false;
		$remove_endo       = false;
		$remove_inactive   = false;
		$qry_employee_type = '';

		if( isset($data['incentive_leave_remove_resigned']) && $data['incentive_leave_remove_resigned'] == 1 ){
			$remove_resigned   = true;
		}
		if( isset($data['incentive_leave_remove_terminated']) && $data['incentive_leave_remove_terminated'] == 1 ){
			$remove_terminated = true;  
		}
		if( isset($data['incentive_leave_remove_endo']) && $data['incentive_leave_remove_endo'] == 1 ){
			$remove_endo = true;  
		}
		if( isset($data['incentive_leave_remove_inactive']) && $data['incentive_leave_remove_inactive'] == 1 ){
			$remove_inactive = true;  
		}		

		if( $remove_resigned ){
			$qry_add_on[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
		}

		if( $remove_terminated ){
			$qry_add_on[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
		}

		if( $remove_endo ){
			$qry_add_on[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
		}

		if( $remove_inactive ){
			$qry_add_on[] = "(e.inactive_date = '0000-00-00' OR e.inactive_date = '')";
		}

		if( !empty($qry_add_on) ){
			$is_additional_qry .= " AND " . implode(" AND ", $qry_add_on);
		}

		$data['leave_id'] = 11;

		$e = new G_Employee();	

		//$incentive_leave = $e->getEmployeeIncentiveReport($data, $is_additional_qry);	//old code
		$incentive_basic_employee_details = $e->getEmployeeBasicDetails($data, $is_additional_qry);	

		$months_to_check = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

		$group_incentive_leave = array();
		//foreach( $incentive_leave as $il ){ //old code
		foreach( $incentive_basic_employee_details as $il ){
			//$month  = date("F",strtotime($il['hired_date'])); //old code

			$group_incentive_leave[$il['employee_pkid']]['employee_details'] = array(
				'employee_code' 	=> $il['employee_code'],
				'lastname' 			=> $il['lastname'],
				'firstname' 		=> $il['firstname'],
				'middlename' 		=> $il['middlename'],
				'hired_date' 		=> $il['hired_date'],
				'employee_status' 	=> $il['employee_status'],
				'section_name' 		=> $il['section_name'],
				'department_name' 	=> $il['department_name'],
				'position' 			=> $il['position']
			);

			foreach($months_to_check as $m) {
				$month_number = date('m', strtotime($m));
				$from 		  = date($data['incentive_leave_year'].'-'.$month_number.'-01');
				$to   		  = date($data['incentive_leave_year'].'-'.$month_number.'-t');				
				$employee_id  = $il['employee_pkid'];

				//$p   = G_Attendance_Helper::perfectAttendanceDataByDateRangeAndEmployeeId($from, $to, $employee_id); //old code
				$p   = G_Attendance_Helper::perfectAttendanceDataByMonthAndEmployeeId($from, $to, $employee_id);		
				
				$is_perfect_attendance = $p;
				if(isset($is_perfect_attendance) && $is_perfect_attendance > 0) {
					$group_incentive_leave[$il['employee_pkid']]['leave_credit'][$m] = $p;			
				} else {
					$group_incentive_leave[$il['employee_pkid']]['leave_credit'][$m] = 0;			
				}
			}

			//old code - start
			/*if( !isset($group_incentive_leave[$il['employee_pkid']]['leave_credit'][$month]) && $group_incentive_leave[$il['employee_pkid']]['leave_credit'][$month] < 1 ) {

				$month_number = date('m', strtotime($month));

				$from 		 = date('Y-'.$month_number.'-01');
				$to   		 = date('Y-'.$month_number.'-t');
				$employee_id = $il['employee_pkid'];

				$p   = G_Attendance_Helper::perfectAttendanceDataByDateRangeAndEmployeeId($from, $to, $employee_id);		

				$is_perfect_attendance = count($p);
				if($is_perfect_attendance > 0) {
					$group_incentive_leave[$il['employee_pkid']]['leave_credit'][$month] += $il['credits_added'];			
				}

			}*/
			//old code - end

		}	
		
		$months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
		$this->var['header']   = "Incentive Leave Report for the year of " . $data['incentive_leave_year'];
		$this->var['filename'] = 'incentive_leave.xls';
		$this->var['incentive_leave_year'] = $_POST['incentive_leave_year'];
		$this->var['months']   = $months;
		$this->var['data']     = $group_incentive_leave;
		$this->view->render('reports/time_management/incentive_leave/excel_download.php', $this->var);	
	}
	
	function download_attendance_absence_data_deprecated()
	{
	
		$sql = "SELECT 	e.id,a.firstname,
						a.lastname,
						a.middlename, 
						a.extension_name,
						a.applied_date_time,
						e.hiring_manager,
						e.date_time_event,
						e.event_type,
						e.application_status_id,
						e.notes,
						j.title as position_applied
				
				FROM g_applicant a, g_job_application_event e, g_job j
				".$search_by_date."
				".$query." 
				".$applied."
				AND j.id=a.job_id
				GROUP BY e.id
				ORDER BY e.date_time_event 
				
		";
		print_r($_POST);
		echo $sql;
		echo "<pre>";
		$rec = Model::runSql($sql,true);
		print_r($rec);
		exit;
		//echo "<pre>";
		//print_r($rec);
		$this->var['data'] = $rec;

		$this->view->render('reports/time_management/attendance_absence_data/excel_download.php', $this->var);
	}
	
	//load display_absence_quota_information
	function _load_display_absence_quota_information()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Absence Quota Information";
		$this->view->noTemplate();
		$this->view->render('reports/time_management/displaying_absence_quota_information/index.php',$this->var);	
	}
	
	function _load_display_tardiness()
	{
		$this->validatePermission(G_Sprint_Modules::HR,'reports','tardiness');

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		//get all project sites
		$project_site = G_PROJECT_SITE::findAllProjectSite();
		$this->var['project_site'] = $project_site;
		
		$this->var['departments'] = G_Company_Structure_Finder::findAllDepartmentsIsNotArchiveByParentId($this->company_structure_id);
		$this->var['job']         = $j;
		$this->var['title'] = "Late";
		$this->view->noTemplate();
		$this->view->render('reports/time_management/tardiness/index.php',$this->var);	
	}

	function _load_manpower_count()
	{
		$this->validatePermission(G_Sprint_Modules::HR,'reports','manpower_count');

		Utilities::ajaxRequest();

        $fields    = array('id','code','status');
		$ordery_by = "ORDER BY code ASC";
		$es        = new G_Settings_Employment_Status();
        $employment_status = $es->getAllEmploymentStatus($fields, $order_by);

        $fields    = array('DISTINCT(course)AS course');
		$ordery_by = "ORDER BY code ASC";
		$ee        = new G_Employee_Education();
        $educational_courses = $ee->getAllUniqueCourse($fields, $order_by);

        $fields    = array('DISTINCT(skill)AS skill');
		$ordery_by = "ORDER BY skill ASC";
		$ss        = new G_Settings_Skills();
        $skills    = $ss->getAllUniqueSkills($fields, $order_by);

        $report = new G_Report();
		$report_options = $report->getReportOptions();

        $gender  = array('Male','Female');
		
		$this->var['employment_status']   = $employment_status;
		$this->var['educational_courses'] = $educational_courses;
		$this->var['skills'] 		      = $skills;
		$this->var['gender'] 			  = $gender;
		$this->var['report_options'] 	  = $report_options;
		$this->var['title']       		  = "Manpower Count";

		$this->view->noTemplate();
		$this->view->render('reports/time_management/manpower_count/index.php',$this->var);	
	}
    
    function _load_end_of_contract()
	{
		$this->validatePermission(G_Sprint_Modules::HR,'reports','end_of_contract');

		Utilities::ajaxRequest();
        $j = G_Job_Finder::findAll();

        //get all project sites
		$project_site = G_PROJECT_SITE::findAllProjectSite();
		$this->var['project_site'] = $project_site;
		
		$this->var['departments'] = G_Company_Structure_Finder::findAllDepartmentsIsNotArchiveByParentId($this->company_structure_id);
		$this->var['job']         = $j;
        $this->var['title'] = "End of Contract";
		$this->view->noTemplate();
		$this->view->render('reports/time_management/end_of_contract/index.php',$this->var);	
	}

	function _load_resigned_employees()
	{
		$this->validatePermission(G_Sprint_Modules::HR,'reports','resigned_employees');

		Utilities::ajaxRequest();
        $j = G_Job_Finder::findAll();
		
        //get all project sites
		$project_site = G_PROJECT_SITE::findAllProjectSite();
		$this->var['project_site'] = $project_site;

		$this->var['departments'] = G_Company_Structure_Finder::findAllDepartmentsIsNotArchiveByParentId($this->company_structure_id);
		$this->var['job']         = $j;
        $this->var['title'] = "Resigned Employees";
		$this->view->noTemplate();
		$this->view->render('reports/time_management/resigned_employees/index.php',$this->var);	
	}

	function _load_terminated_employees()
	{
		$this->validatePermission(G_Sprint_Modules::HR,'reports','terminated_employees');

		Utilities::ajaxRequest();
        $j = G_Job_Finder::findAll();

        //get all project sites
		$project_site = G_PROJECT_SITE::findAllProjectSite();
		$this->var['project_site'] = $project_site;
		
		$this->var['departments'] = G_Company_Structure_Finder::findAllDepartmentsIsNotArchiveByParentId($this->company_structure_id);
		$this->var['job']         = $j;
        $this->var['title'] = "Terminated Employees";
		$this->view->noTemplate();
		$this->view->render('reports/time_management/terminated_employees/index.php',$this->var);	
	}
    
    function _load_daily_time_record()
	{
		$this->validatePermission(G_Sprint_Modules::HR,'reports','reports_daily_time_record');

		Utilities::ajaxRequest();
        $j = G_Job_Finder::findAll();
		
		//get all project sites
		$project_site = G_PROJECT_SITE::findAllProjectSite();
		$this->var['project_site'] = $project_site;

		$this->var['departments'] = G_Company_Structure_Finder::findAllDepartmentsIsNotArchiveByParentId($this->company_structure_id);
		$this->var['job']         = $j;
        $this->var['title'] = "Daily Time Record";
		$this->view->noTemplate();
		$this->view->render('reports/time_management/daily_time_record/index.php',$this->var);	
	}
    
    function _load_incomplete_time_in_out()
	{
		$this->validatePermission(G_Sprint_Modules::HR,'reports','inc_time_in_and_time_out');

		Utilities::ajaxRequest();
        $j = G_Job_Finder::findAll();
		
		//get all project sites
		$project_site = G_PROJECT_SITE::findAllProjectSite();
		$this->var['project_site'] = $project_site;

		$this->var['departments'] = G_Company_Structure_Finder::findAllDepartmentsIsNotArchiveByParentId($this->company_structure_id);
		$this->var['job']         = $j;
        $this->var['title'] = "Incomplete Time In / Out";
		$this->view->noTemplate();
		$this->view->render('reports/time_management/incomplete_time_in_out/index.php',$this->var);	
	}

	function _load_incorrect_shift()
	{
		$this->validatePermission(G_Sprint_Modules::HR,'reports','reports_incorrect_shift');

		Utilities::ajaxRequest();
        $j = G_Job_Finder::findAll();
		
		$this->var['departments'] = G_Company_Structure_Finder::findAllDepartmentsIsNotArchiveByParentId($this->company_structure_id);
		$this->var['job']         = $j;
        $this->var['title'] = "Incorrect Shift";
		$this->view->noTemplate();
		$this->view->render('reports/time_management/incorrect_shift/index.php',$this->var);	
	}

	function _load_loans_report()
	{
		$this->validatePermission(G_Sprint_Modules::HR,'reports','reports_loans');

		Utilities::ajaxRequest();
		$loan_types = G_Loan_Type_Finder::findAllIsNotArchive();

		$year_tags    = G_Cutoff_Period_Helper::sqlGetAllUniqueYearTags();
		$current_year = date('Y');    

		$c    			= new G_Cutoff_Period();
		$cutoff_periods = $c->expectedCutoffPeriodByYear($current_year);
        
        $prev_y = date("Y") - 1;
        $cutoff_periods = G_Cutoff_Period_Finder::findAllByBetweenYear($prev_y, $current_year); //G_Cutoff_Period_Finder::findAllByYear($current_year);

		$this->var['positions']      = G_Job_Finder::findAllActive();
		$this->var['departments']    = G_Company_Structure_Finder::findAllDepartmentsIsNotArchiveByParentId($this->company_structure_id);
		$this->var['months_tags']    = array("January","February","March","April","May","June","July","August","September","October","November","December");
		$this->var['year_tags']      = $year_tags;  
		$this->var['cutoff_periods'] = $cutoff_periods;
		$this->var['loan_types']     = $loan_types;
		$this->var['title'] 		 = "Loans";
		$this->view->noTemplate();
		$this->view->render('reports/time_management/loan/index.php',$this->var); 
	}	
    
    function _load_timesheet()
	{
		$this->validatePermission(G_Sprint_Modules::HR,'reports','reports_timesheet');

		Utilities::ajaxRequest();
        $j = G_Job_Finder::findAll();

        //get all project sites
		$project_site = G_PROJECT_SITE::findAllProjectSite();
		$this->var['project_site'] = $project_site;
		
		$this->var['departments'] = G_Company_Structure_Finder::findAllDepartmentsIsNotArchiveByParentId($this->company_structure_id);
		$this->var['job']         = $j;
        $this->var['title'] = "Timesheet";
		$this->view->noTemplate();
		$this->view->render('reports/time_management/timesheet/index.php',$this->var);	
	}

	function _load_disciplinary_action()
	{
		$this->validatePermission(G_Sprint_Modules::HR,'reports','reports_disciplinary_action');

		Utilities::ajaxRequest();

		//get all project sites
		$project_site = G_PROJECT_SITE::findAllProjectSite();
		$this->var['project_site'] = $project_site;
 
        $this->var['title'] = "Disciplinary Action";
		$this->view->noTemplate();
		$this->view->render('reports/disciplinary_action/index.php',$this->var);	
	}

	function _load_overtime()
	{
		$this->validatePermission(G_Sprint_Modules::HR,'reports','reports_overtime');

		Utilities::ajaxRequest();
        $j = G_Job_Finder::findAll();

        //get all project sites
		$project_site = G_PROJECT_SITE::findAllProjectSite();
		$this->var['project_site'] = $project_site;

		
		$this->var['departments'] = G_Company_Structure_Finder::findAllDepartmentsIsNotArchiveByParentId($this->company_structure_id);
		$this->var['job']         = $j;
        $this->var['title'] = "Overtime";
		$this->view->noTemplate();
		$this->view->render('reports/time_management/overtime/index.php',$this->var);	
	}

	function _load_undertime()
	{
		$this->validatePermission(G_Sprint_Modules::HR,'reports','undertime');

		Utilities::ajaxRequest();
        $j = G_Job_Finder::findAll();

        //get all project sites
		$project_site = G_PROJECT_SITE::findAllProjectSite();
		$this->var['project_site'] = $project_site;
		
		$this->var['departments'] = G_Company_Structure_Finder::findAllDepartmentsIsNotArchiveByParentId($this->company_structure_id);
		$this->var['job']         = $j;
        $this->var['title'] = "Undertime";
		$this->view->noTemplate();
		$this->view->render('reports/time_management/undertime/index.php',$this->var);	
	}
    
    function _load_leave()
	{
		$this->validatePermission(G_Sprint_Modules::HR,'reports','reports_leave');

		Utilities::ajaxRequest();
        $j = G_Job_Finder::findAll();
		
        //get all project sites
		$project_site = G_PROJECT_SITE::findAllProjectSite();
		$this->var['project_site'] = $project_site;

		$this->var['departments'] = G_Company_Structure_Finder::findAllDepartmentsIsNotArchiveByParentId($this->company_structure_id);
		$this->var['job']         = $j;
        $this->var['title'] = "Leave";
		$this->view->noTemplate();
		$this->view->render('reports/time_management/leave/index.php',$this->var);	
	}
	function _load_incentive_leave()
	{
		$this->validatePermission(G_Sprint_Modules::HR,'reports','reports_leave');

		Utilities::ajaxRequest();
        $j = G_Job_Finder::findAll();
		
		$this->var['start_year']  = "2000";		
		$this->var['departments'] = G_Company_Structure_Finder::findAllDepartmentsIsNotArchiveByParentId($this->company_structure_id);
		$this->var['job']         = $j;
        $this->var['title'] = "Incentive Leave";
		$this->view->noTemplate();
		$this->view->render('reports/time_management/incentive_leave/index.php',$this->var);	
	}

	function _load_leave_balance()
	{
		$this->validatePermission(G_Sprint_Modules::HR,'reports','reports_leave_balance');

		Utilities::ajaxRequest();        

		Utilities::ajaxRequest();
        $j = G_Job_Finder::findAll();

        $year_start = "2015";

        //get all project sites
		$project_site = G_PROJECT_SITE::findAllProjectSite();
		$this->var['project_site'] = $project_site;

        $this->var['year_start']  = $year_start;
        $this->var['departments'] = G_Company_Structure_Finder::findAllDepartmentsIsNotArchiveByParentId($this->company_structure_id);
		$this->var['job']         = $j;
        $this->var['title'] = "Leave Balance";
		$this->view->noTemplate();
		$this->view->render('reports/time_management/leave_balance/index.php',$this->var);	
	}

	function _load_employment_status()
	{
		$this->validatePermission(G_Sprint_Modules::HR,'reports','reports_employment_status');

		Utilities::ajaxRequest();
        $j = G_Job_Finder::findAll();

        //get all project sites
		$project_site = G_PROJECT_SITE::findAllProjectSite();
		$this->var['project_site'] = $project_site;
		
		$this->var['departments'] = G_Company_Structure_Finder::findAllDepartmentsIsNotArchiveByParentId($this->company_structure_id);
		$this->var['status']	  = G_Settings_Employment_Status_Finder::findByCompanyStructureId($this->company_structure_id);
		$this->var['job']         = $j;
        $this->var['title'] = "Employment Status";
		$this->view->noTemplate();
		$this->view->render('reports/time_management/employment_status/index.php',$this->var);	
	}

	function _load_employee_details()
	{
		$this->validatePermission(G_Sprint_Modules::HR,'reports','reports_employee_details');

		Utilities::ajaxRequest();

		//get all project sites
		$project_site = G_PROJECT_SITE::findAllProjectSite();
		$this->var['project_site'] = $project_site;
		
		$this->var['departments'] = G_Company_Structure_Finder::findAllDepartmentsIsNotArchiveByParentId($this->company_structure_id);
		$this->var['status']	  = G_Settings_Employment_Status_Finder::findByCompanyStructureId($this->company_structure_id);
        $this->var['title'] = "Employee Details";
		$this->view->noTemplate();
		$this->view->render('reports/employee_details/index.php',$this->var);	
	}

	function _load_ee_er_contribution()
	{
		$this->validatePermission(G_Sprint_Modules::HR,'reports','reports_ee_er_contribution');

		Utilities::ajaxRequest();
        $j = G_Job_Finder::findAll();
		
		$this->var['departments'] = G_Company_Structure_Finder::findAllDepartmentsIsNotArchiveByParentId($this->company_structure_id);
		$this->var['job']         = $j;
        $this->var['title'] = "EE / ER Contribution";
		$this->view->noTemplate();
		$this->view->render('reports/time_management/ee_er_contribution/index.php',$this->var);	
	}
	
	function download_display_absence_quota_information()
	{

		$sql = "SELECT 	e.id,a.firstname,
						a.lastname,
						a.middlename, 
						a.extension_name,
						a.applied_date_time,
						e.hiring_manager,
						e.date_time_event,
						e.event_type,
						e.application_status_id,
						e.notes,
						j.title as position_applied
				
				FROM g_applicant a, g_job_application_event e, g_job j
				".$search_by_date."
				".$query." 
				".$applied."
				AND j.id=a.job_id
				GROUP BY e.id
				ORDER BY e.date_time_event 
				
		";
	//	echo $sql;
		$rec = Model::runSql($sql,true);
		//echo "<pre>";
		//print_r($rec);
		$this->var['data'] = $rec;

		$this->view->render('reports/time_management/displaying_absence_quota_information/excel_download.php', $this->var);
	}
	
	
	// personnel administration menu
	function personnel_administration()
	{
		$this->var['page_title'] = 'Reports';
		$this->var['sub_menu_personnel_administration'] = true;
		$this->view->setTemplate('template_reports.php');
		$this->view->render('reports/index.php',$this->var);		
	}
	
	// personnel development menu
	function personnel_development()
	{
		$this->var['page_title'] = 'Reports';
		$this->var['sub_menu_personnel_development'] = true;
		$this->view->setTemplate('template_reports.php');
		$this->view->render('reports/index.php',$this->var);		
	}
	
	// benefits menu
	function benefits()
	{
		$this->var['page_title'] = 'Reports';
		$this->var['sub_menu_benefits'] = true;
		$this->view->setTemplate('template_reports.php');
		$this->view->render('reports/index.php',$this->var);		
	}
	
	// compensation menu
	function compensation()
	{
		$this->var['page_title'] = 'Reports';
		$this->var['sub_menu_compensation_management'] = true;
		$this->view->setTemplate('template_reports.php');
		$this->view->render('reports/index.php',$this->var);		
	}
	
	// time management menu
	function time_management()
	{
		Utilities::checkModulePackageAccess('attendance','dtr');
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainJTags();

		$btn_absences_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'reports',
    		'child_index'			=> 'absences',
    		'required_permission'	=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#attendance_absence_data',
    		'onclick' 				=> 'javascript:hashClick("#attendance_absence_data");',
    		'wrapper_start'			=> '<li id="attendance_absence_data_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Absences'
    		); 

		$btn_tardiness_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'reports',
    		'child_index'			=> 'tardiness',
    		'required_permission'	=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#display_absence_quota_information',
    		'onclick' 				=> 'javascript:hashClick("#display_absence_quota_information");',
    		'wrapper_start'			=> '<li id="display_absence_quota_information_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Late'
    		); 

		$btn_birthday_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'reports',
    		'child_index'			=> 'reports_birthday',
    		'required_permission'	=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#display_birthday',
    		'onclick' 				=> 'javascript:hashClick("#display_birthday");',
    		'wrapper_start'			=> '<li id="display_birthday_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Birthday'
    		); 

		$btn_overtime_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'reports',
    		'child_index'			=> 'reports_overtime',
    		'required_permission'	=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#display_overtime',
    		'onclick' 				=> 'javascript:hashClick("#display_overtime");',
    		'wrapper_start'			=> '<li id="display_overtime_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Overtime'
    		); 

		$btn_undertime_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'reports',
    		'child_index'			=> 'undertime',
    		'required_permission'	=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#display_undertime',
    		'onclick' 				=> 'javascript:hashClick("#display_undertime");',
    		'wrapper_start'			=> '<li id="display_undertime_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Undertime'
    		);

		$btn_leave_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'reports',
    		'child_index'			=> 'reports_leave',
    		'required_permission'	=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#display_leave',
    		'onclick' 				=> 'javascript:hashClick("#display_leave");',
    		'wrapper_start'			=> '<li id="display_leave_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Leave'
    		);
		$btn_incentive_leave_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'reports',
    		'child_index'			=> 'reports_incentive_leave',
    		'required_permission'	=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#display_incentive_leave',
    		'onclick' 				=> 'javascript:hashClick("#display_incentive_leave");',
    		'wrapper_start'			=> '<li id="display_incentive_leave_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Incentive Leave'
    	);

		$btn_leave_balance_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'reports',
    		'child_index'			=> 'reports_leave_balance',
    		'required_permission'	=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#display_leave_balance',
    		'onclick' 				=> 'javascript:hashClick("#display_leave_balance");',
    		'wrapper_start'			=> '<li id="display_leave_balance_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Leave Balance'
    	);

    	$btn_shift_schedule_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'reports',
    		'child_index'			=> 'shift_schedule',
    		'required_permission'	=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#display_shift_schedule',
    		'onclick' 				=> 'javascript:hashClick("#display_shift_schedule");',
    		'wrapper_start'			=> '<li id="display_shift_schedule_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Shift Schedule'
    	);

		$btn_manpower_count_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'reports',
    		'child_index'			=> 'manpower_count',
    		'required_permission'	=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#display_manpower_count',
    		'onclick' 				=> 'javascript:hashClick("#display_manpower_count");',
    		'wrapper_start'			=> '<li id="display_manpower_count_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Manpower Count'
    		);

		$btn_end_of_contract_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'reports',
    		'child_index'			=> 'end_of_contract',
    		'required_permission'	=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#display_end_of_contract',
    		'onclick' 				=> 'javascript:hashClick("#display_end_of_contract");',
    		'wrapper_start'			=> '<li id="display_end_of_contract_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'End of Contract'
    		);

		$btn_resigned_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'reports',
    		'child_index'			=> 'resigned_employees',
    		'required_permission'	=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#display_resigned_employees',
    		'onclick' 				=> 'javascript:hashClick("#display_resigned_employees");',
    		'wrapper_start'			=> '<li id="display_resigned_employees_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Resigned Employees'
    		);

		$btn_terminated_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'reports',
    		'child_index'			=> 'terminated_employees',
    		'required_permission'	=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#display_terminated_employees',
    		'onclick' 				=> 'javascript:hashClick("#display_terminated_employees");',
    		'wrapper_start'			=> '<li id="display_terminated_employees_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Terminated Employees'
    		);

		$btn_daily_time_record_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'reports',
    		'child_index'			=> 'reports_daily_time_record',
    		'required_permission'	=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#display_daily_time_record',
    		'onclick' 				=> 'javascript:hashClick("#display_daily_time_record");',
    		'wrapper_start'			=> '<li id="display_daily_time_record_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Daily Time Record'
    		);

		$btn_incomplete_time_in_out_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'reports',
    		'child_index'			=> 'inc_time_in_and_time_out',
    		'required_permission'		=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#display_incomplete_time_in_out',
    		'onclick' 				=> 'javascript:hashClick("#display_incomplete_time_in_out");',
    		'wrapper_start'			=> '<li id="display_incomplete_time_in_out_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Incomplete Time In / Out'
    		);

		$btn_incorrect_shift_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'reports',
    		'child_index'			=> 'reports_incorrect_shift',
    		'required_permission'		=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#display_incorrect_shift',
    		'onclick' 				=> 'javascript:hashClick("#display_incorrect_shift");',
    		'wrapper_start'			=> '<li id="display_incorrect_shift_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Incorrect Shift'
    		);

		$btn_timesheet_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'reports',
    		'child_index'			=> 'reports_timesheet',
    		'required_permission'		=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#display_timesheet',
    		'onclick' 				=> 'javascript:hashClick("#display_timesheet");',
    		'wrapper_start'			=> '<li id="display_timesheet_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Timesheet'
    		);

		$btn_disciplinary_action_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'reports',
    		'child_index'			=> 'reports_disciplinary_action',
    		'required_permission'		=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#display_disciplinary_action',
    		'onclick' 				=> 'javascript:hashClick("#display_disciplinary_action");',
    		'wrapper_start'			=> '<li id="display_disciplinary_action_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Disciplinary Action'
    		);

		$btn_loans_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'reports',
    		'child_index'			=> 'reports_loans',
    		'required_permission'		=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#display_loans',
    		'onclick' 				=> 'javascript:hashClick("#display_loans");',
    		'wrapper_start'			=> '<li id="display_loans_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Loans'
    		);

		$btn_employment_status_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'reports',
    		'child_index'			=> 'reports_employment_status',
    		'required_permission'		=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#display_employment_status',
    		'onclick' 				=> 'javascript:hashClick("#display_employment_status");',
    		'wrapper_start'			=> '<li id="display_employment_status_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Employment Status'
    		);

		$btn_employee_details_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'reports',
    		'child_index'			=> 'reports_employee_details',
    		'required_permission'		=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#display_employee_details',
    		'onclick' 				=> 'javascript:hashClick("#display_employee_details");',
    		'wrapper_start'			=> '<li id="display_employee_details_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Employee 201'
    		);

		$btn_ee_er_contribution_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'reports',
    		'child_index'			=> 'reports_ee_er_contribution',
    		'required_permission'		=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#display_ee_er_contribution',
    		'onclick' 				=> 'javascript:hashClick("#display_ee_er_contribution");',
    		'wrapper_start'			=> '<li id="display_ee_er_contribution_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'EE / ER Contribution'
    		);

		$btn_final_pay_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'reports',
    		'child_index'			=> 'reports_final_pay',
    		'required_permission'		=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#display_final_pay',
    		'onclick' 				=> 'javascript:hashClick("#display_final_pay");',
    		'wrapper_start'			=> '<li id="display_final_pay_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Resigned Accountability'
    		);

		$this->var['btn_final_pay_config']      		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_final_pay_config);
		$this->var['btn_birthday_config']      		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_birthday_config);
		$btn_perfect_attendance_config = array(
			'module'				=> 'hr',
			'parent_index'			=> 'reports',
			'child_index'			=> 'reports_perfect_attendance',
			'required_permission'		=> Sprint_Modules::PERMISSION_01,
			'href' 					=> '#display_perfect_attendance',
			'onclick' 				=> 'javascript:hashClick("#display_perfect_attendance");',
			'wrapper_start'			=> '<li id="display_perfect_attendance_nav" class="left_nav">',
			'wrapper_end'			=> '</li>',
			'caption' 				=> 'Perfect Attendance'
		);		

		$btn_coe_config = array(
			'module'				=> 'hr',
			'parent_index'			=> 'reports',
			'child_index'			=> 'reports_coe',
			'required_permission'		=> Sprint_Modules::PERMISSION_01,
			'href' 					=> '#display_coe',
			'onclick' 				=> 'javascript:hashClick("#display_coe");',
			'wrapper_start'			=> '<li id="display_coe_nav" class="left_nav">',
			'wrapper_end'			=> '</li>',
			'caption' 				=> 'Certificate of Employment'
		);	

		$btn_actual_hours_config = array(
			'module'				=> 'hr',
			'parent_index'			=> 'reports',
			'child_index'			=> 'reports_actual_hours',
			'required_permission'		=> Sprint_Modules::PERMISSION_01,
			'href' 					=> '#display_actual_hours',
			'onclick' 				=> 'javascript:hashClick("#display_actual_hours");',
			'wrapper_start'			=> '<li id="display_actual_hours_nav" class="left_nav">',
			'wrapper_end'			=> '</li>',
			'caption' 				=> 'Actual Hours'
		);	

		$btn_required_shift_config = array(
			'module'				=> 'hr',
			'parent_index'			=> 'reports',
			'child_index'			=> 'reports_required_shift',
			'required_permission'		=> Sprint_Modules::PERMISSION_01,
			'href' 					=> '#display_required_shift',
			'onclick' 				=> 'javascript:hashClick("#display_required_shift");',
			'wrapper_start'			=> '<li id="display_required_shift_nav" class="left_nav">',
			'wrapper_end'			=> '</li>',
			'caption' 				=> 'Required Shift'
		);

		$btn_government_remittances_config = array(
			'module'				=> 'hr',
			'parent_index'			=> 'reports',
			'child_index'			=> 'reports_government_remittances',
			'required_permission'		=> Sprint_Modules::PERMISSION_01,
			'href' 					=> '#display_government_remittances',
			'onclick' 				=> 'javascript:hashClick("#display_government_remittances");',
			'wrapper_start'			=> '<li id="display_government_remittances_nav" class="left_nav">',
			'wrapper_end'			=> '</li>',
			'caption' 				=> 'Government Remittances'
		);	
		
		$btn_last_pay_config = array(
			'module'				=> 'hr',
			'parent_index'			=> 'reports',
			'child_index'			=> 'reports_last_pay',
			'required_permission'		=> Sprint_Modules::PERMISSION_01,
			'href' 					=> '#display_last_pay',
			'onclick' 				=> 'javascript:hashClick("#display_last_pay");',
			'wrapper_start'			=> '<li id="display_last_pay_nav" class="left_nav">',
			'wrapper_end'			=> '</li>',
			'caption' 				=> 'Last Pay'
		);

		$this->var['btn_leave_balance_config']      = G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_leave_balance_config);
		$this->var['btn_terminated']				= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_terminated_config);
		$this->var['btn_resigned']					= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_resigned_config);
		$this->var['btn_loans'] 					= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_loans_config);
		$this->var['btn_absences'] 					= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_absences_config);
		$this->var['btn_tardiness'] 				= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_tardiness_config);
		$this->var['btn_overtime'] 					= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_overtime_config);
		$this->var['btn_undertime'] 				= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_undertime_config);
		$this->var['btn_leave'] 					= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_leave_config);
		$this->var['btn_incentive_leave'] 			= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_incentive_leave_config);
		$this->var['btn_manpower_count'] 			= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_manpower_count_config);
		$this->var['btn_end_of_contract'] 			= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_end_of_contract_config);
		$this->var['btn_daily_time_record'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_daily_time_record_config);
		$this->var['btn_incomplete_time_in_out'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_incomplete_time_in_out_config);
		$this->var['btn_incorrect_shift'] 			= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_incorrect_shift_config);
		$this->var['btn_timesheet'] 				= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_timesheet_config);
		$this->var['btn_disciplinary_action'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_disciplinary_action_config);
		$this->var['btn_employment_status'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_employment_status_config);
		$this->var['btn_employee_details'] 			= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_employee_details_config);
		$this->var['btn_ee_er_contribution'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_ee_er_contribution_config);
		$this->var['btn_shift_schedule_config']      = G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_shift_schedule_config);
		
		$this->var['btn_perfect_attendance'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_perfect_attendance_config);

		$this->var['btn_coe'] 						= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_coe_config);
		$this->var['btn_actual_hours'] 				= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_actual_hours_config);
		$this->var['btn_required_shift'] 			= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_required_shift_config);
		$this->var['btn_government_remittances'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_government_remittances_config);
		$this->var['btn_last_pay'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_last_pay_config);

		//general reports
		$btn_audit_trail_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'reports',
    		'child_index'			=> 'audit_trail',
    		'required_permission'	=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#audit_trail_data',
    		'onclick' 				=> 'javascript:hashClick("#audit_trail_data");',
    		'wrapper_start'			=> '<li id="audit_trail_data_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Audit Trail'
    		); 

		$this->var['btn_audit_trail'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_audit_trail_config);

		//end of general report----------

		$this->var['page_title'] = 'Reports';
		$this->var['sub_menu_time_management'] = true;
		$this->view->setTemplate('template_reports.php');
		$this->view->render('reports/index.php',$this->var);		
	}
	
	// payroll management menu
	function payroll_management()
	{
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTextBoxList();
		$this->var['page_title'] = 'Reports';
		$this->var['sub_menu_payroll_management'] = true;
		$this->view->setTemplate('payroll/template_reports.php');
		$this->view->render('reports/index.php',$this->var);		
	}
	
	function _load_sss_r1a_depre()
	{
		$this->validatePermission(G_Sprint_Modules::PAYROLL,'reports','sss');

		Utilities::ajaxRequest();
		$this->var['action'] = 'reports/sss';
		$this->var['title'] = "SSS";
		$this->view->noTemplate();
		$this->view->render('reports/contribution/forms/sss_form.php',$this->var);
	}

	function _load_sss_r1a()
	{
		//$this->validatePermission(G_Sprint_Modules::PAYROLL,'reports','payroll_register');

		Utilities::ajaxRequest();		        

        $employee_access = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_access');
        if($employee_access == Sprint_Modules::PERMISSION_05) {
			$is_with_confi_nonconfi_option = true;
		}else{
			$is_with_confi_nonconfi_option = false;
		}

		$emp_cost_center = G_Employee_Helper::getAllEmployeeCostCenter();

		//get all project sites
		$project_site = G_PROJECT_SITE::findAllProjectSite();
		$this->var['project_site'] = $project_site;

		$this->var['emp_cost_center'] 	= $emp_cost_center;
		$this->var['start_year']        = 2015;
		$this->var['is_with_confi_nonconfi_option'] = $is_with_confi_nonconfi_option;
        $this->var['ini_start']			= 1;        
        $this->var['action'] 		    = 'reports/payroll_register';
		$this->var['title'] 		    = "SSS";
        $this->var['cutoff_periods']    = $c;
		$this->view->noTemplate();
		$this->view->render('reports/contribution/forms/sss_form.php',$this->var);
	}
	
	function _load_philhealth_depre()
	{
		$this->validatePermission(G_Sprint_Modules::PAYROLL,'reports','philhealth');

		Utilities::ajaxRequest();
		$this->var['action'] = 'reports/philhealth';
		$this->var['title'] = "Philhealth";
		$this->view->noTemplate();
		$this->view->render('reports/contribution/forms/philhealth_form.php',$this->var);
	}	

	function _load_philhealth()
	{
		//$this->validatePermission(G_Sprint_Modules::PAYROLL,'reports','payroll_register');

		Utilities::ajaxRequest();		
        
        $employee_access = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_access');
        if($employee_access == Sprint_Modules::PERMISSION_05) {
			$is_with_confi_nonconfi_option = true;
		}else{
			$is_with_confi_nonconfi_option = false;
		}
		$emp_cost_center = G_Employee_Helper::getAllEmployeeCostCenter();

		//get all project sites
		$project_site = G_PROJECT_SITE::findAllProjectSite();
		$this->var['project_site'] = $project_site;

		$this->var['emp_cost_center'] 	= $emp_cost_center;
		$this->var['start_year']        = 2015;
		$this->var['is_with_confi_nonconfi_option'] = $is_with_confi_nonconfi_option;
        $this->var['ini_start']			= 1;        
        $this->var['action'] 		    = 'reports/payroll_register';
		$this->var['title'] 		    = "Philhealth";
        
		$this->view->noTemplate();
		$this->view->render('reports/contribution/forms/philhealth_form.php',$this->var);
	}

	function _load_alpha_list()
	{
		Utilities::ajaxRequest();

		$employee_access = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_access');		
		if($employee_access == Sprint_Modules::PERMISSION_05) {			
			$is_with_confi_nonconfi_option = true;
		}elseif($employee_access == Sprint_Modules::PERMISSION_06) {			
			$is_with_confi_nonconfi_option = false;
		}elseif($employee_access == Sprint_Modules::PERMISSION_07) {			
			$is_with_confi_nonconfi_option = false;
		}else{			
			$is_with_confi_nonconfi_option = true;
		}

		$this->var['is_with_confi_nonconfi_option'] = $is_with_confi_nonconfi_option;
		$this->var['start_year'] = 2015;
		$this->var['action'] = 'reports/alphalist';
		$this->var['title'] = "Alpha List";
		$this->view->noTemplate();
		$this->view->render('reports/contribution/forms/alphalist_form.php',$this->var);
	}

	function _load_bir_2316()
	{
		Utilities::ajaxRequest();

		$this->validatePermission(G_Sprint_Modules::PAYROLL,'reports','bir_2316');

		$employee_access = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_access');		
		if($employee_access == Sprint_Modules::PERMISSION_05) {			
			$is_with_confi_nonconfi_option = true;
		}elseif($employee_access == Sprint_Modules::PERMISSION_06) {			
			$is_with_confi_nonconfi_option = false;
		}elseif($employee_access == Sprint_Modules::PERMISSION_07) {			
			$is_with_confi_nonconfi_option = false;
		}else{			
			$is_with_confi_nonconfi_option = true;
		}

		$employment_status = G_Settings_Employment_Status_Finder::findAll();

		$this->var['is_with_confi_nonconfi_option'] = $is_with_confi_nonconfi_option;
		$this->var['employment_status'] = $employment_status;
		$this->var['start_year'] = 2015;
		$this->var['action'] = 'reports/bir_2316';
		$this->var['title'] = "BIR 2316";
		$this->view->noTemplate();
		$this->view->render('reports/contribution/forms/bir_2316.php',$this->var);
	}	

	function _load_yearly_bonus()
	{
		$this->validatePermission(G_Sprint_Modules::PAYROLL,'reports','yearly_bonus');		
		Utilities::ajaxRequest();
		$this->var['start_year']  = 2015;    
		$this->var['action'] = url('reports/download_yearly_bonus_report');
		$this->var['title'] = "13th Month Bonus";
		$this->view->noTemplate();
		$this->view->render('reports/yearly_bonus/forms/yearly_bonus_form.php',$this->var);
	}	

	function _load_leave_converted()
	{
		$this->validatePermission(G_Sprint_Modules::PAYROLL,'reports','leave_converted');		
		Utilities::ajaxRequest();
		$this->var['start_year']  = 2015;    
		$this->var['action'] = url('reports/download_leave_converted_report');
		$this->var['title'] = "Leave Converted";
		$this->view->noTemplate();
		$this->view->render('reports/leave_converted/forms/leave_converted_form.php',$this->var);
	}	
	
	function _load_pagibig()
	{
		//$this->validatePermission(G_Sprint_Modules::PAYROLL,'reports','payroll_register');

		Utilities::ajaxRequest();		       

        $employee_access = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_access');
        if($employee_access == Sprint_Modules::PERMISSION_05) {
			$is_with_confi_nonconfi_option = true;
		}else{
			$is_with_confi_nonconfi_option = false;
		}

		$emp_cost_center = G_Employee_Helper::getAllEmployeeCostCenter();

		//get all project sites
		$project_site = G_PROJECT_SITE::findAllProjectSite();
		$this->var['project_site'] = $project_site;

		$this->var['emp_cost_center'] 	= $emp_cost_center;
		$this->var['is_with_confi_nonconfi_option'] = $is_with_confi_nonconfi_option;
        $this->var['ini_start']			= 1;        
        $this->var['action'] 		    = 'reports/download_pagibig';
		$this->var['title'] 		    = "Pagibig";
        $this->var['start_year']        = 2015;
		$this->view->noTemplate();
		$this->view->render('reports/contribution/forms/pagibig_form.php',$this->var);
	}

	function _load_tax()
	{
		$this->validatePermission(G_Sprint_Modules::PAYROLL,'reports','tax');

		Utilities::ajaxRequest();
		$this->var['action'] = 'reports/tax';
		$this->var['title'] = "Tax";
		$this->view->noTemplate();
		$this->view->render('reports/contribution/forms/tax_form.php',$this->var);
	}

	function _load_annual_tax()
	{
		$this->validatePermission(G_Sprint_Modules::PAYROLL,'reports','tax');

		Utilities::ajaxRequest();
		$this->var['start_year'] = 2015;
		$this->var['end_year']   = date("Y");     
		$this->var['action'] = 'reports/annuliazed_tax';
		$this->var['title']  = "Annual Tax";
		$this->view->noTemplate();
		$this->view->render('reports/contribution/forms/annualized_tax.php',$this->var);
	}

	function _load_other_earnings()
	{
		$this->validatePermission(G_Sprint_Modules::PAYROLL,'reports','other_earnings');

		Utilities::ajaxRequest();		        
        
        $earnings_title = G_Employee_Earnings_Helper::sqlAllUniqueEmployeeEarnings();

		$this->var['earnings_title'] = $earnings_title;
        $this->var['ini_start']		 = 1;        
        $this->var['start_year']     = 2015;
        $this->var['action'] 		 = 'reports/payroll_register';
		$this->var['title'] 		 = "Other Earnings";        
		$this->view->noTemplate();
		$this->view->render('reports/contribution/forms/other_earnings_form.php',$this->var);
	}
	
	function _load_contribution()
	{
		Utilities::ajaxRequest();
		$this->var['action'] = 'reports/contribution';
		$this->var['title'] = "Contribution";
		$this->view->noTemplate();
		$this->view->render('reports/contribution/forms/contribution_form.php',$this->var);
	}
	
	function _load_payslip()
	{
		$this->validatePermission(G_Sprint_Modules::PAYROLL,'reports','payslip');

		Utilities::ajaxRequest();

		$employee_access = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_access');
        if($employee_access == Sprint_Modules::PERMISSION_05) {
			$is_with_confi_nonconfi_option = true;
		}else{
			$is_with_confi_nonconfi_option = false;
		}
		$emp_cost_center = G_Employee_Helper::getAllEmployeeCostCenter();


		//get all project sites
		$project_site = G_PROJECT_SITE::findAllProjectSite();
		$this->var['project_site'] = $project_site;


		$this->var['emp_cost_center'] 	= $emp_cost_center;
		$this->var['is_with_confi_nonconfi_option'] = $is_with_confi_nonconfi_option;
		$this->var['action'] = 'reports/payslip';
		$this->var['title']  = "Payslip";
        $this->var['start_year']     = 2015;
		$this->view->noTemplate();
		$this->view->render('reports/contribution/forms/payslip_form.php',$this->var);
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
	  	$c = G_Weekly_Cutoff_Period_Finder::findAllByYear($selected_year);
	  }
	  else if ($selected_frequency == 3) {
  		$c = G_Monthly_Cutoff_Period_Finder::findAllByYear($selected_year);
  		}
	  else {
	  	$c = G_Cutoff_Period_Finder::findAllByYear($selected_year);
	  }

	  
	  	$this->var['cutoff_periods'] = $c;
		$this->view->noTemplate();
		$this->view->render('reports/contribution/forms/_payroll_period.php',$this->var);
	}

	function _load_cash_file()
	{
		$this->validatePermission(G_Sprint_Modules::PAYROLL,'reports','cash_file');

		Utilities::ajaxRequest();

		$employee_access = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_access');
        if($employee_access == Sprint_Modules::PERMISSION_05) {
			$is_with_confi_nonconfi_option = true;
		}else{
			$is_with_confi_nonconfi_option = false;
		}

		$emp_cost_center = G_Employee_Helper::getAllEmployeeCostCenter();

		//get all project sites
		$project_site = G_PROJECT_SITE::findAllProjectSite();
		$this->var['project_site'] = $project_site;


		$this->var['emp_cost_center'] 	= $emp_cost_center;
		$this->var['is_with_confi_nonconfi_option'] = $is_with_confi_nonconfi_option;
		$this->var['action'] = 'reports/cash_file';
		$this->var['title'] = "Cash File";
        $this->var['start_year']     = 2015;
		$this->view->noTemplate();
		$this->view->render('reports/contribution/forms/cash_file.php',$this->var);
	}

	function _load_department_sections()
	{
		$id = Utilities::decrypt($_GET['eid']);
		if( !empty($id) ){			
			$fields   = array('id','title');
			$order_by = "ORDER BY title ASC";
			$cs = new G_Company_Structure();
			$cs->setParentId($id);
			$data = $cs->getAllIsNotArchiveDepartmentSections($fields, $order_by);

			$this->var['groups'] = $data;
			$this->view->noTemplate();
			$this->view->render('reports/time_management/manpower_count/_sections.php',$this->var);
		}	
	}

	function _load_payroll_register()
	{
		//$this->validatePermission(G_Sprint_Modules::PAYROLL,'reports','payroll_register');

		/*Utilities::ajaxRequest();		        

        $qry = new Query_Builder();
        $qry_options       = $qry->queryOptions();
        $qry_tbl_structure = $qry->payrollRegisterQueryStructure();

        $employee_access = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_access');
        if($employee_access == Sprint_Modules::PERMISSION_05) {
			$is_with_confi_nonconfi_option = true;
		}else{
			$is_with_confi_nonconfi_option = false;
		}
		$emp_cost_center = G_Employee_Helper::getAllEmployeeCostCenter();

		$this->var['emp_cost_center'] 	= $emp_cost_center;

		$this->var['start_year']        = 2015;
		$this->var['is_with_confi_nonconfi_option'] = $is_with_confi_nonconfi_option;
        $this->var['ini_start']			= 1;
        $this->var['qry_options']       = $qry_options;
        $this->var['qry_tbl_structure'] = $qry_tbl_structure;
        $this->var['action'] 		    = 'reports/payroll_register';
		$this->var['title'] 		    = "Payroll Register";
        $this->var['cutoff_periods']    = $c;
		$this->view->noTemplate();
		$this->view->render('reports/contribution/forms/payroll_register_form.php',$this->var);*/

		$this->_load_cost_center();

	}


     function _load_cost_center()
	{
		Utilities::ajaxRequest();		        

        $qry = new Query_Builder();
        $qry_options       = $qry->queryOptions();
        $qry_tbl_structure = $qry->payrollRegisterQueryStructure();

        $employee_access = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_access');
        if($employee_access == Sprint_Modules::PERMISSION_05) {
			$is_with_confi_nonconfi_option = true;
		}else{
			$is_with_confi_nonconfi_option = false;
		}

		$emp_cost_center = G_Employee_Helper::getAllEmployeeCostCenter();

		$project_site = G_PROJECT_SITE::findAllProjectSite();

		$this->var['project_site'] 	= $project_site;
		$this->var['emp_cost_center'] 	= $emp_cost_center;
		
		$this->var['start_year']        = 2017;
		$this->var['current_year']      = date("Y");
		$this->var['is_with_confi_nonconfi_option'] = $is_with_confi_nonconfi_option;
        $this->var['ini_start']			= 1;
        $this->var['qry_options']       = $qry_options;
        $this->var['qry_tbl_structure'] = $qry_tbl_structure;
        $this->var['action'] 		    = 'reports/cost_center';
		$this->var['title'] 		    = "Payroll Register / Cost Center";
        $this->var['cutoff_periods']    = $c;
		$this->view->noTemplate();
		$this->view->render('reports/contribution/forms/cost_center_form.php',$this->var);
	}


	
	function _load_payable()
	{
		Utilities::ajaxRequest();
		$this->var['action'] = 'reports/payable';
		$this->var['title'] = "Deduction Monitoring";
		$this->view->noTemplate();
		$this->view->render('reports/contribution/forms/payable_form.php',$this->var);
	}
	
	function _load_bank()
	{
		Utilities::ajaxRequest();
		$this->var['action'] = 'reports/bank';
		$this->var['title'] = "Bank Report";
		$this->view->noTemplate();
		$this->view->render('reports/bank/forms/bank_form.php',$this->var);
	}						
		
	function payroll_register() {
		$from = $this->var['from'] = $_POST['date_from'];//'2012-05-11';
		$to = $this->var['to'] = $_POST['date_to'];//'2012-05-25';
		$this->var['payout_date'] = $_POST['payout_date'];//'2012-05-31';
		
		$this->var['period'] = date('M j', strtotime($from)) .' to '. date('M j, Y', strtotime($to));
		$this->var['bill_date'] = date('M j, Y', strtotime($to));
		$this->var['due_date'] = date('M j, Y', strtotime($to));
		
		// to be used by Sheet: Billing 15th
		$this->var['address'] = '3/F Unit 14 AMJB Bldg., Aguinaldo Highway,';
		$this->var['street'] = 'cor By-Pass Road, Palico IV';
		$this->var['city'] = 'Imus, Cavite';
		
		$c = G_Company_Branch_Finder::findById(1);
		$this->var['company_name'] = $c->getName();
		$this->var['company_code'] = '';
		$this->var['company_location'] = $c->getAddress();
		//$this->var['employees'] = G_Employee_Finder::findAllActive();
				
		$this->var['employees'] = $employees = G_Employee_Finder::findByPayslipPeriod($from, $to);	
		$payslips = G_Payslip_Helper::getAllPayslipsByPeriodGroupByEmployee($from, $to);
		$this->var['payslips'] = $payslips;
		$this->var['total_employees'] = count($employees);
		
		//$this->view->render('reports/payroll_register/payroll_register_download.php', $this->var);
		$this->view->render('payroll/download_payroll_register.php', $this->var);
	}
	
	function contribution() {
		$from = $this->var['from'] = $_POST['date_from'];//'2012-05-11';
		$to = $this->var['to'] = $_POST['date_to'];//'2012-05-25';
		$this->var['payout_date'] = $_POST['date_to'];//'2012-05-31';
		
		$this->var['period'] = date('M j', strtotime($from)) .' to '. date('M j, Y', strtotime($to));
		$this->var['bill_date'] = date('M j, Y', strtotime($to));
		$this->var['due_date'] = date('M j, Y', strtotime($to));		
		$this->var['employees'] = $employees = G_Employee_Finder::findByPayslipPeriod($from, $to);
		$this->view->render('reports/contribution/contribution_download.php', $this->var);
	}

	function download_sss_report() {

		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
				
		$data = $_POST;
		$frequency_id        = $data['frequency_id'];

        if($data['report_type'] == 'per_pay_period'){

        	$a_periods = explode("/", $data['cutoff_period']);
			$from      = trim($a_periods[0]);
			$to        = trim($a_periods[1]);
        }
        else{


        	$month_number = $data['cutoff_period2'];
        	$year = $data['year_selecter'];
        	$month = '01-'.$month_number.'-'.$year;
        	$date = date_create($month);
        	$month_name = date_format($date,"F-Y"); //for reports header
        	
        	if($frequency_id == 2){

        		$period = G_Weekly_Cutoff_Period_Finder::findAllByMonthYear($year, $month_number);
        	}

        	elseif($frequency_id == 3){

        		$period = G_Monthly_Cutoff_Period_Finder::findAllByMonthYear($year, $month_number);
        	}

        	else{

        		$period = G_Cutoff_Period_Finder::findAllByMonthYear($year, $month_number);
        	}

 
        }
		

		$report_data = array();

		$remove_resigned   = false;
		$remove_terminated = false;
		$remove_endo       = false;
		$remove_inactive   = false;
		$qry_employee_type = '';
		$is_filter_by_cost_center = false;

		if( (isset($data['cost_center']) && $data['cost_center'] != '' && $data['cost_center'] != 'all')){
			$is_filter_by_cost_center   = true;
		}

		if( isset($data['sss_q']) ){
			$qry_employee_type = trim(strtolower($data['sss_q']));	
		}

		if( isset($data['sss_remove_resigned']) && $data['sss_remove_resigned'] == 1 ){
			$remove_resigned   = true;
		}
		if( isset($data['sss_remove_terminated']) && $data['sss_remove_terminated'] == 1 ){
			$remove_terminated = true;	
		}
		if( isset($data['sss_remove_endo']) && $data['sss_remove_endo'] == 1 ){
			$remove_endo = true;	
		}
		if( isset($data['sss_remove_inactive']) && $data['sss_remove_inactive'] == 1 ){
			$remove_inactive = true;	
		}

		$this->var['from'] = $from;
		$this->var['to']   = $to; 

		if (strtotime($from) && strtotime($to) || $period ) {

			$employee_access = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_access');
			if($employee_access == Sprint_Modules::PERMISSION_05) {
				if($qry_employee_type == "confidential") {
					$is_confidential_qry = " AND (e.is_confidential = 1) ";	
					$employee_type = "Confidential Employees";
				}elseif($qry_employee_type == "non-confidential"){
					$is_confidential_qry = " AND (e.is_confidential = 0) ";
					$employee_type = "Non-confidential Employees";
				}else{
					$is_confidential_qry = "";
					$employee_type       = "";
				}
			}elseif($employee_access == Sprint_Modules::PERMISSION_06) {
				$is_confidential_qry = " AND (e.is_confidential = 1) ";	
				$employee_type 		 = "Confidential Employees";
			}elseif($employee_access == Sprint_Modules::PERMISSION_07) {
				$is_confidential_qry = " AND (e.is_confidential = 0) ";
				$employee_type 		 = "Non-confidential Employees";
			}else{
				$is_confidential_qry = "";
				$employee_type       = "";
			}

			if( $remove_resigned ){
				//$qry_add_on[] = " AND e.resignation_date < " . Model::safeSql($from);
				$qry_add_on[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
			}

			if( $remove_terminated ){
				//$qry_add_on[] = " AND e.terminated_date <" . Model::safeSql($from);
				$qry_add_on[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
			}

			if( $remove_endo ){
				//$qry_add_on[] = " AND e.terminated_date <" . Model::safeSql($from);
				$qry_add_on[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
			}

			if($is_filter_by_cost_center){
				$qry_add_on[] = "(e.cost_center = '".$data['cost_center']."')";
			}

			if( $remove_inactive ){
				$qry_add_on[] = "(e.inactive_date = '0000-00-00' OR e.inactive_date = '')";
			}

			//remove archive employee in report
			$qry_add_on[] = "(e.e_is_archive = 'No')";

			if( !empty($qry_add_on) ){
				$is_confidential_qry .= " AND " . implode(" AND ", $qry_add_on);
			}

			$r = new G_Report();							
			$r->setFromDate($from);
			$r->setToDate($to);

			if ($frequency_id == 2) {

				if($data['report_type'] == 'per_pay_period'){

					$report_data = $r->getWeeklySSSContributionsNoDup( $is_confidential_qry );
					$this->var['header2']   = "SSS Contribution : {$from} to {$to}";
			        $this->var['data'] = $report_data;
				}
				else{


					foreach($period as $p){
						
							$r->setFromDate($p->getStartDate());
							$r->setToDate($p->getEndDate());
							$report_data2[] = $r->getWeeklySSSContributionsNoDup( $is_confidential_qry );
						}

						$test =array();

						$test = array_merge((array)$report_data2[0],(array)$report_data2[1], (array)$report_data2[2], (array)$report_data2[3], (array)$report_data2[4]);

						
						$group_array = array();

						foreach($test as $dkey => $d) {
							$group_array[$d['employee_code']][] = $d;
						}
						$converted_contributions = array();
						foreach($group_array as $dgkey => $dg) {
							foreach($dg as $d) {
								$converted_contributions[$dgkey]['employee_code'] = $d['employee_code'];
								$converted_contributions[$dgkey]['firstname'] = $d['firstname'];
								$converted_contributions[$dgkey]['lastname']  = $d['lastname'];
								$converted_contributions[$dgkey]['section_name'] = $d['section_name'];
								$converted_contributions[$dgkey]['department_name'] = $d['department_name'];
								$converted_contributions[$dgkey]['sss_contribution'] += $d['sss_contribution'];
								$converted_contributions[$dgkey]['sss_number'] = $d['sss_number'];
								$converted_contributions[$dgkey]['status'] = $d['status'];
								$converted_contributions[$dgkey]['company_share'] += $d['company_share'];
								$converted_contributions[$dgkey]['company_ec'] += $d['company_ec'];
								$converted_contributions[$dgkey]['provident_ee'] += $d['provident_ee'];
								$converted_contributions[$dgkey]['provident_er'] += $d['provident_er'];


							}
							}

								
								$this->var['header2']   = "SSS Contribution : {$month_name}";
								$this->var['data'] = $converted_contributions;

				}
				
					
			}

			elseif($frequency_id == 3){

					if($data['report_type'] == 'per_pay_period'){

						$report_data = $r->getMonthlySSSContributionsNoDup( $is_confidential_qry );
							
							$this->var['header2']   = "SSS Contribution : {$from} to {$to}";
							$this->var['data'] = $report_data;
							
					}

					else{

						foreach($period as $p){
						
							$r->setFromDate($p->getStartDate());
							$r->setToDate($p->getEndDate());
							$report_data2[] = $r->getMonthlySSSContributionsNoDup( $is_confidential_qry );
						}

						$test =array();

						$test = array_merge((array)$report_data2[0],(array)$report_data2[1] );

						$group_array = array();

						foreach($test as $dkey => $d) {
							$group_array[$d['employee_code']][] = $d;
						}
						$converted_contributions = array();
						foreach($group_array as $dgkey => $dg) {
							foreach($dg as $d) {
								$converted_contributions[$dgkey]['employee_code'] = $d['employee_code'];
								$converted_contributions[$dgkey]['firstname'] = $d['firstname'];
								$converted_contributions[$dgkey]['lastname']  = $d['lastname'];
								$converted_contributions[$dgkey]['section_name'] = $d['section_name'];
								$converted_contributions[$dgkey]['department_name'] = $d['department_name'];
								$converted_contributions[$dgkey]['sss_contribution'] += $d['sss_contribution'];
								$converted_contributions[$dgkey]['sss_number'] = $d['sss_number'];
								$converted_contributions[$dgkey]['status'] = $d['status'];
								$converted_contributions[$dgkey]['company_share'] += $d['company_share'];
								$converted_contributions[$dgkey]['company_ec'] += $d['company_ec'];
								$converted_contributions[$dgkey]['provident_ee'] += $d['provident_ee'];
								$converted_contributions[$dgkey]['provident_er'] += $d['provident_er'];


							}
							}

								//utilities::displayArray($converted_contributions);exit();
								$this->var['header2']   = "SSS Contribution : {$month_name}";
								$this->var['data'] = $converted_contributions;
								
								
					}


			}




			else {

					if($data['report_type'] == 'per_pay_period'){

						$report_data = $r->getSSSContributionsNoDup( $is_confidential_qry );
							
							$this->var['header2']   = "SSS Contribution : {$from} to {$to}";
							$this->var['data'] = $report_data;
							
					}
					else{

						foreach($period as $p){
						
							$r->setFromDate($p->getStartDate());
							$r->setToDate($p->getEndDate());
							$report_data2[] = $r->getSSSContributionsNoDup( $is_confidential_qry );
						}

						$test =array();

						$test = array_merge((array)$report_data2[0],(array)$report_data2[1] );

						$group_array = array();

						foreach($test as $dkey => $d) {
							$group_array[$d['employee_code']][] = $d;
						}
						$converted_contributions = array();
						foreach($group_array as $dgkey => $dg) {
							foreach($dg as $d) {
								$converted_contributions[$dgkey]['employee_code'] = $d['employee_code'];
								$converted_contributions[$dgkey]['firstname'] = $d['firstname'];
								$converted_contributions[$dgkey]['lastname']  = $d['lastname'];
								$converted_contributions[$dgkey]['section_name'] = $d['section_name'];
								$converted_contributions[$dgkey]['department_name'] = $d['department_name'];
								$converted_contributions[$dgkey]['sss_contribution'] += $d['sss_contribution'];
								$converted_contributions[$dgkey]['sss_number'] = $d['sss_number'];
								$converted_contributions[$dgkey]['status'] = $d['status'];
								$converted_contributions[$dgkey]['company_share'] += $d['company_share'];
								$converted_contributions[$dgkey]['company_ec'] += $d['company_ec'];
								$converted_contributions[$dgkey]['provident_ee'] += $d['provident_ee'];
								$converted_contributions[$dgkey]['provident_er'] += $d['provident_er'];


							}
							}

								
								$this->var['header2']   = "SSS Contribution : {$month_name}";
								$this->var['data'] = $converted_contributions;
								
								
					}
							
			}

		}	

		$this->var['filename'] = "sss_contributions.xls";
		$this->var['header1']   = G_Company_Structure_Helper::sqlCompanyName();
	    $this->view->render('reports/contribution/sss_download.php', $this->var);	
		

	
	}

	function download_earnings_report() {
		$data = $_POST;
		
		if( $data['chk_all_earnings'] ){
			$earnings = array();
		}else{			
			$earnings = $data['earnings'];
		}
		
		$param = array();
		$report = new G_Report();
		$report_data = $report->getOtherEarningsReport($data['cutoff_period'], $earnings);

		$this->var['filename'] = "other_earnings.xls";
		$this->var['header1']  = "Payroll Period : " . $data['cutoff_period'];		
		$this->var['data'] = $report_data;
		$this->view->render('reports/other_earnings/other_earnings_download.php', $this->var);
	}

	function download_yearly_bonus_report() {

		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
				
		$data = $_POST;
		$year = $data['year_payroll'];		
		$report_data = array();

		$remove_resigned   = false;
		$remove_terminated = false;
		$remove_endo       = false;
		$remove_inactive   = false;
		$qry_employee_type = '';

		if( isset($data['yearly_bonus_q']) ){
			$qry_employee_type = trim(strtolower($data['yearly_bonus_q']));	
		}

		if( isset($data['yearly_bonus_remove_resigned']) && $data['yearly_bonus_remove_resigned'] == 1 ){
			$remove_resigned   = true;
		}
		if( isset($data['yearly_bonus_remove_terminated']) && $data['yearly_bonus_remove_terminated'] == 1 ){
			$remove_terminated = true;	
		}
		if( isset($data['yearly_bonus_remove_endo']) && $data['yearly_bonus_remove_endo'] == 1 ){
			$remove_endo = true;	
		}
		if( isset($data['yearly_bonus_remove_inactive']) && $data['yearly_bonus_remove_inactive'] == 1 ){
			$remove_inactive = true;	
		}

		$this->var['from'] = $from;
		$this->var['to']   = $to; 

		if ( $year ) {

			$employee_access = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_access');
			if($employee_access == Sprint_Modules::PERMISSION_05) {
				if($qry_employee_type == "confidential") {
					$is_confidential_qry = " AND (e.is_confidential = 1) ";	
					$employee_type = "Confidential Employees";
				}elseif($qry_employee_type == "non-confidential"){
					$is_confidential_qry = " AND (e.is_confidential = 0) ";
					$employee_type = "Non-confidential Employees";
				}else{
					$is_confidential_qry = "";
					$employee_type       = "";
				}
			}elseif($employee_access == Sprint_Modules::PERMISSION_06) {
				$is_confidential_qry = " AND (e.is_confidential = 1) ";	
				$employee_type 		 = "Confidential Employees";
			}elseif($employee_access == Sprint_Modules::PERMISSION_07) {
				$is_confidential_qry = " AND (e.is_confidential = 0) ";
				$employee_type 		 = "Non-confidential Employees";
			}else{
				$is_confidential_qry = "";
				$employee_type       = "";
			}

			if( $remove_resigned ){
				//$qry_add_on[] = " AND e.resignation_date < " . Model::safeSql($from);
				$qry_add_on[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
			}

			if( $remove_terminated ){
				//$qry_add_on[] = " AND e.terminated_date <" . Model::safeSql($from);
				$qry_add_on[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
			}

			if( $remove_endo ){
				//$qry_add_on[] = " AND e.terminated_date <" . Model::safeSql($from);
				$qry_add_on[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
			}

			if( $remove_inactive ){
				$qry_add_on[] = "(e.inactive_date = '0000-00-00' OR e.inactive_date = '')";
			}

			if( !empty($qry_add_on) ){
				$is_confidential_qry .= " AND " . implode(" AND ", $qry_add_on);
			}		

			/*$r = new G_Report();										
			$report_data = $r->getYearlyBonus( $year, $is_confidential_qry );*/

			$is_confidential_qrys = "";
			/*$qry_add_ons[] = "(e.employee_code = 225)";
			if( !empty($qry_add_on) ){
				$is_confidential_qrys .= " AND " . implode(" AND ", $qry_add_ons);
			}*/				

			$e = new G_Employee();
			$query['year'] = $year;			
			$report_data = $e->getEmployeesYearlyBonusByYear($query, $is_confidential_qry);
		
		}

		//utilities::displayArray($report_data);exit();

		$this->var['year'] = $year;				
		$this->var['header1']   = "13th Month Pay";
		$this->var['data'] = $report_data;
		$this->view->render('reports/yearly_bonus/yearly_bonus_download2.php', $this->var);
	}

	function download_leave_converted_report() {

		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
				
		$data = $_POST;
		$year = $data['year_payroll'];		
		$report_data = array();

		$remove_resigned   = false;
		$remove_terminated = false;
		$remove_endo       = false;
		$remove_inactive   = false;
		$qry_employee_type = '';

		if( isset($data['leave_converted_q']) ){
			$qry_employee_type = trim(strtolower($data['yearly_bonus_q']));	
		}

		if( isset($data['leave_converted_remove_resigned']) && $data['leave_converted_remove_resigned'] == 1 ){
			$remove_resigned   = true;
		}
		if( isset($data['leave_converted_remove_terminated']) && $data['leave_converted_remove_terminated'] == 1 ){
			$remove_terminated = true;	
		}
		if( isset($data['leave_converted_remove_endo']) && $data['leave_converted_remove_endo'] == 1 ){
			$remove_endo = true;	
		}
		if( isset($data['leave_converted_remove_inactive']) && $data['leave_converted_remove_inactive'] == 1 ){
			$remove_inactive = true;	
		}

		$this->var['from'] = $from;
		$this->var['to']   = $to; 

		if ( $year ) {

			$employee_access = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_access');
			if($employee_access == Sprint_Modules::PERMISSION_05) {
				if($qry_employee_type == "confidential") {
					$is_confidential_qry = " AND (e.is_confidential = 1) ";	
					$employee_type = "Confidential Employees";
				}elseif($qry_employee_type == "non-confidential"){
					$is_confidential_qry = " AND (e.is_confidential = 0) ";
					$employee_type = "Non-confidential Employees";
				}else{
					$is_confidential_qry = "";
					$employee_type       = "";
				}
			}elseif($employee_access == Sprint_Modules::PERMISSION_06) {
				$is_confidential_qry = " AND (e.is_confidential = 1) ";	
				$employee_type 		 = "Confidential Employees";
			}elseif($employee_access == Sprint_Modules::PERMISSION_07) {
				$is_confidential_qry = " AND (e.is_confidential = 0) ";
				$employee_type 		 = "Non-confidential Employees";
			}else{
				$is_confidential_qry = "";
				$employee_type       = "";
			}

			if( $remove_resigned ){
				//$qry_add_on[] = " AND e.resignation_date < " . Model::safeSql($from);
				$qry_add_on[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
			}

			if( $remove_terminated ){
				//$qry_add_on[] = " AND e.terminated_date <" . Model::safeSql($from);
				$qry_add_on[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
			}

			if( $remove_endo ){
				//$qry_add_on[] = " AND e.terminated_date <" . Model::safeSql($from);
				$qry_add_on[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
			}

			if( $remove_inactive ){
				$qry_add_on[] = "(e.inactive_date = '0000-00-00' OR e.inactive_date = '')";
			}

			if( !empty($qry_add_on) ){
				$is_confidential_qry .= " AND " . implode(" AND ", $qry_add_on);
			}

			/*$r = new G_Report();										
			$report_data = $r->getYearlyBonus( $year, $is_confidential_qry );*/
			$e = new G_Employee();
			$query['year'] = $year;			
			$report_data = $e->getEmployeesYearlyConvertedLeave($query, $is_confidential_qry);
		
		}						
		$this->var['header1']   = "Leave converted : {$year}";
		$this->var['data'] = $report_data;
		$this->view->render('reports/leave_converted/leave_converted_download.php', $this->var);
	}
	
	function sss_depre() {

		$data = $_POST;		
		$encrypted_dept_ids = explode(",", $data['contri_search_keywords_department']);
		$encrypted_emp_ids  = explode(",", $data['contri_search_keywords_employee']);
		$date_from 			= date('d-M-Y', strtotime($data['date_from']));
		$date_to   			= date('d-M-Y', strtotime($data['date_to']));

		if( $data['contri_all'] ){
			$new_emp_ids  = array('all');
			$new_dept_ids = array('all');
		}else{
			foreach($encrypted_emp_ids as $id){
				$new_emp_ids[] = Utilities::decrypt($id);
			}

			foreach($encrypted_dept_ids as $id){
				$new_dept_ids[] = Utilities::decrypt($id);
			}
		}

		$r = new G_Report();				
		$r->setToDate($date_to);
		$r->setFromDate($date_from);

		if( $data['contri-search-by'] == 'department' ){
			$r->setDepartmentIds($new_dept_ids);
		}else{
			$r->setEmployeeIds($new_emp_ids);
		}

		$data = $r->getEmployeeSSSContributions();

		$this->var['filename'] = "sss_contribution.xls";
		$this->var['header']   = "SSS Contribution : {$date_from} to {$date_to}";		
		$this->var['data']     = $data;
		$this->view->render('reports/contribution/sss_download.php', $this->var);
	}

	function alphalist() {
		$year = $_POST['alpha_year'];
		if( isset($_POST['q']) ){
			$qry_employee_type = trim(strtolower($_POST['q']));	
		}

		$employee_access = $this->validatePermission(G_Sprint_Modules::PAYROLL,'reports','alphalist');

		if($employee_access == Sprint_Modules::PERMISSION_05) {
			if($qry_employee_type == "confidential") {
				$is_confidential_qry = " AND (e.is_confidential = 1) ";	
				$employee_type = "Confidential Employees";
			}elseif($qry_employee_type == "non-confidential"){
				$is_confidential_qry = " AND (e.is_confidential = 0) ";
				$employee_type = "Non-confidential Employees";
			}else{
				$is_confidential_qry = "";
				$employee_type       = "";
			}
		}elseif($employee_access == Sprint_Modules::PERMISSION_06) {
			$is_confidential_qry = " AND (e.is_confidential = 1) ";	
			$employee_type 		 = "Confidential Employees";
		}elseif($employee_access == Sprint_Modules::PERMISSION_07) {
			$is_confidential_qry = " AND (e.is_confidential = 0) ";
			$employee_type 		 = "Non-confidential Employees";
		}else{		
			//$is_confidential_qry = "";
			//$employee_type       = "";
			if($qry_employee_type == "confidential") {
				$is_confidential_qry = " AND (e.is_confidential = 1) ";	
				$employee_type = "Confidential Employees";
			}elseif($qry_employee_type == "non-confidential"){
				$is_confidential_qry = " AND (e.is_confidential = 0) ";
				$employee_type = "Non-confidential Employees";
			}else{
				$is_confidential_qry = "";
				$employee_type       = "";
			}			
		}

		$options['add_query'] = $is_confidential_qry;				
		$report = new G_Report();

		if($_POST['report_type'] == 'detailed'){
			if($qry_employee_type == "confidential") {
			if($year == '2016') {
			
				$alpha_data = $report->alphaListReportCustomFixJanuaryPayslip($year, $options);
			} else {
			
				$alpha_data = $report->alphaListReport($year, $options);		
			}
			
		} else {
			// var_dump($year);
			// var_dump($options);
			$alpha_data = $report->alphaListReport($year, $options);	
			// echo "<pre>";
			// var_dump($alpha_data);
			// echo "</pre>";

			// exit();
			
		}
	}else{
		
						if($qry_employee_type == "confidential") {
						if($year == '2016') {
						
							$alpha_data = $report->alphaListReportCustomFixJanuaryPayslip($year, $options);
						} else {
						
							$alpha_data = $report->alphaListReportSummarized($year, $options);		
						}
						
					} else {
						// var_dump($year);
						// var_dump($options);
						$alpha_data = $report->alphaListReportSummarized($year, $options);	
					// echo "<pre>";
					// var_dump($alpha_data);
					// echo "</pre>";

						
						
					}

	}
    
        //for sorting array by lastname
		array_multisort(array_map(function($element) {
		      return $element['lastname'];
		  }, $alpha_data), SORT_ASC, $alpha_data);

		//utilities::displayArray($alpha_data);exit();

		$a_sort = array();
		foreach ($alpha_data as $key => $row)
		{
		    $a_sort[$key] = $row['lastname'];
		}

		// array_multisort($a_sort, SORT_ASC, $alpha_data);	


 
		$this->var['filename'] = "alphalist.xls";
		$this->var['header']   = "Alpha List : {$year}";		
		$this->var['data']     = $alpha_data;		
		
		if($_POST['report_type'] == 'detailed'){
			

		
				$this->var['header']   = "Alpha List : {$year} Detailed ({$qry_employee_type})";		
				$this->view->render('reports/contribution/alphalist.php', $this->var);
		}else{
		
				$this->var['header']   = "Alpha List : {$year} Summarized ({$qry_employee_type})";		
				$this->view->render('reports/contribution/alphalistsummarized.php', $this->var);
		}

		
	}

	function download_pagibig() {
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
				
		$data = $_POST;	
		
		$frequency_id        = $data['frequency_id'];

		 if($data['report_type'] == 'per_pay_period'){
			$a_periods = explode("/", $data['cutoff_period']);
			$from      = trim($a_periods[0]);
			$to        = trim($a_periods[1]);

		 }else{


        	$month_number = $data['cutoff_period2'];
        	$year = $data['year_selecter'];
        	$month = '01-'.$month_number.'-'.$year;
        	$date = date_create($month);
        	$month_name = date_format($date,"F-Y"); //for reports header
        	
        	if($frequency_id == 2){

        		$period = G_Weekly_Cutoff_Period_Finder::findAllByMonthYear($year, $month_number);
        	}
        	elseif($frequency_id == 3){

        		$period = G_Monthly_Cutoff_Period_Finder::findAllByMonthYear($year, $month_number);
        	}
        	else{

        		$period = G_Cutoff_Period_Finder::findAllByMonthYear($year, $month_number);
        	}

 
        }

		$report_data = array();

		$remove_resigned   = false;
		$remove_terminated = false;
		$remove_endo       = false;
		$remove_inactive   = false;
		$is_filter_by_cost_center = false;
		$qry_employee_type = '';


		if( (isset($data['cost_center']) && $data['cost_center'] != '' && $data['cost_center'] != 'all')){
			$is_filter_by_cost_center   = true;
		}


		if( isset($data['pagibig_q']) ){
			$qry_employee_type = trim(strtolower($data['pagibig_q']));	
		}

		if( isset($data['pagibig_remove_resigned']) && $data['pagibig_remove_resigned'] == 1 ){			
			$remove_resigned   = true;
		}
		if( isset($data['pagibig_remove_terminated']) && $data['pagibig_remove_terminated'] == 1 ){
			$remove_terminated = true;	
		}
		if( isset($data['pagibig_remove_endo']) && $data['pagibig_remove_endo'] == 1 ){
			$remove_endo = true;	
		}
		if( isset($data['pagibig_remove_inactive']) && $data['pagibig_remove_inactive'] == 1 ){
			$remove_inactive = true;	
		}

		$this->var['from'] = $from;
		$this->var['to']   = $to; 

		if (strtotime($from) && strtotime($to) || $period) {

			$employee_access = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_access');
			if($employee_access == Sprint_Modules::PERMISSION_05) {
				if($qry_employee_type == "confidential") {
					$is_confidential_qry = " AND (e.is_confidential = 1) ";	
					$employee_type = "Confidential Employees";
				}elseif($qry_employee_type == "non-confidential"){
					$is_confidential_qry = " AND (e.is_confidential = 0) ";
					$employee_type = "Non-confidential Employees";
				}else{
					$is_confidential_qry = "";
					$employee_type       = "";
				}
			}elseif($employee_access == Sprint_Modules::PERMISSION_06) {
				$is_confidential_qry = " AND (e.is_confidential = 1) ";	
				$employee_type 		 = "Confidential Employees";
			}elseif($employee_access == Sprint_Modules::PERMISSION_07) {
				$is_confidential_qry = " AND (e.is_confidential = 0) ";
				$employee_type 		 = "Non-confidential Employees";
			}else{
				$is_confidential_qry = "";
				$employee_type       = "";
			}

			if( $remove_resigned ){				
				//$qry_add_on[] = " AND e.resignation_date < " . Model::safeSql($from);
				$qry_add_on[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
			}

			if( $remove_terminated ){
				//$qry_add_on[] = " AND e.terminated_date <" . Model::safeSql($from);
				$qry_add_on[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
			}

			if( $remove_endo ){
				//$qry_add_on[] = " AND e.terminated_date <" . Model::safeSql($from);
				$qry_add_on[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
			}

			if($is_filter_by_cost_center){
				$qry_add_on[] = "(e.cost_center = '".$data['cost_center']."')";
			}

			if( $remove_inactive ){
				$qry_add_on[] = "(e.inactive_date = '0000-00-00' OR e.inactive_date = '')";
			}

			//remove archive employee in report
			$qry_add_on[] = "(e.e_is_archive = 'No')";

			if( !empty($qry_add_on) ){
				$is_confidential_qry .= " AND " . implode(" AND ", $qry_add_on);
			}

			$r = new G_Report();							
			$r->setFromDate($from);
			$r->setToDate($to);

			if ($frequency_id == 2) {

				if($data['report_type'] == 'per_pay_period'){
				$report_data = $r->getWeeklyPagibigContributionsNoDup( $is_confidential_qry );
				$this->var['data'] = $report_data;
				//utilities::displayArray($report_data);exit();

				}
					else{

						foreach($period as $p){
						
							$r->setFromDate($p->getStartDate());
							$r->setToDate($p->getEndDate());
							$report_data2[] = $r->getWeeklyPagibigContributionsNoDup( $is_confidential_qry );
						}

						$test =array();
						$test = array_merge((array)$report_data2[0],(array)$report_data2[1], (array)$report_data2[2], (array)$report_data2[3], (array)$report_data2[4]);
						
						$group_array = array();

						foreach($test as $dkey => $d) {
							$group_array[$d['employee_code']][] = $d;
						}
						$converted_contributions = array();
						foreach($group_array as $dgkey => $dg) {
							foreach($dg as $d) {
								$converted_contributions[$dgkey]['employee_code'] = $d['employee_code'];
								$converted_contributions[$dgkey]['firstname'] = $d['firstname'];
								$converted_contributions[$dgkey]['lastname']  = $d['lastname'];
								$converted_contributions[$dgkey]['middlename']  = $d['middlename'];
								$converted_contributions[$dgkey]['extension_name']  = $d['extension_name'];
								$converted_contributions[$dgkey]['section_name'] = $d['section_name'];
								$converted_contributions[$dgkey]['department_name'] = $d['department_name'];
								$converted_contributions[$dgkey]['pagibig_contribution'] += $d['pagibig_contribution'];
								$converted_contributions[$dgkey]['pagibig_number'] = $d['pagibig_number'];
								$converted_contributions[$dgkey]['status'] = $d['status'];
								$converted_contributions[$dgkey]['pagibig_employer'] += $d['pagibig_employer'];
								$converted_contributions[$dgkey]['period_start'] = $month;

								if($d['pagibig_contribution'] != 0){
									$converted_contributions[$dgkey]['pagibig_er_contribution'] += $d['pagibig_er_contribution'];
								}
								
								


							}
							}

								
								//$this->var['header2']   = "SSS Contribution : {$month_name}";
								$this->var['data'] = $converted_contributions;
								

								
								
					}


			}

			elseif($frequency_id == 3){

					if($data['report_type'] == 'per_pay_period'){


						$report_data = $r->getMonthlyPagibigContributionsNoDup( $is_confidential_qry );
						$this->var['data'] = $report_data;

					}
					else{

						foreach($period as $p){
						
							$r->setFromDate($p->getStartDate());
							$r->setToDate($p->getEndDate());
							$report_data2[] = $r->getMonthlyPagibigContributionsNoDup( $is_confidential_qry );
						}

						$test =array();

						$test = array_merge((array)$report_data2[0],(array)$report_data2[1] );
						$group_array = array();

						foreach($test as $dkey => $d) {
							$group_array[$d['employee_code']][] = $d;
						}
						$converted_contributions = array();
						foreach($group_array as $dgkey => $dg) {
							foreach($dg as $d) {
								$converted_contributions[$dgkey]['employee_code'] = $d['employee_code'];
								$converted_contributions[$dgkey]['firstname'] = $d['firstname'];
								$converted_contributions[$dgkey]['lastname']  = $d['lastname'];
								$converted_contributions[$dgkey]['middlename']  = $d['middlename'];
								$converted_contributions[$dgkey]['extension_name']  = $d['extension_name'];
								$converted_contributions[$dgkey]['section_name'] = $d['section_name'];
								$converted_contributions[$dgkey]['department_name'] = $d['department_name'];
								$converted_contributions[$dgkey]['pagibig_contribution'] += $d['pagibig_contribution'];
								
								$converted_contributions[$dgkey]['pagibig_number'] = $d['pagibig_number'];
								$converted_contributions[$dgkey]['status'] = $d['status'];
								$converted_contributions[$dgkey]['pagibig_employer'] += $d['pagibig_employer'];
								$converted_contributions[$dgkey]['period_start'] = $month;

								if($d['pagibig_contribution'] != 0){
									$converted_contributions[$dgkey]['pagibig_er'] += $d['pagibig_er'];
								}
								
								
								


							}
							}

								
								//$this->var['header2']   = "SSS Contribution : {$month_name}";
								$this->var['data'] = $converted_contributions;

					}


			}

			else {

				if($data['report_type'] == 'per_pay_period'){
					$report_data = $r->getPagibigContributionsNoDup( $is_confidential_qry );
					$this->var['data'] = $report_data;
					//utilities::displayArray($report_data);exit();
				}
					else{

						foreach($period as $p){
						
							$r->setFromDate($p->getStartDate());
							$r->setToDate($p->getEndDate());
							$report_data2[] = $r->getPagibigContributionsNoDup( $is_confidential_qry );
						}

						$test =array();

						$test = array_merge((array)$report_data2[0],(array)$report_data2[1] );
						$group_array = array();

						foreach($test as $dkey => $d) {
							$group_array[$d['employee_code']][] = $d;
						}
						$converted_contributions = array();
						foreach($group_array as $dgkey => $dg) {
							foreach($dg as $d) {
								$converted_contributions[$dgkey]['employee_code'] = $d['employee_code'];
								$converted_contributions[$dgkey]['firstname'] = $d['firstname'];
								$converted_contributions[$dgkey]['lastname']  = $d['lastname'];
								$converted_contributions[$dgkey]['middlename']  = $d['middlename'];
								$converted_contributions[$dgkey]['extension_name']  = $d['extension_name'];
								$converted_contributions[$dgkey]['section_name'] = $d['section_name'];
								$converted_contributions[$dgkey]['department_name'] = $d['department_name'];
								$converted_contributions[$dgkey]['pagibig_contribution'] += $d['pagibig_contribution'];
								
								$converted_contributions[$dgkey]['pagibig_number'] = $d['pagibig_number'];
								$converted_contributions[$dgkey]['status'] = $d['status'];
								$converted_contributions[$dgkey]['pagibig_employer'] += $d['pagibig_employer'];
								$converted_contributions[$dgkey]['period_start'] = $month;

								if($d['pagibig_contribution'] != 0){
									$converted_contributions[$dgkey]['pagibig_er'] += $d['pagibig_er'];
								}
								
								
								


							}
							}

								
								//$this->var['header2']   = "SSS Contribution : {$month_name}";
								$this->var['data'] = $converted_contributions;
								
								
					}



			}
		
		}		

		$this->var['frequency_id'] = $frequency_id;
		$company_info = G_Company_Structure_Helper::sqlCompanyInfo();
		$this->var['filename'] = "pagibig_contributions.xls";
		$this->var['header1']   = G_Company_Structure_Helper::sqlCompanyName();
		if($data['report_type'] == 'per_pay_period'){
		$this->var['header2']   = "PAGIBIG Contribution : {$from} to {$to}";
		}
		else{
			$this->var['header2']   = "PAGIBIG Contribution : {$month_name}";
		}
		$this->view->render('reports/contribution/pagibig_download.php', $this->var);
	}

	function download_philhealth() {
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
				
		$data = $_POST;		
		$frequency_id        = $data['frequency_id'];

		if($data['report_type'] == 'per_pay_period'){

			$a_periods = explode("/", $data['cutoff_period']);
			$from      = trim($a_periods[0]);
			$to        = trim($a_periods[1]);

		 }
        else{


        	$month_number = $data['cutoff_period2'];
        	$year = $data['year_selecter'];
        	$month = '01-'.$month_number.'-'.$year;
        	$date = date_create($month);
        	$month_name = date_format($date,"F-Y"); //for reports header
        	
        	if($frequency_id == 2){

        		$period = G_Weekly_Cutoff_Period_Finder::findAllByMonthYear($year, $month_number);
        	}

        	elseif($frequency_id == 3){

        		$period = G_Monthly_Cutoff_Period_Finder::findAllByMonthYear($year, $month_number);
        	}

        	else{

        		$period = G_Cutoff_Period_Finder::findAllByMonthYear($year, $month_number);
        	}
 
        }

		$report_data = array();

		$remove_resigned   = false;
		$remove_terminated = false;
		$remove_endo       = false;
		$remove_inactive   = false;
		$qry_employee_type = '';
		$is_filter_by_cost_center = false;

		if( isset($data['philhealth_q']) ){
			$qry_employee_type = trim(strtolower($data['philhealth_q']));	
		}

		if( (isset($data['cost_center']) && $data['cost_center'] != '' && $data['cost_center'] != 'all')){
			$is_filter_by_cost_center   = true;
		}

		if( isset($data['philhealth_remove_resigned']) && $data['philhealth_remove_resigned'] == 1 ){			
			$remove_resigned   = true;
		}
		if( isset($data['philhealth_remove_terminated']) && $data['philhealth_remove_terminated'] == 1 ){
			$remove_terminated = true;	
		}
		if( isset($data['philhealth_remove_endo']) && $data['philhealth_remove_endo'] == 1 ){
			$remove_endo = true;	
		}
		if( isset($data['philhealth_remove_inactive']) && $data['philhealth_remove_inactive'] == 1 ){
			$remove_inactive = true;	
		}

		$this->var['from'] = $from;
		$this->var['to']   = $to; 

		if (strtotime($from) && strtotime($to) || $period) {

			$employee_access = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_access');
			if($employee_access == Sprint_Modules::PERMISSION_05) {
				if($qry_employee_type == "confidential") {
					$is_confidential_qry = " AND (e.is_confidential = 1) ";	
					$employee_type = "Confidential Employees";
				}elseif($qry_employee_type == "non-confidential"){
					$is_confidential_qry = " AND (e.is_confidential = 0) ";
					$employee_type = "Non-confidential Employees";
				}else{
					$is_confidential_qry = "";
					$employee_type       = "";
				}
			}elseif($employee_access == Sprint_Modules::PERMISSION_06) {
				$is_confidential_qry = " AND (e.is_confidential = 1) ";	
				$employee_type 		 = "Confidential Employees";
			}elseif($employee_access == Sprint_Modules::PERMISSION_07) {
				$is_confidential_qry = " AND (e.is_confidential = 0) ";
				$employee_type 		 = "Non-confidential Employees";
			}else{
				$is_confidential_qry = "";
				$employee_type       = "";
			}

			if( $remove_resigned ){				
				//$qry_add_on[] = " AND e.resignation_date < " . Model::safeSql($from);
				$qry_add_on[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
			}

			if( $remove_terminated ){
				//$qry_add_on[] = " AND e.terminated_date <" . Model::safeSql($from);
				$qry_add_on[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
			}

			if( $remove_endo ){
				//$qry_add_on[] = " AND e.terminated_date <" . Model::safeSql($from);
				$qry_add_on[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
			}

			if($is_filter_by_cost_center){
				$qry_add_on[] = "(e.cost_center = '".$data['cost_center']."')";
			}

			if( $remove_inactive ){
				$qry_add_on[] = "(e.inactive_date = '0000-00-00' OR e.inactive_date = '')";
			}


			//remove archive employee in report
			$qry_add_on[] = "(e.e_is_archive = 'No')";

			if( !empty($qry_add_on) ){
				$is_confidential_qry .= " AND " . implode(" AND ", $qry_add_on);
			}
			
			$r = new G_Report();							
			$r->setFromDate($from);
			$r->setToDate($to);

			if ($frequency_id == 2) {

				if($data['report_type'] == 'per_pay_period'){
				$report_data = $r->getWeeklyPhilhealthContributionsNoDup( $is_confidential_qry );
				    $this->var['header2']   = "Philhealth Contribution : {$from} to {$to}";
			        $this->var['data'] = $report_data;
			        //utilities::displayArray($report_data);exit();
				}
				else{


					foreach($period as $p){
						
							$r->setFromDate($p->getStartDate());
							$r->setToDate($p->getEndDate());
							$report_data2[] = $r->getWeeklyPhilhealthContributionsNoDup( $is_confidential_qry );
						}

						$test =array();

						$test = array_merge((array)$report_data2[0],(array)$report_data2[1], (array)$report_data2[2], (array)$report_data2[3], (array)$report_data2[4]);


						
						
						$group_array = array();

						foreach($test as $dkey => $d) {
							$group_array[$d['employee_code']][] = $d;
						}
						$converted_contributions = array();
						foreach($group_array as $dgkey => $dg) {
							foreach($dg as $d) {
								$converted_contributions[$dgkey]['employee_code'] = $d['employee_code'];
								$converted_contributions[$dgkey]['firstname'] = $d['firstname'];
								$converted_contributions[$dgkey]['lastname']  = $d['lastname'];
								$converted_contributions[$dgkey]['middlename']  = $d['middlename'];
								$converted_contributions[$dgkey]['birthdate']  = $d['birthdate'];
								$converted_contributions[$dgkey]['section_name'] = $d['section_name'];
								$converted_contributions[$dgkey]['department_name'] = $d['department_name'];
								$converted_contributions[$dgkey]['philhealth_contribution'] += $d['philhealth_contribution'];
								$converted_contributions[$dgkey]['philhealth_number'] = $d['philhealth_number'];
								$converted_contributions[$dgkey]['status'] = $d['status'];
								$converted_contributions[$dgkey]['philhealth_er_contribution'] += $d['philhealth_er_contribution'];
							


							}
							}

								
								$this->var['header2']   = "Philhealth Contribution : {$month_name}";
								$this->var['data'] = $converted_contributions;

							//	utilities::displayArray($converted_contributions);exit();

				}
				
			}

			elseif($frequency_id == 3){

				if($data['report_type'] == 'per_pay_period'){

					$report_data = $r->getMonthlyPhilhealthContributionsNoDup( $is_confidential_qry );
					foreach($report_data as $key => $value){

						foreach($value as $v => $vv){
							if($v == 'labels'){
								$object = unserialize($vv);
								foreach($object as $obj){
									if($obj->getVariable() == 'philhealth_er'){
										$report_data[$key]['philhealth_er'] = $obj->getValue();
									}
								}
							}
						}

					}

					$this->var['header2']   = "Philhealth Contribution : {$from} to {$to}";
					$this->var['data'] = $report_data;

				}else{


					foreach($period as $p){
						
							$r->setFromDate($p->getStartDate());
							$r->setToDate($p->getEndDate());
							$report_data2[] = $r->getMonthlyPhilhealthContributionsNoDup( $is_confidential_qry );
						}


					    $test =array();

						$test = array_merge((array)$report_data2[0],(array)$report_data2[1] );
						$group_array = array();

						foreach($test as $dkey => $d) {
							$group_array[$d['employee_code']][] = $d;
						}

						foreach($group_array as $key => $dh){
							foreach($dh as $v => $vv){
								foreach($vv as $c => $vvv){
									if($c == 'labels'){
										$object = unserialize($vvv);
										foreach($object as $obj){
											if($obj->getVariable() == 'philhealth_er'){
												$group_array[$key][$v]['philhealth_er'] = $obj->getValue();
											}
										}
									}
								}

							}
						}

						//utilities::displayArray($group_array);exit();

						$converted_contributions = array();
						foreach($group_array as $dgkey => $dg) {
							foreach($dg as $d) {
								$converted_contributions[$dgkey]['employee_code'] = $d['employee_code'];
								$converted_contributions[$dgkey]['firstname'] = $d['firstname'];
								$converted_contributions[$dgkey]['lastname']  = $d['lastname'];
								$converted_contributions[$dgkey]['middlename']  = $d['middlename'];
								$converted_contributions[$dgkey]['birthdate']  = $d['birthdate'];
								$converted_contributions[$dgkey]['section_name'] = $d['section_name'];
								$converted_contributions[$dgkey]['department_name'] = $d['department_name'];
								$converted_contributions[$dgkey]['philhealth_contribution'] += $d['philhealth_contribution'];
								$converted_contributions[$dgkey]['philhealth_number'] = $d['philhealth_number'];
								$converted_contributions[$dgkey]['status'] = $d['status'];

								if($d['philhealth_contribution'] != 0){
									$converted_contributions[$dgkey]['philhealth_er'] += $d['philhealth_er'];
								}	
							}
							}


								
								$this->var['header2']   = "Philhealth Contribution : {$month_name}";
								$this->var['data'] = $converted_contributions;
								//utilities::displayArray($converted_contributions);exit();
					
				}


			}
			else {

				if($data['report_type'] == 'per_pay_period'){

					$report_data = $r->getPhilhealthContributionsNoDup( $is_confidential_qry );
					//utilities::displayArray($report_data);exit();
					foreach($report_data as $key => $value){

						foreach($value as $v => $vv){
							if($v == 'labels'){
								$object = unserialize($vv);
								foreach($object as $obj){
									if($obj->getVariable() == 'philhealth_er'){
										$report_data[$key]['philhealth_er'] = $obj->getValue();
									}
								}
							}
						}

					}

					$this->var['header2']   = "Philhealth Contribution : {$from} to {$to}";
					$this->var['data'] = $report_data;
					//utilities::displayArray($report_data);exit();

				}
				else{

					foreach($period as $p){
						
							$r->setFromDate($p->getStartDate());
							$r->setToDate($p->getEndDate());
							$report_data2[] = $r->getPhilhealthContributionsNoDup( $is_confidential_qry );
						}


					    $test =array();

						$test = array_merge((array)$report_data2[0],(array)$report_data2[1] );
						$group_array = array();

						foreach($test as $dkey => $d) {
							$group_array[$d['employee_code']][] = $d;
						}

						foreach($group_array as $key => $dh){
							foreach($dh as $v => $vv){
								foreach($vv as $c => $vvv){
									if($c == 'labels'){
										$object = unserialize($vvv);
										foreach($object as $obj){
											if($obj->getVariable() == 'philhealth_er'){
												$group_array[$key][$v]['philhealth_er'] = $obj->getValue();
											}
										}
									}
								}

							}
						}

						//utilities::displayArray($group_array);exit();

						$converted_contributions = array();
						foreach($group_array as $dgkey => $dg) {
							foreach($dg as $d) {
								$converted_contributions[$dgkey]['employee_code'] = $d['employee_code'];
								$converted_contributions[$dgkey]['firstname'] = $d['firstname'];
								$converted_contributions[$dgkey]['lastname']  = $d['lastname'];
								$converted_contributions[$dgkey]['middlename']  = $d['middlename'];
								$converted_contributions[$dgkey]['birthdate']  = $d['birthdate'];
								$converted_contributions[$dgkey]['section_name'] = $d['section_name'];
								$converted_contributions[$dgkey]['department_name'] = $d['department_name'];
								$converted_contributions[$dgkey]['philhealth_contribution'] += $d['philhealth_contribution'];
								$converted_contributions[$dgkey]['philhealth_number'] = $d['philhealth_number'];
								$converted_contributions[$dgkey]['status'] = $d['status'];

								if($d['philhealth_contribution'] != 0){
									$converted_contributions[$dgkey]['philhealth_er'] += $d['philhealth_er'];
								}	
							}
							}


								
								$this->var['header2']   = "Philhealth Contribution : {$month_name}";
								$this->var['data'] = $converted_contributions;

				}

				
			}
		
		}		
		// echo "<pre>";
		// var_dump($report_data);
		// echo "</pre>";
		$d = new G_Settings_Weekly_Deduction_Breakdown();
		$deduction_breakdown = $d->getActiveContributionsBreakDown();

		if($data['report_type'] == 'per_pay_period'){

					if($frequency_id == 2)
					{
						$period = G_Weekly_Cutoff_Period_Finder::findByPeriod($from, $to);
					}
					elseif($frequency_id == 3)
					{
						$period = G_Monthly_Cutoff_Period_Finder::findByPeriod($from, $to);
					}
					else
					{
						$period = G_Cutoff_Period_Finder::findByPeriod($from, $to);
					}
					$cutoff_number     = $period->getCutoffNumber();


		}

		

		foreach( $deduction_breakdown as $key => $deduction ){
			if($key == 'Phil Health'){
				
				$breakdown     = explode(":", $deduction['breakdown']);

				$percentage    = $breakdown[$cutoff_number - 1];


			}
				
		}
		$this->var['frequency_id'] = $frequency_id;
		$this->var['percentage'] = $percentage;
		$this->var['filename'] = "philhealth_contributions.xls";
		$this->var['header1']   = G_Company_Structure_Helper::sqlCompanyName();
		$this->view->render('reports/contribution/philhealth_download.php', $this->var);
	}
	
	function philhealth() {
		$data = $_POST;		
		$encrypted_dept_ids = explode(",", $data['contri_search_keywords_department']);
		$encrypted_emp_ids  = explode(",", $data['contri_search_keywords_employee']);
		$date_from 			= date('d-M-Y', strtotime($data['date_from']));
		$date_to   			= date('d-M-Y', strtotime($data['date_to']));

		if( $data['contri_all'] ){
			$new_emp_ids  = array('all');
			$new_dept_ids = array('all');
		}else{
			foreach($encrypted_emp_ids as $id){
				$new_emp_ids[] = Utilities::decrypt($id);
			}

			foreach($encrypted_dept_ids as $id){
				$new_dept_ids[] = Utilities::decrypt($id);
			}
		}

		$r = new G_Report();				
		$r->setToDate($date_to);
		$r->setFromDate($date_from);

		if( $data['contri-search-by'] == 'department' ){
			$r->setDepartmentIds($new_dept_ids);
		}else{
			$r->setEmployeeIds($new_emp_ids);
		}

		$data = $r->getEmployeePhilhealthContributions();

		$this->var['filename'] = "philhealth_contribution.xls";
		$this->var['header']   = "Philhealth Contribution : {$date_from} to {$date_to}";		
		$this->var['data']     = $data;		
		$this->view->render('reports/contribution/philhealth_download.php', $this->var);
	}

	function annuliazed_tax() {
		$data = $_POST;
		/*Utilities::displayArray($data);
		exit;*/
	}

	function tax() {
		$data = $_POST;		
		$encrypted_dept_ids = explode(",", $data['contri_search_keywords_department']);
		$encrypted_emp_ids  = explode(",", $data['contri_search_keywords_employee']);
		$date_from 			= date('d-M-Y', strtotime($data['date_from']));
		$date_to   			= date('d-M-Y', strtotime($data['date_to']));

		if( $data['contri_all'] ){
			$new_emp_ids  = array('all');
			$new_dept_ids = array('all');
		}else{
			foreach($encrypted_emp_ids as $id){
				$new_emp_ids[] = Utilities::decrypt($id);
			}

			foreach($encrypted_dept_ids as $id){
				$new_dept_ids[] = Utilities::decrypt($id);
			}
		}

		$r = new G_Report();				
		$r->setToDate($date_to);
		$r->setFromDate($date_from);

		if( $data['contri-search-by'] == 'department' ){
			$r->setDepartmentIds($new_dept_ids);
		}else{
			$r->setEmployeeIds($new_emp_ids);
		}

		$data = $r->getEmployeeTaxContributions();

		$this->var['filename'] = "tax_contribution.xls";
		$this->var['header']   = "Tax Contribution : {$date_from} to {$date_to}";		
		$this->var['data']     = $data;
		$this->view->render('reports/contribution/tax_download.php', $this->var);
	}	

	function bir_2316() {
		$data = $_POST;				

		$encrypted_emp_ids  = explode(",", $data['contri_search_keywords_employee']);
		$year_selected		= $data['year_selected'];
		$all_nonconfi       = false;
		$all_confi          = false;
		$options 			= array();
		$report_data        = array();

		if( isset($data['contri_all_nonconfi']) ){
			$all_nonconfi = true;
			$options['add_query'] = ' AND e.is_confidential = 0';
		}	
		if( isset($data['contri_all_confi']) ){
			$all_confi = true;
			$options['add_query'] = ' AND e.is_confidential = 1';
		}
		if( isset($data['employment_status_id']) && $data['employment_status_id'] != ''){
			$all_confi = true;
			$options['add_query'] = ' AND e.employment_status_id = ' . $data['employment_status_id'];
		}		
				
		if( $data['contri_all'] ){
			$new_emp_ids  = array('all');
			$new_dept_ids = array('all');
		}else{
			foreach($encrypted_emp_ids as $id){
				$new_emp_ids[] = Utilities::decrypt($id);
			}

			foreach($encrypted_dept_ids as $id){
				$new_dept_ids[] = Utilities::decrypt($id);
			}
		}

		$report  = new G_Report();
		$alpha_data = $report->alphaListReport($year_selected, $options);

		if( !$all_nonconfi && !$all_confi ){			
			foreach($encrypted_emp_ids as $id){
				$new_emp_ids[] = Utilities::decrypt($id);
			}

			foreach( $new_emp_ids as $id ){
				$report_data[$id] = $alpha_data[$id];
			}
		}else{
			$report_data = $alpha_data;
		}

		$a_sort = array();
		foreach ($report_data as $key => $row)
		{
		    $a_sort[$key] = $row['lastname'];
		}

		array_multisort($a_sort, SORT_ASC, $report_data);	
		
		//Company Data
		$company = G_Company_Info_Helper::sqlDataById(1);		

		$default_minimum_rate = G_Sprint_Variables_Helper::sqlVariableValue('minimum_rate');		
		if(!empty($default_minimum_rate)) {
			$this->var['default_minimum_rate'] = $default_minimum_rate;
		}
		// echo "<pre>";
		// var_dump($report_data);
		// echo "</pre>";
		$this->var['filename'] = "bir_2316.xls";
		$this->var['header']   = "";		
		$this->var['form_signatory'] = strtoupper($data['form_signatory']);
		$this->var['data']     = $report_data;
		$this->var['company']  = $company;
		$this->var['year_selected'] = $year_selected;		
		$this->view->render('reports/contribution/2316/download.php', $this->var);
	}
	
	function pagibig() {
		$data = $_POST;		
		$encrypted_dept_ids = explode(",", $data['contri_search_keywords_department']);
		$encrypted_emp_ids  = explode(",", $data['contri_search_keywords_employee']);
		$date_from 			= date('d-M-Y', strtotime($data['date_from']));
		$date_to   			= date('d-M-Y', strtotime($data['date_to']));

		if( $data['contri_all'] ){
			$new_emp_ids  = array('all');
			$new_dept_ids = array('all');
		}else{
			foreach($encrypted_emp_ids as $id){
				$new_emp_ids[] = Utilities::decrypt($id);
			}

			foreach($encrypted_dept_ids as $id){
				$new_dept_ids[] = Utilities::decrypt($id);
			}
		}

		$r = new G_Report();				
		$r->setToDate($date_to);
		$r->setFromDate($date_from);

		if( $data['contri-search-by'] == 'department' ){
			$r->setDepartmentIds($new_dept_ids);
		}else{
			$r->setEmployeeIds($new_emp_ids);
		}

		$data = $r->getEmployeePagibigContributions();

		$this->var['filename'] = "pagibig_contribution.xls";
		$this->var['header']   = "Pagibig Contribution : {$date_from} to {$date_to}";		
		$this->var['data']     = $data;
		$this->view->render('reports/contribution/pagibig_download.php', $this->var);
	}	
	
	function payslip() {
		$from = $this->var['from'] = $_POST['date_from'];//'2012-07-06';
		$to = $this->var['to'] = $_POST['date_to'];//'2012-07-20';
		$this->var['payout_date'] = $payout = $_POST['payout_date'];//'2012-07-20';
		
		$this->var['payout'] = date('M j, Y', strtotime($payout));
		$this->var['period'] = date('M j', strtotime($from)) .' to '. date('M j, Y', strtotime($to));	
		$this->var['employees'] = $employees = G_Employee_Finder::findByPayslipPeriod($from, $to);
		$this->var['filename'] = "Payslip_". $from ."_to_". $to .".xls";
		$this->view->noTemplate();
		$this->view->render('reports/payslip/payslip_html.php', $this->var);
		//$this->view->render('reports/payslip/payslip.php', $this->var);
	}
	
	function payable() {
		//$this->var['date'] = $date = '2012-06-10';
		//$this->var['payments'] = G_Payment_Finder::findAllUntilDate($date);
		$this->var['payments'] = G_Payment_Finder::findAll();
		$this->view->render('reports/payable/payable_download.php', $this->var);
	}
	
	function bank() {
		$from = $this->var['from'] = $_POST['date_from']; //'2012-05-11';
		$to = $this->var['to'] = $_POST['date_to']; //'2012-05-25';
				
		//$c = G_Company_Branch_Finder::findById(1);
		$this->var['company_name'] = 'FilCore Job Assistance Co.';//$c->getName();
		$this->var['employees'] = $employees = G_Employee_Finder::findByPayslipPeriod($from, $to);	
		$this->var['total_employees'] = count($employees);
		$this->view->render('reports/bank/bank_download.php', $this->var);
	}

	function _load_perfect_attendance()
	{		
		if(PERFECT_ATTENDANCE_REPORT_ENABLED)
		{
			$this->validatePermission(G_Sprint_Modules::HR,'reports','reports_perfect_attendance');		
			//Utilities::ajaxRequest();		
			$j = G_Job_Finder::findAll();		

			//get all project sites
			$project_site = G_PROJECT_SITE::findAllProjectSite();
			$this->var['project_site'] = $project_site;


			$this->var['departments'] = G_Company_Structure_Finder::findAllDepartmentsIsNotArchiveByParentId($this->company_structure_id);
			$this->var['job']         = $j;
			$this->var['title'] = "Perfect Attendance";
			$this->view->noTemplate();		
			$this->view->render('reports/time_management/attendance_perfect_data/index.php',$this->var);
		}
	}	

	function _load_coe()
	{
		$this->validatePermission(G_Sprint_Modules::HR,'reports','reports_coe');

		Utilities::ajaxRequest();
 
        $this->var['title'] = "Certificate of Employment";
		$this->view->noTemplate();
		$this->view->render('reports/time_management/coe/index.php',$this->var);			
	}

	function download_coe_data()
	{
		$post = $_POST;				

		if($post['h_employee_id'] != '') {
			if( $post['report_type'] == 'EXCEL' ) {

				$employee_id = Utilities::decrypt($post['h_employee_id']);
				$e = G_Employee_Finder::findById($employee_id);
				$d = G_Employee_Helper::findByEmployeeId($employee_id);

				$c = G_Company_Info_Finder::findById(1);				

				if( strtolower($d['gender']) == 'female' ){
					$person_title = 'MS.';
					$geneder_exp  = 'She';
				}elseif( strtolower($d['gender']) == 'male' ){
					$person_title = 'MR.';
					$geneder_exp  = 'He';
				}else{
					$person_title = 'MR/MS.';
					$geneder_exp  = 'He/She';
				}

				$this->var['coe_signatory'] = $post['coe_signatory'];
				$this->var['coe_position']  = $post['coe_position']; 
				$this->var['person_title']  = $person_title;
				$this->var['geneder_exp'] = $geneder_exp;
				$this->var['c']		   = $c;
				$this->var['e']        = $e;
				$this->var['d']        = $d;
				$this->var['reason']   = $post['coe_reason'];
				$this->var['template'] = $post['template'];
				$this->var['filename'] = 'certificateofemployment.xls';
				$this->view->render('reports/time_management/coe/excel_download.php', $this->var);	


				//General Reports / Shr Audit Trail
				$emp_name = $d['firstname'].' '.$d['lastname'];
	        	$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_GENERATE, ' Certificate of Employment Reports of ', $emp_name, '', '', 1, $d['position'], $d['department']);

			}elseif( $post['report_type'] == 'PDF' ) {
				ini_set("memory_limit", "999M");
				$employee_id = Utilities::decrypt($post['h_employee_id']);
				$e = G_Employee_Finder::findById($employee_id);
				$d = G_Employee_Helper::findByEmployeeId($employee_id);

				$this->var['e']	= $e;
				$this->var['d'] = $d;

				if($e) {
					$fullName = strtoupper($e->getFirstName() . $e->getLastName());
				}else{ $fullName = '';	}				

				$other_info = array('coe_signatory' => $post['coe_signatory'], 'coe_position' => $post['coe_position']);
				$fName 	  = $fullName . 'certificateofemployment.pdf';
		        $pName    = Generate_Pdf::generateCoe($fName, $e, $d, $post['reason'], $other_info);     
		        $pdf_path = 'http://' . $_SERVER['SERVER_NAME'] . BASE_FOLDER . 'files/coe/' . $fName;
		        if($pName){
		            header('Location: ' . $pdf_path);
		        }

		        //General Reports / Shr Audit Trail
				$emp_name = $d['firstname'].' '.$d['lastname'];
	        	$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_GENERATE, ' Certificate of Employment Reports of ', $emp_name, '', '', 1, $d['position'], $d['department']);

			} else {
				echo 'Error';

				//General Reports / Shr Audit Trail
				$emp_name = $d['firstname'].' '.$d['lastname'];
	        	$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_GENERATE, ' Certificate of Employment Reports of ', $emp_name, '', '', 0, $d['position'], $d['department']);
			}
		} else {
			redirect("reports/time_management#display_coe");
		}
	}

	function download_perfect_attendance_data()
	{
		$data = $_POST;

		$remove_resigned   = false;
		$remove_terminated = false;
		$remove_endo       = false;
		$remove_inactive   = false;
		$qry_employee_type = '';

	    if( isset($data['absences_remove_resigned']) && $data['absences_remove_resigned'] == 1 ){
	      $remove_resigned   = true;
	    }
	    if( isset($data['absences_remove_terminated']) && $data['absences_remove_terminated'] == 1 ){
	      $remove_terminated = true;  
	    }
	    if( isset($data['absences_remove_endo']) && $data['absences_remove_endo'] == 1 ){
	      $remove_endo = true;  
	    }
	    //if( isset($data['absences_remove_inactive']) && $data['absences_remove_inactive'] == 1 ){
	    //  $remove_inactive = true;  
	    //}		

		if($_POST['perfect_date_from'] && $_POST['perfect_date_to']){

			if( $remove_resigned ){       
				$qry_add_on[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
			}

			if( $remove_terminated ){
				$qry_add_on[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
			}

			if( $remove_endo ){
				$qry_add_on[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
			}

			//if( $remove_inactive ){
			//	$qry_add_on[] = "(e.inactive_date = '0000-00-00' OR e.inactive_date = '')";
			//}

			if( !empty($qry_add_on) ){
				$is_additional_qry .= " AND " . implode(" AND ", $qry_add_on);
			}

	        $attendance = new G_Attendance();           
	        $perfect    = $attendance->countAttendancePerfectData($data, $is_additional_qry);		
			
	        if($data['project_site_id'] != 'all'){
				 $project = G_Project_Site_Finder::findById($data['project_site_id']);
				 if($project){
				 	$this->var['filter_by'] = "<strong>".$project->getprojectname()."</strong>";
				 }
			}
			else{
				$this->var['filter_by'] = "<strong>All</strong>";
			}
			
			$this->var['filename']         = 'perfect_attendance.xls';
			$this->var['date_from']		   = $_POST['perfect_date_from'];
			$this->var['date_to']		   = $_POST['perfect_date_to'];
			$this->var['perfect']          = $perfect;
			$this->view->render('reports/time_management/attendance_perfect_data/excel_download.php', $this->var);	
			
		}		
	}

	function _load_actual_hours()
	{
		$this->validatePermission(G_Sprint_Modules::HR,'reports','reports_actual_hours');

		Utilities::ajaxRequest();
 
 		$j = G_Job_Finder::findAll();		
		$this->var['departments'] = G_Company_Structure_Finder::findAllDepartmentsIsNotArchiveByParentId($this->company_structure_id);
		$this->var['job']         = $j;
        $this->var['title'] = "Actual Hours";
		$this->view->noTemplate();
		$this->view->render('reports/time_management/actual_hours/index.php',$this->var);			
	}

	function download_actual_hours_data()
	{
		$data = $_POST;

		$remove_resigned   = false;
		$remove_terminated = false;
		$remove_endo       = false;
		$remove_inactive   = false;
		$qry_employee_type = '';

	    if( isset($data['absences_remove_resigned']) && $data['absences_remove_resigned'] == 1 ){
	      $remove_resigned   = true;
	    }
	    if( isset($data['absences_remove_terminated']) && $data['absences_remove_terminated'] == 1 ){
	      $remove_terminated = true;  
	    }
	    if( isset($data['absences_remove_endo']) && $data['absences_remove_endo'] == 1 ){
	      $remove_endo = true;  
	    }
	    //if( isset($data['absences_remove_inactive']) && $data['absences_remove_inactive'] == 1 ){
	    //  $remove_inactive = true;  
	    //}		

		if($_POST['actual_hours_date_from'] && $_POST['actual_hours_date_to']){

			if( $remove_resigned ){       
				$qry_add_on[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
			}

			if( $remove_terminated ){
				$qry_add_on[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
			}

			if( $remove_endo ){
				$qry_add_on[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
			}

			//if( $remove_inactive ){
			//	$qry_add_on[] = "(e.inactive_date = '0000-00-00' OR e.inactive_date = '')";
			//}
			//$qry_add_on[] = "(e.employee_code = 6332)";

			if( !empty($qry_add_on) ){
				$is_additional_qry .= " AND " . implode(" AND ", $qry_add_on);
			}

	        $attendance = new G_Attendance();           
	        $actual_hours    = $attendance->getActualHoursData($data, $is_additional_qry);

	        //test 
	        $date_range =Tools::createDateRangeArray($_POST['actual_hours_date_from'] , $_POST['actual_hours_date_to']);


//for test
// 	 foreach($actual_hours as $key => $a){ 

//  		foreach($date_range as $value) {
 	
// if( $a['dates'][$value]['base_on_actual'] > 0 ){
                   
//                          if($a['dates'][$value]['is_restday'] == 1 || $a['dates'][$value]['is_holiday'] == 1){
                         
//                             $get_total_hrs_worked = $a['dates'][$value]['base_on_actual'] - $a['dates'][$value]['total_breaktime_deductible_hours'];
                            
//                             $break_total_hours_worked = explode(".",$get_total_hrs_worked);

//                             	 $hours = $break_total_hours_worked[0];
//               																$minutes = $break_total_hours_worked[1];
                          
// 	                          $n = $get_total_hrs_worked;
// 	                           $whole = floor($n);      // 1
// 	                $get_minutes_dec = $n - $whole;
// 	                               if(($hours) >= 8){
	                 
// 												                    if($get_minutes_dec < .25){	            
// 												                        $get_minutes_dec = 0;
// 												                    }elseif($minutes < .50){              
// 												                        $get_minutes = .25;
// 												                    }elseif($get_minutes_dec < .75){
// 												                        $get_minutes = .50;
// 												                    }else{
// 												                        $get_minutes = .75;               
// 												                    }
// 																                    $total_hrs_worked = $hours + $get_minutes;
// 																                }else{
																              		
// 																                    $total_hrs_worked = $get_total_hrs_worked;
// 																                }

                         
//                         }else{
                      
//                             $total_hrs_worked = $a['dates'][$value]['base_on_schedule'];
      
//                         }
                        
//                     }else{
                 
//                         $total_hrs_worked = 0;
//                     }

//  		}
//  }

	 
	
			$this->var['filename']         = 'actual_hours.xls';
			$this->var['date_from']		   = $_POST['actual_hours_date_from'];
			$this->var['date_to']		   = $_POST['actual_hours_date_to'];
			$this->var['actual_hours']          = $actual_hours;
			$this->var['date_range']		= Tools::createDateRangeArray($_POST['actual_hours_date_from'] , $_POST['actual_hours_date_to']);
			$this->view->render('reports/time_management/actual_hours/excel_download.php', $this->var);	
			
		}		
	}

	function _load_required_shift()
	{
		$this->validatePermission(G_Sprint_Modules::HR,'reports','reports_required_shift');

		Utilities::ajaxRequest();

		if ($_GET['month'] == '') {
            $this->var['show_month'] = date("n");
        } else {
            $this->var['show_month'] = $_GET['month'];
        }

        if ($_GET['year'] == '') {
            $this->var['show_year'] = date("Y");
        } else {
            $this->var['show_year'] = $_GET['year'];
        }

 		$this->var['months'] = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        $this->var['years'] = array(date('Y'), date('Y')-1);
        $this->var['title'] = "Required Shift";
		$this->view->noTemplate();
		$this->view->render('reports/time_management/required_shift/index.php',$this->var);			
	}

	function ajax_load_schedule_selector() {
		if(!empty($_POST['month']) && !empty($_POST['year'])) {
			$month = $_POST['month'];
			$year = $_POST['year'];
			$this->var['schedule'] = G_Schedule_Group_Finder::findAllByMonthAndYearWithDefault($month, $year);
			$this->view->noTemplate();
			$this->view->render('reports/time_management/required_shift/_schedule_selector.php',$this->var);
		}
	}

	function download_required_shift_data() {
		$data = array();
		if(!empty($_POST['schedule_public_id'])) {
			$id = $_POST['schedule_public_id'];
			$is_all = false;

			$year  = $_POST['year'];
			$month = $_POST['month'];				
			$start_date   = date("Y-m-d",strtotime($year . "-" . $month . "-" . "01"));
			$month_string = date("F Y",strtotime($start_date));
			$end_day      = date("t",strtotime($month_string));		
			$end_date     = date("Y-m-d",strtotime($year . "-" . $month . "-" . $end_day));

			if( $_POST['schedule_public_id'] != 'all' ){
				$g = G_Schedule_Group_Finder::findByPublicId($id);
				$schedule_name = $g->getName();
				$employees = G_Employee_Finder::findByScheduleGroup($g);
				$groups = G_Group_Finder::findByScheduleGroup($g);
		
				foreach($employees as $e) {
					$d = G_Employee_Helper::findByEmployeeId($e->getId());
					$section = G_Company_Structure_Finder::findById($d['section_id']);
					if($section) {
						$section_name = $section->getTitle();			
					}

					$data[$e->getId()] = array(
						'employee_code' => $e->getEmployeeCode(),
						'employee_name' => $e->getLastname() . ", " . $e->getFirstname(),
						'department_name' => $d['department'],
						'section_name' => $section_name,
						'position_name' => $d['position'],
						'employment_status' => $d['employment_status']
					);
				}

				foreach($groups as $g) {
					$employees = G_Employee_Subdivision_History_Finder::findAllCurrentEmployeesByCompanyStructureId($g->getId());
					foreach($employees as $e) {
						$d = G_Employee_Helper::findByEmployeeId($e->getEmployeeId());
						$section = G_Company_Structure_Finder::findById($d['section_id']);
						if($section) {
							$section_name = $section->getTitle();			
						}

						$data[$e->getEmployeeId()] = array(
							'employee_code' => $d['employee_code'],
							'employee_name' => $d['lastname'] . ", " . $d['firstname'],
							'department_name' => $d['department'],
							'section_name' => $section_name,
							'position_name' => $d['position'],
							'employment_status' => $d['employment_status']
						);
					}					
				}
			}else{
				$is_all = true;		
				$schedules  = G_Schedule_Group_Finder::findAllInBetweenDate($start_date, $end_date);			
				foreach($schedules as $g){
					$schedule_name = $g->getName();
					$employees     = G_Employee_Finder::findByScheduleGroup($g);
					$groups 	   = G_Group_Finder::findByScheduleGroup($g);
			
					foreach($employees as $e) {
						$d = G_Employee_Helper::findByEmployeeId($e->getId());
						$section = G_Company_Structure_Finder::findById($d['section_id']);
						if($section) {
							$section_name = $section->getTitle();			
						}

						$data[$schedule_name][$e->getId()] = array(
							'employee_code' => $e->getEmployeeCode(),
							'employee_name' => $e->getLastname() . ", " . $e->getFirstname(),
							'department_name' => $d['department'],
							'section_name' => $section_name,
							'position_name' => $d['position'],
							'employment_status' => $d['employment_status']
						);
					}

					foreach($groups as $g) {
						$employees = G_Employee_Subdivision_History_Finder::findAllCurrentEmployeesByCompanyStructureId($g->getId());
						foreach($employees as $e) {
							$d = G_Employee_Helper::findByEmployeeId($e->getEmployeeId());
							$section = G_Company_Structure_Finder::findById($d['section_id']);
							if($section) {
								$section_name = $section->getTitle();			
							}

							$data[$schedule_name][$e->getEmployeeId()] = array(
								'employee_code' => $d['employee_code'],
								'employee_name' => $d['lastname'] . " " . $d['firstname'],
								'department_name' => $d['department'],
								'section_name' => $section_name,
								'position_name' => $d['position'],
								'employment_status' => $d['employment_status']
							);
						}					
					}
				}
			}				
		}

		$this->var['start_date']	 = $start_date;
		$this->var['end_date']		 = $end_date;
		$this->var['is_all']		 = $is_all;	
		$this->var['filename']       = 'required_shift.xls';
		$this->var['schedule_name']	 = $schedule_name;
		$this->var['required_shift'] = $data;
		$this->view->render('reports/time_management/required_shift/excel_download.php', $this->var);	
	}

	function _load_government_remittances()
	{
		$this->validatePermission(G_Sprint_Modules::HR,'reports','reports_government_remittances');

		Utilities::ajaxRequest();
 
        $this->var['title'] = "Government Remittances";
		$this->view->noTemplate();
		$this->view->render('reports/time_management/government_remittances/index.php',$this->var);			
	}

	function download_government_remittances_data()
	{
		$post = $_POST;

		if($post['h_employee_id'] != '' || $post['signatory_employee_id'] != '') {

			$employee_id = Utilities::decrypt($post['h_employee_id']);
			$e = G_Employee_Finder::findById($employee_id);
			$d = G_Employee_Helper::findByEmployeeId($employee_id);

			$signatory_employee_id = Utilities::decrypt($post['signatory_employee_id']);
			$signatory_d = G_Employee_Helper::findByEmployeeId($signatory_employee_id);
			
			if( strtolower($d['gender']) == 'female' ){
				$person_title = 'MS.';
			}elseif( strtolower($d['gender']) == 'male' ){
				$person_title = 'MR.';
			}else{
				$person_title = 'MR/MS.';
			}

			$is_loan = false;
			if($post['remittance_type'] == 'pagibig_contribution' || $post['remittance_type'] == 'pagibig_loan') {
				$r_type = 'PAGIBIG';
				$r_no = $e->getPagibigNumber();
				if($post['remittance_type'] == 'pagibig_loan') {
					$is_loan = true;
				}
			}else if($post['remittance_type'] == 'sss_contribution' || $post['remittance_type'] == 'sss_loan') {
				$r_type = 'SSS';
				$r_no = $e->getSssNumber();
				if($post['remittance_type'] == 'sss_loan') {
					$is_loan = true;
				}
			}else if($post['remittance_type'] == 'philhealth') {
				$r_type = 'PHILHEALTH';
				$r_no = $e->getPhilhealthNumber();
			}	

			$query['employee_id'] = $employee_id;
			$query['date_from'] = $post['date_from'];
			$query['date_to'] = $post['date_to'];
			$query['remittance_type'] = $post['remittance_type'];

			$pperiod  = G_Settings_Pay_Period_Finder::findDefault();
   $payperiod_date = $pperiod->getCutOff();

   
  
   	$arr_date_from = explode("-", $post['date_from']);
  
   																																																		
   if($payperiod_date == '26-10,11-25' && $arr_date_from[0] <= 2017){
   	$c = new G_Cutoff_Period();
   	$next_cutoff_data     = $c->getNextCutOffByDate($to_date);
  		$previous_cutoff_data = $c->getPreviousCutOffByDate($query['date_from']);
  		$query['date_from'] = $previous_cutoff_data['start_date'];
  		// echo "111";
   }
   // var_dump($query);
			if($is_loan) { 
		
				$data = G_Employee_Helper::getGovernmentRemittancesLoans($query);
			}else{			

				$data = G_Employee_Helper::getGovernmentRemittances($query);
		
			}

			$this->var['is_loan']		= $is_loan;
			$this->var['data'] 			= $data;
			$this->var['purpose'] 		= $post['purpose'];
			$this->var['r_no'] 			= $r_no;
			$this->var['r_type'] 		= $r_type;
			$this->var['person_title']  = $person_title;
			$this->var['e']        		= $e;
			$this->var['d']        		= $d;
			$this->var['signatory_d']   = $signatory_d;
			$this->var['filename'] 		= 'government_remittances.xls';
			$this->view->render('reports/time_management/government_remittances/excel_download.php', $this->var);
		} else {
			redirect("reports/time_management#display_coe");
		}
	}

	function _load_last_pay()
	{
		$this->validatePermission(G_Sprint_Modules::HR,'reports','reports_last_pay');

		Utilities::ajaxRequest();
 
        $this->var['title'] = "Last Pay";
		$this->view->noTemplate();
		$this->view->render('reports/time_management/last_pay/index.php',$this->var);			
	}

	function download_last_pay_data()
	{
		$post = $_POST;

		if($post['h_employee_id'] != '') {
			$employee_id = Utilities::decrypt($post['h_employee_id']);
			$e = G_Employee_Finder::findById($employee_id);
			$d = G_Employee_Helper::findByEmployeeId($employee_id);

			$prepared_by_employee_id = Utilities::decrypt($post['prepared_by_employee_id']);
			$prepared_by_d = G_Employee_Helper::findByEmployeeId($prepared_by_employee_id);

			$checked_by_employee_id = Utilities::decrypt($post['checked_by_employee_id']);
			$checked_by_d = G_Employee_Helper::findByEmployeeId($checked_by_employee_id);

			$approved_by_employee_id = Utilities::decrypt($post['approved_by_employee_id']);
			$approved_by_d = G_Employee_Helper::findByEmployeeId($approved_by_employee_id);

			$query['employee_id'] = $employee_id;

			$data = $e->getEmployeeLastPayData();


			$date_to_e = date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $data['period_start_date']) ) ));
			$date_to_s = date("Y-m-d", mktime(0, 0, 0, 1, 1));

			$annual_income_data = G_Payslip_Helper::sqlIndividualEmployeePayslipDataByEmployeeIdAndDateRange($employee_id, $date_to_s, $date_to_e);

	        if(empty($data)) {
	            redirect("reports/time_management#display_last_pay");
	        }

	        if( !empty($d['resignation_date']) ) {
	        	$year_a = explode('-', $d['resignation_date']);	
	        	$year   = $year_a[0];
	        } else {
	        	$year   = date("Y");
	        }
			
	        $leave_availables = G_Employee_Leave_Available_Finder::findByEmployeeIdYear($employee_id, $year);	        

	        $employee_pending_loan = G_Employee_Loan_Finder::findByEmployeeIdStatusAndNotArchive($employee_id);

	        $this->var['annual_income_data']    = $annual_income_data;
	        $this->var['employee_pending_loan'] = $employee_pending_loan;
	        $this->var['resigned_year']         = $year;
	        $this->var['leave_availables']      = $leave_availables;
			$this->var['data'] 				    = $data;
			$this->var['e']        				= $e;
			$this->var['d']        				= $d;
			$this->var['prepared_by_d']   		= $prepared_by_d;
			$this->var['checked_by_d']   		= $checked_by_d;
			$this->var['approved_by_d']   		= $approved_by_d;
			$this->var['filename'] 				= 'last_pay.xls';
			$this->view->render('reports/time_management/last_pay/excel_download.php', $this->var);				
		} else {
			redirect("reports/time_management#display_coe");
		}
	}


	//load audit_trail_data
	function _load_audit_trail_data()
	{
		
		$test = $this->validatePermission(G_Sprint_Modules::AUDIT_TRAIL,'general_reports','audit_trail');
		Utilities::ajaxRequest();
		
		$this->var['title']       = "Audit Trail";
		$this->view->noTemplate();
		$this->view->render('reports/time_management/general_reports/index.php',$this->var);	
	}

	function load_hr_audit_log_list() {
		$username 	= $this->global_user_username;
		$role 		= $this->global_user_role_name;

		if($_GET['search_col'] == ''){
			$search_col = 'all';
		}
		else{
			$search_col = $_GET['search_col'];
		}

		if($_GET['search_field'] == ''){
			$search_field = '';
		}
		else{
			$search_field = $_GET['search_field'];
		}
		
		$this->var['data'] = G_Shr_Audit_Trail_Helper::getShrAuditTrailHRData($username, $role, $search_col, $search_field);
		$this->view->render('reports/time_management/general_reports/_hr_audit_log_list.php', $this->var);
	}

	function load_payroll_audit_log_list() {
		$username 	= $this->global_user_username;
		$role 		= $this->global_user_role_name;

		if($_GET['search_col'] == ''){
			$search_col = 'all';
		}
		else{
			$search_col = $_GET['search_col'];
		}

		if($_GET['search_field'] == ''){
			$search_field = '';
		}
		else{
			$search_field = $_GET['search_field'];
		}

		$this->var['data'] = G_Shr_Audit_Trail_Helper::getShrAuditTrailPayrollData($username, $role, $search_col, $search_field);
		$this->view->render('reports/time_management/general_reports/_payroll_audit_log_list.php', $this->var);
	}

	function load_timekeeping_audit_log_list() {
	
		$username 	= $this->global_user_username;
		$role 		= $this->global_user_role_name;
		
		if($_GET['search_col'] == ''){
			$search_col = 'all';
		}
		else{
			$search_col = $_GET['search_col'];
		}

		if($_GET['search_field'] == ''){
			$search_field = '';
		}
		else{
			$search_field = $_GET['search_field'];
		}

		$this->var['data'] = G_Shr_Audit_Trail_Helper::getShrAuditTrailTimeKeepingData($username, $role, $search_col, $search_field);
		$this->view->render('reports/time_management/general_reports/_timekeeping_audit_log_list.php', $this->var);
	}

	function filter_load_hr_audit_log_list() {
		
		$username 	= $this->global_user_username;
		$role 		= $this->global_user_role_name;

		if($_GET['search_col'] == ''){
			$search_col = 'all';
		}
		else{
			$search_col = $_GET['search_col'];
		}

		if($_GET['search_field'] == ''){
			$search_field = '';
		}
		else{
			$search_field = $_GET['search_field'];
		}


		$this->var['data'] = G_Shr_Audit_Trail_Helper::getShrAuditTrailHRData($username, $role, $search_col, $search_field);
		$this->view->render('reports/time_management/general_reports/_filter_hr_audit_log_list.php', $this->var);
	}


	function filter_load_payroll_audit_log_list() {
		
		$username 	= $this->global_user_username;
		$role 		= $this->global_user_role_name;

		if($_GET['search_col'] == ''){
			$search_col = 'all';
		}
		else{
			$search_col = $_GET['search_col'];
		}

		if($_GET['search_field'] == ''){
			$search_field = '';
		}
		else{
			$search_field = $_GET['search_field'];
		}

		//echo $search_col .' = '. $search_field;

		$this->var['data'] = G_Shr_Audit_Trail_Helper::getShrAuditTrailPayrollData($username, $role, $search_col, $search_field);
		$this->view->render('reports/time_management/general_reports/_filter_payroll_audit_log_list.php', $this->var);
	}


	function filter_load_timekeeping_audit_log_list() {
		
		$username 	= $this->global_user_username;
		$role 		= $this->global_user_role_name;

		if($_GET['search_col'] == ''){
			$search_col = 'all';
		}
		else{
			$search_col = $_GET['search_col'];
		}

		if($_GET['search_field'] == ''){
			$search_field = '';
		}
		else{
			$search_field = $_GET['search_field'];
		}

		//echo $search_col.' = '.$search_field;

		$this->var['data'] = G_Shr_Audit_Trail_Helper::getShrAuditTrailTimeKeepingData($username, $role, $search_col, $search_field);
		$this->view->render('reports/time_management/general_reports/_filter_timekeeping_audit_log_list.php', $this->var);
	}


	

	function download_audit_trail_hr(){
		
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		
		$username 	= $this->global_user_username;
		$role 		= $this->global_user_role_name;
		
		if($_GET['search_col'] == ''){
			$search_col = 'all';
		}
		else{
			$search_col = $_GET['search_col'];
		}

		if($_GET['search_field'] == ''){
			$search_field = '';
		}
		else{
			$search_field = $_GET['search_field'];
		}

		$this->var['filename'] = "audit_hr_list.xls";
		$this->var['data'] = G_Shr_Audit_Trail_Helper::getShrAuditTrailHRData($username, $role, $search_col, $search_field);
		
		$this->view->render('reports/time_management/general_reports/download_at_hr.html.php',$this->var);
		
	}

	function download_audit_trail_payroll(){
		
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		
		$username 	= $this->global_user_username;
		$role 		= $this->global_user_role_name;

		if($_GET['search_col'] == ''){
			$search_col = 'all';
		}
		else{
			$search_col = $_GET['search_col'];
		}

		if($_GET['search_field'] == ''){
			$search_field = '';
		}
		else{
			$search_field = $_GET['search_field'];
		}
		
		$this->var['filename'] = "audit_payroll_list.xls";
		$this->var['data'] = G_Shr_Audit_Trail_Helper::getShrAuditTrailPayrollData($username, $role, $search_col, $search_field);
		
		$this->view->render('reports/time_management/general_reports/download_at_payroll.html.php',$this->var);
		
	}

	function download_audit_trail_timekeeping(){
		
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		
		$username 	= $this->global_user_username;
		$role 		= $this->global_user_role_name;

		if($_GET['search_col'] == ''){
			$search_col = 'all';
		}
		else{
			$search_col = $_GET['search_col'];
		}

		if($_GET['search_field'] == ''){
			$search_field = '';
		}
		else{
			$search_field = $_GET['search_field'];
		}
		
		$this->var['filename'] = "audit_timekeeping_list.xls";
		$this->var['data'] = G_Shr_Audit_Trail_Helper::getShrAuditTrailTimeKeepingData($username, $role, $search_col, $search_field);
		
		$this->view->render('reports/time_management/general_reports/download_at_timekeeping.html.php',$this->var);
		
	}

}
?>