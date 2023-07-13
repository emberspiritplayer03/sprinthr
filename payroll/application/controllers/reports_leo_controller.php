<?php
class Reports_Leo_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		
		//ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		
		$this->login();
		Loader::appMainScript('reports.js');
		Loader::appStyle('style.css');
		$this->var['reports'] = 'selected';
		Jquery::loadMainInlineValidation2();
		$this->company_structure_id = $_SESSION['sprint_hr']['company_structure_id'];
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
	
	function download_applicant_list() {
		
		$x=0;
		while($x<11) {
			$x++;
			if($_POST['checkbox'.$x]) {
				$field .=  $_POST['checkbox'.$x].",";
				$title_field[] = $_POST['checkbox'.$x];
			}
			
		}
				
		$title = array('lastname','firstname','middlename','extension_name','applied_date_time','job_applied');
		if($title_field) {
			$excel_title = array_merge($title,$title_field);	
		}else {
			$excel_title = $title;
		}
		
		//print_r($excel_title);
		
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
		a.lastname, 
		a.firstname, 
		a.middlename,  
		a.extension_name,
		a.applied_date_time,
		".$field."
		j.title as job_applied
		FROM g_applicant a, g_job j
				".$search_by_date."
				".$query." 
				".$applied."
				ORDER BY a.applied_date_time 
		";
		$rec = Model::runSql($sql,true);
		
		$this->var['excel_title'] = $excel_title;
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
				e.date_time_event,
				e.event_type,
				e.notes,
				e.date_time_created,
				CONCAT(emp.lastname, ', ', emp.firstname) as hiring_manager,
				CONCAT(a.lastname,', ' , a.firstname) as applicant_name,
				a.application_status_id,
				j.title as position_applied
				
				FROM g_job_application_event e
				LEFT JOIN g_applicant a ON a.id=e.applicant_id
				LEFT JOIN g_employee emp ON emp.id=e.hiring_manager_id
				LEFT JOIN g_job j ON j.id=a.job_id
				WHERE date_time_event between ".Model::safeSql($from)." AND ".Model::safeSql($to)."
				AND e.event_type>0
				".$position_query."
				
				GROUP BY e.id
				ORDER BY e.date_time_event 
				
		";
		//echo $sql;
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
		/*$sql = "SELECT 
		a.lastname, 
		a.middlename, 
		a.lastname, 
		a.firstname, 
		a.extension_name,
		a.applied_date_time,
		j.title as job_applied
		FROM g_applicant a
		LEFT JOIN g_job j ON j.id=a.job_id
	
		WHERE a.application_status_id=".APPLICATION_SUBMITTED." ".$search."

		ORDER BY a.applied_date_time 
		";*/
		
		$sql = "
		SELECT 
		a.lastname, 
		a.middlename, 
		a.lastname, 
		a.firstname, 
		a.extension_name, 
		a.applied_date_time, 
		j.title as job_applied 
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
				CONCAT(e.lastname, ', ', e.firstname, e.middlename) as hiring_manager,
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
	
	function download_birthday_list()
	{
		
		$department_id = $_POST['department_id'];
		$month_birthday = $_POST['month_birthday'];
	
		$rec = G_Employee_Helper::findByDepartmentIdMonth($department_id,$month_birthday);	
		$this->var['data'] = $rec;

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
			e.employee_code,
			concat(e.lastname, ', ', e.firstname, ' ', e.middlename) as employee_name,
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
		//echo $sql;
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
		d.name as department,
		e.employee_code,
		e.lastname, 
		e.firstname, 
		e.middlename,  
		e.extension_name,
		e.hired_date,
		".$field."
		j.name as position
		FROM g_employee_subdivision_history d
		LEFT JOIN g_employee e ON e.id=d.employee_id 
		LEFT JOIN g_employee_job_history j ON j.employee_id=e.id 
		".$where."
		ORDER BY e.hired_date 
		";
		
		$data = Model::runSql($sql,true);
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
		$query = ($_POST['department_id']!='')? 'AND d.id='.$_POST['department_id'] : '' ; 
		$date_start = $_POST['date_applied_from'];
		$date_to = $_POST['date_applied_to'];
	$sql = "SELECT 
		d.name as department,
		e.employee_code,
		CONCAT(e.lastname,', ',e.firstname,' ',e.middlename,' ', e.extension_name) AS `employee_name`,
		e.hired_date,
		j.name as position,
		r.date_applied,
		r.date_start,
		r.date_end,
		r.leave_comments,
		r.is_approved,
		l.name as type,
		r.is_paid
		
		FROM g_employee_leave_request r
		LEFT JOIN g_employee e ON e.id=r.employee_id
		LEFT JOIN g_employee_job_history j ON j.employee_id=e.id
		LEFT JOIN g_employee_subdivision_history d ON d.employee_id=e.id
		LEFT join g_leave l ON l.id=r.leave_id	
		WHERE r.date_applied BETWEEN ".Model::safeSql($date_from)." AND ".Model::safeSql($date_to)."

		".$query."
		";
		echo $sql;
		$rec = Model::runSql($sql,true);
		//echo "<pre>";
		//print_r($rec);
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
	
	function download_daily_work_schedule()
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
		//echo $sql;
		$this->var['is_all'] = $all;
		$this->var['data'] = $schedule;
		$this->view->render('reports/time_management/daily_work_schedule/'.$filename, $this->var);
	}
	
	//load attendance_absence_data
	function _load_attendance_absence_data()
	{

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();

		$this->var['job'] = $j;
		$this->var['title'] = "Attendance Absence Data";
		$this->view->noTemplate();
		$this->view->render('reports/time_management/attendance_absence_data/index.php',$this->var);	
	}
	
	function download_attendance_absence_data()
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
		Jquery::loadMainTextBoxList();
		
		$this->var['page_title'] = 'Reports';
		$this->var['sub_menu_time_management'] = true;
		$this->view->setTemplate('template_reports.php');
		$this->view->render('reports/index.php',$this->var);		
	}
	
	// payroll management menu
	function payroll_management()
	{
		$this->var['page_title'] = 'Reports';
		$this->var['sub_menu_payroll_management'] = true;
		$this->view->setTemplate('template_reports.php');
		$this->view->render('reports/index.php',$this->var);		
	}
	
	function _load_sss_r1a()
	{
		Utilities::ajaxRequest();
		$this->var['action'] 	= 'benchmark_leo/download_sss_report';
		$this->var['title'] 	= "SSS R-1A";
		$this->view->noTemplate();
		$this->view->render('reports_leo/contribution/forms/sss_form.php',$this->var);
	}
	
	function _load_total_max_records_page() {
		if(!empty($_POST)) {
			$total 	= G_Employee_Helper::countTotalPayslipDateRange($_POST['date_from'],$_POST['date_to']);
			$pages	= floor($total/40);
			
			$this->var['max_page'] = $pages;
			$this->view->render('reports_leo/contribution/forms/_max_total_records_page.php',$this->var);
		}
	}
	
	function _load_philhealth()
	{
		Utilities::ajaxRequest();
		$this->var['action'] = 'reports/philhealth';
		$this->var['title'] = "Philhealth";
		$this->view->noTemplate();
		$this->view->render('reports/contribution/forms/philhealth_form.php',$this->var);
	}	
	
	function _load_pagibig()
	{
		Utilities::ajaxRequest();
		$this->var['action'] = 'reports/pagibig';
		$this->var['title'] = "Pagibig";
		$this->view->noTemplate();
		$this->view->render('reports/contribution/forms/pagibig_form.php',$this->var);
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
		Utilities::ajaxRequest();
		$this->var['action'] = 'reports/payslip';
		$this->var['title'] = "Payslip";
		$this->view->noTemplate();
		$this->view->render('reports/contribution/forms/payslip_form.php',$this->var);
	}
	
	function _load_payroll_register()
	{
		Utilities::ajaxRequest();
		$this->var['action'] = 'reports/payroll_register';
		$this->var['title'] = "Payroll Register";
		$this->view->noTemplate();
		$this->view->render('reports/contribution/forms/payroll_register_form.php',$this->var);
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
	
	function sss() {
		$from = $this->var['from'] = $_POST['date_from'];//'2012-03-06';
		$to = $this->var['to'] = $_POST['date_to'];//'2012-05-07';
		$submission_date = $_POST['submission_date'];//'2012-05-31';
		
		$this->var['submission_date'] = date('d-M-y', strtotime($submission_date));
		$this->var['employees'] = $employees = G_Employee_Finder::findAllByHiredDateRange($from, $to);
		$this->view->render('reports/contribution/sss_download.php', $this->var);
	}
	
	function philhealth() {
		$from = $this->var['from'] = $_POST['date_from'];//'2012-03-06';
		$to = $this->var['to'] = $_POST['date_to'];//'2012-05-07';
		
		$this->var['employees'] = $employees = G_Employee_Finder::findAllByHiredDateRange($from, $to);
		$this->view->render('reports/contribution/philhealth_download.php', $this->var);
	}	
	
	function pagibig() {
		$from = $this->var['from'] = $_POST['date_from'];//'2012-05-11';
		$to = $this->var['to'] = $_POST['date_to'];//'2012-05-25';
		$submission_date = $_POST['submission_date'];//'2012-05-31';
		
		$this->var['month_covered'] = date('F', strtotime($from));
		$this->var['year_covered'] = date('Y', strtotime($from));		
		$this->var['submission_date'] = date('d-M-y', strtotime($submission_date));
		$this->var['employees'] = $employees = G_Employee_Finder::findByPayslipPeriod($from, $to);
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
}
?>