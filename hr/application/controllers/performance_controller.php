<?php
class Performance_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		$this->login();
		Loader::appMainUtilities();
		Loader::appMainScript('performance.js');
		Loader::appStyle('style.css');
		$this->var['performance'] = 'selected';
		$this->company_structure_id = $_SESSION['sprint_hr']['company_structure_id'];
		
		Utilities::checkModulePackageAccess('hr','performance');
		
		//employee module must be enable
		Utilities::checkModulePackageAccess('hr','employee');	
	}

	function index()
	{

		$this->performance();
		
	}
	
	function performance() 
	{
		$this->var['token'] = Utilities::createFormToken();
		Yui::loadMainDatatable();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTextBoxList();
		
		//$performance = G_Performance_Finder::findByCompanyStructureId($this->company_structure_id);
		$performance = G_Performance_Finder::findActivePerformance();
				
		$this->var['company_structure_id'] = $this->company_structure_id;
		$this->var['performance'] 		   = $performance;
		$this->var['employee'] 			   = 'selected';
		
		//$this->var['departments'] = G_Company_Structure_Finder::findParentChildByCompanyStructureId($company_structure_id);		
		$this->var['positions'] 		   = $p = G_Job_Finder::findByCompanyStructureId2($this->company_structure_id);		
		$this->var['page_title'] 		   = 'Performance';
		$this->view->setTemplate('template_performance.php');
		$this->view->render('performance/index.php',$this->var);
	}
	
	function _insert_employee_performance()
	{

		$row = $_POST;
		Utilities::verifyFormToken($_POST['token']);
		if($_POST['employee_id']=='' || $_POST['reviewer_id']=='')
		{
			echo 0;	
		}else {
			$performance = G_Performance_Finder::findById($row['performance_id']);
			$row['title'] = $performance->title;
			$e = G_Employee_Helper::findByEmployeeId($row['employee_id']);
			$row['position'] = $e['position'];
			$p = new G_Employee_Performance;
			$p->setCompanyStructureId($row['company_structure_id']);
			$p->setPerformanceId($row['performance_id']);
			$p->setPerformanceTitle($row['title']);
			$p->setEmployeeId($row['employee_id']);
			$p->setPosition($row['position']);
			$p->setReviewerId($row['reviewer_id']);
			$p->setPeriodFrom($row['period_from']);
			$p->setPeriodTo($row['period_to']);
			$p->setDueDate($row['due_date']);
			$p->setStatus('pending');
			$p->save();
			echo 1;	
		}
	}
	
	function performance_details()
	{	
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		$this->var['token'] = Utilities::createFormToken();
		
   	 	$employee_performance_id = Utilities::decrypt($_GET['performance']);
		$e = G_Employee_Performance_Finder::findById($employee_performance_id);
		$p = G_Performance_Indicator_Finder::findByPerformanceId($e->performance_id);
		$employee = G_Employee_Finder::findById($e->employee_id);
		$r = G_Employee_Finder::findById($e->reviewer_id);
		$this->var['employee_performance_id'] = $employee_performance_id;
		$this->var['performance_id'] =$e->performance_id;
		$this->var['performance_title'] = $e->performance_title;
		$this->var['employee'] = $e;
		$this->var['employee_name'] = $employee->lastname . ', ' .$employee->firstname;
		$this->var['reviewer_name'] =  $r->lastname . ', ' .$r->firstname;
		$this->var['kpi'] = $p;

		$this->var['page_title'] = 'Performance Details';
		$this->view->setTemplate('template.php');
		$this->view->render('performance/form/performance_evaluation_form.php',$this->var);
	}
	
	function performance_summary()
	{
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		$this->var['token'] = Utilities::createFormToken();
		
   	 	$employee_performance_id = Utilities::decrypt($_GET['performance']);
		$e = G_Employee_Performance_Finder::findById($employee_performance_id);
		$p = G_Performance_Indicator_Finder::findByPerformanceId($e->performance_id);
		$employee = G_Employee_Finder::findById($e->employee_id);
		$r = G_Employee_Finder::findById($e->reviewer_id);	
		
		$this->var['employee_performance_id'] = $employee_performance_id;
		$this->var['performance_id']          = $e->performance_id;
		$this->var['summary']		          = explode(",",$e->getSummary());
		$this->var['performance_title'] 	  = $e->performance_title;
		$this->var['employee'] 			      = $e;
		$this->var['employee_name'] 		  = $employee->lastname . ', ' .$employee->firstname;
		$this->var['reviewer_name'] 		  =  $r->lastname . ', ' .$r->firstname;
		
		
		$xmlStr = simplexml_load_string($e->kpi);
			
		$xml2 = new Xml;
		$arrXml  = $xml2->objectsIntoArray($xmlStr);						
		$summary = G_Employee_Performance_Helper::employeePerformanceResultsSummary($arrXml);		
		$this->var['kpi'] = $arrXml;
		$this->var['performance_average']     = G_Employee_Performance_Helper::computePerformanceAverage($arrXml,$e->getSummary());

		$this->var['page_title'] = 'Performance Summary';
		$this->view->setTemplate('template.php');
		$this->view->render('performance/summary.php',$this->var);
	}
	
	function _save_evaluation()
	{
		
		
		Utilities::verifyFormToken($_POST['token']);
		
		$kpi = G_Performance_Indicator_Finder::findByPerformanceId($_POST['performance_id']);
		
		header("Content-Type:text/xml");
		$xml = new Xml;
		$ctr=1;
		foreach($kpi as $key=>$val) {
				
				$var = 'kpi_'.$ctr;
				//echo $GLOBALS['hr']['performance_rate'][$_POST['rate_'.$val->id]];
				$ob->$var->id		=	$val->id;
				$ob->$var->title 	=	$_POST['title_'.$val->id];
				$ob->$var->desc		=	$_POST['desc_'.$val->id];
				$ob->$var->rate		=	$_POST['rate_'.$val->id];
				$ob->$var->comment	=	stripslashes($_POST['comment_'.$val->id]); 
				$ob->$var->result	=	$GLOBALS['hr']['performance_rate'][$_POST['rate_'.$val->id]];
				$summary[$GLOBALS['hr']['performance_rate'][$_POST['rate_'.$val->id]]]++;				
				$ctr++;
			}
			
			$summary = G_Employee_Performance_Helper::employeePerformanceResultsSummary($summary);
			
		$xml->setNode('kpi');
			//----test object----
		$xmlObj =  $xml->toXml($ob);
	
		$e = G_Employee_Performance_Finder::findById($_POST['employee_performance_id']);
		if($e->status=='pending') {
			$e->setKpi($xmlObj);
			$e->setSummary($summary);
			$e->setStatus('being evaluated');
			$e->save();	
		}
		
		echo 1;
	}
	
	function _json_encode_employee_performance_list()
	{
		$search = ($_GET['fieldname']  !='') ? "WHERE ". $_GET['fieldname']." like '". $_GET['search'] ."%'": '' ;
		
		$limit = 'LIMIT '.$_GET['startIndex'] . ', ' . $_GET['results'];
		//$order_by = ($_GET['sort'] != '') ? 'ORDER BY '.$_GET['sort'] . ' ' . $_GET['dir']  :  'ORDER BY e.schedule_date asc' ;
		
		
		$company = G_Company_Structure_Finder::findById($this->company_structure_id);
		
		$exam = G_Employee_Performance_Helper::findByCompanyStructureId($company->id,$order_by,$limit,$date);
		foreach($exam as $key=>$value) {
			unset($exam[$key]['questions']);
			$exam[$key]['hash'] = Utilities::encrypt($value['id']);
		}
		$data = $exam;
				
		$data2 =  G_Employee_Performance_Helper::findByCompanyStructureId($company->id,$order_by,'',$date);
		$total = count($data);
		$total_records =count($data2);
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";	
	}	
	
	function _json_encode_advance_search_performance_list()
	{
		//Utilities::ajaxRequest();
		
		$colon_count = substr_count($_GET['search'], ':'); 
		if($colon_count>0) {/* if has a colon*/
			$search = G_Employee_Performance_Helper::getDynamicQueries($_GET['search']);
		}else {
			//no colon
			if($_GET['search']) {				
				$search .= " AND(e.firstname like '%". $_GET['search'] ."%' OR e.lastname like '%". $_GET['search'] ."%' ";
				$search .= " OR e.middlename like '%". $_GET['search'] ."%' ";
				$search .= " OR j.name like '%".$_GET['search']."%'";
				$search .= " OR e.employee_code like '%". $_GET['search'] ."%' OR j.employment_status like  '%". $_GET['search'] ."%'  )";	
			}
		}
		
		$limit = 'LIMIT '.$_GET['startIndex'] . ', ' . $_GET['results'];
		//$order_by = ($_GET['sort'] != '') ? 'ORDER BY '.$_GET['sort'] . ' ' . $_GET['dir']  :  'ORDER BY e.schedule_date asc' ;
		
		
		$company = G_Company_Structure_Finder::findById($this->company_structure_id);
		
		$exam = G_Employee_Performance_Helper::advanceSearchfindByCompanyStructureId($company->id,$order_by,$limit,$search);
		foreach($exam as $key=>$value) {
			unset($exam[$key]['questions']);
			$exam[$key]['hash'] = Utilities::encrypt($value['id']);
		}
		$data = $exam;
				
		$data2 =  G_Employee_Performance_Helper::findByCompanyStructureId($company->id,$order_by,'',$date);
		$total = count($data);
		$total_records =count($data2);
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";	
	}	
}
?>