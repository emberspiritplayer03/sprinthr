<?php
class Overtime_Settings_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();

		$this->sprintHdrMenu(G_Sprint_Modules::HR, 'settings');
		$this->validatePermission(G_Sprint_Modules::HR,'settings','');

		Loader::appMainScript('overtime_settings.js');
		Loader::appMainScript('overtime_settings_base.js');		
		Loader::appStyle('style.css');		

		$this->eid                  = $this->global_user_eid;				
		$this->c_date  				= Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila');
		$this->company_structure_id = Utilities::decrypt($this->global_user_ecompany_structure_id);
		
		$this->var['settings'] = 'current';

		$this->validatePermission(G_Sprint_Modules::HR,'settings','');
	}
	
	function index()
	{	
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainBootStrapDropDown();

		$this->var['page_title'] 			= 'Settings';
		$this->var['overtime_settings_sb'] 	= 'selected';
		$this->var['module_title']			= 'Overtime Settings';
		$this->view->setTemplate('template_settings.php');
		$this->view->render('settings/overtime_settings/index.php',$this->var);
	}

	function load_overtime_allowance_list() {
		$this->view->render('settings/overtime_settings/_overtime_allowance_list_dt.php',$this->var);
	}

	function load_overtime_rate_list() {
		$this->view->render('settings/overtime_settings/_overtime_rate_list_dt.php',$this->var);
	}

	function _load_add_ot_allowance_form() {
		$ot = new G_Overtime_Allowance();
		$day_types = $ot->validAppliedDayType();

		$this->var['day_types']     = $day_types;
		$this->var['token']         = Utilities::createFormToken(); 		
		$this->view->render('settings/overtime_settings/forms/add_ot_allowance_form.php',$this->var);
	}

	function _load_add_ot_rate_form() {
		$ot = new G_Overtime_Allowance();
		$day_types = $ot->validAppliedDayType();

		$this->var['day_types']     = $day_types;
		$this->var['token']         = Utilities::createFormToken(); 		
		$this->view->render('settings/overtime_settings/forms/add_ot_rate_form.php',$this->var);
	}

	function ajax_edit_ot_allowance() {	
		$oa = G_Overtime_Allowance_Finder::findById(Utilities::decrypt($_GET['eid']));
		if($oa) {
			$day_types = $oa->validAppliedDayType();
			$data_day_types = unserialize($oa->getAppliedDayType());

			$this->var['data_day_types'] = $data_day_types;
			$this->var['day_types'] = $day_types;
			$this->var['oa']        = $oa;
			$this->var['token'] = Utilities::createFormToken();
			$this->view->render('settings/overtime_settings/forms/edit_ot_allowance_form.php', $this->var);	
		}else{
			echo "<div class=\"alert alert-error\">Record not found</div>";
		}
	}

	function ajax_edit_ot_rate() {	
		$or = G_Employee_Overtime_Rate_Finder::findById(Utilities::decrypt($_GET['eid']));
		if($or) {			
			$e = G_Employee_Finder::findById($or->getEmployeeId());
			$this->var['employee_name'] = $e->getLastname() . " " . $e->getFirstname();
			$this->var['or']    = $or;
			$this->var['token'] = Utilities::createFormToken();
			$this->view->render('settings/overtime_settings/forms/edit_ot_rate_form.php', $this->var);	
		}else{
			echo "<div class=\"alert alert-error\">Record not found</div>";
		}
	}

	function save_ot_allowance() {
		$return['is_success'] = false;

		if(Utilities::isFormTokenValid($_POST['token'])) {	
			$data = $_POST;

			$a_obj_ids[] = $data['employee_id'];
			$a_obj_ids[] = $data['department_id'];
			$a_obj_ids[] = $data['employment_status_id'];
			$s_obj_ids   = implode(",", array_filter($a_obj_ids));
			$data['applied_to_ids'] = $s_obj_ids;

			$oa = new G_Overtime_Allowance();
			//$return = $oa->createValidDayTypeArray($data['day_type'])->addOtAllowance($data);		
			$return = $oa->setDayType($data['day_type'])->addOtAllowance($data);
		}else{
			$return['message'] = "<div style='margin-left:38px;' class='alert alert-error'>Invalid form token.</div>";
		}

		$return['token'] = Utilities::createFormToken();
		echo json_encode($return);
	}

	function save_ot_rate() {
		$return['is_success'] = false;

		if(Utilities::isFormTokenValid($_POST['token'])) {	
			$data = $_POST;

			$employee_ids = explode(",", $data['employee_id']);
			$counter = 0;
			foreach( $employee_ids as $d ){				
				$id = Utilities::decrypt($d);
				$or = new G_Employee_Overtime_Rate();
				$or->setEmployeeId($id);
				$or->setOtRate($data['ot_rate']);
				$or->save();
				$counter++;
			}
			$return['is_success'] = true;
			$return['message'] =  $counter . " record(s) saved";
		}else{
			$return['message'] = "<div style='margin-left:38px;' class='alert alert-error'>Invalid form token.</div>";
		}

		$return['token'] = Utilities::createFormToken();
		echo json_encode($return);
	}

	function update_ot_allowance() {
		$return['is_success'] = false;
		if(Utilities::isFormTokenValid($_POST['token'])) {	
			$data = $_POST;

			$oa = new G_Overtime_Allowance();
			//$return = $oa->createValidDayTypeArray($data['day_type'])->updateOtAllowance($data);
			$return = $oa->setDayType($data['day_type'])->updateOtAllowance($data);
		}else{
			$return['message'] = "<div style='margin-left:38px;' class='alert alert-error'>Invalid form token.</div>";
		}

		$return['token'] = Utilities::createFormToken();
		echo json_encode($return);
	}

	function update_ot_rate() {
		$return['is_success'] = false;
		$return['message'] = "<div style='margin-left:38px;' class='alert alert-error'>Cannot update record.</div>";
		if(Utilities::isFormTokenValid($_POST['token'])) {	
			$data = $_POST;
			$id = Utilities::decrypt($data['eid']);
			$or = G_Employee_Overtime_Rate_Finder::findById($id);
			if( $or ){
				$or->setOtRate($data['ot_rate']);
				$or->save();
				$return['message'] = "Record updated";
				$return['is_success'] = true;
			}
		}else{
			$return['message'] = "<div style='margin-left:38px;' class='alert alert-error'>Invalid form token.</div>";
		}

		$return['token'] = Utilities::createFormToken();
		echo json_encode($return);
	}

	function delete_ot_allowance() {
		$return['message'] = "No record found.";
		$oa = G_Overtime_Allowance_Finder::findById(Utilities::decrypt($_POST['eid']));
		if($oa) {
			$oa->delete();
			$return['message'] = "Record was successfully deleted.";
		}
		echo json_encode($return);
	}

	function delete_ot_rate() {
		$return['message'] = "No record found.";
		$or = G_Employee_Overtime_Rate_Finder::findById(Utilities::decrypt($_POST['eid']));
		if($or) {
			$or->delete();
			$return['message'] = "Record was successfully deleted.";
		}
		echo json_encode($return);
	}
	
	function _load_overtime_allowance_list_dt()
	{
		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_OVERTIME_ALLOWANCE);
		/*$dt->setSQL("
			SELECT oa.id, CONCAT('add ',oa.ot_allowance,' pesos') as ot_allowance, CONCAT('every ',oa.multiplier,' OT hours') as multiplier, 
				CONCAT(oa.max_ot_allowance,' pesos') as max_ot_allowance, DATE_FORMAT(oa.date_start,'%M %d, %Y') as date_start, oa.description
			FROM ". G_OVERTIME_ALLOWANCE ." oa 
		");		*/
		$dt->setSQL("
			SELECT oa.id, oa.ot_allowance, oa.multiplier, max_ot_allowance, DATE_FORMAT(oa.date_start,'%b. %d, %Y') as date_start, oa.description,
				CONCAT('Adds <b>',oa.ot_allowance,'</b> pesos for every <b>',oa.multiplier,'</b> OT hours with maximum of <b>',oa.max_ot_allowance,'</b> pesos per day - ', '[Applied to : <b>',oa.description_day_type ,'</b>]')as info
			FROM ". G_OVERTIME_ALLOWANCE ." oa 
		");
		$dt->setCountSQL("SELECT COUNT(oa.id) as c FROM ". G_OVERTIME_ALLOWANCE ." oa ");	
		

		//$dt->setCondition("elr.is_approved = ". Model::safeSql(G_Employee_Leave_Request::PENDING) ." AND elr.employee_id = ". Model::safeSql($user_id) ." AND elr.is_archive = ". Model::safeSql(G_Employee_Leave_Request::NO));
		$dt->setColumns('description,date_start,info,ot_allowance,multiplier,max_ot_allowance');	
		$dt->setPreDefineSearch(
			array(				
				"date_start" => "DATE_FORMAT(oa.date_start,'%b. %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'",
				"info" => "oa.description LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'"
			)
		); 	 
		$dt->setOrder('ASC');
		$dt->setSort(0);							
		$dt->setCustomColumn(
				array(		
						1 => '<div class=\"i_container\"><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></div>',
						2 => '<div class=\"btn-group pull-right\"><a class=\"btn dropdown-toggle\" href=\"#\">Action <span class=\"caret\"></span></a><ul class=\"dropdown-menu\"><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"btn-edit-ot-allowance\" ><i class=\"icon-pencil\"></i> Edit </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"btn-delete-ot-allowance\" ><i class=\"icon-trash\"></i> Delete </a></li></ul></div>'
		));
		//echo "<pre>"; print_r($dt);
		echo $dt->constructDataTableRightTools();
	}

	function _load_overtime_rate_list_dt()
	{
		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_OVERTIME_RATES);		
		$dt->setSQL("
			SELECT eor.id, eor.ot_rate, CONCAT(e.lastname, ' ', e.firstname)AS employee_name				
			FROM ". G_EMPLOYEE_OVERTIME_RATES ." eor 
				LEFT JOIN " . G_EMPLOYEE . " e ON eor.employee_id = e.id 
		");
		$dt->setCountSQL("SELECT COUNT(eor.id) as c FROM ". G_EMPLOYEE_OVERTIME_RATES ." eor LEFT JOIN ON " . G_EMPLOYEE . " e ON eor.employee_id = e.id");		
		$dt->setColumns('employee_name, ot_rate');	
		$dt->setPreDefineSearch(
		   array(
		   	"employee_name" => "e.lastname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' OR e.firstname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'",
			"ot_rate" => "eor.ot_rate = " . Model::safeSql(addslashes($_REQUEST['sSearch']))
		   )
		); 	 
		$dt->setOrder('ASC');
		$dt->setSort(0);							
		$dt->setCustomColumn(
			array(		
				1 => '<div class=\"i_container\"><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></div>',
				2 => '<div class=\"btn-group pull-right\"><a class=\"btn dropdown-toggle\" href=\"#\">Action <span class=\"caret\"></span></a><ul class=\"dropdown-menu\"><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"btn-edit-ot-rate\" ><i class=\"icon-pencil\"></i> Edit </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"btn-delete-ot-rate\" ><i class=\"icon-trash\"></i> Delete </a></li></ul></div>'
		));
		//echo "<pre>"; print_r($dt);
		echo $dt->constructDataTableRightTools();
	}
}
?>