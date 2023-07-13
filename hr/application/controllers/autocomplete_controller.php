<?php
class Autocomplete_Controller extends Controller
{
	function __construct()
	{	
		parent::__construct();
		
	}

	function ajax_get_employees() 
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
	
	function ajax_get_active_employees() 
	{
		$q = Model::safeSql(strtolower($_GET["search"]), false);
		if ($q != '') {						
			$employees = G_Employee_Finder::searchActiveEmployeeByFirstnameAndLastname($q);
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

	function ajax_valid_ot_rate_employees() 
	{
		$q = Model::safeSql(strtolower($_GET["search"]), false);
		if ($q != '') {						
			$employees = G_Employee_Overtime_Rate_Helper::searchValidEmployees($q);
			foreach ($employees as $e) {
				$response[] = array(Utilities::encrypt($e['id']), $e['employee_name'], null);
			}
		}
		
		if(count($response) == 0) {
			$response = '';
		}
		header('Content-type: application/json');
		echo json_encode($response);		
	}
	
	function ajax_get_active_and_terminated_employees_within_date_range()
	{
		$q = Model::safeSql(strtolower($_GET["search"]), false);
		if ($q != '') {						
			$employees = G_Employee_Finder::searchActiveEmployeeByFirstnameAndLastnameWithTerminated($q);
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

	function ajax_get_active_and_resigned_employees()
	{
		$q = Model::safeSql(strtolower($_GET["search"]), false);
		if ($q != '') {						
			$employees = G_Employee_Finder::searchActiveEmployeeByFirstnameAndLastnameAndResigned($q);
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

	function ajax_get_employees_not_enrolled_to_benefit_id()
	{
		$q    = Model::safeSql(strtolower($_GET["search"]), false);
		$beid = Utilities::decrypt($_GET["eid"]);
		
		if ($q != '') {
			$b = new G_Employee_Benefits_Main();
			$b->setId($beid);
			$employees = $b->searchEmployeesByKeywordNotEnrolledToBenefit($q);			
			foreach ($employees as $e) {
				$response[] = array(Utilities::encrypt($e['employee_id']), $e['employee_name'], null);
			}
		}
		
		if(count($response) == 0) {
			$response = '';
		}
		header('Content-type: application/json');
		echo json_encode($response);
	}

	function ajax_get_dept_section_not_enrolled_to_benefit_id()
	{
		$q    = Model::safeSql(strtolower($_GET["search"]), false);
		$beid = Utilities::decrypt($_GET["eid"]);
		
		if ($q != '') {
			$b = new G_Employee_Benefits_Main();
			$b->setId($beid);

			$dept = $b->searchDepartmentSectionByKeywordNotEnrolledToBenefit($q);			
			foreach ($dept as $d) {
				$response[] = array(Utilities::encrypt($d['dept_section_id']), $d['title'], null);
			}
		}
		
		if(count($response) == 0) {
			$response = '';
		}
		header('Content-type: application/json');
		echo json_encode($response);
	}

	function ajax_get_employment_status_not_enrolled_to_benefit_id()
	{
		$q    = Model::safeSql(strtolower($_GET["search"]), false);
		$beid = Utilities::decrypt($_GET["eid"]);
		
		if ($q != '') {
			$b = new G_Employee_Benefits_Main();
			$b->setId($beid);

			$estatus = $b->searchEmploymentStatusByKeywordNotEnrolledToBenefit($q);			

			foreach ($estatus as $es) {
				$response[] = array(Utilities::encrypt($es['employment_status_id']), $es['status'], null);
			}
		}
		
		if(count($response) == 0) {
			$response = '';
		}
		header('Content-type: application/json');
		echo json_encode($response);
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

	function ajax_get_employees_autocomplete_not_in_allowed_ip() 
	{
		$q = Model::safeSql(strtolower($_GET["search"]), false);
		
		if ($q != '') {
			$employees = G_Employee_Finder::searchByFirstnameAndLastnameAndNotInAllowedIp($q);
			
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

	function ajax_get_employees_department_autocomplete() 
	{
		$q = Model::safeSql(strtolower($_GET["search"]), false);
		
		if ($q != '') {
			$employees   = G_Employee_Finder::searchByFirstnameAndLastname($q);
			$departments = G_Company_Structure_Finder::searchAllDepartmentByTitleAndIsNotArchive($q);
			
			foreach ($employees as $e) {
				$id_pattern = Utilities::encrypt($e->getId()) . ":" . G_Request_Approver_Requestor::PREFIX_EMPLOYEE;
				$response[] = array($id_pattern, $e->getFullname(), null);
			}

			foreach ($departments as $d) {
				$id_pattern = Utilities::encrypt($d->getId()) . ":" . G_Request_Approver_Requestor::PREFIX_DEPARTMENT;
				$response[] = array($id_pattern, $d->getTitle(), null);
			}
		}
		
		if(count($response) == 0) {
			$response = '';
		}
		header('Content-type: application/json');
		echo json_encode($response);		
	}

	function ajax_get_employees_department_breaktime_schedule_autocomplete() 
	{
		$q = Model::safeSql(strtolower($_GET["search"]), false);
		
		if ($q != '') {
			$employees   = G_Employee_Finder::searchByFirstnameAndLastname($q);
			$departments = G_Company_Structure_Finder::searchAllDepartmentByTitleAndIsNotArchive($q);
			
			foreach ($employees as $e) {
				$id_pattern = Utilities::encrypt($e->getId()) . ":" . G_Break_Time_Schedule_Details::PREFIX_EMPLOYEE;
				$response[] = array($id_pattern, $e->getFullname(), null);
			}

			foreach ($departments as $d) {
				$id_pattern = Utilities::encrypt($d->getId()) . ":" . G_Break_Time_Schedule_Details::PREFIX_DEPARTMENT;
				$response[] = array($id_pattern, $d->getTitle(), null);
			}
		}
		
		if(count($response) == 0) {
			$response = '';
		}
		header('Content-type: application/json');
		echo json_encode($response);		
	}

	function ajax_get_unique_requestors_autocomplete() 
	{
		$q = Model::safeSql(strtolower($_GET["search"]), false);
		
		if ($q != '') {
			$employees   = G_Employee_Finder::searchUniqueRequestorsByFirstnameAndLastname($q);
			$departments = G_Company_Structure_Finder::searchAllDepartmentByTitleAndIsNotArchive($q);
			
			foreach ($employees as $e) {
				$id_pattern = Utilities::encrypt($e->getId()) . ":" . G_Request_Approver_Requestor::PREFIX_EMPLOYEE;
				$response[] = array($id_pattern, $e->getFullname(), null);
			}

			foreach ($departments as $d) {
				$id_pattern = Utilities::encrypt($d->getId()) . ":" . G_Request_Approver_Requestor::PREFIX_DEPARTMENT;
				$response[] = array($id_pattern, $d->getTitle(), null);
			}
		}
		
		if(count($response) == 0) {
			$response = '';
		}
		header('Content-type: application/json');
		echo json_encode($response);		
	}

	function ajax_get_department_autocomplete() 
	{
		$q = Model::safeSql(strtolower($_GET["search"]), false);
		
		if ($q != '') {
			$departments = G_Company_Structure_Finder::searchAllDepartmentByTitleAndIsNotArchive($q);
			
			foreach ($departments as $d) {
				$response[] = array(Utilities::encrypt($d->getId()), $d->getTitle(), null);
			}
		}
		
		if(count($response) == 0) {
			$response = '';
		}
		header('Content-type: application/json');
		echo json_encode($response);		
	}

	function ajax_get_all_department_type_autocomplete() 
	{
		$q = Model::safeSql(strtolower($_GET["search"]), false);
		
		if ($q != '') {
			$departments = G_Company_Structure_Finder::searchAllDepartmentTypeByTitleAndIsNotArchive($q);
			
			foreach ($departments as $d) {
				$response[] = array(Utilities::encrypt($d->getId()), $d->getTitle(), null);
			}
		}
		
		if(count($response) == 0) {
			$response = '';
		}
		header('Content-type: application/json');
		echo json_encode($response);		
	}

	function ajax_get_employment_status_autocomplete() 
	{
		$q = Model::safeSql(strtolower($_GET["search"]), false);
		
		if ($q != '') {
			$employment_status = G_Settings_Employment_Status_Finder::searchAllEmploymentStatus($q);
			
			foreach ($employment_status as $es) {
				$response[] = array(Utilities::encrypt($es->getId()), $es->getStatus(), null);
			}
		}
		
		if(count($response) == 0) {
			$response = '';
		}
		header('Content-type: application/json');
		echo json_encode($response);		
	}

	function ajax_get_employee_department_for_ot_allowance() 
	{
		$q = Model::safeSql(strtolower($_GET["search"]), false);
		
		if ($q != '') {
			$employees   = G_Employee_Finder::searchByFirstnameAndLastname($q);
			$departments = G_Company_Structure_Finder::searchAllDepartmentByTitleAndIsNotArchive($q);
			
			foreach ($employees as $e) {
				$id_pattern = Utilities::encrypt($e->getId()) . ":" . G_Overtime_Allowance::EMPLOYEE_TYPE;
				$response[] = array($id_pattern, $e->getFullname(), null);
			}

			foreach ($departments as $d) {
				$id_pattern = Utilities::encrypt($d->getId()) . ":" . G_Overtime_Allowance::DEPARTMENT_TYPE;
				$response[] = array($id_pattern, $d->getTitle(), null);
			}
		}
		
		if(count($response) == 0) {
			$response = '';
		}
		header('Content-type: application/json');
		echo json_encode($response);		
	}

	function ajax_get_employee_ot_allowance() 
	{
		$q = Model::safeSql(strtolower($_GET["search"]), false);
		
		if ($q != '') {
			$employees   = G_Employee_Finder::searchByFirstnameAndLastname($q);
			foreach ($employees as $e) {
				$id_pattern = Utilities::encrypt($e->getId()) . ":" . G_Overtime_Allowance::EMPLOYEE_TYPE;
				$response[] = array($id_pattern, $e->getFullname(), null);
			}

		}
		
		if(count($response) == 0) {
			$response = '';
		}
		header('Content-type: application/json');
		echo json_encode($response);		
	}

	function ajax_get_department_ot_allowance() 
	{
		$q = Model::safeSql(strtolower($_GET["search"]), false);
		
		if ($q != '') {			
			$departments = G_Company_Structure_Finder::searchAllDepartmentByTitleAndIsNotArchive($q);

			foreach ($departments as $d) {
				$id_pattern = Utilities::encrypt($d->getId()) . ":" . G_Overtime_Allowance::DEPARTMENT_TYPE;
				$response[] = array($id_pattern, $d->getTitle(), null);
			}
		}
		
		if(count($response) == 0) {
			$response = '';
		}
		header('Content-type: application/json');
		echo json_encode($response);		
	}

	function ajax_get_employment_status_ot_allowance() 
	{
		$q = Model::safeSql(strtolower($_GET["search"]), false);
		
		if ($q != '') {
			$employment_status = G_Settings_Employment_Status_Finder::searchAllEmploymentStatus($q);
			
			foreach ($employment_status as $es) {
				$id_pattern = Utilities::encrypt($es->getId()) . ":" . G_Overtime_Allowance::EMPLOYMENT_STATUS_TYPE;
				$response[] = array($id_pattern, $es->getStatus(), null);				
			}
		}
		
		if(count($response) == 0) {
			$response = '';
		}
		header('Content-type: application/json');
		echo json_encode($response);		
	}

}
?>